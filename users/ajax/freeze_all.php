<?php session_start();
require_once('../../auth.php');
require_once('../../classes/UserClass.php');

if(!isset($_POST["report_id"])) header("Location: ".STRSITE."access-denied.php");

$report_id = $_POST["report_id"];
$final_freeze = strtotime("now");
$voting_type = 2;

$votes = mysql_query("SELECT id from voting where report_id = '$report_id' ");
while ($row_votes = mysql_fetch_array($votes)) {
	$query_vote = mysql_query("SELECT vote,comment from  user_admin_voting where vote_id='$row_votes[id]' and user_id='$_SESSION[MEM_ID]' limit 1");
	if(mysql_num_rows($query_vote)>0){
		$row_vote = mysql_fetch_array($query_vote);
		$vote_fill = $row_vote["vote"];
		$comment_fill = $row_vote["comment"];
	} else {
		$vote_fill = 0;
		$comment_fill = 'N/A';
	}

	mysql_query("INSERT into user_past_voting (user_id, report_id, vote_id, vote, comment, voting_type, freeze_date, add_date) values ('$_SESSION[MEM_ID]', '$report_id', '$row_votes[id]', '$vote_fill','$comment_fill','$voting_type','$final_freeze', '".strtotime("now")."') ");
}

$check = mysql_query("SELECT id from user_admin_proxy_ad where user_id='$_SESSION[MEM_ID]' and report_id='$report_id' limit 1");
if(mysql_num_rows($check) > 0){
	$row = mysql_fetch_array($check);
	if(mysql_query("UPDATE user_admin_proxy_ad set final_freeze='$final_freeze', final_unfreeze='' where id='$row[id]' ")) echo 'All votes have been freezed and locked.'; else echo 'fail';
	mysql_query("INSERT into user_activity (user_id, activity_id,report_id, report_type,details) values ('$_SESSION[MEM_ID]','9','$report_id','1','$final_freeze')");
} else {
	if(mysql_query("INSERT into user_admin_proxy_ad (user_id,report_id,final_freeze,add_date) values ('$_SESSION[MEM_ID]','$report_id','$final_freeze','".strtotime("now")."') ")) echo 'All votes have been freezed and locked.';
	else echo 'Error';
	mysql_query("INSERT into user_activity (user_id, activity_id,report_id, report_type,details) values ('$_SESSION[MEM_ID]','9','$report_id','1','$final_freeze')");
}

?>