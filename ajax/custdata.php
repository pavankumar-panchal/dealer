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
	case 'getalldata':
	{
		
		$query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode,inv_mas_dealer.telecaller,inv_mas_dealer.branch,inv_mas_dealer.region
		from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
		 where inv_mas_dealer.slno = '".$userid."';";
		$fetch = runmysqlqueryfetch($query);
		$relyonexecutive = $fetch['relyonexecutive'];
		$district = $fetch['district'];
		$state = $fetch['statecode'];
		$branch = $fetch['branch'];
		$region = $fetch['region'];
		$telecaller = $fetch['telecaller'];
		if($telecaller == 'yes')
		{
			$query = "select slno as slno, businessname as businessname from inv_mas_customer where branch in( select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."') order by businessname;";
			$result = runmysqlquery($query);
			$dealerpiece = " AND inv_mas_customer.branch in (select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."')";
			if(mysqli_num_rows($result) == 0)
			{
				$dealerpiece = " AND inv_mas_customer.branch = '".$branch."' ";
			}
		}
		else
		{
			if($relyonexecutive == 'no')
			{
				$dealerpiece = " AND inv_mas_customer.currentdealer = '".$userid."'";
			}
			else
			{
				if(($region == '1') || ($region == '3'))
				{
					$dealerpiece = " AND (inv_mas_customer.region = '1'  or inv_mas_customer.region = '3') ";
				}
				else
				{
					$query = "select slno as slno, businessname as businessname from inv_mas_customer where branch in( select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."') order by businessname;";
					$result = runmysqlquery($query);
					if(mysqli_num_rows($result) == 0)
					{
						$dealerpiece = " AND inv_mas_customer.branch = '".$branch."' ";
					}
					else
						$dealerpiece = " AND inv_mas_customer.branch in (select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."')";
				}
			}
		}
		
		//Count of Blank Emailid
		$query1 = "SELECT count(distinct inv_mas_customer.slno) as slnocount
from inv_mas_customer left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
where inv_mas_customer.slno not in (select inv_contactdetails.customerid as slno
from inv_contactdetails) ".$dealerpiece."";
		$fetch1 = runmysqlqueryfetch($query1);
		$blankcount = $fetch1['slnocount'];
		
		
		
		//Fetch all Dealer Emailid's 
		$query1 = "select distinct slno,emailid from inv_mas_dealer order by inv_mas_dealer.businessname";
		$fetch1 = runmysqlquery($query1);
		while($resultfetch = mysqli_fetch_array($fetch1))
		{
			$arrayofemailid[] = $resultfetch['emailid'];
		}
		for($i = 0; $i < count($arrayofemailid); $i++)
		{
		   if($i == 0)
			  $concatenatedIDs .= '\''.$arrayofemailid[$i].'\'';
		   else
			  $concatenatedIDs .= ',' . '\''.$arrayofemailid[$i].'\'';
		}

		//Count of Relyon Emailid
		$query2 = 'SELECT count(distinct inv_mas_customer.slno) as slnocount 
 from inv_mas_customer left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
where inv_mas_customer.slno  in (select inv_contactdetails.customerid as slno
from inv_contactdetails where (inv_contactdetails.emailid like "%@relyonsoft.com" and inv_contactdetails.emailid not in ('.$concatenatedIDs.'))) '.$dealerpiece.'';
		$fetch2 = runmysqlqueryfetch($query2);
		$relyoncount = $fetch2['slnocount'];
		
		//Count of Dealer Emailid
		$query3 = 'SELECT count(distinct inv_mas_customer.slno) as slnocount 
 from inv_mas_customer left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
where inv_mas_customer.slno  in (select inv_contactdetails.customerid as slno
from inv_contactdetails where inv_contactdetails.emailid not like "%a@a.com%" 
and (inv_contactdetails.emailid <> "" or  inv_contactdetails.emailid is not null)  and
(inv_contactdetails.emailid  in ('.$concatenatedIDs.'))) '.$dealerpiece.'';
		$fetch3 = runmysqlqueryfetch($query3);
		$dealercount = $fetch3['slnocount'];
		
		//Count of Dummy Emailid
		$query4 ="select count(distinct inv_mas_customer.slno) as slnocount 
from inv_mas_customer left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
where LENGTH(inv_contactdetails.emailid) < 8 and (inv_contactdetails.emailid <> '' or inv_contactdetails.emailid is null)
or (inv_contactdetails.emailid REGEXP '([,][\s]|[;][\s]|[,;]|[ ])')
or  (inv_contactdetails.emailid REGEXP '^[ ]$') or  (inv_contactdetails.emailid like '%df@dff.com%') ".$dealerpiece.";";
		$fetch4 = runmysqlqueryfetch($query4);
		$dummycount = $fetch4['slnocount'];
		
		//Count of total email IDs/records
		$query41 ="SELECT count(distinct inv_contactdetails.emailid) as slnocount
from inv_contactdetails left join inv_mas_customer on inv_mas_customer.slno= inv_contactdetails.customerid where (inv_contactdetails.emailid <> '' or inv_contactdetails.emailid is  null) ".$dealerpiece."";
		$fetch41 = runmysqlqueryfetch($query41);
		$totalemailidcount = $fetch41['slnocount'];
		
		//Count of Blank Cell Nos
		$query5 = "SELECT count(distinct inv_mas_customer.slno) as slnocount
from inv_mas_customer left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
where inv_mas_customer.slno not in (select inv_contactdetails.customerid as slno
from inv_contactdetails) ".$dealerpiece."";
		$fetch5 = runmysqlqueryfetch($query5);
		$blankcellcount = $fetch5['slnocount'];
		
		//Count of Dummy Cell Nos
		$query6 = "SELECT count(distinct inv_mas_customer.slno) as slnocount
 from inv_mas_customer left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
where inv_mas_customer.slno  in (select inv_contactdetails.customerid as slno
from inv_contactdetails where length(inv_contactdetails.cell)< 10  and 
inv_contactdetails.cell <> '' or (inv_contactdetails.cell like '%9999999999%')
or (inv_contactdetails.cell like '%8888888888%')or (inv_contactdetails.cell like '%7777777777%')) ".$dealerpiece."";
		$fetch6 = runmysqlqueryfetch($query6);
		$dummycellcount = $fetch6['slnocount'];
		
		//Count of total cell/records
		$query42 ="SELECT count(distinct inv_contactdetails.cell) as slnocount
from inv_contactdetails left join inv_mas_customer on inv_mas_customer.slno= inv_contactdetails.customerid where (inv_contactdetails.cell <> '' or inv_contactdetails.cell is  null) ".$dealerpiece."";
		$fetch42 = runmysqlqueryfetch($query42);
		$totalcellcount = $fetch42['slnocount'];
		
		//Count of Blank Phone Nos
		$query7 = "SELECT count(distinct inv_mas_customer.slno) as slnocount
from inv_mas_customer left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
where inv_mas_customer.slno not in (select inv_contactdetails.customerid as slno
from inv_contactdetails) ".$dealerpiece."";
		$fetch7 = runmysqlqueryfetch($query7);
		$blankphonecount = $fetch7['slnocount'];
		
		//Count of Dummy Phone Nos
		$query8 = "SELECT count(distinct inv_mas_customer.slno) as slnocount
 from inv_mas_customer left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
where inv_mas_customer.slno  in (select inv_contactdetails.customerid as slno
from inv_contactdetails where (length(inv_contactdetails.phone)< 6  and 
inv_contactdetails.phone <> '') or (inv_contactdetails.phone like '%222222%')
or (inv_contactdetails.phone like '%33333%') or (inv_contactdetails.phone like '%44444%')
or (inv_contactdetails.phone like '%55555%') or (inv_contactdetails.phone like '%66666%')
or (inv_contactdetails.phone like '%77777%') or (inv_contactdetails.phone like '%88888%')
or (inv_contactdetails.phone like '%99999%') or (inv_contactdetails.phone like '%00000%')
or (inv_contactdetails.phone like '%11111%') or( inv_contactdetails.phone like '%12345%')) ".$dealerpiece."";
		$fetch8 = runmysqlqueryfetch($query8);
		$dummyphonecount = $fetch8['slnocount'];
		
		//Count of total cell/records
		$query43 ="SELECT count(distinct inv_contactdetails.phone) as slnocount
from inv_contactdetails left join inv_mas_customer on inv_mas_customer.slno= inv_contactdetails.customerid where (inv_contactdetails.phone <> '' or inv_contactdetails.phone is  null) ".$dealerpiece."";
		$fetch43 = runmysqlqueryfetch($query43);
		$totalphonecount = $fetch43['slnocount'];
		
		
		//Count of Customer Type Not Selected
		$query9 = "select count(distinct inv_mas_customer.slno) as slnocount from inv_mas_customer where (`type` = '' or `type` is null) ".$dealerpiece."";
		$fetch9 = runmysqlqueryfetch($query9);
		$notselectedtype = $fetch9['slnocount'];
		
		//Count of Customer Type Others
		$query11 = "select count(distinct inv_mas_customer.slno) as slnocount from inv_mas_customer where (`type` = '9') ".$dealerpiece."";
		$fetch11 = runmysqlqueryfetch($query11);
		$typeothers = $fetch11['slnocount'];
		
		//Count of Customer Type Not Selected
		$query19 = "select count(distinct inv_mas_customer.slno) as slnocount from inv_mas_customer where (`type` <> '') ".$dealerpiece."";
		$fetch19 = runmysqlqueryfetch($query19);
		$resultanttotaltype = $fetch19['slnocount'];
		
		//Count of Customer Category Not Selected
		$query10 = "select count(distinct inv_mas_customer.slno) as slnocount from inv_mas_customer where (category = '' or category is null) ".$dealerpiece.";";
		$fetch10 = runmysqlqueryfetch($query10);
		$notselectedcategory = $fetch10['slnocount'];
		
		//Count of Customer Category Others
		$query12 = "select count(distinct inv_mas_customer.slno) as slnocount from inv_mas_customer where (category = '10') ".$dealerpiece.";";
		$fetch12 = runmysqlqueryfetch($query12);
		$categoryothers = $fetch12['slnocount'];
		
		//Count of Customer Category Not Selected
		$query13 = "select count(distinct inv_mas_customer.slno) as slnocount from inv_mas_customer where (category <> '') ".$dealerpiece.";";
		$fetch13 = runmysqlqueryfetch($query13);
		$resultanttotalcategory = $fetch13['slnocount'];
		
		
		echo('1^'.$blankcount.'^'.$relyoncount.'^'.$dealercount.'^'.$dummycount.'^'.$totalemailidcount.'^'.$blankcellcount.'^'.$dummycellcount.'^'.$totalcellcount.'^'.$blankphonecount.'^'.$dummyphonecount.'^'.$totalphonecount.'^'.$notselectedtype.'^'.$notselectedcategory.'^'.$typeothers.'^'.$categoryothers.'^'.$resultanttotaltype.'^'.$resultanttotalcategory);
		
	}
	break;
	
	case 'detailemailgrid':
	{
		$type = $_POST['type'];
		$query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode,inv_mas_dealer.telecaller,inv_mas_dealer.branch,inv_mas_dealer.region
		from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
		 where inv_mas_dealer.slno = '".$userid."';";
		$fetch = runmysqlqueryfetch($query);
		$relyonexecutive = $fetch['relyonexecutive'];
		$district = $fetch['district'];
		$state = $fetch['statecode'];
		$branch = $fetch['branch'];
		$region = $fetch['region'];
		$telecaller = $fetch['telecaller'];
		if($telecaller == 'yes')
		{
			$query = "select slno as slno, businessname as businessname from inv_mas_customer where branch in( select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."') order by businessname;";
			$result = runmysqlquery($query);
			$dealerpiece = " AND inv_mas_customer.branch in (select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."')";
			if(mysqli_num_rows($result) == 0)
			{
				$dealerpiece = " AND inv_mas_customer.branch = '".$branch."' ";
			}
		}
		else
		{
			if($relyonexecutive == 'no')
			{
				$dealerpiece = " AND inv_mas_customer.currentdealer = '".$userid."'";
			}
			else
			{
				if(($region == '1') || ($region == '3'))
				{
					$dealerpiece = " AND (inv_mas_customer.region = '1'  or inv_mas_customer.region = '3') ";
				}
				else
				{
					$query = "select slno as slno, businessname as businessname from inv_mas_customer where branch in( select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."') order by businessname;";
					$result = runmysqlquery($query);
					if(mysqli_num_rows($result) == 0)
					{
						$dealerpiece = " AND inv_mas_customer.branch = '".$branch."' ";
					}
					else
						$dealerpiece = " AND inv_mas_customer.branch in (select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."')";
				}
			}
		}
		//Fetch all Dealer Emailid's 
		$query1 = "select distinct slno,emailid from inv_mas_dealer order by inv_mas_dealer.businessname";
		$fetch1 = runmysqlquery($query1);
		while($resultfetch = mysqli_fetch_array($fetch1))
		{
			$arrayofemailid[] = $resultfetch['emailid'];
		}
		for($i = 0; $i < count($arrayofemailid); $i++)
		{
		   if($i == 0)
			  $concatenatedIDs .= '\''.$arrayofemailid[$i].'\'';
		   else
			  $concatenatedIDs .= ',' . '\''.$arrayofemailid[$i].'\'';
		}
		switch($type)
		{
			case 'blank' : 
						{
							$query = "SELECT distinct inv_mas_customer.slno as slno, inv_mas_customer.customerid as customerid,inv_mas_customer.customerid as customerid,inv_mas_customer.businessname as company,trim(both ',' from GROUP_CONCAT(inv_contactdetails.contactperson)) as contactperson, trim(both ',' from GROUP_CONCAT(inv_contactdetails.emailid)) as emailid from inv_mas_customer left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno where inv_mas_customer.slno not in (select inv_contactdetails.customerid as slno
from inv_contactdetails) ".$dealerpiece." group  by inv_mas_customer.slno order by inv_mas_customer.businessname";
							$tabdescription = "List of customers with Blank Emailid's";
						}
						break;
			
			case 'relyon' : 
						{
							$query = 'SELECT distinct inv_mas_customer.slno as slno,inv_mas_customer.customerid as customerid,
inv_mas_customer.businessname as company,trim(both "," from GROUP_CONCAT(inv_contactdetails.contactperson)) as contactperson,
trim(both "," from GROUP_CONCAT(inv_contactdetails.emailid)) as emailid
 from inv_mas_customer left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
where inv_mas_customer.slno  in (select inv_contactdetails.customerid as slno
from inv_contactdetails where (inv_contactdetails.emailid like "%@relyonsoft.com" and inv_contactdetails.emailid not in ('.$concatenatedIDs.'))) '.$dealerpiece.' group  by inv_contactdetails.customerid  order by inv_mas_customer.businessname';
							$tabdescription = "List of customers with Relyon Emailid's";
						}
						break;
			
			case 'dealer' : 
						{
							$query = 'SELECT distinct inv_mas_customer.slno as slno,inv_mas_customer.customerid as customerid,
inv_mas_customer.businessname as company,trim(both "," from GROUP_CONCAT(inv_contactdetails.contactperson)) as contactperson,
trim(both "," from GROUP_CONCAT(inv_contactdetails.emailid)) as emailid
 from inv_mas_customer left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
where inv_mas_customer.slno  in (select inv_contactdetails.customerid as slno
from inv_contactdetails where inv_contactdetails.emailid not like "%a@a.com%" 
and (inv_contactdetails.emailid <> "" or  inv_contactdetails.emailid is not null)  and
 (inv_contactdetails.emailid in ('.$concatenatedIDs.'))) '.$dealerpiece.' group  by inv_contactdetails.customerid  order by inv_mas_customer.businessname';
 							$tabdescription = "List of customers with Dealer Emailid's";
						}
						break;
			case 'dummy' : 
						{
							$query = "select distinct inv_mas_customer.slno as slno,inv_mas_customer.customerid as customerid,
inv_mas_customer.businessname as company,trim(both ',' from GROUP_CONCAT(inv_contactdetails.contactperson)) as contactperson,
trim(both ',' from GROUP_CONCAT(inv_contactdetails.emailid)) as emailid
from inv_mas_customer left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
where LENGTH(inv_contactdetails.emailid) < 8 and (inv_contactdetails.emailid <> '' or inv_contactdetails.emailid is null)
or (inv_contactdetails.emailid REGEXP '([,][\s]|[;][\s]|[,;]|[ ])')
or  (inv_contactdetails.emailid REGEXP '^[ ]$') or  (inv_contactdetails.emailid like '%df@dff.com%') ".$dealerpiece." group  by inv_contactdetails.customerid  order by inv_mas_customer.businessname";
							$tabdescription = "List of customers with Dummy Emailid's";
						}
						break;
		}
		$result = runmysqlquery($query);
		$grid .= '<table width="100%" border="0" cellspacing="0" cellpadding="5" class="table-border-grid" >';
		$grid .= '<tr class="tr-grid-header">';
		$grid .= '<td width="16%" align="center" nowrap = "nowrap" class="td-border-grid">Slno</td>';
		$grid .= '<td width="17%" align="center" nowrap = "nowrap" class="td-border-grid">Customer ID</td>';
		$grid .= '<td width="17%" align="center" nowrap = "nowrap" class="td-border-grid">Company</td>';
		$grid .= '<td width="17%" align="center" nowrap = "nowrap" class="td-border-grid">Contact person</td>';
		$grid .= '<td width="16%" align="center" nowrap = "nowrap" class="td-border-grid">Email ID</td>';
		$grid .= '<td width="17%" align="center" nowrap = "nowrap" class="td-border-grid">&nbsp;</td>';
		$grid .= '</tr>';
		if(mysqli_num_rows($result) <> 0)
		{
			$slno = 0;
			while($fetchcount = mysqli_fetch_array($result))
			{
					if($fetchcount['contactperson'] <> '')
					{
						$splitcommaincontact = explode(',',$fetchcount['contactperson']);
						$splitcommaincontactcount = count($splitcommaincontact);
						$splitcommaincontactdisplay = $splitcommaincontact[0];
					}
					else
					{
						$splitcommaincontactdisplay = 'Not Avaliable';
						$splitcommaincontactcount = 0;
					}
					if($fetchcount['emailid'] <> '')
					{
						$splitcommainemail = explode(',',$fetchcount['emailid']);
						$splitcommainemailcount = count($splitcommainemail);
						$splitcommainemaildisplay = $splitcommainemail[0];
					}
					else
					{
						$splitcommainemaildisplay = 'Not Avaliable';
						$splitcommainemailcount = 0;
					}
					
					$slno++;
					$grid .= '<tr >';
					$grid .= '<td align="left" nowrap = "nowrap" class="td-border-grid">'.$slno.'</td>';
					$grid .= '<td align="left" nowrap = "nowrap" class="td-border-grid">'.cusidcombine($fetchcount['customerid']).'</td>';
					$grid .= '<td align="left" nowrap = "nowrap" class="td-border-grid">'.gridtrim($fetchcount['company']).'</td>';
					
					$grid .= '<td align="left" nowrap = "nowrap" class="td-border-grid">'.gridtrim($splitcommaincontactdisplay.' '.'('.$splitcommaincontactcount.')').'</td>';
					$grid .= '<td align="left" nowrap = "nowrap" class="td-border-grid">'.$splitcommainemaildisplay.' '.'('.$splitcommainemailcount.')'.'</td>';
					$grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"> <a onclick="viewhistory(\''.$fetchcount['slno'].'\');" class="resendtext" style = "cursor:pointer"> View Details >></a> </td>';
					$grid .= '</tr>';
			}
			$grid .= '</table>';	
		}
		elseif(mysqli_num_rows($result) == 0)
		{
			$grid .= '<tr><td colspan="6" bgcolor="#FFFFD2" class="td-border-grid" style="font-weight:bold"><font color="#FF4F4F" >No Records</font></td></tr>';
		
		}
		//sleep(10);
		echo('1^'.$grid.'^'.$tabdescription);
	}
	break;
	
	case 'detailcellgrid':
	{
		$celltype = $_POST['type'];
		$query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode,inv_mas_dealer.telecaller,inv_mas_dealer.branch,inv_mas_dealer.region
		from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
		 where inv_mas_dealer.slno = '".$userid."';";
		$fetch = runmysqlqueryfetch($query);
		$relyonexecutive = $fetch['relyonexecutive'];
		$district = $fetch['district'];
		$state = $fetch['statecode'];
		$branch = $fetch['branch'];
		$region = $fetch['region'];
		$telecaller = $fetch['telecaller'];
		if($telecaller == 'yes')
		{
			$query = "select slno as slno, businessname as businessname from inv_mas_customer where branch in( select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."') order by businessname;";
			$result = runmysqlquery($query);
			$dealerpiece = " AND inv_mas_customer.branch in (select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."')";
			if(mysqli_num_rows($result) == 0)
			{
				$dealerpiece = " AND inv_mas_customer.branch = '".$branch."' ";
			}
		}
		else
		{
			if($relyonexecutive == 'no')
			{
				$dealerpiece = " AND inv_mas_customer.currentdealer = '".$userid."'";
			}
			else
			{
				if(($region == '1') || ($region == '3'))
				{
					$dealerpiece = " AND (inv_mas_customer.region = '1'  or inv_mas_customer.region = '3') ";
				}
				else
				{
					$query = "select slno as slno, businessname as businessname from inv_mas_customer where branch in( select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."') order by businessname;";
					$result = runmysqlquery($query);
					if(mysqli_num_rows($result) == 0)
					{
						$dealerpiece = " AND inv_mas_customer.branch = '".$branch."' ";
					}
					else
						$dealerpiece = " AND inv_mas_customer.branch in (select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."')";
				}
			}
		}
		switch($celltype)
		{
			case 'blank' : 
						{
							$query = "SELECT distinct inv_mas_customer.slno as slno, inv_mas_customer.customerid as customerid,inv_mas_customer.customerid as customerid,inv_mas_customer.businessname as company,trim(both ',' from GROUP_CONCAT(inv_contactdetails.contactperson)) as contactperson, trim(both ',' from GROUP_CONCAT(inv_contactdetails.cell)) as cell from inv_mas_customer left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno where inv_mas_customer.slno not in (select inv_contactdetails.customerid as slno
from inv_contactdetails) ".$dealerpiece." group  by inv_mas_customer.slno order by inv_mas_customer.businessname";
							$tabdescription = "List of customers with Blank Cell Numbers";
						}
						break;
			case 'dummy' : 
						{
							$query = "SELECT distinct inv_mas_customer.slno as slno, inv_mas_customer.customerid as customerid,inv_mas_customer.customerid as customerid,inv_mas_customer.businessname as company,trim(both ',' from GROUP_CONCAT(inv_contactdetails.contactperson)) as contactperson, trim(both ',' from GROUP_CONCAT(inv_contactdetails.cell)) as cell
 from inv_mas_customer left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
where inv_mas_customer.slno  in (select inv_contactdetails.customerid as slno
from inv_contactdetails where length(inv_contactdetails.cell)< 10  and 
inv_contactdetails.cell <> '' or (inv_contactdetails.cell like '%9999999999%')
or (inv_contactdetails.cell like '%8888888888%')or (inv_contactdetails.cell like '%7777777777%')) ".$dealerpiece." 
group  by inv_mas_customer.slno order by inv_mas_customer.businessname";
							$tabdescription = "List of customers with Dummy Cell Numbers";
						}
						break;
		}
		$result1 = runmysqlquery($query);
		$grid .= '<table width="100%" border="0" cellspacing="0" cellpadding="5" class="table-border-grid">';
		$grid .= '<tr class="tr-grid-header">';
		$grid .= '<td width="16%" align="center" nowrap = "nowrap" class="td-border-grid">Slno</td>';
		$grid .= '<td width="17%" align="center" nowrap = "nowrap" class="td-border-grid">Customer ID</td>';
		$grid .= '<td width="17%" align="center" nowrap = "nowrap" class="td-border-grid">Company</td>';
		$grid .= '<td width="17%" align="center" nowrap = "nowrap" class="td-border-grid">Contact person</td>';
		$grid .= '<td width="16%" align="center" nowrap = "nowrap" class="td-border-grid">Cell</td>';
		$grid .= '<td width="17%" align="center" nowrap = "nowrap" class="td-border-grid">&nbsp;</td>';
		$grid .= '</tr>';
		if(mysqli_num_rows($result1) <> 0)
		{
			$slno = 0;
			while($fetchcount = mysqli_fetch_array($result1))
			{
				
					if($fetchcount['contactperson'] <> '')
					{
						$splitcommaincontact = explode(',',$fetchcount['contactperson']);
						$splitcommaincontactcount = count($splitcommaincontact);
						$splitcommaincontactdisplay = $splitcommaincontact[0];
					}
					else
					{
						$splitcommaincontactdisplay = 'Not Avaliable';
						$splitcommaincontactcount = 0;
					}
					if($fetchcount['cell'] <> '')
					{
						$splitcommaincell = explode(',',$fetchcount['cell']);
						$splitcommaincellcount = count($splitcommaincell);
						$splitcommaincelldisplay = $splitcommaincell[0];
					}
					else
					{
						$splitcommaincelldisplay = 'Not Avaliable';
						$splitcommaincellcount = 0;
					}
					$slno++;
					$grid .= '<tr>';
					$grid .= '<td align="left" nowrap = "nowrap" class="td-border-grid">'.$slno.'</td>';
					$grid .= '<td align="left" nowrap = "nowrap" class="td-border-grid">'.cusidcombine($fetchcount['customerid']).'</td>';
					$grid .= '<td align="left" nowrap = "nowrap" class="td-border-grid">'.gridtrim($fetchcount['company']).'</td>';
					
					$grid .= '<td align="left" nowrap = "nowrap" class="td-border-grid">'.gridtrim($splitcommaincontactdisplay.' '.'('.$splitcommaincontactcount.')').'</td>';
					$grid .= '<td align="left" nowrap = "nowrap" class="td-border-grid">'.$splitcommaincelldisplay.' '.'('.$splitcommaincellcount.')'.'</td>';
					$grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"> <a onclick="viewhistory(\''.$fetchcount['slno'].'\');" class="resendtext" style = "cursor:pointer"> View Details >></a> </td>';
					$grid .= '</tr>';
			}
			$grid .= '</table>';	
		}
		elseif(mysqli_num_rows($result1) == 0)
		{
			$grid .= '<tr><td colspan="6" bgcolor="#FFFFD2" class="td-border-grid" style="font-weight:bold"><font color="#FF4F4F" >No Records</font></td></tr>';
		
		}
		//sleep(10);
		echo('1^'.$grid.'^'.$tabdescription);
	}
	break;
	
	case 'detailphonegrid':
	{
		$phonetype = $_POST['type'];
		$query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode,inv_mas_dealer.telecaller,inv_mas_dealer.branch,inv_mas_dealer.region
		from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
		 where inv_mas_dealer.slno = '".$userid."';";
		$fetch = runmysqlqueryfetch($query);
		$relyonexecutive = $fetch['relyonexecutive'];
		$district = $fetch['district'];
		$state = $fetch['statecode'];
		$branch = $fetch['branch'];
		$region = $fetch['region'];
		$telecaller = $fetch['telecaller'];
		if($telecaller == 'yes')
		{
			$query = "select slno as slno, businessname as businessname from inv_mas_customer where branch in( select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."') order by businessname;";
			$result = runmysqlquery($query);
			$dealerpiece = " AND inv_mas_customer.branch in (select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."')";
			if(mysqli_num_rows($result) == 0)
			{
				$dealerpiece = " AND inv_mas_customer.branch = '".$branch."' ";
			}
		}
		else
		{
			if($relyonexecutive == 'no')
			{
				$dealerpiece = " AND inv_mas_customer.currentdealer = '".$userid."'";
			}
			else
			{
				if(($region == '1') || ($region == '3'))
				{
					$dealerpiece = " AND (inv_mas_customer.region = '1'  or inv_mas_customer.region = '3') ";
				}
				else
				{
					$query = "select slno as slno, businessname as businessname from inv_mas_customer where branch in( select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."') order by businessname;";
					$result = runmysqlquery($query);
					if(mysqli_num_rows($result) == 0)
					{
						$dealerpiece = " AND inv_mas_customer.branch = '".$branch."' ";
					}
					else
						$dealerpiece = " AND inv_mas_customer.branch in (select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."')";
				}
			}
		}
		switch($phonetype)
		{
			case 'blank' : 
						{
							$query = "SELECT distinct inv_mas_customer.slno as slno, inv_mas_customer.customerid as customerid,inv_mas_customer.customerid as customerid,inv_mas_customer.businessname as company,trim(both ',' from GROUP_CONCAT(inv_contactdetails.contactperson)) as contactperson, trim(both ',' from GROUP_CONCAT(inv_contactdetails.phone)) as phone from inv_mas_customer left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno where inv_mas_customer.slno not in (select inv_contactdetails.customerid as slno from inv_contactdetails) ".$dealerpiece." group  by inv_mas_customer.slno order by inv_mas_customer.businessname";
							$tabdescription = "List of customers with Blank Phone Numbers";
						}
						break;
			case 'dummy' : 
						{
							$query = "SELECT distinct inv_mas_customer.slno as slno, inv_mas_customer.customerid as customerid,inv_mas_customer.customerid as customerid,inv_mas_customer.businessname as company,trim(both ',' from GROUP_CONCAT(inv_contactdetails.contactperson)) as contactperson, trim(both ',' from GROUP_CONCAT(inv_contactdetails.phone)) as phone
 from inv_mas_customer left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
where inv_mas_customer.slno  in (select inv_contactdetails.customerid as slno
from inv_contactdetails where (length(inv_contactdetails.phone)< 6  and 
inv_contactdetails.phone <> '') or (inv_contactdetails.phone like '%222222%')
or (inv_contactdetails.phone like '%33333%') or (inv_contactdetails.phone like '%44444%')
or (inv_contactdetails.phone like '%55555%') or (inv_contactdetails.phone like '%66666%')
or (inv_contactdetails.phone like '%77777%') or (inv_contactdetails.phone like '%88888%')
or (inv_contactdetails.phone like '%99999%') or (inv_contactdetails.phone like '%00000%')
or (inv_contactdetails.phone like '%11111%') or( inv_contactdetails.phone like '%12345%')) ".$dealerpiece." group  by inv_mas_customer.slno order by inv_mas_customer.businessname";
							$tabdescription = "List of customers with Dummy Phone Numbers";
						}
						break;
		}
		$result2 = runmysqlquery($query);
		$grid .= '<table width="100%" border="0" cellspacing="0" cellpadding="5" class="table-border-grid">';
		$grid .= '<tr class="tr-grid-header">';
		$grid .= '<td width="16%" align="center" nowrap = "nowrap" class="td-border-grid">Slno</td>';
		$grid .= '<td width="17%" align="center" nowrap = "nowrap" class="td-border-grid">Customer ID</td>';
		$grid .= '<td width="17%" align="center" nowrap = "nowrap" class="td-border-grid">Company</td>';
		$grid .= '<td width="17%" align="center" nowrap = "nowrap" class="td-border-grid">Contact person</td>';
		$grid .= '<td width="16%" align="center" nowrap = "nowrap" class="td-border-grid">Phone</td>';
		$grid .= '<td width="17%" align="center" nowrap = "nowrap" class="td-border-grid">&nbsp;</td>';
		$grid .= '</tr>';
		if(mysqli_num_rows($result2) <> 0)
		{
			$slno = 0;
			while($fetchcount = mysqli_fetch_array($result2))
			{
				
					if($fetchcount['contactperson'] <> '')
					{
						$splitcommaincontact = explode(',',$fetchcount['contactperson']);
						$splitcommaincontactcount = count($splitcommaincontact);
						$splitcommaincontactdisplay = $splitcommaincontact[0];
					}
					else
					{
						$splitcommaincontactdisplay = 'Not Avaliable';
						$splitcommaincontactcount = 0;
					}
					if($fetchcount['phone'] <> '')
					{
						$splitcommainphone = explode(',',$fetchcount['phone']);
						$splitcommainphonecount = count($splitcommainphone);
						$splitcommainphonedisplay = $splitcommainphone[0];
					}
					else
					{
						$splitcommainphonedisplay = 'Not Avaliable';
						$splitcommainphonecount = 0;
					}
					$slno++;
					$grid .= '<tr>';
					$grid .= '<td align="left" nowrap = "nowrap" class="td-border-grid">'.$slno.'</td>';
					$grid .= '<td align="left" nowrap = "nowrap" class="td-border-grid">'.cusidcombine($fetchcount['customerid']).'</td>';
					$grid .= '<td align="left" nowrap = "nowrap" class="td-border-grid">'.gridtrim($fetchcount['company']).'</td>';
					
					$grid .= '<td align="left" nowrap = "nowrap" class="td-border-grid">'.gridtrim($splitcommaincontactdisplay.' '.'('.$splitcommaincontactcount.')').'</td>';
					$grid .= '<td align="left" nowrap = "nowrap" class="td-border-grid">'.$splitcommainphonedisplay.' '.'('.$splitcommainphonecount.')'.'</td>';
					$grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"> <a onclick="viewhistory(\''.$fetchcount['slno'].'\');" class="resendtext" style = "cursor:pointer"> View Details >></a> </td>';
					$grid .= '</tr>';
			}
			$grid .= '</table>';	
		}
		elseif(mysqli_num_rows($result2) == 0)
		{
			$grid .= '<tr><td colspan="6" bgcolor="#FFFFD2" class="td-border-grid" style="font-weight:bold"><font color="#FF4F4F" >No Records</font></td></tr>';
		
		}
		//sleep(10);
		echo('1^'.$grid.'^'.$tabdescription);
	}
	break;
	
	case 'detailcustgrid':
	{
		$type = $_POST['type'];
		$query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode,inv_mas_dealer.telecaller,inv_mas_dealer.branch,inv_mas_dealer.region
		from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
		 where inv_mas_dealer.slno = '".$userid."';";
		$fetch = runmysqlqueryfetch($query);
		$relyonexecutive = $fetch['relyonexecutive'];
		$district = $fetch['district'];
		$state = $fetch['statecode'];
		$branch = $fetch['branch'];
		$region = $fetch['region'];
		$telecaller = $fetch['telecaller'];
		if($telecaller == 'yes')
		{
			$query = "select slno as slno, businessname as businessname from inv_mas_customer where branch in( select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."') order by businessname;";
			$result = runmysqlquery($query);
			$dealerpiece = " AND inv_mas_customer.branch in (select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."')";
			if(mysqli_num_rows($result) == 0)
			{
				$dealerpiece = " AND inv_mas_customer.branch = '".$branch."' ";
			}
		}
		else
		{
			if($relyonexecutive == 'no')
			{
				$dealerpiece = " AND inv_mas_customer.currentdealer = '".$userid."'";
			}
			else
			{
				if(($region == '1') || ($region == '3'))
				{
					$dealerpiece = " AND (inv_mas_customer.region = '1'  or inv_mas_customer.region = '3') ";
				}
				else
				{
					$query = "select slno as slno, businessname as businessname from inv_mas_customer where branch in( select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."') order by businessname;";
					$result = runmysqlquery($query);
					if(mysqli_num_rows($result) == 0)
					{
						$dealerpiece = " AND inv_mas_customer.branch = '".$branch."' ";
					}
					else
						$dealerpiece = " AND inv_mas_customer.branch in (select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."')";
				}
			}
		}
		switch($type)
		{
			case 'custtype' : 
						{
							$query = "SELECT distinct inv_mas_customer.slno as slno, inv_mas_customer.customerid as customerid,
inv_mas_customer.customerid as customerid,inv_mas_customer.businessname as company,
trim(both ',' from GROUP_CONCAT(inv_contactdetails.contactperson)) as contactperson, 
inv_mas_customer.type as typecategory from inv_mas_customer 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno 
where (inv_mas_customer.type = '' or inv_mas_customer.type is null) ".$dealerpiece."
group  by inv_mas_customer.slno order by inv_mas_customer.businessname";
							$tabdescription = "List of customers were Customer Type is not selected";
						}
						break;
			case 'category' : 
						{
							$query = "SELECT distinct inv_mas_customer.slno as slno, inv_mas_customer.customerid as customerid,
inv_mas_customer.customerid as customerid,inv_mas_customer.businessname as company,
trim(both ',' from GROUP_CONCAT(inv_contactdetails.contactperson)) as contactperson, 
inv_mas_customer.category as typecategory from inv_mas_customer 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno 
where (inv_mas_customer.category = '' or inv_mas_customer.category is null) ".$dealerpiece."
group  by inv_mas_customer.slno order by inv_mas_customer.businessname";
							$tabdescription = "List of customers were Customer Category is not selected";
						}
						break;
			case 'custtypeothers' : 
						{
							$query = "SELECT distinct inv_mas_customer.slno as slno, inv_mas_customer.customerid as customerid,
inv_mas_customer.customerid as customerid,inv_mas_customer.businessname as company,
trim(both ',' from GROUP_CONCAT(inv_contactdetails.contactperson)) as contactperson, 
inv_mas_customer.type as typecategory from inv_mas_customer 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno 
where (inv_mas_customer.type = '9') ".$dealerpiece."
group  by inv_mas_customer.slno order by inv_mas_customer.businessname";
							$tabdescription = "List of customers where Customer Type selection is Others";
						}
						break;
			case 'categoryothers' : 
						{
							$query = "SELECT distinct inv_mas_customer.slno as slno, inv_mas_customer.customerid as customerid,
inv_mas_customer.customerid as customerid,inv_mas_customer.businessname as company,
trim(both ',' from GROUP_CONCAT(inv_contactdetails.contactperson)) as contactperson, 
inv_mas_customer.category as typecategory from inv_mas_customer 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno 
where (inv_mas_customer.category = '10') ".$dealerpiece."
group  by inv_mas_customer.slno order by inv_mas_customer.businessname";
							$tabdescription = "List of customers where Customer Category selection is Others";
						}
						break;
		}
		$result2 = runmysqlquery($query);
		$grid .= '<table width="100%" border="0" cellspacing="0" cellpadding="5" class="table-border-grid">';
		$grid .= '<tr class="tr-grid-header">';
		$grid .= '<td width="16%" align="center" nowrap = "nowrap" class="td-border-grid">Slno</td>';
		$grid .= '<td width="17%" align="center" nowrap = "nowrap" class="td-border-grid">Customer ID</td>';
		$grid .= '<td width="17%" align="center" nowrap = "nowrap" class="td-border-grid">Company</td>';
		$grid .= '<td width="17%" align="center" nowrap = "nowrap" class="td-border-grid">Contact person</td>';
		if($type == 'custtype')
			$grid .= '<td width="16%" align="center" nowrap = "nowrap" class="td-border-grid">Customer Type</td>';
		else
			$grid .= '<td width="16%" align="center" nowrap = "nowrap" class="td-border-grid">Category</td>';
		$grid .= '<td width="17%" align="center" nowrap = "nowrap" class="td-border-grid">&nbsp;</td>';
		$grid .= '</tr>';
		if(mysqli_num_rows($result2) <> 0)
		{
			$slno = 0;
			while($fetchcount = mysqli_fetch_array($result2))
			{
				
					if($fetchcount['contactperson'] <> '')
					{
						$splitcommaincontact = explode(',',$fetchcount['contactperson']);
						$splitcommaincontactcount = count($splitcommaincontact);
						$splitcommaincontactdisplay = $splitcommaincontact[0];
					}
					else
					{
						$splitcommaincontactdisplay = 'Not Avaliable';
						$splitcommaincontactcount = 0;
					}
					if($fetchcount['typecategory'] == '0')
					{
						$resultant = 'Not Avaliable';
					}
					elseif($fetchcount['typecategory'] == '9' or $fetchcount['typecategory'] == '10')
					{
						$resultant = 'Others';
					}
					
					$slno++;
					$grid .= '<tr>';
					$grid .= '<td align="left" nowrap = "nowrap" class="td-border-grid">'.$slno.'</td>';
					$grid .= '<td align="left" nowrap = "nowrap" class="td-border-grid">'.cusidcombine($fetchcount['customerid']).'</td>';
					$grid .= '<td align="left" nowrap = "nowrap" class="td-border-grid">'.gridtrim($fetchcount['company']).'</td>';
					
					$grid .= '<td align="left" nowrap = "nowrap" class="td-border-grid">'.gridtrim($splitcommaincontactdisplay.' '.'('.$splitcommaincontactcount.')').'</td>';
					$grid .= '<td align="left" nowrap = "nowrap" class="td-border-grid">'.$resultant.'</td>';
					$grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"> <a onclick="viewhistory(\''.$fetchcount['slno'].'\');" class="resendtext" style = "cursor:pointer"> View Details >></a> </td>';
					$grid .= '</tr>';
			}
			$grid .= '</table>';	
		}
		elseif(mysqli_num_rows($result2) == 0)
		{
			$grid .= '<tr><td colspan="6" bgcolor="#FFFFD2" class="td-border-grid" style="font-weight:bold"><font color="#FF4F4F" >No Records</font></td></tr>';
		
		}
		//sleep(10);
		echo('1^'.$grid.'^'.$tabdescription);
	}
	break;
	
	case 'generatecustomerlist':
	{
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
		$grid = '';
		$count = 1;
		while($fetch = mysqli_fetch_array($result))
		{
			if($count > 1)
				$grid .='^*^';
			$grid .= $fetch['businessname'].'^'.$fetch['slno'];
			$count++;
		}
		echo($grid);
	}
	break;
	
	case 'customerdetailstoform':
	{
		$lastslno = $_POST['lastslno'];
		$query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode,inv_mas_dealer.telecaller,inv_mas_dealer.branch,inv_mas_dealer.region
		from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
		 where inv_mas_dealer.slno = '".$userid."';";
		$fetch = runmysqlqueryfetch($query);
		$relyonexecutive = $fetch['relyonexecutive'];
		$district = $fetch['district'];
		$state = $fetch['statecode'];
		$branch = $fetch['branch'];
		$region = $fetch['region'];
		$telecaller = $fetch['telecaller'];
		if($telecaller == 'yes')
		{
			$query = "select slno as slno, businessname as businessname from inv_mas_customer where branch in( select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."') order by businessname;";
			$result = runmysqlquery($query);
			$dealerpiece = " AND inv_mas_customer.branch in (select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."')";
			if(mysqli_num_rows($result) == 0)
			{
				$dealerpiece = " AND inv_mas_customer.branch = '".$branch."' ";
			}
		}
		else
		{
			if($relyonexecutive == 'no')
			{
				$dealerpiece = " AND inv_mas_customer.currentdealer = '".$userid."'";
			}
			else
			{
				if(($region == '1') || ($region == '3'))
				{
					$dealerpiece = " AND (inv_mas_customer.region = '1'  or inv_mas_customer.region = '3') ";
				}
				else
				{
					$query = "select slno as slno, businessname as businessname from inv_mas_customer where branch in( select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."') order by businessname;";
					$result = runmysqlquery($query);
					if(mysqli_num_rows($result) == 0)
					{
						$dealerpiece = " AND inv_mas_customer.branch = '".$branch."' ";
					}
					else
						$dealerpiece = " AND inv_mas_customer.branch in (select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."')";
				}
			}
		}
		$countquery = "SELECT * FROM inv_customerreqpending WHERE customerid = '".$lastslno."' AND requestfrom = 'dealer_module' and inv_customerreqpending.customerstatus ='pending';";
		$countresult = runmysqlquery($countquery);
		if(mysqli_num_rows($countresult) == 0)
		{
			$query1 = "SELECT count(*) as count from inv_mas_customer where slno = '".$lastslno."'";
			$fetch1 = runmysqlqueryfetch($query1);
			if($fetch1['count'] > 0)
			{
				$query = "SELECT inv_mas_customer.slno, inv_mas_customer.customerid, inv_mas_customer.businessname,  inv_mas_customer.address,inv_mas_customer.place, inv_mas_district.districtname as districtname,inv_mas_state.statename as state, 
	inv_mas_customer.pincode, inv_mas_customer.fax, inv_mas_region.category as regionname,
	inv_mas_branch.branchname as branchname, inv_mas_customercategory.businesstype as businesstype,inv_mas_customertype.customertype as customertype,inv_mas_customer.companyclosed, inv_mas_customer.stdcode, inv_mas_customer.website,
	inv_mas_customer.category, inv_mas_customer.type, inv_mas_customer.isdealer,inv_mas_customer.remarks, 
	inv_mas_dealer.businessname as dealername,inv_mas_customer.disablelogin,
	inv_mas_customer.corporateorder,inv_mas_customer.createddate,inv_mas_customer.activecustomer, 
	inv_mas_customer.displayinwebsite, inv_mas_customer.promotionalsms,inv_mas_customer.promotionalemail ,inv_mas_state.statecode as statecode ,inv_mas_customer.district as districtcode,inv_mas_customer.region as regioncode,inv_mas_customer.branch as branchcode,inv_mas_customer.type as typecode,inv_mas_customer.category as categorycode,inv_mas_customer.currentdealer as dealerid FROM inv_mas_customer left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode 
	left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
	left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region
	left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch
	left join inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer
	left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category
	left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type
	where inv_mas_customer.slno = '".$lastslno."'  ".$dealerpiece.";";
				$fetch = runmysqlqueryfetch($query);
				
				$query1 ="SELECT * FROM inv_contactdetails where customerid = '".$lastslno."'; ";
				$resultfetch = runmysqlquery($query1);
				$contactgrid .= '<table width="100%" border="0" cellspacing="0" cellpadding="5" class="table-border-grid">';
				$contactgrid .= '<tr class="tr-grid-header">';
				$contactgrid .= '<td width="7%" align="center" nowrap = "nowrap" class="td-border-grid">Slno</td>';
				$contactgrid .= '<td width="15%" align="center" nowrap = "nowrap" class="td-border-grid">Type</td>';
				$contactgrid .= '<td width="19%" align="center" nowrap = "nowrap" class="td-border-grid">Name</td>';
				$contactgrid .= '<td width="18%" align="center" nowrap = "nowrap" class="td-border-grid">Phone</td>';
				$contactgrid .= '<td width="15%" align="center" nowrap = "nowrap" class="td-border-grid">Cell</td>';
				$contactgrid .= '<td width="26%" align="center" nowrap = "nowrap" class="td-border-grid">Email Id</td>';
				$contactgrid .= '</tr>';
				$slno = 0;$i_n = 0;
				if(mysqli_num_rows($resultfetch) > 0)
				{
					while($fetchres = mysqli_fetch_array($resultfetch))
					{
						$slno++;$i_n++;
						if($i_n%2 == 0)
							$color = "#edf4ff";
						else
							$color = "#f7faff";
						$contactgrid .= '<tr class="gridrow" onclick="getcontactdetails(\''.$fetchres['slno'].'\',\''.$slno.'\');">';
						$contactgrid .= '<td width="7%" align="left" nowrap = "nowrap" class="td-border-grid">'.$slno.'</td>';
						$contactgrid .= '<td width="15%" align="left" nowrap = "nowrap" class="td-border-grid" id="selecttype'.$slno.'">'.$fetchres['selectiontype'].'</td>';
						$contactgrid .= '<td width="19%" align="left" nowrap = "nowrap" class="td-border-grid" id="contactperson'.$slno.'">'.$fetchres['contactperson'].'</td>';
						$contactgrid .= '<td width="18%" align="left" nowrap = "nowrap" class="td-border-grid" id="phonetype'.$slno.'">'.$fetchres['phone'].'</td>';
						$contactgrid .= '<td width="15%" align="left" nowrap = "nowrap" class="td-border-grid" id="celltype'.$slno.'">'.$fetchres['cell'].'</td>';
						$contactgrid .= '<td width="26%" align="left" nowrap = "nowrap" class="td-border-grid" id="emailtype'.$slno.'">'.$fetchres['emailid'].'</td>';
						$contactgrid .= '</tr>';
						
					}
				}
				elseif(mysqli_num_rows($resultfetch) == 0)
				{
					$contactgrid .= '<tr><td colspan="6" class="td-border-grid" height="20px"  bgcolor="#FFFFD2"><table width="100%" border="0" cellspacing="0" cellpadding="0" ><tr><td><font color="#FF4F4F">No More Records</font></td></tr></table></td></tr>';
				}
				$contactgrid .= '</table>';
				
				if($fetch['customerid'] == '')
				$customerid = '';
				else
				$customerid = cusidcombine($fetch['customerid']);
				
				$checkboxval = $fetch['activecustomer'].'$#$'.$fetch['disablelogin'].'$#$'.$fetch['corporateorder'].'$#$'.$fetch['companyclosed'].'$#$'.$fetch['isdealer'].'$#$'.$fetch['displayinwebsite'].'$#$'.$fetch['promotionalsms'].'$#$'.$fetch['promotionalemail'];
				if($fetch['fax'] == '')
					$faxtype = 'Not Avaliable';
				else
					$faxtype = $fetch['fax'];
				if($fetch['stdcode'] == '')
					$stdcodetype = 'Not Avaliable';
				else
					$stdcodetype = $fetch['stdcode'];
				if($fetch['website'] == '')
					$websitetype = 'Not Avaliable';
				else
					$websitetype = $fetch['stdcode'];
				if($fetch['customertype'] == '')
					$customertype = 'Not Avaliable';
				else
					$customertype = $fetch['customertype'];
				if($fetch['businesstype'] == '')
					$businesstype = 'Not Avaliable';
				else
					$businesstype = $fetch['businesstype'];
				if($fetch['address'] == '')
					$addresstype = 'Not Avaliable';
				else
					$addresstype = $fetch['address'];
				echo($fetch['slno'].'^'.$customerid.'^'.$fetch['businessname'].'^'.$addresstype.'^'.$fetch['place'].'^'.$fetch['districtname'].'^'.$fetch['state'].'^'.$fetch['pincode'].'^'.$fetch['regionname'].'^'.$stdcodetype.'^'.$websitetype.'^'.$businesstype.'^'.$customertype.'^'.$fetch['dealername'].'^'.$fetch['disablelogin'].'^'.changedateformatwithtime($fetch['createddate']).'^'.$fetch['corporateorder'].'^'.$faxtype.'^'.$fetch['activecustomer'].'^'.$fetch['branchname'].'^'.$fetch['companyclosed'].'^'.$fetch['isdealer'].'^'.$fetch['displayinwebsite'].'^'.$fetch['promotionalsms'].'^'.$fetch['promotionalemail'].'^'.$contactgrid.'^'.$fetch['statecode'].'^'.$fetch['districtcode'].'^'.$fetch['regioncode'].'^'.$fetch['branchcode'].'^'.$fetch['typecode'].'^'.$fetch['categorycode'].'^'.$fetch['dealerid'].'^'.$checkboxval);
			}
			else
			{
				echo($lastslno.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.'');
			}
		}
		else
		{
			$query = "SELECT inv_mas_customer.slno, inv_mas_customer.customerid, inv_customerreqpending.businessname,  inv_customerreqpending.address,inv_customerreqpending.place, inv_mas_district.districtname as districtname,inv_mas_state.statename as state, 
inv_customerreqpending.pincode, inv_customerreqpending.fax, inv_mas_region.category as regionname,
inv_mas_branch.branchname as branchname, inv_mas_customercategory.businesstype as businesstype,inv_mas_customertype.customertype as customertype,inv_customerreqpending.companyclosed, inv_customerreqpending.stdcode, inv_customerreqpending.website,
inv_customerreqpending.category, inv_customerreqpending.type, inv_mas_customer.isdealer,inv_mas_customer.remarks, 
inv_mas_dealer.businessname as dealername,inv_mas_customer.disablelogin,
inv_mas_customer.corporateorder,inv_mas_customer.createddate,inv_mas_customer.activecustomer, 
inv_mas_customer.displayinwebsite, inv_customerreqpending.promotionalsms,inv_customerreqpending.promotionalemail ,inv_mas_state.statecode as statecode ,inv_customerreqpending.district as districtcode,inv_mas_customer.region as regioncode,inv_mas_customer.branch as branchcode,inv_customerreqpending.type as typecode,inv_customerreqpending.category as categorycode,inv_mas_customer.currentdealer as dealerid FROM inv_customerreqpending 
left join inv_mas_customer on inv_mas_customer.slno = inv_customerreqpending.customerid
left join inv_mas_district on inv_customerreqpending.district = inv_mas_district.districtcode
left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region
left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch
left join inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer
left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_customerreqpending.category
left join inv_mas_customertype on inv_mas_customertype.slno = inv_customerreqpending.type
where inv_mas_customer.slno = '".$lastslno."' AND requestfrom = 'dealer_module' and inv_customerreqpending.customerstatus ='pending'  ".$dealerpiece.";";
			$fetch = runmysqlqueryfetch($query);
			
			$query1 ="SELECT * FROM inv_contactreqpending  where customerid = '".$lastslno."' AND inv_contactreqpending.requestfrom = 'dealer_module' and inv_contactreqpending.customerstatus ='pending'; ";
			$resultfetch = runmysqlquery($query1);
			$contactgrid .= '<table width="100%" border="0" cellspacing="0" cellpadding="5" class="table-border-grid">';
			$contactgrid .= '<tr class="tr-grid-header">';
			$contactgrid .= '<td width="7%" align="center" nowrap = "nowrap" class="td-border-grid">Slno</td>';
			$contactgrid .= '<td width="15%" align="center" nowrap = "nowrap" class="td-border-grid">Type</td>';
			$contactgrid .= '<td width="19%" align="center" nowrap = "nowrap" class="td-border-grid">Name</td>';
			$contactgrid .= '<td width="18%" align="center" nowrap = "nowrap" class="td-border-grid">Phone</td>';
			$contactgrid .= '<td width="15%" align="center" nowrap = "nowrap" class="td-border-grid">Cell</td>';
			$contactgrid .= '<td width="26%" align="center" nowrap = "nowrap" class="td-border-grid">Email Id</td>';
			$contactgrid .= '</tr>';
			$slno = 0;$i_n = 0;
			if(mysqli_num_rows($resultfetch) > 0)
			{
				while($fetchres = mysqli_fetch_array($resultfetch))
				{
					$slno++;$i_n++;
					if($i_n%2 == 0)
						$color = "#edf4ff";
					else
						$color = "#f7faff";
					$contactgrid .= '<tr class="gridrow" onclick="getcontactdetails(\''.$fetchres['slno'].'\',\''.$slno.'\');">';
					$contactgrid .= '<td width="7%" align="left" nowrap = "nowrap" class="td-border-grid">'.$slno.'</td>';
					$contactgrid .= '<td width="15%" align="left" nowrap = "nowrap" class="td-border-grid" id="selecttype'.$slno.'">'.$fetchres['selectiontype'].'</td>';
					$contactgrid .= '<td width="19%" align="left" nowrap = "nowrap" class="td-border-grid" id="contactperson'.$slno.'">'.$fetchres['contactperson'].'</td>';
					$contactgrid .= '<td width="18%" align="left" nowrap = "nowrap" class="td-border-grid" id="phonetype'.$slno.'">'.$fetchres['phone'].'</td>';
					$contactgrid .= '<td width="15%" align="left" nowrap = "nowrap" class="td-border-grid" id="celltype'.$slno.'">'.$fetchres['cell'].'</td>';
					$contactgrid .= '<td width="26%" align="left" nowrap = "nowrap" class="td-border-grid" id="emailtype'.$slno.'">'.$fetchres['emailid'].'</td>';
					$contactgrid .= '</tr>';
					
				}
			}
			elseif(mysqli_num_rows($resultfetch) == 0)
			{
				$contactgrid .= '<tr><td colspan="6" class="td-border-grid" height="20px"  bgcolor="#FFFFD2"><table width="100%" border="0" cellspacing="0" cellpadding="0" ><tr><td><font color="#FF4F4F">No More Records</font></td></tr></table></td></tr>';
			}
			$contactgrid .= '</table>';
			
			if($fetch['customerid'] == '')
			$customerid = '';
			else
			$customerid = cusidcombine($fetch['customerid']);
			
			$checkboxval = $fetch['activecustomer'].'$#$'.$fetch['disablelogin'].'$#$'.$fetch['corporateorder'].'$#$'.$fetch['companyclosed'].'$#$'.$fetch['isdealer'].'$#$'.$fetch['displayinwebsite'].'$#$'.$fetch['promotionalsms'].'$#$'.$fetch['promotionalemail'];
			if($fetch['fax'] == '')
				$faxtype = 'Not Avaliable';
			else
				$faxtype = $fetch['fax'];
			if($fetch['stdcode'] == '')
				$stdcodetype = 'Not Avaliable';
			else
				$stdcodetype = $fetch['stdcode'];
			if($fetch['website'] == '')
				$websitetype = 'Not Avaliable';
			else
				$websitetype = $fetch['stdcode'];
			if($fetch['customertype'] == '')
				$customertype = 'Not Avaliable';
			else
				$customertype = $fetch['customertype'];
			if($fetch['businesstype'] == '')
				$businesstype = 'Not Avaliable';
			else
				$businesstype = $fetch['businesstype'];
			if($fetch['address'] == '')
				$addresstype = 'Not Avaliable';
			else
				$addresstype = $fetch['address'];
			echo($fetch['slno'].'^'.$customerid.'^'.$fetch['businessname'].'^'.$addresstype.'^'.$fetch['place'].'^'.$fetch['districtname'].'^'.$fetch['state'].'^'.$fetch['pincode'].'^'.$fetch['regionname'].'^'.$stdcodetype.'^'.$websitetype.'^'.$businesstype.'^'.$customertype.'^'.$fetch['dealername'].'^'.$fetch['disablelogin'].'^'.changedateformatwithtime($fetch['createddate']).'^'.$fetch['corporateorder'].'^'.$faxtype.'^'.$fetch['activecustomer'].'^'.$fetch['branchname'].'^'.$fetch['companyclosed'].'^'.$fetch['isdealer'].'^'.$fetch['displayinwebsite'].'^'.$fetch['promotionalsms'].'^'.$fetch['promotionalemail'].'^'.$contactgrid.'^'.$fetch['statecode'].'^'.$fetch['districtcode'].'^'.$fetch['regioncode'].'^'.$fetch['branchcode'].'^'.$fetch['typecode'].'^'.$fetch['categorycode'].'^'.$fetch['dealerid'].'^'.$checkboxval);
			
		}
	}
	break;
	case 'customergridtoform':
	{
		$cusid = $_POST['cusid'];
		$query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode,inv_mas_dealer.telecaller,inv_mas_dealer.branch,inv_mas_dealer.region
		from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
		 where inv_mas_dealer.slno = '".$userid."';";
		$fetch = runmysqlqueryfetch($query);
		$relyonexecutive = $fetch['relyonexecutive'];
		$district = $fetch['district'];
		$state = $fetch['statecode'];
		$branch = $fetch['branch'];
		$region = $fetch['region'];
		$telecaller = $fetch['telecaller'];
		if($telecaller == 'yes')
		{
			$query = "select slno as slno, businessname as businessname from inv_mas_customer where branch in( select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."') order by businessname;";
			$result = runmysqlquery($query);
			$dealerpiece = " AND inv_mas_customer.branch in (select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."')";
			if(mysqli_num_rows($result) == 0)
			{
				$dealerpiece = " AND inv_mas_customer.branch = '".$branch."' ";
			}
		}
		else
		{
			if($relyonexecutive == 'no')
			{
				$dealerpiece = " AND inv_mas_customer.currentdealer = '".$userid."'";
			}
			else
			{
				if(($region == '1') || ($region == '3'))
				{
					$dealerpiece = " AND (inv_mas_customer.region = '1'  or inv_mas_customer.region = '3') ";
				}
				else
				{
					$query = "select slno as slno, businessname as businessname from inv_mas_customer where branch in( select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."') order by businessname;";
					$result = runmysqlquery($query);
					if(mysqli_num_rows($result) == 0)
					{
						$dealerpiece = " AND inv_mas_customer.branch = '".$branch."' ";
					}
					else
						$dealerpiece = " AND inv_mas_customer.branch in (select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."')";
				}
			}
		}
		$query = "select distinct inv_mas_customer.slno,inv_mas_customer.businessname from inv_mas_customer  where inv_mas_customer.slno = '".$cusid."' ".$dealerpiece." order  by inv_mas_customer.businessname;";
		$result = runmysqlquery($query);
		$grid = '';
		$count = 1;
		while($fetch = mysqli_fetch_array($result))
		{
			if($count > 1)
				$grid .='^*^';
			$grid .= $fetch['businessname'].'^'.$fetch['slno'];
			$count++;
		}
		echo($grid);
	}
	break;
	case 'processupdate':
	{
		$fieldname = $_POST['fieldname'];
		$editvalue = $_POST['editvalue'];
		$lastslno = $_POST['lastslno'];
		$updatepiece = $fieldname." = '".$editvalue."'";
		$createddate = datetimelocal('d-m-Y').' '.datetimelocal('H:i:s');
		switch($fieldname)
		{
			case 'businessname':
			{
				$selectedfields = ("inv_customerreqpending.businessname as resultvalue");
				$joinlink = "";
				$businessname = $editvalue;
				$countquery = "SELECT COUNT(*) as count FROM inv_customerreqpending WHERE customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
				$countfetch = runmysqlqueryfetch($countquery);
				if($countfetch['count'] == 0)
				{
					$query = "SELECT inv_mas_customer.address as address, inv_mas_customer.place as place, inv_mas_customer.district as district, inv_mas_customer.pincode as pincode, inv_mas_customer.fax as fax, inv_mas_customer.stdcode as stdcode, inv_mas_customer.website as website, inv_mas_customer.category as category, inv_mas_customer.type as type ,inv_mas_customer.promotionalsms as promotionalsms,inv_mas_customer.promotionalemail as promotionalemail FROM inv_mas_customer where inv_mas_customer.slno = '".$lastslno."';";
					$fetchval = runmysqlqueryfetch($query);
					$address = $fetchval['address'];
					$place = $fetchval['place'];
					$pincode = $fetchval['pincode'];
					$district = $fetchval['district'];
					$category = $fetchval['category'];
					$type = $fetchval['type'];
					$fax = $fetchval['fax'];
					$stdcode = $fetchval['stdcode'];
					$website = $fetchval['website'];
					$promotionalemail = $fetchval['promotionalemail'];
					$promotionalsms = $fetchval['promotionalsms'];
				}
				else
				{
					$query = "SELECT inv_customerreqpending.address as address, inv_customerreqpending.place as place, inv_customerreqpending.district as district, inv_customerreqpending.pincode as pincode, inv_customerreqpending.fax as fax, inv_customerreqpending.stdcode as stdcode, inv_customerreqpending.website as website, inv_customerreqpending.category as category, inv_customerreqpending.type as type ,inv_customerreqpending.promotionalsms as promotionalsms,inv_customerreqpending.promotionalemail as promotionalemail FROM inv_customerreqpending where inv_customerreqpending.customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
					$fetchval = runmysqlqueryfetch($query);
					$address = $fetchval['address'];
					$place = $fetchval['place'];
					$pincode = $fetchval['pincode'];
					$district = $fetchval['district'];
					$category = $fetchval['category'];
					$type = $fetchval['type'];
					$fax = $fetchval['fax'];
					$stdcode = $fetchval['stdcode'];
					$website = $fetchval['website'];
					$promotionalemail = $fetchval['promotionalemail'];
					$promotionalsms = $fetchval['promotionalsms'];
				}
			}
			break;
			case 'address':
			{
				$selectedfields = ("inv_customerreqpending.address as resultvalue");
				$joinlink = "";
				$address = $editvalue;
				$countquery = "SELECT COUNT(*) as count FROM inv_customerreqpending WHERE customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
				$countfetch = runmysqlqueryfetch($countquery);
				if($countfetch['count'] <> 0)
				{
					$query = "SELECT inv_customerreqpending.businessname as businessname, inv_customerreqpending.place as place, inv_customerreqpending.district as district, inv_customerreqpending.pincode as pincode, inv_customerreqpending.fax as fax, inv_customerreqpending.stdcode as stdcode, inv_customerreqpending.website as website, inv_customerreqpending.category as category, inv_customerreqpending.type as type ,inv_customerreqpending.promotionalsms as promotionalsms,inv_customerreqpending.promotionalemail as promotionalemail FROM inv_customerreqpending where inv_customerreqpending.customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
					$fetchval = runmysqlqueryfetch($query);
					$businessname = $fetchval['businessname'];
					$place = $fetchval['place'];
					$pincode = $fetchval['pincode'];
					$district = $fetchval['district'];
					$category = $fetchval['category'];
					$type = $fetchval['type'];
					$fax = $fetchval['fax'];
					$stdcode = $fetchval['stdcode'];
					$website = $fetchval['website'];
					$promotionalemail = $fetchval['promotionalemail'];
					$promotionalsms = $fetchval['promotionalsms'];
				}
				else
				{
					$query = "SELECT inv_mas_customer.businessname as businessname, inv_mas_customer.place as place, inv_mas_customer.district as district, inv_mas_customer.pincode as pincode, inv_mas_customer.fax as fax, inv_mas_customer.stdcode as stdcode, inv_mas_customer.website as website, inv_mas_customer.category as category, inv_mas_customer.type as type ,inv_mas_customer.promotionalsms as promotionalsms,inv_mas_customer.promotionalemail as promotionalemail FROM inv_mas_customer where inv_mas_customer.slno = '".$lastslno."';";
					$fetchval = runmysqlqueryfetch($query);
					$businessname = $fetchval['businessname'];
					$place = $fetchval['place'];
					$pincode = $fetchval['pincode'];
					$district = $fetchval['district'];
					$category = $fetchval['category'];
					$type = $fetchval['type'];
					$fax = $fetchval['fax'];
					$stdcode = $fetchval['stdcode'];
					$website = $fetchval['website'];
					$promotionalemail = $fetchval['promotionalemail'];
					$promotionalsms = $fetchval['promotionalsms'];
				}
			}
			break;
			case 'place':
			{
				$selectedfields = ("inv_customerreqpending.place as resultvalue");
				$joinlink = "";
				$place = $editvalue;
				$countquery = "SELECT COUNT(*) as count FROM inv_customerreqpending WHERE customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
				$countfetch = runmysqlqueryfetch($countquery);
				if($countfetch['count'] == 0)
				{
					$query = "SELECT inv_mas_customer.businessname as businessname, inv_mas_customer.address as address, inv_mas_customer.district as district, inv_mas_customer.pincode as pincode, inv_mas_customer.fax as fax, inv_mas_customer.stdcode as stdcode, inv_mas_customer.website as website, inv_mas_customer.category as category, inv_mas_customer.type as type ,inv_mas_customer.promotionalsms as promotionalsms,inv_mas_customer.promotionalemail as promotionalemail FROM inv_mas_customer where inv_mas_customer.slno = '".$lastslno."';";
					$fetchval = runmysqlqueryfetch($query);
					$businessname = $fetchval['businessname'];
					$address = $fetchval['address'];
					$pincode = $fetchval['pincode'];
					$district = $fetchval['district'];
					$category = $fetchval['category'];
					$type = $fetchval['type'];
					$fax = $fetchval['fax'];
					$stdcode = $fetchval['stdcode'];
					$website = $fetchval['website'];
					$promotionalemail = $fetchval['promotionalemail'];
					$promotionalsms = $fetchval['promotionalsms'];
				}
				else
				{
					$query = "SELECT inv_customerreqpending.businessname as businessname, inv_customerreqpending.address as address, inv_customerreqpending.district as district, inv_customerreqpending.pincode as pincode, inv_customerreqpending.fax as fax, inv_customerreqpending.stdcode as stdcode, inv_customerreqpending.website as website, inv_customerreqpending.category as category, inv_customerreqpending.type as type ,inv_customerreqpending.promotionalsms as promotionalsms,inv_customerreqpending.promotionalemail as promotionalemail FROM inv_customerreqpending where inv_customerreqpending.customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
					$fetchval = runmysqlqueryfetch($query);
					$businessname = $fetchval['businessname'];
					$address = $fetchval['address'];
					$pincode = $fetchval['pincode'];
					$district = $fetchval['district'];
					$category = $fetchval['category'];
					$type = $fetchval['type'];
					$fax = $fetchval['fax'];
					$stdcode = $fetchval['stdcode'];
					$website = $fetchval['website'];
					$promotionalemail = $fetchval['promotionalemail'];
					$promotionalsms = $fetchval['promotionalsms'];
				}
			}
			break;
			case 'district':
			{
				$selectedfields = ("inv_mas_district.districtname as districtname,inv_mas_state.statename as statename");
				$joinlink = "LEFT JOIN inv_mas_district on inv_mas_district.districtcode = inv_customerreqpending.district LEFT JOIN inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode";
				$district = $editvalue;
				$countquery = "SELECT COUNT(*) as count FROM inv_customerreqpending WHERE customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
				$countfetch = runmysqlqueryfetch($countquery);
				if($countfetch['count'] == 0)
				{
					$query = "SELECT inv_mas_customer.businessname as businessname, inv_mas_customer.address as address, inv_mas_customer.place as place, inv_mas_customer.pincode as pincode, inv_mas_customer.fax as fax, inv_mas_customer.stdcode as stdcode, inv_mas_customer.website as website, inv_mas_customer.category as category, inv_mas_customer.type as type ,inv_mas_customer.promotionalsms as promotionalsms,inv_mas_customer.promotionalemail as promotionalemail FROM inv_mas_customer where inv_mas_customer.slno = '".$lastslno."';";
					$fetchval = runmysqlqueryfetch($query);
					$businessname = $fetchval['businessname'];
					$address = $fetchval['address'];
					$pincode = $fetchval['pincode'];
					$place = $fetchval['place'];
					$category = $fetchval['category'];
					$type = $fetchval['type'];
					$fax = $fetchval['fax'];
					$stdcode = $fetchval['stdcode'];
					$website = $fetchval['website'];
					$promotionalemail = $fetchval['promotionalemail'];
					$promotionalsms = $fetchval['promotionalsms'];
				}
				else
				{
					$query = "SELECT inv_customerreqpending.businessname as businessname, inv_customerreqpending.address as address, inv_customerreqpending.place as place, inv_customerreqpending.pincode as pincode, inv_customerreqpending.fax as fax, inv_customerreqpending.stdcode as stdcode, inv_customerreqpending.website as website, inv_customerreqpending.category as category, inv_customerreqpending.type as type ,inv_customerreqpending.promotionalsms as promotionalsms,inv_customerreqpending.promotionalemail as promotionalemail FROM inv_customerreqpending where inv_customerreqpending.customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
					$fetchval = runmysqlqueryfetch($query);
					$businessname = $fetchval['businessname'];
					$address = $fetchval['address'];
					$pincode = $fetchval['pincode'];
					$place = $fetchval['place'];
					$category = $fetchval['category'];
					$type = $fetchval['type'];
					$fax = $fetchval['fax'];
					$stdcode = $fetchval['stdcode'];
					$website = $fetchval['website'];
					$promotionalemail = $fetchval['promotionalemail'];
					$promotionalsms = $fetchval['promotionalsms'];
				}
			}
			break;
			case 'stdcode':
			{
				$selectedfields = ("inv_customerreqpending.stdcode as resultvalue");
				$joinlink = "";
				$stdcode = $editvalue;
				$countquery = "SELECT COUNT(*) as count FROM inv_customerreqpending WHERE customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
				$countfetch = runmysqlqueryfetch($countquery);
				if($countfetch['count'] == 0)
				{
					$query = "SELECT inv_mas_customer.businessname as businessname, inv_mas_customer.address as address, inv_mas_customer.place as place, inv_mas_customer.pincode as pincode, inv_mas_customer.fax as fax, inv_mas_customer.district as district, inv_mas_customer.website as website, inv_mas_customer.category as category, inv_mas_customer.type as type ,inv_mas_customer.promotionalsms as promotionalsms,inv_mas_customer.promotionalemail as promotionalemail FROM inv_mas_customer where inv_mas_customer.slno = '".$lastslno."';";
					$fetchval = runmysqlqueryfetch($query);
					$businessname = $fetchval['businessname'];
					$address = $fetchval['address'];
					$pincode = $fetchval['pincode'];
					$place = $fetchval['place'];
					$category = $fetchval['category'];
					$type = $fetchval['type'];
					$fax = $fetchval['fax'];
					$district = $fetchval['district'];
					$website = $fetchval['website'];
					$promotionalemail = $fetchval['promotionalemail'];
					$promotionalsms = $fetchval['promotionalsms'];
				}
				else
				{
					$query = "SELECT inv_customerreqpending.businessname as businessname, inv_customerreqpending.address as address, inv_customerreqpending.place as place, inv_customerreqpending.pincode as pincode, inv_customerreqpending.fax as fax, inv_customerreqpending.district as district, inv_customerreqpending.website as website, inv_customerreqpending.category as category, inv_customerreqpending.type as type ,inv_customerreqpending.promotionalsms as promotionalsms,inv_customerreqpending.promotionalemail as promotionalemail FROM inv_customerreqpending where inv_customerreqpending.customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
					$fetchval = runmysqlqueryfetch($query);
					$businessname = $fetchval['businessname'];
					$address = $fetchval['address'];
					$pincode = $fetchval['pincode'];
					$place = $fetchval['place'];
					$category = $fetchval['category'];
					$type = $fetchval['type'];
					$fax = $fetchval['fax'];
					$district = $fetchval['district'];
					$website = $fetchval['website'];
					$promotionalemail = $fetchval['promotionalemail'];
					$promotionalsms = $fetchval['promotionalsms'];
				}
			}
			break;
			case 'fax':
			{
				$selectedfields = ("inv_customerreqpending.fax as resultvalue");
				$joinlink = "";
				$fax = $editvalue;
				$countquery = "SELECT COUNT(*) as count FROM inv_customerreqpending WHERE customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
				$countfetch = runmysqlqueryfetch($countquery);
				if($countfetch['count'] == 0)
				{
					$query = "SELECT inv_mas_customer.businessname as businessname, inv_mas_customer.address as address, inv_mas_customer.place as place, inv_mas_customer.pincode as pincode, inv_mas_customer.stdcode as stdcode, inv_mas_customer.district as district, inv_mas_customer.website as website, inv_mas_customer.category as category, inv_mas_customer.type as type ,inv_mas_customer.promotionalsms as promotionalsms,inv_mas_customer.promotionalemail as promotionalemail FROM inv_mas_customer where inv_mas_customer.slno = '".$lastslno."';";
					$fetchval = runmysqlqueryfetch($query);
					$businessname = $fetchval['businessname'];
					$address = $fetchval['address'];
					$pincode = $fetchval['pincode'];
					$place = $fetchval['place'];
					$category = $fetchval['category'];
					$type = $fetchval['type'];
					$stdcode = $fetchval['stdcode'];
					$district = $fetchval['district'];
					$website = $fetchval['website'];
					$promotionalemail = $fetchval['promotionalemail'];
					$promotionalsms = $fetchval['promotionalsms'];
				}
				else
				{
					$query = "SELECT inv_customerreqpending.businessname as businessname, inv_customerreqpending.address as address, inv_customerreqpending.place as place, inv_customerreqpending.pincode as pincode, inv_customerreqpending.stdcode as stdcode, inv_customerreqpending.district as district, inv_customerreqpending.website as website, inv_customerreqpending.category as category, inv_customerreqpending.type as type ,inv_customerreqpending.promotionalsms as promotionalsms,inv_customerreqpending.promotionalemail as promotionalemail FROM inv_customerreqpending where inv_customerreqpending.customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
					$fetchval = runmysqlqueryfetch($query);
					$businessname = $fetchval['businessname'];
					$address = $fetchval['address'];
					$pincode = $fetchval['pincode'];
					$place = $fetchval['place'];
					$category = $fetchval['category'];
					$type = $fetchval['type'];
					$stdcode = $fetchval['stdcode'];
					$district = $fetchval['district'];
					$website = $fetchval['website'];
					$promotionalemail = $fetchval['promotionalemail'];
					$promotionalsms = $fetchval['promotionalsms'];
				}
			}
			case 'pincode':
			{
				$selectedfields = ("inv_customerreqpending.pincode as resultvalue");
				$joinlink = "";
				$pincode = $editvalue;
				$countquery = "SELECT COUNT(*) as count FROM inv_customerreqpending WHERE customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
				$countfetch = runmysqlqueryfetch($countquery);
				if($countfetch['count'] == 0)
				{
					$query = "SELECT inv_mas_customer.businessname as businessname, inv_mas_customer.address as address, inv_mas_customer.place as place, inv_mas_customer.fax as fax, inv_mas_customer.stdcode as stdcode, inv_mas_customer.district as district, inv_mas_customer.website as website, inv_mas_customer.category as category, inv_mas_customer.type as type ,inv_mas_customer.promotionalsms as promotionalsms,inv_mas_customer.promotionalemail as promotionalemail FROM inv_mas_customer where inv_mas_customer.slno = '".$lastslno."';";
					$fetchval = runmysqlqueryfetch($query);
					$businessname = $fetchval['businessname'];
					$address = $fetchval['address'];
					$fax = $fetchval['fax'];
					$place = $fetchval['place'];
					$category = $fetchval['category'];
					$type = $fetchval['type'];
					$stdcode = $fetchval['stdcode'];
					$district = $fetchval['district'];
					$website = $fetchval['website'];
					$promotionalemail = $fetchval['promotionalemail'];
					$promotionalsms = $fetchval['promotionalsms'];
				}
				else
				{
					$query = "SELECT inv_customerreqpending.businessname as businessname, inv_customerreqpending.address as address, inv_customerreqpending.place as place, inv_customerreqpending.fax as fax, inv_customerreqpending.stdcode as stdcode, inv_customerreqpending.district as district, inv_customerreqpending.website as website, inv_customerreqpending.category as category, inv_customerreqpending.type as type ,inv_customerreqpending.promotionalsms as promotionalsms,inv_customerreqpending.promotionalemail as promotionalemail FROM inv_customerreqpending where inv_customerreqpending.customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
					$fetchval = runmysqlqueryfetch($query);
					$businessname = $fetchval['businessname'];
					$address = $fetchval['address'];
					$fax = $fetchval['fax'];
					$place = $fetchval['place'];
					$category = $fetchval['category'];
					$type = $fetchval['type'];
					$stdcode = $fetchval['stdcode'];
					$district = $fetchval['district'];
					$website = $fetchval['website'];
					$promotionalemail = $fetchval['promotionalemail'];
					$promotionalsms = $fetchval['promotionalsms'];
				}
			}
			break;
			case 'website':
			{
				$selectedfields = ("inv_customerreqpending.website as resultvalue");
				$joinlink = "";
				$website = $editvalue;
				$countquery = "SELECT COUNT(*) as count FROM inv_customerreqpending WHERE customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
				$countfetch = runmysqlqueryfetch($countquery);
				if($countfetch['count'] == 0)
				{
					$query = "SELECT inv_mas_customer.businessname as businessname, inv_mas_customer.address as address, inv_mas_customer.place as place, inv_mas_customer.fax as fax, inv_mas_customer.stdcode as stdcode, inv_mas_customer.district as district, inv_mas_customer.pincode as pincode, inv_mas_customer.category as category, inv_mas_customer.type as type ,inv_mas_customer.promotionalsms as promotionalsms,inv_mas_customer.promotionalemail as promotionalemail FROM inv_mas_customer where inv_mas_customer.slno = '".$lastslno."';";
					$fetchval = runmysqlqueryfetch($query);
					$businessname = $fetchval['businessname'];
					$address = $fetchval['address'];
					$fax = $fetchval['fax'];
					$place = $fetchval['place'];
					$category = $fetchval['category'];
					$type = $fetchval['type'];
					$stdcode = $fetchval['stdcode'];
					$district = $fetchval['district'];
					$pincode = $fetchval['pincode'];
					$promotionalemail = $fetchval['promotionalemail'];
					$promotionalsms = $fetchval['promotionalsms'];
				}
				else
				{
					$query = "SELECT inv_customerreqpending.businessname as businessname, inv_customerreqpending.address as address, inv_customerreqpending.place as place, inv_customerreqpending.fax as fax, inv_customerreqpending.stdcode as stdcode, inv_customerreqpending.district as district, inv_customerreqpending.pincode as pincode, inv_customerreqpending.category as category, inv_customerreqpending.type as type ,inv_customerreqpending.promotionalsms as promotionalsms,inv_customerreqpending.promotionalemail as promotionalemail FROM inv_customerreqpending where inv_customerreqpending.customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
					$fetchval = runmysqlqueryfetch($query);
					$businessname = $fetchval['businessname'];
					$address = $fetchval['address'];
					$fax = $fetchval['fax'];
					$place = $fetchval['place'];
					$category = $fetchval['category'];
					$type = $fetchval['type'];
					$stdcode = $fetchval['stdcode'];
					$district = $fetchval['district'];
					$pincode = $fetchval['pincode'];
					$promotionalemail = $fetchval['promotionalemail'];
					$promotionalsms = $fetchval['promotionalsms'];
				}
			}
			break;
			case 'type':
			{
				$selectedfields = ("inv_mas_customertype.customertype as resultvalue");
				$joinlink = ("LEFT JOIN inv_mas_customertype on inv_mas_customertype.slno = inv_customerreqpending.type ");
				$type = $editvalue;
				$countquery = "SELECT COUNT(*) as count FROM inv_customerreqpending WHERE customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
				$countfetch = runmysqlqueryfetch($countquery);
				if($countfetch['count'] == 0)
				{
					$query = "SELECT inv_mas_customer.businessname as businessname, inv_mas_customer.address as address, inv_mas_customer.place as place, inv_mas_customer.fax as fax, inv_mas_customer.stdcode as stdcode, inv_mas_customer.district as district, inv_mas_customer.pincode as pincode, inv_mas_customer.category as category, inv_mas_customer.website as website ,inv_mas_customer.promotionalsms as promotionalsms,inv_mas_customer.promotionalemail as promotionalemail FROM inv_mas_customer where inv_mas_customer.slno = '".$lastslno."';";
					$fetchval = runmysqlqueryfetch($query);
					$businessname = $fetchval['businessname'];
					$address = $fetchval['address'];
					$fax = $fetchval['fax'];
					$place = $fetchval['place'];
					$category = $fetchval['category'];
					$website = $fetchval['website'];
					$stdcode = $fetchval['stdcode'];
					$district = $fetchval['district'];
					$pincode = $fetchval['pincode'];
					$promotionalemail = $fetchval['promotionalemail'];
					$promotionalsms = $fetchval['promotionalsms'];
				}
				else
				{
					$query = "SELECT inv_customerreqpending.businessname as businessname, inv_customerreqpending.address as address, inv_customerreqpending.place as place, inv_customerreqpending.fax as fax, inv_customerreqpending.stdcode as stdcode, inv_customerreqpending.district as district, inv_customerreqpending.pincode as pincode, inv_customerreqpending.category as category, inv_customerreqpending.website as website ,inv_customerreqpending.promotionalsms as promotionalsms,inv_customerreqpending.promotionalemail as promotionalemail FROM inv_customerreqpending where inv_customerreqpending.customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
					$fetchval = runmysqlqueryfetch($query);
					$businessname = $fetchval['businessname'];
					$address = $fetchval['address'];
					$fax = $fetchval['fax'];
					$place = $fetchval['place'];
					$category = $fetchval['category'];
					$website = $fetchval['website'];
					$stdcode = $fetchval['stdcode'];
					$district = $fetchval['district'];
					$pincode = $fetchval['pincode'];
					$promotionalemail = $fetchval['promotionalemail'];
					$promotionalsms = $fetchval['promotionalsms'];
				}
			}
			break;
			case 'category':
			{
				$selectedfields = ("inv_mas_customercategory.businesstype as resultvalue");
				$joinlink = ("LEFT JOIN inv_mas_customercategory on inv_mas_customercategory.slno = inv_customerreqpending.category ");
				$category = $editvalue;
				$countquery = "SELECT COUNT(*) as count FROM inv_customerreqpending WHERE customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
				$countfetch = runmysqlqueryfetch($countquery);
				if($countfetch['count'] == 0)
				{
					$query = "SELECT inv_mas_customer.businessname as businessname, inv_mas_customer.address as address, inv_mas_customer.place as place, inv_mas_customer.fax as fax, inv_mas_customer.stdcode as stdcode, inv_mas_customer.district as district, inv_mas_customer.pincode as pincode, inv_mas_customer.type as type, inv_mas_customer.website as website ,inv_mas_customer.promotionalsms as promotionalsms,inv_mas_customer.promotionalemail as promotionalemail FROM inv_mas_customer where inv_mas_customer.slno = '".$lastslno."';";
					$fetchval = runmysqlqueryfetch($query);
					$businessname = $fetchval['businessname'];
					$address = $fetchval['address'];
					$fax = $fetchval['fax'];
					$place = $fetchval['place'];
					$type = $fetchval['type'];
					$website = $fetchval['website'];
					$stdcode = $fetchval['stdcode'];
					$district = $fetchval['district'];
					$pincode = $fetchval['pincode'];
					$promotionalemail = $fetchval['promotionalemail'];
					$promotionalsms = $fetchval['promotionalsms'];
				}
				else
				{
					$query = "SELECT inv_customerreqpending.businessname as businessname, inv_customerreqpending.address as address, inv_customerreqpending.place as place, inv_customerreqpending.fax as fax, inv_customerreqpending.stdcode as stdcode, inv_customerreqpending.district as district, inv_customerreqpending.pincode as pincode, inv_customerreqpending.type as type, inv_customerreqpending.website as website ,inv_customerreqpending.promotionalsms as promotionalsms,inv_customerreqpending.promotionalemail as promotionalemail FROM inv_customerreqpending where inv_customerreqpending.customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
					$fetchval = runmysqlqueryfetch($query);
					$businessname = $fetchval['businessname'];
					$address = $fetchval['address'];
					$fax = $fetchval['fax'];
					$place = $fetchval['place'];
					$type = $fetchval['type'];
					$website = $fetchval['website'];
					$stdcode = $fetchval['stdcode'];
					$district = $fetchval['district'];
					$pincode = $fetchval['pincode'];
					$promotionalemail = $fetchval['promotionalemail'];
					$promotionalsms = $fetchval['promotionalsms'];
				}
			}
			break;
		}
			
		
			$countquery = "SELECT COUNT(*) as count FROM inv_customerreqpending WHERE customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
			$countfetch = runmysqlqueryfetch($countquery);
			if($countfetch['count'] <> 0)
			{
				$countquery1 = "SELECT COUNT(*) as count FROM inv_contactreqpending WHERE customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
				$countfetch1 = runmysqlqueryfetch($countquery1);
				if($countfetch1['count'] <> 0)
				{
					$query1 ="SELECT slno,customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactreqpending where customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending' ";
					$resultfetch = runmysqlquery($query1);
					$valuecount = 0;
					while($fetchres = mysqli_fetch_array($resultfetch))
					{
						if($valuecount > 0)
							$contactarray .= '****';
						$selectiontype = $fetchres['selectiontype'];
						$contactperson = $fetchres['contactperson'];
						$phone = $fetchres['phone'];
						$cell = $fetchres['cell'];
						$emailid = $fetchres['emailid'];
						$slno = $fetchres['slno'];
						
						$contactarray .= $selectiontype.'#'.$contactperson.'#'.$phone.'#'.$cell.'#'.$emailid.'#'.$slno;
						$valuecount++;
					}
					$contactsplit = explode('****',$contactarray);
					$contactcount = count($contactsplit);
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
					$query1 = "UPDATE inv_contactreqpending SET customerstatus = 'oldrequest' WHERE customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
					$result = runmysqlquery($query1);
				}
				else
				{
					$query1 ="SELECT slno,customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactdetails where customerid = '".$lastslno."' ";
					$resultfetch = runmysqlquery($query1);
					$valuecount = 0;
					while($fetchres = mysqli_fetch_array($resultfetch))
					{
						if($valuecount > 0)
							$contactarray .= '****';
						$selectiontype = $fetchres['selectiontype'];
						$contactperson = $fetchres['contactperson'];
						$phone = $fetchres['phone'];
						$cell = $fetchres['cell'];
						$emailid = $fetchres['emailid'];
						$slno = $fetchres['slno'];
						
						$contactarray .= $selectiontype.'#'.$contactperson.'#'.$phone.'#'.$cell.'#'.$emailid.'#'.$slno;
						$valuecount++;
					}
					$contactsplit = explode('****',$contactarray);
					$contactcount = count($contactsplit);
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
				$query1 = "UPDATE inv_customerreqpending SET customerstatus = 'oldrequest' WHERE customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
				$result = runmysqlquery($query1);
				
				
				$query = runmysqlqueryfetch("SELECT (MAX(slno) + 1) AS slno FROM inv_customerreqpending");
				$pendingtableslno = $query['slno'];
				
				$query = "Insert into inv_customerreqpending(slno,customerid,businessname,address,place,district,pincode,stdcode,website,type,category,createddate, customerstatus,requestfrom,requestby,fax,promotionalsms,promotionalemail,companyclosed) values('".$pendingtableslno."','".$lastslno."','".trim($businessname)."','".$address."','".$place."','".$district."','".$pincode."','".$stdcode."','".$website."','".$type."','".$category."','".changedateformatwithtime($createddate)."','pending','dealer_module','".$userid."','".$fax."','".$promotionalsms."','".$promotionalemail."','no')";
				$result1 = runmysqlquery($query);
			
				for($j=0;$j<count($contactressplit);$j++)
				{
					$selectiontype = $contactressplit[$j][0];
					$contactperson = $contactressplit[$j][1];
					$phone = $contactressplit[$j][2];
					$cell = $contactressplit[$j][3];
					$emailid = $contactressplit[$j][4];
					//Added Space after comma is not avaliable in phone, cell and emailid fields
					$phonespace = str_replace(", ", ",",$phone);
					$phonevalue = str_replace(',',', ',$phonespace);
					
					$cellspace = str_replace(", ", ",",$cell);
					$cellvalue = str_replace(',',', ',$cellspace);
					
					$emailidspace = str_replace(", ", ",",$emailid);
					$emailidvalue = str_replace(',',', ',$emailidspace);
					
					$query2 = "Insert into inv_contactreqpending(refslno,customerid,selectiontype,contactperson,phone,cell,emailid,customerstatus,requestfrom,editedtype) values  ('".$pendingtableslno."','".$lastslno."','".$selectiontype."','".$contactperson."','".$phonevalue."','".$cellvalue."','".$emailidvalue."','pending','dealer_module','edit_type');";
					$result = runmysqlquery($query2);
				}
				$updateddata = $lastslno."|^|".$businessname."|^|".$contactperson."|^|".$address."|^|".$place."|^|".$district."|^|".$pincode."|^|".$stdcode."|^|".$phone."|^|".$cell."|^|".$emailid."|^|".$website."|^|".$type."|^|".$category."|^|".$createddate."|^|".$fax."|^|".$userid."|^|".$companyclosed."|^|".$contactarray;
				$query2 = "Insert into inv_logs_pendingrequest(userid,type,updateddata,updateddate,updatedtime,system) values('".$userid."','dealer_module','".$updateddata."','".datetimelocal('Y-m-d')."','".datetimelocal('H:i:s')."','".$_SERVER['REMOTE_ADDR']."')";
				$result2 = runmysqlquery($query2);
				
			}
			else
			{
				$query1 ="SELECT slno,customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactdetails where customerid = '".$lastslno."';";
				$resultfetch = runmysqlquery($query1);
				$valuecount = 0;
				while($fetchres = mysqli_fetch_array($resultfetch))
				{
					if($valuecount > 0)
						$contactarray .= '****';
					$selectiontype = $fetchres['selectiontype'];
					$contactperson = $fetchres['contactperson'];
					$phone = $fetchres['phone'];
					$cell = $fetchres['cell'];
					$emailid = $fetchres['emailid'];
					$slno = $fetchres['slno'];
					
					$contactarray .= $selectiontype.'#'.$contactperson.'#'.$phone.'#'.$cell.'#'.$emailid.'#'.$slno;
					$valuecount++;
				}
				$contactsplit = explode('****',$contactarray);
				$contactcount = count($contactsplit);
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
				$query = runmysqlqueryfetch("SELECT (MAX(slno) + 1) AS slno FROM inv_customerreqpending");
				$pendingtableslno = $query['slno'];
					
				$query = "Insert into inv_customerreqpending(slno,customerid,businessname,address,place,district,pincode,stdcode,website,type,category,createddate,customerstatus,requestfrom,requestby,fax,companyclosed,promotionalsms,promotionalemail) values('".$pendingtableslno."','".$lastslno."','".trim($businessname)."','".$address."','".$place."','".$district."','".$pincode."','".$stdcode."','".$website."','".$type."','".$category."','".changedateformatwithtime($createddate)."','pending','dealer_module','".$userid."','".$fax."','no','".$promotionalsms."','".$promotionalemail."')";
				$result = runmysqlquery($query);
				
				for($j=0;$j<count($contactressplit);$j++)
				{
					$selectiontype = $contactressplit[$j][0];
					$contactperson = $contactressplit[$j][1];
					$phone = $contactressplit[$j][2];
					$cell = $contactressplit[$j][3];
					$emailid = $contactressplit[$j][4];
					//Added Space after comma is not avaliable in phone, cell and emailid fields
					$phonespace = str_replace(", ", ",",$phone);
					$phonevalue = str_replace(',',', ',$phonespace);
					
					$cellspace = str_replace(", ", ",",$cell);
					$cellvalue = str_replace(',',', ',$cellspace);
					
					$emailidspace = str_replace(", ", ",",$emailid);
					$emailidvalue = str_replace(',',', ',$emailidspace);
					
					$query2 = "Insert into inv_contactreqpending(refslno,customerid,selectiontype,contactperson,phone,cell,emailid,customerstatus,requestfrom,editedtype) values  ('".$slno."','".$lastslno."','".$selectiontype."','".$contactperson."','".$phonevalue."','".$cellvalue."','".$emailidvalue."','pending','dealer_module','edit_type');";
					$result = runmysqlquery($query2);
				}
					
				$updateddata = $lastslno."|^|".$businessname."|^|".$address."|^|".$place."|^|".$district."|^|".$pincode."|^|".$stdcode."|^|".$website."|^|".$type."|^|".$category."|^|".$createddate."|^|".$fax."|^|".$userid."|^|".$companyclosed."|^|".$contactarray;
				$query2 = "Insert into inv_logs_pendingrequest(userid,type,updateddata,updateddate,updatedtime,system) values('".$userid."','dealer_module','".$updateddata."','".datetimelocal('Y-m-d')."','".datetimelocal('H:i:s')."','".$_SERVER['REMOTE_ADDR']."')";
				$result2 = runmysqlquery($query2);
			
			}
			if($fieldname == 'district')
			{
				$query11 = "Select ".$selectedfields." from  inv_customerreqpending ".$joinlink." where inv_customerreqpending.customerid = '".$lastslno."' and inv_customerreqpending.slno = '".$pendingtableslno."' AND inv_customerreqpending.requestfrom = 'dealer_module' ;";
				$fetch11 = runmysqlqueryfetch($query11);
				$valuedetails = $fetch11['districtname'].'^'. $fetch11['statename'];
			}
			else
			{
				$query11 = "Select ".$selectedfields." from  inv_customerreqpending ".$joinlink." where inv_customerreqpending.customerid = '".$lastslno."' and inv_customerreqpending.slno = '".$pendingtableslno."' AND inv_customerreqpending.requestfrom = 'dealer_module' ";
				$fetch11 = runmysqlqueryfetch($query11);
				$valuedetails = $fetch11['resultvalue'];
			}
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','146','".date('Y-m-d').' '.date('H:i:s')."')";
			$eventresult = runmysqlquery($eventquery);
			echo('1^'.'Record Edited Successfully.'.'^'.$valuedetails);
		}
		break;
	
	case 'updatecontactdetails':
	{
		$contactslno = $_POST['contactslno'];
		$lastslno = $_POST['lastslno'];
		$selectiontype1 = $_POST['selectiontype'];
		$contactperson1 = $_POST['name'];
		$phone1 = $_POST['phone'];
		$cell1 = $_POST['cell'];
		$emailid1 = $_POST['emailid'];
		$createddate = datetimelocal('d-m-Y').' '.datetimelocal('H:i:s');
		$countquery1 = "SELECT COUNT(*) as count FROM inv_customerreqpending WHERE customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
		$countfetch = runmysqlqueryfetch($countquery1);
		if($countfetch['count'] == 0)
		{
			$query = "SELECT inv_mas_customer.businessname as businessname, inv_mas_customer.address as address, inv_mas_customer.place as place, inv_mas_customer.fax as fax, inv_mas_customer.stdcode as stdcode, inv_mas_customer.district as district, inv_mas_customer.pincode as pincode, inv_mas_customer.type as type, inv_mas_customer.website as website , inv_mas_customer.category as category,inv_mas_customer.promotionalsms as promotionalsms,inv_mas_customer.promotionalemail as promotionalemail FROM inv_mas_customer where inv_mas_customer.slno = '".$lastslno."';";
			$fetchval = runmysqlqueryfetch($query);
			$businessname = $fetchval['businessname'];
			$address = $fetchval['address'];
			$fax = $fetchval['fax'];
			$place = $fetchval['place'];
			$type = $fetchval['type'];
			$website = $fetchval['website'];
			$stdcode = $fetchval['stdcode'];
			$district = $fetchval['district'];
			$pincode = $fetchval['pincode'];
			$promotionalemail = $fetchval['promotionalemail'];
			$promotionalsms = $fetchval['promotionalsms'];
			$category = $fetchval['category'];
		}
		else
		{
			$query = "SELECT inv_customerreqpending.businessname as businessname, inv_customerreqpending.address as address, inv_customerreqpending.place as place, inv_customerreqpending.fax as fax, inv_customerreqpending.stdcode as stdcode, inv_customerreqpending.district as district, inv_customerreqpending.pincode as pincode, inv_customerreqpending.type as type, inv_customerreqpending.website as website , inv_customerreqpending.category as category,inv_customerreqpending.promotionalsms as promotionalsms,inv_customerreqpending.promotionalemail as promotionalemail FROM inv_customerreqpending where inv_customerreqpending.customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
			$fetchval = runmysqlqueryfetch($query);
			$businessname = $fetchval['businessname'];
			$address = $fetchval['address'];
			$fax = $fetchval['fax'];
			$place = $fetchval['place'];
			$type = $fetchval['type'];
			$website = $fetchval['website'];
			$stdcode = $fetchval['stdcode'];
			$district = $fetchval['district'];
			$pincode = $fetchval['pincode'];
			$promotionalemail = $fetchval['promotionalemail'];
			$promotionalsms = $fetchval['promotionalsms'];
			$category = $fetchval['category'];
		}
		
		
		
		$countquery = "SELECT COUNT(*) as count FROM inv_customerreqpending WHERE customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
		
		$countfetch = runmysqlqueryfetch($countquery);
		if($countfetch['count'] <> 0)
		{
			$countquery1 = "SELECT COUNT(*) as count FROM inv_contactreqpending WHERE customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
			$countfetch1 = runmysqlqueryfetch($countquery1);
			if($countfetch1['count'] <> 0)
			{
				$query1 ="SELECT slno,customerid,contactperson,selectiontype,phone,cell,emailid from inv_contactreqpending where customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending' ";
				$resultfetch = runmysqlquery($query1);
				$valuecount = 0;
				
				while($fetchres = mysqli_fetch_array($resultfetch))
				{
					if($fetchres['slno'] != $contactslno)
					{
						if($valuecount > 0)
							$contactarray .= '****';
						$selectiontype = $fetchres['selectiontype'];
						$contactperson = $fetchres['contactperson'];
						$phone = $fetchres['phone'];
						$cell = $fetchres['cell'];
						$emailid = $fetchres['emailid'];
						$slno = $fetchres['slno'];
						
						$contactarray .= $selectiontype.'#'.$contactperson.'#'.$phone.'#'.$cell.'#'.$emailid.'#'.$slno;
						$valuecount++;
					}
				}
				if(mysqli_num_rows($resultfetch) > 1)
					$added = '****';
				else
					$added = '';
				$contactarray .= $added.$selectiontype1.'#'.$contactperson1.'#'.$phone1.'#'.$cell1.'#'.$emailid1.'#'.$contactslno;
				$contactsplit = explode('****',$contactarray);
				$contactcount = count($contactsplit);
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
				$query1 = "UPDATE inv_contactreqpending SET customerstatus = 'oldrequest' WHERE customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
				$result = runmysqlquery($query1);
			}
			else
			{
				$query1 ="SELECT slno,customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactdetails where customerid = '".$lastslno."';";
				$resultfetch = runmysqlquery($query1);
				$valuecount = 0;
				while($fetchres = mysqli_fetch_array($resultfetch))
				{
					if($valuecount > 0)
						$contactarray .= '****';
					$selectiontype = $fetchres['selectiontype'];
					$contactperson = $fetchres['contactperson'];
					$phone = $fetchres['phone'];
					$cell = $fetchres['cell'];
					$emailid = $fetchres['emailid'];
					$slno = $fetchres['slno'];
					
					$contactarray .= $selectiontype.'#'.$contactperson.'#'.$phone.'#'.$cell.'#'.$emailid.'#'.$slno;
					$valuecount++;
				}
				$contactsplit = explode('****',$contactarray);
				$contactcount = count($contactsplit);
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
			$query1 = "UPDATE inv_customerreqpending SET customerstatus = 'oldrequest' WHERE customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
			$result = runmysqlquery($query1);
			
			
			$query = runmysqlqueryfetch("SELECT (MAX(slno) + 1) AS slno FROM inv_customerreqpending");
			$pendingtableslno = $query['slno'];
			
			$query = "Insert into inv_customerreqpending(slno,customerid,businessname,address,place,district,pincode,stdcode,website,type,category,createddate, customerstatus,requestfrom,requestby,fax,promotionalsms,promotionalemail,companyclosed) values('".$pendingtableslno."','".$lastslno."','".trim($businessname)."','".$address."','".$place."','".$district."','".$pincode."','".$stdcode."','".$website."','".$type."','".$category."','".changedateformatwithtime($createddate)."','pending','dealer_module','".$userid."','".$fax."','".$promotionalsms."','".$promotionalemail."','no')";
			$result1 = runmysqlquery($query);
		
			
			for($j=0;$j<count($contactressplit);$j++)
			{
				$selectiontype = $contactressplit[$j][0];
				$contactperson = $contactressplit[$j][1];
				$phone = $contactressplit[$j][2];
				$cell = $contactressplit[$j][3];
				$emailid = $contactressplit[$j][4];
				//Added Space after comma is not avaliable in phone, cell and emailid fields
				$phonespace = str_replace(", ", ",",$phone);
				$phonevalue = str_replace(',',', ',$phonespace);
				
				$cellspace = str_replace(", ", ",",$cell);
				$cellvalue = str_replace(',',', ',$cellspace);
				
				$emailidspace = str_replace(", ", ",",$emailid);
				$emailidvalue = str_replace(',',', ',$emailidspace);
				
				$query2 = "Insert into inv_contactreqpending(refslno,customerid,selectiontype,contactperson,phone,cell,emailid,customerstatus,requestfrom,editedtype) values  ('".$pendingtableslno."','".$lastslno."','".$selectiontype."','".$contactperson."','".$phonevalue."','".$cellvalue."','".$emailidvalue."','pending','dealer_module','edit_type');";
				$result = runmysqlquery($query2);
			}
			$updateddata = $lastslno."|^|".$businessname."|^|".$contactperson."|^|".$address."|^|".$place."|^|".$district."|^|".$pincode."|^|".$stdcode."|^|".$phone."|^|".$cell."|^|".$emailid."|^|".$website."|^|".$type."|^|".$category."|^|".$createddate."|^|".$fax."|^|".$userid."|^|".$companyclosed."|^|".$contactarray;
			$query2 = "Insert into inv_logs_pendingrequest(userid,type,updateddata,updateddate,updatedtime,system) values('".$userid."','dealer_module','".$updateddata."','".datetimelocal('Y-m-d')."','".datetimelocal('H:i:s')."','".$_SERVER['REMOTE_ADDR']."')";
			$result2 = runmysqlquery($query2);
			
		}
		else
		{
			$query1 ="SELECT slno,customerid,contactperson,selectiontype,phone,cell,emailid from inv_contactdetails where customerid = '".$lastslno."' ";
			$resultfetch = runmysqlquery($query1);
			$valuecount = 0;
			while($fetchres = mysqli_fetch_array($resultfetch))
			{
				if($fetchres['slno'] != $contactslno)
				{
					if($valuecount > 0)
						$contactarray .= '****';
					$selectiontype = $fetchres['selectiontype'];
					$contactperson = $fetchres['contactperson'];
					$phone = $fetchres['phone'];
					$cell = $fetchres['cell'];
					$emailid = $fetchres['emailid'];
					$slno = $fetchres['slno'];
					
					$contactarray .= $selectiontype.'#'.$contactperson.'#'.$phone.'#'.$cell.'#'.$emailid.'#'.$slno;
					$valuecount++;
				}
			}
			if(mysqli_num_rows($resultfetch) > 1)
				$added = '****';
			else
				$added = '';
			$contactarray .= $added.$selectiontype1.'#'.$contactperson1.'#'.$phone1.'#'.$cell1.'#'.$emailid1.'#'.$contactslno;
			$contactsplit = explode('****',$contactarray);
			$contactcount = count($contactsplit);
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
			
			$query = runmysqlqueryfetch("SELECT (MAX(slno) + 1) AS slno FROM inv_customerreqpending");
			$pendingtableslno = $query['slno'];
				
			$query = "Insert into inv_customerreqpending(slno,customerid,businessname,address,place,district,pincode,stdcode,website,type,category,createddate,customerstatus,requestfrom,requestby,fax,companyclosed,promotionalsms,promotionalemail) values('".$pendingtableslno."','".$lastslno."','".trim($businessname)."','".$address."','".$place."','".$district."','".$pincode."','".$stdcode."','".$website."','".$type."','".$category."','".changedateformatwithtime($createddate)."','pending','dealer_module','".$userid."','".$fax."','no','".$promotionalsms."','".$promotionalemail."')";
			$result = runmysqlquery($query);
			
			for($j=0;$j<count($contactressplit);$j++)
			{
				$selectiontype = $contactressplit[$j][0];
				$contactperson = $contactressplit[$j][1];
				$phone = $contactressplit[$j][2];
				$cell = $contactressplit[$j][3];
				$emailid = $contactressplit[$j][4];
				//Added Space after comma is not avaliable in phone, cell and emailid fields
				$phonespace = str_replace(", ", ",",$phone);
				$phonevalue = str_replace(',',', ',$phonespace);
				
				$cellspace = str_replace(", ", ",",$cell);
				$cellvalue = str_replace(',',', ',$cellspace);
				
				$emailidspace = str_replace(", ", ",",$emailid);
				$emailidvalue = str_replace(',',', ',$emailidspace);
				
				$query2 = "Insert into inv_contactreqpending(refslno,customerid,selectiontype,contactperson,phone,cell,emailid,customerstatus,requestfrom,editedtype) values  ('".$pendingtableslno."','".$lastslno."','".$selectiontype."','".$contactperson."','".$phonevalue."','".$cellvalue."','".$emailidvalue."','pending','dealer_module','edit_type');";
				$result = runmysqlquery($query2);
			}
				
			$updateddata = $lastslno."|^|".$businessname."|^|".$address."|^|".$place."|^|".$district."|^|".$pincode."|^|".$stdcode."|^|".$website."|^|".$type."|^|".$category."|^|".$createddate."|^|".$fax."|^|".$userid."|^|".$companyclosed."|^|".$contactarray;
			$query2 = "Insert into inv_logs_pendingrequest(userid,type,updateddata,updateddate,updatedtime,system) values('".$userid."','dealer_module','".$updateddata."','".datetimelocal('Y-m-d')."','".datetimelocal('H:i:s')."','".$_SERVER['REMOTE_ADDR']."')";
			$result2 = runmysqlquery($query2);
		
		}
		
		
		//$query12 = "Select * from  inv_contactreqpending where inv_contactreqpending.slno = '".$contactslno."' and inv_contactreqpending.customerid = '".$lastslno."' AND inv_contactreqpending.requestfrom = 'dealer_module' ;";
		//$fetch12 = runmysqlqueryfetch($query12);
		if($selectiontype1 == 'general')
			$selectionvalue = 'General';
		elseif($selectiontype1 == 'gm/director')
			$selectionvalue = 'GM/Director';
		elseif($selectiontype1 == 'hrhead')
			$selectionvalue = 'HR Head';
		elseif($selectiontype1 == 'ithead/edp')
			$selectionvalue = 'IT-Head/EDP';
		elseif($selectiontype1 == 'softwareuser')
			$selectionvalue = 'Software User';
		elseif($selectiontype1 == 'financehead')
			$selectionvalue = 'Finance Head';
		elseif($selectiontype1 == 'others')
			$selectionvalue = 'Others';
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','146','".date('Y-m-d').' '.date('H:i:s')."')";
		$eventresult = runmysqlquery($eventquery);
		//echo($query12);
		echo('1^'.'Record Edited Successfully.'.'^'.$selectiontype1.'^'.$contactperson1.'^'.$phone1.'^'.$cell1.'^'.$emailid1);
	}
	break;
	case 'updatecheckboxdetails':
	{
		$lastslno = $_POST['lastslno'];
		$companyclosed = $_POST['companyclosed'];
		$promotionalsms = $_POST['promotionalsms'];
		$promotionalemail = $_POST['promotionalemail'];
		$createddate = datetimelocal('d-m-Y').' '.datetimelocal('H:i:s');
		$countquery = "SELECT COUNT(*) as count FROM inv_customerreqpending WHERE customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
		$countfetch = runmysqlqueryfetch($countquery);
		if($countfetch['count'] == 0)
		{
			$query = "SELECT inv_mas_customer.businessname as businessname, inv_mas_customer.address as address, inv_mas_customer.place as place, inv_mas_customer.fax as fax, inv_mas_customer.stdcode as stdcode, inv_mas_customer.district as district, inv_mas_customer.pincode as pincode, inv_mas_customer.type as type, inv_mas_customer.website as website , inv_mas_customer.category as category FROM inv_mas_customer where inv_mas_customer.slno = '".$lastslno."';";
			$fetchval = runmysqlqueryfetch($query);
			$businessname = $fetchval['businessname'];
			$address = $fetchval['address'];
			$fax = $fetchval['fax'];
			$place = $fetchval['place'];
			$type = $fetchval['type'];
			$website = $fetchval['website'];
			$stdcode = $fetchval['stdcode'];
			$district = $fetchval['district'];
			$pincode = $fetchval['pincode'];
			$category = $fetchval['category'];
		}
		else
		{
			$query = "SELECT inv_customerreqpending.businessname as businessname, inv_customerreqpending.address as address, inv_customerreqpending.place as place, inv_customerreqpending.fax as fax, inv_customerreqpending.stdcode as stdcode, inv_customerreqpending.district as district, inv_customerreqpending.pincode as pincode, inv_customerreqpending.type as type, inv_customerreqpending.website as website , inv_customerreqpending.category as category FROM inv_customerreqpending where inv_customerreqpending.customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
			$fetchval = runmysqlqueryfetch($query);
			$businessname = $fetchval['businessname'];
			$address = $fetchval['address'];
			$fax = $fetchval['fax'];
			$place = $fetchval['place'];
			$type = $fetchval['type'];
			$website = $fetchval['website'];
			$stdcode = $fetchval['stdcode'];
			$district = $fetchval['district'];
			$pincode = $fetchval['pincode'];
			$category = $fetchval['category'];
		}
		
		
		$countquery = "SELECT COUNT(*) as count FROM inv_customerreqpending WHERE customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
		$countfetch = runmysqlqueryfetch($countquery);
		if($countfetch['count'] <> 0)
		{
			$countquery1 = "SELECT COUNT(*) as count FROM inv_contactreqpending WHERE customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
			$countfetch1 = runmysqlqueryfetch($countquery1);
			if($countfetch1['count'] <> 0)
			{
				$query1 ="SELECT slno,customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactreqpending where customerid = '".$lastslno."'  AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
				$resultfetch = runmysqlquery($query1);
				$valuecount = 0;
				while($fetchres = mysqli_fetch_array($resultfetch))
				{
					if($valuecount > 0)
						$contactarray .= '****';
					$selectiontype = $fetchres['selectiontype'];
					$contactperson = $fetchres['contactperson'];
					$phone = $fetchres['phone'];
					$cell = $fetchres['cell'];
					$emailid = $fetchres['emailid'];
					$slno = $fetchres['slno'];
					
					$contactarray .= $selectiontype.'#'.$contactperson.'#'.$phone.'#'.$cell.'#'.$emailid.'#'.$slno;
					$valuecount++;
				}
				$contactsplit = explode('****',$contactarray);
				$contactcount = count($contactsplit);
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
				$query134 = "UPDATE inv_contactreqpending SET customerstatus = 'oldrequest' WHERE customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
				$result = runmysqlquery($query134);
			}
			else
			{
				$query1 ="SELECT slno,customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactdetails where customerid = '".$lastslno."';";
				$resultfetch = runmysqlquery($query1);
				$valuecount = 0;
				while($fetchres = mysqli_fetch_array($resultfetch))
				{
					if($valuecount > 0)
						$contactarray .= '****';
					$selectiontype = $fetchres['selectiontype'];
					$contactperson = $fetchres['contactperson'];
					$phone = $fetchres['phone'];
					$cell = $fetchres['cell'];
					$emailid = $fetchres['emailid'];
					$slno = $fetchres['slno'];
					
					$contactarray .= $selectiontype.'#'.$contactperson.'#'.$phone.'#'.$cell.'#'.$emailid.'#'.$slno;
					$valuecount++;
				}
				$contactsplit = explode('****',$contactarray);
				$contactcount = count($contactsplit);
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
			$query1 = "UPDATE inv_customerreqpending SET customerstatus = 'oldrequest' WHERE customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
			$result = runmysqlquery($query1);
			
			$query = runmysqlqueryfetch("SELECT (MAX(slno) + 1) AS slno FROM inv_customerreqpending");
			$pendingtableslno = $query['slno'];
			
			$query = "Insert into inv_customerreqpending(slno,customerid,businessname,address,place,district,pincode,stdcode,website,type,category,createddate, customerstatus,requestfrom,requestby,fax,promotionalsms,promotionalemail,companyclosed) values('".$pendingtableslno."','".$lastslno."','".trim($businessname)."','".$address."','".$place."','".$district."','".$pincode."','".$stdcode."','".$website."','".$type."','".$category."','".changedateformatwithtime($createddate)."','pending','dealer_module','".$userid."','".$fax."','".$promotionalsms."','".$promotionalemail."','".$companyclosed."')";
			$result1 = runmysqlquery($query);
		
			for($j=0;$j<count($contactressplit);$j++)
			{
				$selectiontype = $contactressplit[$j][0];
				$contactperson = $contactressplit[$j][1];
				$phone = $contactressplit[$j][2];
				$cell = $contactressplit[$j][3];
				$emailid = $contactressplit[$j][4];
				//Added Space after comma is not avaliable in phone, cell and emailid fields
				$phonespace = str_replace(", ", ",",$phone);
				$phonevalue = str_replace(',',', ',$phonespace);
				
				$cellspace = str_replace(", ", ",",$cell);
				$cellvalue = str_replace(',',', ',$cellspace);
				
				$emailidspace = str_replace(", ", ",",$emailid);
				$emailidvalue = str_replace(',',', ',$emailidspace);
				
				$query2 = "Insert into inv_contactreqpending(refslno,customerid,selectiontype,contactperson,phone,cell,emailid,customerstatus,requestfrom,editedtype) values  ('".$pendingtableslno."','".$lastslno."','".$selectiontype."','".$contactperson."','".$phonevalue."','".$cellvalue."','".$emailidvalue."','pending','dealer_module','edit_type');";
				$result = runmysqlquery($query2);
			}
			$updateddata = $lastslno."|^|".$businessname."|^|".$contactperson."|^|".$address."|^|".$place."|^|".$district."|^|".$pincode."|^|".$stdcode."|^|".$phone."|^|".$cell."|^|".$emailid."|^|".$website."|^|".$type."|^|".$category."|^|".$createddate."|^|".$fax."|^|".$userid."|^|".$companyclosed."|^|".$contactarray;
			$query2 = "Insert into inv_logs_pendingrequest(userid,type,updateddata,updateddate,updatedtime,system) values('".$userid."','dealer_module','".$updateddata."','".datetimelocal('Y-m-d')."','".datetimelocal('H:i:s')."','".$_SERVER['REMOTE_ADDR']."')";
			$result2 = runmysqlquery($query2);
			
		}
		else
		{
			$query1 ="SELECT slno,customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactdetails where customerid = '".$lastslno."';";
				$resultfetch = runmysqlquery($query1);
				$valuecount = 0;
				while($fetchres = mysqli_fetch_array($resultfetch))
				{
					if($valuecount > 0)
						$contactarray .= '****';
					$selectiontype = $fetchres['selectiontype'];
					$contactperson = $fetchres['contactperson'];
					$phone = $fetchres['phone'];
					$cell = $fetchres['cell'];
					$emailid = $fetchres['emailid'];
					$slno = $fetchres['slno'];
					
					$contactarray .= $selectiontype.'#'.$contactperson.'#'.$phone.'#'.$cell.'#'.$emailid.'#'.$slno;
					$valuecount++;
				}
				$contactsplit = explode('****',$contactarray);
				$contactcount = count($contactsplit);
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
			$query = runmysqlqueryfetch("SELECT (MAX(slno) + 1) AS slno FROM inv_customerreqpending");
			$pendingtableslno = $query['slno'];
				
			$query = "Insert into inv_customerreqpending(slno,customerid,businessname,address,place,district,pincode,stdcode,website,type,category,createddate,customerstatus,requestfrom,requestby,fax,promotionalsms,promotionalemail,companyclosed) values('".$pendingtableslno."','".$lastslno."','".trim($businessname)."','".$address."','".$place."','".$district."','".$pincode."','".$stdcode."','".$website."','".$type."','".$category."','".changedateformatwithtime($createddate)."','pending','dealer_module','".$userid."','".$fax."','".$promotionalsms."','".$promotionalemail."','".$companyclosed."')";
			$result = runmysqlquery($query);
			
			for($j=0;$j<count($contactressplit);$j++)
			{
				$selectiontype = $contactressplit[$j][0];
				$contactperson = $contactressplit[$j][1];
				$phone = $contactressplit[$j][2];
				$cell = $contactressplit[$j][3];
				$emailid = $contactressplit[$j][4];
				//Added Space after comma is not avaliable in phone, cell and emailid fields
				$phonespace = str_replace(", ", ",",$phone);
				$phonevalue = str_replace(',',', ',$phonespace);
				
				$cellspace = str_replace(", ", ",",$cell);
				$cellvalue = str_replace(',',', ',$cellspace);
				
				$emailidspace = str_replace(", ", ",",$emailid);
				$emailidvalue = str_replace(',',', ',$emailidspace);
				
				$query2 = "Insert into inv_contactreqpending(refslno,customerid,selectiontype,contactperson,phone,cell,emailid,customerstatus,requestfrom,editedtype) values  ('".$pendingtableslno."','".$lastslno."','".$selectiontype."','".$contactperson."','".$phonevalue."','".$cellvalue."','".$emailidvalue."','pending','dealer_module','edit_type');";
				$result = runmysqlquery($query2);
			}
				
			$updateddata = $lastslno."|^|".$businessname."|^|".$address."|^|".$place."|^|".$district."|^|".$pincode."|^|".$stdcode."|^|".$website."|^|".$type."|^|".$category."|^|".$createddate."|^|".$fax."|^|".$userid."|^|".$companyclosed."|^|".$contactarray;
			$query2 = "Insert into inv_logs_pendingrequest(userid,type,updateddata,updateddate,updatedtime,system) values('".$userid."','dealer_module','".$updateddata."','".datetimelocal('Y-m-d')."','".datetimelocal('H:i:s')."','".$_SERVER['REMOTE_ADDR']."')";
			$result2 = runmysqlquery($query2);
		
		}
		
		
		$query13 = "Select  inv_mas_customer.activecustomer, inv_mas_customer.disablelogin ,inv_mas_customer.corporateorder, inv_mas_customer.companyclosed,inv_mas_customer.isdealer,inv_mas_customer.displayinwebsite,inv_customerreqpending.companyclosed, inv_customerreqpending.promotionalsms, inv_customerreqpending.promotionalemail from  inv_customerreqpending 
		left join inv_mas_customer on inv_mas_customer.slno = inv_customerreqpending.customerid
		 WHERE inv_customerreqpending.customerid = '".$lastslno."' AND inv_customerreqpending.requestfrom = 'dealer_module' and inv_customerreqpending.slno = '".$pendingtableslno."';";
		$fetch13 = runmysqlqueryfetch($query13);
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','146','".date('Y-m-d').' '.date('H:i:s')."')";
		$eventresult = runmysqlquery($eventquery);
		//echo($query12);
		echo('1^'.'Record Edited Successfully.'.'^'.strtoupper($fetch13['activecustomer']).'^'.strtoupper($fetch13['disablelogin']).'^'.strtoupper($fetch13['corporateorder']).'^'.strtoupper($fetch13['companyclosed']).'^'.strtoupper($fetch13['isdealer']).'^'.strtoupper($fetch13['displayinwebsite']).'^'.strtoupper($fetch13['promotionalsms']).'^'.strtoupper($fetch13['promotionalemail']));
	}
	break;

}

?>