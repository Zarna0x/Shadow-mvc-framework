<?php
/*
* Shadowphp framework
* @link https://github.com/Zarna0x/Shadow-mvc-framework
* @Author Zarna0x
*/

namespace Shadowapp\Sys;

class Validator
{
   /*
   * Static Properties
   */
   protected static $_rules = ['required','min','max','empty'];
   protected static $_message ;
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
       #echo '<pre>',print_r($rules,1),'</pre>';
       
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
  	     		if(!isset($req[$arg]))
  	     		{
  	     			return "param is required<br>";
  	     		    
  	     		}
  	     		 
  	     	break;

  	     	case 'min':
  	     		if(strlen($req[$arg]) <= $param)
  	     		{
  	     			return $req[$arg]." length must be more than ".$param."<br>";
  	     		    
  	     		}
  	     		 
  	     	break;

  	     	case 'max':
  	     		if(strlen($req[$arg]) >= $param)
  	     		{
  	     			return $req[$arg]." length must be less than ".$param."<br>";
  	     		    
  	     		}
  	     		 
  	     	break;

  	     	case 'empty':
  	     		if(empty($req[$arg]))
  	     		{
                   return 'Data is empty<br>';
                  
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
