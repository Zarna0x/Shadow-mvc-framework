<?php

namespace Shadowapp\Sys\Http;

class Middleware
{
    public static $middlewareDir = MIDDLEWARE_DIR;
    protected static $middlewareClasses = [];
    protected static $middlewareNamespace = "Shadowapp\\Components\\Middlewares\\";
    

	public static function handle( string $middlewareParts )
	{
       if (!is_string( $middlewareParts ) || empty($middlewareParts)) {
          throw new \Shadowapp\Sys\Exceptions\WrongVariableTypeException("Middleware Parameter have to be string", 1);
       }

       list($mdClass,$mdMethod) =  explode('.', $middlewareParts);
       
       $allowedMiddlewares = self::getMiddlewareClasses();
       
       if (!in_array(ucfirst($mdClass), $allowedMiddlewares )) {
           throw new  \Shadowapp\Sys\Exceptions\MiddlewareNotFoundException("Middleware class ".ucfirst($mdClass)." not found.");
       }

       $fullMiddlewareClassName = self::$middlewareNamespace.ucfirst($mdClass);
       
       $currentMdClass = new $fullMiddlewareClassName;

       if ( !$currentMdClass instanceof MiddlewareInterface) {
       	 return;
       }

       if (!in_array($mdMethod, $currentMdClass->register())) {
           throw new  \Shadowapp\Sys\Exceptions\MiddlewareMethodNotFoundException("Middleware method ".$mdMethod." not found in class ".ucfirst($mdClass));    
       } 

      (new \ReflectionMethod( $currentMdClass, $mdMethod ))
                                                ->invoke($currentMdClass);
      

	}

    public static function exists ( string $middlewareParts )
    {
      $middlewareArray = explode('.', $middlewareParts);
       
      if (count($middlewareArray) != 2) {
        
         return false;
      } 

      list($mdClass,$mdMethod) =  $middlewareArray;
       
       $allowedMiddlewares = self::getMiddlewareClasses();
       if (!in_array(ucfirst($mdClass), $allowedMiddlewares )) {
           return false;
       }

       $fullMiddlewareClassName = self::$middlewareNamespace.ucfirst($mdClass);
       
       $currentMdClass = new $fullMiddlewareClassName;

       if ( !$currentMdClass instanceof MiddlewareInterface) {
         return false;
       }

       if (!in_array($mdMethod, $currentMdClass->register())) {
           return false;  
       } 

       return true;
    }

    public static function getMiddlewareClasses()
    {
    	$classCollection = array();

    	array_values(array_filter(scandir(self::$middlewareDir),function($class){
        	if ( $class != '.'  && $class != '..') {
               self::$middlewareClasses[] = basename($class,'.php');
        	}
        }));

        return self::$middlewareClasses;
    }
}