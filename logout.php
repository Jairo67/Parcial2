<?php
session_start();

// Destruir los datos de la sesión
session_destroy();

// Redirigir al usuario a la página de inicio de sesión con un mensaje opcional
header("Location: index.php?mensaje=logout");
exit();
?>
