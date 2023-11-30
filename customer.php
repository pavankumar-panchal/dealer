<?php
ob_start("ob_gzhandler");

include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');
include('../softkey/regfunction.bin');
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

// Current Year 
$currentyear = "2014-15";

switch($switchtype)
{
	case 'save':
	{
		$customerid = $_POST['customerid'];
		$businessname = $_POST['businessname'];
		$address = $_POST['address'];
		$place = $_POST['place'];
		$pincode = $_POST['pincode'];
		$district = $_POST['district'];
		$category = $_POST['category'];
		$type = $_POST['type'];
		$lastslno = $_POST['lastslno'];
		$fax = $_POST['fax'];
		$stdcode = $_POST['stdcode'];
		$contactarray = $_POST['contactarray'];
		$totalarray = $_POST['totalarray'];
		$totalsplit = explode(',',$totalarray);
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
		
		$website = $_POST['website'];
		$remarks = $_POST['remarks'];
		$companyclosed = $_POST['companyclosed'];
		$promotionalsms = $_POST['promotionalsms'];
		$promotionalemail = $_POST['promotionalemail'];
		$createddate = datetimelocal('d-m-Y').' '.datetimelocal('H:i:s');
		$date = datetimelocal('d-m-Y');
		
		if($lastslno == "")
		{
			
			//Get the Logged in dealer Region and Branch
			$query111 = "select branch,region from inv_mas_dealer where slno = '".$userid."';";
			$fetchresult111 = runmysqlqueryfetch($query111);
			$regionid = $fetchresult111['region'];
			$branchid = $fetchresult111['branch'];
			
			$query = runmysqlqueryfetch("SELECT (MAX(slno) + 1) AS newcustomerid FROM inv_mas_customer");
			$cusslno = $query['newcustomerid'];
	
			$query = "Insert into inv_mas_customer(slno,customerid,businessname,address, place,pincode,district,region,category,type,stdcode,website,remarks,password,passwordchanged,disablelogin,createddate,createdby,corporateorder,currentdealer,fax,activecustomer,lastmodifieddate,lastmodifiedby,createdip,lastmodifiedip,branch,companyclosed, promotionalsms,promotionalemail) values ('".$cusslno."','','".trim($businessname)."','".$address."','".$place."','".$pincode."','".$district."','".$regionid."','".$category."','".$type."','".$stdcode."','".$website."','".$remarks."','','N','no','".changedateformatwithtime($createddate)."','2','no','".$userid."','".$fax."','yes','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".$_SERVER['REMOTE_ADDR']."','".$_SERVER['REMOTE_ADDR']."','".$branchid."','".$companyclosed."','no','no');";
			$result = runmysqlquery($query);
			
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','113','".date('Y-m-d').' '.date('H:i:s')."','".$cusslno."')";
			$eventresult = runmysqlquery($eventquery);
			
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
				
				$query2 = "Insert into inv_contactdetails(customerid,selectiontype,contactperson,phone,cell,emailid) values  ('".$cusslno."','".$selectiontype."','".$contactperson."','".$phonevalue."','".$cellvalue."','".$emailidvalue."');";
				$result = runmysqlquery($query2);
			}

			$responsesavearray = array();
			$responsesavearray['successcode'] = "1";
			$responsesavearray['successmessage'] = "Customer Record  Saved Successfully";
			echo(json_encode($responsesavearray));
		}
		elseif($lastslno <> "")
		{
			$countquery = "SELECT COUNT(*) as count FROM inv_customerreqpending WHERE customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
			$countfetch = runmysqlqueryfetch($countquery);
			if($countfetch['count'] <> 0)
			{
				$countquery1 = "SELECT COUNT(*) as count FROM inv_contactreqpending WHERE customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
				$countfetch1 = runmysqlqueryfetch($countquery1);
				if($countfetch1['count'] <> 0)
				{
					$query1 = "UPDATE inv_contactreqpending SET customerstatus = 'oldrequest' WHERE customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
					$result = runmysqlquery($query1);
				}
				$query1 = "UPDATE inv_customerreqpending SET customerstatus = 'oldrequest' WHERE customerid = '".$lastslno."' AND requestfrom = 'dealer_module' AND customerstatus = 'pending';";
				$result = runmysqlquery($query1);
				
				$query = runmysqlqueryfetch("SELECT (MAX(slno) + 1) AS slno FROM inv_customerreqpending");
				$slno = $query['slno'];
				
				$query = "Insert into inv_customerreqpending(slno,customerid,businessname,address,place,district,pincode,stdcode,website,type,category,createddate, customerstatus,requestfrom,requestby,fax,companyclosed,remarks,promotionalsms,promotionalemail) values('".$slno."','".$lastslno."','".trim($businessname)."','".$address."','".$place."','".$district."','".$pincode."','".$stdcode."','".$website."','".$type."','".$category."','".changedateformatwithtime($createddate)."','pending','dealer_module','".$userid."','".$fax."','".$companyclosed."','".$remarks."','".$promotionalsms."','".$promotionalemail."')";
				$result1 = runmysqlquery($query);
				
				if($totalarray <> '')
				{
					$query22 = "SELECT slno,customerid,contactperson,selectiontype,phone,cell,emailid from inv_contactdetails where slno IN (".$totalarray.")";
					$result11 = runmysqlquery($query22);
					while($fetchres = mysqli_fetch_array($result11))
					{
						$selectiontype1 = $fetchres['selectiontype'];
						$contactperson1 = $fetchres['contactperson'];
						$phone1 = $fetchres['phone'];
						$cell1 = $fetchres['cell'];
						$emailid1= $fetchres['emailid'];
						
						$query4 = "Insert into inv_contactreqpending(refslno,customerid,selectiontype,contactperson,phone,cell,emailid,customerstatus,requestfrom,editedtype) values  ('".$slno."','".$lastslno."','".$selectiontype1."','".$contactperson1."','".$phone1."','".$cell1."','".$emailid1."','pending','dealer_module','delete_type');";
						$result = runmysqlquery($query4);
					}
				}
			
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
				$updateddata = $lastslno."|^|".$businessname."|^|".$contactperson."|^|".$address."|^|".$place."|^|".$district."|^|".$pincode."|^|".$stdcode."|^|".$phone."|^|".$cell."|^|".$emailid."|^|".$website."|^|".$type."|^|".$category."|^|".$createddate."|^|".$fax."|^|".$userid."|^|".$companyclosed."|^|".$contactarray;
				$query2 = "Insert into inv_logs_pendingrequest(userid,type,updateddata,updateddate,updatedtime,system) values('".$userid."','dealer_module','".$updateddata."','".datetimelocal('Y-m-d')."','".datetimelocal('H:i:s')."','".$_SERVER['REMOTE_ADDR']."')";
				$result2 = runmysqlquery($query2);
				
			}
			else
			{
				$query = runmysqlqueryfetch("SELECT (MAX(slno) + 1) AS slno FROM inv_customerreqpending");
				$slno = $query['slno'];
					
				$query = "Insert into inv_customerreqpending(slno,customerid,businessname,address,place,district,pincode,stdcode,website,type,category,createddate,customerstatus,requestfrom,requestby,fax,companyclosed,remarks,promotionalsms,promotionalemail) values('".$slno."','".$lastslno."','".trim($businessname)."','".$address."','".$place."','".$district."','".$pincode."','".$stdcode."','".$website."','".$type."','".$category."','".changedateformatwithtime($createddate)."','pending','dealer_module','".$userid."','".$fax."','".$companyclosed."','".$remarks."','".$promotionalsms."','".$promotionalemail."')";
				$result = runmysqlquery($query);
				if($totalarray <> '')
				{
					$query22 = "SELECT slno,customerid,contactperson,selectiontype,phone,cell,emailid from inv_contactdetails where slno IN (".$totalarray.")";
					$result11 = runmysqlquery($query22);
					while($fetchres = mysqli_fetch_array($result11))
					{
						$selectiontype1 = $fetchres['selectiontype'];
						$contactperson1 = $fetchres['contactperson'];
						$phone1 = $fetchres['phone'];
						$cell1 = $fetchres['cell'];
						$emailid1= $fetchres['emailid'];
						
						$query4 = "Insert into inv_contactreqpending(refslno,customerid,selectiontype,contactperson,phone,cell,emailid,customerstatus,requestfrom,editedtype) values  ('".$slno."','".$lastslno."','".$selectiontype1."','".$contactperson1."','".$phone1."','".$cell1."','".$emailid1."','pending','dealer_module','delete_type');";
						$result = runmysqlquery($query4);
					}
				}
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
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','114','".date('Y-m-d').' '.date('H:i:s')."','".$lastslno.'$$'.$slno."')";
			$eventresult = runmysqlquery($eventquery);
			$responsesavearray1 = array();
			$responsesavearray1['successcode'] = "2";
			$responsesavearray1['successmessage'] = "Customer Record  Saved Successfully";
			echo(json_encode($responsesavearray1));
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
		$productslist = str_replace('\\','',$_POST['productscode']);
		$district2 = $_POST['district'];
		$type2 = $_POST['type2'];
		$category2= $_POST['category2'];
		$regionpiece = ($region2 == "")?(""):(" AND inv_mas_customer.region = '".$region2."' ");
		$state_typepiece = ($state2 == "")?(""):(" AND inv_mas_district.statecode = '".$state2."' ");
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
		$query = "select distinct inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode,inv_mas_dealer.region,inv_mas_dealer.branch,inv_mas_dealer.telecaller from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
		left join inv_mas_customer on inv_mas_customer.currentdealer = inv_mas_dealer.slno
		where inv_mas_dealer.slno = '".$userid."'  ORDER BY inv_mas_customer.businessname ";
		$fetch2 = runmysqlqueryfetch($query);
		$relyonexecutive = $fetch2['relyonexecutive'];
		$district = $fetch2['district'];
		$state = $fetch2['statecode'];
		$branch = $fetch2['branch'];
		$region = $fetch2['region'];
		$telecaller = $fetch2['telecaller'];
		switch($databasefield)
		{
			case "slno":
			{
				$customeridlen = strlen($textfield);
				$lastcustomerid = cusidsplit($textfield);
				if($telecaller == 'yes')
				{
					$query0 = "select  distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname
from inv_mas_customer left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode 
LEFT JOIN (select distinct customerreference from inv_customerproduct where left(computerid,3)   IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and  (inv_mas_customer.slno LIKE '%".$lastcustomerid."%' OR inv_mas_customer.customerid LIKE '%".$lastcustomerid."%') ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$typepiece.$categorypiece.$branchpiece." and inv_mas_customer.branch in( select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."') ORDER BY inv_mas_customer.businessname  ";
					$result = runmysqlquery($query0);
					if(mysqli_num_rows($result) == 0)
					{
						$query0 = "select  distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname
from inv_mas_customer left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode 
LEFT JOIN (select distinct customerreference from inv_customerproduct where left(computerid,3)   IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and  (inv_mas_customer.slno LIKE '%".$lastcustomerid."%' OR inv_mas_customer.customerid LIKE '%".$lastcustomerid."%') ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$typepiece.$categorypiece.$branchpiece." and inv_mas_customer.region = '2' ORDER BY inv_mas_customer.businessname  ";
						$result = runmysqlquery($query0);
					}
				}
				else
				{
					if($relyonexecutive == 'no')
					{
						$query0 = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname 
from inv_mas_customer LEFT JOIN (select distinct customerreference from inv_customerproduct where left(computerid,3) IN (".$productslist.")) as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and  (inv_mas_customer.slno = '".$lastcustomerid."' OR inv_mas_customer.customerid = '".$lastcustomerid."') ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and inv_mas_customer.currentdealer = '".$userid."' ORDER BY inv_mas_customer.businessname ";
						$result = runmysqlquery($query0);
					}
					else
					{
						if(($region == '1') || ($region == '3'))
						{
							$query0 = "select  distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname
from inv_mas_customer left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode 
LEFT JOIN (select distinct customerreference from inv_customerproduct where left(computerid,3)   IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and  (inv_mas_customer.slno LIKE '%".$lastcustomerid."%' OR inv_mas_customer.customerid LIKE '%".$lastcustomerid."%') AND  (inv_mas_customer.region = '1' or inv_mas_customer.region = '3') ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$typepiece.$categorypiece.$branchpiece." ORDER BY inv_mas_customer.businessname  ";
							$result = runmysqlquery($query0);
						}
						else
						{
							$query0 = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname 
from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
LEFT JOIN (select distinct customerreference from inv_customerproduct where left(computerid,3)  IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and  (inv_mas_customer.slno LIKE '".$lastcustomerid."' OR inv_mas_customer.customerid = '".$lastcustomerid."') ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece."  and inv_mas_customer.branch = '".$branch."' ORDER BY inv_mas_customer.businessname  ";
							$result = runmysqlquery($query0);
						}
					}
				}//echo($query0);exit;
			}
			break;
			case 'contactperson':
			{
				if($telecaller == 'yes')
				{
					$query1 = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname
from inv_mas_customer left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
LEFT JOIN (select distinct customerreference from inv_customerproduct where left(computerid,3)  IN (".$productslist.")) as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference
where inv_customerproduct.customerreference IS NOT NULL and inv_contactdetails.contactperson LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and inv_mas_customer.branch in( select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."') ORDER BY inv_mas_customer.businessname";
					$result = runmysqlquery($query1);
					if(mysqli_num_rows($result) == 0)
					{
						$query1 = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname
						from inv_mas_customer left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
LEFT JOIN (select distinct customerreference from inv_customerproduct where left(computerid,3)  IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_contactdetails.contactperson LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and inv_mas_customer.region = '2' ORDER BY inv_mas_customer.businessname";
						$result = runmysqlquery($query1);
					}
				}
				else
				{
					if($relyonexecutive == 'no')
					{
						$query1 = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname  from inv_mas_customer left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno LEFT JOIN (select distinct customerreference from inv_customerproduct where left(computerid,3) IN (".$productslist.")) as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_contactdetails.contactperson LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and 
inv_mas_customer.currentdealer = '".$userid."'  ORDER BY inv_mas_customer.businessname";
						$result = runmysqlquery($query1);
					}
					else
					{
						if(($region == '1') || ($region == '3'))
						{
							$query1 = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname
						from inv_mas_customer left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
LEFT JOIN (select distinct customerreference from inv_customerproduct where left(computerid,3)  IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_contactdetails.contactperson LIKE '%".$textfield."%' AND  (inv_mas_customer.region = '1' or inv_mas_customer.region = '3') ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." ORDER BY inv_mas_customer.businessname";
							$result = runmysqlquery($query1);
						}
						else
						{
							$query1 = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname
							from inv_mas_customer left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
LEFT JOIN (select distinct customerreference from inv_customerproduct where left(computerid,3)  IN (".$productslist.")) as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_contactdetails.contactperson LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and inv_mas_customer.branch = '".$branch."' ORDER BY inv_mas_customer.businessname";
							$result = runmysqlquery($query1);
						}
					}
				}
			}
			break;
			case 'place':
			{
				if($telecaller == 'yes')
				{
					
					$query2 = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname
from inv_mas_customer left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode 
LEFT JOIN (select distinct customerreference from inv_customerproduct where left(computerid,3)   IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_mas_customer.place LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and inv_mas_customer.branch in( select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."') ORDER BY inv_mas_customer.businessname";
					$result = runmysqlquery($query2);
					if(mysqli_num_rows($result) == 0)
					{
						$query2 = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname
from inv_mas_customer left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode 
LEFT JOIN (select distinct customerreference from inv_customerproduct where left(computerid,3)   IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_mas_customer.place LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and inv_mas_customer.region = '2' ORDER BY inv_mas_customer.businessname";
					$result = runmysqlquery($query2);
					}
				}
				else
				{
					if($relyonexecutive == 'no')
					{
						$query2 = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname  from inv_mas_customer LEFT JOIN (select distinct customerreference from inv_customerproduct where left(computerid,3) IN (".$productslist.")) as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_mas_customer.place LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and 
inv_mas_customer.currentdealer = '".$userid."'  ORDER BY inv_mas_customer.businessname";
						$result = runmysqlquery($query2);
					}
					else
					{
						if(($region == '1') || ($region == '3'))
						{
							$query2 = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname
from inv_mas_customer left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode 
LEFT JOIN (select distinct customerreference from inv_customerproduct where left(computerid,3)   IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_mas_customer.place LIKE '%".$textfield."%' AND  (inv_mas_customer.region = '1' or inv_mas_customer.region = '3') ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece."  ORDER BY inv_mas_customer.businessname";
							$result = runmysqlquery($query2);
						}
						else
						{
							$query2 = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname
from inv_mas_customer left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode 
LEFT JOIN (select distinct customerreference from inv_customerproduct where left(computerid,3)   IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_mas_customer.place LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and inv_mas_customer.branch = '".$branch."' ORDER BY inv_mas_customer.businessname";
							$result = runmysqlquery($query2);
						}
					}
				}
			}
			break;
			case 'emailid':
			{
				if($telecaller == 'yes')
				{
					$query3 = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname
from inv_mas_customer left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
LEFT JOIN (select distinct customerreference from inv_customerproduct where left(computerid,3)  IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_contactdetails.emailid LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and inv_mas_customer.branch in( select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."') ORDER BY inv_mas_customer.businessname";
					$result = runmysqlquery($query3);
					if(mysqli_num_rows($result) == 0)
					{
						$query3 = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname
from inv_mas_customer left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
LEFT JOIN (select distinct customerreference from inv_customerproduct where left(computerid,3)  IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_contactdetails.emailid LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and inv_mas_customer.region = '2' ORDER BY inv_mas_customer.businessname";
						$result = runmysqlquery($query3);
					}
				}
				else
				{
					if($relyonexecutive == 'no')
					{
						$query3 = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname  from inv_mas_customer left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno LEFT JOIN (select distinct customerreference from inv_customerproduct where left(computerid,3) IN (".$productslist.")) as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_contactdetails.emailid LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and 
		inv_mas_customer.currentdealer = '".$userid."'  ORDER BY inv_mas_customer.businessname";
						$result = runmysqlquery($query3);
					}
					else
					{
						if(($region == '1') || ($region == '3'))
						{
							$query3 = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname
from inv_mas_customer left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
LEFT JOIN (select distinct customerreference from inv_customerproduct where left(computerid,3)  IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_contactdetails.emailid LIKE '%".$textfield."%' AND (inv_mas_customer.region = '1' or inv_mas_customer.region = '3') ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece."  ORDER BY inv_mas_customer.businessname";
							$result = runmysqlquery($query3);
						}
						else
						{
							$query3 = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname
from inv_mas_customer left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
LEFT JOIN (select distinct customerreference from inv_customerproduct where left(computerid,3)  IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_contactdetails.emailid LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and inv_mas_customer.branch = '".$branch."' ORDER BY inv_mas_customer.businessname";
							$result = runmysqlquery($query3);
						}
					}
				}
			}
			break;
			case 'phone':
			{
				if($telecaller == 'yes')
				{
					$query4 = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname
from inv_mas_customer left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno  left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode 
LEFT JOIN (select distinct customerreference from inv_customerproduct where left(computerid,3)  IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and (inv_contactdetails.phone LIKE '%".$textfield."%'  OR inv_contactdetails.cell LIKE '%".$textfield."%') ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and inv_mas_customer.branch in( select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."') ORDER BY inv_mas_customer.businessname";
					$result = runmysqlquery($query4);
					if(mysqli_num_rows($result) == 0)
					{
						$query4 = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname
from inv_mas_customer left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno  left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode 
LEFT JOIN (select distinct customerreference from inv_customerproduct where left(computerid,3)  IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and (inv_contactdetails.phone LIKE '%".$textfield."%'  OR inv_contactdetails.cell LIKE '%".$textfield."%') ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and inv_mas_customer.region = '2' ORDER BY inv_mas_customer.businessname";
						$result = runmysqlquery($query4);
					}
				}
				else
				{
					if($relyonexecutive == 'no')
					{
						$query4 = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname  from inv_mas_customer left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno  LEFT JOIN (select distinct customerreference from inv_customerproduct where left(computerid,3) IN (".$productslist.")) as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and (inv_contactdetails.phone LIKE '%".$textfield."%'  OR inv_contactdetails.cell LIKE '%".$textfield."%') ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and inv_mas_customer.currentdealer = '".$userid."'  ORDER BY inv_mas_customer.businessname";
						$result = runmysqlquery($query4);
					}
					else
					{
						if(($region == '1') || ($region == '3'))
						{
							$query4 = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname
from inv_mas_customer left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno  left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode 
LEFT JOIN (select distinct customerreference from inv_customerproduct where left(computerid,3)  IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and (inv_contactdetails.phone LIKE '%".$textfield."%'  OR inv_contactdetails.cell LIKE '%".$textfield."%') AND (inv_mas_customer.region = '1' or inv_mas_customer.region = '3') ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece."  ORDER BY inv_mas_customer.businessname";
							$result = runmysqlquery($query4);
						}
						else
						{
							$query4 = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname
from inv_mas_customer left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno  left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode 
LEFT JOIN (select distinct customerreference from inv_customerproduct where left(computerid,3)  IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and (inv_contactdetails.phone LIKE '%".$textfield."%'  OR inv_contactdetails.cell LIKE '%".$textfield."%') ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and inv_mas_customer.branch = '".$branch."' ORDER BY inv_mas_customer.businessname";
							$result = runmysqlquery($query4);
						}
					}
				}
			}
			break;
			case 'cardid':
			{
				if($telecaller == 'yes')
				{
					$query5 = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname
from inv_mas_customer LEFT JOIN (inv_customerproduct LEFT JOIN inv_mas_scratchcard ON inv_customerproduct.cardid = inv_mas_scratchcard.cardid) ON  inv_mas_customer.slno = inv_customerproduct.customerreference 
left join inv_mas_product on left(inv_customerproduct.computerid,3) IN (".$productslist.")
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
WHERE inv_customerproduct.customerreference IS NOT NULL and inv_mas_customer.customerid <> '' and inv_mas_scratchcard.cardid LIKE  '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and inv_mas_customer.branch in( select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."') ORDER BY inv_mas_customer.businessname";
					$result = runmysqlquery($query5);
					if(mysqli_num_rows($result) == 0)
					{
						$query5 = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname
from inv_mas_customer LEFT JOIN (inv_customerproduct LEFT JOIN inv_mas_scratchcard ON inv_customerproduct.cardid = inv_mas_scratchcard.cardid) ON  inv_mas_customer.slno = inv_customerproduct.customerreference 
left join inv_mas_product on left(inv_customerproduct.computerid,3) IN (".$productslist.")
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
WHERE inv_customerproduct.customerreference IS NOT NULL and inv_mas_customer.customerid <> '' and inv_mas_scratchcard.cardid LIKE  '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and inv_mas_customer.region = '2' ORDER BY inv_mas_customer.businessname";
						$result = runmysqlquery($query5);
					}
				}
				else
				{
					if($relyonexecutive == 'no')
					{
						$query5 = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname  from inv_mas_customer LEFT JOIN (inv_customerproduct LEFT JOIN inv_mas_scratchcard ON inv_customerproduct.cardid = inv_mas_scratchcard.cardid) ON  inv_mas_customer.slno = inv_customerproduct.customerreference 
left join inv_mas_product on left(inv_customerproduct.computerid,3) IN (".$productslist.")
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
WHERE inv_customerproduct.customerreference IS NOT NULL and inv_mas_customer.customerid <> '' and inv_mas_scratchcard.cardid LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and inv_mas_customer.currentdealer = '".$userid."'  ORDER BY inv_mas_customer.businessname";
						$result = runmysqlquery($query5);
					}
					else
					{
						if(($region == '1') || ($region == '3'))
						{
							$query5 = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname
from inv_mas_customer LEFT JOIN (inv_customerproduct LEFT JOIN inv_mas_scratchcard ON inv_customerproduct.cardid = inv_mas_scratchcard.cardid) ON  inv_mas_customer.slno = inv_customerproduct.customerreference 
left join inv_mas_product on left(inv_customerproduct.computerid,3) IN (".$productslist.")
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
WHERE inv_customerproduct.customerreference IS NOT NULL and inv_mas_customer.customerid <> '' and inv_mas_scratchcard.cardid LIKE  '%".$textfield."%' AND (inv_mas_customer.region = '1' or inv_mas_customer.region = '3') ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." ORDER BY inv_mas_customer.businessname";
							$result = runmysqlquery($query5);
						}
						else
						{
							$query5 = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname
from inv_mas_customer LEFT JOIN (inv_customerproduct LEFT JOIN inv_mas_scratchcard ON inv_customerproduct.cardid = inv_mas_scratchcard.cardid) ON  inv_mas_customer.slno = inv_customerproduct.customerreference 
left join inv_mas_product on left(inv_customerproduct.computerid,3) IN (".$productslist.")
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
WHERE inv_customerproduct.customerreference IS NOT NULL and inv_mas_customer.customerid <> '' and inv_mas_scratchcard.cardid LIKE  '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and inv_mas_customer.branch = '".$branch."' ORDER BY inv_mas_customer.businessname";
							$result = runmysqlquery($query5);
						}
					}
				}
			}
			break;
			case 'scratchnumber':
			{
				if($telecaller == 'yes')
				{
					$query6 = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname
from inv_mas_customer LEFT JOIN (inv_customerproduct LEFT JOIN inv_mas_scratchcard ON inv_customerproduct.cardid = inv_mas_scratchcard.cardid) ON  inv_mas_customer.slno = inv_customerproduct.customerreference 
left join inv_mas_product on left(inv_customerproduct.computerid,3) IN (".$productslist.")
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
WHERE inv_customerproduct.customerreference IS NOT NULL and inv_mas_customer.customerid <> '' and inv_mas_scratchcard.scratchnumber LIKE  '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and inv_mas_customer.branch in( select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."') ORDER BY inv_mas_customer.businessname";
					$result = runmysqlquery($query6);
					if(mysqli_num_rows($result) == 0)
					{
						$query6 = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname
from inv_mas_customer LEFT JOIN (inv_customerproduct LEFT JOIN inv_mas_scratchcard ON inv_customerproduct.cardid = inv_mas_scratchcard.cardid) ON  inv_mas_customer.slno = inv_customerproduct.customerreference 
left join inv_mas_product on left(inv_customerproduct.computerid,3) IN (".$productslist.")
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
	WHERE inv_customerproduct.customerreference IS NOT NULL and inv_mas_customer.customerid <> '' and inv_mas_scratchcard.scratchnumber LIKE  '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and inv_mas_customer.region = '2' ORDER BY inv_mas_customer.businessname";
						$result = runmysqlquery($query6);
					}
				}
				else
				{
					if($relyonexecutive == 'no')
					{
						$query6 = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname  from inv_mas_customer LEFT JOIN (inv_customerproduct LEFT JOIN inv_mas_scratchcard ON inv_customerproduct.cardid = inv_mas_scratchcard.cardid) ON  inv_mas_customer.slno = inv_customerproduct.customerreference 
left join inv_mas_product on left(inv_customerproduct.computerid,3) IN (".$productslist.")
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
WHERE inv_customerproduct.customerreference IS NOT NULL and inv_mas_customer.customerid <> '' and inv_mas_scratchcard.scratchnumber LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and inv_mas_customer.currentdealer = '".$userid."'  ORDER BY inv_mas_customer.businessname";
						$result = runmysqlquery($query6);
					}
					else
					{
						if(($region == '1') || ($region == '3'))
						{
							$query6 = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname
from inv_mas_customer LEFT JOIN (inv_customerproduct LEFT JOIN inv_mas_scratchcard ON inv_customerproduct.cardid = inv_mas_scratchcard.cardid) ON  inv_mas_customer.slno = inv_customerproduct.customerreference 
left join inv_mas_product on left(inv_customerproduct.computerid,3) IN (".$productslist.")
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
WHERE inv_customerproduct.customerreference IS NOT NULL and inv_mas_customer.customerid <> '' and inv_mas_scratchcard.scratchnumber LIKE  '%".$textfield."%' AND (inv_mas_customer.region = '1' or inv_mas_customer.region = '3') ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece."  ORDER BY inv_mas_customer.businessname";
							$result = runmysqlquery($query6);
						}
						else
						{
							$query6 = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname
from inv_mas_customer LEFT JOIN (inv_customerproduct LEFT JOIN inv_mas_scratchcard ON inv_customerproduct.cardid = inv_mas_scratchcard.cardid) ON  inv_mas_customer.slno = inv_customerproduct.customerreference 
left join inv_mas_product on left(inv_customerproduct.computerid,3) IN (".$productslist.")
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
WHERE inv_customerproduct.customerreference IS NOT NULL and inv_mas_customer.customerid <> '' and inv_mas_scratchcard.scratchnumber LIKE  '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and inv_mas_customer.branch = '".$branch."' ORDER BY inv_mas_customer.businessname";
							$result = runmysqlquery($query6);
						}
					}
				}
			}
				break;
			case 'computerid':
			{
				if($telecaller == 'yes')
				{
					$query7 = "select DISTINCT inv_mas_customer.slno AS slno, inv_mas_customer.businessname AS businessname from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region 
LEFT JOIN (select distinct customerreference,computerid from inv_customerproduct  where left(computerid,3)  IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_customerproduct.computerid LIKE  '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and inv_mas_customer.branch in( select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."') ORDER BY inv_mas_customer.businessname";
					$result = runmysqlquery($query7);
					if(mysqli_num_rows($result) == 0)
					{
						$query7 = "select DISTINCT inv_mas_customer.slno AS slno, inv_mas_customer.businessname AS businessname from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region 
LEFT JOIN (select distinct customerreference,computerid from inv_customerproduct  where left(computerid,3)  IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_customerproduct.computerid LIKE  '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and inv_mas_customer.region = '2' ORDER BY inv_mas_customer.businessname";
						$result = runmysqlquery($query7);
					}
				}
				else
				{
					if($relyonexecutive == 'no')
					{
						$query7 = "select DISTINCT inv_mas_customer.slno AS slno, inv_mas_customer.businessname AS businessname from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region 
LEFT JOIN (select distinct customerreference,computerid from inv_customerproduct  where left(computerid,3) not IN ('')) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_customerproduct.computerid LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and 
inv_mas_customer.currentdealer = '".$userid."'  ORDER BY inv_mas_customer.businessname";
						$result = runmysqlquery($query7);
					}
					else
					{
						if(($region == '1') || ($region == '3'))
						{
							$query7 = "select DISTINCT inv_mas_customer.slno AS slno, inv_mas_customer.businessname AS businessname from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region 
LEFT JOIN (select distinct customerreference,computerid from inv_customerproduct  where left(computerid,3)  IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_customerproduct.computerid LIKE  '%".$textfield."%' AND (inv_mas_customer.region = '1' or inv_mas_customer.region = '3') ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." ORDER BY inv_mas_customer.businessname";
							$result = runmysqlquery($query7);
						}
						else
						{
							$query7 = "select DISTINCT inv_mas_customer.slno AS slno, inv_mas_customer.businessname AS businessname from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region 
LEFT JOIN (select distinct customerreference,computerid from inv_customerproduct  where left(computerid,3)  IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_customerproduct.computerid LIKE  '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and inv_mas_customer.branch = '".$branch."' ORDER BY inv_mas_customer.businessname";
							$result = runmysqlquery($query7);
						}
					}
				}
			}
			break;
			case 'softkey':
			{
				if($telecaller == 'yes')
				{
					$query8 = "select DISTINCT inv_mas_customer.slno AS slno, inv_mas_customer.businessname AS businessname from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region 
LEFT JOIN (select distinct customerreference,softkey from inv_customerproduct  where left(computerid,3)  IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_customerproduct.softkey LIKE  '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and inv_mas_customer.branch in( select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."') ORDER BY inv_mas_customer.businessname";
					$result = runmysqlquery($query8);
					if(mysqli_num_rows($result) == 0)
					{
						$query8 = "select DISTINCT inv_mas_customer.slno AS slno, inv_mas_customer.businessname AS businessname from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region 
LEFT JOIN (select distinct customerreference,softkey from inv_customerproduct  where left(computerid,3)  IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_customerproduct.softkey LIKE  '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and inv_mas_customer.region = '2'  ORDER BY inv_mas_customer.businessname";
						$result = runmysqlquery($query8);
					}
				}
				else
				{
					if($relyonexecutive == 'no')
					{
						$query8 = "select DISTINCT inv_mas_customer.slno AS slno, inv_mas_customer.businessname AS businessname from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region 
LEFT JOIN (select distinct customerreference,softkey from inv_customerproduct  where left(computerid,3) not IN ('')) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_customerproduct.softkey LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and 
inv_mas_customer.currentdealer = '".$userid."'  ORDER BY inv_mas_customer.businessname";
						$result = runmysqlquery($query8);
					}
					else
					{
						if(($region == '1') || ($region == '3'))
						{
							$query8 = "select DISTINCT inv_mas_customer.slno AS slno, inv_mas_customer.businessname AS businessname from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region 
LEFT JOIN (select distinct customerreference,softkey from inv_customerproduct  where left(computerid,3)  IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_customerproduct.softkey LIKE  '%".$textfield."%' AND (inv_mas_customer.region = '1' or inv_mas_customer.region = '3') ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece."  ORDER BY inv_mas_customer.businessname";
							$result = runmysqlquery($query8);
						}
						else
						{
							$query8 = "select DISTINCT inv_mas_customer.slno AS slno, inv_mas_customer.businessname AS businessname from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region 
LEFT JOIN (select distinct customerreference,softkey from inv_customerproduct  where left(computerid,3)  IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_customerproduct.softkey LIKE  '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and inv_mas_customer.branch = '".$branch."'  ORDER BY inv_mas_customer.businessname";
							$result = runmysqlquery($query8);
						}
					}
				}
			}
			break;
			case 'billno':
			{
				if($telecaller == 'yes')
				{
					$query10 = "select DISTINCT inv_mas_customer.slno AS slno, inv_mas_customer.businessname AS businessname from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region 
LEFT JOIN (select distinct customerreference,billnumber from inv_customerproduct  where left(computerid,3)  IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_customerproduct.billnumber LIKE  '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and inv_mas_customer.branch in( select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."') ORDER BY inv_mas_customer.businessname";
						$result = runmysqlquery($query10);
						if(mysqli_num_rows($result) == 0)
						{
							$query10 = "select DISTINCT inv_mas_customer.slno AS slno, inv_mas_customer.businessname AS businessname from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region 
LEFT JOIN (select distinct customerreference,billnumber from inv_customerproduct  where left(computerid,3)  IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_customerproduct.billnumber LIKE  '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and inv_mas_customer.region = '2' ORDER BY inv_mas_customer.businessname";
							$result = runmysqlquery($query10);
						}
				}
				else
				{
					if($relyonexecutive == 'no')
					{
						$query10 = "select DISTINCT inv_mas_customer.slno AS slno, inv_mas_customer.businessname AS businessname from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region 
LEFT JOIN (select distinct customerreference,billnumber from inv_customerproduct  where left(computerid,3) not IN ('')) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_customerproduct.billnumber LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and 
		inv_mas_customer.currentdealer = '".$userid."'  ORDER BY inv_mas_customer.businessname";
						$result = runmysqlquery($query10);
					}
					else
					{
						if(($region == '1') || ($region == '3'))
						{
							$query10 = "select DISTINCT inv_mas_customer.slno AS slno, inv_mas_customer.businessname AS businessname from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region 
LEFT JOIN (select distinct customerreference,billnumber from inv_customerproduct  where left(computerid,3)  IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_customerproduct.billnumber LIKE  '%".$textfield."%' AND (inv_mas_customer.region = '1' or inv_mas_customer.region = '3') ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece."  ORDER BY inv_mas_customer.businessname";
							$result = runmysqlquery($query10);
						}
						else
						{
							$query10 = "select DISTINCT inv_mas_customer.slno AS slno, inv_mas_customer.businessname AS businessname from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region 
LEFT JOIN (select distinct customerreference,billnumber from inv_customerproduct  where left(computerid,3)  IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_customerproduct.billnumber LIKE  '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and inv_mas_customer.branch = '".$branch."' ORDER BY inv_mas_customer.businessname";
							$result = runmysqlquery($query10);
						}
					}
				}
			}
			break;
			default:
			{
				if($telecaller == 'yes')
				{
					$query11 = "select DISTINCT inv_mas_customer.slno AS slno, inv_mas_customer.businessname AS businessname from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
LEFT JOIN (select distinct customerreference from inv_customerproduct where left(computerid,3)  IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_mas_customer.businessname LIKE  '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and inv_mas_customer.branch in( select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."') ORDER BY inv_mas_customer.businessname";
					$result = runmysqlquery($query11);
					if(mysqli_num_rows($result) == 0)
					{
						$query11 = "select DISTINCT inv_mas_customer.slno AS slno, inv_mas_customer.businessname AS businessname from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
LEFT JOIN (select distinct customerreference from inv_customerproduct where left(computerid,3)  IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_mas_customer.businessname LIKE  '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and inv_mas_customer.region = '2' ORDER BY inv_mas_customer.businessname";
						$result = runmysqlquery($query11);
					}
				}
				else
				{
					if($relyonexecutive == 'no')
					{
						$query11 = "select DISTINCT inv_mas_customer.slno AS slno, inv_mas_customer.businessname AS businessname from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
LEFT JOIN (select distinct customerreference from inv_customerproduct where left(computerid,3)  IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_mas_customer.businessname LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and 
inv_mas_customer.currentdealer = '".$userid."'  ORDER BY inv_mas_customer.businessname";
						$result = runmysqlquery($query11);
					}
					else
					{
						if(($region == '1') || ($region == '3'))
						{
							$query11 = "select DISTINCT inv_mas_customer.slno AS slno, inv_mas_customer.businessname AS businessname from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
LEFT JOIN (select distinct customerreference from inv_customerproduct where left(computerid,3)  IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_mas_customer.businessname LIKE  '%".$textfield."%' AND (inv_mas_customer.region = '1' or inv_mas_customer.region = '3') ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece."   ORDER BY inv_mas_customer.businessname";
							$result = runmysqlquery($query11);
						}
						else
						{
							$query11 = "select DISTINCT inv_mas_customer.slno AS slno, inv_mas_customer.businessname AS businessname from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
LEFT JOIN (select distinct customerreference from inv_customerproduct where left(computerid,3)  IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_mas_customer.businessname LIKE  '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece." and inv_mas_customer.branch = '".$branch."' ORDER BY inv_mas_customer.businessname";
							$result = runmysqlquery($query11);
						}
					}
				}
			}
		}
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','116','".date('Y-m-d').' '.date('H:i:s')."','Advanced Search')";
		$eventresult = runmysqlquery($eventquery);
		$count = 0;
		$customersearcharray = array();
		while($fetch = mysqli_fetch_array($result))
		{
			$customersearcharray[$count] = $fetch['businessname'].'^'.$fetch['slno'];
			$count++;
			
		}
		echo(json_encode($customersearcharray));
	}
	break;
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
					//$query = "select slno as slno, businessname as businessname from inv_mas_customer where (inv_mas_customer.region != '1' and inv_mas_customer.region != '3') order by businessname LIMIT ".$startindex.",".$limit.";";
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
		$countquery = "SELECT * FROM inv_customerreqpending WHERE customerid = '".$lastslno."' AND requestfrom = 'dealer_module' and inv_customerreqpending.customerstatus ='pending';";
		$countresult = runmysqlquery($countquery);
		if(mysqli_num_rows($countresult) == 0)
		{
			$query1 = "SELECT count(*) as count from inv_mas_customer where slno = '".$lastslno."'";
			$fetch1 = runmysqlqueryfetch($query1);
			if($fetch1['count'] > 0)
			{
				$query = "SELECT inv_mas_customer.slno, inv_mas_customer.customerid, inv_mas_customer.businessname, inv_mas_customer.address, inv_mas_customer.place, inv_mas_customer.district,inv_mas_district.statecode as state, inv_mas_customer.pincode, inv_mas_customer.fax, inv_mas_customer.activecustomer, inv_mas_customer.region, inv_mas_customer.companyclosed, inv_mas_branch.branchname as branch, inv_mas_customer.stdcode, inv_mas_customer.website, inv_mas_customer.category, inv_mas_customer.type, inv_mas_customer.remarks, inv_mas_customer.currentdealer, inv_mas_customer.promotionalsms,inv_mas_customer.promotionalemail,inv_mas_customer.passwordchanged, inv_mas_customer.disablelogin, inv_mas_customer.corporateorder,inv_mas_customer.createddate, inv_mas_users.fullname, inv_mas_product.productname, inv_mas_district.districtname as districtname,inv_mas_state.statename as statename FROM inv_mas_customer LEFT JOIN inv_mas_product ON inv_mas_product.productcode = inv_mas_customer.firstproduct  LEFT JOIN inv_mas_users ON inv_mas_customer.createdby = inv_mas_users.slno left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode left join inv_mas_branch on inv_mas_branch.slno = inv_mas_district.branchid left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode  where inv_mas_customer.slno = '".$lastslno."';";
				$fetch = runmysqlqueryfetch($query);
				if($fetch['customerid'] == '')
					$customerid = '';
					else
					$customerid = cusidcombine($fetch['customerid']);
				if($fetch['currentdealer'] == '')
				$currentdealer = '';
				else
				{
					$query = "select * from inv_mas_dealer where slno = '".$fetch['currentdealer']."'";
					$resultfetch = runmysqlqueryfetch($query);
					$currentdealer = $resultfetch['businessname'];
					
				}
				
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
				
				$contactvalues .= $contactperson;
				$contactvalues .= appendcomma($contactperson);
				$phoneres .= $phone;
				$phoneres .= appendcomma($phone);
				$cellres .= $cell;
				$cellres .= appendcomma($cell);
				$emailidres .= $emailid;
				$emailidres .= appendcomma($emailid);
				
			}
			$rescontact = trim($contactvalues,',');
			$resphone = trim($phoneres,',');
			$rescell = trim($cellres,',');
			$resemailid = trim($emailidres,',');
			
			// 2011- 12 Summary
			
			$query2 = "select * from 
(select distinct inv_mas_product.group from inv_mas_product where inv_mas_product.group in ('TDS','SPP','STO','SVH','SVI','XBRL') and inv_mas_product.year = '".$currentyear."') as groups
left join
(select distinct inv_mas_product.group as bills from inv_invoicenumbers join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode where  right(inv_invoicenumbers.customerid,5) = '".$lastslno."' and inv_mas_product.year = '".$currentyear."') as bills
on bills.bills = groups.group
left join
(select distinct inv_mas_product.group as pins from inv_dealercard join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode where  inv_dealercard.customerreference = '".$lastslno."' and inv_mas_product.year = '".$currentyear."') as pins
on pins.pins = groups.group
left join
(select distinct inv_mas_product.group as registrations from inv_customerproduct join inv_mas_product on left(inv_customerproduct.computerid,3) = inv_mas_product.productcode where  inv_customerproduct.customerreference = '".$lastslno."' and inv_mas_product.year = '".$currentyear."') as registrations
on registrations.registrations = groups.group order by groups.group desc"; //echo($query2);exit;

			$result2 = runmysqlquery($query2);
			$grid .= '<table width="100%" border="0" cellspacing="0" cellpadding="4" class="table-border-grid">';
			$grid .= '<tr bgcolor = "#E2F1F1">';
			$grid .= '<td nowrap = "nowrap" class="grid-td-border" align="center" >&nbsp;</td>';
			$grid .= '<td nowrap = "nowrap" class="grid-td-border" align="center"><strong>Bill</strong></td>';
			$grid .= '<td nowrap = "nowrap" class="grid-td-border" align="center"><strong>PIN</strong></td>';
			$grid .= '<td nowrap = "nowrap" class="grid-td-border" align="center"><strong>Regn</strong>
			</td>';
  			$grid .= '</tr>';
			$i_n = 0;
			while($fetch2 = mysqli_fetch_array($result2))
			{
				$i_n++;
				$slno++;
				$color;
				if($i_n%2 == 0)
					$color = "#edf4ff";
				else
					$color = "#f7faff";
				
				$bills = ($fetch2['bills'] == '')?'NO':'YES';
				$pins = ($fetch2['pins'] == '')?'NO':'YES';
				$registrations = ($fetch2['registrations'] == '')?'NO':'YES';
				
				$billbgcolor = ($bills == 'YES')?'#c1ddb9':'#FFD9D9';
				$pinsbgcolor = ($pins == 'YES')?'#c1ddb9':'#FFD9D9';
				$registrationsbgcolor = ($registrations == 'YES')?'#c1ddb9':'#FFD9D9';
				
				
				$grid .= '<tr>';
				$grid .= '<td nowrap = "nowrap" class="grid-td-border" align="center"  bgcolor = "#E2F1F1"><strong>'.$fetch2['group'].'</strong></td>';
				$grid .= '<td nowrap = "nowrap" class="grid-td-border" align="center" bgcolor='.$billbgcolor.'>'.$bills.'</td>';
				$grid .= '<td nowrap = "nowrap" class="grid-td-border" align="center"  bgcolor='.$pinsbgcolor.'>'.$pins.'</td>';
				$grid .= '<td nowrap = "nowrap" class="grid-td-border" align="center"  bgcolor='.$registrationsbgcolor.'>'.$registrations.'</td>';
				$grid .= '</tr>';
			}
			$grid .= '</table>';
			
			
				
			$responsearray = array();
			$responsearray['cusslno'] = $fetch['slno'];
			$responsearray['customerid'] = $customerid;
			$responsearray['businessname'] = $fetch['businessname'];
			$responsearray['address'] = $fetch['address'];
			$responsearray['place'] = $fetch['place'];
			$responsearray['district'] = $fetch['district'];
			$responsearray['state'] = $fetch['state'];
			$responsearray['pincode'] = $fetch['pincode'];
			$responsearray['stdcode'] = $fetch['stdcode'];
			$responsearray['website'] = $fetch['website'];
			$responsearray['category'] = $fetch['category'];
			$responsearray['type'] = $fetch['type'];
			$responsearray['remarks'] = $fetch['remarks'];
			$responsearray['dealerbusinessname'] = $currentdealer;
			$responsearray['disablelogin'] = $fetch['disablelogin'];
			$responsearray['createddate'] = changedateformatwithtime($fetch['createddate']);
			$responsearray['corporateorder'] = strtolower($fetch['corporateorder']);
			$responsearray['fax'] = $fetch['fax'];
			$responsearray['userid'] = $userid;
			$responsearray['activecustomer'] = $fetch['activecustomer'];
			$responsearray['branch'] = $fetch['branch'];
			$responsearray['companyclosed'] = $fetch['companyclosed'];
			$responsearray['userid'] = $userid;
			$responsearray['districtname'] = $fetch['districtname'];
			$responsearray['statename'] = $fetch['statename'];
			$responsearray['currentdealer'] = $currentdealer;
			$responsearray['contactarray'] = $contactarray;
			$responsearray['rescontact'] = $rescontact;
			$responsearray['resphone'] = $resphone;
			$responsearray['rescell'] = $rescell;
			$responsearray['resemailid'] = $resemailid;
			$responsearray['fax'] = $fetch['fax'];
			$responsearray['promotionalsms'] = $fetch['promotionalsms'];
			$responsearray['promotionalemail'] = $fetch['promotionalemail'];
			$responsearray['grid'] = $grid;
			$responsearray['pendingrequestmsg'] = '';
			echo(json_encode($responsearray));
			}
			else
			{
				$responsearray3 = array();
				$responsearray3['cusslno'] = '';
				echo(json_encode($responsearray3));
			}
		}
		else
		{

		$query = "SELECT inv_customerreqpending.slno as refslno,inv_customerreqpending.customerid as tempid, inv_mas_customer.customerid, inv_customerreqpending.businessname, 
inv_customerreqpending.address, inv_customerreqpending.place,inv_mas_district.statecode as state, inv_customerreqpending.district, 
inv_customerreqpending.pincode, inv_customerreqpending.fax, inv_customerreqpending.stdcode, inv_customerreqpending.website, inv_customerreqpending.category, inv_customerreqpending.type,inv_mas_branch.branchname as branch,inv_customerreqpending.companyclosed,inv_customerreqpending.promotionalsms,inv_customerreqpending.promotionalemail,
 inv_customerreqpending.remarks, inv_mas_customer.currentdealer, inv_mas_customer.password, inv_mas_customer.passwordchanged, 
inv_mas_customer.disablelogin, inv_mas_customer.corporateorder,inv_mas_customer.activecustomer,inv_customerreqpending.createddate, inv_mas_users.fullname,inv_mas_product.productname, inv_mas_district.districtname as districtname,inv_mas_state.statename as statename FROM inv_customerreqpending LEFT JOIN inv_mas_customer on inv_mas_customer.slno = inv_customerreqpending.customerid
LEFT JOIN inv_mas_product ON inv_mas_product.productcode = inv_mas_customer.firstproduct 
 LEFT JOIN inv_mas_users ON inv_mas_customer.createdby = inv_mas_users.slno 
left join inv_mas_district on inv_customerreqpending.district = inv_mas_district.districtcode 
left join inv_mas_branch on inv_mas_branch.slno = inv_mas_district.branchid
left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
where inv_customerreqpending.customerid = '".$lastslno."'AND requestfrom = 'dealer_module' and inv_customerreqpending.customerstatus ='pending';";
				$fetch = runmysqlqueryfetch($query);
				
				$query1 ="SELECT refslno,customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactreqpending where refslno = '".$fetch['refslno']."' and editedtype = 'edit_type' ; ";
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
					$contactvalues .= $contactperson.',';
					$phoneres .= $phone.',';
					$cellres .= $cell.',';
					$emailidres .= $emailid.',';
					
				}
				$char1 = str_replace(',,',',',$contactvalues);
				$rescontact = trim($char1,',');
				
				$char2 = str_replace(',,',',',$phoneres);
				$resphone = trim($char2,',');
				
				$char3 = str_replace(',,',',',$cellres);
				$rescell = trim($char3,',');
				
				$char4 = str_replace(',,',',',$emailidres);
				$resemailid = trim($char4,',');
				
				if($fetch['customerid'] == '')
					$customerid = '';
					else
					$customerid = cusidcombine($fetch['customerid']);
				if($fetch['currentdealer'] == '')
					$currentdealer = '';
					else
					{
						$query = "select * from inv_mas_dealer where slno = '".$fetch['currentdealer']."'";
						$resultfetch = runmysqlqueryfetch($query);
						$currentdealer = $resultfetch['businessname'];
						
					}
				
				// 2011- 12 Summary
			
			$query2 = "select * from 
(select distinct inv_mas_product.group from inv_mas_product where inv_mas_product.group in ('TDS','SPP','STO','SVH','SVI') and inv_mas_product.year = '".$currentyear."') as groups
left join
(select distinct inv_mas_product.group as bills from inv_invoicenumbers join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode where  right(inv_invoicenumbers.customerid,5) = '".$lastslno."' and inv_mas_product.year = '".$currentyear."') as bills
on bills.bills = groups.group
left join
(select distinct inv_mas_product.group as pins from inv_dealercard join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode where  inv_dealercard.customerreference = '".$lastslno."' and inv_mas_product.year = '".$currentyear."') as pins
on pins.pins = groups.group
left join
(select distinct inv_mas_product.group as registrations from inv_customerproduct join inv_mas_product on left(inv_customerproduct.computerid,3) = inv_mas_product.productcode where  inv_customerproduct.customerreference = '".$lastslno."' and inv_mas_product.year = '".$currentyear."') as registrations
on registrations.registrations = groups.group order by groups.group desc"; //echo($query2);exit;

			$result2 = runmysqlquery($query2);
			$grid .= '<table width="100%" border="0" cellspacing="0" cellpadding="4" class="table-border-grid">';
			$grid .= '<tr bgcolor = "#E2F1F1">';
			$grid .= '<td nowrap = "nowrap" class="grid-td-border" align="center" >&nbsp;</td>';
			$grid .= '<td nowrap = "nowrap" class="grid-td-border" align="center"><strong>Bill</strong></td>';
			$grid .= '<td nowrap = "nowrap" class="grid-td-border" align="center"><strong>PIN</strong></td>';
			$grid .= '<td nowrap = "nowrap" class="grid-td-border" align="center"><strong>Regn</strong>
			</td>';
  			$grid .= '</tr>';
			$i_n = 0;
			while($fetch2 = mysqli_fetch_array($result2))
			{
				$i_n++;
				$slno++;
				$color;
				if($i_n%2 == 0)
					$color = "#edf4ff";
				else
					$color = "#f7faff";
				
				$bills = ($fetch2['bills'] == '')?'NO':'YES';
				$pins = ($fetch2['pins'] == '')?'NO':'YES';
				$registrations = ($fetch2['registrations'] == '')?'NO':'YES';
				
				$billbgcolor = ($bills == 'YES')?'#c1ddb9':'#FFD9D9';
				$pinsbgcolor = ($pins == 'YES')?'#c1ddb9':'#FFD9D9';
				$registrationsbgcolor = ($registrations == 'YES')?'#c1ddb9':'#FFD9D9';
				
				
				$grid .= '<tr>';
				$grid .= '<td nowrap = "nowrap" class="grid-td-border" align="center"  bgcolor = "#E2F1F1"><strong>'.$fetch2['group'].'</strong></td>';
				$grid .= '<td nowrap = "nowrap" class="grid-td-border" align="center" bgcolor='.$billbgcolor.'>'.$bills.'</td>';
				$grid .= '<td nowrap = "nowrap" class="grid-td-border" align="center"  bgcolor='.$pinsbgcolor.'>'.$pins.'</td>';
				$grid .= '<td nowrap = "nowrap" class="grid-td-border" align="center"  bgcolor='.$registrationsbgcolor.'>'.$registrations.'</td>';
				$grid .= '</tr>';
			}
			$grid .= '</table>';
			
			$responsearray = array();
			$responsearray['cusslno'] = $fetch['tempid'];
			$responsearray['customerid'] = $customerid;
			$responsearray['businessname'] = $fetch['businessname'];
			$responsearray['address'] = $fetch['address'];
			$responsearray['place'] = $fetch['place'];
			$responsearray['district'] = $fetch['district'];
			$responsearray['state'] = $fetch['state'];
			$responsearray['pincode'] = $fetch['pincode'];
			$responsearray['stdcode'] = $fetch['stdcode'];
			$responsearray['website'] = $fetch['website'];
			$responsearray['category'] = $fetch['category'];
			$responsearray['type'] = $fetch['type'];
			$responsearray['remarks'] = $fetch['remarks'];
			$responsearray['dealerbusinessname'] = $currentdealer;
			$responsearray['disablelogin'] = $fetch['disablelogin'];
			$responsearray['createddate'] = changedateformatwithtime($fetch['createddate']);
			$responsearray['corporateorder'] = strtolower($fetch['corporateorder']);
			$responsearray['fax'] = $fetch['fax'];
			$responsearray['userid'] = $userid;
			$responsearray['activecustomer'] = $fetch['activecustomer'];
			$responsearray['branch'] = $fetch['branch'];
			$responsearray['companyclosed'] = $fetch['companyclosed'];
			$responsearray['userid'] = $userid;
			$responsearray['districtname'] = $fetch['districtname'];
			$responsearray['statename'] = $fetch['statename'];
			$responsearray['currentdealer'] = $currentdealer;
			$responsearray['contactarray'] = $contactarray;
			$responsearray['rescontact'] = $rescontact;
			$responsearray['resphone'] = $resphone;
			$responsearray['rescell'] = $rescell;
			$responsearray['resemailid'] = $resemailid;
			$responsearray['fax'] = $fetch['fax'];
			$responsearray['promotionalsms'] = $fetch['promotionalsms'];
			$responsearray['promotionalemail'] = $fetch['promotionalemail'];
			$responsearray['grid'] = $grid;
			$responsearray['pendingrequestmsg'] = 'Your Profile request for update is Pending for Approval';
			echo(json_encode($responsearray));
	
		}
	}
	break;
	
	case 'searchbycustomerid':
	{
		$customerid = $_POST['customerid'];
		$customeridlen = strlen($customerid);
		$lastslno = substr($customerid, $customeridlen - 5);
		$customerid = ($_POST['customerid'] == 5)?($_POST['customerid']):(substr($customerid, $customeridlen - 5));
		
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
				$dealerpiece = " AND inv_mas_customer.region = '2' ";
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
		$query1 = "SELECT count(*) as count from inv_mas_customer left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode where inv_mas_customer.slno = '".$customerid."'".$dealerpiece."";
		$fetch1 = runmysqlqueryfetch($query1);
		$countquery = "SELECT * FROM inv_customerreqpending WHERE customerid = '".$customerid."' AND requestfrom = 'dealer_module' and inv_customerreqpending.customerstatus ='pending';";
		$countresult = runmysqlquery($countquery);
		if(mysqli_num_rows($countresult) == 0)
		{
			
			if($fetch1['count'] > 0)
			{
				$query = "SELECT inv_mas_customer.slno, inv_mas_customer.customerid, inv_mas_customer.businessname,  inv_mas_customer.address, inv_mas_customer.place, inv_mas_customer.district,inv_mas_district.statecode as state, inv_mas_customer.pincode, inv_mas_customer.fax, inv_mas_customer.activecustomer, inv_mas_customer.region, inv_mas_customer.companyclosed, inv_mas_branch.branchname as branch, inv_mas_customer.stdcode,inv_mas_customer.website, inv_mas_customer.category, inv_mas_customer.type, inv_mas_customer.remarks, inv_mas_customer.currentdealer,  inv_mas_customer.promotionalsms, inv_mas_customer.promotionalemail,inv_mas_customer.passwordchanged, inv_mas_customer.disablelogin, inv_mas_customer.corporateorder,inv_mas_customer.createddate, inv_mas_users.fullname, inv_mas_product.productname, inv_mas_district.districtname as districtname,inv_mas_state.statename as statename FROM inv_mas_customer LEFT JOIN inv_mas_product ON inv_mas_product.productcode = inv_mas_customer.firstproduct  LEFT JOIN inv_mas_users ON inv_mas_customer.createdby = inv_mas_users.slno left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode left join inv_mas_branch on inv_mas_branch.slno = inv_mas_district.branchid left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode  where inv_mas_customer.slno = '".$customerid."'".$dealerpiece."";
				$fetch = runmysqlqueryfetch($query);
				$customerid = ($fetch['customerid'] == '')?'':cusidcombine($fetch['customerid']);	if($fetch['currentdealer'] == '')
				$currentdealer = '';
				else
				{
					$query = "select * from inv_mas_dealer where slno = '".$fetch['currentdealer']."'";
					$resultfetch = runmysqlqueryfetch($query);
					$currentdealer = $resultfetch['businessname'];
					
				}
				$query1 ="SELECT slno,customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactdetails where customerid = '".$fetch['slno']."'; ";
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
				
				$contactvalues .= $contactperson;
				$contactvalues .= appendcomma($contactperson);
				$phoneres .= $phone;
				$phoneres .= appendcomma($phone);
				$cellres .= $cell;
				$cellres .= appendcomma($cell);
				$emailidres .= $emailid;
				$emailidres .= appendcomma($emailid);
				
			}
			$rescontact = trim($contactvalues,',');
			$resphone = trim($phoneres,',');
			$rescell = trim($cellres,',');
			$resemailid = trim($emailidres,',');
			
			// 2011- 12 Summary
			
			$query2 = "select * from 
(select distinct inv_mas_product.group from inv_mas_product where inv_mas_product.group in ('TDS','SPP','STO','SVH','SVI') and inv_mas_product.year = '".$currentyear."') as groups
left join
(select distinct inv_mas_product.group as bills from inv_invoicenumbers join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode where  right(inv_invoicenumbers.customerid,5) = '".$lastslno."' and inv_mas_product.year = '".$currentyear."') as bills
on bills.bills = groups.group
left join
(select distinct inv_mas_product.group as pins from inv_dealercard join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode where  inv_dealercard.customerreference = '".$lastslno."' and inv_mas_product.year = '".$currentyear."') as pins
on pins.pins = groups.group
left join
(select distinct inv_mas_product.group as registrations from inv_customerproduct join inv_mas_product on left(inv_customerproduct.computerid,3) = inv_mas_product.productcode where  inv_customerproduct.customerreference = '".$lastslno."' and inv_mas_product.year = '".$currentyear."') as registrations
on registrations.registrations = groups.group order by groups.group desc"; //echo($query2);exit;

			$result2 = runmysqlquery($query2);
			$grid .= '<table width="100%" border="0" cellspacing="0" cellpadding="4" class="table-border-grid">';
			$grid .= '<tr bgcolor = "#E2F1F1">';
			$grid .= '<td nowrap = "nowrap" class="grid-td-border" align="center" >&nbsp;</td>';
			$grid .= '<td nowrap = "nowrap" class="grid-td-border" align="center"><strong>Bill</strong></td>';
			$grid .= '<td nowrap = "nowrap" class="grid-td-border" align="center"><strong>PIN</strong></td>';
			$grid .= '<td nowrap = "nowrap" class="grid-td-border" align="center"><strong>Regn</strong>
			</td>';
  			$grid .= '</tr>';
			$i_n = 0;
			while($fetch2 = mysqli_fetch_array($result2))
			{
				$i_n++;
				$slno++;
				$color;
				if($i_n%2 == 0)
					$color = "#edf4ff";
				else
					$color = "#f7faff";
				
				$bills = ($fetch2['bills'] == '')?'NO':'YES';
				$pins = ($fetch2['pins'] == '')?'NO':'YES';
				$registrations = ($fetch2['registrations'] == '')?'NO':'YES';
				
				$billbgcolor = ($bills == 'YES')?'#c1ddb9':'#FFD9D9';
				$pinsbgcolor = ($pins == 'YES')?'#c1ddb9':'#FFD9D9';
				$registrationsbgcolor = ($registrations == 'YES')?'#c1ddb9':'#FFD9D9';
				
				
				$grid .= '<tr>';
				$grid .= '<td nowrap = "nowrap" class="grid-td-border" align="center"  bgcolor = "#E2F1F1"><strong>'.$fetch2['group'].'</strong></td>';
				$grid .= '<td nowrap = "nowrap" class="grid-td-border" align="center" bgcolor='.$billbgcolor.'>'.$bills.'</td>';
				$grid .= '<td nowrap = "nowrap" class="grid-td-border" align="center"  bgcolor='.$pinsbgcolor.'>'.$pins.'</td>';
				$grid .= '<td nowrap = "nowrap" class="grid-td-border" align="center"  bgcolor='.$registrationsbgcolor.'>'.$registrations.'</td>';
				$grid .= '</tr>';
			}
			$grid .= '</table>';	
				
				$responsecusidarray = array();
				$responsecusidarray['cusslno'] = $fetch['slno'];
				$responsecusidarray['customerid'] = $customerid;
				$responsecusidarray['businessname'] = $fetch['businessname'];
				$responsecusidarray['address'] = $fetch['address'];
				$responsecusidarray['place'] = $fetch['place'];
				$responsecusidarray['district'] = $fetch['district'];
				$responsecusidarray['state'] = $fetch['state'];
				$responsecusidarray['pincode'] = $fetch['pincode'];
				$responsecusidarray['stdcode'] = $fetch['stdcode'];
				$responsecusidarray['website'] = $fetch['website'];
				$responsecusidarray['category'] = $fetch['category'];
				$responsecusidarray['type'] = $fetch['type'];
				$responsecusidarray['remarks'] = $fetch['remarks'];
				$responsecusidarray['dealerbusinessname'] = $currentdealer;
				$responsecusidarray['disablelogin'] = $fetch['disablelogin'];
				$responsecusidarray['createddate'] = changedateformatwithtime($fetch['createddate']);
				$responsecusidarray['corporateorder'] = strtolower($fetch['corporateorder']);
				$responsecusidarray['fax'] = $fetch['fax'];
				$responsecusidarray['userid'] = $userid;
				$responsecusidarray['activecustomer'] = $fetch['activecustomer'];
				$responsecusidarray['branch'] = $fetch['branch'];
				$responsecusidarray['companyclosed'] = $fetch['companyclosed'];
				$responsecusidarray['userid'] = $userid;
				$responsecusidarray['districtname'] = $fetch['districtname'];
				$responsecusidarray['statename'] = $fetch['statename'];
				$responsecusidarray['currentdealer'] = $currentdealer;
				$responsecusidarray['contactarray'] = $contactarray;
				$responsecusidarray['rescontact'] = $rescontact;
				$responsecusidarray['resphone'] = $resphone;
				$responsecusidarray['rescell'] = $rescell;
				$responsecusidarray['resemailid'] = $resemailid;
				$responsecusidarray['fax'] = $fetch['fax'];
				$responsecusidarray['promotionalsms'] = $fetch['promotionalsms'];
				$responsecusidarray['promotionalemail'] = $fetch['promotionalemail'];
				$responsecusidarray['grid'] = $grid;
				$responsecusidarray['pendingrequestmsg'] = '';
				echo(json_encode($responsecusidarray));
			}
			else
			{
				$responsearray5 = array();
				$responsearray5['cusslno'] = '';
				echo(json_encode($responsearray5));
			}
		}
		else
		{
			$query = "SELECT inv_customerreqpending.slno as refslno,inv_customerreqpending.customerid as tempid, inv_mas_customer.customerid, inv_customerreqpending.businessname, inv_customerreqpending.address, inv_customerreqpending.place,inv_mas_district.statecode as state, inv_customerreqpending.district, 
inv_customerreqpending.pincode, inv_customerreqpending.fax, inv_customerreqpending.stdcode, inv_customerreqpending.website, inv_customerreqpending.category, inv_customerreqpending.type,inv_mas_branch.branchname as branch,inv_customerreqpending.companyclosed,inv_customerreqpending.promotionalsms,inv_customerreqpending.promotionalemail,
 inv_customerreqpending.remarks, inv_mas_customer.currentdealer, inv_mas_customer.password, inv_mas_customer.passwordchanged, 
inv_mas_customer.disablelogin, inv_mas_customer.corporateorder,inv_mas_customer.activecustomer,inv_customerreqpending.createddate, inv_mas_users.fullname,inv_mas_product.productname, inv_mas_district.districtname as districtname,inv_mas_state.statename as statename FROM inv_customerreqpending LEFT JOIN inv_mas_customer on inv_mas_customer.slno = inv_customerreqpending.customerid
LEFT JOIN inv_mas_product ON inv_mas_product.productcode = inv_mas_customer.firstproduct 
 LEFT JOIN inv_mas_users ON inv_mas_customer.createdby = inv_mas_users.slno 
left join inv_mas_district on inv_customerreqpending.district = inv_mas_district.districtcode 
left join inv_mas_branch on inv_mas_branch.slno = inv_mas_district.branchid
left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
where inv_customerreqpending.customerid = '".$customerid."'AND requestfrom = 'dealer_module' and inv_customerreqpending.customerstatus ='pending'".$dealerpiece.";";
				$fetch = runmysqlqueryfetch($query);
				
				$query1 ="SELECT slno,customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactreqpending where refslno = '".$fetch['refslno']."' and editedtype = 'edit_type'; ";
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
					$contactvalues .= $contactperson;
					$contactvalues .= appendcomma($contactperson);
					$phoneres .= $phone;
					$phoneres .= appendcomma($phone);
					$cellres .= $cell;
					$cellres .= appendcomma($cell);
					$emailidres .= $emailid;
					$emailidres .= appendcomma($emailid);
				}
				$rescontact = trim($contactvalues,',');
				$resphone = trim($phoneres,',');
				$rescell = trim($cellres,',');
				$resemailid = trim($emailidres,',');
				
				if($fetch['customerid'] == '')
					$customerid = '';
				else
					$customerid = cusidcombine($fetch['customerid']);
				if($fetch['currentdealer'] == '')
					$currentdealer = '';
				else
				{
					$query = "select * from inv_mas_dealer where slno = '".$fetch['currentdealer']."'";
					$resultfetch = runmysqlqueryfetch($query);
					$currentdealer = $resultfetch['businessname'];
				}
				// 2011- 12 Summary
			
			$query2 = "select * from 
(select distinct inv_mas_product.group from inv_mas_product where inv_mas_product.group in ('TDS','SPP','STO','SVH','SVI') and inv_mas_product.year = '".$currentyear."') as groups
left join
(select distinct inv_mas_product.group as bills from inv_invoicenumbers join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode where  right(inv_invoicenumbers.customerid,5) = '".$lastslno."' and inv_mas_product.year = '".$currentyear."') as bills
on bills.bills = groups.group
left join
(select distinct inv_mas_product.group as pins from inv_dealercard join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode where  inv_dealercard.customerreference = '".$lastslno."' and inv_mas_product.year = '".$currentyear."') as pins
on pins.pins = groups.group
left join
(select distinct inv_mas_product.group as registrations from inv_customerproduct join inv_mas_product on left(inv_customerproduct.computerid,3) = inv_mas_product.productcode where  inv_customerproduct.customerreference = '".$lastslno."' and inv_mas_product.year = '".$currentyear."') as registrations
on registrations.registrations = groups.group order by groups.group desc"; //echo($query2);exit;

			$result2 = runmysqlquery($query2);
			$grid .= '<table width="100%" border="0" cellspacing="0" cellpadding="4" class="table-border-grid">';
			$grid .= '<tr bgcolor = "#E2F1F1">';
			$grid .= '<td nowrap = "nowrap" class="grid-td-border" align="center" >&nbsp;</td>';
			$grid .= '<td nowrap = "nowrap" class="grid-td-border" align="center"><strong>Bill</strong></td>';
			$grid .= '<td nowrap = "nowrap" class="grid-td-border" align="center"><strong>PIN</strong></td>';
			$grid .= '<td nowrap = "nowrap" class="grid-td-border" align="center"><strong>Regn</strong>
			</td>';
  			$grid .= '</tr>';
			$i_n = 0;
			while($fetch2 = mysqli_fetch_array($result2))
			{
				$i_n++;
				$slno++;
				$color;
				if($i_n%2 == 0)
					$color = "#edf4ff";
				else
					$color = "#f7faff";
				
				$bills = ($fetch2['bills'] == '')?'NO':'YES';
				$pins = ($fetch2['pins'] == '')?'NO':'YES';
				$registrations = ($fetch2['registrations'] == '')?'NO':'YES';
				
				$billbgcolor = ($bills == 'YES')?'#c1ddb9':'#FFD9D9';
				$pinsbgcolor = ($pins == 'YES')?'#c1ddb9':'#FFD9D9';
				$registrationsbgcolor = ($registrations == 'YES')?'#c1ddb9':'#FFD9D9';
				
				
				$grid .= '<tr>';
				$grid .= '<td nowrap = "nowrap" class="grid-td-border" align="center"  bgcolor = "#E2F1F1"><strong>'.$fetch2['group'].'</strong></td>';
				$grid .= '<td nowrap = "nowrap" class="grid-td-border" align="center" bgcolor='.$billbgcolor.'>'.$bills.'</td>';
				$grid .= '<td nowrap = "nowrap" class="grid-td-border" align="center"  bgcolor='.$pinsbgcolor.'>'.$pins.'</td>';
				$grid .= '<td nowrap = "nowrap" class="grid-td-border" align="center"  bgcolor='.$registrationsbgcolor.'>'.$registrations.'</td>';
				$grid .= '</tr>';
			}
			$grid .= '</table>';	
				
			$responsearray = array();
			$responsearray['cusslno'] = $fetch['tempid'];
			$responsearray['customerid'] = $customerid;
			$responsearray['businessname'] = $fetch['businessname'];
			$responsearray['address'] = $fetch['address'];
			$responsearray['place'] = $fetch['place'];
			$responsearray['district'] = $fetch['district'];
			$responsearray['state'] = $fetch['state'];
			$responsearray['pincode'] = $fetch['pincode'];
			$responsearray['stdcode'] = $fetch['stdcode'];
			$responsearray['website'] = $fetch['website'];
			$responsearray['category'] = $fetch['category'];
			$responsearray['type'] = $fetch['type'];
			$responsearray['remarks'] = $fetch['remarks'];
			$responsearray['dealerbusinessname'] = $currentdealer;
			$responsearray['disablelogin'] = $fetch['disablelogin'];
			$responsearray['createddate'] = changedateformatwithtime($fetch['createddate']);
			$responsearray['corporateorder'] = strtolower($fetch['corporateorder']);
			$responsearray['fax'] = $fetch['fax'];
			$responsearray['userid'] = $userid;
			$responsearray['activecustomer'] = $fetch['activecustomer'];
			$responsearray['branch'] = $fetch['branch'];
			$responsearray['companyclosed'] = $fetch['companyclosed'];
			$responsearray['userid'] = $userid;
			$responsearray['districtname'] = $fetch['districtname'];
			$responsearray['statename'] = $fetch['statename'];
			$responsearray['currentdealer'] = $currentdealer;
			$responsearray['contactarray'] = $contactarray;
			$responsearray['rescontact'] = $rescontact;
			$responsearray['resphone'] = $resphone;
			$responsearray['rescell'] = $rescell;
			$responsearray['resemailid'] = $resemailid;
			$responsearray['fax'] = $fetch['fax'];
			$responsearray['promotionalsms'] = $fetch['promotionalsms'];
			$responsearray['promotionalemail'] = $fetch['promotionalemail'];
			$responsearray['grid'] = $grid;
			$responsearray['pendingrequestmsg'] = 'Your Profile request for update is Pending for Approval';
			echo(json_encode($responsearray));
		}
	}
	break;
	case 'customerregistration':
	{
		$startlimit = $_POST['startlimit'];
		$slno = $_POST['slno'];
		$showtype = $_POST['showtype'];
		$resultcount = "SELECT inv_mas_product.productname as productname FROM inv_customerproduct left join inv_mas_product on left(inv_customerproduct.computerid, 3) = inv_mas_product.productcode left join inv_mas_users on inv_customerproduct.generatedby = inv_mas_users.slno left join inv_mas_dealer on inv_customerproduct.dealerid = inv_mas_dealer.slno  where customerreference = '".$lastslno."' order by `date`  desc,`time` desc ; ";
		$resultfetch = runmysqlquery($resultcount);
		$fetchresultcount = mysqli_num_rows($resultfetch);
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
		
		$query = "SELECT inv_mas_product.productname as productname,getPINNo(inv_customerproduct.cardid) AS cardid, 
inv_customerproduct.computerid AS computerid,inv_customerproduct.softkey AS softkey,inv_customerproduct.date AS regdate, 
inv_customerproduct.time AS regtime, inv_mas_users.fullname AS generatedby, inv_mas_dealer.businessname AS businessname, 
inv_customerproduct.billnumber as Billnum,inv_customerproduct.billamount as billamount,inv_customerproduct.remarks as remarks 
FROM inv_customerproduct 
left join inv_mas_product on left(inv_customerproduct.computerid, 3) = inv_mas_product.productcode 
left join inv_mas_users on inv_customerproduct.generatedby = inv_mas_users.slno 
left join inv_mas_dealer on inv_customerproduct.dealerid = inv_mas_dealer.slno  where customerreference = '".$lastslno."' order by `date`  desc,`time` desc LIMIT ".$startlimit.",".$limit."; ";

		$result = runmysqlquery($query);
		if($startlimit == 0)
		{
		$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
		$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">PIN Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">Computer ID</td><td nowrap = "nowrap" class="td-border-grid" align="left">Soft Key</td><td nowrap = "nowrap" class="td-border-grid" align="left">Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Time</td><td nowrap = "nowrap" class="td-border-grid" align="left">Generatd By</td><td nowrap = "nowrap" class="td-border-grid" align="left">Dealer</td><td nowrap = "nowrap" class="td-border-grid" align="left">Bill No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Bill Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Remarks</td></tr>';
		}
		
		$i_n = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$i_n++;
			$slno++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$grid .= '<tr class="gridrow1" bgcolor='.$color.' align="left">';
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left' >".$slno."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['productname']."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['cardid']."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['computerid']."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['softkey']."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['regdate']."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['regtime']."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['generatedby']."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['businessname']."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['Billnum']."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['billamount']."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['remarks']."</td>";
			$grid .= "</tr>";
		}
		$grid .= "</table>";
 
		$fetchcount = mysqli_num_rows($result);
		if($slno >= $fetchresultcount)
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"  ><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font></td></tr></table>';
		else
			$linkgrid .= '<table><tr><td align ="left"><div align ="left" style="padding-right:10px">&nbsp;&nbsp;&nbsp;<a onclick ="getmorecustomerregistration(\''.$lastslno.'\',\''.$startlimit.'\',\''.$slno.'\',\'more\');" class="resendtext" style="cursor:pointer;">Show More Records >></a> &nbsp;&nbsp;&nbsp;<a onclick ="getmorecustomerregistration(\''.$lastslno.'\',\''.$startlimit.'\',\''.$slno.'\',\'all\');" class="resendtext1"><font color= "#000000">(Show All Records)</font></a></div></td></tr></table>';
			
		$responsearray2 = array();
		$responsearray2['errorcode'] = '1';
		$responsearray2['grid'] = $grid;
		$responsearray2['count'] = $fetchresultcount;
		$responsearray2['linkgrid'] = $linkgrid;
		echo(json_encode($responsearray2));
		
		
	}
	break;
	case 'generatesoftkey':
	{
		$errorfound = array();
		$registrationtype = $_POST['registrationtype'];
		switch($registrationtype)
		{
			case 'newlicence':
			{
				$customerid = $_POST['customerid'];
				$cardid = $_POST['scratchnumber'];
				$delaerrep = $_POST['delaerrep']; 
				$productcode = $_POST['productcode'];
				$productname = $_POST['productname'];
				$computerid = $_POST['computerid'];
				$billno = $_POST['billno'];
				$billamount = $_POST['billamount'];
				$regremarks = $_POST['regremarks'];
				$usagetype = $_POST['usagetype'];
				$purchasetype = $_POST['purchasetype'];
				$date = datetimelocal('d-m-Y');
				$systemip = '';
				if($usagetype == 'multiuser')
					$usagetypecode = '09';
				elseif($usagetype == 'singleuser')
					$usagetypecode = '00';
				elseif($usagetype == 'additionallicense')
					$usagetypecode = '00';

				$computeridedition = substr($computerid, 0, 3);
				$computeridusagetype = substr($computerid, 3, 2);
				$computeridformat = substr($computerid, 5, 1);
				$computeridlength = strlen($computerid);
				if($computeridlength <> 15)
				{ echo(json_encode("2^"."Computer Id format is not valid.")); }
				elseif($computeridedition <> $productcode)
				{ echo(json_encode("2^"."Computer Id is not matching with the product you have selected.")); }
				elseif($computeridusagetype <> $usagetypecode)
				{ echo(json_encode("2^"."Computer Id is not matching with the purchase type.")); }
				elseif($computeridformat <> '-')
				{ echo(json_encode("2^"."Computer Id format is not valid.")); }
				
				else
				{
					$errorfound = "";
					//Card Number present in Database
					
					if($errorfound = "")
					{
						$query ="select * from inv_mas_scratchcard where cardid = '".$cardid."' and attached = 'yes' and blocked = 'no'";
						$result = runmysqlquery($query);
						if(mysqli_num_rows($result) == 0)
						$errorfound = '2 ^Invalid pin';
					}
					if($errorfound == "")
					{
						//Card No is not registered
						$query ="select * from inv_mas_scratchcard where cardid = '".$cardid."' and registered = 'no'";
						$result = runmysqlquery($query);
						if(mysqli_num_rows($result) == 0)
						$errorfound = '2^ This pin is already registered';
					}
					if($errorfound == "")
					{
						//Product code is the same, attached with Card
						$query ="select inv_mas_scratchcard.scratchnumber, inv_dealercard.dealerid, inv_dealercard.scheme as cardscheme from inv_mas_scratchcard left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid where inv_mas_scratchcard.cardid = '".$cardid."' and inv_dealercard.productcode = '".$productcode."'";
						$result = runmysqlquery($query);
						if(mysqli_num_rows($result) == 0)
						$errorfound = '2^ This Card belongs to a different Product.';
						//Read the card ID, dealer ID 
						else
						{
							$fetch = mysqli_fetch_array($result);
							$scratchnumber = $fetch['scratchnumber'];
							$cardscheme = $fetch['cardscheme'];
						}
					}
					if($errorfound == "")
					{
						//Usage type is the same, attached with Card
						$query ="select inv_mas_scratchcard.cardid as cradid from inv_mas_scratchcard left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid where inv_mas_scratchcard.cardid = '".$cardid."' and inv_dealercard.productcode = '".$productcode."'";
						$result = runmysqlqueryfetch($query);
						if($computeridusagetype == '00')
						{
							$query ="select * from inv_mas_scratchcard left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid where inv_mas_scratchcard.cardid = '".$cardid."' and (inv_dealercard.usagetype = 'singleuser' OR inv_dealercard.usagetype = 'additionallicense')";
							$result = runmysqlquery($query);
							if(mysqli_num_rows($result) == 0)
							$errorfound = '2^ This Card belongs to a Multiuser.';
						}
						elseif($computeridusagetype == '09')
						{
							$query ="select * from inv_mas_scratchcard left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid where inv_mas_scratchcard.cardid = '".$cardid."' and (inv_dealercard.usagetype = 'multiuser')";
							$result = runmysqlquery($query);
							if(mysqli_num_rows($result) == 0)
							$errorfound = '2^ This Card belongs to Single User or Additonal License.';
						}

						else
							$errorfound = '2^ The usage type of computer ID is invalid.'.$computeridusagetype;
					}
					if($errorfound == "")
					{
						$query = "SELECT `type` as prdtype,productname as productname FROM inv_mas_product WHERE productcode = '".$productcode."'";
						$fetch = runmysqlqueryfetch($query);
						$prdtype = $fetch['prdtype'];
						$productname = $fetch['productname'];
						$softkey  = generateserial($computerid, $prdtype);
						//$softkey  = generatesoftkeydummy($computerid, $prdtype);
						$query = "UPDATE inv_mas_scratchcard SET registered = 'yes', blocked = 'no', online = 'no', cancelled = 'no' WHERE cardid = '".$cardid."';";
						$result = runmysqlquery($query);
						//If this is the first registration for this customer, generate customerid, update it to customer master and send welcome email
						$sendcustomeridpassword = "";
						$query22 = "Select * from inv_mas_customer where slno ='".$customerid."' and customerid = ''; ";
						$fetchresult = runmysqlquery($query22);
						if(mysqli_num_rows($fetchresult) <> 0)
						{
							$query1 = "Select * from inv_customerproduct where customerreference = '".$customerid."'";
							$result = runmysqlquery($query1);
							if(mysqli_num_rows($result) == 0)
							{
								$newcustomerid = generatecustomerid($customerid,$productcode,$delaerrep);
								$password = generatepwd();
								$query14 = "UPDATE inv_mas_customer SET customerid = '".$newcustomerid."',loginpassword = AES_ENCRYPT('".$password."','imaxpasswordkey'),initialpassword = '".$password."',firstdealer = '".$delaerrep."',firstproduct ='".$productcode."',passwordchanged = 'N' WHERE slno = '".$customerid."'";
								$result = runmysqlquery($query14); 
								$sendcustomeridpassword = cusidcombine($newcustomerid)."%".$password;
								sendwelcomeemail($customerid,$userid);
							}
						}
						//Find the next slno to be inserted
						$query = "SELECT (MAX(slno) + 1) AS newslno FROM inv_customerproduct";
						$fetch = runmysqlqueryfetch($query);
						$customerproductslno = $fetch['newslno'];
						$query = "INSERT INTO inv_customerproduct(slno,customerreference,cardid,computerid,softkey,cusbillnumber,billnumber,billamount,dealerid,generatedby,system,date,time,remarks,reregistration,`type`,module) VALUES('".$customerproductslno."','".$customerid."','".$cardid."','".$computerid."','".$softkey."',(SELECT cusbillnumber from inv_dealercard where cardid = '".$cardid."'),'".$billno."','".$billamount."','".$delaerrep."','2','".$_SERVER['REMOTE_ADDR']."','".datetimelocal('Y-m-d')."','".datetimelocal('H:i:s')."','".$regremarks."','no','newlicence','dealer_module');";
						$result = runmysqlquery($query);
						
						//Get the dealer Branch and Region
						$query111 = "select branch,region from inv_mas_dealer where slno = '".$delaerrep."';";
						$fetchresult111 = runmysqlqueryfetch($query111);
						$branchid = $fetchresult111['branch'];
						$regionid = $fetchresult111['region'];
						
						$query = "UPDATE inv_mas_customer SET currentdealer = '".$delaerrep."',activecustomer ='yes',branch = '".$branchid."',region = '".$regionid."' WHERE slno = '".$customerid."'";
						$result = runmysqlquery($query); 
						$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','115','".date('Y-m-d').' '.date('H:i:s')."','".$customerid."')";
						$eventresult = runmysqlquery($eventquery);
						sendregistrationemail($customerproductslno,$userid);
						$query = "INSERT INTO inv_logs_softkeygen(generatedby,customerref,cardno,computerid,softkey,`date`,time,system,module) VALUES('".$userid."','".$customerid."','".$cardid."','".$computerid."','".$softkey."','".datetimelocal('Y-m-d')."','".datetimelocal('H:i:s')."','".$_SERVER['REMOTE_ADDR']."','dealer_module');";
						$result = runmysqlquery($query);
						/*	//Attching card to customer if purchasetype is new
						if(freeupdateproductcode($productcode) <> 'codenotfound' && $purchasetype == 'new' && $cardscheme == '1')
						{
							$newproductcode = freeupdateproductcode($productcode);
							$query1 = "select cardid as cusattachedcard from inv_mas_scratchcard where attached = 'no' order by cardid limit 1 ;";
							$result1 = runmysqlqueryfetch($query1);
							$cusattachedcard = $result1['cusattachedcard'];
							$query2 = "INSERT INTO inv_dealercard (dealerid, cardid, productcode, date, remarks, cusbillnumber, usagetype, purchasetype,  userid,scheme,initialusagetype,initialpurchasetype,initialproduct,initialdealerid,customerreference,cuscardattacheddate) values('".$delaerrep."','".$cusattachedcard."','".$newproductcode."','".datetimelocal('Y-m-d')." ".datetimelocal('H:i:s')."','','".$billno."','".$usagetype."','updation','yes','".$userid."','7','".$usagetype."','updation','".$newproductcode."','".$delaerrep."','".$customerid."','".datetimelocal('Y-m-d')." ".datetimelocal('H:i:s')."');";
							$result2 = runmysqlquery($query2);
							$query3 = "update inv_mas_scratchcard set attached = 'yes', registered='no', blocked='no', online='no', cancelled='no'  where attached = 'no' and cardid = ".$cusattachedcard.";";			
							$result3 = runmysqlquery($query3);
							sendfreeupdationcardemail($cusattachedcard,$customerproductslno);
						}*/
						$errorfound = "3^Softkey Generated Your Softkey is: ".$softkey."^".$sendcustomeridpassword;
					}
					echo(json_encode($errorfound));
				}
			}
			break;
		
			case 'updationlicense':
			{
				$errorfound = array();
				$customerid = $_POST['customerid'];
				$cardid = $_POST['scratchnumber'];
				$delaerrep = $_POST['delaerrep'];
				$productcode = $_POST['productcode'];
				$productname = $_POST['productname'];
				$computerid = $_POST['computerid'];
				$billno = $_POST['billno'];
				$billamount = $_POST['billamount'];
				$regremarks = $_POST['regremarks'];
				$usagetype = $_POST['usagetype'];
				$purchasetype = $_POST['purchasetype'];
				$date = datetimelocal('d-m-Y');
				$systemip = '';
				if($usagetype == 'multiuser')
					$usagetypecode = '09';
				elseif($usagetype == 'singleuser')
					$usagetypecode = '00';
				elseif($usagetype == 'additionallicense')
					$usagetypecode = '00';

				$computeridedition = substr($computerid, 0, 3);
				$computeridusagetype = substr($computerid, 3, 2);
				$computeridformat = substr($computerid, 5, 1);
				$computeridlength = strlen($computerid);
				if($computeridlength <> 15)
				{ echo(json_encode("2^"."Computer Id format is not valid.")); }
				elseif($computeridedition <> $productcode)
				{ echo(json_encode("2^"."Computer Id is not matching with the product you have selected.")); }
				elseif($computeridusagetype <> $usagetypecode)
				{ echo(json_encode("2^"."Computer Id is not matching with the purchase type.")); }
				elseif($computeridformat <> '-')
				{ echo(json_encode("2^"."Computer Id format is not valid.")); }
				
				else
				{
					$errorfound = "";
					//Card Number present in Database
					
					if($errorfound = "")
					{
						$query ="select * from inv_mas_scratchcard where cardid = '".$cardid."' and attached = 'yes' and blocked = 'no'";
						$result = runmysqlquery($query);
						if(mysqli_num_rows($result) == 0)
						$errorfound = '2 ^Invalid pin';
					}
					if($errorfound == "")
					{
						//Card No is not registered
						$query ="select * from inv_mas_scratchcard where cardid = '".$cardid."' and registered = 'no'";
						$result = runmysqlquery($query);
						if(mysqli_num_rows($result) == 0)
						$errorfound = '2^ This pin is already registered';
					}
					if($errorfound == "")
					{
						//Product code is the same, attached with Card
						$query ="select inv_mas_scratchcard.scratchnumber, inv_dealercard.dealerid from inv_mas_scratchcard left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid where inv_mas_scratchcard.cardid = '".$cardid."' and inv_dealercard.productcode = '".$productcode."'";
						$result = runmysqlquery($query);
						if(mysqli_num_rows($result) == 0)
						$errorfound = '2^ This Card belongs to a different Product.';
						//Read the card ID, dealer ID
						else
						{
							$fetch = mysqli_fetch_array($result);
							$scratchnumber = $fetch['scratchnumber'];
						}
					}
					if($errorfound == "")
					{
						//check if card is attached to the customer
						$query012 ="select * from inv_dealercard where cardid = '".$cardid."' and (customerreference = '' or customerreference IS NULL);";
						$result = runmysqlquery($query012);
						if(mysqli_num_rows($result) == 0)
						{
							$query013 ="select * from inv_dealercard where cardid = '".$cardid."' and customerreference = '".$customerid."';";
							$result1 = runmysqlquery($query013);
							if(mysqli_num_rows($result1) == 0)
								$errorfound = '2^ This Card belongs to a different Customer';
						}
					}
					if($errorfound == "")
					{
						//Usage type is the same, attached with Card
						$query ="select inv_mas_scratchcard.cardid as cradid from inv_mas_scratchcard left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid where inv_mas_scratchcard.cardid = '".$cardid."' and inv_dealercard.productcode = '".$productcode."'";
						$result = runmysqlqueryfetch($query);
						if($computeridusagetype == '00')
						{
							$query ="select * from inv_mas_scratchcard left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid where inv_mas_scratchcard.cardid = '".$cardid."' and (inv_dealercard.usagetype = 'singleuser' OR inv_dealercard.usagetype = 'additionallicense')";
							$result = runmysqlquery($query);
							if(mysqli_num_rows($result) == 0)
							$errorfound = '2^ This Card belongs to a Multiuser.';
						}
						elseif($computeridusagetype == '09')
						{
							$query ="select * from inv_mas_scratchcard left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid where inv_mas_scratchcard.cardid = '".$cardid."' and (inv_dealercard.usagetype = 'multiuser')";
							$result = runmysqlquery($query);
							if(mysqli_num_rows($result) == 0)
							$errorfound = '2^ This Card belongs to Single User or Additonal License.';
						}

						else
							$errorfound = '2^ The usage type of computer ID is invalid.'.$computeridusagetype;
					}
					if($errorfound == "")
					{
						$query = "SELECT `type` as prdtype,productname as productname FROM inv_mas_product WHERE productcode = '".$productcode."'";
						$fetch = runmysqlqueryfetch($query);
						$prdtype = $fetch['prdtype'];
						$productname = $fetch['productname'];
						$softkey  = generateserial($computerid, $prdtype);
						//$softkey  = generatesoftkeydummy($computerid, $prdtype);
						$query = "UPDATE inv_mas_scratchcard SET registered = 'yes', blocked = 'no', online = 'no', cancelled = 'no' WHERE cardid = '".$cardid."';";
						$result = runmysqlquery($query);
							//If this is the first registration for this customer, generate customerid, update it to customer master and send welcome email
						$query22 = "Select * from inv_mas_customer where slno ='".$customerid."' and customerid = ''; ";
						$fetchresult = runmysqlquery($query22);
						if(mysqli_num_rows($fetchresult) <> 0)
						{
							$query1 ="Select * from inv_customerproduct where customerreference = '".$customerid."'";
							$result = runmysqlquery($query1);
							if(mysqli_num_rows($result) == 0)
							{
								$password = generatepwd();
								$newcustomerid = generatecustomerid($customerid,$productcode,$delaerrep);
								$query14 = "UPDATE inv_mas_customer SET customerid = '".$newcustomerid."',loginpassword = AES_ENCRYPT('".$password."','imaxpasswordkey'),initialpassword = '".$password."',passwordchanged = 'N' WHERE slno = '".$customerid."'";
								$result = runmysqlquery($query14); 
								$sendcustomeridpassword = cusidcombine($newcustomerid)."%".$password;
								sendwelcomeemail($customerid,$userid); 
							}
						}
							//Find the next slno to be inserted
						$query = "SELECT (MAX(slno) + 1) AS newslno FROM inv_customerproduct";
						$fetch = runmysqlqueryfetch($query);
						$customerproductslno = $fetch['newslno'];
						$query = "INSERT INTO inv_customerproduct(slno,customerreference,cardid,computerid,softkey,cusbillnumber,billnumber,billamount,dealerid,generatedby,system,date,time,remarks,reregistration,`type`,module) VALUES('".$customerproductslno."','".$customerid."','".$cardid."','".$computerid."','".$softkey."',(SELECT cusbillnumber from inv_dealercard where cardid = '".$cardid."'),'".$billno."','".$billamount."','".$delaerrep."','2','".$_SERVER['REMOTE_ADDR']."','".datetimelocal('Y-m-d')."','".datetimelocal('H:i:s')."','".$regremarks."','no','updationlicense','dealer_module');";
					$result = runmysqlquery($query);
					
					//Get the dealer Branch and Region
					$query111 = "select branch,region from inv_mas_dealer where slno = '".$delaerrep."';";
					$fetchresult111 = runmysqlqueryfetch($query111);
					$branchid = $fetchresult111['branch'];
					$regionid = $fetchresult111['region'];
					
					$query = "UPDATE inv_mas_customer SET currentdealer = '".$delaerrep."',activecustomer ='yes',branch = '".$branchid."',region = '".$regionid."' WHERE slno = '".$customerid."'";
					$result = runmysqlquery($query); 
$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','115','".date('Y-m-d').' '.date('H:i:s')."','".$customerid."')";
					$eventresult = runmysqlquery($eventquery);
					sendregistrationemail($customerproductslno,$userid);
					$query = "INSERT INTO inv_logs_softkeygen(generatedby,customerref,cardno,computerid,softkey,`date`,time,system,module) VALUES('".$userid."','".$customerid."','".$cardid."','".$computerid."','".$softkey."','".datetimelocal('Y-m-d')."','".datetimelocal('H:i:s')."','".$_SERVER['REMOTE_ADDR']."','dealer_module');";
					$result = runmysqlquery($query);
					$errorfound = "3^Softkey Generated Your Softkey is: ".$softkey."^".$sendcustomeridpassword;
					}
					echo(json_encode($errorfound));
				}
			}
			break;
		}
	}
	break;

	case 'newregcardlist':
	{
		$newregcardlistarray = array();
		## query Edited By Bhavesh added inv_dealercard.productcode ##
		$query = "select * from inv_mas_scratchcard left join inv_dealercard on inv_dealercard.cardid = inv_mas_scratchcard.cardid where attached = 'yes' and registered = 'no' and blocked = 'no' and inv_dealercard.purchasetype = 'new' and (inv_dealercard.productcode <> 353 and inv_dealercard.productcode <> 308 and inv_dealercard.productcode <> 371 and inv_dealercard.productcode <> 215 and inv_dealercard.productcode <> 217 and inv_dealercard.productcode <> 216 and inv_dealercard.productcode <> 515 and inv_dealercard.productcode <> 242 and inv_dealercard.productcode <> 243 and inv_dealercard.productcode <> 881 and inv_dealercard.productcode <> 690 and inv_dealercard.productcode <> 643  and inv_dealercard.productcode <> 658  and inv_dealercard.productcode <> 659  and inv_dealercard.productcode <> 882  and inv_dealercard.productcode <> 883  and inv_dealercard.productcode <> 884 and inv_dealercard.productcode <> 885 and inv_dealercard.productcode <> 887) and inv_dealercard.dealerid = '".$userid."';";
		$result = runmysqlquery($query);
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$newregcardlistarray[$count] =  $fetch['scratchnumber'].' | '.$fetch['cardid'].'^'.$fetch['cardid'];
			$count++;
		}
		echo(json_encode($newregcardlistarray));
	 }
	break;

	case 'updationcardlist':
	{
		$updationcardlistarray = array();
		## query Edited By Bhavesh added inv_dealercard.productcode ##
		$query = "select * from inv_mas_scratchcard left join inv_dealercard on inv_dealercard.cardid = inv_mas_scratchcard.cardid where attached = 'yes' and registered = 'no' and blocked = 'no' and inv_dealercard.purchasetype = 'updation' and (inv_dealercard.productcode <> 353 and inv_dealercard.productcode <> 308 and inv_dealercard.productcode <> 371 and inv_dealercard.productcode <> 215 and inv_dealercard.productcode <> 217 and inv_dealercard.productcode <> 216 and inv_dealercard.productcode <> 515 and inv_dealercard.productcode <> 242 and inv_dealercard.productcode <> 243 and inv_dealercard.productcode <> 881 and inv_dealercard.productcode <> 690 and inv_dealercard.productcode <> 643 and inv_dealercard.productcode <> 658 and inv_dealercard.productcode <> 659 and inv_dealercard.productcode <> 882 and inv_dealercard.productcode <> 883 and inv_dealercard.productcode <> 884 and inv_dealercard.productcode <> 885 and inv_dealercard.productcode <> 887) and inv_dealercard.dealerid = '".$userid."';";
		$result = runmysqlquery($query);
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$updationcardlist[$count] .=  $fetch['scratchnumber'].' | '.$fetch['cardid'].'^'.$fetch['cardid'];
			$count++;
		}
		echo(json_encode($updationcardlist));
	 }
	break;
	case 'scratchdetailstoform':
	{
		$cardid = $_POST['cardid'];
		$query = "SELECT distinct inv_dealercard.cardid , inv_mas_scratchcard.scratchnumber, inv_mas_scratchcard.blocked,inv_mas_scratchcard.cancelled,inv_mas_dealer.businessname as attachedto, inv_mas_dealer.slno as dealerid, inv_mas_product.productcode, inv_mas_product.productname, inv_dealercard.purchasetype, inv_dealercard.usagetype, inv_dealercard.date as attachdate, inv_customerproduct.date as registereddate, inv_mas_customer.businessname as registeredto,inv_dealercard.scheme,inv_mas_scheme.schemename as schemename from inv_dealercard left join inv_mas_scratchcard on inv_dealercard.cardid = inv_mas_scratchcard.cardid left join inv_mas_dealer on inv_dealercard.dealerid = inv_mas_dealer.slno left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode left join (select cardid, customerreference, min(`date`) as `date` from inv_customerproduct GROUP BY cardid) AS inv_customerproduct on  inv_dealercard.cardid = inv_customerproduct.cardid left join inv_mas_customer on  inv_customerproduct.customerreference = inv_mas_customer.slno left join inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme where inv_dealercard.cardid = '".$cardid."';";
		$fetch = runmysqlqueryfetch($query);
		$attcheddate = substr($fetch['attachdate'] ,0,10);
		$registereddate =$fetch['registereddate'];
		if($registereddate != '')
			$registereddate = changedateformat($registereddate);
			
			
		if($fetch['blocked'] == 'yes')
			$cardstatus = 'Blocked';
		else if($fetch['cancelled'] == 'yes')
			$cardstatus = 'Cancelled';
		else
			$cardstatus = 'Active';
		$responsecardarray = array();
		$responsecardarray['errorcode'] = "1";
		$responsecardarray['cardid'] = $fetch['cardid'];
		$responsecardarray['scratchnumber'] = $fetch['scratchnumber'];
		$responsecardarray['purchasetype'] = $fetch['purchasetype'];
		$responsecardarray['usagetype'] = $fetch['usagetype'];
		$responsecardarray['attachedto'] = $fetch['attachedto'];
		$responsecardarray['dealerid'] = $fetch['dealerid'];
		$responsecardarray['productcode'] = $fetch['productcode'];
		$responsecardarray['productname'] = $fetch['productname'];
		$responsecardarray['attcheddate'] = changedateformat($attcheddate);
		$responsecardarray['registereddate'] = $registereddate;
		$responsecardarray['registeredto'] = $fetch['registeredto'];
		$responsecardarray['cardstatus'] = $cardstatus;
		$responsecardarray['schemename'] = $fetch['schemename'];
		echo(json_encode($responsecardarray));
		
	}
	break;
	case 'getcardlist':
	{
		$limit = $_POST['limit'];
		$startindex = $_POST['startindex'];
		$query = "SELECT inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber FROM inv_mas_scratchcard left join
inv_dealercard on inv_dealercard.cardid = inv_mas_scratchcard.cardid 
left join inv_mas_dealer on inv_mas_dealer.slno = inv_dealercard.dealerid
where inv_mas_dealer.slno = '".$userid."' ORDER BY scratchnumber LIMIT ".$startindex.",".$limit.";";
		$result = runmysqlquery($query);
		$responsecardgrid =  array();
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$responsecardgrid[$count] =  $fetch['scratchnumber'].' | '.$fetch['cardid'].'^'.$fetch['cardid'];
			$count++;		
		}
		
		echo(json_encode($responsecardgrid));
	 }
	break;
	case 'getcardcount':
	{
		$query = "SELECT count(*) as totalcardcount FROM inv_mas_scratchcard;";
		$resultfetch = runmysqlqueryfetch($query);
		$responsecardcount = array();
		$responsecardcount['totalcardcount'] = $resultfetch['totalcardcount'];
		echo(json_encode($responsecardcount));
	 }
	break;
	case 'generatecustomerattachcards':
	{
		$lastslno = $_POST['lastslno'];
		$query = "select inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber,inv_mas_dealer.businessname,inv_mas_product.productname,inv_dealercard.purchasetype,inv_dealercard.usagetype,inv_mas_scheme.schemename,inv_dealercard.cuscardattacheddate,inv_dealercard.remarks,inv_dealercard.cuscardremarks as cardremarks  from inv_dealercard left join inv_mas_scratchcard on inv_dealercard.cardid =inv_mas_scratchcard.cardid left join inv_mas_dealer on inv_dealercard.dealerid = inv_mas_dealer.slno left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode left join inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme where customerreference ='".$lastslno."' and inv_mas_scratchcard.registered = 'no' order by inv_dealercard.cuscardattacheddate;";
		$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
		$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">PIN Serial Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">PIN Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">Dealer</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product</td><td nowrap = "nowrap" class="td-border-grid" align="left">Usage Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Purchase Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Scheme</td><td nowrap = "nowrap" class="td-border-grid" align="left">Attached Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Remarks</td><td nowrap = "nowrap" class="td-border-grid" align="left">Card Remarks</td></tr>';
		$i_n = 0;$slno = 0;
		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_row($result))
		{
			$i_n++;$slno++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$grid .= '<tr class="gridrow1" bgcolor='.$color.' align="left">';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slno."</td>";
			for($i = 0; $i < count($fetch); $i++)
			{
				if($i == 8)
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformatwithtime($fetch[$i])."</td>";
				else
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch[$i])."</td>";
				
			}
			$grid .= "</tr>";
		}
		$grid .= "</table>";
		$responseattchcardarray = array();
		$responseattchcardarray['errorcode'] = "1";
		$responseattchcardarray['grid'] = $grid;
		echo(json_encode($responseattchcardarray));
	}
	break;
	case 'carddetailstoform':
	{
		$cardlastslno = $_POST['cardlastslno'];
		$query = "SELECT distinct inv_dealercard.cardid , inv_mas_scratchcard.scratchnumber, 
inv_mas_scratchcard.blocked,inv_mas_scratchcard.cancelled,inv_mas_dealer.businessname as attachedto, 
inv_mas_dealer.slno as dealerid, inv_mas_product.productcode, inv_mas_product.productname, 
inv_dealercard.purchasetype, inv_dealercard.usagetype, inv_dealercard.date as attachdate, 
inv_customerproduct.date as registereddate, inv_customerproduct.businessname as registeredto,inv_dealercard.scheme ,
inv_mas_scheme.schemename as schemename ,inv_mas_customer.businessname as businessname from inv_dealercard 
left join inv_mas_scratchcard on inv_dealercard.cardid = inv_mas_scratchcard.cardid 
left join inv_mas_dealer on inv_dealercard.dealerid = inv_mas_dealer.slno 
left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode 
left join 
(select cardid, customerreference, min(`date`) as `date` ,inv_mas_customer.businessname as businessname
from inv_customerproduct 
left join inv_mas_customer on  inv_customerproduct.customerreference = inv_mas_customer.slno 
GROUP BY cardid) 
AS inv_customerproduct on  inv_dealercard.cardid = inv_customerproduct.cardid 
left join inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme 
left join inv_mas_customer on  inv_dealercard.customerreference = inv_mas_customer.slno 
where inv_dealercard.cardid = '".$cardlastslno."' and inv_mas_dealer.slno = '".$userid."'";
			$fetch = runmysqlqueryfetch($query);
			$attcheddate = substr($fetch['attachdate'] ,0,10);
			$registereddate =$fetch['registereddate'];
			if($registereddate != '')
				$registereddate = changedateformat($registereddate);
			if($fetch['blocked'] == 'yes')
				$cardstatus = 'Blocked';
			else if($fetch['cancelled'] == 'yes')
				$cardstatus = 'Cancelled';
			else
				$cardstatus = 'Active';
				
			$responsecardlistarray = array();
			$responsecardlistarray['errorcode'] = "1";
			$responsecardlistarray['cardid'] = $fetch['cardid'];
			$responsecardlistarray['scratchnumber'] = $fetch['scratchnumber'];
			$responsecardlistarray['purchasetype'] = $fetch['purchasetype'];
			$responsecardlistarray['usagetype'] = $fetch['usagetype'];
			$responsecardlistarray['attachedto'] = $fetch['attachedto'];
			$responsecardlistarray['dealerid'] = $fetch['dealerid'];
			$responsecardlistarray['productcode'] = $fetch['productcode'];
			$responsecardlistarray['productname'] = $fetch['productname'];
			$responsecardlistarray['attcheddate'] = changedateformat($attcheddate);
			$responsecardlistarray['registereddate'] = $fetch['registereddate'];
			$responsecardlistarray['registeredto'] = $fetch['registeredto'];
			$responsecardlistarray['cardstatus'] = $cardstatus;
			$responsecardlistarray['remarks'] = $fetch['remarks'];
			$responsecardlistarray['schemename'] = $fetch['schemename'];
			$responsecardlistarray['businessname'] = $fetch['businessname'];
			echo(json_encode($responsecardlistarray));
			
			//echo('1^'.$fetch['cardid'].'^'.$fetch['scratchnumber'].'^'.$fetch['purchasetype'].'^'.$fetch['usagetype'].'^'.$fetch['attachedto'].'^'.$fetch['dealerid'].'^'.$fetch['productcode'].'^'.$fetch['productname'].'^'.changedateformat($attcheddate).'^'.$registereddate.'^'.$fetch['registeredto'].'^'.$cardstatus.'^'.$fetch['remarks'].'^'.$fetch['schemename'].'^'.$fetch['businessname']);
			//echo($query);
	
	}
	break;
	case 'invoicedetailsgrid':
	{
		$startlimit = $_POST['startlimit'];
		$slno = $_POST['slno'];
		$showtype = $_POST['showtype'];
		$lastslno = $_POST['lastslno'];
		$resultcount = "select inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_invoicenumbers.contactperson,inv_invoicenumbers.description,inv_invoicenumbers.invoiceno,inv_invoicenumbers.dealername,inv_invoicenumbers.createddate,inv_invoicenumbers.amount,inv_invoicenumbers.servicetax,inv_invoicenumbers.netamount,inv_invoicenumbers.purchasetype from inv_invoicenumbers left join dealer_online_purchase on dealer_online_purchase.onlineinvoiceno = inv_invoicenumbers.slno where right(customerid,5) = '".$lastslno."' order by createddate  desc;";
		$resultfetch = runmysqlquery($resultcount);
		$fetchresultcount = mysqli_num_rows($resultfetch);
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
			$startlimit = $slno ;
			$slno = $slno;
		}
		$query = "select inv_invoicenumbers.slno as invoicenumber, inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_invoicenumbers.contactperson,inv_invoicenumbers.description,inv_invoicenumbers.invoiceno,inv_invoicenumbers.dealername,inv_invoicenumbers.createddate,inv_invoicenumbers.amount,inv_invoicenumbers.servicetax,inv_invoicenumbers.netamount,inv_invoicenumbers.purchasetype,inv_invoicenumbers.createdby,inv_invoicenumbers.status from inv_invoicenumbers left join dealer_online_purchase on dealer_online_purchase.onlineinvoiceno = inv_invoicenumbers.slno where right(customerid,5) = '".$lastslno."'  order by createddate  desc LIMIT ".$startlimit.",".$limit.";";
		$result = runmysqlquery($query);
		if($startlimit == 0)
		{
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
			$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Received</td><td nowrap = "nowrap" class="td-border-grid" align="left">Outstanding Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Status</td><td nowrap = "nowrap" class="td-border-grid" align="left">Generated By</td><td nowrap = "nowrap" class="td-border-grid" align="left">Action</td></tr>';
		}
		
		$i_n = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$i_n++;
			$slno++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$query1 = "select sum(receiptamount) as  receivedamount from inv_mas_receipt where invoiceno = '".$fetch['invoicenumber']."';";
			$resultfetch1 = runmysqlqueryfetch($query1);
			if($resultfetch1['receivedamount'] == '')
			{
				$receivedamount = 0;
			}
			else
			{
				$receivedamount = $resultfetch1['receivedamount'];
			}
			$balanceamount =  $fetch['netamount'] - $receivedamount;
			$grid .= '<tr class="gridrow1" bgcolor='.$color.'>';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slno."</td> ";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformatwithtime($fetch['createddate'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['invoiceno']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['netamount']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$receivedamount."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$balanceamount."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['status']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['createdby']."</td>";
			$grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"> <a onclick="viewinvoice(\''.$fetch['invoicenumber'].'\');" class="resendtext" style = "cursor:pointer"> View >></a> </td>';
			$grid .= "</tr>";
		}
		$grid .= "</table>";

		$fetchcount = mysqli_num_rows($result);
		if($slno >= $fetchresultcount)
		$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2" align = "left"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
		$linkgrid .= '<table><tr><td class="resendtext"><div align ="left" style="padding-right:10px"><a onclick ="getmoreinvoicedetails(\''.$lastslno.'\',\''.$startlimit.'\',\''.$slno.'\',\'more\');" style="cursor:pointer">Show More Records >></a>&nbsp;&nbsp;&nbsp;<a onclick ="getmoreinvoicedetails(\''.$lastslno.'\',\''.$startlimit.'\',\''.$slno.'\',\'all\');" class ="resendtext1" style="cursor:pointer"><font color= "#000000">(Show All Records)</font></a></div></td></tr></table>';
		
		$responseinvoicearray = array();
		$responseinvoicearray['errorcode'] = "1";
		$responseinvoicearray['grid'] = $grid;
		$responseinvoicearray['resultcount'] = $fetchresultcount;
		$responseinvoicearray['linkgrid'] = $linkgrid;
		echo(json_encode($responseinvoicearray));
		//echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid;
	}
	break;
	
}

function generatecustomerid($customerid,$productcode,$delaerrep)
{
	$query = "SELECT * FROM inv_mas_customer where slno = '".$customerid."'";
	$fetch = runmysqlqueryfetch($query);
	$district = $fetch['district'];
	$query = runmysqlqueryfetch("SELECT distinct statecode from inv_mas_district where districtcode = '".$district."'");
	$cusstatecode = $query['statecode'];
	$newcustomerid = $cusstatecode.$district.$delaerrep.$productcode.$customerid;
	return $newcustomerid;
}

/*function freeupdateproductcode($productcode)
{
	switch($productcode)
	{
		case '638': return '639'; break;
		case '647': return '650'; break;
		case '648': return '651'; break;
		case '349': return '350'; break;
		case '304': return '305'; break;
		case '469': return '472'; break;
		case '471': return '473'; break;
		case '470': return '474'; break;
		case '511': return '512'; break;
		default: return 'codenotfound';
	}
}

function sendfreeupdationcardemail($cusattachedcard,$customerproductslno)
{
		
		$query1 = "select inv_mas_scratchcard.scratchnumber as pinno,inv_mas_dealer.businessname as dealername,inv_mas_dealer.emailid as dealeremailid,inv_mas_product.productname as newproductname from inv_dealercard left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid left join inv_mas_dealer on inv_mas_dealer.slno = inv_dealercard.dealerid 
left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode where inv_dealercard.cardid = '".$cusattachedcard."' ";
		$resultfetch = runmysqlqueryfetch($query1);
		$pinno = $resultfetch['pinno'];
		$dealername = $resultfetch['dealername'];
		$newproductname = $resultfetch['newproductname'];
		$dealeremailid = $resultfetch['dealeremailid'];
		
		$query = "Select inv_mas_customer.businessname as businessname,inv_mas_customer.contactperson as contactperson,inv_mas_customer.place as place,inv_mas_customer.emailid as emailid,inv_mas_customer.customerid as customerid, inv_mas_product.productname as oldproductname ,inv_customerproduct.date as regdate from inv_customerproduct Left join inv_mas_customer on inv_mas_customer.slno = inv_customerproduct.customerreference left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_customerproduct.cardid	left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3)	where inv_customerproduct.slno = '".$customerproductslno."'";
	$result = runmysqlqueryfetch($query);
	
	$regdate = $result['regdate'];
	$businessname = $result['businessname'];
	$contactperson = $result['contactperson'];
	$place = $result['place'];
	$customerid = $result['customerid'];
	$oldproductname = $result['oldproductname'];
	$emailid = $result['emailid'];

		//Dummy line to override To email ID
	//$emailid = 'rashmi.hk@relyonsoft.com';
	$emailarray = explode(',',$emailid);
	$emailcount = count($emailarray);
	
	// CC mail to dealer 
	$ccemailarray = explode(',',$dealeremailid);
	$ccemailcount = count($ccemailarray);
	
	for($i = 0; $i < $emailcount; $i++)
	{
		if(checkemailaddress($emailarray[$i]))
		{
			$emailids[$emailarray[$i]] = $emailarray[$i];
		}
	}
	
	for($i = 0; $i < $ccemailcount; $i++)
	{
		if(checkemailaddress($ccemailarray[$i]))
		{
			$ccemailids[$ccemailarray[$i]] = $ccemailarray[$i];
		}
	}

	$fromname = "Relyon";
	$fromemail = "imax@relyon.co.in";
	require_once("../inc/RSLMAIL_MAIL.php");
	$msg = file_get_contents("../mailcontents/customercardinfo.htm");
	$textmsg = file_get_contents("../mailcontents/customercardinfo.txt");
	$date = datetimelocal('d-m-Y');
	$array = array();
	$array[] = "##DATE##%^%".changedateformat($regdate);
	$array[] = "##NAME##%^%".$contactperson;
	$array[] = "##COMPANY##%^%".$businessname;
	$array[] = "##PLACE##%^%".$place;
	$array[] = "##CUSTOMERID##%^%".cusidcombine($customerid);
	$array[] = "##OLDPRODUCTNAME##%^%".$oldproductname;
	$array[] = "##NEWPRODUCTNAME##%^%".$newproductname;
	$array[] = "##SCRATCHCARDNO##%^%".$pinno;
	$array[] = "##CARDID##%^%".$cusattachedcard;
	$array[] = "##DEALERNAME##%^%".$dealername;
	
	$filearray = array(
		array('../images/relyon-logo.jpg','inline','1234567890')
	);
	$toarray = $emailids;
	$bccemailids['vijaykumar'] ='vijaykumar@relyonsoft.com';
	$bccarray = $bccemailids;
	$ccarray = $ccemailids;
	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$subject = "Free PIN No for ".$newproductname." (2010-11)";
	$html = $msg;
	$text = $textmsg;
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,$ccarray,$bccarray,$filearray); 
						
}*/
?>