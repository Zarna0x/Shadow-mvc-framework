<?php

namespace Shadowapp\Sys\Db;

use Shadowapp\Sys\Config;
use \PDO;

Class Connection 
{

	/**
     * pdo connection.
     *
     * @var PDO object
     */
	protected static $connection;
    

    /**
     * static function for getting connection object.
     *
     * @param  none
     * @return \PDO object
     */
	public static function get()
	{
        try
	    	{
          $config = new Config;
          
          $data = $config->get(); 
		  	 
          self::$connection    = new PDO("mysql:host=$data->db_host;dbname=$data->db_name",$data->db_user,$data->db_pass);
          self::$connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);  
	        
          return self::$connection;
		}
		catch(PDOException $e)
		{
          echo $e->getMessage();
		}
	}


  
}