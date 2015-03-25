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
$user = mysql_real_escape_string($_POST["user"]);


if(!isset($_POST["package_id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 1) header("Location: ".STRSITE."access-denied.php");


$ar_fields_all = array("package_name","package_year","visibility");
$start_time =  strtotime('01-03-'.$_POST["package_year"]);
$end_time =  strtotime('01-04-'.$_POST["package_year"]) - 1; 
	foreach ($ar_fields_all as $ar) {
		if(mysql_query("UPDATE package set $ar = '".mysql_real_escape_string(($_POST[$ar]))."' where package_id = '$_POST[package_id]' ")){
			
		} else {
			echo 'fail';
		}
	}
	mysql_query("UPDATE package set start_time = '$start_time' where package_id = '$_POST[package_id]' ");
	mysql_query("UPDATE package set end_time = '$end_time' where package_id = '$_POST[package_id]' ");
	echo 'success';



?>