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



if(!isset($_POST["id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 1) header("Location: ".STRSITE."access-denied.php");


if(mysql_query("UPDATE proxy_ad set skipped_on='".strtotime("now")."' , skipped_by = '$_SESSION[MEM_ID]' where id = '$_POST[id]' ")){
	mysql_query("DELETE from report_analyst where report_id = '$_POST[id]' and rep_type = 1 ");
	echo 'success';
} else {
	echo 'fail';
}
	
	



?>