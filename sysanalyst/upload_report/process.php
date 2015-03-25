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

$id= mysql_real_escape_string($_GET["id"]);
$rep_id = mysql_real_escape_string($_GET["rep_id"]);
$rep_type = mysql_real_escape_string($_GET["rep_type"]);
$strtime = strtotime("now");

$filename = $_FILES["attachfile"]["name"]; 
  if($filename != '') {

    $exten = explode('.',$filename);
    $last_val = sizeof($exten) - 1;
    $ext=$exten[$last_val];
    if(!in_array($ext, $file_types)) die('Please input a valid file');


    $filename = substr(str_shuffle(strtotime("now")), 0, 10).$filename;
    $rep = mysql_real_escape_string($filename);
    switch ($rep_type) {
      case '1':
        move_uploaded_file($_FILES["attachfile"]["tmp_name"],"../../proxy_reports/".$filename);
        if(file_exists("../../proxy_reports/".$filename)){
        	mysql_query("UPDATE proxy_ad set report='$filename' where id='$rep_id' ");
          check_status($rep_id,$id);
        }
        break;
      
       case '2':
        move_uploaded_file($_FILES["attachfile"]["tmp_name"],"../../cgs/".$filename);
        if(file_exists("../../cgs/".$filename)){
        	mysql_query("UPDATE cgs set report_upload='$filename', completed_on= '$strtime' where cgs_id='$rep_id' ");
        }
        break;

         case '3':

        move_uploaded_file($_FILES["attachfile"]["tmp_name"],"../../research/".$filename);
        if(file_exists("../../research/".$filename)){
        	mysql_query("UPDATE research set report_upload='$filename', completed_on= '$strtime' where res_id='$rep_id' ");
        }
        break;
    }

   if($rep_type == 2 || $rep_type == 3){
     mysql_query("UPDATE report_analyst set completed_on= '$strtime' where id='$id' ");
    
   }
    echo 'Succefully Uploaded.';
  } else{
    echo 'fail';
    die();
  }

function check_status($report_id, $report_analyst_id){
  
  $pa_report = new PA_admin($report_id);
  $flag_check = $pa_report->check_status();

  $strtime = strtotime("now");

  if($flag_check == 0) mysql_query("UPDATE report_analyst set completed_on= '$strtime' where id='$report_analyst_id' ");
  if($flag_check == 0) mysql_query("UPDATE proxy_ad set completed_on= '$strtime' where id='$report_id' ");

}

?>