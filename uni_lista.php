<?php
//************************************************** *******
//Cortesia de:@M3uKodi Telegram Group
//WebSite:https://www.m3ukodi.com
//Mail:m3ukodi@m3ukodi.com
//Desarrollado:30/Mayo/2020
//Donaciones:https://paypal.me/m3ukodi?locale.x=es_XC
//************************************************** *******


//Parametro que forzan bajar lista con el nombre M3UKODI.M3U
$public_name ="m3ukodi.m3u";
header("Content-Type: text/plain");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: "GET, POST, OPTIONS, HEAD"');
header("Content-type: aplication/mpeg; Accept-Charset: UTF-8");
header("Content-Disposition: attachment; filename=$public_name;");


// Selector de M3U
$sat_file=array(
	"https://www.achoapps.com/listas/lista21.m3u",
	"https://www.achoapps.com/listas/chile1.m3u",
	"https://www.achoapps.com/listas/latinos1.m3u",
	"https://www.achoapps.com/listas/lista9.m3u",
	"https://www.achoapps.com/listas/spain3.m3u",
	"https://pastebin.com/raw/0jb3hfQ5",
	"https://pastebin.com/raw/aiMX4ifx",
	"https://www.achoapps.com/listas/latino4.m3u",
	"https://www.achoapps.com/listas/lista12.m3u",
	"https://www.achoapps.com/listas/lista8.m3u",
	"https://www.achoapps.com/listas/adultos1.m3u",
	"https://www.achoapps.com/listas/deportes5.m3u",
	"http://tecnotv.club/caducado.m3u",
);


foreach ($sat_file as $url_final) {

$ch = curl_init($url_final);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
	$result = curl_exec($ch);
	curl_close($ch);
echo $result;
}
?>
