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

$location = $_POST["location"];
$report_type = $_POST["report_type"];


if(!isset($_POST["location"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 1) header("Location: ".STRSITE."access-denied.php");
$str = '';
$sql_met = mysql_query("SELECT vid,name from proxy_voters where location = '$location' ");
while ($row_met = mysql_fetch_array($sql_met)) {
   $str .= '<option value="'.$row_met["vid"].'">'.$row_met["name"].'</option>';
 } 
echo $str;
?>