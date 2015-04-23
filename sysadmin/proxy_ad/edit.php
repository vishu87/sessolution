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
    }</style>

  </head>
  <!-- END HEAD -->
  <!-- BEGIN BODY -->
  <body >
    <?php 
    $rid = $_GET["id"];

    $sql_rep = mysql_query("SELECT proxy_ad.*, companies.com_name from proxy_ad inner join companies on proxy_ad.com_id=companies.com_id where proxy_ad.id='$rid' ");
    $report = mysql_fetch_array($sql_rep);

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
         <form action="process.php?cat=2&amp;rid=<?php echo $rid;?>" class="form-horizontal" method="post" enctype="multipart/form-data" id="submit_form">

           <div class="row-fluid">
            <div class="span6">
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
              echo '<div class="alert '.$text_class.'" style="">
              <button class="close" data-dismiss="alert"></button>
              '.$text.'
              </div>';
            }


            ?>
          </div>
          <div class="span6" align="right">
            <button type="button" onclick="check_submit()" class="btn blue" style="margin-bottom:20px;">Update</button>
          </div>
        </div>
        <div class="row-fluid">
         <div class="span6 ">
           <div class="control-group">
             <label class="control-label">Meeting Date</label>
             <div class="controls">
              <input type="hidden" name="com_id" value="<?php echo $report["com_id"]?>">
              <input type="text" name="meeting_date" class="datepicker_month" value="<?php echo date("d-m-Y", $report["meeting_date"]); ?>">
              <span class="help-block"></span>
            </div>
          </div>
        </div>
        <!--/span-->
        <div class="span6 ">
          <div class="control-group">
           <label class="control-label">Meeting Type</label>
           <div class="controls">
             <select name="meeting_type">
              <?php
              $sql_type = mysql_query("SELECT * from met_type");
              while ($type = mysql_fetch_array($sql_type)) {
                echo '<option value="'.$type["id"].'" ';
                if($type["id"] == $report["meeting_type"]) echo 'selected';
                echo '>'.$type["type"].'</option>';
              }
              ?>
            </select>

            <select name="ccm_type" class="ccm_type"  <?php if($report["meeting_type"] != 5) echo 'style="display:none"'; ?>>
              <option value="0">None</option>
              <?php
              foreach ($ccm_types as $key => $ccm_type) {
                ?>
                <option value="<?php echo $key ?>" <?php if($key == $report["ccm_type"]) echo 'selected' ?> ><?php  echo $ccm_type ?></option>
                <?php
              }
              ?>
            </select>
          </div>
        </div>
      </div>
      <!--/span-->
    </div>
    <!--/row-->
    <div class="row-fluid">
     <div class="span6 ">
       <div class="control-group">
         <label class="control-label">Record Date</label>
         <div class="controls">
           <input type="text" class="datepicker_month_top"  name="record_date" placeholder="Record Date" value="<?php echo ($report["record_date"])?date("d-m-Y", $report["record_date"]):''; ?>">
           <span class="help-block" ></span>
         </div>
       </div>
     </div>
     <!--/span-->
     <div class="span6 ">
      <div class="control-group">
       <label class="control-label">E-voting Start</label>
       <div class="controls">
         <input type="text" class="datepicker_month_top"  name="evoting_start" placeholder="" value="<?php echo ($report["evoting_start"])?date("d-m-Y", $report["evoting_start"]):''; ?>">
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
       <label class="control-label">E-voting End</label>
       <div class="controls">
         <input type="text" class="datepicker_month_top"  name="evoting_end" placeholder="" value="<?php echo ($report["evoting_end"])?date("d-m-Y", $report["evoting_end"]):''; ?>">
         <span class="help-block" ></span>
       </div>
     </div>
   </div>
   <!--/span-->
   <div class="span6 ">
    <div class="control-group">
     <label class="control-label">E-voting Platform</label>
     <div class="controls">
       <input type="text"  name="evoting_plateform" placeholder="" value="<?php echo $report["evoting_plateform"]?>">
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
   <label class="control-label">Report</label>
   <div class="controls">
     <input type="file" name="report" id="report"><br>
     <?php
     if($report["report"] != '') {
       ?>
       <a href="../../proxy_reports/<?php echo $report["report"]?>" target="_blank">View Current</a>&nbsp;&nbsp;<a href="<?php echo $folder;?>process.php?cat=5&amp;rid=<?php echo $rid;?>">Remove Current</a>
       <?php } ?>
       <span class="help-block" ></span>
     </div>
   </div>
 </div>
 <!--/span-->
 <div class="span6 ">
   <div class="control-group">
     <label class="control-label">Notice</label>
     <div class="controls">
       <input type="file" name="notice"><br>
       <?php
       if($report["notice"] != '') {
         ?>
         <a href="../../proxy_notices/<?php echo $report["notice"]?>" target="_blank">View Current</a>&nbsp;&nbsp;<a href="<?php echo $folder;?>process.php?cat=6&amp;rid=<?php echo $rid;?>">Remove Current</a>
         <?php } ?>
         <br>OR<br>
         <input type="text" name="notice_link" placeholder="Add Link" value="<?php echo $report["notice_link"]?>">
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
       <label class="control-label">Teasor</label>
       <div class="controls">
         <input type="text" name="teasor" placeholder="Teasor Link" value="<?php echo $report["teasor"]?>">
         <span class="help-block" ></span>
       </div>
     </div>
   </div>
   <!--/span-->
   <div class="span6 ">
    <div class="control-group">
     <label class="control-label">Annual Report</label>
     <div class="controls">
       <input type="text" name="annual_report" placeholder="Annual Report Link" value="<?php echo $report["annual_report"]?>">
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
     <label class="control-label">Meeting Outcome</label>
     <div class="controls">
       <input type="text" name="meeting_outcome" placeholder="Meeting Outcome Link" value="<?php echo $report["meeting_outcome"]?>">
       <span class="help-block" ></span>
     </div>
   </div>
 </div>
 <!--/span-->
 <div class="span6 ">
  <div class="control-group">
   <label class="control-label">Meeting Minutes</label>
   <div class="controls">
     <input type="text" name="meeting_minutes" placeholder="Meeting Minutes Link" value="<?php echo $report["meeting_minutes"]?>" >
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
     <label class="control-label">Proxy Slip</label>
     <div class="controls">
       <input type="file" name="proxy_slip"><br>
       <?php
       if($report["proxy_slip"] != '') {
         ?>
         <a href="../../proxy_slips/<?php echo $report["proxy_slip"]?>" target="_blank">View Current</a>&nbsp;&nbsp;<a href="<?php echo $folder;?>process.php?cat=7&amp;rid=<?php echo $rid;?>">Remove Current</a>
         <?php } ?>
         <span class="help-block" ></span>
       </div>
     </div>
   </div>
   <!--/span-->
   <div class="span6 ">
    <div class="control-group">
     <label class="control-label">Attendance Slip</label>
     <div class="controls">
       <input type="file" name="attendance_slip"><br>
       <?php
       if($report["attendance_slip"] != '') {
         ?>
         <a href="../../attendance_slips/<?php echo $report["attendance_slip"]?>" target="_blank">View Current</a>&nbsp;&nbsp;<a href="<?php echo $folder;?>process.php?cat=9&amp;rid=<?php echo $rid;?>">Remove Current</a>
         <?php } ?>
         <span class="help-block" ></span>
       </div>
     </div>
   </div>
   <!--/span-->
 </div>

 <div class="row-fluid">
   <div class="span6 ">
     <div class="control-group">
       <label class="control-label">Meeting Time</label>
       <div class="controls">
         <input type="text" name="meeting_time" placeholder="Meeting Time" value="<?php echo $report["meeting_time"]?>">
         <span class="help-block" ></span>
       </div>
     </div>
   </div>
   <!--/span-->
   <div class="span6 ">
    <div class="control-group">
     <label class="control-label">Meeting Venue</label>
     <div class="controls">
       <input type="text" name="meeting_venue" placeholder="Meeting Venue" value="<?php echo $report["meeting_venue"]?>">
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
     <label class="control-label">Abridged Analyst</label>
     <div class="controls">
       <select name="an_id" value="<?php echo $report["an_id"]?>">
        <option value="0">Select</option>
        <?php
        $sql_an = mysql_query("SELECT an_id, name from  analysts where active=0 order by name asc ");
        while ($row_an = mysql_fetch_array($sql_an)) {
          echo '<option value="'.$row_an["an_id"].'" ';
          echo ($row_an["an_id"] == $report["an_id"])?'selected':'';
          echo '>'.$row_an["name"].'</option>';
        }
        ?>
      </select>
      <span class="help-block" ></span>
    </div>
  </div>
</div>
<!--/span-->
<div class="span6 ">
  <div class="control-group">
   <label class="control-label">Voting Results (Clause 35A)</label>
   <div class="controls">
     <input type="text" name="meeting_results" placeholder="Meeting Results" value="<?php echo $report["meeting_results"]?>">
     <span class="help-block" ></span>
   </div>
 </div>
</div>
<!--/span-->
</div>
<!--/row-->


<!--/row-->




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

      $('.datepicker_month').datepicker({format:'dd-mm-yyyy', minViewMode:'1',}).on('changeDate', function(ev){
        $(this).datepicker('hide');
      });

      $('.datepicker_month_top').datepicker({orientation: "top auto",format:'dd-mm-yyyy', minViewMode:'1',}).on('changeDate', function(ev){
        $(this).datepicker('hide');
      });

      $("select[name=meeting_type]").change(function(){
        if($(this).val() == 5){
          $(".ccm_type").show();
        } else {
          $(".ccm_type").hide();
        }
      });
      
    });

function check_submit(){

  if($("#report").val() != ''){

    var ext = $("#report").val().split('.').pop().toLowerCase();

    if($.inArray(ext, ['pdf','doc','docx','xls','xlsx']) == -1) {
      alert("Please Choose a valid file");
      return false;
    } else {
      $("#submit_form").submit();
    }

  } else {
    $("#submit_form").submit();
  }

}



</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
