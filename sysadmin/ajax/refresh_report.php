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

$proxy_id = mysql_real_escape_string($_POST["id"]);
$count = mysql_real_escape_string($_POST["count"]);

$proxy_report = new PA_admin($proxy_id);


?>
   <td><?php echo $count;?></td>
   <td><?php echo $proxy_report->company_name; 
    $row["com_name"] = name_filter($proxy_report->company_name);
   ?></td>
   
   <td><?php echo $proxy_report->meeting_date; ?></td>
   <td><?php echo $proxy_report->evoting_end; ?></td>
   <td><?php echo $proxy_report->meeting_type;?></td>
   
   <td><?php echo $proxy_report->report() ?></td>
   
   <td>
    <?php 
    $proxy_report->users();
    $proxy_report->add_user_button($count);
   ?>
   
 </td>
   <td>
    <?php 
      $proxy_report->ses_voting($count);
      $proxy_report->edit_button($count);
      $proxy_report->custom_button($count); 
      $proxy_report->release($count); 
      $proxy_report->release_abridged($count); 
      $proxy_report->skip($count);
      $proxy_report->unskip($count); 
      $proxy_report->delete();
      if($proxy_report->meeting_timestamp < strtotime("now"))$proxy_report->meeting_results($count);

    ?>
  </td>