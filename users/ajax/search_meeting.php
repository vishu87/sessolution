<?php session_start();
require_once('../../auth.php');
require_once('../../classes/UserClass.php');

$user = $_SESSION["MEM_ID"];

$com_string = $_POST["com_string"];
  $coms = explode('/', $com_string);
  $com_name = addslashes($coms[0]);
  $com_bse_code = $coms[1];

  $sql = mysql_query("SELECT com_id from companies where com_name='$com_name' and com_bse_code='$com_bse_code' limit 1 ");
  if(mysql_num_rows($sql) > 0){
    $com_det = mysql_fetch_array($sql);
    $com_id = $com_det["com_id"];
  }else {
    header("Location: ../".$folder.".php?cat=1&success=3");
    die();
}

$date_low = ($_POST["date_from"])?strtotime($_POST["date_from"]):'';
$date_high = ($_POST["date_to"])?strtotime($_POST["date_to"]):'';


if($date_high && $date_low){
 $date_sql = "and meeting_date <= '$date_high' and meeting_date >= '$date_low'";
} elseif ($date_high && !$date_low) {
  $date_sql = "and meeting_date <= '$date_high'";
} elseif(!$date_high && $date_low) {
  $date_sql = "and meeting_date >= '$date_low'";
} else {
  $date_sql = "";
}
?>
<table class="table table-stripped">
  <tr>
    <th>Name</th>
    <th>Meeting Date</th>
    <th>Meeting Type</th>
    <th>Action</th>
  </tr>
<?php
$sql = mysql_query("SELECT id from proxy_ad where com_id='$com_id' ".$date_sql." order by meeting_date desc");
while ($row = mysql_fetch_array($sql)) {
   $pa_report = new PA($row["id"]);
?>
  <tr id="tr_<?php echo $row["id"];?>">
   <td>
   <?php echo $pa_report->company_name;;?>
  </td>
   <td><?php echo $pa_report->meeting_date;?></td>
    <td><?php echo $pa_report->meeting_type;?></td>
    <td>
      <button class="btn green" id="add_met_button_<?php echo $row["id"] ?>" onclick="add_meeting_rec(<?php echo $row["id"] ?>)">Add to voting records</button>
    </td>
  </tr>
<?php
}
?>
</table>