<?php 
if(!isset($title)) {
		die('This page can not be viewed');
	}
?>
<div class="container-fluid">
  <!-- BEGIN PAGE HEADER-->
  <div class="row-fluid">
    <h3 class="page-title">
      Subscribe
      <small></small>
    </h3>
  </div>

<?php
if(isset($_POST["report_id"])) {

  $report_id = mysql_real_escape_string($_POST["report_id"]);
  $com_id = mysql_real_escape_string($_POST["com_id"]);
  $report_type = mysql_real_escape_string($_POST["report_type"]);

  $query_check = mysql_query("SELECT id from subscription_request where com_id='$com_id' and report_type='$report_type' and user_id='$_SESSION[MEM_ID]' and status='0' ");
  if(mysql_num_rows($query_check) == 0){
    mysql_query("INSERT into subscription_request (report_id,com_id,report_type, user_id, add_date) values ('$report_id','$com_id','$report_type','$_SESSION[MEM_ID]','".strtotime("now")."' ) ");
  }

 ?>
<div class="alert alert-success">
                  
                  <strong>Your subscription request is successfully received by us.</strong> We will contact you soon.
                </div>
 <?php
}
else {

  $rep_type = mysql_real_escape_string($_POST["rep_type"]);
  $rep_id = mysql_real_escape_string($_POST["rep_id"]);

  switch ($rep_type) {
    case '1':
      $report = new PA($rep_id);
      $report_type = 'Proxy Advisory';
      break;
    
      case '2':
     $report = new CGS($rep_id);
      $report_type = 'Governance Score';
      break;

        case '3':
      $report = new Research($rep_id);
       $report_type = 'Governance Research';
      break;
  }
$query = mysql_query("SELECT com_bse_code from companies where com_id='".$report->company_id."' ");
$row = mysql_fetch_array($query);
?>


<div class="portlet box blue">
                              <div class="portlet-title">
                                 <h4><i class="icon-reorder"></i>Product Details</h4>
                                 
                              </div>
                              <div class="portlet-body form">
                                 <!-- BEGIN FORM-->
                                 <div class="form-horizontal form-view">
                                  
                                    <div class="row-fluid">
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label" for="firstName">Company Name:</label>
                                             <div class="controls">
                                                <span class="text"><?php echo $report->company_name; ?></span>
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label" for="lastName">Company BSE Code:</label>
                                             <div class="controls">
                                                <span class="text"><?php echo $row["com_bse_code"]; ?></span>
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                    </div>
                                    <!--/row-->
                                    <div class="row-fluid">
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label">Report Type:</label>
                                             <div class="controls">
                                                <span class="text"><?php echo $report_type; ?></span> 
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                         
                                       </div>
                                       <!--/span-->
                                    </div>
                                    <!--/row-->        
                                    
                                    <form action="" method="post">
                                      <input type="hidden" name="report_id" value="<?php echo $rep_id; ?>">
                                      <input type="hidden" name="com_id" value="<?php echo $report->company_id; ?>">
                                      <input type="hidden" name="report_type" value="<?php echo $rep_type; ?>">
                                    <div class="form-actions">
                                       <button type="submit" class="btn blue"><i class="icon-ok"></i> Subscribe</button>
                                    </div>
                                  </form>
                                 </div>
                                 <!-- END FORM-->  
                              </div>
                           
                  </div>
                  <?php } ?>
               </div>
         
<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:750px;">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Modal header</h3>
  </div>
  <div class="modal-body" id="modal-body">
    
  </div>
  <div class="modal-footer">

    <button class="btn" data-dismiss="modal" aria-hidden="true" id="close_button">Close</button>
  </div>
</div>  
				
				
</div>
