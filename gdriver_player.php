<?php
//*****************************************************
//Cortesia de:"@M3uKodi Telegram Group"
//Fecha : 17/09/2024     
//WebSite:https://www.m3ukodi.com
//Mail:m3ukodi@m3ukodi.com
//Donaciones:https://paypal.me/m3ukodi?locale.x=es_XC
//**************************************
//GDrive de Google, Este PHP solo es para los ARCHIVOS que este compartido de forma publica por el Propietario 
//https://drive.google.com/file/d/1rBgRZTK6E6reM4Su6YtI5kDHMWg6bn6Z/view 
ini_set("display_errors",1);
ini_set("memory_limit","1024M");
ini_set('upload_max_filesize', '500M');
ini_set('post_max_size', '5000M');
ini_set('max_input_time', 300);
ini_set('max_execution_time', 0);
ini_set('allow_url_include', 'On');
ignore_user_abort(true);
clearstatcache();
header("X-Robots-Tag: noindex, nofollow", true);
header("Content-Type: text/plain");	

ob_start();
// Configuración de encabezados HTTP
header("X-Robots-Tag: noindex, nofollow", true); // Indicar a los robots que no indexen ni sigan
header("Content-Type: text/plain; charset=UTF-8"); // Establecer el tipo de contenido como texto plano con codificación UTF-8
header("Expires: Mon, 20 Dec 1998 01:00:00 GMT"); // Establecer fecha de caducidad
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // Fecha de última modificación
header("Cache-Control: no-cache, must-revalidate"); // Control de caché
header("Pragma: no-cache"); // Desactivar el almacenamiento en caché
header('Access-Control-Allow-Origin: *'); // Permitir acceso a todos los orígenes
header('Access-Control-Allow-Headers: origin,range,accept,accept-encoding,referer,content-type,SOAPAction,X-AxDRM-Message'); // Permitir estos encabezados
header('Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST'); // Permitir estos métodos
header('Access-Control-Expose-Headers: server,range,content-range,content-length,content-type'); // Exponer estos encabezados

// Iniciar una nueva sesión y destruir cualquier sesión anterior
session_start();
session_unset();
session_destroy();

// Función cUrlGetData para obtener datos usando cURL
function cUrlGetData($url, $headers = [], $head = null, $cookie = null, $referer = null, $checkUrl = null, $proxies = null, $postFields = null, $ua = null, $getRemoteFilesize = null) {
    $ch = curl_init();

    // Configurar el agente de usuario del cliente que realiza la solicitud
    $userAgent = $ua ?? $_SERVER['HTTP_USER_AGENT'] ?? 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36';
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '', // Soporte para codificación de respuesta
        CURLOPT_FOLLOWLOCATION => true, // Seguir redirecciones
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSLVERSION => 0, // Usar TLS automáticamente
        CURLOPT_CONNECTTIMEOUT => 600,
        CURLOPT_USERAGENT => $userAgent,
        CURLOPT_HEADER => true, // Incluye el encabezado en la salida
    ]);

    if ($referer) {
        curl_setopt($ch, CURLOPT_REFERER, $referer);
    }

    // Configurar el proxy si se proporciona en $proxies
    if ($proxies && !empty($proxies['proxy'])) {
        $proxy = $proxies['proxy'];
        $typeProxy = $proxies['typeProxy'] ?? 'http';

        $proxyType = ($typeProxy === 'socks') ? CURLPROXY_SOCKS5 :
                     (($typeProxy === 'socks4') ? CURLPROXY_SOCKS4 : CURLPROXY_HTTP);
        $proxyPrefix = ($typeProxy === 'socks') ? 'socks5://' :
                       (($typeProxy === 'socks4') ? 'socks4://' : 'http://');

        curl_setopt_array($ch, [
            CURLOPT_PROXY => $proxyPrefix . $proxy,
            CURLOPT_PROXYTYPE => $proxyType,
        ]);
    }

    // Establecer los encabezados si se proporcionan
    if ($headers) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }

    if ($head) {
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    }

    // Si se proporciona $checkUrl, verificar la URL
    if ($checkUrl) {
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $httpCode === 200;
    }

    // Configurar los datos del cuerpo si se proporcionan en $postFields
    if ($postFields) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));
    }

    // Configurar para obtener el tamaño del archivo remoto si se solicita
    if ($getRemoteFilesize) {
        curl_setopt($ch, CURLOPT_NOBODY, true);
        $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
    }

    // Configurar el manejo de cookies si se proporciona en $cookie
    if ($cookie) {
        curl_setopt($ch, CURLOPT_COOKIESESSION, 1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, __DIR__ . "/cookie.txt");
        curl_setopt($ch, CURLOPT_COOKIEJAR, __DIR__ . "/cookie.txt");
    }

    $response = curl_exec($ch);
    $error = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Extraer cookies del encabezado de respuesta solo si $cookie es NULL
    if (!$cookie) {
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);
        
        // Guardar cookies en la variable global
        $matches = [];
        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $header, $matches);
        foreach ($matches[1] as $match) {
            parse_str($match, $cookieData);
            foreach ($cookieData as $key => $value) {
                // Actualiza la variable global con las cookies
                $GLOBALS['globalCookies'][$key] = $value;
            }
        }
    }

    curl_close($ch);

    if ($error) {
        echo 'Error: ' . $error;
        return false;
    }

    if ($httpCode === 200) {
        if ($getRemoteFilesize) {
            return $size;
        }
        return $cookie ? $response : ['body' => $body, 'cookies' => $GLOBALS['globalCookies']];
    }

    return false;
}

$url_cookies='https://drive.google.com?tab=oo';
$headers = [
    "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36",
    "Accept-Language: en-US,en;q=0.9",
    "Referer: https://drive.google.com",
];
$referer = 'https://drive.google.com';
$get_cookie=cUrlGetData($url_cookies, $headers, $cookie=true);
$nid = $GLOBALS['globalCookies']['NID'] ?? null;

// Obtener el ID de Google Drive desde la URL
if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
    
    $id = $_SERVER['QUERY_STRING'];
    $url_uui=rtrim("https://drive.usercontent.google.com/download?id=".$id. "&export=download&authuser=0&confirm=t");	
 header("Location:".$url_uui);
}
?>
