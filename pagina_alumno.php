<?php
session_start();
include 'conexion.php';

// Verificar si el usuario es un estudiante y está autenticado
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'estudiante') {
    header("Location: index.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página del Alumno</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #121212; color: white; }
        .container { margin-top: 50px; }
        .navbar { background-color: #007bff; }
        .navbar a { color: white; }
        .list-group-item { background-color: #212529; color: white; }
    </style>
</head>
<body>

<!-- Barra de menú -->
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <a class="navbar-brand" href="pagina_alumno.php">Plataforma Tutor</a>
        <div class="navbar-nav">
            <a class="nav-item nav-link" href="mensajes.php">Mensajes</a>
            <a class="nav-item nav-link" href="historial.php">Historial de Preguntas</a>
            <a class="nav-item nav-link" href="logout.php">Cerrar sesión</a>
        </div>
    </div>
</nav>

<!-- Formulario para hacer una pregunta -->
<div class="container">
    <h2 class="text-center">Hacer una Pregunta</h2>
    <form id="form-pregunta">
        <div class="mb-3">
            <label for="pregunta" class="form-label">Tu Pregunta</label>
            <textarea name="pregunta" id="pregunta" class="form-control" rows="3" required></textarea>
        </div>

        <!-- Seleccionar tipo de tutor (IA o humano) -->
        <div class="mb-3">
            <label for="tipo_tutor" class="form-label">Selecciona el tipo de tutor</label>
            <select name="tipo_tutor" id="tipo_tutor" class="form-select" required>
                <option value="ia">Tutor IA</option>
                <option value="humano">Tutor Humano</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Enviar Pregunta</button>
    </form>

    <!-- Espacio para mostrar la respuesta de IA -->
    <div id="respuesta_ia" class="mt-3 p-3 bg-dark text-white"></div>
</div>

<script>
document.getElementById("form-pregunta").addEventListener("submit", function(event) {
    event.preventDefault();

    const pregunta = document.getElementById("pregunta").value.trim();
    const tipoTutor = document.getElementById("tipo_tutor").value;
    const respuestaDiv = document.getElementById("respuesta_ia");

    if (!pregunta) {
        alert("❌ Debes escribir una pregunta.");
        return;
    }

    respuestaDiv.innerHTML = "<em>Cargando respuesta...</em>"; // Indicar que la IA está generando la respuesta

    fetch("procesar_consulta.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({ pregunta: pregunta, tipo_tutor: tipoTutor })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            respuestaDiv.innerHTML = `<strong>Error:</strong> ${data.error}`;
        } else {
            respuestaDiv.innerHTML = `<strong>Respuesta IA:</strong> ${data.respuesta}`;
        }
    })
    .catch(error => {
        respuestaDiv.innerHTML = `<strong>Error:</strong> No se pudo obtener una respuesta.`;
        console.error("Error:", error);
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>