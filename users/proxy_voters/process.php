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

if($_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 0) header("Location: ".STRSITE."access-denied.php");

$folder = "proxy_voters";
$user_id = $_SESSION["MEM_ID"];

//add analyst
if($_GET["cat"] == 1){
	
	$name = mysql_real_escape_string($_POST["name"]);
	$email = mysql_real_escape_string($_POST["email"]);
	$mobile = mysql_real_escape_string($_POST["mobile"]);
	$add_date =strtotime("now");
	
	$query = "INSERT into self_proxy_voters (name, mobile, email, user_id, add_date) values ('$name','$mobile','$email','$user_id','$add_date') ";
	if(mysql_query($query)) header("Location: ../".$folder.".php?cat=1&success=1");
	else header("Location: ../".$folder.".php?cat=1&success=0");
	

	
}

if($_GET["cat"] == 2){
	
	$id = mysql_real_escape_string($_GET["aid"]);
	$name = mysql_real_escape_string($_POST["name"]);
	$email = mysql_real_escape_string($_POST["email"]);
	$mobile = mysql_real_escape_string($_POST["mobile"]);
	
	$query = "UPDATE self_proxy_voters set name='$name', email='$email', mobile='$mobile' where vid='$id' ";

	if(mysql_query($query)) header("Location: ../".$folder.".php?cat=2&aid=".encrypt($id)."&success=1");
	else header("Location: ../".$folder.".php?cat=2&aid=".encrypt($id)."&success=0");
	
}

//file upload
if($_GET["cat"] == 3){	
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
		echo '</th><th>Name</th><th>Email</th><th>Mobile</th><tr>';
		while (($line = fgetcsv($file)) !== FALSE) {
			
		 	if($count >0) {
		  	
			 	$update["name"] = mysql_real_escape_string($line[0]);
			 	$update["email"] = mysql_real_escape_string($line[1]);
			 	$update["mobile"] = mysql_real_escape_string($line[2]);
			 	$user_id = $_SESSION["MEM_ID"];

			 	//now building the query
			 	$table = 'self_proxy_voters'; //name of the table
			 	
				if(mysql_query("INSERT into $table (user_id, name, email, mobile, add_date) values ('$user_id','$update[name]','$update[email]','$update[mobile]', '$timenow') ")) {
					$success = 'success';
				} else {
					$success = "Error";
				}

				echo '<tr style="background:#';
				echo ($success == 'success')?'35aa47':'e02222';
				echo '"><td>'.$count.'</td><td>'.$update["name"].'</td><td>'.$update["email"].'</td><td>'.$update["mobile"].'</td><td>'.$success.'</td></tr>';
			}
		  $count++;
		}
		fclose($file);
		echo '</table>';
		die();
}


?>