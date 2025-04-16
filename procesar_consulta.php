<?php
include 'conexion.php';
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si los datos fueron enviados correctamente
    if (!isset($_POST['pregunta']) || !isset($_POST['tipo_tutor'])) {
        echo json_encode(["error" => "❌ Los datos no fueron enviados correctamente."]);
        exit();
    }

    $pregunta = trim($_POST['pregunta']);
    $tipo_tutor = $_POST['tipo_tutor'];
    $respuesta_ia = "";

    if (empty($pregunta) || empty($tipo_tutor)) {
        echo json_encode(["error" => "❌ La pregunta o el tipo de tutor están vacíos."]);
        exit();
    }

    if ($tipo_tutor === "ia") {
        // Configurar API de Perplexity AI
        $api_key = PERPLEXITY_API_KEY;
        $url = "https://www.perplexity.ai"; // Asegúrate de que usas HTTPS correctamente

        $data = [
            "model" => "sonar-pro", // Modelo usado. Cambia si necesitas otro.
            "messages" => [
                ["role" => "system", "content" => "Responde como un tutor educativo."],
                ["role" => "user", "content" => $pregunta]
            ],
            "temperature" => 0.7,
            "max_tokens" => 1000
        ];

        $headers = [
            "Authorization: Bearer $api_key",
            "Content-Type: application/json"
        ];

        // Enviar solicitud a Perplexity AI
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            echo json_encode(["error" => "Error de cURL: " . curl_error($ch)]);
            curl_close($ch);
            exit();
        }

        curl_close($ch);

        if ($http_status !== 200 || !$response) {
            echo json_encode(["error" => "❌ No se pudo obtener una respuesta de la API. Código HTTP: $http_status"]);
            exit();
        }

        $result = json_decode($response, true);
        $respuesta_ia = $result['messages'][0]['content'] ?? "Lo siento, no pude generar una respuesta.";

        // Guardar la respuesta en la base de datos
        $sql_insert = "INSERT INTO Preguntas (id_usuario, pregunta, tipo_tutor, respuesta_ia, fecha_pregunta) VALUES (?, ?, ?, ?, GETDATE())";
        $params_insert = array($_SESSION['id_usuario'], $pregunta, $tipo_tutor, $respuesta_ia);
        sqlsrv_query($conn, $sql_insert, $params_insert);
    }

    echo json_encode(["respuesta" => $respuesta_ia]);
    exit();
}
?>