<?php session_start();
require_once('../../auth.php');

require_once('../../classes/UserClass.php');


if(!isset($_POST["user_id"]) || !isset($_POST["vote_id"]) ) header("Location: ".STRSITE."access-denied.php");

$user_id = mysql_real_escape_string($_POST["user_id"]);
$vote_id = mysql_real_escape_string($_POST["vote_id"]);
$user = new User($user_id);

if($user->parent != $_SESSION["MEM_ID"]) $ar["success"] = 0;

$query = mysql_query("SELECT vote, comment from user_voting where user_id ='$user_id' and vote_id = '$vote_id' limit 1");
$row = mysql_fetch_array($query);
$ar["vote"] = $row["vote"];
$ar["comment"] = $row["comment"];
$ar["success"] = 1;
echo json_encode($ar);

?>
