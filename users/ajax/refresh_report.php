<?php session_start();
require_once('../../auth.php');

require_once('../../classes/UserClass.php');
require_once('../../classes/GeneralVoting.php');

if(!isset($_POST["id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 0) header("Location: ".STRSITE."access-denied.php");

$member = new User($_SESSION["MEM_ID"]);
$pa_report = new PA($_POST["id"]);
$yr = $pa_report->year;

$member->pa_total_comapnies_year($yr);

$member->pa_subscribed_comapnies_year($_SESSION["MEM_ID"],$yr);

$count = $_POST["count"];
if(!isset($_POST["type"])) $_POST["type"] = 1;
?>

     <td><?php echo $count;?></td>
     <td>
     <?php echo $pa_report->company_name;;?>
    </td>
     <td><?php echo $pa_report->meeting_date;?></td>
      <td><?php echo $pa_report->meeting_type;?></td>
     <td>
      <?php 
           
            if($pa_report->subscribed($member->companies_subscribed_year)) {
             ?>
           <?php 
                 if($pa_report->subscribed($member->companies_report_subscribed_year)) echo $pa_report->report($_SESSION["MEM_ID"],$member->customized);
                  else echo $pa_report->subscription_request("Get Full Report");

            } else {
             echo $pa_report->subscription_request();
            }
          
             ?>
      </td>
     <td>
       <?php 

              if($pa_report->coverage($member->companies_total_year)) {
               ?>
             <?php 
                   
               echo $pa_report->ses_voting($_SESSION["MEM_ID"],$_POST["type"]);
                  
              } else {
             
                echo $pa_report->self_voting($_SESSION["MEM_ID"],$_POST["type"]);
             
              }
           
               ?>
        </td>
     
   <?php if($_POST["special"] == 1 || $_POST["special"] == 2){
       $pa_report->request_proxy($_SESSION["MEM_ID"],$member->proxy_module);
    if($_POST["special"] == 2){
    ?>
     <td id="td_proxy_<?php echo $pa_report->id; ?>" >
      <?php
    
       echo $pa_report->proxy_button;
      ?>
 </td>
 <?php } ?>
<td id="td_proxy_status_<?php echo $pa_report->id; ?>" style=" text-align:center"><?php echo $pa_report->proxy_status;?></td> 

<?php } ?>
       <?php
       if($_POST["special"] == 1 || $_POST["special"] == 2){
        ?>
        <td><?php echo $pa_report->voting_record_users($member->parent);?></td>
      <?php } ?>
  

   <td>
   <?php
    if($_POST["special"] == 1){
      ?>
    <a  href="#myModal" role="button" class="btn blue" data-toggle="modal" onclick="view_report('<?php echo $pa_report->company_name; ?>', <?php echo $pa_report->id ?>)" >Details</a>

      <a href="#stack1" data-toggle="modal" role="button" class="btn black icn-only" onclick="delete_meeting_voting_records(<?php echo $pa_report->id; ?>)"><i class="icon-remove icon-white"></i></a>
      <?php } else {
         echo $pa_report->details();
      } ?>
    </td>