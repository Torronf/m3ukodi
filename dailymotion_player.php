<?php
//*****************************************************
//Cortesia de:"@M3uKodi Telegram Group"
//Fecha : 14/06/2024
//WebSite:https://www.m3ukodi.com
//Mail:m3ukodi@m3ukodi.com
//Donaciones:https://paypal.me/m3ukodi?locale.x=es_XC
//**************************************
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("log_errors", 1);
ini_set("error_log", "/errores.log");
ini_set("display_errors", 0);

@ini_set("memory_limit","1024M");
@ini_set('upload_max_filesize', '500M');
@ini_set('post_max_size', '5000M');
@ini_set('max_input_time', 300);
@ini_set('max_execution_time', 0);
@ini_set('output_buffering', 'Off'); 
@ini_set('implicit_flush', 1); 
@ini_set('zlib.output_compression', 0); 
@ini_set('default_socket_timeout', 20);

set_time_limit(60*5);
ignore_user_abort(true);
clearstatcache();
session_start(); // Se destruye cualquier 
session_unset(); // session anterior antes de 
session_destroy(); // comenzar con el scrip. Esto es opcional 

header("X-Robots-Tag: noindex, nofollow", true);
header("Content-Type: text/plain");	 
header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: origin,range,accept,accept-encoding,referer,content-type, SOAPAction,X-AxDRM-Message');
header('Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST');
header('Access-Control-Expose-Headers: server,range,content-range,content-length,content-type');
require './../../developer/config/country.php';
require './../../developer/config/arch_php.php';

ob_start();
ob_implicit_flush(1); 
$cf = basename($_SERVER['SCRIPT_FILENAME']);

function cUrlGetData($url,$headers=null,$head=null,$postFields=null,$proxies=null,$cookie = null) {

				$ch = curl_init($url);
				$timeout = 10;
				curl_setopt($ch, CURLOPT_URL, $url);
				
				if ($postFields && !empty($postFields))
				{
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
				}   
				if ($headers && !empty($headers)) 
				{
					curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);					
				}				
				if ($proxies && !empty($proxies)) 
				{
					curl_setopt($url, CURLOPT_PROXY, $proxies);
					curl_setopt($url, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
					curl_setopt($url, CURLOPT_HTTPPROXYTUNNEL, 0); 
				}
				if ($cookie && !empty($cookie)) 
				{
					//curl_setopt($ch, CURLOPT_COOKIESESSION, 1);
					//curl_setopt($ch, CURLOPT_COOKIEFILE, realpath(dirname(__FILE__)) . '/m3ukodi.txt');//Activo para Data
					curl_setopt($ch, CURLOPT_COOKIEJAR, realpath(dirname(__FILE__)) . '/m3ukodi.txt'); //Activo Para Login
				}
				if ($head && !empty($head)) 
				{
					curl_setopt($ch, CURLOPT_HEADER, $head);
					curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
	  $head_data = curl_getinfo($ch, CURLINFO_HEADER_OUT);
				}			
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_ENCODING,"");
				curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
				$data = curl_exec($ch);
    
    if (curl_errno($ch)) 
    {
        echo 'Error:' . curl_error($ch);
    }

    curl_close($ch);
    return $data;
}

$url_video = $_SERVER['QUERY_STRING'];
$id_ext_reg = '/https?:\/\/(?:www\.)?dai(?:lymotion\.com\/video|\.ly)\/([a-zA-Z0-9]{7})/i';

preg_match($id_ext_reg, $url_video, $matches);

if (isset($matches[1])) {
    $id_video = $matches[1];

    $headers = array(   
        'Referer: '.$url_video,
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36 Edg/110.0.1587.46',
    );

    $data = cUrlGetData($url_video, $headers);
	// Usar expresión regular para extraer el valor del atributo src
		$src_reg = '/<script[^>]*id="player_embed_script_placeholder"[^>]*src="([^"]+)"[^>]*><\/script>/i';
		preg_match($src_reg, $data, $src_matches);

		if (isset($src_matches[1])) {
		$headers = array(   
			'Host: geo.dailymotion.com',
			'Referer: '.$url_video,
			'Cookie: v1st=bd9279e2-eee3-409f-9485-1baa191ea1a3; usprivacy=1---; '
			);
			
				$src_url = $src_matches[1];
				$src_data = cUrlGetData($src_url, $headers);
				preg_match('/"v1st":"([^"]+)"/', $src_data, $v1st_match);
				
				$data_url='https://www.dailymotion.com/player/metadata/video/'.$matches[1].'?dmV1st='.$v1st_match[1];
				$headers =[
					'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36 Edg/125.0.0.0',
					'Accept-Encoding: gzip, deflate, br, zstd',
					'sec-ch-ua: "Microsoft Edge";v="125", "Chromium";v="125", "Not.A/Brand";v="24"',
					'sec-ch-ua-mobile: ?0',
					'sec-ch-ua-platform: "Windows"',
					'Origin: https://geo.dailymotion.com',
					'Sec-Fetch-Site: same-site',
					'Sec-Fetch-Mode: cors',
					'Sec-Fetch-Dest: empty',
					'Referer: https://geo.dailymotion.com/',
					'Accept-Language: es,es-ES;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6,es-MX;q=0.5',
				  ];

				$response=cUrlGetData($data_url,$headers);
				$url_video_json=json_decode($response,true)['qualities']['auto'][0]['url'];

				$url_video=cUrlGetData($url_video_json,$headers);
				print_r($url_video);
		} else {
			echo "No se encontró el valor de src en el contenido obtenido.";
		}
} else {
    // Maneja el caso en que no se encuentre un ID de video
    echo "No se encontró un ID de video válido en la URL proporcionada.";
}
?>
