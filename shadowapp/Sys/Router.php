<?php
 /*
 * Router 
 * @Author Zarna0x
 * (c) 2016
 */
 namespace Shadowapp\Sys;
 

 class Router
 {
  
 	protected static $_uri     = [];
 	protected static $_methods = [];
  protected static $_request = [];  
  protected static $_requestList = ['AJAX','GET','POST'];

   /*
    * Define routes and collect it to $_uri array
    * @param type $uri
    */
 	public static function define($uri,$method = null,$request_type = "get")
 	{
       self::$_uri[]   = trim($uri,'/');
       self::$_methods[trim($uri,'/')] = $method;
       self::$_request[trim($uri,'/')] = strtoupper($request_type);
     
 	}

    /*
    * Run Routes
    */
 	public static function run()
 	{
  
 		/*
 		* Parse Uri
 		*/
 		    $uri = isset($_GET['uri'])? trim(strip_tags($_GET['uri'])): '' ;
        $uriArr = explode('/', $uri);
        
        #check if current uri belongs to Route
        

        /*
        * Custom route To Load
        */
        $uriController = $uriArr[0];

        /*
        * Method To Load
        */
        $uriMethod     = isset($uriArr[1]) ? $uriArr[1] : null;
     
 		foreach(self::$_uri as $Key => $Value)
 		{  
     $routedUri = trim(substr($uri, 0 , strlen($Value)));

     if (empty($routedUri) && !empty($uri)) {
          $routedUri = md5(rand(0,999999999999));
     }  

	if($Value == $routedUri)
 			{

        $requestType    =  isset(self::$_request[$Value]) ? self::$_request[$Value] : null;
        
        if(!in_array($requestType, self::$_requestList))
        {
           echo 'Wrong Request method specified';
           die();
        }
        
        if ( $_SERVER['REQUEST_METHOD'] == $requestType) {
           
          /*
           * Check Route::Define arguments
           */
       
         if(is_array(self::$_methods[$Value]))
         {
            $controllerName = "Shadowapp\\Controllers\\".ucfirst(self::$_methods[$Value]['controller'])."Shadow";

            $methodName     =  isset(self::$_methods[$Value]['method']) ? self::$_methods[$Value]['method']."Method" : null;



           /*
           * Check If Class Exists
           */
                 if(class_exists($controllerName) == false)
                 {
                    echo $controllerName." doesn't exists";
                    return;
                 }

            /*
            * Check if Method Exists
            */
            if(isset(self::$_methods[$Value]['method']))
            {

                      if(method_exists($controllerName, $methodName))
                      { 
                        $paramString = trim(substr($uri,strlen($routedUri)),'/');
                        $paramArray  = explode('/', $paramString);
                        $paramCount  = (empty($paramString)) ? 0: count($paramArray);
                        

                        $reflectionMethod = new \ReflectionMethod($controllerName,$methodName);
                        $optArgCount = 0;
                        foreach ($reflectionMethod->getParameters() as $param) {
                          
                                if ($param->isOptional()) {
                                      $optArgCount++;
                                }

                        }
                        // Get All Available params
                        $numOfArgs = count($reflectionMethod->getParameters()) - $optArgCount;
                      
                        if ($paramCount < $numOfArgs) {
                            echo 'Wrong Param count!';
                            die;
                        }

                        //parr($reflectionMethod->getParameters());
                        $obj = new $controllerName;
                        call_user_func_array([
                          $obj,$methodName
                          ], $paramArray);
                        
                      }else
                      {
                         echo "Can't load  ".$methodName;
                         die;
                      }
                     


            }else
            {  
              
               new $controllerName;
            }
            #code here
         }
         /*
         * If Argument Is Function
         */
         else
         {
                   call_user_func(self::$_methods[$Value]);

         }
        } // Wrong Request method else
         
 			}

 		}

 	}

 }

?>
