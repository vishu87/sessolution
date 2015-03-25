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
$folder = "wishlist";
$user_id = $_SESSION["MEM_ID"];
//add analyst
if($_GET["cat"] == 1){
	
	$table = 'user_wishlist';
	$time = strtotime("now");
	$flag1 =0;
	$flag2=0;

if(sizeof($_POST["com_id_select"])>0) {
	foreach ($_POST["com_id_select"] as $com) {
		$sql_check = mysql_query("SELECT wish_id from $table WHERE user_id='$user_id' and com_id='$com' ");
		$num = mysql_num_rows($sql_check);
		if($num== 0){
			$sql ="INSERT into $table (user_id, com_id, add_date) VALUES ('$user_id','$com','$time') ";
			mysql_query($sql);
			$flag1 = 1;
		} else{
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
					$query_check = mysql_query("SELECT wish_id from user_wishlist where com_id = '$row[com_id]' and user_id='$user_id' ");
					if(mysql_num_rows($query_check) > 0){
						$success = 'Duplicate Entry';
					} else {
						if(mysql_query("INSERT into user_wishlist (user_id, com_id, add_date) values ('$user_id','$row[com_id]','$timenow') ")) {
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
//alerts

//add analyst
if($_GET["cat"] == 3){
	
	$table = 'user_wishlist';
	
	//$meeting_alert = mysql_real_escape_string($_POST["meeting_alert"]);
	//if($meeting_alert > 0){
	//	mysql_query("UPDATE users set meeting_alert ='$meeting_alert' where id='$user_id' ");
	//}
	
	$ar_fields = array("meeting_alert","meeting_schedule","report_upload","notice","annual_report","meeting_outcome","meeting_minutes");

	foreach ($_POST["com_id"] as $com_id) {
		foreach ($ar_fields as $ar) {
			if($_POST[$ar.'_'.$com_id] == 'on'){
				mysql_query("UPDATE user_wishlist set $ar = '1' where user_id='$user_id' and com_id='$com_id' ");
			} else {
				mysql_query("UPDATE user_wishlist set $ar = '0' where user_id='$user_id' and com_id='$com_id' ");
			}
			
		}
	}
	header("Location: ../".$folder.".php?cat=3&success=1");
	
	die();
	
}

?>