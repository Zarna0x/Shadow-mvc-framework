<?php

namespace Shadowapp\Controllers;

use Shadowapp\Sys\View as View;
use Shadowapp\Sys\Db\Query\Builder as db;
use Shadowapp\Models\PagesShadow as Pages;
    

class ModelShadow
{
  
  protected $db;

  public function __construct()
  {
      $this->db = new db;

  }

 	public function dbtestMethod()
 	{
    
  //Query Builder
  $builder = $this->db
                  ->select('id,name')
                  ->from('users')
                  ->orderBy('id','DESC')
                  ->limit(2,7)
                  ->andWhere('id', '>',4) 
                  ->get();      
    
   
   //ORM
   $pages = new Pages;
   $pages->home = 'homex';
   $pages->save();      
   
   #var_dump($pages);
  //  $pages->getSchema();
  }
}


?>
