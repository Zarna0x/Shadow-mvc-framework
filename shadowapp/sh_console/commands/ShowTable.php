<?php

namespace ShadowApp\Console\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Shadowapp\Sys\Db\Json\Table;
use Shadowapp\Sys\Db\Connection;

class ShowTable extends Command 
{
  protected $db;

  public function __construct()
  {
    parent::__construct();

    $this->db = Connection::get();

  }
   
   public function configure()
   {
   	  $this->setName('show_tables')
		     ->setDescription('Show all tables in the database');
	 }	
 
   public function execute(InputInterface $inp, OutputInterface $out)
   {
     try {

       $tables = $this->db->query( "SHOW TABLES" )->fetchAll(\PDO::FETCH_COLUMN);;
       if ( empty($tables) ) {
         $out->writeln(" \033[41m There is no table in the Database  \033[0m ");
         exit;
       }

       array_walk($tables, function ( $tablename , $index) use ( $out ) {
         
         $correctIndex = $index+1;
         echo "\n \033[35m ". $correctIndex ." => ". $tablename ." \033[0m   \n\n";
        
       });


     } catch (\Shadowapp\Sys\Exceptions\Db\WrongQueryException $e ) {

      $out->writeln(" \033[41m ".$e->getMessage()."\033[0m ");
      exit;
     }
   }


     
   
}