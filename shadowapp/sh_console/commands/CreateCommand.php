<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ShadowApp\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CreateCommand extends Command
{
    

    public function configure()
    {
        $this->setName('create_command')
                ->setDescription('Create new controller')
                ->addArgument('name', InputArgument::REQUIRED)
                ->addOption('withoutHandler', '-c', InputOption::VALUE_OPTIONAL, 'decides if handler will generated too', false);
                
    } 

    public function execute(InputInterface $inp, OutputInterface $out)
    {
        $commandToCreate = $inp->getArgument('name');
        
        $withoutHandler = $inp->getOption('withoutHandler');
        
        $commandPath = $this->checkOrGetIfCommandExists($commandToCreate,$out);
                
        if ($withoutHandler != 'true' ) {
            $commandHandlerPath = $this->checkOrGetIfCommandHandlerExists($commandToCreate,$out); 
            $this->create($commandHandlerPath,true);
        }
        
        
        $this->create($commandPath);
        
          echo "\n \033[35m Command generated Succesfully... \033[0m   \n\n";
        
    }
    
    private function create ( $path, $isHandler = false )
    {
         
        $file = fopen($path, "w");
        
       
        $preferedTemplate = (!$isHandler) ? getcwd() . '/shadowapp/sh_console/templates/command.template':
            getcwd() . '/shadowapp/sh_console/templates/commandHandler.template'; 
        
        $nameOfClass = $this->getClassNameFromPath($path);
        
        
        $templateCont = file_get_contents($preferedTemplate);
        
        
        # replace with real name
        $compiled = str_replace("__PLACEHOLDER__", $nameOfClass, $templateCont);
        
        
        
        fwrite($file, $compiled);
        
        fclose($file); 
    }
    
    private function checkOrGetIfCommandExists ( string $command, OutputInterface $out)
    {
        $commandFile = COMMANDS_DIR.$command.'.php';
         
        if (file_exists($commandFile)) {
            $out->writeln(" \033[41m Command ".$command." Allready Exists ! \033[0m \n");
            exit(1);
        }
        
        return $commandFile;
    }
    
    private function checkOrGetIfCommandHandlerExists ( string $command, OutputInterface $out)
    {
        $commandHandlerFile = COMMAND_HANDLER_DIR.$command.'Handler.php';
         
        if (file_exists($commandHandlerFile)) {
            $out->writeln(" \033[41m Command Handler ".$command."Handler Allready Exists ! \033[0m \n");
            exit(1);
        }
        
        return $commandHandlerFile;
    }
    
    private function getClassNameFromPath ( $path ) 
    {
        if (empty ($path)) {
            exit('Template path dont have to be empty');
        }
        
        $explodedPathEnd = explode('/', $path);
        $withTemp = explode('.',end($explodedPathEnd));
        
        return ucfirst(shcol('0',$withTemp));
        
        
    }

}
