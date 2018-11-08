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


ShadowRouter::withMiddleware('http.test')->define('/',[
 'controller' => 'api',
 'method' => 'kk'
])->name('neimi');

// Auth Routes

ShadowRouter::define('/register',[
  'controller' => 'auth',
  'method' => 'getRegister'
]);

ShadowRouter::define('/register',[
  'controller' => 'auth',
  'method' => 'register'
],'post');


ShadowRouter::withMiddleware('http.test')->define('/login',function () {
  echo 'aaaaaa';
})->name('tslogin');

ShadowRouter::define('/login',[
  'controller' => 'auth',
  'method' => 'login'
],'post')->name('pstlogin');

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
  'method' => 'withMiddleware'
]);

ShadowRouter::withPrefix('idx')->api('/auth',function () {
  echo 'ok';
});


// Run rest API routes.

ShadowRouter::setDefaultApiPrefix('api');


#ShadowRouter::StartGroup(['middleware' => 'auth.http',
//'apiPrefix'  => 'sxvaprefix']);



ShadowRouter::withPrefix('sxva')->api('/wtf/{int:user}',[
   'controller' => 'api',
   'method' => 'auth'
],'get');




ShadowRouter::api('/ok',function  () {
  echo 'hmm';
});

ShadowRouter::group([
 'middleware' => 'auth.http',
 'apiPrefix'  => 'sxvaprefix'
],function ($k) {
  ShadowRouter::define('/modiaq','auth@ok');
});

ShadowRouter::withPrefix('idx')->withMiddleware('http.test')->api('/users/{string:username}/create/{int:resourceid}',[
   'controller' => 'api',
   'method' => 'auth'
],'get')->where([
  'username' => '!ok',
  'resourceid' => '>14'
])->name('uscrete');

// ShadowRouter::define('/rgx',[
//   'controller' => 'regex',
//   'method' => 'makeRegex'
// ]);


// ShadowRouter::withPrefix('sfx')->api('/rap/{int:id}',[
//    'controller' => 'api'
// ])->where([
//    'id' => '> 5'
// ]);


/////// 

/*
* Run Routes
*/
ShadowRouter::run();
?>