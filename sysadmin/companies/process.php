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
$folder = "companies";
if($_GET["cat"] == 1){
	set_time_limit(1200);
		$id = strtotime("now");
		$filename = $_FILES["attach_file"]["name"];
		$exten = explode('.',$filename);
		$last_val = sizeof($exten) - 1;
		$ext=$exten[$last_val];
		$temp_filename=$id.'.'.$ext;	
		move_uploaded_file($_FILES["attach_file"]["tmp_name"],"../../Temp/".$temp_filename);

		$file_path = '../../Temp/'.$temp_filename;
		//echo $file_path;

		$ar_fields = array("com_name","com_bse_code","com_bse_srcip","com_nse_sym","com_reuters","com_bloomberg","com_isin","com_address","com_telephone","com_website","com_sec_email","com_full_name");
		$ar_fields_all = array("com_name","com_bse_code","com_bse_srcip","com_nse_sym","com_reuters","com_bloomberg","com_isin","com_address","com_telephone","com_website","com_sec_email","com_full_name","add_date");
		//echo sizeof($ar_fields) == 25 right now + 1 password at the end (always);

		$file = fopen($file_path, 'r');
		$count =0;
		echo '<table cellpadding="5" cellspacing="0" ><tr style="background:#4b8df8"><th>SN</th><th>BSE Code</th><th>NSE Symbol</th><th>Name</th><th>Success</th><tr>';
		while (($line = fgetcsv($file)) !== FALSE) {
			//print_r($line);
			//echo $count;
		  if($count >0) {
		  	
			  	$update =array();
			 	for ($i=0; $i < sizeof($line); $i++) { 
			 		$update[$ar_fields[$i]] = mysql_real_escape_string($line[$i]);
			 	}
			 	$update["add_date"] = strtotime("now");

			 	//now building the query
			 	$table = 'companies'; //name of the table
			 	$query1 = "INSERT INTO $table(";
				$query2 = ") VALUES (";
				$query3 = ")";

				$flag_check = 0;

				
				if(($update["com_bse_code"] == '' || $update["com_bse_code"] == '0') && ($update["com_nse_sym"] == '' || $update["com_nse_sym"] == '0') ) {
					$success = "BSE/NSE Code Not Available";
				} else {

				$sql_check = "SELECT com_id from companies where com_bse_code='$update[com_bse_code]' AND com_nse_sym='$update[com_nse_sym]' ";
				$query_check = mysql_query($sql_check);

				$sum = mysql_num_rows($query_check);

				if($sum == 0){

				 	for ( $i = 0; $i < sizeof($ar_fields_all); $i++ ) {
							$name = $ar_fields_all[$i];

							if($i==0) $query1 = $query1.$name;
							else $query1 = $query1.', '.$name;

							if($i==0) $query2 = $query2."'".$update[$name]."'";
							else $query2 = $query2.", '".$update[$name]."'";
						}
						$query_final = $query1.$query2.$query3;
						echo $query_final;
						if(mysql_query($query_final)) {
							$success="success";
						} else { $success = "fail"; }
				} else { $success = "Duplicate entry."; }
			}
					$student_id = mysql_insert_id();
					echo '<tr style="background:#';
					echo ($success == 'success')?'35aa47':'e02222';
					echo '"><td>'.$count.'</td><td>'.$update["com_bse_code"].'</td><td>'.$update["com_nse_sym"].'</td><td>'.$update["com_name"].'</td><td>'.$success.'</td></tr>';
			}
		  $count++;
		}
		fclose($file);
		echo '</table>';
		die();
}
//Add a company
if($_GET["cat"] == 2)
{
	$table = 'companies';
	$ar_fields = array("name","bse_code","bse_srcip","nse_sym","reuters","bloomberg","isin","address","telephone","website","sec_email","full_name");
	$update = array();

	foreach($ar_fields as $ar){
		$update["com_".$ar] = mysql_real_escape_string($_POST[$ar]);
	}

	$update["add_date"] = strtotime("now");
	if($update["com_bse_code"] != ''){
	$sql = "select com_id from $table where com_bse_code = '".$update["com_bse_code"]."' ";
	$result = mysql_query($sql);
	if(mysql_num_rows($result) > 0) { header("Location: ../".$folder.".php?cat=2&success=4"); die();} }

	if($update["com_nse_sym"] != ''){
	$sql = "select com_id from $table where com_nse_sym = '".$update["com_nse_sym"]."' ";
	$result = mysql_query($sql);
	if(mysql_num_rows($result) > 0) { header("Location: ../".$folder.".php?cat=2&success=5"); die();} }

	if($update["com_isin"] != ''){
	$sql = "select com_id from $table where com_isin = '".$update["com_isin"]."' ";
	$result = mysql_query($sql);
	if(mysql_num_rows($result) > 0) { header("Location: ../".$folder.".php?cat=2&success=6"); die();} }
	
	$result = mysql_query("select * from $table");
	$field = mysql_num_fields($result);
	$query1 = "INSERT INTO $table(";
	$query2 = ")VALUES (";
	$query3 = ")";

	$result = mysql_query("select * from $table");
	$field = mysql_num_fields($result);

	for ( $i = 1; $i < $field; $i++ ) {
	$name = mysql_field_name( $result, $i );

		if($i==1) $query1 = $query1.$name;
		else $query1 = $query1.', '.$name;

		if($i==1) $query2 = $query2."'".$update[$name]."'";
		else $query2 = $query2.", '".$update[$name]."'";
	}

	if(mysql_query($query1.$query2.$query3)) header("Location: ../".$folder.".php?cat=2&success=3");
	else header("Location: ../".$folder.".php?cat=2&success=0");
}
?>