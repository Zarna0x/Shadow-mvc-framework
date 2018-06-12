<?php

namespace Shadowapp\Sys\Db\Json;

use Shadowapp\Sys\Db\Connection;


class Table
{
	protected $_db;
	protected $_dir;
	protected $_tableList = [];
	protected $_jsonContentList = [];
	protected $_notAllowed = ['.','..'];
	protected $_createQueries = [];
    protected $_otherValues = [];
    

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
            return $this->_executeQuery($this->_createQueries[$tableStr]);
		 }
	}

	protected function _executeQuery($queryStr)
	{
       $pQuery = $this->_db->prepare($queryStr);
       try{
          return $pQuery->execute();
       }catch(\PDOException $e){
          throw new \Shadowapp\Sys\Exceptions\Db\WrongQueryException($e->getMessage());
       }	
 }

	protected function _renderTable ($tableName,$tableData) 
	{
        $query = 'CREATE TABLE IF NOT EXISTS `'.$tableName.'` (';
        $qStack = [];
        $syscfg = '';

        $pkStr = '';
        foreach ($tableData as $column => $attrs) {
            if ($column == 'indexes') {

               $syscfg = $this->_renderCfgData($attrs);
               continue;
            }

            
            if (shcol('primary_key',$attrs) === true) {
           
               $pkStr = "PRIMARY KEY (".$column.")";
	        }
            
            $qStack[] = ' `'.$column.'` '.$this->_generateQueryAttrs($attrs);
            
        }

       $realTableQuery = implode(',', $qStack);
       
       if (!empty($pkStr)) {
          $realTableQuery .= ', '.$pkStr;
       }
       $realQuery = $query.$realTableQuery.')';
       if (!empty($syscfg)){
          $realQuery .= ' '.$syscfg.' ;';
       }else {
       	  $realQuery .= ' ;';
       }
        return $realQuery;
       
	}

	protected function _renderCfgData($cfgData)
	{
        $this->_otherValues = array_change_key_case(array_merge($this->_otherValues,(array)$cfgData),CASE_UPPER);
        
        $engineStr = '';
        if (!empty(shcol('ENGINE',$this->_otherValues))) {
            $engineStr = "ENGINE = ".shcol('ENGINE',$this->_otherValues);

        }

         $diffStr = '';
        foreach (array_reverse($this->_otherValues) as $fula => $sfx) {
              if ($fula == 'ENGINE') {
                   continue;
              }

             $diffStr .= $fula.'='.$sfx.' ';
        }
        
         $realStr = '';
        if (!empty($diffStr)){
           $realStr = trim($engineStr).' DEFAULT '.trim($diffStr);   
        }

       return $realStr;


	}

	protected function _generateQueryAttrs($attributes)
	{
	   $queryStack = [];
	   $queryStack['type'] = shcol('type',$attributes);
	   if (!empty($queryStack['type'])) {
	     $queryStack['length'] = (!empty(shcol('length',$attributes))) ? '('.shcol('length',$attributes).')' : '';
	   }
	   
       if (!empty(shcol('auto_increment',$attributes))) {
             $queryStack['auto_increment'] = 'AUTO_INCREMENT';
             $this->_otherValues['auto_increment'] = shcol('auto_increment',$attributes);
       }
	   $queryStack['null'] = (shcol('null',$attributes) == 'true') ? 'DEFAULT NULL' : 'NOT NULL' ;
	   if ($queryStack['null'] != 'DEFAULT NULL') {
           $queryStack['default'] = (!empty(shcol('default',$attributes))) ? 'DEFAULT "'.shcol('default',$attributes).'"' : '';
	   }
       
	   
	   // parr($this->_otherValues);

	   // die;
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