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
ShadowRouter::withPrefix('idx')->api('/auth',function () {
  echo 'ok';
});


// Run rest API routes.


ShadowRouter::setDefaultAPiPrefix('api');

ShadowRouter::withPrefix('sxva')->api('/wtf',[
   'controller' => 'api',
   'method' => 'auth'
],'get');

ShadowRouter::api('/users/create/oh/{id}',[
   'controller' => 'api',
   'method' => 'auth'
],'get');





ShadowRouter::api('/ok',function  () {
  echo 'hmm';
});


/////// 

/*
* Run Routes
*/
ShadowRouter::run();


?>
