<?php session_start();
require_once('../../sysan.php');
require_once('../../config.php');
$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if(!$link) {
	die('Failed to connect to server: ' . mysql_error());
}
//Select database
$db = mysql_select_db(DB_DATABASE);
if(!$db) {
	die("Unable to select database");
}

$report_id = $_POST["id"];


mysql_query("UPDATE report_analyst set completed_on='' where id='$report_id' and an_id='$_SESSION[MEM_ID]' ");

echo 'success';

?>