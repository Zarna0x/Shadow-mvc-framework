<?php

namespace Shadowapp\Sys;

use Exception;

abstract class ExceptionHandler
{
	/*
	* @return Object \Exception
	*/
	
    public function handle (Exception $exception) 
	{
      $dontReports = shcol('dontReport',get_class_vars(static::class),[]);
      
      if (in_array(get_class($exception),$dontReports)) {
      	
          return;
      }

      static::render($exception);
      static::report($exception);

      throw new $exception;

    
	}


}