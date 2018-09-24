<?php
/*
* Shadowphp framework
* @link https://github.com/Zarna0x/Shadow-mvc-framework
* @Author Zarna0x
*/

namespace Shadowapp\Sys;

use Shadowapp\Sys\Db\Query\Builder as DB;

class Validator
{
   /*
   * @var array
   */
   protected static $_rules = [
    'required',
    'min',
    'max',
    'empty',
    'mail',
    'number',
    'unique',
    'confirmed'
  ];
   
   /*
   * @var string;
   */
   protected static $_message;

   /*
   * @var boolean;
   */
   protected static $_fail;
   
  /*
  * Run Validator
  * @param1 array $request
  * @param2 array $rules
  */

  public static function run($request,$rules = null)
  {
    
    if(isset($request))
    {
       foreach($rules as $fuck => $rule)
       { 
        
         foreach($rule as $data => $param)
         {


            if(is_string(self::validateRules($request,$fuck,$data,$param)))
            {

              self::$_message = self::validateRules($request,$fuck,$data,$param);
              self::$_fail    = true;
              return false;
            }
            self::$_fail    = false;
            

         }
       
       }
    }   
  
  }	

  /*
  * Validate Rules
  * @param1 array $req
  * @param2 string $arg
  * @param3 string $data
  * @param4 string $param
  */
  public static function validateRules($req,$arg,$data,$param)
  {

     if(in_array($data, self::$_rules))
  	 {
         	    
          
  	     switch ($data) {
  	     	
  	     	case 'required':

  	     		if(!isset($req[$arg]) && $param)
  	     		{
  	     			return '<b>'.$arg. "</b> param is required<br>";
  	     		    
  	     		}
  	     		 
  	     	break;

  	     	case 'min':
  	     		if(strlen($req[$arg]) <= $param)
  	     		{
  	     			return '<b>'.$arg."</b> length must be more than ".$param."<br>";
  	     		    
  	     		}
  	     		 
  	     	break;

  	     	case 'max':

  	     		if(strlen($req[$arg]) >= $param)
  	     		{
  	     			return '<b>'.$arg."</b> length must be less than ".$param."<br>";
  	     		    
  	     		}
  	     		 
  	     	break;

  	     	case 'empty':
  	     		if(empty($req[$arg]))
  	     		{
                   return '<b>'.$arg. '</b> is empty<br>';
                  
  	     		}
  	     		 
  	     	break;
         
         case 'mail':
           if (filter_var($req[$arg],FILTER_VALIDATE_EMAIL)  === false && $param ) {
               return '<b>'.$req[$arg].'</b> is not valid email!';
           }

         break;

         case 'number':
           if (  is_numeric( shcol($arg,$req) ) === false  && $param  ) {
              return '<b>'.$arg. '</b> Must be correct phone number';
           }
         break;     
         
         case 'unique':
         
         $uniqValue = shcol($arg,$req);
         
         // check if table exists
         $db = new DB; 

          try 
          {

            if (false !== $db->select($arg)->from( $param )->where([
              'email'     => $uniqValue,
              'confirmed' => 1
            ])->get()) {
                 return $arg. " <b>".$uniqValue.'</b> already exists in database';
            }
          } catch (\Shadowapp\Sys\Exceptions\Db\WrongQueryException $e) {
            return $e->getMessage();
          }
         
         break;
    	   


         case 'confirmed':

         $passValue = trim($req[$arg]);
         
         // check if field exists.
         if ( !isset( $req[trim($param)] ) ) {
            return 'Field <b>'.$param.'</b> does not exists';
         }  

         // check if they are same

         if ( $passValue !== trim($req[trim( $param )]) ) {
             return '<b>'.$arg.'</b> and <b>'.$param.'</b> have to be same !';
         }
        
        break;


  	     	default:
             echo "Wrong validation rule";
  	     	break;
  	     }
  	 }
  }

  /*
  * Check if validation failed
  */
  public static function failed()
  {
     return self::$_fail;
  }

  /*
  * Print Error Messages
  */
  public static function errorMessage()
  {
    return self::$_message;
  }

}

?>
