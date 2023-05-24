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
header("Content-Type: text/plain"); //Anular esta Linea para Navegacion HTML

ob_start();

function cUrlGetData($url,$headers=null,$head = null,$getRemoteFilesize = null,$postFields = null,$proxies = null,$cookie = null) {
$time_out=2222;
				$ch = curl_init();

				curl_setopt($ch, CURLOPT_URL, $url);
				if ($postFields && !empty($postFields))
				{
					$postfields=rtrim($postFields);
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS,$postfields);
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
					curl_setopt($ch, CURLOPT_COOKIESESSION, 1);
					curl_setopt($ch, CURLOPT_COOKIEFILE, "./");
					curl_setopt($ch, CURLOPT_COOKIEJAR, "./"); 
				}
				if ($head && !empty($head)) 
				{
					curl_setopt($ch, CURLOPT_HEADER, $head);
					curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
			$head_data = curl_getinfo($ch, CURLINFO_HEADER_OUT);
				}
				if ($getRemoteFilesize && !empty($getRemoteFilesize)) 
				{
					curl_setopt($ch, CURLOPT_VERBOSE, 1);
					curl_setopt($ch, CURLOPT_TIMEOUT, $time_out);
					curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
					curl_setopt($ch, CURLOPT_NOBODY, 1);
			$size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
				}			
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_ENCODING , '');
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				//curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
				curl_setopt($ch, CURLOPT_TIMEOUT, $time_out);
				$data = curl_exec($ch);
    
    if (curl_errno($ch)) 
    {
        echo 'Error:' . curl_error($ch);
    }

    curl_close($ch);
	
	if ($data) return $data;else return FALSE;
}

function web_archive($url,$update=null){

	if ($update == 1){echo file_get_contents('https://web.archive.org/cdx/save/cdx?url='.$url);}
	
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

echo web_archive('https://www3.animeflv.net',$update=1); //(1=Update Null= Nada)

?>
