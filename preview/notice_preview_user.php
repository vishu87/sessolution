<?php session_start();
require_once('../config.php');
require_once('../classes/UserClass.php');

$report_id= decrypt($_GET["report_id"]);
$report_id= preg_replace('/[^0-9]*/','', $report_id);

$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if(!$link) {
	die('Failed to connect to server: ' . mysql_error());
}
//Select database
$db = mysql_select_db(DB_DATABASE);
if(!$db) {
	die("Unable to select database");
}


$report = new PA($report_id);

$final_report = 'proxy_notices/'.$report->notice;

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

