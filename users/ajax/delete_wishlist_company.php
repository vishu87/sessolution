<?php session_start();
require_once('../../auth.php');

if(!isset($_POST["id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 0) header("Location: ".STRSITE."access-denied.php");

$company_id = $_POST["id"];

if(mysql_query("DELETE from user_wishlist where com_id='$company_id' and user_id='$_SESSION[MEM_ID]'")){
	
	echo 'success';
}

?>