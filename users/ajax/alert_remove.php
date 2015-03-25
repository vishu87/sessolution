<?php session_start();
require_once('../../auth.php');

$id = mysql_real_escape_string($_POST["id"]);

$check = mysql_query("select id from meeting_alert where user_id = '$_SESSION[MEM_ID]' and id = '$id' ");
if(mysql_num_rows($check) == 0){
	echo 'fail';
} else {
	mysql_query("DELETE from meeting_alert where user_id = '$_SESSION[MEM_ID]' and id='$id' ");

}


?>
