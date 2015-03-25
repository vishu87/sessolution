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
		$year = $_POST["year"];
		$file_path = '../../Temp/'.$temp_filename;
		//echo $file_path;
		$file = fopen($file_path, 'r');
		$count =0;
		echo '<table cellpadding="5" cellspacing="0" ><tr style="background:#4b8df8"><th>SN</th><th>BSE Code/NSE Code</th><th>Name</th><th>Success</th><tr>';
		$flag = 1;
		$final_flag = 0;
		$array_companies = array();
		while (($line = fgetcsv($file)) !== FALSE) {
			//print_r($line);
			//echo $count;
		  if($count >0) {

		  		if($line[0]){
			  		$sql_check = mysql_query("SELECT com_id, com_name, com_bse_code from companies where com_bse_code = '$line[0]' ");
			  		$row_check = mysql_fetch_array($sql_check);
			  		$find = mysql_num_rows($sql_check);
			  		$flag = ($find == 0)?'0':'1';
			  		if(!in_array($row_check["com_id"], $array_companies)) {
			  			array_push($array_companies, $row_check["com_id"]);
			  			$success = ($find >= 1)?'Company Available':'Company Unavailable';
			  		} else {
			  			$success = 'Duplicate';
			  		}
							echo '<tr style="background:#';
							echo ($success == 'Company Available')?'35aa47':'e02222';
							echo '"><td>'.$count.'</td><td>'.$line[0].'</td><td>'.$row_check["com_name"].'</td><td>'.$success.'</td></tr>';
							if($flag == 0) $final_flag = 1;
							
				}


				if($line[1]){
					$sql = "SELECT com_id, com_name, com_nse_sym from companies where com_nse_sym = '$line[1]' ";
					//echo $sql;
			  		$sql_check = mysql_query($sql);
			  		$row_check = mysql_fetch_array($sql_check);
			  		$find = mysql_num_rows($sql_check);
			  		$flag = ($find == 0)?'0':'1';
			  		if(!in_array($row_check["com_id"], $array_companies)) {
			  			array_push($array_companies, $row_check["com_id"]);
			  			$success = ($find >= 1)?'Company Available':'Company Unavailable';
			  		} else {
			  			$success = 'Duplicate';
			  		}
							echo '<tr style="background:#';
							echo ($success == 'Company Available')?'35aa47':'e02222';
							echo '"><td>'.$count.'</td><td>'.$line[1].'</td><td>'.$row_check["com_name"].'</td><td>'.$success.'</td></tr>';
							if($flag == 0) $final_flag = 1;
							
				}

			}
		  $count++;
		}
		fclose($file);
		echo '</table>';
		$start_time =  strtotime('01-03-'.$year);
		$end_time =  strtotime('01-04-'.$year) - 1;
		$add_time = strtotime("now");
		$type = mysql_real_escape_string($_POST["package_type"]);
		$visibility = mysql_real_escape_string($_POST["visibility"]);
		$name = mysql_real_escape_string($_POST["name"]);
		if($final_flag == 0){
			echo "<br>All companies are available";
			
			if(mysql_query("INSERT into package (package_name, package_year, package_type, visibility, add_date, start_time, end_time) VALUES ('$name','$year','$type', '$visibility','$add_time','$start_time','$end_time') ")) {
				$pack_id = mysql_insert_id();
				foreach ($array_companies as $com) {
				mysql_query("INSERT into package_company (package_id, com_id) VALUES ('$pack_id', '$com')");
			}
				echo "<br><br>Package is successfully added.";
			}

		} else {
			echo "Some companies are not available";
		}

		die();
}
//Add a company
if($_GET["cat"] == 2)
{
	$table = 'companies';
	$ar_fields = array("name","bse_code","bse_srcip","nse_sym","reuters","bloomberg","isin");
	$update = array();
	foreach($ar_fields as $ar){
		$update["com_".$ar] = mysql_real_escape_string($_POST[$ar]);
	}
	$update["add_date"] = strtotime("now");
	$sql = "select com_id from $table where com_bse_code = '".$update["com_bse_code"]."' ";
	$result = mysql_query($sql);
	if(mysql_num_rows($result) > 0) { header("Location: ../".$folder.".php?cat=2&success=4"); die();}
	
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
	//echo $query1.$query2.$query3;
	if(mysql_query($query1.$query2.$query3)) header("Location: ../".$folder.".php?cat=2&success=3");
	else header("Location: ../".$folder.".php?cat=2&success=0");
}
?>