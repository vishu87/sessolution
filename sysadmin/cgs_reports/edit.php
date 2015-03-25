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
require_once('../../classes/MemberClass.php')
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
$cgs_id = $_GET["id"];

$sql_cgs = mysql_query("SELECT cgs.*, companies.com_name from cgs inner join companies on cgs.com_id = companies.com_id where cgs.cgs_id='$cgs_id' ");
$row_cgs = mysql_fetch_array($sql_cgs);
  
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
                                  <form id="cgs_form" action="process.php?cat=3&amp;cid=<?php echo $cgs_id;?>" class="form-horizontal" method="post" enctype="multipart/form-data" >
                                    <div class="row-fluid">
                                       <div class="span6" >

                                      
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

                                           
                                          }
                                          echo '<div class="alert '.$text_class.'" style="">
                                             <button class="close" data-dismiss="alert"></button>
                                             '.$text.'
                                             </div>';
                                       }
                                       ?>
                                       </div>

                                       <div class="span6" align="right">
                                        <button type="button" class="btn blue" onclick="cgs_submit()"> Submit</button>
                                       </div>

                                     </div>
                                   <div class="row-fluid">
                                       <div class="span6 ">
                                         <div class="control-group">
                                       <label class="control-label">Upload File</label>
                                       <div class="controls">

                                           <input type="file" name="attach_file" id="attach_file"/><br>
                                           <?php
                                            if($row_cgs["report_upload"] != '') {
                                           ?>
                                           <a href="../../cgs/<?php echo $row_cgs["report_upload"]?>" target="_blank">View Current</a>&nbsp;&nbsp;&nbsp;
                                           <a href="process.php?cat=2&amp;cid=<?php echo $row_cgs["cgs_id"]?>" >Remove Current</a>
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
                                       <label class="control-label">Year</label>
                                       <div class="controls">
                                        <?php echo fetch_years('year',$row_cgs["year"]);?>
                                          <span class="help-block" ></span>
                                       </div>
                                       </div>
                                       </div>
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
                                   
                                    
                                      
                                 </form>
                                 <!-- END FORM--> 
                              </div>
                           </div>
          </div>
        </div>                

</div>

<script>

function cgs_submit(){
     
    if($("#attach_file").val() != ''){
      var ext = $("#attach_file").val().split('.').pop().toLowerCase();
      if($.inArray(ext, ['pdf','doc','docx','xls','xlsx']) == -1) {
        alert("Please select a valid file");
      } else {
       $('#cgs_form').submit();
      }
    } else {
      $('#cgs_form').submit();
    }
    
}

    jQuery(document).ready(function() {     
      // initiate layout and plugins
     
     $('.datepicker_month').datepicker({format:'dd-mm-yyyy', minViewMode:'1',}).on('changeDate', function(ev){
        $(this).datepicker('hide');
      });
      
      });
  

  </script>
  <!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
