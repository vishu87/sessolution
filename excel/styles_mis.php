<?php 
//Styles
$styleArray1 = array(
'font' => array(
'bold' => true,
'color' => array('argb' => 'FFFFFFFF',)
),
'alignment' => array(
'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
'WrapText' => true,
),
 
'fill' => array(
'type' => PHPExcel_Style_Fill::FILL_SOLID,
 
'color' => array(
'argb' => 'FF0344A6',
),
 
),
 
'borders' => array(
'allborders' => array(
'style' => PHPExcel_Style_Border::BORDER_THIN,
'color' => array('argb' => 'FF000000'),
),
),

);

$styleArray2 = array(
 
'alignment' => array(
'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
'WrapText' => true,
),
 
'fill' => array(
'type' => PHPExcel_Style_Fill::FILL_SOLID,
 
'color' => array(
'argb' => 'FFEEEEEE',
),
 
),
'borders' => array(
'allborders' => array(
'style' => PHPExcel_Style_Border::BORDER_THIN,
'color' => array('argb' => 'FF000000'),
),
),
);

$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(9); 


$styleArray3 = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'WrapText' => true,),'borders' => array(
'allborders' => array(
'style' => PHPExcel_Style_Border::BORDER_THIN,
'color' => array('argb' => 'FF000000'),
),
),);

$styleArray4 = array('font' => array('bold' => true,'color' => array('argb' => 'FFFFFFFF',),),'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('argb' => 'FF4E81BE',),), 'borders' => array(
'allborders' => array(
'style' => PHPExcel_Style_Border::BORDER_THIN,
'color' => array('argb' => 'FF000000'),
),
),);

$styleArray5 = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),);

$styleArraytop = array('font' => array('bold' => true,),);

$styleArrayborder = array('borders' => array(
'allborders' => array(
'style' => PHPExcel_Style_Border::BORDER_THIN,
'color' => array('argb' => 'FF000000'),
),
),);

$styleArraybb = array(
	'borders' => array(
		'bottom' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF888888'),
		),
	),
);
$styleArraylr = array(
          'borders' => array(
              'allborders' => array(
                  'style' => PHPExcel_Style_Border::BORDER_THIN
              )
          )
      );
?>