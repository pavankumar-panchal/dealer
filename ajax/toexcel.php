<?php
ob_start("ob_gzhandler");
ini_set('memory_limit', '2048M');
include('../functions/phpfunctions.php');
require_once '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

//PHPExcel
////require_once '../phpgeneration/PHPExcel.php';

//PHPExcel_IOFactory
////require_once '../phpgeneration/PHPExcel/IOFactory.php';

$switchtype = $_POST['transactiontype'];
$fromdate = $_POST['fromdate'];
$fromdateconverted = changedateformat($fromdate);
$todate = $_POST['todate'];
$todateconverted = changedateformat($todate);
$dealerid = imaxgetcookie('dealeruserid');

switch($switchtype)
{
	case 'purchase':
				$query = "SELECT slno AS slno,billdate as `date`, netamount AS amount, 'PURCHASE' as transactiontype FROM inv_bill WHERE LEFT(billdate,10) BETWEEN '".$fromdateconverted."' AND '".$todateconverted ."' AND dealerid = '".$dealerid."' AND billstatus = 'successful'";
		
			break;
			case 'credit':
				$query ="SELECT slno AS slno, createddate AS `date`,creditamount AS amount, 'CREDIT' as transactiontype FROM inv_credits WHERE LEFT(createddate,10) BETWEEN '".$fromdateconverted."'  AND '".$todateconverted ."' AND dealerid = '".$dealerid."' ";
			break;
			case 'both':
				$query = "(SELECT slno as slno,billdate as `date`,netamount as amount, 'PURCHASE' as transactiontype from inv_bill where LEFT(billdate,10) between '".$fromdateconverted."' AND '".$todateconverted ."' AND dealerid = '".$dealerid."' AND billstatus = 'successful') 
union all 
(SELECT slno as slno,createddate  as `date`,creditamount as amount, 'CREDIT' as transactiontype from inv_credits where LEFT(createddate,10) between  '".$fromdateconverted ."'  AND '".$todateconverted ."' AND dealerid = '".$dealerid."' ) order by `date`";
			break;
}
	$result = runmysqlquery($query);
	
	// Create new Spreadsheet object
	$objPHPExcel = new Spreadsheet();
	
	//Set Active Sheet	
	$mySheet = $objPHPExcel->getActiveSheet();
		
	//Define Style for header row
	$styleArray = array(
						'font' => array('bold' => true,),
						'fill'=> array('fillType'=>\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,'startColor'=> array('argb' => 'FFCCFFCC')),
						'borders' => array('allBorders'=> array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM))
					);
	
	//Merge the cell
	$mySheet->mergeCells('A1:D1');
	$mySheet->mergeCells('A2:D2');
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
				->setCellValue('A2', 'Transaction Summary Details');
	$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
	$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
	$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);

	//Apply style for header Row
	$mySheet->getStyle('A3:D3')->applyFromArray($styleArray);

	//Fille contents for Header Row
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A3', 'Sl No')
				->setCellValue('B3', 'Date')
				->setCellValue('C3', 'Amount')
				->setCellValue('D3', 'Transaction Type');
	$j =4;
	$slno = 0;
	while($fetch = mysqli_fetch_array($result))
	{
		$slno++;
		$mySheet->setCellValue('A' . $j,$slno)
				->setCellValue('B' . $j,changedateformatwithtime($fetch['date']))
				->setCellValue('C' . $j,$fetch['amount'])
				->setCellValue('D' . $j,$fetch['transactiontype']);
		$j++;
	}
	//Define Style for content area
	$styleArrayContent = array(
						'borders' => array('allBorders'=> array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN))
					);
	//Get the last cell reference
	$highestRow = $mySheet->getHighestRow(); 
	$highestColumn = $mySheet->getHighestColumn(); 
	$myLastCell = $highestColumn.$highestRow;
	
	//Deine the content range
	$myDataRange = 'A4:'.$myLastCell;
	if(mysqli_num_rows($result) <> 0)
	{
	//Apply style to content area range
		$mySheet->getStyle($myDataRange)->applyFromArray($styleArrayContent);
	}
	
	//set the default width for column
	$mySheet->getColumnDimension('A')->setWidth(6);
	$mySheet->getColumnDimension('B')->setWidth(13);
	$mySheet->getColumnDimension('C')->setWidth(17);
	$mySheet->getColumnDimension('D')->setWidth(17);

	$localdate = datetimelocal('Ymd');
	$localtime = datetimelocal('His');
	$filebasename = "Transactionsummary".$localdate."-".$localtime.".xls";
	$addstring = "/imax/dealer";
	if($_SERVER['HTTP_HOST'] == "meghanab" || $_SERVER['HTTP_HOST'] == "archanaab")
		$addstring = "/saralimax-dealer";
	
	$query = 'select slno,dealerusername from inv_mas_dealer where slno = '.$dealerid.'';
	$fetchres = runmysqlqueryfetch($query);
	$dealername = $fetchres['dealerusername'];				
	$localdate = datetimelocal('Ymd');
	$localtime = datetimelocal('His');
	$filebasename = "Transactionsummary".$localdate."-".$localtime."-".strtolower($dealername).".xls";
	$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','excel_transactionsummary_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
	$result = runmysqlquery($query1);	
	$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','137','".date('Y-m-d').' '.date('H:i:s')."','excel_transactionsummary_report".'-'.strtolower($dealername)."')";
	$eventresult = runmysqlquery($eventquery);
	
	$filepath = $_SERVER['DOCUMENT_ROOT'].$addstring.'/filecreated/'.$filebasename;
	$downloadlink = 'http://'.$_SERVER['HTTP_HOST'].$addstring.'/filecreated/'.$filebasename;
	
	$objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xls');
	$objWriter->save($filepath);
	
	$fp = fopen($filebasename,"wa+");
	if($fp)
	{
		downloadfile($filepath);
		fclose($fp);
	}
	//unlink($filepath);
	unlink($filebasename);
	exit; 
 ?>
