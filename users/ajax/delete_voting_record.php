<?php session_start();
require_once('../../auth.php');
require_once('../../classes/UserClass.php');

if(!isset($_POST["id"]) ) header("Location: ".STRSITE."access-denied.php");

$user = new User($_SESSION["MEM_ID"]);
$report_id = mysql_real_escape_string($_POST["id"]);

if(mysql_query("DELETE from user_voting_proxy_reports where user_id='$_SESSION[MEM_ID]' and report_id='$report_id' ")){
	echo 'success';
} else {
	echo 'fail';
}




?>