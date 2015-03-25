<?php 
session_start();
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
include('../../classes/MemberClass.php');
$report_id = $_POST["id"];
 $sql = mysql_query("SELECT * from report_analyst where id='$report_id' ");
$row = mysql_fetch_array($sql);

  $task_type=array("","Data","Analysis","Review");

   $burn = array();
    $sql_burn=mysql_query("SELECT * from deadline_burn");
    while ($row_burn = mysql_fetch_array($sql_burn)) {
      array_push($burn, $row_burn["days_left"]);
    }
       $subscribed_img = '<img src="../assets/img/subs.png">'; 
       switch ($row["rep_type"]) {
                                     case '1':
                                     $report = new PA_admin($row["report_id"]);
                                     break;
                                   
                                    case '2':
                                     $report = new CGS_admin($row["report_id"]);
                                     break;

                                     case '3':
                                     $report = new Research_admin($row["report_id"]);
                                     break;
                                  }               
?>
<td><?php echo $report_types[$row["rep_type"]]; ?> <?php if($report->subs_bool()) echo $subscribed_img; ?></td>
                                 <td><?php echo $report->company_name;  $report->company_name = name_filter($report->company_name); ?></td>
                                 <td><?php echo (isset($report->meeting_type))?$report->meeting_type:''; ?></td>
                                 <td><?php echo $task_type[$row["type"]]; ?></td>
                                 <td class="<?php
                                  $days_left = ($row["deadline"] != '')?(($row["deadline"] - strtotime("now"))/86400):'50';
                                  if($days_left <=0){
                                    echo 'burn_red_dark';
                                  } elseif($days_left <= $burn[0]){
                                    echo 'burn_red';
                                  } elseif ($days_left <= $burn[1]) {
                                    echo 'burn_yellow';
                                  } else{
                                    echo 'burn_green';
                                  }
                                 ?>"><?php echo ($row["deadline"] != '')?date("d M Y",$row["deadline"]):'Not set'; ?></td>
                                
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
                                  if($flag_check == 0){
                                    if($row["type"] != 1){
                                      ?>
                                        <a href="javascript:void(0)"  class="btn yellow" data-toggle="modal" onclick="send_back(<?php echo $row["id"]?>);">Send Back</a>&nbsp;&nbsp;
                                      <?php
                                    }
                                  }



                                 if($row["type"] ==1 || $row["type"] == 2){
                                  if($flag_check == 0){
                                  ?>
                                  <a href="javascript:void(0)"  class="btn blue" data-toggle="modal"  id="mark_complete_<?php echo $row["id"]?>" onclick="mark_complete(<?php echo $row["id"]?>);">Mark Complete</a>&nbsp;&nbsp;

                                 <?php }}

                                 else {
                                  if($flag_check == 0){
                                     if($report->gen_report == '') {
                                    ?>
                                    
                                     <a role="button" href="#myModal"  class="btn blue" data-toggle="modal" onclick="upload_report('<?php echo $report->company_name?>','<?php echo $row["id"]?>','<?php echo $row["report_id"]?>','<?php echo $row["rep_type"]?>');" data-backdrop="static" data-keyboard="false">Upload Report</a>

                                     &nbsp;&nbsp;
                                      <?php 
                                    } else {
                                      ?>
                                         <a role="button" href="javascript:void(0)"  class="btn red"  onclick="remove_report('<?php echo $report->company_name?>','<?php echo $row["id"]?>','<?php echo $row["report_id"]?>','<?php echo $row["rep_type"]?>');">Remove Report</a>&nbsp;&nbsp;
                                     
                                      <?php 
                                    }
                                        if($row["rep_type"] == 1){//means proxy advisory where we can have custome reports

                                         
                                               $users = fetch_customized_users($report->company_id , $report->year);

                                               if(sizeof($users) > 0){
                                                  
                                              ?>
                                             <a href="#myModal" role="button" class="btn" data-toggle="modal"  onclick="load_custom(<?php echo $row["id"];?>,<?php echo $row["report_id"];?>,'<?php echo $report->company_name?>','<?php echo $report->meeting_date;?>',<?php echo $report->company_id ?>)" data-backdrop="static" data-keyboard="false">Custom Reports</a>
                                              <?php
                                              
                                            }

                                        } 

                                      ?>
                                     
                                    <?php
                                  }
                                 }

                                 ?>
                                 <a href="task.php?cat=<?php 
                                      switch ($row["rep_type"]) {
                                        case '1':
                                          echo '3';
                                          break;
                                        case '2':
                                          echo '4';
                                           break;

                                       case '3':
                                         echo '5';
                                           break;
                                        
                                      }

                                     ?>&amp;<?php 
                                      switch ($row["rep_type"]) {
                                        case '1':
                                          echo 'proxy';
                                          break;
                                        case '2':
                                          echo 'cgs';
                                           break;

                                       case '3':
                                         echo 'res';
                                           break;
                                        
                                      }
                                     ?>=<?php echo encrypt($row["report_id"]); ?>" target="_blank"  class="btn" >Edit</a>&nbsp;&nbsp;
                                  <a role="button" href="#myModal"  class="btn" data-toggle="modal" onclick="view_rep('<?php echo $report->company_name?>','<?php echo $row["id"]?>','<?php echo $row["report_id"]?>','<?php echo $row["rep_type"]?>');">Details</a>
                                 
                                 <a class="btn icn-only" id="refresh_<?php echo $row["id"];?>" onclick="refresh_tr(<?php echo $row["id"]?>)"><i class="icon-refresh"></i></a>
                                  </td>