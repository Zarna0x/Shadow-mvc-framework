<?php
 
 namespace Shadowapp\Controllers;

 use Shadowapp\Sys\View as View;
 use \Shadowapp\Sys\Requester as Requester;
 use \Shadowapp\Sys\Session   as Session;  
 use \Shadowapp\Sys\Validator as Validator; 


class LoginShadow
{


    public function getLoginMethod () 
    {
       View::run('login/index');
    }


 	public function makeLoginMethod()
 	{
 		
    var_Dump($_POST);
    exit;
 		if(Requester::isPost())
 		{
          
           $name = Requester::getPost('name');
           $pass = Requester::getPost('pass');
          var_dump($name);
           $db = new \Shadowapp\Sys\Dbmanager();
            

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
         
          if(Validator::failed())
          {
              Session::flashShadow('error',Validator::errorMessage());
              Requester::redirect('login');
              return;
          }
            

            
           $selectdata = $db->select('users',['*'],[
             'name' => $name,
             'password' => $pass
		       ]);
            
            if($selectdata != false)
            {
               foreach($selectdata as $key => $Value)
               {
                  $dataArr = $Value;
               }
               
                 
               if(Session::start('auth', [
                  'id'   => $dataArr->id,
                  'name' => $dataArr->name
               ]))
               {
                 
               //  Requester::redirect('home');  
               } else
               {
                 echo 'Cannot Start Session';
               }
               
            }else
            {
              Session::flashShadow('error','Wrong Username Or Password');
             // Requester::redirect('login');
            }
  		}
 		else
 		{
 		   //Requester::redirect('');	
 		}

 		
 	}
   
   public function sxvaMethod () {
       echo 'wrd';
   }


  }
?>
