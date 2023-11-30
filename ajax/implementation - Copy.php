<?php
ob_start("ob_gzhandler");

include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');
include('../inc/checksession.php');

$lastslno = $_POST['lastslno'];
$switchtype = $_POST['switchtype'];

if(imaxgetcookie('dealeruserid')<> '') 
$userid = imaxgetcookie('dealeruserid');
else
{ 
	echo(json_encode('Thinking to redirect'));
	exit;
}

switch($switchtype)
{
	
	case 'getcustomercount':
	{
		$customerarraycount = array();
		$query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode, inv_mas_dealer.branch , inv_mas_dealer.region from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
 where inv_mas_dealer.slno = '".$userid."';";
		$fetch = runmysqlqueryfetch($query);
		$relyonexecutive = $fetch['relyonexecutive'];
		$district = $fetch['district'];
		$branch = $fetch['branch'];
		$state = $fetch['statecode'];
		$region = $fetch['region'];
		if($relyonexecutive == 'no')
		{
			$query = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname  from inv_mas_customer where inv_mas_customer.currentdealer = '".$userid."' order by businessname;";
			$result = runmysqlquery($query);
		}
		else
		{
			if(($region == '1') || ($region == '3'))
			{
				$query = "select distinct slno as slno, businessname as businessname from inv_mas_customer where (inv_mas_customer.region = '1' or inv_mas_customer.region = '3') order by businessname;";
				$result = runmysqlquery($query);
			}
			else
			{
				
				$query = "select distinct slno as slno, businessname as businessname from inv_mas_customer where branch = '".$branch."' order by businessname;";
				$result = runmysqlquery($query);
			}
		}
		$count = mysqli_num_rows($result);
		$customerarraycount['count'] = $count;
		echo(json_encode($customerarraycount));
	}
	break;
	case 'generatecustomerlist':
	{
		$limit = $_POST['limit'];
		$startindex = $_POST['startindex'];
		$query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode , inv_mas_dealer.branch, inv_mas_dealer.region from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
 where inv_mas_dealer.slno = '".$userid."';";
		$fetch = runmysqlqueryfetch($query);
		$relyonexecutive = $fetch['relyonexecutive'];
		$district = $fetch['district'];
		$branch = $fetch['branch'];
		$state = $fetch['statecode'];
		$region = $fetch['region'];
		if($relyonexecutive == 'no')
		{
			$query = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname  from inv_mas_customer where inv_mas_customer.currentdealer = '".$userid."' order by businessname LIMIT ".$startindex.",".$limit.";";
			$result = runmysqlquery($query);
		}
		else
		{
			if(($region == '1') || ($region == '3'))
			{
				$query = "select  distinct slno as slno, businessname as businessname from inv_mas_customer where (inv_mas_customer.region = '1' or inv_mas_customer.region = '3') order by businessname LIMIT ".$startindex.",".$limit.";";
				$result = runmysqlquery($query);
			}
			else
			{
				
				$query = "select distinct slno as slno, businessname as businessname from inv_mas_customer where branch = '".$branch."' order by businessname LIMIT ".$startindex.",".$limit.";";
				$result = runmysqlquery($query);
			}
		}
		//echo($query);exit;	
		$customerarray = array();
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$customerarray[$count] = $fetch['businessname'].'^'.$fetch['slno'];
			$count++;
		}
		echo(json_encode($customerarray));
		
	}
	break;
	case 'customerdetailstoform':
	{
		$responsearray20 = array();
		$lastslno = $_POST['lastslno'];
		$query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode, inv_mas_dealer.branch,inv_mas_dealer.region  from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode where inv_mas_dealer.slno = '".$userid."';";
		$fetch = runmysqlqueryfetch($query);
		$relyonexecutive = $fetch['relyonexecutive'];
		$district = $fetch['district'];
		$branch = $fetch['branch'];
		$state = $fetch['statecode'];
		$region = $fetch['region'];
		if($relyonexecutive == 'no')
		{
			$dealerpiece = " AND inv_mas_customer.currentdealer = '".$userid."'";
		}
		else
		{
			if(($region == '1') || ($region == '3'))
			{
			
				$dealerpiece = " AND (inv_mas_customer.region = '1' or inv_mas_customer.region = '3')";
			}
			else
			{
				$dealerpiece = " AND (inv_mas_customer.branch = '".$branch."')";
			}
		}
		
		$query2 = "select * from inv_customerreqpending where customerid = '".$lastslno."' and inv_customerreqpending.customerstatus = 'Pending' and requestfrom = 'dealer_module';";
		$result2 = runmysqlquery($query2);
		
		if(mysqli_num_rows($result2) > 0)
		{
			$query = "select inv_customerreqpending.customerid as slno, inv_customerreqpending.businessname as companyname,inv_customerreqpending.place,inv_customerreqpending.address,inv_mas_region.category as region,inv_mas_branch.branchname as branch,inv_mas_customercategory.businesstype,inv_mas_customertype.customertype,inv_mas_dealer.businessname as dealername,inv_customerreqpending.stdcode, inv_mas_customer.pincode,inv_mas_district.districtname, inv_mas_state.statename,inv_mas_customer.customerid  from inv_mas_customer left join inv_customerreqpending on inv_customerreqpending.customerid = inv_mas_customer.slno left join inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region 
left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type 
left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode where inv_mas_customer.slno = '".$lastslno."'".$dealerpiece." and inv_customerreqpending.customerstatus = 'Pending' and requestfrom = 'dealer_module';";
			$fetch = runmysqlqueryfetch($query);
			// contact Details
			$querycontactdetails = "select customerid, GROUP_CONCAT(contactperson) as contactperson,  
GROUP_CONCAT(phone) as phone, GROUP_CONCAT(cell) as cell, GROUP_CONCAT(emailid) as emailid from inv_contactreqpending where inv_contactreqpending.customerid = '".$fetch['slno']."'  group by customerid ";
			$resultcontactdetails = runmysqlqueryfetch($querycontactdetails);
			
			$contactperson = removedoublecomma($resultcontactdetails['contactperson']);
			$phoneres = removedoublecomma($resultcontactdetails['phone']);
			$cellres = removedoublecomma($resultcontactdetails['cell']);
			$emailidres = removedoublecomma($resultcontactdetails['emailid']);
			
			$customerid = ($fetch['customerid'] == '')?'':cusidcombine($fetch['customerid']);	
			$pincode = ($resultfetch['pincode'] == '')?'':'Pin - '.$fetch['pincode'];
			$address = $fetch['address'].', '.$fetch['districtname'].', '.$fetch['statename'].$pincode;
			$contactvalues1 = explode(',', $contactperson);
			$contactvalues = $contactvalues1[0];
			$phonenumber = explode(',', $phoneres);
			$phone = $phonenumber[0];
			$cellnumber = explode(',', $cellres);
			$cell = $cellnumber[0];
			$emailid = explode(',', $emailidres);
			$emailidplit = $emailid[0];
			
			
			$responsearray20['errorcode'] = "1";
			$responsearray20['slno'] = $fetch['slno'];
			$responsearray20['customerid'] = $customerid;
			$responsearray20['companyname'] = $fetch['companyname'];
			$responsearray20['contactvalues'] = $contactvalues;
			$responsearray20['address'] = $address;
			$responsearray20['phone'] = $phone;
			$responsearray20['cell'] = $cell;
			$responsearray20['emailidplit'] = $emailidplit;
			$responsearray20['region'] = $fetch['region'];
			$responsearray20['branch'] = $fetch['branch'];
			$responsearray20['businesstype'] = $fetch['businesstype'];
			$responsearray20['customertype'] = $fetch['customertype'];
			$responsearray20['dealername'] = $fetch['dealername'];
			echo(json_encode($responsearray20));
				//echo('1^'.$fetch['slno'].'^'.$customerid.'^'.$fetch['companyname'].'^'.$contactvalues.'^'.$address.'^'.$phone.'^'.$cell.'^'.$emailidplit.'^'.$fetch['region'].'^'.$fetch['branch'].'^'.$fetch['businesstype'].'^'.$fetch['customertype'].'^'.$fetch['dealername'].'^'.$query2);
		}
		else
		{
			$query1 = "SELECT count(*) as count from inv_mas_customer left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode where inv_mas_customer.slno = '".$lastslno."'".$dealerpiece."";
			$fetch1 = runmysqlqueryfetch($query1);
			if($fetch1['count'] > 0)
			{
				$query = "select inv_mas_customer.slno as slno, inv_mas_customer.businessname as companyname,inv_mas_customer.place,inv_mas_customer.address,inv_mas_region.category as region,inv_mas_branch.branchname as branch	,inv_mas_customercategory.businesstype,inv_mas_customertype.customertype,inv_mas_dealer.businessname as dealername,inv_mas_customer.stdcode, inv_mas_customer.pincode,inv_mas_district.districtname, inv_mas_state.statename,inv_mas_customer.customerid  from inv_mas_customer left join inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode where inv_mas_customer.slno = '".$lastslno."'".$dealerpiece."";
				
				// contact Details
				$querycontactdetails = "select customerid, GROUP_CONCAT(contactperson) as contactperson,  
	GROUP_CONCAT(phone) as phone, GROUP_CONCAT(cell) as cell, GROUP_CONCAT(emailid) as emailid from inv_contactdetails where customerid = '".$lastslno."'  group by customerid ";
				$resultcontactdetails = runmysqlqueryfetch($querycontactdetails);
				
				$contactperson = removedoublecomma($resultcontactdetails['contactperson']);
				$phoneres = removedoublecomma($resultcontactdetails['phone']);
				$cellres = removedoublecomma($resultcontactdetails['cell']);
				$emailidres = removedoublecomma($resultcontactdetails['emailid']);
			
				$fetch = runmysqlqueryfetch($query);
				$customerid = ($fetch['customerid'] == '')?'':cusidcombine($fetch['customerid']);	
				$pincode = ($resultfetch['pincode'] == '')?'':'Pin - '.$fetch['pincode'];
				$address = $fetch['address'].', '.$fetch['districtname'].', '.$fetch['statename'].$pincode;
				$contactvalues1 = explode(',', $contactperson);
				$contactvalues = $contactvalues1[0];
				$phonenumber = explode(',', $phoneres);
				$phone = $phonenumber[0];
				$cellnumber = explode(',', $cellres);
				$cell = $cellnumber[0];
				$emailid = explode(',', $emailidres);
				$emailidplit = $emailid[0];
				
				
				$responsearray20['errorcode'] = "1";
				$responsearray20['slno'] = $fetch['slno'];
				$responsearray20['customerid'] = $customerid;
				$responsearray20['companyname'] = $fetch['companyname'];
				$responsearray20['contactvalues'] = $contactvalues;
				$responsearray20['address'] = $address;
				$responsearray20['phone'] = $phone;
				$responsearray20['cell'] = $cell;
				$responsearray20['emailidplit'] = $emailidplit;
				$responsearray20['region'] = $fetch['region'];
				$responsearray20['branch'] = $fetch['branch'];
				$responsearray20['businesstype'] = $fetch['businesstype'];
				$responsearray20['customertype'] = $fetch['customertype'];
				$responsearray20['dealername'] = $fetch['dealername'];
				echo(json_encode($responsearray20));			//echo('1^'.$fetch['slno'].'^'.$customerid.'^'.$fetch['companyname'].'^'.$contactvalues.'^'.$address.'^'.$phone.'^'.$cell.'^'.$emailidplit.'^'.$fetch['region'].'^'.$fetch['branch'].'^'.$fetch['businesstype'].'^'.$fetch['customertype'].'^'.$fetch['dealername'].'^'.$query2);
			}
			else
			{
				$responsearray20['errorcode'] = "";
				echo(json_encode($responsearray20));						//echo(''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.'');
			}
		}
	}
	break;
	
	case 'invoicedetails':
	{
		$lastslno = $_POST['lastslno'];
		$query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode,inv_mas_dealer.branch ,inv_mas_dealer.region from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode where inv_mas_dealer.slno = '".$userid."';";
		$fetch = runmysqlqueryfetch($query);
		$relyonexecutive = $fetch['relyonexecutive'];
		$district = $fetch['district'];
		$state = $fetch['statecode'];
		$branch = $fetch['branch'];
		$region = $fetch['region'];
		if($relyonexecutive == 'no')
		{
			$dealerpiece = " AND inv_mas_customer.currentdealer = '".$userid."'";
		}
		else
		{
			if(($region == '1') || ($region == '3'))
			{
			
				$dealerpiece = " AND (inv_mas_customer.region = '1' or inv_mas_customer.region = '3')";
			}
			else
			{
				$dealerpiece = " AND (inv_mas_customer.branch = '".$branch."')";
			}
		}
		

		$query1 = "SELECT count(*) as count from inv_mas_customer left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode where inv_mas_customer.slno = '".$lastslno."'".$dealerpiece."";
		$fetch1 = runmysqlqueryfetch($query1);
		if($fetch1['count'] > 0)
		{
			$query12 = "SELECT count(*) as count from imp_cfentries where substring(imp_cfentries.customerid,16) = '".$lastslno."'";
			$fetch12 = runmysqlqueryfetch($query12);
			if($fetch12['count'] > 0)
			{
				$resquery = "select  * from imp_cfentries 
where imp_cfentries.productcode in ('265','816','640','604','643','851','871','874','834','836','835','841','838',
'843','844','850','869','872','875','870','873','876','861','863',
'862','864','262','261','269','266','267','268','237','238','877')
and substring(imp_cfentries.customerid,16) = '".$lastslno."'";
				$resresult = runmysqlquery($resquery);
				$cfgrid = '<select name="cfdetails" class="swiftselect" id="cfdetails" style="width:125px;" ><option value="" selected="selected">Select an Entry</option>';
				while($resfetch = mysqli_fetch_array($resresult))
				{
					$cfgrid .= '<option value="'.$resfetch['invoiceno'].'%%'.''.'%%'.$resfetch['productcode'].'">'.$resfetch['invoiceno'].'</option>';
				}
				$cfgrid .= "</select>";
			}
			$query121 = "select distinct count( *) as count
	from inv_invoicenumbers left join inv_dealercard on inv_invoicenumbers.slno = inv_dealercard.invoiceid
	where inv_dealercard.productcode in ('265','816','640','604','643','851','871','874','834','836','835','841','838',
	'843','844','850','869','872','875','870','873','876','861','863',
	'862','864','262','261','269','266','267','268','237','238','877')
	and substring(inv_invoicenumbers.customerid,16) = '".$lastslno."'";
			$fetch123 = runmysqlqueryfetch($query121);
			if($fetch123['count'] > 0)
			{
				$query = "select distinct inv_invoicenumbers.slno, inv_invoicenumbers.customerid,
	inv_invoicenumbers.businessname, inv_invoicenumbers.description
	,inv_invoicenumbers.invoiceno, inv_invoicenumbers.netamount,inv_dealercard.productcode
	from inv_invoicenumbers left join inv_dealercard on inv_invoicenumbers.slno = inv_dealercard.invoiceid
	where inv_dealercard.productcode in ('265','816','640','604','643','851','871','874','834','836','835','841','838',
	'843','844','850','869','872','875','870','873','876','861','863',
	'862','864','262','261','269','266','267','268','237','238','877')
	and substring(inv_invoicenumbers.customerid,16) = '".$lastslno."'";
				$result = runmysqlquery($query);
				$invoicegrid = '<select name="invoicedetails" class="swiftselect" id="invoicedetails" style="width:125px;" ><option value="" selected="selected">Select a Invoice</option>';
				while($fetch = mysqli_fetch_array($result))
				{
					$invoicegrid .= '<option value="'.$fetch['invoiceno'].'%%'.$fetch['slno'].'%%'.$fetch['productcode'].'">'.$fetch['invoiceno'].'</option>';
				}
				$invoicegrid .= "</select>";
			}
			$responsearray21 = array();
			$responsearray21['errorcode'] = "1";
			$responsearray21['count'] = $fetch12['count'];
			$responsearray21['cfgrid'] = $cfgrid;
			$responsearray21['invoicegrid'] = $invoicegrid;
			echo(json_encode($responsearray21));
			//echo('1^'.$fetch12['count'].'^'.$cfgrid.'^'.$invoicegrid);
			
		}
		else
		{
			$responsearray21 = array();
			$responsearray21['errorcode'] = "";
			echo(json_encode($responsearray21));
			//echo(''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.'');
		}
	}
	break;
	
	case 'customervalidation':
	{
		$responsearray1 = array();
		$lastslno = $_POST['lastslno'];
		$query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode, inv_mas_dealer.branch , inv_mas_dealer.region from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode where inv_mas_dealer.slno = '".$userid."';";
		$fetch = runmysqlqueryfetch($query);
		$relyonexecutive = $fetch['relyonexecutive'];
		$district = $fetch['district'];
		$state = $fetch['statecode'];
		$branch = $fetch['branch'];
		$region = $fetch['region'];
		if($relyonexecutive == 'no')
		{
			$dealerpiece = " AND inv_mas_customer.currentdealer = '".$userid."'";
		}
		else
		{
			if(($region == '1') || ($region == '3'))
			{
			
				$dealerpiece = " AND (inv_mas_customer.region = '1' or inv_mas_customer.region = '3')";
			}
			else
			{
				$dealerpiece = " AND (inv_mas_customer.branch = '".$branch."')";
			}
		}
		

		$query1 = "SELECT count(*) as count from imp_implementation left join inv_mas_customer on inv_mas_customer.slno = imp_implementation.customerreference left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode  where imp_implementation.customerreference = '".$lastslno."'".$dealerpiece."";
		$fetch1 = runmysqlqueryfetch($query1);
		if($fetch1['count'] > 0)
		{
			$query12 = "SELECT imp_implementation.slno,imp_implementation.customerreference,imp_implementation.invoicenumber,
imp_implementation.advancecollected, imp_implementation.advanceamount,imp_implementation.advanceremarks,
imp_implementation.balancerecoveryremarks,imp_implementation.podetails,
imp_implementation.numberofcompanies,imp_implementation.numberofmonths,
imp_implementation.processingfrommonth,imp_implementation.additionaltraining,imp_implementation.freedeliverables,
imp_implementation.schemeapplicable,imp_implementation.commissionapplicable,imp_implementation.commissionname, imp_implementation.commissionmobile,imp_implementation.commissionemail, imp_implementation.commissionvalue,imp_implementation.masterdatabyrelyon,
imp_implementation.masternumberofemployees,imp_implementation.salescommitments,imp_implementation.attendanceapplicable,
imp_implementation.attendanceremarks,imp_implementation.attendancefilepath
,imp_implementation.attendancefiledate,imp_implementation.attendancefileattachedby,imp_implementation.shipinvoiceapplicable,
imp_implementation.shipinvoiceremarks,imp_implementation.shipmanualapplicable,
imp_implementation.shipmanualremarks,imp_implementation.customizationapplicable,imp_implementation.customizationremarks,
imp_implementation.customizationreffilepath,imp_implementation.customizationreffiledate
,imp_implementation.customizationreffileattachedby,imp_implementation.customizationbackupfilepath,
imp_implementation.customizationbackupfiledate, imp_implementation.customizationbackupfileattachedby,
imp_implementation.customizationstatus,imp_implementation.implementationstatus,imp_implementation.webimplemenationapplicable, imp_implementation.webimplemenationremarks ,branchapproval, imp_implementation.invoicenumber, attendancedeletefilepath,imp_implementation.podetailspath,imp_implementation.podetailsdate,imp_implementation.podetailsattachedby ,imp_implementation.customizationcustomerdisplay,imp_implementation.committedstartdate,imp_implementation.advancecollecreceipt as  advancecollecreceipt,imp_implementation.podate as  podate from imp_implementation  left join inv_mas_customer on inv_mas_customer.slno = imp_implementation.customerreference left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode 
where imp_implementation.customerreference = '".$lastslno."'".$dealerpiece."";
			$fetch = runmysqlqueryfetch($query12);
			
			$query66 ="Delete from imp_rafiles where impref = '' or impref is null ;;";
			$result66 = runmysqlquery($query66);
			
			$query1 ="SELECT imp_addon.slno, imp_addon.customerid, imp_addon.refslno, imp_mas_addons.addonname as addon, imp_addon.remarks,imp_addon.addon as addonslno from imp_addon left join imp_mas_addons on imp_mas_addons.slno = imp_addon.addon where refslno  = '".$fetch['slno']."'; ";
			$resultfetch = runmysqlquery($query1);
			$valuecount = 0;
			while($fetchres = mysqli_fetch_array($resultfetch))
			{
				if($valuecount > 0)
					$addonarray .= '****';
				$addon = $fetchres['addon'];
				$remarks = $fetchres['remarks'];
				$slno = $fetchres['slno'];
				$addonslno = $fetchres['addonslno'];
				
				$addonarray .= $addon.'#'.$remarks.'#'.$slno.'#'.$addonslno;
				$valuecount++;
			}
			
			
			
			
			$responsearray1['errorcode'] = "1";
			$responsearray1['slno'] = $fetch['slno'];
			$responsearray1['customerreference'] = $fetch['customerreference'];
			$responsearray1['invoicenumber'] = $fetch['invoicenumber'];
			$responsearray1['advancecollected'] = $fetch['advancecollected'];
			$responsearray1['advanceamount'] = $fetch['advanceamount'];
			$responsearray1['advancecollecreceipt'] = $fetch['advancecollecreceipt'];
			$responsearray1['advanceremarks'] = $fetch['advanceremarks'];
			$responsearray1['balancerecoveryremarks'] = $fetch['balancerecoveryremarks'];
			$responsearray1['podetails'] = $fetch['podetails'];
			$responsearray1['numberofcompanies'] = $fetch['numberofcompanies'];
			$responsearray1['numberofmonths'] = $fetch['numberofmonths'];
			$responsearray1['processingfrommonth'] = $fetch['processingfrommonth'];
			$responsearray1['additionaltraining'] = $fetch['additionaltraining'];
			$responsearray1['freedeliverables'] = $fetch['freedeliverables'];
			$responsearray1['schemeapplicable'] = $fetch['schemeapplicable'];
			$responsearray1['commissionapplicable'] = $fetch['commissionapplicable'];
			$responsearray1['commissionname'] = $fetch['commissionname'];
			$responsearray1['commissionmobile'] = $fetch['commissionmobile'];
			$responsearray1['commissionemail'] = $fetch['commissionemail'];
			$responsearray1['commissionvalue'] = $fetch['commissionvalue'];
			$responsearray1['masterdatabyrelyon'] = $fetch['masterdatabyrelyon'];
			$responsearray1['masternumberofemployees'] = $fetch['masternumberofemployees'];
			$responsearray1['salescommitments'] = $fetch['salescommitments'];
			$responsearray1['attendanceapplicable'] = $fetch['attendanceapplicable'];
			$responsearray1['attendanceremarks'] = $fetch['attendanceremarks'];
			$responsearray1['attendancefilepath'] = $fetch['attendancefilepath'];
			$responsearray1['shipinvoiceapplicable'] = $fetch['shipinvoiceapplicable'];
			$responsearray1['shipinvoiceremarks'] = $fetch['shipinvoiceremarks'];
			$responsearray1['shipmanualapplicable'] = $fetch['shipmanualapplicable'];
			$responsearray1['shipmanualremarks'] = $fetch['shipmanualremarks'];
			$responsearray1['customizationapplicable'] = $fetch['customizationapplicable'];
			$responsearray1['customizationremarks'] = $fetch['customizationremarks'];
			$responsearray1['customizationreffilepath'] = $fetch['customizationreffilepath'];
			$responsearray1['customizationbackupfilepath'] = $fetch['customizationbackupfilepath'];
			$responsearray1['customizationstatus'] = $fetch['customizationstatus'];
			$responsearray1['implementationstatus'] = $fetch['implementationstatus'];
			$responsearray1['grid'] = $grid;
			$responsearray1['addonarray'] = $addonarray;
			$responsearray1['webimplemenationapplicable'] = $fetch['webimplemenationapplicable'];
			$responsearray1['webimplemenationremarks'] = $fetch['webimplemenationremarks'];
			$responsearray1['branchapproval'] = $fetch['branchapproval'];
			$responsearray1['attendancedeletefilepath'] = $fetch['attendancedeletefilepath'];
			$responsearray1['podetailspath'] = $fetch['podetailspath'];
			$responsearray1['customizationcustomerdisplay'] = $fetch['customizationcustomerdisplay'];
			$responsearray1['committedstartdate'] = changedateformat($fetch['committedstartdate']);
			$responsearray1['podate'] = changedateformat($fetch['podate']);
			echo(json_encode($responsearray1));
			//echo('1^'.$fetch['slno'].'^'.$fetch['customerreference'].'^'.$fetch['invoicenumber'].'^'.$fetch['advancecollected'].'^'.$fetch['advanceamount'].'^'.$fetch['advanceremarks'].'^'.$fetch['balancerecoveryremarks'].'^'.$fetch['podetails'].'^'.$fetch['numberofcompanies'].'^'.$fetch['numberofmonths'].'^'.$fetch['processingfrommonth'].'^'.$fetch['additionaltraining'].'^'.$fetch['freedeliverables'].'^'.$fetch['schemeapplicable'].'^'.$fetch['commissionapplicable'].'^'.$fetch['commissionname'].'^'.$fetch['commissionmobile'].'^'.$fetch['commissionemail'].'^'.$fetch['commissionvalue'].'^'.$fetch['masterdatabyrelyon'].'^'.$fetch['masternumberofemployees'].'^'.$fetch['salescommitments'].'^'.$fetch['attendanceapplicable'].'^'.$fetch['attendanceremarks'].'^'.$fetch['attendancefilepath'].'^'.$fetch['shipinvoiceapplicable'].'^'.$fetch['shipinvoiceremarks'].'^'.$fetch['shipmanualapplicable'].'^'.$fetch['shipmanualremarks'].'^'.$fetch['customizationapplicable'].'^'.$fetch['customizationremarks'].'^'.$fetch['customizationreffilepath'].'^'.$fetch['customizationbackupfilepath'].'^'.$fetch['customizationstatus'].'^'.$fetch['implementationstatus'].'^'.$grid.'^'.$addonarray.'^'.$fetch['webimplemenationapplicable'].'^'.$fetch['webimplemenationremarks'].'^'.$fetch['branchapproval'].'^'.$fetch['attendancedeletefilepath'].'^'.$fetch['podetailspath'].'^'.$fetch['customizationcustomerdisplay'].'^'.changedateformat($fetch['committedstartdate']));
			
		}
		else
		{
			$responsearray1['errorcode'] = "2";
			$responsearray1['errormsg'] = 'No Invoice Entry for Saral Paypack.';
			echo(json_encode($responsearray1));
			
			//echo('2^'.'No Invoice Entry for Saral Paypack.');
		}
	}
	break;
	
	case 'invoiceconfirmation':
	{
		$rslno = $_POST['rslno'];
		$invoiceslno = $_POST['invoiceslno'];
		$query13 = "SELECT count(*) as count from imp_cfentries where  imp_cfentries.invoiceno = '".$rslno."'";
		$fetch13 = runmysqlqueryfetch($query13);
		$query = "select distinct inv_invoicenumbers.slno, inv_invoicenumbers.customerid,
inv_invoicenumbers.businessname, inv_invoicenumbers.description, inv_invoicenumbers.invoiceno, inv_invoicenumbers.netamount
from inv_invoicenumbers where  inv_invoicenumbers.invoiceno = '".$rslno."'";

		$query989 = "select count(*) as count from inv_mas_receipt where invoiceno = '".$invoiceslno."'";
		$result11 = runmysqlqueryfetch($query989);
		if($result11['count'] == 0)
		{
			$receiptgrid = '<select name="receipt" class="diabledatefield" id="receipt" style="width: 201px;" disabled="disabled">
<option selected="selected" value="" >---Select---</option></select>';
		}
		else
		{
			$query66 = "select * from inv_mas_receipt where invoiceno = '".$invoiceslno."'";
			$result11 = runmysqlquery($query66);
			$receiptgrid = '<select name="receipt" class="diabledatefield" id="receipt" style="width: 201px;" disabled="disabled">';
			$receiptgrid .= '<option selected="selected" value="" >---Select---</option>';
			while($fetch55 = mysqli_fetch_array($result11))
			{
				$receiptgrid .= '<option value="'.$fetch55['slno'].'">Receipt/'.$fetch55['slno'].' '.'-'.' '.'Rs'.' '.$fetch55['receiptamount'].'</option>';
			}
			$receiptgrid .= '</select>';
		}
		
		$result = runmysqlqueryfetch($query);
		$productsplit = explode('*',$result['description']);
		$grid = '<table width="100%" border="0" cellspacing="0" cellpadding="3" class="table-border-grid">';
		$grid .='<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Slno</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Usage Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Purchase Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Pin Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">Net Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">&nbsp;</td></tr>';
		for($i=0;$i<count($productsplit);$i++)
		{
			$splitproduct[] = explode('$',$productsplit[$i]);
		}
		$slno = 0;
		for($j=0;$j<count($splitproduct);$j++)
			{
				$slno++;
				$i_n++;
				if($i_n%2 == 0)
				$color = "#edf4ff";
				else
				$color = "#f7faff";
				$grid .= '<tr bgcolor='.$color.' align="left">';
				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$slno.'</td>';
				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$splitproduct[$j][1].'</td>';
				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$splitproduct[$j][2].'</td>';
				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$splitproduct[$j][3].'</td>';
				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$splitproduct[$j][4].'</td>';
				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$result['netamount'].'</td>';
				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left"><a onclick="viewinvoice(\''.$result['slno'].'\');" class="resendtext" style = "cursor:pointer"> View >></a> </td>';
				$grid .= '</tr>';
			}
		$grid .= '</table>';
		$responsearray2 = array();
		$responsearray2['errorcode'] = "1";
		$responsearray2['grid'] = $grid;
		$responsearray2['invoiceno'] = $result['invoiceno'];
		$responsearray2['customerid'] = substr($result['customerid'],15);
		$responsearray2['count'] = $fetch13['count'];
		$responsearray2['receiptgrid'] = $receiptgrid;
		echo(json_encode($responsearray2));
		//echo('1'.'^'.$grid.'^'.$result['invoiceno'].'^'.substr($result['customerid'],15).'^'.$fetch13['count']);
			
	}
	break;
	
	case 'cfinvoiceconfirmation':
	{
		$rslno = $_POST['rslno'];
		
		$query = "select distinct inv_mas_product.productname as product,imp_cfentries.usagetype,imp_cfentries.purchasetype,
inv_mas_scratchcard.scratchnumber,imp_cfentries.customerid from imp_cfentries left join inv_mas_product on inv_mas_product.productcode = imp_cfentries.productcode left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = imp_cfentries.cardid where imp_cfentries.invoiceno = '".$rslno."'";
		$result = runmysqlquery($query);
		$grid = '<table width="100%" border="0" cellspacing="0" cellpadding="3" class="table-border-grid">';
		$grid .='<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Slno</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Usage Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Purchase Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Pin Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">Price</td></tr>';
		$slno = 0;
		while($fetchres = mysqli_fetch_array($result))
		{
				$slno++;
				$i_n++;
				if($i_n%2 == 0)
				$color = "#edf4ff";
				else
				$color = "#f7faff";
				$grid .= '<tr   bgcolor='.$color.' align="left">';
				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$slno.'</td>';
				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$fetchres['product'].'</td>';
				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$fetchres['usagetype'].'</td>';
				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$fetchres['purchasetype'].'</td>';
				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$fetchres['scratchnumber'].'</td>';
				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">-</td>';
				$grid .= '</tr>';
				$customerid = substr($fetchres['customerid'],15);
		}
		$grid .= '</table>';
		
		$responsearray3['errorcode'] = "2";
		$responsearray3['grid'] = $grid;
		$responsearray3['invoiceno'] = $rslno;
		$responsearray3['customerid'] = $customerid;
		echo(json_encode($responsearray3));
		//echo('2'.'^'.$grid.'^'.$rslno.'^'.$customerid);
			
	}
	break;
	
	case 'save':
	{
		$lastslno = $_POST['lastslno'];
		$customerreference = $_POST['customerreference'];
		$invoicenumber = $_POST['invoicenumber'];
		$advancecollected = $_POST['advancecollected'];
		$advancecollecreceipt = $_POST['advancecollecreceipt'];
		$advanceamount = $_POST['advanceamount'];
		$advanceremarks = $_POST['advanceremarks'];
		$balancerecoveryremarks = $_POST['balancerecoveryremarks'];
		$podetails = $_POST['podetails'];
		$numberofcompanies = $_POST['numberofcompanies'];
		$numberofmonths = $_POST['numberofmonths'];
		$processingfrommonth = $_POST['processingfrommonth'];
		$additionaltraining = $_POST['additionaltraining'];
		$freedeliverables = $_POST['freedeliverables'];
		$schemeapplicable = $_POST['schemeapplicable'];
		$commissionapplicable = $_POST['commissionapplicable'];
		$commissionname = $_POST['commissionname'];
		$commissionmobile = $_POST['commissionmobile'];
		$commissionemail = $_POST['commissionemail'];
		$commissionvalue = $_POST['commissionvalue'];
		$masterdatabyrelyon = $_POST['masterdatabyrelyon'];
		$masternumberofemployees = $_POST['masternumberofemployees'];
		$salescommitments = $_POST['salescommitments'];
		$attendanceapplicable = $_POST['attendanceapplicable'];
		$attendanceremarks = $_POST['attendanceremarks'];
		
		$attendancedeletefilepath = $_POST['attendancedeletefilepath'];
		$attendancefilepath = $_POST['attendancefilepath'];
		$shipinvoiceapplicable = $_POST['shipinvoiceapplicable'];
		$shipinvoiceremarks = $_POST['shipinvoiceremarks'];
		$shipmanualapplicable = $_POST['shipmanualapplicable'];
		$shipmanualremarks = $_POST['shipmanualremarks'];
		$customizationapplicable = $_POST['customizationapplicable'];
		$customizationremarks = $_POST['customizationremarks'];
		$customizationreffilepath = $_POST['customizationreffilepath'];
		$customizationbackupfilepath = $_POST['customizationbackupfilepath'];
		$customizationstatus = $_POST['customizationstatus'];
		$webimplemenationapplicable = $_POST['webimplemenationapplicable'];
		$webimplemenationremarks = $_POST['webimplemenationremarks'];
		$pofilepath = $_POST['pofilepath'];
		$customizationcustomerview = $_POST['customizationcustomerview'];
	
		$contactarray = $_POST['contactarray'];
		$contactsplit = explode('~',$contactarray);
		$contactcount = count($contactsplit);
		$totalarray = $_POST['totalarray'];
		$totalsplit = explode(',',$totalarray);
		$rafslno = $_POST['rafslno'];
		$deleterafslno = $_POST['deleterafslno'];
		$DPC_startdate = $_POST['DPC_startdate'];
		$DPC_podatedetails = $_POST['DPC_podatedetails'];
		$productcode = $_POST['productcode'];
		if($rafslno != '')
		{
			$slnoraf = explode(',',$rafslno);
			$countdslno = count($slnoraf);
		}
		if($contactarray != '')
		{
			if($contactcount > 1)
			{
				for($i=0;$i<$contactcount;$i++)
				{
					$contactressplit[] = explode('#',$contactsplit[$i]);
				}
			}
			else
			{
				for($i=0;$i<$contactcount;$i++)
				{
					$contactressplit[] = explode('#',$contactsplit[$i]);
				}
			}
		}
		if($lastslno == "")
		{
			$query99 = runmysqlqueryfetch("SELECT ifnull(MAX(slno),0)+1 AS slno FROM imp_implementation");
			$refslno = $query99['slno'];
			for($j=0;$j<$countdslno;$j++)
			{
				$queryres = "Update imp_rafiles set impref = '".$refslno."' where slno = '".$slnoraf[$j]."' ";
				$result11 = runmysqlquery($queryres);
			}

			$query = "Insert into imp_implementation(slno,customerreference,invoicenumber,
advancecollected, advanceamount,advanceremarks,balancerecoveryremarks,podetails,
numberofcompanies,numberofmonths,processingfrommonth,additionaltraining,freedeliverables,schemeapplicable,
commissionapplicable,commissionname,commissionmobile,commissionemail,commissionvalue,masterdatabyrelyon,
masternumberofemployees,salescommitments,attendanceapplicable,attendanceremarks,attendancefilepath
,attendancefiledate,attendancefileattachedby,shipinvoiceapplicable,shipinvoiceremarks,shipmanualapplicable,
shipmanualremarks,customizationapplicable,customizationremarks,customizationreffilepath,customizationreffiledate
,customizationreffileattachedby,customizationbackupfilepath,customizationbackupfiledate, customizationbackupfileattachedby,
customizationstatus,webimplemenationapplicable,webimplemenationremarks,attendancedeletefilepath,podetailspath,podetailsdate,podetailsattachedby,customizationcustomerdisplay,dealerid,createdmodule,createddatetime,createdby,createdip,committedstartdate,advancecollecreceipt,productcode,podate) values('".$refslno."','".$customerreference."','".$invoicenumber."','".$advancecollected."','".$advanceamount."','".$advanceremarks."','".$balancerecoveryremarks."','".$podetails."','".$numberofcompanies."','".$numberofmonths."','".$processingfrommonth."','".$additionaltraining."','".$freedeliverables."','".$schemeapplicable."','".$commissionapplicable."','".$commissionname."','".$commissionmobile."','".$commissionemail."','".$commissionvalue."','".$masterdatabyrelyon."','".$masternumberofemployees."','".$salescommitments."','".$attendanceapplicable."','".$attendanceremarks."','".$attendancefilepath."','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".$shipinvoiceapplicable."','".$shipinvoiceremarks."','".$shipmanualapplicable."','".$shipmanualremarks."','".$customizationapplicable."','".$customizationremarks."','".$customizationreffilepath."','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".$customizationbackupfilepath."','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".$customizationstatus."','".$webimplemenationapplicable."','".$webimplemenationremarks."','".$attendancedeletefilepath."','".$pofilepath."','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".$customizationcustomerview."','".$userid."','dealer_module','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".$_SERVER['REMOTE_ADDR']."','".changedateformat($DPC_startdate)."','".$advancecollecreceipt."','".$productcode."','".changedateformat($DPC_podatedetails)."') ";
			$result = runmysqlquery($query);
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','131','".date('Y-m-d').' '.date('H:i:s')."','".$customerreference."')";
			$eventresult = runmysqlquery($eventquery);
			if($contactarray != '')
			{
				for($j=0;$j<count($contactressplit);$j++)
				{
					$addontype = $contactressplit[$j][0];
					$addonremarks = $contactressplit[$j][1];
					$slno = $contactressplit[$j][2];
					
					$query21 = "Insert into imp_addon(customerid,refslno,addon,remarks) values 
					('".$customerreference."','".$refslno."','".$addontype."','".addslashes($addonremarks)."');";
					$result12 = runmysqlquery($query21);
				}
			}
			$result = sendimplementationmail($refslno,$customerreference,$userid);
			$responsearray4 = array();
			$responsearray4['errorcode'] = "1";
			$responsearray4['errormsg'] = "Customer Record Saved Successfully";
			$responsearray4['refslno'] = $refslno;
			$responsearray4['result'] = $result;
			
			echo(json_encode($responsearray4));
			
			//echo("1^"."Customer Record Saved Successfully"."^".$refslno."^".$result);
		}
		else
		{
			$query4 = 'SELECT * from imp_implementation WHERE slno = "'.$lastslno.'"';
			$queryfetch = runmysqlqueryfetch($query4);
			$e_customerreference = $queryfetch['customerreference'];
			$e_invoicenumber = $queryfetch['invoicenumber'];
			$e_advancecollected = $queryfetch['advancecollected'];
			$e_advanceamount = $queryfetch['advanceamount'];
			$e_advanceremarks = $queryfetch['advanceremarks'];
			$e_balancerecoveryremarks = $queryfetch['balancerecoveryremarks'];
			$e_podetails = $queryfetch['podetails'];
			$e_numberofcompanies = $queryfetch['numberofcompanies'];
			$e_numberofmonths = $queryfetch['numberofmonths'];
			$e_processingfrommonth = $queryfetch['processingfrommonth'];
			$e_additionaltraining = $queryfetch['additionaltraining'];
			$e_freedeliverables = $queryfetch['freedeliverables'];
			$e_schemeapplicable = $queryfetch['schemeapplicable'];
			$e_commissionapplicable = $queryfetch['commissionapplicable'];
			$e_commissionname = $queryfetch['commissionname'];
			$e_commissionmobile = $queryfetch['commissionmobile'];
			$e_commissionemail = $queryfetch['commissionemail'];

			$e_commissionvalue = $queryfetch['commissionvalue'];
			$e_masterdatabyrelyon = $queryfetch['masterdatabyrelyon'];
			$e_masternumberofemployees = $queryfetch['masternumberofemployees'];
			$e_salescommitments = $queryfetch['salescommitments'];
			$e_attendanceapplicable = $queryfetch['attendanceapplicable'];
			$e_attendanceremarks = $queryfetch['attendanceremarks'];
			$e_attendancefilepath = $queryfetch['attendancefilepath'];
			$e_shipinvoiceapplicable = $queryfetch['shipinvoiceapplicable'];
			$e_shipinvoiceremarks = $queryfetch['shipinvoiceremarks'];
			$e_shipmanualapplicable = $queryfetch['shipmanualapplicable'];
			$e_shipmanualremarks = $queryfetch['shipmanualremarks'];
			$e_customizationapplicable = $queryfetch['customizationapplicable'];
			$e_customizationremarks = $queryfetch['customizationremarks'];
			$e_customizationreffilepath = $queryfetch['customizationreffilepath'];
			$e_customizationbackupfilepath = $queryfetch['customizationbackupfilepath'];
			$e_customizationstatus = $queryfetch['customizationstatus'];
			$e_webimplemenationapplicable = $queryfetch['webimplemenationapplicable'];
			$e_webimplemenationremarks = $queryfetch['webimplemenationremarks'];
			$e_implementationstatus = $queryfetch['implementationstatus'];
			$e_podetailspath = $queryfetch['podetailspath'];
			$e_attendancedeletefilepath = $queryfetch['attendancedeletefilepath'];
			$e_customizationcustomerdisplay = $queryfetch['customizationcustomerdisplay'];
			$e_committedstartdate = $queryfetch['committedstartdate'];
			$e_advancecollecreceipt = $queryfetch['advancecollecreceipt'];
			$e_DPC_podatedetails = $queryfetch['podate'];
			$query1 ="SELECT imp_addon.slno, imp_addon.customerid, imp_addon.refslno , imp_mas_addons.addonname as addon, imp_addon.remarks,imp_addon.addon as addonslno from imp_addon left join imp_mas_addons on imp_mas_addons.slno = imp_addon.addon where refslno  = '".$lastslno."'; ";
			$resultfetch = runmysqlquery($query1);
			$valuecount = 0;
			while($fetchres = mysqli_fetch_array($resultfetch))
			{
				if($valuecount > 0)
					$addonarray .= '****';
				$addon = $fetchres['addon'];
				$remarks = $fetchres['remarks'];
				$addonslno = $fetchres['addonslno'];
				
				$addonarray .= $addon.'#'.$remarks.'#'.$addonslno;
				$valuecount++;
			}

			
			$query23 = "Insert into imp_logs_implementation(customerreference,invoicenumber,
advancecollected, advanceamount,advanceremarks,balancerecoveryremarks,podetails,
numberofcompanies,numberofmonths,processingfrommonth,additionaltraining,freedeliverables,schemeapplicable,
commissionapplicable,commissionname,commissionmobile,commissionemail,commissionvalue,masterdatabyrelyon,
masternumberofemployees,salescommitments,attendanceapplicable,attendanceremarks,attendancefilepath
,attendancefiledate,attendancefileattachedby,shipinvoiceapplicable,shipinvoiceremarks,shipmanualapplicable,
shipmanualremarks,customizationapplicable,customizationremarks,customizationreffilepath,customizationreffiledate
,customizationreffileattachedby,customizationbackupfilepath,customizationbackupfiledate, customizationbackupfileattachedby,
customizationstatus,webimplemenationapplicable,webimplemenationremarks,implementationstatus,datetime,system,addondetails,attendancedeletefilepath,podetailspath,podetailsdate,podetailsattachedby,customizationcustomerdisplay,dealerid,createdmodule,createddatetime,createdby,createdip,committedstartdate,advancecollecreceipt,podate) values('".$e_customerreference."','".$e_invoicenumber."','".$e_advancecollected."','".addslashes($e_advanceamount)."','".addslashes($e_advanceremarks)."','".addslashes($e_balancerecoveryremarks)."','".addslashes($e_podetails)."','".$e_numberofcompanies."','".addslashes($e_numberofmonths)."','".addslashes($e_processingfrommonth)."','".addslashes($e_additionaltraining)."','".addslashes($e_freedeliverables)."','".addslashes($e_schemeapplicable)."','".addslashes($e_commissionapplicable)."','".addslashes($e_commissionname)."','".addslashes($e_commissionmobile)."','".addslashes($e_commissionemail)."','".addslashes($e_commissionvalue)."','".$e_masterdatabyrelyon."','".$e_masternumberofemployees."','".addslashes($e_salescommitments)."','".$e_attendanceapplicable."','".addslashes($e_attendanceremarks)."','".$e_attendancefilepath."','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".$e_shipinvoiceapplicable."','".addslashes($e_shipinvoiceremarks)."','".$e_shipmanualapplicable."','".addslashes($e_shipmanualremarks)."','".$e_customizationapplicable."','".addslashes($e_customizationremarks)."','".$e_customizationreffilepath."','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".$e_customizationbackupfilepath."','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".$e_customizationstatus."','".$e_webimplemenationapplicable."','".addslashes($e_webimplemenationremarks)."','".$e_implementationstatus."','".date('Y-m-d').' '.date('H:i:s')."','".$_SERVER['REMOTE_ADDR']."','".$addonarray."','".addslashes($e_attendancedeletefilepath)."','".addslashes($e_podetailspath)."','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".addslashes($e_customizationcustomerdisplay)."','".$userid."','dealer_module','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".$_SERVER['REMOTE_ADDR']."','".changedateformat($e_committedstartdate)."','".$e_advancecollecreceipt."','".changedateformat($e_DPC_podatedetails)."') ";
			$result = runmysqlquery($query23);
			if($deleterafslno != '')
			{
				$queryres ="Delete from imp_rafiles where slno = '".$deleterafslno."';";
				$result11 = runmysqlquery($queryres);
			}
			
			$query11 = "UPDATE imp_implementation SET invoicenumber = '".$invoicenumber."',advancecollected = '".$advancecollected."',advanceamount = '".$advanceamount."',advanceremarks = '".$advanceremarks."',balancerecoveryremarks = '".$balancerecoveryremarks."',podetails = '".$podetails."',numberofcompanies = '".$numberofcompanies."',numberofmonths = '".$numberofmonths."',processingfrommonth = '".$processingfrommonth."',additionaltraining = '".$additionaltraining."',freedeliverables = '".$freedeliverables."',schemeapplicable ='".$schemeapplicable."',commissionapplicable ='".$commissionapplicable."',commissionname ='".$commissionname."',commissionmobile = '".$commissionmobile."' ,commissionemail = '".$commissionemail."' ,commissionvalue = '".$commissionvalue."',masterdatabyrelyon = '".$masterdatabyrelyon."' ,masternumberofemployees = '".$masternumberofemployees."',salescommitments = '".$salescommitments."',attendanceapplicable = '".$attendanceapplicable."',attendanceremarks = '".$attendanceremarks."',attendancefilepath = '".$attendancefilepath."',attendancefiledate = '".date('Y-m-d').' '.date('H:i:s')."',attendancefileattachedby = '".$userid."',shipinvoiceapplicable = '".$shipinvoiceapplicable."',shipinvoiceremarks = '".$shipinvoiceremarks."',shipmanualapplicable = '".$shipmanualapplicable."',shipmanualremarks = '".$shipmanualremarks."',customizationapplicable = '".$customizationapplicable."',customizationremarks = '".$customizationremarks."',customizationreffilepath = '".$customizationreffilepath."',customizationreffiledate = '".date('Y-m-d').' '.date('H:i:s')."',customizationreffileattachedby ='".$userid."',customizationbackupfilepath ='".$customizationbackupfilepath."',customizationbackupfiledate ='".date('Y-m-d').' '.date('H:i:s')."',customizationbackupfileattachedby = '".$userid."' ,customizationstatus = '".$customizationstatus."',webimplemenationapplicable = '".$webimplemenationapplicable."',webimplemenationremarks = '".$webimplemenationremarks."' ,attendancedeletefilepath = '".$attendancedeletefilepath."',podetailspath = '".$pofilepath."',podetailsdate ='".date('Y-m-d').' '.date('H:i:s')."',podetailsattachedby = '".$userid."',customizationcustomerdisplay = '".$customizationcustomerview."',committedstartdate = '".changedateformat($DPC_startdate)."',advancecollecreceipt = '".$advancecollecreceipt."',productcode = '".$productcode."',podate = '".changedateformat($DPC_podatedetails)."' WHERE slno = '".$lastslno."'";
				$result = runmysqlquery($query11);
				$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','132','".date('Y-m-d').' '.date('H:i:s')."','".$customerreference."')";
				$eventresult = runmysqlquery($eventquery);
				
				for($j=0;$j<$countdslno;$j++)
				{
					$queryres = "Update imp_rafiles set impref = '".$lastslno."' where slno = '".$slnoraf[$j]."' ";
					$result11 = runmysqlquery($queryres);
				}
				for($i=0;$i<count($totalsplit);$i++)
				{
					$deleteslno = $totalsplit[$i];
					$query22 = "DELETE FROM imp_addon WHERE slno = '".$deleteslno."'";
					$result = runmysqlquery($query22);
				}
				if($contactarray != '')
				{
					for($j=0;$j<count($contactressplit);$j++)
					{
						$addontype = $contactressplit[$j][0];
						$addonremarks = $contactressplit[$j][1];
						$slno = $contactressplit[$j][2];
						if($slno <> '')
						{
							$query21 = "UPDATE imp_addon SET addon = '".$addontype."',remarks = '".addslashes($addonremarks)."' WHERE slno = '".$slno."'";
							$result = runmysqlquery($query21);
						}
						else
						{
							$query23 = "Insert into imp_addon(customerid,refslno,addon,remarks) values  ('".$customerreference."','".$lastslno."','".$addontype."','".addslashes($addonremarks)."');";
							$result = runmysqlquery($query23);
						}
					}
				}
			
			//echo("2^"."Customer Record Saved Successfully"."^".$lastslno);
			$responsearray41 = array();
			$responsearray41['errorcode'] = "2";
			$responsearray41['errormsg'] = "Customer Record Saved Successfully";
			$responsearray41['refslno'] = $lastslno;
			
			echo(json_encode($responsearray41));
		}
			

		
	}
	break;
	
	case 'implementationvalidate':
	{
		$responsearray22 = array();
		$lastslno = $_POST['lastslno'];
		$query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode, inv_mas_dealer.branch , inv_mas_dealer.region from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode where inv_mas_dealer.slno = '".$userid."';";
		$fetch = runmysqlqueryfetch($query);
		$relyonexecutive = $fetch['relyonexecutive'];
		$district = $fetch['district'];
		$state = $fetch['statecode'];
		$branch = $fetch['branch'];
		$region = $fetch['region'];
		if($relyonexecutive == 'no')
		{
			$dealerpiece = " AND inv_mas_customer.currentdealer = '".$userid."'";
		}
		else
		{
			if(($region == '1') || ($region == '3'))
			{
			
				$dealerpiece = " AND (inv_mas_customer.region = '1' or inv_mas_customer.region = '3')";
			}
			else
			{
				$dealerpiece = " AND (inv_mas_customer.branch = '".$branch."')";
			}
		}
		

		$query1 = "select count(distinct inv_invoicenumbers.slno) as count from inv_invoicenumbers
left join inv_dealercard on inv_invoicenumbers.slno = inv_dealercard.invoiceid
left join inv_mas_customer on inv_mas_customer.slno = substring(inv_invoicenumbers.customerid,16)
left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode
where inv_dealercard.productcode in ('265','816','640','604','643','851','871','874','834','836','835','841','838',
'843','844','850','869','872','875','870','873','876','861','863',
'862','864','262','261','269','266','267','268','237','238','877')
and substring(inv_invoicenumbers.customerid,16)  = '".$lastslno."'".$dealerpiece."";

		$fetch1 = runmysqlqueryfetch($query1);
		
		$query12 = "SELECT count(*) as count from imp_cfentries where substring(imp_cfentries.customerid,16) = '".$lastslno."'";
		$fetch12 = runmysqlqueryfetch($query12);
		
		$queryres ="Delete from imp_rafiles where impref = '' or impref is null ;";
		$result11 = runmysqlquery($queryres);

		if($fetch1['count'] > 0)
		{
			$responsearray22['errorcode'] = "1";
			$responsearray22['errormsg'] = 'Invoice Found';
			echo(json_encode($responsearray22));
			//echo('1^'.'Invoice Found');
			exit;
		}
		elseif($fetch12['count'] > 0)
		{
			$responsearray22['errorcode'] = "1";
			$responsearray22['errormsg'] = 'Invoice Found';
			echo(json_encode($responsearray22));
			//echo('1^'.'Invoice Found');
			exit;
		}
		else
		{
			$responsearray22['errorcode'] = "2";
			$responsearray22['errormsg'] = 'No Invoice Entry for Saral Paypack.';
			echo(json_encode($responsearray22));
			//echo('2^'.'No Invoice Entry for Saral Paypack.');
			exit;
		}
	}
	break;
	case 'deletepath':
	{
		$pathlink = $_POST['pathlink'];
		$lastslno = $_POST['lastslno'];
		unlink($pathlink);
		$query = "Update imp_implementation set attendancedeletefilepath = '',attendancefilepath = '',attendancefiledate ='',attendancefileattachedby='' where slno = '".$lastslno."'";
		$result = runmysqlquery($query);
		$responsearray5 = array();
		$responsearray5['errorcode'] = "1";
		$responsearray5['errormsg'] = 'Success';
		echo(json_encode($responsearray5));
		//echo('1^'.'Success'.$query);
	}
	break;
	case 'approve':
	{
		$responsearray11 = array();
		$customerreference = $_POST['customerreference'];
		$appremarks = $_POST['appremarks'];
		$remarks = $_POST['remarks'];
		$type = $_POST['type'];
		$lastslno = $_POST['lastslno'];
		if($type == 'approved')
		{
			$query = "UPDATE imp_implementation SET branchreject = 'no',branchapproval = 'yes',coordinatorreject='no',approvalremarks = '".$remarks."', advancesnotcollectedremarks = '".$advremarks."',branchapprovalmodule = 'dealer_module', branchapprovaldatetime = '".date('Y-m-d').' '.date('H:i:s')."', branchapprovalip = '".$_SERVER['REMOTE_ADDR']."' , branchapprovalby = '".$userid."' WHERE imp_implementation.customerreference = '".$customerreference."' and imp_implementation.slno = '".$lastslno."'";
			$result = runmysqlquery($query);
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','133','".date('Y-m-d').' '.date('H:i:s')."','".$customerreference."')";
			$eventresult = runmysqlquery($eventquery);
			$result = sendbranchappovedmail($lastslno,$userid);
			$responsearray11['errorcode']= "3";
			$responsearray11['errormsg']= "Implemenation has been Approved.";
		}
		else if($type == 'rejected')
		{
			$query = "UPDATE imp_implementation SET branchreject = 'yes',branchapproval='no',branchrejectremarks = '".$remarks."', branchrejectmodule = 'dealer_module', branchrejectdatetime = '".date('Y-m-d').' '.date('H:i:s')."', branchrejectip = '".$_SERVER['REMOTE_ADDR']."' , branchrejectby = '".$userid."' WHERE imp_implementation.customerreference = '".$customerreference."' and imp_implementation.slno = '".$lastslno."'";
			$result = runmysqlquery($query);
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','134','".date('Y-m-d').' '.date('H:i:s')."','".$customerreference."')";
			$eventresult = runmysqlquery($eventquery);
			$result1 = sendbranchrejectmail($lastslno,$userid);
			if($result1 == 'sucess')
			{
				$responsearray11['errorcode']= "2";
				$responsearray11['errormsg']= "Implemenation Request has been Rejected.";
			}
		}
		echo(json_encode($responsearray11));
		//echo('3^'.'Implemenation has been Approved.');
	}
	break;
	case 'rafilesave':
	{
		$slnoarraylist = '';
		$slnolist = '';
		$requrimentremarks = $_POST['requrimentremarks'];
		$link_value = $_POST['link_value'];
		$lastslno = $_POST['lastslno'];
		$customerreference = $_POST['customerreference'];
		$impraflastslno = $_POST['impraflastslno'];
		if($impraflastslno == '')
		{
			
			$query = "Insert into imp_rafiles (customerreference,attachfilepath, remarks, createddatetime,createdby, createdip, lastupdateddatetime, lastupdatedip, lastupdatedby) values('".$customerreference."','".$link_value."','".$requrimentremarks."','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".$_SERVER['REMOTE_ADDR']."','".date('Y-m-d').' '.date('H:i:s')."','".$_SERVER['REMOTE_ADDR']."','".$userid."');";
			$result = runmysqlquery($query);
			$query12 = "SELECT slno AS slno FROM imp_rafiles where customerreference = '".$customerreference."'";
			$result222 = runmysqlquery($query12);
			while($fetch = mysqli_fetch_array($result222))
			{
				$slnoarraylist .= $fetch['slno'].',';
			}
			$slnolist = trim($slnoarraylist,',');
		}
		else
		{
			$query = "Update imp_rafiles set attachfilepath =  '".$link_value."', remarks = '".$requrimentremarks."', lastupdateddatetime = '".date('Y-m-d').' '.date('H:i:s')."',lastupdatedip = '".$_SERVER['REMOTE_ADDR']."',lastupdatedby = '".$userid."',customerreference =  '".$customerreference."' where slno = '".$impraflastslno."' ; ";
			$result = runmysqlquery($query);
			$query12 = "SELECT slno AS slno FROM imp_rafiles where slno = '".$impraflastslno."'";
			$result222 = runmysqlquery($query12);
			while($fetch = mysqli_fetch_array($result222))
			{
				$slnoarraylist .= $fetch['slno'];
			}
			$slnolist = trim($slnoarraylist,',');
		}
		
		$responsearray6 = array();
		$responsearray6['errorcode'] = "1";
		$responsearray6['errormsg'] = "Record has been marked for Save";
		$responsearray6['slnolist'] = $slnolist;
		echo(json_encode($responsearray6));
		
		//echo('1^Record has been marked for Save'.'^'.$slnolist);
	}
	break;
	case 'rafiledelete':
	{
		$impraflastslno = $_POST['impraflastslno'];
		$customerreference = $_POST['customerreference'];
		$query1 = "select * from imp_rafiles where slno = '".$impraflastslno."';";
		$resultfetch = runmysqlqueryfetch($query1);
		$query12 = "SELECT slno AS slno FROM imp_rafiles where customerreference = '".$customerreference."'";
		$result222 = runmysqlquery($query12);
		while($fetch = mysqli_fetch_array($result222))
		{
			$slnoarraylist .= $fetch['slno'].',';
		}
		$slnolist = trim($slnoarraylist,',');
		//$query = "Delete from imp_rafiles where slno = '".$impraflastslno."';";
		//$result = runmysqlquery($query);
		//unlink($filepath);
		
		$responsearray7 = array();
		$responsearray7['errorcode'] = "1";
		$responsearray7['errormsg'] = "Record has been marked for Save";
		$responsearray7['impraflastslno'] = $impraflastslno;
		$responsearray7['slnolist'] = $slnolist;
		echo(json_encode($responsearray7));
		//echo('2^Record has been marked for Deletion '.'^'.$impraflastslno.'^'.$slnolist);
	}
	break;
	case 'rafilegrid':
	{
		$lastslno = $_POST['lastslno'];
		$startlimit = $_POST['startlimit'];
		$slno = $_POST['slno'];	
		$showtype = $_POST['showtype'];
		$resultcount = "SELECT count(*) as count from imp_rafiles where customerreference = '".$lastslno."';";
		$fetch10 = runmysqlqueryfetch($resultcount);
		$fetchresultcount = $fetch10['count'];;
		if($showtype == 'all')
		$limit = 100000;
		else
		$limit = 10;
		if($startlimit == '')
		{
			$startlimit = 0;
			$slno = 0;
		}
		else
		{
			$startlimit = $slno;
			$slno = $slno;
		}
		
		$query = "SELECT slno,attachfilepath,remarks from imp_rafiles  WHERE customerreference = '".$lastslno."' order by createddatetime DESC LIMIT ".$startlimit.",".$limit.";";
		if($startlimit == 0)
		{
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
			$grid .= '<tr class="tr-grid-header" align ="left"><td nowrap = "nowrap" class="td-border-grid" >Sl No</td><td nowrap = "nowrap" class="td-border-grid">File Name</td><td nowrap = "nowrap" class="td-border-grid">Remarks</td><td nowrap = "nowrap" class="td-border-grid">Download</td></tr>';
		}
		$i_n = 0;
		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_array($result))
		{
			$i_n++;
			$slno++;
			$color;
			$filename = explode('/',$fetch['attachfilepath']);
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$grid .= '<tr class="gridrow" bgcolor='.$color.' onclick="rafgridtoform(\''.$fetch['slno'].'\'); " align ="left">';
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$slno."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$filename[5]."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".gridtrim($fetch['remarks'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid'><div align = 'center'><img src='../images/download-arrow.gif'  onclick = viewfilepath('".$fetch['attachfilepath']."','1') /></div></td>";
			$grid .= "</tr>";
		}
		$fetchcount = mysqli_num_rows($result);
		if($fetchcount == '0')
			$grid .= "<tr><td colspan ='4'><div align='center'><font color='#FF0000'><strong>No Records to Display</strong></font></div></td></tr>";
		$grid .= "</table>";

		if($slno >= $fetchresultcount)
			$linkgrid = '';
			//$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
			$linkgrid = '';
			//$linkgrid .= '<table><tr><td class="resendtext"><div align ="left" style="padding-right:10px"><a onclick ="getmoredatagrid(\''.$startlimit.'\',\''.$slno.'\',\'more\'); " style="cursor:pointer">Show More Records >></a>&nbsp;&nbsp;&nbsp;<a onclick ="getmoredatagrid(\''.$startlimit.'\',\''.$slno.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
		
		//echo '1^'.$grid.'^'.$linkgrid.'^'.$fetchresultcount.'^'.$fetchcount;
		$responsearray8 = array();
		$responsearray8['errorcode'] = "1";
		$responsearray8['grid'] = $grid;
		$responsearray8['linkgrid'] = $linkgrid;
		$responsearray8['fetchresultcount'] = $fetchresultcount;
		$responsearray8['fetchcount'] = $fetchcount;
		echo(json_encode($responsearray8));
	}
	break;
	case 'rafgridtoform':
	{
		$implastslno = $_POST['implastslno'];
		$query = "SELECT * from  imp_rafiles where slno = '".$implastslno."';";
		$fetch = runmysqlqueryfetch($query);
		$filename = explode('/',$fetch['attachfilepath']);
		$responsearray9 = array();
		$responsearray9['errorcode']= "1";
		$responsearray9['filename']= $filename[5];
		$responsearray9['remarks']= $fetch['remarks'];
		$responsearray9['attachfilepath']= $fetch['attachfilepath'];
		echo(json_encode($responsearray9));
		//echo('1^'.$filename[5].'^'.$fetch['remarks'].'^'.$fetch['attachfilepath']);
	}
	break;
	case 'implemenationstatus':
	{
		$lastslno = $_POST['lastslno'];
		$query = "SELECT imp_implementation.branchapproval,imp_implementation.approvalremarks as branchremarks,imp_implementation.branchreject,imp_implementation.branchrejectremarks as branchrejectremarks,
imp_implementation.coordinatorreject, imp_implementation.coordinatorrejectremarks,
imp_implementation.coordinatorapproval, imp_implementation.coordinatorapprovalremarks, 
imp_implementation.implementationstatus, inv_mas_implementer.businessname, imp_implementation.advancecollected ,imp_implementation.advancesnotcollectedremarks from  imp_implementation 
left join inv_mas_implementer on inv_mas_implementer.slno = imp_implementation.assignimplemenation 
where imp_implementation.slno = '".$lastslno."';";
		$fetch = runmysqlqueryfetch($query);
		
		$query1 = "Select assigneddate from imp_implementationdays where imp_implementationdays.impref = '".$lastslno."';";
		$result = runmysqlquery($query1);
		$fetchcount = mysqli_num_rows($result);
		$tablegrid = '<table width="100%" border="0" cellspacing="0" cellpadding="5">';
		$tablegrid .= '<tr><td width="30%"><strong>Assigned To:</strong></td><td width="70%">'.$fetch['businessname'].'</td></tr>';
		$tablegrid .= '<tr><td><strong>No of Days:</strong></td><td>'.$fetchcount.'</td></tr></table>';
		//echo('1^'.$fetch['branchapproval'].'^'.$fetch['coordinatorreject'].'^'.$fetch['coordinatorapproval'].'^'.$fetch['implementationstatus'].'^'.$fetch['branchremarks'].'^'.$fetch['coordinatorrejectremarks'].'^'.$fetch['coordinatorapprovalremarks'].'^'.$tablegrid.'^'.$fetch['advancecollected'].'^'.$fetch['advancesnotcollectedremarks']);
		
		$responsearray12 = array();
		$responsearray12['errorcode'] = "1";
		$responsearray12['branchapproval'] = $fetch['branchapproval'];
		$responsearray12['coordinatorreject'] = $fetch['coordinatorreject'];
		$responsearray12['coordinatorapproval'] = $fetch['coordinatorapproval'];
		$responsearray12['implementationstatus'] = $fetch['implementationstatus'];
		$responsearray12['branchremarks'] = $fetch['branchremarks'];
		$responsearray12['coordinatorrejectremarks'] = $fetch['coordinatorrejectremarks'];
		$responsearray12['coordinatorapprovalremarks'] = $fetch['coordinatorapprovalremarks'];
		$responsearray12['tablegrid'] = $tablegrid;
		$responsearray12['advancecollected'] = $fetch['advancecollected'];
		$responsearray12['advancesnotcollectedremarks'] = $fetch['advancesnotcollectedremarks'];
		$responsearray12['branchrejectremarks'] = $fetch['branchrejectremarks'];
		$responsearray12['branchreject'] = $fetch['branchreject'];
		echo(json_encode($responsearray12));
		
	}
	break;
	
	case 'resetfunc':
	{
		$responsearray10 = array();
		$lastslno = $_POST['lastslno'];
		$customerreference = $_POST['customerreference'];
		$resultcount = "SELECT count(*) as count from imp_implementation where imp_implementation.slno = '".$lastslno."' and imp_implementation.customerreference = '".$customerreference."';";
		$fetch10 = runmysqlqueryfetch($resultcount);
		
		if($fetch10['count'] == '0')
		{
			$queryres ="Delete from imp_rafiles where impref = '' or impref is null ;";
			$result11 = runmysqlquery($queryres);
			
			$responsearray10['errorcode'] = "2";
			$responsearray10['errormsg'] = "Record is not Found!!.";
			echo(json_encode($responsearray10));
			//echo('2^'.'Record is not Found!!.');
		}
		else
		{
			$responsearray10['errorcode'] = "1";
			$responsearray10['errormsg'] = "Record is Found!!.";
			echo(json_encode($responsearray10));
			//echo('1^'.'Record is Found!!.');
		}
	}
	
	break;
	case 'customizationgrid':
	{
		$implastslno = $_POST['imprslno'];
		$startlimit = $_POST['startlimit'];
		$slno = $_POST['slno'];	
		$showtype = $_POST['showtype'];
		$resultcount = "SELECT count(*) as count from imp_customizationfiles where imp_customizationfiles.impref = '".$implastslno."';";
		$fetch10 = runmysqlqueryfetch($resultcount);
		$fetchresultcount = $fetch10['count'];;
		if($showtype == 'all')
		$limit = 100000;
		else
		$limit = 10;
		if($startlimit == '')
		{
			$startlimit = 0;
			$slno = 0;
		}
		else
		{
			$startlimit = $slno;
			$slno = $slno;
		}
		
		$query = "SELECT imp_customizationfiles.slno,imp_customizationfiles.remarks,imp_customizationfiles.attachfilepath from imp_customizationfiles  WHERE imp_customizationfiles.impref = '".$implastslno."' order by createddatetime DESC LIMIT ".$startlimit.",".$limit.";";
		if($startlimit == 0)
		{
			$grid = '<table width="100%" cellpadding="2" cellspacing="0" class="table-border-grid">';
			$grid .= '<tr class="tr-grid-header" align ="left"><td nowrap = "nowrap" class="td-border-grid" >Sl No</td><td nowrap = "nowrap" class="td-border-grid">Remarks</td><td nowrap = "nowrap" class="td-border-grid">Downloadlink</td></tr>';
		}
		$i_n = 0;
		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_array($result))
		{
			$i_n++;
			$slno++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$grid .= '<tr class="gridrow" bgcolor='.$color.'  align ="left">';
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$slno."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".gridtrim($fetch['remarks'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid'><div align = 'center'><img src='../images/download-arrow.gif'  onclick = viewfilepath('".$fetch['attachfilepath']."','1') /></div></td>";
			$grid .= "</tr>";
		}
		$grid .= "</table>";
		$fetchcount = mysqli_num_rows($result);
		if($slno >= $fetchresultcount)
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
			$linkgrid .= '<table><tr><td class="resendtext"><div align ="left" style="padding-right:10px"><a onclick ="getmorecustomerregistration(\''.$startlimit.'\',\''.$slno.'\',\'more\'); " style="cursor:pointer">Show More Records >></a>&nbsp;&nbsp;&nbsp;<a onclick ="getmorecustomerregistration(\''.$startlimit.'\',\''.$slno.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
		
		$responsearray13 = array();
		$responsearray13['errorcode'] = "1";
		$responsearray13['grid'] = $grid;
		$responsearray13['linkgrid'] = $linkgrid;
		$responsearray13['fetchresultcount'] = $fetchresultcount;
		echo(json_encode($responsearray13));
		//echo '1^'.$grid.'^'.$linkgrid.'^'.$fetchresultcount;
	}
	break;
	
	case 'appovedmail':
	{
		
		$lastslno = $_POST['lastslno'];
		$result = sendbranchappovedmail($lastslno,$userid);
		$responsearray14 = array();
		$responsearray14['errorcode']= '1';
		$responsearray14['errormsg']= 'Mail has been sent Successfully.';
		echo(json_encode($responsearray14));
		//echo("1^Mail has been sent Successfully.");
	}
	break;
	
	case 'updatedmail':
	{
		
		$lastslno = $_POST['lastslno'];
		sendupdateimpmail($lastslno,$userid);
		$responsearray15 = array();
		$responsearray15['errorcode']= '1';
		$responsearray15['errormsg']= 'Mail has been sent Successfully.';
		echo(json_encode($responsearray15));
		//echo("1^Mail has been sent Successfully.");
	}
	break;
	
	case 'shipinvoicemail':
	{
		
		$customerid = $_POST['customerid'];
		$type = $_POST['type'];
		$remarks = $_POST['remarks'];
		$result = sendshippmentmail($customerid,$type,$remarks);
		$responsearray16 = array();
		$responsearray16['errorcode']= '1';
		$responsearray16['result']= $result;
		echo(json_encode($responsearray16));
		//echo("1^".$result);
	}
	break;
	
	case 'searchbycustomerid':
	{
		$responsearray17 = array();
		$customerid = $_POST['customerid'];
		$customeridlen = strlen($customerid);
		$customerid = ($_POST['customerid'] == 5)?($_POST['customerid']):(substr($customerid, $customeridlen - 5));
		
		$query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode,  inv_mas_dealer.branch ,  inv_mas_dealer.region from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode where inv_mas_dealer.slno = '".$userid."';";
		$fetch = runmysqlqueryfetch($query);
		$relyonexecutive = $fetch['relyonexecutive'];
		$district = $fetch['district'];
		$state = $fetch['statecode'];
		$branch = $fetch['branch'];
		$region = $fetch['region'];
		if($relyonexecutive == 'no')
		{
			$dealerpiece = " AND inv_mas_customer.currentdealer = '".$userid."'";
		}
		else
		{
			if(($region == '1') || ($region == '3'))
			{
			
				$dealerpiece = " AND (inv_mas_customer.region = '1' or inv_mas_customer.region = '3')";
			}
			else
			{
				$dealerpiece = " AND (inv_mas_customer.branch = '".$branch."')";
			}
		}
		
		$query1 = "SELECT count(*) as count from inv_mas_customer left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode where inv_mas_customer.slno = '".$customerid."'".$dealerpiece."";
		$fetch1 = runmysqlqueryfetch($query1);
			
		if($fetch1['count'] > 0)
			{
				$query2 = "SELECT inv_mas_customer.slno as slno,inv_mas_customer.businessname as businessname,inv_mas_customer.customerid as customerid from inv_mas_customer left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode where inv_mas_customer.slno = '".$customerid."'".$dealerpiece."";
				$fetch123 = runmysqlqueryfetch($query2);
				$responsearray17['errorcode']= '1';
				$responsearray17['slno'] = $fetch123['slno'];
				$responsearray17['customerid'] = $fetch123['customerid'];
				$responsearray17['businessname'] = $fetch123['businessname'];
				echo(json_encode($responsearray17));
				//echo('1^'.$fetch123['slno'].'^'.$fetch123['customerid'].'^'.$fetch123['businessname']);
			}
			else
			{
				$responsearray17['errorcode']= '2';
				$responsearray17['errormsg']= 'Not Found the details';
				echo(json_encode($responsearray17));
				//echo('2^'.'Not Found the details');
			}
	}
	break;
	
	case 'searchcustomerlist':
	{
		$databasefield = $_POST['databasefield'];
		$textfield = $_POST['textfield'];
		$state2 = $_POST['state'];
		$region2 = $_POST['region'];
		$dealer2 = $_POST['dealer2'];
		$branch2 = $_POST['branch2'];
		$category2= $_POST['category2'];
		$type2= $_POST['type2'];
		$statuslist = $_POST['statuslist'];
		$statuslistsplit = explode(',',$statuslist);
		$countsummarize = count($statuslistsplit);
		for($i = 0; $i<$countsummarize; $i++)
		{
			if($i < ($countsummarize-1))
					$appendor = 'or'.' ';
				else
					$appendor = '';
			switch($statuslistsplit[$i])
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
				case 'status9' :
				{
					$statuspiece = "right(imp_cfentries.customerid,5) not in (select customerreference from imp_implementation)";
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
		
		$regionpiece = ($region2 == "")?(""):(" AND inv_mas_customer.region = '".$region2."' ");
		$district_typepiece = ($district2 == "")?(""):(" AND inv_mas_customer.district = '".$district2."' ");
		$dealer_typepiece = ($dealer2 == "")?(""):(" AND inv_mas_customer.currentdealer = '".$dealer2."' ");
		$branchpiece = ($branch2 == "")?(""):(" AND inv_mas_customer.branch = '".$branch2."' ");
		if($type2 == 'Not Selected')
		{
			$typepiece = ($type2 == "")?(""):(" AND inv_mas_customer.type = ''");
		}
		else
		{
			$typepiece = ($type2 == "")?(""):(" AND inv_mas_customer.type = '".$type2."' ");
		}
		if($category2 == 'Not Selected')
		{
			$categorypiece = ($category2 == "")?(""):(" AND inv_mas_customer.category = ''");
		}
		else
		{
			$categorypiece = ($category2 == "")?(""):(" AND inv_mas_customer.category = '".$category2."' ");
		}
		
		// Customer Listing
		$query1 = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode , inv_mas_dealer.branch , inv_mas_dealer.region from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
 where inv_mas_dealer.slno = '".$userid."';";
		$fetch1 = runmysqlqueryfetch($query1);
		$relyonexecutive = $fetch1['relyonexecutive'];
		$district = $fetch1['district'];
		$state = $fetch1['statecode'];
		$branch = $fetch1['branch'];
		$region = $fetch1['region'];
		if($relyonexecutive == 'no')
		{
			$customerlistpiece = " AND inv_mas_customer.currentdealer = '".$userid."'";
		}
		else
		{
			if(($region == '1') || ($region == '3'))
			{
			
				$customerlistpiece = " AND (inv_mas_customer.region = '1' or inv_mas_customer.region = '3')";
			}
			else
			{
				$customerlistpiece = " AND (inv_mas_customer.branch = '".$branch."')";
			}
		}
		
		
		
		
		switch($databasefield)
		{
			case 'slno':
			{
				$customeridlen = strlen($textfield);
				$lastcustomerid = cusidsplit($textfield);
				$query = "select DISTINCT inv_mas_customer.slno,inv_mas_customer.businessname from inv_mas_customer 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno  
left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode
left join imp_implementation on imp_implementation.customerreference = inv_mas_customer.slno
left join imp_implementationdays on imp_implementationdays.impref = imp_implementation.slno
left join imp_cfentries on right(imp_cfentries.customerid,5) = inv_mas_customer.slno
where (inv_mas_customer.slno LIKE '%".$lastcustomerid."%' OR inv_mas_customer.customerid LIKE '%".$lastcustomerid."%') ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$typepiece.$categorypiece.$branchpiece.$finalliststatus." ".$customerlistpiece." ORDER BY inv_mas_customer.businessname"; //echo($query);exit;
				$result = runmysqlquery($query);
			}
			break;
			
			case 'contactperson':
			{
				$query = "select DISTINCT inv_mas_customer.slno,inv_mas_customer.businessname from inv_mas_customer 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno  
left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode
left join imp_implementation on imp_implementation.customerreference = inv_mas_customer.slno
left join imp_implementationdays on imp_implementationdays.impref = imp_implementation.slno
left join imp_cfentries on right(imp_cfentries.customerid,5) = inv_mas_customer.slno
where inv_contactdetails.contactperson LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$typepiece.$categorypiece.$branchpiece.$finalliststatus." ".$customerlistpiece." ORDER BY inv_mas_customer.businessname";
				$result = runmysqlquery($query);
			}
			break;
			
			case 'place':
			{
				$query = "select DISTINCT inv_mas_customer.slno,inv_mas_customer.businessname from inv_mas_customer 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno  
left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode
left join imp_implementation on imp_implementation.customerreference = inv_mas_customer.slno
left join imp_implementationdays on imp_implementationdays.impref = imp_implementation.slno
left join imp_cfentries on right(imp_cfentries.customerid,5) = inv_mas_customer.slno
where inv_mas_customer.place LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$typepiece.$categorypiece.$branchpiece.$finalliststatus." ".$customerlistpiece." ORDER BY inv_mas_customer.businessname";
				$result = runmysqlquery($query);
			}
			break;
			
			case 'phone':
			{
				$query = "select DISTINCT inv_mas_customer.slno,inv_mas_customer.businessname from inv_mas_customer 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno  
left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode
left join imp_implementation on imp_implementation.customerreference = inv_mas_customer.slno
left join imp_implementationdays on imp_implementationdays.impref = imp_implementation.slno
left join imp_cfentries on right(imp_cfentries.customerid,5) = inv_mas_customer.slno
where (inv_contactdetails.phone LIKE '%".$textfield."%'  OR inv_contactdetails.cell LIKE '%".$textfield."%') ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$typepiece.$categorypiece.$branchpiece.$finalliststatus." ".$customerlistpiece." ORDER BY inv_mas_customer.businessname";
				$result = runmysqlquery($query);
			}
			break;
			
			case 'emailid':
			{
				$query = "select DISTINCT inv_mas_customer.slno,inv_mas_customer.businessname from inv_mas_customer 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno  
left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode
left join imp_implementation on imp_implementation.customerreference = inv_mas_customer.slno
left join imp_implementationdays on imp_implementationdays.impref = imp_implementation.slno
left join imp_cfentries on right(imp_cfentries.customerid,5) = inv_mas_customer.slno
where inv_contactdetails.emailid LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$typepiece.$categorypiece.$branchpiece.$finalliststatus." ".$customerlistpiece." ORDER BY inv_mas_customer.businessname";
				$result = runmysqlquery($query);
			}
			break;
			
			default:
			{
				$query = "select DISTINCT inv_mas_customer.slno,inv_mas_customer.businessname from inv_mas_customer 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno  
left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode
left join imp_implementation on imp_implementation.customerreference = inv_mas_customer.slno
left join imp_implementationdays on imp_implementationdays.impref = imp_implementation.slno
left join imp_cfentries on right(imp_cfentries.customerid,5) = inv_mas_customer.slno
where inv_mas_customer.businessname LIKE  '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$typepiece.$categorypiece.$branchpiece.$finalliststatus." ".$customerlistpiece." ORDER BY inv_mas_customer.businessname"; 
				$result = runmysqlquery($query);
			}
			break;
		}
		$responsearray18 = array();
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$responsearray18[$count] = $fetch['businessname'].'^'.$fetch['slno'];
			$count++;
		}
		echo(json_encode($responsearray18));
	}
	break;
	case 'filtercustomerlist':
	{
		$status = $_POST['impsearch'];
		$statuspiece = '';
		if($status == 'Awaiting Branch Head Approval')
		{
			$statuspiece = "AND imp_implementation.branchreject = 'no' AND imp_implementation.branchapproval = 'no' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'no' AND imp_implementation.implementationstatus = 'pending'";
		}
		elseif($status == 'Fowarded back to Sales Person')
		{
			$statuspiece = "AND imp_implementation.branchreject = 'yes' AND imp_implementation.branchapproval = 'no'  AND imp_implementation.implementationstatus = 'pending'";
		}
		else if($status == 'Awaiting Co-ordinator Approval')
		{
			$statuspiece = "AND imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'no' AND imp_implementation.implementationstatus = 'pending'";
		}
		else if($status == 'Fowarded back to Branch Head')
		{
			$statuspiece = "AND imp_implementation.branchapproval = 'no' AND imp_implementation.coordinatorreject = 'yes' AND imp_implementation.coordinatorapproval = 'no' AND imp_implementation.implementationstatus = 'pending'";
		}
		else if($status == 'Implementation, Yet to be Assigned')
		{
			$statuspiece = "AND imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'pending'";
		}
		else if($status == 'Assigned For Implementation')
		{
			$statuspiece = "AND imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'assigned'";
		}
		else if($status == 'Implementation in progess')
		{
			$statuspiece = "AND imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'progess'";
		}
		else if($status == 'Implementation Completed')
		{
			$statuspiece = "AND imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'completed'";
		}
		
				// Customer Listing
		$query1 = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode, inv_mas_dealer.branch , inv_mas_dealer.region from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
 where inv_mas_dealer.slno = '".$userid."';";
		$fetch1 = runmysqlqueryfetch($query1);
		$relyonexecutive = $fetch1['relyonexecutive'];
		$district = $fetch1['district'];
		$state = $fetch1['statecode'];
		$branch = $fetch1['branch'];
		$region = $fetch1['region'];
		if($relyonexecutive == 'no')
		{
			$customerlistpiece = " AND inv_mas_customer.currentdealer = '".$userid."'";
		}
		else
		{
			if(($region == '1') || ($region == '3'))
			{
			
				$customerlistpiece = " AND (inv_mas_customer.region = '1' or inv_mas_customer.region = '3')";
			}
			else
			{
				$customerlistpiece = " AND (inv_mas_customer.branch = '".$branch."')";
			}
		}
		

		$query = "select DISTINCT inv_mas_customer.slno,inv_mas_customer.businessname from inv_mas_customer 
left join imp_implementation on imp_implementation.customerreference = inv_mas_customer.slno
left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode
where inv_mas_customer.slno <> '99999999999' ".$statuspiece."  ".$customerlistpiece." ORDER BY businessname";
		$result = runmysqlquery($query);
		$responsearray19 = array();
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$responsearray19[$count] = $fetch['businessname'].'^'.$fetch['slno'];
			$count++;
		}
		echo(json_encode($responsearray19));
	}
	break;
	case 'advcollectmail':
	{
		$responsearray20= array();
		$lastslno = $_POST['lastslno'];
		$customerid = $_POST['customerid'];
		$type = $_POST['type'];
		$result = sendadvcollectmail($lastslno,$customerid,$type,$userid);
		if($result == 'sucess')
		{
			$responsearray20['errorcode']= '1';
			$responsearray20['errormsg']= 'Mail sent Successfully.';
		}
		echo(json_encode($responsearray20));
	}
	break;
	case 'invoicecfdetailstoform':
	{
		$responsearray21 = array();
		$implslno = $_POST['implslno'];
		$query11 = "SELECT * from imp_implementation where  imp_implementation.slno = '".$implslno."'";
		$fetch = runmysqlqueryfetch($query11);
		$advancecollecreceipt = $fetch['advancecollecreceipt'];
		$productcodevalue = $fetch['productcode'];
		
		$query13 = "SELECT count(*) as count from imp_cfentries where  imp_cfentries.invoiceno = '".$fetch['invoicenumber']."'";
		$fetch13 = runmysqlqueryfetch($query13);
		if($fetch13['count'] == 0)
		{
			$query = "select distinct inv_invoicenumbers.slno, inv_invoicenumbers.customerid,
inv_invoicenumbers.businessname, inv_invoicenumbers.description, inv_invoicenumbers.invoiceno, inv_invoicenumbers.netamount
from inv_invoicenumbers where  inv_invoicenumbers.invoiceno = '".$fetch['invoicenumber']."'";
			$result = runmysqlqueryfetch($query);
			
			$productsplit = explode('*',$result['description']);
			$grid .= '<table width="100%" border="0" cellspacing="0" cellpadding="3" class="table-border-grid">';
			$grid .='<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Slno</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Usage Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Purchase Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Pin Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">Net Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">&nbsp;</td></tr>';
			for($i=0;$i<count($productsplit);$i++)
			{
				$splitproduct[] = explode('$',$productsplit[$i]);
			}
			$slno = 0;
			for($j=0;$j<count($splitproduct);$j++)
				{
					$slno++;
					$i_n++;
					if($i_n%2 == 0)
					$color = "#edf4ff";
					else
					$color = "#f7faff";
					$grid .= '<tr  bgcolor='.$color.' align="left">';
					$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$slno.'</td>';
					$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$splitproduct[$j][1].'</td>';
					$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$splitproduct[$j][2].'</td>';
					$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$splitproduct[$j][3].'</td>';
					$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$splitproduct[$j][4].'</td>';
					$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$result['netamount'].'</td>';
					$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left"><a onclick="viewinvoice(\''.$result['slno'].'\');" class="resendtext" style = "cursor:pointer"> View >></a> </td>';
					$grid .= '</tr>';
				}
			$grid .= '</table>';
			
			if($advancecollecreceipt <> '')
			{ 
				$query989 = "select count(*) as count from inv_mas_receipt where invoiceno = '".$result['slno']."'";
				$result11 = runmysqlqueryfetch($query989);
				if($result11['count'] == 0)
				{
					$receiptgrid = '<select name="receipt" class="diabledatefield" id="receipt" style="width: 201px;" >
		<option selected="selected" value="" >---Select---</option></select>';
				}
				else
				{
					$query66 = "select * from inv_mas_receipt where invoiceno = '".$result['slno']."'";
					$result11 = runmysqlquery($query66);
					$receiptgrid = '<select name="receipt" class="diabledatefield" id="receipt" style="width: 201px;" >';
					$receiptgrid .= '<option  value="" >---Select---</option>';
					while($fetch55 = mysqli_fetch_array($result11))
					{
						$receiptgrid .= '<option  ' . ($advancecollecreceipt == $fetch55['slno'] ? 'selected="selected"' : '') . ' value="'.$fetch55['slno'].'">Receipt/'.$fetch55['slno'].' '.'-'.' '.'Rs'.' '.$fetch55['receiptamount'].'</option>'; 
						
						//$receiptgrid .= '<option value="'.$fetch55['slno'].'">Receipt/'.$fetch55['slno'].' '.'-'.' '.'Rs'.' '.$fetch55['receiptamount'].'</option>';
					}
					$receiptgrid .= '</select>';
				}
			}
			else
			{
				$query989 = "select count(*) as count from inv_mas_receipt where invoiceno = '".$result['slno']."'";
				$result11 = runmysqlqueryfetch($query989);
				if($result11['count'] == 0)
				{
					$receiptgrid = '<select name="receipt" class="diabledatefield" id="receipt" style="width: 201px;" >
		<option selected="selected" value="" >---Select---</option></select>';
				}
				else
				{
					$query66 = "select * from inv_mas_receipt where invoiceno = '".$result['slno']."'";
					$result11 = runmysqlquery($query66);
					$receiptgrid = '<select name="receipt" class="diabledatefield" id="receipt" style="width: 201px;" >';
					$receiptgrid .= '<option  value="" selected="selected" >---Select---</option>';
					while($fetch55 = mysqli_fetch_array($result11))
					{
						$receiptgrid .= '<option  value="'.$fetch55['slno'].'">Receipt/'.$fetch55['slno'].' '.'-'.' '.'Rs'.' '.$fetch55['receiptamount'].'</option>'; 
						
						//$receiptgrid .= '<option value="'.$fetch55['slno'].'">Receipt/'.$fetch55['slno'].' '.'-'.' '.'Rs'.' '.$fetch55['receiptamount'].'</option>';
					}
					$receiptgrid .= '</select>';
				}
			}
			$responsearray21['errorcode'] = "1";
			$responsearray21['customerreference'] = $fetch['customerreference'];
			$responsearray21['invoicenumber'] = $fetch['invoicenumber'];
			$responsearray21['grid'] = $grid;
			$responsearray21['receiptgrid'] = $receiptgrid;
			$responsearray21['advanceremarks'] = $fetch['advanceremarks'];
			$responsearray21['advancecollected'] = $fetch['advancecollected'];
			$responsearray21['productcode'] = $productcodevalue;
			echo(json_encode($responsearray21));
			
		}
		else
		{
			$query = "select distinct inv_mas_product.productname as product,imp_cfentries.usagetype,imp_cfentries.purchasetype,
inv_mas_scratchcard.scratchnumber,imp_cfentries.customerid,imp_cfentries.productcode as productcode from imp_cfentries left join inv_mas_product on inv_mas_product.productcode = imp_cfentries.productcode left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = imp_cfentries.cardid where imp_cfentries.invoiceno = '".$fetch['invoicenumber']."'";
			$result = runmysqlquery($query);
			$grid = '<table width="100%" border="0" cellspacing="0" cellpadding="3" class="table-border-grid">';
			$grid .='<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Slno</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Usage Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Purchase Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Pin Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">Price</td></tr>';
			$slno = 0;
			while($fetchres = mysqli_fetch_array($result))
			{
					$slno++;
					$i_n++;
					if($i_n%2 == 0)
					$color = "#edf4ff";
					else
					$color = "#f7faff";
					$grid .= '<tr bgcolor='.$color.' align="left">';
					$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$slno.'</td>';
					$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$fetchres['product'].'</td>';
					$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$fetchres['usagetype'].'</td>';
					$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$fetchres['purchasetype'].'</td>';
					$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$fetchres['scratchnumber'].'</td>';
					$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">-</td>';
					$grid .= '</tr>';
					$customerid = substr($fetchres['customerid'],15);
			}
			$grid .= '</table>';
			
			$responsearray21['errorcode'] = "2";
			$responsearray21['customerreference'] = $fetch['customerreference'];
			$responsearray21['invoicenumber'] = $fetch['invoicenumber'];
			$responsearray21['grid'] = $grid;
			$responsearray21['advanceamount'] = $fetch['advanceamount'];
			$responsearray21['advanceremarks'] = $fetch['advanceremarks'];
			$responsearray21['advancecollected'] = $fetch['advancecollected'];
			$responsearray21['productcode'] = $fetch['productcode'];
			echo(json_encode($responsearray21));
		}
	break;	
}
			
	
}

?>