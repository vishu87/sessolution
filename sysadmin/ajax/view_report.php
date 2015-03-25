<?php session_start();
require_once('../../sysauth.php');
require_once('../../config.php');
$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if(!$link) {
	die('Failed to connect to server: ' . mysql_error());
}
//Select database
$db = mysql_select_db(DB_DATABASE);
if(!$db) {
	die("Unable to select database");
}
if(!isset($_POST["report_id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 1) header("Location: ".STRSITE."access-denied.php");
$report_id = $_POST["report_id"];
$report_type = $_POST["report_type"];

$analysts = array();
 $sql_an = mysql_query("SELECT an_id, name from analysts ");
 while ($row_an = mysql_fetch_array($sql_an)) {
   $analysts[$row_an["an_id"]] = $row_an["name"];
 }


  
  $task_type=array("","Data","Analysis","Review");


$query_sent = mysql_query("SELECT * from companies where com_id='$_POST[id]' LIMIT 1");
$ar_fields_all = array("com_name","com_bse_code","com_bse_srcip","com_nse_sym","com_reuters","com_bloomberg","com_isin","add_date");
$ar_fields_name = array("Company Name","BSE Code","BSE Srcip","NSE Symbol","Rueters","Bloomberg","ISIN","Added on");




$update = array();
echo '<table class="table table-bordered table-hover">';
switch ($report_type) {
	case '1':
		$ar_fields_name = array("Report Type","Meeting Date","Meeting Type","Report","Notice","Proxy Slip","Teasor","Annual Report","Meeting Outcome","Meeting Minutes","Meeting Time","Meeting Venue","Data","Deadline/Completed On","Analysis","Deadline/Completed On","Review","Deadline/Completed On");
		$ar_fields_type = array("ReportType","MeetingDate","MeetingType","Report","Notice","ProxySlip","Teasor","AnnualReport","MeetingOutcome","MeetingMinutes","MeetingTime","MeetingVenue","Data","Deadline1","Analysis","Deadline2","Review","Deadline3");
		$query = mysql_query("SELECT * from proxy_ad where id='$report_id' ");
		$row = mysql_fetch_array($query);
		$update["ReportType"] =  $report_types[1];
		$update["MeetingDate"] = ($row["meeting_date"] != '')? date("d M y", $row["meeting_date"]):'Not Set';
		$update["MeetingType"] = $meeting_types[$row["meeting_type"]];
		$update["Report"] = ($row["report"] !='')?'<a href="../proxy_reports/'.$row["report"].'" target="_blank">View</a>':'';
		$update["ProxySlip"] = ($row["proxy_slip"] !='')?'<a href="../proxy_slips/'.$row["proxy_slip"].'" target="_blank">View</a>':'';
		$update["Notice"] = ($row["notice"] != '')?'<a href="../proxy_notices/'.$row["notice"].'" target="_blank">View</a><br>'.$row["notice_link"]:$row["notice_link"];
		$update["Teasor"] = $row["teasor"];
		$update["AnnualReport"] = $row["annual_report"];
		$update["MeetingOutcome"] = $row["meeting_outcome"];
		$update["MeetingMinutes"] = $row["meeting_minutes"];
		$update["MeetingTime"] = $row["meeting_time"];
		$update["MeetingVenue"] = $row["meeting_venue"];

		$query_data = mysql_query("SELECT an_id, deadline, completed_on from report_analyst where report_id='$report_id' and rep_type='$report_type' and type='1' ");
		$data = mysql_fetch_array($query_data);
		$update["Data"] = $analysts[$data["an_id"]];
		$update["Deadline1"] = ($data["deadline"] != '')?date("d-M-Y", $data["deadline"]):'';
		$update["Deadline1"] .= ' / ';
		$update["Deadline1"] .= ($data["completed_on"] != '')?date("d-M-Y", $data["completed_on"]):'';
		
		$query_data = mysql_query("SELECT an_id, deadline, completed_on from report_analyst where report_id='$report_id' and rep_type='$report_type' and type='2' ");
		$data = mysql_fetch_array($query_data);
		$update["Analysis"] = $analysts[$data["an_id"]];
		$update["Deadline2"] = ($data["deadline"] != '')?date("d-M-Y", $data["deadline"]):'';
		$update["Deadline2"] .= ' / ';
		$update["Deadline2"] .= ($data["completed_on"] != '')?date("d-M-Y", $data["completed_on"]):'';

		$query_data = mysql_query("SELECT an_id, deadline, completed_on from report_analyst where report_id='$report_id' and rep_type='$report_type' and type='3' ");
		$data = mysql_fetch_array($query_data);
		$update["Review"] = $analysts[$data["an_id"]];
		$update["Deadline3"] = ($data["deadline"] != '')?date("d-M-Y", $data["deadline"]):'';
		$update["Deadline3"] .= ' / ';
		$update["Deadline3"] .= ($data["completed_on"] != '')?date("d-M-Y", $data["completed_on"]):'';
		break;

	case '2':
		$ar_fields_name = array("Report Type","Publishing Date","Report","Governance Index","India Mandatory","Board of Directors","Directors Remuneration","Stakeholder Engagement","Financial Reporting","Sustainability","Data","Deadline/Completed On","Analysis","Deadline/Completed On","Review","Deadline/Completed On");
		$ar_fields_type = array("ReportType","MeetingDate","Report", "GovernmentIndex","IndiaMandatory","BoardofDirectors","DirectorsRemuneration","StakeholderEngagement","FinancialReporting","Sustainability","Data", "Deadline1","Analysis","Deadline2","Review","Deadline3");
		$query = mysql_query("SELECT * from cgs where cgs_id='$report_id' ");
		$row = mysql_fetch_array($query);
		$update["ReportType"] =  $report_types[2];
		$update["MeetingDate"] = ($row["publishing_date"] != '')? date("d M y", $row["publishing_date"]):'Not Set';
		$update["Report"] = ($row["report_upload"] !='')?'<a href="../cgs/'.$row["report_upload"].'" target="_blank">View</a>':'';
		$update["GovernmentIndex"] = $row["govt_index"];
		$update["IndiaMandatory"] = $row["india_man"];
		$update["GovernanceIndexScore"] = $row["govt_index_score"];
		$update["BoardofDirectors"] = $row["board_dir"];
		$update["DirectorsRemuneration"] = $row["dir_rem"];
		$update["StakeholderEngagement"] = $row["stake_eng"];
		$update["FinancialReporting"] = $row["fin_rep"];
		$update["Sustainability"] = $row["sustain"];
		$update["ComplianceScore"] = $row["comp_score"];

		$query_data = mysql_query("SELECT an_id, deadline, completed_on from report_analyst where report_id='$report_id' and rep_type='$report_type' and type='1' ");
		$data = mysql_fetch_array($query_data);
		$update["Data"] = $analysts[$data["an_id"]];
		$update["Deadline1"] = ($data["deadline"] != '')?date("d-M-Y", $data["deadline"]):'';
		$update["Deadline1"] .= ' / ';
		$update["Deadline1"] .= ($data["completed_on"] != '')?date("d-M-Y", $data["completed_on"]):'';
		
		$query_data = mysql_query("SELECT an_id, deadline, completed_on from report_analyst where report_id='$report_id' and rep_type='$report_type' and type='2' ");
		$data = mysql_fetch_array($query_data);
		$update["Analysis"] = $analysts[$data["an_id"]];
		$update["Deadline2"] = ($data["deadline"] != '')?date("d-M-Y", $data["deadline"]):'';
		$update["Deadline2"] .= ' / ';
		$update["Deadline2"] .= ($data["completed_on"] != '')?date("d-M-Y", $data["completed_on"]):'';

		$query_data = mysql_query("SELECT an_id, deadline, completed_on from report_analyst where report_id='$report_id' and rep_type='$report_type' and type='3' ");
		$data = mysql_fetch_array($query_data);
		$update["Review"] = $analysts[$data["an_id"]];
		$update["Deadline3"] = ($data["deadline"] != '')?date("d-M-Y", $data["deadline"]):'';
		$update["Deadline3"] .= ' / ';
		$update["Deadline3"] .= ($data["completed_on"] != '')?date("d-M-Y", $data["completed_on"]):'';
		break;

	case '3':
		$ar_fields_name = array("Report Type","Publishing Date","Report","Heading","Description","Data","Deadline/Completed On","Analysis","Deadline/Completed On","Review","Deadline/Completed On");
		$ar_fields_type = array("ReportType","MeetingDate","Report", "Heading","Description", "Data", "Deadline1","Analysis","Deadline2","Review","Deadline3");
		$query = mysql_query("SELECT * from research where res_id='$report_id' ");
		$row = mysql_fetch_array($query);
		$update["ReportType"] =  $report_types[3];
		$update["MeetingDate"] = ($row["publishing_date"] != '')? date("d M y", $row["publishing_date"]):'Not Set';
		$update["Report"] = ($row["report_upload"] !='')?'<a href="../research/'.$row["report_upload"].'" target="_blank">View</a>':'';
		$update["Heading"] = stripcslashes($row["heading"]);
		$update["Description"] = stripcslashes($row["description"]);

		$query_data = mysql_query("SELECT an_id, deadline, completed_on from report_analyst where report_id='$report_id' and rep_type='$report_type' and type='1' ");
		$data = mysql_fetch_array($query_data);
		$update["Data"] = $analysts[$data["an_id"]];
		$update["Deadline1"] = ($data["deadline"] != '')?date("d-M-Y", $data["deadline"]):'';
		$update["Deadline1"] .= ' / ';
		$update["Deadline1"] .= ($data["completed_on"] != '')?date("d-M-Y", $data["completed_on"]):'';
		
		$query_data = mysql_query("SELECT an_id, deadline, completed_on from report_analyst where report_id='$report_id' and rep_type='$report_type' and type='2' ");
		$data = mysql_fetch_array($query_data);
		$update["Analysis"] = $analysts[$data["an_id"]];
		$update["Deadline2"] = ($data["deadline"] != '')?date("d-M-Y", $data["deadline"]):'';
		$update["Deadline2"] .= ' / ';
		$update["Deadline2"] .= ($data["completed_on"] != '')?date("d-M-Y", $data["completed_on"]):'';

		$query_data = mysql_query("SELECT an_id, deadline, completed_on from report_analyst where report_id='$report_id' and rep_type='$report_type' and type='3' ");
		$data = mysql_fetch_array($query_data);
		$update["Review"] = $analysts[$data["an_id"]];
		$update["Deadline3"] = ($data["deadline"] != '')?date("d-M-Y", $data["deadline"]):'';
		$update["Deadline3"] .= ' / ';
		$update["Deadline3"] .= ($data["completed_on"] != '')?date("d-M-Y", $data["completed_on"]):'';
		break;
}

	$count = 0;
	
	foreach ($ar_fields_type as $ar) {
			echo "<tr><td>".$ar_fields_name[$count]."</td><td>".$update[$ar]."</td></tr>";
		$count++;
	}

echo '</table>';

?>