<?php session_start();
require_once('../../auth.php');
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
$folder = "sub_users";

//add analyst
if($_GET["cat"] == 1){
	
	$name = mysql_real_escape_string($_POST["name"]);
	$email = mysql_real_escape_string($_POST["email"]);
	$mobile = mysql_real_escape_string($_POST["mobile"]);

	$username = 'ses1234'; // just for password

		$password = md5($username);
		$add_date =strtotime("now");
		$query = "INSERT into users (name, username, password, email, mobile, primary_user, created_by_prim, add_date) values ('$name','$email','$password','$email','$mobile','0','$_SESSION[MEM_ID]','$add_date') ";
		if(mysql_query($query)) header("Location: ../".$folder.".php?cat=1&success=1");
		else header("Location: ../".$folder.".php?cat=1&success=0");
	
	
}

if($_GET["cat"] == 2){
	
	$id = mysql_real_escape_string($_GET["aid"]);
	$name = mysql_real_escape_string($_POST["name"]);
	$email = mysql_real_escape_string($_POST["email"]);
	$active = mysql_real_escape_string($_POST["active"]);
	
	$query = "UPDATE analysts set name='$name', email='$email', active='$active' where an_id='$id' ";

	if(mysql_query($query)) header("Location: ../".$folder.".php?cat=3&aid=".$id."&success=1");
	else header("Location: ../".$folder.".php?cat=3&aid=".$id."&success=0");
	

	
}

?>