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

	$ar_fields = array("even","com_name","meeting_date","meeting_type","ccm_type","meeting_time","meeting_venue","record_date","evoting_start","evoting_end","evoting_plateform","even" );

	$file = fopen($file_path, 'r');
	$count =0;
	echo '<table cellpadding="5" cellspacing="0" ><tr style="background:#4b8df8"><th>SN</th><th>Name</th><th>BSE Code</th><th>Meeting Date</th><th>Meeting Date</th><th>Success</th><tr>';
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
		 	$table = 'proxy_ad'; //name of the table
		 	
		 	$query = 'SELECT com_id, com_bse_code from companies where com_name = "'.$update["com_name"].'" ';
		 	//echo $query;
			$query_com_check = mysql_query($query);
			$com_bse_code =0;
			if(mysql_num_rows($query_com_check) > 0 ){
				$row_com = mysql_fetch_array($query_com_check);
				$com_id = $row_com["com_id"];
				$com_bse_code = $row_com["com_bse_code"];

				$date = explode('/', $update["meeting_date"]);

				if(sizeof($date) == 3 && strlen($date[0])== 2 && strlen($date[1])== 2 && strlen($date[2])== 4 ){

					$met_date = $date[0].'-'.$date[1].'-'.$date[2];
					$meeting_on = strtotime($met_date);
					$year = $date[2];

					if(preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $update["record_date"])){
						$date = explode('/', $update["record_date"]);
						$date_act = $date[0].'-'.$date[1].'-'.$date[2];
						$record_date = strtotime($date_act);
					} else $record_date = '';

					if(preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $update["evoting_start"])){
						$date = explode('/', $update["evoting_start"]);
						$date_act = $date[0].'-'.$date[1].'-'.$date[2];
						$evoting_start = strtotime($date_act);
					} else $evoting_start = '';

					if(preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $update["evoting_end"])){
						$date = explode('/', $update["evoting_end"]);
						$date_act = $date[0].'-'.$date[1].'-'.$date[2];
						$evoting_end = strtotime($date_act);
					} else $evoting_end = '';

					$check_timestamp = strtotime("01-04-".$year);

					if($meeting_on >= $check_timestamp){
						$update["year"] = $year;
					} else {
						$update["year"] =  ($year -1);
					}

					if(in_array($update["meeting_type"], $meeting_types_check)){

						switch ($update["meeting_type"]) {
							case 'AGM':
								$met_type = 1;
								break;
							
							case 'EGM':
								$met_type = 2;
								break;

							case 'PB':
								$met_type = 3;
								break;
							case 'CCM':
								$met_type = 4;
								break;
							case 'CCMC':
								$met_type = 5;
								break;
						}
						$sql_check_dup = mysql_query("SELECT id from proxy_ad where com_id='$com_id' and meeting_date='$meeting_on' and meeting_type='$met_type' ");
						if(mysql_num_rows($sql_check_dup) == 0){
							$sql = "INSERT into proxy_ad (com_id, meeting_date, meeting_time, meeting_venue, meeting_type, ccm_type, record_date, evoting_start, evoting_end, evoting_plateform, even, year,add_date ) VALUES ('$com_id','$meeting_on', '$update[meeting_time]', '$update[meeting_venue]','$met_type','$update[ccm_type]', '$record_date', '$evoting_start', '$evoting_end', '$update[evoting_plateform]', '$update[even]', '$update[year]','$update[add_date]')";
							$success = 'success';
							// echo $sql;
							mysql_query($sql);
							$insert_id = mysql_insert_id();

							$insert_rec_sql = mysql_query("SELECT user_id from user_voting_company where com_id='$com_id' and add_date <= '$meeting_on' ");
							while ($row_rec = mysql_fetch_array($insert_rec_sql)) {
								if($record_date != ''){
									if($record_date < strtotime("now")){
										mysql_query("INSERT into user_proxy_allow (user_id, report_id, add_date) values ('$row_rec[user_id]','$insert_id', '".strtotime("now")."') ");
									} else {
										mysql_query("INSERT into user_voting_proxy_reports (user_id, report_id, add_date) values ('$row_rec[user_id]','$insert_id', '".strtotime("now")."') ");
									}
								} else {
									mysql_query("INSERT into user_voting_proxy_reports (user_id, report_id, add_date) values ('$row_rec[user_id]','$insert_id', '".strtotime("now")."') ");
								}
							}

						} else{
							$success ="Duplicate Entry";

						}

					} else {
						$success = "Meeting type is not OK";
					}

				} else {
					$success = "Date has improper format/ not found";
				}


			} else {
				$success = "Company not found";
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