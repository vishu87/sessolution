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

$sql = "SELECT * from users order by name asc";

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

$ar_fields = array("sn","name","username","email","mobile","sub_users");

$ar_names = array("SN","Name","Username","Email","Mobile","Parent (Addon Users)");
$ar_width = array("6","20","20","20","20","20","20","20","20","20");

$year_sql= mysql_query("SELECT * from years order by year_sh desc");
while ($row_yr = mysql_fetch_array($year_sql)) {
    array_push($ar_fields, 'pack_'.$row_yr["year_sh"]);
    array_push($ar_fields, 'pa_'.$row_yr["year_sh"]);
    array_push($ar_fields, 'cgs_'.$row_yr["year_sh"]);
    array_push($ar_fields, 'gr_'.$row_yr["year_sh"]);

    array_push($ar_names, 'Base Package ('.$row_yr["period"].')');
    array_push($ar_names, 'Add. PA ('.$row_yr["period"].')');
    array_push($ar_names, 'Add. CGS ('.$row_yr["period"].')');
    array_push($ar_names, 'Add. Research ('.$row_yr["period"].')');

    array_push($ar_width, '20');
    array_push($ar_width, '20');
    array_push($ar_width, '20');
    array_push($ar_width, '20');

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

$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cell_val.$seq, $ar_names[$count]);
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
 $var ='';
 $ext = explode('_', $ar);
$cell_val = $i+$offset;
$cell_val = getNameFromNumber($cell_val);

if($ar == 'sn') $var = $count_student;
elseif ($ar == 'sub_users'){
    if($row_att["created_by_prim"] == 0){
        $var = 'Self ('.$row_att["sub_users"].')';
    } else {
        $sql_query = mysql_query("SELECT name from users where id='$row_att[created_by_prim]' limit 1");
        $fetch_row = mysql_fetch_array($sql_query);
        $var = $fetch_row["name"];
    }
}
elseif($ext[0] == 'pack') {
    $query = mysql_query("SELECT users_package.id, package.package_name from users_package inner join package on users_package.package_id = package.package_id where package.package_year ='$ext[1]' and users_package.user_id = '$row_att[id]' ");
    $packs = array();
    while($row_pack = mysql_fetch_array($query)){
        array_push($packs, $row_pack["package_name"]);
    }
    if(sizeof($packs) > 0) $var =  implode(', ', $packs);
   
}
elseif($ext[0] == 'pa') {
    $query = mysql_query("SELECT id from users_companies where year ='$ext[1]' and user_id = '$row_att[id]' and type='1' ");
    $var =  mysql_num_rows($query);
   
}
elseif($ext[0] == 'cgs') {
     $query = mysql_query("SELECT id from users_companies where year ='$ext[1]' and user_id = '$row_att[id]' and type='2' ");
    $var =  mysql_num_rows($query);
   
}
elseif($ext[0] == 'gr') {
     $query = mysql_query("SELECT id from users_companies where year ='$ext[1]' and user_id = '$row_att[id]' and type='3' ");
    $var =  mysql_num_rows($query);
   
} else {
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

$objPHPExcel->getActiveSheet()->freezePane('C3');
$objPHPExcel->getActiveSheet()->mergeCells('A1:'.$val_chr.'1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'SES Users List');

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
$objPHPExcel->getActiveSheet()->setTitle('Users');

$name = 'UserList_'.date("d-M-y",strtotime("now"));
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$name.'.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>