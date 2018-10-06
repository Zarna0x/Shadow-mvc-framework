<?php

namespace ShadowApp\Console\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Shadowapp\Sys\Db\Json\Table;
use Shadowapp\Sys\Db\Connection;

class CreateTable extends Command 
{
   
   public function configure()
   {
   	  $this->setName('migrate_table')
		     ->setDescription('Create new Table and migrate it, first you should create .json file in sh_db directory')
		     ->addArgument('tablename',InputArgument::REQUIRED);
   }	
 
   public function execute(InputInterface $inp, OutputInterface $out)
   {
       $tableName = $inp->getArgument('tablename');
   	   $jsonFileName = (string) trim($inp->getArgument('tablename')).'.json';
       // check if file exits
       $filePath = getcwd() . '/shadowapp/sh_db/' . $jsonFileName;

       if (false == file_exists($filePath)) {
          $out->writeln(" \033[41m Json file for Table ".$tableName." does not exists  \033[0m ");
          exit;   
       }
      
      $this->checkIfTableExists($tableName,$out);

      if ( (new Table)->execute($tableName) ) {
          echo "\n \033[35m Table ".$tableName."  created succesfully... \033[0m   \n\n";
      }   
   }
     public function checkIfTableExists($tableName, OutputInterface $o)
     {
      // check if table exists 
      $db = Connection::get();
      
      $query = "SHOW TABLES LIKE '".$tableName."'";

      try 
      {
        if ( $db->query($query)->rowCount() > 0) {
          $o->writeln(" \033[41m Table ".$tableName ." already exsits.\033[0m ");
          exit;
        }
      } catch (\Shadowapp\Sys\Exceptions\Db\WrongQueryException $e) {
        $o->writeln(" \033[41m ".$e->getMessage()."\033[0m ");
        exit;        
      }
     }

   
}