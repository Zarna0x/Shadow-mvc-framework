<?php
 namespace Shadowapp\Controllers;

 use Shadowapp\Sys\View\View ;
 use Shadowapp\Sys\Http\Middleware;
 use Shadowapp\Sys\Routing\Router;

class ApiShadow
{
  	public function __construct()
 	  {
 	     echo route('uscrete',[
         'username' => 'Someone',
         'resourceid' => 78
       ]);
 	  }

  public function kk ()
  {
    //route('tslogin');
  }

 	public function auth ($username,$resourceId,$k = 'asd')
 	{
   // echo 'asd';
   // var_dump($username,$resourceId);

 	}

 	public function withMiddleware()
 	{
        View::run('contact/contact',[
            'username'=> 'wtfd'
        ]);
 	}

  }
