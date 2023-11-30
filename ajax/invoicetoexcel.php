<?php
ini_set('memory_limit', '2048M');

include('../functions/phpfunctions.php');

if(imaxgetcookie('dealeruserid')<> '') 
$userid = imaxgetcookie('dealeruserid');
else
{ 
	echo('Thinking to redirect');
	exit;
}

//PHPExcel 
//require_once '../phpgeneration/PHPExcel.php';

//PHPExcel_IOFactory
//require_once '../phpgeneration/PHPExcel/IOFactory.php';

$flag = $_POST['flag'];
if($flag == '')
{
	$url = '../home/index.php?a_link=viewinvoice'; 
	header("location:".$url);
	exit;
}
elseif($flag == 'true')
{
		$textfield = $_POST['textfield'];
		$subselection = $_POST['databasefield'];
		$orderby = $_POST['orderby'];
		$region = $_POST['region'];
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		$fromdate = changedateformat($_POST['fromdate']);
		$todate = changedateformat($_POST['todate']);
		$region = $_POST['region'];
		$reporttype = $_POST['reporttype'];
		$dealerid = $_POST['dealerid'];
		$users = $_POST['users'];
		$region = $_POST['region'];
		$branch = $_POST['branch'];
		$generatedby = $_POST['generatedby'];
		$chks = $_POST['productname'];
		for ($i = 0;$i < count($chks);$i++)
		{
			$c_value .= "'" . $chks[$i] . "'" ."," ;
		}
		$productslist = rtrim($c_value , ',');
		$value = str_replace('\\','',$productslist);
		
		$generatedbypiece = 'left join inv_mas_dealer on inv_mas_dealer.slno = dealer_online_purchase.createdby
';
		$modulepiece = 'and dealer_online_purchase.module = "dealer_module"';
		
		
		$datepiece = "and left(inv_invoicenumbers.createddate,10) BETWEEN '".$fromdate."' and '".$todate."'";
		$regionpiece = ($region == "")?(""):(" and inv_mas_region.slno = '".$region."' ");
		$branchpiece = ($branch == "")?(""):(" and inv_mas_branch.slno = '".$branch."' ");
		$userpiece = ($users == "")?(""):(" and inv_mas_users.slno = '".$users."' ");
		$reporttypepiece = ($reporttype == "")?(""):(" and (inv_mas_receipt.slno is null or invoiceamount <> receiptamount) ");
		$productpiece = ($chks == "")?(""):(" and inv_billdetail.productcode in (".$productslist.") ");
		switch($orderby)
		{
			case "customerid":
				$orderbyfield = "customerid";
				break;
			case "businessname":
				$orderbyfield = "businessname";
				break;
			case "contactperson":
				$orderbyfield = "contactperson";
				break;
			case "invoiceno":
				$orderbyfield = "invoiceno";
				break;
			case "dealername":
				$orderbyfield = "dealername";
				break;
			case "createdby":
				$orderbyfield = "createdby";
				break;
			default:
				$orderbyfield = "businessname";
				break;
		}
		switch($subselection)
		{
			case "customerid":
				$query = "select distinct inv_invoicenumbers.slno,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_contactdetails.contactperson,inv_mas_customer.address,inv_mas_customer.place,inv_mas_district.districtname,inv_mas_state.statename,inv_contactdetails.cell,inv_contactdetails.phone,inv_contactdetails.emailid,inv_mas_region.category,inv_mas_branch.branchname,inv_mas_customer.pincode,inv_mas_customertype.customertype,inv_mas_customercategory.businesstype,inv_invoicenumbers.invoiceno,inv_invoicenumbers.createddate,inv_invoicenumbers.createddate as invoicedate,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,ifnull((inv_mas_receipt.invoiceamount-inv_mas_receipt.receiptamount),inv_invoicenumbers.netamount) as balanceamount,ifnull(inv_mas_receipt.receiptamount,0) as receiptamount,inv_invoicenumbers.netamount,inv_invoicenumbers.description from inv_invoicenumbers left join inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno left join dealer_online_purchase on dealer_online_purchase.onlineinvoiceno = inv_invoicenumbers.slno left join (select GROUP_CONCAT(emailid)as  emailid,GROUP_CONCAT(cell)as  cell,GROUP_CONCAT(phone)as  phone,GROUP_CONCAT(contactperson)as  emailid,customerid from inv_contactdetails group by customerid) as inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno left join inv_mas_customer on inv_mas_customer.slno = dealer_online_purchase.customerreference left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode  left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category left join inv_bill on inv_bill.onlineinvoiceno = inv_invoicenumbers.slno left join inv_billdetail on inv_bill.slno = inv_billdetail.cusbillnumber ".$generatedbypiece." where inv_invoicenumbers.customerid LIKE '%".$textfield."%' ".$datepiece.$regionpiece.$branchpiece.$reporttypepiece.$productpiece.$modulepiece." and dealer_online_purchase.createdby = '".$userid."' ORDER BY ".$orderbyfield."";
				break;
				
			case "contactperson":
				$query = "select distinct inv_invoicenumbers.slno,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_contactdetails.contactperson,inv_mas_customer.address,inv_mas_customer.place,inv_mas_district.districtname,inv_mas_state.statename,inv_contactdetails.cell,inv_contactdetails.phone,inv_contactdetails.emailid,inv_mas_region.category,inv_mas_branch.branchname,inv_mas_customer.pincode,inv_mas_customertype.customertype,inv_mas_customercategory.businesstype,inv_invoicenumbers.invoiceno,inv_invoicenumbers.createddate,inv_invoicenumbers.createddate as invoicedate,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,ifnull((inv_mas_receipt.invoiceamount-inv_mas_receipt.receiptamount),inv_invoicenumbers.netamount) as balanceamount,ifnull(inv_mas_receipt.receiptamount,0) as receiptamount,inv_invoicenumbers.netamount,inv_invoicenumbers.description from inv_invoicenumbers left join inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno left join dealer_online_purchase on dealer_online_purchase.onlineinvoiceno = inv_invoicenumbers.slno  left join (select GROUP_CONCAT(emailid)as  emailid,GROUP_CONCAT(cell)as  cell,GROUP_CONCAT(phone)as  phone,GROUP_CONCAT(contactperson)as  emailid,customerid from inv_contactdetails group by customerid) as inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno left join inv_mas_customer on inv_mas_customer.slno = dealer_online_purchase.customerreference left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode  left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category left join inv_bill on inv_bill.onlineinvoiceno = inv_invoicenumbers.slno left join inv_billdetail on inv_bill.slno = inv_billdetail.cusbillnumber ".$generatedbypiece." where inv_invoicenumbers.contactperson LIKE '%".$textfield."%' ".$datepiece.$regionpiece.$branchpiece.$reporttypepiece.$productpiece.$modulepiece." and dealer_online_purchase.createdby = '".$userid."' ORDER BY ".$orderbyfield."";
				break;
				
			case "invoiceno":
				$query = "select distinct inv_invoicenumbers.slno,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_contactdetails.contactperson,inv_mas_customer.address,inv_mas_customer.place,inv_mas_district.districtname,inv_mas_state.statename,inv_contactdetails.cell,inv_contactdetails.phone,inv_contactdetails.emailid,inv_mas_region.category,inv_mas_branch.branchname,inv_mas_customer.pincode,inv_mas_customertype.customertype,inv_mas_customercategory.businesstype,inv_invoicenumbers.invoiceno,inv_invoicenumbers.createddate,inv_invoicenumbers.createddate as invoicedate,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,ifnull((inv_mas_receipt.invoiceamount-inv_mas_receipt.receiptamount),inv_invoicenumbers.netamount) as balanceamount,ifnull(inv_mas_receipt.receiptamount,0) as receiptamount,inv_invoicenumbers.netamount,inv_invoicenumbers.description from inv_invoicenumbers left join inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno left join dealer_online_purchase on dealer_online_purchase.onlineinvoiceno = inv_invoicenumbers.slno left join inv_mas_customer on inv_mas_customer.slno = dealer_online_purchase.customerreference left join (select GROUP_CONCAT(emailid)as  emailid,GROUP_CONCAT(cell)as  cell,GROUP_CONCAT(phone)as  phone,GROUP_CONCAT(contactperson)as  emailid,customerid from inv_contactdetails group by customerid) as inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode  left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category left join inv_bill on inv_bill.onlineinvoiceno = inv_invoicenumbers.slno left join inv_billdetail on inv_bill.slno = inv_billdetail.cusbillnumber ".$generatedbypiece." where inv_invoicenumbers.invoiceno LIKE '%".$textfield."%' ".$datepiece.$regionpiece.$branchpiece.$reporttypepiece.$productpiece.$modulepiece." and dealer_online_purchase.createdby = '".$userid."' ORDER BY ".$orderbyfield."";
				break;
				
			default:
				$query = "select distinct inv_invoicenumbers.slno,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_contactdetails.contactperson,inv_mas_customer.address,inv_mas_customer.place,inv_mas_district.districtname,inv_mas_state.statename,inv_contactdetails.cell,inv_contactdetails.phone,inv_contactdetails.emailid,inv_mas_region.category,inv_mas_branch.branchname,inv_mas_customer.pincode,inv_mas_customertype.customertype,inv_mas_customercategory.businesstype,inv_invoicenumbers.invoiceno,inv_invoicenumbers.createddate,inv_invoicenumbers.createddate as invoicedate,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,ifnull((inv_mas_receipt.invoiceamount-inv_mas_receipt.receiptamount),inv_invoicenumbers.netamount) as balanceamount,ifnull(inv_mas_receipt.receiptamount,0) as receiptamount,inv_invoicenumbers.netamount,inv_invoicenumbers.description from inv_invoicenumbers left join inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno left join dealer_online_purchase on dealer_online_purchase.onlineinvoiceno = inv_invoicenumbers.slno left join inv_mas_customer on inv_mas_customer.slno = dealer_online_purchase.customerreference  left join (select GROUP_CONCAT(emailid)as  emailid,GROUP_CONCAT(cell)as  cell,GROUP_CONCAT(phone)as  phone,GROUP_CONCAT(contactperson)as  emailid,customerid from inv_contactdetails group by customerid) as inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode  left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category left join inv_bill on inv_bill.onlineinvoiceno = inv_invoicenumbers.slno left join inv_billdetail on inv_bill.slno = inv_billdetail.cusbillnumber ".$generatedbypiece." where inv_invoicenumbers.businessname LIKE '%".$textfield."%' ".$datepiece.$regionpiece.$branchpiece.$reporttypepiece.$productpiece.$modulepiece." and dealer_online_purchase.createdby = '".$userid."' ORDER BY ".$orderbyfield."";
				break;
		}
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
		$mySheet->getStyle('A3:X3')->applyFromArray($styleArray);
		
		//Merge the cell
		$mySheet->mergeCells('A1:W1');
		$mySheet->mergeCells('A2:U2');
		$objPHPExcel->setActiveSheetIndex($pageindex)
					->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
					->setCellValue('A2', 'Invoice Details Report');
		$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
		$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
		$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
	
		
		//Fille contents for Header Row
		$objPHPExcel->setActiveSheetIndex($pageindex)
							->setCellValue('A3', 'Sl No')
							->setCellValue('B3', 'Customer ID')
							->setCellValue('C3', 'Business Name')
							->setCellValue('D3', 'Contact person')
							->setCellValue('E3', 'Address')
							->setCellValue('F3', 'Place')
							->setCellValue('G3', 'Pincode')
							->setCellValue('H3', 'District')
							->setCellValue('I3', 'State')
							->setCellValue('J3', 'Cell')
							->setCellValue('K3', 'Phone')
							->setCellValue('L3', 'Emailid')
							->setCellValue('M3', 'Region')
							->setCellValue('N3', 'Branch')
							->setCellValue('O3', 'Type')
							->setCellValue('P3', 'Category')
							->setCellValue('Q3', 'Product')
							->setCellValue('R3', 'Invoice No')
							->setCellValue('S3', 'Invoice Date')
							->setCellValue('T3', 'Balance Amount')
							->setCellValue('U3', 'Receipt Amount')
							->setCellValue('V3', 'Invoice Amount')
							->setCellValue('W3', 'Dealer')
							->setCellValue('X3', 'Created By');

		$j =4;
		$slno_count =0;
		while($fetch = mysqli_fetch_array($result))
		{
			/*if($fetch['balanceamount'] == '')
				$balanceamount = $fetch['netamount'];
			else
				$balanceamount = $fetch['balanceamount'];*/
			$productdescription = $fetch['description'];
			$productdescriptionsplit = explode('*',$productdescription);
			$productcount = 0;
			for($i=0;$i<count($productdescriptionsplit);$i++)
			{
				$productsplit = explode('$',$productdescriptionsplit[$i]);
				if($productcount > 0)
					$productdisplay .= ',';
				$productdisplay .= $productsplit[1].'-'.$productsplit[2].'-'.$productsplit[3];
				$productcount++;
			}
			$productdescription = str_replace('*',',',$fetch['description']);
			$productdescription = str_replace('$','-',$productdescription);
			$slno_count++;
			$mySheet->setCellValue('A' . $j,$slno_count)
					->setCellValue('B' . $j,$fetch['customerid'])
					->setCellValue('C' . $j,$fetch['businessname'])
					->setCellValue('D' . $j,$fetch['contactperson'])
					->setCellValue('E' . $j,$fetch['address'])
					->setCellValue('F' . $j,$fetch['place'])
					->setCellValue('G' . $j,$fetch['pincode'])
					->setCellValue('H' . $j,$fetch['districtname'])
					->setCellValue('I' . $j,$fetch['statename'])
					->setCellValue('J' . $j,$fetch['cell'])
					->setCellValue('K' . $j,$fetch['phone'])
					->setCellValue('L' . $j,$fetch['emailid'])
					->setCellValue('M' . $j,$fetch['category'])
					->setCellValue('N' . $j,$fetch['branchname'])
					->setCellValue('O' . $j,$fetch['customertype'])
					->setCellValue('P' . $j,$fetch['businesstype'])
					->setCellValue('Q' . $j,$productdisplay)
					->setCellValue('R' . $j,$fetch['invoiceno'])
					->setCellValue('S' . $j,changedateformatwithtime($fetch['invoicedate']))
					->setCellValue('T' . $j,$fetch['balanceamount'])
					->setCellValue('U' . $j,$fetch['receiptamount'])
					->setCellValue('V' . $j,$fetch['netamount'])
					->setCellValue('W' . $j,$fetch['dealername'])
					->setCellValue('X' . $j,$fetch['createdby']);
					$j++;
					$productdisplay = "";
		}
	
				//Get the last cell reference
				$highestRow = $mySheet->getHighestRow(); 
				$highestColumn = $mySheet->getHighestColumn(); 
				$myLastCell = $highestColumn.$highestRow;
				
				//Deine the content range
				$myDataRange = 'A3:'.$myLastCell;
	
				//Apply style to content area range
				$mySheet->getStyle($myDataRange)->applyFromArray($styleArrayContent);
				
				//set the default width for column
				$mySheet->getColumnDimension('A')->setWidth(6);
				$mySheet->getColumnDimension('B')->setWidth(20);
				$mySheet->getColumnDimension('C')->setWidth(35);
				$mySheet->getColumnDimension('D')->setWidth(20);
				$mySheet->getColumnDimension('E')->setWidth(20);
				$mySheet->getColumnDimension('F')->setWidth(25);
				$mySheet->getColumnDimension('G')->setWidth(25);
				$mySheet->getColumnDimension('H')->setWidth(25);
				$mySheet->getColumnDimension('I')->setWidth(25);
				$mySheet->getColumnDimension('J')->setWidth(25);
				$mySheet->getColumnDimension('K')->setWidth(25);
				$mySheet->getColumnDimension('L')->setWidth(25);
				$mySheet->getColumnDimension('M')->setWidth(25);
				$mySheet->getColumnDimension('N')->setWidth(25);
				$mySheet->getColumnDimension('O')->setWidth(25);
				$mySheet->getColumnDimension('P')->setWidth(25);
				$mySheet->getColumnDimension('Q')->setWidth(50);
				$mySheet->getColumnDimension('R')->setWidth(25);
				$mySheet->getColumnDimension('S')->setWidth(25);
				$mySheet->getColumnDimension('T')->setWidth(25);
				$mySheet->getColumnDimension('U')->setWidth(25);
				$mySheet->getColumnDimension('V')->setWidth(25);
				$mySheet->getColumnDimension('W')->setWidth(25);
				$mySheet->getColumnDimension('X')->setWidth(25);
				$pageindex++;

			$addstring = "/imax/dealer";
			if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") || ($_SERVER['HTTP_HOST'] == "archanaab"))
				$addstring = "/saralimax-dealer";
			
				$query = 'select slno,businessname from inv_mas_dealer where slno = '.$userid.'';
				$fetchres = runmysqlqueryfetch($query);	
				$localdate = datetimelocal('Ymd');
				$localtime = datetimelocal('His');
				$filebasename = "Invoice-Register".$localdate."-".$localtime."-".strtolower($fetchres['username']).".xls";
				
				$filepath = $_SERVER['DOCUMENT_ROOT'].$addstring.'/filecreated/'.$filebasename;
				$downloadlink = 'http://'.$_SERVER['HTTP_HOST'].$addstring.'/filecreated/'.$filebasename;
		
				$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','excel_invoicedetails_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
				$result = runmysqlquery($query1);
				
				
				$objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xls');
				$objWriter->save($filepath);
				
				$fp = fopen($filebasename,"wa+");
				if($fp)
				{
					downloadfile($filepath);
					fclose($fp);
				}
				unlink($filebasename);
				exit;
		}
	
?>
