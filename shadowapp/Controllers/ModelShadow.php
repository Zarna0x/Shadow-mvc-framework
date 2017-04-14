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
        $builder = $this->db->select('name,password')
                 ->where('name','zarna')
                 ->andWhere('id','<',"2")
                 ->get('users');         
    
   var_dump($builder);
   var_dump($this->db->rowCount);
       //opt 2
       
 	}

  }
?>
