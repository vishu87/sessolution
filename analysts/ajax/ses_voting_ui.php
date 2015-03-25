<?php session_start();
require_once('../../subuserauth.php');

require_once('../../classes/UserClass.php');

if(!isset($_POST["id"])) header("Location: ".STRSITE."access-denied.php");

$user = new User($_SESSION["MEM_ID"]);
require_once('../../classes/'.$user->voting_class.'.php');

$voting = new SelfVoting();
$report_id = $_POST["id"];

$parent_id = $user->parent;

$voting->voting_ui($report_id,$parent_id);
?>
