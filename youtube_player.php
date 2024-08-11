<?php
/**
 * Script courtesy of "@M3uKodi Telegram Group"
 * Date: 04/08/2024
 * Website: https://www.m3ukodi.com
 * Contact: m3ukodi@m3ukodi.com
 * Donations: https://paypal.me/m3ukodi?locale.x=es_XC
 */

// Configuración para mostrar errores y logs
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("log_errors", 1);
ini_set("error_log", __DIR__ . "/errores.log");
ini_set("display_errors", 0); // Cambiar a 0 en producción

// Configuración de límites y ajustes de PHP
@ini_set("memory_limit","1024M");
@ini_set('upload_max_filesize', '500M');
@ini_set('post_max_size', '5000M');
@ini_set('max_input_time', 300);
@ini_set('max_execution_time', 0);
@ini_set('output_buffering', 'Off'); 
@ini_set('implicit_flush', 1); 
@ini_set('zlib.output_compression', 0); 

// Headers para seguridad y cache
header("X-Robots-Tag: noindex, nofollow", true);
header("Content-type: text/plain; charset=UTF-8");
header("Expires: Mon, 20 Dec 1998 01:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: origin,range,accept,accept-encoding,referer,content-type,SOAPAction,X-AxDRM-Message');
header('Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST');
header('Access-Control-Expose-Headers: server,range,content-range,content-length,content-type');


// Iniciar el buffer de salida
ob_start();
ob_implicit_flush(1);

// Función para obtener datos usando cURL
function cUrlGetData($url, $headers = null, $postFields = null, $head = null, $proxies = null, $cookie = null) {
    $ch = curl_init($url);
    $timeout = 10;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_ENCODING, '');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

    if ($postFields && !empty($postFields)) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    }
    if ($headers && !empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    if ($proxies && !empty($proxies)) {
        curl_setopt($ch, CURLOPT_PROXY, $proxies);
        curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 0);
    }
    if ($cookie && !empty($cookie)) {
        curl_setopt($ch, CURLOPT_COOKIEJAR, realpath(dirname(__FILE__)) . '/m3ukodi.txt');
    }
    if ($head && !empty($head)) {
        curl_setopt($ch, CURLOPT_HEADER, $head);
        curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
        $head_data = curl_getinfo($ch, CURLINFO_HEADER_OUT);
    }

    $data = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    return $data;
}

// Función para decodificar secuencias de escape UTF-8
function unescapeUTF8EscapeSeq($str) {
    return preg_replace_callback(
        "/\\\u([0-9a-f]{4})/i",
        function ($matches) {
            return html_entity_decode('&#x' . $matches[1] . ';', ENT_QUOTES, 'UTF-8');
        },
        $str
    );
}

// Función para obtener la URL del video
function get_url_video($video_id) {
    // Configurar headers para la solicitud a la API
    $headers = [
        'accept: application/json',
        'content-type: application/json',
        'origin: https://shailendramaurya.github.io',
        'referer: https://shailendramaurya.github.io/', 
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36 Edg/127.0.0.0'
    ];

    // URL de la API para obtener el video de YouTube
    $apiUrl = 'https://co.wuk.sh/api/json';

    // URL del video de YouTube
    $videoUrl = 'https://www.youtube.com/watch?v='.$video_id;

    // Datos para enviar en la solicitud POST
    $postData = [
        'url' => $videoUrl,
        'filenamePattern' => 'pretty',
        'vQuality' => '720',
        'isAudioOnly' => false,
        'aFormat' => null
    ];

    // Convertir datos a JSON
    $postFields = json_encode($postData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);

    // Realizar la solicitud POST a la API y obtener la URL del video
    $url_video = json_decode(cUrlGetData($apiUrl, $headers, $postFields), true)['url'];

    return $url_video;
}
$server = "https://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}";
// Expresión regular para validar y capturar videoId de YouTube
$id_ext_reg = '/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com|m\.youtube\.com|youtu\.be|youtube-nocookie\.com)\/(?:watch\?v=|embed\/|v\/|live\/|be\/|user\/|channel\/|e\/|oembed\?|v%3D|live\/|live_stream\?channel=|)([@a-zA-Z0-9_-]+)/';

// Verificar si QUERY_STRING está vacío
if (empty($_SERVER['QUERY_STRING'])) {
    echo 'Error: URL o ID no Valido, verifique';
    echo 'Ejemplo:'.$server.'?https://www.youtube.com/watch?v=lMlu-4o6Pyk';
    exit;
}

// Procesar QUERY_STRING para obtener la ID del canal de YouTube
if (isset($_SERVER['QUERY_STRING']) && preg_match($id_ext_reg, $_SERVER['QUERY_STRING'])) {

    $dominio = parse_url($_SERVER['QUERY_STRING'], PHP_URL_HOST);
    $headers = [
        'authority:' . $dominio,
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36',
    ];
    preg_match($id_ext_reg, $_SERVER['QUERY_STRING'], $channel_id);
    $video_id=$channel_id[1];
    $url_data = rtrim('https://www.youtube.com/v/' . $video_id. '?version=3&autoplay=1');
    $data_itag = cUrlGetData($url_data, $headers);
    $ytInitialPlayer_rex = '/ytInitialPlayerResponse\s*=\s*(\{.*?\})\s*;/s';
    $ytInitialData_rex = '/ytInitialData\s*=\s*(\{.*?\})\s*;/s';
    $ytData_rex = '/ytcsi\s*=\s*(\{.*?\})\s*;/s';

    // Buscar y decodificar el JSON de ytInitialPlayerResponse o ytInitialData
     if (preg_match($ytInitialPlayer_rex, $data_itag, $json_match) ||
         preg_match($ytInitialData_rex, $data_itag, $json_match)||
         preg_match($ytData_rex, $data_itag, $json_match)) {

        $ytInitialResponse = json_decode($json_match[1], true);
        // Verificar si se encontró ytInitialResponse o ytInitialData válido
        if ($ytInitialResponse !== null) {
            $url_m3u8 = '';

            // Obtener la URL del manifiesto HLS o adaptar formatos
            if (isset($ytInitialResponse['streamingData']['hlsManifestUrl'])) {
                $url_m3u8 = urldecode(unescapeUTF8EscapeSeq(rtrim($ytInitialResponse['streamingData']['hlsManifestUrl'])));
            } elseif (isset($ytInitialResponse['streamingData']['formats'][0]['url'])) {
                $url_m3u8 = get_url_video($video_id);
            } elseif (isset($ytInitialResponse['videoDetails']['isLive']) && $ytInitialResponse['videoDetails']['isLive'] == 1) {
                $channelId = $ytInitialResponse['videoDetails']['channelId'];
                $video_id = $ytInitialResponse['videoDetails']['videoId'];
               
                prn($get_channel_info = 'https://'.$dominio.'/embed/live_stream?autoplay=1&channel='.$channelId);     
                //prn($get_channel_data = cUrlGetData($get_channel_info, $headers));
                //preg_match($ytInitialData_rex, $get_channel_data, $get_channel_match);
               //print_r(json_decode($get_channel_match[1],true));
            }
            // Redirigir al usuario a la URL de streaming obtenida
            if (!empty($url_m3u8)) {
                header("Location: " . $url_m3u8);
                exit;
            } else {
                echo 'Error: No se encontró una URL de streaming válida.';
            }
        } else {
            echo 'Error: No se pudo decodificar el JSON ytInitialPlayerResponse o ytInitialData.';
        }
    } else {
        echo 'Error: No se pudo encontrar el JSON ytInitialPlayerResponse o ytInitialData en los datos recibidos.';
    }
} else {
    echo 'Error: No se encontró una ID de canal válida en la cadena de consulta.';
}
?>
