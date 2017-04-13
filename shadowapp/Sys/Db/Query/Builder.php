<?php

namespace Shadowapp\Sys\Db\Query;

use Shadowapp\Sys\Config as Config;
use Shadowapp\Sys\Db\Connection;
use \PDO;

class Builder  implements \Shadowapp\Sys\Db\QueryBuilderInterface
{
    /**
     * pdo connection.
     *
     * @var PDO object
     */
   protected $_con;

  
   /**
     * count rows
     *
     * @var int
     */
   public $rowCount;
    
    /**
     * Collect select data for query
     *
     * @var array
     */
   protected $_selectStack = [];
   

   /**
     * Collect select data for query
     *
     * @var array
     */
   protected $_from = '';
   

   /**
     * Collect where data for query
     *
     * @var array
     */
   protected $_where = [];

   /**
     * Create new PDO stmt
     *
     * @var PDOStatement
     */
   protected $_stmt = '';

   /**
     * Constructor function.
     *
     * @param  none
     * @return \PDO object
     */
	
   public function __construct()
   {
      $this->_con = Connection::get();  
   }

   /**
     * select a part of query builder.
     *
     * @param  string $selectData
     * @return \Shadowapp\Sys\Db\Query\Builder
     */
    public function select($selectData = '*')
    {
   	  
   	  foreach (explode(',',$selectData)  as $data) {
         if (!in_array($data, $this->_selectStack)) {
            $this->_selectStack[] = $this->clean($data);
         } 
   	  }
       
       return $this;
    }

   /**
     * from part of query builder.
     * 
     * @param  string $fromtData
     * @return \Shadowapp\Sys\Db\Query\Builder
     */
   public function from($fromData)
   {
   	   $this->_from = $this->clean($fromData);

   	   return $this;
   }
   

   /**
     * from part of query builder.
     * 
     * @param  string $fromtData
     * @return \Shadowapp\Sys\Db\Query\Builder
     */
   public function where($whereData,$optionalData = null)
   {
   	   if (is_array($whereData)) {
          $dt = $whereData;
   	   }else if (is_string($whereData)){
          if ($optionalData != null) {
            $dt[$this->clean($whereData)] = $this->clean($optionalData);
          }
       }
       
       foreach ($dt as $key => $val) {
          if(!in_array($key, $this->_where)){
              $this->_where[$this->clean($key)] =  $this->clean($val);
          }
       }
     
   	   return $this;
   }

   
   public function get()
   {
     
     // var_dump($this->_selectStack);
     // var_dump($this->_where);
     // var_dump($this->_from);

     //get keys and values of where clause
     foreach ($this->_where as $key => $val) {
        $whereKeyCollection[] = $key;
        $whereValCollection[] = $val;
     }


     $whereInput = implode("= ? AND ",$whereKeyCollection)."= ?"; 
     $actualSelect = implode(',', $this->_selectStack);
     
     $SQL = "SELECT $actualSelect FROM $this->_from WHERE $whereInput"; 
     $this->_stmt = $this->_con->prepare($SQL);
     $this->_stmt->execute($whereValCollection);
     
     $this->rowCount = $this->_stmt->rowCount();
    if ($this->rowCount > 0) {
       return $this->_stmt->fetchAll(PDO::FETCH_OBJ);
     } 
       return false;    	  
    }

   protected function clean($str){
       return htmlentities(addslashes(strip_tags(trim($str))));
   }

   public function getSelectData()
   {
   	  return $this->_selectStack;
   }
}