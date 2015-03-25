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
//echo date("d-M-y",$today).'<br>';
$seven_day = $today+ 2*86400;
$eight_day = $today+ 3*86400;
//echo $seven_day.' '.date("d-M-y",$seven_day).'<br>';

$query = "SELECT analysts.name, analysts.email, report_analyst.* from report_analyst inner join analysts on report_analyst.an_id = analysts.an_id where deadline = '$seven_day'  and completed_on='' ";
//echo $query.'<br>';

$sql = mysql_query($query);

$subject = "Deadline Reminder: 2 Days"; 

while ($row = mysql_fetch_array($sql)) {
	$body = createmessage($row["rep_type"],$row["report_id"],$task_types[$row["type"]],$seven_day);	
	$body = mysql_real_escape_string($body);
	mysql_query("INSERT into mail_queue (mailto, mailcc, mailbcc, mailbccmore, subject, content, at_folder, at_file) values ('$row[email]','','','','$subject', '$body','','') ");
	mysql_query("INSERT into pending_email (an_id, report_analyst_id, num_days) values ('$row[an_id]','$row[id]','7') ");
	
}
function createmessage($rep_type, $report_id, $task_type,$seven_day){

	$analysts = array();
 $sql_an = mysql_query("SELECT an_id, name from analysts ");
 while ($row_an = mysql_fetch_array($sql_an)) {
   $analysts[$row_an["an_id"]] = $row_an["name"];
 }

	switch ($rep_type) {
		case 1:
			$query_rep = mysql_query("SELECT companies.com_name, proxy_ad.meeting_date, proxy_ad.meeting_type from proxy_ad inner join companies on proxy_ad.com_id = companies.com_id where id='$report_id' limit 1");
			$row_rep = mysql_fetch_array($query_rep);
			break;
		
		case 2:
			$query_rep = mysql_query("SELECT companies.com_name, cgs.publishing_date as meeting_date from cgs inner join companies on cgs.com_id = companies.com_id where cgs_id='$report_id' limit 1");
			$row_rep = mysql_fetch_array($query_rep);
			break;

		case 3:
			$query_rep = mysql_query("SELECT companies.com_name, research.publishing_date as meeting_date from research inner join companies on research.com_id = companies.com_id where res_id='$report_id' limit 1");
			$row_rep = mysql_fetch_array($query_rep);
			break;
	}
	$str = '<p>
				Your deadline is due on <b>'.date("d-M-y",$seven_day).'</b> for:
			</p>
			<p>
				Task Type: <b>'.$task_type.'</b>
			</p>
			<p>
				Company Name: <b>'.$row_rep["com_name"].'</b>
			</p>
			<p>
				Meeting Date:<b>';
			$str .= ($row_rep["meeting_date"])?date("d-M-y",$row_rep["meeting_date"]):'N/A';
			$str .='</b>
			</p>';

			$query_data = mysql_query("SELECT an_id, deadline, completed_on from report_analyst where report_id='$report_id' and rep_type='$rep_type' and type='1' ");
		$data = mysql_fetch_array($query_data);
		$str .= '<p><b>Data: </b>'.$analysts[$data["an_id"]].' / Status: ';
		$str .= ($data["completed_on"] != '')?'Completed</p>':'Pending</p>';
		
		$query_data = mysql_query("SELECT an_id, deadline, completed_on from report_analyst where report_id='$report_id' and rep_type='$rep_type' and type='2' ");
		$data = mysql_fetch_array($query_data);
		$str .= '<p><b>Analysis: </b>'.$analysts[$data["an_id"]].' / Status: ';
		$str .= ($data["completed_on"] != '')?'Completed</p>':'Pending</p>';

		$query_data = mysql_query("SELECT an_id, deadline, completed_on from report_analyst where report_id='$report_id' and rep_type='$rep_type' and type='3' ");
		$data = mysql_fetch_array($query_data);
		$str .= '<p><b>Review: </b>'.$analysts[$data["an_id"]].' / Status: ';
		$str .= ($data["completed_on"] != '')?'Completed</p>':'Pending</p>';


			$str .= '<hr>
			<i>This is an auto generated email. Please do not reply.</i>
			';

			return $str;
}
?>


