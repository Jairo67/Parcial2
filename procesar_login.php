<?php
// Iniciar la sesión al comienzo
session_start();

// Incluir la conexión
include 'conexion.php'; // Asegúrate de que la conexión esté configurada para la base de datos PlataformaTutorVirtual

// Verificar si se enviaron los datos del formulario
if (isset($_POST['correo']) && isset($_POST['contrasena'])) {
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    // Buscar el usuario por correo
    $sql = "SELECT id_usuario, nombre, contraseña, rol FROM Usuarios WHERE correo = ?";
    $params = array($correo);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt && sqlsrv_has_rows($stmt)) {
        $usuario = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

        // Verificar la contraseña
        if (password_verify($contrasena, $usuario['contraseña'])) {
            // Guardar los datos del usuario en la sesión
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['rol'] = $usuario['rol'];

            // Redirigir al panel según el rol
            if ($usuario['rol'] == 'tutor') {
                header("Location: pagina_tutor.php");
                exit();
            } elseif ($usuario['rol'] == 'estudiante') {
                header("Location: pagina_alumno.php");
                exit();
            } else {
                echo "❌ Rol no válido. Por favor contacta al administrador.";
            }
        } else {
            // Contraseña incorrecta
            echo "❌ Contraseña incorrecta. Intenta nuevamente.";
        }
    } else {
        // Usuario no encontrado
        echo "❌ Usuario no encontrado. Por favor verifica tu correo.";
    }
} else {
    // Si no se enviaron los datos
    echo "❌ Todos los campos son obligatorios.";
}
?>