<?php
//*****************************************************
//Cortesia de:"@M3uKodi Telegram Group"
//Fecha : 12/04/2023
//WebSite:https://www.m3ukodi.com
//Mail:m3ukodi@m3ukodi.com
//Donaciones:https://paypal.me/m3ukodi?locale.x=es_XC
//**************************************

ini_set("display_errors",1);
ini_set("memory_limit","1024M");
ini_set('upload_max_filesize', '500M');
ini_set('post_max_size', '5000M');
ini_set('max_input_time', 300);
ini_set('max_execution_time', 0);
ignore_user_abort(true);
clearstatcache();
header("X-Robots-Tag: noindex, nofollow", true);
header("Content-Type: text/plain");	
//Dailymotion Sistema de Stream
//Ejemplo de uso
//https://m3ukodi.com/iptv/dplay.php?v=https://www.dailymotion.com/video/x82z4if
ob_start();

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

$id_ext_reg='/(?:dailymotion.com).*?([a-zA-Z0-9_-]{7})/'; 
preg_match($id_ext_reg,$_SERVER['QUERY_STRING'],$id_video);

$headers = array(   
   'Referer:'.$_SERVER['QUERY_STRING'],
   'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36 Edg/110.0.1587.46',
);

$data = cUrlGetData($_SERVER['QUERY_STRING'],$headers);

preg_match('/dmTs=(.*?)\"/',$data,$dmTs_match);
preg_match('/name=\"(.*?)\"[\n].*?src/',$data,$dmV1st_match);
@$dmV1st=explode('"',urldecode($dmV1st_match[1]));

	    @$data_url='https://www.dailymotion.com/player/metadata/video/'.$id_video[1].'?locale=en-US&dmV1st='.$dmV1st[5].'&dmTs='.$dmTs_match[1].'&is_native_app=0';

		$headers = [
				'Host: www.dailymotion.com',
				'Connection: keep-alive',
				'sec-ch-ua: "Chromium";v="110", "Not A(Brand";v="24", "Microsoft Edge";v="110"',
				'sec-ch-ua-mobile: ?0',
				'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhaWQiOiJmMWEzNjJkMjg4YzFiOTgwOTljNyIsInJvbCI6ImNhbi1tYW5hZ2UtcGFydG5lcnMtcmVwb3J0cyBjYW4tcmVhZC12aWRlby1zdHJlYW1zIGNhbi1zcG9vZi1jb3VudHJ5IGNhbi1hZG9wdC11c2VycyBjYW4tcmVhZC1jbGFpbS1ydWxlcyBjYW4tbWFuYWdlLWNsYWltLXJ1bGVzIGNhbi1tYW5hZ2UtdXNlci1hbmFseXRpY3MgY2FuLXJlYWQtbXktdmlkZW8tc3RyZWFtcyBjYW4tZG93bmxvYWQtbXktdmlkZW9zIGFjdC1hcyBhbGxzY29wZXMgYWNjb3VudC1jcmVhdG9yIGNhbi1yZWFkLWFwcGxpY2F0aW9ucyIsInNjbyI6InJlYWQgd3JpdGUgZGVsZXRlIGVtYWlsIHVzZXJpbmZvIGZlZWQgbWFuYWdlX3ZpZGVvcyBtYW5hZ2VfY29tbWVudHMgbWFuYWdlX3BsYXlsaXN0cyBtYW5hZ2VfdGlsZXMgbWFuYWdlX3N1YnNjcmlwdGlvbnMgbWFuYWdlX2ZyaWVuZHMgbWFuYWdlX2Zhdm9yaXRlcyBtYW5hZ2VfbGlrZXMgbWFuYWdlX2dyb3VwcyBtYW5hZ2VfcmVjb3JkcyBtYW5hZ2Vfc3VidGl0bGVzIG1hbmFnZV9mZWF0dXJlcyBtYW5hZ2VfaGlzdG9yeSBpZnR0dCByZWFkX2luc2lnaHRzIG1hbmFnZV9jbGFpbV9ydWxlcyBkZWxlZ2F0ZV9hY2NvdW50X21hbmFnZW1lbnQgbWFuYWdlX2FuYWx5dGljcyBtYW5hZ2VfcGxheWVyIG1hbmFnZV9wbGF5ZXJzIG1hbmFnZV91c2VyX3NldHRpbmdzIG1hbmFnZV9jb2xsZWN0aW9ucyBtYW5hZ2VfYXBwX2Nvbm5lY3Rpb25zIG1hbmFnZV9hcHBsaWNhdGlvbnMgbWFuYWdlX2RvbWFpbnMgbWFuYWdlX3BvZGNhc3RzIiwibHRvIjoiYVcxZVpSQkJXUnBKYVJORWMxaDFEMDgwQUc4TlQwbGFkaElERHciLCJhaW4iOjEsImFkZyI6MSwiaWF0IjoxNjc2NjQ3NzI3LCJleHAiOjE2NzY2ODMxODcsImRtdiI6IjEiLCJhdHAiOiJicm93c2VyIiwiYWRhIjoid3d3LmRhaWx5bW90aW9uLmNvbSIsInZpZCI6IkJDQTZFRDkzQTRGODRGNDEzNjg1NDEwNDUwQzhCM0FEIiwiZnRzIjo0Njk0NjAsImNhZCI6MiwiY3hwIjoyLCJjYXUiOjIsImtpZCI6IkFGODQ5REQ3M0E1ODYzQ0Q3RDk3RDBCQUIwNzIyNDNCIn0.T-yLhrEc1C5CGw94hi5-tFqcVvmh7bnWGJIKajxbfxU',
				'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36 Edg/110.0.1587.46',
				'sec-ch-ua-platform: "Windows"',
				'Accept: */*',
				'Sec-Fetch-Site: same-origin',
				'Sec-Fetch-Mode: cors',
				'Sec-Fetch-Dest: empty',
				'Referer: https://www.dailymotion.com/embed?api=postMessage&apimode=json&app=com.dailymotion.neon&autoplay-mute=true&client_type=website&collections-action=trigger_event&collections-enable=fullscreen_only&endscreen-enable=false&info=false&like-action=trigger_event&like-enable=fullscreen_only&queue-enable=false&sharing-action=trigger_event&sharing-enable=fullscreen_only&ui-logo=false&watchlater-action=trigger_event&watchlater-enable=fullscreen_only&source=https%3A%2F%2Fwww.google.com%2F&dmTs='.$dmTs_match[1],
				'Accept-Language: en-US,en;q=0.9,es;q=0.8',
				'Cookie: v1st='.@$dmV1st[5].'; ts='.@$dmTs_match[1].'; usprivacy=1---; dmaid=fdea1dc0-2572-4dd4-bb7b-85fb74c1d7a7; dmvk=63ef9d2dd9a7d; client_token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhaWQiOiJmMWEzNjJkMjg4YzFiOTgwOTljNyIsInJvbCI6ImNhbi1tYW5hZ2UtcGFydG5lcnMtcmVwb3J0cyBjYW4tcmVhZC12aWRlby1zdHJlYW1zIGNhbi1zcG9vZi1jb3VudHJ5IGNhbi1hZG9wdC11c2VycyBjYW4tcmVhZC1jbGFpbS1ydWxlcyBjYW4tbWFuYWdlLWNsYWltLXJ1bGVzIGNhbi1tYW5hZ2UtdXNlci1hbmFseXRpY3MgY2FuLXJlYWQtbXktdmlkZW8tc3RyZWFtcyBjYW4tZG93bmxvYWQtbXktdmlkZW9zIGFjdC1hcyBhbGxzY29wZXMgYWNjb3VudC1jcmVhdG9yIGNhbi1yZWFkLWFwcGxpY2F0aW9ucyIsInNjbyI6InJlYWQgd3JpdGUgZGVsZXRlIGVtYWlsIHVzZXJpbmZvIGZlZWQgbWFuYWdlX3ZpZGVvcyBtYW5hZ2VfY29tbWVudHMgbWFuYWdlX3BsYXlsaXN0cyBtYW5hZ2VfdGlsZXMgbWFuYWdlX3N1YnNjcmlwdGlvbnMgbWFuYWdlX2ZyaWVuZHMgbWFuYWdlX2Zhdm9yaXRlcyBtYW5hZ2VfbGlrZXMgbWFuYWdlX2dyb3VwcyBtYW5hZ2VfcmVjb3JkcyBtYW5hZ2Vfc3VidGl0bGVzIG1hbmFnZV9mZWF0dXJlcyBtYW5hZ2VfaGlzdG9yeSBpZnR0dCByZWFkX2luc2lnaHRzIG1hbmFnZV9jbGFpbV9ydWxlcyBkZWxlZ2F0ZV9hY2NvdW50X21hbmFnZW1lbnQgbWFuYWdlX2FuYWx5dGljcyBtYW5hZ2VfcGxheWVyIG1hbmFnZV9wbGF5ZXJzIG1hbmFnZV91c2VyX3NldHRpbmdzIG1hbmFnZV9jb2xsZWN0aW9ucyBtYW5hZ2VfYXBwX2Nvbm5lY3Rpb25zIG1hbmFnZV9hcHBsaWNhdGlvbnMgbWFuYWdlX2RvbWFpbnMgbWFuYWdlX3BvZGNhc3RzIiwibHRvIjoiYVcxZVpSQkJXUnBKYVJORWMxaDFEMDgwQUc4TlQwbGFkaElERHciLCJhaW4iOjEsImFkZyI6MSwiaWF0IjoxNjc2NjQ3NzI3LCJleHAiOjE2NzY2ODMxODcsImRtdiI6IjEiLCJhdHAiOiJicm93c2VyIiwiYWRhIjoid3d3LmRhaWx5bW90aW9uLmNvbSIsInZpZCI6IkJDQTZFRDkzQTRGODRGNDEzNjg1NDEwNDUwQzhCM0FEIiwiZnRzIjo0Njk0NjAsImNhZCI6MiwiY3hwIjoyLCJjYXUiOjIsImtpZCI6IkFGODQ5REQ3M0E1ODYzQ0Q3RDk3RDBCQUIwNzIyNDNCIn0.T-yLhrEc1C5CGw94hi5-tFqcVvmh7bnWGJIKajxbfxU; lang=en_US; _ga=GA1.2.1527937344.1676647728; _gid=GA1.2.318440514.1676647728; vc=0/false; cookie_policy_closed=1; __gads=ID=fd124533583b963a:T=1676647881:S=ALNI_MaXH3QfjsXxclRxS1xg_5qoOarlag; __gpi=UID=000009b1b4f552b2:T=1676647881:RT=1676647881:S=ALNI_MbnRq6pVnPz61RopyrToR-bos7KhQ; 1stsearch=1; cto_bundle=aWEfv19TRmZmMnBtemxwJTJCdHlwcGdFJTJCd2NSN2RvMGpwM0dSZ2xva1ZJSFNGZjUzNTdVclduc3d6cElmQXljNFR2a2MxU0hqY1JpZ2paZzZ0eXkzdndaWEo3QURCTWtteCUyRndBcDFrZG91MmZTSFJFYjg3JTJGM09TR1h4dnZkMDVsbCUyQlZPZm96SDh6VmxzJTJCQ2dBWlRrWlNNbmhDOTJOSUlDcEYzZkk3MDFrVE9xJTJGUWw2VSUzRA; ff=on; damd=R7sM8r3Kyf4bLOKbZE0eupnnlpGbt7Yk6fJB9fatAFKOZ47WUGczyE4LC_NVtmPCZoky30mkNLdS7mM7CqXvinqY7g847D5wvddjyNN9jnK5baKQGm8hTYMHIe1K7ax2EfeuJsAgNRX9oitcWTHzZYiuAj_vDodqWfTf3a3pyBA_fmu9fKqgY_QINYycUx3Bpbj8X-MPIrAeNJEkAVIoYVAd05JLEeQK7afEzANzR0zX-kVmLlhp6-CYwefrG0rTtbj8etBiJ6BCk_JpD0V2Ivm3N13QhjAwI7fCzANaqFU9fVH_5_3UlWpuPWca1vO3Waul00v19mz6Z6L2HJUE3A',
];
		  
			$sele_dat=json_decode(cUrlGetData($data_url,$headers),true);
			
			if(!empty($sele_dat['error']['message'])){header('Location: ' . filter_var("https://tinyurl.com/m3ukodivideolost", FILTER_SANITIZE_URL));exit($sele_dat['error']['message']);};
			
			$TS = explode("\n",cUrlGetData($sele_dat['qualities']['auto'][0]['url'],$headers));
 
				$url_domain=substr(strtok($TS[12],"#"),0);
				$url_ts=cUrlGetData($TS[12],$headers);
			header('Location: ' . filter_var($url_domain, FILTER_SANITIZE_URL));
?>
