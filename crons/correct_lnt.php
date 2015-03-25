<?php 

define('ROOT_PATH',dirname(__FILE__).'/');

include(ROOT_PATH.'../config.php');

$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if(!$link) {
	die('Failed to connect to server: ' . mysql_error());
}
//Select database
$db = mysql_select_db(DB_DATABASE);
if(!$db) {
	die("Unable to select database");
}

$user_arr = array();

$parent_id = 7;
array_push($user_arr, $parent_id);

$ana_sql = mysql_query("SELECT id from users where created_by_prim = '$parent_id' ");
while ($row = mysql_fetch_array($ana_sql)) {
	array_push($user_arr, $row["id"]);
}

$usr_string = implode(',', $user_arr);

$sql = mysql_query("SELECT distinct report_id from user_voting_proxy_reports where user_id IN (".$usr_string.") ");

while ($row = mysql_fetch_array($sql)) {
	
	$report_id  = $row["report_id"];
	//echo $report_id.'<br>';
	$check_ignore = mysql_query("SELECT final_freeze, final_unfreeze ,ignore_an,deadline from user_admin_proxy_ad where user_id='$parent_id' and report_id='$report_id' ");
	if(mysql_num_rows($check_ignore)>0){

		$row_ignore = mysql_fetch_array($check_ignore);
		$deadline = $row_ignore["deadline"];
		$final_freeze = $row_ignore["final_freeze"];
		$final_unfreeze = $row_ignore["final_unfreeze"];
		if($row_ignore["ignore_an"] == 1) $ignore_an = $row_ignore["ignore_an"];
	} else {
		continue;
	}

	if($final_freeze != 0 && $final_unfreeze == 0){
		if($ignore_an == 1){
			$sql_del = "SELECT user_id from user_proxy_ad where report_id='$report_id' and user_id IN (".$usr_string.") and auto_abstained != 0 ";
			//echo $sql_del.'<br>';
			$sql_check_ana = mysql_query($sql_del);
			while ($row_del = mysql_fetch_array($sql_check_ana)) {
				mysql_query("DELETE from user_voting_proxy_reports where user_id='$row_del[user_id]' and report_id = '$report_id' ");
				mysql_query("DELETE from user_proxy_ad where user_id='$row_del[user_id]' and report_id = '$report_id' ");
			}

			foreach ($user_arr as $user) {
				$check_entry = mysql_query("SELECT id from user_proxy_ad where report_id='$report_id' and user_id = '$user' ");
				if(mysql_num_rows($check_entry) == 0){
					mysql_query("DELETE from user_voting_proxy_reports where user_id='$user' and report_id = '$report_id' ");
				}
			}

		}

	}
}


$sql = mysql_query("SELECT distinct report_id from user_voting_proxy_reports where user_id IN (".$usr_string.") ");


while ($row = mysql_fetch_array($sql)) {

	$report_id  = $row["report_id"];

	$check_ignore = mysql_query("SELECT final_freeze, final_unfreeze ,ignore_an,deadline from user_admin_proxy_ad where user_id='$parent_id' and report_id='$report_id' ");
	if(mysql_num_rows($check_ignore)>0){

		$row_ignore = mysql_fetch_array($check_ignore);
		$deadline = $row_ignore["deadline"];
		$final_freeze = $row_ignore["final_freeze"];
		$final_unfreeze = $row_ignore["final_unfreeze"];
		if($row_ignore["ignore_an"] == 1) $ignore_an = $row_ignore["ignore_an"];
	} else {
		continue;
	}

	if($final_freeze != 0 && $final_unfreeze == 0){
		if($ignore_an == 1){
			$sql_del = mysql_query("SELECT user_id from user_proxy_ad where report_id='$report_id' and user_id IN (".$usr_string.") ");
			echo $report_id.' :'.mysql_num_rows($sql_del);
			echo (mysql_num_rows($sql_del) != 1)?'NOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO':'';
			echo '<br>';

		}

	}

}
?>