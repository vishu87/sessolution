<?php session_start();
error_reporting(E_ALL);

/** PHPExcel */
require_once 'Classes/PHPExcel.php';

//Link to the Mysql
require_once '../auth.php';

$date_from = strtotime(mysql_real_escape_string($_POST["date_from"]));
$date_to = strtotime(mysql_real_escape_string($_POST["date_to"]));
$users_ar = array();

//firm wide

array_push($users_ar, $_SESSION["MEM_ID"]);
$q_ot = mysql_query("SELECT id from users where created_by_prim = '$_SESSION[MEM_ID]' ");
while ($r_ot = mysql_fetch_array($q_ot)) {
	array_push($users_ar, $r_ot["id"]);
}
$name="Firm Wide";


$user_string = implode(',', $users_ar);

$type_id = mysql_real_escape_string($_POST["type_id"]);

if($date_from && $date_to){
	$date_sql = ' and proxy_ad.meeting_date between '.$date_from.' and '.$date_to;
} else if($date_from){
	$date_sql = ' and proxy_ad.meeting_date >= '.$date_from;
} else if($date_to){
	$date_sql = ' and proxy_ad.meeting_date <= '.$date_to;
} else {
	$date_sql = '';
}

$query = "SELECT distinct(proxy_ad.id) as report_id, proxy_ad.year , proxy_ad.meeting_date from user_voting_proxy_reports inner join proxy_ad on user_voting_proxy_reports.report_id = proxy_ad.id where user_voting_proxy_reports.user_id IN (".$user_string.") ".$date_sql." order by proxy_ad.meeting_date asc";

$sql_query = mysql_query($query);

$pre_year = 0;
$pre_quarter = 0;
$res = 0;
$for = 0;
$against = 0;
$abstain = 0;
$count = 0;
echo '<table border="1"><tr><td>Year</td><td>Quarter</td><td>Date</td><td>Report ID</td><td>Total</td><td>FOR</td><td>AGAINST</td><td>ABSTAIN</td><td>TOTFOR</td><td>TOTAGAINST</td><td>TOTABSTAIN</td></tr>';

while($row = mysql_fetch_array($sql_query)){

	$vote_for = 0;
	$vote_against = 0;
	$vote_abstain = 0;
	$vote_res = 0;
	$res_array = array(); 

	$report_id = $row["report_id"];
	$year = $row["year"];
	$quarter = ceil(date("n",$row["meeting_date"])/3) - 1;
	if($quarter == 0) $quarter = 4;

	$final_freeze_query = mysql_query("SELECT final_freeze, ignore_an from user_admin_proxy_ad where report_id='$report_id' and user_id = '$_SESSION[MEM_ID]' limit 1");
	$row_final_freeze = mysql_fetch_array($final_freeze_query);
	if($row_final_freeze["final_freeze"] == 0) continue;

	$total_res_query = mysql_query("SELECT id from voting where report_id='$report_id' ");
	$total_res = mysql_num_rows($total_res_query);
	while ($row_vote = mysql_fetch_array($total_res_query)) {
		array_push($res_array, $row_vote["id"]);
	}

	

	$res_string = implode(',', $res_array);

	$check_ignore = $row_final_freeze["ignore_an"];

	if($total_res > 0){
		if($check_ignore == 0){

			$res += $total_res;
			$vote_res += $total_res;

			$query_user_admin_vote = mysql_query("SELECT vote from user_admin_voting where vote_id IN (".$res_string.") and user_id='$_SESSION[MEM_ID]' and vote = '1' ");
			$vote_for += mysql_num_rows($query_user_admin_vote);

			$query_user_admin_vote = mysql_query("SELECT vote from user_admin_voting where vote_id IN (".$res_string.") and user_id='$_SESSION[MEM_ID]' and vote = '2' ");
			$vote_against += mysql_num_rows($query_user_admin_vote);

			$query_user_admin_vote = mysql_query("SELECT vote from user_admin_voting where vote_id IN (".$res_string.") and user_id='$_SESSION[MEM_ID]' and vote = '3' ");
			$vote_abstain += mysql_num_rows($query_user_admin_vote);

		} else {

			// get users for that meeting
			$query_users = mysql_query("SELECT user_id from user_voting_proxy_reports where user_id IN (".$user_string.") and report_id = '$report_id' ");
			while ($row_users = mysql_fetch_array($query_users)) {
				

				$res += $total_res;
				$vote_res += $total_res;

				foreach ($res_array as $res_id) {
					$query_user_admin_vote = mysql_query("SELECT vote from user_voting where vote_id = '$res_id' and user_id='$row_users[user_id]' order by id desc limit 1 ");
					if(mysql_num_rows($query_user_admin_vote) > 0){
						$row_vote_value = mysql_fetch_array($query_user_admin_vote);

						switch ($row_vote_value["vote"]) {
							case 1:
								$vote_for++;
								break;
							
							case 2:
								$vote_against++;
								break;

							case 3:
								$vote_abstain++;
								break;
						}
					}

				}
			}


		}
	}

	$for += $vote_for;
	$against += $vote_against;
	$abstain += $vote_abstain;
	echo '<tr><td>'.$year.'</td><td>'.$quarter.' '.$check_ignore.'a</td><td>'.date("d-M-y",$row["meeting_date"]).'</td><td>'.$report_id.'</td><td>'.$vote_res.'</td><td>'.$vote_for.'</td><td>'.$vote_against.'</td><td>'.$vote_abstain.'</td><td>'.$for.'</td><td>'.$against.'</td><td>'.$abstain.'</td></tr>';
}
echo '</table>';
?>