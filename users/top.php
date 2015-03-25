<?php require_once('../auth.php');

// check whether student is already selected


include('../classes/UserClass.php');
include('../classes/MemberClass.php');
include('../classes/GeneralVoting.php');
$member = new User($_SESSION["MEM_ID"]);
$years = array();
$year_sql = mysql_query("SELECT year_sh from years order by year_sh desc");
while ($row_yr = mysql_fetch_array($year_sql)) {
array_push($years, $row_yr["year_sh"]);
}
?>