<?php session_start();
require_once('../../sysauth.php');
require_once('../../config.php');
require_once('../../mail/sendmail.php');
require_once('../../classes/UserClass.php');

$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if(!$link) {
	die('Failed to connect to server: ' . mysql_error());
}
//Select database
$db = mysql_select_db(DB_DATABASE);
if(!$db) {
	die("Unable to select database");
}
$folder = "proxy_ad";
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

		$ar_fields = array( "com_name","meeting_date","meeting_type","meeting_time","meeting_venue","record_date","evoting_start","evoting_end","evoting_plateform" );
		//$ar_fields_all = array("com_name","com_bse_code","com_bse_srcip","com_nse_sym","com_reuters","com_bloomberg","com_isin","add_date");
		//echo sizeof($ar_fields) == 25 right now + 1 password at the end (always);

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

						if(in_array($update["meeting_type"], $meeting_types)){

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
							}
							$sql_check_dup = mysql_query("SELECT id from proxy_ad where com_id='$com_id' and meeting_date='$meeting_on' and meeting_type='$met_type' ");
							if(mysql_num_rows($sql_check_dup) == 0){
								$sql = "INSERT into proxy_ad (com_id, meeting_date, meeting_time, meeting_venue, meeting_type, record_date, evoting_start, evoting_end, evoting_plateform, year,add_date ) VALUES ('$com_id','$meeting_on', '$update[meeting_time]', '$update[meeting_venue]','$met_type', '$record_date', '$evoting_start', '$evoting_end', '$update[evoting_plateform]', $update[year],'$update[add_date]')";
								$success = 'success';
								// echo $sql;
								mysql_query($sql);
								$insert_id = mysql_insert_id();

								$insert_rec_sql = mysql_query("SELECT user_id from user_voting_company where com_id='$com_id' and add_date <= '$meeting_on' ");
								while ($row_rec = mysql_fetch_array($insert_rec_sql)) {
									mysql_query("INSERT into user_voting_proxy_reports (user_id, report_id, add_date) values ('$row_rec[user_id]','$insert_id', '".strtotime("now")."') ");
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
}


//Edit a report
if($_GET["cat"] == 2)
{
	$table = 'proxy_ad';
	$rid = $_GET["rid"];
	$ar_fields = array("meeting_date", "meeting_type","notice_link","teasor","annual_report","meeting_outcome","meeting_minutes","meeting_time","meeting_venue","record_date","evoting_start","evoting_end","evoting_plateform");
	$update = array();
	
	foreach($ar_fields as $ar){
		$update[$ar] = mysql_real_escape_string($_POST[$ar]);
	}

	$report = $_FILES["report"]["name"];
	$notice = $_FILES["notice"]["name"];
	$proxy_slip = $_FILES["proxy_slip"]["name"];

	if($report != '')	{

		$exten = explode('.',$report);
	    $last_val = sizeof($exten) - 1;
	    $ext=$exten[$last_val];
	    if(!in_array($ext, $file_types)) die('Please input a valid file');

		$report = substr(str_shuffle(strtotime("now")), 0, 10).$report;
		move_uploaded_file($_FILES["report"]["tmp_name"],"../../proxy_reports/".$report);
		$update["report"] = $report;
	}
	if($notice != ''){

		$exten = explode('.',$notice);
	    $last_val = sizeof($exten) - 1;
	    $ext=$exten[$last_val];
	    if(!in_array($ext, $file_types)) die('Please input a valid file');

		$notice = substr(str_shuffle(strtotime("now")), 0, 10).$notice;
		move_uploaded_file($_FILES["notice"]["tmp_name"],"../../proxy_notices/".$notice);
		$update["notice"] = $notice;
	}
	if($proxy_slip != ''){

		$exten = explode('.',$proxy_slip);
	    $last_val = sizeof($exten) - 1;
	    $ext=$exten[$last_val];
	    if(!in_array($ext, $file_types)) die('Please input a valid file');

		$proxy_slip = substr(str_shuffle(strtotime("now")), 0, 10).$proxy_slip;
		move_uploaded_file($_FILES["proxy_slip"]["tmp_name"],"../../proxy_slips/".$proxy_slip);
		$update["proxy_slip"] = $proxy_slip;
	}
		

	$update["modified"] = strtotime("now");
	$update["meeting_date"] = strtotime($update["meeting_date"]);
	if($update["record_date"]) $update["record_date"] = strtotime($update["record_date"]);
	if($update["evoting_start"]) $update["evoting_start"] = strtotime($update["evoting_start"]);
	if($update["evoting_end"]) $update["evoting_end"] = strtotime($update["evoting_end"]);

	$year = date("Y", $update["meeting_date"]);

	$check_timestamp = strtotime("01-04-".$year);

	if($update["meeting_date"] >= $check_timestamp){
		$update["year"] = $year;
	} else {
		$update["year"] =  ($year -1);
	}

	$check_changes_query = mysql_query("SELECT * from proxy_ad where id='$rid' limit 1 ");
	$check_ch = mysql_fetch_array($check_changes_query);

	if($check_ch["meeting_date"] < $update["meeting_date"]){
			$meeting_on = $update["meeting_date"];
			$com_id = $check_ch["com_id"];
			$insert_rec_sql = mysql_query("SELECT user_id from user_voting_company where com_id='$com_id' and add_date <= '$meeting_on' ");
			while ($row_rec = mysql_fetch_array($insert_rec_sql)) {
				$check_if = mysql_query("SELECT id from user_voting_proxy_reports where user_id='$row_rec[user_id]' and report_id='$rid' ");
				if(mysql_num_rows($check_if) == 0){
					mysql_query("INSERT into user_voting_proxy_reports (user_id, report_id, add_date) values ('$row_rec[user_id]','$rid', '".strtotime("now")."') ");
				}
			}
	}

	$check_array = array("notice","notice_link","annual_report","meeting_outcome","meeting_minutes");
	$change_fields = array();
	foreach ($check_array as $check) {
		if($update[$check] == $check_ch[$check]){
			//echo $check.' no change';
		} else {
			
			if($check == 'notice'){
				if($notice != '') {
					//echo $check.' change';
					array_push($change_fields, $check);
				} else {
					//echo $check.' no change';
				}
			} else{
				array_push($change_fields, $check);
			}
						
		}
	}

	

	$result = mysql_query("select * from $table");
	$field = mysql_num_fields($result);
	$flag=0;
	for ( $i = 1; $i < $field; $i++ ) {
		$name = mysql_field_name( $result, $i );
		if(isset($update[$name]))
		{
			if(!mysql_query("UPDATE $table SET $name = '$update[$name]' WHERE id='$rid'")) {
				$flag=1; break;
			}
		}

	}

	if(sizeof($change_fields) > 0) send_email($change_fields, $check_ch["com_id"],$rid);
	
	if($flag==0) header("Location: edit.php?cat=5&success=1&id=".$rid);
	else header("Location: edit.php?cat=5&success=0&id=".$rid);
}

//Add a vote
if($_GET["cat"] == 4)
{
	$report_id = $_GET["rid"];
	$resolution = $_POST["resolution"];
	$detail = mysql_real_escape_string($_POST["detail"]);
	$reason = ($_POST["reason"] != '')? implode(',', $_POST["reason"]):'';
	$date = strtotime("now");

	$query = "INSERT into voting (report_id, resolution,detail,reasons, modified) values ('$report_id', '$resolution', '$detail', '$reason', '$date') ";
	//echo $query1.$query2.$query3;
	if(mysql_query($query)) header("Location: ../".$folder.".php?cat=4&success=1&rid=".$report_id);
	else header("Location: ../".$folder.".php?cat=4&success=0&rid=".$report_id);
}

//Remove Report
if($_GET["cat"] == 5)
{
	$report_id = $_GET["rid"];
	

	

	$query = mysql_query("SELECT id from report_analyst where report_id='$report_id' and rep_type='1' and type='3' ");
	$row = mysql_fetch_array($query);

	mysql_query("UPDATE report_analyst set completed_on = '' where id = '$row[id]' ");

	mysql_query("UPDATE proxy_ad set completed_on = '' where id = '$report_id' ");

	$query = "UPDATE proxy_ad set report='' where id='$report_id' ";

	//echo $query1.$query2.$query3;
	if(mysql_query($query)) header("Location: edit.php?cat=5&success=2&id=".$report_id);
	else header("Location: edit.php?cat=5&success=0&id=".$report_id);
}

//Remove Notice
if($_GET["cat"] == 6)
{
	$report_id = $_GET["rid"];
	

	$query = "UPDATE proxy_ad set notice='' where id='$report_id' ";
	//echo $query1.$query2.$query3;
	if(mysql_query($query)) header("Location: edit.php?cat=5&success=2&id=".$report_id);
	else header("Location: edit.php?cat=5&success=0&id=".$report_id);
}
//Remove Notice
if($_GET["cat"] == 7){
	$report_id = $_GET["rid"];
	

	$query = "UPDATE proxy_ad set proxy_slip='' where id='$report_id' ";
	//echo $query1.$query2.$query3;
	if(mysql_query($query)) header("Location: edit.php?cat=5&success=2&id=".$report_id);
	else header("Location: edit.php?cat=5&success=0&id=".$report_id);
}

//add a report manually
if($_GET["cat"] == 8){	

		$update = array();

		$ar_fields = array("com_id","meeting_date","meeting_type","meeting_time","meeting_venue");
		foreach ($ar_fields as $key) {
			$update[$key] = mysql_real_escape_string($_POST[$key]);
		}
		$update["meeting_date"] = strtotime($update["meeting_date"]);

		$year = date("Y",$update["meeting_date"]);

		$check_timestamp = strtotime("01-04-".$year);

		if($update["meeting_date"] >= $check_timestamp){
			$update["year"] = $year;
		} else {
			$update["year"] =  ($year -1);
		}

		$update["add_date"] = strtotime("now");

		$sql = "INSERT into proxy_ad (com_id, meeting_date, meeting_time, meeting_venue, meeting_type, year,add_date ) VALUES ('$update[com_id]','$update[meeting_date]', '$update[meeting_time]', '$update[meeting_venue]','$update[meeting_type]', $update[year],'$update[add_date]')";

		if(mysql_query($sql)){
			$insert_id = mysql_insert_id();

			$insert_rec_sql = mysql_query("SELECT user_id from user_voting_company where com_id='$update[com_id]' and add_date <= '$update[meeting_date]' ");
			while ($row_rec = mysql_fetch_array($insert_rec_sql)) {
				mysql_query("INSERT into user_voting_proxy_reports (user_id, report_id, add_date) values ('$row_rec[user_id]','$insert_id', '".strtotime("now")."') ");
			}
			header("Location: ../".$folder.".php?success=1");

			die();
		} else {
			header("Location: ../".$folder.".php?success=0");
			
		}

		
}

?>