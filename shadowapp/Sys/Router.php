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
           'apiPrefix' => self::$defaultApiPrefix,
           'action'    => $method 
		];
		return new static;
	}

	protected static function getPrefix()
	{
		return self::$defaultApiPrefix;
	}

	public static function withPrefix ( string $apiPrefix ) 
	{
      if (!empty( $apiPrefix ) && is_string( $apiPrefix )) {
         self::$defaultApiPrefix = strtolower(trim($apiPrefix));
      } 
      return new static;
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
         
         if ( false === array_key_exists($routedUri, self::$_routes[self::$_currentRequstMethod]) ) {
           
         	 if (false === self::apiEndpointExists( $uri )) {
                echo 'No Route for given uri';
                exit;
         	 }
             

             self::runAPI();
             exit;
         }
 
			if ( empty( $routedUri ) && !empty( $uri ) )
			{
				
				$routedUri = md5( rand( 0, 999999999999 ) );
			}

			$routedArg = shcol($routedUri,self::$_routes[self::$_currentRequstMethod]);

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
