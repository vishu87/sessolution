<?php session_start();
require_once('../../sysan.php');
require_once('../../config.php');
require_once('../../mail/class.phpmailer.php');
require_once('../../mail/sendmail.php');
$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if(!$link) {
	die('Failed to connect to server: ' . mysql_error());
}
//Select database
$db = mysql_select_db(DB_DATABASE);
if(!$db) {
	die("Unable to select database");
}
$folder = "task";

if($_GET["cat"] == 1)
{
	$table = 'proxy_ad';
	$rid = mysql_real_escape_string( $_GET["rid"]);
	$ar_fields = array("notice_link","teasor","annual_report","meeting_outcome","meeting_minutes");
	$update = array();
	
	foreach($ar_fields as $ar){
		$update[$ar] = mysql_real_escape_string($_POST[$ar]);
	}

	$notice = $_FILES["notice"]["name"];

	if($notice != ''){

		$notice = $name=substr(str_shuffle(strtotime("now")), 0, 10).$notice;
		$notice_exts = explode('.', $notice);

		if(!in_array($notice_exts[(sizeof($notice_exts)-1)], $file_types)) {
			header("Location: ../".$folder.".php?cat=3&success=3&proxy=".encrypt($rid));
			die();
		}

		move_uploaded_file($_FILES["notice"]["tmp_name"],"../../proxy_notices/".$notice);
		$update["notice"] = $notice;

	}
		

	$update["modified"] = strtotime("now");

	$check_changes_query = mysql_query("SELECT * from proxy_ad where id='$rid' limit 1 ");
	$check_ch = mysql_fetch_array($check_changes_query);
	$check_array = array("notice","notice_link","annual_report","meeting_outcome","meeting_minutes");
	$change_fields = array();
	foreach ($check_array as $check) {
		if($update[$check] == $check_ch[$check]){
			//echo $check.' no change';
		} else {
			
			if($check == 'notice'){
				if($notice != '') {
					//echo $check.' change';
					array_push($change_fields, $check);
				} else {
					//echo $check.' no change';
				}
			} else {
				if($update[$check] != '')
				array_push($change_fields, $check);
				//echo $check.' change';
			}
						
		}
	
	}
	
	$result = mysql_query("select * from $table");
	$field = mysql_num_fields($result);
	$flag=0;
	for ( $i = 1; $i < $field; $i++ ) {
		$name = mysql_field_name( $result, $i );
		if(isset($update[$name]))
		{
			if(!mysql_query("UPDATE $table SET $name = '$update[$name]' WHERE id='$rid'")) {
				$flag=1; break;
			}
		}

	}
	
	if(sizeof($change_fields) > 0) send_email($change_fields, $check_ch["com_id"],$rid);

	if($flag==0) header("Location: ../".$folder.".php?cat=3&success=1&proxy=".encrypt($rid));
	else header("Location: ../".$folder.".php?cat=3&success=0&proxy=".encrypt($rid));

}

if($_GET["cat"] == 2){

	$table = 'cgs';
	$cgs_id = $_GET["cid"];
	
	$update=array();
	$update["govt_index"] = mysql_real_escape_string($_POST["govt_index"]);
	$update["india_man"] = mysql_real_escape_string($_POST["india_man"]);
	$update["board_dir"] = mysql_real_escape_string($_POST["board_dir"]);
	$update["dir_rem"] = mysql_real_escape_string($_POST["dir_rem"]);
	$update["stake_eng"] = mysql_real_escape_string($_POST["stake_eng"]);
	$update["fin_rep"] = mysql_real_escape_string($_POST["fin_rep"]);
	$update["sustain"] = mysql_real_escape_string($_POST["sustain"]);

	$query = "UPDATE cgs set  govt_index = '$update[govt_index]', india_man = '$update[india_man]', board_dir='$update[board_dir]', dir_rem ='$update[dir_rem]', stake_eng = '$update[stake_eng]', fin_rep = '$update[fin_rep]', sustain = '$update[sustain]'  where cgs_id='$cgs_id' ";
	
	

	if(mysql_query($query)) header("Location: ../".$folder.".php?success=1&cat=4&cgs=".encrypt($cgs_id));
	else header("Location: ../".$folder.".php?success=0&cat=4&cgs=".encrypt($cgs_id));
	
	die();
}

if($_GET["cat"] == 3){

	$table = 'research';
	$res_id = $_GET["rid"];
	
	$update=array();
	$update["heading"] = mysql_real_escape_string($_POST["heading"]);
	$update["description"] = mysql_real_escape_string($_POST["description"]);

	$query = "UPDATE $table set heading = '$update[heading]', description = '$update[description]' where res_id='$res_id' ";	

	if(mysql_query($query)) header("Location: ../".$folder.".php?success=1&cat=5&res=".encrypt($res_id));
	else header("Location: ../".$folder.".php?success=0&cat=5&res=".encrypt($res_id));
	
	die();
}
  
//Remove Notice
if($_GET["cat"] == 4){

	$report_id = $_GET["rid"];
	$query = "UPDATE proxy_ad set notice='' where id='$report_id' ";
	if(mysql_query($query)) header("Location: ../".$folder.".php?cat=3&success=2&proxy=".encrypt($report_id));
	else header("Location: ../".$folder.".php?cat=3&success=0&proxy=".encrypt($report_id));
	
}

?>