<?php

define("BASEDIR", __DIR__);
define("DS","/");
define("JSON_DIR",BASEDIR.DS."shadowapp".DS."sh_db");

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

function parr($Value)
{
   echo "<pre>".print_r($Value,1)."</pre>";
}


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

function response ()
{
  return new Shadowapp\Sys\Http\Response;
}
?>
