<?php

namespace Shadowapp\Sys\Db;

use Shadowapp\Sys\Db\Query\Builder as db;


class Model
{
   protected $db;
   private   $table = '';
   protected $con;
   protected $_tableCollection = [];
  
   public function __construct () 
   {
      $this->db = new db;
      $this->table = $this->getTable();
    } 

   public function __set($tableName,$val)
   {
      if (in_array($tableName, $this->getColumnList())) {
      	$this->_tableCollection[$tableName] = $val;
      }
   }

   public function __get($tableName)
   {
       return $this->_tableCollection[$tableName];
   }


   public function save()
   {

   	  if (!count($this->_tableCollection)) {
         echo "Specify Columns to inserts";
         return;
   	  }
   	  $insertColumns = array_keys($this->_tableCollection);
   	  #var_dump($insertColumns);
   	  foreach ($this->_tableCollection as $key => $val) {
         $askMarkData[] = '?';
         $valueData[] = $val;
   	  }

   	 
    $SQL = "INSERT INTO ".$this->table."(".implode(",", $insertColumns).") VALUES (".implode(',', $askMarkData).")";
    
    $stmt = $this->con->prepare($SQL);
    
    if ($stmt->execute($valueData)) {
    	return true;
    } else {
    	return false;
    }
   }
   public function getColumnList()
   {
   	  $this->con = Connection::get();
   	  $stmt = $this->con->query("DESCRIBE ".$this->table);
   	  $stmt->execute();
   	  foreach ($stmt->fetchAll() as $i => $data) {
        $fieldData[] = $data['Field'];
   	  }

   	  return $fieldData;
   }

   protected function getTable()
   {
   	$fullClassName  = new \ReflectionClass(get_called_class());
   	$offset = strpos($fullClassName->getShortName(),'Shadow');
   	$tableName = strtolower(substr($fullClassName->getShortName(),0,$offset));
   	return $tableName;
   }
   
   /*
    * find result using primary key or specify pamereters using array,
    * if table doesnot contains primary key result will be fetchecd by ID
    */
   public function find($primaryKeyOrArray)
   {
      if (is_array($primaryKeyOrArray)) {
        return  $this->db->where($primaryKeyOrArray)->get($this->table);
       }
       //Or Find result By Primary Key
       
       $primaryKey = ($this->db->getPrimaryKey($this->table) != false)? $this->db->getPrimaryKey($this->table) : 'id';     
       
       return $this->db->where($primaryKey,$primaryKeyOrArray)->get($this->table);
   }

   public function findFirst()
   {
    
   }  
} 