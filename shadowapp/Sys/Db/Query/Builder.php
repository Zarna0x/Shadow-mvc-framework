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
    

   protected $_limitStr;
   protected $_orderByStr;

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
     * Save raw where statements
     *
     * @var String
     */
   protected $_rawWhereData = [];
   

   protected $_addWhereArr = [];

   protected $_allowedOpperators = [
    '>','<','>=','<=','!=','<>','!<','!>'
   ];   
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
     * where part of query builder.
     * 
     * @param  $whereData array|string
     * @param  $optionalData string
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

   /**
     * additional andWhere part of query builder.
     * 
     * @return \Shadowapp\Sys\Db\Query\Builder
     */

   public function andWhere($col, $oper,$val)
   {
      if(!in_array($oper, $this->_allowedOpperators)){
          $oper = '=';
      }
      $this->_rawWhereData[] = strip_tags(trim(implode(" ",[$col,$oper,'?'])));
      $this->_addWhereArr[] =  $this->clean($val);

      return $this;
   }

   /**
     * where part of query builder.
     * 
     * @param  string $fromtData
     * @return \Shadowapp\Sys\Db\Query\Builder
     */
    public function limit($limit,$offset = null)
    {
       $this->_limitStr = " LIMIT ".intval($limit);
       if (!is_null($offset)) {
         $this->_limitStr .= ', '.intval($offset);
       }

       return $this;
    }

    public function orderBy($val, $sort = 'ASC')
    {
       $this->_orderByStr = ' ORDER BY '.$val.' '.$sort;
       return $this;
    }
   
   public function get($table = null)
   {
     if (!count($this->_selectStack)) {
        $this->_selectStack = ['*'];
     }

     if(!empty($table)){
        $this->_from = $table;
     }   
     
   
     $actualSelect = implode(',', $this->_selectStack);
     $SQL = "SELECT $actualSelect FROM $this->_from";
     

      $whereValCollection = [];
     
     if (count($this->_where)) {
        foreach ($this->_where as $key => $val) {
          $whereKeyCollection[] = $key;
          $whereValCollection[] = $val;
        }


       $whereInput = implode("= ? AND ",$whereKeyCollection)."= ?"; 
       $SQL .= " WHERE $whereInput";
     } 
       
       // build addition andWhere statement     
       $sqlStr = '';
     if (!empty($this->_rawWhereData)) {
       foreach ($this->_rawWhereData as $whereSTMT) {
         $sqlStr .= " AND ".$whereSTMT;
       }
       
      // strip first AND if prevous WHERE does not exists
       if (!count($this->_where)){
          $sqlStr = " WHERE ".$this->getAfterString($sqlStr);
       }
       
       // bind to original query string
       $SQL .= " ".$sqlStr;
  
        $whereValCollection = array_merge($whereValCollection,$this->_addWhereArr);     
     }
      
      if (!empty($this->_orderByStr)) {
        $SQL .= $this->_orderByStr;       
      }    


     if (!empty($this->_limitStr)) {
        $SQL .= $this->_limitStr;
     }
      

     $this->_stmt = $this->_con->prepare($SQL);
     try{
       $this->_stmt->execute(count($whereValCollection)? $whereValCollection : null);
     }catch(\PDOException $e){
        throw new \Shadowapp\Sys\Exceptions\Db\WrongQueryException($e->getMessage());
     }
     
     $this->rowCount = $this->_stmt->rowCount();

     $this->flushProperties();

    if ($this->rowCount > 0) {
       return $this->_stmt->fetchAll(PDO::FETCH_OBJ);
     } 
       return false;    	  
    }
   

   protected function getAfterString($sqlStr,$find = 'AND')
   {
      $offset = strpos($sqlStr, $find)+strlen($find);
      return trim(substr($sqlStr,$offset));
   }


   protected function flushProperties()
   {
      $this->_selectStack = [];
      $this->_where = [];
      $this->_from = '';
      $this->_rawWhereData = [];
      $this->_addWhereArr = [];
   }


   protected function clean($str){
       return htmlentities(addslashes(strip_tags(trim($str))));
   }

   public function getSelectData()
   {
   	  return $this->_selectStack;
   }
   
   public function getPrimaryKey($table)
   {
     try
     {
         $table = $this->clean($table);
         $this->_stmt = $this->_con->query("SHOW KEYS FROM $table WHERE Key_name = 'PRIMARY'");
          
        $primaryKeyData = $this->_stmt->fetch(PDO::FETCH_OBJ);
         if ($primaryKeyData != false) {
           return $primaryKeyData->Column_name;
         } 
         return false;  
     } catch (\PDOException $e) {
        throw new \Shadowapp\Sys\Exceptions\Db\WrongQueryException($e->getMessage());
     }

     
   }
   

   public function delete($table,array $data)
   {
      if  ( !is_string( $table ) ) {
        throw new  \ Shadowapp\Sys\Exceptions\WrongVariableTypeException("Wrong Variable Type. Table name have be string", 1);
     } 

     try {

     $queryString = [];
     $values = [];

     foreach ($data as $key => $val) {
          $queryString[] = $this->clean($key)." = ?";
          $values[] = $val;
      }
       
      $implodedQueryString = trim(implode(" AND ", $queryString));
      

      $sql = "DELETE FROM ".$this->clean($table)." WHERE ".$implodedQueryString;

       $stmt = $this->_con->prepare( $sql );

      if (!$stmt->execute( $values )) {
         return false;
      }

      return true;
       
     } catch (\PDOException $e ) {
        throw new \Shadowapp\Sys\Exceptions\Db\WrongQueryException($e->getMessage());
     }
   }

   

   public function insert($table, array $data)
   {
     if  ( !is_string( $table ) ) {
      throw new  \Shadowapp\Sys\Exceptions\WrongVariableTypeException("Wrong Variable Type. Table name have be string", 1);
     }
     
     try
     {
      
     $askMarkData =  [];
     $columns   =  []; 
     $values = [];

     foreach ($data as $key => $val) {
         $askMarkData[] = '?';
         $columns[] = $this->clean($key);
         $values[] = $this->clean($val);
      }


      $sql = 'INSERT INTO '.$this->clean( $table ).'('. implode(',', $columns) .') VALUES( '. implode(',', $askMarkData) .' )';
      
      $stmt = $this->_con->prepare( $sql );

      if (!$stmt->execute( $values )) {
         return false;
      }

      return true;

     } catch ( \PDOException $e ) {
        throw new \Shadowapp\Sys\Exceptions\Db\WrongQueryException($e->getMessage());
     }

   }
   

   public function update($table, array $data)
   {
     if  ( !is_string( $table ) ) {
      throw new  \Shadowapp\Sys\Exceptions\WrongVariableTypeException("Wrong Variable Type. Table name have be string", 1);
     }

        try
     {
  
      
      $whereCollection = [];
      $values          = [];
      $setCollection   = [];


      

     
     foreach ($data as $key => $val) {
          $setCollection[] = $this->clean($key)." = ?";
          $values[] = $val;
      }
     
     $implodedSetString = implode(', ',$setCollection);
      

      $sql = "UPDATE ".$this->clean($table)." SET ".$implodedSetString;
      
      if ( count($this->_where) ) {
        foreach ($this->_where as $key => $val) {
          $whereCollection[] = $this->clean($key)." = ?";
          $values[] = $val;
        }
        $implodedWhereString = implode(" AND ", $whereCollection);
        $sql .= " WHERE ".$implodedWhereString;
      }
      
      $stmt = $this->_con->prepare($sql);

      if (!$stmt->execute( $values )) {
         return false;
      }

      return true;
      
      $this->flushProperties();
  
     } catch ( \PDOException $e ) {
        throw new \Shadowapp\Sys\Exceptions\Db\WrongQueryException($e->getMessage());
     }

   }


}