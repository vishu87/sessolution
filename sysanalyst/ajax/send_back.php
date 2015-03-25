<?php session_start();
require_once('../../sysan.php');
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

$report_analyst_id = $_POST["id"];
$str = strtotime("now");
$sql = "SELECT * from report_analyst where id='$report_analyst_id' and an_id='$_SESSION[MEM_ID]' limit 1 ";

$fetch_sql= mysql_query($sql);

if(mysql_num_rows($fetch_sql) == 0 ){
	die('No such entry');
} else {
	$row = mysql_fetch_array($fetch_sql);
	
	switch ($row["type"]) {
		case 2:
			if(mysql_query("UPDATE report_analyst set completed_on='', previous_completion='$row[completed_on]' where type='1' and report_id='$row[report_id]' and rep_type = '$row[rep_type]' ")) echo 'success';
			else echo 'Database Error';
			break;
		
		case 3:
			if(mysql_query("UPDATE report_analyst set completed_on='', previous_completion='$row[completed_on]' where type='2' and report_id='$row[report_id]' and rep_type = '$row[rep_type]' ")) echo 'success';
			else echo 'Database Error';
			break;
	}
}

?>