<?php session_start();
require_once('../../auth.php');

$package_id = mysql_real_escape_string($_POST["package_id"]);

if(!isset($_POST["package_id"])) header("Location: ".STRSITE."access-denied.php");



$query_sent = mysql_query("SELECT * from package where package_id='$package_id' LIMIT 1");
$row_sent = mysql_fetch_array($query_sent);

if(mysql_num_rows($query_sent) == 0) header("Location: ".STRSITE."access-denied.php");

$package_type = $row_sent["package_type"];
$package_year = $row_sent["package_year"];

$query_sent = mysql_query("SELECT * from package where package_type='$package_type' and package_year='$package_year' and visibility = 0");

echo '<form id="update_form" ><h3>Upgrade Package</h3>';
echo '<select name="new_package" id="new_package" class="m-wrap" >';
while($row = mysql_fetch_array($query_sent))  {
	echo '<option value="'.$row["package_id"].'">'.$row["package_name"].' </option>';
}

echo '</select><br><br>
<button type="button" onclick="upgrade_submit('.$package_id.')" class="btn blue" id="sub_button"><i class="icon-ok"></i> Send Request</button>
</form>';

?>
<script type="text/javascript">
	
function upgrade_submit(old_package_id){
	
  $("#sub_button").removeAttr('onclick').html('Upgrading');
   var file = 'upgrade_submit';
   $.post("ajax/"+ file +".php", {new_package_id:$("#new_package").val(), old_package_id:old_package_id}, function(data) {
      $("#modal-body").html(data);
   }); 
          

}

</script>