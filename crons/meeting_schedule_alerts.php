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
$last_day = $today - 86400;

$subject = "Meetings Schedule Update : SES Governance"; 


$query = mysql_query("SELECT email, name, id,other_email from users where active = 0");
while ($row = mysql_fetch_array($query)) {
	$meeting_date = $today+ $row["num_days"]*86400;
	$sql = "SELECT proxy_ad.id from user_voting_company inner join proxy_ad on user_voting_company.com_id = proxy_ad.com_id where user_voting_company.meeting_schedule = 1 and proxy_ad.add_date between $last_day and $today and user_voting_company.user_id='$row[id]'  ";
	$query_rep = mysql_query($sql);
	if(mysql_num_rows($query_rep)>0){
		$body = '<p>Following meetings have been added to SES Governance Portal: </p>';
		while ($row_rep = mysql_fetch_array($query_rep) ) {
			$body .= createmessage($row_rep["id"]);
		} 
		$body .= '<hr><i>This is an auto generated email. Please do not reply.</i>';
		$body = mysql_real_escape_string($body);
		mysql_query("INSERT into mail_queue (mailto, mailcc, mailbcc, mailbccmore, subject, content, at_folder, at_file) values ('$row[email]','$row[other_email]','','','$subject', '$body','','') ");
	}
}

function createmessage( $report_id){

			$query_rep = mysql_query("SELECT companies.com_name, proxy_ad.meeting_date, met_type.type from proxy_ad inner join companies on proxy_ad.com_id = companies.com_id join met_type on proxy_ad.meeting_type = met_type.id where proxy_ad.id='$report_id' limit 1");
			$row_rep = mysql_fetch_array($query_rep);
		
	$str = '<p>
				Company Name: <b>'.$row_rep["com_name"].'</b>&nbsp;/&nbsp;Meeting Type: <b>'.$row_rep["type"].'</b>&nbsp;/&nbsp;Meeting Date:<b>';
			$str .= ($row_rep["meeting_date"])?date("d-M-y",$row_rep["meeting_date"]):'N/A';
			$str .='</b>
			</p>
			
			';
			
			return $str;
}
?>


