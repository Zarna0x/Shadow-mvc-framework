<?php

namespace ShadowApp\Console\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Shadowapp\Sys\Db\Json\Table;
use Shadowapp\Sys\Db\Connection;

class DropTable extends Command 
{
  protected $db;

  public function __construct()
  {
    parent::__construct();

    $this->db = Connection::get();

  }
   
   public function configure()
   {
   	  $this->setName('drop_table')
		     ->setDescription('Drop specified table')
		     ->addArgument('tablename',InputArgument::REQUIRED);
   }	
 
   public function execute(InputInterface $inp, OutputInterface $out)
   {
       $tableName = trim($inp->getArgument('tablename'));  
       $this->checkIfTableExists($tableName,$out);

       $this->dropTable( $tableName, $out );
   }

   public function dropTable($tableName, OutputInterface $o)
   {
      $sql = "DROP TABLE ".$tableName;

      try {

        $this->db->query( $sql );
        echo "\n \033[35m Table ".$tableName." dropped succesfully... \033[0m   \n\n";

      } catch ( \Shadowapp\Sys\Exceptions\Db\WrongQueryException $e ) {
         $o->writeln(" \033[41m ".$e->getMessage()."\033[0m ");
         exit;   
      }
   }


   public function checkIfTableExists($tableName, OutputInterface $o)
     {
      // check if table exists 
      $query = "SHOW TABLES LIKE '".$tableName."'";

      try 
      {
        if ( $this->db->query($query)->rowCount() == 0) {
          $o->writeln(" \033[41m Table ".$tableName ." does not exsits.\033[0m ");
          exit;
        }
      } catch (\Shadowapp\Sys\Exceptions\Db\WrongQueryException $e) {
        $o->writeln(" \033[41m ".$e->getMessage()."\033[0m ");
        exit;        
      }
     }

     
   
}