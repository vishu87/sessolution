<?php session_start();
require_once('../../auth.php');

require_once('../../classes/UserClass.php');
$user = new User($_SESSION["MEM_ID"]);

$report_id = mysql_real_escape_string($_POST["report_id"]);

if($user->parent != $_SESSION["MEM_ID"]) die("You are not authorized for this");

$vot_sql = mysql_query("SELECT users.name, users.email, users.id from user_voting_proxy_reports inner join users on user_voting_proxy_reports.user_id = users.id where report_id='".$report_id."' and (users.created_by_prim='".$user->parent."' OR users.id='".$user->parent."' ) ");
?>
<table class="table table-stripped">
	<thead>
		<th>#</th>
		<th>Name</th>
		<th>Email</th>
		<th>Status</th>
		<th>Reminder</th>
	</thead>
	<tbody>
<?php
$count = 1;
while ($row_sql = mysql_fetch_array($vot_sql)) {
   	echo '<tr><td>'.$count.'</td><td>'.$row_sql["name"].'</td><td>'.$row_sql["email"].'</td>';
   	$count++;

   	$flag_freeze = 0;
	$query1 = "SELECT freeze_on, unfreeze_on from user_proxy_ad where user_id='$row_sql[id]' and report_id='$report_id' and freeze_on != 0 order by id desc limit 1";
	$check_freeze = mysql_query($query1);
	if(mysql_num_rows($check_freeze) > 0){
		$row_freeze = mysql_fetch_array($check_freeze);
		if($row_freeze["freeze_on"] != 0 && $row_freeze["unfreeze_on"] == 0) $flag_freeze = 1;
	}
	?>
	<td> <?php echo ($flag_freeze == 1)?'<button class="btn green">Freezed</button>':'<button class="btn yellow">Unfreezed</button>'; ?></td>
	<td> <?php echo ($flag_freeze == 1)?'':'<button class="btn red" id="reminder_'.$row_sql["id"].'" onclick="send_reminder('.$row_sql["id"].','.$report_id.')">Send Reminder</button>'; ?></td>
</tr>
	<?php 
}
?>
</tbody>
</table>