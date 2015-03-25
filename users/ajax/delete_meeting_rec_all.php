<?php session_start();
require_once('../../auth.php');

if(!isset($_POST["report_id"]) ) header("Location: ".STRSITE."access-denied.php");

$report_id = mysql_real_escape_string($_POST["report_id"]);
$user = $_SESSION["MEM_ID"];

	$ck = mysql_query("SELECT users.id from users join user_voting_proxy_reports on users.id = user_voting_proxy_reports.user_id where (users.created_by_prim='$_SESSION[MEM_ID]' OR users.id='$_SESSION[MEM_ID]' ) and user_voting_proxy_reports.report_id = '$report_id' ");
	$flag = 1;
  while ($row = mysql_fetch_array($ck)) {
		if(mysql_query("DELETE from user_voting_proxy_reports where user_id='$row[id]' and report_id='$report_id' ")){
			$flag = 1;
			mysql_query("INSERT into user_activity (user_id, activity_id,report_id,report_type,details) values ('$_SESSION[MEM_ID]','33','$report_id','1','$row[id]')" );
		} else {
			$flag = 0;
			die('fail');
		}
}

if($flag == 1) echo 'success';


?>