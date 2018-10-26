<?php

$composerFile = __DIR__ . '/vendor/autoload.php';

if (false === file_exists($composerFile)) {
   echo 'Please run composer install first';
   exit;
}


$loader = require $composerFile;

include_once 'defines.php';

/*
* Set Directories
*/
try {

 setDir('shadowapp/sh_http/');

}catch (\Exception $Exception) {
 
  (new Shadowapp\Components\Exceptions\Handler)->handle($Exception);

}
 

?>
