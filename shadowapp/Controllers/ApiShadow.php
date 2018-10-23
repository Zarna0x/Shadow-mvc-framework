<?php
 namespace Shadowapp\Controllers;

 use Shadowapp\Sys\View\View ;
   
  class ApiShadow
  {
  	public function __construct()
 	{
     //      var_dump('kkkkkkkkkkk');
 	}

 	public function auth ($username,$resourceId,$k = 'asd')
 	{
       View::run('contact/contact',[
          'username' => $username,
          'resourceId' => $resourceId
       ]);

 	}

 	public function withMidleware()
 	{
        
 	}

  }
?>
