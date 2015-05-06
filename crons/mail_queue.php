<?php 
define('ROOT_PATH',dirname(__FILE__).'/');

include(ROOT_PATH.'../config.php');
require_once(ROOT_PATH.'../mail/class.phpmailer.php');
error_reporting(E^ALL);
$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if(!$link) {
	die('Failed to connect to server: ' . mysql_error());
}
//Select database
$db = mysql_select_db(DB_DATABASE);
if(!$db) {
	die("Unable to select database");
}

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPAuth   = true; 
$mail->SMTPSecure = "tls"; 
$mail->Host       = "email-smtp.us-east-1.amazonaws.com";
$mail->Username   = "AKIAID7CBUQKCREFMSBQ";
$mail->Password   = "AvphiYmJWkhaQDvZsGEn6Jla1AFBmdVOqi4WnDf6wKdH";
$mail->SetFrom('info@sesgovernance.com', 'SES Governance'); //from (verified email address)
$mail->IsHTML(true);


$sql = mysql_query("SELECT * from mail_queue where solved = 0 ");
mysql_num_rows($sql);
while ($row = mysql_fetch_array($sql)) {

	$mail->Subject = $row["subject"]; 
	$body = $row["content"];
	$mail->MsgHTML($body);

	if($row["mailto"] != ''){
		$emails = explode(',', $row["mailto"]);
		foreach ($emails as $email) {
				$mail->AddAddress($email);
		}
	}

	if($row["mailcc"] != ''){
		$emails = explode(',', $row["mailcc"]);
		foreach ($emails as $email) {
			$mail->AddCC($email);
		}
	} 

	if($row["mailbcc"] != ''){
		$emails = explode(',', $row["mailbcc"]);
		foreach ($emails as $email) {
			$mail->AddBCC($email);
		}
	} 

	if($row["at_folder"] != ''){
		if($row["at_file"] != ''){
			$mail->addAttachment(ROOT_PATH.'../'.$row["at_folder"].'/'.$row["at_file"], substr($row["at_file"], 10));
		}
	}

	if($row["more_attach"] != ''){
		$more_attach = unserialize($more_attach);
		foreach ($more_attach as $attach) {
			$mail->addAttachment(ROOT_PATH.'../'.$attach["folder"].'/'.$attach["file"], substr($row["at_file"], 10));
		}
	}

	if($mail->Send()){
		mysql_query("UPDATE mail_queue set solved=1 where id='$row[id]' ");
	}
	
	$mail->ClearAllRecipients();
	$mail->ClearAttachments();
	$mail->ClearCustomHeaders();

}

?>


