<?php session_start();
require_once('../../sysauth.php');
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
$folder = "analyst";

if($_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 1) header("Location: ".STRSITE."access-denied.php");


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


//add analyst
if($_GET["cat"] == 1){
	
	$name = mysql_real_escape_string($_POST["name"]);
	$email = mysql_real_escape_string($_POST["email"]);
	$contact = mysql_real_escape_string($_POST["contact"]);
	$details = mysql_real_escape_string($_POST["details"]);

	// preg_match_email

	if($email){
		$sql_check = mysql_query("SELECT an_id from analysts where email ='$email' OR username='$email' ");
		$sql_check2 = mysql_query("SELECT id from users where email ='$email' OR username='$email' ");
		$sql_check3 = mysql_query("SELECT id from admin where email ='$email' OR username='$email' ");
	
		if((mysql_num_rows($sql_check) + mysql_num_rows($sql_check2) +mysql_num_rows($sql_check3)) > 0){
			header("Location: ../".$folder.".php?cat=2&success=2"); // Duplicate email address
		} else {
			$add_date =strtotime("now");
			$username = $email;

			$pass= rand_string(8);
			$password = md5($pass);

			$query = "INSERT into analysts (name, contact, email, username, password, details, add_date,priv) values ('$name','$contact','$email','$username','$password','$details', '$add_date','2') ";
			mysql_query($query);

			send_mail($name,$username,$pass);

			header("Location: ../".$folder.".php?cat=2&success=1");
		}
	} else {
		header("Location: ../".$folder.".php?cat=2&success=3"); // Duplicate valid email
	}
}



if($_GET["cat"] == 2){
	
	$id = mysql_real_escape_string($_GET["aid"]);
	$name = mysql_real_escape_string($_POST["name"]);
	$contact = mysql_real_escape_string($_POST["contact"]);
	$details = mysql_real_escape_string($_POST["details"]);

	
	$query = "UPDATE analysts set name='$name', contact='$contact', details='$details' where an_id='$id'";

	if(mysql_query($query)) header("Location: ../".$folder.".php?cat=3&aid=".$id."&success=1");
	else header("Location: ../".$folder.".php?cat=3&aid=".$id."&success=0");
	

	
}

if($_GET["cat"] == 3){
	
	$id = mysql_real_escape_string($_GET["aid"]);

	$query = "UPDATE analysts set active='1' where an_id='$id'";

	if(mysql_query($query)) header("Location: ../".$folder.".php?cat=2");
	else header("Location: ../".$folder.".php?cat=2");
	

	
}

if($_GET["cat"] == 4){
	
	$id = mysql_real_escape_string($_GET["aid"]);

	$query = "UPDATE analysts set active='0' where an_id='$id'";

	if(mysql_query($query)) header("Location: ../".$folder.".php?cat=2");
	else header("Location: ../".$folder.".php?cat=2");
}


function send_mail($name,$username,$password){
	$subject = "Registration Details"; 
	
	$body = '<html>
		<head>
			<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css">
		</head>
		<body style="font-family: \'Open Sans\', sans-serif; color:#666;"> 
			<p>
				Dear '.$name.'
			</p>
			<p style="padding-left:20px;">
				You have successfully registered on SES Governance Portal. Following are your login details:
			</p>
			<p style="padding-left:20px;">
				Portal Address: http://portal.sesgovernance.com/
			</p>
			<p style="padding-left:20px;">
				Username: <b>'.$username.'</b>
			</p>
			<p style="padding-left:20px;">
				Password: <b>'.$password.'</b>
			</p>
		</body>
	</html>';	
	$body = mysql_real_escape_string($body);
	mysql_query("INSERT into mail_queue (mailto, mailcc, mailbcc, mailbccmore, subject, content, at_folder, at_file) values ('$username','','','','$subject', '$body','','') ");

}

?>