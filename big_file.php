<?php

/**
Función de descarga de archivos en PHP.
    Versión 1.4
    Derechos de autor (c) 2014 성기진 Kijin Sung
    Modificado por ssut
    Modificado por M3uKodi (2023)
    Licencia: Licencia MIT (también conocida como Licencia X11)
        http://www.olis.or.kr/ossw/license/license/detail.do?lid=1006
Esta función admite las siguientes características:
    No rompe los caracteres UTF-8. (Estándares RFC2231/5987 y considerando los navegadores)
    Elimina los caracteres especiales cuando incluye caracteres que no se pueden usar por el sistema operativo.
    Agrega la cabecera Cache-Control y Expires cuando se desea habilitar la caché.
    Corrige el error de descarga al usar la caché y IE <= 8.
    Descarga reanudable. (Detecta automáticamente la cabecera de rango y genera automáticamente la cabecera Accept-Ranges)
    Corrige la fuga de memoria cuando se descarga un archivo grande.
    Admite la limitación de velocidad.
    Cómo utilizar: send_attachment('nombre de archivo que se proporciona al cliente', 'ruta de archivo o URL remota', [período de caché], [límite de velocidad]);
    Por ejemplo, descarga el archivo 'foo.jpg' desde el servidor al cliente con el nombre '사진.jpg'.
    send_attachment('사진.jpg', '/srv/www/files/uploads/foo.jpg');
    A continuación, descarga el archivo 'bar.mp3' desde el servidor con caché de 24 horas y límite de velocidad de 300KB/s.
    send_attachment('bar.mp3', '/srv/www/files/uploads/bar.mp3', 60 * 60 * 24, 300);
    Retorna: true si se envía correctamente, de lo contrario false.
Precaución:
    Por favor, ejecutar 'exit' cuando finalice la transferencia. (el archivo puede estar corrupto)
    No se asegura que la versión de PHP sea muy baja (< 5.1) o que el entorno no sea UTF-8.
    La limitación de velocidad es muy peligrosa cuando se usa FastCGI/FPM. Se recomienda utilizar la limitación de velocidad del servidor web.
    Algunas versiones de Android no admiten la codificación UTF-8.
*/
//https://gist.github.com/ssut/a3d97c7a35e5458687ed
/*
Modo de Uso:
 Copie big_file.php en el mismo directorio suyo
Coloque al inicio de su PHP origen:
            require_once './big_file.php';
            
Dentro de su PHP al momentpo de llamar el archivo lo siguiente:
$headers=[ //El Header segun sea necesario para el acceso al server origen.
			'Accept-Language: en-US,en;q=0.9,es;q=0.8',
			'Connection: keep-alive',
			'Range: bytes=0-',
			'Referer:
];
echo send_attachment('filename.mp4',rtrim($url_origen),$headers);
*/
function send_attachment($filename, $server_filename, $headers=null, $expires = 0, $speed_limit = 0) {
    $remote = false;

    // check filename
    if (strpos($server_filename, 'http') === false) {
        if (!file_exists($server_filename) || !is_readable($server_filename)) {
            return false;
        }
        if (($filesize = filesize($server_filename)) == 0) {
            return false;
        }
        if (($fp = @fopen($server_filename, 'rb')) === false) {
            return false;
        }
    } else {
        $remote = true;
        $handle = curl_init($server_filename);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);	
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($handle, CURLOPT_HEADER, true);
        curl_setopt($handle, CURLOPT_NOBODY, true);

        $response = curl_exec($handle);

        $http_code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        if ($http_code == 404) {
            return false;
        }

        $filesize = curl_getinfo($handle, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
        curl_close($handle);

        $ch = curl_init($server_filename);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    }

   $pass_remote = function($ch, $chunk) {
        echo $chunk; flush();
        return strlen($chunk);
   };
    
    // replace special characters
    
    $illegal = array('\\', '/', '<', '>', '{', '}', ':', ';', '|', '"', '~', '`', '@', '#', '$', '%', '^', '&', '*', '?');
    $replace = array('', '', '(', ')', '(', ')', '_', ',', '_', '', '_', '\'', '_', '_', '_', '_', '_', '_', '', '');
    $filename = str_replace($illegal, $replace, $filename);
    $filename = preg_replace('/([\\x00-\\x1f\\x7f\\xff]+)/', '', $filename);
    
    // replace special spaces to normal spaces(0x20).
    $filename = trim(preg_replace('/[\\pZ\\pC]+/u', ' ', $filename));
    
    // remove duplicates or dots.
    $filename = trim($filename, ' .-_');
    $filename = preg_replace('/__+/', '_', $filename);
    if ($filename === '') {
        return false;
    }
    
    // get User-Agent from browser
    $ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    $old_ie = (bool)preg_match('#MSIE [3-8]\.#', $ua);
    
    // add filename to header when filename only includes normal characters.
    if (preg_match('/^[a-zA-Z0-9_.-]+$/', $filename)) {
        $header = 'filename="' . $filename . '"';
    }
    
    // < IE 9 or < FF 5
    elseif ($old_ie || preg_match('#Firefox/(\d+)\.#', $ua, $matches) && $matches[1] < 5) {
        $header = 'filename="' . rawurlencode($filename) . '"';
    }
    
    // < Chrome 11
    elseif (preg_match('#Chrome/(\d+)\.#', $ua, $matches) && $matches[1] < 11) {
        $header = 'filename=' . $filename;
    }
    
    // < Safari 6
    elseif (preg_match('#Safari/(\d+)\.#', $ua, $matches) && $matches[1] < 6) {
        $header = 'filename=' . $filename;
    }
    
    // Android
    elseif (preg_match('#Android #', $ua, $matches)) {
        $header = 'filename="' . $filename . '"';
    }
    
    // other browsers assume that validate RFC/2231/5987 standards
    // but, add old style filename information for special circumstances
    else {
        $header = "filename*=UTF-8''" . rawurlencode($filename) . '; filename="' . rawurlencode($filename) . '"';
    }
    
    // cache is disallowed by client
    ob_get_clean();
    if (!$expires) {
        
        // Cannot use no-cache and pragma header when use old IE versions(<= 8) and SSL.
        if ($old_ie) {
            header('Cache-Control: private, must-revalidate, post-check=0, pre-check=0');
            header('Expires: Sat, 01 Jan 2000 00:00:00 GMT');
        }
        
        else {
            header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
            header('Expires: Sat, 01 Jan 2000 00:00:00 GMT');
        }
    }
    
    // cache is allowed by client
    else {
        header('Cache-Control: max-age=' . (int)$expires);
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + (int)$expires) . ' GMT');
    }
    
    // process range header for resume download
    if (isset($_SERVER['HTTP_RANGE']) && preg_match('/^bytes=(\d+)-/', $_SERVER['HTTP_RANGE'], $matches)) {
        $range_start = $matches[1];
        if ($range_start < 0 || $range_start > $filesize) {
            header('HTTP/1.1 416 Requested Range Not Satisfiable');
            return false;
        }
        header('HTTP/1.1 206 Partial Content');
        header('Content-Range: bytes ' . $range_start . '-' . ($filesize - 1) . '/' . $filesize);
        header('Content-Length: ' . ($filesize - $range_start));
        if ($remote) {
            curl_setopt($ch, CURLOPT_WRITEFUNCTION, $pass_remote);
            curl_setopt($ch, CURLOPT_RANGE, $range_start . '-' . ($filesize - 1));
        }
    } else {
        $range_start = 0;
        header('Content-Length: ' . $filesize);

        if ($remote) {
            curl_setopt($ch, CURLOPT_WRITEFUNCTION, $pass_remote);
            curl_setopt($ch, CURLOPT_RANGE, '0-' . $filesize);
        }
    }
    
    // send other headers.
    header('Content-Description: File Transfer');
    header('Content-Type: application/application/x-mpegURL');
    header('Content-Type: application/octet-stream');     
    header('Accept-Ranges: bytes');
    header("Content-Type: video/mp4");
    header('Content-Disposition: attachment; ' . $header);

    // clear output buffer.
    // (blocks file broken and decrease memory usage)
    
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    // send a file each 64KB and clear output buffer.
    // sometimes occurs memory leak when use readfile() function.
    
    $block_size = 16 * 1024;
    $speed_sleep = $speed_limit > 0 ? round(($block_size / $speed_limit / 1024) * 1000000) : 0;
    
    $buffer = '';
    if ($range_start > 0 && !$remote) {
        fseek($fp, $range_start);
        $alignment = (ceil($range_start / $block_size) * $block_size) - $range_start;
        if ($alignment > 0) {
            $buffer = fread($fp, $alignment);
            echo $buffer; unset($buffer); flush();
        }
    }
    while (!feof($fp) && !$remote) {
        $buffer = fread($fp, $block_size);
        echo $buffer; unset($buffer); flush();
        usleep($speed_sleep);
    }

    if ($remote && $ch) {
        curl_exec($ch);
    }
    
    if (!$remote) {
        fclose($fp);
    } else {
        curl_close($ch);
    }
    
    // true when successfully sent.
    return true;
}
