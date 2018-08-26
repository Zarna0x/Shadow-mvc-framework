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
  'controller' => 'contact',
   'method' => 'printx'
]);

ShadowRouter::define('/test111',function () {
   echo 'wtff OKKKKKKKKkk KAi';

});

ShadowRouter::define('/home',[
   'controller' => 'index',
   'method'     => 'hello'
]);
ShadowRouter::define('/contact',function(){
	ShadowApp\Sys\View::run('contact/contact');
});

ShadowRouter::define('/cheki',[
   'controller' => "cheki"
]);

ShadowRouter::define('/register',function(){
	Shadowapp\Sys\View::run('register/index',[
       "erti" => "this is ssvar"
	]);
});

ShadowRouter::define('/somelink',function(){
    ShadowApp\Sys\View::run('rame/index',[
       'cvladi' => 'rame mnishvneloba'
    ]);
});

ShadowRouter::define('/yoh',[
   'controller' => 'my'
]);

// Login
ShadowRouter::define('/login',function () {
   var_dump("test");
},'get');

ShadowRouter::define('/postlogin',[
   'controller' => 'login',
   'method'    => 'sxva'
],'post');

ShadowRouter::define('/testcase', [
  'controller' => 'pux',
  'method'   => 'test'
]);

ShadowRouter::define('/modcss',[
   'controller' => 'kernel',
   'method'     => 'index'
]);
ShadowRouter::define('/modx',function(){
  echo 'wtf';
});

ShadowRouter::define('/abc',function(){

});

ShadowRouter::define('/dbtest',[
   'controller' => 'model',
   'method'     => 'dbtest'
]);


//api endpoints 

ShadowRouter::define('articles',[
  'controller' => 'api',
   'method' => 'getArticles'
]);

/*
* Run Routes
*/
ShadowRouter::run();


?>
