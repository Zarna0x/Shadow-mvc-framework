<?php
 namespace Shadowapp\Controllers;

 use Shadowapp\Sys\View;
   
  class ApiShadow
  {

  	public function __construct()
 	{
           #code here
 	}

 	public function getArticles()
 	{

       echo '<pre>'.print_R(response()->getHeaders('host'),1).'</pre>';
 	}

  }
?>
