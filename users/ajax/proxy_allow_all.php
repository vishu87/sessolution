<?php session_start();
require_once('../../auth.php');
require_once('../../classes/UserClass.php');


$report_id = mysql_real_escape_string($_POST["report_id"]);
$parent = mysql_real_escape_string($_POST["parent_id"]);

?>
<div class="row-fluid">

<?php

$sql_user = mysql_query("SELECT users.id from user_proxy_allow inner join users on user_proxy_allow.user_id = users.id where (user_proxy_allow.user_id = '$parent' OR users.created_by_prim = '$parent') AND user_proxy_allow.report_id = '$report_id' and user_proxy_allow.status = 0 ");
while ($row_user = mysql_fetch_array($sql_user)) {

$user_id = $row_user["id"];

mysql_query("UPDATE user_proxy_allow set status = 1 where user_id = '$user_id' and report_id = '$report_id'  ");
	$check = mysql_query("SELECT id from user_voting_proxy_reports where user_id ='$user_id' and report_id = '$report_id' ");
	if(mysql_num_rows($check) == 0){
		if (mysql_query("INSERT into user_voting_proxy_reports (user_id, report_id , add_date) values ('$user_id','$report_id', '".strtotime("now")."') ")) echo 'success';
		else echo 'fail';
	} else {
		echo 'fail';
	}

}
?>
</div>