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
if($_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 1) header("Location: ".STRSITE."access-denied.php");

$report_id = $_POST["id"];
$rep_type = $_POST["type"];
$count = $_POST["count"];

switch ($rep_type) {
  case '1':
    $sql_rep = mysql_query("SELECT meeting_date from proxy_ad where id='$report_id' ");
    $rep = mysql_fetch_array($sql_rep);
    $meeting_date = $rep["meeting_date"];
    break;
  
  case '2':
      $sql_rep = mysql_query("SELECT publishing_date from cgs where cgs_id='$report_id' ");
    $rep = mysql_fetch_array($sql_rep);
    $meeting_date = $rep["publishing_date"];
    break;
   

  case '3':
    $sql_rep = mysql_query("SELECT publishing_date from research where res_id='$report_id' ");
    $rep = mysql_fetch_array($sql_rep);
    $meeting_date = $rep["publishing_date"];
    break;

}

$sql = mysql_query("SELECT id, an_id, deadline,completed_on from report_analyst where report_id= '$report_id' and rep_type='$rep_type' and type= '1' ");
$data = mysql_fetch_array($sql);

$sql = mysql_query("SELECT id, an_id, deadline,completed_on from report_analyst where report_id= '$report_id' and rep_type='$rep_type' and type= '2' ");
$analysis = mysql_fetch_array($sql);

$sql = mysql_query("SELECT id, an_id, deadline,completed_on from report_analyst where report_id= '$report_id' and rep_type='$rep_type' and type= '3' ");
$review = mysql_fetch_array($sql);


if(!isset($_POST["id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] == 0) header("Location: ".STRSITE."access-denied.php");


echo '<form id="voting_form" action="" class="form-horizontal" method="post" enctype="multipart/form-data" >
                                   
       <div class="row-fluid">
           <div class="span12 ">
             <div class="control-group">
           <label class="control-label">Data</label>
           <div class="controls">
              <select name="data_an_id" id="data_an_id"><option value=""></option>';
              $sql_ana = mysql_query("SELECT an_id, name from analysts where active=0 ");
              while($row = mysql_fetch_array($sql_ana)){
                echo '<option value="'.$row["an_id"].'" ';
                if($data["an_id"] == $row["an_id"]) echo 'selected';
                echo '>'.$row["name"].'</option>';
              }
              echo '</select> &nbsp;&nbsp;
              <input type="text" class="datepicker_month" placeholder="Deadline" name="data_deadline" id="data_deadline" value="';
              if($data["deadline"]!='') echo date("d-m-Y", $data["deadline"]);
              echo '">
              &nbsp;&nbsp;Status: ';
              echo ($data["completed_on"] == '')?'Pending':'Completed On '.date("d M y", $data["completed_on"]);
              echo '&nbsp;&nbsp;';
              if($data["completed_on"] != '') echo '<a href="javascript:void(0)"  class="btn red" data-toggle="modal" onclick="mark_incomplete('.$count.','.$report_id.','.$rep_type.','.$data["id"].');">Mark Incomplete</a>';
              else echo '<a href="javascript:void(0)"  class="btn green" data-toggle="modal" onclick="mark_complete('.$count.','.$report_id.','.$rep_type.','.$data["id"].');">Mark Complete</a>';
              echo '
              <span class="help-block"></span>
           </div>
           </div>
           </div>
        </div>
        <!--/row-->

        <div class="row-fluid">
           <div class="span12 ">
             <div class="control-group">
           <label class="control-label">Analysis</label>
           <div class="controls">
              <select name="analysis_an_id" id="analysis_an_id"><option value=""></option>';
              $sql_ana = mysql_query("SELECT an_id, name from analysts where active=0 ");
              while($row = mysql_fetch_array($sql_ana)){
                echo '<option value="'.$row["an_id"].'" ';
                if($analysis["an_id"] == $row["an_id"]) echo 'selected';
                echo '>'.$row["name"].'</option>';
              }
              echo '</select> &nbsp;&nbsp;
              <input type="text" class="datepicker_month" placeholder="Deadline" name="analysis_deadline" id="analysis_deadline" value="';
              if($analysis["deadline"]!='') echo date("d-m-Y", $analysis["deadline"]);
              echo '">
               &nbsp;&nbsp;Status: ';
              echo ($analysis["completed_on"] == '')?'Pending':'Completed On '.date("d M y", $analysis["completed_on"]);
              echo '&nbsp;&nbsp;';
              if($analysis["completed_on"] != '') echo '<a href="javascript:void(0)"  class="btn red" data-toggle="modal" onclick="mark_incomplete('.$count.','.$report_id.','.$rep_type.','.$analysis["id"].');" > Mark Incomplete</a>';
              else echo '<a href="javascript:void(0)"  class="btn green" data-toggle="modal" onclick="mark_complete('.$count.','.$report_id.','.$rep_type.','.$analysis["id"].');">Mark Complete</a>';
              echo '
              <span class="help-block"></span>
           </div>
           </div>
           </div>
        </div>
        <!--/row-->

        <div class="row-fluid">
           <div class="span12 ">
             <div class="control-group">
           <label class="control-label">Review</label>
           <div class="controls">
              <select name="review_an_id" id="review_an_id"><option value=""></option>';
              $sql_ana = mysql_query("SELECT an_id, name from analysts where active=0 ");
              while($row = mysql_fetch_array($sql_ana)){
                echo '<option value="'.$row["an_id"].'" ';
                if($review["an_id"] == $row["an_id"]) echo 'selected';
                echo '>'.$row["name"].'</option>';
              }
              echo '</select> &nbsp;&nbsp;
              <input type="text" class="datepicker_month" placeholder="Deadline" name="review_deadline" id="review_deadline" value="';
              if($review["deadline"]!='') echo date("d-m-Y", $review["deadline"]);
              else {
                if($rep_type == 1 ) $dead_an = $meeting_date - 10*86400;
                else $dead_an = $meeting_date - 0*86400;

                echo date("d-m-Y", $dead_an);
              }
              echo '">
               &nbsp;&nbsp;Status: ';
              echo ($review["completed_on"] == '')?'Pending':'Completed On '.date("d M y", $review["completed_on"]);
              echo '&nbsp;&nbsp;';
              if($review["completed_on"] != '') echo '<a href="javascript:void(0)"  class="btn red" data-toggle="modal" onclick="mark_incomplete('.$count.','.$report_id.','.$rep_type.','.$review["id"].');">Mark Incomplete</a>';
              else echo '<a href="javascript:void(0)"  class="btn green" data-toggle="modal" onclick="mark_complete('.$count.','.$report_id.','.$rep_type.','.$review["id"].');">Mark Complete</a>';
              echo '
              <span class="help-block"></span>
           </div>
           </div>
           </div>
        </div>
        <!--/row-->
       
        <div class="row-fluid">
           <div class="span6 ">
             <div class="control-group">
           <label class="control-label"></label>
           <div class="controls">
           	 <button type="button" onclick="analyst_submit('.$count.','.$report_id.','.$rep_type.')" class="btn blue" id="vote_s"><i class="icon-ok"></i>Update</button>
           </div>
           </div>
           </div>
           <!--/span-->
           <div class="span6 ">
              
           </div>
           <!--/span-->
        </div>
        <!--/row-->
     </form>';





?>