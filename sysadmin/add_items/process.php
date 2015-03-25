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
$folder = "add_items";
$update = mysql_real_escape_string( $_GET["update"]);

//add resolution type
if($_GET["cat"] == 1){

	$name = mysql_real_escape_string($_POST["name"]);
	$sebi_clause = mysql_real_escape_string($_POST["sebi_clause"]);

	if(mysql_query("INSERT into resolutions (resolution, sebi_clause) values ('$name', '$sebi_clause') ")){
		 header("Location: ../".$folder.".php?cat=1&success=1");
	}
	else header("Location: ../".$folder.".php?cat=1&success=0");
	
	die();
}

//update resolution type
if($_GET["cat"] == 2){

	$name = mysql_real_escape_string($_POST["name"]);
	$sebi_clause = mysql_real_escape_string($_POST["sebi_clause"]);
	

	if(mysql_query("UPDATE resolutions set resolution = '$name', sebi_clause='$sebi_clause' where id='$update' " )){
		 header("Location: ../".$folder.".php?cat=1&success=1");
	}
	else header("Location: ../".$folder.".php?cat=1&success=0");
	
	die();
}

//add recommendation
if($_GET["cat"] == 3){

	$name = mysql_real_escape_string($_POST["name"]);

	if(mysql_query("INSERT into ses_recos (reco) values ('$name') ")){
		 header("Location: ../".$folder.".php?cat=2&success=1");
	}
	else header("Location: ../".$folder.".php?cat=2&success=0");
	
	die();
}

//update recommendation
if($_GET["cat"] == 4){

	$name = mysql_real_escape_string($_POST["name"]);

	if(mysql_query("UPDATE ses_recos set reco = '$name' where id='$update' " )){
		 header("Location: ../".$folder.".php?cat=2&success=1");
	}
	else header("Location: ../".$folder.".php?cat=2&success=0");
	
	die();
}

//add reasons
if($_GET["cat"] == 5){

	$name = mysql_real_escape_string($_POST["name"]);
	$res_type_id = mysql_real_escape_string($_POST["res_type_id"]);

	if(mysql_query("INSERT into reasons (reason,res_type_id) values ('$name','$res_type_id') ")){
		 header("Location: ../".$folder.".php?cat=3&success=1");
	}
	else header("Location: ../".$folder.".php?cat=3&success=0");
	
	die();
}

//update reasons
if($_GET["cat"] == 6){

	$name = mysql_real_escape_string($_POST["name"]);
	$res_type_id = mysql_real_escape_string($_POST["res_type_id"]);


	if(mysql_query("UPDATE reasons set reason = '$name', res_type_id='$res_type_id' where id='$update' " )){
		 header("Location: ../".$folder.".php?cat=3&success=1");
	}
	else header("Location: ../".$folder.".php?cat=3&success=0");
	
	die();
}

//add locations
if($_GET["cat"] == 7){

	$name = mysql_real_escape_string($_POST["name"]);

	if(mysql_query("INSERT into locations (place) values ('$name') ")){
		 header("Location: ../".$folder.".php?cat=4&success=1");
	}
	else header("Location: ../".$folder.".php?cat=4&success=0");
	
	die();
}

//update locations
if($_GET["cat"] == 8){

	$name = mysql_real_escape_string($_POST["name"]);

	if(mysql_query("UPDATE locations set place = '$name' where id='$update' " )){
		 header("Location: ../".$folder.".php?cat=4&success=1");
	}
	else header("Location: ../".$folder.".php?cat=4&success=0");
	
	die();
}


//add years
if($_GET["cat"] == 9){

	$name = mysql_real_escape_string($_POST["name"]);
	$period = mysql_real_escape_string($_POST["period"]);

	if(mysql_query("INSERT into years (year_sh, period) values ('$name','$period') ")){
		 header("Location: ../".$folder.".php?cat=5&success=1");
	}
	else header("Location: ../".$folder.".php?cat=5&success=0");
	
	die();
}

//update years
if($_GET["cat"] == 10){

	$name = mysql_real_escape_string($_POST["name"]);
	$period = mysql_real_escape_string($_POST["period"]);

	if(mysql_query("UPDATE years set year_sh = '$name', period= '$period' where year_sh='$update' " )){
		 header("Location: ../".$folder.".php?cat=5&success=1");
	}
	else header("Location: ../".$folder.".php?cat=5&success=0");
	
	die();
}

//block location
if($_GET["cat"] == 11){

	$update = mysql_real_escape_string($_GET["update"]);

	$query = mysql_query("SELECT status from locations where id='$update'");
	$res = mysql_fetch_array($query);
	if($res["status"] == 1){
		$query_up = "UPDATE locations set status = 0 where id='$update' ";
	} else {
		$query_up = "UPDATE locations set status = 1 where id='$update' ";
	}

	if(mysql_query($query_up)){
		 header("Location: ../".$folder.".php?cat=4&success=2");
	}
	else header("Location: ../".$folder.".php?cat=4");
	
	die();
}

//block type
if($_GET["cat"] == 12){

	$update = mysql_real_escape_string($_GET["update"]);

	$query = mysql_query("SELECT status from resolutions where id='$update'");
	$res = mysql_fetch_array($query);
	if($res["status"] == 1){
		$query_up = "UPDATE resolutions set status = 0 where id='$update' ";
	} else {
		$query_up = "UPDATE resolutions set status = 1 where id='$update' ";
	}

	if(mysql_query($query_up)){
		 header("Location: ../".$folder.".php?cat=1&success=2");
	}
	else header("Location: ../".$folder.".php?cat=1");
	
	die();
}

//block recommendation
if($_GET["cat"] == 13){

	$update = mysql_real_escape_string($_GET["update"]);

	$query = mysql_query("SELECT status from ses_recos where id='$update'");
	$res = mysql_fetch_array($query);
	if($res["status"] == 1){
		$query_up = "UPDATE ses_recos set status = 0 where id='$update' ";
	} else {
		$query_up = "UPDATE ses_recos set status = 1 where id='$update' ";
	}

	if(mysql_query($query_up)){
		 header("Location: ../".$folder.".php?cat=2&success=2");
	}
	else header("Location: ../".$folder.".php?cat=2");
	
	die();
}

//block recommendation
if($_GET["cat"] == 14){

	$update = mysql_real_escape_string($_GET["update"]);

	$query = mysql_query("SELECT status from reasons where id='$update'");
	$res = mysql_fetch_array($query);
	if($res["status"] == 1){
		$query_up = "UPDATE reasons set status = 0 where id='$update' ";
	} else {
		$query_up = "UPDATE reasons set status = 1 where id='$update' ";
	}

	if(mysql_query($query_up)){
		 header("Location: ../".$folder.".php?cat=3&success=2");
	}
	else header("Location: ../".$folder.".php?cat=3");
	
	die();
}
?>