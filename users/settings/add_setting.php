<?php 
if(!isset($title)  || !isset($user_id)) {
		die('This page can not be viewed');
	}

?>
<div class="container-fluid">
	<!-- BEGIN PAGE HEADER-->
	<div class="row-fluid">
		<div class="span6">
         <h3 class="page-title">
            Settings
            <small></small>
         </h3>
      </div>
      
	</div>
   
   <?php
   if(isset($_GET["success"]))
   {
      switch($_GET["success"])
      {
         case (1):
               $text_class= 'alert-success';
               $text = 'Settings are successfully saved.';
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
                                 <h4><i class="icon-reorder"></i>Add Companies to Wishlist</h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                                 <!-- BEGIN FORM-->
                                  <form action="<?php echo $folder?>process.php?cat=1" class="form-horizontal " method="post" >
                                   
                                   <div class="row-fluid">
                                       <div class="span12 ">
                                         <div class="control-group">
                                           <label class="control-label">Proxy Module</label>
                                           <div class="controls">
                                              <select name="proxy_module">
                                                <option value="0">SES Voting</option>
                                                <option value="1" <?php echo ($member->proxy_module == 1)?'selected':''; ?>>Self Voting</option>
                                              </select>
                                              <span class="help-block"></span>
                                           </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                      
                                       <!--/span-->
                                    </div>
                                    <!--/row-->

                                      <div class="row-fluid" style="display:none;">
                                       <div class="span12 ">
                                         <div class="control-group">
                                           <label class="control-label">Voting Span</label>
                                           <div class="controls">
                                              <select name="voting_span">
                                                <option value="0">Firm Wide</option>
                                                <option value="1" <?php echo ($member->voting_span == 1)?'selected':''; ?>>Individual</option>
                                              </select>
                                              <span class="help-block"></span>
                                           </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                      
                                       <!--/span-->
                                    </div>
                                    <!--/row-->

                                    <div class="form-actions">
                                       <button type="submit" class="btn blue"><i class="icon-ok"></i> Save</button>
                                       
                                    </div>

                                 </form>
                                 <!-- END FORM--> 

                                
                        

                              </div>
                           </div>
               </div>
            </div>

             
            
</div><!-- END CONTAINER -->
