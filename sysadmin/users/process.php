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
$folder = "users";
$uid = $_GET["uid"];

//update user 
if($_GET["cat"] == 1){

	$table = 'users';
	$ar_fields = array("user_admin_name","other_email","mobile","address","sub_users","customized","portal_access","voting_template","voting_template_type","pa_mail_details");
	$update = array();
	foreach($ar_fields as $ar){
		$update[$ar] = mysql_real_escape_string($_POST[$ar]);
	}

$sql_check = mysql_query("SELECT portal_access, name, username from users where id='$uid' limit 1");
	$row_check = mysql_fetch_array($sql_check);

	$initial_access = $row_check["portal_access"];

	if($initial_access == 1 && $update["portal_access"] == 0){
		$password = rand_string(8);
		$md5_password = md5($password);

		mysql_query("UPDATE users set password='$md5_password' where id= '$uid' ");

		mysql_query("INSERT into user_activity (user_id, activity_id, details) values ('$insert_id','23','$md5_password')" );
		send_mail($row_check["name"],$row_check["username"],$password);

		mysql_query("UPDATE users set portal_access = 0 where created_by_prim = '$uid' ");

		
	}

	if($initial_access == 0 && $update["portal_access"] == 1){
		mysql_query("UPDATE users set portal_access = 1 where created_by_prim = '$uid' ");
	}

	$result = mysql_query("select * from $table");
	$field = mysql_num_fields($result);
	
	$flag=0;
	for ( $i = 1; $i < $field; $i++ ) {
		$name = mysql_field_name( $result, $i );
		if(isset($update[$name]))
		{
			if(!mysql_query("UPDATE $table SET $name = '$update[$name]' WHERE id='$uid'")) {
				$flag=1; break;
			}
		}

	}
	if($flag == 0){
		 header("Location: ../".$folder.".php?cat=3&success=1&uid=".$uid);
	}
	else header("Location: ../".$folder.".php?cat=3&success=0&uid=".$uid);
	
	die();
}
//Add a package
if($_GET["cat"] == 2){
	$table = 'users_package';
	$today = strtotime("today");
	$_POST["limited"] = mysql_real_escape_string($_POST["limited"]);
	if($_POST["select_package"] != 0){
	$sql_check = mysql_query("SELECT id from $table WHERE user_id='$uid' and package_id='$_POST[select_package]' ");
	if(mysql_num_rows($sql_check) == 0){
		$query = "INSERT into $table (user_id, package_id, limited) VALUES ('$uid','$_POST[select_package]','$_POST[limited]') ";
		if(mysql_query($query))  {

			$query_com = mysql_query("SELECT com_id from package_company where package_id='$_POST[select_package]' ");
			while ($row_com = mysql_fetch_array($query_com)) {
				
				//UNSKIPPING ALL PA REPORT WHICH ARE SKIPPED WHEN NO USER WAS ASSIGNED
        	    mysql_query("UPDATE proxy_ad set skipped_on = 0 where com_id='$row_com[com_id]' and meeting_date > '$today' ");
			}
			header("Location: ../".$folder.".php?cat=3&success=2&uid=".$uid);
		}
	}
	
	else header("Location: ../".$folder.".php?cat=3&success=0&uid=".$uid);
	}
	else{
		header("Location: ../".$folder.".php?cat=3&success=3&uid=".$uid);
	}
}
// add a company
if($_GET["cat"] == 3){
	$table = 'users_companies';
	$year = $_POST["year"];
	$type = $_POST["type"];
	$time = strtotime("now");
	$flag =0;
	foreach ($_POST["com_id_select"] as $com) {
		$sql_check = mysql_query("SELECT id from users_companies WHERE user_id='$uid' and com_id='$com' and type='$type' and year='$year' ");
		$num = mysql_num_rows($sql_check);
		if($num == 0){	

			$sql_check_2 =  mysql_query(" SELECT distinct package_company.com_id from package_company inner join users_package on package_company.package_id = users_package.package_id inner join package on users_package.package_id=package.package_id where users_package.user_id='$uid' AND package.package_year='$year' AND package_company.com_id='$com' AND package.package_type = '$type' ");

			if(mysql_num_rows($sql_check_2) == 0){
				$sql = "INSERT into users_companies (user_id, com_id, type, year, add_date) VALUES ('$uid','$com','$type', '$year','$time') ";
				mysql_query($sql);
				 //UNSKIPPING ALL PA REPORT WHICH ARE SKIPPED WHEN NO USER WAS ASSIGNED
	      		  $today = strtotime("today");
	      		  mysql_query("UPDATE proxy_ad set skipped_on = 0 where com_id='$com' and meeting_date > '$today' ");
			} else{
				$flag = 1;
			}
		} else { $flag =1; }
	}
	if($flag ==0 ) header("Location: ../".$folder.".php?cat=3&success=4&uid=".$uid);
	else header("Location: ../".$folder.".php?cat=3&success=6&uid=".$uid);
}

//Add a user
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

if($_GET["cat"] == 4){

	$table = 'users';
	$ar_fields = array("name","user_admin_name","email","other_email","mobile","address","sub_users","customized", "proxy_module","portal_access");
	$update = array();
	foreach($ar_fields as $ar){
		$update[$ar] = mysql_real_escape_string($_POST[$ar]);
	}
	$email = $update["email"];
	$update["proxy_module"] = 1;
	if($email){
		$sql_check = mysql_query("SELECT an_id from analysts where email ='$email' OR username='$email' ");
		$sql_check2 = mysql_query("SELECT id from users where email ='$email' OR username='$email' ");
		$sql_check3 = mysql_query("SELECT id from admin where email ='$email' OR username='$email' ");
	
		if((mysql_num_rows($sql_check) + mysql_num_rows($sql_check2) +mysql_num_rows($sql_check3)) > 0){
			header("Location: ../".$folder.".php?cat=1&success=2"); // Duplicate email address
		} else {
			$update["username"] = $update["email"];
			$update["add_date"] = strtotime("now");
			$update["priv"] = 0;
			$update["pic"] = '';
			$update["primary_user"] = 1;//yes
			$password = rand_string(8);
			$update["password"] = md5($password);

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
			mysql_query($query1.$query2.$query3);
			$insert_id = mysql_insert_id();

			mysql_query("INSERT into user_activity (user_id, activity_id, details) values ('$insert_id','23','$update[password]')" );
			if($update["portal_access"] == 0) send_mail($update["name"],$update["username"],$password);
			header("Location: ../".$folder.".php?cat=1&success=1");

		}
	} else {
		header("Location: ../".$folder.".php?cat=1&success=3"); // valid email
	}
	
	die();
}


if($_GET["cat"] == 5){
	
	$id = mysql_real_escape_string($_GET["id"]);

	$query = "UPDATE users set active='1' where id='$id'";
	mysql_query($query);
	$query = "UPDATE users set active='1' where created_by_prim='$id'";

	if(mysql_query($query)) header("Location: ../".$folder.".php?cat=2");
	else header("Location: ../".$folder.".php?cat=2");
	

	
}

if($_GET["cat"] == 6){
	
	$id = mysql_real_escape_string($_GET["id"]);

	$query = "UPDATE users set active='0' where id='$id'";
	mysql_query($query);
	$query = "UPDATE users set active='0' where created_by_prim='$id'";

	if(mysql_query($query)) header("Location: ../".$folder.".php?cat=2");
	else header("Location: ../".$folder.".php?cat=2");
	

	
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
	mysql_query("INSERT into mail_queue (mailto, mailcc, mailbcc, mailbccmore, subject, content, at_folder, at_file) values ('$username','','','','$subject', '$body','','') ");
}
?>