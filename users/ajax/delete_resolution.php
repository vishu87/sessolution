<?php session_start();
require_once('../../auth.php');
require_once('../../classes/UserClass.php');

if(!isset($_POST["id"]) ) header("Location: ".STRSITE."access-denied.php");

$user = new User($_SESSION["MEM_ID"]);
require_once('../../classes/'.$user->voting_class.'.php');

$voting = new SelfVoting();

$parent_id = $user->parent;
$report_id = $_POST["id"];
$argv = array();

foreach ($voting->resolution_remove_fields as $field) {
	array_push($argv, mysql_real_escape_string($_POST[$field]));
}

$voting->delete_resolution($argv,$report_id,$parent_id,$_SESSION["MEM_ID"]);


?>