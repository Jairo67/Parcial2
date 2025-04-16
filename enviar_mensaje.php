<?php
session_start();
include 'conexion.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(["error" => "❌ Usuario no autenticado."]);
    exit();
}

$id_remitente = $_SESSION['id_usuario'];
$id_destinatario = isset($_POST['id_destinatario']) ? $_POST['id_destinatario'] : null;
$id_pregunta = isset($_POST['id_pregunta']) ? $_POST['id_pregunta'] : null;
$contenido = isset($_POST['mensaje']) ? trim($_POST['mensaje']) : "";

// Validar que los datos no estén vacíos
if (empty($id_pregunta) || empty($id_remitente) || empty($id_destinatario) || empty($contenido)) {
    echo json_encode(["error" => "❌ Los datos no son válidos. Por favor verifica los campos."]);
    exit();
}

// Aplicar seguridad al mensaje
$contenido = htmlspecialchars($contenido, ENT_QUOTES, 'UTF-8');

// Intento de inserción
$sql = "INSERT INTO Mensajes (id_pregunta, id_remitente, id_destinatario, mensaje) VALUES (?, ?, ?, ?)";
$params = array($id_pregunta, $id_remitente, $id_destinatario, $contenido);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    echo json_encode(["error" => "❌ Error al enviar el mensaje.", "sql_error" => print_r(sqlsrv_errors(), true)]);
    exit();
}

echo json_encode(["success" => "✅ Mensaje enviado correctamente."]);
?>