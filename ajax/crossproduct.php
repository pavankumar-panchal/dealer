<?php

ob_start("ob_gzhandler");

include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');
include('../inc/checksession.php');

if(imaxgetcookie('dealeruserid')<> '') 
$userid = imaxgetcookie('dealeruserid');
else
{ 
	echo(json_encode('Thinking to redirect'));
	exit;
}

$lastslno = $_POST['lastslno'];
$switchtype = $_POST['switchtype'];

switch($switchtype)
{
	case 'generatecustomerlist':
	{
		$limit = $_POST['limit'];
		$startindex = $_POST['startindex'];
		$query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode,inv_mas_dealer.telecaller,inv_mas_dealer.branch,inv_mas_dealer.region
from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
 where inv_mas_dealer.slno = '".$userid."';";
		$fetch = runmysqlqueryfetch($query);
		$relyonexecutive = $fetch['relyonexecutive'];
		$telecaller = $fetch['telecaller'];
		$branch = $fetch['branch'];
		$district = $fetch['district'];
		$state = $fetch['statecode'];
		$region = $fetch['region'];
		if($telecaller == 'yes')
		{
			$query = "select slno as slno, businessname as businessname from inv_mas_customer where branch in( select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."') order by businessname LIMIT ".$startindex.",".$limit.";";
			$result = runmysqlquery($query);
			if(mysqli_num_rows($result) == 0)
			{
				$query = "select slno as slno, businessname as businessname from inv_mas_customer where region = '2' order by businessname LIMIT ".$startindex.",".$limit.";";
				$result = runmysqlquery($query);
			}
		}
		else
		{
			if($relyonexecutive == 'no')
			{
				$query = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname  from inv_mas_customer where inv_mas_customer.currentdealer = '".$userid."' order by businessname LIMIT ".$startindex.",".$limit.";";
				$result = runmysqlquery($query);
			}
			else
			{
				if(($region == '1') || ($region == '3'))
				{
					$query = "select slno as slno, businessname as businessname from inv_mas_customer where (inv_mas_customer.region = '1' or inv_mas_customer.region = '3') order by businessname LIMIT ".$startindex.",".$limit.";";
					$result = runmysqlquery($query);
				}
				else
				{
					
					$query = "select slno as slno, businessname as businessname from inv_mas_customer where branch = '".$branch."' order by businessname LIMIT ".$startindex.",".$limit.";";
					$result = runmysqlquery($query);
				}
			}
		}
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
	case 'getcustomercount':
	{
		$customerarraycount = array();
		$query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode,inv_mas_dealer.telecaller,inv_mas_dealer.branch,inv_mas_dealer.region
from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
 where inv_mas_dealer.slno = '".$userid."';";
		$fetch = runmysqlqueryfetch($query);
		$relyonexecutive = $fetch['relyonexecutive'];
		$telecaller = $fetch['telecaller'];
		$branch = $fetch['branch'];
		$district = $fetch['district'];
		$state = $fetch['statecode'];
		$region = $fetch['region'];
		if($telecaller == 'yes')
		{
			$query = "select slno as slno, businessname as businessname from inv_mas_customer where branch in( select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."') order by businessname;";
			$result = runmysqlquery($query);
			if(mysqli_num_rows($result) == 0)
			{
				$query = "select slno as slno, businessname as businessname from inv_mas_customer where region = '2' order by businessname;";
				$result = runmysqlquery($query);
			}
		}
		else
		{
			if($relyonexecutive == 'no')
			{
				$query = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname  from inv_mas_customer where inv_mas_customer.currentdealer = '".$userid."' order by businessname;";
				$result = runmysqlquery($query);
			}
			else
			{
				if(($region == '1') || ($region == '3'))
				{
					$query = "select slno as slno, businessname as businessname from inv_mas_customer where (inv_mas_customer.region = '1' or inv_mas_customer.region = '3') order by businessname;";
					$result = runmysqlquery($query);
				}
				else
				{
					
					$query = "select slno as slno, businessname as businessname from inv_mas_customer where branch = '".$branch."' order by businessname;";
					$result = runmysqlquery($query);
				}
			}
		}		
		$count = mysqli_num_rows($result);
		$customerarraycount['count'] = $count;
		echo(json_encode($customerarraycount));
	}
	break;
	
	case 'customerdetailstoform':
	{
		$lastslno = $_POST['lastslno'];
		
		$query = "select inv_mas_customer.slno as slno, inv_mas_customer.businessname as companyname,inv_mas_customer.place,inv_mas_customer.address,inv_mas_region.category as region,inv_mas_branch.branchname as branch	,inv_mas_customercategory.businesstype,inv_mas_customertype.customertype,inv_mas_dealer.businessname as dealername,inv_mas_customer.stdcode, inv_mas_customer.pincode,inv_mas_district.districtname, inv_mas_state.statename,inv_mas_customer.customerid  from inv_mas_customer left join inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode where inv_mas_customer.slno = '".$lastslno."'".$dealerpiece."";
		$fetch = runmysqlqueryfetch($query);
		
		// Fetch Contact Details
		$querycontactdetails = "select  phone,cell,emailid,contactperson from inv_contactdetails where customerid = '".$fetch['slno']."'";
		$resultcontactdetails = runmysqlquery($querycontactdetails);
		// contact Details
		$contactvalues = '';
		$phoneres = '';
		$cellres = '';
		$emailidres = '';
				
		while($fetchcontactdetails = mysqli_fetch_array($resultcontactdetails))
		{
			$contactperson = $fetchcontactdetails['contactperson'];
			$phone = $fetchcontactdetails['phone'];
			$cell = $fetchcontactdetails['cell'];
			$emailid = $fetchcontactdetails['emailid'];
			
			$contactvalues .= $contactperson;
			$contactvalues .= appendcomma($contactperson);
			$phoneres .= $phone;
			$phoneres .= appendcomma($phone);
			$cellres .= $cell;
			$cellres .= appendcomma($cell);
			$emailidres .= $emailid;
			$emailidres .= appendcomma($emailid);
		}
		
		$customerid = ($fetch['customerid'] == '')?'':cusidcombine($fetch['customerid']);	
		$pincode = ($resultfetch['pincode'] == '')?'':'Pin - '.$fetch['pincode'];
		$address = $fetch['address'].', '.$fetch['districtname'].', '.$fetch['statename'].$pincode;
		$phonenumber = explode(',',trim($phoneres,','));
		$phone = $phonenumber[0];
		$cellnumber = explode(',',trim($cellres,','));
		$cell = $cellnumber[0];
		$emailid = explode(',',trim($emailidres,','));
		$emailidplit = $emailid[0];
		
		$responsearray = array();
		$responsearray['errorcode'] = "1";
		$responsearray['slno'] = $fetch['slno'];
		$responsearray['customerid'] = $customerid;
		$responsearray['companyname'] = $fetch['companyname'];
		$responsearray['contactvalues'] = trim($contactvalues,',');
		$responsearray['address'] = $address;
		$responsearray['phone'] = $phone;
		$responsearray['cell'] = $cell;
		$responsearray['emailidplit'] = $emailidplit;
		$responsearray['region'] = $fetch['region'];
		$responsearray['branch'] = $fetch['branch'];
		$responsearray['businesstype'] = $fetch['businesstype'];
		$responsearray['customertype'] = $fetch['customertype'];
		$responsearray['dealername'] = $fetch['dealername'];
		echo(json_encode($responsearray));
		//echo('1^'.$fetch['slno'].'^'.$customerid.'^'.$fetch['companyname'].'^'.trim($contactvalues,',').'^'.$address.'^'.$phone.'^'.$cell.'^'.$emailidplit.'^'.$fetch['region'].'^'.$fetch['branch'].'^'.$fetch['businesstype'].'^'.$fetch['customertype'].'^'.$fetch['dealername']);
	}
	break;
	case 'yearwisedetails':
	{
		$lastslno = $_POST['lastslno'];
		$query = "select table1.group, if(table2.count<> '','Yes','No') as `2004-05`, if(table3.count<> '','Yes','No')as `2005-06`,
if(table4.count<> '','Yes','No') as `2006-07`, if(table5.count<> '','Yes','No') as `2007-08`, 
if(table6.count<> '','Yes','No')as `2008-09`
, if(table7.count<> '','Yes','No')as `2009-10`, if(table8.count<> '','Yes','No')as `2010-11`, if(table9.count<> '','Yes','No')as `2011-12`  from
(select distinct inv_mas_product.group from inv_mas_product 
where  inv_mas_product.group IN ('tds','sto','svh','svi','sac','spp','xbrl')  
group by  inv_mas_product.group) as table1 
left join (select COUNT(DISTINCT inv_customerproduct.customerreference) as `count` ,inv_mas_product.group from inv_customerproduct left join inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3)
where inv_mas_product.group IN ('tds','sto','svh','svi','sac','spp','xbrl') 
and reregistration = 'no' and (inv_mas_customer.customerid <> '' or inv_mas_customer.customerid is null)
and inv_mas_product.year = '2004-05'  and inv_customerproduct.customerreference = '".$lastslno."'
group by inv_mas_product.group ) as table2 
on table1.group = table2.group 
left join
(select COUNT(DISTINCT inv_customerproduct.customerreference) as `count` ,inv_mas_product.group from inv_customerproduct left join inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3)
where inv_mas_product.group IN ('tds','sto','svh','svi','sac','spp','xbrl') 
and reregistration = 'no' and (inv_mas_customer.customerid <> '' or inv_mas_customer.customerid is null)
and inv_mas_product.year = '2005-06'  and inv_customerproduct.customerreference = '".$lastslno."'
group by inv_mas_product.group ) as table3 
on table1.group = table3.group
left join
(select COUNT(DISTINCT inv_customerproduct.customerreference) as `count` ,inv_mas_product.group from inv_customerproduct left join inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3)
where inv_mas_product.group IN ('tds','sto','svh','svi','sac','spp','xbrl') 
and reregistration = 'no' and (inv_mas_customer.customerid <> '' or inv_mas_customer.customerid is null)
and inv_mas_product.year = '2006-07'  and inv_customerproduct.customerreference = '".$lastslno."'
group by inv_mas_product.group ) as table4 
on table1.group = table4.group
left join
(select COUNT(DISTINCT inv_customerproduct.customerreference) as `count` ,inv_mas_product.group from inv_customerproduct left join inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3)
where inv_mas_product.group IN ('tds','sto','svh','svi','sac','spp','xbrl') 
and reregistration = 'no' and (inv_mas_customer.customerid <> '' or inv_mas_customer.customerid is null)
and inv_mas_product.year = '2007-08'  and inv_customerproduct.customerreference = '".$lastslno."'
group by inv_mas_product.group ) as table5 
on table1.group = table5.group
left join
(select COUNT(DISTINCT inv_customerproduct.customerreference) as `count` ,inv_mas_product.group from inv_customerproduct left join inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3)
where inv_mas_product.group IN ('tds','sto','svh','svi','sac','spp','xbrl') 
and reregistration = 'no' and (inv_mas_customer.customerid <> '' or inv_mas_customer.customerid is null)
and inv_mas_product.year = '2008-09'  and inv_customerproduct.customerreference = '".$lastslno."'
group by inv_mas_product.group ) as table6 
on table1.group = table6.group
left join
(select COUNT(DISTINCT inv_customerproduct.customerreference) as `count` ,inv_mas_product.group from inv_customerproduct left join inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3)
where inv_mas_product.group IN ('tds','sto','svh','svi','sac','spp','xbrl') 
and reregistration = 'no' and (inv_mas_customer.customerid <> '' or inv_mas_customer.customerid is null)
and inv_mas_product.year = '2009-10'  and inv_customerproduct.customerreference = '".$lastslno."'
group by inv_mas_product.group ) as table7 
on table1.group = table7.group
left join
(select COUNT(DISTINCT inv_customerproduct.customerreference) as `count` ,inv_mas_product.group from inv_customerproduct left join inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3)
where inv_mas_product.group IN ('tds','sto','svh','svi','sac','spp','xbrl') 
and reregistration = 'no' and (inv_mas_customer.customerid <> '' or inv_mas_customer.customerid is null)
and inv_mas_product.year = '2010-11'  and inv_customerproduct.customerreference = '".$lastslno."'
group by inv_mas_product.group ) as table8 
on table1.group = table8.group
left join
(select COUNT(DISTINCT inv_customerproduct.customerreference) as `count` ,inv_mas_product.group from inv_customerproduct left join inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3)
where inv_mas_product.group IN ('tds','sto','svh','svi','sac','spp','xbrl') 
and reregistration = 'no' and (inv_mas_customer.customerid <> '' or inv_mas_customer.customerid is null)
and inv_mas_product.year = '2011-12'  and inv_customerproduct.customerreference = '".$lastslno."'
group by inv_mas_product.group ) as table9 
on table1.group = table9.group";
				$result2 = runmysqlquery($query);
		$griddisplay .= '<table width="100%" border="0" cellspacing="0" cellpadding="5" style="border:1px solid #308ebc; border-top:none;"';
		$griddisplay .= '<tr><td><table width="100%" border="0" cellspacing="0" cellpadding="3"  class="grid-border">';
		$griddisplay .= '<tr bgcolor = "#e9967a"><td class="grid-td-border"><div align="center" ><strong>Groups</strong></div></td><td class="grid-td-border"><div align="center" ><strong>2004-05</strong></div></td><td class="grid-td-border"><div align="center"><strong>2005-06</strong></div></td><td class="grid-td-border"><div align="center"><strong>2006-07</strong></div></td> <td class="grid-td-border"><div align="center"><strong>2007-08</strong></div></td><td class="grid-td-border"><div align="center"><strong>2008-09</strong></div></td><td class="grid-td-border"><div align="center"><strong>2009-10</strong></div></td><td class="grid-td-border"><div align="center"><strong>2010-11</strong></div></td><td class="grid-td-border"><div align="center"><strong>2011-12</strong></div></td></tr>';
				while($fetch = mysqli_fetch_array($result2))
				{
					$color1 = ($fetch['2004-05'] == 'No')?('#FFDFDF'):('#C1DDB9');
					$color2 = ($fetch['2005-06'] == 'No')?('#FFDFDF'):('#C1DDB9');
					$color3 = ($fetch['2006-07'] == 'No')?('#FFDFDF'):('#C1DDB9');
					$color4 = ($fetch['2007-08'] == 'No')?('#FFDFDF'):('#C1DDB9');
					$color5 = ($fetch['2008-09'] == 'No')?('#FFDFDF'):('#C1DDB9');
					$color6 = ($fetch['2009-10'] == 'No')?('#FFDFDF'):('#C1DDB9');
					$color7 = ($fetch['2010-11'] == 'No')?('#FFDFDF'):('#C1DDB9');
					$color8 = ($fetch['2011-12'] == 'No')?('#FFDFDF'):('#C1DDB9');
					
					$griddisplay .= '<tr  bgcolor = "#e9967a">';
					$griddisplay .= '<td class="grid-td-border"><div align="center"><strong>'.$fetch['group'].'</strong></div></td>';
					$griddisplay .= '<td class="grid-td-border" bgcolor = "'.$color1.'"><div align="center"><strong>'.$fetch['2004-05'].'</strong></div></td>';
					$griddisplay .= '<td class="grid-td-border" bgcolor = "'.$color2.'"><div align="center"><strong>'.$fetch['2005-06'].'</strong></div></td>';
					$griddisplay .= '<td class="grid-td-border" bgcolor = "'.$color3.'"><div align="center"><strong>'.$fetch['2006-07'].'</strong></div></td>';
					$griddisplay .= '<td class="grid-td-border" bgcolor = "'.$color4.'"><div align="center"><strong>'.$fetch['2007-08'].'</strong></div></td>';
					$griddisplay .= '<td class="grid-td-border" bgcolor = "'.$color5.'"><div align="center"><strong>'.$fetch['2008-09'].'</strong></div></td>';
					$griddisplay .= '<td class="grid-td-border" bgcolor = "'.$color6.'"><div align="center"><strong>'.$fetch['2009-10'].'</strong></div></td>';
					$griddisplay .= '<td class="grid-td-border" bgcolor = "'.$color7.'"><div align="center"><strong>'.$fetch['2010-11'].'</strong></div></td>';
					$griddisplay .= '<td class="grid-td-border" bgcolor = "'.$color8.'"><div align="center"><strong>'.$fetch['2011-12'].'</strong></div></td>';
					$griddisplay .= '</tr/>';
				}
			$griddisplay .= '</td></tr></table></table>';
		
		$responsearray2 = array();
		$responsearray2['errorcode'] = "1";
		$responsearray2['grid'] = $griddisplay;
		echo(json_encode($responsearray2));
		//echo('1^'.$griddisplay);

	}
	break;
	
	case 'save':
	{
		$responsearray1  = array();
		$lastslno = $_POST['lastslno'];
		$customerid = $_POST['customerid'];
		$remarks = $_POST['remarks'];
		$entereddate = $_POST['entereddate'];
		$followupdate = $_POST['followupdate'];
		$dealer = $_POST['dealer'];
		$status = $_POST['status'];
		$overallstatus = $_POST['overallstatus'];
		$productgroup = $_POST['productgroup'];
		$query = "Select businessname,slno from inv_mas_dealer where slno = '".$userid."'";
	  	$fetch = runmysqlqueryfetch($query);
		$dealerusername = $fetch['slno']; 
		$query1 = "SELECT distinct count(*) as count from inv_crossproductstatus  where inv_crossproductstatus.customerid = '".$customerid."'";
		$fetch1 = runmysqlqueryfetch($query1);
		if($fetch1['count'] > 0)
		{
			if($lastslno == '' )
			{
				$query11 = "Select productgroup,`status` from inv_crossproductstatus where customerid = '".$customerid."'";
				$result22= runmysqlquery($query11);
				while($fetch22 = mysqli_fetch_array($result22))
				{
					$productgrouparray[] = $fetch22['productgroup'];
				}
				if(in_array($productgroup,$productgrouparray,true))
				{ 
					$responsearray2['errorcode'] = "3";
					$responsearray2['errormsg'] = "Status is already set for that group";
					echo(json_encode($responsearray2));
					//echo("3^"."Status is already set for that group");
	
				}
				else
				{
					$query = "Insert into inv_crossproduct(customerid,createdby,remarks,todealer,entereddate,followupdate, lastmodifieddate,lastmodifiedby,createdip,lastmodifiedip,modulename) 
		values ('".$customerid."','".$dealerusername."','".$remarks."','".$dealer."','".changedateformat($entereddate)."','".changedateformat($followupdate)."','".date('Y-m-d').' '.date('H:i:s')."','".$dealerusername."','".$_SERVER['REMOTE_ADDR']."','".$_SERVER['REMOTE_ADDR']."','dealer_module');";		
					$result = runmysqlquery($query);
					$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','120','".date('Y-m-d').' '.date('H:i:s')."')";
					$eventresult = runmysqlquery($eventquery);
					$fetch1 = runmysqlqueryfetch("SELECT MAX(slno) AS statusslno FROM inv_crossproduct;");
					$query1 = "Insert into inv_crossproductstatus(customerid,productgroup,overallremarks,statusnumber,status) 
		values ('".$customerid."','".$productgroup."','".$overallstatus."','".$fetch1['statusslno']."','1');";
					$result = runmysqlquery($query1);
					$responsearray2['errorcode'] = "1";
					$responsearray2['errormsg'] = "Follow Up Record Saved Successfully";
					echo(json_encode($responsearray2));
					//echo("1^"."Follow Up Record Saved Successfully");
	
				}
				
			}
			else
			{
				$query = "UPDATE inv_crossproduct SET remarks = '".$remarks."', todealer = '".$dealer."', entereddate = '".changedateformat($entereddate)."',followupdate = '".changedateformat($followupdate)."' ,
	lastmodifieddate ='".date('Y-m-d').' '.date('H:i:s')."' , createdby ='".$dealerusername."', lastmodifiedip = '".$_SERVER['REMOTE_ADDR']."',modulename = 'dealer_module' WHERE slno = '".$lastslno."'";
				$result = runmysqlquery($query);
				$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','120','".date('Y-m-d').' '.date('H:i:s')."')";
				$eventresult = runmysqlquery($eventquery);
				$responsearray2['errorcode'] = "1";
				$responsearray2['errormsg'] = "Follow Up Record Saved Successfully";
				echo(json_encode($responsearray2));
				//echo("1^"."Follow Up Record Saved Successfully");
			}
		}
		else
		{
			if($lastslno == '')
			{
				$query = "Insert into inv_crossproduct(customerid,createdby,remarks,todealer,entereddate,followupdate, lastmodifieddate,lastmodifiedby,createdip,lastmodifiedip,modulename) 
			values ('".$customerid."','".$dealerusername."','".$remarks."','".$dealer."','".changedateformat($entereddate)."','".changedateformat($followupdate)."','".date('Y-m-d').' '.date('H:i:s')."','".$dealerusername."','".$_SERVER['REMOTE_ADDR']."','".$_SERVER['REMOTE_ADDR']."','dealer_module');";		$result = runmysqlquery($query);
				$fetch1 = runmysqlqueryfetch("SELECT MAX(slno) AS statusslno FROM inv_crossproduct;");
				$query1 = "Insert into inv_crossproductstatus(customerid,productgroup,overallremarks,statusnumber,status) 
	values ('".$customerid."','".$productgroup."','".$overallstatus."','".$fetch1['statusslno']."','1');";
				$result = runmysqlquery($query1);
				$responsearray2['errorcode'] = "1";
				$responsearray2['errormsg'] = "Follow Up Record Saved Successfully";
				echo(json_encode($responsearray2));
				//echo("1^"."Follow Up Record Saved Successfully");
			}
			else
			{
				$query = "UPDATE inv_crossproduct SET remarks = '".$remarks."', todealer = '".$dealer."', entereddate = '".changedateformat($entereddate)."',followupdate = '".changedateformat($followupdate)."' ,
	lastmodifieddate ='".date('Y-m-d').' '.date('H:i:s')."' , createdby ='".$dealerusername."', lastmodifiedip = '".$_SERVER['REMOTE_ADDR']."',modulename = 'dealer_module' WHERE slno = '".$lastslno."'";
				$result = runmysqlquery($query);
				$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','120','".date('Y-m-d').' '.date('H:i:s')."')";
				$eventresult = runmysqlquery($eventquery);
				$responsearray2['errorcode'] = "1";
				$responsearray2['errormsg'] = "Follow Up Record Saved Successfully";
				echo(json_encode($responsearray2));
				//echo("1^"."Follow Up Record Saved Successfully");
			}
		}
	}
	break;
	
	case 'generategrid':
	{
		$startlimit = $_POST['startlimit'];
		$slno = $_POST['slno'];
		$showtype = $_POST['showtype'];
		$lastslno = $_POST['lastslno'];
		$query1 = "SELECT  inv_crossproductstatus.customerid as cusslno, inv_crossproductstatus.overallremarks, inv_mas_status.statusname as `status`, inv_crossproductstatus.productgroup,inv_crossproduct.remarks, inv_crossproduct.entereddate, inv_crossproduct.followupdate ,inv_crossproduct.createdby ,inv_crossproduct.customerid,inv_crossproduct.slno,
inv_crossproduct.lastmodifieddate,inv_crossproduct.modulename,inv_mas_dealer.businessname as dealername
FROM inv_crossproduct left join inv_crossproductstatus on inv_crossproductstatus.statusnumber =  inv_crossproduct.slno
left join inv_mas_dealer on inv_mas_dealer.slno = inv_crossproduct.todealer
left join inv_mas_status on inv_mas_status.statusvalue = inv_crossproductstatus.status where
inv_crossproductstatus.customerid = '".$lastslno."' order by inv_crossproduct.lastmodifieddate desc;";
		$fetch6 = runmysqlquery($query1);
		$fetchresultcount = mysqli_num_rows($fetch6);
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
	$query = "SELECT  inv_crossproductstatus.customerid as cusslno, inv_crossproductstatus.overallremarks, inv_mas_status.statusname as `status`, inv_crossproductstatus.productgroup,inv_crossproduct.remarks, inv_crossproduct.entereddate, inv_crossproduct.followupdate ,inv_crossproduct.createdby ,inv_crossproduct.customerid,inv_crossproduct.slno,
inv_crossproduct.lastmodifieddate,inv_crossproduct.modulename,inv_mas_dealer.businessname as dealername
FROM inv_crossproduct left join inv_crossproductstatus on inv_crossproductstatus.statusnumber =  inv_crossproduct.slno
left join inv_mas_dealer on inv_mas_dealer.slno = inv_crossproduct.todealer
left join inv_mas_status on inv_mas_status.statusvalue = inv_crossproductstatus.status where
inv_crossproductstatus.customerid = '".$lastslno."' order by inv_crossproduct.lastmodifieddate desc LIMIT ".$startlimit.",".$limit.";";
		if($startlimit == 0)
		{
		$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid" style="text-align:left">';
		$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid">Sl No</td><td nowrap = "nowrap" class="td-border-grid">Entered By</td><td nowrap = "nowrap" class="td-border-grid">Dealer</td><td nowrap = "nowrap" class="td-border-grid">Product Group</td><td nowrap = "nowrap" class="td-border-grid">Remarks</td><td nowrap = "nowrap" class="td-border-grid">Next Followup Date</td><td nowrap = "nowrap" class="td-border-grid">Entered Date</td><td nowrap = "nowrap" class="td-border-grid">Current Status</td><td nowrap = "nowrap" class="td-border-grid">Overall Remarks</td><td nowrap = "nowrap" class="td-border-grid">Module Name</td></tr>';
		}
	$i_n = 0;
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		$i_n++;$slno++;
		$color;
		if($i_n%2 == 0)
			$color = "#edf4ff";
		else
			$color = "#f7faff";
		$grid .= '<tr class="gridrow" bgcolor='.$color.'  onclick="gridtoform(\''.$fetch['slno'].'\')" style="cursor:pointer">';
		$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$slno."</td>";
		
		if($fetch['modulename'] == 'dealer_module')
		{
			$query2 ="select inv_mas_dealer.businessname as businessname from inv_mas_dealer 
left join inv_crossproduct on inv_crossproduct.createdby = inv_mas_dealer.slno 
WHERE inv_mas_dealer.slno = '".$fetch['createdby']."'";
			$fetchresult = runmysqlqueryfetch($query2);
			$enteredby  = $fetchresult['businessname'];
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$enteredby."</td>";
		}
		else if($fetch['modulename'] == 'user_module')
		{
			$query1 ="select fullname from inv_mas_users left join inv_crossproduct on inv_crossproduct.createdby = inv_mas_users.slno WHERE inv_mas_users.slno = '".$fetch['createdby']."'";
			$resultfetch = runmysqlqueryfetch($query1);
			$enteredby  = $resultfetch['fullname'];
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$enteredby."</td>";
		}
		
		$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$fetch['dealername']."</td>";
		$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$fetch['productgroup']."</td>";
		$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$fetch['remarks']."</td>";
		$grid .= "<td nowrap='nowrap' class='td-border-grid'>".changedateformat($fetch['followupdate'])."</td>";
		$grid .= "<td nowrap='nowrap' class='td-border-grid'>".changedateformat($fetch['entereddate'])."</td>";
		$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$fetch['status']."</td>";
		$grid .= "<td nowrap='nowrap' class='td-border-grid'>&nbsp;".$fetch['overallremarks']."</td>";
		$grid .= "<td nowrap='nowrap' class='td-border-grid'>".modulegropname($fetch['modulename'])."</td>";
		$grid .= "</tr>";

	}
		$grid .= "</table>";

		$fetchcount = mysqli_num_rows($result);
		if($slno >= $fetchresultcount)
		$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2" align = "left"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
	else
		$linkgrid .= '<table><tr><td class="resendtext" align = "left"><div  style="padding-right:10px;text-align:left;"><a onclick ="crossmoredatagrid(\''.$lastslno.'\',\''.$startlimit.'\',\''.$slno.'\',\'more\');">Show More Records >></a><a onclick ="crossmoredatagrid(\''.$lastslno.'\',\''.$startlimit.'\',\''.$slno.'\',\'all\');" class="resendtext1"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
		
		$responsearray3 = array();
		$responsearray3['errorcode'] = "1";
		$responsearray3['grid'] = $grid;
		$responsearray3['fetchresultcount'] = $fetchresultcount;
		$responsearray3['linkgrid'] = $linkgrid;
		echo(json_encode($responsearray3));
		//echo('1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid);

	}
	break;
	
	case 'gridtoform':
	{
		$responsearray4 = array();
		$slno = $_POST['slno'];
		$query1 = "SELECT distinct count(*) as count from inv_crossproduct  where inv_crossproduct.slno = '".$slno."'";
		$fetch1 = runmysqlqueryfetch($query1);
		if($fetch1['count'] > 0)
		{
			$query = "SELECT inv_crossproduct.slno,inv_crossproduct.remarks, inv_crossproduct.entereddate, 
inv_crossproduct.followupdate,inv_mas_dealer.businessname as dealername,inv_crossproductstatus.status,inv_crossproductstatus.productgroup,inv_crossproduct.modulename,
inv_crossproduct.todealer ,inv_crossproduct.customerid FROM inv_crossproduct 
left join inv_mas_dealer on inv_mas_dealer.slno = inv_crossproduct.createdby 
left join inv_crossproductstatus on inv_crossproductstatus.statusnumber = inv_crossproduct.slno 
WHERE inv_crossproduct.slno = '".$slno."'";
			$fetch = runmysqlqueryfetch($query);
			
			$modulename = $fetch['modulename'];
			if($modulename == 'dealer_module' )
			{
				$query2 ="select inv_mas_dealer .businessname as businessname,inv_mas_dealer.slno as dealerid from inv_mas_dealer left join inv_crossproduct on inv_crossproduct.createdby = inv_mas_dealer.slno 
WHERE inv_crossproduct.slno = '".$slno."'";
				$fetchresult = runmysqlqueryfetch($query2);
				$enteredby  = $fetchresult['businessname'];
			}
			else
			{
				$query1 = "select inv_mas_users.fullname as enteredby from inv_mas_users left join inv_crossproduct on inv_crossproduct.createdby = inv_mas_users.slno WHERE inv_crossproduct.slno = '".$slno."'";
				$fetch1 = runmysqlqueryfetch($query1);
				$enteredby =$fetch1['enteredby'];				
			}
			$responsearray4['errorcode'] = "1";
			$responsearray4['remarks'] = $fetch['remarks'];
			$responsearray4['entereddate'] = changedateformat($fetch['entereddate']);
			$responsearray4['followupdate'] = changedateformat($fetch['followupdate']);
			$responsearray4['enteredby'] = $enteredby;
			$responsearray4['todealer'] = $fetch['todealer'];
			$responsearray4['slno'] = $slno;
			$responsearray4['productgroup'] = $fetch['productgroup'];
			$responsearray4['status'] = $fetch['status'];
			echo(json_encode($responsearray4));
			
			//echo('1^'.$fetch['remarks'].'^'.changedateformat($fetch['entereddate']).'^'.changedateformat($fetch['followupdate']).'^'.$enteredby.'^'.$fetch['todealer'].'^'.$slno.'^'.$fetch['productgroup'].'^'.$fetch['status']);

			
		}
		else
		{
			$responsearray4['errorcode'] = "2";
			$responsearray4['errormsg'] = "No datas found to be displayed.";
			echo(json_encode($responsearray4));
			//echo('2^No datas found to be displayed.');
		}
	}
	break;
	
	case 'updatestatus':
	{
		$responsearray5 = array();
		$status = $_POST['status'];
		$lastslno = $_POST['cusid'];
		$remarks = $_POST['remarks'];
		$lastupdateddate = datetimelocal("Y-m-d");
		$lastupdatetime = datetimelocal("H:i:s");
		if((checkstatus($lastslno,$status)) == true)
		{
			$query = "Select inv_crossproductstatus.slno,inv_crossproductstatus.customerid from inv_crossproduct left join inv_crossproductstatus on inv_crossproductstatus.statusnumber = inv_crossproduct.slno where    inv_crossproduct.slno = '".$lastslno."' ";
			$fetchres = runmysqlqueryfetch($query);
			$query1 = "UPDATE inv_crossproductstatus SET status = '".$status."',overallremarks = '".$remarks."' WHERE slno = '".$fetchres['slno']."'";
			$result = runmysqlquery($query1);
			// Insert details to logs 
			$query2 = "insert into inv_logs_crossproductupdatelogs(customerid,`status`,updatedate,updatedby,overallremarks,module) values('".$fetchres['customerid']."','".$status."','".$lastupdateddate.' '.$lastupdatetime."','".$userid."','".$remarks."','dealer_module')";
			$result2 = runmysqlquery($query2);
			$responsearray5['errorcode'] = "1";
			$responsearray5['errormsg'] = "Successfully Updated.";
			echo(json_encode($responsearray5));
			//echo('1^'.'Successfully Updated');
		}
		else
		{
			$responsearray5['errorcode'] = "1";
			$responsearray5['errormsg'] = "Cannot Update.";
			echo(json_encode($responsearray5));
			//echo('2^'.'Cannot Update');
		}
	}
	break;
	
	
	case 'searchcustomerlist':
	{
		$responsearray6 = array();
		$databasefield = $_POST['databasefield'];
		$textfield = $_POST['textfield'];
		$state = $_POST['state'];
		$region = $_POST['region'];
		$dealer2 = $_POST['dealer2'];
		$branch2 = $_POST['branch2'];
		$list1 = str_replace('\\','',$_POST['list1']);
		$listvalue2 = str_replace('\\','',$_POST['list2']);
		if($listvalue2 == '')
			$list2 = "''";
		else
			$list2 = $listvalue2;
		$district = $_POST['district'];
		$type2 = $_POST['type2'];
		$category2= $_POST['category2'];
		$regionpiece = ($region == "")?(""):(" AND inv_mas_customer.region = '".$region."' ");
		$state_typepiece = ($state == "")?(""):(" AND inv_mas_district.statecode = '".$state."' ");
		$district_typepiece = ($district == "")?(""):(" AND inv_mas_customer.district = '".$district."' ");
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
			
		switch($databasefield)
		{
			case "slno":
			{
				$customeridlen = strlen($textfield);
				$lastcustomerid = cusidsplit($textfield);
				
					$query = "select distinct table1.businessname,table1.customerreference
from (select distinct inv_mas_customer.businessname ,inv_customerproduct.customerreference ,inv_mas_customer.currentdealer,
inv_mas_product.group,inv_mas_customer.slno,inv_mas_customer.customerid, inv_mas_customer.district, inv_mas_district.statecode, inv_mas_customer.region,inv_mas_customer.type, inv_mas_customer.category, inv_mas_customer.branch
from inv_customerproduct left join inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno 
left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where inv_mas_product.group IN ('tds','sto','svh','svi','sac','spp','xbrl') having `group` in (".$list1.") and  
inv_mas_customer.currentdealer = '".$userid."' and  (inv_mas_customer.slno LIKE '%".$lastcustomerid."%' OR inv_mas_customer.customerid LIKE '%".$lastcustomerid."%') ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." order by businessname)as table1
left join
(select distinct inv_mas_customer.businessname ,inv_customerproduct.customerreference ,inv_mas_customer.currentdealer,
inv_mas_product.group,inv_mas_customer.slno,inv_mas_customer.customerid, inv_mas_customer.district, inv_mas_district.statecode, inv_mas_customer.region,inv_mas_customer.type, inv_mas_customer.category, inv_mas_customer.branch
from inv_customerproduct left join inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno 
left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where inv_mas_product.group IN ('tds','sto','svh','svi','sac','spp','xbrl') having `group` not  in (".$list2.") 
and  inv_mas_customer.currentdealer = '".$userid."' and  (inv_mas_customer.slno LIKE '%".$lastcustomerid."%' OR inv_mas_customer.customerid LIKE '%".$lastcustomerid."%') ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." order by businessname) as table2 on
table1.group = table2.group  ";
			}
				break;
				
			case 'contactperson':
			{
				$query = "select distinct table1.businessname,table1.customerreference
from (select distinct inv_mas_customer.businessname ,inv_customerproduct.customerreference ,inv_mas_customer.currentdealer,
inv_mas_product.group,inv_contactdetails.contactperson, inv_mas_customer.district, inv_mas_district.statecode, inv_mas_customer.region,inv_mas_customer.type, inv_mas_customer.category, inv_mas_customer.branch
from inv_customerproduct left join inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where inv_mas_product.group IN ('tds','sto','svh','svi','sac','spp','xbrl') having `group` in (".$list1.") and  
inv_mas_customer.currentdealer = '".$userid."' and (inv_contactdetails.contactperson LIKE '%".$textfield."%' ) ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." order by businessname)as table1
left join
(select distinct inv_mas_customer.businessname ,inv_customerproduct.customerreference ,inv_mas_customer.currentdealer,
inv_mas_product.group,inv_contactdetails.contactperson, inv_mas_customer.district, inv_mas_district.statecode, inv_mas_customer.region,inv_mas_customer.type, inv_mas_customer.category, inv_mas_customer.branch
from inv_customerproduct left join inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where inv_mas_product.group IN ('tds','sto','svh','svi','sac','spp','xbrl') having `group` not  in (".$list2.") 
and  inv_mas_customer.currentdealer = '".$userid."' and (inv_contactdetails.contactperson LIKE '%".$textfield."%' ) ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." order by businessname) as table2 on
table1.group = table2.group  ";
			}
			break;
			case 'place':
			{
				$query = "select distinct table1.businessname,table1.customerreference
from (select distinct inv_mas_customer.businessname ,inv_customerproduct.customerreference ,inv_mas_customer.currentdealer,
inv_mas_product.group,inv_mas_customer.place, inv_mas_customer.district, inv_mas_district.statecode, inv_mas_customer.region,inv_mas_customer.type, inv_mas_customer.category, inv_mas_customer.branch
from inv_customerproduct left join inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where inv_mas_product.group IN ('tds','sto','svh','svi','sac','spp','xbrl') having `group` in (".$list1.") and  
inv_mas_customer.currentdealer = '".$userid."' and (inv_mas_customer.place LIKE '%".$textfield."%' ) ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." order by businessname)as table1
left join
(select distinct inv_mas_customer.businessname ,inv_customerproduct.customerreference ,inv_mas_customer.currentdealer,
inv_mas_product.group,inv_mas_customer.place, inv_mas_customer.district, inv_mas_district.statecode, inv_mas_customer.region,inv_mas_customer.type, inv_mas_customer.category, inv_mas_customer.branch
from inv_customerproduct left join inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where inv_mas_product.group IN ('tds','sto','svh','svi','sac','spp','xbrl') having `group` not  in (".$list2.") 
and  inv_mas_customer.currentdealer = '".$userid."' and (inv_mas_customer.place LIKE '%".$textfield."%' ) ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." order by businessname) as table2 on
table1.group = table2.group  ";
			}
			break;
			case 'emailid':
			{
				$query = "select distinct table1.businessname,table1.customerreference
from (select distinct inv_mas_customer.businessname ,inv_customerproduct.customerreference ,inv_mas_customer.currentdealer,
inv_mas_product.group,inv_contactdetails.emailid, inv_mas_customer.district, inv_mas_district.statecode, inv_mas_customer.region,inv_mas_customer.type, inv_mas_customer.category,inv_mas_customer.branch
from inv_customerproduct left join inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where inv_mas_product.group IN ('tds','sto','svh','svi','sac','spp','xbrl') having `group` in (".$list1.") and  
inv_mas_customer.currentdealer = '".$userid."' and (inv_contactdetails.emailid LIKE '%".$textfield."%' ) ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." order by businessname)as table1
left join
(select distinct inv_mas_customer.businessname ,inv_customerproduct.customerreference ,inv_mas_customer.currentdealer,
inv_mas_product.group,inv_contactdetails.emailid, inv_mas_customer.district, inv_mas_district.statecode, inv_mas_customer.region,inv_mas_customer.type, inv_mas_customer.category,inv_mas_customer.branch
from inv_customerproduct left join inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where inv_mas_product.group IN ('tds','sto','svh','svi','sac','spp','xbrl') having `group` not  in (".$list2.") 
and  inv_mas_customer.currentdealer = '".$userid."' and (inv_contactdetails.emailid LIKE '%".$textfield."%' ) ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." order by businessname) as table2 on
table1.group = table2.group  ";
			}
			break;
			case 'phone':
			{
				$query = "select distinct table1.businessname,table1.customerreference
from (select distinct inv_mas_customer.businessname ,inv_customerproduct.customerreference ,inv_mas_customer.currentdealer,
inv_mas_product.group,inv_contactdetails.phone,inv_contactdetails.cell, inv_mas_customer.district, inv_mas_district.statecode, inv_mas_customer.region,inv_mas_customer.type, inv_mas_customer.category,inv_mas_customer.branch
from inv_customerproduct left join inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where inv_mas_product.group IN ('tds','sto','svh','svi','sac','spp','xbrl') having `group` in (".$list1.") and  
inv_mas_customer.currentdealer = '".$userid."' and (inv_contactdetails.phone LIKE '%".$textfield."%' or inv_contactdetails.cell LIKE '%".$textfield."%') ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." order by businessname)as table1
left join
(select distinct inv_mas_customer.businessname ,inv_customerproduct.customerreference ,inv_mas_customer.currentdealer,
inv_mas_product.group,inv_contactdetails.phone,inv_contactdetails.cell, inv_mas_customer.district, inv_mas_district.statecode, inv_mas_customer.region,inv_mas_customer.type, inv_mas_customer.category,inv_mas_customer.branch
from inv_customerproduct left join inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where inv_mas_product.group IN ('tds','sto','svh','svi','sac','spp','xbrl') having `group` not  in (".$list2.") 
and  inv_mas_customer.currentdealer = '".$userid."' and (inv_contactdetails.phone LIKE '%".$textfield."%' or inv_contactdetails.cell LIKE '%".$textfield."%') ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." order by businessname) as table2 on
table1.group = table2.group  ";
			}
			break;
			case 'cardid':
			{
				$query = "select distinct table1.businessname,table1.customerreference
from (select distinct inv_mas_customer.businessname ,inv_customerproduct.customerreference ,inv_mas_customer.currentdealer,
inv_mas_product.group, inv_mas_customer.district, inv_mas_district.statecode, inv_mas_customer.region,inv_mas_customer.type, inv_mas_customer.category,inv_mas_customer.branch,inv_customerproduct.cardid
from inv_customerproduct left join inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where inv_mas_product.group IN ('tds','sto','svh','svi','sac','spp','xbrl') having `group` in (".$list1.") and  
inv_mas_customer.currentdealer = '".$userid."' and (inv_customerproduct.cardid LIKE '%".$textfield."%' ) ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." order by businessname)as table1
left join
(select distinct inv_mas_customer.businessname ,inv_customerproduct.customerreference ,inv_mas_customer.currentdealer,
inv_mas_product.group, inv_mas_customer.district, inv_mas_district.statecode, inv_mas_customer.region,inv_mas_customer.type, inv_mas_customer.category,inv_mas_customer.branch,inv_customerproduct.cardid
from inv_customerproduct left join inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where inv_mas_product.group IN ('tds','sto','svh','svi','sac','spp','xbrl') having `group` not  in (".$list2.") 
and  inv_mas_customer.currentdealer = '".$userid."' and (inv_customerproduct.cardid LIKE '%".$textfield."%') ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." order by businessname) as table2 on
table1.group = table2.group  ";
			}
			case 'scratchnumber':
			{
				$query = "select distinct table1.businessname,table1.customerreference
from (select distinct inv_mas_customer.businessname ,inv_customerproduct.customerreference ,inv_mas_customer.currentdealer,
inv_mas_product.group, inv_mas_customer.district, inv_mas_district.statecode, inv_mas_customer.region,inv_mas_customer.type, inv_mas_customer.category,inv_mas_customer.branch,inv_mas_scratchcard.scratchnumber
from inv_customerproduct left join inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
LEFT JOIN inv_mas_scratchcard ON inv_customerproduct.cardid = inv_mas_scratchcard.cardid
where inv_mas_product.group IN ('tds','sto','svh','svi','sac','spp','xbrl') having `group` in (".$list1.") and  
inv_mas_customer.currentdealer = '".$userid."' and (inv_mas_scratchcard.scratchnumber LIKE '%".$textfield."%' ) ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." order by businessname)as table1
left join
(select distinct inv_mas_customer.businessname ,inv_customerproduct.customerreference ,inv_mas_customer.currentdealer,
inv_mas_product.group, inv_mas_customer.district, inv_mas_district.statecode, inv_mas_customer.region,inv_mas_customer.type, inv_mas_customer.category,inv_mas_customer.branch,inv_mas_scratchcard.scratchnumber
from inv_customerproduct left join inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
LEFT JOIN inv_mas_scratchcard ON inv_customerproduct.cardid = inv_mas_scratchcard.cardid
where inv_mas_product.group IN ('tds','sto','svh','svi','sac','spp','xbrl') having `group` not  in (".$list2.") 
and  inv_mas_customer.currentdealer = '".$userid."' and (inv_mas_scratchcard.scratchnumber LIKE '%".$textfield."%') ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." order by businessname) as table2 on
table1.group = table2.group  ";
			}
			break;
			case 'computerid':
			{
				$query = "select distinct table1.businessname,table1.customerreference
from (select distinct inv_mas_customer.businessname ,inv_customerproduct.customerreference ,inv_mas_customer.currentdealer,
inv_mas_product.group, inv_mas_customer.district, inv_mas_district.statecode, inv_mas_customer.region,inv_mas_customer.type, inv_mas_customer.category,inv_mas_customer.branch,inv_customerproduct.computerid
from inv_customerproduct left join inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where inv_mas_product.group IN ('tds','sto','svh','svi','sac','spp','xbrl') having `group` in (".$list1.") and  
inv_mas_customer.currentdealer = '".$userid."' and (inv_customerproduct.computerid LIKE '%".$textfield."%' ) ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." order by businessname)as table1
left join
(select distinct inv_mas_customer.businessname ,inv_customerproduct.customerreference ,inv_mas_customer.currentdealer,
inv_mas_product.group, inv_mas_customer.district, inv_mas_district.statecode, inv_mas_customer.region,inv_mas_customer.type, inv_mas_customer.category,inv_mas_customer.branch,inv_customerproduct.computerid
from inv_customerproduct left join inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where inv_mas_product.group IN ('tds','sto','svh','svi','sac','spp','xbrl') having `group` not  in (".$list2.") 
and  inv_mas_customer.currentdealer = '".$userid."' and (inv_customerproduct.computerid LIKE '%".$textfield."%') ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." order by businessname) as table2 on
table1.group = table2.group  ";
			}
			break;			
			case 'softkey':
			{
				$query = "select distinct table1.businessname,table1.customerreference
from (select distinct inv_mas_customer.businessname ,inv_customerproduct.customerreference ,inv_mas_customer.currentdealer,
inv_mas_product.group, inv_mas_customer.district, inv_mas_district.statecode, inv_mas_customer.region,inv_mas_customer.type, inv_mas_customer.category,inv_mas_customer.branch,inv_customerproduct.softkey
from inv_customerproduct left join inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where inv_mas_product.group IN ('tds','sto','svh','svi','sac','spp','xbrl') having `group` in (".$list1.") and  
inv_mas_customer.currentdealer = '".$userid."' and (inv_customerproduct.softkey LIKE '%".$textfield."%' ) ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." order by businessname)as table1
left join
(select distinct inv_mas_customer.businessname ,inv_customerproduct.customerreference ,inv_mas_customer.currentdealer,
inv_mas_product.group, inv_mas_customer.district, inv_mas_district.statecode, inv_mas_customer.region,inv_mas_customer.type, inv_mas_customer.category,inv_mas_customer.branch,inv_customerproduct.softkey
from inv_customerproduct left join inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where inv_mas_product.group IN ('tds','sto','svh','svi','sac','spp','xbrl') having `group` not  in (".$list2.") 
and  inv_mas_customer.currentdealer = '".$userid."' and (inv_customerproduct.softkey LIKE '%".$textfield."%') ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." order by businessname) as table2 on
table1.group = table2.group  ";
			$result = runmysqlquery($query);
			}
			break;		
			case 'billno':
			{
				$query = "select distinct table1.businessname,table1.customerreference
from (select distinct inv_mas_customer.businessname ,inv_customerproduct.customerreference ,inv_mas_customer.currentdealer,
inv_mas_product.group, inv_mas_customer.district, inv_mas_district.statecode, inv_mas_customer.region,inv_mas_customer.type, inv_mas_customer.category,inv_mas_customer.branch,inv_customerproduct.billnumber
from inv_customerproduct left join inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where inv_mas_product.group IN ('tds','sto','svh','svi','sac','spp','xbrl') having `group` in (".$list1.") and  
inv_mas_customer.currentdealer = '".$userid."' and (inv_customerproduct.billnumber LIKE '%".$textfield."%' ) ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." order by businessname)as table1
left join
(select distinct inv_mas_customer.businessname ,inv_customerproduct.customerreference ,inv_mas_customer.currentdealer,
inv_mas_product.group, inv_mas_customer.district, inv_mas_district.statecode, inv_mas_customer.region,inv_mas_customer.type, inv_mas_customer.category,inv_mas_customer.branch,inv_customerproduct.billnumber
from inv_customerproduct left join inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where inv_mas_product.group IN ('tds','sto','svh','svi','sac','spp','xbrl') having `group` not  in (".$list2.") 
and  inv_mas_customer.currentdealer = '".$userid."' and (inv_customerproduct.billnumber LIKE '%".$textfield."%') ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." order by businessname) as table2 on
table1.group = table2.group  ";
			$result = runmysqlquery($query);
			}
			break;
			default:
			$query = "select distinct table1.businessname,table1.customerreference
from (select distinct inv_mas_customer.businessname ,inv_customerproduct.customerreference ,inv_mas_customer.currentdealer,
inv_mas_product.group, inv_mas_customer.district, inv_mas_district.statecode, inv_mas_customer.region,inv_mas_customer.type, inv_mas_customer.category,inv_mas_customer.branch
from inv_customerproduct left join inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where inv_mas_product.group IN ('tds','sto','svh','svi','sac','spp','xbrl') having `group` in (".$list1.") and  
inv_mas_customer.currentdealer = '".$userid."' and (inv_mas_customer.businessname LIKE '%".$textfield."%' ) ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece."order by businessname)as table1
left join
(select distinct inv_mas_customer.businessname ,inv_customerproduct.customerreference ,inv_mas_customer.currentdealer,
inv_mas_product.group, inv_mas_customer.district, inv_mas_district.statecode, inv_mas_customer.region,inv_mas_customer.type, inv_mas_customer.category,inv_mas_customer.branch from inv_customerproduct left join inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where inv_mas_product.group IN ('tds','sto','svh','svi','sac','spp','xbrl') having `group` not  in (".$list2.") 
and  inv_mas_customer.currentdealer = '".$userid."' and (inv_mas_customer.businessname LIKE '%".$textfield."%' ) ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece."  order by businessname) as table2 on
table1.group = table2.group  ";

		}	
		$result = runmysqlquery($query);
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','121','".date('Y-m-d').' '.date('H:i:s')."')";
		$eventresult = runmysqlquery($eventquery);
		
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$responsearray6[$count] = $fetch['businessname'].'^'.$fetch['customerreference'];
			$count++;
		}
		echo(json_encode($responsearray6));
		//echo($query);
	}
	break;

}
function checkstatus($lastslno,$updatestatus)
{
	$query = "Select inv_crossproductstatus.status from inv_crossproduct 
	left join inv_crossproductstatus on inv_crossproductstatus.statusnumber = inv_crossproduct.slno where inv_crossproduct.slno = '".$lastslno."' ";
	$fetch = runmysqlqueryfetch($query);
	$presentstatus = $fetch['status'];
	if($presentstatus < $updatestatus)
		return true;
	else
		return false;
}

?>