<?php session_start();
error_reporting(E_ALL);

/** PHPExcel */
require_once 'Classes/PHPExcel.php';

//Link to the Mysql
require_once '../auth.php';

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

array_push($users_ar, $_SESSION["MEM_ID"]);
$q_ot = mysql_query("SELECT id from users where created_by_prim = '$_SESSION[MEM_ID]' ");
while ($r_ot = mysql_fetch_array($q_ot)) {
	array_push($users_ar, $r_ot["id"]);
}
$name="Firm Wide";
$flag_indi = 0;


$user_string = implode(',', $users_ar);

$type_id = mysql_real_escape_string($_POST["type_id"]);


$query = "SELECT distinct(user_admin_proxy_ad.report_id), companies.com_full_name,companies.com_isin, proxy_ad.meeting_date, proxy_ad.meeting_type, proxy_ad.evoting_end from user_admin_proxy_ad inner join proxy_ad on user_admin_proxy_ad.report_id = proxy_ad.id  inner join companies on proxy_ad.com_id = companies.com_id where user_admin_proxy_ad.com_approval = '".strtotime("today")."' order by proxy_ad.meeting_date asc";

$query_rep = mysql_query($query);

$row_rep = array();
$proxy_ids = array();
while ($row = mysql_fetch_array($query_rep)) {
	array_push($proxy_ids, $row["report_id"]);
	$row_rep[$row["report_id"]]["com_full_name"] = $row["com_full_name"];
	$row_rep[$row["report_id"]]["com_isin"] = $row["com_isin"];
	$row_rep[$row["report_id"]]["meeting_date"] = $row["meeting_date"];
	$row_rep[$row["report_id"]]["evoting_end"] = $row["evoting_end"];
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

$ar_fields_top = array("com_full_name","com_isin","meeting_type","meeting_date","evoting_end");

$ar_names_top = array("Company Name", "ISIN","Meeting Type","Meeting Date","e-Voting Deadline");

$ar_fields = array("meeting_date","com_full_name","empty","empty","meeting_type","resolution_name","vote","comment", "ses_reco");

$ar_names = array("Meeting Date","Company Name","% of AUM equity","% of capital","Meeting Type","Proposal Description","Vote","Reasons", "SES Recommendations");

$ar_width = array("10","12","10","10","10","35","10","35","12");

$seq=1;
$offset = 0;
$count =0;
$i=0;

$seq++;
$count_met = 0;

foreach ($proxy_ids as $proxy_id) {
	$seq_in = $seq;
	$count =0;
	$i = 0;
	$count =0;
	foreach ($ar_fields as $ar) {
		$cell_val = 65+$i+$offset;
		$objPHPExcel->getActiveSheet()->setCellValue(chr($cell_val).$seq, $ar_names[$count]);
		$objPHPExcel->getActiveSheet()->getColumnDimension(chr($cell_val))->setWidth($ar_width[$count]);
		$i++;
		$objPHPExcel->getActiveSheet()->getStyle(chr($cell_val).$seq)->applyFromArray($styleArrayborder);
		$count++;
	}
	$objPHPExcel->getActiveSheet()->getStyle('A'.$seq.':'.chr($cell_val).$seq)->applyFromArray($styleArray4);
	$seq ++;

	
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
						case 'com_full_name':
							$var = $row_rep[$proxy_id]["com_full_name"];
							break;
						case 'meeting_date':
							$var = date("d-M-y", $row_rep[$proxy_id]["meeting_date"]);
							break;
						case 'meeting_type':
							$var = $meeting_types[$row_rep[$proxy_id]["meeting_type"]];
							break;
						case 'resolution_name':
							$var = $res["resolution_name"];
							break;
						case 'vote':
							$var = $vote["vote"];
							break;
						case 'comment':
							$var = stripcslashes($vote["comment"]);
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
		$seq += 2;
	}
	$seq_out = $seq -1;

	$val_chr = chr($cell_val);

	$count_met++;

}
$seq--;
$objPHPExcel->getActiveSheet()->getStyle('I1:'.$val_chr.$seq.'')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:'.$val_chr.$seq.'')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:'.$val_chr.$seq.'')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);;

$objPHPExcel->getActiveSheet()->setTitle('MIS Report');


$name = $name.'_ProxyCommitteeAproval_'.date("dMy",strtotime("today"));

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$name.'.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>