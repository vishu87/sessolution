<?php session_start();
require_once('../../auth.php');

$user = $_SESSION["MEM_ID"];

$report_id = mysql_real_escape_string($_POST["report_id"]);
$com_id = mysql_real_escape_string($_POST["com_id"]);
$report_type = mysql_real_escape_string($_POST["report_type"]);
if($report_type != 3){
	$query_check = mysql_query("SELECT id from subscription_request where com_id='$com_id' and report_type='$report_type' and user_id='$_SESSION[MEM_ID]' and status='0' ");
}
else {
	$query_check = mysql_query("SELECT id from subscription_request where report_id='$report_id' and report_type='$report_type' and user_id='$_SESSION[MEM_ID]' and status='0' ");
}
if(mysql_num_rows($query_check) == 0){

	if(mysql_query("INSERT into subscription_request (report_id,com_id,report_type, user_id, add_date) values ('$report_id','$com_id','$report_type','$_SESSION[MEM_ID]','".strtotime("now")."' ) ")) {
		echo 'Subscription request has been sent to us. We will contact you soon.';
		mysql_query("INSERT into user_activity (user_id, activity_id,report_id, report_type) values ('$_SESSION[MEM_ID]','14','$report_id','$report_type')");
	}
	else echo 'Database Error. Please contact us';
} else {
	echo 'Subscription request for this company/report has been already sent to us.';
}



?>