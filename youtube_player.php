<?php
//*****************************************************
//Cortesia de:"@M3uKodi Telegram Group"
//Fecha : 14/08/2024       
//WebSite:https://www.m3ukodi.com
//Mail:m3ukodi@m3ukodi.com
//Donaciones:https://paypal.me/m3ukodi?locale.x=es_XC
//**************************************

error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("log_errors", 1);
ini_set("error_log", __DIR__ . "/errores.log");
ini_set("display_errors", 1);

ini_set("memory_limit", "1024M");
ini_set('upload_max_filesize', '500M');
ini_set('post_max_size', '5000M');
ini_set('max_input_time', 300);
ini_set('max_execution_time', 0);
ini_set('output_buffering', 'Off');
ini_set('implicit_flush', 1);
ini_set('zlib.output_compression', 0);
ini_set('default_socket_timeout', 20);

set_time_limit(60 * 5);
ignore_user_abort(true);
clearstatcache();

session_start();
session_unset();
session_destroy();

header("X-Robots-Tag: noindex, nofollow", true);
header("Content-Type: text/plain; charset=UTF-8");
header("Expires: Mon, 20 Dec 1998 01:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: origin,range,accept,accept-encoding,referer,content-type, SOAPAction,X-AxDRM-Message');
header('Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST');
header('Access-Control-Expose-Headers: server,range,content-range,content-length,content-type');

function cUrlGetData($url, $headers = [], $postFields = null, $cookieFile = null, $proxy = null, $userAgent = null, $getRemoteFilesize = null) {
    // Construir los encabezados HTTP
    $httpHeaders = implode("\r\n", $headers);

    $options = [
        'http' => [
            'method'  => $postFields ? 'POST' : 'GET',
            'header'  => $httpHeaders,
            'timeout' => 600,
            'ignore_errors' => true, // Ignorar errores HTTP para leer el contenido completo
        ]
    ];

    // Agregar datos POST si se proporcionan
    if ($postFields !== null) {
        $options['http']['content'] = $postFields;
    }

    // Agregar encabezado de cookies si se proporciona un archivo de cookies
    if ($cookieFile !== null && file_exists($cookieFile)) {
        $cookies = file_get_contents($cookieFile);
        if ($cookies !== false) {
            $options['http']['header'] .= "\r\nCookie: " . $cookies;
        }
    }

    // Configurar proxy si se proporciona
    if ($proxy !== null) {
        $proxyPrefix = ($proxy['type'] === 'socks') ? 'socks://' : 'tcp://';
        $options['http']['proxy'] = $proxyPrefix . $proxy['address'];
    }

    // Configurar User-Agent si se proporciona
    if ($userAgent !== null) {
        $options['http']['header'] .= "\r\nUser-Agent: " . $userAgent;
    }

    // Configurar HEAD request si se solicita el tamaño del archivo
    if ($getRemoteFilesize) {
        $options['http']['method'] = 'HEAD';
    }

    // Crear el contexto de flujo
    $context = stream_context_create($options);

    // Obtener el contenido usando file_get_contents
    $response = @file_get_contents($url, false, $context);

    // Si se solicita el tamaño del archivo remoto, obtener y devolver el tamaño
    if ($getRemoteFilesize) {
        $headers = @get_headers($url, 1, $context);
        return isset($headers['Content-Length']) ? $headers['Content-Length'] : false;
    }

    // Manejar errores si la respuesta es falsa
    if ($response === false) {
        $error = error_get_last();
        echo "Error: " . (isset($error['message']) ? $error['message'] : 'Unknown error');
        return false;
    }

    return $response;
}
// Función para decodificar secuencias de escape UTF-8 y limpiar la URL
function unescapeUTF8EscapeSeq($str) {
    while (urldecode($str) !== $str) {
        $str = urldecode($str);
    }

    $str = preg_replace_callback(
        "/\\\u([0-9a-f]{4})/i",
        function ($matches) {
            return html_entity_decode('&#x' . $matches[1] . ';', ENT_QUOTES, 'UTF-8');
        },
        $str
    );

    return rtrim($str, "/ ");
}
// Función para obtener la URL del video
function get_url_video($video_id) {
    $headers = [
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36 Edg/128.0.0.0',
        'Accept: application/json',
        //'Accept-Encoding: gzip, deflate, br, zstd', // Descomentar si decides manejar la compresión
        'Content-Type: application/json',
        'sec-ch-ua: "Chromium";v="128", "Not;A=Brand";v="24", "Microsoft Edge";v="128"',
        'sec-ch-ua-platform: "Windows"',
        'sec-ch-ua-mobile: ?0',
        'origin: https://cobalt.tools',
        'sec-fetch-site: same-site',
        'sec-fetch-mode: cors',
        'sec-fetch-dest: empty',
        'referer: https://cobalt.tools/',
        'accept-language: es,es-ES;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6,es-MX;q=0.5',
        'priority: u=1, i'
    ];

    $apiUrl = 'https://api.cobalt.tools/api/json';
    $videoUrl = 'https://www.youtube.com/watch?v=' . $video_id . '&list=RD' . $video_id . '&start_radio=1';
    $postData = ['url' => $videoUrl];
    $postFields = json_encode($postData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);

    // Obtener datos y verificar respuesta
    $response = cUrlGetData($apiUrl, $headers, $postFields);
    if ($response === false) {
        return null;
    }

    $json = json_decode($response, true);

    return isset($json['url']) ? $json['url'] : null;
}
$server = "https://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}";

// Expresión regular para validar y capturar videoId de YouTube
$id_ext_reg = '/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com|m\.youtube\.com|youtu\.be|youtube-nocookie\.com)\/(?:watch\?v=|embed\/|v\/|live\/|be\/|user\/|channel\/|e\/|oembed\?|v%3D|live\/|live_stream\?channel=|)([@a-zA-Z0-9_-]+)/';

// Verificar si QUERY_STRING está vacío
if (empty($_SERVER['QUERY_STRING'])) {
    echo 'Error: URL o ID no válido, verifique' . PHP_EOL;
    echo 'Ejemplo: ' . $server . '?https://www.youtube.com/watch?v=lMlu-4o6Pyk';
    exit;
}

// Procesar QUERY_STRING para obtener la ID del canal de YouTube
if (preg_match($id_ext_reg, $_SERVER['QUERY_STRING'], $matches)) {
    // Extraer el dominio de la URL
    $dominio = parse_url($_SERVER['QUERY_STRING'], PHP_URL_HOST);
        // Cabeceras HTTP que quieres enviar
        $headers = [
            'authority:' . $dominio,
            'Host:' . $dominio,
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
            //'Accept-Encoding: gzip, deflate, br, zstd',
            'sec-ch-ua: "Chromium";v="128", "Not;A=Brand";v="24", "Google Chrome";v="128"',
            'sec-ch-ua-mobile: ?0',
            'sec-ch-ua-full-version: "128.0.6613.27"',
            'sec-ch-ua-arch: "x86"',
            'sec-ch-ua-platform: "Windows"',
            'sec-ch-ua-platform-version: "15.0.0"',
            'sec-ch-ua-model: ""',
            'sec-ch-ua-bitness: "64"',
            'sec-ch-ua-wow64: ?0',
            'sec-ch-ua-full-version-list: "Chromium";v="128.0.6613.27", "Not;A=Brand";v="24.0.0.0", "Google Chrome";v="128.0.6613.27"',
            'sec-ch-ua-form-factors: "Desktop"',
            'upgrade-insecure-requests: 1',
            'service-worker-navigation-preload: true',
            'sec-fetch-site: cross-site',
            'sec-fetch-mode: navigate',
            'sec-fetch-user: ?1',
            'sec-fetch-dest: document',
            'accept-language: es,es-ES;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6,es-MX;q=0.5',
            'priority: u=0, i'
        ];
    
            $cookie = 'PREF=; CONSENT=YES+cb.20210328-17-p0.en+FX+796';
            
    // Determinar la variable correcta según la longitud del ID
    if (strlen($matches[1]) == 11) {
        // Video ID
        $video_id = $matches[1];
        $url_path = 'https://www.youtube.com/watch?v=' . $video_id . '&bpctr=9999999999&has_verified=1';
        $url_data = cUrlGetData($url_path, $headers);

        $ytInitialPlayerResponse_rex = '/ytInitialPlayerResponse\s*=\s*(\{.*?\})\s*;/s';
        $ytInitialChannel = '/"channelIds":\["([^"]+)"\]/s';

        if (preg_match($ytInitialPlayerResponse_rex, $url_data, $hls_match)) {
            $hls_json = json_decode($hls_match[1], true);
            preg_match($ytInitialChannel, $url_data, $json_match);
            $channelIds = trim($json_match[1]);
        } else {
            echo 'No se encontraron datos iniciales de YouTube.';
            exit;
        }
    } elseif (strlen($matches[1]) == 24) {
        // Channel ID
        $channelIds = $matches[1];
    } else {
        echo 'Longitud del ID no válida.';
        exit;
    }
    
    if (!empty($channelIds)) {
        // Fetch channel info
        $get_channel_info = 'https://' . $dominio . '/channel/' . $channelIds . '/live';
        $get_channel_data = unescapeUTF8EscapeSeq(cUrlGetData($get_channel_info, $headers, null, $cookie));

        // Extract HLS URL
        $url_m3u8 = null;
        if (preg_match('/"hlsManifestUrl":"([^"]+)"/', $get_channel_data, $hlsManifest_match)) {
            $url_m3u8 = unescapeUTF8EscapeSeq($hlsManifest_match[1]);
        } elseif (preg_match('/"formats":\[\{"url":"([^"]+)"/', $get_channel_data, $formats_match)) {
            $url_m3u8 = unescapeUTF8EscapeSeq($formats_match[1]);
        } elseif (preg_match('/"videoId":"([^"]+)"/', $get_channel_data, $video_id_match)) { 
            $url_m3u8 = rtrim(get_url_video($video_id_match[1]));
            if (!$url_m3u8) {exit('No se encontró una URL de video.');}
            
        } elseif (preg_match('/"itag":(\d+).*?url=([^"]+)/', $get_channel_data, $itag_match)) {
            $itag_regex = '/"itag":(\d+).*?url=([^"]+)/';
            preg_match_all($itag_regex, $get_channel_data, $matches);

            $default_itag = "18"; // Default itag
            $default_url = null;

            foreach ($matches[1] as $i => $itag) {
                $url_m3u8_itag = unescapeUTF8EscapeSeq(urldecode($matches[2][$i]));

                if ($itag == $default_itag) {
                    $default_url = $url_m3u8_itag;
                }

                if ($httpCode = cUrlGetData($url_m3u8_itag, $headers, null, $cookie, null, null, true)) {
                    if ($httpCode >= 200 && $httpCode < 400) {
                        echo $url_m3u8_itag;
                        exit;
                    }
                }
            }

            if ($default_url) {
                if ($httpCode = cUrlGetData($default_url, $headers, null, $cookie, null, null, true)) {
                    if ($httpCode >= 200 && $httpCode < 400) {
                        echo $default_url;
                        exit;
                    }
                }
            }

            echo "No se encontró una URL activa.";
            exit;
        } else {
            echo 'No se encontraron URLs adecuadas ni el video está en vivo.';
            exit;
        }

        // Redirigir a la URL de streaming
        if (!empty($url_m3u8)) {
            header("Location: " . $url_m3u8);
            exit;
        } else {
            echo 'Error: No se encontró una URL de streaming válida.';
            exit;
        }
    } else {
        echo "URL o VIDEO ID no válido.";
        exit;
    }
} else {
    echo 'Error: URL o ID no válido, verifique' . PHP_EOL;
    echo 'Ejemplo: ' . $server . '?https://www.youtube.com/watch?v=lMlu-4o6Pyk';
    exit;
}
?>
