<?php 
session_start();
error_reporting(0);

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

$sql = "SELECT * from analysts order by name asc";
$result_att=mysql_query($sql);
if(mysql_num_rows($result_att) == 0) {exit();}

$objPHPExcel = new PHPExcel();

// Set properties
$objPHPExcel->getProperties()->setCreator("SES")
->setLastModifiedBy("SES");
 

include ('styles.php');

//Serializing a

$ar_fields = array("sn","name","username","add_date");

$ar_names = array("SN","Name","Username","Add Date");

$ar_width = array("6","20","20","12");



$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', 'Name');
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(25);
$seq=2;
$offset = 0;
$count =0;
$i=0;
foreach ($ar_fields as $ar) {
 
$cell_val = 65+$i+$offset;
$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($cell_val).$seq, $ar_names[$count]);
$objPHPExcel->getActiveSheet()->getColumnDimension(chr($cell_val))->setWidth($ar_width[$count]);
$i++;
$objPHPExcel->getActiveSheet(0)->getStyle(chr($cell_val).$seq)->applyFromArray($styleArrayborder);
$count++;
}

$objPHPExcel->getActiveSheet()->getStyle('A'.$seq.':'.chr($cell_val).$seq)->applyFromArray($styleArray4);

$seq++;


$count_student = 0;
 
while($row_att = mysql_fetch_array($result_att))
{
 
$count_student++;
$i=0;
 
foreach ($ar_fields as $ar) {
 
$cell_val = 65+$i+$offset;
if($ar == 'sn') $var = $count_student;
elseif($ar == 'add_date') $var = date("d-M-y",$row_att[$ar]);
else $var = stripslashes($row_att[$ar]);
 
 

$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($cell_val).$seq, $var);

 
$objPHPExcel->getActiveSheet(0)->getStyle(chr($cell_val).$seq)->applyFromArray($styleArrayborder);
$i++;
 
}
$val_chr = chr($cell_val);
if($count_student%2 == 0)
{
$objPHPExcel->getActiveSheet()->getStyle('A'.$seq.':'.chr($cell_val).$seq)->applyFromArray($styleArray3);
}
else
{
$objPHPExcel->getActiveSheet()->getStyle('A'.$seq.':'.chr($cell_val).$seq)->applyFromArray($styleArray2);
}
 
$seq++;
}
$objPHPExcel->getActiveSheet()->freezePane('C3');
$objPHPExcel->getActiveSheet()->mergeCells('A1:'.$val_chr.'1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'SES Analysts List');

/*

  $objPHPExcel->getActiveSheet()->freezePane('B3');
  $objPHPExcel->getActiveSheet()->mergeCells('A1:'.$val_chr.'1');
 
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'SES Companies List');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB('FF000000');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(10); 
$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArraytop);


$objPHPExcel->getActiveSheet()->getStyle('A1:'.$val_chr.$seq.'')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:'.$val_chr.$seq.'')->getAlignment()->setWrapText(true);
*/
$objPHPExcel->getActiveSheet()->setTitle('Analysts');

$name = 'Analysts_'.date("d-M-y", strtotime("now"));
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$name.'.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>