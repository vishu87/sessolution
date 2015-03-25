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

$query = "SELECT id from proxy_ad where meeting_date < '$meeting_date' ";

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
	$check_array = array();
	$sql_users = mysql_query("SELECT user_id from user_voting_proxy_reports where report_id='$report_id' ");
	while ($row_user = mysql_fetch_array($sql_users)) {
		//check for parent user
		$sql_parent = mysql_query("SELECT created_by_prim from users where id='$row_user[user_id]' limit 1 ");
		$row_parent = mysql_fetch_array($sql_parent);
		$parent_id = ($row_parent["created_by_prim"] == 0)?$row_user["user_id"]:$row_parent["created_by_prim"];
		if(!in_array($parent_id, $check_array)) array_push($check_array, $parent_id);
	}

	foreach($check_array as $user_id) {

		// check for freeze
		$check_freeze = mysql_query("SELECT final_freeze, final_unfreeze from user_admin_proxy_ad where user_id='$user_id' and report_id='$report_id' limit 1");

		if(mysql_num_rows($check_freeze) > 0){
			$row_freeze = mysql_fetch_array($check_freeze);

			if($row_freeze["final_freeze"] != 0 && $row_freeze["final_unfreeze"] == 0){
				$flag_freeze = 1;
			} else $flag_freeze = 0;

			$flag_new = 0;

		} else {
			$flag_freeze = 0;
			$flag_new = 1;
		}

		$details = '';

		if($flag_freeze == 0){

			foreach ($vote_ids as $id) {
				$insert_check = mysql_query("SELECT * from user_admin_voting where proxy_id= '$report_id' and user_id = '$user_id' and vote_id='$id' ");
		        if(mysql_num_rows($insert_check) > 0){

		        	$res = mysql_fetch_array($insert_check);
		        	$id_change = $res["id"];

		        	mysql_query("UPDATE user_admin_voting set vote='$abstain_vote_id', comment = '$abstain_vote_comment' , modified = '$timenow' where id = '$id_change' ");

		    	 	$details .= $res["vote_id"].'||'.$res["vote"].'||'.$res["comment"].'/';

		        } else {
		        	mysql_query("INSERT into user_admin_voting (user_id, vote_id, proxy_id, vote, comment, add_date) values ('$user_id','$id','$report_id','$abstain_vote_id','$abstain_vote_comment','$timenow') ");
		        }

		         mysql_query("INSERT into user_past_voting (user_id, report_id, vote_id, vote, comment, voting_type, freeze_date, add_date) values ('$user_id', '$report_id', '$id', '$abstain_vote_id','$abstain_vote_comment','2','$freeze_on', '".strtotime("now")."') ");

			}
			// insert modification into user_activity
			mysql_query("INSERT into user_activity (user_id, activity_id,report_id, report_type, voting_type, details) values ('$user_id','13','$report_id','1', '2','$details')");

			// now freeze that row and enter in user activity
			if($flag_new == 0){
				mysql_query("UPDATE user_admin_proxy_ad set final_freeze='$freeze_on', final_unfreeze='', auto_abstained='$freeze_on' where user_id='$user_id' and report_id='$report_id'  ");
			} else {
				mysql_query("INSERT into user_admin_proxy_ad (user_id, report_id,add_date, final_freeze,auto_abstained) values ('$user_id','$report_id','$freeze_on','$freeze_on','$freeze_on') ");
			}
			mysql_query("INSERT into user_activity (user_id, activity_id,report_id, report_type, details) values ('$user_id','9','$report_id','1','$freeze_on')");
		}
	}
}
?>