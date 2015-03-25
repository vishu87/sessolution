<?php session_start();
error_reporting(0);
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

$pa_report_id = mysql_real_escape_string($_POST["id"]);
   
   if(mysql_query("UPDATE proxy_ad set released_on=0 where id='".$pa_report_id."' ")){
    echo 'success';
   }
  else {
  echo 'fail';
 }

?>