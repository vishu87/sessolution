<?php session_start();

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

error_reporting(E_ALL^E_NOTICE);

$date_from = strtotime(mysql_real_escape_string($_POST["date_from"]));
$date_to = strtotime(mysql_real_escape_string($_POST["date_to"]));

$user_ids = array();
$user_names = array();

$sql_user = mysql_query("SELECT id,name,user_admin_name from users where created_by_prim='$_SESSION[MEM_ID]' OR id= '$_SESSION[MEM_ID]' order by id asc ");
while ($row_user = mysql_fetch_array($sql_user)) {
	array_push($user_ids, $row_user["id"]);
	if($row_user["id"] == $_SESSION["MEM_ID"]){
		array_push($user_names, $row_user["user_admin_name"]);
	} else array_push($user_names, $row_user["name"]);
}

$user_string = implode(',', $user_ids);

if($date_from && $date_to){
	$date_sql = ' and proxy_ad.meeting_date between '.$date_from.' and '.$date_to;
} else if($date_from){
	$date_sql = ' and proxy_ad.meeting_date >= '.$date_from;
} else if($date_to){
	$date_sql = ' and proxy_ad.meeting_date <= '.$date_to;
} else {
	$date_sql = '';
}

$objPHPExcel = new PHPExcel();

// // Set properties
$objPHPExcel->getProperties()->setCreator("SES")->setLastModifiedBy("SES");

include ('styles_mis.php');

// //Serializing a
$ar_names = array("Meeting Date","Company Name","Type of Meetings","Admin Freeze","Upload Form");
$ar_width = array("25","20","20","20","20");

$seq=1;
$offset = 0;
$count =0;
$i=0;

foreach ($ar_names as $ar) {
	$cell_val = $i+$offset;
	$cell_val = getNameFromNumber($cell_val);
	$objPHPExcel->getActiveSheet()->setCellValue($cell_val.$seq, $ar);
	$objPHPExcel->getActiveSheet()->getColumnDimension($cell_val)->setWidth($ar_width[$count]);
	$i++;
	$objPHPExcel->getActiveSheet()->getStyle($cell_val.$seq)->applyFromArray($styleArrayborder);
	$count++;
}

foreach ($user_names as $ar) {
	$cell_val = $i+$offset;
	$cell_val = getNameFromNumber($cell_val);
	$objPHPExcel->getActiveSheet()->setCellValue($cell_val.$seq, $ar);
	$objPHPExcel->getActiveSheet()->getColumnDimension($cell_val)->setWidth(20);
	$i++;
	$objPHPExcel->getActiveSheet()->getStyle($cell_val.$seq)->applyFromArray($styleArrayborder);
	$count++;
}

$objPHPExcel->getActiveSheet()->getStyle('A'.$seq.':'.$cell_val.$seq)->applyFromArray($styleArray4);

$seq++;
$count_met = 0;

$sql_reports = mysql_query("SELECT distinct report_id from user_voting_proxy_reports where user_id IN ($user_string) ".$date_sql." ");
while($row_rep = mysql_fetch_array($sql_reports)){
	$seq_in = $seq;
	$i=0;

	$sql_details = mysql_query("SELECT companies.com_name, companies.com_bse_code, proxy_ad.meeting_date, proxy_ad.meeting_type from proxy_ad inner join companies on proxy_ad.com_id = companies.com_id where proxy_ad.id = '$row_rep[report_id]' ");
	$row_details = mysql_fetch_array($sql_details);
	//echo $row_rep["report_id"].$row_details["com_name"].'<br>';
	$meeting_date = $row_details["meeting_date"];

	$cell_val = $i + $offset; $cell_val = getNameFromNumber($cell_val); $i++;
	$objPHPExcel->getActiveSheet()->setCellValue($cell_val.$seq, date("d-M-y",$row_details["meeting_date"]));
	$objPHPExcel->getActiveSheet()->getStyle($cell_val.$seq)->applyFromArray($styleArrayborder);

	$cell_val = $i + $offset; $cell_val = getNameFromNumber($cell_val);  $i++;
	$objPHPExcel->getActiveSheet()->setCellValue($cell_val.$seq, $row_details["com_name"]);
	$objPHPExcel->getActiveSheet()->getStyle($cell_val.$seq)->applyFromArray($styleArrayborder);

	$cell_val = $i + $offset; $cell_val = getNameFromNumber($cell_val); $i++;
	$objPHPExcel->getActiveSheet()->setCellValue($cell_val.$seq, $meeting_types[$row_details["meeting_type"]] );
	$objPHPExcel->getActiveSheet()->getStyle($cell_val.$seq)->applyFromArray($styleArrayborder);
	
	// check for final freeze
	$time_limit = $meeting_date + 86400;

	$query_final_freeze = mysql_query("SELECT final_freeze,auto_abstained from user_admin_proxy_ad where report_id='$row_rep[report_id]' and user_id='$_SESSION[MEM_ID]' " );
	if(mysql_num_rows($query_final_freeze)){
		$val = 'Not Freezed';
	} else {
		$row_final_freeze = mysql_fetch_array($query_final_freeze);
		if($row_final_freeze["auto_abstained"] > 0){
			$val = 'Not Freezed';
		} else if($row_final_freeze["final_freeze"] == 0 || $row_final_freeze["final_freeze"] > $time_limit){
			$query_check = mysql_query("SELECT timestamp from user_activity where user_id='$_SESSION[MEM_ID]' and report_id='$row_rep[report_id]' and activity_id = 9 order by timestamp desc limit 1");
			if(mysql_num_rows($query_check) > 0){	
				$row_check = mysql_fetch_array($query_check);
				$val = date("d-M-y",$row_check["timestamp"]);
			} else {
				$val = 'Not Freezed';
			}
		} else {
			$val = date("d-M-y",$row_final_freeze["final_freeze"]);
		}
	}

	$cell_val = $i + $offset; $cell_val = getNameFromNumber($cell_val); $i++;
	$objPHPExcel->getActiveSheet()->setCellValue($cell_val.$seq, $val);
	$objPHPExcel->getActiveSheet()->getStyle($cell_val.$seq)->applyFromArray($styleArrayborder);

	$query_form_upload = mysql_query("SELECT form_upload_date from proxies where proxy_id='$row_rep[report_id]' and user_id='$_SESSION[MEM_ID]' " );
	if(mysql_num_rows($query_form_upload)){
		$val = 'N/A';
	} else {
		$row_upload = mysql_fetch_array($query_form_upload);
		if($row_upload["form_upload_date"] > 0){
			$val = date("d-M-y",$row_upload["form_upload_date"]);
		} else {
			$val = 'N/A';
		}
	}

	$cell_val = $i + $offset; $cell_val = getNameFromNumber($cell_val); $i++;
	$objPHPExcel->getActiveSheet()->setCellValue($cell_val.$seq, $val);
	$objPHPExcel->getActiveSheet()->getStyle($cell_val.$seq)->applyFromArray($styleArrayborder);

	foreach ($user_ids as $user_id) {
		
		$query_final_freeze = mysql_query("SELECT freeze_on, auto_abstained from user_proxy_ad where report_id='$row_rep[report_id]' and user_id='$user_id' " );
		if(mysql_num_rows($query_final_freeze)){
			$val = 'Not Freezed';
		} else {
			$row_final_freeze = mysql_fetch_array($query_final_freeze);
			if($row_final_freeze["auto_abstained"] > 0){
				$val = 'Not Freezed';
			} else if($row_final_freeze["final_freeze"] == 0 || $row_final_freeze["final_freeze"] > $time_limit){
				$query_check = mysql_query("SELECT timestamp from user_activity where user_id='$user_id' and report_id='$row_rep[report_id]' and activity_id = 7 order by timestamp desc limit 1");
				if(mysql_num_rows($query_check) > 0){	
					$row_check = mysql_fetch_array($query_check);
					$val = date("d-M-y",$row_check["timestamp"]);
				} else {
					$val = 'Not Freezed';
				}
			} else {
				$val = date("d-M-y",$row_final_freeze["final_freeze"]);
			}
		}

		$cell_val = $i + $offset; $cell_val = getNameFromNumber($cell_val); $i++;
		$objPHPExcel->getActiveSheet()->setCellValue($cell_val.$seq, $val);
		$objPHPExcel->getActiveSheet()->getStyle($cell_val.$seq)->applyFromArray($styleArrayborder);

	}

	$seq++;
	$seq_out = $seq -1;

	if($count_met%2 == 0){
		$objPHPExcel->getActiveSheet()->getStyle('A'.$seq_in.':'.$cell_val.$seq_out)->applyFromArray($styleArray3);
	} else {
		$objPHPExcel->getActiveSheet()->getStyle('A'.$seq_in.':'.$cell_val.$seq_out)->applyFromArray($styleArray2);
	}

	$count_met++;
}

$seq--;

$objPHPExcel->getActiveSheet()->getStyle('A1:'.$cell_val.$seq.'')->getAlignment()->setWrapText(true);
// $objPHPExcel->getActiveSheet()->getStyle('F1:'.$cell_val.$seq.'')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:'.$cell_val.$seq.'')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()->setTitle('MIS Report');

$name = $name.'_MIS_Report_'.date("dMy",strtotime("today"));

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$name.'.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>