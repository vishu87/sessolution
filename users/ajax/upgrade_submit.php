<?php session_start();
require_once('../../auth.php');

require_once('../../classes/MemberClass.php');

$user_id = $_SESSION["MEM_ID"];
$old_package_id = mysql_real_escape_string($_POST["old_package_id"]);
$new_package_id = mysql_real_escape_string($_POST["new_package_id"]);
$timenow = strtotime("now");

if(!isset($_POST["old_package_id"])  ) header("Location: ".STRSITE."access-denied.php");

if(mysql_query("INSERT into subscription_request (new_package, old_package, user_id, add_date) values ('$new_package_id','$old_package_id','$user_id','$timenow') ")){
	echo 'Your upgradation request has been successfully sent.';
	$val = $new_package_id.' '.$old_package_id;
	mysql_query("INSERT into user_activity (user_id, activity_id, details) values ('$_SESSION[MEM_ID]','15','$val')");
} else {
	echo 'Please try again later.';
}

?>