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
  
<div class="row-fluid">
  <div class="span6">
 
      <div class="row-fluid">
       <div class="span12 ">
          <div class="control-group">
             <label class="control-label">Alert me for a Meeting before (No. of days)</label>
             <div class="controls">
                  <input type="text" class="m-wrap span6"  id="meeting_alert_add" placeholder="Number of Days e.g. 7" value=""><button class="btn" type="button" onclick="alert_add()" >Add</button>
                  <span class="help-block"></span>   
                </div>
             </div>
          </div>
       </div>
    
  </div>
  <div class="span6">
    <h5>Meeting Alerts (click to remove)</h5> 
    <div id="days_span">
    <?php 
      $alert_query = mysql_query("SELECT id,num_days from meeting_alert where user_id='$_SESSION[MEM_ID]' order by num_days desc");
      while ($row_alert = mysql_fetch_array($alert_query)) {
        ?>
        <button class="btn red" onclick="alert_remove('<?php echo $row_alert["id"]; ?>')" id="btn_<?php echo $row_alert["id"]; ?>"><?php echo $row_alert["num_days"] ?> Days</button>
        <?php
      }
    ?>
    </div>
  </div>
</div>

<form action="<?php echo $folder; ?>process.php?cat=3" method="post" class="horizontal-form" id="alert_form">
   <button class="btn green span3 pull-right" type="submit" style="margin-bottom:10px">Save</button>
            <div class="row-fluid">
                <table class="table table-stripped tablesorter_without">
                           <thead>
                              <tr>
                                 <th>#</th>
                                 <th>Company Name</th>
                                 <th>BSE Code</th>
                                 <th>Meeting Alerts</th>
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
                                   <td><input type="checkbox" id="meeting_alert" onclick ="change('meeting_alert')" ></td>
                                   <td><input type="checkbox" id="meeting_schedule" onclick ="change('meeting_schedule')" ></td>
                                   <td><input type="checkbox" id="report_upload" onclick ="change('report_upload')"></td>
                                   <td><input type="checkbox" id="notice" onclick ="change('notice')"></td>
                                   <td><input type="checkbox" id="annual_report" onclick ="change('annual_report')"></td>
                                   <td><input type="checkbox" id="meeting_outcome" onclick ="change('meeting_outcome')"></td>
                                   <td><input type="checkbox" id="meeting_minutes" onclick ="change('meeting_minutes')"></td>
                                   </tr>
                              <?php


                                $sql = mysql_query("SELECT user_wishlist.*, companies.com_name, companies.com_id, companies.com_bse_code from user_wishlist inner join companies on user_wishlist.com_id = companies.com_id where user_wishlist.user_id='$user_id' order by companies.com_name asc");
                                $count =1;
                                while ($row = mysql_fetch_array($sql)) {
                                 echo '<tr id="tr_'.$row["com_id"].'">';
                                 echo '<td>'.$count.'</td>';
                                    echo '<td>'.$row["com_name"].'</td>';
                                    echo '<td>'.$row["com_bse_code"].'<input type="hidden" name="com_id[]" value="'.$row["com_id"].'"></td>';
                                    echo '<td><input type="checkbox" name="meeting_alert_'.$row["com_id"].'" class="meeting_alert" ';
                                    echo ($row["meeting_alert"] == 1)?'checked="checked"':'';
                                    echo ' ></td>';
                                    echo '<td><input type="checkbox" name="meeting_schedule_'.$row["com_id"].'" class="meeting_schedule" ';
                                    echo ($row["meeting_schedule"] == 1)?'checked="checked"':'';
                                    echo ' ></td>';
                                    echo '<td><input type="checkbox" name="report_upload_'.$row["com_id"].'" class="report_upload" ';
                                    echo ($row["report_upload"] == 1)?'checked="checked"':'';
                                    echo '></td>';
                                    echo '<td><input type="checkbox" name="notice_'.$row["com_id"].'"  class="notice" ';
                                    echo ($row["notice"] == 1)?'checked="checked"':'';
                                    echo '></td>';
                                    echo '<td><input type="checkbox" name="annual_report_'.$row["com_id"].'" class="annual_report" ';
                                    echo ($row["annual_report"] == 1)?'checked="checked"':'';
                                    echo '></td>';
                                    echo '<td><input type="checkbox" name="meeting_outcome_'.$row["com_id"].'" class="meeting_outcome" ';
                                    echo ($row["meeting_outcome"] == 1)?'checked="checked"':'';
                                    echo'></td>';
                                    echo '<td><input type="checkbox" name="meeting_minutes_'.$row["com_id"].'" class="meeting_minutes" ';
                                    echo ($row["meeting_minutes"] == 1)?'checked="checked"':'';
                                    echo '></td>';
                                    echo '</tr>';
                                    $count++;
                                }
                                                                
                                    
                                  
                              ?>
                             
                             
                           </tbody>
                        </table>
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
   if(validate_required_number_idinfo($("#meeting_alert_add").val(), 'meeting_alert_add','Please input valid number') ) {
        var file = "alert_add";
         $.post("ajax/"+ file +".php", {days:$("#meeting_alert_add").val()}, function(data) {
            if(data=='fail'){
              bootbox.alert('You have already alert for '+$("#meeting_alert_add").val()+' days');
            } else {
              $("#days_span").prepend(data);
            }
       });
   } else {
    return false;
   }
}

function alert_remove(id){
       bootbox.confirm("Are you sure to delete ?", function(result){
          if(result){
            
             var file = "alert_remove";
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


</script>