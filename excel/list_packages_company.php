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

$sql = "SELECT distinct package_company.com_id from package_company inner join companies on package_company.com_id = companies.com_id order by companies.com_name asc";
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
$objPHPExcel->setActiveSheetIndex(0);
$ar_fields = array("sn","com_name");

$ar_names = array("SN","Name");
$ar_width = array("6","20");
$package_types = array("","PA","CGS");
$sql_pack = mysql_query("SELECT * from package order by package_year desc");
while ($row  = mysql_fetch_array($sql_pack)) {
    array_push($ar_fields, $row["package_id"]);

    array_push($ar_names, $package_types[$row["package_type"]].':'.$row["package_name"].'('.$row["package_year"].')');
    
    array_push($ar_width, "15");
}

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(25);
$seq=2;
$offset = 0;
$count =0;
$i=0;
foreach ($ar_fields as $ar) {
 
$cell_val = $i+$offset;
$cell_val = getNameFromNumber($cell_val);

$objPHPExcel->getActiveSheet()->setCellValue($cell_val.$seq, $ar_names[$count]);
$objPHPExcel->getActiveSheet()->getColumnDimension($cell_val)->setWidth($ar_width[$count]);
$i++;
$objPHPExcel->getActiveSheet(0)->getStyle($cell_val.$seq)->applyFromArray($styleArrayborder);

$count++;
}

$objPHPExcel->getActiveSheet()->getStyle('A'.$seq.':'.$cell_val.$seq)->applyFromArray($styleArray4);

$seq++;


$count_student = 0;
 
while($row_att = mysql_fetch_array($result_att))
{
 
$count_student++;
$i=0;
 
foreach ($ar_fields as $ar) {
 
$cell_val = $i+$offset;
$cell_val = getNameFromNumber($cell_val);

if($ar == 'sn') $var = $count_student;
elseif($ar == 'com_name'){
    $sql_c = mysql_query("SELECT com_name from companies where com_id='$row_att[com_id]' ");
    $row_c = mysql_fetch_array($sql_c);
    $var =  $row_c["com_name"];
}
else {
    $sql_p = mysql_query("SELECT id from package_company where com_id='$row_att[com_id]' and package_id='$ar' ");
    if(mysql_num_rows($sql_p) >0 ) $var =  'Yes';
    else $var= '';
}
 
 
$objPHPExcel->getActiveSheet()->setCellValue($cell_val.$seq, $var);

 
$objPHPExcel->getActiveSheet()->getStyle($cell_val.$seq)->applyFromArray($styleArrayborder);
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

$objPHPExcel->getActiveSheet()->freezePane('C3');
$objPHPExcel->getActiveSheet()->mergeCells('A1:'.$val_chr.'1');
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'SES Package -  Companies');
$objPHPExcel->getActiveSheet()->setTitle('Companies');


$objPHPExcel->setActiveSheetIndex(1);

$sql = "SELECT distinct users_package.user_id from users_package inner join users on users_package.user_id = users.id order by users.name asc";
$result_att=mysql_query($sql);

$ar_fields = array("sn","user_name");

$ar_names = array("SN","User Name");
$ar_width = array("6","20");

$sql_pack = mysql_query("SELECT * from package order by package_year desc");
while ($row  = mysql_fetch_array($sql_pack)) {
    array_push($ar_fields, $row["package_id"]);
    array_push($ar_names, $row["package_name"].'('.$row["package_year"].')');
    array_push($ar_width, "15");
}

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(25);
$seq=2;
$offset = 0;
$count =0;
$i=0;
foreach ($ar_fields as $ar) {
 
$cell_val = $i+$offset;
$cell_val = getNameFromNumber($cell_val);

$objPHPExcel->getActiveSheet()->setCellValue($cell_val.$seq, $ar_names[$count]);
$objPHPExcel->getActiveSheet()->getColumnDimension($cell_val)->setWidth($ar_width[$count]);
$i++;
$objPHPExcel->getActiveSheet()->getStyle($cell_val.$seq)->applyFromArray($styleArrayborder);
$count++;
}

$objPHPExcel->getActiveSheet()->getStyle('A'.$seq.':'.$cell_val.$seq)->applyFromArray($styleArray4);

$seq++;


$count_student = 0;
 
while($row_att = mysql_fetch_array($result_att))
{
 
$count_student++;
$i=0;
 
foreach ($ar_fields as $ar) {
 
$cell_val = $i+$offset;
$cell_val = getNameFromNumber($cell_val);

if($ar == 'sn') $var = $count_student;
elseif($ar == 'user_name'){
    $sql_c = mysql_query("SELECT name from users where id='$row_att[user_id]' ");
    $row_c = mysql_fetch_array($sql_c);
    $var =  $row_c["name"];
}
else {
    $sql_p = mysql_query("SELECT id from users_package where user_id='$row_att[user_id]' and package_id='$ar' ");
    if(mysql_num_rows($sql_p) >0 ) $var =  'Yes';
    else $var= '';
}
 
 
$objPHPExcel->getActiveSheet()->setCellValue($cell_val.$seq, $var);

 
$objPHPExcel->getActiveSheet()->getStyle($cell_val.$seq)->applyFromArray($styleArrayborder);
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

$objPHPExcel->getActiveSheet()->freezePane('C3');
$objPHPExcel->getActiveSheet()->mergeCells('A1:'.$val_chr.'1');
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'SES Package - Users');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB('FF777777');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14); 


$objPHPExcel->getActiveSheet()->setTitle('Users');
$objPHPExcel->setActiveSheetIndex(0);

$name = 'Packages_'.date("d-M-y", strtotime("now"));
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$name.'.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>