<?php 
if(!isset($title)) {
		die('This page can not be viewed');
	}
?>
<div class="container-fluid">
	<!-- BEGIN PAGE HEADER-->
	<div class="row-fluid">
		<h3 class="page-title">
			Add CGS
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
               $text = 'CGS is successfully added.';
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
                                 <h4><i class="icon-reorder"></i>Add Score</h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                                 <!-- BEGIN FORM-->
                                  <form id="cgs_form" action="<?php echo $folder?>process.php?cat=1" class="form-horizontal" method="post" enctype="multipart/form-data" >
                                   
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
                                       <label class="control-label">Publishing Date</label>
                                       <div class="controls">
                                          <input type="text" name="pub_date" id="pub_date" class="datepicker_month"/>
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
                                       <label class="control-label">Upload File</label>
                                       <div class="controls">
                                           <input type="file" name="attach_file" id="attach_file"/>
                                          <span class="help-block" ></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group">
                                       <label class="control-label">Government Index</label>
                                       <div class="controls">
                                          <input type="text" name="govt_index" id="govt_index"/>
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
                                       <label class="control-label">India Mandatory</label>
                                       <div class="controls">
                                         <input type="text" name="india_man" id="india_man"/>
                                          <span class="help-block" ></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                           <div class="control-group">
                                       <label class="control-label">Year</label>
                                       <div class="controls">
                                        <?php echo fetch_years('year',$row_cgs["year"]);?>
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
                                       <label class="control-label">Sector</label>
                                       <div class="controls">
                                         <input type="text" name="sector" id="sector"/>
                                          <span class="help-block" ></span>
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
                                       <button type="button" onclick="cgs_submit()" class="btn blue"><i class="icon-ok"></i> Submit</button>
                                      
                                    </div>
                                 </form>
                                 <!-- END FORM--> 
                              </div>
                           </div>
					</div>
				</div>
	
				
</div><!-- END CONTAINER -->
<script type="text/javascript">


function cgs_submit(){
   if( validate_required_date_idinfo($("#pub_date").val(), 'pub_date','Please input valid date dd-mm-yyyy') ){
    
    if($("#attach_file").val() != ''){
      var ext = $("#attach_file").val().split('.').pop().toLowerCase();
      if($.inArray(ext, ['pdf','doc','docx','xls','xlsx']) == -1) {
        alert("Please select a valid file");
      } else {
       $('#cgs_form').submit();
      }
    } else {
      $('#cgs_form').submit();
    }
               
            
   }
     
   else
      return false;     
}
</script>