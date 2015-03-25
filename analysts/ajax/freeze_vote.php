<?php session_start();
require_once('../../subuserauth.php');
require_once('../../classes/UserClass.php');

if(!isset($_POST["id"])) header("Location: ".STRSITE."access-denied.php");

$report_id = $_POST["id"];
$freeze_on = strtotime("now");
$voting_type = 1;

$votes = mysql_query("SELECT id from voting where report_id = '$report_id' ");
while ($row_votes = mysql_fetch_array($votes)) {
	$query_vote = mysql_query("SELECT vote,comment from  user_voting where vote_id='$row_votes[id]' and user_id='$_SESSION[MEM_ID]' limit 1");
	if(mysql_num_rows($query_vote)>0){
		$row_vote = mysql_fetch_array($query_vote);
		$vote_fill = $row_vote["vote"];
		$comment_fill = $row_vote["comment"];
	} else {
		$vote_fill = 0;
		$comment_fill = 'N/A';
	}

	mysql_query("INSERT into user_past_voting (user_id, report_id, vote_id, vote, comment, voting_type, freeze_date, add_date) values ('$_SESSION[MEM_ID]', '$report_id', '$row_votes[id]', '$vote_fill','$comment_fill','$voting_type','$freeze_on', '".strtotime("now")."') ");
}


$check = mysql_query("SELECT id from user_proxy_ad where user_id='$_SESSION[MEM_ID]' and report_id='$report_id' limit 1");
if(mysql_num_rows($check) > 0){
	$row = mysql_fetch_array($check);
	if(mysql_query("UPDATE user_proxy_ad set freeze_on='$freeze_on', unfreeze_on='' where id='$row[id]' ")) echo 'Your votes has been freezed.'; else echo 'fail';
	mysql_query("INSERT into user_activity (user_id, activity_id,report_id, report_type,details) values ('$_SESSION[MEM_ID]','7','$report_id','1','$freeze_on')");
} else {
	if(mysql_query("INSERT into user_proxy_ad (user_id,report_id,freeze_on,add_date) values ('$_SESSION[MEM_ID]','$report_id','$freeze_on','".strtotime("now")."') ")) echo 'Your votes has been freezed.';
	else echo 'Error';
	mysql_query("INSERT into user_activity (user_id, activity_id,report_id, report_type,details) values ('$_SESSION[MEM_ID]','7','$report_id','1','$freeze_on')");
}

//sending mail to the user admin
$report_info_sql = mysql_query("SELECT companies.com_name, proxy_ad.meeting_type, proxy_ad.meeting_date from proxy_ad inner join companies on proxy_ad.com_id = companies.com_id where proxy_ad.id = '$report_id' limit 1 ");
$report_info = mysql_fetch_array($report_info_sql);

$user = new User($_SESSION["MEM_ID"]);
$email = $user->parent_email;
$subject = 'Votes Freezed: '.$user->name.' for '.$report_info["com_name"].' / '.$meeting_types[$report_info["meeting_type"]].' / '.date("d-M-y", $report_info["meeting_date"]);

	$body_in = '<p> Votes has been freezed for '.$report_info["com_name"].' / '.$meeting_types[$report_info["meeting_type"]].' / '.date("d-M-y", $report_info["meeting_date"]).' by <b>'.$user->name.'</b> <hr><i>This is an auto generated email. Please do not reply.</i>';

	$body = mysql_real_escape_string($body_in);
	mysql_query("INSERT into mail_queue (mailto, mailcc, mailbcc, mailbccmore, subject, content, at_folder, at_file) values ('$email','','','','$subject', '$body','','') ");
?>