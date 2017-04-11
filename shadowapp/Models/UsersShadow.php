<?php
 namespace Shadowapp\Models;
   
 
 use \Shadowapp\Sys\Dbmanager as Dbmanager;


class UsersShadow 
{

    protected $_db;
	public function __construct()
	{
		$this->_db = new Dbmanager();
		
    }


    public  function selectData()
    {
    	$select = $this->_db->select('users',['*'],[
             'name' => 'user',
             'password' => 'user123'
		]);

		
	return $select;
    }
}




?>
