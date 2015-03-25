<?php session_start();

require_once('../../auth.php');

$sql = mysql_query("SELECT * from proxy_ad");
while ($row = mysql_fetch_array($sql)) {
	$times = $row["meeting_date"] + 30*86400;
	mysql_query("UPDATE proxy_ad set meeting_date = '$times' where id='$row[id]' ");
}
?>