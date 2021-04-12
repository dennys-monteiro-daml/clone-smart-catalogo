<?php

// The generic "load everything in this folder except myself"
$dir = __DIR__;
// $dir_name = dirname(__DIR__);

foreach (scandir($dir) as $filename) {
    if (!in_array($filename, [basename(__FILE__), ".", ".."])) {
        $path = __DIR__ . DIRECTORY_SEPARATOR . $filename;
        if (is_file($path)) {
            require_once $path;
        }
    }
}
