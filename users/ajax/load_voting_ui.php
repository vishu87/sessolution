<?php session_start();
require_once('../../sysauth.php');
ie("Unable to select database");
}
$sql_vote = mysql_query("SELECT * from voting where id= '$_POST[id]' ");
$vote = mysql_fetch_array($sql_vote);

if(!isset($_POST["id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] == 0) header("Location: ".STRSITE."access-denied.php");


echo '<form id="voting_form" action="" class="form-horizontal" method="post" enctype="multipart/form-data" >
                                   
       <div class="row-fluid">
           <div class="span6 ">
             <div class="control-group">
           <label class="control-label">Resolution Name</label>
           <div class="controls">
              <input type="text" name="resolution_name_pop" id="resolution_name_pop" value="'.$vote["resolution_name"].'">';
              
              echo '
              <span class="help-block"></span>
           </div>
           </div>
           </div>
           <!--/span-->
            <div class="span6 ">
             <div class="control-group">
           <label class="control-label">Resolution Type</label>
           <div class="controls">
              <select name="resolution_pop" id="resolution_pop">';
              $sql_reso = mysql_query("Select * from resolutions");
              while ($row_reso = mysql_fetch_array($sql_reso)) {
                echo '<option value="'.$row_reso["id"].'" ';
                if($row_reso["id"] == $vote["resolution"]) echo 'selected';
                echo '>'.$row_reso["resolution"].'</option>';
              }

              echo '</select>
              <span class="help-block"></span>
           </div>
           </div>
           </div>
           <!--/span-->
           <!--/span-->
        </div>
        <!--/row-->
        <div class="row-fluid">
        <div class="span6 ">
             <div class="control-group">
           <label class="control-label">SES Recommendation</label>
           <div class="controls">
              <select name="ses_reco_pop" id="ses_reco_pop">';
                
                  $sql_reso = mysql_query("Select * from ses_recos");
                  while ($row_reso = mysql_fetch_array($sql_reso)) {
                    echo '<option value="'.$row_reso[id].'" ';
                    if($row_reso["id"] == $vote["ses_reco"]) echo 'selected';
                    echo '>'.$row_reso["reco"].'</option>';
                  }
echo '
                </select>
           </div>
           </div>
         </div>

           <div class="span6 ">
             <div class="control-group">
           <label class="control-label">Details</label>
           <div class="controls">
           	<textarea name="detail_pop" id="detail_pop">'.$vote["detail"].'</textarea>   
           </div>
           </div>
           </div>
           <!--/span-->
           
           <!--/span-->
        </div>
        <!--/row-->

         <div class="row-fluid">
           <div class="span6 ">
             <div class="control-group">
           <label class="control-label">Upload file</label>
           <div class="controls">
               <select name="reason_pop" id="reason_pop" multiple>';
               if($vote["reasons"] != '')
               $reasons_array = explode(',', $vote["reasons"]);
              else $reasons_array =array();
              $sql_reso = mysql_query("Select * from reasons");
              while ($row_reso = mysql_fetch_array($sql_reso)) {
              	echo '<option value="'.$row_reso["id"].'" ';
                if(in_array($row_reso["id"], $reasons_array)) echo 'selected';
                echo '>'.$row_reso["reason"].'</option>';
              }

              echo '</select>
              <span class="help-block" id="fileInfo"></span>
           </div>
           </div>
           </div>
           <!--/span-->
           <div class="span6 ">
             <div class="control-group">
           <label class="control-label">Details</label>
           <div class="controls">
            <input type="text" name="resolution_number" id="resolution_number_pop" value="'.$vote["resolution_number"].'">
           </div>
           </div>
           </div>
           <!--/span-->
        </div>
        <!--/row-->

        <div class="row-fluid">
           <div class="span6 ">
             <div class="control-group">
           <label class="control-label"></label>
           <div class="controls">
           	 <button type="button" onclick="voting_submit('.$vote["report_id"].','.$_POST["id"].')" class="btn blue" id="vote_s"><i class="icon-ok"></i>Update Vote</button>
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