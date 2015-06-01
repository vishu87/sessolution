<?php session_start();
require_once('../../auth.php');
require_once('../../config.php');
set_time_limit(1200);


$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if(!$link) {
	die('Failed to connect to server: ' . mysql_error());
}
//Select database
$db = mysql_select_db(DB_DATABASE);
if(!$db) {
	die("Unable to select database");
}
$folder = "schemes";


if($_GET["cat"] == 1){	
		$today = strtotime("today");
		$id = strtotime("now");
		$filename = $_FILES["csv_file"]["name"];
		$_POST["scheme_name"] = mysql_real_escape_string($_POST["scheme_name"]);
		$_POST["dp_id"] = mysql_real_escape_string($_POST["dp_id"]);
		$_POST["client_id"] = mysql_real_escape_string($_POST["client_id"]);

		if(mysql_query("INSERT into schemes (scheme_name, dp_id, client_id, user_id, depository, created_at) values ('$_POST[scheme_name]', '$_POST[dp_id]', '$_POST[client_id]', '$_SESSION[MEM_ID]', '$_POST[depository]', '$id') ")){
		header("Location: ../".$folder.".php?cat=1&success=1");
		$scheme_id = mysql_insert_id();
		if($filename != ''){
			$today = strtotime("today");
			$id = strtotime("now");
			$exten = explode('.',$filename);
			$last_val = sizeof($exten) - 1;
			$ext=$exten[$last_val];
			$temp_filename=$id.'.'.$ext;	
			move_uploaded_file($_FILES["csv_file"]["tmp_name"],"../../Temp/".$temp_filename);

			$file_path = '../../Temp/'.$temp_filename;
			$timenow = strtotime("now");
		
			$file = fopen($file_path, 'r');
			$count =0;
			echo '<table cellpadding="5" cellspacing="0" ><tr style="background:#4b8df8"><th>SN</th><th>Company Name</th><th>BSE</th><th>ISIN</th><th>Shares</th><th>Success</th><tr>';
			while (($line = fgetcsv($file)) !== FALSE) {
				$com_name = '';
			 	if($count > 0) {

		 		$isin = $line[0];
		 		$bse = $line[1];
		 		$shares = $line[2];

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
							
					$row = mysql_fetch_array($query_com_check);
					$com_name = $row["com_name"];
					$com_id = $row["com_id"];

					mysql_query("INSERT into scheme_companies (scheme_id, com_id, shares_held, add_date) values ('$scheme_id', '$com_id', '$shares', '$update[add_date]') ");
					$success = 'success';
				
				} else {
					$com_name = '';
					$success = "Company not found";
				}
				echo '<tr style="background:#';
				echo ($success == 'success')?'35aa47':'e02222';
				echo '"><td>'.$count.'</td><td>'.$com_name.'</td><td>'.$bse.'</td><td>'.$isin.'</td><td>'.$shares.'</td><td>'.$success.'</td></tr>';
			}
		  $count++;
		}
		fclose($file);
		echo '</table>';
		die();
			
		}
	}
}


if($_GET["cat"] == 2) {	

		$id = strtotime("now");
		$filename = $_FILES["attach_file"]["name"];

		if($filename != ''){
			$today = strtotime("today");
			$id = strtotime("now");
			$exten = explode('.',$filename);
			$last_val = sizeof($exten) - 1;
			$ext=$exten[$last_val];
			$temp_filename=$id.'.'.$ext;	
			move_uploaded_file($_FILES["attach_file"]["tmp_name"],"../../Temp/".$temp_filename);

			$file_path = '../../Temp/'.$temp_filename;
			$timenow = strtotime("now");
		
			$file = fopen($file_path, 'r');
			$count =0;
			echo '<table cellpadding="5" cellspacing="0" ><tr style="background:#4b8df8"><th>SN</th><th>Scheme Name</th><th>DP ID</th><th>Client ID</th><th>Company</th><th>BSE</th><th>ISIN</th><th>Shares</th><th>Success</th><tr>';
			while (($line = fgetcsv($file)) !== FALSE) {
				$com_name = '';
			 	if($count > 0) {

			 	$scheme_name = $line[0];
			 	$dp_id = $line[1];
			 	$client_id = $line[2];
		 		$isin = $line[3];
		 		$bse = $line[4];
		 		$shares = $line[5];

			 	$update["add_date"] = strtotime("now");

			 	//now building the query
			 	$table = 'proxy_ad'; //name of the table
			 	$scheme_count = 0;
			 	if(!$dp_id){
			 		if($client_id){
			 			$query_com_check = mysql_query("SELECT id, scheme_name from schemes where client_id='$client_id' and user_id = '$_SESSION[MEM_ID]' limit 1");
			 			$scheme_count = mysql_num_rows($query_com_check);
			 		} else $scheme_count = 0;
			 		
			 	} else if(!$client_id)  {
			 		if($dp_id){
			 			$query_com_check = mysql_query("SELECT id, scheme_name from schemes where dp_id='$dp_id' and user_id = '$_SESSION[MEM_ID]' limit 1");
			 			$scheme_count = mysql_num_rows($query_com_check);
			 		} else $scheme_count = 0;
			 	} else {
			 		$query_com_check = mysql_query("SELECT id, scheme_name from schemes where dp_id='$dp_id' and client_id='$client_id' and user_id = '$_SESSION[MEM_ID]' limit 1");
			 			$scheme_count = mysql_num_rows($query_com_check);
			 	}
			 	if($scheme_count > 0){

			 		$row = mysql_fetch_array($query_com_check);
					$scheme_name = $row["scheme_name"];
					$scheme_id = $row["id"];
					$com_id = 0;
					$com_count = 0;

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
				 	} else {
				 		$query_com_check = mysql_query("SELECT com_id, com_name from companies where com_bse_code='$bse' and com_isin='$isin' limit 1");
				 		$com_count = mysql_num_rows($query_com_check);
				 	}
					
					if($com_count > 0 ){
								
						$row = mysql_fetch_array($query_com_check);
						$com_name = $row["com_name"];
						$com_id = $row["com_id"];

						$check = mysql_query("SELECT * from scheme_companies where scheme_id = '$scheme_id' and com_id = '$com_id' limit 1");
						if(mysql_num_rows($check) > 0){
							$row_check = mysql_fetch_array($check);
							if($row_check["shares_held"] == $shares){
								$success = 'success';
								$note = 'Already Present | No change';
							} else {
								mysql_query("UPDATE scheme_companies set shares_held = '$shares' where id = '$row_check[id]' ");
								$success = 'success';
								$note = 'Already Present | No. of shares changed';
							}
						} else {
							mysql_query("INSERT into scheme_companies (scheme_id, com_id, shares_held, add_date) values ('$scheme_id', '$com_id', '$shares', '$update[add_date]') ");
							$success = 'success';
							$note = 'New company added';

						}
					
					} else {
						$com_name = '';
						$success = "Company not found";
						$note = '';

					}
				}	else {
						$com_name = '';
						$success = "Scheme not found";
						$note = '';
					}
				echo '<tr style="background:#';
				echo ($success == 'success')?'35aa47':'e02222';
				echo '"><td>'.$count.'</td><td>'.$scheme_name.'</td><td>'.$dp_id.'</td><td>'.$client_id.'</td><td>'.$com_name.'</td><td>'.$bse.'</td><td>'.$isin.'</td><td>'.$shares.'</td><td>'.$success;
				echo ($note != '')?' <i>Note:'.$note.'</i>':'';
				echo '</td></tr>';
			}
		  $count++;
		}
		fclose($file);
		echo '</table>';
		die();
			
		}
}


if($_GET["cat"] == 3){	
	if($_GET["update"]){
		$update_id = decrypt($_GET["update"]);
		if(is_numeric($update_id)) {
			$query = mysql_query("SELECT * from schemes where id = $update_id and user_id  = $_SESSION[MEM_ID] limit 1 ");
			if(mysql_num_rows($query) == 0){
				header("location: ".STRSITE."access-denied.php");
			} else {
				$scheme_name = mysql_real_escape_string($_POST["scheme_name"]);
				$dp_id = mysql_real_escape_string($_POST["dp_id"]);
				$client_id = mysql_real_escape_string($_POST["client_id"]);
				$depository = mysql_real_escape_string($_POST["depository"]);
				if(mysql_query("UPDATE schemes set scheme_name = '$scheme_name', dp_id = '$dp_id', client_id = '$client_id', depository = '$depository' where id = '$update_id' ")) header("Location: ../".$folder.".php?cat=2&update=".encrypt($update_id)."&success=1");
				else header("Location: ../".$folder.".php?cat=2&update=".encrypt($update_id)."&success=0");

			}
		} else header("location: ".STRSITE."access-denied.php");
	}

}

?>