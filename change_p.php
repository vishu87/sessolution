<?php
	//Start session
	session_start();
	
	//Include database connection details
	require_once('config.php');

	//Array to store validation errors
	$errmsg_arr = array();
	
	//Validation error flag
	$errflag = false;
	
	//Connect to mysql server
	$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
	if(!$link) {
		die('Failed to connect to server: ' . mysql_error());
	}
	
	//Select database
	$db = mysql_select_db(DB_DATABASE);
	if(!$db) {
		die("Unable to select database");
	}
	
	$table = 'users';
	/**************************************** Personal Information **********************************************/
	$query  = mysql_query("SELECT password from $table where id='$_SESSION[MEM_ID]' ");
	$row  = mysql_fetch_array($query);
	$old_password = $row["password"];

	$old_p = mysql_real_escape_string($_POST["old_p"]);  
	$new_p = mysql_real_escape_string($_POST["new_p"]); 
	$re_new_p = mysql_real_escape_string($_POST["re_new_p"]);

	$query_old_pass = mysql_query("SELECT details from user_activity where activity_id='23' and user_id='$_SESSION[MEM_ID]' order by timestamp desc  limit 2 ");
	$pass_array= array();
	while ($row = mysql_fetch_array($query_old_pass)) {
		array_push($pass_array, $row["details"]);
	}
	array_push($pass_array, md5($old_p));
	//check for old password
	if(strcmp(md5($old_p), $old_password) != 0 ){
		echo 'old_p';
	} elseif(!preg_match('/^(?=.*\d)(?=.*[A-Z])(?=.*[~!@#$%&_^*]).{8,}$/', $new_p)) {
		echo 'new_p_format';
	} elseif(in_array(md5($new_p), $pass_array)){
		echo 'new_p';
	} elseif(strcmp(md5($new_p), md5($re_new_p)) != 0) {
		echo 're_new_p';
	} else {
		$new_p = md5($new_p);
		mysql_query("UPDATE users set password='$new_p' where id='$_SESSION[MEM_ID]' ");
		mysql_query("INSERT into user_activity (user_id, activity_id, details) values ('$_SESSION[MEM_ID]','23','$old_password')" );
		
		$token = substr(md5(strtotime("now").$member["username"]),0,200);

		if($member["priv"] == 2){
			mysql_query("UPDATE $table set token='$token' where an_id = '$_SESSION[MEM_ID]' ");
		} else {
			mysql_query("UPDATE $table set token='$token' where id = '$_SESSION[MEM_ID]' ");
		}
		$_SESSION["token"] = $token;


		if($_SESSION["PRIV"] == 0){
			echo 'users';
		} else {
			echo 'analysts';
		}
	}
	
?>