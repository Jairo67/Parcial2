<?php
session_start();
include 'conexion.php';

// Verificar si el usuario es un estudiante y está autenticado
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'estudiante') {
    header("Location: index.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario']; // ID del estudiante autenticado

// Obtener los mensajes respondidos por tutores
$sql_respuestas = "SELECT m.mensaje, m.fecha, p.pregunta, t.nombre AS tutor_nombre 
                   FROM Mensajes m
                   JOIN Preguntas p ON m.id_pregunta = p.id_pregunta
                   JOIN Usuarios t ON m.id_remitente = t.id_usuario
                   WHERE p.id_usuario = ? AND t.rol = 'tutor'
                   ORDER BY m.fecha DESC";
$params_respuestas = array($id_usuario);
$stmt_respuestas = sqlsrv_query($conn, $sql_respuestas, $params_respuestas);

if ($stmt_respuestas === false) {
    die(print_r(sqlsrv_errors(), true)); // Depuración de errores
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensajes Respondidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            color: white;
        }
        .container {
            margin-top: 50px;
        }
        .list-group-item {
            background-color: #212529;
            color: white;
        }
        .list-group-item strong {
            color: #007bff;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-primary">
    <div class="container">
        <a class="navbar-brand text-white" href="pagina_alumno.php">Plataforma Tutor</a>
        <div class="navbar-nav">
            <a class="nav-item nav-link text-white" href="mensajes.php">Mensajes</a>
            <a class="nav-item nav-link text-white" href="historial.php">Historial de Preguntas</a>
            <a class="nav-item nav-link text-white" href="logout.php">Cerrar sesión</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h2 class="text-center">Mensajes Respondidos por los Tutores</h2>

    <?php if (sqlsrv_has_rows($stmt_respuestas)): ?>
        <ul class="list-group">
            <?php while ($row = sqlsrv_fetch_array($stmt_respuestas, SQLSRV_FETCH_ASSOC)): ?>
                <li class="list-group-item">
                    <strong>Tutor:</strong> <?php echo htmlspecialchars($row['tutor_nombre']); ?><br>
                    <strong>Pregunta:</strong> <?php echo htmlspecialchars($row['pregunta']); ?><br>
                    <strong>Mensaje:</strong> <?php echo htmlspecialchars($row['mensaje']); ?><br>
                    <small><strong>Fecha:</strong> <?php echo $row['fecha']->format('Y-m-d H:i:s'); ?></small>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p class="text-center">No hay mensajes respondidos por tutores aún. ¡Haz tus preguntas para recibir ayuda!</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>