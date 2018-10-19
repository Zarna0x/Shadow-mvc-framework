<?php


namespace Shadowapp\sys;


Class View
{ 
	/*
	* Run View
	* @paramstring $path - view path
	* @param array  $params - variables to set
	* @param boolean $autoBootstrap - specify include index/footer or not 
	* @Author Zarna0x
	*/
	public static function run($path,$params = null,$autoBootstrap = true)
	{   
		
		$viewPath     = dirname(dirname(__FILE__)).'/sh_views';
       	$explodedPath = explode('/', $path);



        $filePath     = file_exists($viewPath."/".$explodedPath[0]."/".$explodedPath[1].".shadow")? $viewPath."/".$explodedPath[0]."/".$explodedPath[1].".shadow" : null; 
	    
      
	   if($filePath != null)
	   { 


	     if($params != null)
	     {
               extract($params);
	           $templateCompiler = new \Shadowapp\Components\TemplateCompiler($filePath);
	           
	           $templateCompiler->assign($params);
	          if ($autoBootstrap) {

	          include_once $viewPath.'/index.shadow';
              include_once $templateCompiler->run();
              include_once $viewPath.'/footer.shadow';   
	                          
             } else {
               include_once $templateCompiler->run();
             }
	     }else{
             if ($autoBootstrap) {
                include_once $viewPath.'/index.shadow';
                include_once $filePath;
                include_once $viewPath.'/footer.shadow';   
	         } else {
              include_once $filePath;
             }
	     }
	   }else
	   {
	   	 echo 'View Doesnot Exists ...';
	   }
	}
}

?>
