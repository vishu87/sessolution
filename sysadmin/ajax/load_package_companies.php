<?php session_start();
require_once('../../sysauth.php');
require_once('../../config.php');
$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if(!$link) {
	die('Failed to connect to server: ' . mysql_error());
}
//Select database
$db = mysql_select_db(DB_DATABASE);
if(!$db) {
	die("Unable to select database");
}
$user = mysql_real_escape_string($_POST["user"]);


if(!isset($_POST["id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 1) header("Location: ".STRSITE."access-denied.php");

$sql = "SELECT companies.com_id,companies.com_name, companies.com_bse_code from companies inner join package_company on companies.com_id = package_company.com_id where package_company.package_id='$_POST[id]' ";

$query_sent = mysql_query($sql);

echo '<table class="table table-bordered table-hover tablesorter"><th>#</th><th>Company Name</th><th>BSE Code</th><th>Action</th>';
	$count = 1;
while($row = mysql_fetch_array($query_sent))  {

	echo '<tr id="tr_com_'.$row["com_id"].'">';

			echo "<td>".$count."</td><td>".$row["com_name"]."</td><td>".$row["com_bse_code"]."</td>";
			echo '<td> <a href="javascript:void(0)" onclick="delete_company('.$_POST["id"].','.$row["com_id"].')" class="btn red icn-only"><i class="icon-remove icon-white"></i></a></td>';
		
		echo '</tr>';
		$count++;
	}

echo '</table>';

?>