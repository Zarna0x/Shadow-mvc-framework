<?php
 namespace Shadowapp\Controllers;

 use Shadowapp\Sys\View as View;
 use Shadowapp\Sys\Session;
 use Shadowapp\Sys\Http\Requester as Request;
 use Shadowapp\Models\StaffShadow as StaffModel;
   
  class StaffShadow
  {
  	public function __construct()
 	{
        $this->checkAccess();           
 	}
    
    protected function checkAccess()
    {
      if (!Session::has('staffMember')) {
       	  Request::redirect('login');
       }
    }


 	public function dashboard()
 	{
        View::run('home/index');
 	}

 	public function logout()
 	{
      if (!Request::isGet()) {
        Request::redirect('login');
      }
      
      (Session::has('staffMember')) ?
        Session::remove('staffMember') && Request::redirect('login')
      : Request::redirectBack() ;
  	}

  }
?>
