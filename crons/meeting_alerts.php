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

	$subject = "Upcoming Meeting Alerts"; 


$query = mysql_query("SELECT meeting_alert.*, users.email, users.name, users.other_email from meeting_alert inner join users on meeting_alert.user_id= users.id and users.active = 0 ");
while ($row = mysql_fetch_array($query)) {
	$meeting_date = $today+ $row["num_days"]*86400;

	$array_reports = array();

	$query_rep1 = mysql_query("SELECT proxy_ad.id from  user_voting_company inner join proxy_ad on user_voting_company.com_id = proxy_ad.com_id where user_voting_company.meeting_alert = 1 and proxy_ad.meeting_date = '$meeting_date' and (proxy_ad.evoting_end = '' || proxy_ad.evoting_end = 0) and user_voting_company.user_id='$row[user_id]' ");
	while ($row1 = mysql_fetch_array($query_rep1)) {
		if(!in_array($row1["id"], $array_reports)) array_push($array_reports, $row1["id"]);
	}

	$query_rep2 = mysql_query("SELECT proxy_ad.id from  user_voting_company inner join proxy_ad on user_voting_company.com_id = proxy_ad.com_id where user_voting_company.meeting_alert = 1 and proxy_ad.evoting_end = '$meeting_date' and user_voting_company.user_id='$row[user_id]' ");
	while ($row2 = mysql_fetch_array($query_rep2)) {
		if(!in_array($row2["id"], $array_reports)) array_push($array_reports, $row2["id"]);
	}


	foreach($array_reports as $report_id){
		$body = createmessage($report_id);
		$body = mysql_real_escape_string($body);
		mysql_query("INSERT into mail_queue (mailto, mailcc, mailbcc, mailbccmore, subject, content, at_folder, at_file) values ('$row[email]','$row[other_email]','','','$subject', '$body','','') ");
	}
}

function createmessage( $report_id){
			$query_rep = mysql_query("SELECT companies.com_name, proxy_ad.meeting_date, met_type.type from proxy_ad inner join companies on proxy_ad.com_id = companies.com_id join met_type on proxy_ad.meeting_type = met_type.id where proxy_ad.id='$report_id' limit 1");
			$row_rep = mysql_fetch_array($query_rep);
		
	$str = '<p>
				This is an alert for upcoming meeting:
			</p>
			<p>
				Meeting Type: <b>'.$row_rep["type"].'</b>
			</p>
			<p>
				Company Name: <b>'.$row_rep["com_name"].'</b>
			</p>
			<p>
				Meeting Date:<b>';
			$str .= ($row_rep["meeting_date"])?date("d-M-y",$row_rep["meeting_date"]):'N/A';
			$str .='</b>
			</p><hr>
			<i>This is an auto generated email. Please do not reply.</i>
			';
			
			return $str;
}
?>


