<?php session_start();
require_once('../../auth.php');
$separator = '^';
$line_break = '\n';
$time = strtotime("now");
$depositories_short = array("1"=>"N","2"=>"C","3"=>"P");

$user_id = $_SESSION["MEM_ID"];
$report_id = mysql_real_escape_string($_GET["id"]);

$user_info = mysql_query("SELECT * from evoting_info where user_id = $user_id limit 1");
$row_info = mysql_fetch_array($user_info);

$query = mysql_query("SELECT com_id, even from proxy_ad where id= $report_id limit 1");
$row_com = mysql_fetch_array($query);
$com_id = $row_com["com_id"];
$even = $row_com["even"];

$query_sc = mysql_query("SELECT schemes.dp_id, schemes.client_id, schemes.depository, scheme_companies.shares_held from schemes join scheme_companies on schemes.id = scheme_companies.scheme_id where schemes.user_id = $user_id and scheme_companies.com_id = $com_id  ");
$total_schemes = mysql_num_rows($query_sc);

$array_res = array();
$qx = "SELECT user_admin_voting.vote_id, user_admin_voting.vote, voting.resolution_number from user_admin_voting join voting on user_admin_voting.vote_id = voting.id where user_admin_voting.proxy_id = $report_id and voting.report_id = $report_id and user_admin_voting.user_id = $user_id and user_admin_voting.vote IN (1,2) order by voting.resolution_number asc ";
//echo $qx.'<br>';
$query_res = mysql_query($qx);
$total_resolutions = mysql_num_rows($query_res);
while ($row_res = mysql_fetch_array($query_res)) {
	array_push($array_res, array("vote"=>$row_res["vote"],"resolution_number"=>$row_res["resolution_number"]));
}

mysql_query("INSERT into nsdl_votes (user_id, report_id) values ('$user_id','$report_id') ");
$batch_id = mysql_insert_id();
$batch_id_count = strlen($batch_id);

$str = '';
for ($i=0; $i < (7-$batch_id_count) ; $i++) { 
	$str .= '0';
}

$batch_id = $str.$batch_id;

$header_line = $batch_id.$separator.'11'.$separator.$row_info["nsdl_mf_id"].$separator.($total_schemes*$total_resolutions).$separator.$even.$separator.date("Ymd",$time).$separator.date("Hi",$time);
echo $header_line.'<br>';

while ($row_sc = mysql_fetch_array($query_sc)) {
	foreach ($array_res as $ar) {
		$line = $batch_id.$separator.'12'.$separator.$depositories_short[$row_sc["depository"]].$separator.$row_sc["dp_id"].$row_sc["client_id"].$separator.$row_info["nsdl_poa_id"].$separator.$ar["resolution_number"].$separator.$ar["vote"].$separator.'asdsad';
		echo $line.'<br>';
	}
}


?>