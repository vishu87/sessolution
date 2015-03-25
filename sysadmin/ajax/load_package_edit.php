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
$user = mysql_real_escape_string($_POST["user"]);


if(!isset($_POST["id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 1) header("Location: ".STRSITE."access-denied.php");

$query_sent = mysql_query("SELECT * from package where package_id='$_POST[id]' LIMIT 1");

$ar_fields_all = array("package_name","package_year","visibility");
$ar_fields_name = array("Package Name","Year","Visibility");
echo '<form id="update_form" ><table class="table table-bordered table-hover">';
while($row = mysql_fetch_array($query_sent))  {
	$count = 0;
	echo "<input type='hidden' name='package_id' id='package_id' value='".$row["package_id"]."'>";
	echo '<input type="hidden" id="package_type" value="'.$package_types[$row["package_type"]].'">';
	foreach ($ar_fields_all as $ar) {
		if($ar == 'package_year') {
			echo "<tr><td>".$ar_fields_name[$count]."</td><td>";
			echo fetch_years($ar,$row[$ar]);
			echo "</td></tr>";
		} else if($ar == 'visibility') {
			echo "<tr><td>".$ar_fields_name[$count]."</td><td>";
			echo '<select name="visibility" id="visibility">
                 <option value="1">Not visible for all users</option>
                 <option value="0" ';
            if($row[$ar] == 0) echo 'selected';
            echo '>Visible for all users</option>
              		</select>';
			echo "</td></tr>";
		} else {
			echo "<tr><td>".$ar_fields_name[$count]."</td><td><input type=text name=".$ar." id=".$ar." value=".stripcslashes($row[$ar])."></td></tr>";
		}

		
		$count++;
	}
}

echo '</table>
<button type="button" onclick="check_edit_submit()" class="btn blue"><i class="icon-ok"></i> Update</button>
</form>';

?>