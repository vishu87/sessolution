<?php session_start();
require_once('../../subuserauth.php');


$proxy_ids = explode(',', $_POST["report_ids"]);

if(!isset($_POST["report_ids"]) ) header("Location: ".STRSITE."access-denied.php");
 echo '<br>';
foreach ($proxy_ids as $proxy_id) {
  $sql_p = mysql_query("SELECT companies.com_name, proxy_ad.meeting_type, proxy_ad.meeting_date from proxy_ad inner join companies on proxy_ad.com_id = companies.com_id where proxy_ad.id='$proxy_id' ");
  $row_p = mysql_fetch_array($sql_p);
  $com_name_clr = name_filter($row_p["com_name"]);
  $tooltip_check = ($row_p["meeting_type"] == 3)?'':'ttip';
  echo '<div class="meet meet'.$row_p["meeting_type"].' '.$tooltip_check.'" data-toggle="modal" href="#stack1" onclick="view_report(\''.$com_name_clr.'\','.$proxy_id.',1);" data-toggle="tooltip" title="Meeting Date: '.date("d-M-y",$row_p["meeting_date"]).'">'.$row_p["com_name"].'</div>';
}

?>
<div class="row-fluid" style="margin:10px 5px;">
    <div class="span12">
      <button class="btn meet1" style="color:#fff;">AGM</button>
      <button class="btn meet2" style="color:#fff;">EGM</button>
      <button class="btn meet3" style="color:#fff;">PB</button>
      <button class="btn meet4" style="color:#fff;">CCM</button>
    </div>
  </div>