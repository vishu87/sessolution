<?php session_start();
error_reporting(0);
require_once '../auth.php';

/** PHPExcel */
require_once 'Classes/PHPExcel.php';
include('../classes/UserClass.php');

$member = new User($_SESSION["MEM_ID"]);
$member->voting_records_firm($member->parent,1); 
  
$today = strtotime("today");

$query = "SELECT proxy_ad.*, companies.com_name, companies.com_isin from proxy_ad join companies on proxy_ad.com_id = companies.com_id where proxy_ad.id IN (".$member->voting_records_firm_string.")";
$query .= " and proxy_ad.meeting_date >= '$today' ";
$query .= " order by proxy_ad.meeting_date asc";

$objPHPExcel = new PHPExcel();

// Set properties
$objPHPExcel->getProperties()->setCreator("SES")->setLastModifiedBy("SES");

include ('styles.php');

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
//Serializing a

$ar_fields = array("sn","com_name","com_isin","meeting_date","meeting_type","evoting_start","evoting_end","evoting_plateform");

$ar_names = array("SN","Company Name","ISIN","Meeting Date","Meeting Type","e-Voting Start Date","e-Voting Deadline","e-Voting Platform");

$ar_width = array("6","25","15","15","15","20","20","20","12","12","12","12","12","20","20","10","20","20");

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(25);
$seq=2;
$offset = 0;
$count =0;
$i=0;
foreach ($ar_fields as $ar) {
 
    $cell_val = $i+$offset;
    $cell_val = getNameFromNumber($cell_val);

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cell_val.$seq, $ar_names[$count]);
    $objPHPExcel->getActiveSheet()->getColumnDimension($cell_val)->setWidth($ar_width[$count]);
    $i++;
    $objPHPExcel->getActiveSheet(0)->getStyle($cell_val.$seq)->applyFromArray($styleArrayborder);
    $count++;

}

$objPHPExcel->getActiveSheet()->getStyle('A'.$seq.':'.$cell_val.$seq)->applyFromArray($styleArray4);

$seq++;


$count = 0;

$fetch_query = mysql_query($query);

while($row = mysql_fetch_array($fetch_query)){
	++$count;
    $cell_val = 0;
    foreach ($ar_fields as $ar) {
    	$var = '';
    	if($ar == 'sn'){
    		$var = $count;
    	} else if($ar == 'meeting_date' || $ar == 'evoting_start' || $ar == 'evoting_end' ) {
    		if($row[$ar]) $var = date("d-M-y",$row[$ar]);
    	}else if($ar == 'meeting_type' ) {
            if($row[$ar]) $var = $meeting_types[$row[$ar]];
        } else {
            $var = $row[$ar];
        }
		$cell = getNameFromNumber($cell_val);
    	$objPHPExcel->getActiveSheet()->setCellValue($cell.$seq, $var);
        $cell_val++;
    }
	$objPHPExcel->getActiveSheet()->getStyle('A'.$seq.':'.getNameFromNumber($cell_val - 1).$seq)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		
	$seq++;

}

$objPHPExcel->getActiveSheet()->freezePane('B2');
$objPHPExcel->getActiveSheet()->mergeCells('A1:'.getNameFromNumber($cell_val - 1).'1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Upcoming Meetings');
$objPHPExcel->getActiveSheet()->getStyle('A1:'.getNameFromNumber($cell_val - 1).$seq.'')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:'.getNameFromNumber($cell_val - 1).'1')->applyFromArray($styleArray1);
/*


$objPHPExcel->getActiveSheet()->getStyle('A1:'.$val_chr.$seq.'')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:'.$val_chr.$seq.'')->getAlignment()->setWrapText(true);
*/
$objPHPExcel->getActiveSheet()->setTitle('Proxy Advisory');

$name = 'Upcoming_Meetings_'.date("d-M-y",strtotime("now"));
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$name.'.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>