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


ShadowRouter::define('/',function () {
   Shadowapp\Sys\View::run('home/index',[
       'cvladi' => 'rame mnishvneloba'
    ],false);
});

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
	ShadowApp\Sys\View::run('register/index',[
       "erti" => "this is var"
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

/*
* Run Routes
*/
ShadowRouter::run();


?>
