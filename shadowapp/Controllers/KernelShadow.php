<?php
 namespace Shadowapp\Controllers;

 use Shadowapp\Sys\View as View;

  class KernelShadow 
  
  {
  	public function indexMethod()
  	{
 	    View::run('test/index',[],false);
 	  }

  }
?>
