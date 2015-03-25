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

$subject = "Report Update Alert for ".$pa_report->company_name;
$noreply = 'noreply@sesgovernance.com';

//******************VOTING EMAIL **********************************************

$voting_body = '<table cellspacing="0" cellpadding="10" border="1"><tr><th>#</th><th>Resolution Name</th><th>Management/Shareholder Recommendation</th><th>SES Recommendation</th></tr>';

$recos = array();
$sql_reco = mysql_query("SELECT * from ses_recos");
while ($row_reco = mysql_fetch_array($sql_reco)) {
  $recos[$row_reco["id"]] = $row_reco["reco"];
}
  $sql_vote = mysql_query("SELECT * from voting where report_id='$report_id' order by resolution_number asc");
     $count =1;
     while($row_vote = mysql_fetch_array($sql_vote)) {
      $voting_body .= '<tr><td>'.stripcslashes($row_vote["resolution_number"]).'</td>';
      $voting_body .= '<td>'.stripcslashes($row_vote["resolution_name"]).'</td>';
      $voting_body .= '<td>'.stripcslashes($man_recos[$row_vote["man_reco"]]).'</td>';
      $voting_body .= '<td>'.stripcslashes($recos[$row_vote["ses_reco"]]).'</td>';
      $voting_body .= '</td></tr>';
     }

$voting_body .= '</table>';

//****************************************NORMAL SUBSCRIBED USERS EMAIL****************************************************

$normal_users = fetch_normal_users($pa_report->company_id, $pa_report->year);
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
  $body_in = '<p> Report has been uploaded for <b>'.$pa_report->company_name.'</b> / <b>'.$pa_report->meeting_type.'</b> / <b>'.$pa_report->meeting_date.'</b>. Please check the attached file.</p>
  '.$voting_body.'<hr><i>This is an auto generated email. Please do not reply.</i>';

  $at_folder = 'proxy_reports';
  $at_file = $pa_report->gen_report;
  $email_string = implode(',', $normal_users_email);
  $body = mysql_real_escape_string($body_in);
  mysql_query("INSERT into mail_queue (mailto, mailcc, mailbcc, mailbccmore, subject, content, at_folder, at_file) values ('$noreply','','$email_string','','$subject', '$body','$at_folder','$at_file') ");
   
}

//print_r($normal_users_email);


//****************************************CUSTOMIZED SUBSCRIBED USERS EMAIL****************************************************


$customized_users = fetch_customized_users($pa_report->company_id, $pa_report->year);

foreach ($customized_users as $customized_user) {

  $customized_users_email = array();

  $query = mysql_query("SELECT email,id,other_email from users where id='$customized_user' and active=0 limit 1 ");
  while($row = mysql_fetch_array($query)){
  array_push($customized_users_email, $row["email"]);
  if($row["other_email"] != '')array_push($customized_users_email, $row["other_email"]);
  array_push($sub_users, $row["id"]);
}
   

  $query = mysql_query("SELECT email,id from users where created_by_prim='$customized_user' and active = 0 ");
  while($row = mysql_fetch_array($query)){
    array_push($customized_users_email, $row["email"]);
    array_push($sub_users, $row["id"]);

  }

  // final mail to all
  if(sizeof($customized_users_email) > 0){
    $body_in = '<p> Report has been uploaded for <b>'.$pa_report->company_name.'</b> / <b>'.$pa_report->meeting_type.'</b> / <b>'.$pa_report->meeting_date.'</b>. Please check the attached file.</p>
    '.$voting_body.'<hr><i>This is an auto generated email. Please do not reply.</i>';
    $custom_file = $pa_report->custom_report($customized_user);
    if($custom_file){
      $at_folder = 'custom_reports';
      $at_file = $custom_file;
      $email_string = implode(',', $customized_users_email);
      $body = mysql_real_escape_string($body_in);
      mysql_query("INSERT into mail_queue (mailto, mailcc, mailbcc, mailbccmore, subject, content, at_folder, at_file) values ('$noreply','','$email_string','','$subject', '$body','$at_folder','$at_file') ");
    }
   
  }
}

//print_r($customized_users_email);


//****************************************UNSUBSCRIBED USERS EMAIL****************************************************

$user_string = '';

if(sizeof($sub_users) > 0) $user_string = implode(',', $sub_users);

$unsub_users_email = array();

if($user_string == ''){
  $query = mysql_query("SELECT users.email, users.other_email from users join user_voting_company on users.id=user_voting_company.user_id where users.active=0 and user_voting_company.report_upload = 1 and user_voting_company.com_id='".$pa_report->company_id."' ");
} else {
    $query = mysql_query("SELECT users.email, users.other_email from users join user_voting_company on users.id=user_voting_company.user_id where users.id NOT IN (".$user_string.")  and  users.active=0 and user_voting_company.report_upload = 1 and user_voting_company.com_id='".$pa_report->company_id."' ");
}

while($row = mysql_fetch_array($query)){
  array_push($unsub_users_email, $row["email"]);
  if($row["other_email"] != '')array_push($unsub_users_email, $row["other_email"]);
}
// final mail to all
if(sizeof($unsub_users_email)>0){
 $body_in = '<p> Report has been uploaded for <b>'.$pa_report->company_name.'</b> / <b>'.$pa_report->meeting_type.'</b> / <b>'.$pa_report->meeting_date.'</b>. Please subscribe to view the report.</p>
    '.$voting_body.'<hr><i>This is an auto generated email. Please do not reply.</i>';

  $email_string = implode(',', $unsub_users_email);
  $body = mysql_real_escape_string($body_in);
  mysql_query("INSERT into mail_queue (mailto, mailcc, mailbcc, mailbccmore, subject, content, at_folder, at_file) values ('$noreply','','$email_string','','$subject', '$body','','') ");
}
//print_r($unsub_users_email);

   
   $timenow = strtotime("now");
   $pre_rel = $pa_report->previous_release.' '.$timenow;
   
   if(mysql_query("UPDATE proxy_ad set released_on='$timenow', previous_release='$pre_rel' where id='".$pa_report->id."' ")){
    echo 'success';
   }


?>