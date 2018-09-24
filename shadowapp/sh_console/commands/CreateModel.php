<?php

namespace ShadowApp\Console\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Shadowapp\Sys\Db\Query\Builder as db;


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

   	 #$this->createModel($modelName);
   }

   public function checkIfTableExists($tableName, OutputInterface $o)
   {
      // check if table exists 
      $db = new db;

      
     try
	 {
        $db->limit(1)->get($tableName);
     }
	catch(\Shadowapp\Sys\Exceptions\Db\WrongQueryException $e)
	{
         $o->writeln(" \033[41m  Table ".$tableName." does not exists  \033[0m ");       
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