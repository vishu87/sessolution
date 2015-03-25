<?php session_start();
require_once('../../auth.php');


if(!isset($_POST["id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 0) header("Location: ".STRSITE."access-denied.php");

$today = strtotime("today");
$timenow = strtotime("now");
$com_id = mysql_real_escape_string($_POST["id"]);
$user_id = mysql_real_escape_string($_POST["user_id"]);
$table = 'user_voting_company';
$parent_id = $_SESSION["MEM_ID"];

if($user_id == $parent_id){
	$flag = true;
} else {
	$check = mysql_query("SELECT id from users where created_by_prim = $parent_id and id = $user_id limit 1 ");
	if(mysql_num_rows($check) != 0){
		$flag = true;
	} else $flag = false;
}

$sql_check = mysql_query("SELECT * from $table WHERE user_id='$user_id' and com_id='$com_id' ");
$num = mysql_num_rows($sql_check);
if($num != 0 && $flag){
	$res = mysql_fetch_array($sql_check);
	$sql ="INSERT into user_voting_company_delete (user_id, com_id, add_date, delete_date) VALUES ('$user_id','$com_id','$res[add_date]','$timenow') ";
	if(mysql_query($sql)){
		mysql_query("DELETE from user_voting_company where id='$res[id]' and user_id='$user_id' ");
		
		mysql_query("INSERT into user_activity (user_id, activity_id,details) values ('$_SESSION[MEM_ID]','30','$com_id')" );
		
		mysql_query("DELETE user_voting_proxy_reports from user_voting_proxy_reports inner join proxy_ad on user_voting_proxy_reports.report_id=proxy_ad.id where user_voting_proxy_reports.user_id='$user_id' and proxy_ad.com_id='$com_id' and proxy_ad.meeting_date > $today ");
		echo 'success';
	}

} else {
	echo 'fail';
}


?>