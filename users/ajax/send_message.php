<?php session_start();
require_once('../../auth.php');

$user = $_SESSION["MEM_ID"];

if(!isset($_POST["id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 0) header("Location: ".STRSITE."access-denied.php");

$query_user = mysql_query("SELECT name, email from users where id='$user' ");
$row_user = mysql_fetch_array($query_user);

if($_POST["id"] != 0){
	$query = mysql_query("SELECT name,email from analysts where an_id='$_POST[id]' ");
	$row = mysql_fetch_array($query);
	$name = $row["name"];
	$email = $row["email"];
} else {
	$name = 'Admin';
	$email = ADMIN_MAIL;
}
$subject = ($_POST["type"] == '1')?'Client Message':'Client Meeting Request';

	$body = '<html>
		<head>
			<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css">
		</head>
		<body style="font-family: \'Open Sans\', sans-serif; color:#444;"> 
			
			<p>
				Details of request:
			</p>
			
			<p>
				User Name: <b>'.$row_user["name"].'</b>
			</p>
			<p>
				User Email: <b>'.$password.'</b>
			</p>
			<p>
				Message: '.$_POST["message"].'
			</p>';

			if($_POST["ana_date"] != ''){
				$body .= '<p>
				Date: '.$_POST["ana_date"].'<br>
				Time: '.$_POST["ana_time"].'
				</p>';
			}

		$body .= '</body>
	</html>';
	$body = mysql_real_escape_string($body);
	if(mysql_query("INSERT into mail_queue (mailto, mailcc, mailbcc, mailbccmore, subject, content, at_folder, at_file) values ('$email','','','','$subject', '$body','','') ")) echo 'success';
	else echo 'Mail can not be sent right now. Please try again later';
?>