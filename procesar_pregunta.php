<?php
session_start();
include 'conexion.php';

// Verificar si el usuario es un estudiante y está autenticado
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'estudiante') {
    header("Location: index.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$pregunta = trim($_POST['pregunta']); // Limpiar espacios en la pregunta
$tipo_tutor = $_POST['tipo_tutor'];
$tutor_id = null;

// Validar que la pregunta no esté vacía
if (empty($pregunta)) {
    die("❌ La pregunta no puede estar vacía.");
}

// Si el tipo de tutor es humano, verificar y asignar el tutor
if ($tipo_tutor === 'humano') {
    if (isset($_POST['tutor']) && !empty($_POST['tutor'])) {
        $tutor_id = $_POST['tutor'];
    } else {
        die("❌ Debes seleccionar un tutor humano.");
    }
}

// Inserta la pregunta en la base de datos
$sql = "INSERT INTO Preguntas (id_usuario, pregunta, tipo_tutor, id_tutor) VALUES (?, ?, ?, ?)";
$params = array($id_usuario, $pregunta, $tipo_tutor, $tutor_id);

$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die("❌ Error al insertar la pregunta: " . print_r(sqlsrv_errors(), true));
}

// Redirigir al alumno a su página principal con mensaje de éxito
header("Location: pagina_alumno.php?mensaje=exito");
exit();
?>