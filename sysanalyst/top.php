<?php require_once('../sysan.php');
$link = mysql_connect( DB_HOST, DB_USER , DB_PASSWORD );
	if(!$link) {
		die('Failed to connect to server: ' . mysql_error());
	}
	
	//Select database
	$db = mysql_select_db(DB_DATABASE);
	if(!$db) {
		die("Unable to select database");
	}
require_once('../config.php');
$inactive = 200000; // Set timeout period in seconds

if (isset($_SESSION['timeout'])) {
    $session_life = time() - $_SESSION['timeout'];
    if ($session_life > $inactive) {
        session_destroy();
        header("Location: ".STRSITE."access-denied.php");
    }
}
$_SESSION['timeout'] = time();
include('../classes/MemberClass.php');
$member = new Member();
?>