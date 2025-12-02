<?php

include_once( __DIR__ . '/../../inc/includes.php');

require_once(__DIR__ . '/vendor/autoload.php');

//$dirsToScan = [
//    'src',
//];
//
//foreach ($dirsToScan as $dir) {
//    $fullPath = __DIR__ . '/' .  $dir;
//    $files = array_diff(scandir($fullPath), array('..', '.'));
//
//    foreach ($files as $file) {
//        include_once(__DIR__ . '/' . $dir . '/' . $file);
//    }
//}

//
//ini_set('xdebug.var_display_max_depth', 10); // -1 for no limits
//ini_set('debug.var_display_max_children', 256);
//ini_set('xdebug.var_display_max_data', 1024);