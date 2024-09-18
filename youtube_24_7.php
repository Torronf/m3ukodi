<?php
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

// Función para obtener la URL del video
    function get_url_video($video_id) {
        $headers = [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36 Edg/128.0.0.0',
            'Accept: application/json',
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

// URL de las listas de reproducción de YouTube
    $urls = [
        'https://www.youtube.com/playlist?list=PLW_5GrNJG1Wgfxb68s1nV8P6JRRJrK25R',
        'https://www.youtube.com/playlist?list=PLW_5GrNJG1WgsSbUoLEEywAfxPh7qTNbU',
        'https://www.youtube.com/playlist?list=PLW_5GrNJG1WhFa9GpuLsUZLahnR-IMRCJ'
    ];
//Extrae Los Video contenidos en la Lista de Youtube, Expresión regular para extraer videoId, miniatura y título directamente del HTML
  $pattern = '/"playlistVideoRenderer":\s*\{\s*"videoId":"(\w+)",.*?"thumbnail":\s*\{\s*"thumbnails":\s*\[\s*\{\s*"url":"([^"]+)",.*?"title":\s*\{\s*"runs":\s*\[\s*\{\s*"text":"([^"]+)"/s';
  
  $videos = [];

// Procesar cada URL
    foreach ($urls as $url) {
        $html = cUrlGetData($url);
    
        if (preg_match_all($pattern, $html, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $videoId = $match[1];
                $thumbnailUrl = $match[2];
                $title = $match[3];
    
                $videos[] = [
                    'videoId' => $videoId,
                    'thumbnail' => $thumbnailUrl,
                    'title' => $title
                ];
            }
        } else {
            echo "No se encontraron datos en $url\n";
        }
    }


// Codificar el array de videos en JSON
    $finalJson = json_encode($videos, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

// Guardar el JSON en un archivo local
    $file = __DIR__ . '/videos_data.json';
    file_put_contents($file, $finalJson);

// Decodificar el JSON
    $data = json_decode($finalJson, true);

// Verificar si la decodificación fue exitosa
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "Error al decodificar JSON.";
        exit;
    }

// Número de repeticiones
    $repeticiones = 1;
    $totalVideos = count($data);
    
    if ($totalVideos === 0) {
        echo "No hay videos para mostrar.";
        exit;
    }

// Imprimir registros aleatorios
    for ($i = 0; $i < $repeticiones; $i++) {
        // Seleccionar un índice aleatorio
        $randomIndex = rand(0, $totalVideos - 1);
        $video = $data[$randomIndex];
        $url_m3u8=get_url_video($video['videoId']);
        
        // Redirigir a la URL de streaming
            if (!empty($url_m3u8)) {
                header("Location: " . $url_m3u8);
                exit;
            } else {
                echo 'Error: No se encontró una URL de streaming válida.';
                exit;
            }
}
?>
