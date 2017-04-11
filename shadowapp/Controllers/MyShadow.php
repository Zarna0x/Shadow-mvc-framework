<?php
  namespace Shadowapp\Controllers;

  use Shadowapp\Sys\View as View;
  
  

  class MyShadow
  {
  	public function __construct()
  	{
  	
        $users = new \Shadowapp\Models\UsersShadow();
        $users->selectData();
        
  	}
  }

?>