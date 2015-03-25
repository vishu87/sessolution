<?php session_start();
require_once('../../auth.php');

$report_id = $_POST["report_id"];

if($_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 0) header("Location: ".STRSITE."access-denied.php");
$timenow = strtotime("now");

$sql_check = mysql_query("SELECT id from proxies where proxy_id='$report_id' and user_id='$_SESSION[MEM_ID]' ");
$num = mysql_num_rows($sql_check);
if($num == 0){
	mysql_query("INSERT into proxies (proxy_id, user_id, add_date) values('$report_id','$_SESSION[MEM_ID]','$timenow') ");	
	mysql_query("INSERT into user_activity (user_id, activity_id,report_id, report_type) values ('$_SESSION[MEM_ID]','3','$report_id','1')");
	echo 'success';
} else {
	echo 'fail';
}
	

?>