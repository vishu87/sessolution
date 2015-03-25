<?php session_start();
require_once('../../sysauth.php');
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

if(!isset($_POST["id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 1) header("Location: ".STRSITE."access-denied.php");

$report_id = $_POST["id"];
$resolution_number = mysql_real_escape_string($_POST["res_number"]);

$sql_check = mysql_query("SELECT id from voting where report_id='$report_id' and resolution_number='$resolution_number' ");
if(mysql_num_rows($sql_check) > 0) echo 'fail';
else echo 'success';

?>
