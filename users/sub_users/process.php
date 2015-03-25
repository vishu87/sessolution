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
$folder = "sub_users";

//add analyst
if($_GET["cat"] == 1){
	
	$name = mysql_real_escape_string($_POST["name"]);
	$email = mysql_real_escape_string($_POST["email"]);
	$mobile = mysql_real_escape_string($_POST["mobile"]);
	$voting_access = mysql_real_escape_string($_POST["voting_access"]);
	if($email){
		$sql_check = mysql_query("SELECT an_id from analysts where email ='$email' OR username='$email' ");
		$sql_check2 = mysql_query("SELECT id from users where email ='$email' OR username='$email' ");
		$sql_check3 = mysql_query("SELECT id from admin where email ='$email' OR username='$email' ");
		if((mysql_num_rows($sql_check) + mysql_num_rows($sql_check2) +mysql_num_rows($sql_check3)) > 0){
			header("Location: ../".$folder.".php?cat=1&success=2"); // Duplicate email address
		} else {
			$password_ori = rand_string(8);
			$password = md5($password_ori);
			$add_date =strtotime("now");
			$query = "INSERT into users (name, username, password, email, mobile,voting_access,primary_user, created_by_prim, add_date) values ('$name','$email','$password','$email','$mobile','$voting_access','0','$_SESSION[MEM_ID]','$add_date') ";
			
			if(mysql_query($query)) {
				
				$insert_id = mysql_insert_id();
				//echo $insert_id;
				mysql_query("INSERT into user_activity (user_id, activity_id, details) values ('$_SESSION[MEM_ID]','17','$insert_id')");
				mysql_query("INSERT into user_activity (user_id, activity_id, details) values ('$insert_id','23','$password')" );
				send_mail($name,$email,$password_ori);
				header("Location: ../".$folder.".php?cat=1&success=1");
			}
			else header("Location: ../".$folder.".php?cat=1&success=0");
		}
	}
	else {
		header("Location: ../".$folder.".php?cat=1&success=3"); // Invalid valid email
	}

		
	
}
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
		echo '<table cellpadding="5" cellspacing="0" ><tr style="background:#4b8df8"><th>SN</th><th>Email</th><th>Name</th><th>Success</th><tr>';

		$priv_sub = mysql_query("SELECT name,sub_users from users where id='$_SESSION[MEM_ID]' ");
        $row_sub = mysql_fetch_array($priv_sub);
        $allow_ana = $row_sub["sub_users"];

        $sql_name = mysql_query("SELECT id from users where created_by_prim = '$_SESSION[MEM_ID]' ");
        $total_ana = mysql_num_rows($sql_name);
		$count_ok = 1;
		while (($line = fgetcsv($file)) !== FALSE) {

			$com_name = '';
		 	if($count > 0) {

		 		
		  	
				 	$name = mysql_real_escape_string($line[0]);
					$email = mysql_real_escape_string($line[1]);
					$mobile = mysql_real_escape_string($line[2]);
					$voting_access = mysql_real_escape_string($line[3]);
					if($voting_access == 1) $voting_access = 0;
					else $voting_access = 1;
					if(($total_ana + $count_ok) <= $allow_ana){
					if($email && $name){
						
						$sql_check = mysql_query("SELECT id from users where email ='$email' OR username='$email' ");
						if(mysql_num_rows($sql_check) > 0){
							$success = "Duplicate Email";
						} else {
							$password_ori = rand_string(8);
							$password = md5($password_ori);
							$add_date =strtotime("now");
							$query = "INSERT into users (name, username, password, email, mobile,voting_access,primary_user, created_by_prim, add_date) values ('$name','$email','$password','$email','$mobile','$voting_access','0','$_SESSION[MEM_ID]','$add_date') ";
							
							if(mysql_query($query)) {
								
								$insert_id = mysql_insert_id();
								//echo $insert_id;
								mysql_query("INSERT into user_activity (user_id, activity_id, details) values ('$_SESSION[MEM_ID]','17','$insert_id')");
								mysql_query("INSERT into user_activity (user_id, activity_id, details) values ('$insert_id','23','$password')" );
								send_mail($name,$email,$password_ori);
								$count_ok++;
								$success = 'success';
							}
						}
					} else {
						$success = "Email/Name not found";
					}
				} else {
					$success = 'You can not add more users. To add more please contact us.';
				}
				echo '<tr style="background:#';
				echo ($success == 'success')?'35aa47':'e02222';
				echo '"><td>'.$count.'</td><td>'.$email.'</td><td>'.$name.'</td><td>'.$success.'</td></tr>';

			}
		  $count++;
		}
		fclose($file);
		echo '</table>';
		die();
}

if($_GET["cat"] == 3){	
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
		echo '<table cellpadding="5" cellspacing="0" ><tr style="background:#4b8df8"><th>SN</th><th>Email</th><th>Name</th><th>Company Name</th><th>BSE/ISIN</th><th>Success</th><tr>';

		$users_ar = array();
		$user_details = array();
        $sql_name = mysql_query("SELECT id, voting_access from users where id = '$_SESSION[MEM_ID]' OR created_by_prim = '$_SESSION[MEM_ID]' ");
        while ($row_name = mysql_fetch_array($sql_name)) {
        	array_push($users_ar, $row_name["id"]);
        	$user_details[$row_name["id"]]["voting_access"] = $row_name["voting_access"];
        }

        $total_ana = mysql_num_rows($sql_name);
		$count_ok = 1;
		while (($line = fgetcsv($file)) !== FALSE) {

			$com_name = '';
		 	if($count > 0) {

		 		 	$name = mysql_real_escape_string($line[0]);
					$email = mysql_real_escape_string($line[1]);
					$bse = mysql_real_escape_string($line[2]);
					$isin = mysql_real_escape_string($line[3]);
					$com_name = '';

					if($email && ($bse || $isin)){
						if(!$bse){
					 		if($isin){
					 			
					 			$query_com_check = mysql_query("SELECT com_id, com_name from companies where com_isin='$isin' limit 1");
					 			$com_count = mysql_num_rows($query_com_check);
					 			
					 		} else $com_count = 0;
					 		
					 	} else if(!$isin)  {
					 		if($bse){
					 			$query_com_check = mysql_query("SELECT com_id, com_name from companies where com_bse_code='$bse' limit 1");
					 			$com_count = mysql_num_rows($query_com_check);
					 		} else $com_count = 0;
					 		
					 	}
						
						if($com_count > 0){

							$row = mysql_fetch_array($query_com_check);
							$com_name = $row["com_name"];
							$com_id = $row["com_id"];

							$sql_check = mysql_query("SELECT id from users where email ='$email' OR username='$email' limit 1");
							$row_check = mysql_fetch_array($sql_check);

							if(in_array($row_check["id"], $users_ar)){
								$user_id = $row_check["id"];

								if($user_details[$user_id]["voting_access"] == 0){
									$success = "Please change voting access to restricted";
								} else {
									$check = mysql_query("SELECT id from voting_access where user_id='$user_id' and com_id='$com_id' ");
									if(mysql_num_rows($check) == 0){
									    if(mysql_query("INSERT into voting_access (user_id, com_id, added_by,add_date) values ('$user_id','$com_id','$_SESSION[MEM_ID]','".strtotime("now")."') ")){
									   			$success = 'success';
									    } else $success = "Some error";
									} else $success = "Company already present in voting access list";
								}
								
							} else {
								$success = "User not found";
							}
						} else $success = "Company not found";
					} else {
						$success = "Email / BSE or ISIN not found";
					}

				echo '<tr style="background:#';
				echo ($success == 'success')?'35aa47':'e02222';
				echo '"><td>'.$count.'</td><td>'.$email.'</td><td>'.$name.'</td><td>'.$com_name.'</td><td>'.$bse.' '.$isin.'</td><td>'.$success.'</td></tr>';

			}
		  $count++;
		}
		fclose($file);
		echo '</table>';
		die();
}

function rand_string( $length ) {

$str = '';
$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
$str .= substr(str_shuffle($chars),0,1);

$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
$str .= substr(str_shuffle($chars),0,$length-2);

$chars = "~!@#$%&_";
$str .= substr(str_shuffle($chars),0,1);

$str = str_shuffle($str);

return $str;
}
function send_mail($name,$username,$password){

	$subject = "Registration Details"; 

	
	$body = '<html>
		<head>
			<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css">
		</head>
		<body style="font-family: \'Open Sans\', sans-serif; color:#666;"> 
			<p>
				Dear '.$name.'
			</p>
			<p style="padding-left:20px;">
				You have successfully registered on SES Governance Portal. Following are your login details:
			</p>
			<p style="padding-left:20px;">
				Portal Address: http://portal.sesgovernance.com/
			</p>
			<p style="padding-left:20px;">
				Username: <b>'.$username.'</b>
			</p>
			<p style="padding-left:20px;">
				Password: <b>'.$password.'</b>
			</p>
		</body>
	</html>';	

	$body = mysql_real_escape_string($body);
	if(mysql_query("INSERT into mail_queue (mailto, mailcc, mailbcc, mailbccmore, subject, content, at_folder, at_file) values ('$username','','','','$subject', '$body','','') ")) echo '';
	else echo 'Mail can not be sent right now. Please try again later';
}
/*
if($_GET["cat"] == 2){
	
	$id = mysql_real_escape_string($_GET["aid"]);
	$name = mysql_real_escape_string($_POST["name"]);
	$email = mysql_real_escape_string($_POST["email"]);
	$active = mysql_real_escape_string($_POST["active"]);
	
	$query = "UPDATE analysts set name='$name', email='$email', active='$active' where an_id='$id' ";

	if(mysql_query($query)) header("Location: ../".$folder.".php?cat=3&aid=".$id."&success=1");
	else header("Location: ../".$folder.".php?cat=3&aid=".$id."&success=0");
	

	
} */

?>