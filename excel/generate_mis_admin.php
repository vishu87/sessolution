<?php session_start();

/** PHPExcel */
require_once 'Classes/PHPExcel.php';

//Link to the Mysql
//require_once '../auth.php';
error_reporting(E_ALL ^ (E_DEPRECATED | E_NOTICE));
	set_time_limit(1200);
	define('DB_HOST', 'localhost');
	define('DB_USER', 'root');
	define('DB_PASSWORD', '');
	define('DB_DATABASE', 'portal_new');
	$package_types = array("","Proxy Advisory","Governance Scores");
	$meeting_types = array("","AGM", "EGM", "PB","CCM");
	$report_types  = array("","Proxy Advisory","CGS","Research");
	$task_types = array("","Data","Analysis","Review");
	$file_types = array("pdf","doc","docx","xls","xlsx");
	$man_recos = array("","FOR","AGAINST","ABSTAIN");
	$types_business = array("","Ordinary","Special");
	$types_res_os = array("","Ordinary","Special");
	$man_share_recos = array("","Management","Shareholders");

$link = mysql_connect( DB_HOST, DB_USER , DB_PASSWORD );
	if(!$link) {
		die('Failed to connect to server: ' . mysql_error());
	}
	
	//Select database
	$db = mysql_select_db(DB_DATABASE);
	if(!$db) {
		die("Unable to select database");
	}

$date_from = strtotime("01-04-2014");
$date_to = strtotime("31-03-2015");
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

/////////////////////////////firm wide

if($date_from && $date_to){
	$date_sql = ' proxy_ad.meeting_date between '.$date_from.' and '.$date_to;
} else if($date_from){
	$date_sql = ' and proxy_ad.meeting_date >= '.$date_from;
} else if($date_to){
	$date_sql = ' and proxy_ad.meeting_date <= '.$date_to;
} else {
	$date_sql = '';
}

$query = "SELECT proxy_ad.id, companies.com_full_name, proxy_ad.meeting_date, proxy_ad.meeting_type from proxy_ad inner join companies on proxy_ad.com_id = companies.com_id where ".$date_sql." order by proxy_ad.meeting_date asc ";

$query_rep = mysql_query($query);

$row_rep = array();
$proxy_ids = array();
while ($row = mysql_fetch_array($query_rep)) {
	array_push($proxy_ids, $row["id"]);
	$row_rep[$row["id"]]["com_full_name"] = $row["com_full_name"];
	$row_rep[$row["id"]]["meeting_date"] = $row["meeting_date"];
	$row_rep[$row["id"]]["meeting_type"] = $row["meeting_type"];
}

if(mysql_num_rows($query_rep) == 0) {die('No meetings found');}

$objPHPExcel = new PHPExcel();
$man_reco_mis = $man_recos;
$man_share_reco_mis = $man_share_recos;

// // Set properties
$objPHPExcel->getProperties()->setCreator("SES")->setLastModifiedBy("SES");

include ('styles_mis.php');

// //Serializing a

$ar_fields = array("quarter","meeting_date","com_full_name","meeting_type","man_share_reco","resolution_name","man_reco","ses_reco","comment");

$ar_names = array("Quarter","Meeting Date","Company Name","Type of Meetings","Proposal by Management or Shareholder","Proposal's description","Investee company's Management Recommendation","SES Recommendation","Reason supporting the vote decision");


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
	$qu = ceil(date("n",$row_rep[$proxy_id]["meeting_date"])/3) - 1;
	switch ($qu) {
		case 0:
			$quarter = 1;
			break;
		default:
			$quarter = $qu;
			break;
	}

		$query_res = mysql_query("SELECT voting.id, voting.resolution_name, voting.resolution_number, voting.man_reco, voting.man_share_reco, ses_recos.reco, voting.detail from voting inner join ses_recos on voting.ses_reco = ses_recos.id where voting.report_id='$proxy_id' and voting.ses_reco NOT IN (0,1) "); 
		if(mysql_num_rows($query_res) == 0) continue;
			while ( $res = mysql_fetch_array($query_res)){
				$i=0;
				foreach ($ar_fields as $ar) {
					$cell_val = 65+$i+$offset;
					switch ($ar) {
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
							$var = $res["resolution_number"];
							break;
						case 'resolution_name':
							$var = $res["resolution_name"];
							break;
						case 'man_reco':
							$var = $man_reco_mis[$res["man_reco"]];
							break;
						case 'man_share_reco':
							$var = $man_share_reco_mis[$res["man_share_reco"]];
							break;
						case 'comment':
							$var = stripcslashes($res["detail"]);
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