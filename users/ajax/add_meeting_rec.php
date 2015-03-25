<?php session_start();
require_once('../../auth.php');
require_once('../../classes/UserClass.php');
if(!isset($_POST["report_id"]) ) header("Location: ".STRSITE."access-denied.php");

$report_id = mysql_real_escape_string($_POST["report_id"]);
// $val = explode(',', $_POST["val"]);
$val = $_POST["val"];

foreach ($val as $user) {
	$check_user_flag = 0;

	if($user == $_SESSION["MEM_ID"]) $check_user_flag = 1;
	else {
		$ck = mysql_query("SELECT id from users where created_by_prim='$_SESSION[MEM_ID]' and id='$user' ");
		if (mysql_num_rows($ck) > 0) {
			$check_user_flag = 1;
		}
	}
	
	$sql_name = mysql_query("SELECT name from users where id='$user' ");
	$row_name = mysql_fetch_array($sql_name);

	if($check_user_flag == 1){
		$check = mysql_query("SELECT id from user_voting_proxy_reports where user_id='$user' and report_id='$report_id' ");
		if(mysql_num_rows($check) > 0){
			echo 'This company is already exits in voting records of '.$row_name["name"].'<br>';
		} else {
			if(mysql_query("INSERT into user_voting_proxy_reports (user_id, report_id, add_date) values ('$user','$report_id','".strtotime("now")."') ")){
				
				mysql_query("INSERT into user_activity (user_id, activity_id,report_id,report_type,details) values ('$_SESSION[MEM_ID]','32','$report_id','1','$user')" );

				echo 'Successfully added in portfolio of '.$row_name["name"].'<br>';
			} else {
				echo 'Error in adding report';
			}
		}
	}

}
?>
<br>
<button onclick="reload()" class="btn">Reload Page</button>
<button onclick="hideall()" class="btn">Cancel</button>