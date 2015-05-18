<?php session_start();
require_once('../../auth.php');
set_time_limit(1200);

$folder = "evoting_info";

if($_GET["cat"] == 1){
	$nsdl_user_id = mysql_real_escape_string($_POST["nsdl_user_id"]);
	$nsdl_mf_id = mysql_real_escape_string($_POST["nsdl_mf_id"]);
	$nsdl_poa_id = mysql_real_escape_string($_POST["nsdl_poa_id"]);

	if(mysql_query("UPDATE evoting_info set nsdl_user_id = '$nsdl_user_id', nsdl_mf_id = '$nsdl_mf_id', nsdl_poa_id = '$nsdl_poa_id' where user_id = '$_SESSION[MEM_ID]' "))
	header("Location: ../".$folder.".php?cat=1&success=1");
}

?>