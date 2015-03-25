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

if(!isset($_POST["id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 1) header("Location: ".STRSITE."access-denied.php");

$query_sent = mysql_query("SELECT * from companies where com_id='$_POST[id]' LIMIT 1");
$ar_fields_all = array("com_name","com_bse_code","com_bse_srcip","com_nse_sym","com_reuters","com_bloomberg","com_isin","com_address","com_telephone","com_website","com_sec_email","com_full_name","add_date");
$ar_fields_name = array("Company Name","BSE Code","BSE Srcip","NSE Symbol","Rueters","Bloomberg","ISIN","Address","Telephone","Website","Secretary email ID","Full Name","Added on");
echo '<table class="table table-bordered table-hover">';
while($row = mysql_fetch_array($query_sent))  {
	$count = 0;
	foreach ($ar_fields_all as $ar) {
		if($ar == 'add_date') {
			echo "<tr><td>".$ar_fields_name[$count]."</td><td>".date("d-M-y",$row[$ar])."</td></tr>";
		} else {
			echo "<tr><td>".$ar_fields_name[$count]."</td><td>".stripcslashes($row[$ar])."</td></tr>";
		}
		
		$count++;
	}
}
echo '</table>';

?>