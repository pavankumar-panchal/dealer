<?php
ini_set('memory_limit', '2048M');

include('../functions/phpfunctions.php');
include('../inc/checksession.php');

if(imaxgetcookie('dealeruserid')<> '') 
$userid = imaxgetcookie('dealeruserid');
else
{ 
	echo(json_encode('Thinking to redirect'));
	exit;
}

//PHPExcel 
//require_once '../phpgeneration/PHPExcel.php';

//PHPExcel_IOFactory
//require_once '../phpgeneration/PHPExcel/IOFactory.php';

$flag = $_POST['flag'];
$fromdate = changedateformat($_POST['fromdate']);
$sortby = $_POST['sortby'];
$sortby1 = $_POST['sortby1'];
$aged = $_POST['aged'];

if($_POST['dealerid'] == 'all') 
{
	$query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode, inv_mas_dealer.region, inv_mas_dealer.branchhead as branchhead,inv_mas_dealer.telecaller as telecaller from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
where inv_mas_dealer.slno = '".$userid."';";
	$fetch = runmysqlqueryfetch($query);
	$branchhead = $fetch['branchhead'];
	$telecaller = $fetch['telecaller'];
	if($branchhead == 'yes')
	{
		$query1 = "select * from inv_mas_dealer where slno = '".$userid."';";
		$resultfetch1 = runmysqlqueryfetch($query1);
		$branch = $resultfetch1['branch'];
		$query = "SELECT distinct inv_mas_dealer.slno, inv_mas_dealer.businessname,inv_mas_dealer.branch
FROM inv_mas_dealer left join inv_invoicenumbers on  inv_mas_dealer.slno = inv_invoicenumbers.dealerid 
where inv_mas_dealer.disablelogin = 'no' and inv_mas_dealer.dealernotinuse = 'no' and inv_mas_dealer.branch = '".$branch."' order by businessname;";
		$result1 = runmysqlquery($query);
		while($fetch = mysqli_fetch_array($result1))
		{
			$dealerlistdisplay.= '\''.$fetch['slno'].'\''.',';
		}
		$dealerpiece = (" and inv_invoicenumbers.dealerid IN (".trim($dealerlistdisplay,',').") ");
	}
	elseif($telecaller == 'yes')
	{
		$query33 = "select distinct inv_mas_dealer.slno as slno,inv_mas_dealer.businessname as businessname from inv_invoicenumbers left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.createdbyid where (inv_invoicenumbers.dealerid = '".$userid."' OR inv_invoicenumbers.createdbyid = '".$userid."') AND inv_invoicenumbers.module = 'dealer_module' order by businessname ";
		$result1 = runmysqlquery($query33);
		while($fetch = mysqli_fetch_array($result1))
		{
			$dealerlistdisplay.= '\''.$fetch['slno'].'\''.',';
		}
		$dealerpiece = (" and (inv_invoicenumbers.createdbyid IN (".trim($dealerlistdisplay,',').") or (inv_invoicenumbers.dealerid IN (".trim($dealerlistdisplay,',')."))) ");
		
	}
	
}
else
{
	$dealerid = $_POST['dealerid'];
	$query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode, inv_mas_dealer.region, inv_mas_dealer.branchhead as branchhead,inv_mas_dealer.telecaller as telecaller from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
where inv_mas_dealer.slno = '".$userid."';";
	$fetch = runmysqlqueryfetch($query);
	$branchhead = $fetch['branchhead'];
	$telecaller = $fetch['telecaller'];
	if($telecaller == 'yes')
	{
		$query = "select distinct inv_mas_dealer.slno as slno,inv_mas_dealer.businessname as businessname from inv_invoicenumbers left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.createdbyid where (inv_invoicenumbers.dealerid = '".$userid."' OR inv_invoicenumbers.createdbyid = '".$userid."') AND inv_invoicenumbers.module = 'dealer_module' order by businessname;";
		$result1 = runmysqlquery($query);
		while($fetch = mysqli_fetch_array($result1))
		{
			$dealerlistdisplay.= '\''.$fetch['slno'].'\''.',';
		}	
		if($dealerid != $userid)
			$dealerpiece = (" and inv_invoicenumbers.dealerid = '9999999999' ");
		else
			$dealerpiece = (" and (inv_invoicenumbers.createdbyid IN (".trim($dealerlistdisplay,',').") or (inv_invoicenumbers.dealerid IN (".trim($dealerlistdisplay,',')."))) ");
	}
	else
	{
		$query22 = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.branchhead as branchhead,inv_mas_dealer.telecaller as telecaller from inv_mas_dealer 
where inv_mas_dealer.slno = '".$dealerid."';";
		$fetch22 = runmysqlqueryfetch($query22);
		if($fetch22['telecaller'] == 'yes')
			$dealerpiece = (" and (inv_invoicenumbers.createdbyid = '".$dealerid."' or  inv_invoicenumbers.dealerid = '".$dealerid."') ");
		else
			$dealerpiece = (" and inv_invoicenumbers.dealerid = '".$dealerid."' ");
	}
}
if($flag == '')
{
	$url = '../home/index.php?a_link=receiptregister'; 
	header("location:".$url);
	exit;
}
elseif($flag == 'true')
{
		$query = "SELECT distinct inv_invoicenumbers.slno,left(inv_invoicenumbers.createddate,10) as createddate,inv_invoicenumbers.invoiceno,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_dealer.businessname as dealername,inv_invoicenumbers.netamount,ifnull(inv_mas_receipt.receiptamount,0) as receiptamount, (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) as outstandingamount , DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) as age,inv_invoicenumbers.status from inv_invoicenumbers  left join (select sum(receiptamount) as receiptamount, invoiceno,status from inv_mas_receipt where inv_mas_receipt.status <> 'CANCELLED' group by invoiceno) as inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid where left(inv_invoicenumbers.createddate,10) <= '".$fromdate."' and DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) >= '".$aged."' and (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) > 0 ".$dealerpiece." and inv_invoicenumbers.status <> 'CANCELLED'  ORDER BY ".$sortby." ".$sortby1.", inv_invoicenumbers.createddate desc;";
				
		//echo($query); exit;
		$result = runmysqlquery($query);
		
		// Create new Spreadsheet object
	$objPHPExcel = new Spreadsheet();
		
		//Define Style for header row
		$styleArray = array(
							'font' => array('bold' => true,),
							'fill'=> array('fillType'=>\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,'startColor'=> array('argb' => 'FFCCFFCC')),
							'borders' => array('allBorders'=> array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM))
						);
						
		//Define Style for content area
		$styleArrayContent = array(
							'borders' => array('allBorders'=> array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN))
						);
						
		//set page index
		$pageindex = 0;

		//Set Active Sheet	
		$mySheet = $objPHPExcel->getActiveSheet();
		
		//Set the worksheet name
		$mySheet->setTitle('Receipt Details');
			
		//Apply style for header Row
		$mySheet->getStyle('A3:K3')->applyFromArray($styleArray);
		
		//Merge the cell
		$mySheet->mergeCells('A1:K1');
		$mySheet->mergeCells('A2:K2');
		$objPHPExcel->setActiveSheetIndex($pageindex)
					->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
					->setCellValue('A2', 'Outstanding Details Report');
		$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
		$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
		$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
	
		
		//Fille contents for Header Row
		$objPHPExcel->setActiveSheetIndex($pageindex)
							->setCellValue('A3', 'Sl No')
							->setCellValue('B3', 'Invoice Date')
							->setCellValue('C3', 'Invoice No')
							->setCellValue('D3', 'Customer ID')
							->setCellValue('E3', 'Customer Name')
							->setCellValue('F3', 'Sales Person')
							->setCellValue('G3', 'Invoice Amount')
							->setCellValue('H3', 'Received Amount')
							->setCellValue('I3', 'Outstanding Amount')
							->setCellValue('J3', 'Age (Days)')
							->setCellValue('K3', 'Status');

		$j =4;
		$slno_count =0;
		while($fetch = mysqli_fetch_array($result))
		{
			if($fetch['outstandingamount'] < 0)
				$outstandingamount = '0';
			else
				$outstandingamount = $fetch['outstandingamount'];
			$slno_count++;
			$mySheet->setCellValue('A' . $j,$slno_count)
					->setCellValue('B' . $j,changedateformat($fetch['createddate']))
					->setCellValue('C' . $j,$fetch['invoiceno'])
					->setCellValue('D' . $j,$fetch['customerid'])
					->setCellValue('E' . $j,$fetch['businessname'])
					->setCellValue('F' . $j,$fetch['dealername'])
					->setCellValue('G' . $j,$fetch['netamount'])
					->setCellValue('H' . $j,$fetch['receiptamount'])
					->setCellValue('I' . $j,$outstandingamount)
					->setCellValue('J' . $j,$fetch['age'])
					->setCellValue('K' . $j,$fetch['status']);
					$j++;
		}
	
				//Get the last cell reference
				$highestRow = $mySheet->getHighestRow(); 
				$highestColumn = $mySheet->getHighestColumn(); 
				$myLastCell = $highestColumn.$highestRow;
				
				//Deine the content range
				$myDataRange = 'A3:'.$myLastCell;
	
				//Apply style to content area range
				$mySheet->getStyle($myDataRange)->applyFromArray($styleArrayContent);
				
				//Invoice details
				$objPHPExcel->setActiveSheetIndex($pageindex)
					->setCellValue('E'. ($highestRow + 2), 'Total Invoice')
					->setCellValue('E'. ($highestRow + 3), 'Total Outstanding');

					
				$objPHPExcel->setActiveSheetIndex($pageindex)
					->setCellValue('F'. ($highestRow + 2), ($highestRow-3))
					->setCellValue('F'. ($highestRow + 3), "=SUM(I4:I".($highestRow).")");
				
				//set the default width for column
				$mySheet->getColumnDimension('A')->setWidth(6);
				$mySheet->getColumnDimension('B')->setWidth(15);
				$mySheet->getColumnDimension('C')->setWidth(15);
				$mySheet->getColumnDimension('D')->setWidth(20);
				$mySheet->getColumnDimension('E')->setWidth(40);
				$mySheet->getColumnDimension('F')->setWidth(15);
				$mySheet->getColumnDimension('G')->setWidth(15);
				$mySheet->getColumnDimension('H')->setWidth(15);
				$mySheet->getColumnDimension('I')->setWidth(25);
				$mySheet->getColumnDimension('J')->setWidth(25);
				$mySheet->getColumnDimension('K')->setWidth(25);
				$pageindex++;

		$addstring = "/imax/dealer";
		if($_SERVER['HTTP_HOST'] == "rashmihk" || $_SERVER['HTTP_HOST'] == "archanaab" || $_SERVER['HTTP_HOST'] == "meghanab" )
			$addstring = "/saralimax-dealer";
		
				$query = 'select slno,dealerusername from inv_mas_dealer where slno = '.$userid.'';
				$fetchres = runmysqlqueryfetch($query);
				$dealername = $fetchres['dealerusername'];
				$localdate = datetimelocal('Ymd');
				$localtime = datetimelocal('His');
				$filebasename = "OutstandingRegister".$localdate."-".$localtime."-".strtolower($dealername).".xls";
	
				$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','excel_outstandingregister_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
				$result = runmysqlquery($query1);
				$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','130','".date('Y-m-d').' '.date('H:i:s')."','excel_outstandingregister_report".'-'.strtolower($dealername)."')";
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
		}
	
?>
