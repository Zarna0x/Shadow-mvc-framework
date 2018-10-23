<?php

define("BASEDIR", __DIR__);
define("DS","/");
define("JSON_DIR",BASEDIR.DS."shadowapp".DS."sh_db");
define("MIDDLEWARE_DIR",BASEDIR.DS."shadowapp".DS."Components".DS."Middlewares");

if (!function_exists('setDir')) {
  function setDir($dirPath)
  {

  $dirList = scandir($dirPath);
    
  for($i = 0; $i < count($dirList); $i++){
       if($i > 1)
       {
          require_once $dirPath.$dirList[$i];
       }
    }
  
 }
}

if (!function_exists('parr')) {
  function parr($Value)
  {
     echo "<pre>".print_r($Value,1)."</pre>";
  }
}


if (!function_exists('shcol')) {
  function shcol($Key,$Collection,$Default = '')
  {
     $Keys = explode('.', $Key);
     $Data = $Collection;
     foreach ($Keys as $kkk) {
     
         if (is_object($Data)) {
         	
             $Data = (array)$Data;
         } 
         if (!isset($Data[$kkk])){
            return $Default;
         }

         $Data = $Data[$kkk];
     }

     return $Data;
  }
}

if (!function_exists('response')) {
  function response ()
  {
    return new Shadowapp\Sys\Http\Response;
  }
}


if (!function_exists('baseurl')) {
  function baseurl(){
    
    $baseFolder =  ( class_exists(\Shadowapp\Sys\Config::class) ) ? '/'.shcol('base_folder',(new \Shadowapp\Sys\Config)->get()) : '';
    
    $http = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http';
    
     return $http.'://'.$_SERVER['HTTP_HOST'].$baseFolder;

  }
}


if (!function_exists('basePath')) {
   function basePath () {
    return file_exists(__DIR__.DS.'shadowapp/') ?
       __DIR__.DS.'shadowapp/' :
       '';  
   }
}

?>
