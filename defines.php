<?php

  DEFINE('BASE_URL','http://localhost/phproot/shadow/');
  DEFINE('BASE_PATH',"shadowapp/sh_views");
  DEFINE('CACHE_DIR', 'shadowapp/sh_cache');
$dirStack = [];
function setDir($dirPath)
{
	global $dirStack;
	$dirList = scandir($dirPath);
    
	for($i = 0; $i < count($dirList); $i++){
		   if($i > 1)
		   {
		      $dirStack[$dirList[$i]] = require_once $dirPath.$dirList[$i];
		   }
    }
  
}

setDir('shadowapp/sh_config/');

?>
