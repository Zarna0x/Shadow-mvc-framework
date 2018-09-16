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
	 * Define routes and collect it to $_uri array
	 * @param type $uri
	 */
	public static function define( $uri, $method = null, $request_type = "get" )
	{

	    self::$_routes[strtoupper( $request_type )][trim( $uri, '/' )] = $method;
        self::$_currentRequstMethod = shcol("REQUEST_METHOD",$_SERVER);
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
             echo 'No Route for given uri';
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
	
					}
					/*
					 * If Argument Is Function
					 */
					else
					{
 						call_user_func( $routedArg );
					}
			
		

	}

}

?>
