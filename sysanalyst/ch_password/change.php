<?php 
if(!isset($title)) {
		die('This page can not be viewed');
	}
?>
<div class="container-fluid">
	<!-- BEGIN PAGE HEADER-->
	<div class="row-fluid">
		<h3 class="page-title">
			Change Password
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
					$text = 'Password is successfully changed.';
					break;
			case (2):
					$text_class= 'alert-error';
					$text = 'Error: Old password does not match';
					break;
			case (3):
					$text_class= 'alert-error';
					$text = 'Error: New passwords does not match';
					break;
			case (4):
					$text_class= 'alert-error';
					$text = 'Error: New Password should be atleast 8 characters long.';
					break;
		}
		echo '<div class="alert '.$text_class.'">
			<button class="close" data-dismiss="alert"></button>
			'.$text.'
			</div>';
	}
	?>
	<div class="row-fluid">
               <div class="span12">
                  <!-- BEGIN SAMPLE FORM PORTLET-->   
                  <div class="portlet box blue tabbable">
                     <div class="portlet-title">
                        <h4>
                           <i class="icon-reorder"></i>
                           <span class="hidden-480">Change Password</span>
                           &nbsp;
                        </h4>
                     </div>
                     <div class="portlet-body form">
                        <div class="tabbable portlet-tabs">
                           <ul class="nav nav-tabs">
                              <li class="active"><a href="#portlet_tab1" data-toggle="tab">Change</a></li>
                           </ul>
                           <div class="tab-content">
                              <div class="tab-pane active" id="portlet_tab1">
                                 <!-- BEGIN FORM-->
                                 <form action="<?php echo $folder;?>process.php" method="post" class="form-horizontal">
                                    <div class="control-group">
                                       <label class="control-label">Old Password</label>
                                       <div class="controls">
                                          <input type="password" name="old_p" id="old_p" placeholder="" class="m-wrap small">
                                          <span class="help-inline"></span>
                                       </div>
                                    </div>

                                    <div class="control-group">
                                       <label class="control-label">New Password</label>
                                       <div class="controls">
                                          <input type="password" name="new_p" id="new_p" placeholder="" class="m-wrap small">
                                          <span class="help-inline">At least 8 characters long</span>
                                       </div>
                                    </div>

                                    <div class="control-group">
                                       <label class="control-label">Retype New Password</label>
                                       <div class="controls">
                                          <input type="password" name="re_new_p" id="re_new_p" placeholder="" class="m-wrap small">
                                          <span class="help-inline"></span>
                                       </div>
                                    </div>
                                    
                                    <div class="form-actions">
                                       <button type="submit" class="btn blue"><i class="icon-ok"></i> Save</button>
                                       <button type="button" class="btn">Cancel</button>
                                    </div>
                                 </form>
                                 <!-- END FORM-->  
                              </div>
                             
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- END SAMPLE FORM PORTLET-->
               </div>
            </div>


				
</div><!-- END CONTAINER -->

</script>