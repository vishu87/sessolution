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
	$email = clean($_POST['email']);

	$query_check = mysql_query("SELECT username from analysts where username='$email' ");
	$query_check1 = mysql_query("SELECT username from users where username='$email' ");

	if( mysql_num_rows($query_check)>0 ){
		$table = 'analysts';

	} elseif (mysql_num_rows($query_check1)>0) {
		$table = 'users';
	} else	{
		echo "fail";
		exit();
	}
	

	function rand_string( $length ) {

		$str = '';
		$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$str .= substr(str_shuffle($chars),0,1);

		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str .= substr(str_shuffle($chars),0,$length-2);

		$chars = "~!@#$%&_";
		$str .= substr(str_shuffle($chars),0,1);

		$str = str_shuffle($str);

		return $str;
	}

	$pass = rand_string(8);
	$pass_md5 = md5($pass);

	if(mysql_query("UPDATE $table set password='$pass_md5' where username='$email' ")){
		$subject = "Password Reset: SES Governance"; 
		$body = 'Dear '.$email.'<br>';
		$body .= '<p>Your password has been reset to <b>'.$pass.'</b> on SES Governance Portal</p>';
		$body .= '<hr><i>This is an auto generated email. Please do not reply.</i>';
		$body = mysql_real_escape_string($body);
		mysql_query("INSERT into mail_queue (mailto, mailcc, mailbcc, mailbccmore, subject, content, at_folder, at_file) values ('$email','','','','$subject', '$body','','') "); 
		echo 'success';
	} else {
		echo 'fail';
	}
	

?>