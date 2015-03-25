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

$sql = "SELECT com_id from package_company where package_id='$_POST[id]' ";
$query_sent = mysql_query($sql);
$array_comp = array();
while ($row = mysql_fetch_array($query_sent)) {
	array_push($array_comp, $row["com_id"]);
}
$string_comp = implode(',', $array_comp);
echo '<div class="portlet box blue">
                     <div class="portlet-title">
                        <h4><i class="icon-reorder"></i>Add Companies</h4>
                     </div>
                     <div class="portlet-body form">
                        <!-- BEGIN FORM-->
                        <form class="form-horizontal">
                           
                           <div class="control-group">
                              <div class="controls" style="margin:0px auto" align="center">
                              	<select name="com_id_select[]" id="com_id_select" data-placeholder="Choose a Country" class="chosen-select" >
                              		<option value="0">Select Company</option>';
                              		$query_comp = mysql_query("SELECT com_id,com_bse_code, com_name from companies where com_id NOT IN ($string_comp)");
                              		while ($row_comp = mysql_fetch_array($query_comp)) {
                              			echo '<option value="'.$row_comp["com_id"].'"">'.$row_comp["com_name"].' ('.$row_comp["com_bse_code"].')</option>';
                              		}

                              		echo '
                              	</select>
                              </div>
                           </div>
                          
                           <div class="form-actions">
                              <button type="button" class="btn blue" onclick="add_company_submit()">Add</button>
                           </div>
                        </form>
                        <!-- END FORM-->           
                     </div>
                  </div>';

?>