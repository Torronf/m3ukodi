<?php
//*****************************************************
//Cortesia de:"@M3uKodi Telegram Group"
//Fecha : 14/06/2024 
//WebSite:https://www.m3ukodi.com
//Mail:m3ukodi@m3ukodi.com
//Donaciones:https://paypal.me/m3ukodi?locale.x=es_XC
//**************************************
// Configuración de reporte y registro de errores
error_reporting(E_ERROR | E_WARNING | E_PARSE); // Mostrar solo errores, advertencias y errores de análisis
ini_set("log_errors", 1); // Habilitar el registro de errores
ini_set("error_log", "/errores.log"); // Especificar el archivo de registro de errores
ini_set("display_errors", 1); // Mostrar errores en la salida

// Configuración de límites y tiempos de ejecución
@ini_set("memory_limit","1024M"); // Establecer límite de memoria a 1024M
@ini_set('upload_max_filesize', '500M'); // Límite máximo de tamaño de archivo para subir a 500M
@ini_set('post_max_size', '5000M'); // Límite máximo del tamaño de datos POST a 5000M
@ini_set('max_input_time', 300); // Tiempo máximo de entrada permitido
@ini_set('max_execution_time', 0); // Tiempo máximo de ejecución del script (0 significa sin límite)
@ini_set('output_buffering', 'Off'); // Desactivar el almacenamiento en búfer de salida
@ini_set('implicit_flush', 1); // Activar el vaciado implícito
@ini_set('zlib.output_compression', 0); // Desactivar la compresión de salida zlib
@ini_set('default_socket_timeout', 20); // Establecer tiempo de espera predeterminado del socket a 20 segundos

set_time_limit(60*5); // Establecer límite de tiempo de ejecución del script a 5 minutos
ignore_user_abort(true); // Ignorar la desconexión del usuario
clearstatcache(); // Limpiar la caché de estado de archivos

// Iniciar una nueva sesión y destruir cualquier sesión anterior
session_start(); 
session_unset();
session_destroy();

// Configuración de encabezados HTTP
header("X-Robots-Tag: noindex, nofollow", true); // Indicar a los robots que no indexen ni sigan
header("Content-Type: text/plain"); // Establecer el tipo de contenido como texto plano
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

function cUrlGetData($url, $headers = [], $head = null, $checkUrl = null, $proxies = null, $postFields = null, $cookie = null, $ua = null, $getRemoteFilesize = null) {
    $ch = curl_init();

    // Configurar el agente de usuario del cliente que realiza la solicitud
    if ($ua && !empty($ua)) {
        $_SERVER['HTTP_USER_AGENT'] = $ua;
    }

    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '', // Soporte para codificación de respuesta
        CURLOPT_FOLLOWLOCATION => 0, // Seguir redirecciones
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSLVERSION => 3,
        CURLOPT_SSL_VERIFYSTATUS => false,
        CURLOPT_CONNECTTIMEOUT => 600,
        CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
    ]);

    // Configurar el proxy si se proporciona en $proxies
    if ($proxies !== null && !empty($proxies['proxy'])) {
        $proxy = $proxies['proxy'];
        $typeProxy = isset($proxies['typeProxy']) ? $proxies['typeProxy'] : 'http';

        if ($typeProxy === 'socks') {
            $proxyType = CURLPROXY_SOCKS5;
            $proxyPrefix = 'socks5://';
        } elseif ($typeProxy === 'socks4') {
            $proxyType = CURLPROXY_SOCKS4;
            $proxyPrefix = 'socks4://';
        } else {
            $proxyType = CURLPROXY_HTTP;
            $proxyPrefix = 'http://';
        }

        curl_setopt_array($ch, [
            CURLOPT_PROXY => $proxyPrefix . $proxy,
            CURLOPT_PROXYTYPE => $proxyType,
        ]);
    }

    // Establecer los encabezados si se proporcionan
    if ($headers && !empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    if ($head && !empty($head)) {
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    } else {
        curl_setopt($ch, CURLOPT_HEADER, false);
    }

    // Si se proporciona $checkUrl, verificar la URL
    if ($checkUrl !== null) {
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $httpCode === 200;
    }

    // Configurar los datos del cuerpo si se proporcionan en $postFields
    if ($postFields !== null) {
        $postFields = http_build_query($postFields);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    }

    // Configurar para obtener el tamaño del archivo remoto si se solicita
    if ($getRemoteFilesize) {
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 222222);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
    }

    // Configurar el manejo de cookies si se proporciona en $cookie
    if ($cookie && !empty($cookie)) {
        curl_setopt($ch, CURLOPT_COOKIESESSION, 1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, __DIR__ . "/cookie.txt");
        curl_setopt($ch, CURLOPT_COOKIEJAR, __DIR__ . "/cookie.txt");
    }

    $response = curl_exec($ch);
    $error = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($error) {
        echo $error;
    } elseif ($httpCode === 200) {
        return $response;
    }

    // Extraer el tamaño del Archivo
    if ($getRemoteFilesize && !empty($getRemoteFilesize)) {
        return $size;
    }

    // Extraer cookies del encabezado de respuesta solo si $cookie no es NULL
    if ($cookie && !empty($cookie)) {
        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $response, $matches);
        $cookies = [];
        foreach ($matches[1] as $match) {
            parse_str($match, $cookieData);
            $cookies += $cookieData;
        }

        // Devolver las cookies extraídas
        return $cookies;
    }
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }

    curl_close($ch);

    if ($response) return $response; else return FALSE;
}
define('BEARER', "eyJhbGciOiJIUzI1NiIsImtpZCI6Ijg3YTY3ZTA3LWU0Y2EtNGMxYS04ZTdhLTQ4NWJhNmNiNTg0MiIsInR5cCI6IkpXVCJ9.eyJzZXNzaW9uSUQiOiJiNDY3OTM4MC0yYTUzLTExZWYtOTQ3YS1hNmFjMDRiNmM4NDkiLCJjbGllbnRJUCI6IjE3OS41Mi4yOS45NCIsImNpdHkiOiJCYWpvcyBEZSBIYWluYSIsInBvc3RhbENvZGUiOiI5MTAwMCIsImNvdW50cnkiOiJETyIsImRtYSI6MCwiYWN0aXZlUmVnaW9uIjoiVkUiLCJkZXZpY2VMYXQiOjE4LjQyMDAwMDA3NjI5Mzk0NSwiZGV2aWNlTG9uIjotNzAuMDI5OTk4Nzc5Mjk2ODgsInByZWZlcnJlZExhbmd1YWdlIjoiZXMiLCJkZXZpY2VUeXBlIjoiYW5kcm9pZCxBc3VzLG1vYmlsZSIsImRldmljZVZlcnNpb24iOiI5XzI4IiwiZGV2aWNlTWFrZSI6IkFzdXMiLCJkZXZpY2VNb2RlbCI6IkFTVVNfSTAwNURBIiwiYXBwTmFtZSI6ImFuZHJvaWRtb2JpbGUiLCJhcHBWZXJzaW9uIjoiNS4xMC4wIiwiY2xpZW50SUQiOiI5YjViODA2Zi0zODIwLTQ1ZDUtYjA3ZS1mMzM5ZTNjMWNhOWVfN2RkNjAyYzBiMDBhMmFiOSIsImNtQXVkaWVuY2VJRCI6IiIsImlzQ2xpZW50RE5UIjpmYWxzZSwidXNlcklEIjoiIiwibG9nTGV2ZWwiOiJERUZBVUxUIiwidGltZVpvbmUiOiJBbWVyaWNhL1NhbnRvX0RvbWluZ28iLCJzZXJ2ZXJTaWRlQWRzIjpmYWxzZSwiZTJlQmVhY29ucyI6ZmFsc2UsImZlYXR1cmVzIjp7Im11bHRpUG9kQWRzIjp7ImVuYWJsZWQiOnRydWV9fSwiZm1zUGFyYW1zIjp7ImZ3VmNJRDIiOiI5YjViODA2Zi0zODIwLTQ1ZDUtYjA3ZS1mMzM5ZTNjMWNhOWVfN2RkNjAyYzBiMDBhMmFiOSIsImZ3VmNJRDJDb3BwYSI6IjliNWI4MDZmLTM4MjAtNDVkNS1iMDdlLWYzMzllM2MxY2E5ZV83ZGQ2MDJjMGIwMGEyYWI5IiwiY3VzdG9tUGFyYW1zIjp7ImZtc19saXZlcmFtcF9pZGwiOiIiLCJmbXNfZW1haWxoYXNoIjoiIiwiZm1zX3N1YnNjcmliZXJpZCI6IiIsImZtc19pZmEiOiIiLCJmbXNfaWRmdiI6IiIsImZtc191c2VyaWQiOiI5YjViODA2Zi0zODIwLTQ1ZDUtYjA3ZS1mMzM5ZTNjMWNhOWVfN2RkNjAyYzBiMDBhMmFiOSIsImZtc192Y2lkMnR5cGUiOiJ1c2VyaWQiLCJmbXNfcmFtcF9pZCI6IiIsImZtc19oaF9yYW1wX2lkIjoiIiwiZm1zX2JpZGlkdHlwZSI6IiIsIl9md18zUF9VSUQiOiIiLCJmbXNfcnVsZWlkIjoiMTAwMDAsMTAwMDksMTAwMDMifX0sImlzcyI6ImJvb3QucGx1dG8udHYiLCJzdWIiOiJwcmk6djE6cGx1dG86ZGV2aWNlczpWRTpPV0kxWWpnd05tWXRNemd5TUMwME5XUTFMV0l3TjJVdFpqTXpPV1V6WXpGallUbGxYemRrWkRZd01tTXdZakF3WVRKaFlqaz0iLCJhdWQiOiIqLnBsdXRvLnR2IiwiZXhwIjoxNzE4NDU4ODQ2LCJpYXQiOjE3MTgzNzI0NDYsImp0aSI6IjM2Njc2OGQ3LTU3ZTMtNDAzOC04NGFkLTRmNDdjN2YwMTI2ZCJ9.8lnYQBapFPaIkQH1ePuCIbiLPQXPAQEcpW08bx0wWQo");

$headers = ['Authorization:Bearer '.BEARER]; //Importante Para el Acceso

$pluto_path=json_decode(file_get_contents('https://boot.pluto.tv/v4/start?clientModelNumber=ASUS_I005DA&&clientID=9b5b806f-3820-45d5-b07e-f339e3c1ca9e_7dd602c0b00a2ab9&appName=androidmobile&appVersion=5.10.0'),true)['servers'];
$stitcher=$pluto_path['stitcher'].'/v2';//https://cfd-v4-service-channel-stitcher-use1-1.prd.pluto.tv
$stitcherDash=$pluto_path['stitcherDash']; //https://cfd-v4-service-stitcher-dash-use1-1.prd.pluto.tv
$channels_path=$pluto_path['channels']; //https://service-channels.clusters.pluto.tv
$server = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}";

$url = $channels_path."/v2/guide/channels?offset=&limit=0&sort=&channelIds="; //https://service-channels.clusters.pluto.tv/v2/guide/channels?offset=&limit=0&sort=&channelIds=
                                                                                     
$response = cUrlGetData($url, $headers);

if (isset($_GET['play_live']) && !empty($_GET['play_live'])) {
    $url_path = preg_replace('/master\.m3u8$/', '', $_GET['play_live']);
    
    // Obtener datos de la URL proporcionada
    $playlist_data = cUrlGetData($_GET['play_live'],$headers);
    
    if ($playlist_data !== false && !empty($playlist_data)) {
        // Expresión regular para encontrar las líneas con el patrón específico
        $regex = '/(\d+\/playlist\.m3u8.*)/';

        // Usar preg_match_all para encontrar todas las coincidencias
        preg_match_all($regex, $playlist_data, $matches);
        
        if (!empty($matches[0])) {
            // Añadir $url_path a cada coincidencia
            $updated_matches = array_map(function($match) use ($url_path) {
                return $url_path . $match;
            }, $matches[0]);

            // Verificar cada URL y redirigir si es válida
            foreach ($updated_matches as $url) {
                if (cUrlGetData($url, $headers,$checkUrl=$url)) {
                    echo cUrlGetData($url, $headers);
                    exit; // Detener la ejecución después de la redirección
                }
            }
        } else {
            echo "No se pudieron obtener los datos de la lista de reproducción.";
        }
        exit;
    }
}
echo '#EXTM3U'.PHP_EOL;
    if ($response !== FALSE) {
        $jsonData = json_decode($response, true)['data'];
            if ($jsonData !== null) {

                    foreach($jsonData as $channel)
                        {
                            $id= $channel['id'];//5dcb62e63d4d8f0009f36881
                            $name=$channel['name'];//Pluto TV Cine Acción
                            $number=$channel['number'];//21
                            $stitched_m3u8=$channel['stitched']['path'];//stitch/hls/channel/5dcb62e63d4d8f0009f36881/master.m3u8
                            $images=$channel['images'][0]['url'];//https://images.pluto.tv/channels/5dcb62e63d4d8f0009f36881/colorLogoPNG.png
                            
                            echo '#EXTINF:-1 "tvg-name="'.$title.'" tvg-logo="'.$images.'",'.$name.PHP_EOL;
                            echo $server."?play_live=".$stitcher.$stitched_m3u8.PHP_EOL;
                        }
               
            } else {
                echo "Error al decodificar JSON.";
            }
        } else {
        echo "Error al realizar la solicitud.";
    }
?>
