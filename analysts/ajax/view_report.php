<?php session_start();
require_once('../../subuserauth.php');
require_once('../../classes/UserClass.php');


$user = new User($_SESSION["MEM_ID"]);
$report_id = $_POST["report_id"];
$report_type = $_POST["report_type"];

$query_sent = mysql_query("SELECT * from companies where com_id='$_POST[id]' LIMIT 1");
$ar_fields_all = array("com_name","com_bse_code","com_bse_srcip","com_nse_sym","com_reuters","com_bloomberg","com_isin","add_date");
$ar_fields_name = array("Company Name","BSE Code","BSE Srcip","NSE Symbol","Rueters","Bloomberg","ISIN","Added on");


if(!isset($_POST["report_id"])) header("Location: ".STRSITE."access-denied.php");

$update = array();

echo '<table class="table table-bordered table-hover">';
switch ($report_type) {
	case '1':
		$ar_fields_name = array("Report Type","Meeting Date","Record Date","e-Voting Start Date","e-Voting Deadline","Evoting Platform","Vote Record Date","Meeting Type","Report","Notice","Proxy Slips","Attendance Slip","Annual Report","Meeting Outcome","Voting Results (Clause 35A)","Meeting Minutes");
		$ar_fields_type = array("ReportType","MeetingDate","RecordDate","EvotingStart","EvotingEnd","EvotingPlatform","VoteRecordDate","MeetingType","Report","Notice","ProxySlips","AttendanceSlips","AnnualReport","MeetingOutcome","VotingResults","MeetingMinutes");
		$pa_view = new PA($report_id);
		
		$user->pa_subscribed_comapnies_year($user->parent,$pa_view->year);


		$update["ReportType"] =  $report_types[1];
		$update["VoteRecordDate"] = $pa_view->record_date;
		$update["MeetingDate"] = $pa_view->meeting_date;
		$update["RecordDate"] = $pa_view->record_date;
		$update["EvotingStart"] = $pa_view->evoting_start;
		$update["EvotingEnd"] = $pa_view->evoting_end;
				$update["EvotingPlatform"] = ($pa_view->evoting_name == NULL)?$pa_view->evoting_plateform:'<a href="'.$pa_view->evoting_link.'" target="_blank">'.$pa_view->evoting_name.'</a>';
		$update["MeetingType"] = $pa_view->meeting_type;
		$update["VotingResults"] = $pa_view->voting_results;
		//print_r($user->companies_subscribed_year);

		if($pa_view->subscribed($user->companies_report_subscribed_year)){

			ob_start();
            $update["Report"] = $pa_view->report($user->parent,$user->customized);
            $update["Report"] = ob_get_clean();
		} else {
			$update["Report"] = 'Not Subscribed';
		}

		$update["Notice"] = $pa_view->notice_final();
		$update["ProxySlips"] = $pa_view->slip();
		$update["AttendanceSlips"] = $pa_view->attendance();


		$update["Teasor"] = $pa_view->teasor;
		$update["AnnualReport"] = $pa_view->annual_report;
		$update["MeetingOutcome"] = $pa_view->meeting_outcome;
		$update["MeetingMinutes"] = $pa_view->meeting_minutes;
		

		break;

	case '2':
		$ar_fields_name = array("Report Type","Publishing Date","Report","Governance Index","India Mandatory/Compliance Score","Board of Directors","Directors Remuneration","Stakeholder Engagement","Financial Reporting","Sustainability");
		$ar_fields_type = array("ReportType","MeetingDate","Report", "GovernmentIndex","IndiaMandatory","BoardofDirectors","DirectorsRemuneration","StakeholderEngagement","FinancialReporting","Sustainability");
		$cgs_report = new CGS($report_id);
		$update["ReportType"] =  $report_types[2];
		$update["MeetingDate"] = $cgs_report->meeting_date;
		$user->cgs_subscribed_comapnies_year($_SESSION["MEM_ID"], $cgs_report->year);

		if($cgs_report->subscribed($user->cgs_companies_subscribed_year)){
			$update["Report"] = $cgs_report->report();
		} else {
			$update["Report"] = 'Not Subscribed';
		}
		$update["GovernmentIndex"] = $cgs_report->govt_index;
		$update["IndiaMandatory"] = $cgs_report->india_man;
		$update["GovernanceIndexScore"] = $cgs_report->govt_index_score;
		$update["BoardofDirectors"] = $cgs_report->board_dir;
		$update["DirectorsRemuneration"] = $cgs_report->dir_rem;
		$update["StakeholderEngagement"] = $cgs_report->stake_eng;
		$update["FinancialReporting"] = $cgs_report->fin_rep;
		$update["Sustainability"] = $cgs_report->sustain;
		$update["ComplianceScore"] = $cgs_report->comp_score;
		break;

	case '3':
		$ar_fields_name = array("Report Type","Publishing Date","Report","Heading","Description");
		$ar_fields_type = array("ReportType","MeetingDate","Report", "Heading","Description");
		$query = mysql_query("SELECT * from research where res_id='$report_id' ");
		$row = mysql_fetch_array($query);
		$update["ReportType"] =  $report_types[3];
		$update["MeetingDate"] = ($row["publishing_date"] != '')? date("d M y", $row["publishing_date"]):'Not Set';
		

		$update["Heading"] = stripcslashes($row["heading"]);
		$update["Description"] = stripcslashes($row["description"]);
		break;
}

	$count = 0;
	
	foreach ($ar_fields_type as $ar) {
			echo "<tr><td width='35%'>".$ar_fields_name[$count]."</td><td>".$update[$ar]."</td></tr>";
		$count++;
	}

echo '</table>';

?>