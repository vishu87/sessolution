<?php session_start();
require_once('../../auth.php');

if(!isset($_POST["id"])) header("Location: ".STRSITE."access-denied.php");

$report_id = $_POST["id"];
$query = mysql_query("SELECT id from user_proxy_ad where user_id='$_SESSION[MEM_ID]' and report_id = '$report_id' and freeze_on != 0 order by id desc limit 1 ");
$res = mysql_fetch_array($query);
$timenow = strtotime("now");
if(mysql_query("UPDATE user_proxy_ad set unfreeze_on='$timenow' where user_id='$_SESSION[MEM_ID]' and report_id = '$report_id' and id='$res[id]' ")) echo 'Your Votes have been un-freezed.';
else echo 'Error';
mysql_query("INSERT into user_activity (user_id, activity_id,report_id, report_type) values ('$_SESSION[MEM_ID]','8','$report_id','1')");
?>