<?php
//*****************************************************
//Cortesia de:"@M3uKodi Telegram Group"
//Fecha : 04/05/2022
//WebSite:https://www.m3ukodi.com
//Mail:m3ukodi@m3ukodi.com
//Donaciones:https://paypal.me/m3ukodi?locale.x=es_XC
//**************************************
//uqload.co este PHP es valido para los video contenido en este sistema de Almacenaje
//https://m3ukodi.com/developer/uqload/uqload.php?https://uqload.co/pbdd4svfn7bw.html
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
	
function url_exists($url) {
    return curl_init($url) !== false;
}

$id_ext_reg='/(?:http:|https:)*?\/\/(?:www\.|)(?:uqload\.co)\/([a-zA-Z0-9\-]{1,12})/';
			preg_match($id_ext_reg,$_SERVER['QUERY_STRING'],$channel_id);

$headers=[
	'Host: uqload.co',
	'Upgrade-Insecure-Requests: 1',
	'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36 Edg/109.0.1518.61',
	'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
];

if(strlen(trim($channel_id[1]))==12){

		$embed_url="https://uqload.co/embed-".$channel_id[1].".html";
		$file_data=cUrlGetData($embed_url,$headers);
		preg_match('/sources:.*?\[\"(.*?)\"\]/',$file_data,$url_mach);
		if(url_exists($url_mach[1])==false){die("Video perdido intente con otro!!!");}
		$headers = array(
		   'authority: www.uqload.co',
		   'referer: '.$embed_url,
		   'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/111.0.0.0 Safari/537.36 Edg/111.0.1661.62',
		);
		$data_video=cUrlGetData($url_mach[1],$headers,$head=1,$getRemoteFilesize=1); //Genera Datos de Video

		    $content_length = "unknown";
			$status = "unknown";

			if( preg_match( "/^HTTP\/1\.[01] (\d\d\d)/", $data_video, $matches ) ) {
			  $status = (int)$matches[1];
			} elseif( preg_match( "/^HTTP\/2 (\d\d\d)/", $data_video, $matches ) ) {
			  $status = (int)$matches[1];
			}

			if( preg_match( "/Content-Length: (\d+)/", $data_video, $matches ) ) {
			  $content_length = (int)$matches[1];
			} elseif( preg_match( "/content-length: (\d+)/", $data_video, $matches ) ) {
				$content_length = (int)$matches[1];
			}

			if( $status !== 200 || ($status > 300 && $status <= 308) ) {
			  die("No Valido!!");
			}
			
			if( preg_match( "/Content-Type:(.*)\n*\s*/", $data_video, $matches ) ) {
			  $content_type = (int)$matches[1];
			}
			header('Content-Type: application/octet-stream');
			header('Content-type: '.$content_type);
			header('Content-Transfer-Encoding:­ binary');
			header('Accept-Ranges: bytes');
			header('Content-Range: bytes 0-'.$content_length);
			header('Content-length: '.$content_length);
			header('Content-Disposition: inline; filename="'.$channel_id[1].'.mp4'.'"');
			header("Cache-Control: max-age=2592000, public");
			header("Expires: ".gmdate('D, d M Y H:i:s', time()+2592000) . ' GMT');
			header('Pragma: public');
				while (ob_get_level()) {
					ob_end_clean();
				}
		echo cUrlGetData($url_mach[1],$headers);

}else{
		echo "Debe colocar la url completa","\n";
		echo "Ejemplo:https://uqload.co/v05000m32xm9";
		exit;				
};

?>
