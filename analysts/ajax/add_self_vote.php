<?php session_start();
require_once('../../subuserauth.php');

require_once('../../classes/UserClass.php');


$user = new User($_SESSION["MEM_ID"]);
$parent_id = $user->parent;

if(!isset($_POST["id"])  || $_SESSION["MEM_ID"] == '') header("Location: ".STRSITE."access-denied.php");

$report_id = $_POST["id"];
$resolution_name = mysql_real_escape_string($_POST["res_name"]);
$resolution_number = mysql_real_escape_string($_POST["res_number"]);
$date = strtotime("now");

$sql_check = mysql_query("SELECT resolution_id from user_resolution where resolution_number='$resolution_number' and user_id='$parent_id' and report_id='$report_id' ");

if(mysql_num_rows($sql_check) == 0){
  $query = "INSERT into user_resolution (user_id,report_id, resolution_name, resolution_number,add_date) values ('$parent_id','$report_id', '$resolution_name', '$resolution_number','$date')";
  mysql_query($query);
}

?>