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
$folder = "my_profile";

if($_GET["cat"] == 1){
	
	$id = $_SESSION["MEM_ID"];

	$address = mysql_real_escape_string($_POST["address"]);
	$user_admin_name = mysql_real_escape_string($_POST["user_admin_name"]);
	$mobile = mysql_real_escape_string($_POST["mobile"]);
	$IT_name = mysql_real_escape_string($_POST["IT_name"]);
	$IT_email = mysql_real_escape_string($_POST["IT_email"]);
	$IT_contact = mysql_real_escape_string($_POST["IT_contact"]);
	$def_deadline_vote = mysql_real_escape_string($_POST["def_deadline_vote"]);
	$self_portfolio = mysql_real_escape_string($_POST["self_portfolio"]);

	$query_prev = mysql_query("SELECT user_admin_name,address, mobile, IT_name, IT_contact, IT_email,def_deadline_vote from users where id='$id' ");
	$row = mysql_fetch_array($query_prev);
	$string = $row["user_admin_name"].'/'.$row["address"].'/'.$row["mobile"].'/'.$row["IT_name"].'/'.$row["IT_contact"].'/'.$row["IT_email"].'/'.$row["def_deadline_vote"];

	$query = "UPDATE users set user_admin_name='$user_admin_name', address='$address', mobile='$mobile', IT_name='$IT_name', IT_email='$IT_email', IT_contact='$IT_contact',def_deadline_vote = '$def_deadline_vote', self_portfolio = '$self_portfolio' where id='$id' ";

	if(mysql_query($query)){
		$_SESSION["self_portfolio"] = $self_portfolio;
		header("Location: ../".$folder.".php?cat=1&success=1");
		mysql_query("INSERT into user_activity (user_id, activity_id, details) values ('$_SESSION[MEM_ID]','16','$string')");
	}
	else header("Location: ../".$folder.".php?cat=1&success=0");
}

if($_GET["cat"] == 2){
	
	$id = $_SESSION["MEM_ID"];

	$com_string = $_POST["com_string"];
	$coms = explode('/', $com_string);
	$com_name = addslashes($coms[0]);
	$com_bse_code = $coms[1];

	$sql = mysql_query("SELECT com_id from companies where com_name='$com_name' and com_bse_code='$com_bse_code' limit 1 ");
	if(mysql_num_rows($sql) > 0){
		$com_det = mysql_fetch_array($sql);
		$com_id = $com_det["com_id"];
	}else {
		header("Location: ../".$folder.".php?cat=2&success=0");
		die();
	}

	$type = mysql_real_escape_string($_POST["type"]);
	$timenow = strtotime("now");

	$query = "INSERT into subscription_request (com_id, report_type, user_id, add_date ) values ('$com_id','$type','$_SESSION[MEM_ID]','$timenow') ";
	mysql_query("INSERT into user_activity (user_id, activity_id,details) values ('$_SESSION[MEM_ID]','14','$com_id')");

	if(mysql_query($query)) header("Location: ../".$folder.".php?cat=2&success=1");
	else header("Location: ../".$folder.".php?cat=2&success=0");
}


?>