<?php session_start();
require_once('../../subuserauth.php');

require_once('../../classes/UserClass.php');
require_once('../../classes/GeneralVoting.php');

if(!isset($_POST["id"]) ) header("Location: ".STRSITE."access-denied.php");

$member = new User($_SESSION["MEM_ID"]);
$pa_report = new PA($_POST["id"]);
$yr = $pa_report->year;

$member->pa_total_comapnies_year($yr);

$member->pa_subscribed_comapnies_year($member->parent,$yr);

$count = $_POST["count"];


     $flag_vote = 0;
                              if($member->voting_access == 0){
                                    $flag_vote = 1;
                              } else {
                                $member->voting_access_companies();
                                if(in_array($pa_report->company_id, $member->voting_ac_comp)){
                                  $flag_vote = 1;
                                }
                              }

                           ?>
                              
                                 <td><?php echo $count;?></td>
                                 <td>
                                 <?php echo $pa_report->company_name;;?>
                                </td>
                                 <td><?php echo $pa_report->meeting_date;?></td>
                                 <td><?php echo $pa_report->evoting_end;?></td>
                                  <td><?php echo $pa_report->meeting_type;?></td>
                                 <td>
                                  <?php 
                                  if(in_array($pa_report->company_id, $member->companies_total_year)){
                                  if($pa_report->subscribed($member->companies_subscribed_year)) {
                                   ?>
                                 <?php 
                                   echo $pa_report->report($member->parent,$member->customized); 
                                  } else {
                                   echo 'Not Subscribed';
                                  }
                                } else {
                                  echo 'Not Subscribed';
                                }
                                   ?>

                                  </td>
                                 <td>
                                  <?php 
                                  if($flag_vote == 1) {
                                  if($pa_report->coverage($member->companies_total_year)) {
                                   ?>
                                 <?php 
                                       
                                   echo $pa_report->ses_voting($_SESSION["MEM_ID"],1);
                                      
                                  } else {
                                 
                                    echo $pa_report->self_voting($_SESSION["MEM_ID"],1);
                                 
                                  } }
                               
                                   ?>
                                 <?php 
                                  
                                   ?>
                                  </td>
                                <?php if($pa_report->old_meeting){ ?>
                               <td><?php echo $pa_report->meeting_results(); ?></td>
                                 <?php } ?>

                               <td><?php echo $pa_report->details(); ?></td>
                               