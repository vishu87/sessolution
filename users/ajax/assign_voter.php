<?php session_start();
require_once('../../auth.php');

$report_id = $_POST["report_id"];

if(!isset($_POST["report_id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 0) header("Location: ".STRSITE."access-denied.php");

$sql_voter = mysql_query("SELECT voter_id from self_proxies where  proxy_id='$report_id' and user_id='$_SESSION[MEM_ID]' ");
$voter = mysql_fetch_array($sql_voter);

echo '<div class="alert alert-info">
	<strong>Current Voter - </strong>';
if(mysql_num_rows($sql_voter) == 0) echo 'Not Assigned';
else {
	$sql_name = mysql_query("SELECT * from self_proxy_voters where vid='$voter[voter_id]' ");
	$name = mysql_fetch_array($sql_name);
	echo $name["name"];
}
?>
<?php
	echo '
</div>';
?>

<form action="#" class="horizontal-form">
    <h3 class="form-section">Assign Voter</h3>
    <div class="row-fluid">
      
       <!--/span-->
       <div class="span12 ">
          <div class="control-group">
             <label class="control-label" for="lastName">Name</label>
             <div class="controls">
                <select class="m-wrap span12" name="voter" id="voter_names">
                  <?php 
                    $sql = mysql_query("SELECT vid, name from self_proxy_voters where user_id='$_SESSION[MEM_ID]' ");
                    while ($row = mysql_fetch_array($sql)) {
                      echo '<option value="'.$row["vid"].'" ';
                      if($voter["voter_id"] == $row["vid"]) echo 'selected';
                      echo '>'.$row["name"].'</option>';
                    }

                  ?>            	
                </select>   
             </div>
          </div>
       </div>
       <!--/span-->
    </div>
    <!--/row-->
   
    
       <button type="button" class="btn blue" onclick="submit_voter(<?php echo $report_id;?>)"><i class="icon-ok"></i> Assign/Change</button>
     
 </form>
