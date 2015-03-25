<?php session_start();
require_once('../../subuserauth.php');

require_once('../../classes/UserClass.php');

$subject = "Agenda Items Request";

$user = $_SESSION["MEM_ID"];
$report_id = $_POST["report_id"];

if(mysql_query("INSERT into vote_request (user_id, report_id) values ('$user', '$report_id') ")){
	echo 'success';
}

$pa_report = new PA($report_id);

$user_det_sql = mysql_query("SELECT name, created_by_prim, email from users where id='$user' ");
$sub_user = mysql_fetch_array($user_det_sql);

if($sub_user["created_by_prim"] != 0){
	$user_mail_sql = mysql_query("SELECT name from users where id='$sub_user[created_by_prim]' ");
	$main_user = mysql_fetch_array($user_mail_sql);
}

$main_user = ($sub_user["created_by_prim"] == 0)?'Self':$main_user["name"];


$body_in = '<p> Agenda items request has been made for <b>'.$pa_report->company_name.'</b> / <b>'.$pa_report->meeting_type.'</b> / <b>'.$pa_report->meeting_date.'</b> by User <b>'.$sub_user["name"].'('.$sub_user["email"].', Main User '.$main_user.')</b> </p>';

$body = mysql_real_escape_string($body_in);
mysql_query("INSERT into mail_queue (mailto, mailcc, mailbcc, mailbccmore, subject, content, at_folder, at_file) values ('admin@sesgovernance.com','','','','$subject', '$body','','') ");
?>
