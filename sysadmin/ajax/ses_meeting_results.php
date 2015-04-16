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

include('../../classes/MemberClass.php');

$rid = mysql_real_escape_string($_POST["id"]);

if(!isset($_POST["id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 1) header("Location: ".STRSITE."access-denied.php");

$pa_report = new PA_admin($rid);
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
<form class="meetingResultsForm">
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
$array_result_option = array("","Passed","Rejected","Withdrawn");
$count = 1;
foreach ($votings as $voting){

  echo   '<b>'.stripcslashes($voting[1]).' # '.stripcslashes($voting[2]).'</b>';
  echo   ': SES Recommendation-<b>'.$recos[$voting[3]].'</b>';
  echo '<input type="hidden" name="resolutions[]" value="'.$voting[0].'">';
  echo '<table class="meeting_results" style="width:100%">
  <tr>
    <th></th>
    <th>No. of shares held</th>
    <th>No. of votes polled</th>
    <th>% of Votes in<br>favour on votes polled</th>
    <th>% of Votes<br>against on votes polled</th>
    <th>Results</th>
  <tr>';
  foreach ($types as $key => $value) {
    echo '<tr>';
    echo '<td>'.$value.'</td>';
    echo '<td><input type="text" class="small shares_held ';
    echo ($count == 1)?"shares_held_main":"";
    echo '" data-id="'.$voting[0].'" key-id="'.$key.'" value="'.$results[$voting[0]][$key][0].'" name="shares_held_'.$voting[0].'_'.$key.'" ></td>';
    echo '<td><input type="text" class="small votes_polled" data-id="'.$voting[0].'" value="'.$results[$voting[0]][$key][2].'" name="votes_polled_'.$voting[0].'_'.$key.'" ></td>';
    echo '<td><input type="text" class="small" value="'.$results[$voting[0]][$key][1].'" name="votes_favour_'.$voting[0].'_'.$key.'" >%</td>';
    echo '<td><input type="text" class="small" value="'.$results[$voting[0]][$key][3].'" name="votes_against_'.$voting[0].'_'.$key.'" >%</td>';
    echo '<td></td>';
    echo '</tr>';
  }
  echo '<tr>';
    echo '<td>Grand Total</td>';
    echo '<td><input type="text" value="'.$results[$voting[0]][4][0].'" class="small total_shares_held ';
    echo ($count == 1)?"total_shares_held_main":"";
    echo '" data-id="'.$voting[0].'" key-id="4" name="shares_held_'.$voting[0].'_4" ></td>';
    echo '<td><input type="text" value="'.$results[$voting[0]][4][2].'" class="small total_votes_polled" data-id="'.$voting[0].'" name="votes_polled_'.$voting[0].'_4" ></td>';
    echo '<td><input type="text" value="'.$results[$voting[0]][4][1].'" class="small" name="votes_favour_'.$voting[0].'_4" >%</td>';
    echo '<td><input type="text" value="'.$results[$voting[0]][4][3].'" class="small" name="votes_against_'.$voting[0].'_4" >%</td>';
    echo '<td>
          <select name="result_'.$voting[0].'_4" >';
          foreach ($array_result_option as $option) {
            echo '<option ';
            if($option == $results[$voting[0]][4][4]) echo 'selected';
            echo '>'.$option.'</option>';
          }
    echo '</select>
          </td>';
    echo '</tr>';
  echo '</table><br><br>';
  if($count % 2 == 0) echo '</form><form class="meetingResultsForm">';
  $count++;
}
?>
</form>

<script type="text/javascript">
  $(".shares_held").keyup(function(e){
    var data = $(this).attr("data-id");
    var sum = 0;
    $(".shares_held[data-id="+data+"]").each(function(){
      if(parseInt($(this).val())) sum = sum + parseInt($(this).val());
    });
    $(".total_shares_held[data-id="+data+"]").val(sum);
    if($(".total_shares_held[data-id="+data+"]").hasClass('total_shares_held_main')){
       $(".total_shares_held").val(sum);
    }
  });

  $(".shares_held_main").keyup(function(e){
    var key = $(this).attr("key-id");
    $(".shares_held[key-id="+key+"]").val($(this).val());
  });

  $(".total_shares_held_main").keyup(function(e){
    var key = $(this).attr("key-id");
    $(".total_shares_held[key-id="+key+"]").val($(this).val());
  });

  $(".votes_polled").keyup(function(e){
    var data = $(this).attr("data-id");
    var sum = 0;
    $(".votes_polled[data-id="+data+"]").each(function(){
      if(parseInt($(this).val())) sum = sum + parseInt($(this).val());
    });
    $(".total_votes_polled[data-id="+data+"]").val(sum);
  });
</script>