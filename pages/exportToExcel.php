<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');
ini_set('max_input_vars', 9000);

require '..\vendor\autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


$shopsArray = json_decode($_POST['shops']);
$productsArray = json_decode($_POST['products']);
$expanses_Array = json_decode($_POST['expanses']);
$primeline_Array = json_decode($_POST['primeline']);

$shop = $_POST['shop'];


//require_once dirname(__FILE__) . '/../classes/Excel/PHPExcel.php';

$objPHPExcel = new Spreadsheet();

//$cellArray = array("A","B","C","D","E");

$objPHPExcel->getProperties()->setCreator("Robert Kocjan")
							 ->setLastModifiedBy("Robert Kocjan")
							 ->setTitle($shop. "transfers");
//
$objPHPExcel->createSheet(0);

$objPHPExcel->setActiveSheetIndex(0)->setTitle("Summary");
//

if($shopsArray) {
    foreach ($shopsArray as $index => $invRef) {
        $index += 1;
        $cellNo = 1;
        $objPHPExcel->createSheet($index);
        $objPHPExcel->setActiveSheetIndex($index)
            ->setCellValue('A1', 'Product Name')
            ->setCellValue('B1', "Transfered")
            ->setCellValue('C1', "Destination")
            ->setCellValue('D1', "Cost")
            ->setCellValue('E1', "Value");

        $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);

        $columnWidth = 12;

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(80);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth($columnWidth + 5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth($columnWidth + 15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth($columnWidth);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth($columnWidth);


        $objPHPExcel->getActiveSheet()->setTitle($invRef)->getStyle('A1:E1')->getAlignment()->setWrapText(TRUE);
        $objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension());
        $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        foreach ($productsArray as $productIndex => $productValue) {
            //print_r($productValue);
            //echo $productValue->productName.'<br/>';
            if ($invRef == $productValue->invRef) {
                $cellNo++;
                $objPHPExcel->setActiveSheetIndex($index)->setCellValue('A' . $cellNo, $productValue->productName);
                $objPHPExcel->setActiveSheetIndex($index)->setCellValue('B' . $cellNo, $productValue->transfered);
                $objPHPExcel->setActiveSheetIndex($index)->setCellValue('C' . $cellNo, $productValue->invRef);
                $objPHPExcel->setActiveSheetIndex($index)->setCellValue('D' . $cellNo, $productValue->cost);
                $objPHPExcel->setActiveSheetIndex($index)->setCellValue('E' . $cellNo, $productValue->value);

            }


        }

        $objPHPExcel->getActiveSheet()->getStyle('F1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->setActiveSheetIndex($index)->setCellValue('F1', 'Total:');
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth($columnWidth);
        $objPHPExcel->getActiveSheet()->getStyle('G1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $objPHPExcel->setActiveSheetIndex($index)->setCellValue('G1', '=sum(E2:E' . $cellNo . ')');
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth($columnWidth);
        $objPHPExcel->getActiveSheet()->getStyle('F1:G1')->getFont()->setBold(true);
    }
}


/*
 * Transfers module
 * */

$ind = 3;

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Transfers');
$objPHPExcel->getActiveSheet()->mergeCells('A1:B1');
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
// summary transfers
foreach($shopsArray as $index => $invRef) {

    $cellNo = 1;

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $ind, $invRef)
        ->setCellValue('B' . $ind++, "='" . $invRef . "'!G1");
}



$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(30);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2' , 'Total transfers:')->setCellValue('B2', '=SUM(B3:B' . $ind . ')');
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A2:B2')->getFont()->setBold(true);

/*
 * Invoices and expenses module
 * */
$ind = 4;

$objPHPExcel->getActiveSheet()->mergeCells('F1:H1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Booked in orders');
$objPHPExcel->setActiveSheetIndex(0)->getStyle('F1')->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('F1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
foreach ($expanses_Array as $index => $val){

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('F' . $ind, $val[0])
        ->setCellValue('G' . $ind, $val[1])
        ->setCellValue('H' . $ind++, $val[2]);
}

$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('F')->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F3' , 'Total inv checked:')->setCellValue('H3', '=SUM(H4:H' . $ind . ')');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F2' , 'Total expenses:')->setCellValue('G2', '=SUM(G4:G' . $ind . ')');
$objPHPExcel->setActiveSheetIndex(0)->getStyle('F2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('F3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('F2:H3')->getFont()->setBold(true);

/*
 * Primeline module
 *
 * */


$row = 4;

if(count($primeline_Array) > 0) {
    $objPHPExcel->getActiveSheet()->mergeCells('J1:L1');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'Primeline orders');
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('J1')->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('J1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    foreach ($primeline_Array as $index => $val) {
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('J' . $row, $val[0])
            ->setCellValue('K' . $row, $val[1])
            ->setCellValue('L' . $row++, $val[2]);
    }

    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('J')->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J3', 'Total inv checked:')->setCellValue('L3', '=SUM(L4:L' . $ind . ')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J2', 'Total expenses:')->setCellValue('K2', '=SUM(K4:K' . $ind . ')');
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('J2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('J3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('J2:L3')->getFont()->setBold(true);

}
$objPHPExcel->setActiveSheetIndex(0);

// if(count($productsArray) > 0){
	$objWriter = new Xlsx($objPHPExcel, 'Excel2007');
//	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

	$fileName = str_replace(" ","_",date("Y-m-d").'_'.$shop).'.xlsx';
	
	$fileName_to_save = $fileName;
	
	//$objWriter->save('../files/'.$fileName_to_save);
	
	$pathToFile = dirname(pathinfo(__FILE__)['dirname']).'\\files\\'.$fileName;
	$linkToFile = str_replace("\\","\\\\",$pathToFile);
	
		
	$objWriter->save('../files/'.$fileName_to_save);
	
	
	$directory = explode("\\",dirname(dirname(__FILE__)));
	
	$pathToFile = dirname(pathinfo(__FILE__)['dirname']).'\\files\\'.$fileName_to_save;
	
		if (file_exists($pathToFile)){
			$show = "Click to download <a href = '/".$directory[count($directory)-1]."/files/".$fileName_to_save."'>".$fileName_to_save."</a>";    
		}else{
			$show = "Ups.. something went wrong and file wasn't created. Contact Robert.";    
		}
	
// }else{
//		$show = "<br/><div class='row'>";
//			$show .= "<div class='col-xs-12 col-12'>";
//				$show .= "No results found";
//			$show .= "</div>";
//		$show .= "</div><br/>";
//}
	echo $show;

