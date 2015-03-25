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

$sub_req_id = $_POST["sub_req_id"];

$move_status = $_POST["move_status"];


if(!isset($_POST["sub_req_id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 1) header("Location: ".STRSITE."access-denied.php");

$timenow = strtotime("now");

if($move_status == 1){

	$subject = "Subscription Request Information";
		
	$query = mysql_query("SELECT * from subscription_request where id='$sub_req_id' limit 1 ");
	$row = mysql_fetch_array($query);

	if($row["report_type"] == 3){
		$check = mysql_query("SELECT id from research_users where res_id='$row[report_id]' and user_id= '$row[user_id]' limit 1");
		if(mysql_num_rows($check) > 0){
			die("fail");
		} else {
			mysql_query("INSERT into research_users (res_id, user_id, add_date, admin_id) values ('$row[report_id]','$row[user_id]','".strtotime("now")."','$_SESSION[MEM_ID]') ");
		}
	}

	mysql_query("UPDATE subscription_request set status='1', resolved_date= '$timenow' where id='$sub_req_id' ");

	if($row["report_id"] != 0){
	    if($row["report_type"] == 1){
	    	$type = 'Proxy Advisory';
	    $rep_query = mysql_query("SELECT  companies.com_name, users.name, users.email, users.mobile from subscription_request inner join proxy_ad on subscription_request.report_id = proxy_ad.id inner join companies on proxy_ad.com_id = companies.com_id inner join users on subscription_request.user_id = users.id where subscription_request.id='$row[id]' ");

	   	} 
	   	elseif($row["report_type"] == 2) {
	    	$type = 'Governance Score';

	     $rep_query = mysql_query("SELECT  companies.com_name, users.name, users.email, users.mobile from subscription_request inner join cgs on subscription_request.report_id = cgs.cgs_id inner join companies on cgs.com_id = companies.com_id inner join users on subscription_request.user_id = users.id where subscription_request.id='$row[id]' ");

	    }
	    elseif($row["report_type"] == 3) {
	    	$type = 'Governance Research';

	     $rep_query = mysql_query("SELECT  companies.com_name, users.name, users.email, users.mobile from subscription_request inner join research on subscription_request.report_id = research.res_id inner join companies on research.com_id = companies.com_id inner join users on subscription_request.user_id = users.id where subscription_request.id='$row[id]' ");
	   }
	   else die();

	    $row_rep = mysql_fetch_array($rep_query);
	    $user_email = $row_rep["email"];
	    $body_in = "Dear User<br>Your Subscription request for $type reports of $row_rep[com_name] has been resolved by SES Admin on ".date("d M y", strtotime("now"))."<hr><i>This is an auto generated email. Please do not reply.</i>";
 	}
 	elseif($row["new_package"] == 0) {
  		if($row["report_type"] == 1){
   		 $rep_query = mysql_query("SELECT  companies.com_name, users.name, users.email, users.mobile from subscription_request inner join companies on subscription_request.com_id = companies.com_id inner join users on subscription_request.user_id = users.id where subscription_request.id='$row[id]' ");
  		 } elseif($row["report_type"] == 2) {
    	 $rep_query = mysql_query("SELECT  companies.com_name, users.name, users.email, users.mobile from subscription_request inner join companies on subscription_request.com_id = companies.com_id inner join users on subscription_request.user_id = users.id where subscription_request.id='$row[id]' ");

    	}  else die();
    	 $row_rep = mysql_fetch_array($rep_query);
    	 $user_email = $row_rep["email"];
	    $body_in = "Dear User<br>Your Subscription request for $type reports of $row_rep[com_name] has been resolved by SES Admin on ".date("d M y", strtotime("now"))."<hr><i>This is an auto generated email. Please do not reply.</i>";

	 } else {
	     $rep_query = mysql_query("SELECT  name, email, mobile from  users where id='$row[user_id]' ");
	     $new_pack_sql = mysql_query("SELECT package_name,package_year from package where package_id='$row[new_package]' ");
	     $new_pack = mysql_fetch_array($new_pack_sql);
	     $old_pack_sql = mysql_query("SELECT package_name,package_year from package where package_id='$row[old_package]' ");
	     $old_pack = mysql_fetch_array($old_pack_sql);

	     $row_rep = mysql_fetch_array($rep_query);
    	 $user_email = $row_rep["email"];
	   	 $body_in = "Dear User<br>Your upgradation request from package '".$old_pack["package_name"]."' to package '".$new_pack["package_name"]."' has been resolved by SES Admin on ".date("d M y", strtotime("now"))."<hr><i>This is an auto generated email. Please do not reply.</i>";
	 }

	 $body = mysql_real_escape_string($body_in);
	 mysql_query("INSERT into mail_queue (mailto, mailcc, mailbcc, mailbccmore, subject, content, at_folder, at_file) values ('$user_email','admin@sesgovernance.com','','','$subject', '$body','','') ");
     echo 'success';

} elseif($move_status == 0) {
	mysql_query("UPDATE subscription_request set status='0', resolved_date= '' where id='$sub_req_id' ");
	echo 'success';
} else {
	die('fail');
}
?>