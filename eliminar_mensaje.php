<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'estudiante') {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id_mensaje'])) {
    $id_mensaje = $_GET['id_mensaje'];

    // Eliminar el mensaje
    $sql_eliminar = "DELETE FROM Mensajes WHERE id_mensaje = ?";
    $params = array($id_mensaje);
    $stmt_eliminar = sqlsrv_query($conn, $sql_eliminar, $params);

    if ($stmt_eliminar) {
        header("Location: mensajes.php");
        exit();
    } else {
        echo "Error al eliminar el mensaje.";
    }
} else {
    header("Location: mensajes.php");
    exit();
}
?>
