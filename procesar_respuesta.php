<?php
session_start();
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_pregunta']) && isset($_POST['respuesta'])) {
    $id_pregunta = $_POST['id_pregunta'];
    $respuesta = $_POST['respuesta'];

    $sql = "UPDATE Preguntas SET respuesta = ?, fecha_respuesta = GETDATE() WHERE id_pregunta = ?";
    $params = array($respuesta, $id_pregunta);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt) {
        header("Location: pagina_tutor.php");
    } else {
        echo "Error al responder la pregunta.";
    }
} else {
    echo "Solicitud no válida.";
}
?>
