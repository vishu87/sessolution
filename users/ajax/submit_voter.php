<?php session_start();
require_once('../../auth.php');


$voter = $_POST["voter"];
$report_id = $_POST["report_id"];


if(!isset($_POST["report_id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 0) header("Location: ".STRSITE."access-denied.php");

$timenow = strtotime("now");

if($voter == 0){
	mysql_query("DELETE from self_proxies where proxy_id='$report_id' and user_id='$_SESSION[MEM_ID]' ");
	echo 'success2';
} else {

	$check = mysql_query("SELECT id from self_proxies where proxy_id='$report_id' and user_id='$_SESSION[MEM_ID]' ");
	if(mysql_num_rows($check) > 0){ // proxy request exists
		mysql_query("UPDATE self_proxies set voter_id='$voter', appoint_date='$timenow' where proxy_id='$report_id' and user_id='$_SESSION[MEM_ID]'");
	} else { // new request
		mysql_query("INSERT into self_proxies (proxy_id, user_id, voter_id,appoint_date,add_date) values ('$report_id','$_SESSION[MEM_ID]','$voter','$timenow','$timenow') ");
	}
	mysql_query("INSERT into user_activity (user_id, activity_id,report_id, report_type) values ('$_SESSION[MEM_ID]','6','$report_id','1')");
	echo 'success';

}

?>