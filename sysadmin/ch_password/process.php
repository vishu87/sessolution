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

$folder = 'ch_password';
$table = 'admin';
/**************************************** Personal Information **********************************************/
$query  = mysql_query("SELECT password from $table where id='$_SESSION[MEM_ID]' ");
$row  = mysql_fetch_array($query);
$old_password = $row["password"];

$old_p = mysql_real_escape_string($_POST["old_p"]);  
$new_p = mysql_real_escape_string($_POST["new_p"]); 
$re_new_p = mysql_real_escape_string($_POST["re_new_p"]); 

//check for old password
if(strcmp(md5($old_p), $old_password) != 0 ){
	header("Location: ../".$folder.".php?success=2");
} elseif(strlen($new_p) < 8) {
	header("Location: ../".$folder.".php?success=4");
} elseif(strcmp(md5($new_p), md5($re_new_p)) != 0) {
	header("Location: ../".$folder.".php?success=3");
} else {
	$new_p = md5($new_p);
	mysql_query("UPDATE $table set password='$new_p' where id='$_SESSION[MEM_ID]' ");
	header("Location: ../".$folder.".php?success=1");
}
?>