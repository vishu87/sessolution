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

//firm wide

if($user_id == 0){
	array_push($users_ar, $_SESSION["MEM_ID"]);
	$q_ot = mysql_query("SELECT id from users where created_by_prim = '$_SESSION[MEM_ID]' ");
	while ($r_ot = mysql_fetch_array($q_ot)) {
		array_push($users_ar, $r_ot["id"]);

	}
	$name="Firm Wide";
} else {
	array_push($users_ar, $user_id);
	$sql_user = mysql_query("SELECT name from users where id='$user_id' ");
	$name_user = mysql_fetch_array($sql_user);
	$name=$name_user["name"];
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

$query = "SELECT companies.com_bse_code, companies.com_name, proxy_ad.meeting_date, proxy_ad.meeting_type, proxy_ad.id as report_id, user_voting_proxy_reports.user_id  from user_voting_proxy_reports  inner join proxy_ad on user_voting_proxy_reports.report_id = proxy_ad.id inner join companies on proxy_ad.com_id = companies.com_id where user_voting_proxy_reports.user_id IN (".$user_string.") ".$date_sql." order by proxy_ad.meeting_date asc";

$query_rep = mysql_query($query);

if(mysql_num_rows($query_rep) == 0) {die('No meetings found');}

$objPHPExcel = new PHPExcel();
$man_reco_mis = $man_recos;

// // Set properties
$objPHPExcel->getProperties()->setCreator("SES")->setLastModifiedBy("SES");

include ('styles_mis.php');

// //Serializing a

$ar_fields = array("user","com_bse_code","com_name","meeting_date","meeting_type","resolution_number","resolution_name","man_reco","vote","comment");

$ar_names = array("Portfolio Manager","BSE Code","Company Name","Meeting Date","Meeting Type","Resolution Number","Resolution Type","Management Recommendation","Vote","Comment");

$ar_width = array("25","10","20","15","12","20","45","30","13","50");

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

while($row_rep = mysql_fetch_array($query_rep))
{
	$seq_in = $seq;
	
	$check_admin_vote_freeze = mysql_query("SELECT final_freeze, final_unfreeze, id from admin_proxy_ad where report_id='$row_rep[report_id]' order by id desc limit 1");
	$row_check = mysql_fetch_array($check_admin_vote_freeze);
	if($row_check["final_freeze"] != 0 && $row_check["final_unfreeze"] == 0){
		$flag = true;
	} else $flag = false;

		$query_res = mysql_query("SELECT id, resolution_name, resolution_number, man_reco from voting where report_id='$row_rep[report_id]' "); 
		while ( $res = mysql_fetch_array($query_res)){
			$i=0;
			$query_vote = mysql_query("SELECT user_voting.comment, votes.vote from user_voting join votes on user_voting.vote = votes.id where user_voting.user_id='$row_rep[user_id]' and user_voting.vote_id='$res[id]'  limit 1");
			$vote = mysql_fetch_array($query_vote);
			$sql = "SELECT name from users where id='$row_rep[user_id]' limit 1";
			$q_name = mysql_query($sql);
			$r_name = mysql_fetch_array($q_name);
			$u_name = $r_name["name"];

			foreach ($ar_fields as $ar) {
				$cell_val = 65+$i+$offset;
				switch ($ar) {
					case 'user':
						$var = $u_name;
						break;
					case 'com_bse_code':
						$var = $row_rep["com_bse_code"];
						break;
					case 'com_name':
						$var = $row_rep["com_name"];
						break;
					case 'meeting_date':
						$var = date("d-M-y", $row_rep["meeting_date"]);
						break;
					case 'meeting_type':
						$var = $meeting_types[$row_rep["meeting_type"]];
						break;
					case 'resolution_number':
						$var = ($flag)?$res["resolution_number"]:'';
						break;
					case 'resolution_name':
						$var = ($flag)?$res["resolution_name"]:'';
						break;
					case 'man_reco':
						$var = ($flag)?$man_reco_mis[$res["man_reco"]]:'';
						break;
					case 'vote':
						$var = ($flag)?$vote["vote"]:'';
						break;
					case 'comment':
						$var = ($flag)?stripcslashes($vote["comment"]):'';
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

$name = $name.'_MIS_Report_'.date("dMy",strtotime("today"));

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$name.'.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>