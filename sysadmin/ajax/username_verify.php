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

if( $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 1) header("Location: ".STRSITE."access-denied.php");


$val = $_POST["val"];
$col = $_POST["col"];


$query1 = mysql_query("SELECT $col from admin where $col='$val' ");
$query2 = mysql_query("SELECT $col from users where $col='$val' ");
$sum = mysql_num_rows($query1) + mysql_num_rows($query2);
if( $sum > 0){
	echo "fail";
} else {
	echo "success";
}

?>