<?php
/*
* Working with http requests
* Shadowphp framework
* @link https://github.com/Zarna0x/Shadow-mvc-framework
* @Author Zarna0x
*/

namespace Shadowapp\Sys\Http;

class Requester
{
	/*
	* Check if Request iS post
	*/
	public static function isPost()
	{
		
       if($_SERVER['REQUEST_METHOD'] == "POST")
       {
          return true;
       }
       else
       {
       	 return false;
       }
	}


	/*
	* Check if Request iS get
	*/
	public static function isGet()
	{
		
       if($_SERVER['REQUEST_METHOD'] == "GET")
       {
          return true;
       }
       else
       {
       	 return false;
       }
	}
    
    
    /*
    * Get Post request data
    */
	public static function getPost($key = '')
	{
      return (empty( $key )) ? $_POST  : $_POST[trim($key)] ;
       

       
	}

    /*
    * Redirect user
    * @param string $path
    */
	public static function redirect($path)
	{
       
       if(!empty($path))
       {
          Header('Location: '.$path);
       }
       else
       {
         Header('Location: home');
       }

       exit();
	}
	
       /*
       * Get All Requests
       */
       public static function all()
        {
	       if($_SERVER['REQUEST_METHOD'] == "GET")
	       {
	          return $_GET;
	       }
	       elseif ($_SERVER['REQUEST_METHOD'] == "POST") 
	       {
	         return $_POST;
	       }
      }


}

?>
