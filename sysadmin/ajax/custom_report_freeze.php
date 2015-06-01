<?php session_start();
require_once('../../sysauth.php');
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

$report_id = $_POST["report_id"];
$type = $_POST["type"];
$str = strtotime("now");
$pa_report = new PA_admin($report_id);
if($type == 1){
	$flag_check = 0;
	$users = fetch_customized_users($pa_report->company_id, $pa_report->year);
  if(sizeof($users) > 0){
    foreach ($users as $user) {
       $query_custom = mysql_query("SELECT check_id, report_upload, ses_reco, detail from customized_reports left join customized_votes on customized_reports.report_id = customized_votes.report_id where customized_reports.user_id='$user' and customized_reports.report_id='".$pa_report->id."' ");
       if(mysql_num_rows($query_custom) > 0){
         	while($row_custom = mysql_fetch_array($query_custom)){
            if($row_custom["report_upload"] == '') $flag_check = 1;
            if($row_custom["check_id"] == 1 ){
              if($row_custom["ses_reco"] == NULL || $row_custom["ses_reco"] == 0 || $row_custom["detail"] == '' || $row_custom["detail"] == NULL){
                $flag_check = 1;
              }
            } 
          }
       } else {
        $flag_check = 1;
     }
    }
  }
	if($flag_check == 0){
		mysql_query("UPDATE proxy_ad set custom_report_freeze = $str where id = $report_id ");
	echo 'success';
	} else {
		echo 'Please fill all the details in custom recommendations.';
	}
	
} else if($type == 2) {
	mysql_query("UPDATE proxy_ad set custom_report_freeze = 0 where id = $report_id ");
	echo 'success';
} else echo 'failure';

?>