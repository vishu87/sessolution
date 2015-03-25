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
$folder = "settings";
$user_id = $_SESSION["MEM_ID"];
//add analyst
if($_GET["cat"] == 1){
	
	$proxy_module = mysql_real_escape_string($_POST["proxy_module"]);
	//$voting_span = mysql_real_escape_string($_POST["voting_span"]);
	$voting_span = 0;


	if(mysql_query("UPDATE users set proxy_module = '$proxy_module', voting_span = '$voting_span' where id='$user_id' ")){
		header("Location: ../".$folder.".php?cat=1&success=1");
		mysql_query("INSERT into user_activity (user_id, activity_id, details) values ('$_SESSION[MEM_ID]','22','$proxy_module')");
	} else {
		header("Location: ../".$folder.".php?cat=1&success=0");
	}

	die();
	
}


?>