<?php session_start();
require_once('../../auth.php');

if(!isset($_POST["report_id"])) header("Location: ".STRSITE."access-denied.php");

$report_id = $_POST["report_id"];

$timenow = strtotime("today");
if(mysql_query("UPDATE user_admin_proxy_ad set com_approval='$timenow' where user_id='$_SESSION[MEM_ID]' and report_id = '$report_id' ")) echo 'success';
else echo 'fail';

?>