<?php
/*
* Shadowphp framework
* @link https://github.com/Zarna0x/Shadow-mvc-framework
* @Author Zarna0x
*/

namespace Shadowapp\sys;

use \PDO;

class DbManager
{
    protected $_con;
    private $_host;
	private $_user;
	private $_pass;
	private $_dbName;
	private $_toSelectVars;
	private $_heap = array();
	private $_stmt;


	public function __construct()
	{
		try
		{

			   $this->_host   = DB_HOST;
		     $this->_user   = DB_USER;
		     $this->_pass   = DB_PASS;
		     $this->_dbName = DB_NAME;
	     	
          $this->_con    = new PDO("mysql:host=$this->_host;dbname=$this->_dbName",$this->_user,$this->_pass);
          $this->_con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);  
		}
		catch(PDOException $e)
		{
          echo $e->getMessage();
		}
	}


	public function select($tableForSel,$toSelect = array(),$toGet = array()){
   # RETURNS OBJECT_ARRAY OF SELECTED DATA
    /*
     @param1(string) - NAME OF TABLE
     @param2(array) -COLUMN NAMES WHICH WILL BE SELECTED
     @param3(twoDimensional Array) 'column'=>'value'
     */
      try{
             $toColumns = array();
             $toS = array();
           foreach($toGet as $key=>$val){
              if(is_string($key)){
                $toColumns[] = $key;
                $toS[] = $val;
              }
           }
          $counter = 0;
       while($counter < count($toSelect)){
          $input =  implode("= ? AND ",$toColumns)."= ?";
          $this->_stmt = $this->_con->prepare("SELECT $toSelect[$counter] FROM `$tableForSel` WHERE $input")
          or $this->throwException();
          $this->_stmt->execute($toS) or $this->throwException();
        if($this->_stmt->rowCount() == 0){
            echo 'NO RESULT';
            break;
         }
           $this->_heap[] = $this->_stmt->fetch(PDO::FETCH_OBJ);
           $counter++;
       }
      }catch(Exception $e){
        echo 'ERROR: '.$e->getMessage();
      }
   return $this->_heap;
 }
 public function insert($tableForIns,$toValues){
  # INSERTS DATA IN THE INDICATED TABLE
    /* 
     @param1(string) - CHOOSE TABLE 
     @param2(array) -  'column'=>'value' 
    */
   try{ 
          $columns = array();
          $values = array();
        foreach($toValues as $keys=>$vls){
          if(is_string($keys)){
             $columns[] = $keys;
             $values[]  = $vls;     
          }
        }
      $input1 = implode(",",$columns);
      $helpArr = array();
      for($i=0;$i<count($values);$i++){
       $helpArr[] = '?';
      }
      $input2 = implode(",",$helpArr);
      $this->_stmt = $this->_con->prepare("INSERT INTO `$tableForIns`($input1) VALUES($input2)")
      or $this->throwException();
     if($this->_stmt->execute($values)){
        return true;
       }else{
        $this->throwException();
       }
   }catch(Exception $e){
    echo 'ErrOR: '.$e->getMessage();
   }
 }
 public function delete($tableForDel,$params = array()){
  # DELETES DATA FROM SELECTED COLUMNS,, 
  /*
   @param1(string) - CHOOSE TABLE 
   @param2(array) -  'column'=>'value' 
  */ 
     try{
         $stack = array();
     foreach($params as $keys => $values){
         if(is_string($keys)){
           $stack[] = "`".$keys."`= :".$keys;   
         }
     }
      $input = implode(" AND ",$stack); 
      
      $this->_stmt = $this->_con->prepare("DELETE FROM `$tableForDel` WHERE $input")
      or  $this->throwException();
      if($this->_stmt->execute($params)){
         if($this->_stmt->rowCount() == 0){
           echo 'No Data For delete';
         }else{
          return true;
         }   
      }else{
        $this->throwException();
      }       
     }catch(Exception $e){
          echo 'ERooR: '.$e->getMessage();
     }
  }
  
}


?>
