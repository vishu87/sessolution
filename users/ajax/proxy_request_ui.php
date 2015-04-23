<?php session_start();
require_once('../../auth.php');
require_once('../../classes/UserClass.php');

$user = new User($_SESSION["MEM_ID"]);
$report_id = $_POST["report_id"];

$report_types  = array("","Proxy Advisory","CGS","Research");
$task_type=array("","Data","Analysis","Review");

	

if(!isset($_POST["report_id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 0) header("Location: ".STRSITE."access-denied.php");

$update = array();
$pa_report = new PA($report_id);
$pa_report->request_proxy($_SESSION["MEM_ID"],$user->proxy_module);
echo '<div class="row-fluid"><div class="span12">'.$pa_report->proxy_button.'</div></div><br>';

echo '<table class="table table-bordered table-hover">';

		$ar_fields_name = array("Proxy Request/Initiated","Proxy Appointed","Proxy Form","Proxy Slip/Confirmation");
		$ar_fields_type = array("ProxyRequest","ProxyAppointed","ProxyForm","ProxySlip");
		

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
				$voter_sql = mysql_query("SELECT name,email from self_proxy_voters where user_id='".$user->parent."' ");
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
		


	$count = 0;
	
	foreach ($ar_fields_type as $ar) {
			echo "<tr><td>".$ar_fields_name[$count]."</td><td>".$update[$ar]."</td></tr>";
		$count++;
	}

echo '</table>';

?>