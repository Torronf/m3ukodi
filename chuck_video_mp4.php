<?php
//*****************************************************
//Cortesia de:"@M3uKodi Telegram Group"
//Fecha : 06/01/2023      
//WebSite:https://www.m3ukodi.com
//Mail:m3ukodi@m3ukodi.com
//Donaciones:https://paypal.me/m3ukodi?locale.x=es_XC
//**************************************
//Este PHP permite segmentar un video de gran tamaño en porciones, para ser transmitido sin problema por su tamaño.

ini_set("display_errors",1);
ini_set("memory_limit","128M");
ini_set('upload_max_filesize', '500M');
ini_set('post_max_size', '5000M');
ini_set('max_input_time', 300);
ini_set('max_execution_time', 0);
ini_set("allow_url_fopen", "On");

ignore_user_abort(true);
clearstatcache();
header("X-Robots-Tag: noindex, nofollow", true);
header("Content-Type: text/plain");	

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
function stream_video($link,$size,$type){
	$chunks =get_chunks($size);
	$domain=parse_url($link,PHP_URL_HOST);
        $protocol = parse_url($link, PHP_URL_SCHEME);
		  $opts = array(
			$protocol=>array(
				'method'=>"GET",
					"HTTP/1.1 206 Partial Content",
					"Host:".$domain,
					"Referer: https://".$domain."/",
					"Origin: https://".$domain,
					"Content-Type:".$type,
					"Accept-Ranges: bytes",
					"cache-control: no-cache",
					"Content-Range: bytes 0-". ($size - 1) . '/' . $size,
					"Server: openresty",
					"Date: ".gmdate('D, d M Y H:i:s', time()+2592000) . ' GMT',
					"Content-Length:".$size,
					"Last-Modified: ".gmdate('D, d M Y H:i:s', time()+2592000) . ' GMT',
					"Connection: keep-alive",
					"ETag: '6472c99c-be069d0'",
					"Access-Control-Allow-Origin: *",
					"Allow: OPTIONS, GET, HEAD, POST",
					"Access-Control-Allow-Headers: Upgrade-Insecure-Requests,Range,Content-Type,If-Modified-Since",
					"Access-Control-Expose-Headers: ETag,Expires,Location,Content-Length,Accept-Ranges,Content-Encoding,Content-Range"					
			),
				"ssl" => array(
					"verify_peer" => false,
					"verify_peer_name" => false,
				),
		  );
		$context = stream_context_create($opts);
        $stream = fopen($link, 'rb', false, $context);
        $info = stream_get_meta_data($stream);
        $end = !$info['eof'];
		$buffer="";
			foreach ($chunks as $length) {
				$bytes = strlen($buffer);
					while ($bytes < $length && $end) {
						$data = fread($stream, min(1024, $length - $bytes));
						$buffer .= $data;
						$bytes = strlen($buffer);
						$info = stream_get_meta_data($stream);
						$end = !$info['eof'] && $data;
					}
					$chunk = substr($buffer, 0, $length);
					$buffer = $bytes > $length ? substr($buffer, $length) : '';
					print $chunk;
					ob_flush();
			}
		fclose($stream);
return true;
}
$link=rtrim('url_video');// Aqui coloque el requerimiento del archivo de video a ver
						$head = array_change_key_case(get_headers($link, TRUE));						
							$size = $head['content-length']; //Tamaño del Archivo
							$type = $head['content-type'][1]; //Tipo de Archivo
							echo stream_video($link,$size,$type); //Play el Video
exit(0);
?>
