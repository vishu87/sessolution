<?php session_start();
require_once('../../subuserauth.php');

if(!isset($_POST["id"])  || $_SESSION["MEM_ID"] == '') header("Location: ".STRSITE."access-denied.php");

$today = strtotime("today");
$timenow = strtotime("now");
$com_id = $_POST["id"];
$table = 'user_voting_company';
$user_id = $_SESSION["MEM_ID"];

$sql_check = mysql_query("SELECT * from $table WHERE user_id='$user_id' and com_id='$com_id' and type = 0 ");
$num = mysql_num_rows($sql_check);
if($num != 0){
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