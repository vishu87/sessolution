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

include('../../classes/MemberClass.php');

$rid = mysql_real_escape_string($_POST["id"]);

if(!isset($_POST["id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 1) header("Location: ".STRSITE."access-denied.php");

$pa_report = new PA_admin($rid);
$flag = $pa_report->check_freeze();
?>

<div class="row-fluid">
  <div clas="span12 pull-right">
    <?php

      if($flag){
        ?>
        <button class="btn yellow" href="javascript:;" onclick="unfreeze(<?php echo $rid; ?>);">Unfreeze</button>
        <?php
      } else {
        ?>
        <button class="btn green" href="javascript:;" onclick="freeze(<?php echo $rid; ?>);">Freeze</button>
        <?php
      }

       if($pa_report->vote_completed_on != 0){
        ?>
        <button class="btn yellow" href="javascript:;" onclick="mark_comp(<?php echo $rid; ?>,0);" id="mark_comp">Mark Votes Incomplete</button>
        <?php
      } else {
        ?>
        <button class="btn green" href="javascript:;" onclick="mark_comp(<?php echo $rid; ?>,1);">Mark Votes Complete</button>
        <?php
      }
if($flag){
      if($pa_report->template_release == 0){
        ?>
        <button class="btn yellow" href="javascript:;" onclick="release_template(<?php echo $rid;?>);" id="release_template">Release Template</button>
        <?php
        } else {
        ?>
        <button class="btn green" href="javascript:;" onclick="release_template(<?php echo $rid; ?>);" id="release_template">Re-Send Template</button>
        <?php
      }
    }

      ?>
  </div>
</div>
<br>
<?php

if(!$flag){
?>


<div class="row-fluid ">
          <div class="span12">
            <div class="portlet box blue">
                    <div class="portlet-title">
                                 <h4><i class="icon-reorder"></i>Add Vote</h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                                 <!-- BEGIN FORM-->
                                  <form action="#" class="form-horizontal" method="post" id="ses_voting_form">
                                   
                                   <div class="row-fluid">
                                       <div class="span6 ">
                                        <div class="control-group">
                                       <label class="control-label">Resolution Number</label>
                                       <div class="controls">
                                          <input type="text" name="resolution_number" id="resolution_number" placeholder="e.g. 1, 1A"></div>
                                       </div>

                                        
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">

                                        <div class="control-group">
                                       <label class="control-label">Resolution Name</label>
                                       <div class="controls">
                                           <input type="text" name="resolution_name" id="resolution_name">
                                            
                                          <span class="help-block"></span>
                                       </div>
                                       </div>

                                           
                                       </div>
                                       <!--/span-->
                                    </div>
                                    <!--/row-->
                                    <div class="row-fluid">
                                       <div class="span6 ">
                                         <div class="control-group">
                                       <label class="control-label">SES Recommendation</label>
                                       <div class="controls">
                                          <select name="ses_reco" id="ses_reco">
                                            <?php
                                              $sql_reso = mysql_query("Select * from ses_recos where status=0");
                                              while ($row_reso = mysql_fetch_array($sql_reso)) {
                                                echo '<option value="'.$row_reso["id"].'">'.$row_reso["reco"].'</option>';
                                              } ?>

                                            </select>
                                       </div>
                                       </div>
                                     </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                        <div class="control-group">
                                       <label class="control-label">Resolution Type</label>
                                       <div class="controls">
                                           <select name="resolution" id="resolution" onchange="fetch_reasons('reason','resolution')">
                                            <option value="0">Select</option>
                                            <?php
                                              $sql_reso = mysql_query("Select * from resolutions where status=0");
                                              while ($row_reso = mysql_fetch_array($sql_reso)) {
                                                echo '<option value="'.$row_reso["id"].'">'.$row_reso["resolution"].'</option>';
                                              } ?>

                                            </select>
                                          <span class="help-block"></span>
                                       </div>
                                       </div>

                                          
                                       </div>
                                       <!--/span-->
                                    </div>
                                    <!--/row-->

                                     <div class="row-fluid">
                                       <div class="span6 ">
                                         <div class="control-group">
                                       <label class="control-label">Management Recommendation</label>
                                       <div class="controls">
                                           <select name="man_reco" id="man_reco" class="span9" >
                                              <option value="1"><?php echo $man_recos[1] ?></option>
                                              <option value="2"><?php echo $man_recos[2] ?></option>
                                              <option value="3"><?php echo $man_recos[3] ?></option>
                                            </select>
                                          <span class="help-block" id="fileInfo"></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                         <div class="control-group">
                                       <label class="control-label">Details</label>
                                       <div class="controls">
                                          <textarea name="detail" id="detail"></textarea>   </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                    </div>
                                    <!--/row-->
                                    <div class="row-fluid">
                                       <div class="span6 ">
                                         <div class="control-group">
                                       <label class="control-label">Reasons</label>
                                       <div class="controls">
                                           <select name="reason[]" id="reason" class="chosen" multiple>
                                            </select>
                                          <span class="help-block" id="fileInfo"></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                           <div class="control-group">
                                       <label class="control-label">Proposal by Management or Shareholder</label>
                                       <div class="controls">
                                           <select name="man_share_reco" id="man_share_reco" class="span9" >
                                              <option value="1"><?php echo $man_share_recos[1] ?></option>
                                              <option value="2"><?php echo $man_share_recos[2] ?></option>
                                            </select>
                                          <span class="help-block" id="fileInfo"></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                    </div>

                                     <div class="row-fluid">
                                       <div class="span6 ">
                                         <div class="control-group">
                                       <label class="control-label">Type of Business</label>
                                       <div class="controls">
                                           <select name="type_business" id="type_business">
                                            <?php 
                                              for ($i = 0; $i< sizeof($types_business); $i++ ) {
                                                echo '<option value="'.$i.'">'.$types_business[$i].'</option>';
                                              }
                                            ?>
                                            </select>
                                          <span class="help-block" id="fileInfo"></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                           <div class="control-group">
                                       <label class="control-label">Type of Resolution</label>
                                       <div class="controls">
                                            <select name="type_res_os" id="type_res_os">
                                            <?php 
                                              for ($i = 0; $i< sizeof($types_res_os); $i++ ) {
                                                echo '<option value="'.$i.'">'.$types_res_os[$i].'</option>';
                                              }
                                            ?>
                                            </select>
                                          <span class="help-block" id="fileInfo"></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                    </div>

                                     <div class="row-fluid">
                                       <div class="span6 ">
                                         <div class="control-group">
                                       <label class="control-label">Focus</label>
                                       <div class="controls">
                                           <select name="focus[]" id="focus" multiple><option value="0">Select</option>
                                              <?php
                                              for ($i = 1; $i< sizeof($focus); $i++ ) {
                                                echo '<option value="'.$i.'" ';
                                                echo '>'.$focus[$i].'</option>';
                                              }
                                              ?>
  
                                            </select>

                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                        <div class="control-group">
                                       <label class="control-label">Order</label>
                                       <div class="controls">
                                          <input type="number" name="priority" id="priority">

                                       </div>
                                       </div>

                                       </div>
                                       <!--/span-->
                                    </div>
                                   
                                    <!--/row-->
                                    <div class="form-actions">
                                       <button type="button" class="btn blue" onclick="check_add_vote(<?php echo $rid;?>)" id="ses_voting_button">Add Vote</button>

                                      
                                    </div>
                                 </form>
                                 <!-- END FORM--> 
                              </div>
                           </div>
          </div>
        </div>

<?php } ?>
     <table class="table table-bordered table-hover tablesorter" id="table_votes" >
     <tr><th>#</th><th>Resolution Name</th><th>Type</th><th>SES Reco</th><th>Manag. Reco</th><th>Proposal by Management or Shareholder</th><th>Details</th><th>Reasons</th><th>Business Type / Resolution Type</th><th>Focus</th><th>Action</th></tr>
<?php
$recos = array();
$sql_reco = mysql_query("SELECT * from ses_recos");
while ($row_reco = mysql_fetch_array($sql_reco)) {
  $recos[$row_reco["id"]] = $row_reco["reco"];
}


     $sql_vote = mysql_query("SELECT * from voting where report_id='$rid' order by priority, resolution_number asc");
     $count =1;
     while($row_vote = mysql_fetch_array($sql_vote)) {
      echo '<tr id="tr_vote_'.$row_vote["id"].'">
      <td>'.stripcslashes($row_vote["resolution_number"]).'</td>';
      echo '<td>'.stripcslashes($row_vote["resolution_name"]).'</td>';
      echo '<td>';
      $sql_reso = mysql_query("Select * from resolutions where id='$row_vote[resolution_type]' ");
        while ($row_reso = mysql_fetch_array($sql_reso)) {
          $reso = $row_reso["resolution"];
          echo $row_reso["resolution"];
        }
        echo '</td>';
        echo '<td>'.stripcslashes($recos[$row_vote["ses_reco"]]).'</td>';
        echo '<td>'.stripcslashes($man_recos[$row_vote["man_reco"]]).'</td>';
        echo '<td>'.stripcslashes($man_share_recos[$row_vote["man_share_reco"]]).'</td>';
        echo '<td>'.stripcslashes($row_vote["detail"]).'</td><td>';
        if($row_vote["reasons"] != ''){
        $sql_reso = mysql_query("Select * from reasons where id IN ($row_vote[reasons]) ");
        while ($row_reso = mysql_fetch_array($sql_reso)) {
          echo '<p>'.$row_reso["reason"].'</p>';
        }
    } ?>
        </td>
        <?php echo '<td>'.$types_business[$row_vote["type_business"]].' / '.$types_res_os[$row_vote["type_res_os"]].'</td>'; ?>
        <td>
          <?php if($row_vote["focus"] != 0){ 
            $focs = explode(',', $row_vote["focus"]);
            $final_focs = array();
            foreach ($focs as $foc) {
              array_push($final_focs, $focus[$foc]);
            }
            echo implode('/', $final_focs);
            } ?>
        </td>
        <td>
        <?php 
        if(!$flag){ ?>
           <button class="btn" data-toggle="modal" href="#stack2" onclick="voting('<?php echo name_filter($row_vote["resolution_name"]);  ?>','<?php echo $row_vote["id"]; ?>');">Edit</button>
           
           <a href="javascript:;" onclick="delete_voting(<?php echo $row_vote["id"]?>);" class="btn red icn-only"><i class="icon-remove icon-white"></i></a>
           <?php } ?>
        </td>
</tr>

<?php
        $count++;

     } ?>

</table>

