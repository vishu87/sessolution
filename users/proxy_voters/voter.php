<?php 
if(!isset($title)  || !isset($aid)) {
		die('This page can not be viewed');
	}
  $sql_an = mysql_query("SELECT * from self_proxy_voters where vid='$aid' and user_id='$_SESSION[MEM_ID]' ");
  $row_an = mysql_fetch_array($sql_an);
  if(mysql_num_rows($sql_an) == 0) die('Not Authorized');
?>
<div class="container-fluid">
	<!-- BEGIN PAGE HEADER-->
	<div class="row-fluid">
		<div class="span6">
         <h3 class="page-title">
            <?php echo $row_an["name"];?>  
            <small></small>
         </h3>
      </div>
      <div class="span6" align="right">
         <a href="proxy_voters.php?cat=1" class="btn bigicn-only" style="margin-top:10px;"><i class="m-icon-big-swapleft"></i></a>
      </div>
	</div>
   
   <?php
   if(isset($_GET["success"]))
   {
      switch($_GET["success"])
      {
         case (1):
               $text_class= 'alert-success';
               $text = 'Voter information is successfully updated.';
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
                                 <h4><i class="icon-reorder"></i>Edit Details</h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                                 <!-- BEGIN FORM-->
                                  <form id="update_form" action="<?php echo $folder?>process.php?cat=2&amp;aid=<?php echo $row_an["vid"];?>" class="form-horizontal " method="post" >
                                   
                                   <div class="row-fluid">
                                       <div class="span6 ">
                                         <div class="control-group">
                                       <label class="control-label">Name</label>
                                       <div class="controls">
                                          <input type="text" name="name" id="name" value="<?php echo $row_an["name"];?>"/>
                                          <span class="help-block" ></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                         <div class="control-group">
                                       <label class="control-label">Email</label>
                                       <div class="controls">
                                          <input type="text" name="email" id="email" value="<?php echo $row_an["email"];?>"/>
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
                                          <input type="text" name="mobile" id="mobile" value="<?php echo $row_an["mobile"];?>"/>
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
                                       <button type="button" onclick="update_submit()" class="btn blue"><i class="icon-ok"></i> Update</button>
                                      
                                    </div>
                                 </form>
                                 <!-- END FORM--> 
                              </div>
                           </div>
					</div>
				</div>
	

</div><!-- END CONTAINER -->
<script type="text/javascript">


function update_submit(){
   if(validate_required_gen_idinfo($("#name").val(), 'name','Please input valid name') && validate_required_gen_idinfo($("#email").val(), 'email','Please input valid email')){
//      alert('yes');
      $('#update_form').submit();
   } 
   else
      return false;     
}

function company_submit(){
   if($("#com_id_select").val() != null){
      $('#company_form').submit();
   } 
   else{
    alert('Plase select some companies.');
    return false; 
   }
          
}
</script>