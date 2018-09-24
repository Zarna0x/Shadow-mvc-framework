<?php

namespace ShadowApp\Console\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Shadowapp\Sys\Db\Connection;

class CreateMigration extends Command 
{

  protected $db;

  public function __construct()
  {
    parent::__construct();

    $this->db = Connection::get();
    
  }
   
   public function configure()
   {
   	  $this->setName('create_migration')
		     ->setDescription('Create new migration json file')
		     ->addArgument('tablename',InputArgument::REQUIRED);
   }	
 
   public function execute(InputInterface $inp, OutputInterface $out)
   {
       $tableName = $inp->getArgument('tablename');
   	   $jsonFileName = (string) trim($inp->getArgument('tablename')).'.json';
       // check if file exits
       $filePath = getcwd() . '/shadowapp/sh_db/' . $jsonFileName;

       if (file_exists($filePath)) {
          $out->writeln(" \033[41m Json file for Table ".$tableName." already exists  \033[0m ");
          exit;   
       }
      
      $this->checkIfTableExists($tableName,$out);

      $this->createJsonFile($filePath,$out);


      echo "\n \033[35m Migration file ". strtolower( $jsonFileName ) ." Created Succesfully... \033[0m   \n\n";

     
    }
     
    public function createJsonFile( $filePath, OutputInterface $o )
    {
        if ( false == is_writable( getcwd() . '/shadowapp/sh_db/'  ) ) {
           $out->writeln(" \033[41m Directory is not writable  \033[0m ");
           exit;
        }
         $file = fopen(strtolower( $filePath ), 'w');
         fclose($file);
         
     }
 
     public function checkIfTableExists($tableName, OutputInterface $o)
     {
      // check if table exists 
      
      $query = "SHOW TABLES LIKE '".$tableName."'";

      try 
      {
        if ( $this->db->query($query)->rowCount() > 0) {
          $o->writeln(" \033[41m Table ".$tableName ." already exsits.\033[0m ");
          exit;
        }
      } catch (\Shadowapp\Sys\Exceptions\Db\WrongQueryException $e) {
        $o->writeln(" \033[41m ".$e->getMessage()."\033[0m ");
        exit;        
      }
    }

   
}