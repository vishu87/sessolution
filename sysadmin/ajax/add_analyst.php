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

if($_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 1) header("Location: ".STRSITE."access-denied.php");

$report_id = $_POST["id"];
$rep_type = $_POST["type"];
$add_date = strtotime("now");

//data
$sql = mysql_query("SELECT an_id, deadline from report_analyst where report_id= '$report_id' and rep_type='$rep_type' and type= '1' ");
$an_id = $_POST["data_an_id"];
$deadline = ($_POST["data_deadline"]!='')?strtotime($_POST["data_deadline"]):'';
if(mysql_num_rows($sql) == 0){
  mysql_query("INSERT into report_analyst (an_id, report_id, rep_type, type, deadline,add_date) values ('$an_id','$report_id','$rep_type','1','$deadline','$add_date') ");
} else {
  mysql_query("UPDATE report_analyst set an_id='$an_id', deadline='$deadline' where report_id='$report_id' and rep_type='$rep_type' and type='1' ");
}

//analysis
$sql = mysql_query("SELECT an_id, deadline from report_analyst where report_id= '$report_id' and rep_type='$rep_type' and type= '2' ");
$an_id = $_POST["analysis_an_id"];
$deadline = ($_POST["analysis_deadline"]!='')?strtotime($_POST["analysis_deadline"]):'';
if(mysql_num_rows($sql) == 0){
  mysql_query("INSERT into report_analyst (an_id, report_id, rep_type, type, deadline,add_date) values ('$an_id','$report_id','$rep_type','2','$deadline','$add_date') ");
} else {
  mysql_query("UPDATE report_analyst set an_id='$an_id', deadline='$deadline' where report_id='$report_id' and rep_type='$rep_type' and type='2' ");
}

//review
$sql = mysql_query("SELECT an_id, deadline from report_analyst where report_id= '$report_id' and rep_type='$rep_type' and type= '3' ");
$an_id = $_POST["review_an_id"];
$deadline = ($_POST["review_deadline"]!='')?strtotime($_POST["review_deadline"]):'';
if(mysql_num_rows($sql) == 0){
  mysql_query("INSERT into report_analyst (an_id, report_id, rep_type, type, deadline,add_date) values ('$an_id','$report_id','$rep_type','3','$deadline','$add_date') ");
} else {
  mysql_query("UPDATE report_analyst set an_id='$an_id', deadline='$deadline' where report_id='$report_id' and rep_type='$rep_type' and type='3' ");
}
echo 'Successfully Updated.';

?>