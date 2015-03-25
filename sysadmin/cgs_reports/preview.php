<?php session_start();
require_once('../../sysauth.php');
require_once('../../config.php');
error_reporting(0);
$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if(!$link) {
	die('Failed to connect to server: ' . mysql_error());
}
//Select database
$db = mysql_select_db(DB_DATABASE);
if(!$db) {
	die("Unable to select database");
}

$_GET["cid"] = base64_decode($_GET["cid"]);
$cgs_id= mysql_real_escape_string($_GET["cid"]);
$cgs_id= preg_replace('/[^(0-9)]*/','', $cgs_id);


$query_s = mysql_query("select report_upload from cgs where cgs_id='$cgs_id' ");
$row_s = mysql_fetch_array($query_s);
$file = dirname(__FILE__).'/../../cgs/'.$row_s["report_upload"];
$name = 'preview';

header('Content-type: application/pdf');
header('Content-Disposition: inline; filename="'.$name.'.pdf"');
header('Content-Transfer-Encoding: binary');
@readfile($file);
?>

