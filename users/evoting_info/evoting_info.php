<?php 
if(!isset($title)) {
		die('This page can not be viewed');
	}
?>
<div class="container-fluid">
	<!-- BEGIN PAGE HEADER-->
	<div class="row-fluid">
		<h3 class="page-title">
			eVoting Information
			<small></small>
		</h3>
	</div>
	<?php
	if(isset($_GET["success"]))
	{
		switch($_GET["success"])
		{
			
			case (0):
					$text_class= 'alert-error';
					$text = 'Error: Database error';
					break;
			case (1):
					$text_class= 'alert-success';
					$text = 'Information is successfully updated';
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
                                 <h4><i class="icon-reorder"></i>NSDL</h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>              
                              <div class="portlet-body form">
                                 <!-- BEGIN FORM-->
                                 <form action="<?php echo $folder?>process.php?cat=1" method="post" class="horizontal-form">
                                    <div class="row-fluid">
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label" for="name">User ID</label>
                                             <div class="controls">
                                              <input id="nsdl_user_id" name="nsdl_user_id" class="m-wrap span12" placeholder="e.g. User1234" type="text" value="<?php echo $value["nsdl_user_id"]; ?>" >
                                                <span class="help-block" ></span>
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label" for="lastName">Mutual Fund ID</label>
                                             <div class="controls">
                                              <input id="nsdl_mf_id" name="nsdl_mf_id" class="m-wrap span12" placeholder="e.g. XYZ123" type="text" value="<?php echo $value["nsdl_mf_id"]; ?>">
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
                                             <label class="control-label">POA ID
                                             <div class="controls">
												                        <input id="nsdl_poa_id" name="nsdl_poa_id" class="m-wrap span12" placeholder="e.g. AB1234" type="text" value="<?php echo $value["nsdl_poa_id"]; ?>">
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
                                    
                                 
                                    <div class="form-actions" style="padding: 19px 10px 20px;">
                                       <button type="submit" class="btn blue"><i class="icon-ok"></i> Update</button>
                                      
                                    </div>
                                 </form>
                                 <!-- END FORM--> 
                           </div>
					</div>
				</div>

<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:90%; margin-left:-45%">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Modal header</h3>
  </div>
  <div class="modal-body" id="modal-body" style="min-height:300px;">
    
  </div>
  <div class="modal-footer">

    <button class="btn" data-dismiss="modal" aria-hidden="true" id="close_button">Close</button>
  </div>
</div>  
				
				
</div>
