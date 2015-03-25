<?php session_start();
require_once('../../auth.php');

require_once('../../classes/UserClass.php');

if(!isset($_POST["report_id"]) || !isset($_POST["deadline"])) header("Location: ".STRSITE."access-denied.php");

$report_id = $_POST["report_id"];
$deadline = strtotime($_POST["deadline"]);

$check = mysql_query("SELECT id from user_admin_proxy_ad where user_id='$_SESSION[MEM_ID]' and report_id='$report_id' limit 1");
if(mysql_num_rows($check) > 0){
	$row = mysql_fetch_array($check);
	if(mysql_query("UPDATE user_admin_proxy_ad set deadline='$deadline' where id='$row[id]' ")) echo 'success'; else echo 'fail';
	mysql_query("INSERT into user_activity (user_id, activity_id,report_id, report_type) values ('$_SESSION[MEM_ID]','25','$report_id','1')");
} else {
	if(mysql_query("INSERT into user_admin_proxy_ad (user_id,report_id,deadline,add_date) values ('$_SESSION[MEM_ID]','$report_id','$deadline','".strtotime("now")."') ")) echo 'success';
	else echo 'Error';
	mysql_query("INSERT into user_activity (user_id, activity_id,report_id, report_type) values ('$_SESSION[MEM_ID]','25','$report_id','1')");
}
?>