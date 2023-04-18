<?php
//*****************************************************
//Cortesia de:"@M3uKodi Telegram Group"
//Fecha : 18/04/2022       
//WebSite:https://www.m3ukodi.com
//Mail:m3ukodi@m3ukodi.com
//Donaciones:https://paypal.me/m3ukodi?locale.x=es_XC
//**************************************
//https://m3ukodi.com/developer/streamlare/streamlare.php?https://slmaxed.com/e/4RLg7lXxmaVl2QAe
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
require_once './big_file.php';

ob_start();
function cUrlGetData($url,$headers=null,$head = null,$getRemoteFilesize = null,$postFields = null,$proxies = null,$cookie = null) {

				$ch = curl_init();
				$timeout = 10;
				curl_setopt($ch, CURLOPT_URL, $url);
				
				if ($postFields && !empty($postFields))
				{
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postfields));
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
					curl_setopt($ch, CURLOPT_TIMEOUT, 222222);
					curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
					curl_setopt($ch, CURLOPT_NOBODY, 1);
			$size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
				}			
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				//curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
				$data = curl_exec($ch);
    
    if (curl_errno($ch)) 
    {
        echo 'Error:' . curl_error($ch);
    }

    curl_close($ch);
    return $data;
}
function alert(){
		echo "Debe colocar la url completa","\n";
		echo "Ejemplo:https://streamlare.com/e/4RLg7lXxmaVl2QAe";
		exit;
}

$server= "https://".$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"];

$id_ext_reg='/(?:http:|https:)*?\/\/(?:www\.|)(?:slmaxed\.com)\/(.*?)\/([a-zA-Z0-9\-]{1,16})/';
			preg_match($id_ext_reg,$_SERVER['QUERY_STRING'],$channel_id);
			if(empty($channel_id[2])){alert();};
$headers=[
	'Host: slmaxed.com',
	'Upgrade-Insecure-Requests: 1',
	'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36 Edg/109.0.1518.61',
	'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
];
function url_exists($url) {
    return curl_init($url) !== false;
}

if(strlen(trim($channel_id[2]))==16){

			$data_cookie=cUrlGetData('https://slmaxed.com/'.$channel_id[1].'/'.$channel_id[2],$headers,$head=1);
			if( preg_match_all( "/set-cookie:(.*?)=(.*?)($|);|,?!/", $data_cookie, $mat_cookies,PREG_SET_ORDER ) ) {
				
			$xsrf_token = $mat_cookies[0][2];
			$streamlare_session= $mat_cookies[1][2];
			$cookie_res=$mat_cookies[2][2];
			}
			

$url = "https://slmaxed.com/api/video/stream/get";

			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

			$headers = array(
			   'Accept: application/json, text/plain, */*',
			   'Content-Type: application/json;charset=UTF-8',
			   'Cookie: _gid=GA1.2.169059624.1681732008; _ga=GA1.1.2017592676.1681732008; _ga_SFDCX9TT9E=GS1.1.1681738932.3.0.1681738932.0.0.0; XSRF-TOKEN='.$xsrf_token.'; streamlare_session='.$streamlare_session.'; qQ5mc1cn22qh17Ptvo6qz5Jc60GstsNzbYXLn7WF='.$cookie_res,
			   'Origin: https://slmaxed.com',
			   'Referer: https://slmaxed.com/'.$channel_id[1].'/'.$channel_id[2],
			   'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36 Edg/112.0.1722.48',
			   'X-CSRF-TOKEN: BgzVaYsdcCvyGasOu103okuqyO7eRhrEAvk2Ry7D',
			   'X-Requested-With: XMLHttpRequest',
			   'X-XSRF-TOKEN:'.$xsrf_token.'=',
			);

			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

			$postfields = '{"id":"'.$channel_id[2].'"}';

			curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);

			//for debug only!
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

			$resp = curl_exec($curl);
			curl_close($curl);
			$data_video=json_decode($resp,true);
			
			$url_video=$data_video['result']['Original']['file'];
			
			if (!empty($url_video)){
					$headers = array(
						'Accept: */*',
						'Accept-Language: en-US,en;q=0.9,es;q=0.8',
						'Connection: keep-alive',
						'Range: bytes=0-',
						'Referer: https://slmaxed.com/'.$channel_id[1].'/'.$channel_id[2],
						'Sec-Fetch-Dest: video',
						'Sec-Fetch-Mode: no-cors',
						'Sec-Fetch-Site: cross-site',
						'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36 Edg/112.0.1722.48',
						'sec-ch-ua: "Chromium";v="112", "Microsoft Edge";v="112", "Not:A-Brand";v="99"',
						'sec-ch-ua-mobile: ?0',
						'sec-ch-ua-platform: "Windows"',
					);
						$data_source=cUrlGetData($url_video,$headers,$head=1,$getRemoteFilesize = 1);					
						preg_match( "/location: (.*)\n*\s*/", $data_source, $match_video);
						
				preg_match( "/content-length: (\d+)/",$data_source, $matc_size );
				header('Content-Type: application/application/x-mpegURL');
				if ($matc_size[1] > 500000) { //si la peli es de mas de 500 gigas usa la Funcion de Big Files Debe tener Big_File.php
				
					echo cUrlGetData(send_attachment($channel_id[2].'.mp4',rtrim($match_video[1]),$headers));
				}else{
					header('Location: ' . filter_var(rtrim($match_video[1]), FILTER_SANITIZE_URL));
				}
			}else{
					header('Location: ' . filter_var(rtrim($data_video['result']['file']), FILTER_SANITIZE_URL));
			}		
};
?>
