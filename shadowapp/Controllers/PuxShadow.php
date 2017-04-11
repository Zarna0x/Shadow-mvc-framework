<?php

 namespace Shadowapp\Controllers;

 use Shadowapp\Sys\View as View;
   
  class PuxShadow
  {
  	public function __construct()
 	{
           #code here
 	}

 	public function testMethod ()
 	{
 		View::run('test/index');
 	}

  }

?>