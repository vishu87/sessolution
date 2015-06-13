<?php session_start();
require_once('../../auth.php');
require_once('../../classes/UserClass.php');


if(!isset($_POST["scheme_id"])) header("Location: ".STRSITE."access-denied.php");
$count = 1;
$today = strtotime("today");
?>
<div>
	<h4 style="pad">Add Company</h4>
</div>
<div class="row form-body" style="margin: 0 0 20px 0; border-bottom:1px dashed #CCC">
	
	<div class="span3">
		Company Name<br><input type="text" class="typehead m-wrap" name="company_name" id="company_name">
	</div>
	<div class="span3">
		Shares Held<br><input type="text" name="shares_held" id="shares_held" class="m-wrap">
	</div>
	<div class="span3">
		As on (Date)<br><input type="text" class="datepicker_month m-wrap" name="upload_date_pop" id="upload_date_pop">
	</div>
	<div class="span3">
		<br>
		<button class="btn blue" id="btn_add" onclick="add_company_scheme(<?php echo $_POST["scheme_id"] ?>)">Add Company</button>
	</div>
</div>

<div id="scheme_comps">
	<?php
		$query = "SELECT scheme_companies.id, scheme_companies.com_id, companies.com_name, scheme_companies.shares_held, scheme_companies.held_date from scheme_companies join schemes on scheme_companies.scheme_id = schemes.id join companies on scheme_companies.com_id = companies.com_id  where scheme_companies.scheme_id = '$_POST[scheme_id]' AND schemes.user_id = '$_SESSION[MEM_ID]' and scheme_companies.held_date <= '$today' order by companies.com_name asc, scheme_companies.held_date desc ";
	?>
	<div style="margin:20px 0">
		Shareholding as on <input type="text" id="reload_date" class="m-wrap datepicker_month" value = "<?php echo date("d-m-Y",$today) ?>" style="margin-bottom:0">
		<input type="button" class="btn blue" value = "Submit" onclick="reload_scheme_comp(<?php echo $_POST["scheme_id"] ?>)">
	</div>
	<table class="table table-stripped tablesorter ">
		<thead>
			<tr>
				<th>SN</th>
				<th>Company Name</th>
				<th>Shares Held</th>
				<th>Shareholding as on</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody class="view_scheme_body">
		<?php
			$sql = mysql_query($query);
			$array_comp = array();
			while ($row = mysql_fetch_array($sql)) {
				if(!in_array($row["com_id"], $array_comp)){
				?>

					<tr id="<?php echo 'tr_pop_'.$row["id"] ?>">
						<td><?php echo $count ?></td>
						<td><?php echo $row["com_name"]; ?></td>
						<td><?php echo $row["shares_held"]; ?></td>
						<td><?php echo date("d-M-Y",$row["held_date"]); ?></td>
						<td>
							<a href="javascript:;" class="btn red" id="rm_comp_<?php echo $row["id"] ?>" onclick="remove_scheme_company(<?php echo $row["id"]?>,<?php echo $row["com_id"]; ?>)">Remove</a>
						</td>
					</tr>
				<?php
					array_push($array_comp, $row["com_id"]);
					$count++;
				}
			}
		?>
		</tbody>
	</table>
</div>
