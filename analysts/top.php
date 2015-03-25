<?php require_once('../subuserauth.php');

// check whether student is already selected
	

$inactive = 200000; // Set timeout period in seconds

if (isset($_SESSION['timeout'])) {
    $session_life = time() - $_SESSION['timeout'];
    if ($session_life > $inactive) {
        session_destroy();
        header("Location: ".STRSITE."access-denied.php");
    }
}
$_SESSION['timeout'] = time();
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