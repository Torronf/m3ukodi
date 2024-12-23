<?php
//*****************************************************
// Cortesía de:"@M3uKodi Telegram Group"
// Fecha : 10/12/2024
// WebSite:https://www.m3ukodi.com
// Mail:m3ukodi@m3ukodi.com
// Donaciones:https://paypal.me/m3ukodi?locale.x=es_XC
//**************************************
//https://github.com/dtankdempse/free-iptv-channels
// Configuracion de reporte y registro de errores
error_reporting(E_ERROR | E_WARNING | E_PARSE); // Mostrar solo errores, advertencias y errores de analisis
ini_set("log_errors", 1); // Habilitar el registro de errores
ini_set("error_log", __DIR__ . "/errores.log"); // Especificar el archivo de registro de errores
ini_set("display_errors", 1); // Mostrar errores en la salida

// Configuracion de límites y tiempos de ejecucion
@ini_set("memory_limit", "1024M"); // Establecer límite de memoria a 1024M
@ini_set('upload_max_filesize', '500M'); // Límite maximo de tamaño de archivo para subir a 500M
@ini_set('post_max_size', '5000M'); // Límite maximo del tamaño de datos POST a 5000M
@ini_set('max_input_time', 300); // Tiempo maximo de entrada permitido
@ini_set('max_execution_time', 0); // Tiempo maximo de ejecucion del script (0 significa sin límite)
@ini_set('output_buffering', 'Off'); // Desactivar el almacenamiento en bufer de salida
@ini_set('implicit_flush', 1); // Activar el vaciado implícito
@ini_set('zlib.output_compression', 0); // Desactivar la compresion de salida zlib
@ini_set('default_socket_timeout', 20); // Establecer tiempo de espera predeterminado del socket a 20 segundos

set_time_limit(60 * 5); // Establecer límite de tiempo de ejecucion del script a 5 minutos
ignore_user_abort(true); // Ignorar la desconexion del usuario
clearstatcache(); // Limpiar la cache de estado de archivos

// Iniciar una nueva sesion y destruir cualquier sesion anterior
session_start();
session_unset();
session_destroy();

// Configuracion de encabezados HTTP
header("X-Robots-Tag: noindex, nofollow", true); // Indicar a los robots que no indexen ni sigan
header("Content-Type: text/plain; charset=UTF-8"); // Establecer el tipo de contenido como texto plano con codificacion UTF-8
header("Expires: Mon, 20 Dec 1998 01:00:00 GMT"); // Establecer fecha de caducidad
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // Fecha de ultima modificacion
header("Cache-Control: no-cache, must-revalidate"); // Control de cache
header("Pragma: no-cache"); // Desactivar el almacenamiento en cache
header('Access-Control-Allow-Origin: *'); // Permitir acceso a todos los orígenes
header('Access-Control-Allow-Headers: origin,range,accept,accept-encoding,referer,content-type, SOAPAction,X-AxDRM-Message'); // Permitir estos encabezados
header('Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST'); // Permitir estos metodos
header('Access-Control-Expose-Headers: server,range,content-range,content-length,content-type'); // Exponer estos encabezados

// Definir rutas y variables globales
define('PLAYLIST_PATH', 'playlist.m3u8');
define('EPG_PATH', 'epg.xml');
define('CLEAR_CACHE_PATH', 'clear_cache');
define('APP_URL', 'https://tubitv.com/live'); 
define('EPG_URL', 'https://tubitv.com/oz/epg/programming');
define('CACHE_TIME', 300); // 5 minutos por defecto
define('BASE_PATH',dirname($_SERVER['PHP_SELF'])); // Define Root Directorio
define('SERVER', "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");

// Ruta de los directorios y el archivo .htaccess (pueden cambiarse si lo deseas)
$cache_dir = __DIR__ . '/cache';
$htaccess_path = __DIR__ . '/.htaccess'; // Ruta donde se guardará el archivo .htaccess

// Array con los directorios a crear
$directories = [$cache_dir];

// Crear directorios si no existen
foreach ($directories as $dir) {
    if (!file_exists($dir)) {
        // Intentar crear el directorio y manejar cualquier error
        if (!mkdir($dir, 0777, true)) {
            // Mostrar un mensaje de error si no se puede crear el directorio
            $error = error_get_last();
            die('Error al crear el directorio "' . $dir . '": ' . $error['message']);
        }
    }
}

// Verificar si el archivo .htaccess no existe y ejecuta la función para crearlo
if (!file_exists($htaccess_path)) {
    create_htaccess(); // Llama a la función para crear el archivo .htaccess
}
// Función flusher() para manejar correctamente el buffering
function flusher($output = null, $flush = true) {
    // Si hay datos en el buffer, los vaciamos y reiniciamos el buffer
    if (ob_get_length() > 0) {
        ob_end_flush();  // Finalizar el buffer de salida
        flush();         // Liberar el contenido del buffer
        ob_flush();      // Vaciar el buffer
        ob_start();      // Reiniciar el buffer
    }

    // Si se pasa contenido, lo mostramos y opcionalmente lo vaciamos
    if (!is_null($output)) {
        echo $output;  // Imprimir el contenido
        if ($flush) {
            ob_end_flush();  // Finalizar el buffer de salida
            flush();         // Liberar el contenido del buffer
            ob_flush();      // Vaciar el buffer
            ob_start();      // Reiniciar el buffer
        }
    }
}
function create_htaccess() {
    $htaccess_path = __DIR__ . '/.htaccess'; // Ruta donde se guardara el archivo .htaccess (en el directorio actual)
    $base_path = dirname($_SERVER['PHP_SELF']);
    
    // Verifica si el archivo .htaccess ya existe
    if (!file_exists($htaccess_path)) {
        // Contenido que quieres que tenga el archivo .htaccess
        $htaccess_content = <<<EOT
                        RewriteEngine On
                        RewriteBase $base_path
                        RewriteRule ^(?!.*\.php$)(.*)$ $base_path/index.php [QSA,L]
                    EOT;

        // Escribe el contenido al archivo .htaccess
        file_put_contents($htaccess_path, $htaccess_content);

        echo "El archivo .htaccess ha sido creado exitosamente.\n";
    } else {
        echo "El archivo .htaccess ya existe.\n";
    }
}
function get_id_ch() {

	// Descargar el archivo comprimido
	$response = @file_get_contents(APP_URL);
	if ($response === FALSE) {
		error_log("Error al obtener datos de " . APP_URL);
		die("Error al obtener datos de la API.");
	}
	
	// Expresión regular para extraer solo el JSON válido dentro de window.__data
	preg_match('/window\.__data\s*=\s*(\{.*\})\s*;\s*<\/script>/s',  $response, $matches);

	// Si encontramos el JSON en el script
	if (isset($matches[1])) {
		// El contenido del JSON extraído
		$jsonString = $matches[1];

		// Limpiar el JSON de las palabras 'undefined' y convertirlas en 'null'
		$jsonString = str_replace('undefined', 'null', $jsonString);
		// Reemplazar todas las instancias de `new Date("...")` por solo las fechas (sin la parte de `new Date(...)`)
		$jsonString = preg_replace('/new Date\("(.*?)"\)/', '"$1"', $jsonString);

		// Decodificar el JSON
		$data = json_decode($jsonString, true);


		// Procesar los datos EPG
		if (!isset($data['epg']['contentIdsByContainer'])) {
			return "Error: Invalid EPG data structure";
		}

		$epg = $data['epg'];
		$contentIdsByContainer = $epg['contentIdsByContainer'];
		$skipSlugs = ['favorite_linear_channels', 'recommended_linear_channels', 'featured_channels', 'recently_added_channels'];

		$channelData = [];
        foreach ($contentIdsByContainer as $key => $items) {
            foreach ($items as $item) {
                if (!isset($item['container_slug'], $item['contents'], $item['name'])) {
                    error_log("Elemento inválido en key $key: falta container_slug, contents o name");
                    continue;
                }

                if (!in_array($item['container_slug'], $skipSlugs)) {
                    // Agrupar los IDs bajo el nombre correspondiente
                    if (!isset($channelData[$item['name']])) {
                        $channelData[$item['name']] = [];
                    }
                    $channelData[$item['name']] = array_merge($channelData[$item['name']], $item['contents']);
                }
            }
        }

	}

	return ($channelData);
}
function new_cache($params) {
    global $cache_dir;

    // Validar o crear el directorio de caché
    if (!is_dir($cache_dir)) {
        if (!mkdir($cache_dir, 0755, true)) {
            error_log("Error al crear el directorio de caché: $cache_dir");
            return [];
        }
    }

    // Ruta de la caché (usando la URL codificada en base64)
    $cache_path = $cache_dir . '/' . base64_encode(APP_URL);

    // Obtener los IDs de los canales
    $id_channels = get_id_ch();
    if (empty($id_channels)) {
        error_log("No se obtuvieron canales. No se creará el archivo de caché.");
        return [];
    }

    $groupSize = 187; // Tamaño de los grupos
    $epgData = [];

    foreach ($id_channels as $groupName => $groupContents) {
        $groupedChannelIds = array_chunk($groupContents, $groupSize);

        foreach ($groupedChannelIds as $chunk) {
            $chunkParams = ["content_id" => implode(",", $chunk)];
            try {
                $api_data = file_get_contents(EPG_URL . "?" . http_build_query($chunkParams));
                if ($api_data === false) {
                    throw new Exception("Respuesta vacía");
                }
            } catch (Exception $e) {
                error_log("Error al obtener datos de EPG para el grupo: " . implode(",", $chunk) . " - " . $e->getMessage());
                continue;
            }

            $jsonData = json_decode($api_data, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                error_log("Error al decodificar JSON: " . json_last_error_msg());
                continue;
            }

            foreach ($jsonData['rows'] as &$row) {
                $row['group'] = $groupName;
            }
            $epgData = array_merge($epgData, $jsonData['rows']);
            unset($chunk, $jsonData);
            gc_collect_cycles();
        }
    }

    if (empty($epgData)) {
        error_log("No se obtuvieron datos EPG. No se creará el archivo de caché.");
        return [];
    }

    // Guardar datos en la caché
    $saveResult = file_put_contents($cache_path, json_encode($epgData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    if ($saveResult === false) {
        error_log("Error al guardar el archivo de caché en: $cache_path");
        return [];
    }

    return $epgData;
}

function serve_playlist($params) {

    global $cache_dir;

    // Ruta de la cache (usando la URL codificada en base64)
    $cache_path = $cache_dir . '/' . base64_encode(APP_URL);

    // Inicializar la variable $response
    $channel_data = null;

    // Verificar si ya existe una versión en cache y si no ha caducado
    if (file_exists($cache_path) && (filemtime($cache_path) + CACHE_TIME > time())) {
        // Cargar desde la cache
        $channel_data = json_decode(file_get_contents($cache_path), true);
        
        if ($channel_data === null) {
            // Si el contenido de la caché no es un JSON válido
            error_log("El archivo de caché no contiene un JSON válido");
            $channel_data = null;
        }
    }

    // Si no hay respuesta o la caché ha caducado, vuelve a cargar la lista de canales
    if ($channel_data === null) {
        // Aquí llamarías a la función que recarga el contenido (por ejemplo, `new_cache`), para generar un nuevo JSON
        $channel_data = new_cache($params);
    }

    // Recorremos los datos del canal
    foreach ($channel_data as $channel) {
       
        // Obtener valores de cada canal
        $title = !empty($channel['title']) ? $channel['title'] : 'Unknown Title';
        $lang = !empty($channel['lang']) ? implode(", ", $channel['lang']) : 'Unknown Language';
        $content_id = !empty($channel['content_id']) ? $channel['content_id'] : 'Unknown ID';
        $thumbnail = !empty($channel['images']['thumbnail'][0]) ? $channel['images']['thumbnail'][0] : 'https://tinyurl.com/logom3ukodi';
        $category=!empty($channel['group']) ? $channel['group'] : '';

        // Se establece el logo (por si está vacío o no se proporciona)
        $tvg_logo = $thumbnail;
        $group_logo = "https://www.google.com/s2/favicons?domain=tubitv.com&sz=256";

        // Definir el número de canal si está habilitado
        $chno = isset($params['start_chno']) ? 'tvg-chno="' . $params['start_chno'] . '"' : 'tvg-chno="' . $content_id . '"';

        // Si el número de canal está definido, incrementarlo
        if (isset($params['start_chno'])) {
            $params['start_chno']++;
        }

        // Construcción del archivo M3U
        $m3u_content = '#EXTM3U billed-msg="Listas Recopiladas por @M3uKOdi Telegram Group.!" refresh="23" x-tvg-url="https://iptv.m3ukodi.com/root/tubitv/epg.xml" list-name="M3UKODI TV" list-image="https://i.imgur.com/DNh8m7a.png"' . "\n";
        $m3u_content .= "#EXTGRP:TUBITV\n";
        $m3u_content .= "#EXTVLCOPT:http-reconnect=true\n"; // Opción de reconexión de VLC
        $m3u_content .= "#EXTINF:-1 channel-id=\"$content_id\" tvg-id=\"$content_id\" tvg-logo=\"$tvg_logo\" group-logo=\"$group_logo\" group-title=\"TUBITV\" $chno,{$category} - {$title} - {$lang}\n";
        $m3u_content .= SERVER . "?ch_id=$content_id\n"; // URL de transmisión

        // Imprimir el contenido del canal
        echo $m3u_content;
    }
}

function get_channel_url($content_id) {
    global $cache_dir;

    // Ruta de la cache (usando la URL codificada en base64)
    $cache_path = $cache_dir . '/' . base64_encode(APP_URL);

    // Verificar si ya existe una versión en caché y si no ha caducado
    if (file_exists($cache_path)) {
        // Cargar desde la caché
        $channel_data = json_decode(file_get_contents($cache_path), true);

        if ($channel_data === null) {
            // Si el contenido de la caché no es un JSON válido
            error_log("El archivo de caché no contiene un JSON válido");
            return null;
        }

        // Buscar el canal por content_id
        foreach ($channel_data as $channel) {

            if (isset($channel['content_id']) && $channel['content_id'] == $content_id) {

                if (isset($channel['video_resources'][0]['manifest']['url'])) {
                    return $channel['video_resources'][0]['manifest']['url'];
                } else {
                    return null; // Si no se encuentra la URL del manifiesto
                }
            }
        }
    }

    return null; // Si no se encuentra el canal
}

function createEPGXML() {
    global $cache_dir;

    // Ruta de la caché (usando la URL codificada en base64)
    $cache_path = $cache_dir . '/' . base64_encode(APP_URL);
    $cache_expiry = 3600; // 1 hora
    $xml_output_path = $cache_dir . '/epg_output.xml'; // Ruta donde se guardará el XML

    // Verificar si ya existe una versión en caché válida
    if (file_exists($cache_path) && (time() - filemtime($cache_path) < $cache_expiry)) {
        // Cargar desde la caché
        $epgData = json_decode(file_get_contents($cache_path), true);
        if ($epgData === null) {
            error_log("El archivo de caché no contiene un JSON válido.");
            return null;
        }
    } else {
        // Regenerar los datos en caché
        error_log("El archivo de caché no existe o está vacío. Intentando regenerar...");
        $epgData = new_cache([]); // Intentar regenerar los datos en caché
        if (empty($epgData)) {
            error_log("No se pudieron regenerar los datos de caché.");
            return null;
        }

        // Guardar datos regenerados en la caché
        file_put_contents($cache_path, json_encode($epgData));
    }

    try {
        // Crear la raíz del XML
        $xml = new SimpleXMLElement("<tv></tv>");
        $xml->addAttribute("generator-info-name", "M3uKodi");

        foreach ($epgData as $station) {
            // Validar campos necesarios del canal
            if (empty($station['content_id']) || empty($station['title'])) {
                error_log("Faltan datos necesarios para procesar un canal.");
                continue;
            }

            // Crear elemento del canal
            $channel = $xml->addChild("channel");
            $channel->addAttribute("id", htmlspecialchars($station['content_id']));
            $channel->addChild("display-name", htmlspecialchars($station['title']));

            // Agregar imagen del canal (si está disponible)
            if (!empty($station['images']['thumbnail'][0])) {
                $channel->addChild("icon")->addAttribute("src", htmlspecialchars($station['images']['thumbnail'][0]));
            }

            // Procesar programas de la estación
            if (!empty($station['programs'])) {
                foreach ($station['programs'] as $program) {
                    // Validar tiempos de inicio y fin
                    if (empty($program['start_time']) || empty($program['end_time'])) {
                        error_log("Faltan tiempos de inicio o fin en un programa.");
                        continue;
                    }

                    $start = date("YmdHis O", strtotime($program['start_time']));
                    $end = date("YmdHis O", strtotime($program['end_time']));

                    // Crear elemento del programa
                    $programme = $xml->addChild("programme");
                    $programme->addAttribute("channel", htmlspecialchars($station['content_id']));
                    $programme->addAttribute("start", $start);
                    $programme->addAttribute("stop", $end);

                    // Agregar título del programa
                    if (!empty($program['title'])) {
                        $programme->addChild("title", htmlspecialchars($program['title']));
                    }

                    // Agregar descripción del programa
                    if (!empty($program['description'])) {
                        $programme->addChild("desc", htmlspecialchars($program['description']));
                    }

                    // Agregar imagen del programa (si está disponible)
                    if (!empty($program['images']['landscape'][0])) {
                        $programme->addChild("icon")->addAttribute("src", htmlspecialchars($program['images']['landscape'][0]));
                    }
                }
            }
        }
        function formatXML($xmlContent) {
            // Crear un nuevo objeto DOMDocument
            $dom = new DOMDocument('1.0', 'UTF-8');
            $dom->preserveWhiteSpace = false; // Eliminar espacios en blanco innecesarios
            $dom->formatOutput = true; // Habilitar salida formateada

            // Cargar el XML generado por SimpleXMLElement
            $dom->loadXML($xmlContent);

            // Devolver el XML formateado como string
            return $dom->saveXML();
        }
        // Convertir el XML en string
        $xmlContent = formatXML($xml->asXML());

        // Guardar el contenido XML en un archivo local
        if (file_put_contents($xml_output_path, $xmlContent) === false) {
            error_log("No se pudo guardar el archivo XML en $xml_output_path.");
            return null;
        }

        // Retornar el contenido XML como string
        return $xmlContent;
    } catch (Exception $e) {
        error_log("Error al generar el XML: " . $e->getMessage());
        return null;
    }
}



// Limpiar cache
function clear_cache() {
    global $cache_dir;
    array_map('unlink', glob("$cache_dir/*"));
    echo "Cache cleared\n";
}

function mostrarInformacionDeUso() {
    // Encabezado de respuesta HTTP 404
    header("HTTP/1.0 404 Not Found");
    echo "Codigo: 404 Not Found\n\n";

    // Información de ejemplo de uso
    echo "Ejemplo de Uso:\n";
    echo "Las siguientes solicitudes podrían funcionar:\n\n";
    echo "  " . SERVER . "playlist.m3u8\n";
    echo "  " . SERVER . "epg.xml\n";
    echo "  " . SERVER . "clear_cache\n\n";
}
// Funcion para manejar las solicitudes HTTP

function handle_request() {
    // Obtener la URL completa (protocolo, dominio y ruta)
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $params = [];
    parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $params);

    // Revisar si el parámetro 'content_id' está en la cadena de consulta (query string)
    if (isset($params['ch_id'])) {
        $ch_id = $params['ch_id'];  // Obtener el 'content_id' de los parámetros de la consulta
       $stream_url = get_channel_url($ch_id);

        if ($stream_url !== null) {
            header('Content-Type: video/MP2T');
            header("Location: " . rtrim($stream_url));
            exit;
        } else {
            echo "No se encontró la URL de transmisión para el canal.";
            exit;
        }
    }
    
    // Asegurarse de que las rutas coincidan correctamente
    switch (true) {
        case strpos($path, BASE_PATH . '/' . PLAYLIST_PATH) === 0: //http://tu-dominio.com/playlist.m3u8?content_id=618762
            serve_playlist($params);
            break;
        case strpos($path, BASE_PATH . '/' . EPG_PATH) === 0: //http://tu-dominio.com/epg.xml?regions=us
            echo createEPGXML($params);
            break;
        case strpos($path, BASE_PATH . '/' . CLEAR_CACHE_PATH) === 0: //http://tu-dominio.com/clear_cache
            clear_cache();
            break;
        default:
            mostrarInformacionDeUso(SERVER);
            break;
    }
}

// Llamar al enrutador para manejar la solicitud
handle_request();
