<?php session_start();
require_once('../../subuserauth.php');

require_once('../../classes/UserClass.php');

if(!isset($_POST["id"])) header("Location: ".STRSITE."access-denied.php");

$user = new User($_SESSION["MEM_ID"]);
require_once('../../classes/'.$user->voting_class.'.php');

$voting = new SelfVoting();
$report_id = $_POST["id"];
$parent_id = $user->parent;
$argv = array();

foreach ($voting->resolution_add_fields as $field) {
	array_push($argv, mysql_real_escape_string($_POST[$field]));
}

$voting->add_resolution($argv,$report_id,$parent_id,$_SESSION["MEM_ID"]);


?>