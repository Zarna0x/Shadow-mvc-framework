## Shadow MVC Framework v0.1
![alt tag](https://thelogocompany.net/wp-content/uploads/2013/05/main_shadow.jpg)

* [![Build Status](https://api.travis-ci.org/Zarna0x/Shadow-mvc-framework.svg?branch=master)](https://travis-ci.org/Zarna0x/Shadow-mvc-framework)

* [![license](https://img.shields.io/github/license/Zarna0x/Shadow-mvc-framework.svg?style=flat-square)]()

## Installation
Install using composer
```
composer create-project zarna0x/shadow_framework myproject
```

or you can clone git repository
```
git clone https://github.com/Zarna0x/Shadow-mvc-framework.git
```
and 
```
composer install
```

## Features
ShadowPHP is simple and flexible mvc framework which provides various features just out of the box,
Such as:

*  Models
*  Views
*  Controllers
*  Custom routes
*  Command line tool
*  Request handling
*  Session handling
*  Flash Messages/Sessions
*  Input validation

.etc

furthermore you can add your own features and libraries

## console.shadow

You can create models and controllers from your terminal using console.shadow
just type:
```
php console.shadow
```
![alt tag](https://i.imgsafe.org/ec986eae63.png)

This command will generate controller with name GitShadow

GitShadow.php:
```
 <?php
   namespace Shadowapp\Controllers;

   use Shadowapp\Sys\View as View;
   
  class GitShadow
  {
  
  	public function __construct()
 	  {
        #code here
 	  }

 }
 
?>
```
## Define Routes

ShadowPHP has advanced routing capabilities and user can define custom routes(Located in sh_http/Routes.php).
You can define route this way:
```
use ShadowApp\Sys\Router as ShadowRouter;

ShadowRouter::define('/home',[
   'controller' => 'index',
   'method'     => 'hello'
]);

ShadowRouter::run();
```

OR 

```
ShadowRouter::define('/register',function(){
   echo "This Is Register"
});

```

Also you can pass variable to view

```
ShadowRouter::define('/somelink',function(){
    ShadowApp\Sys\View::run('rame/index',[
       'var' => 'just some random text'
    ]);
});
```

## Requests

Youn can handle http requests using ShadowPHP's Shadowapp\Sys\Requester class:
```
use \Shadowapp\Sys\Requester as Requester;
```
1) check if request method is post
```
Requester::isPost();
```

2) check if request method is Get
```
Requester::isGet();
```

3) get parameters of post request
```
Requester::getPost('name');
```

4) redirect user
```
Requester::redirect('home');
```

5) Retrieve All Input Data

```
Requester::all();
```

## Session

Shadowapp\Sys\Session class provides object-oriented wrapper of session data .

1) start Session
```
use \Shadowapp\Sys\Session as Session

Session::start('auth', [
                  'id'   => user->id,
                  'name' => $user->name
               ]);

```

2) get session
```
Session::get('auth');
```

3) destroy session
```
Session::smash();
```

4) remove session 
```
Session::remove('auth');
```

5) check if specified key is set
```
Session::has('auth');
```

## Flash Messages

Flash Session temporarily stores the messages in session, then messages can be printed in the next request

```
 Session::flashShadow('error','Wrong Username Or Password');
 Requester::redirect('login');
```
and

```
  if(Shadowapp\Sys\Session::has('error'))
  {
      Shadowapp\Sys\Session::flashOutput('error');
  }
```

## Validation
ShadowPHP has easy and flexible approach to validate your application's incoming requests
just use Shadowapp\Sys\Validator class

Create Validator
```
 use \Shadowapp\Sys\Validator as Validator; 

Validator::run(Requester::all(),[
              'name' => [
                 'required' => true,
                 'min' => 3,
                 'max' => 10,
                 'empty'  => false
               ],
              'pass' => [
                 'requred' => true,
                 'min'     => 3,
                 'max'     => 20,
              ]
           ]);
```

Check if there is any validation error and redirect user with flash session
```
  if(Validator::failed())
    {
              Session::flashShadow('error',Validator::errorMessage());
              Requester::redirect('login');
              return;
    }
```
