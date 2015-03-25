<?php session_start();
require_once('../../auth.php');

$folder = 'ch_password';
$table = 'users';
/**************************************** Personal Information **********************************************/
$query  = mysql_query("SELECT password from $table where id='$_SESSION[MEM_ID]' ");
$row  = mysql_fetch_array($query);
$old_password = $row["password"];

$old_p = mysql_real_escape_string($_POST["old_p"]);  
$new_p = mysql_real_escape_string($_POST["new_p"]); 
$re_new_p = mysql_real_escape_string($_POST["re_new_p"]);

$query_old_pass = mysql_query("SELECT details from user_activity where activity_id='23' and user_id='$_SESSION[MEM_ID]' order by timestamp desc  limit 2 ");
$pass_array= array();
while ($row = mysql_fetch_array($query_old_pass)) {
	array_push($pass_array, $row["details"]);
}
array_push($pass_array, md5($old_p));
//check for old password
if(strcmp(md5($old_p), $old_password) != 0 ){
	header("Location: ../".$folder.".php?success=2");
} elseif(!preg_match('/^(?=.*\d)(?=.*[A-Z])(?=.*[~!@#$%&_^*]).{8,}$/', $new_p)) {
	header("Location: ../".$folder.".php?success=4");
} elseif(in_array(md5($new_p), $pass_array)){
	header("Location: ../".$folder.".php?success=5");
} elseif(strcmp(md5($new_p), md5($re_new_p)) != 0) {
	header("Location: ../".$folder.".php?success=3");
} else {
	$new_p = md5($new_p);
	mysql_query("UPDATE users set password='$new_p' where id='$_SESSION[MEM_ID]' ");
	mysql_query("INSERT into user_activity (user_id, activity_id, details) values ('$_SESSION[MEM_ID]','23','$old_password')" );
	header("Location: ../".$folder.".php?success=1");
}
?>