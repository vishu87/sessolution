<?php session_start();
require_once('../../sysauth.php');
require_once('../../config.php');


$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if(!$link) {
  die('Failed to connect to server: ' . mysql_error());
}
//Select database
$db = mysql_select_db(DB_DATABASE);
if(!$db) {
  die("Unable to select database");
}
require_once('../../classes/MemberClass.php');
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
  <meta charset="utf-8" />
  <link href="../../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <link href="../../assets/css/metro.css" rel="stylesheet" />
  <script src="../../assets/js/jquery-1.8.3.min.js"></script>
  <link href="../../assets/datepicker/css/datepicker.css" rel="stylesheet" />
  <script type="text/javascript" src="../../assets/datepicker/js/bootstrap-datepicker.js"></script>
  <style type="text/css">.page-title {
          padding: 0px;
          font-size: 30px;
          letter-spacing: -1px;
          display: block;
          color: #666;
          margin: 20px 0px 15px 0px;
          font-weight: 300;
          font-family: 'Open Sans';
        }
</style>

</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body >
<?php 
$rid = $_GET["id"];
$com_id = $_GET["com_id"];
$pa_report = new PA_admin($rid);

$users = fetch_customized_users($pa_report->company_id, $pa_report->year);
$user_string = implode(',', $users);

$sql_custom = mysql_query("SELECT id, name from users where id IN ($user_string)  and customized = '1' ");
?>
<div class="container-fluid">
	<!-- BEGIN PAGE HEADER-->
 

<div class="row-fluid ">
          <div class="span12">
            <div class="portlet box blue">
                    <div class="portlet-title">
                                 
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                                 <!-- BEGIN FORM-->
                                  <form action="process_custom.php?com_id=<?php echo $com_id?>&amp;rid=<?php echo $rid;?>" class="form-horizontal" method="post" enctype="multipart/form-data">
                                   
                                  <?php 
                                   while ($row = mysql_fetch_array($sql_custom)) {
                                     ?>
                                    <div class="row-fluid">
                                       <div class="span6 ">
                                          <div class="control-group">
                                       <label class="control-label">Report for <b><?php echo $row["name"]?></b></label>
                                       <div class="controls">
                                           <input type="file" name="report_<?php echo $row["id"];?>"><br>
                                           <?php
                                           $sql_file = mysql_query("SELECT custom_id, report_upload from customized_reports where user_id='$row[id]' and report_id='$rid' ");
                                           $file = mysql_fetch_array($sql_file);
                                            if($file["report_upload"] != '') {
                                           ?>
                                           <a href="../../custom_reports/<?php echo $file["report_upload"]?>" target="_blanks">View Current</a>&nbsp;&nbsp;<a href="process_custom_delete.php?custom_id=<?php echo $file["custom_id"]?>&amp;com_id=<?php echo $com_id?>&amp;rid=<?php echo $rid;?>">Remove Current</a>
                                           <?php } ?>
                                          <span class="help-block" ></span>
                                       </div>
                                       </div>
                                       </div>
                                     
                                    </div>
                                    <!--/row-->
                                    <?php
                                   }
                                   ?>
                                                                     
                                       <button type="submit" class="btn blue">Submit</button>
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
          $text = 'Successfully updated';
          break;
      case (2):
          $text_class= 'alert-success';
          $text = 'Successfully Removed';
          break;
    }
    echo '<div class="alert '.$text_class.'" style="float:right; margin-top:-10px;">
      <button class="close" data-dismiss="alert"></button>
      '.$text.'
      </div>';
  }
  

  ?>
                                    
                                 </form>
                                 <!-- END FORM--> 
                              </div>
                           </div>
          </div>
        </div>

	


</div>

<script>
    jQuery(document).ready(function() {     
      // initiate layout and plugins
     
      $('.datepicker_month').datepicker({format:'dd-mm-yyyy', minViewMode:'1'});
      
      });
  

  </script>
  <!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
