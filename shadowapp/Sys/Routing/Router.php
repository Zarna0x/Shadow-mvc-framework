<?php

/*
 * Router 
 * @Author Zarna0x
 * (c) 2016
 */

namespace Shadowapp\Sys\Routing;

use Closure;
use Shadowapp\Sys\Http\Middleware;
use Shadowapp\Sys\Traits\RouteValidatorTrait;

class Router implements RouterInterface
{
    use RouteValidatorTrait;

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
    protected static $_requestList = ['AJAX', 'GET', 'POST'];

    /*
     * @var string
     */
    protected static $defaultApiPrefix = 'api';
    protected static $customApiPrefixes = [];
    protected static $middlewares = [];

    protected static $groupOptions = [
      'started' => false
    ];

    

    private static $counters = [
      'withPrefixCounter'     => 0,
      'withMiddlewareCounter' => 0
    ];
    
    /*
     * @var array
     */
    protected static $apiRoutes = [];

    protected static $lastApiRoute;

    /*
     * Define routes and collect it to $_uri array
     * @param type $uri
     */

    private function __construct ( $lastRoute = '' ) {
       
       if ( !empty($lastRoute) && is_string($lastRoute) ) {
          self::$lastApiRoute = $lastRoute;
       }
    }


   public function getLastApiRoute()
   {
      return self::$lastApiRoute;
   }

    public static function define($uri, $method = null, $request_type = "get") {
        
        self::validateRouteParams($method);

        if (is_string($method)) {
           $method = explode('@', $method);
           
            foreach (['controller','method'] as $key => $value) {
              if (isset($method[$key])){
                $method[$value] = $method[$key];
                unset($method[$key]);
              }
           } 
        }
 
        self::$_routes[strtoupper($request_type)][trim($uri, '/')] = $method;
        self::$_routes[strtoupper($request_type)][trim($uri, '/')]['middleware'] = self::getMiddleware();
        self::$_currentRequstMethod = shcol("REQUEST_METHOD", $_SERVER);
    }

    public static function api($apiUri, $method = null, $request_type = "get") {

        self::$apiRoutes[strtoupper($request_type)][trim($apiUri, '/')] = [
            'apiPrefix' => self::getPrefix(),
            'middleware' => self::getMiddleware(),
            'action' => $method
        ];
        return new static($apiUri);
    }

    public static function withPrefix(string $prefix) {

        if ($prefix != self::$defaultApiPrefix && !empty($prefix) && is_string($prefix)) {
            self::$customApiPrefixes[self::$counters['withPrefixCounter']] = $prefix;
        }
        return new static;
    }

    public static function withMiddleware( string $middlewareMethod )
    {
       if ((is_string( $middlewareMethod ) && !empty( $middlewareMethod )) && Middleware::exists($middlewareMethod)) {
             self::$middlewares[self::$counters['withMiddlewareCounter']] = $middlewareMethod;
       }

      return new static;
    }

    protected static function getPrefix() {

        $currentPrefixCount = self::$counters['withPrefixCounter'];

        self::$counters['withPrefixCounter']++;

        return shcol($currentPrefixCount, self::$customApiPrefixes, self::$defaultApiPrefix);
    }


    protected static function getMiddleware()
    {
        $currentMiddlewareCount = self::$counters['withMiddlewareCounter'];
        
        self::$counters['withMiddlewareCounter']++;

       return shcol($currentMiddlewareCount, self::$middlewares, null);   
    }

    /*
     * Run Routes
     */

    public static function run() {

        /*
         * Parse Uri
         */
        $uri = isset($_GET['uri']) ? trim(strip_tags($_GET['uri'])) : '';
        $uriArr = explode('/', $uri);


        #check if current uri belongs to Route


        /*
         * Custom route To Load
         */
        $uriCustom = $uriArr[0];

        /*
         * Method To Load
         */
        $uriMethod = isset($uriArr[1]) ? $uriArr[1] : null;
        $routedUri = trim(shcol(0, explode('/', $uri)));

        $from = 'web';
         
         


        if (false === array_key_exists($routedUri, self::$_routes[self::$_currentRequstMethod])) {
         
            if (false === self::isApiRouteConfirmed($uri)) {
                echo 'No Route for given uri';
                exit;
            }

            $from = 'api';
        }

        
        self::exec($routedUri, $uri, $from);
    }

    private static function exec($routedUri, $uri, $appType) {
        if (!in_array($appType, ['web', 'api'])) {
            echo 'Wrong Application type';
            exit;
        }

        if (empty($routedUri) && !empty($uri)) {

            $routedUri = md5(rand(0, 999999999999));
        }

        if ($appType == 'api') {

            $routedUri = self::getApiUriWithoutPrefix($uri);
        }

        $routedArg = ($appType == 'web') ?
                shcol($routedUri, self::$_routes[self::$_currentRequstMethod]) :
                shcol("{$routedUri}.action", self::$apiRoutes[self::$_currentRequstMethod]);


        /*
         * Check Route::Define arguments
         */

        if (is_array($routedArg)) {
            if (false === array_key_exists('controller', $routedArg)) {

                echo "You must specify Controller.";
                exit;
            }


            $controllerName = "Shadowapp\\Controllers\\" . ucfirst($routedArg['controller']) . "Shadow";


            $methodName = isset($routedArg['method']) ? $routedArg['method'] : null;


            /*
             * Check If Class Exists
             */
            if (class_exists($controllerName) == false) {

                echo $controllerName . " doesn't exists";
                return;
            }

            /*
             * Check if Method Exists
             */

            if (isset($routedArg['method'])) {

                if (method_exists($controllerName, $methodName)) {
                  
                    $paramString = trim(substr($uri, strlen($routedUri)), '/');
                    $paramArray = explode('/', $paramString);
                    $paramCount = (empty($paramString)) ? 0 : count($paramArray);
       
                    
                    $pApiArray = [];     
                    if ( $appType == 'api' ) {
                       
                       $methodParams = shcol($routedUri.'.params', self::$apiRoutes[self::$_currentRequstMethod],[]);

                       
                                  
                       $expUri = explode('/',$uri);
                       foreach ( $methodParams as $key => $methValue) {

                            $pApiArray[$methValue] =  $expUri[$key];
                       } 

                    }
                    
                   
                    self::checkMiddleware($appType , $routedUri );

                    $reflectionMethod = new \ReflectionMethod($controllerName, $methodName);

                    $optArgCount = 0;

                    foreach ($reflectionMethod->getParameters() as $param) {

                        if ($param->isOptional()) {
                            $optArgCount++;
                        }
                    }

                    // Get All Available params
                    $numOfArgs = count($reflectionMethod->getParameters()) - $optArgCount;

                    
                     if ($paramCount < $numOfArgs && $appType == 'web') {
                        echo 'Wrong Param count!';
                        die;
                    }
                    
                    
                    if (count($pApiArray) && $appType == 'api') {
                      self::validate($pApiArray);
                    } 

                    $obj = new $controllerName;

                    
                    if ($appType == 'web') {
                        (empty($paramCount))?
                          $reflectionMethod->invoke($obj):
                          call_user_func_array([
                            $obj, $methodName
                                ], $paramArray);
                        exit;
                    }

                # Validate Request

                  (empty($pApiArray))?
                        $reflectionMethod->invoke($obj):
                        call_user_func_array([
                            $obj, $methodName
                                ], $pApiArray);

                    
                } else {
                    echo "Can't load  " . $methodName;
                    die;
                }
            } else {

                self::checkMiddleware($appType , $routedUri );

                new $controllerName;
            }
            #code here
        }
        /*
         * If Argument Is Function
         */ else {
            self::checkMiddleware($appType , $routedUri );
            call_user_func($routedArg);
        }
        // Wrong Request method else
    }

    protected static function checkMiddleware ($appType , $routedUri ) 
    {
       $currentMiddlewareToExecute = ( $appType == 'api')?
                        shcol('middleware',self::$apiRoutes[self::$_currentRequstMethod][$routedUri],null)
                   : shcol('middleware',self::$_routes[self::$_currentRequstMethod][$routedUri],null);
                    
                    if (!is_null( $currentMiddlewareToExecute )) {
                        Middleware::handle($currentMiddlewareToExecute);
                    }
    }


    public static function group( array $groupOptions, Closure $func )
    {
        
       self::$groupOptions['started'] = true;

       call_user_func_array($func,['pk']);
    }

    public static function apiEndpointExists($expectedEndpoint) {
        return (in_array($expectedEndpoint, self::getListOfApiEndpoints())) ? true : false;
    }

    private static function isApiRouteConfirmed( $uri )
    {
       $listOfApiEndpoints = self::getListOfApiEndpoints($uri);
        
       if ( !count($listOfApiEndpoints) ) {
           return false;
       }

       foreach ( $listOfApiEndpoints as $endpoint ) {
          if ( self::matches($uri,$endpoint) ) return true;  
       }

       return false;

    }

    private static function matches ( $uri, $endpoint )
    {
         $uriArr = array_filter(explode('/',$uri));
         $endpointArr = array_filter(explode('/', $endpoint));
  
         
         if ( count($uriArr) !== count($endpointArr) ) {
             return false;
         }

         $diff = array_diff($endpointArr,$uriArr);
         
         if (!self::containsBraces( $endpointArr ) && count($diff) == 0) {
             return true;
         } 

         if ( count($diff) == 0 ) return false; 

         foreach ($diff as $key => $part) {
           if (!self::stringContainsBraces($part)) {
             return false;
           }
         }

         // update endpoint uri with correct value
          
         self::setCorrectApiUri($uri,$endpoint,$diff);
         return true;

    }

    private static function setCorrectApiUri ( $uri, $endpoint, $params ) 
    {
         $endpointUri = self::getApiUriWithoutPrefix($endpoint);
         $actualUri   = self::getApiUriWithoutPrefix($uri);
         
         self::$apiRoutes[self::$_currentRequstMethod][$actualUri] = self::$apiRoutes[self::$_currentRequstMethod][$endpointUri];
         self::$apiRoutes[self::$_currentRequstMethod][$actualUri]['params'] = $params;
        
         unset(self::$apiRoutes[self::$_currentRequstMethod][$endpointUri]);

    }

    public function where ( array $whereParams )
    {
       
       //code here
    }

    private static function getApiUriWithoutPrefix ($apiUri) {

       return implode('/', array_filter(explode('/', $apiUri), function ($value, $key) {
                        if ($key > 0) {
                            return true;
                        }
                    }, ARRAY_FILTER_USE_BOTH));
    }


    
    
    public static function setDefaultApiPrefix( $apiPrefix )
    {
        if ( !empty( $apiPrefix ) && is_string( $apiPrefix ) ) {
            self::$defaultApiPrefix = trim(strip_tags($apiPrefix));
        }
    }

    private static function getListOfApiEndpoints() {

        $endpoints = [];
        $t = shcol(self::$_currentRequstMethod, self::$apiRoutes);

        if (empty($t)) {
            return $endpoints;
        }

        foreach ($t as $endp => $endpArr) {
            $endpoints[] = shcol('apiPrefix', $endpArr) . "/" . $endp;
        }

        return $endpoints;
    }

}