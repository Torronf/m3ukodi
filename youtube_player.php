<?php
//*****************************************************
//Cortesia de:"@M3uKodi Telegram Group"
//Fecha : 14/08/2024       
//WebSite:https://www.m3ukodi.com
//Mail:m3ukodi@m3ukodi.com
//Donaciones:https://paypal.me/m3ukodi?locale.x=es_XC
//**************************************

error_reporting(E_ERROR | E_WARNING | E_PARSE); // Mostrar solo errores, advertencias y errores de análisis
ini_set("log_errors", 1); // Habilitar el registro de errores
ini_set("error_log", __DIR__ . "/errores.log"); // Especificar el archivo de registro de errores
ini_set("display_errors", 1); // Mostrar errores en la salida

// Configuración de límites y tiempos de ejecución
ini_set("memory_limit", "1024M"); // Establecer límite de memoria a 1024M
ini_set('upload_max_filesize', '500M'); // Límite máximo de tamaño de archivo para subir a 500M
ini_set('post_max_size', '5000M'); // Límite máximo del tamaño de datos POST a 5000M
ini_set('max_input_time', 300); // Tiempo máximo de entrada permitido
ini_set('max_execution_time', 0); // Tiempo máximo de ejecución del script (0 significa sin límite)
ini_set('output_buffering', 'Off'); // Desactivar el almacenamiento en búfer de salida
ini_set('implicit_flush', 1); // Activar el vaciado implícito
ini_set('zlib.output_compression', 0); // Desactivar la compresión de salida zlib
ini_set('default_socket_timeout', 20); // Establecer tiempo de espera predeterminado del socket a 20 segundos

set_time_limit(60 * 5); // Establecer límite de tiempo de ejecución del script a 5 minutos
ignore_user_abort(true); // Ignorar la desconexión del usuario
clearstatcache(); // Limpiar la caché de estado de archivos

// Iniciar una nueva sesión y destruir cualquier sesión anterior
session_start();
session_unset();
session_destroy();

// Configuración de encabezados HTTP
header("X-Robots-Tag: noindex, nofollow", true); // Indicar a los robots que no indexen ni sigan
header("Content-Type: text/plain; charset=UTF-8"); // Establecer el tipo de contenido como texto plano con codificación UTF-8
header("Expires: Mon, 20 Dec 1998 01:00:00 GMT"); // Establecer fecha de caducidad
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // Fecha de última modificación
header("Cache-Control: no-cache, must-revalidate"); // Control de caché
header("Pragma: no-cache"); // Desactivar el almacenamiento en caché
header('Access-Control-Allow-Origin: *'); // Permitir acceso a todos los orígenes
header('Access-Control-Allow-Headers: origin,range,accept,accept-encoding,referer,content-type, SOAPAction,X-AxDRM-Message'); // Permitir estos encabezados
header('Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST'); // Permitir estos métodos
header('Access-Control-Expose-Headers: server,range,content-range,content-length,content-type'); // Exponer estos encabezados


// Configuración de salida
ob_start();
ob_implicit_flush(1);
$cf = basename($_SERVER['SCRIPT_FILENAME']);

// Función para obtener datos usando cURL
function cUrlGetData($url, $headers = null, $postFields = null, $cookie = null, $head = null, $proxies = null, $returnHttpCodeOnly = false) {
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
    curl_setopt($ch, CURLOPT_NOBODY, $returnHttpCodeOnly); // Si solo se necesita el código de estado

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
    }

    $data = curl_exec($ch);
    
    if ($returnHttpCodeOnly) {
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $httpCode;
    }

    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    
    curl_close($ch);
    return $data;
}


// Función para decodificar secuencias de escape UTF-8, decodificar URLs y limpiar la URL
function unescapeUTF8EscapeSeq($str) {
    // Decodificar la URL de forma recursiva
    while (urldecode($str) !== $str) {
        $str = urldecode($str);
    }

    // Decodificar las secuencias de escape UTF-8
    $str = preg_replace_callback(
        "/\\\u([0-9a-f]{4})/i",
        function ($matches) {
            return html_entity_decode('&#x' . $matches[1] . ';', ENT_QUOTES, 'UTF-8');
        },
        $str
    );
    
    // Limpiar la URL de caracteres no deseados al final
    return rtrim($str, "/ ");
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
            $apiUrl = 'https://api.cobalt.tools/api/json';

            // URL del video de YouTube
            $videoUrl = rtrim('https://www.youtube.com/watch?v='.$video_id);

            // Datos para enviar en la solicitud POST
            $postData = [
                'url' => $videoUrl
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
    echo 'Error: URL o ID no Valido, verifique'.PHP_EOL;
    echo 'Ejemplo: ' . $server . '?https://www.youtube.com/watch?v=lMlu-4o6Pyk';
    exit;
}

// Procesar QUERY_STRING para obtener la ID del canal de YouTube
        if (isset($_SERVER['QUERY_STRING']) && preg_match($id_ext_reg, $_SERVER['QUERY_STRING'], $matches)) {
        //  arch_php($_SERVER['QUERY_STRING']);
            // Extraer el dominio de la URL
            $dominio = parse_url($_SERVER['QUERY_STRING'], PHP_URL_HOST);
            $headers = [
                'authority:' . $dominio,
                'Host:' . $dominio,
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.5795.1 Safari/537.36',
                'Connection: Keep-Alive',
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'Accept-Encoding: gzip,deflate',
                'Accept-Language: en-us,en;q=0.5',
                'Sec-Fetch-Mode: navigate',
            ];
            $cookie = 'PREF=; CONSENT=YES+cb.20210328-17-p0.en+FX+796';

            // Determinar la variable correcta según la longitud del ID
            if (strlen($matches[1]) == 11) {
                // Video ID
                $video_id = $matches[1];
                $url_path = 'https://www.youtube.com/watch?v=' . $video_id . '&bpctr=9999999999&has_verified=1';
                $url_data = cUrlGetData($url_path, $headers, null, $cookie);

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
                } elseif (preg_match('/"videoId"\s*:\s*"'.$video_id.'"/', $get_channel_data, $video_id_match)) {
                    prn($video_id_match);
                    //$url_m3u8 = rtrim(get_url_video($video_id));                
                } elseif (preg_match('/"itag":(\d+).*?url=([^"]+)/', $get_channel_data, $itag_match)) {
                    $itag_regex = '/"itag":(\d+).*?url=([^"]+)/';
                    preg_match_all($itag_regex, $url_data, $matches);

                    $default_itag = "18"; // Default itag
                    $default_url = null;

                    foreach ($matches[1] as $i => $itag) {
                        $url_m3u8_itag = unescapeUTF8EscapeSeq(urldecode($matches[2][$i]));

                        if ($itag == $default_itag) {
                            $default_url = $url_m3u8_itag;
                        }

                        if (cUrlGetData($url_m3u8_itag, $headers, null, $cookie, null, null, true) >= 200 && $httpCode < 400) {
                            echo $url_m3u8_itag;
                            print_r(cUrlGetData($url_m3u8_itag, $headers, null, $cookie));
                            exit;
                        }
                    }

                    if ($default_url) {
                        $httpCode = cUrlGetData($default_url, $headers, null, $cookie, null, null, true);
                        if ($httpCode >= 200 && $httpCode < 400) {
                            echo $default_url;
                            print_r(cUrlGetData($default_url, $headers, null, $cookie));
                            exit;
                        } else {
                            echo "No se encontró una URL activa.";
                        }
                    } else {
                        echo "No se encontraron URLs activas.";
                    }
                    exit;


                } else {
                    echo 'No se encontraron URLs adecuadas ni el video está en vivo.';
                    exit;
                }

                // Redirect to the streaming URL
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

            }
?>
