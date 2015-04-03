<?php session_start();
error_reporting(E_ALL);

/** PHPExcel */
require_once 'Classes/PHPExcel.php';

//Link to the Mysql
require_once '../auth.php';

$date_from = strtotime(mysql_real_escape_string($_POST["date_from"]));
$date_to = strtotime(mysql_real_escape_string($_POST["date_to"]));
$users_ar = array();
$user_id = mysql_real_escape_string($_POST["user_id"]);

function getNameFromNumber($num) {
    $numeric = ($num ) % 26;
    $letter = chr(65 + $numeric);
    $num2 = intval(($num ) / 26);
    if ($num2 > 0) {
        return getNameFromNumber($num2) . $letter;
    } else {
        return $letter;
    }
}

//firm wide

if($user_id == 0){
	array_push($users_ar, $_SESSION["MEM_ID"]);
	$q_ot = mysql_query("SELECT id from users where created_by_prim = '$_SESSION[MEM_ID]' ");
	while ($r_ot = mysql_fetch_array($q_ot)) {
		array_push($users_ar, $r_ot["id"]);
	}
	$name="Firm Wide";
	$flag_indi = 0;
} else {
	array_push($users_ar, $user_id);
	$sql_user = mysql_query("SELECT name, user_admin_name from users where id='$user_id' ");
	$name_user = mysql_fetch_array($sql_user);
	if($name_user["user_admin_name"] != ''){
		$name=$name_user["user_admin_name"];
	} else {
		$name=$name_user["name"];
	}
	$flag_indi = 1;
}

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

$query = "SELECT distinct(user_voting_proxy_reports.report_id), companies.com_full_name, proxy_ad.meeting_date, proxy_ad.meeting_type from user_voting_proxy_reports inner join proxy_ad on user_voting_proxy_reports.report_id = proxy_ad.id  inner join companies on proxy_ad.com_id = companies.com_id where user_voting_proxy_reports.user_id IN (".$user_string.") ".$date_sql." order by proxy_ad.meeting_date asc";

$query_rep = mysql_query($query);

$row_rep = array();
$proxy_ids = array();
while ($row = mysql_fetch_array($query_rep)) {
	array_push($proxy_ids, $row["report_id"]);
	$row_rep[$row["report_id"]]["com_full_name"] = $row["com_full_name"];
	$row_rep[$row["report_id"]]["meeting_date"] = $row["meeting_date"];
	$row_rep[$row["report_id"]]["meeting_type"] = $row["meeting_type"];
}

if(mysql_num_rows($query_rep) == 0) {die('No meetings found');}

$objPHPExcel = new PHPExcel();
$man_reco_mis = $man_recos;
$man_share_reco_mis = $man_share_recos;

// // Set properties
$objPHPExcel->getProperties()->setCreator("SES")->setLastModifiedBy("SES");

include ('styles_mis.php');

// //Serializing a

if($type_id == 0){
	$ar_fields = array("user","meeting_date","com_full_name","meeting_type","man_share_reco","resolution_name","man_reco","vote","comment", "ses_reco");

$ar_names = array("Portfolio Manager","Meeting Date","Company Name","Type of Meetings","Proposal by Management or Shareholder","Proposal's description","Investee company's Management Recommendation","Vote","Reason supporting the vote decision", "SES Recommendation");
} else {
	$ar_fields = array("user","quarter","meeting_date","com_full_name","meeting_type","man_share_reco","resolution_name","man_reco","vote","comment", "ses_reco");

$ar_names = array("Portfolio Manager","Quarter","Meeting Date","Company Name","Type of Meetings","Proposal by Management or Shareholder","Proposal's description","Investee company's Management Recommendation","Vote","Reason supporting the vote decision", "SES Recommendation");
}

$ar_width = array("25","20","20","20","45","45","45","15","50","30");

$seq=1;
$offset = 0;
$count =0;
$i=0;

foreach ($ar_fields as $ar) {
	$cell_val = 65+$i+$offset;
	$objPHPExcel->getActiveSheet()->setCellValue(chr($cell_val).$seq, $ar_names[$count]);
	$objPHPExcel->getActiveSheet()->getColumnDimension(chr($cell_val))->setWidth($ar_width[$count]);
	$i++;
	$objPHPExcel->getActiveSheet()->getStyle(chr($cell_val).$seq)->applyFromArray($styleArrayborder);
	$count++;
}

$objPHPExcel->getActiveSheet()->getStyle('A'.$seq.':'.chr($cell_val).$seq)->applyFromArray($styleArray4);


$seq++;
$count_met = 0;

foreach ($proxy_ids as $proxy_id) {
	$seq_in = $seq;
	
	$check_admin_vote_freeze = mysql_query("SELECT final_freeze, final_unfreeze, id from admin_proxy_ad where report_id='$proxy_id' order by id desc limit 1");
	$row_check = mysql_fetch_array($check_admin_vote_freeze);
	if($row_check["final_freeze"] != 0 && $row_check["final_unfreeze"] == 0){
		$flag_admin = true;
	} else $flag_admin = false;


	$check_user_admin = mysql_query("SELECT final_freeze, final_unfreeze, ignore_an from user_admin_proxy_ad where report_id='$proxy_id' and user_id='$_SESSION[MEM_ID]'  ");
	$row_check_admin = mysql_fetch_array($check_user_admin);
	$ignore_an = $row_check_admin["ignore_an"];
	if($row_check_admin["final_freeze"] != 0 && $row_check_admin["final_unfreeze"] == 0){
		$flag_user_freeze = true;
	} else {
		$flag_user_freeze = false;
	}

	$user_proxy_array = array();
	if($flag_indi == 0){
		if($ignore_an == 0){
			$voting_table = 'user_admin_voting';
			array_push($user_proxy_array, $_SESSION["MEM_ID"]);
		} else{
			$voting_table = 'user_voting';
			$check_users = mysql_query("SELECT user_id from user_voting_proxy_reports where report_id='$proxy_id' and user_id in (".$user_string.") ");
			while ($row_check_users = mysql_fetch_array($check_users)) {
				$check_user_freeze = mysql_query("SELECT id from user_proxy_ad where freeze_on != 0 and unfreeze_on = 0 and user_id='$row_check_users[user_id]' and report_id='$proxy_id' ");
				if(mysql_num_rows($check_user_freeze) > 0) array_push($user_proxy_array, $row_check_users["user_id"]);
			}
		}
	} else {
		$user_proxy_array = $users_ar;
		if($ignore_an == 0){
			$voting_table = 'user_admin_voting';
		} else {
			$voting_table = 'user_voting';
		}
	}

	$qu = ceil(date("n",$row_rep[$proxy_id]["meeting_date"])/3) - 1;
	switch ($qu) {
		case 0:
			$quarter = 1;
			break;
		default:
			$quarter = $qu;
			break;
	}

	foreach ($user_proxy_array as $user_id) {

		if($flag_indi == 0){
			$user_id_vote = $user_id;
		} else {
			if($ignore_an == 0){
				$user_id_vote = $_SESSION["MEM_ID"];
			} else {
				$user_id_vote = $user_id;
			}
		}
		

		$query_res = mysql_query("SELECT voting.id, voting.resolution_name, voting.resolution_number, voting.man_reco, voting.man_share_reco, ses_recos.reco from voting inner join ses_recos on voting.ses_reco = ses_recos.id where voting.report_id='$proxy_id' "); 
			while ( $res = mysql_fetch_array($query_res)){
				$i=0;
				$query_vote = mysql_query("SELECT $voting_table.comment, votes.vote from $voting_table join votes on $voting_table.vote = votes.id where $voting_table.user_id='$user_id_vote' and $voting_table.vote_id='$res[id]'  limit 1");
				$vote = mysql_fetch_array($query_vote);
				$sql = "SELECT name, user_admin_name from users where id='$user_id' limit 1";
				$q_name = mysql_query($sql);
				$r_name = mysql_fetch_array($q_name);

				if($flag_indi == 0){
					if($ignore_an == 0){
						$u_name = $r_name["name"];
					} else {
						if($r_name["user_admin_name"] == ''){
							$u_name = $r_name["name"];
						} else {
							$u_name = $r_name["user_admin_name"];
						}
					}
				} else {
					if($r_name["user_admin_name"] == ''){
						$u_name = $r_name["name"];
					} else {
						$u_name = $r_name["user_admin_name"];
					}
				}

				foreach ($ar_fields as $ar) {
					$cell_val = 65+$i+$offset;
					switch ($ar) {
						case 'user':
							$var = $u_name;
							break;
						case 'quarter':
							$var = $quarter;
							break;
						case 'com_full_name':
							$var = $row_rep[$proxy_id]["com_full_name"];
							break;
						case 'meeting_date':
							$var = date("d-M-y", $row_rep[$proxy_id]["meeting_date"]);
							break;
						case 'meeting_type':
							$var = $meeting_types[$row_rep[$proxy_id]["meeting_type"]];
							break;
						case 'resolution_number':
							$var = ($flag_admin)?$res["resolution_number"]:'';
							break;
						case 'resolution_name':
							$var = ($flag_admin)?$res["resolution_name"]:'';
							break;
						case 'man_reco':
							$var = ($flag_admin)?$man_reco_mis[$res["man_reco"]]:'';
							break;
						case 'man_share_reco':
							$var = ($flag_admin)?$man_share_reco_mis[$res["man_share_reco"]]:'';
							break;
						case 'vote':
							$var = ($flag_user_freeze)?$vote["vote"]:'';
							break;
						case 'comment':
							$var = ($flag_user_freeze)?stripcslashes($vote["comment"]):'';
							break;
						case 'ses_reco':
							$var = $res["reco"];
							break;
						default:
							$var = '';
							break;
					}
					
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($cell_val).$seq, $var);
					$objPHPExcel->getActiveSheet(0)->getStyle(chr($cell_val).$seq)->applyFromArray($styleArrayborder);
					$i++;
				}
				$seq++;
			}
	}
	$seq_out = $seq -1;

	$val_chr = chr($cell_val);

	if($count_met%2 == 0){
		$objPHPExcel->getActiveSheet()->getStyle('A'.$seq_in.':'.chr($cell_val).$seq_out)->applyFromArray($styleArray3);
	} else {
		$objPHPExcel->getActiveSheet()->getStyle('A'.$seq_in.':'.chr($cell_val).$seq_out)->applyFromArray($styleArray2);
	}

	$count_met++;
}
$seq--;
$objPHPExcel->getActiveSheet()->getStyle('I1:'.$val_chr.$seq.'')->getAlignment()->setWrapText(true);
// $objPHPExcel->getActiveSheet()->getStyle('F1:'.$val_chr.$seq.'')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:'.$val_chr.$seq.'')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);;

$objPHPExcel->getActiveSheet()->setTitle('MIS Report');

$objPHPExcel->setActiveSheetIndex(1);

$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Summary of Votes cast');
$objPHPExcel->getActiveSheet()->mergeCells('A1:F1');


$objPHPExcel->getActiveSheet()->setCellValue('A3', 'F.Y.');
$objPHPExcel->getActiveSheet()->setCellValue('B3', 'Quarter');
$objPHPExcel->getActiveSheet()->setCellValue('C3', 'Total No. of Resolutions');
$objPHPExcel->getActiveSheet()->setCellValue('D3', 'Break Up of Vote Decision');
$objPHPExcel->getActiveSheet()->mergeCells('D3:F3');
$objPHPExcel->getActiveSheet()->mergeCells('A3:A4');
$objPHPExcel->getActiveSheet()->mergeCells('B3:B4');
$objPHPExcel->getActiveSheet()->mergeCells('C3:C4');

$objPHPExcel->getActiveSheet()->setCellValue('D4', 'FOR');
$objPHPExcel->getActiveSheet()->setCellValue('E4', 'AGAINST');
$objPHPExcel->getActiveSheet()->setCellValue('F4', 'ABSTAIN');

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);

$period = array();
$query_period = mysql_query("SELECT * from years");
while ($row_period = mysql_fetch_array($query_period)) {
	$period[$row_period["year_sh"]] = $row_period["period"];
}
$query = "SELECT distinct(proxy_ad.id) as report_id, proxy_ad.year , proxy_ad.meeting_date from user_voting_proxy_reports inner join proxy_ad on user_voting_proxy_reports.report_id = proxy_ad.id where user_voting_proxy_reports.user_id IN (".$user_string.") ".$date_sql." order by proxy_ad.meeting_date asc";

$sql_query = mysql_query($query);

$pre_year = 0;
$pre_quarter = 0;
$count = 0;
$seq = 5;

while($row = mysql_fetch_array($sql_query)){

	$vote_for = 0;
	$vote_against = 0;
	$vote_abstain = 0;
	$res_array = array();
	$vote_res = 0;

	$report_id = $row["report_id"];
	$year = $row["year"];

	$quarter = ceil(date("n",$row["meeting_date"])/3) - 1;
	if($quarter == 0) $quarter = 4;

	if($quarter != $pre_quarter ){
		if($count != 0){
			//echo '<tr><td>'.$pre_year.'</td><td>'.$pre_quarter.'</td><td>'.$res.'</td><td>'.$for.'</td><td>'.$against.'</td><td>'.$abstain.' '.$count.'</td></tr>';
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$seq, $period[$pre_year]);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$seq, $pre_quarter);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$seq, $res);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$seq, $for);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$seq, $against);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$seq, $abstain);

			$seq++;
		}
		$pre_quarter = $quarter;
		$pre_year = $year;
		$res = 0;
		$for = 0;
		$against = 0;
		$abstain = 0;
	}

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

				foreach ($res_array as $res_id) {
					$query_user_admin_vote = mysql_query("SELECT vote from user_admin_voting where vote_id = '$res_id' and user_id='$_SESSION[MEM_ID]' order by id desc limit 1 ");
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
	$count++;
}

//echo '<tr><td>'.$pre_year.'</td><td>'.$pre_quarter.'</td><td>'.$res.'</td><td>'.$for.'</td><td>'.$against.'</td><td>'.$abstain.' '.$count.'</td></tr>';


$objPHPExcel->getActiveSheet()->setCellValue('A'.$seq, $period[$pre_year]);
$objPHPExcel->getActiveSheet()->setCellValue('B'.$seq, $pre_quarter);
$objPHPExcel->getActiveSheet()->setCellValue('C'.$seq, $res);
$objPHPExcel->getActiveSheet()->setCellValue('D'.$seq, $for);
$objPHPExcel->getActiveSheet()->setCellValue('E'.$seq, $against);
$objPHPExcel->getActiveSheet()->setCellValue('F'.$seq, $abstain);


for($i = 3; $i<=$seq; $i++){
	for($j = 0; $j<= 5; $j++){
		$objPHPExcel->getActiveSheet()->getStyle(getNameFromNumber($j).$i)->applyFromArray($styleArrayborder);
	}
}

//echo '</table>';
$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray4);
$objPHPExcel->getActiveSheet()->setTitle('Summary Report');
$objPHPExcel->setActiveSheetIndex(0);


$name = $name.'_MIS_Report_'.date("dMy",strtotime("today"));

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$name.'.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>