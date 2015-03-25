<?php session_start();
require_once('../../sysan.php');
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

$sql = mysql_query("SELECT year from proxy_ad where id='$rid' ");
$row_yr = mysql_fetch_array($sql);
$year = $row_yr["year"];

$users = fetch_customized_users($com_id, $year);
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

check_status($rid);

function check_status($report_id){

  $id_sql = mysql_query("SELECT id from report_analyst where report_id='$report_id' and rep_type='1' and type='3' ");
  $id_row = mysql_fetch_array($id_sql);

  $report_analyst_id = $id_row["id"];

  $pa_report = new PA_admin($report_id);
  $flag_check = $pa_report->check_status();

  $strtime = strtotime("now");

  if($flag_check == 0) mysql_query("UPDATE report_analyst set completed_on= '$strtime' where id='$report_analyst_id' ");
    if($flag_check == 0) mysql_query("UPDATE proxy_ad set completed_on= '$strtime' where id='$report_id' ");

}

echo 'Custom Reports have been uploaded.';
?>