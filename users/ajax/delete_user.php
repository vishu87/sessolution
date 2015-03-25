<?php session_start();
require_once('../../auth.php');

$user = $_SESSION["MEM_ID"];

if(!isset($_POST["user_id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 0) header("Location: ".STRSITE."access-denied.php");
$_POST["user_id"] = mysql_real_escape_string($_POST["user_id"]);
$check = mysql_query("SELECT id from users where created_by_prim='$_SESSION[MEM_ID]' and id = '$_POST[user_id]' ");
if(mysql_num_rows($check) == 0) die('You are not authorized for this.' );

 if(mysql_query("DELETE from users where id='$_POST[user_id]' and created_by_prim='$_SESSION[MEM_ID]' ")){
 	mysql_query("INSERT into removed_user (removed_user_id, removed_by) values ('$_POST[user_id]','$_SESSION[MEM_ID]') ");
 	mysql_query("INSERT into user_activity (user_id, activity_id, details) values ('$_SESSION[MEM_ID]','19','$_POST[user_id]')");
   echo 'success';
 } else echo 'fail';


?>