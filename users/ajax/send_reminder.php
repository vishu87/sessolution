<?php session_start();
require_once('../../auth.php');
require_once('../../classes/UserClass.php');


$user_id = $_POST["user_id"];
$report_id = $_POST["report_id"];
$pa_report = new PA($report_id);

$subject = "Reminder for Vote Completion - ".$pa_report->company_name.' / '.$pa_report->meeting_type.' / '.$pa_report->meeting_date;

$user_det_sql = mysql_query("SELECT id, name, created_by_prim, email from users where id='$user_id' ");
$sub_user = mysql_fetch_array($user_det_sql);

if($sub_user["id"] == $_SESSION["MEM_ID"] || $sub_user["created_by_prim"] == $_SESSION["MEM_ID"] ){

	$user_mail_sql = mysql_query("SELECT name from users where id='$_SESSION[MEM_ID]' ");
	$main_user = mysql_fetch_array($user_mail_sql);

	$body_in = '<p>Dear <b>'.$sub_user["name"].'</b>,</p>';
	$body_in .= '<p>Please complete your votes for meeting - <b>'.$pa_report->company_name.'</b> / <b>'.$pa_report->meeting_type.'</b> / <b>'.$pa_report->meeting_date.'</b>.<br><br> Regards,<br> <b>'.$main_user["name"].'</b></p>';

	$body= mysql_real_escape_string($body_in);

	mysql_query("INSERT into mail_queue (mailto, mailcc, mailbcc, mailbccmore, subject, content, at_folder, at_file) values ('$sub_user[email]','','','','$subject', '$body','','') ");
	echo 'success';
} else {
	die('fail');
}
?>
