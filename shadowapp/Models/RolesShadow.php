<?php
 namespace Shadowapp\Models;

 use Shadowapp\Sys\Db\Model;
   
  class RolesShadow extends Model
  {
  	public function __construct()
 	{
 		parent::__construct();
 	}

 	// role has many staff members

 	public function staffs()
 	{
       $this->hasMany('Staff');
 	}

  }
?>
