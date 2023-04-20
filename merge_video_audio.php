<?php
//*****************************************************
//Cortesia de:"@M3uKodi Telegram Group"
//Fecha : 20/04/2023      
//WebSite:https://www.m3ukodi.com
//Mail:m3ukodi@m3ukodi.com
//Donaciones:https://paypal.me/m3ukodi?locale.x=es_XC
//**************************************
//Permite Unir 1 Video con Audio
//
//Modo de Uso en la variable $video coloque el video principal y la variable $audio su respectivo audio.

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

if(isset($_GET['hls_audio'])){
	$hls_audio_data="#EXTM3U"."\n".
			   "#EXT-X-ALLOW-CACHE:NO"."\n".
			   "#EXT-X-VERSION:4"."\n".
			   "#EXT-X-MEDIA-SEQUENCE:1"."\n".
			   "#EXTINF:-1"."\n".
			  base64_decode($_GET['hls_audio'])."\n".
			  "#EXT-X-ENDLIST";
		print_r($hls_audio_data);
		exit; 

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
		exit;
}

$server = "https://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}";

//Esta URL ese solo para Ejemplo sacado de FACEBOOK
$video=rtrim('https://video-hou1-1.xx.fbcdn.net/v/t66.35162-29/10000000_6770235166339934_1515314552155960753_n.mp4?_nc_cat=111&ccb=1-7&_nc_sid=9c5c06&efg=eyJ2ZW5jb2RlX3RhZyI6ImRhc2hfbGl2ZV9xZF9mcmFnXzJfdmlkZW8ifQu00253Du00253D&_nc_ohc=l7w-MS1lfUkAX_Dn8Kt&_nc_ht=video-hou1-1.xx&oh=00_AfAxPaehs-T1Bq_HqIgpHpwXLV0oGuzKvcrEczyaEz1Y7A&oe=6444843B');

//Esta URL ese solo para Ejemplo sacado de FACESBOOK
$audio=rtrim('https://video-hou1-1.xx.fbcdn.net/v/t42.1790-29/10000000_1352128152305646_7514854947205741944_n.mp4?_nc_cat=111&ccb=1-7&_nc_sid=9c5c06&efg=eyJ2ZW5jb2RlX3RhZyI6ImRhc2hfbGl2ZV9tZF9mcmFnXzJfYXVkaW8ifQu00253Du00253D&_nc_ohc=XrJJ73-KW_IAX-cCKRP&_nc_oc=AQkNdz8cHipja3jLCU1y0fiPPeF8A6UF7ddKfAmredSBPMs0e3HLBC5O1tpqG4X89e2QqKfYaGc5j1Bp9ZaRWn-Q&_nc_ht=video-hou1-1.xx&oh=00_AfD9RbH_o5s9FgkMKmyrfBHaZqFOGXHUtc4mBLu3WiX2sw&oe=644578D0');

$hls_master= '#EXTM3U'."\n".
			 '#EXT-X-VERSION:4'."\n".
			 '#EXT-X-MEDIA:URI="'.$server."?hls_audio=".base64_encode($audio).'",TYPE=AUDIO,GROUP-ID="audio_tracks'."\n".
			 '#EXT-X-STREAM-INF:AUDIO="audio_tracks"'."\n".
			  $server."?hls_video=".base64_encode($video)."\n".
			 '#EXT-X-ENDLIST';
print_r($hls_master);

?>
