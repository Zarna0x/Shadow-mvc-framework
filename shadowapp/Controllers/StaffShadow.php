<?php
 namespace Shadowapp\Controllers;

 use Shadowapp\Sys\View as View;
 use Shadowapp\Sys\Session;
 use Shadowapp\Sys\Http\Middleware;
 use Shadowapp\Sys\Http\Requester as Request;
 use Shadowapp\Models\StaffShadow as StaffModel;

 class StaffShadow
 {
 	protected $staffModel;
 	protected $userId;

  	public function __construct()
  	{

       Middleware::handle('auth.member');
       $this->staffModel = new StaffModel;
       $this->userId = shcol('id',Session::get('staffMember'));
  	}
    
    
 	public function dashboard()
 	{

 	    $userInfo = $this->staffModel->withRelated('roles')->find(['confirmed' => 1]);
      
echo '<pre>'.print_R($userInfo,1).'</pre>'; 


        if (!$userInfo) {
        	$this->logout();
        }

        //$userRole = $this->staffModel->getRole(shcol('id',$userInfo));  

        
      //  $userRoles = $this->staffModel->withRelated(['roles','othertable'])->find($this->userId);


        View::run('home/index',[
          'staffInfo' => $userInfo
        ]);
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
