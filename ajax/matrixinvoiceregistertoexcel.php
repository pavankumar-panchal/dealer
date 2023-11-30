<?php
ini_set('memory_limit', '2048M');

include('../functions/phpfunctions.php');
include('../inc/checksession.php');
require_once '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
$todate = changedateformat($_POST['todate']);
$todaynewtotal = 0 ;
$todayupdationtotal = 0;
$softwaretotal = 0;
$softwareupdation = 0;
$softwarenew = 0;
$hardwaretotal = 0; 
$hardwarenew = 0; 
$hardwareupdation = 0;
$overalltotal = 0;
$alltimecheck = $_POST['alltime'];
$datepiece = ($alltimecheck == 'on')?(""):(" AND (left(inv_matrixinvoicenumbers.createddate,10) between '".$fromdate."' and '".$todate."') ");	

if($_POST['dealerid'] == " " &&  $_POST['leftdealerid'] == ' ') 
{
	$query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode, inv_mas_dealer.region, inv_mas_dealer.branchhead as branchhead from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
	where inv_mas_dealer.slno = '".$userid."';";
	$fetch = runmysqlqueryfetch($query);
	$branchhead = $fetch['branchhead'];
	if($branchhead == 'yes')
	{
		$query1 = "select * from inv_mas_dealer where slno = '".$userid."';";
		$resultfetch1 = runmysqlqueryfetch($query1);
		$branch = $resultfetch1['branch'];
		$query = "SELECT distinct inv_mas_dealer.slno, inv_mas_dealer.businessname,inv_mas_dealer.branch
		FROM inv_mas_dealer left join inv_matrixinvoicenumbers on  inv_mas_dealer.slno = inv_matrixinvoicenumbers.dealerid 
		where inv_mas_dealer.disablelogin = 'no' and inv_mas_dealer.dealernotinuse = 'no' and inv_mas_dealer.branch = '".$branch."' order by businessname;";
		$result1 = runmysqlquery($query);
		while($fetch = mysqli_fetch_array($result1))
		{
			$dealerlistdisplay.= '\''.$fetch['slno'].'\''.',';
		}
		$dealerpiece = (" and inv_matrixinvoicenumbers.dealerid IN (".trim($dealerlistdisplay,',').") ");
	}
	
}
else
{
	$dealerid = ($_POST['dealerid'] == " ") ? $_POST['leftdealerid'] : $_POST['dealerid'];
	$dealerpiece = (" and inv_matrixinvoicenumbers.dealerid = '".$dealerid."' ");
}

if($flag == '')
{
	$url = '../home/index.php?a_link=matrixinvoiceregister'; 
	header("location:".$url);
	exit;
}
elseif($flag == 'true')
{
		/*$query = "select distinct inv_matrixinvoicenumbers.slno,left(inv_matrixinvoicenumbers.createddate,10) as createddate,inv_matrixinvoicenumbers.invoiceno,inv_matrixinvoicenumbers.customerid,inv_matrixinvoicenumbers.businessname,inv_matrixinvoicenumbers.amount,inv_matrixinvoicenumbers.servicetax,inv_matrixinvoicenumbers.netamount,inv_matrixinvoicenumbers.dealername,inv_matrixinvoicenumbers.createdby,inv_matrixinvoicenumbers.status from inv_matrixinvoicenumbers
		left join dealer_online_purchase on dealer_online_purchase.onlineinvoiceno = inv_matrixinvoicenumbers.slno
		where left(inv_matrixinvoicenumbers.createddate,10) between '".$fromdate."' and '".$todate."'  and dealer_online_purchase.createdby = '".$userid."' order by inv_matrixinvoicenumbers.createddate desc;";*/
				
		$query = "select distinct inv_matrixinvoicenumbers.slno,left(inv_matrixinvoicenumbers.createddate,10) as createddate,inv_matrixinvoicenumbers.invoiceno,inv_matrixinvoicenumbers.customerid,inv_matrixinvoicenumbers.businessname,inv_matrixinvoicenumbers.amount,(IFNULL(inv_matrixinvoicenumbers.igst,0)+IFNULL(inv_matrixinvoicenumbers.sgst,0)+IFNULL(inv_matrixinvoicenumbers.cgst,0)) as servicetax,inv_matrixinvoicenumbers.netamount,inv_matrixinvoicenumbers.dealername,inv_matrixinvoicenumbers.createdby,inv_matrixinvoicenumbers.status from inv_matrixinvoicenumbers
		where inv_matrixinvoicenumbers.slno <> '123456789' ".$datepiece.$dealerpiece."  AND inv_matrixinvoicenumbers.status <> 'CANCELLED' order by inv_matrixinvoicenumbers.createddate desc ; ";
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
		$mySheet->setTitle('Matrix Invoice Details');
			
		//Apply style for header Row
		$mySheet->getStyle('A3:K3')->applyFromArray($styleArray);
		
		//Merge the cell
		$mySheet->mergeCells('A1:K1');
		$mySheet->mergeCells('A2:K2');
		$objPHPExcel->setActiveSheetIndex($pageindex)
					->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
					->setCellValue('A2', 'Matrix Invoice Details Report');
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
							->setCellValue('F3', 'Sale Value')
							->setCellValue('G3', 'Tax Amount')
							->setCellValue('H3', 'Invoice Amount')
							->setCellValue('I3', 'Sales Person')
							->setCellValue('J3', 'Prepared By')
							->setCellValue('K3', 'Status');

		$j =4;
		$slno_count =0;
		while($fetch = mysqli_fetch_array($result))
		{
			$slno_count++;
			$mySheet->setCellValue('A' . $j,$slno_count)
					->setCellValue('B' . $j,changedateformat($fetch['createddate']))
					->setCellValue('C' . $j,$fetch['invoiceno'])
					->setCellValue('D' . $j,$fetch['customerid'])
					->setCellValue('E' . $j,$fetch['businessname'])
					->setCellValue('F' . $j,$fetch['amount'])
					->setCellValue('G' . $j,$fetch['servicetax'])
					->setCellValue('H' . $j,$fetch['netamount'])
					->setCellValue('I' . $j,$fetch['dealername'])
					->setCellValue('J' . $j,$fetch['createdby'])
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
			->setCellValue('E'. ($highestRow + 2), 'Total Invoices')
			->setCellValue('E'. ($highestRow + 3), 'Total Sale Value')
			->setCellValue('E'. ($highestRow + 4), 'Total Tax ')
			->setCellValue('E'. ($highestRow + 5), 'Total Amount ');
			
		$objPHPExcel->setActiveSheetIndex($pageindex)
			->setCellValue('F'. ($highestRow + 2), ($highestRow-3))
			->setCellValue('F'. ($highestRow + 3), "=SUM(F4:F".($highestRow).")")
			->setCellValue('F'. ($highestRow + 4), "=SUM(G4:G".($highestRow).")")
			->setCellValue('F'. ($highestRow + 5), "=SUM(H4:H".($highestRow).")");
		
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

		// Create Temporary tables 
		
		$querydrop1 = "Drop table if exists invoicedetailssearchdealerexcel;";
		$result1 = runmysqlquery($querydrop1);

		$queryproducts = "CREATE TEMPORARY TABLE `invoicedetailssearchdealerexcel` (                                       
				  `slno` int(10) NOT NULL auto_increment,                             
				  `invoiceno` int(10) default NULL,                                   
				  `productcode` varchar(10) collate latin1_general_ci default NULL,   
				  `amount` varchar(25) collate latin1_general_ci default NULL,        
				  `purchasetype` varchar(25) collate latin1_general_ci default NULL,
				  `dealerid` varchar(25) collate latin1_general_ci default NULL, 
				  `invoicedate` datetime default '0000-00-00 00:00:00',
				  `productgroup` varchar(25) collate latin1_general_ci default NULL, 
				  `regionid` varchar(25) collate latin1_general_ci default NULL,   
				  `branch` varchar(25) collate latin1_general_ci default NULL,  
				  `branchname` varchar(25) collate latin1_general_ci default NULL,  
				  `category` varchar(25) collate latin1_general_ci default NULL,
				   PRIMARY KEY  (`slno`)                                               
				);";
		$resultproducts = runmysqlquery($queryproducts);	
		
	
		$resultsummary = runmysqlquery($query);
		
		// Add data to temporary table.
		
		// For all Search Result 
		while($fetch0 = mysqli_fetch_array($resultsummary))
		{
			// Now insert selected invoice details to temporary table condidering all details of the each invoice
			
			$query2 = "select * from inv_matrixinvoicenumbers where slno = '".$fetch0['slno']."'";
			$fetch2 = runmysqlqueryfetch($query2); //echo($query2);exit;
			
			// Insert data to invoice detals table 
			
			if($fetch2['products'] <> '')
			{
				$count++;
				$totalamount = 0;
				$products = explode('#',$fetch2['products']);
				for($i = 0 ; $i < count($products);$i++)
				{
					$totalamount = 0;
					$description = explode('*',$fetch2['description']);
					$splitdescription = explode('$',$description[$i]);
					
					$productcode = $products[$i];
					$amount = $splitdescription[4];
					$purchasetype = $splitdescription[2];   //echo($usagetype.'^'.$amount.'^'.$purchasetype); exit;
					
					if($i == 0)
					{
						$totalamount = $amount ;
					}
					else 
					{
						$totalamount = $amount;
					}
							
					// Fetch Product 	
					
					$query1 = "select inv_mas_matrixproduct.group as productgroup from inv_mas_matrixproduct where id = '".$productcode."' ";
					$result1 = runmysqlqueryfetch($query1);
					
					// Insert into invoice details table
					
					$query3 = "insert into invoicedetailssearchdealerexcel(invoiceno,productcode,amount,purchasetype,dealerid,invoicedate,productgroup,regionid,branch,branchname,category) values('".$fetch2['slno']."','".$productcode."','".$totalamount."','".$purchasetype."','".$fetch2['dealerid']."','".$fetch2['createddate']."','".$result1['productgroup']."','".$fetch2['regionid']."','".$fetch2['branchid']."','".$fetch2['branch']."','".$fetch2['category']."')"; 
					$result3 =  runmysqlquery($query3);
				}
			}	
		}
		$pageindex++;
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex($pageindex);
	
		//Set Active Sheet	
		$mySheet = $objPHPExcel->getActiveSheet($pageindex);
		
		//Set the worksheet name
		$mySheet->setTitle('Product Wise Summary');
		
		$styleArray1 = array(
							'font' => array('bold' => true,));
		
		$currentrow = 1;
		$slno1 = 0;
		//Set heading
		$mySheet->setCellValue('A'.$currentrow,'Items (Software)');
		$mySheet->getStyle('A1:D1')->applyFromArray($styleArray1);
		$currentrow++;
		//Set table headings
		$objPHPExcel->setActiveSheetIndex($pageindex)
				->setCellValue('A'.$currentrow,'Product')
				->setCellValue('B'.$currentrow,'New')
				->setCellValue('C'.$currentrow,'Updation')
				->setCellValue('D'.$currentrow,'Total');
				
		
		$j = 3;		
		//Apply style for header Row
		$mySheet->getStyle('A'.$currentrow.':D'.$currentrow)->applyFromArray($styleArray);
		$currentrow++;
		$databeginrow = $currentrow;

		$querynewpurchase = "select ifnull(sum(amount),'0') as amount,productgroup from invoicedetailssearchdealerexcel where purchasetype = 'New' group by productgroup;";
		$resultnewpurchase = runmysqlquery($querynewpurchase);
		$softwarenewpurchase= 0;$hardwarenewpurchase = 0;
		while($fetchnewpurchase = mysqli_fetch_array($resultnewpurchase))
		{
			if($fetchnewpurchase['productgroup'] == 'Software')
				$softwarenewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'Hardware')
				$hardwarenewpurchase = $fetchnewpurchase['amount'];
		}

		$queryupdationpurchase = "select ifnull(sum(amount),'0') as amount,productgroup from invoicedetailssearchdealerexcel where purchasetype = 'Updation'  group by productgroup;";
		$resultupdationpurchase = runmysqlquery($queryupdationpurchase);
		$softwareupdationpurchase= 0;$hardwareupdationpurchase = 0;
		while($fetchupdationpurchase = mysqli_fetch_array($resultupdationpurchase))
		{
			if($fetchupdationpurchase['productgroup'] == 'Software')
				$softwareupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'Hardware')
				$hardwareupdationpurchase = $fetchupdationpurchase['amount'];
				
		}
		$softwaretotal = $softwarenewpurchase + $softwareupdationpurchase;
		$hardwaretotal = $hardwarenewpurchase + $hardwareupdationpurchase;
		$mySheet->setCellValue('A'.$j,'Software')
				->setCellValue('B'.$j,($softwarenewpurchase))
				->setCellValue('C'.$j,($softwareupdationpurchase))
				->setCellValue('D'.$j,($softwaretotal));
		$j++;
		$currentrow++;	
		
		$mySheet->setCellValue('A'.$j,'Hardware')
				->setCellValue('B'.$j,($hardwarenewpurchase))
				->setCellValue('C'.$j,($hardwareupdationpurchase))
				->setCellValue('D'.$j,($hardwaretotal));
		$j++;
		$currentrow++;
			
			
		//echo($databeginrow.'^'.$currentrow);exit;
		
		$mySheet->setCellValue('A'.$currentrow,'Total')
				->setCellValue('B'.$currentrow,"=SUM(B".$databeginrow.":B".($currentrow - 1).")")
				->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow - 1).")")
				->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow - 1).")");
		$mySheet->getCell('B'.$currentrow)->getCalculatedValue();		
		$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('D'.$currentrow)->getCalculatedValue();	
			
		$mySheet->getStyle('A'.$databeginrow.':D'.$currentrow)->applyFromArray($styleArrayContent);
		$mySheet->getColumnDimension('A')->setWidth(10);
		$mySheet->getColumnDimension('B')->setWidth(25);
		$mySheet->getColumnDimension('C')->setWidth(25);
		$mySheet->getColumnDimension('D')->setWidth(25);	
		
		
		$addstring = "/imax/dealer";
		if($_SERVER['HTTP_HOST'] == "rashmihk" || $_SERVER['HTTP_HOST'] == "archanaab" || $_SERVER['HTTP_HOST'] == "meghanab")
			$addstring = "/saralimax-dealer";
		
				$query = 'select slno,dealerusername from inv_mas_dealer where slno = '.$userid.'';
				$fetchres = runmysqlqueryfetch($query);
				$dealername = $fetchres['dealerusername'];
				$localdate = datetimelocal('Ymd');
				$localtime = datetimelocal('His');
				$filebasename = "MatrixinvoiceRegister".$localdate."-".$localtime."-".strtolower($dealername).".xls";
	
				$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','excel_invoiceregister_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
				$result = runmysqlquery($query1);
				
				$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','282','".date('Y-m-d').' '.date('H:i:s')."','excel_matrixinvoiceregister_report".'-'.strtolower($dealername)."')";
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
