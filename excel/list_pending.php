<?php session_start();
error_reporting(E_ALL ^ E_NOTICE);

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

$sql = "select an_id,name from analysts order by name desc";

$result_att=mysql_query($sql);

if(mysql_num_rows($result_att) == 0) {exit();}

$objPHPExcel = new PHPExcel();

// Set properties
$objPHPExcel->getProperties()->setCreator("SES")
->setLastModifiedBy("SES");
 

include ('styles.php');

//Serializing a
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

$ar_fields = array("sn","name","data","analysis","review");

$ar_names = array("SN","Name","Data","Analysis","Review");
$ar_width = array("6","20","20","20","20");

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


$today = strtotime("today");
$fivedays = $today + 5*86400;

$count_student = 0;
 
while($row_att = mysql_fetch_array($result_att))
{
 
$count_student++;
$i=0;
  $sql_pack = mysql_query("SELECT id from report_analyst where an_id='$row_att[an_id]' and deadline BETWEEN $today and '$fivedays' and completed_on = ''  and type='1' ");
  $data_rem = mysql_num_rows($sql_pack);
  $sql_pack = mysql_query("SELECT id from report_analyst where an_id='$row_att[an_id]' and deadline BETWEEN $today and '$fivedays' and completed_on = ''  and type='2' ");
  $analysis_rem = mysql_num_rows($sql_pack);
  $sql_pack = mysql_query("SELECT id from report_analyst where an_id='$row_att[an_id]' and deadline BETWEEN $today and '$fivedays' and completed_on = ''  and type='3' ");
  $review_rem = mysql_num_rows($sql_pack);
foreach ($ar_fields as $ar) {

 $var ='';
$cell_val = $i+$offset;
$cell_val = getNameFromNumber($cell_val);

if($ar == 'sn') $var = $count_student;
elseif($ar == 'data') {
   $var =  $data_rem;
}
elseif($ar == 'analysis') {
   $var =  $analysis_rem;
}
elseif($ar == 'review') {
   $var =  $review_rem;
}
 else {
    $var =  $row_att[$ar];
}
 
 
$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cell_val.$seq, $var);

 
$objPHPExcel->getActiveSheet(0)->getStyle($cell_val.$seq)->applyFromArray($styleArrayborder);
$i++;
 
}
$val_chr = $cell_val;

if($count_student%2 == 0)
{
$objPHPExcel->getActiveSheet()->getStyle('A'.$seq.':'.$cell_val.$seq)->applyFromArray($styleArray3);
}
else
{
$objPHPExcel->getActiveSheet()->getStyle('A'.$seq.':'.$cell_val.$seq)->applyFromArray($styleArray2);
}
 
$seq++;
}

$objPHPExcel->getActiveSheet()->freezePane('B3');
$objPHPExcel->getActiveSheet()->mergeCells('A1:'.$val_chr.'1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '5 Day Pending Work List');
$objPHPExcel->getActiveSheet()->setTitle('Analysts pending work');

$name = 'PendingWorkList_'.date("d-M-y",strtotime("now"));
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$name.'.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>