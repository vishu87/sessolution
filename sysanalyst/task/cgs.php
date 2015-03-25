<?php 
/*$sql_check_an = mysql_query("SELECT an_id from report_analyst where report_id='$cgs_id' and rep_type='2' and type='3' ");
$row_check_an = mysql_fetch_array($sql_check_an);
*/
if(!isset($title)  || !isset($cgs_id)) {
		die('This page can not be viewed');
	}
$sql_cgs = mysql_query("SELECT cgs.*, companies.com_name from cgs inner join companies on cgs.com_id = companies.com_id where cgs.cgs_id='$cgs_id' ");
$row_cgs = mysql_fetch_array($sql_cgs);

?>
<div class="container-fluid">
	<!-- BEGIN PAGE HEADER-->
	<div class="row-fluid">
		<div class="span12">
         <h3 class="page-title">
            CGS: <?php echo $row_cgs["com_name"];?>, <?php echo date("d-m-Y",$row_cgs["publishing_date"])?>
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
                                 <h4><i class="icon-reorder"></i>CGS Details</h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                                  <!-- BEGIN FORM-->
                                  <form id="cgs_form" action="<?php echo $folder?>process.php?cat=2&amp;cid=<?php echo $cgs_id;?>" class="form-horizontal" method="post" enctype="multipart/form-data" >
                                   
                                    <div class="row-fluid">
                                      
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group">
                                       <label class="control-label">Governance Index Score</label>
                                       <div class="controls">
                                          <input type="text" name="govt_index" id="govt_index" value="<?php echo stripcslashes($row_cgs["govt_index"])?>"/>
                                          <span class="help-block" ></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                   
                                       <div class="span6 ">
                                         <div class="control-group">
                                       <label class="control-label">India Mandatory/Compliance Score</label>
                                       <div class="controls">
                                         <input type="text" name="india_man" id="india_man" value="<?php echo stripcslashes($row_cgs["india_man"])?>"/>
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
                                       <label class="control-label">Board Of Directors</label>
                                       <div class="controls">
                                         <input type="text" name="board_dir" id="board_dir" value="<?php echo stripcslashes($row_cgs["board_dir"])?>"/>
                                          <span class="help-block" ></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                      <!--/span-->
                                       <div class="span6 ">

                                       </div>
                                    </div>
                                    <!--/row-->

                                     <div class="row-fluid">
                                      
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group">
                                       <label class="control-label">Director's Remuneration</label>
                                       <div class="controls">
                                          <input type="text" name="dir_rem" id="dir_rem" value="<?php echo stripcslashes($row_cgs["dir_rem"])?>"/>
                                          <span class="help-block" ></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                   
                                       <div class="span6 ">
                                         <div class="control-group">
                                       <label class="control-label">Stakeholder Engagement</label>
                                       <div class="controls">
                                         <input type="text" name="stake_eng" id="stake_eng" value="<?php echo stripcslashes($row_cgs["stake_eng"])?>"/>
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
                                       <label class="control-label">Financial Reporting</label>
                                       <div class="controls">
                                          <input type="text" name="fin_rep" id="fin_rep" value="<?php echo stripcslashes($row_cgs["fin_rep"])?>"/>
                                          <span class="help-block" ></span>
                                       </div>
                                       </div>
                                       </div>
                                       <!--/span-->
                                   
                                       <div class="span6 ">
                                         <div class="control-group">
                                       <label class="control-label">Sustainability</label>
                                       <div class="controls">
                                         <input type="text" name="sustain" id="sustain" value="<?php echo stripcslashes($row_cgs["sustain"])?>"/>
                                          <span class="help-block" ></span>
                                       </div>
                                       </div>
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
