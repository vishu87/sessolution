<?php session_start();
require_once('../../subuserauth.php');
$table = 'user_voting_company';
$user_id = $_SESSION["MEM_ID"];
$ar_fields = array("meeting_alert","meeting_schedule","report_upload","notice","annual_report","meeting_outcome","meeting_minutes");
foreach ($_POST["com_id"] as $com_id) {
	$count = 0;
	$str = '';
	foreach ($ar_fields as $ar) {
		if($_POST[$ar.'_'.$com_id] == '1'){
			$str_up = $ar.' =  1';
		} else {
			$str_up = $ar.' =  0';
		}
		if($count != 0) $str .= ', '.$str_up;
		else $str .= $str_up;
		$count++;
	}
	$sql = "UPDATE $table set $str where user_id='$user_id' and com_id='$com_id' ";
	mysql_query($sql);
}
mysql_query("INSERT into user_activity (user_id, activity_id) values ('$user_id','31')" );
?>