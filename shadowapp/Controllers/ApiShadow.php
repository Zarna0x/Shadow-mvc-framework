<?php
 namespace Shadowapp\Controllers;

 use Shadowapp\Sys\View\View ;
 use Shadowapp\Sys\Http\Middleware;
   
  class ApiShadow
  {
  	public function __construct()
 	{
 	  
 	}

 	public function auth ($username,$resourceId,$k = 'asd')
 	{

       View::run('contact/contact',[
          'username' => $username,
          'resourceId' => $resourceId
       ]);

 	}

 	public function withMiddleware()
 	{
        View::run('contact/contact',[
            'username'=> [
              'onr','two','wtsad','asd','asd','sss'
            ]
        ]);
 	}

  }
?>
