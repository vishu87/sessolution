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
'outline' => array(
'style' => PHPExcel_Style_Border::BORDER_THIN,
'color' => array('argb' => 'FF000000'),
),
),

);
$styleArray2 = array(
 
'alignment' => array(
'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
'WrapText' => true,
),
 
'fill' => array(
'type' => PHPExcel_Style_Fill::FILL_SOLID,
 
'color' => array(
'argb' => 'FFEEEEEE',
),
 
),
'borders' => array(
'outline' => array(
'style' => PHPExcel_Style_Border::BORDER_THIN,
'color' => array('argb' => 'FF000000'),
),
),
);

$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(9); 


$styleArray3 = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'WrapText' => true,),);

$styleArray4 = array('font' => array('bold' => true,'color' => array('argb' => 'FFFFFFFF',),),'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('argb' => 'FF4E81BE',),), 'borders' => array(
'outline' => array(
'style' => PHPExcel_Style_Border::BORDER_THIN,
'color' => array('argb' => 'FF000000'),
),
),);

$styleArray5 = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,),);

$styleArraytop = array('font' => array('bold' => true,),);

$styleArrayborder = array('borders' => array(
'outline' => array(
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


$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB('FF777777');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14); 

?>