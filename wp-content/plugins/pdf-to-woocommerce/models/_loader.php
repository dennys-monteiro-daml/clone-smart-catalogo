<?php

// The generic "load everything in this folder except myself"

foreach (scandir(__DIR__) as $filename) {
    if (!in_array($filename, [basename(__FILE__), ".", ".."])) {
        $path = __DIR__ . DIRECTORY_SEPARATOR . $filename;
        if (is_file($path)) {
            require_once $path;
        }
    }
}
