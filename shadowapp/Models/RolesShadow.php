<?php
 namespace Shadowapp\Models;

 use Shadowapp\Sys\Db\Model;
   
  class RolesShadow extends Model
  {


  	public function __construct()
 	{
 		parent::__construct();
 	    var_dump($this->definedRelations);
 	}

 	// role has many staff members

 	public function Staff()
 	{
       $this->hasMany('Staff');
 	}

  }
?>
