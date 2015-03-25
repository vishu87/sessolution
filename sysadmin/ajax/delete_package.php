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


if(!isset($_POST["pack_id"]) ) header("Location: ".STRSITE."access-denied.php");

$check = mysql_query("SELECT id from users_package where package_id ='$_POST[pack_id]' ");

if(mysql_num_rows($check) > 0){
	echo 'users';
} else {
	if(mysql_query("DELETE from package where package_id = '$_POST[pack_id]' ")){
		echo 'success';
	} else {
		echo 'fail';
	}
}

?>