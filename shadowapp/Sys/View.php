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
	     
	           //print_r($params);
	           #echo '<pre>',print_r(get_included_files(),1),'</pre>';
	          
	           $templateCompiler = new \Shadowapp\Components\TemplateCompiler($filePath);
	           // $appendRules = [
               //      'include' 
	           // ];

	           // $params = array_merge($params,$appendRules);
	           
	           $templateCompiler->assign($params);
	          //  var_dump($templateCompiler->template);
	         //echo "<pre>",print_r($templateCompiler->assignedValues,1),"</pre>";
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
