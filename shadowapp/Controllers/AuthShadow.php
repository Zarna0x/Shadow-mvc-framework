<?php
namespace Shadowapp\Controllers;

use Shadowapp\Sys\View as View;
use Shadowapp\Sys\Http\Requester as Request;
use Shadowapp\Sys\Validator;
use Shadowapp\Sys\Session;
use Shadowapp\Sys\Email\Email;
use Shadowapp\Models\StaffShadow as StaffModel;

   
class AuthShadow
{
  protected $staffModel;

  protected $email;


  public function __construct()
 	{
    
    $this->checkLogin(); 
    $this->staffModel = new StaffModel;
    $this->email = new Email;
 	}


  protected function checkLogin()
  {
     if (Session::has( 'staffMember' )) {
         Request::redirect('/psystem');
     } 
  }

 	public function login ()
 	{
    if ( !Request::isPost() ) {
        Request::redirect('register');
    }
    
    $request = Request::getPost();

    $this->validateLoginRequest($request);

    $staff = $this->staffModel->authenticate($request);

    if (!$staff) {
      Session::flashShadow('error','Staff member not found! please make sure you registered and confirmed your emaill address');
      Request::redirect('login');    
    } 
    
    $this->startStaffSession( (array)$staff );
    
 	}


  public function getRegister (  )
 	{
 		View::run('register/index');
 	}

  public function getLogin()
  {
     View::run('login/index');
  }

 	public function register () 
 	{
 	  
    if (!Request::isPost()) {
 	    	Request::redirect('register');
 	  }
     
     $this->validateRequest( Request::getPost() );
          
    if ( false == $this->staffModel->add( Request::getPost() )) {
        Session::flashShadow('error',"Cannot add new staff member. Please Contact administrator");
        Request::redirect('register');  
    }
    
    // Confirm Email

    $this->generateConfirmMail((int)$this->staffModel->id, $this->staffModel->email);
    
    Session::flashShadow('success','New User Added Succesfully');
    Request::redirect('/');
   

   }


   protected function validateRequest ( array $request )
   {
      Validator::run( $request ,[
              'firstname' => [
                 'required' => true,
                 'min' => 2,
                 'max' => 120,
                 'empty'  => false
               ],
              'lastname' => [
                 'requred' => true,
                 'min'     => 3,
                 'max'     => 120,
              ],
              'email' => [
                'required' => true,
                'min' => 3,
                'max' => 120,
                'mail' => true,
                'unique' => 'staff'
              ],
              'phone' => [
                 'required' => true,
                 'min' => 5,
                 'max' => 120,
                 'number' => true
              ],
              'username' => [
                 'required' => true,
                 'min' => 3,
                 'max' => 120,
              ],
              'password' => [
                 'required' => true,
                 'min' => 5,
                 'max' => 150,
                 'confirmed' => 'confirm_password'
              ]

           ]);



      if ( Validator::failed() ) {
          Session::flashShadow('error',Validator::errorMessage());
          Request::redirect('register');          
      }
   }
   
   protected function generateConfirmMail( $staffId, $mailToSend )
   {
      if ( !is_numeric( $staffId  )  ) {
         throw new  \Shadowapp\Sys\Exceptions\WrongVariableTypeException("Wrong Variable Type. Staff ID have to be numeric", 1);
      }

      $tokenToSend = $this->staffModel->storeConfirmData( $staffId, $this->generateToken( $staffId ) );


      $this->sendMail( $mailToSend );
      
   }


  public function sendMail( $mailToSend )
  {
     
     $confirmUrl = '';
     
     $body = 'Hello, Please confirm your email address from this link => '.$confirmUrl;


     $this->email
          ->setAddress( $mailToSend )
          ->setSubject('Email Confirmation')
          ->setMessage( $body )
          ->setHeaders(['From'=>'ShadoPSystem'])
          ->send();
  }

   private function generateToken( $staffId )
   {
      $uniqueValue = bin2hex(random_bytes(15));
      $date = microtime();
      $tokenString = (string)$uniqueValue.$staffId.$date;
      $iv = openssl_random_pseudo_bytes(16);
      $encrypted = sha1(base64_encode(openssl_encrypt($tokenString, 'AES-256-CBC', $uniqueValue,OPENSSL_RAW_DATA,$iv)));
      return $encrypted;
   }

   public function generateConfirmUrl( $secret )
   {
     $staffId = $this->staffModel->checkToken( $secret );

     if (!$staffId) {
       print('Wrong Confirm token!');
       die;
     }
    
     $staffNeedsConfirm = $this->staffModel->checkIfStaffNeedsConfirm($staffId);

     if (!$staffNeedsConfirm) {
      print('Error occured please contact administrator');
      die;
     }

    $confirmStaff = $this->staffModel->confirm( $staffId );

    if (!$confirmStaff) {
      print( 'Error occured please contact administrator' );
      die;
    }
    

     echo 'User Confirmed Succesfully. now You can login from here <a href="/login">click</a>';
  
   }

   protected function startStaffSession( array $staff )
   {
      if (!Session::start('staffMember',[
         'username' => shcol('username',$staff),
         'email' => shcol('email',$staff),
         'log_date' => date('Y-m-d h:i:s')
      ])) {
          Session::flashShadow('error','There was error logging user in. Please try again or contact administrator');
          Request::redirect('login');
      }



      Session::flashShadow('success','Hello '.shcol('username',$staff).', you logged in Succesfully');
      Request::redirect('/');
 }

 protected function validateLoginRequest( array $request )
   {

    
      Validator::run($request,[
         'email' => [
                'required' => true,
                'min' => 3,
                'max' => 120,
                'mail' => true
              ],
           'password' => [
                 'required' => true,
                 'min' => 5,
                 'max' => 150
          ]
      ]);

      if ( Validator::failed() ) {
          Session::flashShadow('error',Validator::errorMessage());
          Request::redirect('login');
      }

     
   }

  
}
?>