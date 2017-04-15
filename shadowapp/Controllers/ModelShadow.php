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
    
  //opt 1
  $builder = $this->db
                  ->select('id,name')
                  ->from('users')
                  ->orderBy('id','DESC')
                  ->limit(2,7)
                  ->andWhere('id', '>',4) 
                  ->get();      
    
  var_dump($builder);
  var_dump($this->db->rowCount);

  }
}


?>
