<?php
//*****************************************************
//Cortesia de:"@M3uKodi Telegram Group"
//Fecha : 31/05/2024      
//WebSite:https://www.m3ukodi.com
//Mail:m3ukodi@m3ukodi.com
//Donaciones:https://paypal.me/m3ukodi?locale.x=es_XC
//**************************************
header("Content-Type: text/plain");	
function cUrlGetData($url) {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_COOKIE => 'pp_main_c7706edb0691aa2d04350b5108d20710=1; dom3ic8zudi28v8lr6fgphwffqoz0j6c=040d5630-e68f-4c37-b8e0-0bf2ee1a5eba%3A1%3A1; sb_main_00b54f2f9fce66fb6c25187caf6030f9=1; pp_sub_c7706edb0691aa2d04350b5108d20710=3; sb_page_00b54f2f9fce66fb6c25187caf6030f9=3; sb_count_00b54f2f9fce66fb6c25187caf6030f9=3; sb_onpage_00b54f2f9fce66fb6c25187caf6030f9=1; sc_is_visitor_unique=rx12360448.1717172696.BC4040C364A64FA985681D8C6767C79A.4.3.2.1.1.1.1.1.1',
            CURLOPT_HTTPHEADER => [
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36 Edg/125.0.0.0',
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
                'sec-ch-ua: "Microsoft Edge";v="125", "Chromium";v="125", "Not.A/Brand";v="24"',
                'sec-ch-ua-mobile: ?0',
                'sec-ch-ua-platform: "Windows"',
                'upgrade-insecure-requests: 1',
                'sec-fetch-site: same-origin',
                'sec-fetch-mode: navigate',
                'sec-fetch-user: ?1',
                'sec-fetch-dest: document',
                'referer: https://photocalltv.site/',
                'accept-language: es,es-ES;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6,es-MX;q=0.5',
                'priority: u=0, i',
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo 'cURL Error #:' . $err;
        } else {
            return $response;
        }
}


function chr_fix($title){
	$search =  array ('ñ', 'á', 'é', 'í', 'ó', 'ú', 'Ñ', 'Á', 'É', 'Í', 'Ó', 'Ú');
	$replace = array ('n', 'a', 'e', 'i', 'o', 'u', 'N', 'A', 'E', 'I', 'O', 'U');
	$title = str_replace($search, $replace, $title);
	return $title; 
}

$html_data = file_get_contents('https://photocalltv.site/'); // Obtener el HTML de la página

// Verificar si se ha obtenido correctamente el HTML
if (!$html_data) {
    die('Error al obtener los datos HTML');
}

// Regex para capturar los enlaces dentro de la sección del canal group
$reg_canal_group = '/<div class="canales">(.*?)<\/div><\/section>/s'; 
preg_match($reg_canal_group, $html_data, $match_canal_group);

// Verificar si se han capturado correctamente los enlaces
if (empty($match_canal_group[0])) {
    die('No se han encontrado enlaces en la sección del navbar');
}

// Ignorar el primer enlace (que corresponde a "Home")
if (count($match_canal_group) > 0) {
    array_shift($match_canal_group); // Elimina el primer elemento del array
}

preg_match_all('/<div class="dropdown"><a href=".*?" class="card.*?" title="(.*?)"><img data-src="(.*?)" alt=".*?"><\/a><ul class="dropdown-content">(.*?)<\/ul><\/div>/s', $match_canal_group[0], $matches);

$server = "https://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}";
if(!isset($_SERVER['QUERY_STRING']) || empty($_SERVER['QUERY_STRING'])|| strpos($_SERVER['QUERY_STRING'], 'auto') !== false)
{
echo "#EXTM3U".PHP_EOL."#EXTINF:-0 tvg-logo=".chr(34)."https://i.imgur.com/zaB5RWk.jpg".chr(34).", M3ukodi+".PHP_EOL."https://i.imgur.com/g4k0TOQ.mp4".PHP_EOL;    
     for ($i = 0; $i < count($matches[0]); $i++) {
        preg_match_all('/<li><a href="(.*?)" target="_blank">Directo<\/a><\/li>/', $matches[3][$i], $links);
        foreach ($links[1] as $index => $href) {
                //$id_data= $id_matches[1];
                $data_name=htmlspecialchars_decode($matches[1][$i]);
                $url_data= $links[1][$index];
                $dirname = pathinfo($url_data, PATHINFO_DIRNAME);
                $data_logo = "https:".$matches[2][$i];
                $group_logo = "https://tinyurl.com/logom3ukodi";
                $group_name="PHOTOCALL TV";
                
                echo '#KODIPROP:inputstream.adaptive.stream_headers=User-Agent=Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Mobile Safari/537.36'.PHP_EOL;
                echo '#EXTGRP:'.$group_name.PHP_EOL;
                echo '#EXTVLCOPT:http-referrer='.$dirname.PHP_EOL;
                echo '#EXTVLCOPT:network-caching=1000'.PHP_EOL;
                echo '#EXTVLCOPT:audio-track="es-ES"'.PHP_EOL;
                echo '#EXTVLCOPT:http-user-agent=Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Mobile Safari/537.36'.PHP_EOL;
                echo '#EXTVLCOPT--http-reconnect=true'.PHP_EOL;
                echo '#EXTINF:0 type="stream"  group-title="'.$group_name.'" group-logo="'.$group_logo.'"  tvg-logo="'.$data_logo.'",'.chr_fix(strtoupper($data_name)).PHP_EOL;
                echo $server."?".$url_data.PHP_EOL;
                //echo get_url($id_data).PHP_EOL;
        }
     }

}else{

// Uso de la función cUrlGetData
$base_url = "https://photocalltv.site/".$_SERVER['QUERY_STRING'];
$result = cUrlGetData($base_url);
$pattern = '/atob\("([A-Za-z0-9+\/=]+)"\)/';
    if (preg_match($pattern, $result, $matches)) {
        $encoded_string = $matches[1];
        $decoded_string = base64_decode($encoded_string);
        header("Location:". $decoded_string);
    } else {
        return null;
    }
}
?>
