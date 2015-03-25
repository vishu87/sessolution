<?php session_start();
require_once('../../auth.php');
require_once('../../classes/UserClass.php');

if(!isset($_POST["id"]) ) header("Location: ".STRSITE."access-denied.php");

$user_id = $_SESSION["MEM_ID"];
$report_id = mysql_real_escape_string($_POST["id"]);

//check on self proxy
$query_proxy = mysql_query("SELECT * from self_proxies where user_id='$user_id' and proxy_id = '$report_id' limit 1");
$num = mysql_num_rows($query_proxy);
if($num > 0){ // continue with self proxy module
	
	$proxy = mysql_fetch_array($query_proxy);
	if($proxy["form"] != ''){
		$proxy_sql = mysql_query("SELECT companies.com_name, proxy_ad.meeting_type, proxy_ad.meeting_date from proxy_ad inner join companies on proxy_ad.com_id = companies.com_id where proxy_ad.id='$report_id' limit 1");
		$row_proxy = mysql_fetch_array($proxy_sql);

		$voter_sql = mysql_query("SELECT name, email from self_proxy_voters where vid='$proxy[voter_id]' ");
		$row_voter = mysql_fetch_array($voter_sql);

		$user_sql = mysql_query("SELECT name,email,other_email from users where id='$_SESSION[MEM_ID]' ");
		$row_user = mysql_fetch_array($user_sql);

		$body .= '<p>Dear '.$row_voter["name"].'</p><p>The voting request generated on '.date("d M y",$proxy["form_upload_date"]).' by '.$row_user["name"].' for following meeting has been reset by the admin. Please do not vote for this meeting.</p><p>Meeting Details:<br>Company Name: <b>'.$row_proxy["com_name"].'</b> <br>Meeting Type: <b>'.$meeting_types[$row_proxy["meeting_type"]].'</b> <br>Meeting Date: <b>'.date("d M y",$row_proxy["meeting_date"]).'</b></p><hr>Please do not reply. This is an auto generated email.';
		$body = mysql_real_escape_string($body);
		$subject = 'Proxy Voting RESET Notification';

		if(mysql_query("INSERT into mail_queue (mailto, mailcc, mailbcc, mailbccmore, subject, content, at_folder, at_file) values ('$row_voter[email]','$row_user[email]','$row_user[other_email]','','$subject', '$body','','') ")) echo '';
		else echo 'Mail can not be sent right now. Please try again later';
		 
	}

	mysql_query("DELETE from self_proxies where user_id='$user_id' and proxy_id='$report_id' ");
	mysql_query("INSERT into user_activity (user_id, activity_id,report_id, report_type) values ('$_SESSION[MEM_ID]','4','$report_id','1')");
	echo 'success';
}
else { // check on ses voting module
	$query_proxy = mysql_query("SELECT * from proxies where user_id='$user_id' and proxy_id = '$report_id' limit 1");
	$num = mysql_num_rows($query_proxy);
	if($num > 0){ //continue with ses proxy module
		$proxy = mysql_fetch_array($query_proxy);
		if($proxy["form"] == '') {
			mysql_query("DELETE from proxies where user_id='$user_id' and proxy_id='$report_id' ");
			mysql_query("INSERT into user_activity (user_id, activity_id,report_id, report_type) values ('$_SESSION[MEM_ID]','4','$report_id','1')");
			echo 'success';
		} else {
			$query_user = mysql_query("SELECT name, email, mobile from users where id='$user_id' limit 1");
			$row_user = mysql_fetch_array($query_user);
			$body = 'User Details: '.$row_user["name"].' / '.$row_user["email"].' / '.$row_user["mobile"].'<br>';

			$query_proxy = mysql_query("SELECT companies.com_name, proxy_ad.meeting_type, proxy_ad.meeting_date from proxy_ad inner join companies on proxy_ad.com_id = companies.com_id where proxy_ad.id='$report_id' limit 1");
			$row_proxy = mysql_fetch_array($query_proxy);
			$body .= 'Proxy Details: '.$row_proxy["com_name"].' / '.$meeting_types[$row_proxy["meeting_type"]].' / '.date("d M y",$row_proxy["meeting_date"]).'<br>';
			//echo $body;

			$body = mysql_real_escape_string($body);
			$subject = 'Proxy Voting Reset Request';
            mysql_query("INSERT into user_activity (user_id, activity_id,report_id, report_type,details) values ('$_SESSION[MEM_ID]','4','$report_id','1','Sent to admin')");
			if(mysql_query("INSERT into mail_queue (mailto, mailcc, mailbcc, mailbccmore, subject, content, at_folder, at_file) values ('admin@sesgovernance.com','','','','$subject', '$body','','') ")) echo 'admincheck';
			else echo 'Mail can not be sent right now. Please try again later';
		}
	} else { // not found
		echo 'fail';
	}
}

?>