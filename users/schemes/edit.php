<?php 
if(!isset($title)) {
		die('This page can not be viewed');
	}
?>
<div class="container-fluid">
	<!-- BEGIN PAGE HEADER-->
	<div class="row-fluid">
		<div class="span6">
      <h3 class="page-title">
        Edit Scheme
        <small></small>
      </h3>
    </div>
    <div class="span6">
      <a href="schemes.php" class="btn yellow pull-right" style="margin-top:20px">Go Back</a>
    </div>
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
					$text = 'Scheme is successfully updated';
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
                                 <h4><i class="icon-reorder"></i>Edit</h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>              
                              <div class="portlet-body form">
                                 <!-- BEGIN FORM-->
                                 <form action="<?php echo $folder?>process.php?cat=3&update=<?php echo encrypt($update_id) ?>" method="post" class="horizontal-form" id="submit_form">
                                    <div class="row-fluid">
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label" for="name">Scheme Name</label>
                                             <div class="controls">
                                              <input id="scheme_name" name="scheme_name" class="m-wrap span12" placeholder="" type="text" value="<?php echo $update["scheme_name"] ?>" >
                                                <span class="help-block" ></span>
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label" for="lastName">DP ID</label>
                                             <div class="controls">
                                              <input id="dp_id" name="dp_id" class="m-wrap span12" placeholder="eg. XYZ123" type="text" value="<?php echo $update["dp_id"] ?>" >
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
                                             <label class="control-label">Client ID
                                             <div class="controls">
												                        <input id="client_id" name="client_id" class="m-wrap span12" placeholder="eg. AB1234" type="text" value="<?php echo $update["client_id"] ?>" >
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
                                       <button type="button" onclick="check_submit()" class="btn blue"><i class="icon-ok"></i> Update</button>
                                      <a href="schemes.php" class="btn">Cancel</a>
                                    </div>
                                 </form>
                                 <!-- END FORM--> 
                           </div>
					</div>
				</div>

  
				
				
</div>
<script type="text/javascript">
var count = 0;
var name = '';

function check_submit(){
  var submit_form = $("#submit_form");
      if(validate_required_name_info($("#scheme_name").val(), 'scheme_name','Please input valid name. Only numbers, alphabates and space.')){
         submit_form.submit();
      } else {
         return false;
      }
}


</script>