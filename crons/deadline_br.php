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
$upcoming = $today + 7*86400;

// Delete Skipped proxy reports 



$subject = "Deadline Breached / Upcoming Meetings"; 

$query = "SELECT analysts.name, analysts.email, analysts.an_id from analysts";
$sql = mysql_query($query);
$analysts = array();
	
while ($row = mysql_fetch_array($sql)) {
	$analysts[$row["an_id"]] = array($row["email"],$row["name"]); 
}

foreach ($analysts as $an_id => $value) {
	$str_deadline = '';
	$query_rep = mysql_query("SELECT report_analyst.deadline, report_analyst.rep_type, report_analyst.type, companies.com_name, proxy_ad.evoting_end, data_analyst.an_id as data_an, data_analyst.completed_on as data_comp, analysis_analyst.an_id as analysis_an, analysis_analyst.completed_on as analysis_comp, review_analyst.an_id as review_an, review_analyst.completed_on as review_comp from report_analyst left join report_analyst as data_analyst on report_analyst.report_id = data_analyst.report_id left join report_analyst as analysis_analyst on report_analyst.report_id = analysis_analyst.report_id left join report_analyst as review_analyst on report_analyst.report_id = review_analyst.report_id join proxy_ad on report_analyst.report_id = proxy_ad.id join companies on proxy_ad.com_id = companies.com_id where report_analyst.an_id = $an_id and data_analyst.type = 1 and analysis_analyst.type =2 and review_analyst.type = 3 and report_analyst.rep_type = 1 and report_analyst.deadline < $today and report_analyst.completed_on = '' and proxy_ad.skipped_on = 0 order by report_analyst.deadline desc limit 15 ");
	while ($row_rep = mysql_fetch_array($query_rep)) {
		$str_deadline .= '<p><b>Deadline Due Date</b>: ';
		$str_deadline .= ($row_rep["deadline"] != '')?date('d-M-y',$row_rep["deadline"]):'N/A';
		$str_deadline .= '&nbsp;&nbsp;&nbsp;<b>Report Type</b>: '.$report_types[$row_rep["rep_type"]].'&nbsp;&nbsp;&nbsp;<b>Task Type</b>: '.$task_types[$row_rep["type"]].'&nbsp;&nbsp;&nbsp;<b>Company Name</b>:'.$row_rep["com_name"].'&nbsp;&nbsp;&nbsp;<b>E-Voting Deadline</b>:';
		$str_deadline .= ($row_rep["evoting_end"] != '')?date('d-M-y',$row_rep["evoting_end"]):'N/A';
		$str_deadline .= '&nbsp;&nbsp;&nbsp;<b>Data</b>: '.$analysts[$row_rep["data_an"]][1].'';
		$str_deadline .= ($row_rep["data_comp"] != '')?'(Completed)':'(Pending)';
		$str_deadline .= '&nbsp;&nbsp;&nbsp;<b>Analysis</b>: '.$analysts[$row_rep["analysis_an"]][1].'';
		$str_deadline .= ($row_rep["analysis_comp"] != '')?'(Completed)':'(Pending)';
		$str_deadline .= '&nbsp;&nbsp;&nbsp;<b>Review</b>: '.$analysts[$row_rep["review_an"]][1].'';
		$str_deadline .= ($row_rep["review_comp"] != '')?'(Completed)':'(Pending)';
		$str_deadline .= '</p>';
	}

	$query_rep = mysql_query("SELECT report_analyst.deadline, report_analyst.rep_type, report_analyst.type, companies.com_name, data_analyst.an_id as data_an, data_analyst.completed_on as data_comp, analysis_analyst.an_id as analysis_an, analysis_analyst.completed_on as analysis_comp, review_analyst.an_id as review_an, review_analyst.completed_on as review_comp from report_analyst left join report_analyst as data_analyst on report_analyst.report_id = data_analyst.report_id left join report_analyst as analysis_analyst on report_analyst.report_id = analysis_analyst.report_id left join report_analyst as review_analyst on report_analyst.report_id = review_analyst.report_id join cgs on report_analyst.report_id = cgs.cgs_id join companies on cgs.com_id = companies.com_id where report_analyst.an_id = $an_id and data_analyst.type = 1 and analysis_analyst.type =2 and review_analyst.type = 3 and report_analyst.rep_type = 2 and report_analyst.deadline < $today and report_analyst.completed_on = '' order by report_analyst.deadline desc limit 15 ");
	while ($row_rep = mysql_fetch_array($query_rep)) {
		$str_deadline .= '<p><b>Deadline Due Date</b>: ';
		$str_deadline .= ($row_rep["deadline"] != '')?date('d-M-y',$row_rep["deadline"]):'N/A';
		$str_deadline .= '&nbsp;&nbsp;&nbsp;<b>Report Type</b>: '.$report_types[$row_rep["rep_type"]].'&nbsp;&nbsp;&nbsp;<b>Task Type</b>: '.$task_types[$row_rep["type"]].'&nbsp;&nbsp;&nbsp;<b>Company Name</b>:'.$row_rep["com_name"].'&nbsp;&nbsp;&nbsp;<b>E-Voting Deadline</b>:';
		$str_deadline .= 'N/A';
		$str_deadline .= '&nbsp;&nbsp;&nbsp;<b>Data</b>: '.$analysts[$row_rep["data_an"]][1].'';
		$str_deadline .= ($row_rep["data_comp"] != '')?'(Completed)':'(Pending)';
		$str_deadline .= '&nbsp;&nbsp;&nbsp;<b>Analysis</b>: '.$analysts[$row_rep["analysis_an"]][1].'';
		$str_deadline .= ($row_rep["analysis_comp"] != '')?'(Completed)':'(Pending)';
		$str_deadline .= '&nbsp;&nbsp;&nbsp;<b>Review</b>: '.$analysts[$row_rep["review_an"]][1].'';
		$str_deadline .= ($row_rep["review_comp"] != '')?'(Completed)':'(Pending)';
		$str_deadline .= '</p>';
	}

	$query_rep = mysql_query("SELECT report_analyst.deadline, report_analyst.rep_type, report_analyst.type, companies.com_name, data_analyst.an_id as data_an, data_analyst.completed_on as data_comp, analysis_analyst.an_id as analysis_an, analysis_analyst.completed_on as analysis_comp, review_analyst.an_id as review_an, review_analyst.completed_on as review_comp from report_analyst left join report_analyst as data_analyst on report_analyst.report_id = data_analyst.report_id left join report_analyst as analysis_analyst on report_analyst.report_id = analysis_analyst.report_id left join report_analyst as review_analyst on report_analyst.report_id = review_analyst.report_id join research on report_analyst.report_id = research.res_id join companies on research.com_id = companies.com_id where report_analyst.an_id = $an_id and data_analyst.type = 1 and analysis_analyst.type =2 and review_analyst.type = 3 and report_analyst.rep_type = 3 and report_analyst.deadline < $today and report_analyst.completed_on = '' order by report_analyst.deadline desc limit 15 ");
	while ($row_rep = mysql_fetch_array($query_rep)) {
		$str_deadline .= '<p><b>Deadline Due Date</b>: ';
		$str_deadline .= ($row_rep["deadline"] != '')?date('d-M-y',$row_rep["deadline"]):'N/A';
		$str_deadline .= '&nbsp;&nbsp;&nbsp;<b>Report Type</b>: '.$report_types[$row_rep["rep_type"]].'&nbsp;&nbsp;&nbsp;<b>Task Type</b>: '.$task_types[$row_rep["type"]].'&nbsp;&nbsp;&nbsp;<b>Company Name</b>:'.$row_rep["com_name"].'&nbsp;&nbsp;&nbsp;<b>E-Voting Deadline</b>:';
		$str_deadline .= 'N/A';
		$str_deadline .= '&nbsp;&nbsp;&nbsp;<b>Data</b>: '.$analysts[$row_rep["data_an"]][1].'';
		$str_deadline .= ($row_rep["data_comp"] != '')?'(Completed)':'(Pending)';
		$str_deadline .= '&nbsp;&nbsp;&nbsp;<b>Analysis</b>: '.$analysts[$row_rep["analysis_an"]][1].'';
		$str_deadline .= ($row_rep["analysis_comp"] != '')?'(Completed)':'(Pending)';
		$str_deadline .= '&nbsp;&nbsp;&nbsp;<b>Review</b>: '.$analysts[$row_rep["review_an"]][1].'';
		$str_deadline .= ($row_rep["review_comp"] != '')?'(Completed)':'(Pending)';
		$str_deadline .= '</p>';
	}

	$str_upcoming = '';
	$query_rep = mysql_query("SELECT report_analyst.deadline, report_analyst.rep_type, report_analyst.type, companies.com_name, proxy_ad.evoting_end, data_analyst.an_id as data_an, data_analyst.completed_on as data_comp, analysis_analyst.an_id as analysis_an, analysis_analyst.completed_on as analysis_comp, review_analyst.an_id as review_an, review_analyst.completed_on as review_comp from report_analyst left join report_analyst as data_analyst on report_analyst.report_id = data_analyst.report_id left join report_analyst as analysis_analyst on report_analyst.report_id = analysis_analyst.report_id left join report_analyst as review_analyst on report_analyst.report_id = review_analyst.report_id join proxy_ad on report_analyst.report_id = proxy_ad.id join companies on proxy_ad.com_id = companies.com_id where report_analyst.an_id = $an_id and data_analyst.type = 1 and analysis_analyst.type =2 and review_analyst.type = 3 and report_analyst.rep_type = 1 and report_analyst.deadline < $upcoming and report_analyst.deadline > $today and report_analyst.completed_on = '' and proxy_ad.skipped_on = 0 ");
	while ($row_rep = mysql_fetch_array($query_rep)) {
		$str_upcoming .= '<p><b>Deadline Due Date</b>: ';
		$str_upcoming .= ($row_rep["deadline"] != '')?date('d-M-y',$row_rep["deadline"]):'N/A';
		$str_upcoming .= '&nbsp;&nbsp;&nbsp;<b>Report Type</b>: '.$report_types[$row_rep["rep_type"]].'&nbsp;&nbsp;&nbsp;<b>Task Type</b>: '.$task_types[$row_rep["type"]].'&nbsp;&nbsp;&nbsp;<b>Company Name</b>:'.$row_rep["com_name"].'&nbsp;&nbsp;&nbsp;<b>E-Voting Deadline</b>:';
		$str_upcoming .= ($row_rep["evoting_end"] != '')?date('d-M-y',$row_rep["evoting_end"]):'N/A';
		$str_upcoming .= '&nbsp;&nbsp;&nbsp;<b>Data</b>: '.$analysts[$row_rep["data_an"]][1].'';
		$str_upcoming .= ($row_rep["data_comp"] != '')?'(Completed)':'(Pending)';
		$str_upcoming .= '&nbsp;&nbsp;&nbsp;<b>Analysis</b>: '.$analysts[$row_rep["analysis_an"]][1].'';
		$str_upcoming .= ($row_rep["analysis_comp"] != '')?'(Completed)':'(Pending)';
		$str_upcoming .= '&nbsp;&nbsp;&nbsp;<b>Review</b>: '.$analysts[$row_rep["review_an"]][1].'';
		$str_upcoming .= ($row_rep["review_comp"] != '')?'(Completed)':'(Pending)';
		$str_upcoming .= '</p>';
	}

	$query_rep = mysql_query("SELECT report_analyst.deadline, report_analyst.rep_type, report_analyst.type, companies.com_name, data_analyst.an_id as data_an, data_analyst.completed_on as data_comp, analysis_analyst.an_id as analysis_an, analysis_analyst.completed_on as analysis_comp, review_analyst.an_id as review_an, review_analyst.completed_on as review_comp from report_analyst left join report_analyst as data_analyst on report_analyst.report_id = data_analyst.report_id left join report_analyst as analysis_analyst on report_analyst.report_id = analysis_analyst.report_id left join report_analyst as review_analyst on report_analyst.report_id = review_analyst.report_id join cgs on report_analyst.report_id = cgs.cgs_id join companies on cgs.com_id = companies.com_id where report_analyst.an_id = $an_id and data_analyst.type = 1 and analysis_analyst.type =2 and review_analyst.type = 3 and report_analyst.rep_type = 2 and report_analyst.deadline < $upcoming and report_analyst.deadline > $today and report_analyst.completed_on = '' ");
	while ($row_rep = mysql_fetch_array($query_rep)) {
		$str_upcoming .= '<p><b>Deadline Due Date</b>: ';
		$str_upcoming .= ($row_rep["deadline"] != '')?date('d-M-y',$row_rep["deadline"]):'N/A';
		$str_upcoming .= '&nbsp;&nbsp;&nbsp;<b>Report Type</b>: '.$report_types[$row_rep["rep_type"]].'&nbsp;&nbsp;&nbsp;<b>Task Type</b>: '.$task_types[$row_rep["type"]].'&nbsp;&nbsp;&nbsp;<b>Company Name</b>:'.$row_rep["com_name"].'&nbsp;&nbsp;&nbsp;<b>E-Voting Deadline</b>:';
		$str_upcoming .= 'N/A';
		$str_upcoming .= '&nbsp;&nbsp;&nbsp;<b>Data</b>: '.$analysts[$row_rep["data_an"]][1].'';
		$str_upcoming .= ($row_rep["data_comp"] != '')?'(Completed)':'(Pending)';
		$str_upcoming .= '&nbsp;&nbsp;&nbsp;<b>Analysis</b>: '.$analysts[$row_rep["analysis_an"]][1].'';
		$str_upcoming .= ($row_rep["analysis_comp"] != '')?'(Completed)':'(Pending)';
		$str_upcoming .= '&nbsp;&nbsp;&nbsp;<b>Review</b>: '.$analysts[$row_rep["review_an"]][1].'';
		$str_upcoming .= ($row_rep["review_comp"] != '')?'(Completed)':'(Pending)';
		$str_upcoming .= '</p>';
	}

	$query_rep = mysql_query("SELECT report_analyst.deadline, report_analyst.rep_type, report_analyst.type, companies.com_name, data_analyst.an_id as data_an, data_analyst.completed_on as data_comp, analysis_analyst.an_id as analysis_an, analysis_analyst.completed_on as analysis_comp, review_analyst.an_id as review_an, review_analyst.completed_on as review_comp from report_analyst left join report_analyst as data_analyst on report_analyst.report_id = data_analyst.report_id left join report_analyst as analysis_analyst on report_analyst.report_id = analysis_analyst.report_id left join report_analyst as review_analyst on report_analyst.report_id = review_analyst.report_id join research on report_analyst.report_id = research.res_id join companies on research.com_id = companies.com_id where report_analyst.an_id = $an_id and data_analyst.type = 1 and analysis_analyst.type =2 and review_analyst.type = 3 and report_analyst.rep_type = 3 and report_analyst.deadline < $upcoming and report_analyst.deadline > $today and report_analyst.completed_on = '' ");
	while ($row_rep = mysql_fetch_array($query_rep)) {
		$str_upcoming .= '<p><b>Deadline Due Date</b>: ';
		$str_upcoming .= ($row_rep["deadline"] != '')?date('d-M-y',$row_rep["deadline"]):'N/A';
		$str_upcoming .= '&nbsp;&nbsp;&nbsp;<b>Report Type</b>: '.$report_types[$row_rep["rep_type"]].'&nbsp;&nbsp;&nbsp;<b>Task Type</b>: '.$task_types[$row_rep["type"]].'&nbsp;&nbsp;&nbsp;<b>Company Name</b>:'.$row_rep["com_name"].'&nbsp;&nbsp;&nbsp;<b>E-Voting Deadline</b>:';
		$str_upcoming .= 'N/A';
		$str_upcoming .= '&nbsp;&nbsp;&nbsp;<b>Data</b>: '.$analysts[$row_rep["data_an"]][1].'';
		$str_upcoming .= ($row_rep["data_comp"] != '')?'(Completed)':'(Pending)';
		$str_upcoming .= '&nbsp;&nbsp;&nbsp;<b>Analysis</b>: '.$analysts[$row_rep["analysis_an"]][1].'';
		$str_upcoming .= ($row_rep["analysis_comp"] != '')?'(Completed)':'(Pending)';
		$str_upcoming .= '&nbsp;&nbsp;&nbsp;<b>Review</b>: '.$analysts[$row_rep["review_an"]][1].'';
		$str_upcoming .= ($row_rep["review_comp"] != '')?'(Completed)':'(Pending)';
		$str_upcoming .= '</p>';
	}
	if($str_deadline != '' || $str_upcoming != ''){
		echo $value[0];
		$body = '<p>Dear Analyst,</p>';
		if($str_deadline != ''){
			$body .= 'Deadline Breached Tasks: ';
			$body .= $str_deadline;
			$body .= '<hr>';
		}
		if($str_upcoming != ''){
			$body .= 'Upcoming Tasks: ';
			$body .= $str_upcoming;
			$body .= '<hr>';
		}
		$body .= '<hr><i>This is an auto generated email. Please do not reply.</i>';
		$body = mysql_real_escape_string($body);
		mysql_query("INSERT into mail_queue (mailto, mailcc, mailbcc, mailbccmore, subject, content, at_folder, at_file) values ('$value[0]','".ADMIN_MAIL."','','','$subject', '$body','','') ");
	}
}
?>


