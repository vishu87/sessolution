<?php session_start();

require_once('../../auth.php');
require_once('../../classes/UserClass.php');

if(!isset($_POST["id"])) header("Location: ".STRSITE."access-denied.php");

$user_id = $_SESSION["MEM_ID"];
$report_id = mysql_real_escape_string($_POST["id"]);
$type = mysql_real_escape_string($_POST["type"]);
$flag_check = 0;

$table = ($type == 1)?'user_voting':'user_admin_voting';

if($type == 2){
	$sql_check_ign = mysql_query("SELECT ignore_an from user_admin_proxy_ad where user_id='$user_id' and report_id='$report_id' and ignore_an = 1  ");
	if(mysql_num_rows($sql_check_ign) > 0){
		die('success');
	}

}

$res_sql = mysql_query("SELECT id from voting where report_id='$report_id' ");
if(mysql_num_rows($res_sql) > 0){
	while ($row = mysql_fetch_array($res_sql)) {
		$check_sql = mysql_query("SELECT id from $table where vote_id='$row[id]' and user_id='$user_id' and vote != 0 and comment != '' ");
		if(mysql_num_rows($check_sql) == 0){
			$flag_check = 1;
			break;
		}
	}

	if($flag_check == 1 ){
		echo 'Please fill all votes and comments field before freezing the votes or press Save button first to commit your changes.';
	} else {
		echo 'success';
	}
} else {
	echo "Votes are not uploaded by SES admin for this report";
}

?>