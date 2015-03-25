<?php session_start();
require_once('../../auth.php');


if(!isset($_POST["request_id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 0) header("Location: ".STRSITE."access-denied.php");

$request_id = $_POST["request_id"];
$proxy_module = $_POST["proxy_module"];


if(!isset($_POST["request_id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 0) header("Location: ".STRSITE."access-denied.php");


echo '<iframe src="upload_form/index.php?id='.$request_id.'&proxy_module='.$proxy_module.'" style="border:0; width:100%"; height:70px;></iframe>';

?>