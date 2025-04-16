<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plataforma Tutor Virtual</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #fff;
            text-align: center;
            padding: 20px;
        }
        .container {
            max-width: 400px;
            margin: auto;
            background-color: #1e1e1e;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: none;
            border-radius: 5px;
        }
        input {
            background-color: #333;
            color: #fff;
        }
        button {
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }
        a {
            color: #00c3ff;
        }
    </style>
</head>
<body>
    <h2>Bienvenido a la Plataforma</h2>

    <?php
    // Mostrar mensaje de cierre de sesión si se ha enviado por URL
    if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'logout') {
        echo "<p>Has cerrado sesión correctamente.</p>";
    }
    ?>

    <div class="container">
        <h3>Iniciar Sesión</h3>
        <form action="procesar_login.php" method="post">
            <label>Correo:</label>
            <input type="email" name="correo" required>

            <label>Contraseña:</label>
            <input type="password" name="contrasena" required>

            <button type="submit">Iniciar Sesión</button>
        </form>

        <p>¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a></p>
    </div>
</body>
</html>