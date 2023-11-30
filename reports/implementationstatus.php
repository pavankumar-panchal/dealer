<?php
ini_set('memory_limit', '2048M');
include('../functions/phpfunctions.php');
require_once('../pdfbillgeneration/tcpdf.php');
if(imaxgetcookie('dealeruserid')<> '') 
$userid = imaxgetcookie('dealeruserid');
else
{ 
	echo('Thinking to redirect');
	exit;
}
require_once '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
//PHPExcel 
//require_once '../phpgeneration/PHPExcel.php';

//PHPExcel_IOFactory
//require_once '../phpgeneration/PHPExcel/IOFactory.php';


$fromdate = $_POST['fromdate'];
$todate = $_POST['todate'];
$salesperson = $_POST['salesperson'];
$productcode = $_POST['productcode'];
$implementer = $_POST['implementer'];
$branchapproval = $_POST['branchapproval'];
$chks = $_POST['productname'];
for ($i = 0;$i < count((array)$chks);$i++)
{
	$c_value .= "'" . $chks[$i] . "'" ."," ;
}
$productslist = rtrim($c_value , ',');
$value = str_replace('\\','',$productslist);

$coordinatorapproval = $_POST['coordinatorapproval'];
$customization = $_POST['customization'];
$advcollected = $_POST['advcollected'];
$attendance = $_POST['attendance'];
$commission = $_POST['commission'];
$webimpl = $_POST['webimpl'];
$icccolllected = $_POST['icccolllected'];

$checkvalue = $_POST['checkvalue'];

if(in_array('regionwise', $checkvalue, true))
	$regionwise = 'regionwise';
else
	$regionwise = '';
	
if(in_array('customerwise', $checkvalue, true))
	$customerwise = 'customerwise';
else
	$customerwise = '';	
	
if(in_array('branchwise', $checkvalue, true))
	$branchwise = 'branchwise';
else
	$branchwise = '';
	
if(in_array('implementerwise', $checkvalue, true))
	$implementerwise = 'implementerwise';
else
	$implementerwise = '';
	
if(in_array('salespersonwise', $checkvalue, true))
	$salespersonwise = 'salespersonwise';
else
	$salespersonwise = '';
	


//Get the Status
$summarize = $_POST['summarize'];
$countsummarize = count($summarize);

for($i = 0; $i<$countsummarize; $i++)
{
	if($i < ($countsummarize-1))
			$appendor = 'or'.' ';
		else
			$appendor = '';
	switch($summarize[$i])
	{
		case 'status1' :
		{
			$statuspiece = " imp_implementation.branchapproval = 'no' AND imp_implementation.branchreject = 'yes' AND imp_implementation.implementationstatus = 'pending' ";
		}
		break;
		case 'status2' :
		{
			$statuspiece = " imp_implementation.branchapproval = 'no' AND imp_implementation.branchreject = 'no' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'no' AND  imp_implementation.implementationstatus = 'pending'";
		}
		break;
		case 'status3' :
		{
			$statuspiece = " imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'no' AND imp_implementation.implementationstatus = 'pending' ";
		}
		break;
		case 'status4' :
		{
			$statuspiece = " imp_implementation.branchapproval = 'no' AND imp_implementation.coordinatorreject = 'yes' AND imp_implementation.coordinatorapproval = 'no' AND imp_implementation.implementationstatus = 'pending' ";
		}
		break;
		case 'status5' :
		{
			$statuspiece = " imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'pending' ";
		}
		break;
		case 'status6' :
		{
			$statuspiece = " imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'assigned' ";
		}
		break;
		case 'status7' :
		{
			$statuspiece = " imp_implementation.branchapproval = 'yes'  AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'progess' ";
		}
		break;
		case 'status8' :
		{
			$statuspiece = " imp_implementation.branchapproval = 'yes'  AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'completed' ";
		}
		break;
	}
	$finalstatuslist .= ''.'('.$statuspiece.')'.'  '.$appendor.'';
	
}
if($finalstatuslist != '')
{
	$finalliststatus = ' AND'.'('.$finalstatuslist.')';
}
else
{
	$finalliststatus = "";
}

$branchpiece = ($branch == "")?(""):(" AND inv_mas_branch.slno = '".$branch."'   ");
$regionpiece = ($region == "")?(""):(" AND inv_mas_region.slno = '".$region."' ");

if($branchapproval == "")
{
	$branchapprovalpiece = "";
}
elseif($branchapproval == "pending")
{
	$branchapprovalpiece = " AND imp_implementation.branchapproval = 'no' AND imp_implementation.branchreject = 'no' ";
}
elseif($branchapproval == "rejected")
{
	$branchapprovalpiece = " AND imp_implementation.branchapproval = 'no' AND imp_implementation.branchreject = 'yes'";
}
elseif($branchapproval == "approved")
{
	$branchapprovalpiece = " AND imp_implementation.branchapproval = 'yes' AND imp_implementation.branchreject = 'no'";
}

if($coordinatorapproval == "")
{
	$coordinatorapprovalpiece = "";
}
elseif($coordinatorapproval == "pending")
{
	$coordinatorapprovalpiece = " AND imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'no'";
}
elseif($coordinatorapproval == "rejected")
{
	$coordinatorapprovalpiece = " AND imp_implementation.branchapproval = 'no' AND imp_implementation.coordinatorreject = 'yes' AND imp_implementation.coordinatorapproval = 'no' ";
}
elseif($coordinatorapproval == "approved")
{
	$coordinatorapprovalpiece = " AND imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' ";
}


$branchapprovalpiece = ($branchapproval == "")?(""):(" AND imp_implementation.branchapproval = '".$branchapproval."' ");
$coordinatorapprovalpiece = ($coordinatorapproval == "")?(""):(" AND imp_implementation.coordinatorapproval = '".$coordinatorapproval."' ");
$customizationpiece = ($customization == "")?(""):(" AND imp_implementation.customizationapplicable = '".$customization."' ");
$advcollectedpiece = ($advcollected == "")?(""):(" AND imp_implementation.advancecollected = '".$advcollected."' ");
$attendancepiece = ($attendance == "")?(""):(" AND imp_implementation.attendanceapplicable = '".$attendance."' ");
$commissionpiece = ($commission == "")?(""):(" AND imp_implementation.commissionapplicable = '".$commission."' ");
$salespersonpiece = ($salesperson == "")?(""):(" AND imp_implementation.dealerid = '".$salesperson."' ");
// $productcodepiece = ($chks == "")?(""):(" AND  (inv_mas_product.productcode IN (".$value.")  or imp_cfentries.productcode IN (".$value."))");
$productcodepiece = ($chks == "")?(""):(" AND  (imp_implementation.productcode IN (".$value."))");
$webimplpiece = ($webimpl == "")?(""):(" AND imp_implementation.webimplemenationapplicable = '".$webimpl."' ");
$icccolllectedpiece = ($icccolllected == "")?(""):(" AND imp_implementationdays.iccattachment = '".$icccolllected."' ");
$implementerpersonpiece = ($implementer == "")?(""):(" AND inv_mas_implementer.slno = '".$implementer."' ");
	
$query = "select * from inv_mas_dealer where slno = '".$userid."'";
$resultfetch = runmysqlqueryfetch($query);
$branchhead = $resultfetch['branchhead'];
$branch = $resultfetch['branch'];

if($branchhead == 'yes')
{
	$dealerpiece = " AND inv_mas_customer.branch = '".$branch."'";
}
else
{
	$dealerpiece = " AND inv_mas_dealer.slno = '".$userid."'";
}

	
	$query1 = "create temporary table implementationstatus Select distinct imp_implementation.slno,inv_mas_customer.businessname as customername,
	inv_mas_customer.customerid as customerid,inv_mas_district.districtname as districtname, inv_mas_state.statename as statename,
	imp_implementation.invoicenumber,inv_mas_region.category as regionname,imp_implementation.customerreference as custslno,
	inv_mas_branch.branchname as branchname, 
	inv_mas_implementer.businessname as implementername,
	inv_mas_dealer.businessname as salesperson,
	imp_implementation.branchapproval,
	imp_implementation.branchreject,
	imp_implementation.implementationstatus,
	imp_implementation.coordinatorapproval,imp_implementation.coordinatorreject,
	imp_implementation.customizationapplicable,imp_implementation.advancecollected,imp_implementation.advanceremarks,
	imp_implementation.advancesnotcollectedremarks,imp_implementation.attendanceapplicable,
	imp_implementation.attendanceremarks,imp_implementation.commissionapplicable,imp_implementation.webimplemenationapplicable,
	imp_implementation.webimplemenationremarks,left(imp_implementation.createddatetime,10) as createddate,imp_implementationdays.iccattachment,IFNULL(inv_mas_region.slno,'') as regionslno,IFNULL(inv_mas_dealer.slno,'') as dealerid,IFNULL(inv_mas_branch.slno,'') as branchslno,IFNULL(inv_mas_implementer.slno,'') as implementerslno,
	imp_implementationdays.visiteddate as noofdays,imp_mas_implementationtype.imptype,imp_implementation.implementationstatus as impstatus,left(imp_implementation.createddatetime,10) as requestdate,left(imp_implementation.branchapprovaldatetime,10) as branchappdate,
	left(imp_implementation.coordinatorappdatetime,10) as coordinatorappdate,left(imp_implementation.assignimpldatetime,10) as assignimpldate,left(dummy.visiteddate,10) as completeddate
	from imp_implementation 
	left join inv_invoicenumbers on inv_invoicenumbers.invoiceno = imp_implementation.invoicenumber 
	left join inv_dealercard  on inv_dealercard.slno = imp_implementation.dealerid 
	left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
	left join imp_mas_implementationtype on imp_mas_implementationtype.slno = imp_implementation.implementationtype
	left join inv_mas_customer on inv_mas_customer.slno = imp_implementation.customerreference 
	left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode 
	left join inv_mas_state on inv_mas_district.statecode = inv_mas_state.slno 
	left join inv_mas_dealer on imp_implementation.dealerid = inv_mas_dealer.slno 
	left join inv_mas_region on inv_mas_region.slno = inv_mas_dealer.region 
	left join inv_mas_branch on inv_mas_branch.slno = inv_mas_dealer.branch 
	left join inv_mas_implementer on inv_mas_implementer.slno = imp_implementation.assignimplemenation 
	left join (select imp_implementationdays.impref as impref, IFNULL(count(imp_implementationdays.visiteddate),'') as visiteddate,imp_implementationdays.iccattachment from imp_implementationdays group by imp_implementationdays.impref) as  imp_implementationdays on imp_implementationdays.impref = imp_implementation.slno 
	left join (select slno,min(visiteddate) as visiteddate,impref from imp_implementationdays  where iccattachment = 'yes' group by impref)as dummy on dummy.impref = imp_implementation.slno 
	where  
	left(imp_implementation.createddatetime,10) BETWEEN '".changedateformat($fromdate)."' AND '".changedateformat($todate)."' ".$finalliststatus.$dealerpiece.$branchapprovalpiece.$coordinatorapprovalpiece.$advcollectedpiece.$attendancepiece.$commissionpiece.$salespersonpiece.$productcodepiece.$webimplpiece.$customizationpiece.$icccolllectedpiece.$implementerpersonpiece."   order by  inv_mas_customer.businessname,imp_implementation.createddatetime;";
	$result1 = runmysqlquery($query1);
	// left join imp_cfentries on imp_cfentries.invoiceno = imp_implementation.invoicenumber
	
		// Create new PHPExcel object
		$objPHPExcel= new Spreadsheet();
		
		$pageindex = 0;
		
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
										
		if($customerwise == "customerwise")
		{
			//Set Active Sheet	
			$mySheet = $objPHPExcel->getActiveSheet();
			
			//Set the worksheet name
			$mySheet->setTitle('Customer List');
				
			
			//Apply style for header Row
			$mySheet->getStyle('A3:AD3')->applyFromArray($styleArray);
			
			//Merge the cell
			$mySheet->mergeCells('A1:AD1');
			$mySheet->mergeCells('A2:AD2');
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
						->setCellValue('A2', 'Implementation Status Report');
			$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
			$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
			$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
			
			
			//Fille contents for Header Row
			$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A3', 'Sl No')
					->setCellValue('B3', 'Customer ID')
					->setCellValue('C3', 'Company Name')
					->setCellValue('D3', 'District')
					->setCellValue('E3', 'State')
					->setCellValue('F3', 'Contact Persons')
					->setCellValue('G3', 'Cell Nos')
					->setCellValue('H3', 'Email IDs')
					->setCellValue('I3', 'Phone Nos')
					->setCellValue('J3', 'Invoice No')
					->setCellValue('K3', 'Region')
					->setCellValue('L3', 'Branch')
					->setCellValue('M3', 'Implementer')
					->setCellValue('N3', 'Sales Person')
					->setCellValue('O3', 'Creation Date')
					->setCellValue('P3', 'Customization')
					->setCellValue('Q3', 'Advance Collected')
					->setCellValue('R3', 'Attendance Integration')
					->setCellValue('S3', 'Commission Payable')
					->setCellValue('T3', 'Web Implementation')
					->setCellValue('U3', 'Branch head Approval')
					->setCellValue('V3', 'Coordinator Approval')
					->setCellValue('W3', 'Implementation Status')
					->setCellValue('X3', 'No of Days')
					->setCellValue('Y3', 'Implementation Status')
					->setCellValue('Z3', 'Request Date')
					->setCellValue('AA3', 'Branch Head Approved date')
					->setCellValue('AB3', 'Co-ordinator Approved date')
					->setCellValue('AC3', 'Assigned date')
					->setCellValue('AD3', 'Completed date');
					
			
			$j = 4;
			$slno = 0;
			$query2 = "Select * from implementationstatus order by  implementationstatus.customername;";
			$result2 = runmysqlquery($query2);
			while($fetch = mysqli_fetch_array($result2))
			{
				$query3 = "select trim(both ',' from GROUP_CONCAT(inv_contactdetails.contactperson)) as contactperson, trim(both ',' from GROUP_CONCAT(inv_contactdetails.cell)) as cell, trim(both ',' from GROUP_CONCAT(inv_contactdetails.phone)) as phone, trim(both ',' from GROUP_CONCAT(inv_contactdetails.emailid)) as emailid from inv_contactdetails left join inv_mas_customer on inv_contactdetails.`customerid` = inv_mas_customer.slno where inv_contactdetails.customerid ='".$fetch['custslno']."' ";
				$fetch3 = runmysqlqueryfetch($query3);

				$slno++;
				if($fetch['implementername'] == '')
					$implementername = '';
				else
					$implementername = $fetch['implementername'];
				if($fetch['salesperson'] == '')
					$salesperson = '';
				else
					$salesperson = $fetch['salesperson'];
				if($fetch['noofdays'] == '' || $fetch['noofdays'] == '0')
					$noofdays = ' ';
				else
					$noofdays = $fetch['noofdays'];
				$mySheet->setCellValue('A' . $j,$slno)
						->setCellValue('B' . $j,cusidcombine($fetch['customerid']))
						->setCellValue('C' . $j,$fetch['customername'])
						->setCellValue('D' . $j,$fetch['districtname'])
						->setCellValue('E' . $j,$fetch['statename'])
						->setCellValue('F' . $j,$fetch3['contactperson'])
						->setCellValue('G' . $j,$fetch3['cell'])
						->setCellValue('H' . $j,$fetch3['emailid'])
						->setCellValue('I' . $j,$fetch3['phone'])
						->setCellValue('J' . $j,$fetch['invoicenumber'])
						->setCellValue('K' . $j,$fetch['regionname'])
						->setCellValue('L' . $j,$fetch['branchname'])
						->setCellValue('M' . $j,$implementername)
						->setCellValue('N' . $j,$salesperson)
						->setCellValue('O' . $j,changedateformat($fetch['createddate']))
						->setCellValue('P' . $j,strtoupper($fetch['customizationapplicable']))
						->setCellValue('Q' . $j,strtoupper($fetch['advancecollected']))
						->setCellValue('R' . $j,strtoupper($fetch['attendanceapplicable']))
						->setCellValue('S' . $j,strtoupper($fetch['commissionapplicable']))
						->setCellValue('T' . $j,strtoupper($fetch['webimplemenationapplicable']))
						->setCellValue('U' . $j,strtoupper($fetch['branchapproval']))
						->setCellValue('V' . $j,strtoupper($fetch['coordinatorapproval']))
						->setCellValue('W' . $j,strtoupper($fetch['implementationstatus']))
						->setCellValue('X' . $j,$noofdays)
						->setCellValue('Y' . $j,strtoupper($fetch['impstatus']))
						->setCellValue('Z' . $j,strtoupper($fetch['requestdate']))
						->setCellValue('AA' . $j,strtoupper($fetch['branchappdate']))
						->setCellValue('AB' . $j,strtoupper($fetch['coordinatorappdate']))
						->setCellValue('AC' . $j,strtoupper($fetch['assignimpldate']))
						->setCellValue('AD' . $j,strtoupper($fetch['completeddate']));
						$j++;
						
			}
			
				
				//Get the last cell reference
				$highestRow = $mySheet->getHighestRow(); 
				$highestColumn = $mySheet->getHighestColumn(); 
				$myLastCell = $highestColumn.$highestRow;
				
				//Deine the content range
				$myDataRange = 'A4:'.$myLastCell;
				if(mysqli_num_rows($result2) <> 0)
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
				$mySheet->getColumnDimension('N')->setWidth(15);
				$mySheet->getColumnDimension('O')->setWidth(10);
				$mySheet->getColumnDimension('P')->setWidth(12);
				$mySheet->getColumnDimension('Q')->setWidth(20);
				$mySheet->getColumnDimension('R')->setWidth(20);
				$mySheet->getColumnDimension('S')->setWidth(20);
				$mySheet->getColumnDimension('T')->setWidth(20);
				$mySheet->getColumnDimension('U')->setWidth(20);
				$mySheet->getColumnDimension('V')->setWidth(20);
				$mySheet->getColumnDimension('W')->setWidth(20);
				$mySheet->getColumnDimension('X')->setWidth(20);
				$mySheet->getColumnDimension('Y')->setWidth(20);
				$mySheet->getColumnDimension('Z')->setWidth(20);
				$mySheet->getColumnDimension('AA')->setWidth(20);
				$mySheet->getColumnDimension('AB')->setWidth(20);
				$mySheet->getColumnDimension('AC')->setWidth(20);
				$mySheet->getColumnDimension('AD')->setWidth(20);
				$pageindex++;
		
				//Begin with Worksheet 2 (Summary)
				$objPHPExcel->createSheet();
				$objPHPExcel->setActiveSheetIndex($pageindex);
			
		}
		// if($regionwise == "regionwise")
		// {
			
		// 	//Set Active Sheet	
		// 	$mySheet = $objPHPExcel->getActiveSheet();
			
		// 	$mySheet->setTitle('Region wise');
				
		// 	//Merge the cell
		// 	$mySheet->mergeCells('A1:K1');
		// 	$objPHPExcel->setActiveSheetIndex($pageindex)
		// 				->setCellValue('A1', 'Region wise Status');
		// 	$mySheet->getStyle('A1')->getFont()->setSize(12); 	
		// 	$mySheet->getStyle('A1')->getFont()->setBold(true); 
		// 	$mySheet->getStyle('A1')->getAlignment()->setWrapText(true);
			
		// 	//Begin writing Region wise
		// 	$currentrow = 2;
			
			
		// 	$currentrow++;
		// 	//Set table headings
		// 	$mySheet->setCellValue('A'.$currentrow,'Slno')
		// 			->setCellValue('B'.$currentrow,'Region')
		// 			->setCellValue('C'.$currentrow,'Awaiting Branch Head Approval')
		// 			->setCellValue('D'.$currentrow,'Fowarded back to Sales Person')
		// 			->setCellValue('E'.$currentrow,'Awaiting Co-ordinator Approval')
		// 			->setCellValue('F'.$currentrow,'Fowarded back to Branch Head')
		// 			->setCellValue('G'.$currentrow,'Implementation, Yet to be Assigned')
		// 			->setCellValue('H'.$currentrow,'Assigned For Implementation')
		// 			->setCellValue('I'.$currentrow,'Implementation in progess')
		// 			->setCellValue('J'.$currentrow,'Implementation Completed')
		// 			->setCellValue('K'.$currentrow,'Total');
					
					
		// 	//Apply style for header Row
		// 	$mySheet->getStyle('A'.$currentrow.':K'.$currentrow)->applyFromArray($styleArray);
			
		// 	//Set table data
		// 	$currentrow++;
		// 	$regionquery = 'select distinct regionslno as slno,regionname from implementationstatus  order by regionname';
		// 	$fetchregion =  runmysqlquery($regionquery);
		// 	$databeginrow = $currentrow;
		// 	$slnocount = 0;
		// 	while($fetchresult = mysqli_fetch_array($fetchregion))
		// 	{
		// 		$slnocount++;
		// 		//Insert data
		// 		$mySheet->setCellValue('A'.$currentrow,$slnocount);
		// 		$mySheet->setCellValue('B'.$currentrow,$fetchresult['regionname']);
		// 		//Fetch With Status1 Data
		// 		$query2 = "select  if(count(implementationstatus.slno or null) = 0,'',count(implementationstatus.slno or null)) as count from implementationstatus where implementationstatus.branchapproval = 'no' AND implementationstatus.coordinatorreject = 'no' 
		// 		AND implementationstatus.coordinatorapproval = 'no' AND implementationstatus.branchreject = 'no' AND implementationstatus.implementationstatus = 'pending' and implementationstatus.regionslno = '".$fetchresult['slno']."'";
		// 		$row_data = runmysqlqueryfetch($query2);
				
		// 		//Fetch With Status2 Data
		// 		$query3 = "select  if(count(implementationstatus.slno or null) = 0,'',count(implementationstatus.slno or null)) as count from implementationstatus where implementationstatus.branchapproval = 'no' AND implementationstatus.branchreject = 'yes' AND implementationstatus.implementationstatus = 'pending' and implementationstatus.regionslno = '".$fetchresult['slno']."'";
		// 		$row_data1 = runmysqlqueryfetch($query3);
				
		// 		//Fetch With Status3 Data
		// 		$query4 = "select  if(count(implementationstatus.slno or null) = 0,'',count(implementationstatus.slno or null)) as count from implementationstatus where implementationstatus.branchapproval = 'yes' AND implementationstatus.coordinatorreject = 'no' AND implementationstatus.coordinatorapproval = 'no' AND implementationstatus.implementationstatus = 'pending' and implementationstatus.regionslno = '".$fetchresult['slno']."'";
		// 		$row_data2 = runmysqlqueryfetch($query4);
				
		// 		//Fetch With Status4 Data
		// 		$query5 = "select   if(count(implementationstatus.slno or null) = 0,'',count(implementationstatus.slno or null)) as count from implementationstatus 
		// 		where implementationstatus.branchapproval = 'no' AND implementationstatus.coordinatorreject = 'yes' AND implementationstatus.coordinatorapproval = 'no' AND implementationstatus.implementationstatus = 'pending' and implementationstatus.regionslno = '".$fetchresult['slno']."'";
		// 		$row_data3 = runmysqlqueryfetch($query5);
				
		// 		//Fetch With Status5 Data
		// 		$query6 = "select  if(count(implementationstatus.slno or null) = 0,'',count(implementationstatus.slno or null)) as count from implementationstatus where implementationstatus.branchapproval = 'yes' AND implementationstatus.coordinatorreject = 'no' AND implementationstatus.coordinatorapproval = 'yes' AND implementationstatus.implementationstatus = 'pending' and implementationstatus.regionslno = '".$fetchresult['slno']."'";
		// 		$row_data4 = runmysqlqueryfetch($query6);
				
		// 		//Fetch With Status6 Data
		// 		$query7 = "select  if(count(implementationstatus.slno or null) = 0,'',count(implementationstatus.slno or null)) as count from implementationstatus where implementationstatus.branchapproval = 'yes' AND implementationstatus.coordinatorreject = 'no' AND implementationstatus.coordinatorapproval = 'yes' AND implementationstatus.implementationstatus = 'assigned' and implementationstatus.regionslno = '".$fetchresult['slno']."'";
		// 		$row_data5 = runmysqlqueryfetch($query7);
				
		// 		//Fetch With Status7 Data
		// 		$query8 = "select  if(count(implementationstatus.slno or null) = 0,'',count(implementationstatus.slno or null)) as count from implementationstatus where implementationstatus.branchapproval = 'yes' AND implementationstatus.coordinatorreject = 'no' AND implementationstatus.coordinatorapproval = 'yes' AND implementationstatus.implementationstatus = 'progess' and implementationstatus.regionslno = '".$fetchresult['slno']."'";
		// 		$row_data6 = runmysqlqueryfetch($query8);
				
		// 		//Fetch With Status8 Data
		// 		$query9 = "select  if(count(implementationstatus.slno or null) = 0,'',count(implementationstatus.slno or null)) as count from implementationstatus	where implementationstatus.branchapproval = 'yes' AND implementationstatus.coordinatorreject = 'no' AND implementationstatus.coordinatorapproval = 'yes' AND implementationstatus.implementationstatus = 'completed' and implementationstatus.regionslno = '".$fetchresult['slno']."'";
		// 		$row_data7 = runmysqlqueryfetch($query9);
				
				
		// 		$mySheet->setCellValue('C'.$currentrow,$row_data['count'])
		// 				->setCellValue('D'.$currentrow,$row_data1['count'])
		// 				->setCellValue('E'.$currentrow,$row_data2['count'])
		// 				->setCellValue('F'.$currentrow,$row_data3['count'])
		// 				->setCellValue('G'.$currentrow,$row_data4['count'])
		// 				->setCellValue('H'.$currentrow,$row_data5['count'])
		// 				->setCellValue('I'.$currentrow,$row_data6['count'])
		// 				->setCellValue('J'.$currentrow,$row_data7['count'])
		// 				->setCellValue('K'.$currentrow,"=SUM(C".$currentrow.":J".$currentrow.")");
		// 		$currentrow++;
				
		// 		//set the default width for column
		// 		$mySheet->getColumnDimension('A')->setWidth(10);
		// 		$mySheet->getColumnDimension('B')->setWidth(15);
		// 		$mySheet->getColumnDimension('C')->setWidth(27);
		// 		$mySheet->getColumnDimension('D')->setWidth(27);
		// 		$mySheet->getColumnDimension('E')->setWidth(27);
		// 		$mySheet->getColumnDimension('F')->setWidth(27);
		// 		$mySheet->getColumnDimension('G')->setWidth(27);
		// 		$mySheet->getColumnDimension('H')->setWidth(27);
		// 		$mySheet->getColumnDimension('I')->setWidth(27);
		// 		$mySheet->getColumnDimension('J')->setWidth(27);
		// 		$mySheet->getColumnDimension('K')->setWidth(13);
		// 	}
			
		// 	//Insert Total
		// 	$mySheet->setCellValue('B'.$currentrow,'Total')
		// 			->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
		// 			->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
		// 			->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
		// 			->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
		// 			->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")")
		// 			->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow-1).")")
		// 			->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow-1).")")
		// 			->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow-1).")")
		// 			->setCellValue('K'.$currentrow,"=SUM(K".$databeginrow.":K".($currentrow-1).")");
		// 	$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
		// 	$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
		// 	$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
		// 	$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
		// 	$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
		// 	$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
		// 	$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
		// 	$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
		// 	$mySheet->getCell('K'.$currentrow)->getCalculatedValue();
	
		// 	//Apply style for content Row
		// 	$mySheet->getStyle('A'.$databeginrow.':K'.$currentrow)->applyFromArray($styleArrayContent);
		// 	$pageindex++;
		
		// 	//Begin with Worksheet 2 (Summary)
		// 	$objPHPExcel->createSheet();
		// 	$objPHPExcel->setActiveSheetIndex($pageindex);
			
		// }
		
		if($branchwise == "branchwise")
		{
			
			//Set Active Sheet	
			$mySheet = $objPHPExcel->getActiveSheet();
			
			$mySheet->setTitle('Branch wise');
				
			//Merge the cell
			$mySheet->mergeCells('A1:K1');
			$objPHPExcel->setActiveSheetIndex($pageindex)
						->setCellValue('A1', 'Branch wise Status');
			$mySheet->getStyle('A1')->getFont()->setSize(12); 	
			$mySheet->getStyle('A1')->getFont()->setBold(true); 
			$mySheet->getStyle('A1')->getAlignment()->setWrapText(true);
			
			//Begin writing Region wise
			$currentrow = 2;
			
			
			$currentrow++;
			//Set table headings
			$mySheet->setCellValue('A'.$currentrow,'Slno')
					->setCellValue('B'.$currentrow,'Branch')
					->setCellValue('C'.$currentrow,'Awaiting Branch Head Approval')
					->setCellValue('D'.$currentrow,'Fowarded back to Sales Person')
					->setCellValue('E'.$currentrow,'Awaiting Co-ordinator Approval')
					->setCellValue('F'.$currentrow,'Fowarded back to Branch Head')
					->setCellValue('G'.$currentrow,'Implementation, Yet to be Assigned')
					->setCellValue('H'.$currentrow,'Assigned For Implementation')
					->setCellValue('I'.$currentrow,'Implementation in progess')
					->setCellValue('J'.$currentrow,'Implementation Completed')
					->setCellValue('K'.$currentrow,'Total');
					
					
			//Apply style for header Row
			$mySheet->getStyle('A'.$currentrow.':K'.$currentrow)->applyFromArray($styleArray);
			
			//Set table data
			$currentrow++;
			$regionquery = 'select distinct branchslno as slno,branchname from implementationstatus  order by branchname';
			$fetchregion =  runmysqlquery($regionquery);
			$databeginrow = $currentrow;
			$slnocount = 0;
			while($fetchresult = mysqli_fetch_array($fetchregion))
			{
				$slnocount++;
				//Insert data
				$mySheet->setCellValue('A'.$currentrow,$slnocount);
				$mySheet->setCellValue('B'.$currentrow,$fetchresult['branchname']);
				//Fetch With Status1 Data
				$query2 = "select if(count(implementationstatus.slno or null) = 0,'0',count(implementationstatus.slno or null)) as count from implementationstatus 	where implementationstatus.branchapproval = 'no' AND implementationstatus.coordinatorreject = 'no' 
				AND implementationstatus.coordinatorapproval = 'no' AND implementationstatus.branchreject = 'no' AND implementationstatus.implementationstatus = 'pending' and implementationstatus.branchslno = '".$fetchresult['slno']."'";
				$row_data = runmysqlqueryfetch($query2);
				
				//Fetch With Status2 Data
				$query3 = "select   if(count(implementationstatus.slno or null) = 0,'0',count(implementationstatus.slno or null)) as count from implementationstatus 
				where implementationstatus.branchapproval = 'no' AND implementationstatus.branchreject = 'yes' AND implementationstatus.implementationstatus = 'pending' and implementationstatus.branchslno = '".$fetchresult['slno']."'";
				$row_data1 = runmysqlqueryfetch($query3);
				
				//Fetch With Status3 Data
				$query4 = "select   if(count(implementationstatus.slno or null) = 0,'0',count(implementationstatus.slno or null)) as count from implementationstatus 
				where implementationstatus.branchapproval = 'yes' AND implementationstatus.coordinatorreject = 'no' AND implementationstatus.coordinatorapproval = 'no' AND implementationstatus.implementationstatus = 'pending' and implementationstatus.branchslno = '".$fetchresult['slno']."'";
				$row_data2 = runmysqlqueryfetch($query4);
				
				//Fetch With Status4 Data
				$query5 = "select   if(count(implementationstatus.slno or null) = 0,'0',count(implementationstatus.slno or null)) as count from implementationstatus 
				where implementationstatus.branchapproval = 'no' AND implementationstatus.coordinatorreject = 'yes' AND implementationstatus.coordinatorapproval = 'no' AND implementationstatus.implementationstatus = 'pending' and implementationstatus.branchslno = '".$fetchresult['slno']."'";
				$row_data3 = runmysqlqueryfetch($query5);
				
				//Fetch With Status5 Data
				$query6 = "select   if(count(implementationstatus.slno or null) = 0,'0',count(implementationstatus.slno or null)) as count from implementationstatus 
				where implementationstatus.branchapproval = 'yes' AND implementationstatus.coordinatorreject = 'no' AND implementationstatus.coordinatorapproval = 'yes' AND implementationstatus.implementationstatus = 'pending' and implementationstatus.branchslno = '".$fetchresult['slno']."'";
				$row_data4 = runmysqlqueryfetch($query6);
				
				//Fetch With Status6 Data
				$query7 = "select   if(count(implementationstatus.slno or null) = 0,'0',count(implementationstatus.slno or null)) as count from implementationstatus 
				where implementationstatus.branchapproval = 'yes' AND implementationstatus.coordinatorreject = 'no' AND implementationstatus.coordinatorapproval = 'yes' AND implementationstatus.implementationstatus = 'assigned' and implementationstatus.branchslno = '".$fetchresult['slno']."'";
				$row_data5 = runmysqlqueryfetch($query7);
				
				//Fetch With Status7 Data
				$query8 = "select   if(count(implementationstatus.slno or null) = 0,'0',count(implementationstatus.slno or null)) as count from implementationstatus 
				where implementationstatus.branchapproval = 'yes' AND implementationstatus.coordinatorreject = 'no' AND implementationstatus.coordinatorapproval = 'yes' AND implementationstatus.implementationstatus = 'progess' and implementationstatus.branchslno = '".$fetchresult['slno']."'";
				$row_data6 = runmysqlqueryfetch($query8);
				
				//Fetch With Status8 Data
				$query9 = "select  if(count(implementationstatus.slno or null) = 0,'0',count(implementationstatus.slno or null)) as count from implementationstatus 
				where implementationstatus.branchapproval = 'yes' AND implementationstatus.coordinatorreject = 'no' AND implementationstatus.coordinatorapproval = 'yes' AND implementationstatus.implementationstatus = 'completed' and implementationstatus.branchslno = '".$fetchresult['slno']."'";
				$row_data7 = runmysqlqueryfetch($query9);
				
				
				$mySheet->setCellValue('C'.$currentrow,$row_data['count'])
						->setCellValue('D'.$currentrow,$row_data1['count'])
						->setCellValue('E'.$currentrow,$row_data2['count'])
						->setCellValue('F'.$currentrow,$row_data3['count'])
						->setCellValue('G'.$currentrow,$row_data4['count'])
						->setCellValue('H'.$currentrow,$row_data5['count'])
						->setCellValue('I'.$currentrow,$row_data6['count'])
						->setCellValue('J'.$currentrow,$row_data7['count'])
						->setCellValue('K'.$currentrow,"=SUM(C".$currentrow.":J".$currentrow.")");
				$currentrow++;
				
				//set the default width for column
				$mySheet->getColumnDimension('A')->setWidth(10);
				$mySheet->getColumnDimension('B')->setWidth(20);
				$mySheet->getColumnDimension('C')->setWidth(27);
				$mySheet->getColumnDimension('D')->setWidth(27);
				$mySheet->getColumnDimension('E')->setWidth(27);
				$mySheet->getColumnDimension('F')->setWidth(27);
				$mySheet->getColumnDimension('G')->setWidth(27);
				$mySheet->getColumnDimension('H')->setWidth(27);
				$mySheet->getColumnDimension('I')->setWidth(27);
				$mySheet->getColumnDimension('J')->setWidth(27);
				$mySheet->getColumnDimension('K')->setWidth(13);
			}
			
			//Insert Total
			$mySheet->setCellValue('B'.$currentrow,'Total')
					->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
					->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
					->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
					->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
					->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")")
					->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow-1).")")
					->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow-1).")")
					->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow-1).")")
					->setCellValue('K'.$currentrow,"=SUM(K".$databeginrow.":K".($currentrow-1).")");
			$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('K'.$currentrow)->getCalculatedValue();
	
			//Apply style for content Row
			$mySheet->getStyle('A'.$databeginrow.':K'.$currentrow)->applyFromArray($styleArrayContent);
			$pageindex++;
		
			//Begin with Worksheet 2 (Summary)
			$objPHPExcel->createSheet();
			$objPHPExcel->setActiveSheetIndex($pageindex);
			
		}
		
		if($implementerwise == "implementerwise")
		{
			
			//Set Active Sheet	
			$mySheet = $objPHPExcel->getActiveSheet();
			
			$mySheet->setTitle('Implementer wise');
				
			//Merge the cell
			$mySheet->mergeCells('A1:K1');
			$objPHPExcel->setActiveSheetIndex($pageindex)
						->setCellValue('A1', 'Implementer wise Status');
			$mySheet->getStyle('A1')->getFont()->setSize(12); 	
			$mySheet->getStyle('A1')->getFont()->setBold(true); 
			$mySheet->getStyle('A1')->getAlignment()->setWrapText(true);
			
			//Begin writing Region wise
			$currentrow = 2;
			
			
			$currentrow++;
			//Set table headings
			$mySheet->setCellValue('A'.$currentrow,'Slno')
					->setCellValue('B'.$currentrow,'Implementer Name')
					->setCellValue('C'.$currentrow,'Awaiting Branch Head Approval')
					->setCellValue('D'.$currentrow,'Fowarded back to Sales Person')
					->setCellValue('E'.$currentrow,'Awaiting Co-ordinator Approval')
					->setCellValue('F'.$currentrow,'Fowarded back to Branch Head')
					->setCellValue('G'.$currentrow,'Implementation, Yet to be Assigned')
					->setCellValue('H'.$currentrow,'Assigned For Implementation')
					->setCellValue('I'.$currentrow,'Implementation in progess')
					->setCellValue('J'.$currentrow,'Implementation Completed')
					->setCellValue('K'.$currentrow,'Total');
					
					
			//Apply style for header Row
			$mySheet->getStyle('A'.$currentrow.':K'.$currentrow)->applyFromArray($styleArray);
			
			//Set table data
			$currentrow++;
			$regionquery = 'select distinct implementerslno as slno,implementername from implementationstatus where  (implementername <> "" or implementername is not null)  order by implementername';
			$fetchregion =  runmysqlquery($regionquery);
			$databeginrow = $currentrow;
			$slnocount = 0;
			while($fetchresult = mysqli_fetch_array($fetchregion))
			{
				$slnocount++;
				//Insert data
				$mySheet->setCellValue('A'.$currentrow,$slnocount);
				$mySheet->setCellValue('B'.$currentrow,$fetchresult['implementername']);
				//Fetch With Status1 Data
				$query2 = "select   if(count(implementationstatus.slno or null) = 0,'0',count(implementationstatus.slno or null)) as count from implementationstatus 
				where implementationstatus.branchapproval = 'no' AND implementationstatus.coordinatorreject = 'no' 
				AND implementationstatus.coordinatorapproval = 'no' AND implementationstatus.branchreject = 'no' AND implementationstatus.implementationstatus = 'pending' and implementationstatus.implementerslno = '".$fetchresult['slno']."'";
				$row_data = runmysqlqueryfetch($query2);
				
				//Fetch With Status2 Data
				$query3 = "select   if(count(implementationstatus.slno or null) = 0,'0',count(implementationstatus.slno or null)) as count from implementationstatus 
				where implementationstatus.branchapproval = 'no' AND implementationstatus.branchreject = 'yes' AND implementationstatus.implementationstatus = 'pending' and implementationstatus.implementerslno = '".$fetchresult['slno']."'";
				$row_data1 = runmysqlqueryfetch($query3);
				
				//Fetch With Status3 Data
				$query4 = "select   if(count(implementationstatus.slno or null) = 0,'0',count(implementationstatus.slno or null)) as count from implementationstatus 
				where implementationstatus.branchapproval = 'yes' AND implementationstatus.coordinatorreject = 'no' AND implementationstatus.coordinatorapproval = 'no' AND implementationstatus.implementationstatus = 'pending' and implementationstatus.implementerslno = '".$fetchresult['slno']."'";
				$row_data2 = runmysqlqueryfetch($query4);
				
				//Fetch With Status4 Data
				$query5 = "select   if(count(implementationstatus.slno or null) = 0,'0',count(implementationstatus.slno or null)) as count from implementationstatus 
				where implementationstatus.branchapproval = 'no' AND implementationstatus.coordinatorreject = 'yes' AND implementationstatus.coordinatorapproval = 'no' AND implementationstatus.implementationstatus = 'pending' and implementationstatus.implementerslno = '".$fetchresult['slno']."'";
				$row_data3 = runmysqlqueryfetch($query5);
				
				//Fetch With Status5 Data
				$query6 = "select   if(count(implementationstatus.slno or null) = 0,'0',count(implementationstatus.slno or null)) as count from implementationstatus 
				where implementationstatus.branchapproval = 'yes' AND implementationstatus.coordinatorreject = 'no' AND implementationstatus.coordinatorapproval = 'yes' AND implementationstatus.implementationstatus = 'pending' and implementationstatus.implementerslno = '".$fetchresult['slno']."'";
				$row_data4 = runmysqlqueryfetch($query6);
				
				//Fetch With Status6 Data
				$query7 = "select   if(count(implementationstatus.slno or null) = 0,'0',count(implementationstatus.slno or null)) as count from implementationstatus 
				where implementationstatus.branchapproval = 'yes' AND implementationstatus.coordinatorreject = 'no' AND implementationstatus.coordinatorapproval = 'yes' AND implementationstatus.implementationstatus = 'assigned' and implementationstatus.implementerslno = '".$fetchresult['slno']."'";
				$row_data5 = runmysqlqueryfetch($query7);
				
				//Fetch With Status7 Data
				$query8 = "select   if(count(implementationstatus.slno or null) = 0,'0',count(implementationstatus.slno or null)) as count from implementationstatus 
				where implementationstatus.branchapproval = 'yes' AND implementationstatus.coordinatorreject = 'no' AND implementationstatus.coordinatorapproval = 'yes' AND implementationstatus.implementationstatus = 'progess' and implementationstatus.implementerslno = '".$fetchresult['slno']."'";
				$row_data6 = runmysqlqueryfetch($query8);
				
				//Fetch With Status8 Data
				$query9 = "select if(count(implementationstatus.slno or null) = 0,'0',count(implementationstatus.slno or null)) as count from implementationstatus 
				where implementationstatus.branchapproval = 'yes' AND implementationstatus.coordinatorreject = 'no' AND implementationstatus.coordinatorapproval = 'yes' AND implementationstatus.implementationstatus = 'completed' and implementationstatus.implementerslno = '".$fetchresult['slno']."'";
				$row_data7 = runmysqlqueryfetch($query9);
				
				
				$mySheet->setCellValue('C'.$currentrow,$row_data['count'])
						->setCellValue('D'.$currentrow,$row_data1['count'])
						->setCellValue('E'.$currentrow,$row_data2['count'])
						->setCellValue('F'.$currentrow,$row_data3['count'])
						->setCellValue('G'.$currentrow,$row_data4['count'])
						->setCellValue('H'.$currentrow,$row_data5['count'])
						->setCellValue('I'.$currentrow,$row_data6['count'])
						->setCellValue('J'.$currentrow,$row_data7['count'])
						->setCellValue('K'.$currentrow,"=SUM(C".$currentrow.":J".$currentrow.")");
				$currentrow++;
				
				//set the default width for column
				$mySheet->getColumnDimension('A')->setWidth(10);
				$mySheet->getColumnDimension('B')->setWidth(20);
				$mySheet->getColumnDimension('C')->setWidth(27);
				$mySheet->getColumnDimension('D')->setWidth(27);
				$mySheet->getColumnDimension('E')->setWidth(27);
				$mySheet->getColumnDimension('F')->setWidth(27);
				$mySheet->getColumnDimension('G')->setWidth(27);
				$mySheet->getColumnDimension('H')->setWidth(27);
				$mySheet->getColumnDimension('I')->setWidth(27);
				$mySheet->getColumnDimension('J')->setWidth(27);
				$mySheet->getColumnDimension('K')->setWidth(13);
			}
			
			//Insert Total
			$mySheet->setCellValue('B'.$currentrow,'Total')
					->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
					->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
					->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
					->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
					->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")")
					->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow-1).")")
					->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow-1).")")
					->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow-1).")")
					->setCellValue('K'.$currentrow,"=SUM(K".$databeginrow.":K".($currentrow-1).")");
			$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('K'.$currentrow)->getCalculatedValue();
	
			//Apply style for content Row
			$mySheet->getStyle('A'.$databeginrow.':K'.$currentrow)->applyFromArray($styleArrayContent);
			$pageindex++;
		
			//Begin with Worksheet 2 (Summary)
			$objPHPExcel->createSheet();
			$objPHPExcel->setActiveSheetIndex($pageindex);
			
		}
		
		if($salespersonwise == "salespersonwise")
		{
			//Set Active Sheet	
			$mySheet = $objPHPExcel->getActiveSheet();
			
			$mySheet->setTitle('Sales Person wise');
				
			//Merge the cell
			$mySheet->mergeCells('A1:K1');
			$objPHPExcel->setActiveSheetIndex($pageindex)
						->setCellValue('A1', 'Sales Person wise Status');
			$mySheet->getStyle('A1')->getFont()->setSize(12); 	
			$mySheet->getStyle('A1')->getFont()->setBold(true); 
			$mySheet->getStyle('A1')->getAlignment()->setWrapText(true);
			
			//Begin writing Region wise
			$currentrow = 2;
			
			
			$currentrow++;
			//Set table headings
			$mySheet->setCellValue('A'.$currentrow,'Slno')
					->setCellValue('B'.$currentrow,'Sales Person')
					->setCellValue('C'.$currentrow,'Awaiting Branch Head Approval')
					->setCellValue('D'.$currentrow,'Fowarded back to Sales Person')
					->setCellValue('E'.$currentrow,'Awaiting Co-ordinator Approval')
					->setCellValue('F'.$currentrow,'Fowarded back to Branch Head')
					->setCellValue('G'.$currentrow,'Implementation, Yet to be Assigned')
					->setCellValue('H'.$currentrow,'Assigned For Implementation')
					->setCellValue('I'.$currentrow,'Implementation in progess')
					->setCellValue('J'.$currentrow,'Implementation Completed')
					->setCellValue('K'.$currentrow,'Total');
					
					
			//Apply style for header Row
			$mySheet->getStyle('A'.$currentrow.':K'.$currentrow)->applyFromArray($styleArray);
			
			//Set table data
			$currentrow++;
			$regionquery = 'select distinct dealerid as slno,salesperson from implementationstatus where  (salesperson <> "" or 
		salesperson is not null)  order by salesperson';
			$fetchregion =  runmysqlquery($regionquery);
			$databeginrow = $currentrow;
			$slnocount = 0;
			while($fetchresult = mysqli_fetch_array($fetchregion))
			{
				$slnocount++;
				//Insert data
				$mySheet->setCellValue('A'.$currentrow,$slnocount);
				$mySheet->setCellValue('B'.$currentrow,$fetchresult['salesperson']);
				//Fetch With Status1 Data
				$query2 = "select   if(count(implementationstatus.slno or null) = 0,'0',count(implementationstatus.slno or null)) as count from implementationstatus 
				where implementationstatus.branchapproval = 'no' AND implementationstatus.coordinatorreject = 'no' 
				AND implementationstatus.coordinatorapproval = 'no' AND implementationstatus.branchreject = 'no' AND implementationstatus.implementationstatus = 'pending' and implementationstatus.dealerid = '".$fetchresult['slno']."'";
				$row_data = runmysqlqueryfetch($query2);
				
				//Fetch With Status2 Data
				$query3 = "select   if(count(implementationstatus.slno or null) = 0,'0',count(implementationstatus.slno or null)) as count from implementationstatus 
				where implementationstatus.branchapproval = 'no' AND implementationstatus.branchreject = 'yes' AND implementationstatus.implementationstatus = 'pending' and implementationstatus.dealerid = '".$fetchresult['slno']."'";
				$row_data1 = runmysqlqueryfetch($query3);
				
				//Fetch With Status3 Data
				$query4 = "select   if(count(implementationstatus.slno or null) = 0,'0',count(implementationstatus.slno or null)) as count from implementationstatus 
				where implementationstatus.branchapproval = 'yes' AND implementationstatus.coordinatorreject = 'no' AND implementationstatus.coordinatorapproval = 'no' AND implementationstatus.implementationstatus = 'pending' and implementationstatus.dealerid = '".$fetchresult['slno']."'";
				$row_data2 = runmysqlqueryfetch($query4);
				
				//Fetch With Status4 Data
				$query5 = "select   if(count(implementationstatus.slno or null) = 0,'0',count(implementationstatus.slno or null)) as count from implementationstatus 
				where implementationstatus.branchapproval = 'no' AND implementationstatus.coordinatorreject = 'yes' AND implementationstatus.coordinatorapproval = 'no' AND implementationstatus.implementationstatus = 'pending' and implementationstatus.dealerid = '".$fetchresult['slno']."'";
				$row_data3 = runmysqlqueryfetch($query5);
				
				//Fetch With Status5 Data
				$query6 = "select   if(count(implementationstatus.slno or null) = 0,'0',count(implementationstatus.slno or null)) as count from implementationstatus 
				where implementationstatus.branchapproval = 'yes' AND implementationstatus.coordinatorreject = 'no' AND implementationstatus.coordinatorapproval = 'yes' AND implementationstatus.implementationstatus = 'pending' and implementationstatus.dealerid = '".$fetchresult['slno']."'";
				$row_data4 = runmysqlqueryfetch($query6);
				
				//Fetch With Status6 Data
				$query7 = "select   if(count(implementationstatus.slno or null) = 0,'0',count(implementationstatus.slno or null)) as count from implementationstatus 
				where implementationstatus.branchapproval = 'yes' AND implementationstatus.coordinatorreject = 'no' AND implementationstatus.coordinatorapproval = 'yes' AND implementationstatus.implementationstatus = 'assigned' and implementationstatus.dealerid = '".$fetchresult['slno']."'";
				$row_data5 = runmysqlqueryfetch($query7);
				
				//Fetch With Status7 Data
				$query8 = "select   if(count(implementationstatus.slno or null) = 0,'0',count(implementationstatus.slno or null)) as count from implementationstatus 
				where implementationstatus.branchapproval = 'yes' AND implementationstatus.coordinatorreject = 'no' AND implementationstatus.coordinatorapproval = 'yes' AND implementationstatus.implementationstatus = 'progess' and implementationstatus.dealerid = '".$fetchresult['slno']."'";
				$row_data6 = runmysqlqueryfetch($query8);
				
				//Fetch With Status8 Data
				$query9 = "select  if(count(implementationstatus.slno or null) = 0,'0',count(implementationstatus.slno or null)) as count from implementationstatus 
				where implementationstatus.branchapproval = 'yes' AND implementationstatus.coordinatorreject = 'no' AND implementationstatus.coordinatorapproval = 'yes' AND implementationstatus.implementationstatus = 'completed' and implementationstatus.dealerid = '".$fetchresult['slno']."'";
				$row_data7 = runmysqlqueryfetch($query9);
				
				
				$mySheet->setCellValue('C'.$currentrow,$row_data['count'])
						->setCellValue('D'.$currentrow,$row_data1['count'])
						->setCellValue('E'.$currentrow,$row_data2['count'])
						->setCellValue('F'.$currentrow,$row_data3['count'])
						->setCellValue('G'.$currentrow,$row_data4['count'])
						->setCellValue('H'.$currentrow,$row_data5['count'])
						->setCellValue('I'.$currentrow,$row_data6['count'])
						->setCellValue('J'.$currentrow,$row_data7['count'])
						->setCellValue('K'.$currentrow,"=SUM(C".$currentrow.":J".$currentrow.")");
				$currentrow++;
				
				//set the default width for column
				$mySheet->getColumnDimension('A')->setWidth(10);
				$mySheet->getColumnDimension('B')->setWidth(20);
				$mySheet->getColumnDimension('C')->setWidth(27);
				$mySheet->getColumnDimension('D')->setWidth(27);
				$mySheet->getColumnDimension('E')->setWidth(27);
				$mySheet->getColumnDimension('F')->setWidth(27);
				$mySheet->getColumnDimension('G')->setWidth(27);
				$mySheet->getColumnDimension('H')->setWidth(27);
				$mySheet->getColumnDimension('I')->setWidth(27);
				$mySheet->getColumnDimension('J')->setWidth(27);
				$mySheet->getColumnDimension('K')->setWidth(13);
			}
			
			//Insert Total
			$mySheet->setCellValue('B'.$currentrow,'Total')
					->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
					->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
					->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
					->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
					->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")")
					->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow-1).")")
					->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow-1).")")
					->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow-1).")")
					->setCellValue('K'.$currentrow,"=SUM(K".$databeginrow.":K".($currentrow-1).")");
			$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('K'.$currentrow)->getCalculatedValue();
	
			//Apply style for content Row
			$mySheet->getStyle('A'.$databeginrow.':K'.$currentrow)->applyFromArray($styleArrayContent);
		}
		
		
		$objPHPExcel->setActiveSheetIndex(0);
		$addstring = "/dealer";
		if($_SERVER['HTTP_HOST'] == "localhost")
		$addstring = "/imax/dealer";
		$query = 'select slno,businessname from inv_mas_dealer where slno = '.$userid.'';
		$fetchres = runmysqlqueryfetch($query);
		$localdate = datetimelocal('Ymd');
		$localtime = datetimelocal('His');
		$filebasename = "ImplementationstatusReport".$localdate."-".$localtime."-".strtolower($fetchres['businessname']).".xls";
		$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','excel_Implementationstatus_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
		$result = runmysqlquery($query1);	
		
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','283','".date('Y-m-d').' '.date('H:i:s')."','excel_Implementationstatus_report".'-'.strtolower($fetchres['businessname'])."')";
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
