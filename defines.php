<?php

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

?>
