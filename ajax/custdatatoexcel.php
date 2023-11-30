<?php
ini_set('memory_limit', '2048M');

include('../functions/phpfunctions.php');

// PHPExcel
//require_once '../phpgeneration/PHPExcel.php';

//PHPExcel_IOFactory
//require_once '../phpgeneration/PHPExcel/IOFactory.php';

	$userid = imaxgetcookie('dealeruserid');
	$id = $_GET['id'];
	$typeselected = $_POST['typeselected'];
	
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
					$dealerpiece = " AND inv_mas_customer.region = ('1'  or '3') ";
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
	
	$selectedfields = array("cusid","cusname","address","place","pincode","district","state","contactperson","phone", "cell","emailid","region","branch","type","category","website","dealername");
	
	$checkvalue = array("Address","Place","Pincode","District","State","Contact Person","Phone", "Cell","Emailid","Region","Branch","Type","Category","Website","Dealer");
	if($id == 'customertypecategory')
	{
		switch($typeselected)
			{
				case 'custtype' : 
							{
								$query = "SELECT distinct inv_mas_customer.slno as slno, inv_mas_customer.customerid as cusid,
	inv_mas_customer.businessname as cusname,inv_mas_customer.address as address,inv_mas_customer.place as place,inv_mas_customer.pincode as pincode,inv_mas_district.districtname as district,inv_mas_state.statename as state,inv_mas_region.category as region	,inv_mas_branch.branchname as branch,ifnull(inv_mas_customertype.customertype,'Not Avaliable') as type,ifnull(inv_mas_customercategory.businesstype,'Not Avaliable') as category ,inv_mas_customer.website as website,inv_mas_dealer.businessname as dealername,trim(both ',' from GROUP_CONCAT(inv_contactdetails.contactperson)) as contactperson,trim(both ','  from GROUP_CONCAT(inv_contactdetails.phone)) as phone,trim(both ','  from  GROUP_CONCAT(inv_contactdetails.cell)) as cell, trim(both ','  from GROUP_CONCAT(inv_contactdetails.emailid)) as emailid from inv_mas_customer 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno 
LEFT JOIN inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
LEFT JOIN inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region
LEFT JOIN inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch
LEFT JOIN inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type 
LEFT JOIN inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category
LEFT JOIN inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer 
where (inv_mas_customer.type = '' or inv_mas_customer.type is null)  ".$dealerpiece."
group  by inv_mas_customer.slno order by inv_mas_customer.businessname";
							}
							break;
				case 'category' : 
							{
								$query = "SELECT distinct inv_mas_customer.slno as slno, inv_mas_customer.customerid as cusid,
	inv_mas_customer.businessname as cusname,inv_mas_customer.address as address,inv_mas_customer.place as place,inv_mas_customer.pincode as pincode,inv_mas_district.districtname as district,inv_mas_state.statename as state,inv_mas_region.category as region	,inv_mas_branch.branchname as branch,ifnull(inv_mas_customertype.customertype,'Not Avaliable') as type,ifnull(inv_mas_customercategory.businesstype,'Not Avaliable') as category ,inv_mas_customer.website as website,inv_mas_dealer.businessname as dealername,trim(both ',' from GROUP_CONCAT(inv_contactdetails.contactperson)) as contactperson,trim(both ','  from GROUP_CONCAT(inv_contactdetails.phone)) as phone,trim(both ','  from  GROUP_CONCAT(inv_contactdetails.cell)) as cell, trim(both ','  from GROUP_CONCAT(inv_contactdetails.emailid)) as emailid from inv_mas_customer 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno 
LEFT JOIN inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
LEFT JOIN inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region
LEFT JOIN inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch
LEFT JOIN inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type 
LEFT JOIN inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category
LEFT JOIN inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer 
where (inv_mas_customer.category = '' or inv_mas_customer.category is null)  ".$dealerpiece."
group  by inv_mas_customer.slno order by inv_mas_customer.businessname";
							}
							break;
				case 'custtypeothers' : 
							{
								$query = "SELECT distinct inv_mas_customer.slno as slno, inv_mas_customer.customerid as cusid,
	inv_mas_customer.businessname as cusname,inv_mas_customer.address as address,inv_mas_customer.place as place,inv_mas_customer.pincode as pincode,inv_mas_district.districtname as district,inv_mas_state.statename as state,inv_mas_region.category as region	,inv_mas_branch.branchname as branch,ifnull(inv_mas_customertype.customertype,'Not Avaliable') as type,ifnull(inv_mas_customercategory.businesstype,'Not Avaliable') as category ,inv_mas_customer.website as website,inv_mas_dealer.businessname as dealername,trim(both ',' from GROUP_CONCAT(inv_contactdetails.contactperson)) as contactperson,trim(both ','  from GROUP_CONCAT(inv_contactdetails.phone)) as phone,trim(both ','  from  GROUP_CONCAT(inv_contactdetails.cell)) as cell, trim(both ','  from GROUP_CONCAT(inv_contactdetails.emailid)) as emailid from inv_mas_customer 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno 
LEFT JOIN inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
LEFT JOIN inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region
LEFT JOIN inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch
LEFT JOIN inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type 
LEFT JOIN inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category
LEFT JOIN inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer 
where (inv_mas_customer.type = '9')  ".$dealerpiece."
group  by inv_mas_customer.slno order by inv_mas_customer.businessname";
							}
							break;
				case 'categoryothers' : 
							{
								$query = "SELECT distinct inv_mas_customer.slno as slno, inv_mas_customer.customerid as cusid,
	inv_mas_customer.businessname as cusname,inv_mas_customer.address as address,inv_mas_customer.place as place,inv_mas_customer.pincode as pincode,inv_mas_district.districtname as district,inv_mas_state.statename as state,inv_mas_region.category as region	,inv_mas_branch.branchname as branch,ifnull(inv_mas_customertype.customertype,'Not Avaliable') as type,ifnull(inv_mas_customercategory.businesstype,'Not Avaliable') as category ,inv_mas_customer.website as website,inv_mas_dealer.businessname as dealername,trim(both ',' from GROUP_CONCAT(inv_contactdetails.contactperson)) as contactperson,trim(both ','  from GROUP_CONCAT(inv_contactdetails.phone)) as phone,trim(both ','  from  GROUP_CONCAT(inv_contactdetails.cell)) as cell, trim(both ','  from GROUP_CONCAT(inv_contactdetails.emailid)) as emailid from inv_mas_customer 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno 
LEFT JOIN inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
LEFT JOIN inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region
LEFT JOIN inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch
LEFT JOIN inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type 
LEFT JOIN inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category
LEFT JOIN inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer 
where (inv_mas_customer.category = '10')  ".$dealerpiece."
group  by inv_mas_customer.slno order by inv_mas_customer.businessname";
							}
							break;
			}
		
	}
	elseif($id == 'phonetype')
	{
		switch($typeselected)
		{
			case 'blank' : 
						{
							$query = "SELECT distinct inv_mas_customer.slno as slno, inv_mas_customer.customerid as cusid,
	inv_mas_customer.businessname as cusname,inv_mas_customer.address as address,inv_mas_customer.place as place,inv_mas_customer.pincode as pincode,inv_mas_district.districtname as district,inv_mas_state.statename as state,inv_mas_region.category as region	,inv_mas_branch.branchname as branch,ifnull(inv_mas_customertype.customertype,'Not Avaliable') as type,ifnull(inv_mas_customercategory.businesstype,'Not Avaliable') as category ,inv_mas_customer.website as website,inv_mas_dealer.businessname as dealername,trim(both ',' from GROUP_CONCAT(inv_contactdetails.contactperson)) as contactperson,trim(both ','  from GROUP_CONCAT(inv_contactdetails.phone)) as phone,trim(both ','  from  GROUP_CONCAT(inv_contactdetails.cell)) as cell, trim(both ','  from GROUP_CONCAT(inv_contactdetails.emailid)) as emailid from inv_mas_customer 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno 
LEFT JOIN inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
LEFT JOIN inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region
LEFT JOIN inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch
LEFT JOIN inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type 
LEFT JOIN inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category
LEFT JOIN inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer  where inv_mas_customer.slno not in (select inv_contactdetails.customerid as slno from inv_contactdetails)  ".$dealerpiece." group  by inv_mas_customer.slno order by inv_mas_customer.businessname";
						}
						break;
			case 'dummy' : 
						{
							$query = "SELECT distinct inv_mas_customer.slno as slno, inv_mas_customer.customerid as cusid,
	inv_mas_customer.businessname as cusname,inv_mas_customer.address as address,inv_mas_customer.place as place,inv_mas_customer.pincode as pincode,inv_mas_district.districtname as district,inv_mas_state.statename as state,inv_mas_region.category as region	,inv_mas_branch.branchname as branch,ifnull(inv_mas_customertype.customertype,'Not Avaliable') as type,ifnull(inv_mas_customercategory.businesstype,'Not Avaliable') as category ,inv_mas_customer.website as website,inv_mas_dealer.businessname as dealername,trim(both ',' from GROUP_CONCAT(inv_contactdetails.contactperson)) as contactperson,trim(both ','  from GROUP_CONCAT(inv_contactdetails.phone)) as phone,trim(both ','  from  GROUP_CONCAT(inv_contactdetails.cell)) as cell, trim(both ','  from GROUP_CONCAT(inv_contactdetails.emailid)) as emailid from inv_mas_customer 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno 
LEFT JOIN inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
LEFT JOIN inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region
LEFT JOIN inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch
LEFT JOIN inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type 
LEFT JOIN inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category
LEFT JOIN inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer 
where inv_mas_customer.slno  in (select inv_contactdetails.customerid as slno
from inv_contactdetails where (length(inv_contactdetails.phone)< 6  and 
inv_contactdetails.phone <> '') or (inv_contactdetails.phone like '%222222%')
or (inv_contactdetails.phone like '%33333%') or (inv_contactdetails.phone like '%44444%')
or (inv_contactdetails.phone like '%55555%') or (inv_contactdetails.phone like '%66666%')
or (inv_contactdetails.phone like '%77777%') or (inv_contactdetails.phone like '%88888%')
or (inv_contactdetails.phone like '%99999%') or (inv_contactdetails.phone like '%00000%')
or (inv_contactdetails.phone like '%11111%') or( inv_contactdetails.phone like '%12345%'))  ".$dealerpiece." group  by inv_mas_customer.slno order by inv_mas_customer.businessname";
						}
						break;
		}
	}
	elseif($id == 'celltype')
	{
		switch($typeselected)
		{
			case 'blank' : 
						{
							$query = "SELECT distinct inv_mas_customer.slno as slno, inv_mas_customer.customerid as cusid,
	inv_mas_customer.businessname as cusname,inv_mas_customer.address as address,inv_mas_customer.place as place,inv_mas_customer.pincode as pincode,inv_mas_district.districtname as district,inv_mas_state.statename as state,inv_mas_region.category as region	,inv_mas_branch.branchname as branch,ifnull(inv_mas_customertype.customertype,'Not Avaliable') as type,ifnull(inv_mas_customercategory.businesstype,'Not Avaliable') as category ,inv_mas_customer.website as website,inv_mas_dealer.businessname as dealername,trim(both ',' from GROUP_CONCAT(inv_contactdetails.contactperson)) as contactperson,trim(both ','  from GROUP_CONCAT(inv_contactdetails.phone)) as phone,trim(both ','  from  GROUP_CONCAT(inv_contactdetails.cell)) as cell, trim(both ','  from GROUP_CONCAT(inv_contactdetails.emailid)) as emailid from inv_mas_customer 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno 
LEFT JOIN inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
LEFT JOIN inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region
LEFT JOIN inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch
LEFT JOIN inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type 
LEFT JOIN inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category
LEFT JOIN inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer where inv_mas_customer.slno not in (select inv_contactdetails.customerid as slno
from inv_contactdetails)  ".$dealerpiece." group  by inv_mas_customer.slno order by inv_mas_customer.businessname";
						}
						break;
			case 'dummy' : 
						{
							$query = "SELECT distinct inv_mas_customer.slno as slno, inv_mas_customer.customerid as cusid,
	inv_mas_customer.businessname as cusname,inv_mas_customer.address as address,inv_mas_customer.place as place,inv_mas_customer.pincode as pincode,inv_mas_district.districtname as district,inv_mas_state.statename as state,inv_mas_region.category as region	,inv_mas_branch.branchname as branch,ifnull(inv_mas_customertype.customertype,'Not Avaliable') as type,ifnull(inv_mas_customercategory.businesstype,'Not Avaliable') as category ,inv_mas_customer.website as website,inv_mas_dealer.businessname as dealername,trim(both ',' from GROUP_CONCAT(inv_contactdetails.contactperson)) as contactperson,trim(both ','  from GROUP_CONCAT(inv_contactdetails.phone)) as phone,trim(both ','  from  GROUP_CONCAT(inv_contactdetails.cell)) as cell, trim(both ','  from GROUP_CONCAT(inv_contactdetails.emailid)) as emailid from inv_mas_customer 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno 
LEFT JOIN inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
LEFT JOIN inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region
LEFT JOIN inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch
LEFT JOIN inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type 
LEFT JOIN inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category
LEFT JOIN inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer
where inv_mas_customer.slno  in (select inv_contactdetails.customerid as slno
from inv_contactdetails where length(inv_contactdetails.cell)< 10  and 
inv_contactdetails.cell <> '' or (inv_contactdetails.cell like '%9999999999%')
or (inv_contactdetails.cell like '%8888888888%')or (inv_contactdetails.cell like '%7777777777%'))  ".$dealerpiece." 
group  by inv_mas_customer.slno order by inv_mas_customer.businessname";
						}
						break;
		}
	}
	elseif($id == 'emailidtype')
	{
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
		switch($typeselected)
		{
			case 'blank' : 
						{
							$query = "SELECT distinct inv_mas_customer.slno as slno, inv_mas_customer.customerid as cusid,
	inv_mas_customer.businessname as cusname,inv_mas_customer.address as address,inv_mas_customer.place as place,inv_mas_customer.pincode as pincode,inv_mas_district.districtname as district,inv_mas_state.statename as state,inv_mas_region.category as region	,inv_mas_branch.branchname as branch,ifnull(inv_mas_customertype.customertype,'Not Avaliable') as type,ifnull(inv_mas_customercategory.businesstype,'Not Avaliable') as category ,inv_mas_customer.website as website,inv_mas_dealer.businessname as dealername,trim(both ',' from GROUP_CONCAT(inv_contactdetails.contactperson)) as contactperson,trim(both ','  from GROUP_CONCAT(inv_contactdetails.phone)) as phone,trim(both ','  from  GROUP_CONCAT(inv_contactdetails.cell)) as cell, trim(both ','  from GROUP_CONCAT(inv_contactdetails.emailid)) as emailid from inv_mas_customer 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno 
LEFT JOIN inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
LEFT JOIN inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region
LEFT JOIN inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch
LEFT JOIN inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type 
LEFT JOIN inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category
LEFT JOIN inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer where inv_mas_customer.slno not in (select inv_contactdetails.customerid as slno
from inv_contactdetails)  ".$dealerpiece." group  by inv_mas_customer.slno order by inv_mas_customer.businessname";
						}
						break;
			
			case 'relyon' : 
						{
							$query = "SELECT distinct inv_mas_customer.slno as slno, inv_mas_customer.customerid as cusid,
	inv_mas_customer.businessname as cusname,inv_mas_customer.address as address,inv_mas_customer.place as place,inv_mas_customer.pincode as pincode,inv_mas_district.districtname as district,inv_mas_state.statename as state,inv_mas_region.category as region	,inv_mas_branch.branchname as branch,ifnull(inv_mas_customertype.customertype,'Not Avaliable') as type,ifnull(inv_mas_customercategory.businesstype,'Not Avaliable') as category ,inv_mas_customer.website as website,inv_mas_dealer.businessname as dealername,trim(both ',' from GROUP_CONCAT(inv_contactdetails.contactperson)) as contactperson,trim(both ','  from GROUP_CONCAT(inv_contactdetails.phone)) as phone,trim(both ','  from  GROUP_CONCAT(inv_contactdetails.cell)) as cell, trim(both ','  from GROUP_CONCAT(inv_contactdetails.emailid)) as emailid from inv_mas_customer 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno 
LEFT JOIN inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
LEFT JOIN inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region
LEFT JOIN inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch
LEFT JOIN inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type 
LEFT JOIN inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category
LEFT JOIN inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer
where inv_mas_customer.slno  in (select inv_contactdetails.customerid as slno
from inv_contactdetails where (inv_contactdetails.emailid like '%@relyonsoft.com' and inv_contactdetails.emailid not in (".$concatenatedIDs.")))  ".$dealerpiece." group  by inv_contactdetails.customerid  order by inv_mas_customer.businessname";
						}
						break;
			
			case 'dealer' : 
						{
							$query = "SELECT distinct inv_mas_customer.slno as slno, inv_mas_customer.customerid as cusid,
	inv_mas_customer.businessname as cusname,inv_mas_customer.address as address,inv_mas_customer.place as place,inv_mas_customer.pincode as pincode,inv_mas_district.districtname as district,inv_mas_state.statename as state,inv_mas_region.category as region	,inv_mas_branch.branchname as branch,ifnull(inv_mas_customertype.customertype,'Not Avaliable') as type,ifnull(inv_mas_customercategory.businesstype,'Not Avaliable') as category ,inv_mas_customer.website as website,inv_mas_dealer.businessname as dealername,trim(both ',' from GROUP_CONCAT(inv_contactdetails.contactperson)) as contactperson,trim(both ','  from GROUP_CONCAT(inv_contactdetails.phone)) as phone,trim(both ','  from  GROUP_CONCAT(inv_contactdetails.cell)) as cell, trim(both ','  from GROUP_CONCAT(inv_contactdetails.emailid)) as emailid from inv_mas_customer 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno 
LEFT JOIN inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
LEFT JOIN inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region
LEFT JOIN inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch
LEFT JOIN inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type 
LEFT JOIN inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category
LEFT JOIN inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer
where inv_mas_customer.slno  in (select inv_contactdetails.customerid as slno
from inv_contactdetails where inv_contactdetails.emailid not like '%a@a.com%' 
and (inv_contactdetails.emailid <> '' or  inv_contactdetails.emailid is not null)  and
 (inv_contactdetails.emailid in (".$concatenatedIDs.")))  ".$dealerpiece." group  by inv_contactdetails.customerid  order by inv_mas_customer.businessname";
						}
						break;
			case 'dummy' : 
						{
							$query = "SELECT distinct inv_mas_customer.slno as slno, inv_mas_customer.customerid as cusid,
	inv_mas_customer.businessname as cusname,inv_mas_customer.address as address,inv_mas_customer.place as place,inv_mas_customer.pincode as pincode,inv_mas_district.districtname as district,inv_mas_state.statename as state,inv_mas_region.category as region	,inv_mas_branch.branchname as branch,ifnull(inv_mas_customertype.customertype,'Not Avaliable') as type,ifnull(inv_mas_customercategory.businesstype,'Not Avaliable') as category ,inv_mas_customer.website as website,inv_mas_dealer.businessname as dealername,trim(both ',' from GROUP_CONCAT(inv_contactdetails.contactperson)) as contactperson,trim(both ','  from GROUP_CONCAT(inv_contactdetails.phone)) as phone,trim(both ','  from  GROUP_CONCAT(inv_contactdetails.cell)) as cell, trim(both ','  from GROUP_CONCAT(inv_contactdetails.emailid)) as emailid from inv_mas_customer 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno 
LEFT JOIN inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
LEFT JOIN inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region
LEFT JOIN inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch
LEFT JOIN inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type 
LEFT JOIN inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category
LEFT JOIN inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer
where LENGTH(inv_contactdetails.emailid) < 8 and (inv_contactdetails.emailid <> '' or inv_contactdetails.emailid is null)
or (inv_contactdetails.emailid REGEXP '([,][\s]|[;][\s]|[,;]|[ ])')
or  (inv_contactdetails.emailid REGEXP '^[ ]$') or  (inv_contactdetails.emailid like '%df@dff.com%')  ".$dealerpiece." group  by inv_contactdetails.customerid   order by inv_mas_customer.businessname";
						}
						break;
		}
	}
	//echo($query);exit;			
			
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
	$value = chr(67+(count($checkvalue))).'3';
	
	//Apply style for header Row
	$mySheet->getStyle('A3:'.$value.'')->applyFromArray($styleArray);
				
	//Merge the cell
	$mySheet->mergeCells('A1:U1');
	$mySheet->mergeCells('A2:U2');
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
				->setCellValue('A2', 'Data Inaccuracy Report');
	$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
	$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
	$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
		
	//Fille contents for Header Row
	$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A3', 'Sl No')
			->setCellValue('B3', 'Customer ID')
			->setCellValue('C3', 'Customer Name');
		for ($j=0; $j<count($checkvalue); $j++)
		{
				$assciivalue = chr(68+$j).'3';
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($assciivalue,$checkvalue[$j]);
		}
	
		$j =4;
		$slno= 0;
		while($fetch = mysqli_fetch_array($result))
		{
			
			
			$slno++;
			$mySheet->setCellValue('A' . $j,$slno);
			for($m=0; $m < count($selectedfields); $m++)
			{
				$resultvalue = chr(66+$m).$j;
				if($selectedfields[$m] == "cusid")
					$mySheet->setCellValue($resultvalue,cusidcombine($fetch[$selectedfields[$m]]));
				else if($selectedfields[$m] == "contactperson")
				{
					if($fetch[$selectedfields[$m]] == '')
						$mySheet->setCellValue($resultvalue,'Not Avaliable');
					else
						$mySheet->setCellValue($resultvalue,str_replace(",",", ",trim($fetch[$selectedfields[$m]])));
				}
				else if($selectedfields[$m] == "phone")
				{
					if($fetch[$selectedfields[$m]] == '')
						$mySheet->setCellValue($resultvalue,'Not Avaliable');
					else
						$mySheet->setCellValue($resultvalue,str_replace(",",", ",trim($fetch[$selectedfields[$m]])));
				}
				else if($selectedfields[$m] == "cell")
				{
					if($fetch[$selectedfields[$m]] == '')
						$mySheet->setCellValue($resultvalue,'Not Avaliable');
					else
						$mySheet->setCellValue($resultvalue,str_replace(",",", ",trim($fetch[$selectedfields[$m]])));
				}
				else if($selectedfields[$m] == "emailid")
				{
					if($fetch[$selectedfields[$m]] == '')
						$mySheet->setCellValue($resultvalue,'Not Avaliable');
					else
						$mySheet->setCellValue($resultvalue,str_replace(",",", ",trim($fetch[$selectedfields[$m]])));
				}
				else
					$mySheet->setCellValue($resultvalue,$fetch[$selectedfields[$m]]);
			}
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
		$mySheet->getColumnDimension('B')->setWidth(20);
		$mySheet->getColumnDimension('C')->setWidth(35);
		$setvalue = chr(67+(count($checkvalue)));
		$mySheet->getDefaultColumnDimension('D:'.$setvalue.'')->setWidth(35);
		
	
		if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") || ($_SERVER['HTTP_HOST'] == "vijaykumar"))
			$addstring = "/saralimax-dealer";
		else
			$addstring = "/imax/dealer";
		
			$query = 'select slno,dealerusername as username  from inv_mas_dealer  where inv_mas_dealer.slno = '.$userid.'';
			$fetchres = runmysqlqueryfetch($query);	
			$username = $fetchres['username'];		
			$localdate = datetimelocal('Ymd');
			$localtime = datetimelocal('His');
			$filebasename = "CustomerDataAnalysis".$localdate."-".$localtime."-".strtolower($username).".xls";
			$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','excel_dataanalysis_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
			$result = runmysqlquery($query1);	
			
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
