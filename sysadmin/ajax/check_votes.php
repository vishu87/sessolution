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
require_once('../../classes/MemberClass.php');

if(!isset($_POST["id"])) header("Location: ".STRSITE."access-denied.php");

$report_id = $_POST["id"];
$pa_report = new PA_admin($report_id);

if($pa_report->vote_completed_on != 0 && $pa_report->vote_completed_on != '' ){
	echo 'success';
} else {
	echo 'fail';
}

?>