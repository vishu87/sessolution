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

$voter = $_POST["voter"];
$request_id = $_POST["request_id"];


if(!isset($_POST["request_id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 1) header("Location: ".STRSITE."access-denied.php");

$timenow = strtotime("now");

if(mysql_query("UPDATE proxies set voter_id='$voter', appoint_date='$timenow' where id='$request_id'")){
	$sql_voter = mysql_query("SELECT name from proxy_voters where vid='$voter' ");
	$name = mysql_fetch_array($sql_voter);
	echo $name["name"];

} else {
	echo 'fail';
}

?>