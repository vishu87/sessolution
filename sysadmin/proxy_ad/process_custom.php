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

require_once('../../classes/MemberClass.php');

$rid = $_GET["rid"];
$com_id = $_GET["com_id"];

$pa_report = new PA_admin($rid);

$users = fetch_customized_users($pa_report->company_id, $pa_report->year);
$user_string = implode(',', $users);

$sql_custom = mysql_query("SELECT id, name from users where id IN ($user_string)  and customized = '1' ");


while ($row = mysql_fetch_array($sql_custom)) {
	
	$report = $_FILES["report_".$row["id"]]["name"];
	$check = $_POST["check_".$row["id"]];
	$custom_reco = $_POST["custom_reco_".$row["id"]];

	$check_entry = mysql_query("SELECT custom_id, report_upload from customized_reports where user_id = '$row[id]' and report_id = '$rid' limit 1 ");
	if(mysql_num_rows($check_entry) > 0){
		$flag_new = 0;
		$row_custom = mysql_fetch_array($check_entry);
		$custom_id = $row_custom["custom_id"];
		$report_upload = $row_custom["report_upload"];
	} else {
		$flag_new = 1;
	}
	
	if($report != ''){

		$id = strtotime("now").md5($row["id"]);
		$exten = explode('.',$report);
		$last_val = sizeof($exten) - 1;
		$ext=$exten[$last_val];
		$name=substr(str_shuffle(strtotime("now")), 0, 10).$report;	
		$timenow = strtotime("now");
		if(in_array($ext, $file_types)){
			move_uploaded_file($_FILES["report_".$row["id"]]["tmp_name"],"../../custom_reports/".$name);
		} else $name = '';
	} else $name = $report_upload;

	if($flag_new == 1){
		mysql_query("INSERT into customized_reports (user_id, report_id,check_id, report_upload, add_date) values ('$row[id]','$rid','$check','$name','$timenow') ");
	} else {
		$sql = "UPDATE customized_reports set user_id = '$row[id]', report_id = '$rid', report_upload = '$name', add_date = '$timenow', check_id = '$check', custom_reco = '$custom_reco' where custom_id = $custom_id ";
		mysql_query($sql);
	}
}

header("Location: custom_reports.php?com_id=".$com_id."&success=1&id=".$rid);

?>