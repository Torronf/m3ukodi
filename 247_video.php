<?php
//*****************************************************
//Cortesia de:"@M3uKodi Telegram Group"
//Fecha : 12/05/2023       
//WebSite:https://www.m3ukodi.com
//Mail:m3ukodi@m3ukodi.com
//Donaciones:https://paypal.me/m3ukodi?locale.x=es_XC
//**************************************
// Debe Crear un archivo externo colocado en el mismo directorio donde este PHP
// Nombre Archivo.:movies.txt (Puede poner nombre deseado)
// Contenido:
// dentro 
//Uso:http://dominio.host.com/247.php?movie <- Segun se llame el archivo

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

function video_fin($video_array){
		$video = $video_array[rand(0, count($video_array) - 1)]; 
		$video = trim($video);
		$video_id= explode('\n', $video);
		$video_code= explode('=', $video);
		$video_pl=$video_code[1];
		return $video_pl;
	}

$video_array = file('./'.$_SERVER['QUERY_STRING'].'.txt'); 
$video = $video_array[rand(0, count($video_array) - 1)];  
$video = explode(",",trim($video)); 
shuffle($video);
$output2 = implode(',', $video);
$video_url = explode(",",trim($output2));
				$file_source = $video_url[0];	
				$fileName="m3ukodi.m3u8";
				header('Content-type:application/vnd.apple.mpegurl; charset=utf-8');
				header('Content-Disposition: attachment; filename="' . $fileName . '"');
				header('Location:'.$file_source);
ob_end_flush();
?>
