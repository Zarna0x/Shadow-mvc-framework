<?php

namespace Shadowapp\Sys\Db\Json;

use Shadowapp\Sys\Config as Config;
use Shadowapp\Sys\Db\Connection;
use \PDO;

class Table
{
	protected $_db;
	protected $_dir;
	protected $_tableList = [];
	protected $_jsonContentList = [];
	protected $_notAllowed = ['.','..'];
	protected $_createQueries = [];

	public function __construct($jsonFile = JSON_DIR)
	{
        $this->_db  = Connection::get();
        $this->_dir = $jsonFile;
        $this->_tableList = array_values(array_filter(scandir($this->_dir),function($jsonSingleFile){
        	if (!in_array($jsonSingleFile, $this->_notAllowed)) {
                 return $jsonSingleFile;
        	}
        })); 
        array_filter($this->_tableList,function ($value) use ($jsonFile){
                $splData  = new \SplFileInfo($value);
                $fileExt  = $splData->getExtension();
                $fileName = $splData->getBasename('.json');
                $this->_jsonContentList[$fileName] = json_decode(file_get_contents($jsonFile.DS.$value));
        });
        
        foreach ($this->_jsonContentList as $jKey => $jValue) {
             $this->_createQueries[$jKey] = $this->_renderTable($jKey,$jValue);
             
		 }

	}
	public function execute($tableStr)
	{
		 if (array_key_exists($tableStr, $this->_createQueries)) {
             $this->_executeQuery($this->_createQueries[$tableStr]);
		 }
	}

	protected function _executeQuery($queryStr)
	{
       $pQuery = $this->_db->prepare($queryStr);
       try{
          var_dump($pQuery->execute());
       }catch(\PDOException $e){
          throw new \Shadowapp\Sys\Exceptions\Db\WrongQueryException($e->getMessage());
       }	
 }

	protected function _renderTable ($tableName,$tableData) 
	{
        $query = 'CREATE TABLE IF NOT EXISTS `'.$tableName.'` (';
        $qStack = [];

        foreach ($tableData as $column => $attrs) {
            
            $qStack[] = ' `'.$column.'` '.$this->_generateQueryAttrs($attrs);
            
        }

       $realTableQuery = implode(',', $qStack);
       $realQuery = $query.$realTableQuery.');';
       return $realQuery;
       
	}

	protected function _generateQueryAttrs($attributes)
	{
	   $queryStack = [];
	   $queryStack['type'] = shcol('type',$attributes);
	   if (!empty($queryStack['type'])) {
	     $queryStack['length'] = (!empty(shcol('length',$attributes))) ? '('.shcol('length',$attributes).')' : '';
	   }
	   $queryStack['null'] = (shcol('null',$attributes) == 'true') ? 'DEFAULT NULL' : 'NOT NULL' ;
	   if ($queryStack['null'] != 'DEFAULT NULL') {
           $queryStack['default'] = (!empty(shcol('default',$attributes))) ? 'DEFAULT "'.shcol('default',$attributes).'"' : '';
	   }
       $implodedQuery = trim(implode(' ', $queryStack));
       return $implodedQuery;
	   
	}

	public function getQueryString($tableStr)
	{
		 if (array_key_exists($tableStr, $this->_createQueries)) {
              echo $this->_createQueries[$tableStr];
	     }

	}
}