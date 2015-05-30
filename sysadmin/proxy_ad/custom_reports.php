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
  <script src="../../assets/js/custom.js"></script>
  <link href="../../assets/datepicker/css/datepicker.css" rel="stylesheet" />
  <script type="text/javascript" src="../../assets/datepicker/js/bootstrap-datepicker.js"></script>
  <script src="../../assets/bootstrap/js/bootstrap.min.js"></script>

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
  $pa_report = new PA_admin($rid);
  $com_id = $pa_report->company_id;

  $users = fetch_customized_users($pa_report->company_id, $pa_report->year);
  $user_string = implode(',', $users);

  $sql_custom = mysql_query("SELECT id, name from users where id IN ($user_string)  and customized = '1' ");
  ?>
  <div class="container-fluid">
   <!-- BEGIN PAGE HEADER-->

   <div style="margin:10px 0">
    <?php if($pa_report->custom_report_freeze == 0){ ?>
    <button class="btn green freeze" data-type="1" onclick="custom_report_freeze(<?php echo $rid ?>)">Freeze</button>
    <?php } else { ?>
    <button class="btn yellow freeze" data-type="2" onclick="custom_report_freeze(<?php echo $rid ?>)">Unfreeze</button>
    <?php } ?>
  </div>

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
       <form action="process_custom.php?com_id=<?php echo $com_id?>&amp;rid=<?php echo $rid;?>" class="horizontal-form" method="post" enctype="multipart/form-data" id="custom-form">

        <?php 
        while ($row = mysql_fetch_array($sql_custom)) {

          $sql_file = mysql_query("SELECT custom_id, report_upload, check_id, custom_reco from customized_reports where user_id='$row[id]' and report_id='$rid' limit 1");
          $file = mysql_fetch_array($sql_file);
          ?>

          <div class="row-fluid" style="border-top:1px solid #EEE; border-bottom:1px solid #EEE; margin-bottom:20px; padding:10px 0">

            <div class="span1 ">
              <label class="control-label"><b><?php echo $row["name"]?></b></label>
            </div>

            <div class="span3 ">
              <label class="control-label"><b>Standard Recommendations?</b></label>
              <select name="check_<?php echo $row["id"]?>">
                <option value="0">Yes</option>
                <option value="1" <?php if($file["check_id"] == 1) echo 'selected'; ?> >No</option>
              </select>
            </div>
            <div class="span5 ">
              <br>
              <a href="javascript:;" onclick="open_custom_reco('<?php echo name_filter($row["name"]) ?>',<?php echo $pa_report->id ?>,<?php echo $row["id"] ?>)" class="btn blue">Custom Recommendations</a>
            </div>

            <div class="span3 ">
              <div class="control-group">
               <label class="control-label">Report for <b><?php echo $row["name"]?></b></label>
               <div class="controls">
                 <input type="file" name="report_<?php echo $row["id"];?>"><br>
                 <?php

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
         <?php if($pa_report->custom_report_freeze == 0){ ?>
          <button type="button" class="btn formsubmit blue" onclick="$('#custom-form').submit()">Save</button>
         <?php } else { ?>
          <button type="button" class="btn formsubmit">Reports are freezed</button>
         <?php } ?>

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

function open_custom_reco(client_name,report_id, user_id){
  window.parent.$("#stack1").modal('show');
  var item = window.parent.$("#stack1");
  item.find(".modal-header h3").text("Customized Resolutions : "+client_name); 
  item.find(".modal-body").html("<p>Loading...</p>");

   var file = 'load_custom_voting_ui';
   $.post("../ajax/"+ file +".php", {report_id:report_id, user_id:user_id}, function(data) {
       item.find(".modal-body").html(data);
   });
}
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
