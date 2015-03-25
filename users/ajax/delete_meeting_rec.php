<?php session_start();
require_once('../../auth.php');

if(!isset($_POST["report_id"]) ) header("Location: ".STRSITE."access-denied.php");

$report_id = mysql_real_escape_string($_POST["report_id"]);
$user = mysql_real_escape_string($_POST["user_id"]);



	$check_user_flag = 0;

	if($user == $_SESSION["MEM_ID"]) $check_user_flag = 1;
	else {
		$ck = mysql_query("SELECT id from users where created_by_prim='$_SESSION[MEM_ID]' and id='$user' ");
		if (mysql_num_rows($ck) > 0) {
			$check_user_flag = 1;
		}
	}
	
	$sql_name = mysql_query("SELECT name from users where id='$user' ");
	$row_name = mysql_fetch_array($sql_name);

	if($check_user_flag == 1){
		if(mysql_query("DELETE from user_voting_proxy_reports where user_id='$user' and report_id='$report_id' ")){
			echo 'success';
			mysql_query("INSERT into user_activity (user_id, activity_id,report_id,report_type,details) values ('$_SESSION[MEM_ID]','33','$report_id','1','$user')" );
		} else {
			echo 'fail';
		}
	} else {
		echo 'fail';
	}


?>