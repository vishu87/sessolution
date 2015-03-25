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


if(!isset($_POST["report_id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 1) header("Location: ".STRSITE."access-denied.php");

$report_id = $_POST["report_id"];
$company_id = $_POST["company_id"];
$year = $_POST["year"];
$type = $_POST["type"];
$user_id = $_POST["user_id"];

if($type == 1){

    $total_comp_subscribed = array();                
    $sql_report = mysql_query(" SELECT distinct package_company.com_id from package_company inner join users_package on package_company.package_id = users_package.package_id inner join package on users_package.package_id=package.package_id where users_package.user_id='$user_id' AND package.package_year='$year' and package.package_type='1' ");

    while($row_rep = mysql_fetch_array($sql_report)){
      array_push($total_comp_subscribed, $row_rep["com_id"]);
    }


     $sql_report = mysql_query("SELECT distinct com_id from users_companies where type='1' and year='$year' and user_id='$user_id'  ");
     while($row_cgs = mysql_fetch_array($sql_report)){
      if(!in_array($row_cgs["com_id"], $total_comp_subscribed))
        array_push($total_comp_subscribed, $row_cgs["com_id"]);
  }

  if(in_array($company_id, $total_comp_subscribed)){
    echo 'fail';
  } else {

      $timenow = strtotime("now");
      if(mysql_query("INSERT into users_companies ( user_id, com_id, type,year, add_date, admin_id) values ('$user_id','$company_id','$type','$year','$timenow','$_SESSION[MEM_ID]') ")){
        //UNSKIPPING ALL PA REPORT WHICH ARE SKIPPED WHEN NO USER WAS ASSIGNED
        $today = strtotime("today");
        mysql_query("UPDATE proxy_ad set skipped_on = 0 where com_id='$company_id' and meeting_date > '$today' ");
        echo 'success';
      } else {
        echo 'fail';
      }
   
  }
die();
}

if($type == 2){

    $total_comp_subscribed = array();                
    $sql_report = mysql_query(" SELECT distinct package_company.com_id from package_company inner join users_package on package_company.package_id = users_package.package_id inner join package on users_package.package_id=package.package_id where users_package.user_id='$user_id' AND package.package_year='$year' and package.package_type='2' ");

    while($row_rep = mysql_fetch_array($sql_report)){
      array_push($total_comp_subscribed, $row_rep["com_id"]);
    }


     $sql_report = mysql_query("SELECT distinct com_id from users_companies where type='2' and year='$year' and user_id='$user_id'  ");
     while($row_cgs = mysql_fetch_array($sql_report)){
      if(!in_array($row_cgs["com_id"], $total_comp_subscribed))
        array_push($total_comp_subscribed, $row_cgs["com_id"]);
  }

  if(in_array($company_id, $total_comp_subscribed)){
    echo 'fail';
  } else {

      $timenow = strtotime("now");
      if(mysql_query("INSERT into users_companies ( user_id, com_id, type,year, add_date, admin_id) values ('$user_id','$company_id','$type','$year','$timenow','$_SESSION[MEM_ID]') ")){
        echo 'success';
      } else {
        echo 'fail';
      }
   
  }
die();

}

if($type == 3){

   $query = mysql_query("SELECT distinct user_id from research_users where res_id='$report_id' and user_id = '$user_id' ");


  if(mysql_num_rows($query) > 0){
    echo 'fail';
  } else {

      $timenow = strtotime("now");
      if(mysql_query("INSERT into research_users ( user_id, res_id, add_date, admin_id) values ('$user_id','$report_id','$timenow','$_SESSION[MEM_ID]') ")){
        echo 'success';
      } else {
        echo 'fail';
      }
   
  }
die();

}
?>