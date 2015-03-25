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

$cgs_report = new CGS_admin($report_id);

$flag_send = 0;

if($cgs_report->gen_report != ''){

$subject = "Governance Score Report: ".$cgs_report->company_name;
$noreply = 'noreply@sesgovernance.com';

//****************************************NORMAL SUBSCRIBED USERS EMAIL****************************************************

$normal_users = fetch_cgs_users($cgs_report->company_id, $cgs_report->year);

$normal_users_email = array();


foreach ($normal_users as $normal_user) {
  $query = mysql_query("SELECT email,other_email,id from users where id='$normal_user' and active=0 limit 1 ");
  while($row = mysql_fetch_array($query)){
  array_push($normal_users_email, $row["email"]);
  if($row["other_email"])array_push($normal_users_email, $row["other_email"]);
  }

  $query = mysql_query("SELECT email,id from users where created_by_prim='$normal_user' and active = 0 ");
  while($row = mysql_fetch_array($query)){
    array_push($normal_users_email, $row["email"]);
    
  }
}

// final mail to all
if(sizeof($normal_users_email) > 0){
  $body_in = '<p> Corporate Governance Score has been uploaded for <b>'.$cgs_report->company_name.'</b> / <b>'.$cgs_report->meeting_date.'</b>. Please check the attached file.</p>
  <hr><i>This is an auto generated email. Please do not reply.</i>';

  
  $at_folder = 'cgs';
  $at_file = $cgs_report->gen_report;
  $email_string = implode(',', $normal_users_email);
  $body = mysql_real_escape_string($body_in);

  mysql_query("INSERT into mail_queue (mailto, mailcc, mailbcc, mailbccmore, subject, content, at_folder, at_file) values ('$noreply','','$email_string','','$subject', '$body','$at_folder','$at_file') ");
}


   $timenow = strtotime("now");
   $pre_rel = $cgs_report->previous_release.' '.$timenow;
   
   if(mysql_query("UPDATE cgs set released_on='$timenow', previous_release='$pre_rel' where cgs_id='".$cgs_report->id."' ")){
    echo 'success';
   }

}
?>