<?php session_start();

require_once('../../subuserauth.php');
require_once('../../classes/UserClass.php');

if(!isset($_POST["id"])) header("Location: ".STRSITE."access-denied.php");

$user_id = $_SESSION["MEM_ID"];
$report_id = mysql_real_escape_string($_POST["id"]);
$flag_check_vote = 0;
$flag_check_comment = 0;
$response = array();
$table = 'user_voting';

$res_sql = mysql_query("SELECT id from voting where report_id='$report_id' ");
if(mysql_num_rows($res_sql) > 0){
	$total_voting = mysql_num_rows($res_sql);
	$array_voting = array();
	while ($row = mysql_fetch_array($res_sql)) {
		array_push($array_voting,$row["id"]);
	}
	$vote_string = implode(',', $array_voting);
	

	$check_sql_total = mysql_query("SELECT id from $table where vote_id IN (".$vote_string.") and user_id='$user_id' ");
	$total_votes = mysql_num_rows($check_sql_total);

	$check_sql = mysql_query("SELECT id from $table where vote_id IN (".$vote_string.") and user_id='$user_id' and vote = 0 ");
	if(mysql_num_rows($check_sql) > 0 || ($total_voting != $total_votes )){
		$flag_check_vote = 1;
	}

	$check_sql = mysql_query("SELECT id from $table where vote_id IN (".$vote_string.") and user_id='$user_id' and comment = '' ");
	if(mysql_num_rows($check_sql) > 0){
		$flag_check_comment = 1;
	}

	if($flag_check_vote == 1 ){
		$response["success"] = false;
		$response["type"] = 0;
		$response["message"] =  'Please fill all votes before freezing the votes or press Save button first to commit your changes.';
	} else if($flag_check_comment == 1){
		$response["success"] = false;
		$response["type"] = 1;
		$response["message"] =  'Do you want to freeze votes without filling comments for every resolution? If you have filled comments for every resolution and still getting this alert, please press <b>Save</b> button first to commit your changes.';
	} else {
		$response["success"] = true;
	}
} else {
	$response["success"] = false;
	$response["type"] = 0;
	$response["message"] = "Votes are not uploaded by SES admin for this report";
}
echo json_encode($response);
die();
?>