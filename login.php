<?php
session_start();

// Usuarios y contraseñas (esto debe almacenarse de forma segura en un entorno real)
$usuarios = [
    'usuario1' => 'contrasena1',
    'usuario2' => 'contrasena2',
    'usuario3' => 'contrasena3',
];

// Función para verificar las credenciales del usuario
function verificarCredenciales($usuario, $contrasena) {
    global $usuarios;
    return isset($usuarios[$usuario]) && $usuarios[$usuario] === $contrasena;
}

// Comprobar si el formulario se ha enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $contrasena = $_POST["contrasena"];

    // Verificar credenciales
    if (verificarCredenciales($usuario, $contrasena)) {
        // Iniciar sesión y redirigir a la página principal
        $_SESSION["usuario"] = $usuario;
        header("Location: c.php");
        exit();
    } else {
        $mensajeError = "Credenciales incorrectas. Por favor, intenta nuevamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text" href="">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        form {
            text-align: center;
        }

        .error {
            color: red;
        }
    </style>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: relative;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('img/arrecife.jpg') center/cover no-repeat; /* Ajusta la ruta de la imagen */
            filter: blur(3px); /* Ajusta el valor de desenfoque según sea necesario */
            z-index: -1; /* Coloca el pseudo-elemento detrás del contenido del cuerpo */
        }

        form {
            text-align: center;
            background-color: rgba(255, 255, 255, 0.8); /* Fondo semi-transparente para mejorar la legibilidad */
            padding: 10px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1; /* Asegura que el formulario esté encima del fondo desenfocado */
        }

        h2 {
            color: #333333;
        }

        label {
            display: block;
            margin: 15px 0 3px;
            color: #555555;
        }

        input {
            width: 90%;
            padding: 6px;
            margin-bottom: 10px;
            box-sizing: border-box;
            border: 2px solid #ccc;
            border-radius: 3px;
        }

        button {
            background-color: #4caf50;
            color: #ffffff;
            padding: 10px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <h2>Login</h2>
        <?php if (isset($mensajeError)): ?>
            <p class="error"><?php echo $mensajeError; ?></p>
        <?php endif; ?>
        <label for="usuario">Usuario:</label>
        <input type="text" name="usuario" required>
        <br>
        <label for="contrasena">Contraseña:</label>
        <input type="password" name="contrasena" required>
        <br>
        <button type="submit">Iniciar Sesión</button>
    </form>
</body>
</html>
