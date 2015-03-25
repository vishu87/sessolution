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

$res_report = new Research_admin($report_id);

$flag_send = 0;

if($res_report->gen_report != ''){

$subject = "Governance Research Report: ".$res_report->heading;
$noreply = 'noreply@sesgovernance.com';

//****************************************NORMAL SUBSCRIBED USERS EMAIL****************************************************

$normal_users = fetch_research_users($res_report->id);

$normal_users_email = array();

foreach ($normal_users as $normal_user) {
  $query = mysql_query("SELECT email,id,other_email from users where id='$normal_user' and active=0 limit 1 ");
  while($row = mysql_fetch_array($query)){
  array_push($normal_users_email, $row["email"]);
  if($row["other_email"])array_push($normal_users_email, $row["other_email"]);
}

  $query = mysql_query("SELECT email,id from users where created_by_prim='$normal_user'  and active = 0 ");
  while($row = mysql_fetch_array($query)){
    array_push($normal_users_email, $row["email"]);
  }
}
//print_r($normal_users_email);

// final mail to all
if(sizeof($normal_users_email) > 0){
  $body_in = '<p> Research report has been uploaded for <b>'.$res_report->heading.'</b> / <b>'.$res_report->meeting_date.'</b>. Please check the attached file.</p>
  <hr><i>This is an auto generated email. Please do not reply.</i>';

  $at_folder = 'research';
  $at_file = $res_report->gen_report;
  $email_string = implode(',', $normal_users_email);
  $body = mysql_real_escape_string($body_in);
  mysql_query("INSERT into mail_queue (mailto, mailcc, mailbcc, mailbccmore, subject, content, at_folder, at_file) values ('$noreply','','$email_string','','$subject', '$body','$at_folder','$at_file') ");
   
}
   
   $timenow = strtotime("now");
   $pre_rel = $res_report->previous_release.' '.$timenow;
   
   if(mysql_query("UPDATE research set released_on='$timenow', previous_release='$pre_rel' where res_id='".$res_report->id."' ")){
    echo 'success';
   }
}
?>