<?php 
/*
$sql_check_an = mysql_query("SELECT an_id from report_analyst where report_id='$res_id' and rep_type='3' and type='3' ");
$row_check_an = mysql_fetch_array($sql_check_an);
*/
if(!isset($title)  || !isset($res_id) ) {
		die('This page can not be viewed');
	}
$sql_cgs = mysql_query("SELECT research.*, companies.com_name from research inner join companies on research.com_id = companies.com_id where research.res_id='$res_id' ");
$row_cgs = mysql_fetch_array($sql_cgs);

?>
<div class="container-fluid">
	<!-- BEGIN PAGE HEADER-->
	<div class="row-fluid">
		<div class="span12">
         <h3 class="page-title">
            Reaserch: <?php echo $row_cgs["com_name"];?>, <?php echo date("d-m-Y",$row_cgs["publishing_date"])?>  
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
             $text = 'Successfully Updated.';
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
                                 <h4><i class="icon-reorder"></i>Research Details</h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                                  <!-- BEGIN FORM-->
                                  <form id="cgs_form" action="<?php echo $folder?>process.php?cat=3&amp;rid=<?php echo $res_id;?>" class="form-horizontal" method="post" enctype="multipart/form-data" >
                                   
                                  

                                    <div class="row-fluid">
                                      
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group">
                                       <label class="control-label">Heading</label>
                                       <div class="controls">
                                          <input type="text" name="heading" id="heading" value="<?php echo stripcslashes($row_cgs["heading"])?>"/>
                                          <span class="help-block" ></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                   
                                       <div class="span6 ">
                                         <div class="control-group">
                                       <label class="control-label">Description</label>
                                       <div class="controls">
                                         <textarea type="text" name="description" id="description"><?php echo stripcslashes($row_cgs["description"])?></textarea>
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
                                       <button type="submit" class="btn blue"><i class="icon-ok"></i> Update</button>
                                      
                                    </div>
                                 </form>
                                 <!-- END FORM--> 
                              </div>
                           </div>
					</div>
				</div>
	
  

</div><!-- END CONTAINER -->
