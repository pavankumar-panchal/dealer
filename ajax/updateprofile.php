<?php
ob_start("ob_gzhandler");

include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php'); 
include('../inc/checksession.php');

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
	case 'tempsave':
	{
		$userid = $_POST['userid'];
		$contactperson = $_POST['contactperson'];
		$address = $_POST['address'];
		$place = $_POST['place'];
		$district = $_POST['district'];
		$state = $_POST['state'];
		$pincode = $_POST['pincode'];
		$stdcode = $_POST['stdcode'];
		//Added Space after comma is not avaliable in phone and cell fields
		$phone = $_POST['phone'];
		$phonespace = str_replace(", ", ",",$phone);
		$phonevalue = str_replace(',',', ',$phonespace);
		
		$cell = $_POST['cell'];
		$cellspace = str_replace(", ", ",",$cell);
		$cellvalue = str_replace(',',', ',$cellspace);
			
		$emailid = $_POST['emailid'];
		$website = $_POST['website'];
		$region = $_POST['region'];
		$personalemailid = $_POST['personalemailid'];
		$businessname = $_POST['businessname'];
		$createddate = datetimelocal('d-m-Y').' '.datetimelocal('H:i:s');
		$countquery = "SELECT COUNT(*) as count FROM inv_dealerreqpending WHERE dealerid = '".$userid."' AND dealerstatus = 'pending';";
		$countfetch = runmysqlqueryfetch($countquery);
		if($countfetch['count'] == 0)
		{
		$query1 = "Insert into inv_dealerreqpending(dealerid,contactperson,address,region,place,district,pincode,stdcode,phone,cell,emailid,website,dealerstatus,createddate,businessname,personalemailid) values('".$userid."','".$contactperson."','".$address."','".$region."','".$place."','".$district."','".$pincode."','".$stdcode."','".$phonevalue."','".$cellvalue."','".$emailid."','".$website."','pending','".changedateformatwithtime($createddate)."','".trim($businessname)."','".$personalemailid."')";
		$result1 = runmysqlquery($query1);
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','147','".date('Y-m-d').' '.date('H:i:s')."')";
		$eventresult = runmysqlquery($eventquery);
		}
		else
		{
			$query = "UPDATE inv_dealerreqpending SET contactperson = '".$contactperson."',businessname = '".trim($businessname)."',address = '".$address."',place = '".$place."',pincode = '".$pincode."',district = '".$district."', region = '".$region."',stdcode = '".$stdcode."',phone = '".$phonevalue."',cell = '".$cellvalue."',emailid  = '".$emailid."',personalemailid  = '".$personalemailid."',website = '".$website."',createddate = '".changedateformatwithtime($createddate)."' WHERE dealerid = '".$userid."'";
			$result = runmysqlquery($query);
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','147','".date('Y-m-d').' '.date('H:i:s')."')";
			$eventresult = runmysqlquery($eventquery);
		}
		
		echo('Profile update Request has been submitted successfully to Relyon. This will be verified and updated in few hours.');
	}
	break;
	case 'undo':
	{
		$dealerid = $_POST['dealerid'];

			$query = "SELECT inv_mas_dealer.slno,inv_mas_dealer.businessname,inv_mas_dealer.contactperson,inv_mas_dealer.region,
inv_mas_dealer.contactperson,inv_mas_dealer.address,inv_mas_dealer.place,inv_mas_dealer.district,inv_mas_dealer.pincode,inv_mas_dealer.stdcode,inv_mas_dealer.phone,inv_mas_dealer.cell,inv_mas_dealer.emailid,inv_mas_dealer.personalemailid,inv_mas_dealer.website,inv_mas_dealer.createddate,inv_mas_district.statecode FROM inv_mas_dealer left join inv_mas_district on 
inv_mas_dealer.district = inv_mas_district.districtcode WHERE inv_mas_dealer.slno= '".$dealerid."';";
			$fetch = runmysqlqueryfetch($query);
			
		echo($fetch['contactperson'].'^'.$fetch['address'].'^'.$fetch['place'].'^'.$fetch['statecode'].'^'.$fetch['district'].'^'.$fetch['pincode'].'^'.$fetch['region'].'^'.$fetch['stdcode'].'^'.$fetch['phone'].'^'.$fetch['cell'].'^'.$fetch['emailid'].'^'.$fetch['website'].'^'.$fetch['createddate'].'^'.$fetch['businessname'].'^'.$fetch['personalemailid']);
		
		}
		break;
	case 'getdealerdetails':
	{
		$dealerid = $_POST['dealerid'];
		$query = "SELECT count(*) AS count FROM inv_dealerreqpending WHERE dealerid = '".$dealerid."' and dealerstatus ='pending';";
		$fetch = runmysqlqueryfetch($query);
		$count = $fetch['count'];
		if($count > 0)
		{
			$query = "SELECT inv_dealerreqpending.dealerid,inv_dealerreqpending.businessname,inv_dealerreqpending.contactperson,inv_dealerreqpending.region,inv_dealerreqpending.contactperson,inv_dealerreqpending.address,inv_dealerreqpending.place,inv_dealerreqpending.district,inv_dealerreqpending.pincode,inv_dealerreqpending.stdcode,inv_dealerreqpending.phone,inv_dealerreqpending.cell,inv_dealerreqpending.emailid,inv_dealerreqpending.personalemailid,inv_dealerreqpending.website, inv_dealerreqpending.createddate,inv_mas_district.statecode as state FROM inv_dealerreqpending left join inv_mas_district on 
inv_dealerreqpending.district = inv_mas_district.districtcode WHERE dealerid = '".$dealerid."' and dealerstatus ='pending';";
			$fetch = runmysqlqueryfetch($query);
			
			echo($fetch['contactperson'].'^'.$fetch['address'].'^'.$fetch['place'].'^'.$fetch['state'].'^'.$fetch['district'].'^'.$fetch['pincode'].'^'.$fetch['region'].'^'.$fetch['stdcode'].'^'.$fetch['phone'].'^'.$fetch['cell'].'^'.$fetch['emailid'].'^'.$fetch['website'].'^'.changedateformatwithtime($fetch['createddate']).'^'.$fetch['businessname'].'^'.'Your Profile update request is pending for Approval'.'^'.$fetch['personalemailid']);
		}
		else
		{
			$query = "SELECT inv_mas_dealer.slno AS dealerid,inv_mas_dealer.businessname AS businessname,inv_mas_dealer.contactperson AS contactperson,inv_mas_dealer.region AS region,inv_mas_dealer.address AS address,inv_mas_dealer.place AS place,inv_mas_dealer.district AS district,inv_mas_district.statecode as state,inv_mas_dealer.pincode AS pincode,inv_mas_dealer.stdcode AS stdcode ,inv_mas_dealer.phone AS phone,inv_mas_dealer.cell AS cell,inv_mas_dealer.emailid AS emailid ,inv_mas_dealer.website AS website,inv_mas_dealer.personalemailid AS personalemailid ,inv_mas_dealer.createddate AS createddate FROM inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode WHERE inv_mas_dealer.slno = '".$dealerid."';";
			$fetch = runmysqlqueryfetch($query);
			
			echo($fetch['contactperson'].'^'.$fetch['address'].'^'.$fetch['place'].'^'.$fetch['state'].'^'.$fetch['district'].'^'.$fetch['pincode'].'^'.$fetch['region'].'^'.$fetch['stdcode'].'^'.$fetch['phone'].'^'.$fetch['cell'].'^'.$fetch['emailid'].'^'.$fetch['website'].'^'.changedateformatwithtime($fetch['createddate']).'^'.$fetch['businessname'].'^'.''.'^'.$fetch['personalemailid']);
			
		}
	
	}
	break;
	case 'cancelupdate':
	{
		$dealerid = $_POST['dealerid'];
		$query ="Update inv_dealerreqpending set dealerstatus = 'cancelled' where dealerid = '".$dealerid."' AND dealerstatus = 'pending';";
		$result = runmysqlquery($query);
		echo('Pending Request Cancelled Successfully');
	}
	break;

}
?>
