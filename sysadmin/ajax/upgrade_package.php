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
require_once('../../classes/MemberClass.php');

$user_id = mysql_real_escape_string($_POST["user_id"]);
$package_id = mysql_real_escape_string($_POST["package_id"]);

if(!isset($_POST["package_id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 1) header("Location: ".STRSITE."access-denied.php");



$query_sent = mysql_query("SELECT * from package where package_id='$package_id' LIMIT 1");
$row_sent = mysql_fetch_array($query_sent);

if(mysql_num_rows($query_sent) == 0) header("Location: ".STRSITE."access-denied.php");
$package_type = $row_sent["package_type"];
$package_year = $row_sent["package_year"];

$query_sent = mysql_query("SELECT * from package where package_type='$package_type' and package_year='$package_year'");

echo '<form id="update_form" >';
echo '<select name="new_package" id="new_package" >';
while($row = mysql_fetch_array($query_sent))  {
	echo '<option value="'.$row["package_id"].'">'.$row["package_name"].' ('.$row["package_year"].')</option>';
}

echo '</select><br><br>
<button type="button" onclick="upgrade_submit('.$user_id.','.$package_id.')" class="btn blue" id="sub_button"><i class="icon-ok"></i> Upgrade</button>
</form>';

?>
<script type="text/javascript">
	
function upgrade_submit(user_id,old_package_id){
	 var new_val = $("#new_package option:selected").text();
	 bootbox.confirm("Are you sure to upgrade from <?php echo $_POST["package_name"] ?> to "+ new_val +" ?", function(result) {
          if(result) {
              $("#sub_button").removeAttr('onclick').html('Upgrading');
			   var file = 'upgrade_submit';
			   $.post("ajax/"+ file +".php", {user_id:user_id,new_package_id:$("#new_package").val(), old_package_id:old_package_id}, function(data) {
			      $("#modal-body").html(data);
			   }); 
          }
          else {
          
          }
        });
  

}

</script>