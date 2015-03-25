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

$report_id = mysql_real_escape_string($_POST["report_id"]);
$type = mysql_real_escape_string($_POST["type"]);
$str = strtotime("now");

if($type == 1){
	$query = mysql_query("SELECT id from admin_proxy_ad where report_id = '$report_id' and final_freeze != 0 order by id desc limit 1 ");
	if(mysql_num_rows($query) > 0){
		$res = mysql_fetch_array($query);
		if($res["final_unfreeze"] == 0) {
			$flag = 0;
			$check = mysql_query("SELECT ses_reco,detail from voting where report_id='$report_id' ");
			while ($row_check = mysql_fetch_array($check)) {
				if($row_check["ses_reco"] == 0 || $row_check["ses_reco"] == 1 || $row_check["detail"]== '' ){
					$flag = 1;
				}
			}
			if($flag == 0){
				if(mysql_query("UPDATE proxy_ad set vote_completed_on='$str' where id='$report_id' ")) echo 'success';
				else echo 'Database Error';
			} else {
				echo 'Please fill all votes and comments before marking complete';
			}
			
		} else {
			echo 'Please freeze your votes before completing.';
		}
	} else {
		echo 'Please freeze your votes before completing.';
	}
} else {
	if(mysql_query("UPDATE proxy_ad set vote_completed_on=0 where id='$report_id' ")) echo 'success';
	else echo 'Database Error';
}

?>