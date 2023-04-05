<?php
//*****************************************************
//Cortesia de:"@M3uKodi Telegram Group"
//Fecha : 04/05/2022
//WebSite:https://www.m3ukodi.com
//Mail:m3ukodi@m3ukodi.com
//Donaciones:https://paypal.me/m3ukodi?locale.x=es_XC
//**************************************
//UPSTREAM.TO este PHP es valido para los video contenido en este sistema de Almacenaje
//https://m3ukodi.com/developer/upstream/upstream_player.php?https://upstream.to/ikm378d2an5n
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
		
	function cUrlGetData($url,$headers=null,$head = null,$postFields = null,$proxies = null,$cookie = null) {

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
function eva($file){
					preg_match('/[\'">]eval\((.*)[\'"]\./', $file, $packed);
					$unpack = JavaScriptUnpacker::unpack($packed[1]);
					preg_match('/(?<=data\|)\d+(.*?)\|sources/i', $unpack, $data_match);
					$delay=explode("|",$data_match[1]);
					return $delay;
				}
$server= "https://".$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"];

$id_ext_reg='/(?:http:|https:)*?\/\/(?:www\.|)(?:upstream\.to)\/([a-zA-Z0-9\-]{1,12})/';
			preg_match($id_ext_reg,$_SERVER['QUERY_STRING'],$channel_id);
$headers=[
	'Host: upstream.to',
	'Upgrade-Insecure-Requests: 1',
	'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36 Edg/109.0.1518.61',
	'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
];

if(strlen(trim($channel_id[1]))==12){
	for ($x = 0; $x <= 10; $x++) {
		$file_data=eva(cUrlGetData("https://upstream.to/".$channel_id[1],$headers));
		
			if ((empty($file_data[2]))&&($file_data[16]!=="co")&&(strlen($file_data[124])==43)&& (count($file_data)==129) ){	
				$url_video='https://'.$file_data[16].'.upstreamcdn.co/'.$file_data[128].'/'.$file_data[13].'/'.$file_data[12].'/'.$file_data[127].'/master.m3u8?t='.$file_data[124].'&s='.time().'&e=10800&f='.$file_data[1].'&i=0.0&sp=0';
				if(url_exists($url_video)==false){header('Location: ' . filter_var("https://tinyurl.com/m3ukodivideolost", FILTER_SANITIZE_URL));};
				header('Location: ' . filter_var($url_video, FILTER_SANITIZE_URL));
				break;
			}
			if ((strlen($file_data[124])==43)&&(count($file_data)==129) ){	
				$url_video='https://'.$file_data[17].'.upstreamcdn.co/'.$file_data[128].'/'.$file_data[14].'/'.$file_data[13].'/'.$file_data[127].'/master.m3u8?t='.$file_data[124].'&s='.time().'&e=10800&f='.$file_data[2].'&i=0.0&sp=0';
				header('Location: ' . filter_var($url_video, FILTER_SANITIZE_URL));
				break;
			}			
			if (strlen($file_data[125])==43 ){
				$url_video='https://'.$file_data[17].'.upstreamcdn.co/'.$file_data[129].'/'.$file_data[14].'/'.$file_data[13].'/'.$file_data[128].'/master.m3u8?t='.$file_data[125].'&s='.time().'&e=10800&f='.$file_data[1].'&i=0.0&sp=0';
				header('Location: ' . filter_var($url_video, FILTER_SANITIZE_URL));
				break;
			}

	}
			die("Video perdido intente con otro!!!");
}else{
		echo "Debe colocar la url completa","\n";
		echo "Ejemplo:https://upstream.to/v05000m32xm9";
		exit;				
};


class JavaScriptUnpacker
{
    /**
     * @var string
     */
    protected static $JS_FUNC = 'eval(function(p,a,c,k,e,';
    /**
     * @var string
     */
    protected $script;
    /**
     * @Param $script
     * @return string
     */
    public static function unpack($script)
    {
        return (new self($script))->deobfuscate();
    }
    /**
     * @Param $script
     */
    protected function __construct($script)
    {
        $this->script = $script;
    }
    /**
     * @return string
     */
    protected function deobfuscate()
    {
        if (self::hasPackedCode($this->script, $start) &&
            (($body = $this->findBlock('{', '}', $start + strlen(self::$JS_FUNC), $pos))) &&
            (($params = $this->findBlock('(', ')', $pos + strlen($body), $pos))) &&
            (($end = strpos($this->script, ')', $pos + strlen($params)))) &&
            (($packed = self::findString($params, 1, $pos, $quote))) &&
            (($keywords = self::findString($params, $offset = $pos + strlen($packed) + 2, $pos))) &&
            (preg_match('/^,([0-9]+),([0-9]+),$/', preg_replace('/[\x03-\x20]+/', '',
                substr($params, $offset, $pos - $offset)), $matches)))
        {
            list(, $ascii, $count) = $matches;
            $packed = str_replace('\\' . $quote, $quote, $packed);
            $decode = 'decode' . self::detectEncoding($body);
            $script = $this->$decode($packed, $ascii, $count, explode('|', $keywords));
            $script = str_replace('\\\\', '\\', $script);
            if (self::isDoubleEscaped($script)) {
                $script = str_replace(['\\\'', '\"', '\\\\\'', '\\\"', '\\\\'],
                    ['\'', '"', '\\\\\'', '\\\"', '\\'],  $script);
            }
            $script = self::replaceSpecials($script);
            return substr($this->script, 0, $start) . self::unpack($script) . self::unpack(substr($this->script, $end + 1));
        }
        return $this->script;
    }
    /**
     * @Param string $str
     * @return string
     */
    protected static function replaceSpecials($str)
    {
        $replace = function($str) {
            return str_replace(['\n', '\r', '\t'], ["\n", "\r", "\t"], $str);
        };
        $pieces = [];
        for ($offset = 0; ($string = self::findString($str, $offset, $pos, $quote)) !== false;) {
            $pieces[] = $replace(substr($str, $offset, $pos - $offset));
            $pieces[] = $quote . $string . $quote;
            $offset = $pos + strlen($string) + 2;
        }
        $pieces[] = $replace(substr($str, $offset));
        return implode('', $pieces);
    }
    /**
     * @Param string $str
     * @return bool
     */
    protected static function isDoubleEscaped($str)
    {
        $result = true;
        foreach (["'", '"'] as $quote) {
            $matches = [];
            foreach (['', '\\'] as $i => $slash) {
                for ($matches[$i] = $j = 0, $find = "{$slash}{$quote}", $len = strlen($find);
                    ($pos = strpos($str, $find, $j)) !== false; $j = $pos + $len, $matches[$i] ++);
            }
            list($x, $y) = $matches;
            if ($x !== $y) {
                return false;
            }
            $result = $result && $x;
        }
        return $result;
    }
    /**
     * @Param string $packed
     * @Param int $ascii
     * @Param int $count
     * @Param string $keywords
     * @return string
     */
    protected function decode62($packed, $ascii, $count, $keywords)
    {
        $packed = " $packed ";
        $base = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $encode = function ($count) use (&$encode, $ascii, $base) {
            return ($count < $ascii ? '' : $encode(intval($count / $ascii))) . $base[$count % $ascii];
        };
        $split = '([^a-zA-Z0-9_])';
        while ($count--) {
            if (!empty($keywords[$count])) {
                $pattern = '/' . $split . preg_quote($encode($count)) . $split . '/';
                $packed = preg_replace_callback($pattern, function($matches) use ($keywords, $count) {
                    return $matches[1] . $keywords[$count] . $matches[2];
                }, $packed);
            }
        }
        return substr($packed, 1, -1);
    }
    /**
     * @Param string $packed
     * @Param int $ascii
     * @Param int $count
     * @Param string $keywords
     * @return string
     */
    protected function decode95($packed, $ascii, $count, $keywords)
    {
        $encode = function ($count) use (&$encode, $ascii) {
            return ($count < $ascii ? '' : $encode(intval($count / $ascii))) .
                mb_convert_encoding(pack('N', $count % $ascii + 161), 'UTF-8', 'UCS-4BE');
        };
        $decoded = [];
        while ($count--) {
            $encoded = $encode($count);
            $decoded[$encoded] = !empty($keywords[$count]) ? $keywords[$count] : $encoded;
        }
        return preg_replace_callback('/([\xa1-\xff]+)/', function($match) use ($decoded) {
            return isset($decoded[$match[1]]) ? $decoded[$match[1]] : $match[1];
        }, $packed);
    }
    /**
     * @Param string $buf
     * @Param int $index
     * @Param int $len
     * @return bool
     */
    protected static function isSlashed($buf, $index, $len)
    {
        if ($buf[$index] === '\\') {
            if ($len > 1 && $buf[$index - 1] === '\\') {
                return self::isSlashed($buf, $index - 2, $len - 2);
            }
            return true;
        }
        return false;
    }
    /**
     * @Param string $buf
     * @Param int $offset
     * @Param null|int $start
     * @Param null|string $quote
     * @return bool|string
     */
    protected static function findString($buf, $offset, &$start=null, &$quote=null)
    {
        for ($start = $offset, $len = strlen($buf); $start < $len; $start++) {
            foreach (['"', "'"] as $quote) {
                if ($buf[$start] === $quote) {
                    for ($i = $start + 1; $i < $len; $i++) {
                        if ($buf[$i] === $quote && !self::isSlashed($buf, $i - 1, $i - $start - 1)) {
                            break;
                        }
                    }
                    if ($i === $len) {
                        return false;
                    }
                    return substr($buf, $start + 1, $i - $start - 1);
                }
            }
        }
        return false;
    }
    /**
     * @Param string $open
     * @Param string $close
     * @Param int $offset
     * @Param int $start
     * @return string|false
     */
    protected function findBlock($open, $close, $offset, &$start)
    {
        $buf = substr($this->script, $offset);
        $len = strlen($buf);
        for ($start = 0; $start < $len && $buf[$start] !== $open; $start++);
        for ($i = $start + 1, $skip = 0; $i < $len; $i++) {
            if ($buf[$i] === $close && 0 === $skip--) {
                break;
            }
            foreach (['"', "'"] as $quote) {
                if ($buf[$i] === $quote) {
                    for ($i++; $i < $len && ($buf[$i] !== $quote || $buf[$i - 1] === '\\'); $i++);
                }
            }
            if ($buf[$i] === $open) {
                $skip++;
            }
        }
        if ($start === $len || $i === $len) {
            return false;
        }
        $block = substr($buf, $start, $i - $start + 1);
        $start += $offset;
        return $block;
    }
    /**
     * @Param string $body
     * @return int
     */
    protected static function detectEncoding($body)
    {
        return strpos($body, '161') ? 95 : 62;
    }
    /**
     * @Param string $str
     * @Param null|int $start
     * @return bool
     */
    public static function hasPackedCode($str, &$start=null)
    {
        if (($pos = strpos(strtolower(preg_replace('/[\x03-\x20]+/', '', $str)), self::$JS_FUNC)) !== false) {
            $start = -1;
            do {
                while (preg_match('/[\x03-\x20]/', $str[++$start]));
            } while (0 < $pos--);
            return true;
        }
        return false;
    }
}
?>
