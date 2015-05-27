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

	$meeting_types_check = array("A","E","P");

	set_time_limit(1200);
	$id = strtotime("now");
	$filename = $_FILES["attach_file"]["name"];
	$exten = explode('.',$filename);
	$last_val = sizeof($exten) - 1;
	$ext=$exten[$last_val];
	$temp_filename=$id.'.'.$ext;

	move_uploaded_file($_FILES["attach_file"]["tmp_name"],"../../Temp/".$temp_filename);

	$file_path = '../../Temp/'.$temp_filename;

	$ar_fields = array("even","id","com_name","meeting_type","com_isin","resolution_number","resolution_header", "resolution_details" );

	$file = fopen($file_path, 'r');
	$count =0;
	echo '<table cellpadding="5" cellspacing="0" ><tr style="background:#4b8df8"><th>SN</th><th>Name</th><th>BSE Code</th><th>Meeting Date</th><th>Meeting Date</th><th>Success</th><tr>';

	$old_even = 0;
	while (($line = fgetcsv($file)) !== FALSE) {

	  	if($count >0) {
	  	
		  	$update =array();
		 	for ($i=0; $i < sizeof($line); $i++) { 
		 		$update[$ar_fields[$i]] = mysql_real_escape_string($line[$i]);
		 	}

		 	$update["add_date"] = strtotime("now");
		 	$report_id = $update["id"];
		 	
		 	
		 	$query = 'SELECT companies.com_id, companies.com_isin from proxy_ad inner join companies on proxy_ad.com_id = companies.com_id where proxy_ad.id = $report_id limit 1';
		 	
			$query_com_check = mysql_query($query);
			$com_bse_code =0;
			if(mysql_num_rows($query_com_check) > 0 ){
				$row_fetch = mysql_fetch_array($query_com_check);
				if($row_fetch["com_isin"] != $update["com_isin"]){
					
					if($update["even"] == $old_even ){

					} else {
						mysql_query("UPDATE proxy_ad set even = '$update[even]' where id = $report_id ");
						$old_even = $update["even"];
					}

					//check resolution
					$check_res = mysql_query("SELECT id from voting where resolution_number = '$update[resolution_number]' and report_id = $report_id limit 1 ");
					if(mysql_num_rows($check_res) > 0){
						$success = "Resolution already present";
					} else {
						mysql_query("INSERT into voting (report_id, resolution_number, resolution_name) values ('$report_id','$update[resolution_number]','$update[resolution_name]') ");
						$success = 'success';
					}

				} else {
					$success = "ISIN does not match";
				}
			} else {
				$success = "Meeting Not Found";
			}
				
			echo '<tr style="background:#';
			echo ($success == 'success')?'35aa47':'e02222';
			echo '"><td>'.$count.'</td><td>'.stripcslashes(stripcslashes($update["com_name"])).'</td><td>'.$com_bse_code.'</td><td>'.$update["meeting_date"].'</td><td>'.$update["meeting_type"].'</td><td>'.$success.'</td></tr>';
		}
	  	$count++;
	}


	fclose($file);
	echo '</table>';
	die();
?>