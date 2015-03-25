<?php session_start();
require_once('../../subuserauth.php');

$days = mysql_real_escape_string($_POST["days"]);

$check1 =mysql_query("select id from meeting_alert where user_id = '$_SESSION[MEM_ID]' ");
if(mysql_num_rows($check1) >= 3) {
	echo 'fail1';
	exit();
}

$check = mysql_query("select id from meeting_alert where user_id = '$_SESSION[MEM_ID]' and num_days = '$days' ");
if(mysql_num_rows($check)>0){
	echo 'fail';
} else {
	mysql_query("INSERT into meeting_alert (user_id, num_days,add_Date) values ('$_SESSION[MEM_ID]','$days','".strtotime("now")."')");
	$insert_id =  mysql_insert_id();
	echo ' <button class="btn red" onclick="alert_remove('.$insert_id.')" id="btn_'.$insert_id.'">'.$days.' Days</button>';
}


?>
