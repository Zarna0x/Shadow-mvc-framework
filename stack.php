<?php

$loader = require __DIR__ . '/vendor/autoload.php';
include_once 'defines.php';

/*
* Set Directories
*/
   
   // spl_autoload_register( function ($className) {
   //       $className = ltrim($className, '\\');
   //       $fileName  = '';
   //       $namespace = '';
   //    if ($lastNsPos = strrpos($className,'\\')) {
   //      //define('DS',DIRECTORY_SEPARATOR);
   //      $namespace = substr($className,0,$lastNsPos);
        
   //      $namespaceData = explode('\\',$namespace);
   //      $namespaceData[0] = strtolower($namespaceData[0]);
   //      $namespaceData[1] = "sh_".strtolower($namespaceData[1]);
        
   //      $className  = substr($className,$lastNsPos + 1);
   //      $fileName = implode('/',$namespaceData).DIRECTORY_SEPARATOR;
   //    }
   //     $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
      
   //    require $fileName;
   // });


 setDir('shadowapp/sh_http/');

?>
