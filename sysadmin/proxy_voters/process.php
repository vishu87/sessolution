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
$folder = "proxy_voters";

//add analyst
if($_GET["cat"] == 1){
	
	$name = mysql_real_escape_string($_POST["name"]);
	$email = mysql_real_escape_string($_POST["email"]);
	$mobile = mysql_real_escape_string($_POST["mobile"]);
	$location = mysql_real_escape_string($_POST["location"]);
	
	

	$add_date =strtotime("now");
	$query = "INSERT into proxy_voters (name, mobile, email, location, add_date) values ('$name','$mobile','$email','$location','$add_date') ";
	if(mysql_query($query)) header("Location: ../".$folder.".php?cat=2&success=1");
	else header("Location: ../".$folder.".php?cat=2&success=0");
	

	
}

if($_GET["cat"] == 2){
	
	$id = mysql_real_escape_string($_GET["aid"]);
	$name = mysql_real_escape_string($_POST["name"]);
	$email = mysql_real_escape_string($_POST["email"]);
	$mobile = mysql_real_escape_string($_POST["mobile"]);
	$location = mysql_real_escape_string($_POST["location"]);
	
	$query = "UPDATE proxy_voters set name='$name', email='$email', mobile='$mobile', location='$location' where vid='$id' ";

	if(mysql_query($query)) header("Location: ../".$folder.".php?cat=3&aid=".$id."&success=1");
	else header("Location: ../".$folder.".php?cat=3&aid=".$id."&success=0");
	

	
}

?>