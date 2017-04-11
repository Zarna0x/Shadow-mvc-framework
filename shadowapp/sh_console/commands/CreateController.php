<?php

namespace ShadowApp\Console\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;


class CreateController extends Command
{
	public function configure ()
	{
		$this->setName('create_controller')
		     ->setDescription('Create new controller')
		     ->addArgument('name',InputArgument::REQUIRED);
	}

	public function execute (InputInterface $inp, OutputInterface $out)
	{
       
       $controllerName = ucfirst( (string) $inp->getArgument('name'))."Shadow.php";
       
       $this->checkIfControllerExists($controllerName,$out);
	   
	   $this->createController($controllerName,$out);

	   echo "\n \033[35m Controller Created Succesfully... \033[0m   \n\n";

	}
    

    /*
     * check if controller exists
     * @param1 string $controllerName
     * @param2 OutputInterface $o
     */

	private function checkIfControllerExists($controllerName,OutputInterface $o) 
	{
		$controller     = getcwd().'/shadowapp/sh_controllers/'.$controllerName;
        
        if(file_exists($controller)){
        	$o->writeln(" \033[41m Controller Allready Exists ! \033[0m \n");
        	exit(1);
        }
        
       

	}
     

     /*
     * Create New Controller
     * @param1 string $controllerName
     * @param2 OutputInterface $o
     */
	public function createController($controllerName,OutputInterface $out)
	{
        $controllerPath = getcwd().'/shadowapp/sh_controllers/'.$controllerName;
        
        $file = fopen($controllerPath, "w+");
        
        # get controller template path
        $templatePath = getcwd() . '/shadowapp/sh_console/templates/controller.template';
        
        #get template's content
        $templateCont = file_get_contents($templatePath);
        
        # get controller name correctly
        $cArr = explode('.', $controllerName);
        
        # replace with real name
        $sampleController = str_replace("__controllerName__", $cArr[0], $templateCont);
        
        fwrite($file, $sampleController);
        
        fclose($file); 
	}

	
}
?>