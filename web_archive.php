<?php
//*****************************************************
//Cortesia de:"@M3uKodi Telegram Group"
//Fecha : 0/24/2023 
//WebSite:https://www.m3ukodi.com
//Mail:m3ukodi@m3ukodi.com
//Donaciones:https://paypal.me/m3ukodi?locale.x=es_XC
//**************************************
// Permite ver y navegar en Web.archive.org la pagina buscada
ini_set('display_startup_errors', 0); 
ini_set('display_errors',1);
ini_set('memory_limit',"1024M");
ini_set('upload_max_filesize', '500M');
ini_set('post_max_size', '5000M');
ini_set('max_input_time', 300);
ini_set('max_execution_time', 0);
ini_set('allow_url_include', 'On');
error_reporting(E_ALL);
ignore_user_abort(true);
clearstatcache();
header("X-Robots-Tag: noindex, nofollow", true);
header("Content-Type: text/plain");	

ob_start();

function web_archive($url){

	$headers = array(
	   'authority: web.archive.org',
	   'upgrade-insecure-requests: 1',
	   'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36 Edg/113.0.1774.42',
	);
		$web_archive_url='https://web.archive.org/cdx/search/cdx?output=json&limit=-1&url='.$url;
		$web_archive_cache=json_decode(cUrlGetData($web_archive_url,$headers),true);
		
	if($web_archive_cache !== [])
	{
		$data_page=cUrlGetData('https://web.archive.org/web/'.$web_archive_cache[1][1].'/'.$web_archive_cache[1][2],$headers);
		return ($data_page);
	}else{
		$data_page=array();
		return($data_page);
	}
}

echo web_archive('https://m3ukodi.com/site/');

?>
