<?php
/*
 * Router 
 * @Author Zarna0x
 * (c) 2016
 */
namespace Shadowapp\Sys;

class Router
{
   /*
	* @var array
	*/
    protected static $_routes;
    
   /*
	* @var string
	*/
    protected static $_currentRequstMethod;
    
   /*
	* @var array
	*/
    protected static $_requestList = [ 'AJAX', 'GET', 'POST' ];
    
   /*
	* @var string
	*/
    protected static $defaultApiPrefix = 'api';

    protected static $customApiPrefixes = [];

    private static $withPrefixCounter = 0;
   

   /*
	* @var array
	*/
	protected static $apiRoutes = [];

	/*
	 * Define routes and collect it to $_uri array
	 * @param type $uri
	 */
	public static function define( $uri, $method = null, $request_type = "get" )
	{
	    self::$_routes[strtoupper( $request_type )][trim( $uri, '/' )] = $method;
        self::$_currentRequstMethod = shcol("REQUEST_METHOD",$_SERVER);
	}


	public static function api ($apiUri, $method = null, $request_type = "get")
	{

	  
	  
	  self::$apiRoutes[strtoupper( $request_type )][trim( $apiUri, '/' )] = [
           'apiPrefix' => self::getPrefix(),
           'action'    => $method 
		];
		return new static;
	}

	public static function withPrefix(string $prefix) 
	{
     
       if ($prefix != self::$defaultApiPrefix && !empty($prefix) && is_string($prefix)) {
           self::$customApiPrefixes[self::$withPrefixCounter] = $prefix;
        }
      return new static;
	}

	protected static function getPrefix()
	{ 

	  $currentPrefixCount = self::$withPrefixCounter;

	  self::$withPrefixCounter++;

	  return shcol($currentPrefixCount,self::$customApiPrefixes,self::$defaultApiPrefix); 
	}

	

	/*
	 * Run Routes
	 */
	public static function run()
	{

		/*
		 * Parse Uri
		 */
		$uri = isset( $_GET['uri'] ) ? trim( strip_tags( $_GET['uri'] ) ) : '';
		$uriArr = explode( '/', $uri );


		#check if current uri belongs to Route


		/*
		 * Custom route To Load
		 */
		$uriCustom = $uriArr[0];

		/*
		 * Method To Load
		 */
		$uriMethod = isset( $uriArr[1] ) ? $uriArr[1] : null;
        $routedUri = trim(shcol(0,explode('/', $uri)));

        $from = 'web';
        
        if ( false === array_key_exists($routedUri, self::$_routes[self::$_currentRequstMethod]) ) {
           

         	 if (false === self::apiEndpointExists( $uri )) {
                echo 'No Route for given uri';
                exit;
         	 }
             
           $from = 'api';
         }

		  self::exec($routedUri,$uri,$from);
	}

	private static function exec($routedUri,$uri,$appType)
	{
           if (!in_array($appType, ['web','api'])) {
               echo 'Wrong Application type';
               exit;
           }

			if ( empty( $routedUri ) && !empty( $uri ) )
			{
				
				$routedUri = md5( rand( 0, 999999999999 ) );
			}

			echo '<pre>'.print_R(self::$apiRoutes,1).'</pre>'; 
			die;


			$routedArg = shcol($routedUri,self::$_routes[self::$_currentRequstMethod]);

die;


					/*
					 * Check Route::Define arguments
					 */

					if ( is_array( $routedArg ) )
					{
                      if ( false === array_key_exists('controller', $routedArg) ) {
                            
                            echo "You must specify Controller.";
                            exit;
                        }


						$controllerName = "Shadowapp\\Controllers\\" . ucfirst( $routedArg['controller'] ) . "Shadow";


						$methodName = isset( $routedArg['method'] ) ? $routedArg['method'] : null;


						/*
						 * Check If Class Exists
						 */
						if ( class_exists( $controllerName ) == false )
						{

							echo $controllerName . " doesn't exists";
							return;
						}

						/*
						 * Check if Method Exists
						 */

						if ( isset( $routedArg['method'] ) )
						{

							if ( method_exists( $controllerName, $methodName ) )
							{

								$paramString = trim( substr( $uri, strlen( $routedUri ) ), '/' );
								$paramArray = explode( '/', $paramString );
								$paramCount = (empty( $paramString )) ? 0 : count( $paramArray );


								$reflectionMethod = new \ReflectionMethod( $controllerName, $methodName );
							
								$optArgCount = 0;

								foreach ( $reflectionMethod->getParameters() as $param )
								{

									if ( $param->isOptional() )
									{
										$optArgCount++;
									}
								}
								// Get All Available params
								$numOfArgs = count( $reflectionMethod->getParameters() ) - $optArgCount;

								if ( $paramCount < $numOfArgs )
								{
									echo 'Wrong Param count!';
									die;
								}

								
					 			$obj = new $controllerName; 
                                
                                if (empty( $paramCount )) {
                                  $reflectionMethod->invoke( $obj ); 
                                } else {
                                	call_user_func_array( [
										$obj, $methodName
												], $paramArray );
                                }
							}
							else
							{
								echo "Can't load  " . $methodName;
								die;
							}
						}
						else
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
 						call_user_func( $routedArg );
					}
				 // Wrong Request method else
	}

	private static function apiEndpointExists( $expectedEndpoint )
	{
      return (in_array( $expectedEndpoint, self::getListOfApiEndpoints() )) ? true : false ;
	}


	private static function getListOfApiEndpoints()
	{
		
		$endpoints = [];

		foreach (shcol(self::$_currentRequstMethod,self::$apiRoutes) as $endp => $endpArr ) {
          $endpoints[] = shcol('apiPrefix',$endpArr)."/".$endp;
		}

		return $endpoints;
	}

	public static function runAPI()
	{
		
	}

}
