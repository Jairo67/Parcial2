<?php  
session_start();
include 'conexion.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'estudiante') {
    header("Location: index.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Obtener el mensaje que se va a responder
if (isset($_GET['id_mensaje'])) {
    $id_mensaje = $_GET['id_mensaje'];
    $sql_mensaje = "SELECT * FROM Mensajes WHERE id_mensaje = ?";
    $params = array($id_mensaje);
    $stmt_mensaje = sqlsrv_query($conn, $sql_mensaje, $params);
    $mensaje = sqlsrv_fetch_array($stmt_mensaje, SQLSRV_FETCH_ASSOC);
} else {
    // Redirigir si no se pasa el id_mensaje
    header("Location: mensajes.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $respuesta = $_POST['respuesta'];
    
    // Guardar la respuesta
    $sql_respuesta = "INSERT INTO Respuestas (id_mensaje, id_usuario, respuesta, fecha) 
                      VALUES (?, ?, ?, GETDATE())";
    $params = array($id_mensaje, $id_usuario, $respuesta);
    $stmt_respuesta = sqlsrv_query($conn, $sql_respuesta, $params);

    if ($stmt_respuesta) {
        header("Location: mensajes.php");
        exit();
    } else {
        echo "Error al enviar la respuesta.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responder Mensaje</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Barra de menú -->
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <a class="navbar-brand" href="pagina_alumno.php">Plataforma Tutor</a>
        <div class="navbar-nav">
            <a class="nav-item nav-link" href="pagina_alumno.php">Hacer Pregunta</a>
            <a class="nav-item nav-link" href="historial.php">Historial de Preguntas</a>
            <a class="nav-item nav-link" href="logout.php">Cerrar sesión</a>
        </div>
    </div>
</nav>

<!-- Formulario de respuesta -->
<div class="container mt-4">
    <h2>Responder al Tutor</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="respuesta" class="form-label">Tu Respuesta</label>
            <textarea name="respuesta" id="respuesta" class="form-control" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Enviar Respuesta</button>
    </form>
</div>

</body>
</html>
