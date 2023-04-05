<?php
//*****************************************************
//Cortesia de:"@M3uKodi Telegram Group"
//Fecha : 05/04/2022       
//WebSite:https://www.m3ukodi.com
//Mail:m3ukodi@m3ukodi.com
//Donaciones:https://paypal.me/m3ukodi?locale.x=es_XC
//**************************************
//GDrive de Google, Este PHP solo es para los ARCHIVOS que este compartido de forma publica por el Propietario 
//https://drive.google.com/file/d/1rBgRZTK6E6reM4Su6YtI5kDHMWg6bn6Z/view 
ini_set("display_errors",1);
ini_set("memory_limit","1024M");
ini_set('upload_max_filesize', '500M');
ini_set('post_max_size', '5000M');
ini_set('max_input_time', 300);
ini_set('max_execution_time', 0);
ini_set('allow_url_include', 'On');
ignore_user_abort(true);
clearstatcache();
header("X-Robots-Tag: noindex, nofollow", true);
header("Content-Type: text/plain");	

ob_start();
function get_drive_id($string) {

  if (strpos($string, "/edit")) {
	$string = str_replace("/edit", "/view", $string);
  } else if (strpos($string, "?id=")) {
	$parts = parse_url($string);
	parse_str($parts['query'], $query);
	return $query['id'];
  } else if (!strpos($string, "/view")) {
	$string = $string . "/view";
  }
  $start  = "file/d/";
  if(strpos($string, "/preview")){
	$end = "/preview";
  }elseif(strpos($string, "/view")){
	$end = "/view";
  }
  $string = " " . $string;
  $ini    = strpos($string, $start);
  if ($ini == 0) {
	return null;
  }
  $ini += strlen($start);
  $len = strpos($string, $end, $ini) - $ini;
  return substr($string, $ini, $len);
}
$data_id='/(?:http:|https:)*?\/\/(?:www\.|)(?:drive\.google\.com).*?([a-zA-Z0-9_]{5,33})/u';

if (isset($_SERVER['QUERY_STRING']) && preg_match('/d\/([a-zA-Z0-9_]{5,33})/u', $_SERVER['QUERY_STRING']))
{
  	$drive_id=get_drive_id($_SERVER['QUERY_STRING']); 
	 if (empty($drive_id)){die("ID No Valido");}
		$ch = curl_init("https://drive.google.com/uc?id=".$drive_id."&authuser=0&export=download");
			curl_setopt_array($ch, array(
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_POSTFIELDS => [],
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_TIMEOUT => 20,
				CURLOPT_ENCODING => 'gzip,deflate',
				CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
				CURLOPT_HTTPHEADER => ['accept-encoding: gzip, deflate, br',
				'content-length: 0',
				'content-type: application/x-www-form-urlencoded;charset=UTF-8',
				'origin: https://drive.google.com',
				'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36',
				'x-client-data: CKG1yQEIkbbJAQiitskBCMS2yQEIqZ3KAQioo8oBGLeYygE=',
				'x-drive-first-party: DriveWebUi',
				'x-json-requested: true',
				]		
			));
			$response = curl_exec($ch);
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				$object = json_decode(str_replace(')]}\'', '', $response) , true);
				if ($httpCode >= 200 && $httpCode < 300) {
					header('Content-Description: File Transfer');
					header('Content-Type: application/octet-stream');		
					header('Content-Disposition: attachment; filename=\"'.$object['fileName'].'\"');
					header('Content-Length: '.$object['sizeBytes']);	
					header("Access-Control-Allow-Origin: *");
					header("Content-type: application/vnd.apple.mpegurl");		
					header('Location: ' . filter_var($object['downloadUrl'], FILTER_SANITIZE_URL));
				}
	
}else{
  header('HTTP/1.0 400 Bad Request');
  die("Parametros Invalidos o Requeridos");
}
?>
