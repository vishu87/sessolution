<?php session_start();
require_once('../../subuserauth.php');
require_once('../../classes/UserClass.php');
if(!isset($_POST["id"])) header("Location: ".STRSITE."access-denied.php");

$user_id = $_SESSION["MEM_ID"];
$report_id = $_POST["id"];

$flag_check = 0;
$res_sql = mysql_query("SELECT id from voting where report_id='$report_id' ");
if(mysql_num_rows($res_sql) > 0){
	while ($row = mysql_fetch_array($res_sql)) {
		$check_sql = mysql_query("SELECT id from user_voting where vote_id='$row[id]' and user_id='$user_id' and vote != 0 and comment != '' ");
		if(mysql_num_rows($check_sql) == 0){
			$flag_check = 1;
			break;
		}
	}

	if($flag_check == 1){
		echo 'Please fill all votes and comments field before freezing the votes or press Save button first to commit your changes.';
	} else {
		echo 'success';
	}
} else {
	echo "Votes are not uploaded by SES admin for this report";
}

?>