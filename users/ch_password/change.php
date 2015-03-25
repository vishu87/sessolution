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
					$text = 'Error: New Password does not meet minimum requirements';
					break;
         case (5):
               $text_class= 'alert-error';
               $text = 'Error: New Password should be different from last three passwords.';
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
                                 <form action="<?php echo $folder;?>process.php" method="post" class="form-horizontal" id="form_change">
                                    <div class="control-group">
                                       <label class="control-label">Old Password</label>
                                       <div class="controls">
                                          <input type="password" name="old_p" id="old_p" placeholder="" class="m-wrap">
                                          <span class="help-inline"></span>
                                       </div>
                                    </div>

                                    <div class="control-group">
                                       <label class="control-label">New Password</label>
                                       <div class="controls">
                                          <input type="password" name="new_p" id="new_p" placeholder="" class="m-wrap" onkeyup="clear_password()"><a href="javascript:;" rel="tooltip" class="btn ttip icn-only" data-placement="right" title="Password must be atleast 8 charaters long. It must contain atleast one Uppercase letter (A-Z),<br> one special charaters ( ! @ # $ % _ ^ * &amp; ~ ) ,and one number(0-9)."><i class="icon-question-sign"></i></a>
                                          <span class="help-inline"></span>
                                       </div>
                                    </div>

                                    <div class="control-group">
                                       <label class="control-label">Retype New Password</label>
                                       <div class="controls">
                                          <input type="password" name="re_new_p" id="re_new_p" placeholder="" class="m-wrap" onkeyup="clear_password()" >
                                          <span class="help-inline"></span>
                                       </div>
                                    </div>
                                    
                                    <div class="form-actions">
                                       <button type="button" onclick="check_save()" class="btn blue"><i class="icon-ok"></i> Save</button>
                                       <button type="button" class="btn" onclick="location.href='index.php';">Cancel</button>
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

<script type="text/javascript">
   function check_save(){
      var id_info = 'new_p';
      if($("#"+id_info).val().match(/^(?=.*\d)(?=.*[A-Z])(?=.*[~!@#$%&_^*]).{8,}$/) == null){
         $("#"+id_info).parent().find('span').text("Please input a valid password.");
         $("#"+id_info).parent().parent().addClass("error");
      } else {
         if($("#new_p").val() !== $("#re_new_p").val()){
            $("#re_new_p").parent().find('span').text("Both passwords do not match.");
            $("#re_new_p").parent().parent().addClass("error");
         } else {
            $("#form_change").submit();
         }
      }
   }

   function clear_password(){
      
      var id_info = 'new_p';
      $("#"+id_info).parent().find('span').text("");
      $("#"+id_info).parent().parent().removeClass("error");

      var id_info = 're_new_p';
      $("#"+id_info).parent().find('span').text("");
      $("#"+id_info).parent().parent().removeClass("error");
   }
   
</script>