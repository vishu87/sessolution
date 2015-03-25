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
	
	if($report != ''){

		$id = strtotime("now").md5($row["id"]);
		$exten = explode('.',$report);
		$last_val = sizeof($exten) - 1;
		$ext=$exten[$last_val];
		$name=substr(str_shuffle(strtotime("now")), 0, 10).$report;	
		$timenow = strtotime("now");
		 if(in_array($ext, $file_types)){
			move_uploaded_file($_FILES["report_".$row["id"]]["tmp_name"],"../../custom_reports/".$name);
			mysql_query("DELETE from customized_reports where user_id='$row[id]' and report_id='$rid' ");
			mysql_query("INSERT into customized_reports (user_id, report_id, report_upload, add_date) values ('$row[id]','$rid','$name','$timenow') ");
		}
	}
}

header("Location: custom_reports.php?com_id=".$com_id."&success=1&id=".$rid);

?>