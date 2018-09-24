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
         Request::redirect('/');
     } 
  }

 	public function login ()
 	{
     
 	}


 	public function getRegister (  )
 	{
 		View::run('register/index');
 	}

  public function getLogin()
  {
      
    $this->sendMail('justnightmare123@gmail.com');
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

     if ($staffNeedsConfirm) {
      print('Error occured please contact administrator');
      die;
     }

    $confirmStaff = $this->staffModel->confirm( $staffId );
  
   }

  
}
?>