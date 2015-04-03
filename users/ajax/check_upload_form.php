<?php session_start();
require_once('../../auth.php');
require_once('../../classes/GeneralVoting.php');

if(!isset($_POST["report_id"])) header("Location: ".STRSITE."access-denied.php");

$report_id = $_POST["report_id"];

$sql_met_date = mysql_query("SELECT meeting_date from proxy_ad where id='$report_id' limit 1");
$row = mysql_fetch_array($sql_met_date);
// check in ses voting

$check1 = new user_proxy_ad($report_id,$_SESSION["MEM_ID"],1);
$today = strtotime("today");

if($check1->check_form == 0 || $today > $row["meeting_date"]  ){
	echo 'success';
} else {
	echo 'fail';
}
?>