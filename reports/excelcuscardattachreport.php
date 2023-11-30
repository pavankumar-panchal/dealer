<?php
ini_set('memory_limit', '2048M');

include('../functions/phpfunctions.php');
require_once '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

//PHPExcel
//require_once '../phpgeneration/PHPExcel.php';

//PHPExcel_IOFactory
//require_once '../phpgeneration/PHPExcel/IOFactory.php';

$flag = $_POST['flag'];
if($flag == '')
{
	$url = '../home/index.php?a_link=cuscardattachreport'; 
	header("location:".$url);
	exit;
}
elseif($flag == 'true')
{
	$userid = imaxgetcookie('dealeruserid');
	$id = $_GET['id'];
	$todate = $_POST['todate'];
	$fromdate = $_POST['fromdate'];
	$geography = $_POST['geography'];
	$attachedby = $_POST['attachedby'];
	$region = $_POST['region'];
	$state = $_POST['state'];
	$scheme = $_POST['scheme'];
	$registered = $_POST['registered'];
	$usagetype = $_POST['usagetype'];
	$purchasetype = $_POST['purchasetype'];
	$chks = $_POST['productname'];
	for ($i = 0;$i < count($chks);$i++)
	{
		$c_value .= "'" . $chks[$i] . "'" ."," ;
	}
	$productslist = rtrim($c_value , ',');
	$value = str_replace('\\','',$productslist);
	$reportdate = datetimelocal('d-m-Y');
	$scheme = $_POST['scheme'];
	
	if($todate <> '' && $fromdate <> '')
	{
		//echo($reportdate);
		if($geography == "") { $geographypiece = ""; } 
		elseif($geography == "region") { $geographypiece = " AND inv_mas_customer.region = '".$region."' " ; }
		elseif($geography == "state") { $geographypiece = " AND  inv_mas_district.statecode = '".$state."' " ; }
		elseif($geography == "district") { $geographypiece = " AND inv_mas_customer.district = '".$district."' " ; }
			
		$productcodepiece = ($chks == "")?(""):(" AND  inv_mas_product.productcode IN (".$value.") ");
		$schemepiece = ($scheme == "")?(""):(" AND inv_mas_scheme.slno = '".$scheme."' ");
		$usagetypepiece = ($usagetype == "")?(""):(" AND inv_dealercard.usagetype = '".$usagetype."' ");
		$purchasetypepiece = ($purchasetype == "")?(""):(" AND inv_dealercard.purchasetype = '".$purchasetype."' ");
		$registeredpiece = ($registered == "")?(""):(" AND inv_mas_scratchcard.registered = '".$registered."' ");
		$attachedbypiece = ($attachedby == "")?(""):("  AND inv_mas_users.slno = '".$attachedby."' ");
		
		$datepiece = " AND substring(inv_dealercard.cuscardattacheddate,1,10) BETWEEN '".changedateformat($fromdate)."' AND '".changedateformat($todate)."'";
		
		$grid = '<table width="100%" border="1" cellpadding="4" cellspacing="0" bordercolor="#527094">';
		$grid .= '<tr><td>';
	
		$query = "select distinct inv_mas_customer.slno as cusslno, inv_mas_customer.businessname as cusname,inv_mas_district.districtname as districtname,inv_mas_state.statename as statename, inv_mas_dealer.businessname as dealername,
		inv_mas_scratchcard.cardid as cardid, inv_mas_product.productname as productname,inv_dealercard.cuscardattacheddate as 
		attcheddate,inv_customerproduct.date as registereddate,inv_mas_scratchcard.registered as registered,inv_mas_scratchcard.scratchnumber as scratchnumber,inv_dealercard.usagetype as usagetype,inv_dealercard.purchasetype as purchasetype,inv_mas_users.fullname ,inv_mas_region.category as region ,inv_mas_scheme.schemename as schemename from inv_dealercard 
		left join inv_mas_scratchcard on inv_dealercard.cardid = inv_mas_scratchcard.cardid 
		left join inv_mas_customer on inv_mas_customer.slno = inv_dealercard.customerreference 
		left join inv_mas_dealer on inv_mas_dealer.slno = inv_dealercard.dealerid
		left join inv_mas_users on inv_mas_users.slno = inv_dealercard.cuscardattachedby 
		left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
		left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region 
		left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
		left join inv_customerproduct on inv_customerproduct.cardid = inv_dealercard.cardid
		left join inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme
		left join inv_mas_state on inv_mas_state.slno = inv_mas_district.statecode WHERE inv_dealercard.cuscardattachedby = '".$userid."' and inv_dealercard.dealerid = '".$userid."'".$datepiece.$geographypiece.$productcodepiece.$usagetypepiece.$purchasetypepiece.$attachedbypiece.$schemepiece.$registeredpiece."; ";		
	
	}
		
		//echo($query); exit;
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
		//Apply style for header Row
		$mySheet->getStyle('A3:T3')->applyFromArray($styleArray);
		
		//Merge the cell
		$mySheet->mergeCells('A1:T1');
		$mySheet->mergeCells('A2:T2');
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
					->setCellValue('A2', 'PIN No Attached Details');
		$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
		$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
		$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
	
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A3', 'Sl No')
					->setCellValue('B3', 'Company Name')
					->setCellValue('C3', 'Contact Person')
					->setCellValue('D3', 'District')
					->setCellValue('E3', 'State')
					->setCellValue('F3', 'Email ID')
					->setCellValue('G3', 'Phone')
					->setCellValue('H3', 'Cell')
					->setCellValue('I3', 'Dealer')
					->setCellValue('J3', 'Pin Serial Number')
					->setCellValue('K3', 'Product')
					->setCellValue('L3', 'Attached Date')
					->setCellValue('M3', 'Registered Date')
					->setCellValue('N3', 'Registered')
					->setCellValue('O3', 'PIN Number')
					->setCellValue('P3', 'Purchase Type')
					->setCellValue('Q3', 'Usage Type')
					->setCellValue('R3', 'Attached By')
					->setCellValue('S3', 'Region')
					->setCellValue('T3', 'Scheme');
	
			$j = 4;
			$slno = 0;
			while($fetch = mysqli_fetch_array($result))
			{
				$slno++;
				if($fetch['registereddate'] == '') 
					$registereddate = '';
				else
					$registereddate = changedateformat($fetch['registereddate']);
					
				
				// Fetch Contact Details
				$querycontactdetails = "select customerid, GROUP_CONCAT(contactperson) as contactperson,  
				GROUP_CONCAT(phone) as phone, GROUP_CONCAT(cell) as cell, GROUP_CONCAT(emailid) as emailid from inv_contactdetails where customerid = '".$fetch['cusslno']."'  group by customerid ";
				$resultdetails = runmysqlquery($querycontactdetails);
				$count = mysqli_num_rows($resultdetails);

				$contactvalues = $phoneres = $cellres = $emailidres= "";
				if($count >0)
				{
					$resultcontactdetails = runmysqlqueryfetch($querycontactdetails);
				
					$contactvalues = removedoublecomma($resultcontactdetails['contactperson']);
					$phoneres = removedoublecomma($resultcontactdetails['phone']);
					$cellres = removedoublecomma($resultcontactdetails['cell']);
					$emailidres = removedoublecomma($resultcontactdetails['emailid']);
				}
				
				
					$mySheet->setCellValue('A' . $j,$slno)
							->setCellValue('B' . $j,$fetch['cusname'])
							->setCellValue('C' . $j,trim($contactvalues,','))
							->setCellValue('D' . $j,$fetch['districtname'])
							->setCellValue('E' . $j,$fetch['statename'])
							->setCellValue('F' . $j,trim($emailidres,','))
							->setCellValue('G' . $j,trim($phoneres,','))
							->setCellValue('H' . $j,trim($cellres,','))
							->setCellValue('I' . $j,$fetch['dealername'])
							->setCellValue('J' . $j,$fetch['cardid'])
							->setCellValue('K' . $j,$fetch['productname'])
							->setCellValue('L' . $j,changedateformat(substr($fetch['attcheddate'],0,10)))
							->setCellValue('M' . $j,$registereddate)
							->setCellValue('N' . $j,$fetch['registered'])
							->setCellValue('O' . $j,$fetch['scratchnumber'])
							->setCellValue('P' . $j,$fetch['usagetype'])
							->setCellValue('Q' . $j,$fetch['purchasetype'])
							->setCellValue('R' . $j,$fetch['fullname'])
							->setCellValue('S' . $j,$fetch['region'])
							->setCellValue('T' . $j,$fetch['schemename']);
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
		$mySheet->getColumnDimension('B')->setWidth(30);
		$mySheet->getColumnDimension('C')->setWidth(35);
		$mySheet->getColumnDimension('D')->setWidth(17);
		$mySheet->getColumnDimension('E')->setWidth(18);
		$mySheet->getColumnDimension('F')->setWidth(40);
		$mySheet->getColumnDimension('G')->setWidth(30);
		$mySheet->getColumnDimension('H')->setWidth(23);
		$mySheet->getColumnDimension('I')->setWidth(26);
		$mySheet->getColumnDimension('J')->setWidth(17);
		$mySheet->getColumnDimension('K')->setWidth(26);
		$mySheet->getColumnDimension('L')->setWidth(13);
		$mySheet->getColumnDimension('M')->setWidth(15);
		$mySheet->getColumnDimension('N')->setWidth(10);
		$mySheet->getColumnDimension('O')->setWidth(16);
		$mySheet->getColumnDimension('P')->setWidth(14);
		$mySheet->getColumnDimension('Q')->setWidth(14);
		$mySheet->getColumnDimension('R')->setWidth(16);
		$mySheet->getColumnDimension('S')->setWidth(7);
		$mySheet->getColumnDimension('T')->setWidth(18);
		
		$addstring = "/imax/dealer";
		if($_SERVER['HTTP_HOST'] == "meghanab" || $_SERVER['HTTP_HOST'] == "rashmihk" || $_SERVER['HTTP_HOST'] == "archanaab")
			$addstring = "/saralimax-dealer";
	
		if($id == 'toexcel')
		{
			
			$query = 'select slno,dealerusername from inv_mas_dealer where slno = '.$userid.'';
			$fetchres = runmysqlqueryfetch($query);	
			$dealername = $fetchres['dealerusername'];		
			$localdate = datetimelocal('Ymd');
			$localtime = datetimelocal('His');
			$filebasename = "Customer-pinno-attached-details".$localdate."-".$localtime."-".strtolower($dealername).".xls";
			$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','excel_customer-pinno-attached_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
			$result = runmysqlquery($query1);	
			
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','143','".date('Y-m-d').' '.date('H:i:s')."','excel_customer_pinno_attached_report".'-'.strtolower($dealername)."')";
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
		elseif($id == 'view')
		{
			$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','view_customer-pinno-attached_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
			$result = runmysqlquery($query1);
			
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','142','".date('Y-m-d').' '.date('H:i:s')."','view_customer_pinno_attached_report')";
			$eventresult = runmysqlquery($eventquery);
			
			$localdate = datetimelocal('Ymd');
			$localtime = datetimelocal('His');
			$filename = "viewcustcardattachreport".$localdate."-".$localtime.".htm";
			$filepath = $_SERVER['DOCUMENT_ROOT'].$addstring.'/filecreated/'.$filename;
	
			$objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'HTML');
			$objWriter->save($filepath);
			
			// get contents of a file into a string
			$handle = fopen($filepath, "r");
			$contents = fread($handle, filesize($filepath));
			fclose($handle);
			
			echo($contents);
			unlink($filepath);
			exit;
		}
	}

	

?>
