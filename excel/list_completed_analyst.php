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


$str_time_1='';

$date_high = $_POST["date_to_p"];
$date_low = $_POST["date_from_p"];
$type= $_POST["type"];
if($date_high && $date_low){

$str_time_1 = " and meeting_date between $date_low and $date_high ";
$str_time_2 = " and cgs.publishing_date between $date_low and $date_high ";
$str_time_3 = " and research.publishing_date between $date_low and $date_high ";


} elseif ($date_high && !$date_low) {

$str_time_1 = " and meeting_date <= $date_high ";
$str_time_2 = " and cgs.publishing_date <= $date_high ";
$str_time_3 = " and research.publishing_date <= $date_high ";

} elseif(!$date_high && $date_low) {

 $str_time_1 = " and meeting_date >= $date_low ";
$str_time_2 = " and cgs.publishing_date >= $date_low ";
$str_time_3 = " and research.publishing_date >= $date_low ";

} else {
$str_time_1 = '';
  $str_time_2 = '';
  $str_time_3 = '';
}


switch ($type) {

case '1':
  $str_time_1 = " and meeting_date between $today and $critical ";
  $str_time_2 = " and cgs.publishing_date between $today and $critical ";
  $str_time_3 = " and research.publishing_date between $today and $critical ";
  break;

case '2':
  $str_time_1 = " and meeting_date between $today and $upcoming ";
  $str_time_2 = " and cgs.publishing_date between $today and $upcoming ";
  $str_time_3 = " and research.publishing_date between $today and $upcoming ";
  break;
}

function check_status($deadline, $completed){
     $burn_purple = "FF7DCFE7";
 $burn_red = "FFE77575";
 $burn_yellow = "FFF6F5AD";
 $burn_green = "FF74BD6E";
    $timenow = strtotime("now");
    if($deadline == ''){
      return '';
    }
    elseif($completed == ''){
      if(($timenow - $deadline) < 86400) return $burn_yellow;
      else return $burn_red;
    }
    else {
      if(($completed - $deadline) < 86400) return $burn_green;
      else return $burn_red;
    }

  }

$analysts = array();
 $sql_an = mysql_query("SELECT an_id, name from analysts ");
 while ($row_an = mysql_fetch_array($sql_an)) {
   $analysts[$row_an["an_id"]] = $row_an["name"];
 }
 $count =1;

 

$count =1;
$meeting_types = array("","AGM", "EGM", "PB");
$report_types  = array("","Proxy Advisory","CGS","Research");   

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
 $ar_fields = array("sn","com_bse_code","com_name","meeting_date","meeting_type","deadline","subscribed","report","notice","data","d_deadline","d_completed","analysis","a_deadline","a_completed","review","r_deadline","r_completed");

$ar_names = array("SN","BSE Code","Company Name","Meeting Date","Meeting Type","Deadline","Subscribed","Report","Notice","Data","Deadline","Completed On","Analysis","Deadline","Completed On","Review","Deadline","Completed On");
$ar_width = array("6","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20");



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



 $sql = mysql_query("SELECT proxy_ad.*,companies.com_name, companies.com_bse_code from report_analyst inner join proxy_ad on report_analyst.report_id = proxy_ad.id inner join companies on proxy_ad.com_id = companies.com_id where report_analyst.completed_on != '' and report_analyst.rep_type='1' and report_analyst.type='3' ");

$count_student = 0;

while($row = mysql_fetch_assoc($sql))
{
        

$sql_data = mysql_query("SELECT an_id, deadline, completed_on from report_analyst where report_id= '$row[id]' and rep_type='1' and type= '1' ");
$data = mysql_fetch_array($sql_data);
$sql_analysis = mysql_query("SELECT an_id, deadline, completed_on from report_analyst where report_id= '$row[id]' and rep_type='1' and type= '2' ");
$analysis = mysql_fetch_array($sql_analysis);
$sql_review = mysql_query("SELECT an_id, deadline, completed_on from report_analyst where report_id= '$row[id]' and rep_type='1' and type= '3' ");
$review = mysql_fetch_array($sql_review);

             
             

$count_student++;
$i=0;

foreach ($ar_fields as $ar) {
 
$cell_val = $i+$offset;
$cell_val = getNameFromNumber($cell_val);

if($ar == 'sn') 
    $var = $count_student;
elseif($ar == 'meeting_date'){
    $var =  date("d-M-y",$row[$ar]);
}
elseif($ar == 'meeting_type'){
    $var =  $meeting_types[$row[$ar]];
}
elseif($ar == 'deadline'){
    $x = $row["meeting_date"] - 10*86400;
    $var =  date("d-M-y",$x);
}
elseif($ar == 'critical'){
     if($row["meeting_date"] <= $critical && $row["meeting_date"] >= $today) $var =  "Critical";
     elseif($row["meeting_date"] <= $upcoming && $row["meeting_date"] >= $today) $var =  "Upcoming";
     else $var='';
}
elseif ($ar == 'data') {
    $var = $analysts[$data["an_id"]];
}
elseif ($ar == 'analysis') {
    $var = $analysts[$analysis["an_id"]];
}
elseif ($ar == 'review') {
    $var = $analysts[$review["an_id"]];
}
elseif ($ar == 'd_deadline') {
    $var = ($data["deadline"] != '')?date("d-M-y",$data["deadline"]):'';
}
elseif ($ar == 'a_deadline') {
    $var = ($analysis["deadline"] != '')?date("d-M-y",$analysis["deadline"]):'';
}
elseif ($ar == 'r_deadline') {
    $var = ($review["deadline"] != '')?date("d-M-y",$review["deadline"]):'';
}
elseif ($ar == 'd_completed') {
    $var = ($data["completed_on"] != '')?date("d-M-y",$data["completed_on"]):'';
}
elseif ($ar == 'a_completed') {
    $var = ($analysis["completed_on"] != '')?date("d-M-y",$analysis["completed_on"]):'';
}
elseif ($ar == 'r_completed') {
    $var = ($review["completed_on"] != '')?date("d-M-y",$review["completed_on"]):'';
}
elseif($ar == 'report'){
      $var= ($row[$ar])?'Available':'N/A';
}
elseif($ar == 'notice'){
      $var= ($row[$ar] == '' && $row["notice_link"] =='')?'N/A':'Available';
}
elseif($ar == 'subscribed'){
     $sql_pack_user = mysql_query("SELECT users_package.user_id from users_package inner join package on users_package.package_id = package.package_id inner join package_company on package_company.package_id = package.package_id where package_company.com_id = '$row[com_id]' and package.package_year='$row[year]' ");


      $sql_addi_user = mysql_query("SELECT id from users_companies where com_id = '$row[com_id]' and year = '$row[year]' and type='1' ");

      $sql_manual_added = mysql_query("SELECT man_id from manual_subscription where report_id='$row[id]' and report_type='1' ");

      $var =  mysql_num_rows($sql_pack_user) + mysql_num_rows($sql_addi_user) + mysql_num_rows($sql_manual_added);
}
else {
    $var= $row[$ar];
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
$color = check_status($data["deadline"],$data["completed_on"]);
if($color != '')
$objPHPExcel->getActiveSheet()->getStyle('J'.$seq.':L'.$seq)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
$color = check_status($analysis["deadline"],$analysis["completed_on"]);
if($color != '')
$objPHPExcel->getActiveSheet()->getStyle('M'.$seq.':O'.$seq)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
$color = check_status($review["deadline"],$review["completed_on"]);
if($color != '')
$objPHPExcel->getActiveSheet()->getStyle('P'.$seq.':R'.$seq)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
$seq++;
}

$objPHPExcel->getActiveSheet()->freezePane('D3');
$objPHPExcel->getActiveSheet()->mergeCells('A1:'.$val_chr.'1');
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Completed Proxy Advisory Reports');
$objPHPExcel->getActiveSheet()->setTitle('PA');






//CGS????????????????????????????????///////////////////////////////////

$objPHPExcel->setActiveSheetIndex(1);

  $sql_cgs = "SELECT cgs.*,companies.com_name, companies.com_bse_code from report_analyst inner join cgs on report_analyst.report_id = cgs.cgs_id inner join companies on cgs.com_id = companies.com_id where report_analyst.completed_on != '' and report_analyst.rep_type='2' and report_analyst.type='3' ";

$result_att=mysql_query($sql_cgs);

$ar_fields = array("sn","com_bse_code","com_name","publishing_date","deadline","subscribed","report_upload","data","d_deadline","d_completed","analysis","a_deadline","a_completed","review","r_deadline","r_completed");

$ar_names = array("SN","BSE Code","Company Name","Meeting Date","Deadline","Subscribed","Report","Data","Deadline","Completed On","Analysis","Deadline","Completed On","Review","Deadline","Completed On");
$ar_width = array("6","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20");


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
 if(mysql_num_rows($result_att) > 0 ){
while($row = mysql_fetch_array($result_att))
{
 
 $sql_data = mysql_query("SELECT an_id, deadline, completed_on from report_analyst where report_id= '$row[cgs_id]' and rep_type='2' and type= '1' ");
$data = mysql_fetch_array($sql_data);
$sql_analysis = mysql_query("SELECT an_id, deadline, completed_on from report_analyst where report_id= '$row[cgs_id]' and rep_type='2' and type= '2' ");
$analysis = mysql_fetch_array($sql_analysis);
$sql_review = mysql_query("SELECT an_id, deadline, completed_on from report_analyst where report_id= '$row[cgs_id]' and rep_type='2' and type= '3' ");
$review = mysql_fetch_array($sql_review);

$count_student++;
$i=0;
 
foreach ($ar_fields as $ar) {
  
$cell_val = $i+$offset;
$cell_val = getNameFromNumber($cell_val);
if($ar == 'sn') 
    $var = $count_student;
elseif($ar == 'publishing_date'){
    $var =  date("d-M-y",$row[$ar]);
}
elseif($ar == 'deadline'){
    $x = $row["publishing_date"] - 10*86400;
    $var =  date("d-M-y",$x);
}
elseif($ar == 'critical'){
     if($row["publishing_date"] <= $critical && $row["publishing_date"] >= $today) $var =  "Critical";
     elseif($row["publishing_date"] <= $upcoming && $row["publishing_date"] >= $today) $var =  "Upcoming";
     else $var='';
}
elseif ($ar == 'data') {
    $var = $analysts[$data["an_id"]];
}
elseif ($ar == 'analysis') {
    $var = $analysts[$analysis["an_id"]];
}
elseif ($ar == 'review') {
    $var = $analysts[$review["an_id"]];
}
elseif ($ar == 'd_deadline') {
    $var = ($data["deadline"] != '')?date("d-M-y",$data["deadline"]):'';
}
elseif ($ar == 'a_deadline') {
    $var = ($analysis["deadline"] != '')?date("d-M-y",$analysis["deadline"]):'';
}
elseif ($ar == 'r_deadline') {
    $var = ($review["deadline"] != '')?date("d-M-y",$review["deadline"]):'';
}
elseif ($ar == 'd_completed') {
    $var = ($data["completed_on"] != '')?date("d-M-y",$data["completed_on"]):'';
}
elseif ($ar == 'a_completed') {
    $var = ($analysis["completed_on"] != '')?date("d-M-y",$analysis["completed_on"]):'';
}
elseif ($ar == 'r_completed') {
    $var = ($review["completed_on"] != '')?date("d-M-y",$review["completed_on"]):'';
}
elseif($ar == 'report_upload'){
      $var= ($row[$ar])?'Available':'N/A';
}

elseif($ar == 'subscribed'){
     
      $sql_addi_user = mysql_query("SELECT id from users_companies where com_id = '$row[com_id]' and year = '$row[year]' and type='2' ");

      $sql_manual_added = mysql_query("SELECT man_id from manual_subscription where report_id='$row[cgs_id]' and report_type='2' ");

      $var =  mysql_num_rows($sql_addi_user) + mysql_num_rows($sql_manual_added);
}
else {
    $var= $row[$ar];
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


$color = check_status($data["deadline"],$data["completed_on"]);
if($color != '')
$objPHPExcel->getActiveSheet()->getStyle('H'.$seq.':J'.$seq)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
$color = check_status($analysis["deadline"],$analysis["completed_on"]);
if($color != '')
$objPHPExcel->getActiveSheet()->getStyle('K'.$seq.':M'.$seq)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
$color = check_status($review["deadline"],$review["completed_on"]);
if($color != '')
$objPHPExcel->getActiveSheet()->getStyle('N'.$seq.':P'.$seq)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);


 
$seq++;
}
}

$objPHPExcel->getActiveSheet()->freezePane('D3');
$objPHPExcel->getActiveSheet()->mergeCells('A1:'.$val_chr.'1');
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Completed Governance Scores');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB('FF777777');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14); 


$objPHPExcel->getActiveSheet()->setTitle('CGS');


///Reasearch????????????????????????????????///////////////////////////////////
$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(2);

  $sql_cgs = "SELECT research.*,companies.com_name, companies.com_bse_code from report_analyst inner join research on report_analyst.report_id = research.res_id inner join companies on research.com_id = companies.com_id where report_analyst.completed_on != '' and report_analyst.rep_type='3' and report_analyst.type='3' ";

$result_att=mysql_query($sql_cgs);

$ar_fields = array("sn","com_bse_code","com_name","publishing_date","deadline","subscribed","report_upload","data","d_deadline","d_completed","analysis","a_deadline","a_completed","review","r_deadline","r_completed");

$ar_names = array("SN","BSE Code","Company Name","Meeting Date","Deadline","Subscribed","Report","Data","Deadline","Completed On","Analysis","Deadline","Completed On","Review","Deadline","Completed On");
$ar_width = array("6","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20");


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
 if(mysql_num_rows($result_att) > 0 ){
while($row = mysql_fetch_array($result_att))
{
 
 $sql_data = mysql_query("SELECT an_id, deadline, completed_on from report_analyst where report_id= '$row[res_id]' and rep_type='3' and type= '1' ");
$data = mysql_fetch_array($sql_data);
$sql_analysis = mysql_query("SELECT an_id, deadline, completed_on from report_analyst where report_id= '$row[res_id]' and rep_type='3' and type= '2' ");
$analysis = mysql_fetch_array($sql_analysis);
$sql_review = mysql_query("SELECT an_id, deadline, completed_on from report_analyst where report_id= '$row[res_id]' and rep_type='3' and type= '3' ");
$review = mysql_fetch_array($sql_review);

$count_student++;
$i=0;
 
foreach ($ar_fields as $ar) {
  
$cell_val = $i+$offset;
$cell_val = getNameFromNumber($cell_val);
if($ar == 'sn') 
    $var = $count_student;
elseif($ar == 'publishing_date'){
    $var =  date("d-M-y",$row[$ar]);
}
elseif($ar == 'deadline'){
    $x = $row["publishing_date"] - 10*86400;
    $var =  date("d-M-y",$x);
}
elseif($ar == 'critical'){
     if($row["publishing_date"] <= $critical && $row["publishing_date"] >= $today) $var =  "Critical";
     elseif($row["publishing_date"] <= $upcoming && $row["publishing_date"] >= $today) $var =  "Upcoming";
     else $var='';
}
elseif ($ar == 'data') {
    $var = $analysts[$data["an_id"]];
}
elseif ($ar == 'analysis') {
    $var = $analysts[$analysis["an_id"]];
}
elseif ($ar == 'review') {
    $var = $analysts[$review["an_id"]];
}
elseif ($ar == 'd_deadline') {
    $var = ($data["deadline"] != '')?date("d-M-y",$data["deadline"]):'';
}
elseif ($ar == 'a_deadline') {
    $var = ($analysis["deadline"] != '')?date("d-M-y",$analysis["deadline"]):'';
}
elseif ($ar == 'r_deadline') {
    $var = ($review["deadline"] != '')?date("d-M-y",$review["deadline"]):'';
}
elseif ($ar == 'd_completed') {
    $var = ($data["completed_on"] != '')?date("d-M-y",$data["completed_on"]):'';
}
elseif ($ar == 'a_completed') {
    $var = ($analysis["completed_on"] != '')?date("d-M-y",$analysis["completed_on"]):'';
}
elseif ($ar == 'r_completed') {
    $var = ($review["completed_on"] != '')?date("d-M-y",$review["completed_on"]):'';
}
elseif($ar == 'report_upload'){
      $var= ($row[$ar])?'Available':'N/A';
}

elseif($ar == 'subscribed'){
     
      $sql_addi_user = mysql_query("SELECT id from users_companies where com_id = '$row[com_id]' and year = '$row[year]' and type='3' ");

      
      $var =  mysql_num_rows($sql_addi_user) ;
}
else {
    $var= $row[$ar];
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


$color = check_status($data["deadline"],$data["completed_on"]);
if($color != '')
$objPHPExcel->getActiveSheet()->getStyle('H'.$seq.':J'.$seq)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
$color = check_status($analysis["deadline"],$analysis["completed_on"]);
if($color != '')
$objPHPExcel->getActiveSheet()->getStyle('K'.$seq.':M'.$seq)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
$color = check_status($review["deadline"],$review["completed_on"]);
if($color != '')
$objPHPExcel->getActiveSheet()->getStyle('N'.$seq.':P'.$seq)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);


 
$seq++;
}
}

$objPHPExcel->getActiveSheet()->freezePane('D3');
$objPHPExcel->getActiveSheet()->mergeCells('A1:'.$val_chr.'1');
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Completed Governance Research');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB('FF777777');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14); 


$objPHPExcel->getActiveSheet()->setTitle('Research');


//LAST
$objPHPExcel->setActiveSheetIndex(0);

$name = 'Analyst_Completed_'.date("d-M-y", strtotime("now"));
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$name.'.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>