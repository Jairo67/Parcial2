<?php
session_start();
include 'conexion.php';

// Verifica si el usuario ha iniciado sesión y tiene el rol de 'estudiante'
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'estudiante') {
    header("Location: index.php");
    exit();
}

// Obtener el id del usuario que está logueado
$id_usuario = $_SESSION['id_usuario'];

// Consultar el historial de preguntas del alumno
$sql_historial = "SELECT * FROM Preguntas WHERE id_usuario = ?";
$params = array($id_usuario);

// Ejecutar la consulta SQL
$stmt_historial = sqlsrv_query($conn, $sql_historial, $params);

// Verificar si la consulta fue exitosa
if ($stmt_historial === false) {
    die(print_r(sqlsrv_errors(), true));  // Muestra el error si la consulta falla
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Preguntas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Historial de Preguntas</h2>

        <?php
        // Verifica si el usuario tiene preguntas en el historial
        if (sqlsrv_has_rows($stmt_historial)) {
            // Muestra las preguntas
            while ($row = sqlsrv_fetch_array($stmt_historial, SQLSRV_FETCH_ASSOC)) {
                echo "<div class='card mb-3'>";
                echo "<div class='card-body'>";
                echo "<h5 class='card-title'>" . $row['pregunta'] . "</h5>";
                echo "<p class='card-text'>Fecha: " . $row['fecha']->format('Y-m-d H:i:s') . "</p>";
                echo "</div></div>";
            }
        } else {
            // Si no hay preguntas, muestra un mensaje
            echo "<p>No has hecho preguntas aún.</p>";
        }
        ?>
    </div>
</body>
</html>
