<?php

$composerFile = __DIR__ . '/vendor/autoload.php';

if (false === file_exists($composerFile)) {
   echo 'Please run composer install first';
   exit;
}


$loader = require __DIR__ . '/vendor/autoload.php';
include_once 'defines.php';

/*
* Set Directories
*/
   
 setDir('shadowapp/sh_http/');
?>
