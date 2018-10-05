<?php

namespace Shadowapp\Sys\Http;



class Middleware
{
    public static $middlewareDir = MIDDLEWARE_DIR;
    protected static $middlewareClasses = [];
    protected static $middlewareNamespace = "Shadowapp\\Components\\Middlewares\\";
    

	public static function handle( $middlewareParts )
	{
       if (!is_string( $middlewareParts ) || empty($middlewareParts)) {
          throw new \Shadowapp\Sys\Exceptions\WrongVariableTypeExcetion("Middleware Parameter have to be string", 1);
       }

       list($mdClass,$mdMethod) =  explode('.', $middlewareParts);
       
       $allowedMiddlewares = self::getMiddlewareClasses();
       
       if (!in_array(ucfirst($mdClass), $allowedMiddlewares )) {
           return;    
       }

       $fullMiddlewareClassName = self::$middlewareNamespace.ucfirst($mdClass);
       
       $currentMdClass = new $fullMiddlewareClassName;

       if ( !$currentMdClass instanceof MiddlewareInterface) {
       	 return;
       }

       if (!in_array($mdMethod, $currentMdClass->register())) {
           return;     
       } 

      (new \ReflectionMethod( $currentMdClass, $mdMethod ))
                                                ->invoke($currentMdClass);
      

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