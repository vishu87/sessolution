<?php 

define('ROOT_PATH',dirname(__FILE__).'/');

include(ROOT_PATH.'../config.php');

$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if(!$link) {
	die('Failed to connect to server: ' . mysql_error());
}
//Select database
$db = mysql_select_db(DB_DATABASE);
if(!$db) {
	die("Unable to select database");
}
		$file_path = 'name_sec.csv';
		$file = fopen($file_path, 'r');
		$count =0;
		while (($line = fgetcsv($file)) !== FALSE) {
			//print_r($line);
			//echo $count;
		  if($count >0) {
		  	
			  	$isin = $line[0];
			  	$full_name = $line[1];
			  	$sec_id = $line[2];

			  	mysql_query("UPDATE companies set com_full_name = '$full_name', com_sec_email = '$sec_id' where com_isin = '$isin' ");
			 }	
		  $count++;
		}
?>