<?php session_start();
require_once('../../auth.php');

if(!isset($_POST["id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 0) header("Location: ".STRSITE."access-denied.php");


$voter_id = $_POST["id"];

$user_id = $_SESSION["MEM_ID"];

$sql_check = mysql_query("SELECT vid from self_proxy_voters WHERE user_id='$user_id' and vid='$voter_id' ");
$num = mysql_num_rows($sql_check);
if($num != 0){
	
		mysql_query("DELETE from self_proxy_voters where vid='$voter_id' and user_id='$user_id' ");
		echo 'success';
		

} else {
	echo 'fail';
}


?>