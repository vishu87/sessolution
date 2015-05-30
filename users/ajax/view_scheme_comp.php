<?php session_start();
require_once('../../auth.php');
require_once('../../classes/UserClass.php');


if(!isset($_POST["scheme_id"])) header("Location: ".STRSITE."access-denied.php");
$count = 1;
$query = "SELECT scheme_companies.id, scheme_companies.com_id, companies.com_name, scheme_companies.shares_held from scheme_companies join schemes on scheme_companies.scheme_id = schemes.id join companies on scheme_companies.com_id = companies.com_id  where scheme_companies.scheme_id = '$_POST[scheme_id]' AND schemes.user_id = '$_SESSION[MEM_ID]' ";
?>
<div class="row form-body" style="margin: 0 0 20px 10px">
	<div class="span3">
		<h3>Add Company</h3>
	</div>
	<div class="span3">
		Company Name<br><input type="text" class="typehead m-wrap" name="company_name" id="company_name">
	</div>
	<div class="span3">
		Shares Held<br><input type="text" name="shares_held" id="shares_held" class="m-wrap">
	</div>
	<div class="span3">
		<br>
		<button class="btn blue" id="btn_add" onclick="add_company_scheme(<?php echo $_POST["scheme_id"] ?>)">Add Company</button>
	</div>
</div>
<table class="table table-stripped tablesorter ">
	<thead>
		<tr>
			<th>SN</th>
			<th>Company Name</th>
			<th>Shares Held</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody class="view_scheme_body">
	<?php
		$sql = mysql_query($query);
		while ($row = mysql_fetch_array($sql)) {
			?>
				<tr id="<?php echo 'tr_pop_'.$row["id"] ?>">
					<td><?php echo $count ?></td>
					<td><?php echo $row["com_name"]; ?></td>
					<td><?php echo $row["shares_held"]; ?></td>
					<td>
						<a href="javascript:;" class="btn red" id="rm_comp_<?php echo $row["id"] ?>" onclick="remove_scheme_company(<?php echo $row["id"]?>,<?php echo $row["com_id"]; ?>)">Remove</a>
					</td>
				</tr>
			<?php
			$count++;
		}
	?>
	</tbody>
</table>
