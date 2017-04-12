<?php

namespace Shadowapp\Sys\Db\Query;

use Shadowapp\Sys\Config as Config;
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
     * Constructor function.
     *
     * @param  none
     * @return \PDO object
     */
	
   public function __construct()
   {
    try
		{
         $config = new Config;
         $data = $config->get(); 
			 
          $this->_con    = new PDO("mysql:host=$data->db_host;dbname=$data->db_name",$data->db_user,$data->db_pass);
          $this->_con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);  
		}
		catch(PDOException $e)
		{
          echo $e->getMessage();
		}
     
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
   	  
   }

   protected function clean($str){
       return htmlentities(addslashes(strip_tags(trim($str))));
   }

   public function getSelectData()
   {
   	  return $this->_selectStack;
   }
}