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


		if(mysql_query("DELETE from proxy_ad where id = '$_POST[id]' ")){
			if(mysql_query("DELETE from user_voting_proxy_reports where report_id= '$_POST[id]' "))	echo 'success';
		} else {
			echo 'fail';
		}
	
	



?>