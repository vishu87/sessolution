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
if(!isset($_POST["report_id"])) header("Location: ".STRSITE."access-denied.php");

$report_id = $_POST["report_id"];
$query = mysql_query("SELECT id from admin_proxy_ad where report_id = '$report_id' and final_freeze != 0 order by id desc limit 1 ");
$res = mysql_fetch_array($query);
$timenow = strtotime("now");
if(mysql_query("UPDATE admin_proxy_ad set final_unfreeze='$timenow' where report_id = '$report_id' and id='$res[id]' ")) echo 'success';
else echo 'Error';
?>