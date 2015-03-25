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
if(!isset($_POST["id"]) ) header("Location: ".STRSITE."access-denied.php");

$request_id = mysql_real_escape_string($_POST["id"]);

if(mysql_query("DELETE from proxies where id='$request_id' ")){
	echo 'success';
} else {
	echo 'fail';
}

?>