<?php session_start();
require_once('../../auth.php');

$timenow = strtotime("today");
$query = mysql_query("SELECT companies.com_name, companies.com_isin, proxy_ad.meeting_date, proxy_ad.meeting_type, proxy_ad.evoting_end from proxy_ad inner join user_admin_proxy_ad on proxy_ad.id = user_admin_proxy_ad.report_id inner join companies on proxy_ad.com_id = companies.com_id where user_admin_proxy_ad.user_id = '$_SESSION[MEM_ID]' and user_admin_proxy_ad.com_approval = '$timenow' ");
$count = 0;
echo '<table class="table table-bordered table-hover"><tr><th>SN</th><th>Company Name</th><th>ISIN</th><th>Meeting Type</th><th>Meeting Date</th><th>eVoting Deadline</th></tr>';
while ($row = mysql_fetch_array($query)) {
	?>
		<tr>
			<td><?php echo ++$count; ?></td>
			<td><?php echo $row["com_name"] ?></td>
			<td><?php echo $row["com_isin"] ?></td>
			<td><?php echo $meeting_types[$row["meeting_type"]] ?></td>
			<td><?php echo ($row["meeting_date"])?date("d M y",$row["meeting_date"]):''; ?></td>
			<td><?php echo ($row["evoting_end"])?date("d M y",$row["evoting_end"]):''; ?></td>
		</tr>	
	<?php
}
echo '</table>';

?>