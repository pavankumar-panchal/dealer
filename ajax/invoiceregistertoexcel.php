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
$tdstotal = 0;
$tdsnew = 0; 
$tdsupdation = 0;
$spptotal = 0; 
$sppnew = 0; 
$sppupdation = 0;
$stototal = 0;
$stonew = 0 ; 
$stoupdation = 0;
$svhtotal = 0;
$svhnew = 0;
$svhupdation = 0;
$svitotal = 0; 
$svinew = 0 ; 
$sviupdation = 0;
$sactotal = 0;
$sacnew = 0;
$sacupdation = 0;
$otherstotal = 0;
$othersnew = 0 ; 
$othersupdation = 0;
$overalltotal = 0;	
$amcttoday = 0;
$xbrltotal = 0;
$xbrlnew = 0;
$xbrlupdation = 0;
$attinttoday =0 ; $custtoday =0 ; $eiptoday = 0; $implementationtoday = 0; $pptoday = 0; $smstoday = 0; 
$supporttoday = 0; $tastoday = 0; $trainingtoday = 0; 
$alltimecheck = $_POST['alltime'];
$datepiece = ($alltimecheck == 'on')?(""):(" AND (left(inv_invoicenumbers.createddate,10) between '".$fromdate."' and '".$todate."') ");	

if($_POST['dealerid'] == " ") 
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
	$url = '../home/index.php?a_link=invoiceregister'; 
	header("location:".$url);
	exit;
}
elseif($flag == 'true')
{
		/*$query = "select distinct inv_invoicenumbers.slno,left(inv_invoicenumbers.createddate,10) as createddate,inv_invoicenumbers.invoiceno,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_invoicenumbers.amount,inv_invoicenumbers.servicetax,inv_invoicenumbers.netamount,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,inv_invoicenumbers.status from inv_invoicenumbers
left join dealer_online_purchase on dealer_online_purchase.onlineinvoiceno = inv_invoicenumbers.slno
where left(inv_invoicenumbers.createddate,10) between '".$fromdate."' and '".$todate."'  and dealer_online_purchase.createdby = '".$userid."' order by inv_invoicenumbers.createddate desc;";*/
				
		$query = "select distinct inv_invoicenumbers.slno,left(inv_invoicenumbers.createddate,10) as createddate,inv_invoicenumbers.invoiceno,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_invoicenumbers.amount,((IFNULL(inv_invoicenumbers.servicetax,0)+IFNULL(inv_invoicenumbers.sbtax,0)+IFNULL(inv_invoicenumbers.kktax,0)+IFNULL(inv_invoicenumbers.igst,0)+IFNULL(inv_invoicenumbers.sgst,0)+IFNULL(inv_invoicenumbers.cgst,0)) as servicetax,inv_invoicenumbers.netamount,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,inv_invoicenumbers.status from inv_invoicenumbers left join dealer_online_purchase on dealer_online_purchase.onlineinvoiceno = inv_invoicenumbers.slno
where inv_invoicenumbers.slno <> '123456789' ".$datepiece.$dealerpiece."  AND inv_invoicenumbers.status <> 'CANCELLED' order by inv_invoicenumbers.createddate desc ; ";
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
		$mySheet->setTitle('Invoice Details');
			
		//Apply style for header Row
		$mySheet->getStyle('A3:K3')->applyFromArray($styleArray);
		
		//Merge the cell
		$mySheet->mergeCells('A1:K1');
		$mySheet->mergeCells('A2:K2');
		$objPHPExcel->setActiveSheetIndex($pageindex)
					->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
					->setCellValue('A2', 'Invoice Details Report');
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

		$querydrop2 = "Drop table if exists servicessearchdealerexcel;";
		$result2 = runmysqlquery($querydrop2);
	 
		// Create Temporary table to insert 'ITEM SOFTWARE' details
		$queryservices = "CREATE TEMPORARY TABLE `servicessearchdealerexcel` ( 
		`slno` int(10) NOT NULL auto_increment, 
		 `invoiceno` int(10) default NULL,      
		 `servicename` varchar(100) collate latin1_general_ci default NULL, 
		 `serviceamount` varchar(10) collate latin1_general_ci default NULL, 
		 `createddate` datetime default '0000-00-00 00:00:00',
		`dealerid` varchar(25) collate latin1_general_ci default NULL, 
		`regionid` varchar(25) collate latin1_general_ci default NULL,   
		`branch` varchar(25) collate latin1_general_ci default NULL,  
		`branchname` varchar(25) collate latin1_general_ci default NULL, 
		`category` varchar(25) collate latin1_general_ci default NULL,   
		 PRIMARY KEY  (`slno`)    
	 );";
		$resultservices = runmysqlquery($queryservices);

		$queryproducts = "CREATE TEMPORARY TABLE `invoicedetailssearchdealerexcel` (                                       
				  `slno` int(10) NOT NULL auto_increment,                             
				  `invoiceno` int(10) default NULL,                                   
				  `productcode` varchar(10) collate latin1_general_ci default NULL,   
				  `usagetype` varchar(50) collate latin1_general_ci default NULL,     
				  `amount` varchar(25) collate latin1_general_ci default NULL,        
				  `purchasetype` varchar(25) collate latin1_general_ci default NULL,
				  `dealerid` varchar(25) collate latin1_general_ci default NULL, 
				  `invoicedate` datetime default '0000-00-00 00:00:00',
				  `productgroup` varchar(25) collate latin1_general_ci default NULL, 
				  `regionid` varchar(25) collate latin1_general_ci default NULL,   
				  `branch` varchar(25) collate latin1_general_ci default NULL,  
				  `branchname` varchar(25) collate latin1_general_ci default NULL,  
				  `category` varchar(25) collate latin1_general_ci default NULL,
				  `scratchnumber` varchar(25) collate latin1_general_ci default NULL,   
				  `cardid` varchar(25) collate latin1_general_ci default NULL,      
				   PRIMARY KEY  (`slno`)                                               
				);";
		$resultproducts = runmysqlquery($queryproducts);	
		
	
		$resultsummary = runmysqlquery($query);
		
		// Add data to temporary table.
		
		// For all Search Result 
		while($fetch0 = mysqli_fetch_array($resultsummary))
		{
			// Now insert selected invoice details to temporary table condidering all details of the each invoice
			
			$query2 = "select * from inv_invoicenumbers where slno = '".$fetch0['slno']."'";
			$fetch2 = runmysqlqueryfetch($query2); //echo($query2);exit;
			// Insert data to services table
			$serviceamount = 0;
			if($fetch2['servicedescription'] <> '')
			{
				$serviceamountsplit = explode('*',$fetch2['servicedescription']);
				for($k = 0 ;$k < count($serviceamountsplit);$k++)
				{
					$finalsplit = explode('$',$serviceamountsplit[$k]); //echo($offerdescriptionsplit[$j]);exit;
					$serviceamount = $serviceamount + $finalsplit[2];
					// Insert into services table 
					$insertservices = "INSERT INTO servicessearchdealerexcel(invoiceno,servicename,serviceamount,createddate,dealerid,regionid,branch,branchname,category) values('".$fetch2['slno']."','". $finalsplit[1]."','". $finalsplit[2]."','".$fetch2['createddate']."','".$fetch2['dealerid']."','".$fetch2['regionid']."','".$fetch2['branchid']."','".$fetch2['branch']."','".$fetch2['category']."')";
					$result = runmysqlquery($insertservices);
				}
			}
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
					$usagetype = $splitdescription[3];
					$scratchnumber = $splitdescription[4];
					$cardid = $splitdescription[5];
					$amount = $splitdescription[6];
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
					
					$query1 = "select inv_mas_product.group as productgroup from inv_mas_product where productcode = '".$productcode."' ";
					$result1 = runmysqlqueryfetch($query1);
					
					// Insert into invoice details table
					
					$query3 = "insert into invoicedetailssearchdealerexcel(invoiceno,productcode,usagetype,amount,purchasetype,dealerid,invoicedate,productgroup,regionid,branch,branchname,category,scratchnumber,cardid) values('".$fetch2['slno']."','".$productcode."','".$usagetype."','".$totalamount."','".$purchasetype."','".$fetch2['dealerid']."','".$fetch2['createddate']."','".$result1['productgroup']."','".$fetch2['regionid']."','".$fetch2['branchid']."','".$fetch2['branch']."','".$fetch2['category']."','".$scratchnumber."','".$cardid."')"; 
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

			$query200 = "select ifnull(sum(amount),'0') as amount from invoicedetailssearchdealerexcel where purchasetype = 'New' and productgroup = 'TDS' " ;
			$result200 = runmysqlqueryfetch($query200);
			$tdsnew =  $tdsnew + $result200['amount'];
			
			$query206 = "select ifnull(sum(amount),'0') as amount from invoicedetailssearchdealerexcel where purchasetype = 'Updation' and productgroup = 'TDS' " ;
			$result206 = runmysqlqueryfetch($query206);
			$tdsupdation = $tdsupdation + $result206['amount'];
			
			$totaltds = $tdsnew + $tdsupdation;
			
			
			$mySheet->setCellValue('A'.$j,'TDS')
			->setCellValue('B'.$j,($tdsnew))
			->setCellValue('C'.$j,($tdsupdation))
			->setCellValue('D'.$j,($totaltds));
			$j++;
			$currentrow++;	
		
			$query201 = "select ifnull(sum(amount),'0') as amount from invoicedetailssearchdealerexcel where  purchasetype = 'New' and productgroup = 'SPP' " ;
			$result201 = runmysqlqueryfetch($query201);
			$sppnew =  $sppnew + $result201['amount'];
			
			$query207 = "select ifnull(sum(amount),'0') as amount from invoicedetailssearchdealerexcel where  purchasetype = 'Updation' and productgroup = 'SPP' " ;
			$result207 = runmysqlqueryfetch($query207);
			$sppupdation = $sppupdation + $result207['amount'];
			
			$spptotal = $sppnew + $sppupdation;
			
			$mySheet->setCellValue('A'.$j,'SPP')
			->setCellValue('B'.$j,($sppnew))
			->setCellValue('C'.$j,($sppupdation))
			->setCellValue('D'.$j,($spptotal));
			$j++;
			$currentrow++;
		
			$query202= "select ifnull(sum(amount),'0') as amount from invoicedetailssearchdealerexcel where  purchasetype = 'New' and productgroup = 'STO' " ;
			$result202 = runmysqlqueryfetch($query202);
			$stonew = $stonew + $result202['amount'];
			
			$query208= "select ifnull(sum(amount),'0') as amount from invoicedetailssearchdealerexcel where purchasetype = 'Updation' and productgroup = 'STO' " ;
			$result208 = runmysqlqueryfetch($query208);
			$stoupdation = $stoupdation + $result208['amount'];
			
			$stototal = $stonew + $stoupdation;
			
			$mySheet->setCellValue('A'.$j,'STO')
			->setCellValue('B'.$j,($stonew))
			->setCellValue('C'.$j,($stoupdation))
			->setCellValue('D'.$j,($stototal));
			$j++;
			$currentrow++;
			
		
			$query203 = "select ifnull(sum(amount),'0') as amount from invoicedetailssearchdealerexcel where  purchasetype = 'New' and productgroup = 'SVH' " ;
			$result203 = runmysqlqueryfetch($query203);
			$svhnew = $svhnew + $result203['amount'];
			
			$query209 = "select ifnull(sum(amount),'0') as amount from invoicedetailssearchdealerexcel where purchasetype = 'Updation' and productgroup = 'SVH' " ;
			$result209 = runmysqlqueryfetch($query209);
			$svhupdation =  $svhupdation + $result209['amount'];
			
			$svhtotal = $svhnew + $svhupdation;
			
			$mySheet->setCellValue('A'.$j,'SVH')
			->setCellValue('B'.$j,($svhnew))
			->setCellValue('C'.$j,($svhupdation))
			->setCellValue('D'.$j,($svhtotal));
			$j++;
			$currentrow++;
		
			$query204 = "select ifnull(sum(amount),'0') as amount from invoicedetailssearchdealerexcel where  purchasetype = 'New' and productgroup = 'SVI'   " ;
			$result204 = runmysqlqueryfetch($query204);
			$svinew = $svinew + $result204['amount'];
			
			$query210 = "select ifnull(sum(amount),'0') as amount from invoicedetailssearchdealerexcel where  purchasetype = 'Updation' and productgroup = 'SVI' " ;
			$result210 = runmysqlqueryfetch($query210);
			$sviupdation = $sviupdation + $result210['amount'];
			
			$svitotal = $svinew + $sviupdation;
			
			$mySheet->setCellValue('A'.$j,'SVI')
			->setCellValue('B'.$j,($svinew))
			->setCellValue('C'.$j,($sviupdation))
			->setCellValue('D'.$j,($svitotal));
			$j++;
			$currentrow++;
		
			$query205 = "select ifnull(sum(amount),'0') as amount from invoicedetailssearchdealerexcel where  purchasetype = 'New' and productgroup = 'SAC' " ;
			$result205 = runmysqlqueryfetch($query205);
			$sacnew =  $sacnew + $result205['amount'];
			
			$query211 = "select ifnull(sum(amount),'0') as amount from invoicedetailssearchdealerexcel where  purchasetype = 'Updation' and productgroup = 'SAC' " ;
			$result211 = runmysqlqueryfetch($query211);
			$sacupdation = $sacupdation + $result211['amount'];
			
			$sactotal = $sacnew + $sacupdation;
			
			$mySheet->setCellValue('A'.$j,'SAC')
			->setCellValue('B'.$j,($sacnew))
			->setCellValue('C'.$j,($sacupdation))
			->setCellValue('D'.$j,($sactotal));
			$j++;
			$currentrow++;
			
			$query206 = "select ifnull(sum(amount),'0') as amount from invoicedetailssearchdealerexcel where  purchasetype = 'New' and productgroup = 'XBRL' " ;
			$result206 = runmysqlqueryfetch($query206);
			$xbrlnew =  $xbrlnew + $result206['amount'];
			
			$query212 = "select ifnull(sum(amount),'0') as amount from invoicedetailssearchdealerexcel where  purchasetype = 'Updation' and productgroup = 'XBRL' " ;
			$result212 = runmysqlqueryfetch($query212);
			$xbrlupdation = $xbrlupdation + $result212['amount'];
			
			$xbrltotal = $xbrlnew + $xbrlupdation;
			
			$mySheet->setCellValue('A'.$j,'XBRL')
			->setCellValue('B'.$j,($xbrlnew))
			->setCellValue('C'.$j,($xbrlupdation))
			->setCellValue('D'.$j,($xbrltotal));
			$j++;
			$currentrow++;
			
			
			$query240 = "select ifnull(sum(amount),'0') as amount from invoicedetailssearchdealerexcel where  purchasetype = 'New' and productgroup = 'OTHERS' " ;
			$result240 = runmysqlqueryfetch($query240);
			$othersnew =  $othersnew + $result240['amount'];
			
			$query241 = "select ifnull(sum(amount),'0') as amount from invoicedetailssearchdealerexcel where  purchasetype = 'Updation' and productgroup = 'OTHERS' " ;
			$result241 = runmysqlqueryfetch($query241);
			$othersupdation = $othersupdation + $result241['amount'];
			
			$otherstotal = $othersnew + $othersupdation;
			
			$mySheet->setCellValue('A'.$j,'OTHERS')
			->setCellValue('B'.$j,($othersnew))
			->setCellValue('C'.$j,($othersupdation))
			->setCellValue('D'.$j,($otherstotal));
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
		
		// Increment the no of rows to give line space.
		
		$currentrow = $currentrow + 2;	
		
		$mySheet->setCellValue('A'.$currentrow,'Items (Others)');
		$mySheet->getStyle('A'.$currentrow.':B'.$currentrow)->applyFromArray($styleArray1);
		$currentrow++;
		//Set table headings
		$objPHPExcel->setActiveSheetIndex($pageindex)
				->setCellValue('A'.$currentrow,'Service Name')
				->setCellValue('B'.$currentrow,'Total');

		//Apply style for header Row
		$mySheet->getStyle('A'.$currentrow.':B'.$currentrow)->applyFromArray($styleArray);
		$currentrow++;
		$databeginrow = $currentrow;
		$j = $currentrow;
		
			$servicesall = "select ifnull(services.netamount,'0') as amount,inv_mas_service.servicename from inv_mas_service left join (select ifnull(sum(serviceamount),'0') as netamount,servicename from servicessearchdealerexcel  group by servicename) as services on services.servicename = inv_mas_service.servicename order by inv_mas_service.servicename";
		$resultallservices = runmysqlquery($servicesall);
		$totalservices = 0;
		$i_n = 0;
		//echo(mysqli_num_rows($resultallservices));exit;
		while($fetchallservices = mysqli_fetch_array($resultallservices))
		{
			$mySheet->setCellValue('A'.$j,$fetchallservices['servicename'])
				->setCellValue('B'.$j,$fetchallservices['amount']);
				
			$j++;
			$currentrow++;			
		}
		
		
		/*$queryserviceamctoday = "select ifnull(sum(serviceamount),'0') as netamount from servicessearchdealerexcel where  servicename like '%AMC Charges%'";
		$resultserviceamctoday = runmysqlqueryfetch($queryserviceamctoday);
		$amcttoday = $resultserviceamctoday['netamount'];
		
		$mySheet->setCellValue('A'.$j,'AMC Charges')
				->setCellValue('B'.$j,$amcttoday);
				
		$j++;
		$currentrow++;
		
		$queryserviceattinttoday = "select ifnull(sum(serviceamount),'0') as netamount from servicessearchdealerexcel where servicename like '%Attendance Integration%'";
		$resultserviceattinttoday = runmysqlqueryfetch($queryserviceattinttoday);
		$attinttoday = $resultserviceattinttoday['netamount'];
		
		$mySheet->setCellValue('A'.$j,'Attendance Integration')
				->setCellValue('B'.$j,$attinttoday);
				
		$j++;
		$currentrow++;
		
		$queryservicecusttoday = "select ifnull(sum(serviceamount),'0') as netamount from servicessearchdealerexcel where   servicename like '%Customization%'";
		$resultservicecusttoday = runmysqlqueryfetch($queryservicecusttoday);
		$custtoday = $resultservicecusttoday['netamount'];
		
		$mySheet->setCellValue('A'.$j,'Customization')
				->setCellValue('B'.$j,$custtoday);
				
		$j++;
		$currentrow++;
		
		
		$queryserviceeiptoday = "select ifnull(sum(serviceamount),'0') as netamount from servicessearchdealerexcel where   servicename like '%Employee Information Portal (EIP- SPP)%'";
		$resultserviceeiptoday = runmysqlqueryfetch($queryserviceeiptoday);
		$eiptoday = $resultserviceeiptoday['netamount'];
		
		$mySheet->setCellValue('A'.$j,'Employee Information Portal (EIP- SPP)')
				->setCellValue('B'.$j,$eiptoday);
				
		$j++;
		$currentrow++;
		
		$queryserviceimplementationtoday = "select ifnull(sum(serviceamount),'0') as netamount from servicessearchdealerexcel where servicename like '%Implementation%'";
		$resultserviceimplementationtoday = runmysqlqueryfetch($queryserviceimplementationtoday);
		$implementationtoday = $resultserviceimplementationtoday['netamount'];
		
		$mySheet->setCellValue('A'.$j,'Implementation')
				->setCellValue('B'.$j,$implementationtoday);
				
		$j++;
		$currentrow++;
		
		
		$queryservicepptoday = "select ifnull(sum(serviceamount),'0') as netamount from servicessearchdealerexcel where   servicename like '%Payroll Processing%'";
		$resultservicepptoday = runmysqlqueryfetch($queryservicepptoday);
		$pptoday = $resultservicepptoday['netamount'];
		
		$mySheet->setCellValue('A'.$j,'Payroll Processing')
				->setCellValue('B'.$j,$pptoday);
				
		$j++;
		$currentrow++;
		
		$queryservicesmstoday = "select ifnull(sum(serviceamount),'0') as netamount from servicessearchdealerexcel where   servicename like '%SMS Credits%'";
		$resultservicesmstoday = runmysqlqueryfetch($queryservicesmstoday);
		$smstoday = $resultservicesmstoday['netamount'];
		
		$mySheet->setCellValue('A'.$j,'SMS Credits')
				->setCellValue('B'.$j,$smstoday);
				
		$j++;
		$currentrow++;
		
		$queryservicesupporttoday = "select ifnull(sum(serviceamount),'0') as netamount from servicessearchdealerexcel where  servicename like '%Support%'";
		$resultservicesupporttoday = runmysqlqueryfetch($queryservicesupporttoday);
		$supporttoday = $resultservicesupporttoday['netamount'];
		
		$mySheet->setCellValue('A'.$j,'Support')
				->setCellValue('B'.$j,$supporttoday);
				
		$j++;
		$currentrow++;
		
		$queryservicetastoday = "select ifnull(sum(serviceamount),'0') as netamount from servicessearchdealerexcel where  servicename like '%Time Attendance Solution (T&A-SPP)%'";
		$resultservicetastoday = runmysqlqueryfetch($queryservicetastoday);
		$tastoday = $resultservicetastoday['netamount'];
		
		$mySheet->setCellValue('A'.$j,'Time Attendance Solution (T&A-SPP)')
				->setCellValue('B'.$j,$tastoday);
				
		$j++;
		$currentrow++;
		
		$queryservicetrainingtoday = "select ifnull(sum(serviceamount),'0') as netamount from servicessearchdealerexcel where servicename like '%training%'";
		$resultservicetrainingtoday = runmysqlqueryfetch($queryservicetrainingtoday);
		$trainingtoday = $resultservicetrainingtoday['netamount'];
		
		$mySheet->setCellValue('A'.$j,'Training')
				->setCellValue('B'.$j,$trainingtoday);
				
		$j++;
		$currentrow++;*/
		
		$mySheet->setCellValue('A'.$currentrow,'Total')
				->setCellValue('B'.$currentrow,"=SUM(B".$databeginrow.":B".($currentrow - 1).")");
		$mySheet->getCell('B'.$currentrow)->getCalculatedValue();		
		
			
		$mySheet->getStyle('A'.$databeginrow.':B'.$currentrow)->applyFromArray($styleArrayContent);
		$mySheet->getColumnDimension('A')->setWidth(30);
		$mySheet->getColumnDimension('B')->setWidth(25);
		
		$addstring = "/imax/dealer";
		if($_SERVER['HTTP_HOST'] == "rashmihk" || $_SERVER['HTTP_HOST'] == "archanaab" || $_SERVER['HTTP_HOST'] == "meghanab")
			$addstring = "/saralimax-dealer";
		
				$query = 'select slno,dealerusername from inv_mas_dealer where slno = '.$userid.'';
				$fetchres = runmysqlqueryfetch($query);
				$dealername = $fetchres['dealerusername'];
				$localdate = datetimelocal('Ymd');
				$localtime = datetimelocal('His');
				$filebasename = "InvoiceRegister".$localdate."-".$localtime."-".strtolower($dealername).".xls";
	
				$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','excel_invoiceregister_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
				$result = runmysqlquery($query1);
				
				$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','126','".date('Y-m-d').' '.date('H:i:s')."','excel_invoiceregister_report".'-'.strtolower($dealername)."')";
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
