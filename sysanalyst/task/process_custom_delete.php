<?php session_start();
require_once('../../sysan.php');
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

$rid = $_GET["rid"];
$com_id = $_GET["com_id"];
$custom_id = $_GET["custom_id"];

mysql_query("DELETE from customized_reports where custom_id='$custom_id' ");
mysql_query("UPDATE proxy_ad set completed_on = '' where id = '$rid' ");

$query = mysql_query("SELECT id from report_analyst where report_id='$rid' and rep_type='1' and type='3' ");
$row = mysql_fetch_array($query);

mysql_query("UPDATE report_analyst set completed_on = '' where id = '$row[id]' ");


header("Location: custom_reports.php?com_id=".$com_id."&success=2&id=".$rid);


/*
if($flag==0) header("Location: edit.php?cat=5&success=1&id=".$rid);
else header("Location: edit.php?cat=5&success=0&id=".$rid);*/
?>