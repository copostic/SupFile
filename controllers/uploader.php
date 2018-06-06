<?php
function directorySize($directory) {
    $size = 0;
    foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory)) as $file){
        $size += $file->getSize();
    }
    return $size;
}

require_once LIB . 'vendor/fineuploader/php-traditional-server/endpoint.php';
