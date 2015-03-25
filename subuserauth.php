<?php
	//Start session
	require_once('config.php');
	$link = mysql_connect( DB_HOST, DB_USER , DB_PASSWORD );
	if(!$link) {
		die('Failed to connect to server: ' . mysql_error());
	}
	
	//Select database
	$db = mysql_select_db(DB_DATABASE);
	if(!$db) {
		die("Unable to select database");
	}
	//Check whether the session variable SESS_MEMBER_ID is present or not
	if(!isset($_SESSION['SESS_MEMBER_ID']) || (trim($_SESSION['SESS_MEMBER_ID']) == '' || $_SESSION["PRIV"]!=3 )) {
		header("location: ".STRSITE."access-denied.php");
		exit();
	} else {
		// check_token
		$check_sql = mysql_query("SELECT token from users where id='$_SESSION[MEM_ID]' ");
		$row_check = mysql_fetch_array($check_sql);

		if($row_check["token"] != $_SESSION["token"]){
			header("location: ".STRSITE."access-denied.php");
			exit();
		}
	}
	
	
?>