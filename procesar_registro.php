<?php
include 'conexion.php'; // Conexión a SQL Server

// Capturar los datos del formulario
$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT); // Encriptar contraseña
$rol = $_POST['rol'];

// Verificar si el correo ya está registrado
$sql_check = "SELECT * FROM Usuarios WHERE correo = ?";
$stmt_check = sqlsrv_query($conn, $sql_check, array($correo));

if (sqlsrv_has_rows($stmt_check)) {
    echo "❌ El correo ya está registrado. Por favor, usa otro.";
    exit; // Detener la ejecución si el correo ya está registrado
}

// Insertar el nuevo usuario en la tabla Usuarios
$sql_insert_user = "INSERT INTO Usuarios (nombre, correo, contraseña, rol) OUTPUT INSERTED.id_usuario VALUES (?, ?, ?, ?)";
$params_user = array($nombre, $correo, $contrasena, $rol);
$stmt_user = sqlsrv_query($conn, $sql_insert_user, $params_user);

// Verificar si se insertó correctamente
if ($stmt_user) {
    // Obtener el ID del nuevo usuario
    $id_usuario = sqlsrv_fetch_array($stmt_user, SQLSRV_FETCH_ASSOC)['id_usuario'];

    // Si el rol es estudiante, insertar en la tabla Estudiantes
    if ($rol == 'estudiante') {
        $sql_student = "INSERT INTO Estudiantes (id_estudiante, id_usuario) VALUES (?, ?)";
        $params_student = array($id_usuario, $id_usuario);
        sqlsrv_query($conn, $sql_student);
    }

    // Si el rol es tutor, insertar en la tabla Tutores
    elseif ($rol == 'tutor') {
        $sql_tutor = "INSERT INTO Tutores (id_tutor, id_usuario) VALUES (?, ?)";
        $params_tutor = array($id_usuario, $id_usuario);
        sqlsrv_query($conn, $sql_tutor);
    }

    // Redirigir al usuario al login después de un registro exitoso
    header("Location: index.php");
    exit;
} else {
    echo "❌ Error al registrar:<br>";
    die(print_r(sqlsrv_errors(), true));
}
?>