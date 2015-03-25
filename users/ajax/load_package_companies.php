<?php session_start();
require_once('../../auth.php');

if(!isset($_POST["id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 0) header("Location: ".STRSITE."access-denied.php");

$sql = "SELECT companies.com_id,companies.com_name, companies.com_bse_code from companies inner join package_company on companies.com_id = package_company.com_id where package_company.package_id='$_POST[id]' ";

$query_sent = mysql_query($sql);

echo '<table class="table table-bordered table-hover tablesorter"><th>#</th><th>Company Name</th><th>BSE Code</th>';
	$count = 1;
while($row = mysql_fetch_array($query_sent))  {

	echo '<tr id="tr_com_'.$row["com_id"].'">';

			echo "<td>".$count."</td><td>".$row["com_name"]."</td><td>".$row["com_bse_code"]."</td>";
			
		
		echo '</tr>';
		$count++;
	}

echo '</table>';

?>