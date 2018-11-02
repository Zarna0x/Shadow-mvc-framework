<?php
 namespace Shadowapp\Controllers;

 use Shadowapp\Sys\View\View;
   
  class RegexShadow
  {
  	private $badWords = [
       
  	];
  	public function makeRegex ()
  	{

  	

     	//	var_dump(preg_replace('/(?<=\$)name/', 'VARIABLE', $str));
        var_dump(preg_replace('(f|ht)tp(s)?:\/\/(www\.)([A-z]+)[.](ge|com|org)', '**',$string));
  	}

  }
?>
