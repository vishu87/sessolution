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
$user_id = $_POST["user_id"];
$vote_id = $_POST["vote_id"];
$votes = $_POST["votes"];
$comments = $_POST["comments"];

mysql_query("DELETE from customized_votes where user_id = $user_id and report_id = $report_id ");
$sql = '';
$count = 0;
foreach ($vote_id as $resolution) {
  mysql_query("INSERT into customized_votes (report_id, user_id, vote_id, ses_reco, detail ) values ('$report_id', '$user_id', '$resolution', '$votes[$count]', '$comments[$count]') "); 
  $count++;
}
echo 'success';
?>