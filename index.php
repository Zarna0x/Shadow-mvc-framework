<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
/*
* Shadowphp framework
* @link https://github.com/Zarna0x/Shadow-mvc-framework
* @Author Zarna0x
*/

include_once 'stack.php';
##########################

$table = new Shadowapp\Sys\Db\Json\Table;
parr($table->getQueryString('articles'));
var_dump($table->execute('articles'));
?>
