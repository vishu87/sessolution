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

$subject = "SES Portal: Upcoming Meeting Schedule for the next three weeks"; 


$query = mysql_query("SELECT distinct(user_voting_company.user_id), users.email, users.name, users.other_email from user_voting_company inner join users on user_voting_company.user_id= users.id and users.active = 0 ");
while ($row = mysql_fetch_array($query)) {
		mysql_query("INSERT into upcoming_mail (user_id) values ($row[user_id]) ");
		$insert_id = mysql_insert_id();
		$body = '<p>Dear User,</p>
			<p><a href="'.STRSITE.'preview/upcoming_report.php?vle='.encrypt($insert_id).'&uvle='.encrypt($row["user_id"]).'">Click here</a> to download excel file for the upcoming meeting schedule for the next three weeks for your portfolio companies.
			</p><hr>
			<i>This is an auto generated email. Please do not reply.</i>
		';
		echo $body;

		$body = mysql_real_escape_string($body);
		mysql_query("INSERT into mail_queue (mailto, mailcc, mailbcc, mailbccmore, subject, content, at_folder, at_file) values ('$row[email]','$row[other_email]','','','$subject', '$body','','') ");
}

?>

