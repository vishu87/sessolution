<?php session_start();
require_once('../../auth.php');
require_once('../../classes/UserClass.php');
$report_id = $_POST["report_id"];

if(!isset($_POST["report_id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 0) header("Location: ".STRSITE."access-denied.php");

$pa_report = new PA($report_id);

if($pa_report->evoting_name != NULL){
	if($pa_report->evoting_id == 1){

		$link = '<a href="#stack3" data-toggle="modal" role="button" class="btn blue span3" onclick="nsdl_voting_screen('.$report_id.',\''.$_POST["company_name"].'\')">e-Vote ('.$pa_report->evoting_name.')</a>';
	} else {
		 $link = '<a href="'.$pa_report->evoting_link.'" target="_blank" class="btn blue span3">e-Vote ('.$pa_report->evoting_name.')</a>';
	}
} else {
  $link = '<div class="span4">e-Voting Plateform: '.$pa_report->evoting_plateform.'</div>';
}

if($pa_report->meeting_type != 'PB') { ?>
  <a href="#myModal" data-toggle="modal"  role="button" class="btn yellow span3" onclick="assign_voter('<?php echo $_POST["company_name"] ?>',<?php echo $report_id ?>)">Appoint Voter</a>
<?php } ?>
<?php echo $link ?>