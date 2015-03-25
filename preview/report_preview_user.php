<?php session_start();
require_once('../config.php');
if($_SESSION["MEM_ID"] == ''){
	header("location: ".STRSITE."access-denied.php");
} else {
	if($_SESSION["PRIV"] != 0 && $_SESSION["PRIV"] != 3 ) header("location: ".STRSITE."access-denied.php");
}

include('../classes/UserClass.php');

$report_id= decrypt($_GET["res"]);
$report_id= preg_replace('/[^0-9]*/','', $report_id);

$report_type= decrypt($_GET["type"]);
$report_type= preg_replace('/[^0-9]*/','', $report_type);

//echo $report_id.' '.$report_type;
//die();
//error_reporting(0);
$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if(!$link) {
	die('Failed to connect to server: ' . mysql_error());
}
//Select database
$db = mysql_select_db(DB_DATABASE);
if(!$db) {
	die("Unable to select database");
}



$user_id = $_SESSION["MEM_ID"];
$user = new User($user_id);

if($report_type == 1){
	$report = new PA($report_id);
	if($user->customized == 0){
     $final_report = 'proxy_reports/'.$report->gen_report;
     } else {
      $sql_c = mysql_query("SELECT report_upload from customized_reports where user_id= '".$user->parent."' and report_id='".$report_id."' ");
      $row_c = mysql_fetch_array($sql_c);
      $final_report = 'custom_reports/'.$row_c["report_upload"];
    }

} elseif($report_type == 2) {
	$report = new CGS($report_id);
	$final_report = 'cgs/'.$report->gen_report;

} elseif($report_type == 3){
	$report = new Research($report_id);
	$final_report = 'research/'.$report->gen_report;
}
else {
	//header("location: ".STRSITE."access-denied.php");
}

if($final_report == '')header("location: ".STRSITE."access-denied.php");

$exts = explode('.', $final_report);
$ext = $exts[sizeof($exts)-1];

if($ext == 'pdf'){
	$file = dirname(__FILE__).'/../'.$final_report;
	header('Content-type: application/pdf');
	header('Content-Disposition: inline; filename="ReportPreview.pdf"');
	header('Content-Transfer-Encoding: binary');
	@readfile($file);
} else {
	header("Location: ../".$final_report);
}

?>

