<?php
//*****************************************************
//Cortesia de:"@M3uKodi Telegram Group"
//Fecha : 23/11/2023       
//WebSite:https://www.m3ukodi.com
//Mail:m3ukodi@m3ukodi.com
//Donaciones:https://paypal.me/m3ukodi?locale.x=es_XC
//**************************************
//https://m3ukodi.com/developers/hochu/hochu.php?playboy
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("log_errors", 1);
ini_set("error_log", "/errores.log");
ini_set("display_errors", 1);

@ini_set("memory_limit","1024M");
@ini_set('upload_max_filesize', '500M');
@ini_set('post_max_size', '5000M');
@ini_set('max_input_time', 300);
@ini_set('max_execution_time', 0);

set_time_limit(60*5);
ignore_user_abort(true);
clearstatcache();
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

ob_start();
function fetchWithCurl($url, $headers) {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	curl_setopt($curl,CURLOPT_ENCODING, '');
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // Solo para depuración, ten cuidado
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // Solo para depuración, ten cuidado

    $resp = curl_exec($curl);
    curl_close($curl);
    
    return $resp;
}
if(isset($_GET['ts'])){

	  //header('Content-Type: video/MP2T');
	  $headers=[
			'Host: 50.7.120.164:8081',
			'Connection: keep-alive',
			'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36 Edg/121.0.0.0',
			'Accept: */*',
			'Origin: http://cdnneedtv.ru',
			'Referer: http://cdnneedtv.ru/',
			'Accept-Encoding: gzip, deflate',
			'Accept-Language: es,es-ES;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6',
		];
			echo fetchWithCurl(rtrim($_GET['ts']),$headers);
	  exit;
}
$headers = [
    "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7",
    "Accept-Language: es,es-ES;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6",
    "Cache-Control: no-cache",
    "Connection: keep-alive",
    "Pragma: no-cache",
    "Referer: http://hochu.tv/",
    "Upgrade-Insecure-Requests: 1",
    "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36 Edg/121.0.0.0",
];

$ch_id = $_SERVER['QUERY_STRING'];

		$url = 'http://cdnneedtv.ru/hochu/'.$ch_id.'.php';
		$response = fetchWithCurl($url, $headers);
		$server= "https://".$_SERVER["HTTP_HOST"].$_SERVER['PHP_SELF'];
		$pattern = '/file:"((http:\/\/[\d\.]+:\d+\/(?:[^\/]+)\/)index\.m3u8\?wmsAuthSign=[^"]+)"/';

			preg_match($pattern, $response, $matches);
				if (isset($matches[1])) {
											$lineas_ts=explode("\n",fetchWithCurl(trim($matches[1]), $headers));

											 for($i=0; $i<count($lineas_ts)-1; $i++){
													if (strpos($lineas_ts[$i], '#') !== false){
														echo $lineas_ts[$i].PHP_EOL;
													}else{				
														echo $server.'?ts='.trim($matches[2]).$lineas_ts[$i].PHP_EOL;
													}
												}
										$salida = ob_get_contents();
										ob_end_clean();
										echo $salida;
				} else {
					echo "No se encontró la URL en el contenido.";
				}
?>
