<?php 
if(!isset($title)) {
		die('This page can not be viewed');
	}
?>
<div class="container-fluid">
	<!-- BEGIN PAGE HEADER-->
	<div class="row-fluid">
		<h3 class="page-title">
			Upload Company CSV		
			<small></small>
		</h3>
	</div>
	
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
</script>