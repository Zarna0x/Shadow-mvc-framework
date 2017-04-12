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
                       ->where([
                           'id' => 4,
                           'name' => 'cheki'
                       	 ])->where('pass','ako123');


        var_dump($builder);
       

       //opt 2
       
 	}

  }
?>
