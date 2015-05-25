<?php session_start();
require_once('../../auth.php');

$timenow = strtotime("today");
$query = mysql_query("SELECT companies.com_name, companies.com_isin from proxy_ad inner join user_admin_proxy_ad on proxy_ad.id = user_admin_proxy_ad.report_id inner join companies on proxy_ad.com_id = companies.com_id where user_admin_proxy_ad.user_id = '$_SESSION[MEM_ID]' and user_admin_proxy_ad.com_approval = '$timenow' ");
$count = 0;
echo '<table class="table table-bordered table-hover"><tr><th>SN</th><th>Company Name</th><th>ISIN</th></tr>';
while ($row = mysql_fetch_array($query)) {
	?>
		<tr>
			<td><?php echo ++$count; ?></td>
			<td><?php echo $row["com_name"] ?></td>
			<td><?php echo $row["com_isin"] ?></td>
		</tr>	
	<?php
}
echo '</table>';

?>