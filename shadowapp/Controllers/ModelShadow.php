<?php

namespace Shadowapp\Controllers;

use Shadowapp\Sys\View as View;
use Shadowapp\Sys\Db\Query\Builder as db;
use Shadowapp\Models\PagesShadow as Pages;
    

class ModelShadow
{
  
  protected $db;
  protected $pages;
  public function __construct()
  {
      $this->db = new db;
      $this->pages = new Pages;
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
  
   #1
   $this->pages->find([
     'id' => 36
   ]);
   #2
   $x = $this->pages->find(36);
   var_dump($x);
  }
}
