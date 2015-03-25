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

$id= $_POST["id"];
$report_id = $_POST["report_id"];
$report_type = $_POST["report_type"];

if(!isset($_POST["id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 2) header("Location: ".STRSITE."access-denied.php");


echo '<iframe src="'.STRSITE.'/sysanalyst/upload_report/index.php?id='.$id.'&rep_id='.$report_id.'&rep_type='.$report_type.'" style="border:0; width:100%"; height:70px;></iframe>';

?>