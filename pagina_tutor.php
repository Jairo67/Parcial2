<?php 
session_start();
include 'conexion.php';

// Verificar si el usuario es un tutor y está autenticado
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'tutor') {
    header("Location: index.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Obtener las preguntas pendientes para este tutor
$sql = "SELECT p.id_pregunta, p.pregunta, p.fecha_pregunta, u.nombre AS alumno_nombre 
        FROM Preguntas p
        JOIN Usuarios u ON p.id_usuario = u.id_usuario
        WHERE p.id_tutor = ? AND p.id_pregunta NOT IN (
            SELECT id_pregunta FROM Respuestas WHERE id_tutor = ?
        )";
$params = array($id_usuario, $id_usuario);

$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true)); // Esto te ayudará a depurar los errores
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preguntas Pendientes - Tutor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f9;
            color: #333;
        }
        .navbar {
            background-color: #007bff;
        }
        .navbar a {
            color: white;
        }
        .navbar .navbar-brand {
            color: white;
        }
        .container {
            margin-top: 50px;
        }
        .table {
            background-color: white;
            border-radius: 8px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
            width: 100%;
            border-radius: 8px;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<!-- Barra de menú -->
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <a class="navbar-brand" href="pagina_tutor.php">Plataforma Tutor Virtual</a>
        <div class="navbar-nav">
            <a class="nav-item nav-link" href="historial_tutor.php">Historial de Preguntas Contestadas</a>
            <a class="nav-item nav-link" href="logout.php">Cerrar sesión</a>
        </div>
    </div>
</nav>

<!-- Contenido de la página -->
<div class="container">
    <h2 class="text-center mb-4">Preguntas Pendientes para Responder</h2>

    <?php if (sqlsrv_has_rows($stmt)): ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID Pregunta</th>
                    <th>Pregunta</th>
                    <th>Alumno</th>
                    <th>Fecha</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo $row['id_pregunta']; ?></td>
                        <td><?php echo $row['pregunta']; ?></td>
                        <td><?php echo $row['alumno_nombre']; ?></td>
                        <td><?php echo $row['fecha_pregunta']->format('Y-m-d H:i:s'); ?></td>
                        <td><a href="chat.php?id_pregunta=<?php echo $row['id_pregunta']; ?>" class="btn btn-custom">Ir al Chat</a></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-center">No tienes preguntas pendientes para responder.</p>
    <?php endif; ?>
</div>

<!-- Scripts de Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>