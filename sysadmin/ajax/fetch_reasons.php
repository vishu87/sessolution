<?php session_start();
require_once('../../sysauth.php');
$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if(!$link) {
	die('Failed to connect to server: ' . mysql_error());
}
//Select database
$db = mysql_select_db(DB_DATABASE);
if(!$db) {
	die("Unable to select database");
}


if($_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 1) header("Location: ".STRSITE."access-denied.php");

$res_type_id = $_POST["res_type_id"];

$sql_reso = mysql_query("Select * from reasons where res_type_id = '$res_type_id' and status=0");
  while ($row_reso = mysql_fetch_array($sql_reso)) {
    echo '<option value="'.$row_reso["id"].'">'.$row_reso["reason"].'</option>';
  }

?>