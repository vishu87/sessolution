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


$request_id = $_POST["request_id"];


if(!isset($_POST["request_id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 1) header("Location: ".STRSITE."access-denied.php");


if(mysql_query("UPDATE proxies set voter_id='0', appoint_date='' where id='$request_id'")){
	echo '<div class="alert alert-success">
			<strong>Success!</strong> The voter has been unassigned.
		</div>';
} else {
	echo 'fail';
}

?>