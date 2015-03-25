<?php session_start();

require_once('../../auth.php');

$request_id = $_POST["request_id"];


if(!isset($_POST["request_id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 0) header("Location: ".STRSITE."access-denied.php");


$sql = mysql_query("SELECT form from proxies where id='$request_id' ");
$res = mysql_fetch_array($sql);

if($res["form"] != '') echo 'success';
else echo 'fail';

?>