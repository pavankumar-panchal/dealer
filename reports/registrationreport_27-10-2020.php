<?php
ini_set('memory_limit', '2048M');

include('../functions/phpfunctions.php');


//PHPExcel
//require_once '../phpgeneration/PHPExcel.php';

//PHPExcel_IOFactory
//require_once '../phpgeneration/PHPExcel/IOFactory.php';

$flag = $_POST['flag'];
if($flag == '')
{
	$url = '../home/index.php?a_link=registrationreport'; 
	header("location:".$url);
	exit;
}
elseif($flag == 'true')
{
	$userid = imaxgetcookie('dealeruserid');
	$id = $_GET['id'];
	$fromdate = $_POST['fromdate'];
	$todate = $_POST['todate'];
	$customerselection = $_POST['customerselection'];
	$searchtext = $_POST['searchtext'];
	$searchtextid = $_POST['searchtextid'];
	$geography = $_POST['geography'];
	$region = $_POST['region'];
	$state = $_POST['state'];
	$district = $_POST['district'];
	$dealer = $_POST['displayalldealer'];
	$groupon = $_POST['groupon'];
	$otheroptions = $_POST['otheroptions'];
	$card = $_POST['card'];
	$reregistration = $_POST['reregistration'];
	//$productcode = $_POST['productcode'];
	$billnumberfrom = $_POST['billnumberfrom'];
	$billnumberto = $_POST['billnumberto'];
	$multilogin = $_POST['multilogin'];
	$usagetype = $_POST['usagetype'];
	$purchasetype = $_POST['purchasetype'];
	$generatedby = $_POST['generatedby'];
	$chks = $_POST['productname'];
	for ($i = 0;$i < count($chks);$i++)
	{
		$c_value .= "'" . $chks[$i] . "'" ."," ;
	}
	$productslist = rtrim($c_value , ',');
	$value = str_replace('\\','',$productslist);

	//exit;
	
	#Added on 2nd Feb 2018
	$query = "select inv_mas_dealer.maindealers,inv_mas_dealer.dealerhead from inv_mas_dealer where inv_mas_dealer.slno = '".$userid."';";
	$fetch = runmysqlqueryfetch($query);
	$maindealers = $fetch['maindealers'];
	$dealerhead = $fetch['dealerhead'];
    #Ends on 2nd Feb 2018
    
		if($fromdate <> '' && $todate <> '')
		{
			$customerselectionpiece = ($customerselection == 'allcustomer')?(""):(" AND inv_customerproduct.customerreference = '".$searchtextid."' ");
			
			if($geography == "") { $geographypiece = ""; } 
			elseif($geography == "region") { $geographypiece = " AND inv_mas_customer.region = '".$region."' " ; }
			elseif($geography == "state") { $geographypiece = " AND inv_mas_district.statecode = '".$state."' " ; }
			elseif($geography == "district") { $geographypiece = " AND inv_mas_customer.district = '".$district."' " ; }
			
			
			if($card == "") {$cardpiece = ""; }
			elseif($card == 'withcard') {$cardpiece = " AND inv_customerproduct.cardid <> '' ";}
			elseif($card == 'withoutcard') {$cardpiece = " AND inv_customerproduct.cardid = '' ";}
			
			if($reregistration == "") {$reregistrationpiece = ""; }
			elseif($reregistration == 'yes') {$reregistrationpiece =  " AND inv_customerproduct.reregistration = 'yes' ";}
			elseif($reregistration == 'no') {$reregistrationpiece = " AND inv_customerproduct.reregistration = 'no' ";}
			
			$generatedbypiece = ($generatedby == "")?(""):(" AND inv_customerproduct.generatedby = '".$generatedby."' ");
			//$productcodepiece = ($productcode == "")?(""):(" AND inv_dealercard.productcode = '".$productcode."' ");
			$productcodepiece = ($chks == "")?(""):(" AND  inv_dealercard.productcode IN (".$value.") ");
			$usagetypepiece = ($usagetype == "")?(""):(" AND inv_dealercard.usagetype = '".$usagetype."' ");
			$purchasetypepiece = ($purchasetype == "")?(""):(" AND inv_dealercard.purchasetype = '".$purchasetype."' ");
			$dealertypepiece = ($dealer == "")?(""):(" AND inv_mas_dealer.slno = '".$dealer."' ");
			$billnumberpiece = ($billnumberfrom == "" || $billnumberto == "")?(""):(" AND inv_customerproduct.cusbillnumber BETWEEN '".$billnumberfrom."' AND '".$billnumberto."' ");
			
			if($groupon == 'product') { $grouponpiece = " inv_mas_product.productname "; }
			elseif($groupon == 'generatedby') { $grouponpiece = " inv_mas_users.fullname "; }
			elseif($groupon == 'dealer') { $grouponpiece = " inv_mas_dealer.businessname "; }	
			elseif($groupon == 'date') { $grouponpiece = " inv_customerproduct.date "; }	
			
			$query0 = "select * from inv_mas_dealer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_dealer.district where inv_mas_dealer.slno = '".$userid."'";
			$result0 = runmysqlqueryfetch($query0);
			$relyonexecutive = $result0['relyonexecutive'];
			$state = $result0['statecode'];
			if($relyonexecutive == 'no')
			{
				
				$query = "select *  from inv_mas_customer where inv_mas_customer.currentdealer = '".$userid."' order by businessname;";
				$result = runmysqlquery($query);
				if(mysqli_num_rows($result) == 0)
				{
					$dealerpiece = " where";
				}
				else
		
					$dealerpiece = " where inv_mas_dealer.slno = '".$userid."'";
			}
			else
			{
				if(($_POST['dealerid'] == 'all') || ($_POST['dealerid'] == ''))
				{
					$userid = imaxgetcookie('dealeruserid');
				}
				else
				$userid = $_POST['dealerid'];
				$query = "select * from inv_mas_customer left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode where inv_mas_district.districtcode in( select districtcode from inv_districtmapping where dealerid = '".$userid."') ;";
					$result = runmysqlquery($query);
					if(mysqli_num_rows($result) == 0)
					{
						if(($_POST['dealerid'] == 'all') )
						{
							$dealerpiece = " where inv_mas_district.statecode = '".$state."'";
						}
						else
						{
							$dealerpiece = " where inv_mas_dealer.slno = '".$userid."'";
						}
					}
					else
					{
						if(($_POST['dealerid'] == 'all') )
						{
							$dealerpiece = " LEFT JOIN inv_districtmapping on inv_mas_customer.district = inv_districtmapping.districtcode WHERE  inv_mas_customer.district is not null and inv_districtmapping.dealerid =  '".$userid."'";
						}
						else
						{
							$dealerpiece = " where inv_mas_customer.currentdealer = '".$userid."'";
						}
					}
			}
			
			$query = "SELECT distinct inv_mas_customer.businessname as customername, 
		 inv_customerproduct.cardid as cardid, 
		inv_customerproduct.date as `date`, inv_customerproduct.computerid as computerid, 
		inv_customerproduct.softkey as softkey, inv_mas_dealer.slno as slno,inv_mas_users.fullname as userid, inv_mas_dealer.businessname as dealername, inv_customerproduct.billnumber as cusbillnumber,  inv_customerproduct.billamount as billamount,  inv_customerproduct.remarks as remarks, inv_mas_product.productname as productname,inv_mas_scheme.schemename from inv_customerproduct  LEFT JOIN inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno
		   LEFT JOIN inv_mas_users on inv_mas_users.slno = inv_customerproduct.generatedby  LEFT JOIN inv_mas_dealer ON inv_mas_dealer.slno = inv_customerproduct.dealerid  LEFT JOIN inv_dealercard ON inv_dealercard.cardid = inv_customerproduct.cardid  LEFT JOIN inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3) LEFT JOIN inv_mas_scheme on inv_mas_scheme.slno =inv_dealercard.scheme left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district ".$dealerpiece." and inv_customerproduct.date BETWEEN '".changedateformat($fromdate)."' AND '".changedateformat($todate)."'    ".$geographypiece.$reregistrationpiece.$cardpiece.$customerselectionpiece.$generatedbypiece.$productcodepiece.$usagetypepiece.$purchasetypepiece.$billnumberpiece.$dealertypepiece."  ORDER BY `date` desc, ".$grouponpiece.";";
		
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
		//Apply style for header Row
		$mySheet->getStyle('A3:M3')->applyFromArray($styleArray);
		
		//Merge the cell
		$mySheet->mergeCells('A1:M1');
		$mySheet->mergeCells('A2:M2');
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
					->setCellValue('A2', 'Product Registration Details');
		$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
		$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
		$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
	
	
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A3', 'Sl No')
					->setCellValue('B3', 'Product')
					->setCellValue('C3', 'Customer')
					->setCellValue('D3', 'Pin Serial Number')
					->setCellValue('E3', 'Date')
					->setCellValue('F3', 'Computer ID')
					->setCellValue('G3', 'Soft Key')
					->setCellValue('H3', 'Generated By')
					->setCellValue('I3', 'Dealer')
					->setCellValue('J3', 'Bill Number')
					->setCellValue('K3', 'Billed Amount')
					->setCellValue('L3', 'Remarks')
					->setCellValue('M3', 'Scheme');
					
		$j =4;
		$slno = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$slno++;
			$mySheet->setCellValue('A' . $j,$slno)
					->setCellValue('B' . $j,$fetch['productname'])
					->setCellValue('C' . $j,$fetch['customername'])
					->setCellValue('D' . $j,$fetch['cardid'])
					->setCellValue('E' . $j,changedateformat($fetch['date']))
					->setCellValue('F' . $j,$fetch['computerid'])
					->setCellValue('G' . $j,$fetch['softkey'])
					->setCellValue('H' . $j,$fetch['userid'])
					->setCellValue('I' . $j,$fetch['dealername'])
					->setCellValue('J' . $j,$fetch['cusbillnumber'])
					->setCellValue('K' . $j,$fetch['billamount'])
					->setCellValue('L' . $j,$fetch['remarks'])
					->setCellValue('M' . $j,$fetch['schemename']);
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
		$mySheet->getColumnDimension('E')->setWidth(13);
		$mySheet->getColumnDimension('F')->setWidth(16);
		$mySheet->getColumnDimension('G')->setWidth(15);
		$mySheet->getColumnDimension('H')->setWidth(18);
		$mySheet->getColumnDimension('I')->setWidth(43);
		$mySheet->getColumnDimension('J')->setWidth(12);
		$mySheet->getColumnDimension('K')->setWidth(15);
		$mySheet->getColumnDimension('L')->setWidth(39);
		$mySheet->getColumnDimension('M')->setWidth(26);
		
		$addstring = "/imax/dealer";
		if($_SERVER['HTTP_HOST'] == "rashmihk" || $_SERVER['HTTP_HOST'] == "meghanab" || $_SERVER['HTTP_HOST'] == "archanaab")
			$addstring = "/saralimax-dealer";
	
		if($id == 'toexcel')
		{
			$query = 'select slno,dealerusername from inv_mas_dealer where slno = '.$userid.'';
			$fetchres = runmysqlqueryfetch($query);
			$dealername = $fetchres['dealerusername'];		
			$localdate = datetimelocal('Ymd');
			$localtime = datetimelocal('His');
			$filebasename = "RegistrationReport".$localdate."-".$localtime."-".strtolower($dealername).".xls";
			$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','excel_registration_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
			$result = runmysqlquery($query1);
			
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','141','".date('Y-m-d').' '.date('H:i:s')."','excel_registration_report".'-'.strtolower($dealername)."')";
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
			$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','view_registration_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
			$result = runmysqlquery($query1);
			
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','140','".date('Y-m-d').' '.date('H:i:s')."','view_registration_report')";
			$eventresult = runmysqlquery($eventquery);
	
			$localdate = datetimelocal('Ymd');
			$localtime = datetimelocal('His');
			$filename = "viewregistrationreport".$localdate."-".$localtime.".htm";
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
