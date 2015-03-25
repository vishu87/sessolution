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

$report_id = $_POST["id"];
$str = strtotime("now");

$query = mysql_query("SELECT * from report_analyst where id='$report_id' ");
$row = mysql_fetch_array($query);

mysql_query("UPDATE report_analyst set completed_on='$str' where id='$report_id' ");

if($row["type"] == 3){
	switch ($row["rep_type"]) {
		case '1':
			$table = 'proxy_ad';
			$id = 'id';
			break;
		
		case '2':
			$id = 'cgs_id';
			$table = 'cgs';
			break;
		case '3':
			$id = 'res_id';
			$table = 'research';
			break;
	}
	mysql_query("UPDATE $table set completed_on='$str' where $id='$row[report_id]' ");
}

echo 'success';

?>