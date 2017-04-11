<?php

  DEFINE('BASE_URL','http://localhost/phproot/shadow/');
  DEFINE('BASE_PATH',"shadowapp/sh_views");
  DEFINE('CACHE_DIR', 'shadowapp/sh_cache');

function setDir($dirPath)
{
	$dirList = scandir($dirPath);

	for($i = 0; $i < count($dirList); $i++){
		   if($i > 1)
		   {
		      include_once $dirPath.$dirList[$i];
		   }
    }

}
?>
