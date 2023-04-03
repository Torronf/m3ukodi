<?php
//*****************************************************
//Cortesia de:"@M3uKodi Telegram Group"
//Fecha : 02/03/2022
//WebSite:https://www.m3ukodi.com
//Mail:m3ukodi@m3ukodi.com
//Donaciones:https://paypal.me/m3ukodi?locale.x=es_XC
//**************************************
//https://ok.ru
//Uso:
//ok-ru.php?https://ok.ru/video/5318264294036
//

function cUrlGetData($url,$headers=null,$postFields=null,$head=null,$proxies=null,$cookie = null) {

				$ch = curl_init();
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

$server= "https://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];

if (strpos($_SERVER['QUERY_STRING'],"ok.ru") !==false) {

  $pattern = '/(?:\/\/|\.)(ok\.ru|odnoklassniki\.ru)\/(?:videoembed|video)\/(\d+)/';
  preg_match($pattern,$_SERVER['QUERY_STRING'],$m);

  $id=$m[2];
  $url="http://www.ok.ru/dk";
  $postFields="cmd=videoPlayerMetadata&mid=".$id;
  $headers=[
    'Origin: https://ok.ru',
    'Referer: https://ok.ru/',
	  'User-Agent:Mozilla/5.0(Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/105.0.0.0 Safari/537.36 Edg/105.0.1343.27 Mozilla/5.0(Linux;Android 7.1.2;ro;RO;MXQ-4K Build/MXQ-4K) MXPlayer/1.8.10'
  ];
	
  $ok_ru_dat=json_decode(cUrlGetData($url,$headers,$postFields),true);
						
		header("Content-type: application/x-mpegURL");
		header("Content-Type: video/x-mpegURL");
		header("Content-Disposition: inline; filename=\"m3ukodi.m3u\"");

  if(!empty($ok_ru_dat['hlsMasterPlaylistUrl'])){
      echo cUrlGetData(rtrim($ok_ru_dat['hlsMasterPlaylistUrl']),$headers);
      exit;
  }  

   if(!empty($ok_ru_dat['hlsManifestUrl'])){
      echo cUrlGetData(rtrim($ok_ru_dat['hlsManifestUrl']));
      exit;
  } 
    if(!empty($ok_ru_dat['video'])){
      echo cUrlGetData(rtrim($ok_ru_dat['video'][4]));
    exit;
  } 
}
  echo "URL No Valida";
