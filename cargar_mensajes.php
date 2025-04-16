<?php
session_start();
include 'conexion.php';

// Validar que se reciba el ID de la pregunta
if (!isset($_GET['id_pregunta']) || empty($_GET['id_pregunta'])) {
    die("❌ Error: ID de pregunta no proporcionado.");
}

$id_pregunta = $_GET['id_pregunta'];

// Obtener los mensajes de la pregunta específica
$sql = "SELECT m.mensaje, m.fecha, u.nombre AS remitente
        FROM Mensajes m
        JOIN Usuarios u ON m.id_remitente = u.id_usuario
        WHERE m.id_pregunta = ?
        ORDER BY m.fecha ASC";
$params = array($id_pregunta);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die("<p class='text-danger'>❌ Error al cargar mensajes: " . print_r(sqlsrv_errors(), true) . "</p>");
}

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    echo "<div class='message'>";
    echo "<strong>" . htmlspecialchars($row['remitente']) . ":</strong> ";
    echo htmlspecialchars($row['mensaje']);
    echo "<br><small>" . $row['fecha']->format('Y-m-d H:i:s') . "</small>";
    echo "</div>";
}
?>