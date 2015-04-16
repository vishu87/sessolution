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
$sql_vote = mysql_query("SELECT * from voting where id= '$_POST[id]' ");
$vote = mysql_fetch_array($sql_vote);

if(!isset($_POST["id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 1) header("Location: ".STRSITE."access-denied.php");


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
              <select name="resolution_pop" id="resolution_pop" onchange="fetch_reasons(\'reason_pop\',\'resolution_pop\')" >';
              $sql_reso = mysql_query("Select * from resolutions");
              while ($row_reso = mysql_fetch_array($sql_reso)) {
                echo '<option value="'.$row_reso["id"].'" ';
                if($row_reso["id"] == $vote["resolution_type"]) echo 'selected';
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
           <label class="control-label">Reasons</label>
           <div class="controls">
               <select name="reason_pop" id="reason_pop" multiple>';
               if($vote["reasons"] != '')
               $reasons_array = explode(',', $vote["reasons"]);
              else $reasons_array =array();
              $sql_reso = mysql_query("Select * from reasons where res_type_id= '$vote[resolution_type]' ");
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
           <label class="control-label">Resolution Number</label>
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
               <label class="control-label">Management Recommendation</label>
               <div class="controls">
                <select name="man_reco" id="man_reco_pop" class="span9">
                  <option value="1" ';
                    if($vote["man_reco"] == 1) echo ' selected ';
                  echo '>'.$man_recos[1].'</option>
                   <option value="2" ';
                    if($vote["man_reco"] == 2) echo ' selected ';
                  echo '>'.$man_recos[2].'</option>
                  <option value="3" ';
                    if($vote["man_reco"] == 3) echo ' selected ';
                  echo '>'.$man_recos[3].'</option>
                </select>
               </div>
               </div>
           </div>
           <div class="span6 ">
              <div class="control-group">
               <label class="control-label">Proposal by Management or Shareholder</label>
               <div class="controls">
                <select name="man_share_reco_pop" id="man_share_reco_pop" class="span9">
                  <option value="1" ';
                    if($vote["man_share_reco"] == 1) echo ' selected ';
                  echo '>'.$man_share_recos[1].'</option>
                   <option value="2" ';
                    if($vote["man_share_reco"] == 2) echo ' selected ';
                  echo '>'.$man_share_recos[2].'</option>
                </select>
               </div>
               </div>
           </div>
        </div>
           <div class="row-fluid">
             <div class="span6 ">
               <div class="control-group">
             <label class="control-label">Type of Business</label>
             <div class="controls">
                 <select name="type_business_pop" id="type_business_pop">';
                   
                    for ($i = 0; $i< sizeof($types_business); $i++ ) {
                      echo '<option value="'.$i.'" ';
                      if($i == $vote["type_business"]) echo 'selected';
                      echo '>'.$types_business[$i].'</option>';
                    }
                  echo '
                  </select>

             </div>
             </div>
             </div>
             <!--/span-->
             <div class="span6 ">
                 <div class="control-group">
             <label class="control-label">Type of Resolution</label>
             <div class="controls">
                  <select name="type_res_os_pop" id="type_res_os_pop">';

                    for ($i = 0; $i< sizeof($types_res_os); $i++ ) {
                      echo '<option value="'.$i.'" ';
                      if($i == $vote["type_res_os"]) echo 'selected';
                      echo '>'.$types_res_os[$i].'</option>';
                    }
                  echo '
                  </select>

             </div>
             </div>
             </div>
             <!--/span-->
          </div>
         <div class="row-fluid">
             <div class="span6 ">
               <div class="control-group">
             <label class="control-label">Focus</label>
             <div class="controls">
                 <select name="focus" id="focus_pop"><option value="0">Select</option>';
                    
                    for ($i = 1; $i< sizeof($focus); $i++ ) {
                      echo '<option value="'.$i.'" ';
                      if($i == $vote["focus"]) echo 'selected';
                      echo '>'.$focus[$i].'</option>';
                    }
                  echo '
                  </select>

             </div>
             </div>
             </div>
             <!--/span-->
             <div class="span6 ">

             </div>
             <!--/span-->
          </div>
        <div class="row-fluid">

           <!--/span-->
           <div class="span6 ">
              <div class="control-group">
           <label class="control-label"></label>
           <div class="controls">
             <button type="button" onclick="voting_submit('.$vote["report_id"].','.$_POST["id"].')" class="btn blue" id="vote_s"><i class="icon-ok"></i>Update Vote</button>
             
           </div>
           </div>
           </div>
           <!--/span-->
        </div>
        <!--/row-->
     </form>';





?>