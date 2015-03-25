<?php 

define('ROOT_PATH',dirname(__FILE__).'/');

include(ROOT_PATH.'../config.php');

$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if(!$link) {
	die('Failed to connect to server: ' . mysql_error());
}
//Select database
$db = mysql_select_db(DB_DATABASE);
if(!$db) {
	die("Unable to select database");
}

$today = strtotime("today");

$meeting_date = $today - 86400;
$abstain_vote_id = 3;
$abstain_vote_comment = 'Abstained';
$freeze_on = strtotime("now");

$query = "SELECT id from proxy_ad where meeting_date = '$meeting_date' ";

$sql = mysql_query($query);

while ($row_proxy = mysql_fetch_array($sql)) {
	$report_id = $row_proxy["id"];

	// fetch votes for this meeting
	$vote_ids = array();
	$sql_votes = mysql_query("SELECT id from voting where report_id='$report_id' ");
	while ($row_votes = mysql_fetch_array($sql_votes)) {
		array_push($vote_ids, $row_votes["id"]);
	}

	// select users who has this report in their portfolio
	$sql_users = mysql_query("SELECT user_id from user_voting_proxy_reports where report_id='$report_id' ");
	while ($user = mysql_fetch_array($sql_users)) {
		// check for freeze
		$check_freeze = mysql_query("SELECT freeze_on, unfreeze_on from user_proxy_ad where user_id='$user[user_id]' and report_id='$report_id' and freeze_on != 0 order by id desc limit 1");
		if(mysql_num_rows($check_freeze) > 0){
			$row_freeze = mysql_fetch_array($check_freeze);
			$flag_new = 0;
			if($row_freeze["freeze_on"] != 0 && $row_freeze["unfreeze_on"] == 0){
				$flag_freeze = 1;
			} else $flag_freeze = 0;
		} else {
			$flag_freeze =0;
			$flag_new = 1;
		}
		$details = '';

		if($flag_freeze == 0){
			foreach ($vote_ids as $id) {
				$insert_check = mysql_query("SELECT * from user_voting where proxy_id= '$report_id' and user_id = '$user[user_id]' and vote_id='$id' ");
		        if(mysql_num_rows($insert_check) > 0){
		        	$res = mysql_fetch_array($insert_check);
		        	$id_change = $res["id"];
		        	mysql_query("UPDATE user_voting set vote='$abstain_vote_id', comment = '$abstain_vote_comment' , modified = '$timenow' where id = '$id_change' ");
		    	 	$details .= $res["vote_id"].'||'.$res["vote"].'||'.$res["comment"].'/';

		        } else {
		        	mysql_query("INSERT into user_voting (user_id, vote_id, proxy_id, vote, comment, add_date) values ('$user[user_id]','$id','$report_id','$abstain_vote_id','$abstain_vote_comment','$timenow') ");
		        }

		        mysql_query("INSERT into user_past_voting (user_id, report_id, vote_id, vote, comment, voting_type, freeze_date, add_date) values ('$user[user_id]', '$report_id', '$id', '$abstain_vote_id','$abstain_vote_comment','1','$freeze_on', '".strtotime("now")."') ");

			}

			// insert modification into user_activity
			mysql_query("INSERT into user_activity (user_id, activity_id,report_id, report_type, voting_type, details) values ('$user[user_id]','13','$report_id','1','1','$details')");

			// now freeze that row and enter in user activity
			$check = mysql_query("SELECT id from user_proxy_ad where user_id='$user[user_id]' and report_id='$report_id' limit 1");
			if(mysql_num_rows($check) > 0){
				$row = mysql_fetch_array($check);
				mysql_query("UPDATE user_proxy_ad set freeze_on='$freeze_on', unfreeze_on='',auto_abstained='$freeze_on' where id='$row[id]' ");
				mysql_query("INSERT into user_activity (user_id, activity_id,report_id, report_type, details) values ('$user[user_id]','7','$report_id','1','$freeze_on')");
			} else {
				mysql_query("INSERT into user_proxy_ad (user_id,report_id,freeze_on,auto_abstained,add_date) values ('$user[user_id]','$report_id','$freeze_on','$freeze_on','".strtotime("now")."') ");
				mysql_query("INSERT into user_activity (user_id, activity_id,report_id, report_type, details) values ('$user[user_id]','7','$report_id','1','$freeze_on')");
			}
		}
	}
}
?>