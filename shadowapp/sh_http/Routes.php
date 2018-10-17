<?php


use Shadowapp\Sys\Router as ShadowRouter;

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

ShadowRouter::define('/logout',[
   'controller' => 'staff',
   'method' => 'logout'
]);



// Run rest API routes.
ShadowRouter::withPrefix('idx')->api('/auth',function () {
  return 'ok';
});
ShadowRouter::api('/me',[
   'controller' => 'api',
   'method' => 'auth'
],'get');

ShadowRouter::withPrefix('sxva')->api('/wtf',[
   'controller' => 'api',
   'method' => 'auth'
],'get');


/////// 

/*
* Run Routes
*/
ShadowRouter::run();


?>
