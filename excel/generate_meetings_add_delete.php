<?php session_start();


/** PHPExcel */
require_once 'Classes/PHPExcel.php';

//Link to the Mysql
require_once '../auth.php';
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

$objPHPExcel = new PHPExcel();

// // Set properties
$objPHPExcel->getProperties()->setCreator("SES")->setLastModifiedBy("SES");

include ('styles_mis.php');

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Meeting Added by Admin after Meeting Date');
$objPHPExcel->getActiveSheet()->mergeCells('A1:E1');

$objPHPExcel->getActiveSheet()->setCellValue('A3', 'PM Name');
$objPHPExcel->getActiveSheet()->setCellValue('B3', 'Company Name');
$objPHPExcel->getActiveSheet()->setCellValue('C3', 'Meeting Type');
$objPHPExcel->getActiveSheet()->setCellValue('D3', 'Meeting Date');
$objPHPExcel->getActiveSheet()->setCellValue('E3', 'Added On');

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);


$date_from = strtotime(mysql_real_escape_string($_POST["date_from"]));
$date_to = strtotime(mysql_real_escape_string($_POST["date_to"]));
$users_ar = array();

//firm wide

array_push($users_ar, $_SESSION["MEM_ID"]);
$q_ot = mysql_query("SELECT id from users where created_by_prim = '$_SESSION[MEM_ID]' ");
while ($r_ot = mysql_fetch_array($q_ot)) {
	array_push($users_ar, $r_ot["id"]);
}
$name="Audit Reports";


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

$query = "SELECT proxy_ad.id as report_id, proxy_ad.meeting_type, proxy_ad.meeting_date, user_activity.details from user_activity inner join proxy_ad on user_activity.report_id = proxy_ad.id where user_activity.user_id = '$_SESSION[MEM_ID]' ".$date_sql."  and user_activity.activity_id=32 order by proxy_ad.meeting_date asc";

$sql_query = mysql_query($query);

$seq = 5;

while($row = mysql_fetch_array($sql_query)){

	$objPHPExcel->getActiveSheet()->setCellValue('A'.$seq, $period[$pre_year]);
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$seq, $pre_quarter);
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$seq, $res);
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$seq, $for);
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$seq, $against);
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$seq, $abstain);

	$seq++;
	$count++;
}




$objPHPExcel->getActiveSheet()->setTitle('Meetings Added');


//////**********************************************SHEET2*****************************************************/////////

// $high_last = 0;
// $objPHPExcel->setActiveSheetIndex(1);

// $query = "SELECT distinct(proxy_ad.id) as report_id, proxy_ad.year, proxy_ad.com_id, proxy_ad.meeting_type , proxy_ad.meeting_date from user_voting_proxy_reports inner join proxy_ad on user_voting_proxy_reports.report_id = proxy_ad.id where user_voting_proxy_reports.user_id IN (".$user_string.") ".$date_sql." order by proxy_ad.meeting_date asc";

// $sql_query = mysql_query($query);

// $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Summary of voting details in case of inclusion of PM Votes');


// $seq = 3;

// $objPHPExcel->getActiveSheet()->setCellValue('A'.$seq, 'Company Name');
// $objPHPExcel->getActiveSheet()->setCellValue('B'.$seq, 'Meeting type');
// $objPHPExcel->getActiveSheet()->setCellValue('C'.$seq, 'Meeting Date');
// $objPHPExcel->getActiveSheet()->setCellValue('D'.$seq, 'Resolution Number');
// $objPHPExcel->getActiveSheet()->setCellValue('E'.$seq, 'Resolution');

// $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
// $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
// $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
// $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
// $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);


// $seq++;

// while($row = mysql_fetch_array($sql_query)){
// 	$seq_in = $seq;
// 	$report_id = $row["report_id"];
// 	$year = $row["year"];

// 	$quarter = ceil(date("n",$row["meeting_date"])/3) - 1;

// 	$final_freeze_query = mysql_query("SELECT final_freeze, ignore_an from user_admin_proxy_ad where report_id='$report_id' and user_id = '$_SESSION[MEM_ID]' limit 1");
// 	$row_final_freeze = mysql_fetch_array($final_freeze_query);

// 	if($row_final_freeze["final_freeze"] == 0) continue;
// 	if($row_final_freeze["ignore_an"] == 0) continue;

// 	$company_details = mysql_query("SELECT com_name from companies where com_id='$row[com_id]' ");
// 	$com  = mysql_fetch_array($company_details);

// 	$total_res_query = mysql_query("SELECT resolution_number, resolution_name, id from voting where report_id='$report_id' order by resolution_number asc ");
// 	while ($row_vote = mysql_fetch_array($total_res_query)) {
		
// 		$i = 0;

// 		$objPHPExcel->getActiveSheet()->setCellValue(getNameFromNumber($i).$seq, $com["com_name"]);
// 		$i++;
// 		$objPHPExcel->getActiveSheet()->setCellValue(getNameFromNumber($i).$seq, $meeting_types[$row["meeting_type"]]);
// 		$i++;
// 		$objPHPExcel->getActiveSheet()->setCellValue(getNameFromNumber($i).$seq, date("d-M-y",$row["meeting_date"]));
// 		$i++;
// 		$objPHPExcel->getActiveSheet()->setCellValue(getNameFromNumber($i).$seq, $row_vote["resolution_number"]);
// 		$i++;
// 		$objPHPExcel->getActiveSheet()->setCellValue(getNameFromNumber($i).$seq, $row_vote["resolution_name"]);
// 		$i++;

// 		$query_users = mysql_query("SELECT user_voting_proxy_reports.user_id, users.name, users.user_admin_name from user_voting_proxy_reports inner join users on user_voting_proxy_reports.user_id = users.id where user_voting_proxy_reports.user_id IN (".$user_string.") and user_voting_proxy_reports.report_id = '$report_id' order by user_voting_proxy_reports.user_id asc ");

// 			while ($row_users = mysql_fetch_array($query_users)) {
// 				$user_name = ($_SESSION["MEM_ID"] == $row_users["user_id"])?$row_users["user_admin_name"]:$row_users["name"];

// 				$objPHPExcel->getActiveSheet()->setCellValue(getNameFromNumber($i).$seq, $user_name);
// 				$i++;

// 				$query_user_vote = mysql_query("SELECT user_voting.vote, votes.vote as vote_name from user_voting left join votes on user_voting.vote = votes.id where user_voting.vote_id = '$row_vote[id]' and user_voting.user_id='$row_users[user_id]' order by user_voting.id desc limit 1 ");
// 				$row_user_vote = mysql_fetch_array($query_user_vote);
// 				$value_vote = $row_user_vote["vote_name"];

// 				$objPHPExcel->getActiveSheet()->setCellValue(getNameFromNumber($i).$seq, $value_vote);
// 				$i++;
// 			}


// 		$seq++;
// 	}
// 	if($i > $high_last) $high_last = $i;
// 	$count++;

// 	$seq_out = $seq -1;

// 	if($count%2 == 0){
// 		$objPHPExcel->getActiveSheet()->getStyle('A'.$seq_in.':'.getNameFromNumber($i -1).$seq_out)->applyFromArray($styleArray3);
// 	} else {
// 		$objPHPExcel->getActiveSheet()->getStyle('A'.$seq_in.':'.getNameFromNumber($i -1).$seq_out)->applyFromArray($styleArray2);
// 	}

// }

// $high_last = $high_last -1;
// $seq_last = $seq - 1;

// $i = 5;
// $count_i = 1;
// while($i <= $high_last){
// 	$objPHPExcel->getActiveSheet()->setCellValue(getNameFromNumber($i).'3', 'PM'.$count_i.' Name');
// 	$objPHPExcel->getActiveSheet()->getColumnDimension(getNameFromNumber($i))->setWidth(20);

// 	$i++;
// 	$objPHPExcel->getActiveSheet()->setCellValue(getNameFromNumber($i).'3', 'PM'.$count_i.' Vote');
// 	$objPHPExcel->getActiveSheet()->getColumnDimension(getNameFromNumber($i))->setWidth(20);

// 	$i++;
// 	$count_i++;
// }
// $objPHPExcel->getActiveSheet()->getStyle('A3'.':'.getNameFromNumber($high_last).'3')->applyFromArray($styleArray4);


// $objPHPExcel->getActiveSheet()->mergeCells('A1:'.getNameFromNumber($high_last).'1');

// $objPHPExcel->getActiveSheet()->getStyle('A1:'.getNameFromNumber($high_last).$seq_last.'')->getAlignment()->setWrapText(true);




// $objPHPExcel->getActiveSheet()->setTitle('Summary Remarks');

$objPHPExcel->setActiveSheetIndex(0);

$name = $name.'_Votes_Summary_Report_'.date("dMy",strtotime("today"));

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$name.'.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;

?>