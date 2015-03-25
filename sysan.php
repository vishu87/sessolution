<?php
	//Start session
	
	require_once('config.php');
	//Check whether the session variable SESS_MEMBER_ID is present or not
	if(!isset($_SESSION['SESS_MEMBER_ID']) || (trim($_SESSION['SESS_MEMBER_ID']) == '' || $_SESSION["PRIV"]!=2 )) {
		header("location: ".STRSITE."access-denied.php");
		exit();
	}
?>