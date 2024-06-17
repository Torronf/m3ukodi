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

    $ch_guide = curl_exec($ch);
    $error = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($error) {
        echo $error;
    } elseif ($httpCode === 200) {
        return $ch_guide;
    }

    // Extraer el tamaño del Archivo
    if ($getRemoteFilesize && !empty($getRemoteFilesize)) {
        return $size;
    }

    // Extraer cookies del encabezado de respuesta solo si $cookie no es NULL
    if ($cookie && !empty($cookie)) {
        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $ch_guide, $matches);
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

    if ($ch_guide) return $ch_guide; else return FALSE;
}
$pluto_boot='https://boot.pluto.tv/v4/start?appName=androidmobile&appVersion=5.10.0&clientID=9b5b806f-3820-45d5-b07e-f339e3c1ca9e_7dd602c0b00a2ab9&clientModelNumber=ASUS_I005DA&deviceVersion=9_28&clientDeviceType=4&deviceModel=ASUS_I005DA&deviceType=android%2CAsus%2Cmobile&deviceMake=Asus&isClientDNT=false&channelCount=0&serverSideAds=false';

$pluto_path = json_decode(cUrlGetData($pluto_boot),true);

// Verificar si los datos fueron obtenidos correctamente
if (!$pluto_path) {
    die("Error: No se pudieron obtener los datos de Pluto TV.");
}

$api=$pluto_path['servers']['api'];// $=> https://api.pluto.tv
$vod=$pluto_path['servers']['vod'];// $=> https://service-vod.clusters.pluto.tv
$stitcher=$pluto_path['servers']['stitcher'];// $=> https://cfd-v4-service-channel-stitcher-use1-1.prd.pluto.tv
$analytics=$pluto_path['servers']['analytics'];// $=> https://sp.pluto.tv
$watchlist=$pluto_path['servers']['watchlist'];// $=> https://service-watchlist-ga.prd.pluto.tv
$search=$pluto_path['servers']['search'];// $=> https://service-media-search.clusters.pluto.tv
$concierge=$pluto_path['servers']['concierge'];// $=> https://service-concierge.clusters.pluto.tv
$preferences=$pluto_path['servers']['preferences'];// $=> 
$campaigns=$pluto_path['servers']['campaigns'];// $=> https://service-campaigns-ga.prd.pluto.tv
$users=$pluto_path['servers']['users'];// $=> https://service-users.clusters.pluto.tv
$recommender=$pluto_path['servers']['recommender'];// $=> https://service-recommender.clusters.pluto.tv
$catalog=$pluto_path['servers']['catalog'];// $=> https://service-media-catalog.clusters.pluto.tv
$stitcherDash=$pluto_path['servers']['stitcherDash'];// $=> https://cfd-v4-service-stitcher-dash-use1-1.prd.pluto.tv
$pause=$pluto_path['servers']['pause'];// $=> https://service-ad-image-ga.prd.pluto.tv
$carousel=$pluto_path['servers']['carousel'];// $=> https://service-carousel-builder-ga.prd.pluto.tv
$features=$pluto_path['servers']['features'];// $=> https://service-features-ga.prd.pluto.tv
$hub=$pluto_path['servers']['hub'];// $=> https://service-hub-builder-ga.prd.pluto.tv
$stitcherParams=$pluto_path['stitcherParams'];//advertisingId=&appName=....
$server = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}";
$version='v2';
$channels=$pluto_path['servers']['channels'];// $=> https://service-channels.clusters.pluto.tv
$url_channel = $channels . "/" . $version . "/guide/channels?channelIds=&offset=0&limit=1000&sort=number%3Aasc";

$all_sessionToken = [
    'USA' => $pluto_path['sessionToken'],
    'LATAM'=>'eyJhbGciOiJIUzI1NiIsImtpZCI6ImM1MTI1YmM0LWUxZjYtNDNkYi04MjE0LTRlM2I2MzQ0N2NhZSIsInR5cCI6IkpXVCJ9.eyJzZXNzaW9uSUQiOiJjNDZiMTkzMS0yY2JjLTExZWYtYTlhNi1lZTYyZGVlYzU2NTkiLCJjbGllbnRJUCI6IjE3OS41Mi45LjI1MSIsImNpdHkiOiJTYWJhbmEgRGUgTGEgTWFyIiwicG9zdGFsQ29kZSI6IjI1MTAwIiwiY291bnRyeSI6IkRPIiwiZG1hIjowLCJhY3RpdmVSZWdpb24iOiJWRSIsImRldmljZUxhdCI6MTkuMDQ5OTk5MjM3MDYwNTQ3LCJkZXZpY2VMb24iOi02OS4zODk5OTkzODk2NDg0NCwicHJlZmVycmVkTGFuZ3VhZ2UiOiJlcyIsImRldmljZVR5cGUiOiJhbmRyb2lkLEFzdXMsbW9iaWxlIiwiZGV2aWNlVmVyc2lvbiI6IjlfMjgiLCJkZXZpY2VNYWtlIjoiQXN1cyIsImRldmljZU1vZGVsIjoiQVNVU19JMDA1REEiLCJhcHBOYW1lIjoiYW5kcm9pZG1vYmlsZSIsImFwcFZlcnNpb24iOiI1LjEwLjAiLCJjbGllbnRJRCI6IjliNWI4MDZmLTM4MjAtNDVkNS1iMDdlLWYzMzllM2MxY2E5ZV83ZGQ2MDJjMGIwMGEyYWI5IiwiY21BdWRpZW5jZUlEIjoiIiwiaXNDbGllbnRETlQiOmZhbHNlLCJ1c2VySUQiOiIiLCJsb2dMZXZlbCI6IkRFRkFVTFQiLCJ0aW1lWm9uZSI6IkFtZXJpY2EvU2FudG9fRG9taW5nbyIsInNlcnZlclNpZGVBZHMiOmZhbHNlLCJlMmVCZWFjb25zIjpmYWxzZSwiZmVhdHVyZXMiOnsibXVsdGlQb2RBZHMiOnsiZW5hYmxlZCI6dHJ1ZX19LCJmbXNQYXJhbXMiOnsiZndWY0lEMiI6IjliNWI4MDZmLTM4MjAtNDVkNS1iMDdlLWYzMzllM2MxY2E5ZV83ZGQ2MDJjMGIwMGEyYWI5IiwiZndWY0lEMkNvcHBhIjoiOWI1YjgwNmYtMzgyMC00NWQ1LWIwN2UtZjMzOWUzYzFjYTllXzdkZDYwMmMwYjAwYTJhYjkiLCJjdXN0b21QYXJhbXMiOnsiZm1zX2xpdmVyYW1wX2lkbCI6IiIsImZtc19lbWFpbGhhc2giOiIiLCJmbXNfc3Vic2NyaWJlcmlkIjoiIiwiZm1zX2lmYSI6IiIsImZtc19pZGZ2IjoiIiwiZm1zX3VzZXJpZCI6IjliNWI4MDZmLTM4MjAtNDVkNS1iMDdlLWYzMzllM2MxY2E5ZV83ZGQ2MDJjMGIwMGEyYWI5IiwiZm1zX3ZjaWQydHlwZSI6InVzZXJpZCIsImZtc19yYW1wX2lkIjoiIiwiZm1zX2hoX3JhbXBfaWQiOiIiLCJmbXNfYmlkaWR0eXBlIjoiIiwiX2Z3XzNQX1VJRCI6IiIsImZtc19ydWxlaWQiOiIxMDAwMCwxMDAwOSwxMDAwMyJ9fSwiaXNzIjoiYm9vdC5wbHV0by50diIsInN1YiI6InByaTp2MTpwbHV0bzpkZXZpY2VzOlZFOk9XSTFZamd3Tm1ZdE16Z3lNQzAwTldRMUxXSXdOMlV0WmpNek9XVXpZekZqWVRsbFh6ZGtaRFl3TW1Nd1lqQXdZVEpoWWprPSIsImF1ZCI6IioucGx1dG8udHYiLCJleHAiOjE3MTg3MjM4NzMsImlhdCI6MTcxODYzNzQ3MywianRpIjoiYjMwMTA2MGEtOWYzYS00NWY4LTg1NmUtYzdiOWU3ZjUzNTQ2In0.hu4nKAMNj1LE22k0BUoY34LvoQDY-aPzkNcYhJk7fdY',
    'SPAIN'=>'eyJhbGciOiJIUzI1NiIsImtpZCI6ImM1MTI1YmM0LWUxZjYtNDNkYi04MjE0LTRlM2I2MzQ0N2NhZSIsInR5cCI6IkpXVCJ9.eyJzZXNzaW9uSUQiOiJlYTliMTM1OC0yYzlmLTExZWYtYTY0Ny1hZTlkODU5ZDlmODgiLCJjbGllbnRJUCI6Ijc3LjI3LjI1NC4xMTYiLCJjaXR5IjoiTHVnbyIsInBvc3RhbENvZGUiOiIyNzAwMSIsImNvdW50cnkiOiJFUyIsImRtYSI6MCwiYWN0aXZlUmVnaW9uIjoiRVMiLCJkZXZpY2VMYXQiOjQzLjAwOTk5ODMyMTUzMzIsImRldmljZUxvbiI6LTcuNTU5OTk5OTQyNzc5NTQxLCJwcmVmZXJyZWRMYW5ndWFnZSI6ImVuIiwiZGV2aWNlVHlwZSI6IndlYiIsImRldmljZVZlcnNpb24iOiIxMjUuMC4wIiwiZGV2aWNlTWFrZSI6ImNocm9tZSIsImRldmljZU1vZGVsIjoid2ViIiwiYXBwTmFtZSI6IndlYiIsImFwcFZlcnNpb24iOiI5LjIuMS1hMmQwOTYwZTk4YTdhMDYyOWQzZDViZDRhOTIwYWM4ZTA5MTNjOTZmIiwiY2xpZW50SUQiOiJiNTRjMTY2NS1jYmJiLTQ5ZjctYjEzZS1kN2JjNzgwNjQ3ZjkiLCJjbUF1ZGllbmNlSUQiOiIiLCJpc0NsaWVudEROVCI6ZmFsc2UsInVzZXJJRCI6IiIsImxvZ0xldmVsIjoiREVGQVVMVCIsInRpbWVab25lIjoiRXVyb3BlL01hZHJpZCIsInNlcnZlclNpZGVBZHMiOmZhbHNlLCJlMmVCZWFjb25zIjpmYWxzZSwiZmVhdHVyZXMiOnsibXVsdGlQb2RBZHMiOnsiZW5hYmxlZCI6dHJ1ZX19LCJmbXNQYXJhbXMiOnsiZndWY0lEMiI6ImI1NGMxNjY1LWNiYmItNDlmNy1iMTNlLWQ3YmM3ODA2NDdmOSIsImZ3VmNJRDJDb3BwYSI6ImI1NGMxNjY1LWNiYmItNDlmNy1iMTNlLWQ3YmM3ODA2NDdmOSIsImN1c3RvbVBhcmFtcyI6eyJmbXNfbGl2ZXJhbXBfaWRsIjoiIiwiZm1zX2VtYWlsaGFzaCI6IiIsImZtc19zdWJzY3JpYmVyaWQiOiIiLCJmbXNfaWZhIjoiIiwiZm1zX2lkZnYiOiIiLCJmbXNfdXNlcmlkIjoiYjU0YzE2NjUtY2JiYi00OWY3LWIxM2UtZDdiYzc4MDY0N2Y5IiwiZm1zX3ZjaWQydHlwZSI6InVzZXJpZCIsImZtc19yYW1wX2lkIjoiIiwiZm1zX2hoX3JhbXBfaWQiOiIiLCJmbXNfYmlkaWR0eXBlIjoiIiwiX2Z3XzNQX1VJRCI6IiIsImZtc19ydWxlaWQiOiIxMDAwMCwxMDAwOSwxMDAwMyJ9fSwiZHJtIjp7Im5hbWUiOiJ3aWRldmluZSIsImxldmVsIjoiTDMifSwiaXNzIjoiYm9vdC5wbHV0by50diIsInN1YiI6InByaTp2MTpwbHV0bzpkZXZpY2VzOkVTOllqVTBZekUyTmpVdFkySmlZaTAwT1dZM0xXSXhNMlV0WkRkaVl6YzRNRFkwTjJZNSIsImF1ZCI6IioucGx1dG8udHYiLCJleHAiOjE3MTg3MTE0ODEsImlhdCI6MTcxODYyNTA4MSwianRpIjoiNDFhODNhOTAtOGJjYy00YzkzLWI2YTgtZmQ3NzQ3ZGIwYzg1In0.bal43CKvHMSFjXHXZGKvf0jZxigTr51ZgpQr9NqbSrI'
];
// Obtener la región desde la URL
$region = isset($_GET['region']) ? strtoupper($_GET['region']) : 'ALL';
    // Mostrar información sobre los parámetros si no se especifica una región
    if (empty($region)) {
        echo "Por favor, especifique una región utilizando el parámetro 'region' en la URL. Ejemplos de uso:\n";
        echo " - ".$_SERVER['PHP_SELF']."?region=Latam\n";
        echo " - ".$_SERVER['PHP_SELF']."?region=USA\n";
        echo " - ".$_SERVER['PHP_SELF']."?region=Spain\n";
        echo " - ".$_SERVER['PHP_SELF']."?region=all\n";
        exit;
    }
// Realizar las solicitudes
$ch_guide_all = [];

    // Realizar solicitudes basadas en la región
    if ($region == 'ALL') {
        foreach ($all_sessionToken as $regionKey => $sessionToken) {
            $headers = [
                'Accept: application/json',
                'authorization: Bearer ' . $sessionToken,
                'origin: https://pluto.tv',
                'referer: https://pluto.tv/',
                'user-agent: Xskdnjoksdna34nm',
            ];
            
            $response = cUrlGetData($url_channel, $headers);
            $ch_guide_all[$regionKey] = json_decode($response, true);
        }
    } else {
        if (array_key_exists($region, $all_sessionToken)) {
            $headers = [
                'Accept: application/json',
                'authorization: Bearer ' . $all_sessionToken[$region],
                'origin: https://pluto.tv',
                'referer: https://pluto.tv/',
                'user-agent: Xskdnjoksdna34nm',
            ];
        
            $response = cUrlGetData($url_channel, $headers);
            $ch_guide_all[$region] = json_decode($response, true);
        } else {
            echo "Región no válida.";
            exit;
        }
    }

// Unir los arrays resultantes
$ch_guide = [];
    foreach ($ch_guide_all as $regionKey => $regionData) {
        if (isset($regionData['data'])) {
            // Agregar cada canal al array $ch_guide, manteniendo la clave de región
            foreach ($regionData['data'] as $channel) {
                // Agregar la clave de región al canal para mantener la asociación
                $channel['region'] = $regionKey;
                $ch_guide[] = $channel;
            }
        }
    }

    if (isset($_GET['play_live']) && !empty($_GET['play_live'])) {
        
        $headers_host=['Host: cfd-v4-service-channel-stitcher-use1-1.prd.pluto.tv'];
        $headers = array_merge($headers, $headers_host);

        // Obtener y descifrar el enlace
        $url_link = $_GET['play_live'] . "?" . $stitcherParams;
        $host = parse_url($url_link, PHP_URL_SCHEME) . '://' . parse_url($url_link, PHP_URL_HOST);
        $url_path = preg_replace('/master\.m3u8.*$/', '', $url_link);
        
        // Expresión regular para encontrar las líneas con el patrón específico
        $regex = '/(\d+\/playlist\.m3u8.*)/';
        
        // Obtener datos de la URL proporcionada
        $playlist_data = cUrlGetData($url_link, $headers);

        
        if ($playlist_data !== false && !empty($playlist_data)) {
            // Manejo de redirecciones
            if (preg_match('/Found. Redirecting to (.+)/', $playlist_data, $matches)) {
                $context = stream_context_create([
                    'http' => [
                        'method' => 'GET',
                        'header' => implode("\r\n", $headers),
                    ]
                ]);
                
                // Hacer la solicitud y obtener los headers de la respuesta
                $headers_response = get_headers($url_link, 1, $context);
                $url_location = $host . $headers_response['location'][0];

                $hls_get_data = $host . $headers_response['location'];
                $hls_data = cUrlGetData($hls_get_data, $headers);

                // Usar preg_match_all para encontrar todas las coincidencias
                preg_match_all($regex, $hls_data, $matches);

                if (!empty($matches[0])) {
                    // Añadir $url_path a cada coincidencia
                    $updated_matches = array_map(function($match) use ($host) {
                        return $host . $match;
                    }, $matches[0]);

                    // Verificar cada URL y redirigir si es válida
                    foreach ($updated_matches as $url) {
                        if (cUrlGetData($url, $headers, $url)) {
                            echo cUrlGetData($url, $headers);
                            exit; // Detener la ejecución después de la redirección
                        }
                    }
                } else {
                    echo "No se pudieron obtener los datos de la lista de reproducción.";
                    exit;
                }
            } else {
                // Usar preg_match_all para encontrar todas las coincidencias
                preg_match_all($regex, $playlist_data, $matches);

                if (!empty($matches[0])) {
                    // Añadir $url_path a cada coincidencia
                    $updated_matches = array_map(function($match) use ($url_path) {
                        return $url_path . $match;
                    }, $matches[0]);

                    // Verificar cada URL y redirigir si es válida
                    foreach ($updated_matches as $url) {
                        if (cUrlGetData($url, $headers, $url)) {
                            echo cUrlGetData($url, $headers);
                            exit; // Detener la ejecución después de la redirección
                        }
                    }
                } else {
                    echo "No se pudieron obtener los datos de la lista de reproducción.";
                    exit;
                }
            }
        } else {
            echo "No se pudieron obtener datos del enlace proporcionado.";
            exit;
        }
    } 

echo '#EXTM3U'.PHP_EOL;

    if ($ch_guide !== FALSE) {
        foreach($ch_guide as $channel) {

                $id= $channel['id'];//5dcb62e63d4d8f0009f36881
                $name=$channel['name'];//Pluto TV Cine Acción
                $number=$channel['number'];//21
                $region=$channel['region'];
                if (isset($channel['stitched']['paths'])) {
                    $stitched_m3u8 = $channel['stitched']['paths'][0]['path'];
                } else {
                    $stitched_m3u8 = $channel['stitched']['path'];
                };
                $images=$channel['images'][0]['url'];//https://images.pluto.tv/channels/5dcb62e63d4d8f0009f36881/colorLogoPNG.png
                
                $ua='Dalvik/2.1.0 (Linux; U; Android 9; ASUS_I005DA Build/PI)';
                $group_logo = "https://tinyurl.com/logom3ukodi";
                $dirname = pathinfo($stitcher, PATHINFO_DIRNAME);
                echo '#KODIPROP:inputstreamaddon=inputstream.adaptive'.PHP_EOL;
                echo '#KODIPROP:inputstream.adaptive.stream_headers=User-Agent='.$ua.PHP_EOL;
                echo '#EXTGRP:PLUTO TV'.PHP_EOL;
                echo '#EXTVLCOPT:http-referrer='.$dirname.PHP_EOL;
                echo '#EXTVLCOPT:network-caching=1000'.PHP_EOL;
                echo '#EXTVLCOPT:audio-track="es-ES"'.PHP_EOL;
                echo '#EXTVLCOPT:http-user-agent='.$ua.PHP_EOL;
                echo '#EXTVLCOPT--http-reconnect=true'.PHP_EOL;
                echo '#EXTINF:-1 "tvg-name="'.$title.'" tvg-logo="'.$images.'",'.$name.PHP_EOL;
                echo '#EXTINF:-1 type="stream" channelId="'.$id.'", group-title="PLUTO TV" group-logo="'.$group_logo.'"  tvg-logo="'.$images.'","'.$region.'-'.$name.'"'.PHP_EOL;
                echo $server."?play_live=".$stitcher."/".$version.$stitched_m3u8.PHP_EOL;
            }
        } else {
        echo "Error al realizar la solicitud.";
    }
?>
