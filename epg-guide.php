<?php
//*****************************************************
//Cortesia de:"@M3uKodi Telegram Group"
//Fecha : 03/04/2022
//WebSite:https://www.m3ukodi.com
//Mail:m3ukodi@m3ukodi.com
//Donaciones:https://paypal.me/m3ukodi?locale.x=es_XC
//**************************************
// Lector Multiple de EPG para listas combinadas.
//Modo de uso
// Al inicio de la lista coloque esto:
//#EXTM3U x-tvg-url="epg-guide.php" list-name="M3UKODI TV" list-image="https://i.imgur.com/DNh8m7a.png"
//Salve este PHP (epg-guide.php) en el mismo FOLDER donde tiene la Lista.

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
$url_tvg=[
	"https://www.tdtchannels.com/epg/TV.xml",
	"https://i.mjh.nz/Plex/us.xml.gz",
	"https://iptv-org.github.io/epg/guides/es/mi.tv.xml",
	"http://m3u4u.com/epg/jq2zy9pzprh3jkjnxr58",
	"https://i.mjh.nz/PlutoTV/mx.xml.gz"
];
	foreach($url_tvg as $key=>$epg_guide) {
		readfile($epg_guide);
	}
?>
