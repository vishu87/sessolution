<?php session_start();
require_once('../../auth.php');

$user = $_SESSION["MEM_ID"];

if(!isset($_POST["scheme_id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 0) header("Location: ".STRSITE."access-denied.php");
$_POST["scheme_id"] = mysql_real_escape_string($_POST["scheme_id"]);

 if(mysql_query("DELETE from schemes where id='$_POST[scheme_id]' and user_id='$_SESSION[MEM_ID]' ")){
   echo 'success';
 } else echo 'fail';
?>