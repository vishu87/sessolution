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

if($_SESSION["MEM_ID"] == '' || $_SESSION["PRIV"] != 1) header("Location: ".STRSITE."access-denied.php");

$resolutions = $_POST["resolutions"];

foreach ($resolutions as $resolution) {
  for ($i=1; $i <=4 ; $i++) {
    $shares_held = mysql_real_escape_string($_POST["shares_held_".$resolution."_".$i]);
    $votes_favour = mysql_real_escape_string($_POST["votes_favour_".$resolution."_".$i]);
    $votes_polled = mysql_real_escape_string($_POST["votes_polled_".$resolution."_".$i]);
    $votes_against = mysql_real_escape_string($_POST["votes_against_".$resolution."_".$i]);
    $result = mysql_real_escape_string($_POST["result_".$resolution."_".$i]);
    $query = mysql_query("SELECT id from meeting_results where resolution_id = '$resolution' and type = '$i' limit 1");
    if(mysql_num_rows($query) == 0){
        mysql_query("INSERT into meeting_results (resolution_id, type, shares_held, votes_favour, votes_polled, votes_against,result) values ('$resolution','$i','$shares_held','$votes_favour','$votes_polled','$votes_against', '$result') ");
    } else {
      mysql_query("UPDATE meeting_results set shares_held = '$shares_held', votes_favour = '$votes_favour', votes_polled = '$votes_polled', votes_against = '$votes_against',result = '$result' where resolution_id = '$resolution' and type = '$i' ");
    }
        
  }
}

?>

