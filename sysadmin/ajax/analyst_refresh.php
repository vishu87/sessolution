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

$today = strtotime("today");

  $critical = $today + 12*86400;

  $upcoming = $today + 17*86400;

$analysts = array();
 $sql_an = mysql_query("SELECT an_id, name from analysts ");
 while ($row_an = mysql_fetch_array($sql_an)) {
   $analysts[$row_an["an_id"]] = $row_an["name"];
 }

function check_status($deadline, $completed){
    $timenow = strtotime("now");
    if($deadline == ''){
      return '';
    }
    elseif($completed == ''){
      if(($timenow - $deadline) < 86400) return 'burn_yellow';
      else return 'burn_red';
    }
    else {
      if(($completed - $deadline) < 86400) return 'burn_green';
      else return 'burn_purple';
    }

  }

$report_id = $_POST["id"];
$rep_type = $_POST["type"];

  $critical_img = '<img src="../assets/img/critical.png">';
  $upcoming_img = '<img src="../assets/img/upcoming.png">';
  $subscribed_img = '<img src="../assets/img/subs.png">';


$str= '';

if($rep_type == 1){
	$sql = mysql_query("SELECT proxy_ad.meeting_date, proxy_ad.id,proxy_ad.meeting_type,companies.com_name, proxy_ad.com_id, proxy_ad.year from proxy_ad inner join companies on proxy_ad.com_id = companies.com_id where id='$report_id' ");
	$row = mysql_fetch_array($sql);
	$str .= '<td>'.$report_types[1].'</td><td>';

  if($row["meeting_date"] <= $critical && $row["meeting_date"] >= $today) $str .= $critical_img;
                                  elseif($row["meeting_date"] <= $upcoming && $row["meeting_date"] >= $today) $str .= $upcoming_img;

                                  $sql_pack_user = mysql_query("SELECT users_package.user_id from users_package inner join package on users_package.package_id = package.package_id inner join package_company on package_company.package_id = package.package_id where package_company.com_id = '$row[com_id]' and package.package_year='$row[year]' ");


                                  $sql_addi_user = mysql_query("SELECT id from users_companies where com_id = '$row[com_id]' and year = '$row[year]' and type='1' ");

                                  $sql_manual_added = mysql_query("SELECT man_id from manual_subscription where report_id='$row[id]' and report_type='1' ");

                                  $str .= ( ( mysql_num_rows($sql_pack_user) + mysql_num_rows($sql_addi_user) + mysql_num_rows($sql_manual_added)) > 0)? $subscribed_img:'';

  $str .= '</td><td>'.$row["com_name"].'</td><td>'.date("d M Y",$row["meeting_date"]).'</td><td>'.$meeting_types[$row["meeting_type"]].'</td>';
}

if($rep_type == 2){
	$sql = mysql_query("SELECT cgs.publishing_date, companies.com_name, cgs.com_id, cgs.cgs_id,cgs.year  from cgs inner join companies on cgs.com_id = companies.com_id where cgs_id='$report_id' ");
	$row = mysql_fetch_array($sql);
	$str .= '<td>'.$report_types[2].'</td><td>';
if($row["publishing_date"] <= $critical && $row["publishing_date"] >= $today) $str .= $critical_img;
                                  elseif($row["publishing_date"] <= $upcoming && $row["publishing_date"] >= $today) $str .= $upcoming_img;

                                 $sql_sub = mysql_query("SELECT id from users_companies where com_id='$row[com_id]' and type='2' and year='$row[year]' ");
                                  $sql_manual_added = mysql_query("SELECT man_id from manual_subscription where report_id='$row[cgs_id]' and report_type='2' ");

                                  $str .= ((mysql_num_rows($sql_sub)+mysql_num_rows($sql_manual_added)) > 0)? $subscribed_img:'';
  $str .= '</td><td>'.$row["com_name"].'</td><td>'.date("d M Y",$row["publishing_date"]).'</td><td></td>';
}

if($rep_type == 3){
	$sql = mysql_query("SELECT research.publishing_date, companies.com_name, research.com_id, research.year from research inner join companies on research.com_id = companies.com_id where res_id='$report_id' ");
	$row = mysql_fetch_array($sql);
	$str .= '<td>'.$report_types[3].'</td><td>';
 if($row["publishing_date"] <= $critical && $row["publishing_date"] >= $today) $str .= $critical_img;
                                  elseif($row["publishing_date"] <= $upcoming && $row["publishing_date"] >= $today) $str .= $upcoming_img;
                                  
                                  $sql = "SELECT id from users_companies where com_id='$row[com_id]' and type='3' and year='$row[year]' ";
                                  $sql_sub = mysql_query($sql);
                                  $str .= (mysql_num_rows($sql_sub) > 0)? $subscribed_img:'';
  $str .= '<td>'.$row["com_name"].'</td><td>'.date("d M Y",$row["publishing_date"]).'</td><td></td>';
}
 $row["com_name"] = name_filter($row["com_name"]);
//data
$sql = mysql_query("SELECT an_id, deadline,completed_on from report_analyst where report_id= '$report_id' and rep_type='$rep_type' and type= '1' ");
$data = mysql_fetch_array($sql);
//analysis
$sql = mysql_query("SELECT an_id, deadline,completed_on from report_analyst where report_id= '$report_id' and rep_type='$rep_type' and type= '2' ");
$analysis = mysql_fetch_array($sql);

$sql = mysql_query("SELECT an_id, deadline,completed_on from report_analyst where report_id= '$report_id' and rep_type='$rep_type' and type= '3' ");
$review = mysql_fetch_array($sql);

$str .= '<td class="';
$color = check_status($data["deadline"],$data["completed_on"]);
$str .= $color;
$str .= '">'.$analysts[$data["an_id"]].'</td>';
$str .= '<td class="';
$color = check_status($analysis["deadline"],$analysis["completed_on"]);
$str .= $color;
$str .= '">'.$analysts[$analysis["an_id"]].'</td>';
$str .= '<td class="';
$color = check_status($review["deadline"],$review["completed_on"]);
$str .= $color;
$str .= '">'.$analysts[$review["an_id"]].'</td><td>';
$str .= ($data["deadline"])?'Data: '.date("d-m-y",$data["deadline"]).'<br>':'';
$str .= ($analysis["deadline"])?'Analysis: '.date("d-m-y",$analysis["deadline"]).'<br>':'';
$str .= ($review["deadline"])?'Review: '.date("d-m-y",$review["deadline"]):'';
$str .= '</td>';
$str .= '<td><a href="#myModal" role="button" class="btn blue icn-only" data-toggle="modal" onclick="edit_analyst('.$_POST["count"].', \''.stripcslashes($row["com_name"]).'\','.$report_id.','.$rep_type.')"><i class="m-icon-swapright m-icon-white"></i></a></td>';
echo $str;
?>