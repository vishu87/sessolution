<?php 
if(!isset($title)) {
		die('This page can not be viewed');
	}
?>
<div class="container-fluid">
	<!-- BEGIN PAGE HEADER-->
	<div class="row-fluid">
		<h3 class="page-title">
			Add User		
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
               $text = 'User is successfully added.';
               break;
         case (0):
               $text_class= 'alert-error';
               $text = 'Error: Database error';
               break;
         case (2):
               $text_class= 'alert-error';
               $text = 'Error: Duplicate Email/Username';
               break;
         case (3):
               $text_class= 'alert-error';
               $text = 'Error: Invalid Email';
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
                                 <h4><i class="icon-reorder"></i>Add</h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                                 <!-- BEGIN FORM-->
                                  <form id="attachment_form" action="<?php echo $folder?>process.php?cat=4" class="form-horizontal " method="post" >
                                   
                                   <div class="row-fluid">
                                       <div class="span6 ">
                                         <div class="control-group">
                                       <label class="control-label">Name</label>
                                       <div class="controls">
                                          <input type="text" name="name" id="name"/>
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

                                    <div class="row-fluid">
                                       <div class="span6 ">
                                        <div class="control-group">
                                       <label class="control-label">Email (Username)</label>
                                       <div class="controls">
                                          <input type="text" name="email" id="email"/>
                                          <span class="help-block" id="usernameInfo"></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group">
                                       <label class="control-label">Other Emails</label>
                                       <div class="controls">
                                          <input type="text" name="other_email" id="other_email"/>
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
                                       <label class="control-label">Mobile</label>
                                       <div class="controls">
                                         <input type="text" name="mobile">
                                          <span class="help-block" ></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group">
                                       <label class="control-label">Address</label>
                                       <div class="controls">
                                          <textarea name="address" id="address"></textarea>
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
                                       <label class="control-label">Addon Users</label>
                                       <div class="controls">
                                          <input type="text" name="sub_users" id="sub_users">
                                          <span class="help-block" ></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group">
                                       <label class="control-label">Customized</label>
                                       <div class="controls">
                                          <select name="customized">
                                             <option value="0">No</option>
                                             <option value="1">Yes</option>
                                          </select>
                                          <span class="help-block" ></span>
                                       </div>
                                       </div>
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


function attachment_submit(){
   if(validate_required_gen_idinfo($("#name").val(), 'name','Please input valid name') && validate_required_email_idinfo($("#email").val(), 'email','Please input valid email') && validate_required_number_idinfo($("#sub_users").val(), 'sub_users','Please input valid number') ){
       var value=$("#username").val();
         var column = 'username';
         var file = 'username_verify';
         var span_info = 'usernameInfo';

         $.post("ajax/"+ file +".php", { col: column, val:value }, function(data) {
            if (data == 'success') {
               $("#"+span_info).text("");
               $("#"+span_info).parent().parent().removeClass("error");
               $('#attachment_form').submit();
            } else {
               $("#"+span_info).text("Duplicate entry");
               $("#"+span_info).parent().parent().addClass("error");
               return false;
            }
         }); 
   }
     
   else
      return false;     
}
</script>