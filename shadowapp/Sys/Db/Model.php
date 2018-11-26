<?php

namespace Shadowapp\Sys\Db;

use Shadowapp\Sys\Db\Query\Builder as db;
use Shadowapp\Sys\Traits\RelationshipTrait;
use Shadowapp\Sys\Eventing\Interfaces\EventInterface;
use Shadowapp\Sys\Eventing\Event;
use Shadowapp\Sys\Traits\Eventing\Eventer as EventTrait;

abstract class Model
{

    use RelationshipTrait;
    use EventTrait;

    protected $db;
    private $table = '';
    protected $con;
    protected $_tableCollection = [];
    protected $relatedTables = [];
    protected $relationshipPrefix = 'relate';
    protected $relatedResults = [];
    protected $fullModelClass = '';

    public function __construct()
    {
        $this->db = new db;
        $this->getTable();
    }

    public function __set($columnName, $val)
    {
        if (in_array($columnName, $this->getColumnList())) {
            $this->_tableCollection[$columnName] = $val;
        }
    }

    public function __call($name, $arguments)
    {
        $calledRelMethod = strtolower($name);
        if (!in_array($calledRelMethod, $this->allowedRelationshipMethods)) {
            return;
        }

        $methodToCall = 'make' . $name;

        return $this->$methodToCall($arguments);
    }

    public function __get($columnName)
    {
        return $this->_tableCollection[$columnName];
    }

    public function getTableData()
    {
        return $this->_tableCollection;
    }

    public function save()
    {

        if (!count($this->_tableCollection)) {
            echo "Specify Columns to inserts";
            return;
        }
        $insertColumns = array_keys($this->_tableCollection);

        foreach ($this->_tableCollection as $val) {
            $askMarkData[] = '?';
            $valueData[] = $val;
        }


        $SQL = "INSERT INTO " . $this->table . "(" . implode(",", $insertColumns) . ") VALUES (" . implode(',', $askMarkData) . ")";

        $stmt = $this->con->prepare($SQL);

        if (!$stmt->execute($valueData)) {
            return false;
        }

        $this->_tableCollection['id'] = $this->con->lastInsertId();
        return true;
    }

    public function getColumnList()
    {
        $this->con = Connection::get();
        $stmt = $this->con->query("DESCRIBE " . $this->table);
        $stmt->execute();
        foreach ($stmt->fetchAll() as $data) {
            $fieldData[] = $data['Field'];
        }

        return $fieldData;
    }

    protected function getTable()
    {
        $fullClassName = new \ReflectionClass(get_called_class());
        $offset = strpos($fullClassName->getShortName(), 'Shadow');
        $this->fullModelClass = shcol('name', $fullClassName);
        $this->table = strtolower(substr($fullClassName->getShortName(), 0, $offset));
    }

    public function withRelated($foreignTables)
    {



        if (!is_string($foreignTables) && !is_array($foreignTables)) {
            throw new \ Shadowapp\Sys\Exceptions\WrongVariableTypeException("Wrong Variable Type. Variable should be array or string", 1);
        }


        (is_array($foreignTables)) ?
                        $this->relatedTables = array_merge($foreignTables) :
                        $this->relatedTables[] = $foreignTables
        ;

        $relationshipMethodList = $this->getRelatedMethods($this->relatedTables);

        if (!count($relationshipMethodList)) {
            exit('Relationship Methods doesnot exists');
        }

        $childObject = new $this->fullModelClass;


        foreach ($relationshipMethodList as $method) {
            $this->relatedResults[$method] = (new \ReflectionMethod($childObject, $method))->invoke($childObject);
        }



        return $this;
    }

    protected function getRelatedMethods($foreignTables)
    {
        if (!is_string($foreignTables) && !is_array($foreignTables)) {
            throw new \ Shadowapp\Sys\Exceptions\WrongVariableTypeException("Wrong Variable Type. Variable should be array or string", 1);
        }


        $expectedMethods = array_map(function ($table) {
            return $this->relationshipPrefix . ucfirst($table);
        }, $foreignTables);

        $modelMethds = get_class_methods(get_called_class());


        foreach ($expectedMethods as $k => $meth) {
            if (!in_array($meth, $modelMethds)) {
                unset($expectedMethods[$k]);
            }
        }

        return $expectedMethods;
    }

    /*
     * find result using primary key or specify pamereters using array,
     * if table doesnot contains primary key result will be fetchecd by ID
     */

    public function find($primaryKeyOrArray)
    {
        if (is_array($primaryKeyOrArray)) {
            $result = $this->db->where($primaryKeyOrArray)->get($this->table);
        } else {

            $primaryKey = ($this->db->getPrimaryKey($this->table) != false) ? $this->db->getPrimaryKey($this->table) : 'id';

            $result = $this->db->where($primaryKey, $primaryKeyOrArray)->get($this->table);
        }


        if (false === $result) {
            return false;
        }

        if (!count($this->relatedResults)) {
            return $result;
        }

        $withRel = [];

        foreach ($result as $mKey => $tabledata) {
            $withRel[] = $this->generateWithRelationships($tabledata);
        }

        return $withRel;
    }

    private function generateWithRelationships($fetchedData)
    {
        if (!is_object($fetchedData) && !is_array($fetchedData)) {
            throw new \ Shadowapp\Sys\Exceptions\WrongVariableTypeException("Wrong Variable Type. Variable should be array or object", 1);
        }

        try {

            foreach ($this->relatedResults as $rName => $rArray) {
                extract($rArray);
                $foreignKeyValue = shcol($foreign_key, $fetchedData);

                $stmt = (Connection::get())->prepare($sql);

                $fetchedData->$rel_tbl_name = (false === $stmt->execute([$foreignKeyValue])) ? [] : $stmt->fetchAll(\PDO::FETCH_OBJ);
            }


            return $fetchedData;
        } catch (\PDOException $e) {
            throw new \Shadowapp\Sys\Exceptions\Db\WrongQueryException($e->getMessage());
        }
    }

    public function findFirst($primaryKeyOrArray, $dataType = 'array')
    {
        $result = $this->find($primaryKeyOrArray);


        if (false === $result) {
            return false;
        }

        $allowedDataTypes = ['array', 'object'];

        if (!in_array($dataType, $allowedDataTypes)) {
            throw new \ Shadowapp\Sys\Exceptions\WrongVariableTypeException("Wrong Variable Type. Variable should be array or object", 1);
        }

        return ($dataType == 'object') ?
                shcol('0', $result) : (array) shcol('0', $result);
    }

}
