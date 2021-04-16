<?php

// the generic wp node_modules loader

$package = file_get_contents(__DIR__ . '/package.json');

$package = json_decode($package, true);

foreach ($package['dependencies'] as $dep => $version) {
    $subpackage = file_get_contents(__DIR__ . "/node_modules/$dep/package.json");
    $subpackage = json_decode($subpackage, true);

    $use = 'main';

    if (isset($subpackage['browser'])) {
        $use = 'browser';
    }

    if (isset($subpackage[$use])) {
        $deps = array();

        if (strpos($dep, 'jquery') !== false) {
            $deps[] = 'jquery';
        }

        wp_enqueue_script("node_module-$dep", plugin_dir_url(__FILE__) . "node_modules/$dep/" . $subpackage[$use], $deps, $subpackage['version']);
    }

    if (isset($subpackage['style'])) {

        wp_enqueue_style("node_module-$dep", plugin_dir_url(__FILE__) . "node_modules/$dep/" . $subpackage['style']);
    }
}
