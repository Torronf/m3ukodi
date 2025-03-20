<?php
//*****************************************************
//Cortesia de:"@M3uKodi Telegram Group"
//Fecha : 17/03/2025       
//WebSite:https://www.m3ukodi.com
//Mail:m3ukodi@m3ukodi.com
//Donaciones:https://paypal.me/m3ukodi?locale.x=es_XC
//http://www.softwareishard.com/har/viewer/?inputUrl=
//**************************************
// Configuración de reporte y registro de errores
error_reporting(E_ERROR | E_WARNING | E_PARSE); // Mostrar solo errores, advertencias y errores de análisis
ini_set("log_errors", 1); // Habilitar el registro de errores
ini_set("error_log", __DIR__ . "/errores.log"); // Especificar el archivo de registro de errores
ini_set("display_errors", 1); // Mostrar errores en la salida
// Configuración de límites y tiempos de ejecución
@ini_set("memory_limit", "1024M"); // Establecer límite de memoria a 1024M
@ini_set('upload_max_filesize', '500M'); // Límite máximo de tamaño de archivo para subir a 500M
@ini_set('post_max_size', '5000M'); // Límite máximo del tamaño de datos POST a 5000M
@ini_set('max_input_time', 300); // Tiempo máximo de entrada permitido
@ini_set('max_execution_time', 0); // Tiempo máximo de ejecución del script (0 significa sin límite)
@ini_set('output_buffering', 'Off'); // Desactivar el almacenamiento en búfer de salida
@ini_set('implicit_flush', 1); // Activar el vaciado implícito
@ini_set('zlib.output_compression', 0); // Desactivar la compresión de salida zlib
@ini_set('default_socket_timeout', 20); // Establecer tiempo de espera predeterminado del socket a 20 segundos
set_time_limit(60 * 5); // Establecer límite de tiempo de ejecución del script a 5 minutos
ignore_user_abort(true); // Ignorar la desconexión del usuario
clearstatcache(); // Limpiar la caché de estado de archivos

// Configuración de encabezados HTTP
header("X-Robots-Tag: noindex, nofollow", true); // Indicar a los robots que no indexen ni sigan
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
$cf = basename($_SERVER['SCRIPT_FILENAME'], '.php');

/****************************** START CONFIGURATION ******************************/
$whitelistPatterns = [];
$blacklistPatterns = [];
$forceCORS = false;
$disallowLocal = true;
$anonymize = true;
$startURL = "";
$landingExampleURL ="https://" . $_SERVER["HTTP_HOST"];
/****************************** END CONFIGURATION ******************************/

ob_start("ob_gzhandler");

if (version_compare(PHP_VERSION, "5.4.7", "<")) {
  die("miniProxy requires PHP version 5.4.7 or later.");
}

$requiredExtensions = ["curl", "mbstring", "xml"];
foreach ($requiredExtensions as $requiredExtension) {
  if (!extension_loaded($requiredExtension)) {
    die("miniProxy requires PHP's \"" . $requiredExtension . "\" extension. Please install/enable it on your server and try again.");
  }
}
// Iniciar sesión al inicio del script
session_start();

// Inicializar variables de sesión si no existen
if (!isset($_SESSION['capture_har'])) {
    $_SESSION['capture_har'] = false;
}
if (!isset($_SESSION['base64_text'])) {
    $_SESSION['base64_text'] = false;
}

// Procesar la activación/desactivación de los botones
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['toggle_capture_har'])) {
        $_SESSION['capture_har'] = !$_SESSION['capture_har']; // Alternar el estado
    }
    if (isset($_POST['toggle_base64_text'])) {
        $_SESSION['base64_text'] = !$_SESSION['base64_text']; // Alternar el estado
    }
}

// Función para capturar entradas HAR
function captureHarEntry($url, $method, $requestHeaders, $requestBody, $responseHeaders, $responseBody, $statusCode, $statusText, $startTime, $duration) {
    global $_SESSION; // Usar variable global

    // Solo capturar si $captureHar está activo
    if (!$_SESSION['capture_har']) {
        return; // Salir si la captura HAR no está activada
    }

    // Extraer dominio y path de la URL
    $urlParts = parse_url($url);
    $domain = str_replace('.', '_', strtolower($urlParts['host'])); // Reemplazar . por _ para directorios
    $path = $urlParts['path'] ?? '/';
    $pageName = basename($path);
    $pageName = !empty($pageName) && $pageName != '/' 
        ? preg_replace('/[^a-zA-Z0-9\-_\.]/', '_', $pageName) 
        : 'index';

    // Crear estructura de directorios
    $domainDir = "har/{$domain}";
    if (!is_dir($domainDir)) {
        mkdir($domainDir, 0755, true);
    }

    // Convertir headers a formato HAR (array de objetos)
    $harRequestHeaders = [];
    foreach ($requestHeaders as $name => $value) {
        $harRequestHeaders[] = ["name" => $name, "value" => $value];
    }
    $harResponseHeaders = [];
    foreach ($responseHeaders as $name => $value) {
        $harResponseHeaders[] = ["name" => $name, "value" => $value];
    }

    // Parsear cookies de la solicitud
    $requestCookies = [];
    if (isset($requestHeaders["Cookie"])) {
        foreach (explode("; ", $requestHeaders["Cookie"]) as $cookie) {
            list($name, $value) = explode("=", $cookie, 2);
            $requestCookies[] = [
                "name" => $name,
                "value" => urldecode($value)
            ];
        }
    }

    // Parsear cookies de la respuesta
    $responseCookies = [];
    if (isset($responseHeaders["Set-Cookie"])) {
        foreach ((array)$responseHeaders["Set-Cookie"] as $cookieHeader) {
            $cookieParts = explode(";", $cookieHeader, 2);
            list($name, $value) = explode("=", $cookieParts[0], 2);
            $responseCookies[] = [
                "name" => $name,
                "value" => urldecode($value)
            ];
        }
    }

    // Manejo especial para respuestas 304 Not Modified
    $content = [];
    if ($statusCode != 304) {
			if (empty($_SESSION['base64_text'])) {
				prn($_SESSION);
				$content = [
					"size" => strlen($responseBody),
					"mimeType" => $responseHeaders["Content-Type"] ?? "application/octet-stream",
					"text" => base64_encode($responseBody),
					"encoding" => "base64"
				];
			} else {
				$content = [
					"size" => strlen($responseBody),
					"mimeType" => $responseHeaders["Content-Type"] ?? "text/plain",
					"text" => $responseBody
				];
			}
    }

    // Crear entrada HAR conforme a la especificación
    $harEntry = [
        "startedDateTime" => date("c", $startTime),
        "time" => $duration,
        "request" => [
            "method" => $method,
            "url" => $url,
            "httpVersion" => "HTTP/1.1",
            "cookies" => $requestCookies,
            "headers" => $harRequestHeaders,
            "queryString" => [],
            "postData" => [
                "mimeType" => "application/x-www-form-urlencoded",
                "text" => $requestBody
            ],
            "headersSize" => -1,
            "bodySize" => strlen($requestBody)
        ],
        "response" => [
            "status" => $statusCode,
            "statusText" => $statusText,
            "httpVersion" => "HTTP/1.1",
            "cookies" => $responseCookies,
            "headers" => $harResponseHeaders,
            "content" => $content,
            "redirectURL" => $responseHeaders["Location"] ?? "",
            "headersSize" => -1,
            "bodySize" => strlen($responseBody)
        ],
        "cache" => (object)[],
        "timings" => [
            "send" => 0,
            "wait" => $duration,
            "receive" => 0
        ]
    ];

    // Actualizar archivo HAR
    $harFile = "{$domainDir}/{$pageName}.har";
    $harData = file_exists($harFile) 
        ? json_decode(file_get_contents($harFile), true) 
        : [
            "log" => [
                "version" => "1.2",
                "creator" => [
                    "name" => "miniSniffer HAR Generator",
                    "version" => "1.0"
                ],
                "entries" => []
            ]
        ];
    $harData["log"]["entries"][] = $harEntry;
    file_put_contents($harFile, json_encode($harData, JSON_PRETTY_PRINT));
}

function getHostnamePattern($hostname) {
  return "@^https?://([a-z0-9-]+\.)*" . preg_quote($hostname, "@") . "@i";
}


//Helper function that determines whether to allow proxying of a given URL.
function isValidURL($url) {
  //Validates a URL against the whitelist.
  function passesWhitelist($url) {
    if (count($GLOBALS['whitelistPatterns']) === 0) return true;
    foreach ($GLOBALS['whitelistPatterns'] as $pattern) {
      if (preg_match($pattern, $url)) {
        return true;
      }
    }
    return false;
  }

  //Validates a URL against the blacklist.
  function passesBlacklist($url) {
    foreach ($GLOBALS['blacklistPatterns'] as $pattern) {
      if (preg_match($pattern, $url)) {
        return false;
      }
    }
    return true;
  }

  function isLocal($url) {
    //First, generate a list of IP addresses that correspond to the requested URL.
    $ips = [];
    $host = parse_url($url, PHP_URL_HOST);
    if (filter_var($host, FILTER_VALIDATE_IP)) {
      //The supplied host is already a valid IP address.
      $ips = [$host];
    } else {
      //The host is not a valid IP address; attempt to resolve it to one.
      $dnsResult = dns_get_record($host, DNS_A + DNS_AAAA);
      $ips = array_map(function($dnsRecord) { return $dnsRecord['type'] == 'A' ? $dnsRecord['ip'] : $dnsRecord['ipv6']; }, $dnsResult);
    }
    foreach ($ips as $ip) {
      //Determine whether any of the IPs are in the private or reserved range.
      if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
        return true;
      }
    }
    return false;
  }

  return passesWhitelist($url) && passesBlacklist($url) && ($GLOBALS['disallowLocal'] ? !isLocal($url) : true);
}

//Helper function used to removes/unset keys from an associative array using case insensitive matching
function removeKeys(&$assoc, $keys2remove) {
  $keys = array_keys($assoc);
  $map = [];
  $removedKeys = [];
  foreach ($keys as $key) {
    $map[strtolower($key)] = $key;
  }
  foreach ($keys2remove as $key) {
    $key = strtolower($key);
    if (isset($map[$key])) {
      unset($assoc[$map[$key]]);
      $removedKeys[] = $map[$key];
    }
  }
  return $removedKeys;
}

if (!function_exists("getallheaders")) {
  //Adapted from http://www.php.net/manual/en/function.getallheaders.php#99814
  function getallheaders() {
    $result = [];
    foreach($_SERVER as $key => $value) {
      if (substr($key, 0, 5) == "HTTP_") {
        $key = str_replace(" ", "-", ucwords(strtolower(str_replace("_", " ", substr($key, 5)))));
        $result[$key] = $value;
      }
    }
    return $result;
  }
}

$usingDefaultPort =  (!isset($_SERVER["HTTPS"]) && $_SERVER["SERVER_PORT"] === 80) || (isset($_SERVER["HTTPS"]) && $_SERVER["SERVER_PORT"] === 443);
$prefixPort = $usingDefaultPort ? "" : ":" . $_SERVER["SERVER_PORT"];
//Use HTTP_HOST to support client-configured DNS (instead of SERVER_NAME), but remove the port if one is present
$prefixHost = $_SERVER["HTTP_HOST"];
$prefixHost = strpos($prefixHost, ":") ? implode(":", explode(":", $_SERVER["HTTP_HOST"], -1)) : $prefixHost;

define("PROXY_PREFIX", "http" . (isset($_SERVER["HTTPS"]) ? "s" : "") . "://" . $prefixHost . $prefixPort . $_SERVER["SCRIPT_NAME"] . "?");

//Makes an HTTP request via cURL, using request data that was passed directly to this script.
function makeRequest($url) {
    // Capturar tiempo inicial con microsegundos
    $startTime = microtime(true);

    global $anonymize;
    $user_agent = $_SERVER["HTTP_USER_AGENT"] ?: "Mozilla/5.0 (compatible; miniProxy)";
    $method = $_SERVER["REQUEST_METHOD"];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);

    $browserRequestHeaders = getallheaders();
    $removedHeaders = removeKeys($browserRequestHeaders, ["Accept-Encoding", "Content-Length", "Host", "Origin"]);
    $removedHeaders = array_map("strtolower", $removedHeaders);
    curl_setopt($ch, CURLOPT_ENCODING, "");

    $curlRequestHeaders = [];
    foreach ($browserRequestHeaders as $name => $value) {
        $curlRequestHeaders[] = "$name: $value";
    }
    
    if (!$anonymize) {
        $curlRequestHeaders[] = "X-Forwarded-For: " . $_SERVER["REMOTE_ADDR"];
    }
    
    if (in_array("origin", $removedHeaders)) {
        $urlParts = parse_url($url);
        $port = $urlParts["port"] ?? "";
        $curlRequestHeaders[] = "Origin: {$urlParts["scheme"]}://{$urlParts["host"]}" . ($port ? ":$port" : "");
    }
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, $curlRequestHeaders);

    $requestBody = "";

    switch ($method) {
        case "POST":
            parse_str(file_get_contents("php://input"), $postData);
            unset($postData["miniProxyFormAction"]);
            $requestBody = http_build_query($postData);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $requestBody);
            break;
        case "PUT":
            $requestBody = file_get_contents("php://input");
            curl_setopt($ch, CURLOPT_PUT, true);
            curl_setopt($ch, CURLOPT_INFILE, fopen('data://text/plain,' . $requestBody, 'r'));
            break;
    }

    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);

    $response = curl_exec($ch);
    $responseInfo = curl_getinfo($ch);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $responseHeadersStr = substr($response, 0, $headerSize);
    $responseBody = substr($response, $headerSize);

    // Procesar headers de respuesta
    $responseHeaders = [];
    $headerLines = explode("\r\n", $responseHeadersStr);
    $statusLine = array_shift($headerLines); // Extraer primera línea (status line)
    
    foreach ($headerLines as $header) {
        if (strpos($header, ': ') !== false) {
            list($name, $value) = explode(': ', $header, 2);
            
            // Manejo especial para múltiples Set-Cookie
            if ($name == "Set-Cookie") {
                if (!isset($responseHeaders[$name])) {
                    $responseHeaders[$name] = [];
                }
                $responseHeaders[$name][] = $value;
            } else {
                $responseHeaders[$name] = $value;
            }
        }
    }

    // Extraer statusText
    preg_match("#HTTP/\d\.\d \d+ (.+)#", $statusLine, $matches);
    $statusText = $matches[1] ?? "";

    // Procesar headers de solicitud
    $requestHeaders = [];
    foreach ($curlRequestHeaders as $header) {
        if (strpos($header, ': ') !== false) {
            list($name, $value) = explode(': ', $header, 2);
            $requestHeaders[$name] = $value;
        }
    }

    // Calcular duración en milisegundos
    $duration = round((microtime(true) - $startTime) * 1000);

    // Capturar entrada HAR
    captureHarEntry(
        $url,
        $method,
        $requestHeaders,
        $requestBody,
        $responseHeaders,
        $responseBody,
        $statusCode,
        $statusText,
        $startTime,
        $duration
    );

    return ["headers" => $responseHeadersStr, "body" => $responseBody, "responseInfo" => $responseInfo];
}
//Converts relative URLs to absolute ones, given a base URL.
//Modified version of code found at http://nashruddin.com/PHP_Script_for_Converting_Relative_to_Absolute_URL
function rel2abs($rel, $base) {
  if (empty($rel)) $rel = ".";
  if (parse_url($rel, PHP_URL_SCHEME) != "" || strpos($rel, "//") === 0) return $rel; //Return if already an absolute URL
  if ($rel[0] == "#" || $rel[0] == "?") return $base.$rel; //Queries and anchors
  extract(parse_url($base)); //Parse base URL and convert to local variables: $scheme, $host, $path
  $path = isset($path) ? preg_replace("#/[^/]*$#", "", $path) : "/"; //Remove non-directory element from path
  if ($rel[0] == "/") $path = ""; //Destroy path if relative url points to root
  $port = isset($port) && $port != 80 ? ":" . $port : "";
  $auth = "";
  if (isset($user)) {
    $auth = $user;
    if (isset($pass)) {
      $auth .= ":" . $pass;
    }
    $auth .= "@";
  }
  $abs = "$auth$host$port$path/$rel"; //Dirty absolute URL
  for ($n = 1; $n > 0; $abs = preg_replace(["#(/\.?/)#", "#/(?!\.\.)[^/]+/\.\./#"], "/", $abs, -1, $n)) {} //Replace '//' or '/./' or '/foo/../' with '/'
  return $scheme . "://" . $abs; //Absolute URL is ready.
}

//Proxify contents of url() references in blocks of CSS text.
function proxifyCSS($css, $baseURL) {
  //Add a "url()" wrapper to any CSS @import rules that only specify a URL without the wrapper,
  //so that they're proxified when searching for "url()" wrappers below.
  $sourceLines = explode("\n", $css);
  $normalizedLines = [];
  foreach ($sourceLines as $line) {
    if (preg_match("/@import\s+url/i", $line)) {
      $normalizedLines[] = $line;
    } else {
      $normalizedLines[] = preg_replace_callback(
        "/(@import\s+)([^;\s]+)([\s;])/i",
        function($matches) use ($baseURL) {
          return $matches[1] . "url(" . $matches[2] . ")" . $matches[3];
        },
        $line);
    }
  }
  $normalizedCSS = implode("\n", $normalizedLines);
  return preg_replace_callback(
    "/url\((.*?)\)/i",
    function($matches) use ($baseURL) {
        $url = $matches[1];
        //Remove any surrounding single or double quotes from the URL so it can be passed to rel2abs - the quotes are optional in CSS
        //Assume that if there is a leading quote then there should be a trailing quote, so just use trim() to remove them
        if (strpos($url, "'") === 0) {
          $url = trim($url, "'");
        }
        if (strpos($url, "\"") === 0) {
          $url = trim($url, "\"");
        }
        if (stripos($url, "data:") === 0) return "url(" . $url . ")"; //The URL isn't an HTTP URL but is actual binary data. Don't proxify it.
        return "url(" . PROXY_PREFIX . rel2abs($url, $baseURL) . ")";
    },
    $normalizedCSS);
}

//Proxify "srcset" attributes (normally associated with <img> tags.)
function proxifySrcset($srcset, $baseURL) {
  $sources = array_map("trim", explode(",", $srcset)); //Split all contents by comma and trim each value
  $proxifiedSources = array_map(function($source) use ($baseURL) {
    $components = array_map("trim", str_split($source, strrpos($source, " "))); //Split by last space and trim
    $components[0] = PROXY_PREFIX . rel2abs(ltrim($components[0], "/"), $baseURL); //First component of the split source string should be an image URL; proxify it
    return implode(" ",$components); //Recombine the components into a single source
  }, $sources);
  $proxifiedSrcset = implode(", ", $proxifiedSources); //Recombine the sources into a single "srcset"
  return $proxifiedSrcset;
}

//Extract and sanitize the requested URL, handling cases where forms have been rewritten to point to the proxy.
if (isset($_POST["miniProxyFormAction"])) {
  $url = $_POST["miniProxyFormAction"];
  unset($_POST["miniProxyFormAction"]);
} else {
  $queryParams = [];
  parse_str($_SERVER["QUERY_STRING"], $queryParams);

      //If the miniProxyFormAction field appears in the query string, make $url start with its value, and rebuild the the query string without it.
      if (isset($queryParams["miniProxyFormAction"])) {
        $formAction = $queryParams["miniProxyFormAction"];
        unset($queryParams["miniProxyFormAction"]);
        $url = $formAction . "?" . http_build_query($queryParams);
      } else {
        
        If (empty($queryParams['url'])){
         $url = substr($_SERVER["REQUEST_URI"], strlen($_SERVER["SCRIPT_NAME"]) + 1);
        }else{
         $url=$queryParams['url'];//=> https://www.google.com/
         $UaUrl=$queryParams['UaURL'];//=> Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36 Edg/117.0.2045.47
         $ExtraURL=$queryParams['ExtraURL'];// =>&Bar=1
        }
      }
}


if (empty($url)) {
    if (empty($startURL)) {
     die("<html><head><title>miniProxy</title></head><body>
        <h1>Bienvenidos al miniProxy!</h1>
        <form onsubmit=\"if (document.getElementById('site').value) { 
            window.location.href='" . PROXY_PREFIX . "' + document.getElementById('site').value; return false; 
        } else { 
            window.location.href='" . PROXY_PREFIX . $landingExampleURL . "'; return false; 
        }\" autocomplete=\"off\">
            <input id='site' type='text' size='50' placeholder='Ingrese una URL aquí...' />
            <input type='submit' value='Proxy Esto!' />
        </form>
        
        <!-- Botón para activar/desactivar Captura HAR -->
        <form method='POST' style='display:inline;'>
            <input type='hidden' name='toggle_capture_har' value='1'>
            <button type='submit'>" . (!empty($_SESSION['capture_har']) ? 'Desactivar Captura HAR' : 'Activar Captura HAR') . "</button>
        </form>
        <span class='har-text' style='" . (!empty($_SESSION['capture_har']) ? 'color:red;' : '') . "'>
            Capturar HAR " . (!empty($_SESSION['capture_har']) ? '(Activo)' : '(Inactivo)') . "
        </span>
        
        <!-- Botón para activar/desactivar Base64 en HAR -->
        <form method='POST' style='display:inline;'>
            <input type='hidden' name='toggle_base64_text' value='1'>
            <button type='submit'>" . (!empty($_SESSION['base64_text']) ? 'Desactivar Base64 en HAR' : 'Activar Base64 en HAR') . "</button>
        </form>
        <span class='base64-text' style='" . (!empty($_SESSION['base64_text']) ? 'color:red;' : '') . "'>
            Base64 a Texto " . (!empty($_SESSION['base64_text']) ? '(Activo)' : '(Inactivo)') . "
        </span>
    </body></html>");
    } else {
        $url = $startURL;
    }
} else if (strpos($url, ":/") !== strpos($url, "://")) {
    // Corregir URLs con doble slash
    $pos = strpos($url, ":/");
    $url = substr_replace($url, "://", $pos, strlen(":/"));
}

$scheme = parse_url($url, PHP_URL_SCHEME);
if (empty($scheme)) {
  if (strpos($url, "//") === 0) {
    //Assume that any supplied URLs starting with // are HTTP URLs.
    $url = "http:" . $url;
  } else {
    //Assume that any supplied URLs without a scheme (just a host) are HTTP URLs.
    $url = "http://" . $url;
  }
} else if (!preg_match("/^https?$/i", $scheme)) {
    die('Error: Detected a "' . $scheme . '" URL. miniProxy exclusively supports http[s] URLs.');
}

if (!isValidURL($url)) {
  die("Error: The requested URL was disallowed by the server administrator.");
}

$response = makeRequest($url);
$rawResponseHeaders = $response["headers"];
$responseBody = $response["body"];
$responseInfo = $response["responseInfo"];

//If CURLOPT_FOLLOWLOCATION landed the proxy at a diferent URL than
//what was requested, explicitly redirect the proxy there.
$responseURL = $responseInfo["url"];
if ($responseURL !== $url) {
  header("Location: " . PROXY_PREFIX . $responseURL, true);
  exit(0);
}

//A regex that indicates which server response headers should be stripped out of the proxified response.
$header_blacklist_pattern = "/^Content-Length|^Transfer-Encoding|^Content-Encoding.*gzip/i";

//cURL can make multiple requests internally (for example, if CURLOPT_FOLLOWLOCATION is enabled), and reports
//headers for every request it makes. Only proxy the last set of received response headers,
//corresponding to the final request made by cURL for any given call to makeRequest().
$responseHeaderBlocks = array_filter(explode("\r\n\r\n", $rawResponseHeaders));
$lastHeaderBlock = end($responseHeaderBlocks);
$headerLines = explode("\r\n", $lastHeaderBlock);
foreach ($headerLines as $header) {
  $header = trim($header);
  if (!preg_match($header_blacklist_pattern, $header)) {
    header($header, false);
  }
}
//Prevent robots from indexing proxified pages
header("X-Robots-Tag: noindex, nofollow", true);

if ($forceCORS) {
  //This logic is based on code found at: http://stackoverflow.com/a/9866124/278810
  //CORS headers sent below may conflict with CORS headers from the original response,
  //so these headers are sent after the original response headers to ensure their values
  //are the ones that actually end up getting sent to the browser.
  //Explicit [ $replace = true ] is used for these headers even though this is PHP's default behavior.

  //Allow access from any origin.
  header("Access-Control-Allow-Origin: *", true);
  header("Access-Control-Allow-Credentials: true", true);

  //Handle CORS headers received during OPTIONS requests.
  if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
    if (isset($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_METHOD"])) {
      header("Access-Control-Allow-Methods: GET, POST, OPTIONS", true);
    }
    if (isset($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_HEADERS"])) {
      header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}", true);
    }
    //No further action is needed for OPTIONS requests.
    exit(0);
  }

}

$contentType = "";
if (isset($responseInfo["content_type"])) $contentType = $responseInfo["content_type"];

//This is presumably a web page, so attempt to proxify the DOM.
if (stripos($contentType, "text/html") !== false) {

  //Attempt to normalize character encoding.
  $detectedEncoding = mb_detect_encoding($responseBody, "UTF-8, ISO-8859-1");
  if ($detectedEncoding) {
    $responseBody = mb_convert_encoding($responseBody, "HTML-ENTITIES", $detectedEncoding);
  }

  //Parse the DOM.
  $doc = new DomDocument();
  @$doc->loadHTML($responseBody);
  $xpath = new DOMXPath($doc);

  //Rewrite forms so that their actions point back to the proxy.
  foreach($xpath->query("//form") as $form) {
    $method = $form->getAttribute("method");
    $action = $form->getAttribute("action");
    //If the form doesn't have an action, the action is the page itself.
    //Otherwise, change an existing action to an absolute version.
    $action = empty($action) ? $url : rel2abs($action, $url);
    //Rewrite the form action to point back at the proxy.
    $form->setAttribute("action", rtrim(PROXY_PREFIX, "?"));
    //Add a hidden form field that the proxy can later use to retreive the original form action.
    $actionInput = $doc->createDocumentFragment();
    $actionInput->appendXML('<input type="hidden" name="miniProxyFormAction" value="' . htmlspecialchars($action) . '" />');
    $form->appendChild($actionInput);
  }
  //Proxify <meta> tags with an 'http-equiv="refresh"' attribute.
  foreach ($xpath->query("//meta[@http-equiv]") as $element) {
    if (strcasecmp($element->getAttribute("http-equiv"), "refresh") === 0) {
      $content = $element->getAttribute("content");
      if (!empty($content)) {
        $splitContent = preg_split("/=/", $content);
        if (isset($splitContent[1])) {
          $element->setAttribute("content", $splitContent[0] . "=" . PROXY_PREFIX . rel2abs($splitContent[1], $url));
        }
      }
    }
  }
  //Profixy <style> tags.
  foreach($xpath->query("//style") as $style) {
    $style->nodeValue = proxifyCSS($style->nodeValue, $url);
  }
  //Proxify tags with a "style" attribute.
  foreach ($xpath->query("//*[@style]") as $element) {
    $element->setAttribute("style", proxifyCSS($element->getAttribute("style"), $url));
  }
  //Proxify "srcset" attributes in <img> tags.
  foreach ($xpath->query("//img[@srcset]") as $element) {
    $element->setAttribute("srcset", proxifySrcset($element->getAttribute("srcset"), $url));
  }
  //Proxify any of these attributes appearing in any tag.
  $proxifyAttributes = ["href", "src"];
  foreach($proxifyAttributes as $attrName) {
    foreach($xpath->query("//*[@" . $attrName . "]") as $element) { //For every element with the given attribute...
      $attrContent = $element->getAttribute($attrName);
      if ($attrName == "href" && preg_match("/^(about|javascript|magnet|mailto):|#/i", $attrContent)) continue;
      if ($attrName == "src" && preg_match("/^(data):/i", $attrContent)) continue;
      $attrContent = rel2abs($attrContent, $url);
      $attrContent = PROXY_PREFIX . $attrContent;
      $element->setAttribute($attrName, $attrContent);
    }
  }

  //Attempt to force AJAX requests to be made through the proxy by
  //wrapping window.XMLHttpRequest.prototype.open in order to make
  //all request URLs absolute and point back to the proxy.
  //The rel2abs() JavaScript function serves the same purpose as the server-side one in this file,
  //but is used in the browser to ensure all AJAX request URLs are absolute and not relative.
  //Uses code from these sources:
  //http://stackoverflow.com/questions/7775767/javascript-overriding-xmlhttprequest-open
  //https://gist.github.com/1088850
  //TODO: This is obviously only useful for browsers that use XMLHttpRequest but
  //it's better than nothing.

  $head = $xpath->query("//head")->item(0);
  $body = $xpath->query("//body")->item(0);
  $prependElem = $head != null ? $head : $body;

  //Only bother trying to apply this hack if the DOM has a <head> or <body> element;
  //insert some JavaScript at the top of whichever is available first.
  //Protects against cases where the server sends a Content-Type of "text/html" when
  //what's coming back is most likely not actually HTML.
  //TODO: Do this check before attempting to do any sort of DOM parsing?
  if ($prependElem != null) {

    $scriptElem = $doc->createElement("script",
      '(function() {

        if (window.XMLHttpRequest) {

          function parseURI(url) {
            var m = String(url).replace(/^\s+|\s+$/g, "").match(/^([^:\/?#]+:)?(\/\/(?:[^:@]*(?::[^:@]*)?@)?(([^:\/?#]*)(?::(\d*))?))?([^?#]*)(\?[^#]*)?(#[\s\S]*)?/);
            // authority = "//" + user + ":" + pass "@" + hostname + ":" port
            return (m ? {
              href : m[0] || "",
              protocol : m[1] || "",
              authority: m[2] || "",
              host : m[3] || "",
              hostname : m[4] || "",
              port : m[5] || "",
              pathname : m[6] || "",
              search : m[7] || "",
              hash : m[8] || ""
            } : null);
          }

          function rel2abs(base, href) { // RFC 3986

            function removeDotSegments(input) {
              var output = [];
              input.replace(/^(\.\.?(\/|$))+/, "")
                .replace(/\/(\.(\/|$))+/g, "/")
                .replace(/\/\.\.$/, "/../")
                .replace(/\/?[^\/]*/g, function (p) {
                  if (p === "/..") {
                    output.pop();
                  } else {
                    output.push(p);
                  }
                });
              return output.join("").replace(/^\//, input.charAt(0) === "/" ? "/" : "");
            }

            href = parseURI(href || "");
            base = parseURI(base || "");

            return !href || !base ? null : (href.protocol || base.protocol) +
            (href.protocol || href.authority ? href.authority : base.authority) +
            removeDotSegments(href.protocol || href.authority || href.pathname.charAt(0) === "/" ? href.pathname : (href.pathname ? ((base.authority && !base.pathname ? "/" : "") + base.pathname.slice(0, base.pathname.lastIndexOf("/") + 1) + href.pathname) : base.pathname)) +
            (href.protocol || href.authority || href.pathname ? href.search : (href.search || base.search)) +
            href.hash;

          }

          var proxied = window.XMLHttpRequest.prototype.open;
          window.XMLHttpRequest.prototype.open = function() {
              if (arguments[1] !== null && arguments[1] !== undefined) {
                var url = arguments[1];
                url = rel2abs("' . $url . '", url);
                if (url.indexOf("' . PROXY_PREFIX . '") == -1) {
                  url = "' . PROXY_PREFIX . '" + url;
                }
                arguments[1] = url;
              }
              return proxied.apply(this, [].slice.call(arguments));
          };

        }

      })();'
    );
    $scriptElem->setAttribute("type", "text/javascript");

    $prependElem->insertBefore($scriptElem, $prependElem->firstChild);

  }

  echo "<!-- Proxified page constructed by miniProxy -->\n" . $doc->saveHTML();
} else if (stripos($contentType, "text/css") !== false) { //This is CSS, so proxify url() references.
  echo proxifyCSS($responseBody, $url);
} else { //This isn't a web page or CSS, so serve unmodified through the proxy with the correct headers (images, JavaScript, etc.)
  header("Content-Length: " . strlen($responseBody), true);
  echo $responseBody;
}
