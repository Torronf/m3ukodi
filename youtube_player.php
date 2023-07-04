<?php
//*****************************************************
//Cortesia de:"@M3uKodi Telegram Group"
//Fecha : 04/04/2022
//WebSite:https://www.m3ukodi.com
//Mail:m3ukodi@m3ukodi.com
//Donaciones:https://paypal.me/m3ukodi?locale.x=es_XC
//**************************************
// Youtube PHP Valido para VIDEO y STREAMING
//https://m3ukodi.com/iptv/youtube_player.php?https://youtu.be/ZEBAEQ3PRrU
//https://m3ukodi.com/iptv/youtube_player.php?https://www.youtube.com/watch?v=xR-4NDFsYNk

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
function cUrlGetData($url,$headers=null,$postFields=null,$head=null,$proxies=null,$cookie = null) {

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
				curl_setopt($ch, CURLOPT_ENCODING , '');
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
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
function unescapeUTF8EscapeSeq($str)
{
return preg_replace_callback(
	"/\\\u([0-9a-f]{4})/i",
	function ($matches) {
		return html_entity_decode('&#x' . $matches[1] . ';', ENT_QUOTES, 'UTF-8');
	},
	$str
);
}
$server= "https://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
$id_ext_reg='/(?:http:|https:)*?\/\/(?:www\.|)(?:youtube\.com|m\.youtube\.com|youtu\.|youtube-nocookie\.com).*(?:v=|v%3D|v\/|(?:a|p)\/(?:a|u)\/\d.*\/|live_stream\?channel=|\/|channel\?|\/|user\?|watch\?|vi(?:=|\/)|\/embed\/|oembed\?|be\/|e\/)([^&?%#\/\n]*)$/';

if (isset($_SERVER['QUERY_STRING']) && preg_match($id_ext_reg, $_SERVER['QUERY_STRING']))
{
$dominio=parse_url($_SERVER['QUERY_STRING'], PHP_URL_HOST);	
$headers = array(
   'authority:'.$dominio,
   'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36',
);		
	preg_match($id_ext_reg,$_SERVER['QUERY_STRING'],$channel_id);
	
	preg_match('/channelIds\":\["([-a-zA-Z0-9_]{24,})\"\]/', cUrlGetData($_SERVER['QUERY_STRING'],$headers), $channel_data);
	header("Content-type: application/x-mpegURL");
	header("Content-Type: video/x-mpegURL");
    	header("Content-Disposition: inline; filename=\"$channel_id[1]\"");

	$data_itag=cUrlGetData('https://www.youtube.com/v/'.$channel_id[1].'?version=3&autoplay=1' ,$headers);
		preg_match('/itag\":22,\"url\":\"([^"]+)|itag\":18,\"url\":\"([^"]+)|itag\":37,\"url\":\"([^"]+)|itag\":38,\"url\":\"([^"]+)/',$data_itag,$itag_ids);
		if (!empty($itag_ids[2])){
			header("Location:".urldecode(unescapeUTF8EscapeSeq($itag_ids[2])));
		}else{
			$data_hls=cUrlGetData('https://www.youtube.com/channel/'.$channel_data[1],$headers);
			preg_match('/\{"url":"\/watch\?v=(.*?)\"/',$data_hls,$hls_ids);
			$data_hls=cUrlGetData('https://www.youtube.com/watch?v='.$hls_ids[1],$headers);
			preg_match('/hlsManifestUrl\":[\'"]([^\'"]+)/',$data_hls,$hls_url);
			header("Location:".urldecode(unescapeUTF8EscapeSeq($hls_url[1])));
		}
}
?>
