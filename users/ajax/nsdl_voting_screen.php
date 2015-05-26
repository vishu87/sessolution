<?php session_start();
require_once('../../auth.php');
require_once('../../classes/UserClass.php');
$report_id = $_POST["report_id"];

if(!isset($_POST["report_id"])  || $_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 0) header("Location: ".STRSITE."access-denied.php");

$pa_report = new PA($report_id);
?>

<a href="<?php echo STRSITE ?>users/ajax/create_nsdl_votes.php?id=<?php echo $pa_report->id ?>" target="_blank" class="btn blue span4">Download Vote File</a>

<a href="<?php echo $pa_report->evoting_link ?>" target="_blank" class="btn green span4">Link to NSDL</a>

<a href="javascript:;" onclick="alert('Under Construction')" class="btn blue span4">Upload Vote Response File</a>
