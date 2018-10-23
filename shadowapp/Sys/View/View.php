<?php


namespace Shadowapp\Sys\View;


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
		
		$viewPath     = basePath().'sh_views';

       	$explodedPath = explode('/', $path);

	
                 
         $filePath     = file_exists($viewPath.DS.shcol(0,$explodedPath).DS.shcol(1,$explodedPath).'.shadow')? $viewPath.DS.shcol(0,$explodedPath).DS.shcol(1,$explodedPath).'.shadow': null; 
        
        if (is_null( $filePath )) {

           throw new \Shadowapp\Sys\Exceptions\View\ViewNotFoundException("View  does not exists", 1);
        }


	     if($params != null)
	     {
               extract($params);
	           $templateCompiler = new Compiler($filePath);
	           
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
	   }
	}



?>
