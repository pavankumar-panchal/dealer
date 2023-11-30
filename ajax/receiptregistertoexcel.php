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
$paymentmode = $_POST['paymentmode'];
$alltimecheck = $_POST['alltime'];
$datepiece = ($alltimecheck == 'on')?(""):(" AND (inv_mas_receipt.receiptdate BETWEEN '".$fromdate."' and '".$todate."') ");	

if($_POST['dealerid'] == 'all') 
{
	$query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode, inv_mas_dealer.region, inv_mas_dealer.branchhead as branchhead,inv_mas_dealer.telecaller as telecaller from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
where inv_mas_dealer.slno = '".$userid."';";
	$fetch = runmysqlqueryfetch($query);
	$branchhead = $fetch['branchhead'];
	$telecaller = $fetch['telecaller'];
	$branch = $fetch['branch'];

	if($branchhead == 'yes' || $telecaller == 'yes')
	{
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
		
}
else
{
	$dealerid = $_POST['dealerid'];
	$query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode, inv_mas_dealer.region, inv_mas_dealer.branchhead as branchhead,inv_mas_dealer.telecaller as telecaller from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
where inv_mas_dealer.slno = '".$dealerid."';";
	$fetch = runmysqlqueryfetch($query);
	$branchhead = $fetch['branchhead'];
	$telecaller = $fetch['telecaller'];
	if($telecaller == 'yes' || $branchhead == 'yes')
		$dealerpiece = (" and (inv_invoicenumbers.createdbyid = ".$dealerid." or (inv_invoicenumbers.dealerid = ".$dealerid.") ");
	else
		$dealerpiece = (" and inv_invoicenumbers.dealerid = '".$dealerid."' ");
}

$paymentmodepiece = ($paymentmode == "")?(""):(" and inv_mas_receipt.paymentmode = '".$paymentmode."' ");
$databasefield = $_POST['databasefield'];
$textfield = $_POST['textfield'];
$chks = $_POST['productarray'];

for ($i = 0;$i < count($chks);$i++)
{
	$c_value .= "'" . $chks[$i] . "'" ."," ;
}
$productslist = rtrim($c_value , ',');
$productlistsplit = str_replace('\\','',$productslist);

$productlistsplitcount = (int)count($productlistsplit);
$status = $_POST['status'];
$receiptstatus = $_POST['receiptstatus'];
$cancelledinvoice = $_POST['cancelledinvoice'];
$itemlist = $_POST['itemlist'];
$reconciletype = $_POST['reconciletype'];
$chks1 = $_POST['itemarray'];
for ($i = 0;$i < count($chks1);$i++)
{
	$c_value1 .= "'" . $chks1[$i] . "'" ."," ;
}
$itemlist = rtrim($c_value1 , ',');
$itemlistsplit = str_replace('\\','',$itemlist);

$itemlistsplitcount = count($itemlistsplit);

if($productslist != '')
{
	for($i = 0;$i< count($chks); $i++)
	{
		if($i < (count($chks)-1))
			$appendor = 'or'.' ';
		else
			$appendor = '';
			
		$finalproductlist .= ' inv_invoicenumbers.products'.' '.'like'.' "'.'%'.$chks[$i].'%'.'" '.$appendor."";
	}
}


if($itemlist != '')
{
	for($j = 0;$j<  count($chks1); $j++)
	{
		if($j < (count($chks1)-1))
			$appendor1 = 'or'.' ';
		else
			$appendor1 = '';
			
		$finalitemlist .= ' inv_invoicenumbers.servicedescription'.' '.'like'.' "'.'%'.$chks1[$j].'%'.'" '.$appendor1."";
	}
}
if(($itemlist == '') && ($productslist == ''))
	$finallistarray = "";
elseif(($itemlist != '') && ($productslist != ''))
	$finallistarray = ' AND ('.$finalproductlist.' '.'OR'.' '.$finalitemlist.')';
elseif($productslist == '')
	$finallistarray = ' AND ('.$finalitemlist.')';
elseif($itemlist == '')
	$finallistarray = ' AND ('.$finalproductlist.')';

$reconciletype_piece = ($reconciletype == "")?(""):(" AND inv_mas_receipt.reconsilation = '".$reconciletype."' ");
$statuspiece = ($status == "")?(""):(" AND inv_invoicenumbers.status = '".$status."'");
$cancelledpiece = ($cancelledinvoice == "on")?("AND inv_invoicenumbers.status <> 'CANCELLED'"):("");
$receiptstatuspiece = ($receiptstatus == "")?(""):(" and inv_mas_receipt.restatus = '".$receiptstatus."' ");
if($flag == '')
{
	$url = '../home/index.php?a_link=receiptregister'; 
	header("location:".$url);
	exit;
}
elseif($flag == 'true')
{
		/*$query = "select distinct inv_mas_receipt.slno,inv_mas_receipt.receiptdate,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_receipt.receiptamount,inv_mas_receipt.paymentmode,inv_invoicenumbers.invoiceno,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,inv_invoicenumbers.status from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno where inv_mas_receipt.module='dealer_module' and inv_mas_receipt.receiptdate BETWEEN '".$fromdate."' and '".$todate."' and  inv_invoicenumbers.dealerid ='".$userid."'  ".$paymentmodepiece." order by inv_invoicenumbers.slno;";*/
				
		
		
		//echo($dealerpiece);exit;
		
		//Calculation of Total Sale, Total Tax, Total Amount
		/*$resultcount = "select count(distinct inv_mas_receipt.slno) as count from inv_mas_receipt 
left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno 
where  inv_mas_receipt.slno <> '123456789' ".$datepiece.$paymentmodepiece.$dealerpiece.$finallistarray.$reconciletype_piece.$statuspiece.$seriespiece.$cancelledpiece.$receiptstatuspiece."  order by inv_mas_receipt.slno;";
		$fetch10 = runmysqlqueryfetch($resultcount);
		$fetchresultcount = $fetch10['count'];
		$totalreceipts = $fetchresultcount;
			
		$query11 = "select sum(inv_mas_receipt.receiptamount) as receiptamount from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno where  inv_mas_receipt.slno <> '123456789' ".$datepiece.$paymentmodepiece.$dealerpiece.$finallistarray.$reconciletype_piece.$statuspiece.$seriespiece.$cancelledpiece.$receiptstatuspiece." order by inv_mas_receipt.slno;";
		$fetchresult = runmysqlqueryfetch($query11);
		$totalamount += $fetchresult['receiptamount'];*/
		
		
		
		switch($databasefield)
		{
			case "slno":
				$cusid = strlen($textfield);
				if($cusid == 17)
					$customerid = substr($textfield,12);
				else if($cusid == 20)
					$customerid = substr($textfield,15);
				else
					$customerid = $textfield;
					
				$query = "select distinct inv_mas_receipt.slno,inv_mas_receipt.receiptdate,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_receipt.receiptamount,inv_mas_receipt.paymentmode,inv_invoicenumbers.invoiceno,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,inv_mas_receipt.restatus as receiptstatus,inv_mas_receipt.reconsilation, inv_mas_receipt.receiptamount from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
 where right(inv_invoicenumbers.customerid,5) like '%".$customerid."%' ".$datepiece.$paymentmodepiece.$dealerpiece.$finallistarray.$reconciletype_piece.$statuspiece.$seriespiece.$cancelledpiece.$receiptstatuspiece."  order by inv_invoicenumbers.createddate desc";
				break;
		
			case "invoiceno":
				$query = "select distinct inv_mas_receipt.slno,inv_mas_receipt.receiptdate,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_receipt.receiptamount,inv_mas_receipt.paymentmode,inv_invoicenumbers.invoiceno,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,inv_mas_receipt.restatus as receiptstatus,inv_mas_receipt.reconsilation, inv_mas_receipt.receiptamount from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
 where inv_invoicenumbers.invoiceno LIKE '%".$textfield."%'  ".$datepiece.$paymentmodepiece.$dealerpiece.$finallistarray.$reconciletype_piece.$statuspiece.$seriespiece.$cancelledpiece.$receiptstatuspiece."  order by inv_invoicenumbers.createddate desc";
				break;
			case "receiptno":
				$query = "select distinct inv_mas_receipt.slno,inv_mas_receipt.receiptdate,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_receipt.receiptamount,inv_mas_receipt.paymentmode,inv_invoicenumbers.invoiceno,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,inv_mas_receipt.status as receiptstatus,inv_mas_receipt.reconsilation, inv_mas_receipt.receiptamount from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5) where inv_mas_receipt.slno LIKE '%".$textfield."%'  ".$datepiece.$paymentmodepiece.$dealerpiece.$finallistarray.$reconciletype_piece.$statuspiece.$seriespiece.$cancelledpiece.$receiptstatuspiece."  order by inv_invoicenumbers.createddate desc";
				break;
			case "chequeno":
				$query = "select distinct inv_mas_receipt.slno,inv_mas_receipt.receiptdate,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_receipt.receiptamount,inv_mas_receipt.paymentmode,inv_invoicenumbers.invoiceno,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,inv_mas_receipt.restatus as receiptstatus,inv_mas_receipt.reconsilation, inv_mas_receipt.receiptamount from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
 where inv_mas_receipt.chequeno LIKE '%".$textfield."%' ".$datepiece.$paymentmodepiece.$dealerpiece.$finallistarray.$reconciletype_piece.$statuspiece.$seriespiece.$cancelledpiece.$receiptstatuspiece."  order by inv_invoicenumbers.createddate desc";
				break;
			case "chequedate":
				$query = "select distinct inv_mas_receipt.slno,inv_mas_receipt.receiptdate,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_receipt.receiptamount,inv_mas_receipt.paymentmode,inv_invoicenumbers.invoiceno,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,inv_mas_receipt.restatus as receiptstatus,inv_mas_receipt.reconsilation, inv_mas_receipt.receiptamount from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5) where  inv_mas_receipt.chequedate LIKE '%".changedateformat($textfield)."%' ".$datepiece.$paymentmodepiece.$dealerpiece.$finallistarray.$reconciletype_piece.$statuspiece.$seriespiece.$cancelledpiece.$receiptstatuspiece."  order by inv_invoicenumbers.createddate desc";
				break;
			case "depositdate":
				$query = "select distinct inv_mas_receipt.slno,inv_mas_receipt.receiptdate,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_receipt.receiptamount,inv_mas_receipt.paymentmode,inv_invoicenumbers.invoiceno,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,inv_mas_receipt.restatus as receiptstatus,inv_mas_receipt.reconsilation, inv_mas_receipt.receiptamount from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5) where inv_mas_receipt.depositdate LIKE '%".changedateformat($textfield)."%'  ".$datepiece.$paymentmodepiece.$dealerpiece.$finallistarray.$reconciletype_piece.$statuspiece.$seriespiece.$cancelledpiece.$receiptstatuspiece."  order by inv_invoicenumbers.createddate desc";
				break;
			case "drawnon":
				$query = "select distinct inv_mas_receipt.slno,inv_mas_receipt.receiptdate,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_receipt.receiptamount,inv_mas_receipt.paymentmode,inv_invoicenumbers.invoiceno,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,inv_mas_receipt.restatus as receiptstatus,inv_mas_receipt.reconsilation, inv_mas_receipt.receiptamount from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5) where inv_mas_receipt.drawnon LIKE '%".$textfield."%'  ".$datepiece.$paymentmodepiece.$dealerpiece.$finallistarray.$reconciletype_piece.$statuspiece.$seriespiece.$cancelledpiece.$receiptstatuspiece."  order by inv_invoicenumbers.createddate desc";
				break;
				
			case "paymentamt":
				$query = "select distinct inv_mas_receipt.slno,inv_mas_receipt.receiptdate,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_receipt.receiptamount,inv_mas_receipt.paymentmode,inv_invoicenumbers.invoiceno,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,inv_mas_receipt.restatus as receiptstatus,inv_mas_receipt.reconsilation, inv_mas_receipt.receiptamount from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5) where inv_mas_receipt.receiptamount LIKE '%".$textfield."%' ".$datepiece.$paymentmodepiece.$dealerpiece.$finallistarray.$reconciletype_piece.$statuspiece.$seriespiece.$cancelledpiece.$receiptstatuspiece."  order by inv_invoicenumbers.createddate desc";
				break;
			
			default:
				$query = "select distinct inv_mas_receipt.slno,inv_mas_receipt.receiptdate,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_receipt.receiptamount,inv_mas_receipt.paymentmode,inv_invoicenumbers.invoiceno,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,inv_mas_receipt.restatus as receiptstatus,inv_mas_receipt.reconsilation, inv_mas_receipt.receiptamount from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5) where inv_invoicenumbers.businessname LIKE '%".$textfield."%' ".$datepiece.$paymentmodepiece.$dealerpiece.$finallistarray.$reconciletype_piece.$statuspiece.$seriespiece.$cancelledpiece.$receiptstatuspiece."  order by inv_invoicenumbers.createddate desc ";
				break;
		}

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
		$mySheet->getStyle('A3:L3')->applyFromArray($styleArray);
		
		//Merge the cell
		$mySheet->mergeCells('A1:L1');
		$mySheet->mergeCells('A2:L2');
		$objPHPExcel->setActiveSheetIndex($pageindex)
					->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
					->setCellValue('A2', 'Receipt Details Report');
		$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
		$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
		$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
	
		
		//Fille contents for Header Row
		$objPHPExcel->setActiveSheetIndex($pageindex)
							->setCellValue('A3', 'Sl No')
							->setCellValue('B3', 'Receipt Date')
							->setCellValue('C3', 'Receipt No')
							->setCellValue('D3', 'Customer ID')
							->setCellValue('E3', 'Customer Name')
							->setCellValue('F3', 'Receipt Amount')
							->setCellValue('G3', 'Mode')
							->setCellValue('H3', 'Invoice No')
							->setCellValue('I3', 'Sales Person')
							->setCellValue('J3', 'Prepared By')
							->setCellValue('K3', 'Receipt Status')
							->setCellValue('L3', 'Reconcile Type');

		$j =4;
		$slno_count =0;
		while($fetch = mysqli_fetch_array($result))
		{
			if($fetch['chequedate'] == '')
				$chequedate = 	'';
			else
				$chequedate = changedateformat($fetch['chequedate']);
			if($fetch['depositdate'] == '')
				$depositdate = 	'';
			else
				$depositdate = changedateformat($fetch['depositdate']);
			$slno_count++;
			$mySheet->setCellValue('A' . $j,$slno_count)
					->setCellValue('B' . $j,changedateformat($fetch['receiptdate']))
					->setCellValue('C' . $j,$fetch['slno'])
					->setCellValue('D' . $j,$fetch['customerid'])
					->setCellValue('E' . $j,$fetch['businessname'])
					->setCellValue('F' . $j,$fetch['receiptamount'])
					->setCellValue('G' . $j,getpaymentmode($fetch['paymentmode']))
					->setCellValue('H' . $j,$fetch['invoiceno'])
					->setCellValue('I' . $j,$fetch['dealername'])
					->setCellValue('J' . $j,$fetch['createdby'])
					->setCellValue('K' . $j,$fetch['receiptstatus'])
					->setCellValue('L' . $j,$fetch['reconsilation']);
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
					->setCellValue('E'. ($highestRow + 2), 'Total Receipts')
					->setCellValue('E'. ($highestRow + 3), 'Total Amount');

					
				$objPHPExcel->setActiveSheetIndex($pageindex)
					->setCellValue('F'. ($highestRow + 2), ($highestRow-3))
					->setCellValue('F'. ($highestRow + 3), "=SUM(F4:F".($highestRow).")");
				
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
				$mySheet->getColumnDimension('L')->setWidth(25);
				$pageindex++;

		$addstring = "/imax/dealer";
		if($_SERVER['HTTP_HOST'] == "rashmihk" || $_SERVER['HTTP_HOST'] == "archanaab" || $_SERVER['HTTP_HOST'] == "meghanab")
			$addstring = "/saralimax-dealer";
		
				$query = 'select slno,dealerusername from inv_mas_dealer where slno = '.$userid.'';
				$fetchres = runmysqlqueryfetch($query);
				$dealername = $fetchres['dealerusername'];
				$localdate = datetimelocal('Ymd');
				$localtime = datetimelocal('His');
				$filebasename = "ReceiptRegister".$localdate."-".$localtime."-".strtolower($dealername).".xls";
	
				$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','excel_receiptregister_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
				$result = runmysqlquery($query1);
				$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','128','".date('Y-m-d').' '.date('H:i:s')."','excel_receiptregister_report".'-'.strtolower($dealername)."')";
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
