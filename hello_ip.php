<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificador de IP / País</title>
</head>
<body>
    <h1>Nuestro Registro Determina:</h1>
    <?php
    // Función para obtener la dirección IP del visitante
    function obtenerIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            // IP de la mayoría de los compartidos
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // IP a través de un proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    // Obtener la dirección IP del visitante
    $ip = obtenerIP();

    // URL de la API de ip-api.com para obtener información geográfica del visitante
    $url_visitante = "http://ip-api.com/json/$ip";

    // Realizar la solicitud GET a la API para el visitante
    $datos_visitante = file_get_contents($url_visitante);

    // Decodificar los datos JSON para el visitante
    $datos_json_visitante = json_decode($datos_visitante);

    // Mostrar el país del visitante
    if ($datos_json_visitante && $datos_json_visitante->status == 'success') {
        echo "<p>Tu País es: " . $datos_json_visitante->country . "</p>";
        echo "<p>Dirección IP de tu Máquina: " . $datos_json_visitante->query . "</p>";
    } else {
        echo "<p>No se pudo obtener la información del país para $ip</p>";
    }

    // Obtener la dirección IP del servidor
    $server_ip = $_SERVER['SERVER_ADDR'];

    // URL de la API de ip-api.com para obtener información geográfica del servidor
    $url_servidor = "http://ip-api.com/json/$server_ip";

    // Realizar la solicitud GET a la API para el servidor
    $datos_servidor = file_get_contents($url_servidor);

    // Decodificar los datos JSON para el servidor
    $datos_json_servidor = json_decode($datos_servidor);

    // Mostrar el país del servidor si está definido
    if ($datos_json_servidor && isset($datos_json_servidor->country)) {
        echo "<p>País de tu Servidor: " . $datos_json_servidor->country . "</p>";
    } else {
        echo "<p>No se pudo obtener la información del país para $server_ip</p>";
    }

    // Mostrar información adicional del servidor
    $server_name = $_SERVER['SERVER_NAME'];
    echo "<p>Nombre del host del servidor: " . $server_name . "</p>";

    $server_port = $_SERVER['SERVER_PORT'];
    echo "<p>Puerto del servidor HTTP: " . $server_port . "</p>";

    $request_method = $_SERVER['REQUEST_METHOD'];
    echo "<p>Método de solicitud HTTP: " . $request_method . "</p>";

    $request_uri = $_SERVER['REQUEST_URI'];
    echo "<p>URL solicitada: " . $request_uri . "</p>";

    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    echo "<p>User Agent: " . $user_agent . "</p>";

    $referer = $_SERVER['HTTP_REFERER'] ?? 'No referer';
    echo "<p>Referencia (referer): " . $referer . "</p>";
    ?>
</body>
</html>
