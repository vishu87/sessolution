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
$user = mysql_real_escape_string($_POST["user"]);


if(!isset($_POST["pack_id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 1) header("Location: ".STRSITE."access-denied.php");

$pack_id = $_POST["pack_id"];

   $com_string = $_POST["com_string"];
   $coms = explode('/', $com_string);
  $com_name = addslashes($coms[0]);
   $com_bse_code = $coms[1];

   $sql_com = mysql_query("SELECT com_id, com_name from companies where com_name='$com_name' and com_bse_code='$com_bse_code' limit 1 ");
   $row_com = mysql_fetch_array($sql_com);
   $ar = $row_com["com_id"];

   $sql_check = mysql_query("SELECT com_id from package_company where package_id='$pack_id' and com_id = '$ar' ");
   if(mysql_num_rows($sql_check) > 0){
      echo '<b>'.$row_com["com_name"].'</b>: Already Exist<br>';
   } else {
      if($ar != 0){
         mysql_query("INSERT into package_company (package_id, com_id) values ('$pack_id','$ar') ");
         echo '<b>'.$row_com["com_name"].'</b>: Successfully Added<br>';

         // package_users
         $check = mysql_query("SELECT distinct user_id from users_package where package_id='$pack_id' ");
         if(mysql_num_rows($check)>0){
             //UNSKIPPING ALL PA REPORT WHICH ARE SKIPPED WHEN NO USER WAS ASSIGNED
           $today = strtotime("today");
            mysql_query("UPDATE proxy_ad set skipped_on = 0 where com_id='$ar' and meeting_date > '$today' ");
           }

      }
   }


?>