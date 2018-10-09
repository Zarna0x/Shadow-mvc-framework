<?php

namespace ShadowApp\Console\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Shadowapp\Sys\Db\Query\Builder as db;
use Shadowapp\Sys\Db\Connection;

class CreateModel extends Command 
{
   
   public function configure()
   {
   	  $this->setName('create_model')
		     ->setDescription('Create new Model')
		     ->addArgument('tablename',InputArgument::REQUIRED);
   }	

   public function execute(InputInterface $inp, OutputInterface $out)
   {
   	 $modelName = ucfirst( (string) $inp->getArgument('tablename'))."Shadow.php";
   	
   	 $this->checkIfTableExists($inp->getArgument('tablename'),$out);

   	 $this->checkIfModelExists($modelName,$out);

   	 $this->createModel($modelName,$out);

   echo "\n \033[35m Model ". $modelName ." Created Succesfully... \033[0m   \n\n";
   }

   public function createModel($modelName,OutputInterface $out)
  {
        $Path = getcwd().'/shadowapp/Models/'.$modelName;
        
        $file = fopen($Path, "w+");
        
        # get controller template path
        $templatePath = getcwd() . '/shadowapp/sh_console/templates/model.template';
        
        #get template's content
        $templateCont = file_get_contents($templatePath);
        
        # get controller name correctly
        $modelArr = explode('.', $modelName);
        

        # replace with real name
        $sampleModel = str_replace("__modelName__", $modelArr[0], $templateCont);
        
        fwrite($file, $sampleModel);
        
        fclose($file); 
  }

   
   public function checkIfTableExists($tableName, OutputInterface $o)
     {
     $db = Connection::get();
       
      // check if table exists 
      $query = "SHOW TABLES LIKE '".$tableName."'";

      try 
      {
        if ( $db->query($query)->rowCount() == 0) {
          $o->writeln(" \033[41m Table ".$tableName ." does not exsits.\033[0m ");
          exit;
        }
      } catch (\Shadowapp\Sys\Exceptions\Db\WrongQueryException $e) {
        $o->writeln(" \033[41m ".$e->getMessage()."\033[0m ");
        exit;        
      }
     }

   public function checkIfModelExists($modelName, OutputInterface $o)
   {
     $model = getcwd().'/shadowapp/Models/'.$modelName;
     
     if (file_exists($model)) {
        $o->writeln(" \033[41m Model Allready Exists ! \033[0m ");
        exit(1);
     }

   }
}