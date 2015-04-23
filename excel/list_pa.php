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
$date_high = $_POST["date_to_p"];
$date_low = $_POST["date_from_p"];
$date_sql = '';
$col_last = 'S';
                        
  if($date_high && $date_low){
   $date_sql = "and meeting_date <= '$date_high' and meeting_date >= '$date_low' ";
  } elseif ($date_high && !$date_low) {
    $date_sql = "and meeting_date <= '$date_high' ";
  } elseif(!$date_high && $date_low) {
    $date_sql = "and meeting_date >= '$date_low' ";
  }

  $year_sql = mysql_query("SELECT year_sh from years order by year_sh desc");
  while ($year_row = mysql_fetch_array($year_sql)) {
    $total_comp = array();
    $year = $year_row["year_sh"];

    $sql_report = mysql_query("SELECT distinct package_company.com_id from package_company inner join package on package_company.package_id = package.package_id where package.package_year='$year' ");
      while($row_cgs = mysql_fetch_array($sql_report)){
        array_push($total_comp, $row_cgs["com_id"]);
      }

    $sql_report = mysql_query("SELECT distinct com_id from users_companies where type='1' and year='$year' ");
    while($row_cgs = mysql_fetch_array($sql_report)){
      if(!in_array($row_cgs["com_id"], $total_comp))
        array_push($total_comp, $row_cgs["com_id"]);
    }

    if(sizeof($total_comp) > 0){

      $str_comp = implode(",", $total_comp);
      $sql = mysql_query("SELECT id from proxy_ad where com_id IN (".$str_comp.") and year ='$year' ".$date_sql." order by meeting_date desc");
      while ($row = mysql_fetch_array($sql)) {
        array_push($proxy_ids, $row["id"]);
      }
    }

  }


if(sizeof($proxy_ids) == 0) {exit();}

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

$ar_fields = array("sn","com_bse_code","com_name","meeting_date","meeting_type","sub","cus_sub","report","notice","teasor","annual_report","meeting_outcome","meeting_minutes","resolution","resolution_name","type","ses","details","reasons");

$ar_names = array("SN","BSE Code","Company Name","Meeting Date","Meeting Type","Subscribers","Customized Subscribers","Report","Notice","Teasor","Annual Report","Meeting Outcome","Meeting Minutes","Resolution Number","Resolution","Resolution Type","SES","Details","Reasons");

$ar_width = array("6","10","20","10","12","12","15","12","12","12","12","12","12","20","20","10","20","20");

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

foreach($proxy_ids as $proxy_id)
{
 	
 	$sql = mysql_query("SELECT proxy_ad.*,companies.com_bse_code, companies.com_name from proxy_ad inner join companies on proxy_ad.com_id = companies.com_id  where proxy_ad.id='$proxy_id' ");
   $row = mysql_fetch_array($sql);

 	$count_student++;
	$i=0;
	 
	foreach ($ar_fields as $ar) {
	 	$var = '';
		$cell = $i+$offset;
		$cell_val = getNameFromNumber($cell);
		
		if($ar == 'sn') $var = $count_student;
		elseif ( $ar == "type"|| $ar == "ses"|| $ar == "details"|| $ar == "reasons") {
			$var='';
		}
		elseif ( $ar == "report" || $ar == "teasor" || $ar == "annual_report" || $ar == "meeting_outcome" || $ar == "meeting_minutes") {
			$var = ($row[$ar] == '')?'N/A':'Available';
		}
		elseif ($ar == "notice") {
			$var = ($row[$ar] == '' && $row["notice_link"]== '' )?'N/A':'Available';
		}
		elseif ($ar == 'sub') {
			 $sql_pack_user = mysql_query("SELECT users_package.user_id from users_package inner join package on users_package.package_id = package.package_id inner join package_company on package_company.package_id = package.package_id where package_company.com_id = '$row[com_id]' and package.package_year='$row[year]' and package.package_type='1' ");
			 $users = array();
                 while ($row_pack = mysql_fetch_array($sql_pack_user)) {
                   array_push($users, $row_pack["user_id"]);
                 }


      $sql_addi_user = mysql_query("SELECT user_id from users_companies where com_id = '$row[com_id]' and year = '$row[year]' and type='1' ");
       while ($row_pack = mysql_fetch_array($sql_addi_user)) {
                                   array_push($users, $row_pack["user_id"]);
                                 }

      $var = mysql_num_rows($sql_pack_user) + mysql_num_rows($sql_addi_user) ;
		}
		elseif ($ar == 'cus_sub') {
		if(sizeof($users) > 0) {
			$users_string = implode(',', $users);
			 $sql_custom = mysql_query("SELECT id from users where id IN ($users_string) and customized = '1' ");
             $var = mysql_num_rows($sql_custom);
         } else $var =0;
		}
		elseif ($ar == 'resolution') {
			$seq_in = $seq;
			$recos = array();
			$sql_reco = mysql_query("SELECT * from ses_recos");
			while ($row_reco = mysql_fetch_array($sql_reco)) {
			  $recos[$row_reco["id"]] = $row_reco["reco"];
			}
			$sql_vote = mysql_query("SELECT * from voting where report_id='$proxy_id' order by id asc");
			$i_in = $i;
			if($count_student %2 == 0) $color = 'FFFFFFFF';
				else $color = 'FFE7E7E7';
			while($row_vote = mysql_fetch_array($sql_vote)) {
				
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
			        $var = implode(", ", $reas);
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
				for ($kk=0; $kk < 13 ; $kk++) { 
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
		elseif ($ar == 'meeting_type') {
			$var = $meeting_types[$row[$ar]];
		}
		elseif ($ar == 'meeting_date') {
			$var = date("d-M-y",$row[$ar]);
		}
		else {
		   $var= $row[$ar];
		}
		 
		if($ar == "resolution" || $ar == "resolution_name" || $ar == "type"|| $ar == "ses"|| $ar == "details"|| $ar == "reasons"){

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
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Proxy Advisory');
$objPHPExcel->getActiveSheet()->getStyle('A1:'.$val_chr.$seq.'')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:'.$val_chr.'1')->applyFromArray($styleArray1);
/*


$objPHPExcel->getActiveSheet()->getStyle('A1:'.$val_chr.$seq.'')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:'.$val_chr.$seq.'')->getAlignment()->setWrapText(true);
*/
$objPHPExcel->getActiveSheet()->setTitle('Proxy Advisory');

$name = 'PA_'.date("d-M-y",strtotime("now"));
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$name.'.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>