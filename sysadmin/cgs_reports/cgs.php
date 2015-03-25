<?php 
if(!isset($title)  || !isset($cgs_id)) {
		die('This page can not be viewed');
	}
$sql_cgs = mysql_query("SELECT cgs.*, companies.com_name from cgs inner join companies on cgs.com_id = companies.com_id where cgs.cgs_id='$cgs_id' ");
$row_cgs = mysql_fetch_array($sql_cgs);

?>
<div class="container-fluid">
	<!-- BEGIN PAGE HEADER-->
	<div class="row-fluid">
		<div class="span6">
         <h3 class="page-title">
            <?php echo $row_cgs["com_name"];?>  
            <small></small>
         </h3>
      </div>
      <div class="span6" align="right">
         <a href="cgs_reports.php?cat=2" class="btn bigicn-only" style="margin-top:10px;"><i class="m-icon-big-swapleft"></i></a>
      </div>
	</div>
   
   <?php
   if(isset($_GET["success"]))
   {
      switch($_GET["success"])
      {
         case (1):
               $text_class= 'alert-success';
               $text = 'Current file is successfully deleted.';
               break;
        case (2):
             $text_class= 'alert-success';
             $text = 'Successfully Updated.';
             break;
         case (0):
               $text_class= 'alert-error';
               $text = 'Error: Database error';
               break;
        case (3):
               $text_class= 'alert-error';
               $text = 'Invalid Package';
               break;
        case (4):
             $text_class= 'alert-success';
             $text = 'Companies are successfully added.';
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
                                 <h4><i class="icon-reorder"></i>CGS Details</h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                                  <!-- BEGIN FORM-->
                                  <form id="cgs_form" action="<?php echo $folder?>process.php?cat=3&amp;cid=<?php echo $cgs_id;?>" class="form-horizontal" method="post" enctype="multipart/form-data" >
                                   
                                   <div class="row-fluid">
                                       <div class="span6 ">
                                         <div class="control-group">
                                       <label class="control-label">Upload File</label>
                                       <div class="controls">

                                           <input type="file" name="attach_file" id="attach_file"/><br>
                                           <?php
                                            if($row_cgs["report_upload"] != '') {
                                           ?>
                                           <a href="<?php echo $folder;?>preview.php?cid=<?php echo base64_encode($row_cgs["cgs_id"]);?>" target="_blank">View Current</a>&nbsp;&nbsp;&nbsp;
                                           <a href="<?php echo $folder;?>process.php?cat=2&amp;cid=<?php echo $row_cgs["cgs_id"]?>" >Remove Current</a>
                                           <?php } ?>

                                          <span class="help-block" ></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group">
                                       <label class="control-label">Publishing Date</label>
                                       <div class="controls">
                                          <input type="text" name="pub_date" id="pub_date" class="datepicker_month" value="<?php echo date("d-m-Y",$row_cgs["publishing_date"])?>"/>
                                          <span class="help-block" ></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                    </div>
                                    <!--/row-->

                                    <div class="row-fluid">
                                      
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group">
                                       <label class="control-label">Government Index</label>
                                       <div class="controls">
                                          <input type="text" name="govt_index" id="govt_index" value="<?php echo stripcslashes($row_cgs["govt_index"])?>"/>
                                          <span class="help-block" ></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                   
                                       <div class="span6 ">
                                         <div class="control-group">
                                       <label class="control-label">India Mandatory</label>
                                       <div class="controls">
                                         <input type="text" name="india_man" id="india_man" value="<?php echo stripcslashes($row_cgs["india_man"])?>"/>
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

               $('#cgs_form').submit();
            
   }
     
   else
      return false;     
}
</script>