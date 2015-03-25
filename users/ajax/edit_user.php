<?php session_start();
require_once('../../auth.php');

$user = $_SESSION["MEM_ID"];

if(!isset($_POST["id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 0) header("Location: ".STRSITE."access-denied.php");

$check = mysql_query("SELECT id from users where created_by_prim='$_SESSION[MEM_ID]' and id = '$_POST[id]' ");
if(mysql_num_rows($check) == 0) die('You are not authorized for this.' );

$mobile = mysql_real_escape_string($_POST["mobile"]);
$name = mysql_real_escape_string($_POST["name"]);
$voting_access = mysql_real_escape_string($_POST["voting_access"]);
$_POST["id"] = mysql_real_escape_string($_POST["id"]);

 if(mysql_query("UPDATE users set mobile='$mobile', voting_access = '$voting_access', name='$name' where id='$_POST[id]' ")){
 	mysql_query("INSERT into user_activity (user_id, activity_id, details) values ('$_SESSION[MEM_ID]','18','$_POST[id]')");
   echo 'success';
 } else echo 'fail';


?>