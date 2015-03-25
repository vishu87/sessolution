<?php session_start();
require_once('../../auth.php');
require_once('../../classes/UserClass.php');

$user = $_SESSION["MEM_ID"];
$report_id = mysql_real_escape_string($_POST["report_id"]);
$users_ar = array();
array_push($users_ar, $user);
?>
<div class="row-fluid">
<h4>Click to delete</h4>
<?php

//fetching users of this user admin
$sql_user = mysql_query("SELECT users.id, users.name from user_voting_proxy_reports inner join users on user_voting_proxy_reports.user_id = users.id where user_voting_proxy_reports.report_id='$report_id' and (users.id='$user' || users.created_by_prim='$user')");
while ($row_user = mysql_fetch_array($sql_user)) {
  echo '<button class="btn red" id="btn_'.$row_user["id"].'" onclick="delete_meeting_rec('.$row_user["id"].','.$report_id.')">'.$row_user["name"].' <i class="icon-remove icon-white"></i></button> ';
}
?>
<button class="btn red" id="btn_all_del" onclick="delete_all_meeting_rec(<?php echo $report_id; ?>)">Delete From All <i class="icon-remove icon-white"></i></button>
</div><br>
<button onclick="reload()" class="btn">Reload Page</button>

