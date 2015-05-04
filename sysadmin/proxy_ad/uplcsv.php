<?php 
if(!isset($title)) {
		die('This page can not be viewed');
	}
?>
<div class="container-fluid">
	<!-- BEGIN PAGE HEADER-->
	<div class="row-fluid">
		<h3 class="page-title">
			Proxy Advisory		
			<small></small>
		</h3>
	</div>
	 <?php
   if(isset($_GET["success"]))
   {
      switch($_GET["success"])
      {
         case (1):
               $text_class= 'alert-success';
               $text = 'PA is successfully added.';
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
	<div class="row-fluid ">
					<div class="span12">
						<div class="portlet box blue">
						        <div class="portlet-title">
                                 <h4><i class="icon-reorder"></i>Upload CSV</h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                                 <!-- BEGIN FORM-->
                                  <form id="attachment_form" action="<?php echo $folder?>process.php?cat=1" class="form-horizontal form-bordered" method="post" enctype="multipart/form-data" target="_blank">
                                    <div class="row-fluid">
                                       <div class="span6 ">
                                         <div class="control-group">
                                       <label class="control-label">Upload file</label>
                                       <div class="controls">
                                           <input type="hidden" name="cv_req" value="1"/>
                                          <input type="file" name="attach_file" id="attach_file"/>
                                          <span class="help-block" id="fileInfo"></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                          
                                       </div>
                                       <!--/span-->
                                    </div>
                                    <!--/row-->
                                   
                                    <!--/row-->
                                    <div class="form-actions">
                                       <button type="button" onclick="attachment_submit()" class="btn blue"><i class="icon-ok"></i> Submit</button>
                                      
                                    </div>
                                 </form>
                                 <!-- END FORM--> 
                              </div>
                           </div>
					</div>
				</div>
	
				
               <div class="row-fluid ">
               <div class="span12">
                  <div class="portlet box blue">
                          <div class="portlet-title">
                                 <h4><i class="icon-reorder"></i>Add Proxy Advisory Meeting</h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                                 <!-- BEGIN FORM-->
                                 <form action="<?php echo $folder?>process.php?cat=8" method="post" class="horizontal-form" id="add_form">
                                    <div class="row-fluid">
                                       <div class="span6 ">
                                          <div class="control-group">
                                       <label class="control-label">Company Name</label>
                                       <div class="controls">
                                         
                                           <select name="com_id" id="com_id" data-placeholder="Choose.." class="chosen-select"  >
                                  <?php
                                  $query_comp = mysql_query("SELECT com_id,com_bse_code, com_name, com_bse_srcip, com_nse_sym from companies");
                                  while ($row_comp = mysql_fetch_array($query_comp)) {
                                    echo '<option value="'.$row_comp["com_id"].'"">'.stripcslashes($row_comp["com_name"]).' '.$row_comp["com_bse_code"].' '.$row_comp["com_bse_srcip"].' '.$row_comp["com_nse_sym"].'</option>';
                                  }
                                  ?>
                                  
                                </select>

                                          <span class="help-block" ></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                           <div class="control-group">
                                       <label class="control-label">Meeting Date</label>
                                       <div class="controls">
                                          <input type="text" name="meeting_date" id="meeting_date" class="datepicker_month"/>
                                          <span class="help-block" id="usernameInfo"></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                    </div>
                                    <!--/row-->
                                     <div class="row-fluid">
                                       <div class="span6 ">
                                         <div class="control-group">
                                       <label class="control-label">Meeting Type</label>
                                       <div class="controls">
                                           <select name="meeting_type" id="meeting_type">
                                             <?php
                                                $sql_type = mysql_query("SELECT * from met_type");
                                                while ($type = mysql_fetch_array($sql_type)) {
                                                  echo '<option value="'.$type["id"].'" ';
                                                  if($type["id"] == $report["meeting_type"]) echo 'selected';
                                                  echo '>'.$type["type"].'</option>';
                                                }
                                                ?>
                                           </select>
                                          <span class="help-block" ></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group">
                                       <label class="control-label">Meeting Time</label>
                                       <div class="controls">
                                          <input type="text" name="meeting_time" id="meeting_time"/>
                                          <span class="help-block" ></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                    </div>
                                    <!--/row-->  

                                      <div class="row-fluid">
                                       <div class="span6 ">
                                         <div class="control-group">
                                       <label class="control-label">Meeting Venue</label>
                                       <div class="controls">
                                          <input type="text" name="meeting_venue" id="meeting_venue"/>
                                           
                                          <span class="help-block" ></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">

                                       </div>
                                       <!--/span-->
                                    </div> 

                                 
                                    <div class="form-actions">
                                       <button type="button" onclick="submit_add()" class="btn blue"><i class="icon-ok"></i> Create</button>
                                      <button type="button" class="btn" onclick="location.reload()">Cancel</button>
                                    </div>
                                 </form>
                                 <!-- END FORM--> 
                              </div>
                           </div>
               </div>
            </div>

</div><!-- END CONTAINER -->
<script type="text/javascript">

function validateFile(){
   var ext = $("#attach_file").val().split('.').pop().toLowerCase();
   if($.inArray(ext, ['csv']) == -1) {
      $('#fileInfo').text("Please Choose a valid csv file");
      return false;
   } else {
      $('#fileInfo').text("");
      return true;
   }
}
function attachment_submit(){
   if(validateFile())
      $('#attachment_form').submit();
   else
      return false;     
}
function submit_add(){
    if( validate_required_date_idinfo($("#meeting_date").val(), 'meeting_date','Please input valid date dd-mm-yyyy')) {
      $('#add_form').submit();         
   }
   else
      return false;   
}
</script>