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
require_once('../../classes/MemberClass.php');

$user_id = mysql_real_escape_string($_POST["user_id"]);
$old_package_id = mysql_real_escape_string($_POST["old_package_id"]);
$new_package_id = mysql_real_escape_string($_POST["new_package_id"]);

if(!isset($_POST["old_package_id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 1) header("Location: ".STRSITE."access-denied.php");

$query_sent = mysql_query("SELECT * from package where package_id='$old_package_id' LIMIT 1");
$row_sent_old = mysql_fetch_array($query_sent);
if(mysql_num_rows($query_sent) == 0) header("Location: ".STRSITE."access-denied.php");

$query_sent = mysql_query("SELECT * from package where package_id='$new_package_id' LIMIT 1");
$row_sent_new = mysql_fetch_array($query_sent);
if(mysql_num_rows($query_sent) == 0) header("Location: ".STRSITE."access-denied.php");

if($row_sent_old["package_type"] != $row_sent_new["package_type"]) header("Location: ".STRSITE."access-denied.php");
if($row_sent_old["package_year"] != $row_sent_new["package_year"]) header("Location: ".STRSITE."access-denied.php");

$query_check = mysql_query("SELECT * from users_package where user_id='$user_id' and package_id='$new_package_id' ");
if(mysql_num_rows($query_check) > 0){
	echo 'This Package has been already exits in your account.';
} else {
	mysql_query("DELETE FROM users_package where user_id='$user_id' and package_id='$old_package_id' ");
	mysql_query("INSERT into users_package (user_id,package_id) values ('$user_id','$new_package_id') ");

	$query_com = mysql_query("SELECT com_id from package_company where package_id='$new_package_id' ");
	while ($row_com = mysql_fetch_array($query_com)) {
		
		//UNSKIPPING ALL PA REPORT WHICH ARE SKIPPED WHEN NO USER WAS ASSIGNED
	    mysql_query("UPDATE proxy_ad set skipped_on = 0 where com_id='$row_com[com_id]' and meeting_date > '$today' ");
	}

	echo 'The package is successfully upgraded';
}

?>