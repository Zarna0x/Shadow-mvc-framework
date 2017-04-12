<?php
 /*
 * Router 
 * @Author Zarna0x
 * (c) 2016
 */
 namespace Shadowapp\Sys;
 
 Class Router
 {
  
 	protected static $_data    = null;
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
 		    $uri = isset($_GET['uri'])? $_GET['uri']: '/' ;
        $uriArr = explode('/', $uri);

        /*
        * Custom route To Load
        */
        $uriController = $uriArr[0];

        /*
        * Method To Load
        */
        $uriMethod     = isset($uriArr[1]) ? $uriArr[1] : null;
         
        $matched = false; // not matched
 		foreach(self::$_uri as $Key => $Value)
 		{   
 			if($Value == $uriController)
 			{
        $matched = true;

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
                    
                 }

            /*
            * Check if Method Exists
            */
            if(isset(self::$_methods[$Value]['method']))
            {
                      if(method_exists($controllerName, $methodName))
                      { 
                        $obj = new $controllerName;
                        $obj->$methodName();
                    
                      }else
                      {
                         echo "Can't load  ".$methodName;
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
      }
   }

 } 
  if (!$matched) {
       self::$_data = new Config;
        $viewsDir = dirname(dirname(__FILE__)).'/sh_views/';  
       
       if (!isset(self::$_data->get()->page['not_found'])) {
          echo "Wrong config.ini configuration. Cant find page[not_found] parameter";
          die;
       }

        $notFoundFile = $viewsDir.self::$_data->get()->page['not_found'].'.shadow';
        if (!file_exists($notFoundFile)) {
           die('Cant find requested file');
        }

        require_once $notFoundFile;
  }

 }
}

?>