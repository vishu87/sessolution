<?php session_start();
require_once('../../subuserauth.php');

if( $_SESSION["MEM_ID"] == '') header("Location: ".STRSITE."access-denied.php");

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
				if(mysql_query($sql)){
					mysql_query("INSERT into user_activity (user_id, activity_id,details) values ('$_SESSION[MEM_ID]','29','$com')" );
					
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
		$id = strtotime("now");
		$filename = $_FILES["attach_file"]["name"];
		$exten = explode('.',$filename);
		$last_val = sizeof($exten) - 1;
		$ext=$exten[$last_val];
		$temp_filename=$id.'.'.$ext;	
		move_uploaded_file($_FILES["attach_file"]["tmp_name"],"../../Temp/".$temp_filename);

		$file_path = '../../Temp/'.$temp_filename;
		$timenow = strtotime("now");
		$today = strtotime("today");
	
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

							$query_in = mysql_query("SELECT id from proxy_ad where com_id='$row[com_id]' and meeting_date >= $today ");
							while ($row_in = mysql_fetch_array($query_in)) {
								$check_in = mysql_query("SELECT id from user_voting_proxy_reports where user_id='$user_id' and report_id='$row_in[id]' ");
								if(mysql_num_rows($check_in) == 0){
									mysql_query("INSERT into user_voting_proxy_reports (user_id,report_id,add_date) VALUES ('$user_id','$row_in[id]','$timenow') ");
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
	
	//$meeting_alert = mysql_real_escape_string($_POST["meeting_alert"]);
	//if($meeting_alert > 0){
	//	mysql_query("UPDATE users set meeting_alert ='$meeting_alert' where id='$user_id' ");
	//}
	
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

?>