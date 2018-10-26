<?php

namespace Shadowapp\Sys;

Class Config
{
	protected $_basePath;

	protected $_configData;
    

	public function __construct($configFile = 'config.ini')
	{
	   $this->_basePath   = dirname(dirname(dirname(__FILE__)));

       if ($configFile != 'config.ini' && file_exists($configFile)) {

          	if (substr($configFile, strrpos($configFile,'.') + 1) == 'ini') {
          	   $this->_configData = $configFile;
          	} else {
              die('Wrong Config File,  extension must be .ini');
          	  }
          }  else {
       
         $this->_configData = $this->_basePath.'/config.ini';
      }
        
	}

	public function get()
	{

        if ( !file_exists( $this->_configData ) ) {
        	$message = $this->_configData.' not found';
           throw new \Shadowapp\Sys\Exceptions\ConfigurationFileNotFoundException($message);
        }
 
		return (object)parse_ini_file($this->_configData);
	}


}