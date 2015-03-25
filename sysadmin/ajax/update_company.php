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


if(!isset($_POST["com_id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 1) header("Location: ".STRSITE."access-denied.php");

$ar_fields_all = array("com_name","com_bse_srcip","com_nse_sym","com_reuters","com_bloomberg","com_isin","com_address","com_telephone","com_website","com_sec_email","com_full_name");

	foreach ($ar_fields_all as $ar) {
		if(mysql_query("UPDATE companies set $ar = '".mysql_real_escape_string($_POST[$ar])."' where com_id = '$_POST[com_id]' ")){
			
		} else {
			echo 'fail';
		}
	}
	echo 'success';



?>