<?php
session_start();
include 'conexion.php';

// Verificar si el usuario está autenticado y es tutor
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'tutor') {
    header("Location: index.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario']; // ID del tutor autenticado

// Validar que se haya recibido el ID de la pregunta
if (!isset($_GET['id_pregunta']) || empty($_GET['id_pregunta'])) {
    die("❌ No se proporcionó el ID de la pregunta.");
}

$id_pregunta = $_GET['id_pregunta'];

// Obtener los detalles de la pregunta y el estudiante
$sql = "SELECT p.pregunta, u.id_usuario AS id_estudiante, u.nombre AS estudiante_nombre 
        FROM Preguntas p
        JOIN Usuarios u ON p.id_usuario = u.id_usuario
        WHERE p.id_pregunta = ?";
$params = array($id_pregunta);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false || !sqlsrv_has_rows($stmt)) {
    die("❌ No se pudo obtener la información de la pregunta.");
}

$pregunta = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
$id_estudiante = $pregunta['id_estudiante']; // Guardamos el ID del estudiante
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat con Estudiante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f4f9; color: #333; }
        .chat-box { height: 400px; overflow-y: scroll; border: 1px solid #ddd; margin-bottom: 15px; padding: 10px; background-color: white; }
        .message { margin-bottom: 10px; }
        .message-tutor { background-color: #d1e7dd; padding: 5px; border-radius: 5px; }
        .message-estudiante { background-color: #f8d7da; padding: 5px; border-radius: 5px; }
        .input-message { width: 100%; }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2>Chat con Estudiante</h2>
    <h4>Pregunta: <?php echo htmlspecialchars($pregunta['pregunta']); ?></h4>
    <h5>Estudiante: <?php echo htmlspecialchars($pregunta['estudiante_nombre']); ?></h5>

    <!-- Caja de chat -->
    <div class="chat-box" id="chat-box">
        <!-- Los mensajes serán cargados aquí mediante AJAX -->
    </div>

    <!-- Enviar mensaje -->
    <textarea id="mensaje" class="form-control input-message" placeholder="Escribe tu respuesta..."></textarea>
    <button id="enviar" class="btn btn-primary mt-2">Enviar</button>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    const id_pregunta = <?php echo json_encode($id_pregunta); ?>;
    const tutor_id = <?php echo json_encode($id_usuario); ?>;
    const estudiante_id = <?php echo json_encode($id_estudiante); ?>;

    // Función para cargar los mensajes
    function cargarMensajes() {
        $.ajax({
            url: 'cargar_mensajes.php',
            type: 'GET',
            data: { id_pregunta: id_pregunta },
            success: function(response) {
                console.log("Mensajes cargados:", response); // Depuración
                $('#chat-box').html(response);
                $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight); // Scroll hasta el último mensaje
            }
        });
    }

    // Cargar los mensajes al cargar la página
    cargarMensajes();

    // Enviar mensaje
    $('#enviar').click(function() {
        const mensaje = $('#mensaje').val().trim();

        console.log("Datos enviados:", id_pregunta, tutor_id, estudiante_id, mensaje); // Depuración

        if (!mensaje) {
            alert("❌ El mensaje no puede estar vacío.");
            return;
        }

        $.ajax({
            url: 'enviar_mensaje.php',
            type: 'POST',
            data: {
                id_pregunta: id_pregunta,
                mensaje: mensaje,
                id_remitente: tutor_id,
                id_destinatario: estudiante_id
            },
            success: function(response) {
                console.log("Respuesta del servidor:", response); // Depuración
                $('#mensaje').val('');
                cargarMensajes();
            }
        });
    });

    // Recargar los mensajes cada 3 segundos
    setInterval(cargarMensajes, 3000);
});
</script>

</body>
</html>