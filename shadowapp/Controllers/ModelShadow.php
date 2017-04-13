<?php
 namespace Shadowapp\Controllers;

 
 use Shadowapp\Sys\View as View;
 use Shadowapp\Sys\Db\Query\Builder as db;
   
  class ModelShadow
  {
  	protected $db;

  	public function __construct()
  	{
       $this->db = new db;

   	}

 	public function dbtestMethod()
 	{

 		// $this->db
 		//     ->select('*')
 		//     ->from('users')
 		//     ->where('id','2')
 		//     ->get();
       
       //opt 1
       $builder = $this->db
                        ->select('id,name,password')
                        ->from('users')
                        ->where([
                           'id' => 1,
                           'name' => 'zarna'
                        ])->where('password','pass')->get();
                        
       
   var_dump($builder);
   var_dump($this->db->rowCount);
       //opt 2
       
 	}

  }
?>
