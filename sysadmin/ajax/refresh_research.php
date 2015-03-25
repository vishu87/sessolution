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

$res_id = $_POST["id"];

if(!isset($_POST["id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 1) header("Location: ".STRSITE."access-denied.php");
$res_report = new Research_admin($res_id);
$count = $_POST["count"];
?>
   <td><?php echo $count;?></td>
   <td><?php echo $res_report->company_name;?></td>
   <td><?php echo $res_report->meeting_date; ?></td>
   <td><?php echo $res_report->heading; ?></td>
   <td><?php echo $res_report->description;?></td>
   <td><?php echo $res_report->report(); ?></td>
   <td><?php echo $res_report->subscribers($count);?>
 </td>
   <td>
    <?php echo $res_report->edit($count); echo $res_report->delete($count); echo $res_report->release($count); ?>
  </td>
