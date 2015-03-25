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

$folder = 'price';
$table = 'price';
/**************************************** Personal Information **********************************************/

	$sql_price = mysql_query("SELECT * from price ");
	while ($row_price = mysql_fetch_assoc($sql_price)) {
		$new_price = mysql_real_escape_string($_POST["price_".$row_price["id"]]);
		$strtime = strtotime("now");
		mysql_query("UPDATE price set price='$new_price', last_modi='$strtime' where id='$row_price[id]' ");

	}	
	header("Location: ../".$folder.".php?success=1");

?>