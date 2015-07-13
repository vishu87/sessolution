<?php 
if(!isset($title)  || !isset($user_id)) {
		die('This page can not be viewed');
	}

?> 
<div class="container-fluid">

	<!-- BEGIN PAGE HEADER-->
	<div class="row-fluid">
		<div class="span9">
         <h3 class="page-title">
            Email Alerts
            <small></small>
         </h3>
      </div>
      <div class="span3">
       
      </div>
      
	</div>

   
   <?php
   if(isset($_GET["success"]))
   {
      switch($_GET["success"])
      {
         case (1):
               $text_class= 'alert-success';
               $text = 'Alerts are successfully saved.';
               break;
        
         case (0):
               $text_class= 'alert-error';
               $text = 'Error: Database error';
               break;
      
       
      }
      echo '<div class="alert '.$text_class.'">
         <button class="close" data-dismiss="alert"></button>
         '.$text.'
         </div>';
   }
   ?>
  
<div class="row-fluid" style="overflow-y:auto ">
  <div class="span5">
      <div class="row-fluid">
       <div class="span12">
          <div class="control-group">
             <label class="control-label">Alert me for a Meeting before e-Voting deadline (no. of days)</label>
             <div class="controls">
                  <input type="text" class="m-wrap span4"  id="meeting_alert_add" placeholder="No. of Days" value=""><button class="btn" type="button" onclick="alert_add()" >Add</button>
                  <span class="help-block"></span>   
                </div>
             </div>
          </div>
       </div>
    
  </div>
  <div class="span4">
    <!-- <h5>e-Voting Alerts (click to remove)</h5>  -->
    <label class="control-label">e-Voting Alerts (click to remove)</label>
    <div id="days_span">
    <?php 
      $alert_query = mysql_query("SELECT id,num_days from meeting_alert where user_id='$_SESSION[MEM_ID]' order by num_days desc");
      while ($row_alert = mysql_fetch_array($alert_query)) {
        ?>
        <button class="btn red" onclick="alert_remove('<?php echo $row_alert["id"]; ?>')" id="btn_<?php echo $row_alert["id"]; ?>"><?php echo $row_alert["num_days"] ?> Days</button>&nbsp;
        <?php
      }
    ?>
    </div>
  </div>
  <div class="span3">
    <form class="horizontal-form alert_form" id="alert_form">
    <label class="control-label">&nbsp;</label>
    <button class="btn green span3 pull-right" type="button" onclick="save_alerts()" id="save_btn" style="margin-bottom:10px">Save</button>
  </div>
</div>
  
             <div class="portlet box light-grey">
                <div class="portlet-title">
                       <h4><i class="icon-reorder"></i> Set Email Alerts</h4>
                       <div class="tools">
                          <a href="javascript:;" class="collapse"></a>
                       </div>
                    </div>
                    <div class="portlet-body">
      <table class="table table-stripped tablesorter_without">
                 <thead>
                    <tr>
                       <th>SN</th>
                       <th>Company Name</th>
                       <th>BSE Code</th>
                       <th>e-Voting Alerts</th>
                       <th>Meeting Schedule Update</th>
                       <th>Report Upload</th>
                       <th>Notice</th>
                       <th>Annual Report</th>
                       <th>Meeting Outcome</th>
                       <th>Meeting Minutes</th>
                       
                      
                    </tr>
                    
                 </thead>
                
                 <tbody>

                      <td></td>
                         <td></td>
                         <td></td>
                         <td><input type="checkbox" value="1" id="meeting_alert" onclick ="change('meeting_alert')" ></td>
                         <td><input type="checkbox" value="1" id="meeting_schedule" onclick ="change('meeting_schedule')" ></td>
                         <td><input type="checkbox" value="1" id="report_upload" onclick ="change('report_upload')"></td>
                         <td><input type="checkbox" value="1" id="notice" onclick ="change('notice')"></td>
                         <td><input type="checkbox" value="1" id="annual_report" onclick ="change('annual_report')"></td>
                         <td><input type="checkbox" value="1" id="meeting_outcome" onclick ="change('meeting_outcome')"></td>
                         <td><input type="checkbox" value="1" id="meeting_minutes" onclick ="change('meeting_minutes')"></td>
                         </tr>
                    <?php

                      $query_alert = "SELECT distinct(user_voting_company.com_id), user_voting_company.meeting_alert, meeting_schedule, report_upload, notice, annual_report, meeting_outcome, meeting_minutes , companies.com_name, companies.com_bse_code from user_voting_company inner join companies on user_voting_company.com_id = companies.com_id inner join users on user_voting_company.user_id = users.id where users.id='$user_id' OR users.created_by_prim = '$user_id' order by companies.com_name asc";

                      $sql = mysql_query($query_alert);
                      $count = 1;
                      $old_com_id = 0;
                      while ($row = mysql_fetch_array($sql)) {
                        if($row["com_id"] == $old_com_id) continue;
                       echo '<tr id="tr_'.$row["com_id"].'">';
                       echo '<td>'.$count.'</td>';
                          echo '<td>'.$row["com_name"].'</td>';
                          echo '<td>'.$row["com_bse_code"].'<input type="hidden" name="com_id[]" value="'.$row["com_id"].'"></td>';
                          echo '<td><input type="checkbox" value="1" name="meeting_alert_'.$row["com_id"].'" class="meeting_alert" ';
                          echo ($row["meeting_alert"] == 1)?'checked="checked"':'';
                          echo ' ></td>';
                          echo '<td><input type="checkbox" value="1" name="meeting_schedule_'.$row["com_id"].'" class="meeting_schedule" ';
                          echo ($row["meeting_schedule"] == 1)?'checked="checked"':'';
                          echo ' ></td>';
                          echo '<td><input type="checkbox" value="1" name="report_upload_'.$row["com_id"].'" class="report_upload" ';
                          echo ($row["report_upload"] == 1)?'checked="checked"':'';
                          echo '></td>';
                          echo '<td><input type="checkbox" value="1" name="notice_'.$row["com_id"].'"  class="notice" ';
                          echo ($row["notice"] == 1)?'checked="checked"':'';
                          echo '></td>';
                          echo '<td><input type="checkbox" value="1" name="annual_report_'.$row["com_id"].'" class="annual_report" ';
                          echo ($row["annual_report"] == 1)?'checked="checked"':'';
                          echo '></td>';
                          echo '<td><input type="checkbox" value="1" name="meeting_outcome_'.$row["com_id"].'" class="meeting_outcome" ';
                          echo ($row["meeting_outcome"] == 1)?'checked="checked"':'';
                          echo'></td>';
                          echo '<td><input type="checkbox" value="1" name="meeting_minutes_'.$row["com_id"].'" class="meeting_minutes" ';
                          echo ($row["meeting_minutes"] == 1)?'checked="checked"':'';
                          echo '></td>';
                          echo '</tr>';
                          $count++;
                          if($count%40 == 0){
                            echo '</form><form class="horizontal-form alert_form" id="alert_form">';
                          }
                          $old_com_id = $row["com_id"];
                      }
                    ?>
                   
                   
                 </tbody>
              </table>
            </div>
          </div>

</div><!-- END CONTAINER -->
</div>
</form>
<script type="text/javascript">

function change(param){
  var val = $("#"+param).attr('checked');
  if(val == 'checked'){
    $("."+param).attr('checked','checked');
  } else {
    $("."+param).removeAttr('checked');
  }
}

function alert_add(){
   if(validate_required_number_idinfo($("#meeting_alert_add").val(), 'meeting_alert_add','Please input valid number') && $("#meeting_alert_add").val() <= 30 ) {
    if(parseInt($("#meeting_alert_add").val()) > 0){
        var file = "alert_add_firm";
         $.post("ajax/"+ file +".php", {days:$("#meeting_alert_add").val()}, function(data) {
            if(data=='fail'){
              bootbox.alert('You have already alert for '+$("#meeting_alert_add").val()+' days');
            } else if(data=='fail1'){
              bootbox.alert('You can set maximum 3 alerts');
            } else {
              $("#days_span").prepend(data+'&nbsp;');
            }
       });
      } else { bootbox.alert("Please input a positive number");}
   } else {
    bootbox.alert('You can set alert for maximum 30 days');
    return false;
   }
}

function alert_remove(id){
       bootbox.confirm("Are you sure to delete ?", function(result){
          if(result){
            
             var file = "alert_remove_firm";
             $.post("ajax/"+ file +".php", {id:id}, function(data) {
                if(data=='fail'){
                  bootbox.alert('Unknown Error');
                } else {
                  $("#btn_"+id).hide("slow",function(){
                    $("#btn_"+id).remove();
                  });
                }
           });
          }
       });
}
var sent = 0;
function save_alerts(){
  $("#save_btn").html('Saving...');
  var file = "save_alerts_firm";
  var count_form = 1;
  $(".alert_form").each(function(){
      var data_st = $(this).serialize();
      var val_data = data_st+'&count_form='+count_form;
      $.post("ajax/"+file+".php",val_data, function(data){
        console.log(data);
        if(++sent == $(".alert_form").length) {
          location.reload();
        }
      })
      count_form++;
    });
}

</script>