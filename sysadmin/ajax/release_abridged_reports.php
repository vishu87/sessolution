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

if(!isset($_POST["id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 1) header("Location: ".STRSITE."access-denied.php");

$report_id = mysql_real_escape_string($_POST["id"]);

$pa_report = new PA_admin($report_id);
$flag_send = 0;

$subject = "Abridged Report for ".$pa_report->company_name.' '.$pa_report->meeting_type.' '.$pa_report->meeting_date;
$noreply = 'noreply@sesgovernance.com';

//******************VOTING EMAIL **********************************************

$voting_body = '';


//****************************************LIMITED SUBSCRIBED USERS EMAIL****************************************************

$normal_users = fetch_limited_users($pa_report->company_id, $pa_report->year);

$normal_users_email = array();

$sub_users = array();

foreach ($normal_users as $normal_user) {
  $query = mysql_query("SELECT email,id,other_email from users where id='$normal_user' and active=0 limit 1 ");
  while($row = mysql_fetch_array($query)){
  array_push($normal_users_email, $row["email"]);
  if($row["other_email"] != '')array_push($normal_users_email, $row["other_email"]);
  array_push($sub_users, $row["id"]);
}
  

  $query = mysql_query("SELECT email,id from users where created_by_prim='$normal_user' and active = 0 ");
  while($row = mysql_fetch_array($query)){
    array_push($normal_users_email, $row["email"]);
    array_push($sub_users, $row["id"]);
  }
}

// final mail to all
if(sizeof($normal_users_email) > 0){
  $body_in = '<p>Abridged Report has been uploaded for <b>'.$pa_report->company_name.'</b> / <b>'.$pa_report->meeting_type.'</b> / <b>'.$pa_report->meeting_date.'</b>. Please check the attached file.</p>
  '.$voting_body.'<hr><i>This is an auto generated email. Please do not reply.</i>';

  $at_folder = 'abridged_reports';
  $at_file = $pa_report->abridged_report;
  $email_string = implode(',', $normal_users_email);
  
  $body = mysql_real_escape_string($body_in);
  mysql_query("INSERT into mail_queue (mailto, mailcc, mailbcc, mailbccmore, subject, content, at_folder, at_file) values ('$noreply','','$email_string','','$subject', '$body','$at_folder','$at_file') ");
   
}
   
   $timenow = strtotime("now");
   $pre_rel = $pa_report->previous_abridged_release.' '.$timenow;
   
   if(mysql_query("UPDATE proxy_ad set abridged_release='$timenow', previous_abridged_release='$pre_rel' where id='".$pa_report->id."' ")){
    echo 'success';
   }


?>