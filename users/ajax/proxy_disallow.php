<?php session_start();
require_once('../../auth.php');
require_once('../../classes/UserClass.php');


$report_id = mysql_real_escape_string($_POST["report_id"]);
$user_id = mysql_real_escape_string($_POST["user_id"]);
$portfolio_id = mysql_real_escape_string($_POST["portfolio_id"]);

?>
<div class="row-fluid">

<?php
mysql_query("UPDATE user_proxy_allow set status = 1 where portfolio_id = '$portfolio_id' and report_id = '$report_id'  ");

?>
</div>