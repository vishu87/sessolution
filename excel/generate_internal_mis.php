<?php session_start();
error_reporting(E_ALL);

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

/** PHPExcel */
require_once 'Classes/PHPExcel.php';

//Link to the Mysql
require_once '../auth.php';

$date_from = strtotime(mysql_real_escape_string($_POST["date_from"]));
$date_to = strtotime(mysql_real_escape_string($_POST["date_to"]));
$users_ar = array();
$user_id = mysql_real_escape_string($_POST["user_id"]);

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

if($date_from && $date_to){
	$date_sql = ' and proxy_ad.meeting_date between '.$date_from.' and '.$date_to;
} else if($date_from){
	$date_sql = ' and proxy_ad.meeting_date >= '.$date_from;
} else if($date_to){
	$date_sql = ' and proxy_ad.meeting_date <= '.$date_to;
} else {
	$date_sql = '';
}

$query = "SELECT distinct user_voting_proxy_reports.report_id from user_voting_proxy_reports inner join proxy_ad on user_voting_proxy_reports.report_id = proxy_ad.id where user_voting_proxy_reports.user_id IN (".$user_string.") ".$date_sql." order by proxy_ad.meeting_date asc";

$query_rep = mysql_query($query);

if(mysql_num_rows($query_rep) == 0) {die('No meetings found');}

$objPHPExcel = new PHPExcel();
$man_reco_mis = $man_recos;
$man_share_reco_mis = $man_share_recos;

// // Set properties
$objPHPExcel->getProperties()->setCreator("SES")->setLastModifiedBy("SES");

include ('styles_mis.php');

// //Serializing a

$ar_fields = array("meeting_date","com_full_name","meeting_type","man_share_reco","resolution_name","man_reco","vote","comment");
$ar_fields_pm = array("user","vote","comment");


$ar_names = array("Meeting Date","Company Name","Type of Meetings","Proposal by Management or Shareholder","Proposal's description","Investee company's Management Recommendation","Admin Vote","Admin Reason");

$ar_width = array("25","20","20","20","45","45","15","30");

$seq=1;
$offset = 0;
$count =0;
$i=0;

foreach ($ar_fields as $ar) {
	$cell_val = $i+$offset;
	$objPHPExcel->getActiveSheet()->setCellValue(getNameFromNumber($cell_val).$seq, $ar_names[$count]);
	$objPHPExcel->getActiveSheet()->getColumnDimension(getNameFromNumber($cell_val))->setWidth($ar_width[$count]);
	$i++;
	$objPHPExcel->getActiveSheet()->getStyle(getNameFromNumber($cell_val).$seq)->applyFromArray($styleArrayborder);
	$count++;
}

$objPHPExcel->getActiveSheet()->getStyle('A'.$seq.':'.getNameFromNumber($cell_val).$seq)->applyFromArray($styleArray4);


$seq++;
$count_met = 0;
$cell_val_max = 0;

while($row_rep = mysql_fetch_array($query_rep))
{
	$seq_in = $seq;
	
	$check_admin_vote_freeze = mysql_query("SELECT final_freeze, final_unfreeze, id from admin_proxy_ad where report_id='$row_rep[report_id]' order by id desc limit 1");
	$row_check = mysql_fetch_array($check_admin_vote_freeze);
	if($row_check["final_freeze"] != 0 && $row_check["final_unfreeze"] == 0){
		$flag_admin = true;
	} else $flag_admin = false;


	$check_user_admin = mysql_query("SELECT final_freeze, final_unfreeze, ignore_an from user_admin_proxy_ad where report_id='$row_rep[report_id]' and user_id='$_SESSION[MEM_ID]'  ");
	$row_check_admin = mysql_fetch_array($check_user_admin);
	$ignore_an = $row_check_admin["ignore_an"];

	if($row_check_admin["final_freeze"] != 0 && $row_check_admin["final_unfreeze"] == 0){
		$flag_user_freeze = true;
	} else {
		$flag_user_freeze = false;
	}

	$user_proxy_array = array();

	if($flag_indi == 0){
		$check_users = mysql_query("SELECT user_id from user_voting_proxy_reports where report_id='$row_rep[report_id]' and user_id in (".$user_string.") ");
		while ($row_check_users = mysql_fetch_array($check_users)) {
			$check_user_freeze = mysql_query("SELECT id from user_proxy_ad where freeze_on != 0 and unfreeze_on = 0 and user_id='$row_check_users[user_id]' and report_id='$row_rep[report_id]' ");
			if(mysql_num_rows($check_user_freeze) > 0) array_push($user_proxy_array, $row_check_users["user_id"]);
		}
	} else {
		array_push($user_proxy_array, $user_id);
	}

	$sql_meeting = mysql_query("SELECT companies.com_bse_code, companies.com_full_name, proxy_ad.meeting_date, proxy_ad.meeting_type from proxy_ad inner join companies on proxy_ad.com_id = companies.com_id where id='$row_rep[report_id]' limit 1 ");
	$row_meeting = mysql_fetch_array($sql_meeting);
	
	$user_admin_proxy_array = array();
	array_push($user_admin_proxy_array, $_SESSION["MEM_ID"]);

	foreach ($user_admin_proxy_array as $user_id_vote) {

		$query_res = mysql_query("SELECT id, resolution_name, resolution_number, man_reco, man_share_reco from voting where report_id='$row_rep[report_id]'  order by priority, resolution_number asc "); 
			while ( $res = mysql_fetch_array($query_res)){
				$i=0;
				$query_vote = mysql_query("SELECT user_admin_voting.comment, votes.vote from user_admin_voting join votes on user_admin_voting.vote = votes.id where user_admin_voting.user_id='$user_id_vote' and user_admin_voting.vote_id='$res[id]'  limit 1");
				$vote = mysql_fetch_array($query_vote);

				$sql = "SELECT name from users where id='$user_id_vote' limit 1";
				$q_name = mysql_query($sql);
				$r_name = mysql_fetch_array($q_name);

				if($r_name["user_admin_name"] == ''){
					$u_name = $r_name["name"];
				} else {
					$u_name = $r_name["user_admin_name"];
				}

				foreach ($ar_fields as $ar) {
					$cell_val = $i+$offset;
					switch ($ar) {
						case 'user':
							$var = $u_name;
							break;
						case 'quarter':
							$var = $quarter;
							break;
						case 'com_full_name':
							$var = $row_meeting["com_full_name"];
							break;
						case 'meeting_date':
							$var = date("d-M-y", $row_meeting["meeting_date"]);
							break;
						case 'meeting_type':
							$var = $meeting_types[$row_meeting["meeting_type"]];
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
							$var = ($flag_user_freeze && $ignore_an == 0)?$vote["vote"]:'';
							break;
						case 'comment':
							$var = ($flag_user_freeze && $ignore_an == 0)?stripcslashes($vote["comment"]):'';
							break;
						default:
							$var = '';
							break;
					}
					
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue(getNameFromNumber($cell_val).$seq, $var);
					$objPHPExcel->getActiveSheet(0)->getStyle(getNameFromNumber($cell_val).$seq)->applyFromArray($styleArrayborder);
					$i++;
				}
				$seq++;
			}
	}
	$seq_total_res_out = $seq;
	$cell_res_out_admin = $i;
	$count_user = 0;

	foreach ($user_proxy_array as $user_id_vote) {
		$seq = $seq_in;
		$query_res = mysql_query("SELECT id from voting where report_id='$row_rep[report_id]' "); 
			while ( $res = mysql_fetch_array($query_res)){

				$i = $cell_res_out_admin + $count_user*3;

				$query_vote = mysql_query("SELECT user_voting.comment, votes.vote from user_voting join votes on user_voting.vote = votes.id where user_voting.user_id='$user_id_vote' and user_voting.vote_id='$res[id]'  limit 1");
				$vote = mysql_fetch_array($query_vote);

				$sql = "SELECT name, user_admin_name from users where id='$user_id_vote' limit 1";
				$q_name = mysql_query($sql);
				$r_name = mysql_fetch_array($q_name);

				if($r_name["user_admin_name"] == ''){
					$u_name = $r_name["name"];
				} else {
					$u_name = $r_name["user_admin_name"];
				}
				foreach ($ar_fields_pm as $ar) {
					$cell_val = $i+$offset;
					switch ($ar) {
						case 'user':
							$var = $u_name;
							break;
						case 'vote':
							$var = $vote["vote"];
							break;
						case 'comment':
							$var = stripcslashes($vote["comment"]);
							break;
						default:
							$var = '';
							break;
					}
					
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue(getNameFromNumber($cell_val).$seq, $var);
					$objPHPExcel->getActiveSheet(0)->getStyle(getNameFromNumber($cell_val).$seq)->applyFromArray($styleArrayborder);
					$i++;
				}
				$seq++;
			}
		$count_user++;
	}

	if($cell_val > $cell_val_max) $cell_val_max = $cell_val;

	$seq = $seq_total_res_out;

	$seq_out = $seq -1;

	$val_chr = getNameFromNumber($cell_val);

	if($count_met%2 == 0){
		$objPHPExcel->getActiveSheet()->getStyle('A'.$seq_in.':'.getNameFromNumber($cell_val).$seq_out)->applyFromArray($styleArray3);
	} else {
		$objPHPExcel->getActiveSheet()->getStyle('A'.$seq_in.':'.getNameFromNumber($cell_val).$seq_out)->applyFromArray($styleArray2);
	}

	$count_met++;
}
$seq--;
$objPHPExcel->getActiveSheet()->getStyle('I1:'.$val_chr.$seq.'')->getAlignment()->setWrapText(true);
// $objPHPExcel->getActiveSheet()->getStyle('F1:'.$val_chr.$seq.'')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:'.$val_chr.$seq.'')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$total_pm = ceil(($cell_val_max - 8)/3);

$seq = 1;

for ($j=1; $j <= $total_pm ; $j++) { 

	$cell_val = 8 + ($j-1)*3;
	$objPHPExcel->getActiveSheet()->setCellValue(getNameFromNumber($cell_val).$seq, "PM".$j." Name");
	$objPHPExcel->getActiveSheet()->getColumnDimension(getNameFromNumber($cell_val))->setWidth(20);

	$cell_val++;
	$objPHPExcel->getActiveSheet()->setCellValue(getNameFromNumber($cell_val).$seq, "PM".$j." Vote");
	$objPHPExcel->getActiveSheet()->getColumnDimension(getNameFromNumber($cell_val))->setWidth(20);

	$cell_val++;
	$objPHPExcel->getActiveSheet()->setCellValue(getNameFromNumber($cell_val).$seq, "PM".$j." Reason");
	$objPHPExcel->getActiveSheet()->getColumnDimension(getNameFromNumber($cell_val))->setWidth(45);

	$objPHPExcel->getActiveSheet()->getStyle('A'.$seq.':'.getNameFromNumber($cell_val).$seq)->applyFromArray($styleArray4);
}

$objPHPExcel->getActiveSheet()->freezePane('F1');
$objPHPExcel->getActiveSheet()->setTitle('MIS Report');

$name = $name.'_Internal_MIS_Report_'.date("dMy",strtotime("today"));

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$name.'.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>