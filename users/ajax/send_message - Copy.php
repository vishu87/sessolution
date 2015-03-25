<?php session_start();
require_once('../../auth.php');
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

$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPAuth   = true; 
	$mail->SMTPSecure = "tls"; 
	$mail->Host       = "email-smtp.us-east-1.amazonaws.com";
	$mail->Username   = "AKIAIQHEHWP7MT45P33Q";
	$mail->Password   = "Arz/nvdSHxbAX47JB3Xjjwb+q0Ocwys8Me6XEtRnFTDR";
	$mail->SetFrom('info@sesgovernance.com', 'SES Governance'); //from (verified email address)
	$mail->Subject = ($_POST["type"] == '1')?'Client Message':'Client Meeting Request'; 
	$mail->IsHTML(true);
	
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
	$mail->MsgHTML($body);
	$mail->AddAddress($email, $name); 
	if($mail->Send()) echo 'success';

?>