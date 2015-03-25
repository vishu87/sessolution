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
$cgs_id = mysql_real_escape_string($_POST["id"]);
$cgs_report = new CGS_admin($cgs_id);

if(!isset($_POST["id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 1) header("Location: ".STRSITE."access-denied.php");

$count = $_POST["count"];
?>
 <td><?php echo $count;?></td>
 <td><?php echo $cgs_report->company_name;
  $cgs_report->company_name = name_filter($cgs_report->company_name);
 ?></td>
 <td><?php echo $cgs_report->meeting_date;?></td>
 <td><?php echo $fetch_period[$cgs_report->year];?></td>
 <td><?php echo $cgs_report->govt_index;?></td>
 <td><?php echo $cgs_report->india_man;?></td>
 <td><?php echo $cgs_report->report(); ?></td>
 <td><?php 
  $cgs_report->subscribers($count);
 ?>
 
</td>
 <td>
  <?php
  $cgs_report->edit($count);
  $cgs_report->release($count);
  $cgs_report->delete($count);
?>
</td>
