<?php session_start();
require_once('../../auth.php');

require_once('../../classes/UserClass.php');
$user = new User($_SESSION["MEM_ID"]);
require_once('../../classes/'.$user->voting_class.'.php');

if(!isset($_POST["report_id"]) ) header("Location: ".STRSITE."access-denied.php");

$voting = new SelfVoting();
$report_id = $_POST["report_id"];
$parent_id = $_POST["parent_id"];
$voting->user_votes($report_id,$user->parent);


?>

