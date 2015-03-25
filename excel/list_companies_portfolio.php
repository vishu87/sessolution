<?php session_start();
error_reporting(E_ALL);

/** PHPExcel */
require_once 'Classes/PHPExcel.php';

//Link to the Mysql
require_once '../auth.php';
$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if(!$link) {
die('Failed to connect to server: ' . mysql_error());
}
//Select database
$db = mysql_select_db(DB_DATABASE);
if(!$db) {
die("Unable to select database");
}

$sql = "SELECT companies.com_name, companies.com_isin, companies.com_bse_code from user_voting_company inner join companies on user_voting_company.com_id = companies.com_id where user_voting_company.user_id='$_SESSION[MEM_ID]' order by companies.com_name asc";
$result_att=mysql_query($sql);
if(mysql_num_rows($result_att) == 0) {exit();}

$objPHPExcel = new PHPExcel();

// Set properties
$objPHPExcel->getProperties()->setCreator("SES")
->setLastModifiedBy("SES");
 

include ('styles.php');

//Serializing a

$ar_fields = array("sn","com_name","com_bse_code","com_isin");

$ar_names = array("SN","Company Name","BSE Code","ISIN");

$ar_width = array("6","20","20","20");



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
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'List of Portfolio Companies');

$objPHPExcel->getActiveSheet()->setTitle('Companies');

$name = 'Portfolio_Companies_'.date("d-M-y", strtotime("now"));
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$name.'.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>