<?php

 /*
 * Index Controller
 */


 namespace Shadowapp\Controllers;

 use ShadowApp\Sys\View as View; 
  
 class IndexShadow
 {
 	public function __construct()
 	{
 		//View::run('home/index');
 	}

 	public function helloMethod()
 	{

        $var  = [
          
            'erti' =>
             [
              'ori' => "sami"
             ],

             
            

            'otxi' => 'xuti'
            
          
        ];
       
        $lang = "php";
        $obj = new \StdClass();
        $obj->cvl = "string";
     
 		View::run('home/index',[
         "var"  => $var,
         "lang" => $lang,
         "obj"  => $obj
 		]);

 		

 	}
 }




 
?>