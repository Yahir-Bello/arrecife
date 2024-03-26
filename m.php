<?php
$filename = 'info_habitaciones_' . date('Ymd') . '.json';

$numeroHabitacionBuscada = '';
$infoHabitacion = array();


if (file_exists($filename)) {
    $habitaciones = json_decode(file_get_contents($filename), true);
} else {
    // Inicializar $habitaciones si el archivo no existe
    $habitaciones = array();
}

// Ruta del archivo de reservas
$reservasFile = 'reservas_' . date('Ymd') . '.txt';

//    $datosReserva = "Número de Habitación: $numeroHabitacionBuscada\n";
//    $datosReserva .= "Nombre: " . $infoHabitacion[0] . "\n";
//    $datosReserva .= "Adultos: " . $infoHabitacion[1] . "\n";
//    $datosReserva .= "Niños: " . $infoHabitacion[2] . "\n";
//    $datosReserva .= "Fecha de reserva: " . date('Y-m-d H:i:s') . "\n\n";








$datosReserva = "Número de Habitación: " . (isset($numeroHabitacionBuscada) ? $numeroHabitacionBuscada : 'No disponible') . "\n";

if (isset($infoHabitacion) && is_array($infoHabitacion) && count($infoHabitacion) >= 3) {
    $datosReserva .= "Nombre: " . (isset($infoHabitacion[1]) ? $infoHabitacion[1] : 'No disponible') . "\n";
    $datosReserva .= "Adultos: " . (isset($infoHabitacion[2]) ? $infoHabitacion[2] : 'No disponible') . "\n";
    $datosReserva .= "Niños: " . (isset($infoHabitacion[3]) ? $infoHabitacion[3] : 'No disponible') . "\n";
} else {
    // Manejar el caso donde la información de la habitación no es válida
    $datosReserva .= "Información de la habitación no disponible\n";
}

$datosReserva .= "Fecha de reserva: " . date('Y-m-d H:i:s') . "\n\n";











// Intentar guardar los datos en el archivo
if (file_put_contents($reservasFile, $datosReserva, FILE_APPEND)) {
        //echo 'Datos de reserva guardados correctamente.'; 
        } else {
            echo 'Error al intentar guardar los datos de reserva.';
        }

// Variables para el mensaje de reserva exitosa
$mostrarMensajeReserva = false;
$mensajeReserva = '';

// Variables para controlar la visibilidad y posición de la tarjeta de información
$mostrarInfoHabitacion = false;
$infoHabitacion = array(); // Almacenar la información de la habitación actual
$posicionInfoHabitacion = '';

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se ha realizado una búsqueda o una reserva
    if (isset($_POST["buscarHabitacionBtn"])) {
        // Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se ha realizado una búsqueda o una reserva
    if (isset($_POST["buscarHabitacionBtn"])) {
        // Verificar si se ingresó el número de habitación
        if (isset($_POST["numeroHabitacion"])) {
            $numeroHabitacionBuscada = $_POST["numeroHabitacion"];

            // Buscar la información de la habitación en el array
            if (isset($habitaciones[$numeroHabitacionBuscada])) {
                $infoHabitacion = $habitaciones[$numeroHabitacionBuscada];

                // Configurar variables para mostrar la tarjeta de información
                $mostrarInfoHabitacion = true;
                $posicionInfoHabitacion = 'center';

                // Formulario para realizar la reserva
                $estadoMesaValue = isset($infoHabitacion[4]) ? $infoHabitacion[4] : '';
                $isCheckin = $estadoMesaValue == 'CHECKIN';

                // Si se hizo clic en el botón de reserva, mostrar el mensaje de reserva exitosa
                if (isset($_POST["reservarBtn"])) {
                    // Verificar si la habitación está disponible para realizar la reserva
                    if ($isCheckin) {
                        $mostrarMensajeReserva = true;
                        $mensajeReserva = 'Reserva exitosa. Se ha guardado la información.';
                        $mostrarInfoHabitacion = false; // Ocultar la tarjeta de información

                        // Guardar reserva en el archivo
                        $reservaData = "Mesa: $numeroHabitacionBuscada, Nombre: " . $infoHabitacion[0] . ", Adultos: " . $infoHabitacion[1] . ", Niños: " . $infoHabitacion[2] . "\n";
                        file_put_contents($reservasFile, $reservaData, FILE_APPEND);
                    } else {
                        // La habitación no está disponible
                        echo '<p>Habitación no disponible para reservar.</p>';
                    }
                }
            } else {
                echo '<p>Habitación no encontrada o desocupada.</p>';
            }
        }
    }
}

// Coloca la lógica para guardar en el archivo al final, después de procesar la reserva
if ($mostrarMensajeReserva && isset($_POST["reservarBtn"])) {
    // Guardar reserva en el archivo
    $reservaData = "Mesa: $numeroHabitacionBuscada, Nombre: " . $infoHabitacion[0] . ", Adultos: " . $infoHabitacion[1] . ", Niños: " . $infoHabitacion[2] . "\n";
    file_put_contents($reservasFile, $reservaData, FILE_APPEND);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Mesas</title>
    <link rel="stylesheet" href="css/cards.css">
    <!-- ... (código anterior) ... -->
     <style>
        /* Estilos adicionales para desenfocar las demás tarjetas durante la búsqueda */
        .card {
                /* ... (otros estilos) ... */
                display: flex;
                flex-direction: column;
                justify-content: center;
            }

        .container-card {
            filter: blur(<?php echo $mostrarInfoHabitacion ? '5px' : '0'; ?>);
            transition: filter 0.0s ease-in-out;
        }

        /* Estilos para centrar la tarjeta de información */
        .card-info {
            display: <?php echo $mostrarInfoHabitacion ? 'block' : 'none'; ?>;
            text-align: <?php echo $posicionInfoHabitacion; ?>;
            position: center;
            top: 50%;
            left: 50%;
            box-shadow: 5px 5px 20px rgba(0, 0, 0, 0.4);
            z-index: 2; /* Asegura que la tarjeta de información esté por encima de las demás tarjetas */
        }
    </style>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .title-cards {
            text-align: center;
            margin-top: 10px;
        }

        .container-card {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin: 20px;
        }

        .card {
            width: 200px;
            margin: 10px;
            border-radius: 5px;
            overflow: hidden;
        }

        .contenido-card {
            padding: 15px;
            text-align: center;
        }

        .botones-horario {
            margin-bottom: 20px;
        }

        .ocupada {
            border: 1px solid grey;
        }

        #searchBar {
            margin: 10px;
            padding: 5px;
        }

        /* Estilo del mensaje de reserva exitosa */
        #mensajeReservaExitosa {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            display: none;
        }
    </style>
</head>
<body>
    <center>
        <div class="title-cards">
            <h2>Arrecife</h2>
        </div>
        <!-- Tarjeta de información de la habitación -->
        <?php
        if ($mostrarInfoHabitacion) {
            echo '<div class="card card-info">';
            echo '<div class="contenido-card">';
            echo '<h3>Información de la Habitacion ' . $numeroHabitacionBuscada . '</h3>';
            echo '<p>Adultos: ' . $infoHabitacion[1] . '</p>';
            echo '<p>Niños: ' . $infoHabitacion[2] . '</p>';

            // Formulario para realizar la reserva
            $estadoMesaValue = isset($infoHabitacion[4]) ? $infoHabitacion[4] : '';
            $isCheckin = $estadoMesaValue == 'CHECKIN';
            echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post" id="formMesa' . $numeroHabitacionBuscada . '">';
            echo '<input type="hidden" name="estadoMesa" value="' . $estadoMesaValue . '">';
            echo '<button type="submit" name="reservarBtn" ' . ($isCheckin ? 'disabled' : '') . '>Reservar</button>';
            echo '</form>';
            echo '</div>';
            echo '</div>';

        }
        ?>
        
         <!-- Mensaje de reserva exitosa -->
        <div id="mensajeReservaExitosa" style="display: none; background-color: #4CAF50; color: white; padding: 10px; margin-top: 10px; border-radius: 5px;">
            Reserva exitosa. Se ha guardado la información.
        </div>


        <div class="container-card">
            <?php
            // Ahora se mostrarán 21 mesas
            $totalMesas = 21;
            for ($numeroMesa = 1; $numeroMesa <= $totalMesas; $numeroMesa++) {
                echo '<div class="card ' . (isset($habitaciones[$numeroMesa]) && $habitaciones[$numeroMesa][4] == 'CHECKIN' ? 'ocupada' : 'disponible') . '">';
                echo '<div class="contenido-card">';
                echo '<h3>Mesa ' . $numeroMesa . '</h3>';

                $estadoHabitacion = '';

                // Verificar si la habitación está ocupada y mostrar la información
                if (isset($habitaciones[$numeroMesa])) {
                    $infoHabitacion = $habitaciones[$numeroMesa];
                    $estadoHabitacion = $infoHabitacion[4]; // Estado de la habitación (CHECKIN o CHECKOUT)

                    if ($estadoHabitacion == 'CHECKIN') {
                        // Mostrar la información de la habitación ocupada
                        echo '<p>Nombre: ' . $infoHabitacion[0] . '</p>';
                        echo '<p>Adultos: ' . $infoHabitacion[1] . '</p>';
                        echo '<p>Niños: ' . $infoHabitacion[2] . '</p>';
                    } 
                } else { 
                    // La habitación está disponible (CHECKIN)
                    echo '<p>Disponible</p>';
                }
                $isCheckin = $estadoHabitacion == 'CHECKIN';

                // Formulario de búsqueda y reserva para la habitación
                echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">';
                echo '<label for="numeroHabitacion">Número de Habitación:</label>';
                echo '<input type="text" name="numeroHabitacion" id="numeroHabitacion" required>';
                echo '<input type="hidden" name="mesaSeleccionada" value="' . $numeroMesa . '">';
                echo '<input type="submit" name="buscarHabitacionBtn" value="Buscar/Reservar">';
                echo '</form>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </center>

        <script>
            // Función para mostrar el mensaje de reserva exitosa
            function mostrarMensajeReservaExitosa() {
                var mensajeReserva = document.getElementById("mensajeReservaExitosa");
                mensajeReserva.style.display = "block";

                // Ocultar el mensaje después de 5 segundos (5000 milisegundos)
                setTimeout(function() {
                    mensajeReserva.style.display = "none";
                }, 5000);
            }

            // Obtener el valor del campo oculto que indica si se hizo clic en el botón de reserva
            var reservarBtnClicked = <?php echo isset($_POST["reservarBtn"]) ? 'true' : 'false'; ?>;

            // Llamar a la función si se hizo clic en el botón de reserva
            if (reservarBtnClicked) {
                mostrarMensajeReservaExitosa();
            }
        </script>
<?php

            if (isset($_POST["reservarBtn"])) {
                    $mostrarMensajeReserva = true;
                    $mensajeReserva = 'Reserva exitosa. Se ha guardado la información.';
                    $mostrarInfoHabitacion = false; // Ocultar la tarjeta de información
                }
            ?>

</body>
</html>
