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
if($_GET["cat"] == 1){

	$table = 'users';
	$ar_fields = array("other_email","mobile","address","sub_users","customized");
	$update = array();
	foreach($ar_fields as $ar){
		$update[$ar] = mysql_real_escape_string($_POST[$ar]);
	}
	
	$result = mysql_query("select * from $table");
	$field = mysql_num_fields($result);
	
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
	if($_POST["select_package"] != 0){
	$sql_check = mysql_query("SELECT id from $table WHERE user_id='$uid' and package_id='$_POST[select_package]' ");
	if(mysql_num_rows($sql_check) == 0){
		$query = "INSERT into $table (user_id, package_id) VALUES ('$uid','$_POST[select_package]') ";
		if(mysql_query($query))  header("Location: ../".$folder.".php?cat=3&success=2&uid=".$uid);
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
	if($type == 4) {
		$type = 1;
		$customized = 1;
	} else { $customized = 0;}
	foreach ($_POST["com_id_select"] as $com) {
		$sql_check = mysql_query("SELECT id from users_companies WHERE user_id='$uid' and com_id='$com' and type='$type' and year='$year' ");
		$num = mysql_num_rows($sql_check);
		if($type == 1){
			if($customized == 0){
			$sql_check_2 =  mysql_query(" SELECT distinct package_company.com_id from package_company inner join users_package on package_company.package_id = users_package.package_id inner join package on users_package.package_id=package.package_id where users_package.user_id='$uid' AND package.package_year='$year' AND package_company.com_id='$com' ");
			$num += mysql_num_rows($sql_check_2);
			}
		}
		if($num== 0){
			
			$sql ="INSERT into users_companies (user_id, com_id, type, year, add_date) VALUES ('$uid','$com','$type', '$year','$time') ";
			mysql_query($sql);
		} else{
			$flag =1;
		}
	}
	if($flag ==0 ) header("Location: ../".$folder.".php?cat=3&success=4&uid=".$uid);
	else header("Location: ../".$folder.".php?cat=3&success=6&uid=".$uid);
}

if($_GET["cat"] == 4){

	$table = 'users';
	$ar_fields = array("name","email","other_email","mobile","address","sub_users","customized");
	$update = array();
	foreach($ar_fields as $ar){
		$update[$ar] = mysql_real_escape_string($_POST[$ar]);
	}
	$email = $update["email"];
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
			$update["password"] = md5("S3Suser");

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
			header("Location: ../".$folder.".php?cat=1&success=1");
		}
	} else {
		header("Location: ../".$folder.".php?cat=1&success=3"); // valid email
	}
	
	die();
}
?>