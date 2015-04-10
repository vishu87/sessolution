<?php session_start();
require_once('../../auth.php');
require_once('../../classes/UserClass.php');


$user = new User($_SESSION["MEM_ID"]);
$report_id = $_POST["report_id"];
$report_type = $_POST["report_type"];

$analysts = array();
 $sql_an = mysql_query("SELECT an_id, name from analysts ");
 while ($row_an = mysql_fetch_array($sql_an)) {
   $analysts[$row_an["an_id"]] = $row_an["name"];
 }


  $report_types  = array("","Proxy Advisory","CGS","Research");
  $task_type=array("","Data","Analysis","Review");


$query_sent = mysql_query("SELECT * from companies where com_id='$_POST[id]' LIMIT 1");
$ar_fields_all = array("com_name","com_bse_code","com_bse_srcip","com_nse_sym","com_reuters","com_bloomberg","com_isin","add_date");
$ar_fields_name = array("Company Name","BSE Code","BSE Srcip","NSE Symbol","Rueters","Bloomberg","ISIN","Added on");


if(!isset($_POST["report_id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 0) header("Location: ".STRSITE."access-denied.php");

$update = array();

echo '<table class="table table-bordered table-hover">';
switch ($report_type) {
	case '1':
		$ar_fields_name = array("Report Type","Meeting Date","Record Date","e-Voting Start Date","e-Voting Deadline","e-Voting Platform","Meeting Type","Report","Notice","Proxy Slip","Annual Report","Meeting Outcome","Meeting Minutes","Proxy Request/Initiated","Proxy Appointed","Proxy Form","Proxy Slip/ Confirmation");
		$ar_fields_type = array("ReportType","MeetingDate","RecordDate","EvotingStart","EvotingEnd","EvotingPlatform","MeetingType","Report","Notice","ProxySlips","AnnualReport","MeetingOutcome","MeetingMinutes","ProxyRequest","ProxyAppointed","ProxyForm","ProxySlip");
		$pa_view = new PA($report_id);
		
		$user->pa_subscribed_comapnies_year($_SESSION["MEM_ID"],$pa_view->year);


		$update["ReportType"] =  $report_types[1];
		$update["MeetingDate"] = $pa_view->meeting_date;
		$update["RecordDate"] = $pa_view->record_date;
		$update["EvotingStart"] = $pa_view->evoting_start;
		$update["EvotingEnd"] = $pa_view->evoting_end;
		$update["EvotingPlatform"] = ($pa_view->evoting_name == NULL)?$pa_view->evoting_plateform:'<a href="'.$pa_view->evoting_link.'" target="_blank">'.$pa_view->evoting_name.'</a>';
		$update["MeetingType"] = $pa_view->meeting_type;
		//print_r($user->companies_subscribed_year);

		if($pa_view->subscribed($user->companies_report_subscribed_year)){
			ob_start();
            $pa_view->report($_SESSION["MEM_ID"],$user->customized);
            $update["Report"] = ob_get_clean();
		} else {
			$update["Report"] = 'Not Subscribed';
		}

		$update["Notice"] = $pa_view->notice_final();
		$update["ProxySlips"] = $pa_view->slip();

		$update["Teasor"] = $pa_view->teasor;
		$update["AnnualReport"] = $pa_view->annual_report;
		$update["MeetingOutcome"] = $pa_view->meeting_outcome;
		$update["MeetingMinutes"] = $pa_view->meeting_minutes;
		
		$query_data = mysql_query("SELECT * from proxies where proxy_id='$report_id' and user_id='$_SESSION[MEM_ID]' ");
		$data = mysql_fetch_array($query_data);
		$update["ProxyRequest"] = (mysql_num_rows($query_data) == 0)?'NA':'Requested on '.date("d-M-Y", $data["add_date"]);
		if(mysql_num_rows($query_data) == 0){ // if the proxy advisroy is not in ses voting check in self voting module for that user

			$query_data = mysql_query("SELECT * from self_proxies where proxy_id='$report_id' and user_id='$_SESSION[MEM_ID]' ");
			$data = mysql_fetch_array($query_data);
			$update["ProxyRequest"] = (mysql_num_rows($query_data) == 0)?'NA':'Initiated on '.date("d-M-Y", $data["add_date"]);
			if(mysql_num_rows($query_data) == 0){ 
				$update["ProxyAppointed"] = 'NA';
				$update["ProxyForm"] = 'NA';
				$update["ProxySlip"] = 'NA';
				
			} else { // if proxy exists.. look for appoint date, form upload user name(for self voting) and confirmation date by voter
				$voter_sql = mysql_query("SELECT name,email from self_proxy_voters where vid='".$data["voter_id"]."'");
				$row_voter = mysql_fetch_array($voter_sql);
				$update["ProxyAppointed"] = $row_voter["name"].' ('.$row_voter["email"].') Appointed on '.date("d-M-Y", $data["appoint_date"]);
				$update["ProxyForm"] = ($data["form"] != '')?'<a href="../user_proxy_forms/'.$data["form"].'" target="_blank">View</a> uploaded on '.date("d-M-Y", $data["form_upload_date"]):'NA';
				$update["ProxySlip"] = ($data["confirmation_date"] != '')?'Confirmed On'.date("d-M-Y", $data["confirmation_date"]):'NA';
				
			}

		} else { // this is for ses voting
			$update["ProxyAppointed"] = ($data["voter_id"] != 0)?'Appointed on '.date("d-M-Y", $data["appoint_date"]):'NA';
			$update["ProxyForm"] = ($data["form"] != '')?'<a href="../user_proxy_forms/'.$data["form"].'" target="_blank">View</a> uploaded on '.date("d-M-Y", $data["form_upload_date"]):'NA';
			$update["ProxySlip"] = ($data["slip"] != '')?'<a href="../user_proxy_slips/'.$data["slip"].'" target="_blank">View</a> completed on '.date("d-M-Y", $data["final_date"]):'NA';
		}

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
		$update["BoardofDirectors"] = $cgs_report->board_dir;
		$update["DirectorsRemuneration"] = $cgs_report->dir_rem;
		$update["StakeholderEngagement"] = $cgs_report->stake_eng;
		$update["FinancialReporting"] = $cgs_report->fin_rep;
		$update["Sustainability"] = $cgs_report->sustain;
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