<?php session_start();
require_once('../../auth.php');

if(!isset($_POST["report_id"])) header("Location: ".STRSITE."access-denied.php");

$report_id = $_POST["report_id"];

if(mysql_query("UPDATE user_admin_proxy_ad set com_approval = '0' where user_id='$_SESSION[MEM_ID]' and report_id = '$report_id' ")) echo 'success';
else echo 'fail';

?>