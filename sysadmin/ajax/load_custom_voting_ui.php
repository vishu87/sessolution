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

if(!isset($_POST["report_id"]) || !isset($_POST["user_id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 1) header("Location: ".STRSITE."access-denied.php");

$array_reco = array();
$sql_recos = mysql_query("SELECT id, reco from ses_recos where status = 0 ");
while ($row_reco = mysql_fetch_array($sql_recos)) {
  $array_reco[$row_reco["id"]] = $row_reco["reco"];
}

$report_id = mysql_real_escape_string($_POST["report_id"]);
$user_id = mysql_real_escape_string($_POST["user_id"]);
?>
<form id="custom_reso_form">

  <table class="table table-bordered">
    <tr>
      <th>#</th>
      <th>Resolution</th>
      <th>SES Recommendation</th>
      <th>Custom Vote</th>
      <th>Custom Comment</th>
    </tr>
  <?php
  $sql_votes = mysql_query("SELECT voting.id, voting.resolution_number, voting.resolution_name, ses_recos.reco from voting join ses_recos on voting.ses_reco = ses_recos.id where voting.report_id = '$report_id' order by voting.resolution_number asc ");
  while ($row = mysql_fetch_array($sql_votes)) {
  ?>
    <tr>
      <td><input name="vote_id[]" value="<?php echo $row["id"] ?>" style="display:none"><?php echo $row["resolution_number"] ?></td>
      <td><?php echo $row["resolution_name"] ?></td>
      <td><?php echo $row["reco"] ?></td>
      <td>
        <select name="votes[]">
        <?php
          foreach ($array_reco as $key => $value) {
            echo '<option value="'.$key.'">'.$value.'</option>';
          }
        ?>
        </select>
      </td>
      <td>
        <textarea name="comments[]"></textarea>
      </td>
    </tr>
  <?php
  }

  ?>

  </table>
  <button type="button" onclick="submit_custom_form(<?php echo $report_id ?>,<?php echo $user_id ?>)" class="btn green pull-right" id="custom_vote_submit">Submit</button>
</form>