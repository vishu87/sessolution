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
$user = mysql_real_escape_string($_POST["user"]);


if(!isset($_POST["id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 1) header("Location: ".STRSITE."access-denied.php");
$report_id = $_POST["report_id"];
$vote_id = $_POST["id"];
$resolution_name = mysql_real_escape_string($_POST["res_name"]);
$resolution_number = mysql_real_escape_string($_POST["res_number"]);
$resolution = $_POST["res"];
$ses_reco = $_POST["ses_reco"];
$man_reco = $_POST["man_reco"];
$man_share_reco = $_POST["man_share_reco"];
$priority = $_POST["priority"];
if($_POST["focus"]){
  $focus_on = implode(',',$_POST["focus"]);
} else{
  $focus_on = 0;
}
$type_business = mysql_real_escape_string($_POST["type_business"]);
$type_res_os = mysql_real_escape_string($_POST["type_res_os"]);
$detail = mysql_real_escape_string($_POST["detail"]);
$reason = ($_POST["reason"] != '')? implode(',', $_POST["reason"]):'';
$date = strtotime("now");
$sql = "UPDATE voting set resolution_name='$resolution_name', resolution_number='$resolution_number', ses_reco='$ses_reco', man_reco='$man_reco', man_share_reco='$man_share_reco', resolution_type = '$resolution',detail = '$detail',reasons = '$reason',type_business='$type_business',type_res_os='$type_res_os',focus = '$focus_on', priority = '$priority',  modified = '$date' where id='$vote_id' ";
mysql_query($sql);


?>
<table class="table table-bordered table-hover tablesorter" id="table_votes" >
     <tr><th>#</th><th>Resolution Name</th><th>Type</th><th>SES Reco</th><th>Manag. Reco</th><th>Proposal by Management or Shareholder</th><th>Details</th><th>Reasons</th><th>Business Type / Resolution Type</th><th>Focus</th><th>Action</th></tr>
<?php

$recos = array();
$sql_reco = mysql_query("SELECT * from ses_recos");
while ($row_reco = mysql_fetch_array($sql_reco)) {
  $recos[$row_reco["id"]] = $row_reco["reco"];
}
     $sql_vote = mysql_query("SELECT * from voting where report_id='$report_id' order by priority, resolution_number asc");
     $count =1;
     while($row_vote = mysql_fetch_array($sql_vote)) {
      echo '<tr id="tr_vote_'.$row_vote["id"].'">
      <td>'.stripcslashes($row_vote["resolution_number"]).'</td>';
      echo '<td>'.stripcslashes($row_vote["resolution_name"]).'</td><td>';
      $sql_reso = mysql_query("Select * from resolutions where id='$row_vote[resolution_type]' ");
        while ($row_reso = mysql_fetch_array($sql_reso)) {
          $reso = $row_reso["resolution"];
          echo $row_reso["resolution"];
        }
        echo '</td><td>'.stripcslashes($recos[$row_vote["ses_reco"]]).'</td>';
        echo '<td>'.stripcslashes($man_recos[$row_vote["man_reco"]]).'</td>';
        echo '<td>'.stripcslashes($man_share_recos[$row_vote["man_share_reco"]]).'</td>';
        echo '<td>'.stripcslashes($row_vote["detail"]).'</td><td>';
        if($row_vote["reasons"] != ''){
        $sql_reso = mysql_query("Select * from reasons where id IN ($row_vote[reasons]) ");
        while ($row_reso = mysql_fetch_array($sql_reso)) {
          echo '<p>'.$row_reso["reason"].'</p>';
        }
    } ?>
        </td>
        <?php echo '<td>'.$types_business[$row_vote["type_business"]].' / '.$types_res_os[$row_vote["type_res_os"]].'</td>'; ?>
                <td>
          <?php if($row_vote["focus"] != 0){ 
            $focs = explode(',', $row_vote["focus"]);
            $final_focs = array();
            foreach ($focs as $foc) {
              array_push($final_focs, $focus[$foc]);
            }
            echo implode('/', $final_focs);
            } ?>
        </td>
        <td>
         
           <button class="btn" data-toggle="modal" href="#stack2" onclick="voting('<?php echo name_filter($row_vote["resolution_name"]);  ?>','<?php echo $row_vote["id"]; ?>');">Edit</button>
           <a href="javascript:;" onclick="delete_voting(<?php echo $row_vote["id"]?>);" class="btn red icn-only"><i class="icon-remove icon-white"></i></a>
        </td>
</tr>

<?php
        $count++;

     } ?>

</table>