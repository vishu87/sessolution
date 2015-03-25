<?php session_start();
require_once('../../auth.php');
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
if( $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 0) header("Location: ".STRSITE."access-denied.php");

$folder = "voting_records";
$user_id = $_SESSION["MEM_ID"];

//add company
if($_GET["cat"] == 1){
	
	$table = 'user_voting_company';
	$time = strtotime("now");
	$flag1 =0;
	$flag2=0;
	$today = strtotime("today");

	$com_string = $_POST["com_string"];
	$coms = explode('/', $com_string);
	$com_name = addslashes($coms[0]);
	$com_bse_code = $coms[1];

	$sql = mysql_query("SELECT com_id from companies where com_name='$com_name' and com_bse_code='$com_bse_code' limit 1 ");
	if(mysql_num_rows($sql) > 0){
		$com_det = mysql_fetch_array($sql);
		$com = $com_det["com_id"];
	}else {
		header("Location: ../".$folder.".php?cat=1&success=3");
		die();
	}



			$sql_check = mysql_query("SELECT id from $table WHERE user_id='$user_id' and com_id='$com' ");
			$num = mysql_num_rows($sql_check);
			if($num== 0){
				$sql ="INSERT into $table (user_id, com_id, add_date) VALUES ('$user_id','$com','$time') ";
				mysql_query("INSERT into user_activity (user_id, activity_id,details) values ('$_SESSION[MEM_ID]','29','$com')" );
				if(mysql_query($sql)){
					$query = mysql_query("SELECT id, record_date from proxy_ad where com_id='$com' and meeting_date >= $today ");
					while ($row = mysql_fetch_array($query)) {
						$check = mysql_query("SELECT id from user_voting_proxy_reports where user_id='$user_id' and report_id='$row[id]' ");
						if(mysql_num_rows($check) == 0){

							if($row["record_date"] >= $today || $row["record_date"] == 0 || $row["record_date"] == ''){
								mysql_query("INSERT into user_voting_proxy_reports (user_id,report_id,add_date) VALUES ('$user_id','$row[id]','$time') ");
							} else {
								mysql_query("INSERT into user_proxy_allow (user_id,report_id,add_date) VALUES ('$user_id','$row[id]','$time') ");
							}
						}
					}
				}
				$flag1 = 1;
			} else {
				$flag2 = 1;
			}
		

	
	if($flag1 == 1 ){ // all companies added successfully
		header("Location: ../".$folder.".php?cat=1&success=1");
	}
	elseif($flag2 == 1){ // no companies added successfully
		header("Location: ../".$folder.".php?cat=1&success=0");
	}
	else {
		header("Location: ../".$folder.".php?cat=1&success=1");
	}
	
	die();
	
}

//file upload
if($_GET["cat"] == 2){	
	set_time_limit(1200);
		$today = strtotime("today");
		$id = strtotime("now");
		$filename = $_FILES["attach_file"]["name"];
		$exten = explode('.',$filename);
		$last_val = sizeof($exten) - 1;
		$ext=$exten[$last_val];
		$temp_filename=$id.'.'.$ext;	
		move_uploaded_file($_FILES["attach_file"]["tmp_name"],"../../Temp/".$temp_filename);

		$file_path = '../../Temp/'.$temp_filename;
		$timenow = strtotime("now");
	
		$file = fopen($file_path, 'r');
		$count =0;
		echo '<table cellpadding="5" cellspacing="0" ><tr style="background:#4b8df8"><th>SN</th><th>';
		echo ($_POST["data_type"] == 1)?'BSE Code':'ISIN';
		echo '</th><th>Name</th><th>Success</th><tr>';
		while (($line = fgetcsv($file)) !== FALSE) {
			//print_r($line);
			//echo $count;
			$com_name = '';
		 	if($count >0) {
		  	
			 	$update["var"] = mysql_real_escape_string($line[0]);
			 	

			 	$update["add_date"] = strtotime("now");

			 	//now building the query
			 	$table = 'proxy_ad'; //name of the table
			 	if($_POST["data_type"] != 1){
			 		$query_com_check = mysql_query("SELECT com_id, com_name from companies where com_isin='$update[var]' ");
			 	} else {
			 		$query_com_check = mysql_query("SELECT com_id, com_name from companies where com_bse_code='$update[var]' ");
			 	}
				
				if(mysql_num_rows($query_com_check) > 0 ){
					$row = mysql_fetch_array($query_com_check);
					$com_name = $row["com_name"];
					$query_check = mysql_query("SELECT id from user_voting_company where com_id = '$row[com_id]' and user_id='$user_id' ");
					if(mysql_num_rows($query_check) > 0){
						$success = 'Duplicate Entry';
					} else {
						if(mysql_query("INSERT into user_voting_company (user_id, com_id, add_date) values ('$user_id','$row[com_id]','$timenow') ")) {
							
							mysql_query("INSERT into user_activity (user_id, activity_id,details) values ('$_SESSION[MEM_ID]','29','$row[com_id]')" );

							$query_in = mysql_query("SELECT id, record_date from proxy_ad where com_id='$row[com_id]' and meeting_date >= $today ");
							while ($row_in = mysql_fetch_array($query_in)) {
								$check_in = mysql_query("SELECT id from user_voting_proxy_reports where user_id='$user_id' and report_id='$row_in[id]' ");
								if(mysql_num_rows($check_in) == 0){

									if($row_in["record_date"] >= $today || $row_in["record_date"] == 0 || $row_in["record_date"] == ''){
										mysql_query("INSERT into user_voting_proxy_reports (user_id,report_id,add_date) VALUES ('$user_id','$row_in[id]','$time') ");
									} else {
										mysql_query("INSERT into user_proxy_allow (user_id,report_id,add_date) VALUES ('$user_id','$row_in[id]','$time') ");
									}
								}
							}
							$success = 'success';
						}
					}

				} else {
					$success = "Company not found";
				}
				echo '<tr style="background:#';
				echo ($success == 'success')?'35aa47':'e02222';
				echo '"><td>'.$count.'</td><td>'.$update["var"].'</td><td>'.$com_name.'</td><td>'.$success.'</td></tr>';
			}
		  $count++;
		}
		fclose($file);
		echo '</table>';
		die();
}

// alerts 
if($_GET["cat"] == 3){
	
	$table = 'user_voting_company';
	
	$ar_fields = array("meeting_alert","meeting_schedule","report_upload","notice","annual_report","meeting_outcome","meeting_minutes");

	foreach ($_POST["com_id"] as $com_id) {
		foreach ($ar_fields as $ar) {
			if($_POST[$ar.'_'.$com_id] == 'on'){
				mysql_query("UPDATE $table set $ar = '1' where user_id='$user_id' and com_id='$com_id' ");
			} else {
				mysql_query("UPDATE $table set $ar = '0' where user_id='$user_id' and com_id='$com_id' ");
			}
			
		}
	}
	mysql_query("INSERT into user_activity (user_id, activity_id) values ('$_SESSION[MEM_ID]','31')" );
	header("Location: ../".$folder.".php?cat=3&success=1");
	
	die();
	
}

if($_GET["cat"] == 4){	
	set_time_limit(1200);
		$today = strtotime("today");
		$id = strtotime("now");
		$filename = $_FILES["attach_file"]["name"];
		$exten = explode('.',$filename);
		$last_val = sizeof($exten) - 1;
		$ext=$exten[$last_val];
		$temp_filename=$id.'.'.$ext;	
		move_uploaded_file($_FILES["attach_file"]["tmp_name"],"../../Temp/".$temp_filename);

		$file_path = '../../Temp/'.$temp_filename;
		$timenow = strtotime("now");
	
		$file = fopen($file_path, 'r');
		$count =0;
		echo '<table cellpadding="5" cellspacing="0" ><tr style="background:#4b8df8"><th>SN</th><th>Company Name</th><th>Analyst Name</th><th>Analyst Email</th><th>Success</th><tr>';
		while (($line = fgetcsv($file)) !== FALSE) {
			$com_name = '';
		 	if($count > 0) {

		 		$isin = $line[1];
		 		$bse = $line[2];
		 		$pm_name = $line[3];
		 		$pm_email = $line[4];

			 	$update["add_date"] = strtotime("now");

			 	//now building the query
			 	$table = 'proxy_ad'; //name of the table
			 	
			 	if(!$bse){
			 		if($isin){
			 			
			 			$query_com_check = mysql_query("SELECT com_id, com_name from companies where com_isin='$isin' limit 1");
			 			$com_count = mysql_num_rows($query_com_check);
			 			
			 		} else $com_count = 0;
			 		
			 	}else if(!$isin)  {
			 		if($bse){
			 			$query_com_check = mysql_query("SELECT com_id, com_name from companies where com_bse_code='$bse' limit 1");
			 			$com_count = mysql_num_rows($query_com_check);
			 		} else $com_count = 0;
			 		
			 	}
				
				if($com_count > 0 ){
					$check_user = mysql_query("SELECT id from users where username = '$pm_email' and ( id = $user_id OR created_by_prim = $user_id  ) limit 1");
					if(mysql_num_rows($check_user) > 0){
							
							$row = mysql_fetch_array($query_com_check);
							$com_name = $row["com_name"];
							$com_id = $row["com_id"];

							$row_user = mysql_fetch_array($check_user);
							$pm_id = $row_user["id"];

						if($_POST["data_type"] == 1){
							

							$query_check = mysql_query("SELECT id, type from user_voting_company where com_id = '$com_id' and user_id='$pm_id' limit 1");
							if(mysql_num_rows($query_check) > 0){
								$row_entry = mysql_fetch_array($query_check);
								if($row_entry["type"] == 1){
									$success = 'Duplicate Entry';
								} else {
									mysql_query("UPDATE user_voting_company set type = 1 where id = $row_entry[id] ");
									$success = 'Updated Added by Admin';
								}
							} else {
								if(mysql_query("INSERT into user_voting_company (user_id, type , com_id, add_date) values ('$pm_id', '1', '$com_id', '$timenow') ")) {
									
									mysql_query("INSERT into user_activity (user_id, activity_id,details) values ('$pm_id','29','$com_id')" );

									$query_in = mysql_query("SELECT id, record_date from proxy_ad where com_id='$com_id' and meeting_date >= $today ");
									while ($row_in = mysql_fetch_array($query_in)) {
										$check_in = mysql_query("SELECT id from user_voting_proxy_reports where user_id='$pm_id' and report_id='$row_in[id]' ");
										if(mysql_num_rows($check_in) == 0){
											
											if($row_in["record_date"] >= $today || $row_in["record_date"] == 0 || $row_in["record_date"] == ''){
													mysql_query("INSERT into user_voting_proxy_reports (user_id,report_id,add_date) VALUES ('$pm_id','$row_in[id]','$time') ");
												} else {
													mysql_query("INSERT into user_proxy_allow (user_id,report_id,add_date) VALUES ('$pm_id','$row_in[id]','$time') ");
												}

										}
									}
									$success = 'Success';
								}
							}
						}

						if($_POST["data_type"] == 2){
							$sql_check = mysql_query("SELECT id, add_date from user_voting_company WHERE user_id='$pm_id' and com_id='$com_id' limit 1");
							$num = mysql_num_rows($sql_check);
							if($num != 0){
								$res = mysql_fetch_array($sql_check);
								$sql ="INSERT into user_voting_company_delete (user_id, com_id, add_date, delete_date) VALUES ('$pm_id','$com_id','$res[add_date]','$timenow') ";
								if(mysql_query($sql)){
									mysql_query("DELETE from user_voting_company where id='$res[id]' and user_id='$pm_id' ");
									
									mysql_query("INSERT into user_activity (user_id, activity_id,details) values ('$pm_id','30','$com_id')" );
									
									mysql_query("DELETE user_voting_proxy_reports from user_voting_proxy_reports inner join proxy_ad on user_voting_proxy_reports.report_id=proxy_ad.id where user_voting_proxy_reports.user_id='$pm_id' and proxy_ad.com_id='$com_id' and proxy_ad.meeting_date > $today ");
									
								}
								$success = 'Success';
							} else {
								$success = 'Company not present';
							}
						}


					} else {
						$success = "User Not Found";
					}
					

				} else {
					$success = "Company not found";
				}
				echo '<tr style="background:#';
				echo ($success == 'Success')?'35aa47':'e02222';
				echo '"><td>'.$count.'</td><td>'.$com_name.'</td><td>'.$pm_name.'</td><td>'.$pm_email.'</td><td>'.$success.'</td></tr>';
			}
		  $count++;
		}
		fclose($file);
		echo '</table>';
		die();
}

/*if($_GET["cat"] == 1){
	
	$table = 'user_voting_company';
	$time = strtotime("now");
	$flag1 =0;
	$flag2=0;
	$today = strtotime("today");

	if(sizeof($_POST["com_id_select"])>0) {
		foreach ($_POST["com_id_select"] as $com) {
			$sql_check = mysql_query("SELECT id from $table WHERE user_id='$user_id' and com_id='$com' ");
			$num = mysql_num_rows($sql_check);
			if($num== 0){
				

				$sql ="INSERT into $table (user_id, com_id, add_date) VALUES ('$user_id','$com','$time') ";
				if(mysql_query($sql)){
					$query = mysql_query("SELECT id from proxy_ad where com_id='$com' and meeting_date >= $today ");
					while ($row = mysql_fetch_array($query)) {
						$check = mysql_query("SELECT id from user_voting_proxy_reports where user_id='$user_id' and report_id='$row[id]' ");
						if(mysql_num_rows($check) == 0){
							mysql_query("INSERT into user_voting_proxy_reports (user_id,report_id,add_date) VALUES ('$user_id','$row[id]','$time') ");
						}
					}
				}
				$flag1 = 1;
			} else {
				$flag2 = 1;
			}
		}
	}
	
	if($flag1 == 1 && $flag2 == 0){ // all companies added successfully
		header("Location: ../".$folder.".php?cat=1&success=1");
	}
	elseif($flag1 == 0 && $flag2 == 1){ // no companies added successfully
		header("Location: ../".$folder.".php?cat=1&success=0");
	}
	elseif($flag1 == 1 && $flag2 == 1){ // some companies added successfully
		header("Location: ../".$folder.".php?cat=1&success=2");
	}
	else {
		header("Location: ../".$folder.".php?cat=1&success=1");
	}
	
	die();
	
}
*/
?>