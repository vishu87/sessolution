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
$ar_fields_all = array("com_name","com_bse_code","com_bse_srcip","com_nse_sym","com_reuters","com_bloomberg","com_isin","com_address","com_telephone","com_website","com_sec_email","com_full_name");
$ar_fields_name = array("Company Name","BSE Code","BSE Srcip","NSE Symbol","Rueters","Bloomberg","ISIN","Address","Telephone","Website","Secretary email ID","Full Name");
echo '<form id="update_form" ><table class="table table-bordered table-hover">';
echo '<button type="button" onclick="check_edit_submit()" class="btn blue" style="margin-bottom:10px;"><i class="icon-ok"></i> Update</button>';
while($row = mysql_fetch_array($query_sent))  {
	$count = 0;
	echo "<input type='hidden' name='com_id' id='com_id' value='".$row["com_id"]."'>";
	foreach ($ar_fields_all as $ar) {
		if($ar == 'com_bse_code') {
			echo "<tr><td>".$ar_fields_name[$count]."</td><td>".$row[$ar]."</td></tr>";
		} else if($ar == 'com_name') {
			echo '<tr><td>'.$ar_fields_name[$count].'</td><td><input type="text" name="'.$ar.'" id="'.$ar.'" value="'.stripslashes($row[$ar]).'"></td></tr>';
		} else {
			echo '<tr><td>'.$ar_fields_name[$count].'</td><td><input type="text" name="'.$ar.'" id="'.$ar.'" value="'.$row[$ar].'"></td></tr>';
		}
		
		$count++;
	}
}
echo '</table>

</form>';

?>