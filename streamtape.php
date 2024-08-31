<?php
//*****************************************************
//Cortesia de:"@M3uKodi Telegram Group"
//Fecha : 31/08/2024
//WebSite:https://www.m3ukodi.com
//Mail:m3ukodi@m3ukodi.com
//Donaciones:https://paypal.me/m3ukodi?locale.x=es_XC
//**************************************

//Allowed memory size exhausted in fpasstrhu
ini_set("display_errors",0);
ini_set("memory_limit","128M");
ini_set('upload_max_filesize', '500M');
ini_set('post_max_size', '5000M');
ini_set('max_input_time', 300);
ini_set('max_execution_time', 0);
ignore_user_abort(true);
clearstatcache();
header("X-Robots-Tag: noindex, nofollow", true);
header("Content-Type: text/plain");	

ob_start();
function flush_buffers(){
    ob_end_flush();
    ob_flush();
    flush();
    ob_start();
}
		
function get_chunks($size) {
        $chunks = array();
        $p = $pp = 0;

        for ($i = 1; $i <= 8 && $p < $size - $i * 0x20000; $i++) {
            $chunks[$p] = $i * 0x20000;
            $pp = $p;
            $p += $chunks[$p];
        }

        while ($p < $size) {
            $chunks[$p] = 0x100000;
            $pp = $p;
            $p += $chunks[$p];
        }

        $chunks[$pp] = ($size - $pp);
        if (!$chunks[$pp]) {
            unset($chunks[$pp]);
        }

        return $chunks;
    }
	
function readfile_chunked($filename,$size,$retbytes=true) {
   $chunksize =get_chunks($size);
   $buffer = '';
   $cnt =0;

   $handle = fopen($filename, 'rb');
   if ($handle === false) {
       return false;
   }
   $info = stream_get_meta_data($handle);
   $end = !$info['eof'];

	foreach ($chunksize as $length) {
		$bytes = strlen($buffer);
			while ($bytes < $length && $end) {
				$data = fread($handle, min(1024, $length - $bytes));
				$buffer .= $data;
				$bytes = strlen($buffer);
				$info = stream_get_meta_data($handle);
				$end = !$info['eof'] && $data;
			}
				$chunk = substr($buffer, 0, $length);
				$buffer = $bytes > $length ? substr($buffer, $length) : '';
				echo $chunk;
				flush_buffers();
					if ($retbytes) {
						$cnt += strlen($buffer);
					}
	}
			$status = fclose($handle);

		   if ($retbytes && $status) {
			   return $cnt; 
		   }
   return $status;
}

function cUrlGetData($url,$headers=null,$head = null,$getRemoteFilesize = null,$postFields = null,$proxies = null,$cookie = null) {
 global $head_data , $size, $contentType; 
				$ch = curl_init();
				$timeout = 10;
				curl_setopt($ch, CURLOPT_URL, $url);
				
				if ($postFields && !empty($postFields))
				{
					$postfields=rtrim(http_build_query($postFields));
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
					curl_setopt($ch, CURLOPT_TIMEOUT, 222222);
					curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
					curl_setopt($ch, CURLOPT_NOBODY, 1);
			$size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
			$contentType=curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
				}			
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_REFERER, 'https://streamtape.com/e/oAyl8rV67auW39');
				//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
				curl_setopt($ch, CURLOPT_ENCODING , '');
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				//curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
				$video_path = curl_exec($ch);
    
    if (curl_errno($ch)) 
    {
        echo 'Error:' . curl_error($ch);
    }

    curl_close($ch);
	
	if ($video_path) return $video_path;else return FALSE;
}

$server= "https://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
if (empty($_SERVER['QUERY_STRING']) || empty($_SERVER['QUERY_STRING'])) {
   header('HTTP/1.0 400 Bad Request');
				echo 'Ejemplo:'.$server.'?https://streamtape.com/v/klP0x2V8jzhO1QX'."\n";
   				echo "Debe colocar la url completa","\n";
   die();
   
}else{
	$headers=array('Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
		'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
		'Accept-Encoding: deflate',
		'Connection: keep-alive',
		'Upgrade-Insecure-Requests: 1');
	$id_ext_reg='/(?:http:|https:)*?\/\/(?:www\.|)(?:.*\.*)\/.*\/([a-zA-Z0-9\-]{1,15})/';
	preg_match($id_ext_reg,$_SERVER['QUERY_STRING'],$channel_id);

			if((strlen(trim($channel_id[1]))==15)||(!empty($channel_id[1]))){
			
				$url_data=cUrlGetData("https://streamtape.com/e/".$channel_id[1],$headers);

			}else{
				echo "Debe colocar la url completa","\n";
				echo "Ejemplo:https://streamtape.com/e/L2LGqAjkv8CRwp1";
			exit;				
			};
				preg_match('/showtitle\":\"([^\"]+)/', $url_data, $title);
				if(empty($title[1])){$filename="m3ukodi.mp4";}else{$filename=$title[1];};

				if (preg_match_all("/\(\'\w+\'\)\.innerHTML\s*\=\s*(.*?)\;/", $url_data,$url_match)) {


					$video_filter=$url_match[1][count($url_match[1])-1];
					$video_filter=str_replace("'",'"',$video_filter);
					$video_path=explode("+",$video_filter);
					$video_out="";
					
					
					for ($k=0;$k<count($video_path);$k++) {
						$s=trim($video_path[$k]);
						preg_match("/\(?\"([^\"]+)\"\)?(\.substring\((\d+)\))?(\.substring\((\d+)\))?/",$s,$p);
						if (isset($p[3]) && isset($p[5]))
						$video_out .=substr(substr($p[1],$p[3]),$p[5]);
						elseif (isset($p[3]))
						$video_out .=substr($p[1],$p[3]);
						else
						$video_out .=$p[1];
					}
						$link=$video_out;
						$link .= "&stream=1";
						if ($link[0]=="/") urldecode($link="https:".$link);
				}
/*
Parte Importante para MP4 grandes
*/
						ob_end_clean();
						

						if(isset($_SERVER['HTTP_RANGE'])) {
							stream_context_set_default([
								'http' => [
									'header' => "Range: " . $_SERVER['HTTP_RANGE']
								],
								'ssl' => [
									"verify_peer" => false,
									"verify_peer_name" => false,
								]
							]);
						}

						$headers = get_headers($link, 1);
					
						if(isset($headers['Accept-Ranges']))                 { header('Accept-Ranges: ' . $headers['Accept-Ranges']);  }
						if(isset($headers['Access-Control-Allow-Headers']))  { header('Access-Control-Allow-Headers:  ' . $headers['Access-Control-Allow-Headers']);}
						if(isset($headers['Access-Control-Allow-Origin'][1])){ header('Access-Control-Allow-Origin:' . $headers['Access-Control-Allow-Origin'][1]); }
						if(isset($headers['Access-Control-Expose-Headers'])) { header('Access-Control-Expose-Headers:  ' . $headers['Access-Control-Expose-Headers']);}
						if(isset($headers['Allow']))                         { header('Allow:  ' . $headers['Allow']);}
						if(isset($headers['Cache-Control']))                 { header('Cache-Control:  ' . $headers['Cache-Control']);}
						if(isset($headers['Connection'][1]))                 { header('Connection:' . $headers['Connection'][1]); }
						if(isset($headers['Content-Length']))                { header('Content-Length:' . $headers['Content-Length']); } 
						if(isset($headers['Content-Range']))                 { header('Content-Range: ' . $headers['Content-Range']);  }
						if(isset($headers['Content-Type'][1]))               { header('Content-Type:  ' . $headers['Content-Type'][1]);}
						if(isset($headers['ETag']))                          { header('ETag:  ' . $headers['ETag']);}
						if(isset($headers['Expires']))		                 { header('Expires: '.gmdate('D, d M Y H:i:s',time()+2592000).' GMT');}
						if(isset($headers['Last-Modified']))                 { header('Last-Modified:' . $headers['Last-Modified']); }
						
																			  header('Content-type:application/vnd.apple.mpegurl; charset=utf-8');
																			  header('Content-Disposition: attachment; filename="' . $fileName . '"');
	

						if($_SERVER['REQUEST_METHOD'] == 'HEAD') { exit; }

						echo readfile_chunked($link,$headers['Content-Length']);
}
?>
