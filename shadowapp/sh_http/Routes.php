<?php

use Shadowapp\Sys\Routing\Router as ShadowRouter;

/*
* Define Routes Here
*/


/*
Example:


ShadowRouter::define('/home',[
   'controller' => 'index',
   'method'     => 'hello'
]);

OR

ShadowRouter::define('/contact',function(){
	Shadowapp\Sys\View::run('contact/contact');
});
*/


ShadowRouter::define('/',[
  'controller' => 'staff',
  'method' => 'dashboard'
]);

// Auth Routes

ShadowRouter::define('/register',[
  'controller' => 'auth',
  'method' => 'getRegister'
]);

ShadowRouter::define('/register',[
  'controller' => 'auth',
  'method' => 'register'
],'post');


ShadowRouter::define('/login',[
  'controller' => 'auth',
  'method' => 'getLogin'
]);

ShadowRouter::define('/login',[
  'controller' => 'auth',
  'method' => 'login'
],'post');

ShadowRouter::define('/confirm',[
   'controller' => 'auth',
   'method' => 'generateConfirmUrl'
]);

ShadowRouter::withMiddleware('auth.member')->define('/logout',[
   'controller' => 'staff',
   'method' => 'logout'
]);

ShadowRouter::withMiddleware('http.test')->define('/withmid',[
  'controller' => 'api',
  'method' => 'withMidleware'
]);

ShadowRouter::withPrefix('idx')->api('/auth',function () {
  echo 'ok';
});


// Run rest API routes.

ShadowRouter::setDefaultApiPrefix('api');


// ShadowRouter::StartGroup(['middleware' => 'auth.http',
// 'apiPrefix'  => 'sxvaprefix']);


ShadowRouter::withPrefix('sxva')->api('/wtf/{int:user}',[
   'controller' => 'api',
   'method' => 'auth'
],'get');

ShadowRouter::withMiddleware('http.test')->api('/users/{string:username}/create/{int:resourceid}',[
   'controller' => 'api',
   'method' => 'auth'
],'get');

ShadowRouter::api('/ok',function  () {
  echo 'hmm';
});

// ShadowRouter::endGroup();



//-------------------------------------//

ShadowRouter::group([
 'middleware' => 'auth.http',
 'apiPrefix'  => 'sxvaprefix'
],function ($k) {

  ShadowRouter::define('/modiaq','auth@ok');
});




/////// 

/*
* Run Routes
*/
ShadowRouter::run();


?>