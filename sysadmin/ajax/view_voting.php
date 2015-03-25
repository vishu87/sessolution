<?php session_start();
require_once('../../sysauth.php');
require_once('../../config.php');
require_once('../../classes/GeneralVoting.php');
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

if(!isset($_POST["request_id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 1 ) header("Location: ".STRSITE."access-denied.php");

$sql = mysql_query("SELECT * from proxies where id='$request_id' ");
$res = mysql_fetch_array($sql);
$report_id = $res["proxy_id"];
$user_id = $res["user_id"];

 $voters = array();
    $sql_met = mysql_query("SELECT vid,name from proxy_voters ");
    while ($row_met = mysql_fetch_array($sql_met)) {
   $voters[$row_met["vid"]] = $row_met["name"];
 }

$update = array();
echo '<table class="table table-bordered table-hover">';

		$ar_fields_name = array("Proxy Request","Proxy Appointed","Proxy Form","Proxy Slip");
		$ar_fields_type = array("ProxyRequest","ProxyAppointed","ProxyForm","ProxySlip");
		
		$query_data = mysql_query("SELECT * from proxies where id='$request_id' ");
		$data = mysql_fetch_array($query_data);
		$update["ProxyRequest"] = (mysql_num_rows($query_data) == 0)?'No':'Requested on '.date("d-M-Y", $data["add_date"]);
		if(mysql_num_rows($query_data) == 0){

		} else {
			$update["ProxyAppointed"] = ($data["voter_id"] != 0)?$voters[$data["voter_id"]].' / Appointed on '.date("d-M-Y", $data["appoint_date"]):'No';
			$update["ProxyForm"] .= ($data["form"] != '')?'<a href="../user_proxy_forms/'.$data["form"].'" target="_blank">View</a> uploaded on '.date("d-M-Y", $data["form_upload_date"]):'No';
			$update["ProxySlip"] .= ($data["slip"] != '')?'<a href="../user_proxy_slips/'.$data["slip"].'" target="_blank">View</a> completed on '.date("d-M-Y", $data["final_date"]):'No';
		}
		
	$count = 0;
	
	foreach ($ar_fields_type as $ar) {
			echo "<tr><td>".$ar_fields_name[$count]."</td><td>".$update[$ar]."</td></tr>";
		$count++;
	}

echo '</table>';

$recos = array();
$sql_reco = mysql_query("SELECT * from ses_recos");
while ($row_reco = mysql_fetch_array($sql_reco)) {
  $recos[$row_reco["id"]] = $row_reco["reco"];
}

$flag_self = 0;

 $sql_vote = mysql_query("SELECT * from voting where report_id='$report_id' order by resolution_number asc");
 if(mysql_num_rows($sql_vote) > 0){
    $flag_self = 1;
     echo '<h3>Agenda Items: SES</h3>';
     $str = '<table class="table table-striped table-bordered table-advance table-hover"><tr><th>#</th><th>Resolution Name</th><th>Type</th><th>SES Recommendation</th><th>Recommendation</th><th>Details</th><th>Reasons</th></tr>';
     $count =1;
     while($row_vote = mysql_fetch_array($sql_vote)) {
      $str .= '<tr id="tr_vote_'.$row_vote["id"].'"><td>'.$row_vote["resolution_number"].'<input type="hidden" name="vote_id[]" value="'.$row_vote["id"].'" ></td>';
       $str .= '<td>'.stripcslashes($row_vote["resolution_name"]).'</td>';
      $sql_reso = mysql_query("Select * from resolutions where id='$row_vote[resolution_type]' ");
         while ($row_reso = mysql_fetch_array($sql_reso)) {
            $reso = $row_reso["resolution"];
            $str .= '<td>'.$row_reso["resolution"].'</td>';
         }
         $str .= '<td>'.stripcslashes($recos[$row_vote["ses_reco"]]).'</td><td>'.$man_recos[$row_vote["man_reco"]].'</td><td>'.stripcslashes($row_vote["detail"]).'</td><td><ul>';
         if($row_vote["reasons"] != ''){
         $sql_reso = mysql_query("Select * from reasons where id IN ($row_vote[reasons]) ");
         while ($row_reso = mysql_fetch_array($sql_reso)) {
            $str .= '<li>'.$row_reso["reason"].'</li>';
         }
     }
         $str .= '</ul></td></tr>';
         $count++;
     }
echo $str.'</table>';
$voting = new SesVoting();
} else {
    $sql = "SELECT * from user_resolution where report_id='$report_id' and user_id='$user_id' order by resolution_number asc";

    $sql_vote = mysql_query($sql);
    if(mysql_num_rows($sql_vote) > 0){
         echo '<h3>Agenda Items: Added by Client</h3>';
         $str = '<table class="table table-striped table-bordered table-advance table-hover"><tr><th>#</th><th>Resolution Name</th><th>Recommendation</th></tr>';
         $count =1;
         while($row_vote = mysql_fetch_array($sql_vote)) {
                 $str .= '<tr id="tr_vote_'.$row_vote["id"].'"><td>'.$row_vote["resolution_number"].'<input type="hidden" name="vote_id[]" value="'.$row_vote["id"].'" ></td>';
                 $str .= '<td>'.stripcslashes($row_vote["resolution_name"]).'</td>';
                 $str .= '<td>'.$man_recos[$row_vote["man_reco"]].'</td></td></tr>';
                 $count++;
             }
             echo $str.'</table>';
        }
        $voting = new SelfVoting();
}

echo $voting->user_votes($report_id, $user_id);


?>