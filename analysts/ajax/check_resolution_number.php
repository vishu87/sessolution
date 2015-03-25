<?php session_start();
require_once('../../subuserauth.php');

require_once('../../classes/UserClass.php');

if(!isset($_POST["id"]) ) header("Location: ".STRSITE."access-denied.php");

$user = new User($_SESSION["MEM_ID"]);
$parent_id = $user->parent;
$report_id = $_POST["id"];

require_once('../../classes/'.$user->voting_class.'.php');

$voting = new SelfVoting();

$argv = array();
foreach ($voting->checks_add as $field) {
	array_push($argv, mysql_real_escape_string($_POST[$field]));
}

$voting->check_resolution($argv,$report_id,$parent_id);

?>
