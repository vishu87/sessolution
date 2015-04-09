<?php session_start();
require_once('../../auth.php');

$rid = mysql_real_escape_string($_POST["id"]);

?>

<div class="row-fluid">
</div>
  <style type="text/css">
  .meeting_results td, .meeting_results th{
    border: 1px solid #ddd;
    padding: 5px 10px;
  }
  .meeting_results td input.small{
    max-width: 80px;
  }
  </style>
<br>
<form id="meetingResultsForm">
<?php
$recos = array();
$sql_reco = mysql_query("SELECT * from ses_recos");
  while ($row_reco = mysql_fetch_array($sql_reco)) {
    $recos[$row_reco["id"]] = $row_reco["reco"];
}
$types = array("1"=>"Promoter and Promoter Group", "2" => "Public - Institutional holders", "3" => "Public-Others");


$array_voting_id = array();
$sql_vote = mysql_query("SELECT id, resolution_number, resolution_name, ses_reco from voting where report_id='$rid' order by resolution_number asc");
while ($row_vote = mysql_fetch_array($sql_vote)) {
  array_push($array_voting_id, $row_vote["id"]);
  $votings[$row_vote["id"]] = array($row_vote["id"],$row_vote["resolution_number"],$row_vote["resolution_name"],$row_vote["ses_reco"]);
}
$results = array();
$sql_result = mysql_query("SELECT * from meeting_results where resolution_id IN (".implode(',', $array_voting_id).") ");
while ($row_result = mysql_fetch_array($sql_result)) {
  $results[$row_result["resolution_id"]][$row_result["type"]] = array($row_result["shares_held"], $row_result["votes_favour"], $row_result["votes_polled"], $row_result["votes_against"], $row_result["result"]);
}
$count =1;
foreach ($votings as $voting){
  echo '<table class="meeting_results" style="width:100%">
  <tr>
    <th>SN</th>
    <th>Resolution</th>
    <th>SES Recommendation</th>
    <th>No. of shares held</th>
    <th>No. of votes polled</th>
    <th>% of Votes in<br>favour on votes polled</th>
    <th>% of votes<br>against on votes polled</th>
    <th>Results</th>
    <th>#</th>
  <tr>';
  echo '<tr>';
  echo '<td>'.$voting[1].'</td>';
  echo '<td>'.$voting[2].'</td>';
  echo '<td>'.$recos[$voting[3]].'</td>';
  echo '<td>'.$results[$voting[0]][4][0].'</td>';
  echo '<td>'.$results[$voting[0]][4][2].'</td>';
  echo '<td>'.$results[$voting[0]][4][1].'%</td>';
  echo '<td>'.$results[$voting[0]][4][3].'%</td>';
  echo '<td>'.$results[$voting[0]][4][4].'</td>';
  echo '<td><a href="javascript:;" class="btn blue mini show_hide_btn'.$voting[0].'" data-show="0" onclick="show_hide('.$voting[0].')" style="width:50px">Details <i class="icon-chevron-down"></i></a></td>';
  echo '</tr>';
  foreach ($types as $key => $value) {
    echo '<tr style="display:none" class="tr_'.$voting[0].'">';
    echo '<td colspan="3" align="right">'.$value.'</td>';
    echo '<td>'.$results[$voting[0]][$key][0].'</td>';
    echo '<td>'.$results[$voting[0]][$key][2].'</td>';
    echo '<td>'.$results[$voting[0]][$key][1].'%</td>';
    echo '<td>'.$results[$voting[0]][$key][3].'%</td>';
    echo '<td colspan="2"></td>';
    echo '</tr>';
  }
 
  echo '</table><br><br>';
  $count++;
}
?>
</form>
