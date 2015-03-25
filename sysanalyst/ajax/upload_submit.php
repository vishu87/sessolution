<?php session_start();
require_once('../../sysan.php');
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

$report_id = $_POST["id"];
$rep_type = $_POST["rep_type"];

$filename = $_FILES["fileup"]["name"]; 
  if($filename != '') {
    $rep = mysql_real_escape_string($filename);
    switch ($rep_type) {
      case '1':
        move_uploaded_file($_FILES["fileup"]["tmp_name"],"../../proxy_reports/".$filename);
        break;
      
       case '2':
        move_uploaded_file($_FILES["fileup"]["tmp_name"],"../../cgs/".$filename);
        break;

         case '3':
        move_uploaded_file($_FILES["fileup"]["tmp_name"],"../../research/".$filename);
        break;
    }
    
  } else{
    echo 'fail';
    die();
  }
  die();
  

?>
<td><?php echo $report_types[$row["rep_type"]]; ?></td>
     <td>
      <?php

     switch ($row["rep_type"]) {
       case '1':
         $query_com = "SELECT proxy_ad.meeting_date, proxy_ad.meeting_type, companies.com_name from proxy_ad inner join companies on companies.com_id = proxy_ad.com_id where proxy_ad.id='$row[report_id]' ";
         break;
       
        case '2':
         $query_com = "SELECT cgs.publishing_date, companies.com_name from cgs inner join companies on companies.com_id = cgs.com_id where cgs.cgs_id='$row[report_id]' ";
         break;

         case '3':
         $query_com = "SELECT research.publishing_date, companies.com_name from research inner join companies on companies.com_id = research.com_id where research.res_id='$row[report_id]' ";
         break;
     }
     $res_com = mysql_query($query_com);
     $row_com = mysql_fetch_array($res_com);
     echo $row_com["com_name"];

     ?></td>
     
      <td><?php if($row["rep_type"] == 1) echo  $meeting_types[$row_com["meeting_type"]];?></td>
     <td><?php
     echo $task_type[$row["type"]];
     ?></td>
     <td><?php echo ($row["deadline"] != '')?date("d M y",$row["deadline"]):'Not set'; ?></td>
    
     <td><?php
     $flag_check =0;
      if($row["type"] ==1){
        echo 'Pending';
      } 
      elseif ($row["type"] == 2) {
       
        $query_check_1 = mysql_query("SELECT completed_on from report_analyst where report_id='$row[report_id]' and rep_type='$row[rep_type]' and type='1' ");
        $check1= mysql_fetch_array($query_check_1);
        if($check1["completed_on"] != '') { echo 'Pending'; }
        else {echo 'Contingent';  $flag_check=1;}

      }
      elseif ($row["type"] == 3) {

        
        $query_check_1 = mysql_query("SELECT completed_on from report_analyst where report_id='$row[report_id]' and rep_type='$row[rep_type]' and type='1' ");
        $check1= mysql_fetch_array($query_check_1);
        
        if($check1["completed_on"] != '') {
        	 
        	 $query_check_2 = mysql_query("SELECT completed_on from report_analyst where report_id='$row[report_id]' and rep_type='$row[rep_type]' and type='2' ");
		        $check2= mysql_fetch_array($query_check_2);
		        if($check2["completed_on"] != '') {
		        	echo 'Pending';
		         }
		        else {echo 'Contingent';  $flag_check=1;}

         }
        else {echo 'Contingent';  $flag_check=1;}
      }
     ?></td>
     <td>
     <?php 
     if($row["type"] ==1 || $row["type"] == 2){
      if($flag_check == 0){
      ?>
      <a href="javascript:void(0)"  class="btn blue" data-toggle="modal" onclick="mark_complete(<?php echo $row["id"]?>);">Mark Complete</a>&nbsp;&nbsp;
     <?php }}?>
     <a class="btn icn-only" href="javascript:;" id="refresh_<?php echo $row["id"];?>" onclick="refresh_tr(<?php echo $row["id"]?>)"><i class="icon-refresh"></i></a>
      </td>