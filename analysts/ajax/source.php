<?php session_start();
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


$query = $_POST["query"];

$sql = mysql_query("select com_name, com_id, com_bse_code , com_bse_srcip from companies where com_name LIKE '%{$query}%' OR com_bse_code LIKE '%{$query}' OR com_bse_srcip LIKE '%{$query}' OR com_nse_sym LIKE '%{$query}' ");
$array = array();

while($row = mysql_fetch_assoc($sql))
{

$array[] = $row["com_name"].'/'.$row["com_bse_code"].'/'.$row["com_bse_srcip"].'/'.$row["com_nse_sym"];
}

echo json_encode($array);

?>