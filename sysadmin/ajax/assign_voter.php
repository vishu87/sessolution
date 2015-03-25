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

$request_id = $_POST["request_id"];

if(!isset($_POST["request_id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 1) header("Location: ".STRSITE."access-denied.php");

$sql_voter = mysql_query("SELECT proxy_id, voter_id from proxies where id='$request_id' ");
$voter = mysql_fetch_array($sql_voter);

echo '<div class="alert alert-info">
	<strong>Current Voter - </strong>';
if($voter["voter_id"] == 0) echo 'Not Assigned';
else {
	$sql_name = mysql_query("SELECT proxy_voters.name, locations.place, proxy_voters.location from proxy_voters inner join locations on proxy_voters.location = locations.id where proxy_voters.vid='$voter[voter_id]' ");
	$name = mysql_fetch_array($sql_name);
	echo $name["name"].', '.$name["place"];
  $location_id = $name["location"]; 
  $voter_id = $voter["voter_id"];
}



?>
<?php
	echo '
</div>';
 $sql_other = mysql_query("SELECT distinct(proxy_voters.name), proxy_voters.vid, proxy_voters.location from proxy_voters inner join proxies on proxy_voters.vid = proxies.voter_id where proxies.proxy_id='$voter[proxy_id]' ");
 echo '<div class="alert alert-info">
  <strong>Selected Voters for this meeting - </strong>';
  while ($row_other = mysql_fetch_array($sql_other)) {
    echo $row_other["name"].' ';
    $loc_id =  $row_other["location"];
    $v_id = $row_other["vid"];
  }

  if(!$location_id){
    $location_id = $loc_id;
    $voter_id = $v_id;
  }
  echo '</div>';
if($voter["voter_id"] != 0) {
?>
<button type="button" class="btn red" onclick="unassign_voter(<?php echo $request_id;?>)"><i class="icon-ok"></i> Unassign</button>
<?php } ?>
<form action="#" class="horizontal-form">
    <h3 class="form-section">Assign Voter</h3>
    <div class="row-fluid">
       <div class="span6 ">
          <div class="control-group">
             <label class="control-label" for="firstName">Location</label>
             <div class="controls">
                <select class="m-wrap span12" name="location" id="location" onchange="fetch_voters()">
                	<option value="0">Select</option>
                	<?php 
                		$sql_loc = mysql_query("SELECT * from locations where status=0 order by place asc");
                		while ($location = mysql_fetch_array($sql_loc)) {
                			echo '<option value="'.$location["id"].'" ';
                        if($location["id"] == $location_id) echo 'selected';
                      echo '>'.$location["place"].'</option>';
                		}
                	?>
                </select>                                                
             </div>
          </div>
       </div>
       <!--/span-->
       <div class="span6 ">
          <div class="control-group">
             <label class="control-label" for="lastName">Name</label>
             <div class="controls">
                <select class="m-wrap span12" name="voter" id="voter_names">
                	<?php 
                    if($location_id && $location_id != 0){
                      $sql_vot = mysql_query("SELECT vid, name from proxy_voters where location='$location_id' ");
                      while ($voter = mysql_fetch_array($sql_vot)) {
                        echo '<option value="'.$voter["vid"].'" ';
                          if($voter["vid"] == $voter_id) echo 'selected';
                        echo '>'.$voter["name"].'</option>';
                      }
                    }
                  ?>
                </select>   
             </div>
          </div>
       </div>
       <!--/span-->
    </div>
    <!--/row-->
   
    
       <button type="button" class="btn blue" onclick="submit_voter(<?php echo $request_id;?>)"><i class="icon-ok"></i> Assign/Change</button>
     
 </form>
