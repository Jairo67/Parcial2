<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <!-- Vinculamos el CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #000000;
            color: #fff;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 500px;
            margin-top: 50px;
            background-color: #333;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #007bff;
        }
        .form-control {
            border-radius: 10px;
            margin-bottom: 15px;
        }
        .btn-custom {
            background-color: #007bff;
            color: #fff;
            border-radius: 10px;
            width: 100%;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .text-center {
            text-align: center;
        }
        .link-custom {
            color: #007bff;
        }
        .link-custom:hover {
            text-decoration: none;
            color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Registro de Usuario</h2>

        <form action="procesar_registro.php" method="post">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" id="nombre" required>
            </div>

            <div class="mb-3">
                <label for="correo" class="form-label">Correo</label>
                <input type="email" class="form-control" name="correo" id="correo" required>
            </div>

            <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña</label>
                <input type="password" class="form-control" name="contrasena" id="contrasena" required>
            </div>

            <div class="mb-3">
                <label for="rol" class="form-label">Rol</label>
                <select name="rol" class="form-select" id="rol" required>
                    <option value="estudiante">Estudiante</option>
                    <option value="tutor">Tutor</option>
                </select>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-custom">Registrarse</button>
            </div>
        </form>

        <p class="text-center mt-3">¿Ya tienes cuenta? <a href="index.php" class="link-custom">Inicia sesión aquí</a></p>
    </div>

    <!-- Vinculamos los JS de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
