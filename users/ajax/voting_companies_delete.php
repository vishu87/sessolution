<?php session_start();
require_once('../../auth.php');

$user = $_SESSION["MEM_ID"];
$_POST["id"] = mysql_real_escape_string($_POST["id"]);

if(!isset($_POST["id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 0) header("Location: ".STRSITE."access-denied.php");

$check = mysql_query("SELECT id from users where created_by_prim='$_SESSION[MEM_ID]' and id = '$_POST[id]' ");
if(mysql_num_rows($check) == 0) die('You are not authorized for this.' );
$flag_check = 0;
$string ='';
$com_id = mysql_real_escape_string($_POST["company_id"]);


mysql_query("DELETE from voting_access where user_id='$_POST[id]' and com_id='$com_id' ");
echo 'success';


?>
