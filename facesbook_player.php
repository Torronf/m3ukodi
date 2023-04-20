<?php
//*****************************************************
//Cortesia de:"@M3uKodi Telegram Group"
//Fecha : 07/09/2021       
//WebSite:https://www.m3ukodi.com
//Mail:m3ukodi@m3ukodi.com
//Donaciones:https://paypal.me/m3ukodi?locale.x=es_XC
//**************************************
//https://www.facebook.com/video/embed?video_id=547663790513218&ref=sharing

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
				curl_setopt($ch, CURLOPT_ENCODING , '');
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
$texto_int= "   *********************************************************"."\n".
				"   *  Cortesia de:@M3uKodi Telegram Group                  *"."\n".
				"   *  WebSite:https://www.m3ukodi.com                      *"."\n".
				"   *  Mail:m3ukodi@m3ukodi.com                             *"."\n".
				"   *  Desarrollado:20/04/2023                              *"."\n".
				"   *  Donaciones:https://paypal.me/m3ukodi?locale.x=es_XC  *"."\n".
				"   *********************************************************"."\n\n".

				"Uso:"."\n"."https://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?https://www.facebook.com/video/embed?video_id=780802066719820"."\n\n".
				"Error Registrado:"."\n";

if(isset($_GET['hls_audio'])){
	$hls_audio_data="#EXTM3U"."\n".
			   "#EXT-X-ALLOW-CACHE:NO"."\n".
			   "#EXT-X-VERSION:4"."\n".
			   "#EXT-X-MEDIA-SEQUENCE:1"."\n".
			   "#EXTINF:-1"."\n".
			  base64_decode($_GET['hls_audio'])."\n".
			  "#EXT-X-ENDLIST";
		print_r($hls_audio_data);

}
if(isset($_GET['hls_video'])){
$hls_video_data = "#EXTM3U"."\n".
			 "#EXT-X-ALLOW-CACHE:NO"."\n".
			 "#EXT-X-VERSION:4"."\n".
			 "#EXT-X-MEDIA-SEQUENCE:1"."\n".
			 "#EXTINF:-1"."\n".
			   base64_decode($_GET['hls_video'])."\n".
			 "#EXT-X-ENDLIST"."\n";
		print_r($hls_video_data);
}

$server = "https://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}";
if (empty($_SERVER['QUERY_STRING'])){die($texto_int);};

$id_ext_reg='/(?:https?:\/\/)?:www.|web.|m.?facebook.com\/?:video.php\/?v=\d+\?v=\d+|\S+\/videos\/\S+\/\d+|([0-9_-]{15,17})|\d+\/?/';
			preg_match($id_ext_reg,urldecode($_SERVER['QUERY_STRING']),$channel_id);
			$face_video=rtrim('https://www.facebook.com/video/embed?video_id='.$channel_id[0]); //547663790513218,780802066719820,3604749446423809
	
$headers = array(
   'authority: m.facebook.com',
   'origin: https://facebook.com',
   'referer: https://facebook.com/',
   'cookie: c_user=812455362;xs=44%3A3lcavsTQ9f9kng%3A2%3A1664282856%3A-1%3A6099%3A%3AAcVNWeDmGPwjzmFY2vMYvKiiTpg9TrdULNAsAI2Jm0M;',
   'sec-fetch-mode: navigate',
   'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36 Edg/112.0.1722.39',
);

				//Extrae Data Source	
				$data_source=cUrlGetData($face_video,$headers);
				$data_source=urldecode(str_replace("\\", "", $data_source));
				$source = ["\u00253A", "\u00252F", "\u00253F", "\u00253D", "\u002526", "\u00257B", "\u00257D", "\u002522", "\u00252C", "\u00255C", "\u00255D", "\u00255B", "\\"];
				$replace = [":", "/", "?", "=", "&", "{", "}", "\"", ",", "\\", "[", "]", ""];
				$data_source = str_replace($source, $replace, htmlspecialchars_decode($data_source));

				$qualities = ["hd_src_no_ratelimit", "hd_src", "sd_src_no_ratelimit", "sd_src","src"];				
					foreach ($qualities as $q) {
						if (preg_match("@{$q}\":\"(https?://[^\"'><\r\n\t ]+)@i",$data_source, $link)) {						
							$link = $link[1];
							$filename = substr($link, strrpos($link, '/') + 1);
							$filename = explode('?', $filename);
							$filename = $filename[0];
							$dominio=parse_url($link, PHP_URL_HOST);
							
							$headers=[
								'Accept-encoding:gzip',
								'Connection:Keep-Alive',
								'Host:'.$dominio,
								'Referer:https:/'.$dominio,
								'range:bytes=0-',
								'User-agent:stagefright/1.2 (Linux;Android 7.1.2)',								
							];
							$data_info=cUrlGetData($link ,$headers,$head=1,$getRemoteFilesize=1);
							
							if( preg_match( "/HTTP\/2 ([0-9\-]{3})/", $data_info, $matches) ) {
								
							  $status = (int)$matches[1];
					  			if( $status !== 200 || ($status > 300 && $status <= 308) ) {
									if( $status == 302){
										preg_match_all( "/([\w-]+): (.*)/", $data_info, $matches_info);
										$url_video=rtrim($matches_info[2][0]);
										$type_video=$matches_info[2][9];
										$size_video=$matches_info[2][14];

											header('Content-Type: application/octet-stream');
											header('Content-type: '.$type_video);
											header('Content-Transfer-Encoding:­ binary');
											header('Accept-Ranges: bytes');
											header('Content-Range: bytes 0-'.$size_video);
											header('Content-length: '.$size_video);
											header('Content-Disposition: inline; filename="'.$filename.'.mp4'.'"');
											header("Cache-Control: max-age=2592000, public");
											header("Expires: ".gmdate('D, d M Y H:i:s', time()+2592000) . ' GMT');
											header('Pragma: public');
										while (ob_get_level()) {
											ob_end_clean();
										}

										echo cUrlGetData($url_video);
										break;
									}

									}elseif(preg_match("/video\":\[{\"url\":\"(https?:\/\/[^\"\"><\r\r\t]+).*?audio\":\[\{\"url\":\"(https?:\/\/[^\"'><\r\n\t ]+)/i",$data_source, $link)){; //https://www.facebook.com/watch/v=238984215280174
										header("Content-Type: video/mp4");
										$hls_master= '#EXTM3U'."\n".
													 '#EXT-X-VERSION:4'."\n".
													 '#EXT-X-MEDIA:URI="'.$server."?hls_audio=".base64_encode($link[2]).'",TYPE=AUDIO,GROUP-ID="audio_tracks'."\n".
													 '#EXT-X-STREAM-INF:AUDIO="audio_tracks"'."\n".
													  $server."?hls_video=".base64_encode($link[1])."\n".
													 '#EXT-X-ENDLIST';
										print_r($hls_master);

									}else{
										die("Video No Compatible....!!");
									}
								}
							}
						}
?>
