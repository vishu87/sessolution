<?php session_start();
require_once('../../auth.php');

require_once('../../classes/UserClass.php');
$user = new User($_SESSION["MEM_ID"]);

$com_id = mysql_real_escape_string($_POST["com_id"]);

if($user->parent != $_SESSION["MEM_ID"]) die("You are not authorized for this");

$vot_sql = mysql_query("SELECT users.name, users.email, users.id from user_voting_company inner join users on user_voting_company.user_id = users.id where user_voting_company.com_id='".$com_id."' and (users.created_by_prim='".$user->parent."' OR users.id='".$user->parent."' ) ");
?>
<table class="table table-stripped">
	<thead>
		<th>#</th>
		<th>Name</th>
		<th>Email</th>
	</thead>
	<tbody>
<?php
$count = 1;
while ($row_sql = mysql_fetch_array($vot_sql)) {
   	echo '<tr><td>'.$count.'</td><td>'.$row_sql["name"].'</td><td>'.$row_sql["email"].'</td>';
   	$count++;
   	?>
</tr>
	<?php 
}
?>
</tbody>
</table>