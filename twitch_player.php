<?php
//*****************************************************
//Cortesia de:"@M3uKodi Telegram Group"
//Fecha :03/04/2022       
//WebSite:https://www.m3ukodi.com
//Mail:m3ukodi@m3ukodi.com
//Donaciones:https://paypal.me/m3ukodi?locale.x=es_XC
//**************************************
//Plataforma Twitch {https://www.twitch.tv/}

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
// Solo para Pruebas
/*
https://m3ukodi.com/developer/twich/twich_player.php?113kkctoon id=833828528
https://m3ukodi.com/developer/twich/twich_player.php?xt_t419r id=833835522
https://m3ukodi.com/developer/twich/twich_helix.php?ft10x02k
*/
$channel=$_SERVER['QUERY_STRING'];
arch_php();
$Client_Id='kimne78kx3ncx6brgo4mv6wki5h1ko'; // With 

$headers = array(
   'Accept: */*',
   'Accept-Language: en-US',
   'Accept-Encoding:gzip',
   'Client-Id: '.$Client_Id,
   'Content-Type: application/json; charset=utf-8',
   'Host:gql.twitch.tv',
   'User-Agent: okhttp/4.9.3',
);

$url_qql= "https://gql.twitch.tv/gql";
//Para entrada Login UserbyLoginQuery
$postFields = '[{"operationName": "UserbyLoginQuery","variables": {"login": "'.$channel.'"},"extensions": {"persistedQuery": {"version": 1,"sha256Hash": "482693e74d50b2d27696dfe31856933b50eef4ff9218e617dedf656c32ff72f1"}}}]';
$json_gql_ulq=json_decode(cUrlGetData($url_qql,$headers,$postFields),true);

//Para Sacar el token
$postFields = '[{"operationName": "PlaybackAccessToken","variables": {"isLive": true,"login": "'.$channel.'","isVod": false,"vodID": "","playerType": "pulsar"},"extensions": {"persistedQuery": {"version": 1,"sha256Hash": "0828119ded1c13477966434e15800ff57ddacf13ba1911c129dc2200705b0712"}}}]';
$json_gql_pat=json_decode(cUrlGetData($url_qql,$headers,$postFields),true);
$token=$json_gql_pat[0]['data']['streamPlaybackAccessToken']['value'];
$signature=$json_gql_pat[0]['data']['streamPlaybackAccessToken']['signature'];

//Para Sacar el ID del Canal
$postFields = '[{"operationName":"VideoPlayerStreamInfoOverlayChannel","variables":{"channel":"'.$channel.'"},"extensions":{"persistedQuery":{"version":1,"sha256Hash":"a5f2e34d626a9f4f5c0204f910bab2194948a9502089be558bb6e779a9e1b3d2"}}},{"operationName":"ActiveWatchParty","variables":{"channelLogin":"'.$channel.'"},"extensions":{"persistedQuery":{"version":1,"sha256Hash":"4a8156c97b19e3a36e081cf6d6ddb5dbf9f9b02ae60e4d2ff26ed70aebc80a30"}}},{"operationName":"VideoPlayerStatusOverlayChannel","variables":{"channel":"'.$channel.'"},"extensions":{"persistedQuery":{"version":1,"sha256Hash":"938d155c890df88b5da53592e327d36ae9b851d2ee38bdb13342a1402fc24ad2"}}},{"operationName":"VideoPlayerPixelAnalyticsUrls","variables":{"login":"'.$channel.'","allowAmazon":false,"allowComscore":false,"allowGoogle":false,"allowNielsen":false},"extensions":{"persistedQuery":{"version":1,"sha256Hash":"bb2a169a523dc7bb6a4369f14548f11b89124aa306b3ab3a7bf1b059e779f296"}}},{"operationName":"ChannelSkins","variables":{"channelLogin":"'.$channel.'"},"extensions":{"persistedQuery":{"version":1,"sha256Hash":"3b0e2221193d8775016ade000749331c01e4eb4b6e814f8df65ca7a450cc6443"}}}]';
$json_gql_vpsic=json_decode(cUrlGetData($url_qql,$headers,$postFields),true);
$channel_id=$json_gql_vpsic[0]['data']['user']['id']; // => 833835522

//Para Sacar el ChannelVodsQuery
$postFields = '[{"operationName": "ChannelVodsQuery","variables": {"channelId": "'.$channel_id.'","types": ["ARCHIVE"],"vodCount": 1},"extensions": {"persistedQuery": {"version": 1,"sha256Hash": "1d7c6cbbfaf7344c867619bc5d16980d953013922cd08c9018a56b8f5182aa56"}}}]';
$json_gql_cv=json_decode(cUrlGetData($url_qql,$headers,$postFields),true);

$video_url=rtrim('https://usher.ttvnw.net/api/channel/hls/'.$channel.'.m3u8?client_id='.$Client_Id.'&token='.$token.'&sig='.$signature.'&allow_source=true&allow_audio_only=true');
header('Location: ' . filter_var($video_url, FILTER_SANITIZE_URL));

?>
