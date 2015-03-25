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
$folder = "cgs_reports";

if($_GET["cat"] == 1){

	$table = 'cgs';
	$update["com_id"] = $_POST["com_id"];
	$update["admin_id"] = $_SESSION["MEM_ID"];
	$update["publishing_date"]= strtotime($_POST["pub_date"]);

	$year = date('Y',$update["publishing_date"]);

	$check_timestamp = strtotime("01-04-".$year);

	$update["year"] = $_POST["year"];
	$update["govt_index"] = mysql_real_escape_string($_POST["govt_index"]);
	$update["india_man"] = mysql_real_escape_string($_POST["india_man"]);
	$update["sector"] = mysql_real_escape_string($_POST["sector"]);
	$update["add_date"] = strtotime("now");

	

	$filename = $_FILES["attach_file"]["name"];
	if($filename != '') {
		$filename = substr(str_shuffle(strtotime("now")), 0, 10).$_FILES["attach_file"]["name"];
		move_uploaded_file($_FILES["attach_file"]["tmp_name"],"../../cgs/".$filename);
	}
	$update["report_upload"] = mysql_real_escape_string($filename);


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
	if(mysql_query($query1.$query2.$query3)) header("Location: ../".$folder.".php?success=1");
	else header("Location: ../".$folder.".php?success=0");
	
	die();
}

if($_GET["cat"] == 2){ //delete the report

	$table = 'cgs';
	$cgs_id = $_GET["cid"];
	
	mysql_query("UPDATE cgs set report_upload='' where cgs_id = '$cgs_id' ");

	header("Location: edit.php?success=1&cat=4&id=".$cgs_id);
	
	
	die();
}

if($_GET["cat"] == 3){

	$table = 'cgs';
	$cgs_id = $_GET["cid"];
	$update["admin_id"] = $_SESSION["MEM_ID"];

	$update["publishing_date"]= strtotime($_POST["pub_date"]);

	$year = date('Y',$update["publishing_date"]);

	$check_timestamp = strtotime("01-04-".$year);
	/*
	if($update["publishing_date"] >= $check_timestamp){
		$update["year"] = $year;
	} else {
		$update["year"] =  ($year -1);
	}*/
	$update["year"] = $_POST["year"];

	$update["govt_index"] = mysql_real_escape_string($_POST["govt_index"]);
	$update["sector"] = mysql_real_escape_string($_POST["sector"]);
	$update["govt_index_score"] = mysql_real_escape_string($_POST["govt_index_score"]);
	$update["board_dir"] = mysql_real_escape_string($_POST["board_dir"]);
	$update["dir_rem"] = mysql_real_escape_string($_POST["dir_rem"]);
	$update["stake_eng"] = mysql_real_escape_string($_POST["stake_eng"]);
	$update["fin_rep"] = mysql_real_escape_string($_POST["fin_rep"]);
	$update["comp_score"] = mysql_real_escape_string($_POST["comp_score"]);
	$update["india_man"] = mysql_real_escape_string($_POST["india_man"]);
	$update["sustain"] = mysql_real_escape_string($_POST["sustain"]);
	
	$filename = substr(str_shuffle(strtotime("now")), 0, 10).$_FILES["attach_file"]["name"];	
	if($filename != '') {
		move_uploaded_file($_FILES["attach_file"]["tmp_name"],"../../cgs/".$filename);
	}
	$update["report_upload"] = mysql_real_escape_string($filename);

	if($filename != ''){
		$query = "UPDATE cgs set publishing_date= '$update[publishing_date]', sector = '$update[sector]', report_upload='$update[report_upload]' , govt_index = '$update[govt_index]', india_man = '$update[india_man]', year = '$update[year]', govt_index_score ='$update[govt_index_score]', board_dir='$update[board_dir]', dir_rem ='$update[dir_rem]', stake_eng = '$update[stake_eng]', fin_rep = '$update[fin_rep]', comp_score='$update[comp_score]', sustain = '$update[sustain]'  where cgs_id='$cgs_id' ";
	} else {
		$query = "UPDATE cgs set publishing_date= '$update[publishing_date]', sector = '$update[sector]', govt_index = '$update[govt_index]', india_man = '$update[india_man]', year = '$update[year]', govt_index_score ='$update[govt_index_score]', board_dir='$update[board_dir]', dir_rem ='$update[dir_rem]', stake_eng = '$update[stake_eng]', fin_rep = '$update[fin_rep]', comp_score='$update[comp_score]', sustain = '$update[sustain]'  where cgs_id='$cgs_id' ";
	}
	

	if(mysql_query($query)) header("Location: edit.php?success=2&cat=4&id=".$cgs_id);
	else header("Location: edit.php?success=0&cat=4&id=".$cgs_id);
	
	die();
}

?>