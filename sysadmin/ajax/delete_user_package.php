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


if(!isset($_POST["package_id"]) ) header("Location: ".STRSITE."access-denied.php");

$package_id = mysql_real_escape_string($_POST["package_id"]);
$user_id = mysql_real_escape_string($_POST["user_id"]);



	if(mysql_query("DELETE from users_package where package_id = '$package_id' and user_id='$user_id' ")){
		echo 'success';
	} else {
		echo 'fail';
	}


?>