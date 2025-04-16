<?php
session_start();

// Verificar si el usuario está logueado como tutor
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 'tutor') {
    header("Location: index.php"); 
    exit();
}

// Incluir la conexión
include 'conexion.php';

// Obtener el nombre del tutor
$tutor_nombre = $_SESSION['nombre'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Preguntas Contestadas</title>
    <!-- Vinculamos el CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .navbar {
            margin-bottom: 20px;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<!-- Barra de navegación -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="pagina_tutor.php">Panel de Tutor</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="historial_tutor.php">Historial de preguntas contestadas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Cerrar sesión</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Contenido de la página -->
<div class="container">
    <h2>Historial de Preguntas Contestadas</h2>

    <?php
    // Consultar las preguntas y respuestas contestadas por el tutor
    $sql_historial = "SELECT p.pregunta, r.respuesta, p.fecha AS fecha_pregunta, r.fecha AS fecha_respuesta, u.nombre AS estudiante_nombre
                      FROM Preguntas p
                      JOIN Usuarios u ON p.id_usuario = u.id_usuario
                      JOIN Respuestas r ON p.id_pregunta = r.id_pregunta
                      WHERE r.id_respuesta IS NOT NULL
                      ORDER BY r.fecha DESC"; // Filtra solo las preguntas que han sido respondidas y las ordena por fecha de respuesta

    $stmt_historial = sqlsrv_query($conn, $sql_historial);

    if ($stmt_historial && sqlsrv_has_rows($stmt_historial)) {
        while ($registro = sqlsrv_fetch_array($stmt_historial, SQLSRV_FETCH_ASSOC)) {
            echo '<div class="mb-4">';
            echo '<strong>Estudiante:</strong> ' . $registro['estudiante_nombre'] . '<br>';
            echo '<strong>Pregunta:</strong> ' . $registro['pregunta'] . '<br>';
            echo '<strong>Respuesta:</strong> ' . $registro['respuesta'] . '<br>';
            echo '<strong>Fecha de la pregunta:</strong> ' . $registro['fecha_pregunta']->format('Y-m-d H:i:s') . '<br>';
            echo '<strong>Fecha de respuesta:</strong> ' . $registro['fecha_respuesta']->format('Y-m-d H:i:s') . '<br>';
            echo '</div>';
        }
    } else {
        echo '<p>No has respondido ninguna pregunta aún.</p>';
    }
    ?>
</div>

<!-- Vinculamos los JS de Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
