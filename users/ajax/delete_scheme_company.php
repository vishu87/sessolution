<?php session_start();
require_once('../../auth.php');

$user = $_SESSION["MEM_ID"];

if(!isset($_POST["id"]) || !isset($_POST["com_id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 0) header("Location: ".STRSITE."access-denied.php");
$_POST["id"] = mysql_real_escape_string($_POST["id"]);
$_POST["com_id"] = mysql_real_escape_string($_POST["com_id"]);

$sql = "DELETE scheme_companies from scheme_companies inner join schemes on scheme_companies.scheme_id = schemes.id  where scheme_companies.id='$_POST[id]' and scheme_companies.com_id = '$_POST[com_id]' and schemes.user_id='$_SESSION[MEM_ID]' ";

 if(mysql_query($sql)){
   echo 'success';
 } else echo 'fail';
?>