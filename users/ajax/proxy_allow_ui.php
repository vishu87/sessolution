<?php session_start();
require_once('../../auth.php');
require_once('../../classes/UserClass.php');

$parent = $_SESSION["MEM_ID"];
$report_id = mysql_real_escape_string($_POST["report_id"]);

?>
<div class="row-fluid" style="">
<button class="btn green" id="allow_all" onclick="proxy_allow_all(<?php echo $report_id?>,<?php echo $parent?>)" >Allow users to Vote for this meeting</button>
<button class="btn red" id="disallow_all" onclick="proxy_disallow_all(<?php echo $report_id?>,<?php echo $parent?>)" >Remove this meeting</button>
<br><br><br>
<?php
// $s = "SELECT users.user_admin_name, users.name, users.id from user_proxy_allow inner join users on  user_proxy_allow.user_id = users.id  where (users.id = '$parent' OR users.created_by_prim = '$parent') AND user_proxy_allow.report_id = '$report_id' and user_proxy_allow.status = 0 ";
// //echo $s;
// $sql_user = mysql_query($s);

// while ($row_user = mysql_fetch_array($sql_user)) {

//   $row_user["name"] = ($row_user["user_admin_name"] != '')?$row_user["user_admin_name"]:$row_user["name"];

//   echo '<button style="margin-left:0px;" class="btn span4 btn_ad_'.$row_user["user_id"].'" onclick="change_check('.$row_user["user_id"].')" >'.$row_user["name"].'</button>&nbsp;&nbsp;<button onclick="proxy_allow('.$report_id.','.$row_user["id"].')" class="btn green btn_ad_'.$row_user["user_id"].'">Allow</button>&nbsp;&nbsp;<button onclick="proxy_disallow('.$report_id.','.$row_user["id"].')" class="btn red btn_ad_'.$row_user["user_id"].'">Remove</button><br><br>';
// }

?>
</div>