<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="" href="css/cargar.css">
    <title>Cargar Archivo</title>
</head>
<body>
    <h2>Cargar Estado de Mesas</h2>

    <?php
    // Verificamos si se envió un archivo de habitaciones
    if (isset($_FILES['archivoHabitaciones'])) {
        $archivoHabitaciones = $_FILES['archivoHabitaciones'];

        // Verificamos si no hay errores en la subida del archivo de habitaciones
        if ($archivoHabitaciones['error'] === UPLOAD_ERR_OK) {
            $nombreTemporalHabitaciones = $archivoHabitaciones['tmp_name'];

            // Leemos el contenido del archivo de habitaciones
            $contenidoArchivoHabitaciones = file_get_contents($nombreTemporalHabitaciones);

            // Divide el contenido en líneas
            $lineasHabitaciones = explode(PHP_EOL, $contenidoArchivoHabitaciones);

            // Inicializa el array de habitaciones
            $habitaciones = array();

            // Recorre cada línea y actualiza el array de habitaciones
            foreach ($lineasHabitaciones as $lineaHabitaciones) {
                // Divide la línea en columnas
                $columnasHabitaciones = explode("\t", $lineaHabitaciones);

                // Verifica si hay suficientes columnas
                if (count($columnasHabitaciones) >= 4) {
                    // Obtiene la información necesaria
                    $numeroHabitacion = $columnasHabitaciones[0];
                    $nombreCliente = $columnasHabitaciones[1];
                    $adultos = (int)$columnasHabitaciones[2];
                    $ninos = (int)$columnasHabitaciones[3];

                    // Agrega la información al array de habitaciones
                    $habitaciones[$numeroHabitacion] = array($nombreCliente, $adultos, $ninos);
                }
            }

            // Guarda el array de habitaciones en un archivo JSON con nombre único
            $nombreArchivoJSONHabitaciones = 'info_habitaciones_' . date('Ymd') . '.json';
            file_put_contents($nombreArchivoJSONHabitaciones, json_encode($habitaciones, JSON_PRETTY_PRINT));
            header('Location: m.php');

            echo 'Archivo de habitaciones cargado exitosamente<br>';
            
        } else {
            echo 'Error al subir el archivo de habitaciones.<br>';
        }
    }
    ?>

    <form action="c.php" method="post" enctype="multipart/form-data">
        <label for="archivoHabitaciones">Seleccionar Archivo de Habitaciones:</label>
        <input type="file" name="archivoHabitaciones" accept=".txt">
        <br>
        <button type="submit">Cargar Archivo</button>
    </form>
</body>
</html>