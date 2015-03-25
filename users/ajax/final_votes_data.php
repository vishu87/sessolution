<?php session_start();
require_once('../../auth.php');
require_once('../../classes/UserClass.php');
$user = new User($_SESSION["MEM_ID"]);
require_once('../../classes/'.$user->voting_class.'.php');

if(!isset($_POST["report_id"]) ) header("Location: ".STRSITE."access-denied.php");

$voting = new SesVoting();
$report_id = $_POST["report_id"];
$parent_id = $_POST["parent_id"];

    $query_data = mysql_query("SELECT companies.com_name, proxy_ad.* from  proxy_ad inner join companies on proxy_ad.com_id = companies.com_id where proxy_ad.id='$report_id' ");
    $data = mysql_fetch_array($query_data);

?>
<div class="row-fluid">
	<div class="span6">
		Voting Details:
	</div>
	<div class="span6">
		<div class="pull-right">
			Date: <?php echo date("d-M-y") ?>
		</div>
	</div>
</div>
<table class="table table-bordered table-hover">
	<tr>
		<td>Company Name</td>
		<th><?php echo $data["com_name"] ?></th>
		<td>Meeting Type</td>
		<th><?php echo $meeting_types[$data["meeting_type"]] ?></th>
	</tr>
	<tr>
		<td>Meeting Date</td>
		<th><?php echo date("d-M-Y",$data["meeting_date"]) ?></th>
		<td>Meeting Details</td>
		<th><?php echo $data["meeting_time"].' at '.$data["meeting_venue"] ?></th>
	</tr>
</table>
<?php

$voting->user_votes_final($report_id,$user->parent,2);

?>

