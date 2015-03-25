<?php session_start();
error_reporting(E_ALL);

/** PHPExcel */
require_once 'Classes/PHPExcel.php';

//Link to the Mysql
require_once '../sysauth.php';
$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if(!$link) {
die('Failed to connect to server: ' . mysql_error());
}
//Select database
$db = mysql_select_db(DB_DATABASE);
if(!$db) {
die("Unable to select database");
}


$date_high = $_POST["date_to_p"];
$date_low = $_POST["date_from_p"];
$date_sql = '';

                        
  if($date_high && $date_low){
   $date_sql = "and research.publishing_date <= '$date_high' and research.publishing_date >= '$date_low' ";
  } elseif ($date_high && !$date_low) {
    $date_sql = "and research.publishing_date <= '$date_high' ";
  } elseif(!$date_high && $date_low) {
    $date_sql = "and research.publishing_date >= '$date_low' ";
  }

   $sql = mysql_query("SELECT research.*, companies.com_name, companies.com_bse_code from research inner join companies on research.com_id = companies.com_id ".$date_sql." order by research.publishing_date desc" );                    

if(mysql_num_rows($sql) == 0) {exit();}

$objPHPExcel = new PHPExcel();

// Set properties
$objPHPExcel->getProperties()->setCreator("SES")
->setLastModifiedBy("SES");
 

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

$ar_fields = array("sn","com_bse_code","com_name","publishing_date","report_upload","sub","heading","description");

$ar_names = array("SN","BSE Code","Company Name","Publishing Date","Report","Subscribers","Heading","Description");

$ar_width = array("6","10","20","10","8","12","10","10");

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


$count_student = 0;

while($row = mysql_fetch_array($sql))
{
 	$count_student++;
	$i=0;
	 
	foreach ($ar_fields as $ar) {
	 	$var = '';
		$cell = $i+$offset;
		$cell_val = getNameFromNumber($cell);
		
		if($ar == 'sn') $var = $count_student;
		elseif ($ar == 'sub') {
			
     $sql_sub = mysql_query("SELECT distinct user_id from research_users where res_id='$row[res_id]' ");
                                  
      $var =mysql_num_rows($sql_sub);
		}
		elseif ($ar == 'report_upload') {
			$var = ($row[$ar] == '')?'No':'Yes';
		}
		elseif ($ar == 'publishing_date') {
			$var = date("d-M-y",$row[$ar]);
		}
		else {
		   $var= stripslashes($row[$ar]);
		}
		 
		
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cell_val.$seq, $var);
		

		$i++;
	 
	}

	$val_chr = $cell_val;


	$objPHPExcel->getActiveSheet()->getStyle('A'.$seq.':'.$val_chr.$seq)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		
	$seq++;

}
$objPHPExcel->getActiveSheet()->freezePane('D3');
$objPHPExcel->getActiveSheet()->mergeCells('A1:'.$val_chr.'1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Research');
$objPHPExcel->getActiveSheet()->getStyle('A1:'.$val_chr.$seq.'')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:'.$val_chr.($seq-1).'')->applyFromArray($styleArraylr);
/*


$objPHPExcel->getActiveSheet()->getStyle('A1:'.$val_chr.$seq.'')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:'.$val_chr.$seq.'')->getAlignment()->setWrapText(true);
*/
$objPHPExcel->getActiveSheet()->setTitle('Research');

$name = 'Research_'.date("d-M-y",strtotime("now"));
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$name.'.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>