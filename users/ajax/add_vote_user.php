<?php session_start();
require_once('../../auth.php');

require_once('../../classes/UserClass.php');

$user = new User($_SESSION["MEM_ID"]);

require_once('../../classes/'.$user->voting_class.'.php');

if(!isset($_POST["id"]) ) header("Location: ".STRSITE."access-denied.php");


$proxy_id = $_POST["id"];
$type = $_POST["type"];

$proxy_report = new PA($proxy_id);
$user->pa_total_comapnies_year($proxy_report->year);

$voting = new SesVoting();
$voting->voting_ui($proxy_id,$user->parent,$type);

 // if($proxy_report->coverage($user->companies_total_year)) {
      
 //   $voting = new SesVoting();
	// $voting->voting_ui($proxy_id,$user->parent);
      
 //  } else {
 
 //   $voting = new SelfVoting();
	// $voting->voting_ui($proxy_id,$user->parent);

 //  }
?>

