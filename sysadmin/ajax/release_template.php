<?php session_start();
//require_once('../../sysauth.php');
require_once('../../config.php');
require_once('../../classes/UserClass.php');

require_once '../../excel/Classes/PHPExcel.php';


$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if(!$link) {
	die('Failed to connect to server: ' . mysql_error());
}
//Select database
$db = mysql_select_db(DB_DATABASE);
if(!$db) {
	die("Unable to select database");
}

$report_id = mysql_real_escape_string($_POST["report_id"]);

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

$objPHPExcel = new PHPExcel();
include ('../../excel/styles.php');

// Set properties

$objPHPExcel->getProperties()->setCreator("SES")->setLastModifiedBy("SES");
$objPHPExcel->getDefaultStyle()->getFont()->setSize(9); 
$objPHPExcel->setActiveSheetIndex(0);

$query = mysql_query("SELECT companies.com_name, companies.com_id, companies.com_address, companies.com_isin, proxy_ad.meeting_type, proxy_ad.meeting_date, proxy_ad.year, proxy_ad.meeting_time from proxy_ad inner join companies on proxy_ad.com_id = companies.com_id where proxy_ad.id='$report_id' limit 1 ");
$row_comp = mysql_fetch_array($query);

$ar_fields = array("resolution_number","resolution_name","man_share_reco","type_business","sebi_clause");
$ar_names = array("Sr. No.","Proposal / Management recommendation","Management Proposal / Shareholders Proposal","Highlight","SEBI Flag");

$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Meeting Details');

$objPHPExcel->getActiveSheet()->setCellValue('A2', 'Company Name');
$objPHPExcel->getActiveSheet()->setCellValue('B2', $row_comp["com_name"]);

$objPHPExcel->getActiveSheet()->setCellValue('A3', 'Address');
$objPHPExcel->getActiveSheet()->setCellValue('B3', $row_comp["com_address"]);

$objPHPExcel->getActiveSheet()->setCellValue('A4', 'Type of Meeting');
$objPHPExcel->getActiveSheet()->setCellValue('B4', $meeting_types[$row_comp["meeting_type"]]);

$objPHPExcel->getActiveSheet()->setCellValue('A5', 'Date of Meeting');
$objPHPExcel->getActiveSheet()->setCellValueExplicit('B5', date("d-M-y",$row_comp["meeting_date"]), PHPExcel_Cell_DataType::TYPE_STRING);

$objPHPExcel->getActiveSheet()->setCellValue('A6', 'Meeting Time');
$objPHPExcel->getActiveSheet()->setCellValue('B6', $row_comp["meeting_time"]);

$objPHPExcel->getActiveSheet()->setCellValue('A7', 'ISIN Code');
$objPHPExcel->getActiveSheet()->setCellValue('B7',  $row_comp["com_isin"]);

$objPHPExcel->getActiveSheet()->setCellValue('B9',  'Items to be Considered');

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(18);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(39);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(23);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(9);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(9);

$objPHPExcel->getActiveSheet()->getStyle('A2:B7')->applyFromArray($styleArraylr);


//$objPHPExcel

$bold = array(
	'font' => array(
		'bold' => true,
	),
);

$objPHPExcel->getActiveSheet()->getStyle('A1:A7')->applyFromArray($bold);
$objPHPExcel->getActiveSheet()->getStyle('B9')->applyFromArray($bold);

$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB('FF000000');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(9); 


$seq=11;
$count = 0;
foreach ($ar_fields as $ar) {
	$cell_val = getNameFromNumber($count);
	$objPHPExcel->getActiveSheet()->setCellValue($cell_val.$seq, $ar_names[$count]);
	$objPHPExcel->getActiveSheet()->getStyle($cell_val.$seq)->applyFromArray($styleArrayborder);
	$count++;
}
$objPHPExcel->getActiveSheet()->getStyle('A'.$seq.':'.$cell_val.$seq)->applyFromArray($bold);

$seq++;


$query_votes = mysql_query("SELECT voting.resolution_number, voting.resolution_name, voting.man_share_reco, voting.type_business, resolutions.sebi_clause from voting left join resolutions on voting.resolution_type = resolutions.id  where voting.report_id ='$report_id' order by voting.resolution_number asc ");
 
while($row_att = mysql_fetch_array($query_votes)){
	$count = 0;

	foreach ($ar_fields as $ar) {
	 switch ($ar) {
	 	
	 	case 'man_share_reco':
	 		$val = $man_share_recos[$row_att["man_share_reco"]];
	 		break;

	 	case 'type_business':	
	 		//$val = $types_business[$row_att["type_business"]];
	 		if($row_att["sebi_clause"] == '' || $row_att["sebi_clause"] == '-'){
	 			$val = 'No';
	 		} else {
	 			$val = 'Yes';
	 		}
	 		break;

	 	default:
	 		$val = $row_att[$ar];
	 		break;
	 }
	 
	
	$val_chr = getNameFromNumber($count);

	$objPHPExcel->getActiveSheet()->setCellValue($val_chr.$seq,  $val);
	$objPHPExcel->getActiveSheet()->getStyle($val_chr.$seq)->applyFromArray($styleArrayborder);

	$count++;
}


	// if($seq%2 != 0){
	// 	$objPHPExcel->getActiveSheet()->getStyle('A'.$seq.':'.$val_chr.$seq)->applyFromArray($styleArray3);
	// }else{
	// 	$objPHPExcel->getActiveSheet()->getStyle('A'.$seq.':'.$val_chr.$seq)->applyFromArray($styleArray2);
	// }

	 
	$seq++;
}

$objPHPExcel->getActiveSheet()->getStyle('A1:'.$val_chr.$seq.'')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:'.$val_chr.$seq.'')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A11:A'.$seq.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


$objPHPExcel->getActiveSheet()->setTitle('Resolutions');

$name = substr(str_shuffle(strtotime("now")), 0, 10).$row_comp["com_name"].'_'.$meeting_types[$row_comp["meeting_type"]].'_'.date("d-M-y",$row_comp["meeting_date"]).'.xls';


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('../../voting_template/'.$name);

$subject = "Recommendation Excel for ".$row_comp["com_name"].' '.$meeting_types[$row_comp["meeting_type"]].' '.date("d-M-y",$row_comp["meeting_date"]);

$noreply = 'noreply@sesgovernance.com';

$body = '<p> Recommendation Excel has been created for <b>'.$row_comp["com_name"].'</b> / <b>'.$meeting_types[$row_comp["meeting_type"]].'</b> / <b>'.date("d-M-y",$row_comp["meeting_date"]).'</b>. Please check the attached file.</p><hr><i>This is an auto generated email. Please do not reply.</i>';
$body = mysql_real_escape_string($body);



$query = mysql_query("SELECT id, email, voting_template_type from users where voting_template = 1 and primary_user =1 ");

while ($row_user = mysql_fetch_array($query)) {
	// first check for which company the client want the report
	//0->all 1->subscribed 2->only limited 3->portfolio

	$user_id = $row_user["id"];
	$user = new User($user_id);

	$flag = false;
	$user->pa_subscribed_comapnies_year($user_id,$row_comp["year"]);

	if($row_user["voting_template_type"] == 0){
		$flag = true;
	} elseif($row_user["voting_template_type"] == 1){ // for all subscribed companies
		echo 'yes';
		print_r($user->companies_limited_year);
		echo $row_comp["com_id"];
		if(in_array($row_comp["com_id"], $user->companies_subscribed_year)) $flag = true;

	} elseif($row_user["voting_template_type"] == 2){
		
		if(in_array($row_comp["com_id"], $user->companies_limited_year)) $flag = true;

	} elseif($row_user["voting_template_type"] == 3){

		$user->voting_records_firm($user->parent);
		if(in_array($report_id, $user->voting_records_firm)) $flag = true;
	}

	if($flag){
		mysql_query("INSERT into mail_queue (mailto, mailcc, mailbcc, mailbccmore, subject, content, at_folder, at_file) values ('$row_user[email]','','','','$subject', '$body','voting_template','$name') ");
	}

}
mysql_query("UPDATE proxy_ad set template_release = '".strtotime("now")."' where id='$report_id' ");

mysql_query("UPDATE proxy_ad set previous_template_release = previous_template_release + ' ".strtotime("now")."' where id='$report_id' ");

echo 'success';

?>