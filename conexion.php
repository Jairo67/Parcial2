<?php
$serverName = "JAIRO_07\\MSSQLSERVER2025"; // Doble barra \\

$connectionInfo = array(
    "Database" => "PlataformaTutorVirtual",
    "CharacterSet" => "UTF-8"
);

// El tercer parámetro en sqlsrv_connect es false para indicar autenticación de Windows
$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn) {
    echo "";
} else {
    // Solo muestra los errores en desarrollo (en localhost)
    if ($_SERVER['SERVER_NAME'] == 'localhost') {
        echo "❌ Error en la conexión:<br>";
        die(print_r(sqlsrv_errors(), true));
    } else {
        echo "❌ Error en la conexión.";
    }
}
?>
