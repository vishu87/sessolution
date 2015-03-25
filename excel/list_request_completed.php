<?php session_start();
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

$proxy_ids = array();

$col_last = 'T';

 $sql = mysql_query("SELECT proxies.* ,users.name, users.id as user_id from proxies inner join users on proxies.user_id = users.id where proxies.final_date !='' order by proxies.add_date asc");

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

$ar_fields = array("sn","com_bse_code","com_name","meeting_date","meeting_type","name","add_date","voter_id","appoint_date","form","slip","final_date","resolution","resolution_name","type","ses","client_vote","client_comment","details","reasons");

$ar_names = array("SN","BSE Code","Company Name","Meeting Date","Meeting Type","User Name","Added on","Voter Name","Appointed On","Request Form","Slip","Completed on","Resolution Number","Resolution Name","Resolution Type","SES","Client Vote","Client Comment","Details","Reasons");

$ar_width = array("6","10","20","10","10","12","15","12","12","12","12","22","10","10","20","10","20","20","20","20","20","20","20","20","20","20");

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
$meeting_types = array("","AGM", "EGM", "PB");
 $voters = array();
$sql_met = mysql_query("SELECT vid,name from proxy_voters ");
while ($row_met = mysql_fetch_array($sql_met)) {
   $voters[$row_met["vid"]] = $row_met["name"];
 } 

while($row = mysql_fetch_array($sql))
{
$sql_com = mysql_query("SELECT proxy_ad.meeting_date, proxy_ad.meeting_type,companies.com_name, companies.com_bse_code from proxy_ad inner join companies on proxy_ad.com_id = companies.com_id where proxy_ad.id='$row[proxy_id]' ");
$row_com = mysql_fetch_array($sql_com);

 	$count_student++;
	$i=0;


	foreach ($ar_fields as $ar) {
	 	$var = '';
		$cell = $i+$offset;
		$cell_val = getNameFromNumber($cell);
		
		if($ar == 'sn') $var = $count_student;
		elseif ($ar == 'com_bse_code' || $ar == 'com_name' ) {
			$var = $row_com[$ar];
		}
		elseif ($ar == 'meeting_type') {
			$var = $meeting_types[$row_com[$ar]];
		}
		elseif ($ar == 'meeting_date') {
			$var = ($row_com[$ar])?date("d-M-y",$row_com[$ar]):'';
		}
		elseif ($ar == 'add_date'|| $ar == 'appoint_date' || $ar == 'final_date') {
			$var = ($row[$ar])?date("d-M-y",$row[$ar]):'';
		}
		elseif ($ar == 'voter_id') {
			$var = $voters[$row[$ar]];
		}
		elseif ($ar == 'form' || $ar == 'slip') {
			$var = ($row[$ar] == '')?'N/A':'Available';
		}
		elseif ( $ar == "type"|| $ar == "ses" || $ar == "resolution_name" || $ar == "details"|| $ar == "reasons" || $ar == 'client_vote' || $ar == 'client_comment') {
			$var='';
		}
		elseif ($ar == 'resolution') {
			$seq_in = $seq;
			$recos = array();
			$sql_reco = mysql_query("SELECT * from ses_recos");
			while ($row_reco = mysql_fetch_array($sql_reco)) {
			  $recos[$row_reco["id"]] = $row_reco["reco"];
			}
			$sql_vote = mysql_query("SELECT * from voting where report_id='$row[proxy_id]' order by id asc");
			$i_in = $i;
			if($count_student %2 == 0) $color = 'FFFFFFFF';
				else $color = 'FFE7E7E7';
			while($row_vote = mysql_fetch_array($sql_vote)) {

				 $sql_vote_pre = mysql_query("SELECT vote, comment from user_voting where vote_id = '$row_vote[id]' and user_id='$row[user_id]' ");
         $count_n = mysql_num_rows($sql_vote_pre);
         if($count_n > 0) $pre = mysql_fetch_array($sql_vote_pre);

				
				$objPHPExcel->getActiveSheet()->getStyle('A'.$seq.':'.$col_last.$seq)->getFill()
				->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
				->getStartColor()->setARGB($color);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$seq.':'.$col_last.$seq)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$seq.':'.$col_last.$seq)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$seq.':'.$col_last.$seq)->applyFromArray($styleArraybb);

						$cell = $i_in;
						$cell_val = getNameFromNumber($cell);
						$var = stripcslashes($row_vote["resolution_number"]);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cell_val.$seq, $var);

						 $cell++;
			      $cell_val = getNameFromNumber($cell); 
			     $var = stripcslashes($row_vote["resolution_name"]);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cell_val.$seq, $var);
			      
			      $cell++;
			      $cell_val = getNameFromNumber($cell); 
			      $sql_reso = mysql_query("Select * from resolutions where id='$row_vote[resolution_type]' ");
			      $row_reso = mysql_fetch_array($sql_reso);
						$var = $row_reso["resolution"];
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cell_val.$seq, $var);

						$cell++;
			      $cell_val = getNameFromNumber($cell);
						$var = $recos[$row_vote["ses_reco"]];
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cell_val.$seq, $var);

							$cell++;
			      $cell_val = getNameFromNumber($cell);
						 $sql_reso_v = mysql_query("Select * from votes where id='$pre[vote]' ");
		         $row_reso_v = mysql_fetch_array($sql_reso_v);  
		          $var = $row_reso_v["vote"];
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cell_val.$seq, $var);

							$cell++;
			      $cell_val = getNameFromNumber($cell);
						$var = stripcslashes($pre["comment"]);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cell_val.$seq, $var);


						$cell++;
			      $cell_val = getNameFromNumber($cell);
						$var = stripcslashes($row_vote["detail"]);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cell_val.$seq, $var);

						$cell++;
			      $cell_val = getNameFromNumber($cell);
			      $reas = array();
						 if($row_vote["reasons"] != ''){
			        $sql_reso = mysql_query("Select * from reasons where id IN ($row_vote[reasons]) ");
			        while ($row_reso = mysql_fetch_array($sql_reso)) {
			          array_push($reas, $row_reso["reason"]);
			        }
			        $var = implode(",\n", $reas);
			   		 }
			   		 else {
			   		 	$var = '';
			   		 } 
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cell_val.$seq, $var);

						

						$seq++;
					
			       
			}
			$num_v = mysql_num_rows($sql_vote);
			if(mysql_num_rows($sql_vote) > 0) {
				$seq--;
				for ($kk=0; $kk < 12 ; $kk++) { 
					$cell_n = getNameFromNumber($kk);
					$objPHPExcel->getActiveSheet()->mergeCells($cell_n.$seq_in.':'.$cell_n.$seq);
				}
				
				
			} else {
				$objPHPExcel->getActiveSheet()->getStyle('A'.$seq.':'.$col_last.$seq)->getFill()
				->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
				->getStartColor()->setARGB($color);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$seq.':'.$col_last.$seq)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$seq.':'.$col_last.$seq)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$seq.':'.$col_last.$seq)->applyFromArray($styleArraybb);
			}
		}
		
		else {
		   $var= $row[$ar];
		}
		 

		if($ar == "type"|| $ar == "ses" || $ar == "resolution_name" || $ar == "details"|| $ar == "reasons" || $ar == 'client_vote' || $ar == 'client_comment'){

		} else {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cell_val.$seq, $var);
		}
		

		$i++;
	 
	}

	$val_chr = $cell_val;


	$objPHPExcel->getActiveSheet()->getStyle('A'.$seq.':J'.$seq)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		
	$seq++;

}
$objPHPExcel->getActiveSheet()->freezePane('D3');
$objPHPExcel->getActiveSheet()->mergeCells('A1:'.$val_chr.'1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Proxy Voting');
$objPHPExcel->getActiveSheet()->getStyle('A1:'.$val_chr.$seq.'')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:'.$val_chr.($seq-1).'')->applyFromArray($styleArraylr);
/*


$objPHPExcel->getActiveSheet()->getStyle('A1:'.$val_chr.$seq.'')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:'.$val_chr.$seq.'')->getAlignment()->setWrapText(true);
*/
$objPHPExcel->getActiveSheet()->setTitle('Completed Proxy Voting');

$name = 'Proxy_Request_'.date("d-M-y",strtotime("now"));
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$name.'.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>