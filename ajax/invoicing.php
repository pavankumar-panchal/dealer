<?php
ob_start("ob_gzhandler");

include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');
$lastslno = $_POST['lastslno'];
$switchtype = $_POST['switchtype'];

if(imaxgetcookie('dealeruserid') <> '') 
$userid = imaxgetcookie('dealeruserid');
else
{ 
	echo('Thinking to redirect');
	exit;
}

//exit();

$query = "select * from inv_mas_dealer where slno = '".$userid."';";
$resultfetch = runmysqlqueryfetch($query);
$loggedindealername = $resultfetch['businessname'];

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
		$responsearray = array();
		$lastslno = $_POST['lastslno'];
		$result = checkcustomer($lastslno);

		if($result == 'true')
		{
			$query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode,inv_mas_dealer.telecaller,inv_mas_dealer.branch,inv_mas_dealer.region
			from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
 			where inv_mas_dealer.slno = '".$userid."';";
			$fetch = runmysqlqueryfetch($query);
			$relyonexecutive = $fetch['relyonexecutive'];
			$branch = $fetch['branch'];
			$region = $fetch['region'];
			$telecaller = $fetch['telecaller'];
			$district = $fetch['district'];
			$state = $fetch['statecode'];
			
			$query_gstn = "select inv_mas_state.state_gst_code as state_gst_code,inv_mas_customer.businessname as businessname,inv_mas_customer.gst_no as gst_no,inv_mas_customer.sez_enabled as sez_enabled,inv_mas_customer.panno
			from inv_mas_customer left join inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer 
			left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district 
			left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode where inv_mas_customer.slno = '".$lastslno."';";
			
			$fetch_gstn = runmysqlqueryfetch($query_gstn);
			//$gst_no = $fetch_gstn['gst_no'];
			$sez_enabled = $fetch_gstn['sez_enabled'];

			$querygstgetdetail = "select gst_no as new_gst_no,gstin_id from customer_gstin_logs where customer_slno =".$lastslno." order by gstin_id desc limit 1";
			$ressultgstdetail = runmysqlquery($querygstgetdetail);
			$count_gst = mysqli_num_rows($ressultgstdetail);
			if($count_gst > 0)
			{
				$fetchgstgetdetail = runmysqlqueryfetch($querygstgetdetail);
			    $new_gst_no = $fetchgstgetdetail['new_gst_no'];
			    $gstin_id = $fetchgstgetdetail['gstin_id'];
			}
			else
			{
				$new_gst_no = $fetch_gstn['gst_no'];
			}
						
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
						$dealerpiece = " AND (inv_mas_customer.region = '1' or inv_mas_customer.region = '3') ";
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
			
			$query2 = "select * from inv_customerreqpending where customerid = '".$lastslno."' and inv_customerreqpending.customerstatus = 'Pending' and requestfrom = 'dealer_module';";
			$result2 = runmysqlquery($query2);
			
			if(mysqli_num_rows($result2) > 0)
			{
				$query = "select inv_customerreqpending.customerid as slno, inv_customerreqpending.businessname as companyname,inv_customerreqpending.place,inv_customerreqpending.address,inv_mas_region.category as region,inv_mas_branch.branchname as branch,inv_mas_customercategory.businesstype as businesstype,inv_mas_customertype.customertype as customertype,inv_mas_dealer.businessname as dealername,inv_customerreqpending.stdcode, inv_mas_customer.pincode,inv_mas_district.districtname, inv_mas_state.statename,inv_mas_customer.customerid  from inv_mas_customer left join inv_customerreqpending on inv_customerreqpending.customerid = inv_mas_customer.slno left join inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region 
				left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type 
				left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode where inv_mas_customer.slno = '".$lastslno."'".$dealerpiece." and inv_customerreqpending.customerstatus = 'Pending' and requestfrom = 'dealer_module';";
				$fetch = runmysqlqueryfetch($query);
				
				// Fetch Contact Details 
				$querycontactdetails = "select phone,cell,emailid,contactperson from inv_contactreqpending where customerid = '".$lastslno."' and editedtype = 'edit_type' and customerstatus = 'pending' and requestfrom = 'dealer_module';";
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
				$pincode = ($resultfetch['pincode'] == '')?'':' Pin - '.$fetch['pincode'];
				$address = $fetch['address'].', '.$fetch['place'].' ,'.$fetch['districtname'].', '.$fetch['statename'].' '.$pincode;
				$phonenumber = explode(',', trim($phoneres,','));
				$phone = $phonenumber[0];
				$cellnumber = explode(',', trim($cellres,','));
				$cell = $cellnumber[0];
				$emailid = explode(',', trim($emailidres,','));
				$emailidplit = $emailid[0];
				
				$responsearray['errorcode'] = "1";
				$responsearray['slno'] = $fetch['slno'];
				$responsearray['customerid'] = $customerid;
				$responsearray['companyname'] = $fetch['companyname'];
				$responsearray['contactvalues'] = trim($contactvalues,',');
				$responsearray['address'] = $address;
				$responsearray['phoneres'] = trim($phoneres,',');
				$responsearray['cellres'] = trim($cellres,',');
				$responsearray['emailidres'] = trim($emailidres,',');
				$responsearray['region'] = $fetch['region'];
				$responsearray['branch'] = $fetch['branch'];
				$responsearray['businesstype'] = $fetch['businesstype'];
				$responsearray['customertype'] = $fetch['customertype'];
				$responsearray['gst_no'] = $new_gst_no;
				$responsearray['gstin_id'] = $gstin_id;
				$responsearray['sez_enabled'] = $sez_enabled;
				$responsearray['dealeridhid'] = $userid;
				$responsearray['dealername'] = $fetch['dealername'];
				$responsearray['podate'] = $podate;
			    $responsearray['state_gst_code'] = $fetch_gstn['state_gst_code'];
				$responsearray['poreference'] = $poreference;
				$responsearray['panno'] = $fetch['panno'];
				echo(json_encode($responsearray));
				
					//echo('1^'.$fetch['slno'].'^'.$customerid.'^'.$fetch['companyname'].'^'.trim($contactvalues,',').'^'.$address.'^'.trim($phoneres,',').'^'.trim($cellres,',').'^'.trim($emailidres,',').'^'.$fetch['region'].'^'.$fetch['branch'].'^'.$fetch['businesstype'].'^'.$fetch['customertype'].'^'.$fetch['dealername']);
			}
			else
			{
				$query1 = "SELECT count(*) as count from inv_mas_customer left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode where inv_mas_customer.slno = '".$lastslno."'".$dealerpiece."";
				$fetch1 = runmysqlqueryfetch($query1);
				if($fetch1['count'] > 0)
				{
					$query = "select inv_mas_customer.slno as slno, inv_mas_customer.businessname as companyname,inv_mas_customer.place,inv_mas_customer.address,inv_mas_region.category as region,inv_mas_branch.branchname as branch	,inv_mas_customercategory.businesstype as businesstype,inv_mas_customertype.customertype as customertype,inv_mas_dealer.businessname as dealername,inv_mas_customer.stdcode, inv_mas_customer.pincode,inv_mas_district.districtname, inv_mas_state.statename,inv_mas_customer.customerid  from inv_mas_customer left join inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode where inv_mas_customer.slno = '".$lastslno."'".$dealerpiece."";
					$fetch = runmysqlqueryfetch($query);
					
					// Fetch Contact Details 
		
					$querycontactdetails = "select  phone,cell,emailid,contactperson from inv_contactdetails where customerid = '".$lastslno."'";
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
					$pincode = ($resultfetch['pincode'] == '')?'':' Pin - '.$fetch['pincode'];
					$address = $fetch['address'].', '.$fetch['place'].', '.$fetch['districtname'].', '.$fetch['statename'].$pincode;
					$phonenumber = explode(',', trim($phoneres,','));
					$phone = $phonenumber[0];
					$cellnumber = explode(',', trim($cellres,','));
					$cell = $cellnumber[0];
					$emailid = explode(',', trim($emailidres,','));
					$emailidplit = $emailid[0];
					
					$responsearray['errorcode'] = "1";
					$responsearray['slno'] = $fetch['slno'];
					$responsearray['customerid'] = $customerid;
					$responsearray['companyname'] = $fetch['companyname'];
					$responsearray['contactvalues'] = trim($contactvalues,',');
					$responsearray['address'] = $address;
					$responsearray['phoneres'] = trim($phoneres,',');
					$responsearray['cellres'] = trim($cellres,',');
					$responsearray['emailidres'] = trim($emailidres,',');
					$responsearray['region'] = $fetch['region'];
					$responsearray['branch'] = $fetch['branch'];
					$responsearray['businesstype'] = $fetch['businesstype'];
					$responsearray['customertype'] = $fetch['customertype'];
					$responsearray['dealername'] = $fetch['dealername'];
					$responsearray['gst_no'] = $new_gst_no;
					$responsearray['gstin_id'] = $gstin_id;
				    $responsearray['sez_enabled'] = $sez_enabled;
				    $responsearray['state_gst_code'] = $fetch_gstn['state_gst_code'];
				    $responsearray['dealeridhid'] = $userid;
					$responsearray['podate'] = $podate;
					$responsearray['poreference'] = $poreference;
					$responsearray['panno'] = $fetch['panno'];
					echo(json_encode($responsearray));
					
					//echo('1^'.$fetch['slno'].'^'.$customerid.'^'.$fetch['companyname'].'^'.trim($contactvalues,',').'^'.$address.'^'.trim($phoneres,',').'^'.trim($cellres,',').'^'.trim($emailidres,',').'^'.$fetch['region'].'^'.$fetch['branch'].'^'.$fetch['businesstype'].'^'.$fetch['customertype'].'^'.$fetch['dealername'].'^'.$query);
				}
				else
				{
				$responsearray['slno'] = '';
				echo(json_encode($responsearray));	//echo(''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.'');
				}
			}
		}
		else
		{
			$responsearray['errorcode'] = "2";
			$responsearray['errormsg'] = "Invalid Customer";
			echo(json_encode($responsearray));
		}
	}
	break;
	
	case 'newupdationchange':
	{
		$customerlicenseid = $_POST['customerid'];
		//$productlicenceid = $_POST['productlicenceid'];
		$productname = $_POST['productname'];
		
		//$newString = preg_replace("/\d+$/","",$productname);
		//$newString = preg_replace("/[^A-Za-z- ]/",'',$productname);
		//echo $newString;
		
		//no need to change
		//$previousyear = "2014-15";
		
		$currentyearquery = "select year,subgroup from inv_mas_product where productname = '".$productname."' 
		order by year desc limit 1;";
		$currentyearfetch = runmysqlqueryfetch($currentyearquery);
		$currentyear = $currentyearfetch['year'];
		$subgroup = $currentyearfetch['subgroup'];
			//$currentyear = "2015-16";
		
		
		//query for taking 	current year updation card count	
		/*$newquery1 = "select count(inv_dealercard.purchasetype)as purchasetype from inv_dealercard
		left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
		left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
		where inv_dealercard.customerreference = ".$customerlicenseid."
		and inv_mas_product.subgroup = '".$subgroup."' and inv_mas_product.year = '".$currentyear."' 
		and inv_dealercard.purchasetype = 'updation' order by year desc";	
		$newresult1 = runmysqlqueryfetch($newquery1);
		$currentyearcard = $newfetch1['purchasetype'];*/
		
		$newquery1 = "select count(inv_dealercard.purchasetype)as purchasetype from inv_dealercard
		left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
		left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
		where inv_dealercard.customerreference = ".$customerlicenseid."
		and inv_mas_product.subgroup = '".$subgroup."' and inv_mas_product.year = '".$currentyear."' 
		and inv_dealercard.purchasetype = 'updation' order by year desc";
		$newfetch1 = runmysqlqueryfetch($newquery1);
		//$newfetch1 = mysqli_fetch_array($newresult1);
		$currentyearcard = $newfetch1['purchasetype'];
		
		//echo $currentyearcard;exit;
		
		//query for taking last two year
        if($productname == "Saral GST - V3")
        {
            $yearcount = ['2017-18','2019-20'];
        }
        else {
            $yearquery = "select distinct(year) from inv_mas_product where year!= '" . $currentyear . "' 
		order by year desc limit 2;";
            $yearresult = runmysqlquery($yearquery);
            while ($yearfetch = mysqli_fetch_array($yearresult)) {
                $yearcount[] = $yearfetch['year'];
            }
        }
		
		//query for taking last two year count
		$totalcards = "";
		$querychange1 = "select inv_mas_product.year from inv_dealercard
		left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
		left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
		where inv_dealercard.customerreference = ".$customerlicenseid."
		and inv_mas_product.subgroup = '".$subgroup."' and inv_mas_product.year in 
		('".$yearcount[0]."','".$yearcount[1]."') order by inv_mas_product.year desc limit 1";
		$resultchange1 = runmysqlquery($querychange1);
		$count = mysqli_num_rows($resultchange1);
		//$lasttwoyearcount = $count;
		
		
		if($count == 1)
		{
			$fetchchange1 = mysqli_fetch_array($resultchange1);
			$lasttwoyear = $fetchchange1['year'];
			
			//query for taking  card count based on last two year count
			$querychange2 = "select count(inv_dealercard.purchasetype) as purchasetype from inv_dealercard
			left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
			left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
			where inv_dealercard.customerreference = ".$customerlicenseid."
			and inv_mas_product.subgroup = '".$subgroup."' and inv_mas_product.year = '".$lasttwoyear."'";
			$fetchchange2 = runmysqlqueryfetch($querychange2);
			//$count2 = mysqli_num_rows($resultchange2);
			$totalcards = $fetchchange2['purchasetype'];
		}
			
		$custprodetails['totalcards'] = $totalcards;
		if($subgroup == 'ESS')
			$custprodetails['lasttwoyearcount'] = 1;
		else
        	$custprodetails['lasttwoyearcount'] = $count;
		$custprodetails['currentyearcard'] = $currentyearcard;
		
		echo(json_encode($custprodetails));
	}
	break;
	
	case 'searchbycustomerid':
	{
		$responsearray5 = array();
		$customerid = $_POST['customerid'];
		$customeridlen = strlen($customerid);
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
			$query = "select slno as slno, businessname as businessname,inv_mas_customer.gst_no as gst_no,inv_mas_customer.sez_enabled as sez_enabled from inv_mas_customer where branch in( select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."') order by businessname;";
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
					$dealerpiece = " AND (inv_mas_customer.region = '1' or inv_mas_customer.region = '3') ";
				}
				else
				{
					$query = "select slno as slno, businessname as businessname,inv_mas_customer.gst_no as gst_no,inv_mas_customer.sez_enabled as sez_enabled from inv_mas_customer where branch in( select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."') order by businessname;";
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
			
			if($fetch1['count'] > 0)
			{
				$query = "SELECT inv_mas_customer.slno, inv_mas_customer.customerid, inv_mas_customer.businessname,inv_mas_customer.gst_no as gst_no,inv_mas_customer.sez_enabled as sez_enabled,  inv_mas_customer.address, inv_mas_customer.place, inv_mas_customer.district,inv_mas_district.statecode as state, inv_mas_customer.pincode, inv_mas_customer.fax, inv_mas_customer.region, inv_mas_customer.stdcode,   inv_mas_customer.activecustomer, inv_mas_customer.website, inv_mas_customer.category, inv_mas_customer.type, inv_mas_customer.remarks, inv_mas_customer.currentdealer,  inv_mas_customer.initialpassword as password, inv_mas_customer.passwordchanged, inv_mas_customer.disablelogin, inv_mas_customer.corporateorder, inv_mas_customer.createddate,inv_mas_users.fullname, inv_mas_product.productname, inv_mas_district.districtname as districtname,inv_mas_state.statename as statename  FROM inv_mas_customer LEFT JOIN inv_mas_product ON inv_mas_product.productcode = inv_mas_customer.firstproduct  LEFT JOIN  inv_mas_users ON  inv_mas_users.slno = inv_mas_customer.createdby left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode  left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode where inv_mas_customer.slno = '".$customerid."'".$dealerpiece."";
				$fetch = runmysqlqueryfetch($query);
				
				// Fetch Contact Details
				
				$querycontactdetails = "select phone,cell,emailid,contactperson from inv_contactdetails where customerid = '".$lastslno."'";
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
				$phonenumber = explode(',', trim($phoneres,','));
				$phone = $phonenumber[0];
				$cellnumber = explode(',', trim($cellres,','));
				$cell = $cellnumber[0];
				$emailid = explode(',', trim($emailidres,','));
				$emailidplit = $emailid[0];
				
				$customerid = ($fetch['customerid'] == '')?'':cusidcombine($fetch['customerid']);	if($fetch['currentdealer'] == '')
				$currentdealer = '';
				else
				{
					$query = "select * from inv_mas_dealer where slno = '".$fetch['currentdealer']."'";
					$resultfetch = runmysqlqueryfetch($query);
					$currentdealer = $resultfetch['businessname'];
					
				}
				
				$responsearray5['cusslno'] = $fetch['slno'];
				$responsearray5['customerid'] = $customerid;
				$responsearray5['businessname'] = $fetch['businessname'];
				$responsearray5['contactvalues'] = trim($contactvalues,',');
				$responsearray5['address'] = $fetch['address'];
				$responsearray5['place'] = $fetch['place'];
				$responsearray5['district'] = $fetch['district'];
				$responsearray5['state'] = $fetch['state'];
				$responsearray5['pincode'] = $fetch['pincode'];
				$responsearray5['stdcode'] = $fetch['stdcode'];
				$responsearray5['phone'] = $phone;
				$responsearray5['cell'] = $cell;
				$responsearray5['emailidplit'] = $emailidplit;
				$responsearray5['website'] = $fetch['website'];
				$responsearray5['category'] = $fetch['category'];
				$responsearray5['type'] = $fetch['type'];
				$responsearray5['remarks'] = $fetch['remarks'];
				$responsearray5['disablelogin'] = $fetch['disablelogin'];
				$responsearray5['currentdealer'] = $currentdealer;
				$responsearray5['disablelogin'] = $fetch['disablelogin'];
				$responsearray5['createddate'] = changedateformatwithtime($fetch['createddate']);
				$responsearray5['corporateorder'] = strtolower($fetch['corporateorder']);
				$responsearray5['fax'] = $fetch['fax'];
				$responsearray5['userid'] = $userid;
				$responsearray5[''] = '';
				$responsearray5['activecustomer'] = strtolower($fetch['activecustomer']);
				$responsearray5['districtname'] = $fetch['districtname'];
				$responsearray5['statename'] = $fetch['statename'];
				$responsearray5['password'] = $fetch['password'];
				$responsearray5['gst_no'] = $fetch['gst_no'];
		        $responsearray5['sez_enabled'] = $fetch['sez_enabled'];
				$responsearray5['passwordchanged'] = strtolower($fetch['passwordchanged']);
				
				echo(json_encode($responsearray5));
				//echo('1^'.$fetch['slno'].'^'.$customerid.'^'.$fetch['businessname'].'^'.trim($contactvalues,',').'^'.$fetch['address'].'^'.$fetch['place'].'^'.$fetch['district'].'^'.$fetch['state'].'^'.$fetch['pincode'].'^'.$fetch['stdcode'].'^'.$phone.'^'.$cell.'^'.$emailidplit.'^'.$fetch['website'].'^'.$fetch['category'].'^'.$fetch['type'].'^'.$fetch['remarks'].'^'.$currentdealer.'^'.$fetch['disablelogin'].'^'.changedateformatwithtime($fetch['createddate']).'^'.strtolower($fetch['corporateorder']).'^'.$fetch['fax'].'^'.$userid.'^'.''.'^'.$fetch['activecustomer'].'^'.$fetch['districtname'].'^'.$fetch['statename'].'^'.$fetch['password'].'^'.strtolower($fetch['passwordchanged']));
			}
			else
			{
				$responsearray5['cusslno'] = "";
				echo(json_encode($responsearray5));
					//echo(''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.''.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.'');
			}
	}
	break;
	case 'calculateamount':
	{
		$selectedcookievalue = $_POST['selectedcookievalue'];
		$selectedcookievaluesplit = explode('#',$selectedcookievalue);
		$pricingtype = removedoublecomma($_POST['pricingtype']);
		$purchasevalues = removedoublecomma($_POST['purchasevalues']);
		$usagevalues = removedoublecomma($_POST['usagevalues']);
		$productamountvalues = removedoublecomma($_POST['productamountvalues']);
		$descriptiontypevalues = removedoublecomma($_POST['descriptiontypevalues']);
		$descriptionvalues = removedoublecomma($_POST['descriptionvalues']);
		$descriptionamountvalues = removedoublecomma($_POST['descriptionamountvalues']);
		$productquantityvalues = removedoublecomma($_POST['productquantityvalues']);
		$purchasevaluesplit = explode(',',$purchasevalues);
		$usagevaluesplit = explode(',',$usagevalues);
		$productamountsplit = explode(',',$productamountvalues);
		$productquantitysplit = explode(',',$productquantityvalues);
		$descriptionamountvaluesnew = str_replace(',','~',$descriptionamountvalues);
		$descriptiontypevaluesnew = str_replace(',','~',$descriptiontypevalues);
		$descamt = getdescriptionamount($descriptionamountvaluesnew,$descriptiontypevaluesnew);
		$seztaxtype = $_POST['seztaxtype'];
		$calculateamount = $_POST['calculateamount'];
		$productpricearray = implode('*',$productamountsplit);
		
		switch($pricingtype)
		{
			/*case 'normal':
			{
				$productcount = count($productamountsplit);
				$totalamount = 0;
				$servicetax = 0;
				$netamount = 0;
				for($i=0;$i<$productcount; $i++)
				{
					$totalamount += $productamountsplit[$i];
				}
				$totalamount = $totalamount + $descamt;*/
				//$currentdate = strtotime(date('Y-m-d'));
				//$expirydate = strtotime('2015-06-01');
				/*if($invoicedated == 'yes')
				{*/
					/*if($expirydate > $currentdate)
						//$servicetaxdetails = roundnearest($totalproductprice * 0.103);
						$servicetaxdetails = roundnearest($totalproductprice * 0.1236);
					else
						//$servicetaxdetails = roundnearest($totalproductprice * 0.1236);
						$servicetaxdetails = roundnearest($totalamount * 0.14);*/
						
				/*}
				else
				{
					if($expirydate > $currentdate)
						//$servicetaxdetails = roundnearest($totalproductprice * 0.103);
						$servicetaxdetails = roundnearest($totalproductprice * 0.1236);
					else
						//$servicetaxdetails = roundnearest($totalproductprice * 0.1236);
						$servicetaxdetails = roundnearest($totalamount * 0.14);
				}*/
				/*$servicetaxdetails = roundnearest($totalamount * 0.14);
				$sbtaxdetails = roundnearest($totalamount * 0.005);
						
				if($seztaxtype == 'yes')
					$servicetax = 0;
				else
				{
					$servicetax = $servicetaxdetails;
					$sbtax = $sbtaxdetails;
				}
				$netamount = $totalamount + $servicetax + $sbtax;
				$amount = '1^'.$totalamount.'^'.$servicetax.'^'.$netamount.'^'.$descamt;
			}
			break;*/
			case 'default':
			{
				$calculatedprice = calculatetotalamount($selectedcookievaluesplit,$purchasevaluesplit,$usagevaluesplit,$productquantitysplit,$pricingtype,$offeramount,$productpricearray);
				$calculatedpricesplit = explode('$',$calculatedprice);
				$totalproductprice = $calculatedpricesplit[0];
				$productpricearray = $calculatedpricesplit[1];
				$totalproductprice = $totalproductprice + $descamt;
				//$currentdate = strtotime(date('Y-m-d'));
				//$expirydate = strtotime('2015-06-01');
				/*if($invoicedated == 'yes')
				{
					if($expirydate > $currentdate)
						//$servicetaxdetails = roundnearest($totalproductprice * 0.103);
						$servicetaxdetails = roundnearest($totalproductprice * 0.1236);
					else
						//servicetaxdetails = roundnearest($totalproductprice * 0.1236);
						$servicetaxdetails = roundnearest($totalamount * 0.14);
				}
				else
				{
					if($expirydate > $currentdate)
						//$servicetaxdetails = roundnearest($totalproductprice * 0.103);
						$servicetaxdetails = roundnearest($totalproductprice * 0.1236);
					else
						//$servicetaxdetails = roundnearest($totalproductprice * 0.1236);
						$servicetaxdetails = roundnearest($totalamount * 0.14);
				}*/
				
				/*$servicetaxdetails = roundnearest($totalamount * 0.14);
				$sbtaxdetails = roundnearest($totalamount * 0.005);
		
				if($seztaxtype == 'yes')
					$servicetax = 0;
				else
				{
					$servicetax = $servicetaxdetails;
					$sbtax = $sbtaxdetails;
				}
				$netamount = $servicetax + $totalproductprice + $sbtax;
				$amount = '2^'.$productpricearray.'^'.$totalproductprice.'^'.$servicetax.'^'.$netamount.'^'.$calculatedprice;*/
				$amount = '2^'.$productpricearray;
			}
			break;
			case 'offer':
			{
				$prdcount = 0;
				$offeramount = $_POST['offeramount'];
				$calculatedprice = calculatetotalamount($selectedcookievaluesplit,$purchasevaluesplit,$usagevaluesplit,$productquantitysplit,$pricingtype,$offeramount,$productpricearray);
				$calculatedpricesplit = explode('$',$calculatedprice);
				$totalproductprice = $calculatedpricesplit[0];
				$productpricearray = $calculatedpricesplit[1];
				$productratio = productratio($selectedcookievaluesplit,$purchasevaluesplit,$usagevaluesplit,$offeramount,$productquantitysplit,$productpricearray);
				//$totalproductprice = $totalproductprice + $descamt;
				//$currentdate = strtotime(date('Y-m-d'));
				//$expirydate = strtotime('2015-06-01');
				/*if($invoicedated == 'yes')
				{
					if($expirydate > $currentdate)
						//$servicetaxdetails = roundnearest($totalproductprice * 0.103);
						$servicetaxdetails = roundnearest($totalproductprice * 0.1236);
					else
						//$servicetaxdetails = roundnearest($totalproductprice * 0.1236);
						$servicetaxdetails = roundnearest($totalamount * 0.14);
				}
				else
				{
					if($expirydate > $currentdate)
						//$servicetaxdetails = roundnearest($totalproductprice * 0.103);
						$servicetaxdetails = roundnearest($totalproductprice * 0.1236);
					else
						//$servicetaxdetails = roundnearest($totalproductprice * 0.1236);
						$servicetaxdetails = roundnearest($totalamount * 0.14);
				}*/
				
				/*$servicetaxdetails = roundnearest($totalamount * 0.14);
				$sbtaxdetails = roundnearest($totalamount * 0.005);
				
				if($seztaxtype == 'yes')
					$servicetax = 0;
				else
				{
					$servicetax = $servicetaxdetails;
					$sbtax = $sbtaxdetails;
				}
				$netamount = $servicetax + $totalproductprice + $sbtax;
				$amount = '2^'.$productpricearray.'^'.$totalproductprice.'^'.$servicetax.'^'.$netamount.'^'.$productratio;*/
				$amount = '2^'.$productpricearray;
				
			}
			break;
			case 'inclusivetax':
			{
				$prdcount = 0;
				$inclusivetaxamount = $_POST['inclusivetaxamount'];
				//$currentdate = strtotime(date('Y-m-d'));
				//$expirydate = strtotime('2012-04-04');
				//$expirydate = strtotime('2015-06-01');
				## Edited By Bhavesh changed roundnearest(($inclusivetaxamount*100)/(112.36))
				/*if($invoicedated == 'yes')
				{
					if($expirydate > $currentdate)
						//grossamountdetails = roundnearest(($inclusivetaxamount*100)/(110.3));
						$grossamountdetails = roundnearest(($inclusivetaxamount*100)/(112.36));
					else
						//$grossamountdetails = roundnearest(($inclusivetaxamount*100)/(112.36));
						$grossamountdetails = roundnearest(($inclusivetaxamount*100)/(114));
				}
				else
				{
					if($expirydate > $currentdate)
						//$grossamountdetails = roundnearest(($inclusivetaxamount*100)/(110.3));
						$grossamountdetails = roundnearest(($inclusivetaxamount*100)/(112.36));
					else
						//$grossamountdetails = roundnearest(($inclusivetaxamount*100)/(112.36));
						$grossamountdetails = roundnearest(($inclusivetaxamount*100)/(114));
				}*/
				
				$grossamountdetails = roundnearest(($inclusivetaxamount*100)/(118));
									
				$grossamount = $grossamountdetails;	
				if($seztaxtype == 'yes')
					$servicetax = 0;
				else
					$servicetax = $inclusivetaxamount - $grossamount;
				$calculatedprice = calculatetotalamount($selectedcookievaluesplit,$purchasevaluesplit,$usagevaluesplit,$productquantitysplit,$pricingtype,$grossamount,$productpricearray);
				$calculatedpricesplit = explode('$',$calculatedprice);
				$totalproductprice = $calculatedpricesplit[0];
				$productpricearray = $calculatedpricesplit[1];
				/*$totalproductprice = $totalproductprice + $descamt;
				$netamount = $servicetax + $totalproductprice;
				$amount = '2^'.$productpricearray.'^'.$totalproductprice.'^'.$servicetax.'^'.$netamount.'^'.$grossamount.'^'.$calculatedprice;*/
				$amount = '2^'.$productpricearray;
			}
			break;
		}
		echo(json_encode($amount.'^'.count($selectedcookievaluesplit)));
	}
	break;
	case 'invoicedetailsgrid':
	{
		$startlimit = $_POST['startlimit'];
		$slno = $_POST['slno'];
		$showtype = $_POST['showtype'];
		$lastslno = $_POST['lastslno'];
		$resultcount = "select inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_invoicenumbers.contactperson,inv_invoicenumbers.description,inv_invoicenumbers.invoiceno,inv_invoicenumbers.dealername,inv_invoicenumbers.createddate,inv_invoicenumbers.amount,inv_invoicenumbers.servicetax,inv_invoicenumbers.netamount,inv_invoicenumbers.purchasetype from inv_invoicenumbers left join dealer_online_purchase on dealer_online_purchase.onlineinvoiceno = inv_invoicenumbers.slno where right(customerid,5) = '".$lastslno."'  order by createddate  desc;";
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
		$query = "select inv_invoicenumbers.slno as invoicenumber, inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_invoicenumbers.contactperson,inv_invoicenumbers.description,inv_invoicenumbers.invoiceno,inv_invoicenumbers.dealername,inv_invoicenumbers.createddate,inv_invoicenumbers.amount,inv_invoicenumbers.servicetax,inv_invoicenumbers.netamount,inv_invoicenumbers.purchasetype,inv_invoicenumbers.createdby,inv_mas_receipt.receiptamount,dealer_online_purchase.slno as invoiceslno,inv_invoicenumbers.status,inv_invoicenumbers.seztaxtype as seztaxtype,inv_invoicenumbers.seztaxfilepath as seztaxfilepath from inv_invoicenumbers left join dealer_online_purchase on dealer_online_purchase.onlineinvoiceno = inv_invoicenumbers.slno
left join (select sum(receiptamount) as receiptamount,invoiceno as invoiceno from inv_mas_receipt group by invoiceno) as inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno 
where right(customerid,5) = '".$lastslno."'  order by createddate  desc LIMIT ".$startlimit.",".$limit.";"; // echo($query);exit;
		$result = runmysqlquery($query);
		if($startlimit == 0)
		{
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
			$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Payment</td><td nowrap = "nowrap" class="td-border-grid" align="left">Status</td><td nowrap = "nowrap" class="td-border-grid" align="left">Generated By</td><td nowrap = "nowrap" class="td-border-grid" align="left">Action<input type="hidden" name="invoicelastslno" id="invoicelastslno" /><input type="hidden" name="filepathinvoicing" id="filepathinvoicing" /></td><td nowrap = "nowrap" class="td-border-grid" align="left">Email</td><td nowrap = "nowrap" class="td-border-grid" align="left">SEZ Download</td></tr>';
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
			$grid .= '<tr class="gridrow1" bgcolor='.$color.'>';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slno."</td> ";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformatwithtime($fetch['createddate'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['invoiceno']."</td>";
			//if(($fetch['receiptamount'] == '') || ($fetch['receiptamount'] < $fetch['netamount']))

			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['netamount']."</td>";
			if($fetch['receiptamount'] == '' || $fetch['receiptamount'] < $fetch['netamount'])
			{
				if($fetch['status'] == 'CANCELLED')
				{
					$grid .= '<td  nowrap="nowrap" class="td-border-grid" align="center"><span class="redtext">CANCELLED</span></td>';
				}
				else
				{
					$grid .= '<td  nowrap="nowrap" class="td-border-grid" align="center">'.getpaymentstatus($fetch['receiptamount'],$fetch['netamount']).'&nbsp;<span class="resendtext" style = "cursor:pointer" onclick = "paynow(\''.$fetch['invoicenumber'].'\');">(Pay Now)</span></td>';
				}
			}
			else
			{
				if($fetch['status'] == 'CANCELLED')
				{
					$grid .= '<td  nowrap="nowrap" class="td-border-grid" align="center"><span class="redtext">CANCELLED</span></td>';
				}
				else
				{
					$grid .= "<td  nowrap='nowrap' class='td-border-grid' align='center'>".getpaymentstatus($fetch['receiptamount'],$fetch['netamount'])."&nbsp;</td>";
				}
			}
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['status']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['createdby']."</td>";
			$grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"> <a onclick="viewinvoice(\''.$fetch['invoicenumber'].'\');" class="resendtext" style = "cursor:pointer"> View >></a> </td>';
			$grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"> <div id= "resendemail'.$slno.'" style ="display:block;"><a onclick="resendinvoice(\''.$fetch['invoicenumber'].'\',\''.$slno.'\');" class="resendtext" style = "cursor:pointer">Resend </a></div><div id = "resendprocess'.$slno.'" style ="display:none;"></div> </td>';
			if($fetch['seztaxtype'] == 'yes')
			{
				$grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"><img src="../images/sez_download.gif" width="15" height="15" border="0" align="absmiddle" style="cursor:pointer" alt="Download" title="Download" onclick ="viewdownloadpath(\''.$fetch['seztaxfilepath'].'\')"/></td>';
			}
			else
			{
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>Not Avaliable</td>";

			}
			$grid .= "</tr>";
		}
		$grid .= "</table>";

		$fetchcount = mysqli_num_rows($result);
		if($slno >= $fetchresultcount)
		$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
		$linkgrid .= '<table><tr><td class="resendtext"><div align ="left" style="padding-right:10px"><a onclick ="getmoreinvoicedetails(\''.$lastslno.'\',\''.$startlimit.'\',\''.$slno.'\',\'more\');" style="cursor:pointer">Show More Records >></a>&nbsp;&nbsp;&nbsp;<a onclick ="getmoreinvoicedetails(\''.$lastslno.'\',\''.$startlimit.'\',\''.$slno.'\',\'all\');" class ="resendtext1" style="cursor:pointer"><font color= "#000000">(Show All Records)</font></a></div></td></tr></table>';
		
		$responsearray1 = array();
		$responsearray1['errorcode'] = "1";
		$responsearray1['grid'] = $grid;
		$responsearray1['fetchresultcount'] = $fetchresultcount;
		$responsearray1['linkgrid'] = $linkgrid;
		$responsearray1['invoicenosplit'] = $invoicenosplit[2];
		echo(json_encode($responsearray1));
		//echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid.'^'.$invoicenosplit[2];
	}
	break;
	case 'resendinvoice':
	{
		$invoiceno = $_POST['invoiceno'];
		$sent = resendinvoice($invoiceno);
		echo(json_encode($sent));
		//echo($sent);
	}
	break;
	case 'productsprices':
	{
		$productcode = $_POST['productcode'];
		$query = "select slno, purchasetype, usagetype, price from inv_relyonsoft_prices where productcode = '$productcode'";
		//exit();
		
		$result = runmysqlquery($query);
		
		$productpricearray = array();
		
		while($fetch_product_prices = mysqli_fetch_array($result))
			{
				$purchasetype = $fetch_product_prices['purchasetype'];
				$usagetype= $fetch_product_prices['usagetype'];
				$price= $fetch_product_prices['price'];
				
				$dataSting = $usagetype.$purchasetype;
				$productpricearray[$dataSting] = $price;
			}
			
			//var_dump($productpricearray);
			//exit();
		$query0 = "select subgroup from inv_mas_product where productcode = '$productcode'";
		$fetch0 = runmysqlqueryfetch($query0);
		$subgroup = $fetch0['subgroup'];

		$productpricearray['subgroup'] = $subgroup;
		echo(json_encode($productpricearray));	
	}
	break;
	case 'productspricesfill':
	{
		$productcode = $_POST['productcode'];
		$editpurchasetypehtml = strtolower($_POST['editpurchasetypehtml']);
		$editusagetypehtml = strtolower($_POST['editusagetypehtml']);
		
		if($editpurchasetypehtml == 'new')
		{
		  $query = "select price from inv_relyonsoft_prices where productcode = '$productcode' AND purchasetype = '$editpurchasetypehtml' AND usagetype = '$editusagetypehtml'";  
		  
		    $result = runmysqlquery($query);
		    $productpricearrays = array();
		    while($fetch_product_prices = mysqli_fetch_array($result))
		    {
				$price= $fetch_product_prices['price'];
				$productpricearrays['product_actual_prices'] = $price;
			}
		}
		else
		{
		    $query = "select updationprice from inv_relyonsoft_prices where productcode = '$productcode' AND purchasetype = '$editpurchasetypehtml' AND usagetype = '$editusagetypehtml'";
		
		    $result = runmysqlquery($query);
		    $productpricearrays = array();
		    
		    while($fetch_product_prices = mysqli_fetch_array($result))
		    {
				$price = $fetch_product_prices['updationprice'];
				$productpricearrays['product_actual_prices'] = $price;
			}
		}
		echo(json_encode($productpricearrays));	
	}
	break;
	case 'proceedforpurchase':
	{
		sleep(5);
		$gstcheck = $_POST['gstcheck'];
		$lastslno = $_POST['lastslno'];
		$result = checkcustomer($lastslno);
		if($result == 'true')
		{
			$selectedcookievalue = $_POST['selectedcookievalue'];
			$dealerid = $_POST['dealerid'];
			$selectedcookievaluesplit = explode('#',$selectedcookievalue);
			for($s=0; $s < count($selectedcookievaluesplit); $s++)
	     	{
    		    if($selectedcookievaluesplit[$s] != $selectedcookievaluesplit[$s-1])
    		    {
    		        $selectedproducts .= $selectedcookievaluesplit[$s].'#';
    		    }
	    	}
	    	$selectedproducts = rtrim($selectedproducts,'#');
			
			$pricingtype = $_POST['pricingtype'];
			
			//$customer_gstno = $_POST['customer_gstno'];
			
			$customer_gstno = $_POST['customer_gstno'];//actual gst_no
		    $customer_gst = $_POST['customer_gst'];//gstin_id from thelogs
			
			$servicetaxamount =0;
    		$sbtaxamount = 0;
    		$kktaxamount = 0;
    		$igstamount = $_POST['igstamount'];
    		$cgstamount = $_POST['cgstamount'];
    		$sgstamount = $_POST['sgstamount'];
			
			
			$purchasevalues = removedoublecomma(trim($_POST['purchasevalues'],','));
			$usagevalues = removedoublecomma(trim($_POST['usagevalues'],','));
			$productamountvalues = removedoublecomma(trim($_POST['productamountvalues'],','));
			$descriptiontypevalues = removedoublecomma(trim($_POST['descriptiontypevalues'],','));
			$descriptionvalues = removedoublecomma(trim($_POST['descriptionvalues'],','));
			$descriptionamountvalues = removedoublecomma(trim($_POST['descriptionamountvalues'],','));
			$productquantityvalues = removedoublecomma(trim($_POST['productquantityvalues'],','));
			$invoiceremarks = $_POST['invoiceremarks'];
			$paymentremarks = $_POST['paymentremarks'];
			$offeramount = $_POST['offeramount'];
			$offerremarks = $_POST['offerremarks'];
			$inclusivetaxamount = $_POST['inclusivetaxamount'];
			$paymenttype = $_POST['paymenttype'];
			$servicelist = $_POST['servicelist'];
			$serviceamountvalues = $_POST['serviceamountvalues'];
			$paymenttypeselected = $_POST['paymenttypeselected'];
			$paymentmode = $_POST['paymentmode'];
			$chequedate = changedateformat($_POST['chequedate']);
			$chequeno = $_POST['chequeno'];
			$drawnon = $_POST['drawnon'];
			$duedate = changedateformat($_POST['duedate']);
			$paymentamount = $_POST['paymentamount'];
			$depositdate = changedateformat($_POST['depositdate']);
			$purchasevaluesplit = explode(',',$purchasevalues);
			$usagevaluesplit = explode(',',$usagevalues);
			$productamountsplit = explode(',',$productamountvalues);
			$productquantitysplit = explode(',',$productquantityvalues);
			$servicelistsplit = explode('#',$servicelist);
			$serviceamountvaluessplit = explode('~',$serviceamountvalues);
			$descriptionamountvaluessplit = explode('~',$descriptionamountvalues);
			$descriptionvaluesplit = explode('~',$descriptionvalues);
			$descriptiontypevaluessplit = explode('~',$descriptiontypevalues);
			$cusname = $_POST['cusname'];
			$cuscontactperson = $_POST['cuscontactperson'];
			$cusaddress = $_POST['cusaddress'];
			$cusemail = $_POST['cusemail'];
			$cusphone = $_POST['cusphone'];
			$cuscell = $_POST['cuscell'];
			$customertype = $_POST['custype'];
			$businesstype = $_POST['cuscategory'];
			$invoicedated = $_POST['invoicedated'];
			$privatenote = $_POST['privatenote'];
			$podate = $_POST['podate'];
			$poreference = $_POST['poreference'];
			$servicelistvalues = $_POST['servicelistvalues'];
			$servicelistvaluessplit = explode('#',$servicelistvalues);
			
			$itemleveldescriptionvalues = $_POST['itemleveldescription'];
			$itemleveldescriptionlistsplit = explode('#',$itemleveldescriptionvalues);
			$productleveldescriptionvalues = $_POST['productleveldescription'];
			$productleveldescriptionlistsplit = explode('#',$productleveldescriptionlist);
			$seztaxtype = $_POST['seztaxtype'];
			$seztaxfilepath = $_POST['seztaxfilepath'];
			if($seztaxtype == 'yes')
			{
				$seztaxtype1 = $seztaxtype;
				$seztaxfilepath1 = $seztaxfilepath;
				$seztaxdate1 = date('Y-m-d').' '.date('H:i:s');
				$seztaxattachedby1 = $userid;
			}
			else
			{
				$seztaxtype1 = $seztaxtype;
				$seztaxfilepath1 = '';
				$seztaxdate1 = '';
				$seztaxattachedby1 = '';
			}
			
			#Added for Multiple GSTIN 
			
			$gstforinvoice = '';
			
			$select_gstin_count = "select count(*) as countgstin from customer_gstin_logs where customer_slno = '$lastslno' and gst_no = '$customer_gstno'";
			$fetch_gstin_count = runmysqlqueryfetch($select_gstin_count);
			
			if($fetch_gstin_count['countgstin'] == 0 && !empty($customer_gstno) && $customer_gstno != '') 
			{
				
				$effective_from = date('Y-m-d');
				
				$insert_gst = "insert into customer_gstin_logs (gstin_id,customer_slno,effective_from,gst_no,created_by,updated_by,usertype,created_at,updated_at) 
				values (NULL,$lastslno,'$effective_from','$customer_gstno','$userid','$userid','dealer_invoice',NOW(),NOW())";
				$result_gst_new = runmysqlquery($insert_gst);
				
				$query_gst1 = runmysqlqueryfetch("SELECT (MAX(gstin_id)) AS gstin_id_inserted FROM customer_gstin_logs where customer_slno = '$lastslno'");
				$gstin_id_inserted = $query_gst1['gstin_id_inserted'];
				
				$gstforinvoice = $gstin_id_inserted;
				
				//$update_customer = "update inv_mas_customer set gst_no = '$gstin_id_inserted' where slno = '$lastslno' limit 1";
				//$result_update_customer = runmysqlquery($update_customer);
				$query_customer_first_gstno = "select count(*) as count_gst from inv_mas_customer where slno = ".$lastslno." and gst_no = ''";
				$fetch_fist_gstno = runmysqlqueryfetch($query_customer_first_gstno);
				if($fetch_fist_gstno['count_gst'] == 1)
				{
					$update_customer = "update inv_mas_customer set gst_no = ".$gstin_id_inserted." where slno = '$lastslno' limit 1";
					$result_update_customer = runmysqlquery($update_customer);
				}
			   
			}
			else
			{
				if(empty($customer_gst) && $fetch_gstin_count['countgstin'] > 0)
				{
					$gstquery = runmysqlqueryfetch("SELECT gstin_id FROM customer_gstin_logs where customer_slno = '$lastslno' and gst_no = '$customer_gstno'");
					$gstin_id_inserted = $gstquery['gstin_id'];
					
					$gstforinvoice = $gstin_id_inserted;
				}
				else
					$gstforinvoice = $customer_gst;
			}    
			//echo $fetch_gstin_count['countgstin']. $gstforinvoice; exit;
	    	#aaded for multiple GSTIN Ends 
	    
			//echo($selectedcookievalue); exit;
			/*$productcheck = checkforproduct($lastslno,$selectedcookievaluesplit,$purchasevaluesplit,$usagevaluesplit);
			if($productcheck == 'true')
			{*/
			$regcheck = checkforproductregistration($lastslno,$selectedcookievaluesplit,$purchasevaluesplit);
			if($regcheck == 'true')
			{
					
				//define spp product array 
				/*$spparray = array('262','265','266','267','268','269','816','876','875','874','237','238','261','872');
					
				//Define array for services
				$queryservice = "select slno from inv_mas_service where disabled  = 'yes';";
				$serviceresultfetch = runmysqlquery($queryservice);
				while($fetchvalue = mysqli_fetch_array($serviceresultfetch))
				{
					$servicearray[] = $fetchvalue['slno'];
				}*/
				
				//server side check for product amount field
				for($i=1;$i<count($productamountsplit);$i++)
				{
					if($productamountsplit[$i] == '')
					{
						echo(json_encode('2^Invalid Entry1^'.$productamountvalues)); exit;
					}
				}
				
				//server side check for Add/Less amount field
				for($i=0;$i<count($descriptiontypevaluessplit);$i++)
				{
					if($descriptiontypevaluessplit[$i] <> '')
					{
						if($descriptionvaluesplit[$i] == '')
						{
							echo(json_encode('2^Invalid Entry2^'.$descriptiontypevalues)); exit;
						}
						if($descriptionamountvaluessplit[$i] == '')
						{
							echo(json_encode('2^Invalid Entry3^'.$descriptionamountvalues)); exit;
						}
					}
				}
				
				//Check for Dealer region if CSD/BKG SPP invoice should not be generated
				$dealerquery = "select * from inv_mas_dealer where slno = '".$userid."';";
				$dealerresultfetch = runmysqlqueryfetch($dealerquery);
				$dealerregion1 = $dealerresultfetch['region'];
				
				/*if($dealerregion1 == '1' || $dealerregion1 == '2')
				{
					if(matcharray($spparray,$selectedcookievaluesplit))
					{
						echo('2^SPP Invoice cannot be generated^'); exit;
					}
					elseif(matcharray($servicearray,$servicelistvaluessplit))
					{
						echo('2^SPP Invoice cannot be generated^'); exit;
					}
				}*/

				//Calculate Add/Less amount
				$descamt = getdescriptionamount($descriptionamountvalues,$descriptiontypevalues);
				
				//Calculate total Service amount 
				$serviceamountvaluessplit = explode('~',$serviceamountvalues);
				for($i=0;$i<count($serviceamountvaluessplit);$i++)
				{
					$totalserviceamount +=  (int)$serviceamountvaluessplit[$i];
				}
				
			
				//Assign service tax amount
				$servicetax = $servicetaxamount;
				
				//Assign sb cess amount
				$sbtax = $sbtaxamount;
				$kktax = $kktaxamount;

				$pricingamount = 0;
				if($selectedcookievalue <> '')
				{
					//Recalculation of amount
					$calculatedprice = calculatenormalprice($productamountsplit,$productquantitysplit);
					$calculatedpricesplit = explode('$',$calculatedprice);
					
					//total product price with quantity multiplied
					$totalproductprice = $calculatedpricesplit[0];
					
					//Product price without quantity
					$productpricearray = $calculatedpricesplit[1];
					
					//Product prices in array
					$totalproductpricearray = $calculatedpricesplit[2];
					
					//Calculate actual product price to insert in dealer online purchase table
					$actualcalculatedprice = calculatetotalamount($selectedcookievaluesplit,$purchasevaluesplit,$usagevaluesplit,$productquantitysplit,$pricingtype,$offeramount,$productpricearray);
					$actualcalculatedpricesplit = explode('$',$actualcalculatedprice);
					$actualproductprice = $actualcalculatedpricesplit[2];
						
					//Calculate Total amount
					$totalproductprice = $totalproductprice + $descamt + $totalserviceamount;
				}
				else
				{
					$totalproductprice = '';
					$productpricearray = '';
					$totalproductpricearray = '';
					//Calculate Total amount
					$totalproductprice =  $descamt + $totalserviceamount;
				}
				if($servicetax!= 0)
				{
					/*$expirydate = strtotime('2015-06-01');
					$currentdate = strtotime(date('Y-m-d'));
						
					if($expirydate > $currentdate)
					{*/
						/*$servicetax1 = roundnearestvalue($totalproductprice * 0.1);
						$servicetax2 = roundnearestvalue($servicetax1 * 0.02);
						$servicetax3 = roundnearestvalue(($totalproductprice * 0.103) - (($servicetax1) + ($servicetax2)));*/
						
						/*$servicetax1 = roundnearestvalue($totalproductprice * 0.12);
						$servicetax2 = roundnearestvalue($servicetax1 * 0.02);
						$servicetax3 = roundnearestvalue(($totalproductprice * 0.1236) - (($servicetax1) + 
						($servicetax2)));
					}
					else
					{*/
						/*$servicetax1 = roundnearestvalue($totalproductprice * 0.12);
						$servicetax2 = roundnearestvalue($servicetax1 * 0.02);
						$servicetax3 = roundnearestvalue(($totalproductprice * 0.1236) - (($servicetax1) + 
						($servicetax2)));*/
						/*$servicetax1 = roundnearestvalue($totalproductprice * 0.14);
						$servicetax2 = 0;
						$servicetax3 = 0;
					}*/
					$servicetax1 = roundnearestvalue($totalproductprice * 0.14);
					$servicetax2 = 0;
					$servicetax3 = 0;
					$servicetax = $servicetax1 + $servicetax2 + $servicetax3;
					$sbtax = roundnearestvalue($totalproductprice * 0.005);
					
						$kktax = roundnearestvalue($totalproductprice * 0.005);
					
				}
				$netamount = $servicetax + $totalproductprice + $sbtax + $kktax + $igstamount + $cgstamount + $sgstamount;
				$netamount = round($netamount);
				
				//Get the customer details
				$query1 = "select * from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode =inv_mas_customer.district left join inv_mas_state on inv_mas_state.statecode =inv_mas_district.statecode left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region left join inv_mas_branch on  inv_mas_branch.slno = inv_mas_customer.branch left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category  where inv_mas_customer.slno = '".$lastslno."';";
				$fetch = runmysqlqueryfetch($query1);
				$einvoiceadd = $fetch['address'];
			
				// Fetch Contact Details
				$querycontactdetails = "select customerid, GROUP_CONCAT(contactperson) as contactperson,  
				GROUP_CONCAT(phone) as phone, GROUP_CONCAT(cell) as cell, GROUP_CONCAT(emailid) as emailid from inv_contactdetails where customerid = '".$lastslno."'  group by customerid ";
				$resultcontactdetails = runmysqlqueryfetch($querycontactdetails);
				
				$contactvalues = removedoublecomma($resultcontactdetails['contactperson']);
				$phoneres = removedoublecomma($resultcontactdetails['phone']);
				$cellres = removedoublecomma($resultcontactdetails['cell']);
				$emailidres = removedoublecomma($resultcontactdetails['emailid']);
				
				//fetch the details from customer pending table
				$query22 = "SELECT count(*) as count from inv_contactreqpending where customerid = '".$lastslno."' and customerstatus = 'pending' and editedtype = 'edit_type'";
				$result22 = runmysqlqueryfetch($query22);
				if($result22['count'] == 0)
				{
					$resultantemailid = $emailidres;
				}
				else
				{
					// Fetch of contact details, from pending request table if any
					$querycontactpending = "select GROUP_CONCAT(emailid) as pendemailid from inv_contactreqpending where customerid = '".$lastslno."' and customerstatus = 'pending' and editedtype = 'edit_type' group by customerid ";
					$resultcontactpending = runmysqlqueryfetch($querycontactpending);
					
					$emailidpending = removedoublecomma($resultcontactpending['pendemailid']);
					
					$finalemailid = $emailidres.','.$emailidpending;
					$resultantemailid = remove_duplicates($finalemailid);
				}
				
				$finalemailids = $resultantemailid.','.$cusemail;
				$resultantemailid = remove_duplicates($finalemailids);
				//Fetched customer contact details
				$generatedcustomerid = $fetch['customerid'];
				$phonenumber = explode(',', $cusphone);
				$phone = $phonenumber[0];
				$cellnumber = explode(',',$cuscell);
				$cell = $cellnumber[0];
				$businessname = $cusname;
				$address = addslashes($cusaddress);
				$place = $fetch['place'];
				$district = $fetch['districtcode'];
				$state = $fetch['statename'];
				$pincode = $fetch['pincode'];
				$branchname = $fetch['branchname'];
				$custcontactperson = trim($cuscontactperson,',');
				$stdcode = $fetch['stdcode'];
				$phone = $phonenumber[0];
				$fax = $fetch['fax'];
				$cell = $cellnumber[0];
				$custemailid = trim($cusemail,',');
				//$category = $fetch['category'];
				//$type = $fetch['type'];
				if($dealerid == '')
				{
					$currentdealer = $userid;
				}
				else
				{
					$currentdealer = $dealerid;
				}
				
				$customerid17digit = $fetch['customerid'];
				$category = $businesstype;
				$type = $customertype;
				
				//echo($contactperson); exit;
				//Check for payment type to update payment remarks 
				if($paymenttype == 'credit/debit')
				{
					$paymentremarksnew = 'Selected for Credit/Debit Card Payment. This is subject to successful transaction';
					$paymenttypeselected = 'paymentmadelater';
					$paymentmode = 'credit/debit';
				}
				
				$qrtype= '';
				if($paymenttype == 'upi')
				{
					$paymentremarksnew = 'Selected for UPI Payment.This is subject to successful transaction.';
					$paymenttypeselected = 'paymentmadelater';
					$paymentmode = 'upi';
					$qrtype = 'upi';
				}
				
				//Define payment remarks for the invoice
				if($paymenttypeselected == 'paymentmadelater')
					$paymentremarksnew = 'Payment Due!! (Due Date: '.changedateformat($duedate).') '.$paymentremarks;
				else
				{
					if($paymentmode == 'chequeordd')
						$paymentremarksnew = 'Received Cheque No: '.$chequeno.', dated '.changedateformat($chequedate).', drawn on '.$drawnon.', for amount '.$paymentamount.'. Cheques received are subject to realization.';
					else if($paymentmode == 'cash')
						$paymentremarksnew = $paymentremarks;
					else
						$paymentremarksnew = 'Payment through Online Transfer. '.$paymentremarks.'';
				}
				$invoiceremarks = ($invoiceremarks == '')?'None':$invoiceremarks;
				$paymentremarksnew = ($paymentremarksnew == '')?'None':$paymentremarksnew;

				//If it is a new customer, generate new customer id and update it in Customer Master
				if($customerid17digit  == '')
				{
					if($selectedcookievalue <> '')
						$firstproduct = $selectedcookievaluesplit[0];
					else
						$firstproduct = '000';
					//Get new customer id
					$query = "select statecode from inv_mas_district where districtcode  = '".$district."';";
					$fetch = runmysqlqueryfetch($query);
					$statecode = $fetch['statecode'];
					$newcustomerid = $statecode.$district.$currentdealer.$firstproduct.$lastslno;
					$password = generatepwd();
					//update customer master with customer product
					$query = "update inv_mas_customer set firstdealer = '".$currentdealer."' , firstproduct = '".$firstproduct."', 
					initialpassword = '".$password."', loginpassword = AES_ENCRYPT('".$password."','imaxpasswordkey'),
					customerid = '".$newcustomerid."' where slno = '".$lastslno."';";
					$result = runmysqlquery($query);
					$generatedcustomerid = $newcustomerid;
					sendwelcomeemail($lastslno, $userid);
			
				}

				//Check for payment type to update payment remarks 
				if($paymenttype == 'credit/debit')
				{
					$paymentremarks = 'Selected for Credit/Debit Card Payment. This is subject to successful transaction';
					$paymenttypeselected = 'paymentmadelater';
					$paymentmode = 'credit/debit';
				}
				
				//Fetch the max slno from dealer online purchase table
				$countquery = "select ifnull(max(slno),0) + 1 as slnotobeinserted from dealer_online_purchase;";
				$fetchcount = runmysqlqueryfetch($countquery);
				$slnotobeinserted = $fetchcount['slnotobeinserted'];
							
				//If payment is made later, set the payment mode to empty
				if($paymenttypeselected == 'paymentmadelater')
				{
					$paymentmode = "";
					$duedate = $duedate;
				}
				else
					$duedate = date('Y-m-d');
				//Insert the purchase details in dealer online purchase table
				$query = "insert into `dealer_online_purchase`(slno,customerreference,businessname,address,place,district,state,pincode,contactperson,stdcode,phone,fax,cell,emailid,category,type,currentdealer,amount,netamount,servicetax,sbtax,kktax, igst,cgst,sgst, products, paymentdate, paymenttime, purchasetype, paymenttype, usagetype, offertype, offerdescription, offeramount, invoiceremarks, paymentremarks,quantity,pricingtype,pricingamount,productpricearray,createdby,createdip,createddate,lastmodifieddate,lastmodifiedip,lastmodifiedby,totalproductpricearray,offerremarks,module,service,serviceamount,paymenttypeselected,paymentmode,actualproductprice,duedate,privatenote,podate,poreference,productbriefdescription,itembriefdescription,seztaxtype,seztaxfilepath,seztaxdate,seztaxattachedby) values
				('".$slnotobeinserted."','".$lastslno."','".$businessname."','".addslashes($address)."','".$place."','".$district."','".$state."','".$pincode."','".$custcontactperson."','".$stdcode."','".$phone."','".$fax."','".$cell."','".$custemailid."','".$category."','".$type."','".$currentdealer."','".$totalproductprice."','".$netamount."','".$servicetax."','".$sbtax."','". $kktax ."','".$igstamount."','".$cgstamount."','".$sgstamount."','".$selectedproducts."','','','".$purchasevalues."','".$paymenttype."','".$usagevalues."','".$descriptiontypevalues."','".$descriptionvalues."','".$descriptionamountvalues."','".$invoiceremarks."','".$paymentremarks."','".$productquantityvalues."','".$pricingtype."','".$pricingamount."','".$productpricearray."','".$userid."','".$_SERVER['REMOTE_ADDR']."','".date('Y-m-d').' '.date('H:i:s')."','".date('Y-m-d').' '.date('H:i:s')."','".$_SERVER['REMOTE_ADDR']."','".$userid."','".$totalproductpricearray."','".$offerremarks."','dealer_module','".$servicelist."','".$serviceamountvalues."','".$paymenttypeselected."','".$paymentmode."','".$actualproductprice."','".$duedate."','".$privatenote."','".changedateformat($podate)."','".$poreference."','".$productleveldescriptionvalues."','".$itemleveldescriptionvalues."','".$seztaxtype1."','".$seztaxfilepath1."','".$seztaxdate1."','".$seztaxattachedby1."')";
				$result = runmysqlquery($query);
				//$amount = '1^'.$calculatedprice.'^'.$totalproductprice.'^'.$servicetax.'^'.$netamount.'^'.$slnotobeinserted;

				if($selectedcookievalue <> '')
				{
					//Update current dealer of customer  with logged in dealer id
					$query = "update inv_mas_customer set currentdealer = '".$currentdealer."'  where slno = '".$lastslno."';";
					$result = runmysqlquery($query);
				}
				
				//Get the logged in  dealer details
				$query0 = "select billingname,inv_mas_region.category as region,inv_mas_dealer.emailid as dealeremailid,inv_mas_dealer.region as regionid,inv_mas_dealer.branch  as branchid,inv_mas_dealer.district as dealerdistrict  from inv_mas_dealer left join inv_mas_region on inv_mas_region.slno = inv_mas_dealer.region  where inv_mas_dealer.slno = '".$currentdealer."';";
				$fetch0 = runmysqlqueryfetch($query0);
				$dealername = $fetch0['billingname'];
				$dealerregion = $fetch0['region'];
				$dealeremailid = $fetch0['dealeremailid'];
				$regionid = $fetch0['regionid'];
				$branchid = $fetch0['branchid'];
				$dealerdistrict = $fetch0['dealerdistrict'];

				//update region and branch of customer as per dealer
				$panno = ($_POST['panno'] == '') ? "": " , panno = '".$_POST['panno']."'";
				$query11 = "update inv_mas_customer set branch = '".$branchid."', region = '".$regionid."'".$panno."  where slno = '".$lastslno."';";
				$result11 = runmysqlquery($query11);
			
				//Get the next record serial number for insertion in invoicenumbers table
				// $query1 = "select ifnull(max(slno),0) + 1 as billref from inv_invoicenumbers";
				// $resultfetch1 = runmysqlqueryfetch($query1);
				// $onlineinvoiceslno = $resultfetch1['billref'];
				
				//Get the next invoice number from invoicenumbers table, for this new_invoice
			
			
				//inserted by saraswati
				//to select gstin no of customer
				$year = '2022';
				$customer_gstcode = '';
				if(!empty($customer_gstno) && $customer_gstno != '')
				{
					$customer_gstcode = substr($customer_gstno, 0, 2);
					
				}

				//$customer_statecode = $fetch['statecode'];
					
				//to select state code of customer
				$querystatecode = "select statecode from inv_mas_district where districtcode  = '".$district."';";
				$querystatefetch = runmysqlqueryfetch($querystatecode);
						
				$statecode = $querystatefetch['statecode'];
				$querystategstcode = "select state_gst_code from inv_mas_state where statecode = '".$statecode."';";
				
				$customer_stategstno_fetch = runmysqlqueryfetch($querystategstcode);  
				
				if($customer_gstcode == '')
				{    
					$customer_gstcode = $customer_stategstno_fetch['state_gst_code'];
				}
				
				//get cgst and sgst detail

				// if($customer_gstcode == '29')
				// {   
				// 	//$state_info = 'L';
				// 		//$onlineinvoiceno='000001';
				// 		//selct online invoice number
				// 		//Get the next invoice number from invoicenumbers table, for this new_invoice
						
				// 		$varState = '2022RL';
				// 		$state_info = 'L';
						
				// 		//$queryonlineinv = "select ifnull(max(onlineinvoiceno),0)+ 1 as invoicenotobeinserted from inv_invoicenumbers where state_info = '".$state_info."'";
						
				// 		$queryonlineinv = "select ifnull(max(onlineinvoiceno),0)+ 1 as invoicenotobeinserted from inv_invoicenumbers where invoiceno like '%".$varState."%'";
						
				// 		$resultfetchinv = runmysqlqueryfetch($queryonlineinv);
				// 		$onlineinvoiceno = $resultfetchinv['invoicenotobeinserted'];
				// 		$onlineinvoiceno=(string)$onlineinvoiceno;
				// 		$onlineinvoiceno=sprintf('%06d', $onlineinvoiceno);
				// 		$invoicenoformat = 'RSL'.$year.'R'.$state_info.''.$onlineinvoiceno;
				// }
				// else
				// {
				// 	//$onlineinvoiceno='000100';
				// 	//$state_info = 'I';
				// 	//selct online invoice number
				// 	//Get the next invoice number from invoicenumbers table, for this new_invoice
					
				// 		$varState = '2022RI';
				// 		$state_info = 'I';
					
				// 	//$queryonlineinv = "select ifnull(max(onlineinvoiceno),0)+ 1 as invoicenotobeinserted from inv_invoicenumbers where state_info = '".$state_info."'";
					
				// 		$queryonlineinv = "select ifnull(max(onlineinvoiceno),0)+ 1 as invoicenotobeinserted from inv_invoicenumbers where  invoiceno like '%".$varState."%'";
					
				// 	$resultfetchinv = runmysqlqueryfetch($queryonlineinv);
				// 	$onlineinvoiceno = $resultfetchinv['invoicenotobeinserted'];
				// 	$onlineinvoiceno=sprintf('%06d', $onlineinvoiceno);
				// 	$onlineinvoiceno=(string)$onlineinvoiceno;		
				// 	$invoicenoformat = 'RSL'.$year.'R'.$state_info.''.$onlineinvoiceno;
				// }

		
				//Get the statename and district name
				$query112 = "select districtname,statename,category as regionname,branchname from inv_mas_district left join inv_mas_state on inv_mas_district.statecode = inv_mas_state.statecode left join inv_mas_region on inv_mas_region.slno = inv_mas_district.region
				left join inv_mas_branch on inv_mas_branch.slno = inv_mas_district.branchid where inv_mas_district.districtcode = '".$dealerdistrict."'";
				$resultfetch112 = runmysqlqueryfetch($query112);
				$districtname = $resultfetch112['districtname'];
				$statename = $resultfetch112['statename'];
				$branchname = $resultfetch112['branchname'];
				
				//echo($query112 ); exit;
				$emailid = explode(',', trim($cusemail,','));
				$emailidplit = $emailid[0];
				$phonenumber = explode(',', trim($cusphone,','));
				$phonenumbersplit = $phonenumber[0];
				$cellnumber = explode(',', trim($cuscell,','));
				$cellnumbersplit = $cellnumber[0];
				$contactperson = explode(',', trim($cuscontactperson,','));
				$contactpersonplit = $contactperson[0];
				$stdcode = ($stdcode == '')?'':$stdcode.' - ';
				$address1 = $address;
				$invoiceheading = ($statename == 'Karnataka')?'Tax Invoice':'Tax Invoice';
				$branchname = $branchname;
				$amountinwords = convert_number($netamount);
				$servicetaxdesc = 'Service Tax Category: Information Technology Software (zzze), Support(zzzq), Training (zzc), Manpower(k), Salary Processing (22g), SMS Service (b)';
				//$invoicedate = date('Y-m-d').' '.date('H:i:s');
				/*$spparray = array('262','265','266','267','268','269','816','876','875','874','237','238','261','872');
				if(matcharray($spparray,$selectedcookievaluesplit))
				{
					$invoicedate = '2011-06-30 23:55:00';
				}
				else
				{
					$invoicedate = date('Y-m-d').' '.date('H:i:s');
				}*/
				## Year Ending Changing Year . . . . ##
				$currentdate = strtotime(date('Y-m-d'));
				//$currentdate = strtotime('2012-04-05');
				$expirydate1 = strtotime('2019-04-03');
			
				if($invoicedated == 'yes')
				{	
					if($expirydate1 > $currentdate)
						$invoicedate = '2019-03-31 23:55:00';
						//$invoicedate = '2014-03-31 23:55:00';
				else
						$invoicedate = date('Y-m-d').' '.date('H:i:s');
						//$invoicedate = '2012-04-01 23:55:00';
				}
				else
					$invoicedate = date('Y-m-d').' '.date('H:i:s');
					//$invoicedate = '2012-04-01 23:55:00';
				
				if(!empty($customer_gstno) && $customer_gstno != '')
				{
					if($gstcheck!= 'gstconfirm')
					{
						//echo "in"; exit;
						$productitemgrid = $serviceitemgrid = $totalitemgrid = array();
						$itemcount = 1;
						$totalitemvalue = $totalitemval = 0;
						$addamt = $discamt = 0;
						$final_amount = 0;;
						$finaltotalamt = $discount =  0;
			
						if($igstamount!= 0)
						{
							$igstamt = $igstamount;
							$cgstamt = 0;
							$sgstamt = 0;
						}
						else{
							$cgstamt = $cgstamount;
							$sgstamt = $sgstamount;
							$igstamt = 0;
						}
						$addition_amount = $totalproductprice+$igstamt+$cgstamt+$sgstamt;
						//echo $addition_amount;
						$roundoff_value = $netamount- round($addition_amount,2);
						//echo $fetch['netamount'] . "amount ". $addition_amount;
						if($roundoff_value != 0 || $roundoff_value != 0.00)
						{
							$roundoff = 'true';
						}
						if($roundoff == 'true')
							$roundoff_value = $roundoff_value;
						else 
							$roundoff_value = 0;
			
						//per product price
						$productpricearraysplit = explode('*',$productpricearray);
			
						if($descriptiontypevalues <> '')
						{
							for($i=0; $i<count($descriptiontypevaluessplit); $i++)
							{
								if($descriptiontypevaluessplit[$i] == 'add')
									$addamt = $descriptionamountvaluessplit[$i];
								elseif($descriptiontypevaluessplit[$i] == 'less' || $descriptiontypevaluessplit[$i] == 'amount' || $descriptiontypevaluessplit[$i] == 'percentage')
									$discamt +=$descriptionamountvaluessplit[$i];
								
								//$offerdescriptiongrid .= $descriptiontypevaluessplit[$i].'$'.$descriptionvaluesplit[$i].'$'.$descriptionamountvaluessplit[$i];			
							}
							//echo $discamt; exit;
							if($selectedcookievalue <> '')
							{
								for($x=0;$x<count($productquantitysplit);$x++)
								{
									//echo $productpricearraysplit[$x];
									$finaltotalamt = $productpricearraysplit[$x] * $productquantitysplit[$x];
			
									$final_amount +=  (int)$finaltotalamt;
								}
							}
							//echo "gross" . $final_amount; exit;
							if($servicelist <> '')
							{
								for($y=0; $y<count($servicelistsplit); $y++)
								{
									$final_amount = $final_amount + $serviceamountvaluessplit[$y];
								}
							}
						}
						$totaldiscamt = round($discamt,2);
			
						if($selectedcookievalue <> '')
						{
							$itemcount = 1;
							$totalamt = $taxableamt = 0;
							$unitigstamt = $unitcgstamt = $unitsgstamt = 0;
							//Total product quantity
							for($i=0;$i<count($productquantitysplit);$i++)
							{
								$totalproductquantity += $productquantitysplit[$i];
							}

							for($l=0;$l<$totalproductquantity;$l++)
							{
								// for($m=0;$m<$productquantitysplit[$l];$m++)
								// {
									if($descriptiontypevalues <> '')
									{
										if($addamt!=0 && $discamt ==0)
										{
											$totalamt0 = $productpricearraysplit[$l];
											$productper = ($totalamt0/$final_amount)*100;
											$proper1 = round($productper);
											$additionalamt = round(($addamt*$proper1)/100);
											$discount = 0;
											$totalamt = $totalamt0 + $additionalamt;
											$taxableamt = $totalamt;
										}
										else
										{
											$totalamt = $productpricearraysplit[$l];
											$productper = ($totalamt/$final_amount)*100;
											$proper1 = round($productper); 
											$discount = round(($totaldiscamt*$proper1)/100);
											$taxableamt = $totalamt - $discount;
										}
									}
									else{
											$totalamt = $productpricearraysplit[$l];
											$taxableamt = $totalamt;
											$discount = 0;
									}
			
									if($seztaxtype == 'yes')
									{
										$unitcgstamt = $unitsgstamt = $unitigstamt = 0;
									}
									else
									{
										if($igstamount!= 0)
										{
											$numberigst = ($taxableamt*18)/100;
											$unitigstamt = round($numberigst,2);
											$unitcgstamt = $unitsgstamt = 0;
										}
										else
										{
											$numbercgstamt = ($taxableamt * 9)/100;
											$unitcgstamt = round($numbercgstamt,2);
			
											$numbersgstamt = ($taxableamt * 9)/100;
											$unitsgstamt = round($numbersgstamt,2);
											$unitigstamt =  0;
										}
									}
									
									//final value per product
									$totalitemval = $taxableamt + $unitigstamt + $unitcgstamt + $unitsgstamt;
			
			
									if($unitcgstamt == 0 && $unitsgstamt == 0)
									{
										$num1 = (int)$unitcgstamt;
										$num2 = (int)$unitsgstamt;
										if(is_float($unitigstamt))
										{
											$num3 = (float)$unitigstamt;
										}
										else
											$num3 = (int)$unitigstamt;
									}
									else
									{
										$num3 = (int)$unitigstamt;
										if(is_float($unitcgstamt) && is_float($unitsgstamt))
										{
											$num1 = (float)$unitcgstamt;
											$num2 = (float)$unitsgstamt;
										}
										else
										{
											$num1 = (int)$unitcgstamt;
											$num2 = (int)$unitsgstamt;
										}
									}
									if(is_float($discount))
										$prodiscount = (float)$discount;
									else
										$prodiscount = (int)$discount;
										
									$productitemgrid[] = array( "SlNo"=> (string)$itemcount++,
									"IsServc"=> 'Y',
									"HsnCd"=> '998434',
									"UnitPrice"=> (int)$totalamt , //price per product
									"TotAmt"=> (int)$totalamt,		//unit price * quantity = toatlamt
									"AssAmt"=> (int)$taxableamt, //gross amt(toatlamt) - discount
									"Discount"=> $prodiscount,					//consider if any
									"GstRt"=> 18,
									"IgstAmt"=> $num3,		//calculate GST based on per product
									"CgstAmt"=> $num1,		//calculate GST based on per product
									"SgstAmt"=> $num2,		//calculate GST based on per product
									"TotItemVal"=> round($totalitemval,2) 	//taxableamt + gst
									);
			
								//}
								
							}
					
						}
					
						if($servicelist <> '')
						{
							$servicecount = 0;
							$itemothers = $itemper1 = 0;
							$unititemigstamt = $unititemcgstamt = $unititemsgstamt = 0;
							for($i=0; $i<count($servicelistsplit); $i++)
							{
								if($descriptiontypevalues <> '')
								{
									if($addamt!= 0)
									{
										$itemothers0 = $serviceamountvaluessplit[$i];
										$itemper = ($itemothers0/$final_amount)*100;
										$itemper1 = round($itemper);
										$itemaddition = round(($addamt*$itemper1)/100);
										$itemothers = $itemothers0 + $itemaddition;
										$itemdiscount = 0;
										$taxableitemamt = $itemothers;
			
									}
									else
									{
										$itemothers = $serviceamountvaluessplit[$i];
										$itemper = ($itemothers/$final_amount)*100;
										$itemper1 = round($itemper);
										$itemdiscount = round(($totaldiscamt*$itemper1)/100);
										$taxableitemamt = $itemothers - $itemdiscount;
									}	
								}
								else{
										$itemothers = $serviceamountvaluessplit[$i];
										$taxableitemamt = $itemothers;
										$itemdiscount = 0;
								}
			
								if($seztaxtype == 'yes')
								{
									$unititemcgstamt = $unititemsgstamt = $unititemigstamt = 0;
								}
								else
								{
									if($igstamount!= 0)
									{
										$numberitemigstamt = ($taxableitemamt * 18)/100;
										$unititemigstamt = round($numberitemigstamt,2);
										$unititemcgstamt = $unititemsgstamt = 0;
									}
									else{
										$numberitemcgstamt = ($taxableitemamt * 9)/100;
										$unititemcgstamt = round($numberitemcgstamt,2);
										$numberitemsgstamt = ($taxableitemamt * 9)/100;
										$unititemsgstamt = round($numberitemsgstamt,2);
										$unititemigstamt =  0;
									}
								}
								
								$servicequery = "select servicecode from inv_mas_service where servicename = '".$servicelistsplit[$i]."'";
								$servicefetch = runmysqlqueryfetch($servicequery);
								$servicecode = $servicefetch['servicecode'];
								
								$totalitemvalue = $taxableitemamt + $unititemigstamt + $unititemcgstamt + $unititemsgstamt;
			
								if($unititemcgstamt == 0 && $unititemsgstamt == 0)
								{
									$inum1 = (int)$unititemcgstamt;
									$inum2 = (int)$unititemsgstamt;
									if(is_float($unititemigstamt))
										$inum3 = (float)$unititemigstamt;
									else
										$inum3 = (int)$unititemigstamt;
								}
								else
								{
									$inum3 = (int)$unititemigstamt;
									if(is_float($unititemcgstamt) && is_float($unititemsgstamt))
									{
										$inum1 = (float)$unititemcgstamt;
										$inum2 = (float)$unititemsgstamt;
									}
									else
									{
										$inum1 = (int)$unititemcgstamt;
										$inum2 = (int)$unititemsgstamt;
									}
								}
								if(is_float($itemdiscount))
									$itemdisc = (float)$itemdiscount;
								else
									$itemdisc = (int)$itemdiscount;
			
								$serviceitemgrid[] = array( "SlNo"=> (string)$itemcount++,
								"IsServc"=> 'Y',
								"HsnCd"=> $servicecode,
								"UnitPrice"=> (int)$itemothers,
								"TotAmt"=> (int)$itemothers,
								"AssAmt"=> (int)$taxableitemamt,
								"Discount"=> $itemdisc,
								"GstRt"=> 18,
								"IgstAmt"=> $inum3,
								"CgstAmt"=> $inum1,
								"SgstAmt"=> $inum2,
								"TotItemVal"=> round($totalitemvalue,2) 
							);
								$servicecount++;
								
							}
						}
						// $abc = array_merge($productitemgrid,$serviceitemgrid);
						// print_r($abc);
						// exit;
						if($servicelist <> '' && $selectedcookievalue <> '')
							$totalitemgrid = array_merge($productitemgrid,$serviceitemgrid);
						elseif($servicelist == '' && $selectedcookievalue <> '')
							$totalitemgrid = $productitemgrid;
						elseif($servicelist <> '' && $selectedcookievalue == '')
							$totalitemgrid = $serviceitemgrid;
						
						$sezenabled = $fetch['sez_enabled'];
						if($seztaxtype == 'yes')
						{
							$SupTyp = "SEZWOP";
							$IgstOnIntra = "Y";
						}
						else if($sezenabled == 'yes')
						{
							$SupTyp = "SEZWP";
							$IgstOnIntra = "Y";
						}
						else
						{
							$SupTyp = "B2B";
							$IgstOnIntra = "N";
						}
						require_once('generateirn.php');
			
						$cgstval = $sgstval = $igstval = $rndoffamt = 0;
						if($igstamt == '0' || $igstamt == '0.00')
						{
							$igstval = (int)$igstamt;
							if(is_float($cgstamt) && is_float($sgstamt))
							{
								$cgstval = (float)$cgstamt;
								$sgstval = (float)$sgstamt;
							}
							else{
								$cgstval = (int)$cgstamt;
								$sgstval = (int)$sgstamt;
							}
						}
						else{
							$cgstval = (int)$cgstamt;
							$sgstval = (int)$sgstamt;
							if(is_float($igstamt))
								$igstval = (float)$igstamt;
							else
								$igstval = (int)$igstamt;
						}
						//echo $roundoff_value; exit;
						if($roundoff_value == 0)
							$rndoffamt = (int)$roundoff_value;
						else
							$rndoffamt = (float)round($roundoff_value,2);
						
						$getinvoiceno = getinvoiceno($customer_gstcode,$year);
						$invoicenoformat = $getinvoiceno[0];
						$onlineinvoiceno = $getinvoiceno[1];
						$state_info = $getinvoiceno[2];
						
						//third api call(Generate IRN)
						//Prepare you post parameters
						$postIrnData = [
							"Irn"=> "",
							"Version"=> "1.1",
							"TranDtls"=> [
								"TaxSch"=> "GST",
								"SupTyp"=> $SupTyp,
								"RegRev"=> "N",
								"IgstOnIntra"=> $IgstOnIntra
							],
							"DocDtls"=> [
								"Typ"=> "INV",
								"No"=> $invoicenoformat, //$invoicenoformat
								"Dt"=> $date
							],
							"SellerDtls"=> [
								"Gstin"=> "29AABCR7796N000",
								"LglNm"=> "Relyon Softech Limited",
								"Addr1"=> "No. 73, Shreelekha Complex, WOC Road",
								"Loc"=> "BANGALORE",
								"Pin"=> 560086,
								"Stcd"=> "29"
							],
							"BuyerDtls"=> [
								"Gstin"=> $customer_gstno,
								"LglNm"=> $businessname,
								"Pos"=> $customer_gstcode,
								"Addr1"=> $einvoiceadd,
								"Loc"=> $place,
								"Pin"=> (int)$pincode,
								"Stcd"=> $customer_gstcode
								// "Gstin"=> "08AACFL7324C1ZM",
								// "LglNm"=> "0test1",
								// "Pos"=> "08",
								// "Addr1"=> "H-172-173, SEZ-II, Special economic zone, Sitapura, Jaipur",
								// "Loc"=> "Rajasthan",
								// "Pin"=> 302022,
								// "Stcd"=> "08"
							],
							"ValDtls"=> [
								"AssVal"=> (int)$totalproductprice,
								"CgstVal" => $cgstval,
								"SgstVal" => $sgstval,
								"IgstVal" => $igstval,
								"RndOffAmt" => $rndoffamt,
								"TotInvVal"=>(int)$netamount
							]
						];
						$postIrnData['ItemList'] = $totalitemgrid;
						//print_r($postIrnData);
						$post_irn_data = json_encode($postIrnData); 
						//print_r($post_irn_data);
						//exit;

						$date = datetimelocal('YmdHis-');
						$filename = $date.$invoicenoformat;
						$filebasename = $filename.".png";
						
						include_once('../../getinvoiceirn/getinvoiceirn.php');
						
						if($status === "ACT")
						{
							$ackNo = $irndata['ackNo'];
							$ackDt = $irndata['ackDt'];
							$irn = $irndata['irn']; 
							$signedInvoice = $irndata['signedInvoice'];
							$signedQRCode = $irndata['signedQRCode'];
							$authgstin = $gstforinvoice; 

							$irnquery = "insert into inv_einvoicestatus(customerid,invoiceno,module,status,irn,signedqrcode,ackno,ackdate,createddate,createdby,createdbyid) values ('".cusidcombine($generatedcustomerid)."','".$invoicenoformat."','dealer_module','".$status."','".$irn."','".$signedQRCode."','".$ackNo."','".$ackDt."','".$invoicedate."','".$loggedindealername."','".$userid."')";
							$irninvresult = runmysqlquery($irnquery);

							if($paymenttype == 'upi')
							{
								include('../../getinvoiceirn/getrazorqrcode.php');
							}

							//Get the next record serial number for insertion in invoicenumbers table
							$onlineinvoiceslno = getmaxslno();

							//Insert complete invoice details to invoicenumbers table 
							$invquery = "Insert into inv_invoicenumbers(slno,customerid,businessname,contactperson,address,place,pincode,
							emailid,description,invoiceno,dealername,createddate,createdby,amount,servicetax,sbtax,kktax,igst,cgst,sgst,
							netamount,phone,cell,customertype,customercategory,region,purchasetype,category,onlineinvoiceno,dealerid,products,
							productquantity,pricingtype,createdbyid,totalproductpricearray,actualproductpricearray,module,servicetype,
							serviceamount,paymenttypeselected,paymentmode,stdcode,branch,amountinwords,remarks,servicetaxdesc,invoiceheading,
							offerremarks,invoiceremarks,duedate,branchid,regionid,privatenote,podate,poreference,productbriefdescription,
							itembriefdescription,seztaxtype,seztaxfilepath,seztaxdate,seztaxattachedby,year,invoice_type,state_info,gst_no,
							irn,signedqrcode,qrimagepath,ackno,ackdate,qrid,image_url,razorqrimage,qrcreateddate,qrtype) values('".$onlineinvoiceslno."','".cusidcombine($generatedcustomerid)."',
							'".$businessname."','".$contactpersonplit."','".addslashes($address1)."','".$place."','".$pincode."','".$emailidplit."',
							'','".$invoicenoformat."','".$dealername."','".$invoicedate."','".$loggedindealername."','".$totalproductprice."',
							'".$servicetax."','".$sbtax."','". $kktax ."','". $igstamount ."','". $cgstamount ."','". $sgstamount ."',
							'".$netamount."','".$phonenumbersplit."','".$cellnumbersplit."','".$type."','".$category."','".$dealerregion."',
							'Product','".$dealerregion."','".$onlineinvoiceno."','".$currentdealer."','".$selectedcookievalue."','".$productquantityvalues."',
							'".$pricingtype."','".$userid."','".$totalproductpricearray."','".$actualproductprice."','dealer_module',
							'".$servicelist."','".$serviceamountvalues."','".$paymenttypeselected."','".$paymentmode."','".$stdcode."',
							'".$branchname."','".$amountinwords."','".$paymentremarksnew."','".$servicetaxdesc."','".$invoiceheading."',
							'".$offerremarks."','".$invoiceremarks."','".$duedate."','".$branchid."','".$regionid."','".$privatenote."',
							'".changedateformat($podate)."','".$poreference."','".$productleveldescriptionvalues."','".$itemleveldescriptionvalues."',
							'".$seztaxtype1."','".$seztaxfilepath1."','".$seztaxdate1."','".$seztaxattachedby1."','".$year."','R','".$state_info ."','".$authgstin."','".$irn."','".$signedQRCode."','".$filebasename."','".$ackNo."','".$ackDt."','".$rqrid."','".$image_url."','".$razorqrimage."','".$rqrdate."','".$qrtype."');";
							// $log_content=$invquery;
							// $myfile = fopen("invlog.txt", "a") or die("Unable to open file!");
							// fwrite($myfile, $log_content);
							// fclose($myfile);
							$invresult = runmysqlquery($invquery);

							$imagepath = "../qrimages/".$filebasename; 
							QRcode::png($signedQRCode,$imagepath,QR_ECLEVEL_L, 2);
							include_once('generateinvoice.php');
							exit;
						}
						//if(isset($irndata['errorDetails']))
						else
						{
							$authgstin = "";
							for($c=0;$c<count($irndata['errorDetails']);$c++)
							{
								$errorDetails = $irndata['errorDetails'][$c]['errorCode'];
								switch ($errorDetails)
								{
									case '3028':
									{
										$authgstin = "0";
										$errormsg = "GSTIN is not present in invoice system";
										$errorcode = '3028';
									}
									break; //0 set invoice as Not Registered under GSTIN
									case '3029': 
									{
										$authgstin = "0";
										$errormsg = "GSTIN is not active";
										$errorcode = '3029';
									}
									break; //0 set invoice as Not Registered under GSTIN
									case '2265': 
									{
										$errormsg = "Recipient GSTIN state code does not match with the state code passed in recipient details"; 
										$errorcode = 2265;
									}    
									break; 
									case '3039': 
									{
										$errormsg = "Pincode of Buyer does not belong to his/her State"; 
										$errorcode = 3039;
									}
									break; 
									case '2150':
									{
										$errormsg='Duplicate IRN';
										$errorcode = 2150;
									}
									break;
									default:
									{
										$errormsg=$irndata['errorDetails'][$c]['errorMessage'];
										$errorcode = $errorDetails;
									}
									break;
								}
							}
								
						}
					}
					else{
						//echo $confrim = 'confirm';
						$authgstin = "0";
					}
					//echo "IRN" . $irn; 
					//exit;
				}
				else
				{
					//echo "out"; exit;
					$authgstin = "0";
				}
				//echo $custcontactperson; 
				//exit;
				//echo "out".$authgstin; exit;
				// $query33 = "select count(*) as statuscount from inv_einvoicestatus where invoiceno = '".$invoicenoformat."' and status = 'ACT'";
				// $result33 = runmysqlqueryfetch($query33);
				
				if((empty($customer_gstno) && $seztaxtype!='yes') || $gstcheck == 'gstconfirm')
				{
					//echo $fetch44['irn']; exit;
					//echo $id;
					//echo $confrim; exit;

					//Get the next record serial number for insertion in invoicenumbers table
					$onlineinvoiceslno = getmaxslno();

					$getinvoiceno = getinvoiceno($customer_gstcode,$year);
					$invoicenoformat = $getinvoiceno[0];
					$onlineinvoiceno = $getinvoiceno[1];
					$state_info = $getinvoiceno[2];

					if($paymenttype == 'upi')
					{
						include('../../getinvoiceirn/getrazorqrcode.php');
					}

					$irninvquery = "select count(*) as invnocount from `inv_einvoicestatus` where invoiceno = '".$invoicenoformat."'";
					$irninvfetch = runmysqlqueryfetch($irninvquery);
					$invnocount = $irninvfetch['invnocount'];

					if($invnocount == 0)
					{
						//Insert complete invoice details to invoicenumbers table 
						$query = "Insert into inv_invoicenumbers(slno,customerid,businessname,contactperson,address,place,pincode,emailid,description,invoiceno,dealername,createddate,createdby,amount,servicetax,sbtax,kktax,igst,cgst,sgst,netamount,phone,cell,customertype,customercategory,region,purchasetype,category,onlineinvoiceno,dealerid,products,productquantity,pricingtype,createdbyid,totalproductpricearray,actualproductpricearray,module,servicetype,serviceamount,paymenttypeselected,paymentmode,stdcode,branch,amountinwords,remarks,servicetaxdesc,invoiceheading,offerremarks,invoiceremarks,duedate,branchid,regionid,privatenote,podate,poreference,productbriefdescription,itembriefdescription,seztaxtype,seztaxfilepath,seztaxdate,seztaxattachedby,year,invoice_type,state_info,gst_no,irn,signedqrcode,ackno,ackdate,qrid,image_url,razorqrimage,qrcreateddate,qrtype) values('".$onlineinvoiceslno."','".cusidcombine($generatedcustomerid)."','".$businessname."','".$contactpersonplit."','".addslashes($address1)."','".$place."','".$pincode."','".$emailidplit."','','".$invoicenoformat."','".$dealername."','".$invoicedate."','".$loggedindealername."','".$totalproductprice."','".$servicetax."','".$sbtax."','". $kktax ."','". $igstamount ."','". $cgstamount ."','". $sgstamount ."','".$netamount."','".$phonenumbersplit."','".$cellnumbersplit."','".$type."','".$category."','".$dealerregion."','Product','".$dealerregion."','".$onlineinvoiceno."','".$currentdealer."','".$selectedcookievalue."','".$productquantityvalues."','".$pricingtype."','".$userid."','".$totalproductpricearray."','".$actualproductprice."','dealer_module','".$servicelist."','".$serviceamountvalues."','".$paymenttypeselected."','".$paymentmode."','".$stdcode."','".$branchname."','".$amountinwords."','".$paymentremarksnew."','".$servicetaxdesc."','".$invoiceheading."','".$offerremarks."','".$invoiceremarks."','".$duedate."','".$branchid."','".$regionid."','".$privatenote."','".changedateformat($podate)."','".$poreference."','".$productleveldescriptionvalues."','".$itemleveldescriptionvalues."','".$seztaxtype1."','".$seztaxfilepath1."','".$seztaxdate1."','".$seztaxattachedby1."','".$year."','R','".$state_info ."','".$authgstin."','".$irn."','".$signedQRCode."','".$ackNo."','".$ackDt."','".$rqrid."','".$image_url."','".$razorqrimage."','".$rqrdate."','".$qrtype."');";
						$result = runmysqlquery($query);
						
						include('generateinvoice.php');
					}					
					else
					{
						$errormsg = 'InvoiceNo is already registered for B2B/SEZ customer.';
						echo(json_encode('4^'.$errormsg.'^'.$errorcode));
					}
				}
				else if($errorcode == '3028' || $errorcode == '3029')
				{
					echo(json_encode('5^'.$errormsg.'^'.$errorcode));
				}
				else
				{
					if(empty($customer_gstno) && $seztaxtype =='yes')
					{
						$errormsg = 'Invoice cannot be generated for non GSTIN SEZ customer';
						echo(json_encode('4^'.$errormsg.'^'.$errorcode));
					}
					else
					//echo(json_encode('E invoice error'));
						echo(json_encode('4^'.$errormsg.'^'.$errorcode));
				}
			}
			else
			{
				echo(json_encode('2^Updation cannot be given, as no earlier licenses found. Admin previliges needed.^"'.$lastslno.'" '));
			}
			/*}
			else
			{
				echo(json_encode('2^An invoice is already present for the product. Admin previliges needed.^"'.$lastslno.'" '));
			}*/
		}
		else
		{
			echo(json_encode('2^Invalid Customer^"'.$lastslno.'" '));
		}
		
	}
	break;
	case 'checkforproduct':
	{
		$checkforproductarray = array();
		$selectedcookievalue = $_POST['selectedcookievalue'];
		$dealerid = $_POST['dealerid'];
		$selectedcookievaluesplit = explode('#',$selectedcookievalue);
		$purchasevalues = removedoublecomma(trim($_POST['purchasevalues'],','));
		$usagevalues = removedoublecomma(trim($_POST['usagevalues'],','));
		$purchasevaluesplit = explode(',',$purchasevalues);
		$usagevaluesplit = explode(',',$usagevalues);
		$productcheck = checkforproduct($lastslno,$selectedcookievaluesplit,$purchasevaluesplit,$usagevaluesplit);
	//	print_r('324'); exit;
		if($productcheck == 'true')
			$productcheckvalue = 'productnotpresent';
		else
			$productcheckvalue = 'producttpresent';
		$checkforproductarray['errorcode'] = '1';
		$checkforproductarray['productcheck'] = $productcheckvalue;
		echo(json_encode($checkforproductarray));
	}
	break;
	case 'searchbyinvoiceno':
	{
		$invoiceno = strtolower($_POST['invoiceno']);
		$query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode,inv_mas_dealer.telecaller,inv_mas_dealer.branch,inv_mas_dealer.region
	from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
	 where inv_mas_dealer.slno = '".$userid."';";
			$fetch = runmysqlqueryfetch($query);
			$relyonexecutive = $fetch['relyonexecutive'];
			$district = $fetch['district'];
			$branch = $fetch['branch'];
			$region = $fetch['region'];
			$state = $fetch['statecode'];
			if($telecaller == 'yes')
			{
				$query = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname  from inv_invoicenumbers left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)  where inv_mas_customer.branch in( select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."') and invoiceno like '%".$invoiceno."%' and inv_mas_customer.slno is not null order by businessname;";
				$result = runmysqlquery($query);
				if(mysqli_num_rows($result) == 0)
				{
					$query = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname  from inv_invoicenumbers left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)  where inv_mas_customer.region  = '2' and invoiceno like '%".$invoiceno."%' and inv_mas_customer.slno is not null order by businessname;";
					$result = runmysqlquery($query);
				}
			}
			else
			{
				if($relyonexecutive == 'no')
				{
					$query = "select distinct inv_mas_customer.slno,inv_mas_customer.businessname from inv_invoicenumbers left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5) where invoiceno like '%".$invoiceno."%' and inv_mas_customer.slno is not null and inv_mas_customer.currentdealer = '".$userid."' order by businessname;";
					$result = runmysqlquery($query);
				}
				else
				{
					if(($region == '1') || ($region == '3'))
					{
						$query = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname  from inv_invoicenumbers left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)  where inv_mas_customer.region  = '".$region."' and invoiceno like '%".$invoiceno."%' and inv_mas_customer.slno is not null order by businessname;";
						$result = runmysqlquery($query);
					}
					else
					{
				$query = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname  from inv_invoicenumbers left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)  where inv_mas_customer.branch in( select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."') and invoiceno like '%".$invoiceno."%' and inv_mas_customer.slno is not null order by businessname;";
						$result = runmysqlquery($query);
						if(mysqli_num_rows($result) == 0)
						{
							$query = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname  from inv_invoicenumbers left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)  where inv_mas_customer.branch  = '".$branch."' and invoiceno like '%".$invoiceno."%' and inv_mas_customer.slno is not null order by businessname;";
							$result = runmysqlquery($query);
						}
					}
				}
			}
			$responsegridarray = array();
			$grid = '';
			$count = 1;
			while($fetch = mysqli_fetch_array($result))
			{
				if($count > 1)
					$grid .='^*^';
				$grid .= $fetch['businessname'].'^'.$fetch['slno'];
				$count++;
			}
			if(mysqli_num_rows($result) == 0)
			{
				//echo('2#@#'.$grid);
				$responsegridarray['errorcode'] = "2";
				$responsegridarray['grid'] = $grid;
				echo(json_encode($responsegridarray));
			}
			else
			{
				$responsegridarray['errorcode'] = "1";
				$responsegridarray['grid'] = $grid;
				echo(json_encode($responsegridarray));
				//echo('1#@#'.$grid);
			}
	}
	break;
	case 'getdealerdetails':
	{
		$dealerid = $_POST['dealerid'];
		$customer_gst_no = $_POST['customer_gstno'];//actual gst_no
		$customer_gst = $_POST['customer_gst'];//gstin_id from thelogs

		$query = "select inv_mas_dealer.slno,inv_mas_dealer.businessname,inv_mas_dealer.contactperson,inv_mas_dealer.phone,inv_mas_dealer.cell,inv_mas_dealer.emailid,inv_mas_dealer.place,inv_mas_district.districtname,inv_mas_state.statename from inv_mas_dealer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_dealer.district
		left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode where inv_mas_dealer.slno = '".$dealerid."'";
		$fetch = runmysqlqueryfetch($query);
		$grid = '<table width="100%" border="0" cellspacing="0" cellpadding="4">';
		$grid .= '<tr>';
        $grid .= '<td colspan="2"><div align="center" style="color:#006699; font-size:17px; font-weight:bold">Contact Details</div></td>';
        $grid .= '</tr>';
		
  		$grid .= '<tr>';
        $grid .= '<td width="45%"><strong>Contact Person: </strong></td>';
        $grid .= '<td width="55%" id="displaydealercontactperson" >'.$fetch['contactperson'].'</td>';
        $grid .= '</tr>';
        $grid .= '<tr>';
        $grid .= '<td valign="top"><strong>Phone: </strong></td>';
        $grid .= '<td  id="displaydealerphone" >'.$fetch['phone'].'</td>';
        $grid .= '</tr>';
        $grid .= '<tr>';
       	$grid .= '<td><strong>Cell: </strong></td>';
        $grid .= '<td id="displaydealercell" >'.$fetch['cell'].'</td>';
       	$grid .= '</tr>';
        $grid .= '<tr>';
        $grid .= '<td ><strong>Email ID: </strong></td>';
        $grid .= '<td id="displaydealeremailid">'.$fetch['emailid'].'</td>';
        $grid .= '</tr>';
        $grid .= '<tr>';
        $grid .= ' <td ><strong>Place:</strong></td>';
        $grid .= ' <td id="displaydealerplace">'.$fetch['place'].'</td>';
        $grid .= '</tr>';
        $grid .= '<tr>';
        $grid .= '<td><strong>District:</strong></td>';
        $grid .= '<td id="displaydealerdistrict">'.$fetch['districtname'].'</td>';
        $grid .= '</tr>';
        $grid .= '<tr>';
        $grid .= '<td><strong>State:</strong></td>';
        $grid .= '<td id="displaydealerstate">'.$fetch['statename'].'</td>';
        $grid .= '</tr>';
		$grid .= '<tr>';
        $grid .= '<td></td>';
        $grid .= '<td align="right"><input type="button" value="Close" id="closecolorboxbutton"  onclick="$().colorbox.close();" class="swiftchoicebutton"/></td>';
        $grid .= '</tr>';
		$grid .= '</table>';
		/*echo('1^'.$fetch['slno'].'^'.$fetch['businessname'].'^'.$fetch['contactperson'].'^'.$fetch['phone'].'^'.$fetch['cell'].'^'.$fetch['emailid'].'^'.$fetch['place'].'^'.$fetch['districtname'].'^'.$fetch['statename']);*/
		
		
		/*----------------------------*/
       
        $search_customer =  str_replace("-","",$_POST['customer_hidden_id']);
        $customer_details = "select inv_mas_customer.sez_enabled as sez_enabled,
        inv_mas_district.statecode as state_code,inv_mas_state.statename as statename
        ,inv_mas_state.state_gst_code as state_gst_code, inv_mas_customer.gst_no as customer_gstno from inv_mas_customer 
        left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode
        left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode 
        where inv_mas_customer.slno like '%".$search_customer."%'";
		//echo $customer_details;
        $fetch_customer_details = runmysqlqueryfetch($customer_details);
        $customer_gstno = $fetch_customer_details['customer_gstno'];
        if($customer_gstno != "" || !empty($customer_gstno))
        {
        	if(is_numeric($customer_gstno))
        	{
        		$query_gst_details = "select gst_no from customer_gstin_logs where gstin_id=".$customer_gstno;
        		$fetch_gst_details = runmysqlqueryfetch($query_gst_details);
        		$customer_gst_code = substr($fetch_gst_details['gst_no'],0,2);

        	}
        	else
        	{
        		if(!empty($customer_gst) && $customer_gst!="")
        		{
	        		$query_gst_details1 = "select gst_no from customer_gstin_logs where gstin_id = ".$customer_gst;
	        		$fetch_gst_details1 = runmysqlqueryfetch($query_gst_details1);
	        		$customer_gst_code = substr($fetch_gst_details1['gst_no'],0,2);
        		}
        		else
        		{
        			$customer_gst_code = substr($customer_gstno,0,2);
        		}
        			//$customer_gst_code = substr($customer_gstno, 0, 2);
        	}
            
        }
        else
        {
         		$customer_gst_code = $fetch_customer_details['state_gst_code'];
        }
        
        /*----------------------------*/
		
		echo('1^'.$fetch['slno'].'^'.$fetch['businessname'].'^'.$grid.'^'.$fetch_customer_details['sez_enabled'].'^'.$customer_gst_code.'^'.$fetch_customer_details['state_gst_code']);
		
		//echo(json_encode($responsearray2));
		
		//echo('1^'.$fetch['slno'].'^'.$fetch['businessname'].'^'.$grid);
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
			
		$responsearray21 = array();
		$responsearray21['errorcode'] = '1';
		$responsearray21['grid'] = $grid;
		$responsearray21['count'] = $fetchresultcount;
		$responsearray21['linkgrid'] = $linkgrid;
		echo(json_encode($responsearray21));
		
		
	}
	break;
	
	case 'contactsave':
	{
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
		$createddate = datetimelocal('d-m-Y').' '.datetimelocal('H:i:s');
		$date = datetimelocal('d-m-Y');
		
		//Get the Logged in dealer Region and Branch
		$query111 = "select branch,region from inv_mas_dealer where slno = '".$userid."';";
		$fetchresult111 = runmysqlqueryfetch($query111);
		$regionid = $fetchresult111['region'];
		$branchid = $fetchresult111['branch'];
		
		$query = runmysqlqueryfetch("SELECT (MAX(slno) + 1) AS newcustomerid FROM inv_mas_customer");
		$cusslno = $query['newcustomerid'];

		$query = "Insert into inv_mas_customer(slno,customerid,businessname,address, place,pincode,district,region,category,type,stdcode,website,remarks,password,passwordchanged,disablelogin,createddate,createdby,corporateorder,currentdealer,fax,activecustomer,lastmodifieddate,lastmodifiedby,createdip,lastmodifiedip,branch,companyclosed, promotionalsms,promotionalemail) values ('".$cusslno."','','".trim($businessname)."','".$address."','".$place."','".$pincode."','".$district."','".$regionid."','".$category."','".$type."','".$stdcode."','".$website."','".$remarks."','','N','no','".changedateformatwithtime($createddate)."','2','no','".$userid."','".$fax."','yes','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".$_SERVER['REMOTE_ADDR']."','".$_SERVER['REMOTE_ADDR']."','".$branchid."','no','no','no');";
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
			
			$query2 = "Insert into inv_contactdetails(customerid,selectiontype,contactperson,phone,cell,emailid) values  ('".$cusslno."','".$selectiontype."','".$contactperson."','".$phonevalue."','".$cellvalue."','".$emailidvalue."');";
			$result = runmysqlquery($query2);
		}

		$responsesavearray = array();
		$responsesavearray['successcode'] = "1";
		$responsesavearray['successmessage'] = "Customer Record  Saved Successfully";
		echo(json_encode($responsesavearray));
	}
	break;
}

function calculatenormalprice($productamountsplit,$productquantitysplit)
{
	for($i = 0; $i < count($productamountsplit); $i++)
	{
		for($j=1;$j<=$productquantitysplit[$i];$j++)
		{
			$singleproductprice = $productamountsplit[$i];
			if($prdcount > 0)
			$productpricearray .= '*';
				$productpricearray .= ($singleproductprice);
			$prdcount++;
		}
		$productprice = $productamountsplit[$i] * ($productquantitysplit[$i]);
		if($prdcount1 > 0)
		$totalproductpricearray .= '*';
			$totalproductpricearray .= ($productprice);
		$prdcount1++;
		$productprice = ($productprice);
		$totalproductprice += $productprice ;
	}
	return $totalproductprice.'$'.$productpricearray.'$'.$totalproductpricearray;
}


function calculatetotalamount($selectedcookievaluesplit,$purchasevaluesplit,$usagevaluesplit,$productquantitysplit,$pricingtype,$offeramount,$perproductpricearray)
{
	$prdcount = 0;
	$productpricearraysplit = explode('*',$perproductpricearray);
	if(($pricingtype == 'offer') || ($pricingtype == 'inclusivetax'))
	{
		$productratio = productratio($selectedcookievaluesplit,$purchasevaluesplit,$usagevaluesplit,$offeramount,$productquantitysplit,$perproductpricearray);
	}
	else
	{
		$productratio = 1;
	}
	for($i = 0; $i < count($selectedcookievaluesplit); $i++)
	{
		$recordnumber = $selectedcookievaluesplit[$i];
		$query0 = "select subgroup from inv_mas_product where productcode = '".$recordnumber."'";
		$fetch0 = runmysqlqueryfetch($query0);
		$subgroup = $fetch0['subgroup'];
			if($selectedcookievaluesplit[$i] != $selectedcookievaluesplit[$i-1])
			{
				
				if($subgroup == 'ESS')
				{
					$singleproductprice = $productpricearraysplit[$i];
				}
				else{
					$purchasetype = $purchasevaluesplit[$i];
					$usagetype = $usagevaluesplit[$i];
					if($purchasetype == 'new' && $usagetype == 'singleuser')
						$producttypeprice = 'newsuprice';
					else if($purchasetype == 'new' && $usagetype == 'multiuser')
						$producttypeprice = 'newmuprice';
					else if($purchasetype == 'updation' && $usagetype == 'singleuser')
						$producttypeprice = 'updatesuprice';
					else if($purchasetype == 'updation' && $usagetype == 'multiuser')
						$producttypeprice = 'updatemuprice';
					else if($purchasetype == 'new' && $purchasetype == 'addlic')
						$producttypeprice = 'newaddlicenseprice';
					else
						$producttypeprice = 'updationaddlicenseprice';

					$query = "select ".$producttypeprice." as productprice from inv_dealer_pricing  where product = '".$recordnumber."'";
					$fetch = runmysqlqueryfetch($query);
					$singleproductprice = $fetch['productprice'];
				}
				
				
				if($prdcount > 0)
					$productpricearray .= '*';
				if(($i == count($selectedcookievaluesplit)-1) && (($pricingtype == 'offer')|| ($pricingtype == 'inclusivetax')))
				{
					$lastproductprice = $offeramount - $totalproductprice;
					$productpricearray .= $lastproductprice;
				}
				else
				{
					$productpricearray .= roundnearest($singleproductprice * $productratio);
				}
				$prdcount++;
				if($subgroup == 'ESS')
					$productprice = $productpricearraysplit[$i] * ($productquantitysplit[$i]);
				else
					$productprice = $fetch['productprice'] * ($productquantitysplit[$i]);
				if($prdcount1 > 0)
					$totalproductpricearray .= '*';
				$totalproductpricearray .= roundnearest($productprice * $productratio);
				$prdcount1++;
				$productprice = roundnearest($productprice * $productratio);
				$totalproductprice += $productprice ;
			}
	}
	return $totalproductprice.'$'.$productpricearray.'$'.$totalproductpricearray.'$'.$productratio;
}

function getdescriptionamount($descriptionamountvalues,$descriptiontypevalues)
{
	$descriptionamountsplit = explode('~',$descriptionamountvalues);
	$descriptiontypesplit = explode('~',$descriptiontypevalues);
	$descriptioncount = count($descriptionamountsplit);
	$amount = 0;
	for($i=0;$i<$descriptioncount; $i++)
	{
		if($descriptiontypesplit[$i] == 'add')
			$amount = ($amount) + $descriptionamountsplit[$i];
		else if($descriptiontypesplit[$i] == 'less' || $descriptiontypesplit[$i] == 'amount' || $descriptiontypesplit[$i] == 'percentage')
			$amount = ($amount) - $descriptionamountsplit[$i];
		else
			$amount;
	}
	return roundnearest($amount);
}

function productratio($selectedcookievaluesplit,$purchasevaluesplit,$usagevaluesplit,$offeramount,$productquantitysplit,$perproductpricearray)
{
	$productpricearraysplit = explode('*',$perproductpricearray);
	for($i = 0; $i < count($selectedcookievaluesplit); $i++)
	{
		$recordnumber = $selectedcookievaluesplit[$i];
		$query0 = "select subgroup from inv_mas_product where productcode = '".$recordnumber."'";
		$fetch0 = runmysqlqueryfetch($query0);
		$subgroup = $fetch0['subgroup'];
		
			$purchasetype = $purchasevaluesplit[$i];
			$usagetype = $usagevaluesplit[$i];
			$productquantity = $productquantitysplit[$i];
			if($purchasetype == 'new' && $usagetype == 'singleuser')
				$producttypeprice = 'newsuprice';
			else if($purchasetype == 'new' && $usagetype == 'multiuser')
				$producttypeprice = 'newmuprice';
			else if($purchasetype == 'updation' && $usagetype == 'singleuser')
				$producttypeprice = 'updatesuprice';
			else if($purchasetype == 'updation' && $usagetype == 'multiuser')
				$producttypeprice = 'updatemuprice';
			else if($purchasetype == 'new' && $purchasetype == 'addlic')
				$producttypeprice = 'newaddlicenseprice';
			else
				$producttypeprice = 'updationaddlicenseprice';
			
			if($subgroup == 'ESS')
			{
				$productprice = $productpricearraysplit[$i] * $productquantity;
			}
			else
			{
				$query = "select ".$producttypeprice." as productprice from inv_dealer_pricing  where product = '".$recordnumber."'";
				$fetch = runmysqlqueryfetch($query);
				$productprice = $fetch['productprice'] * $productquantity;
			}
				
			$totalproductprice += $productprice;
	}
	$productratio = ($offeramount)/($totalproductprice);
	return round($productratio,2);
}

function roundnearest($amount)
{
	$firstamount = round($amount,1);
	$amount1 = round($firstamount);
	return $amount1;
}

function checkcustomer($lastslno)
{
	$userid = imaxgetcookie('dealeruserid');
	$query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode,inv_mas_dealer.telecaller,inv_mas_dealer.branch,inv_mas_dealer.region
	from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
 	where inv_mas_dealer.slno = '".$userid."';";
	$fetch = runmysqlqueryfetch($query);
	$relyonexecutive = $fetch['relyonexecutive'];
	$telecaller = $fetch['telecaller'];
	$region = $fetch['region'];
	$branch = $fetch['branch'];
	$district = $fetch['district'];
	$state = $fetch['statecode'];
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
				$query = "select slno as slno, businessname as businessname from inv_mas_customer where region = '1' or region = '3' order by businessname;";
				$result = runmysqlquery($query);
			}
			else
			{
				$query = "select slno as slno, businessname as businessname from inv_mas_customer where branch in( select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."') order by businessname;";
				$result = runmysqlquery($query);
				if(mysqli_num_rows($result) == 0)
				{
					$query = "select slno as slno, businessname as businessname from inv_mas_customer where branch = '".$branch."' order by businessname;";
					$result = runmysqlquery($query);
				}
			}
		}
	}
	//echo($query); exit;
	$grid = '';
	$count = 1;
	$resultvalue = 'true';
	while($fetch = mysqli_fetch_array($result))
	{			
		if($lastslno == $fetch['slno'])
		{

			$resultvalue = 'true';
			break;
		}
		else
		{
			$resultvalue = 'false';
		}
	}
	return $resultvalue;
}

function getpaymentstatus($receiptamount,$netamount)
{
 if($receiptamount == '')
 {
   return '<span class="redtext">UNPAID</span>';
 }
 else if($receiptamount < $netamount)
 { 
  return '<span class="redtext">PARTIAL</span>';
 }
 else if($receiptamount == $netamount)
 { 
  return '<span class="greentext">PAID</span>';
 }
 else
 	return '<span class="greentext">PAID</span>';
 
}

function getstartnumber($region)
{
	switch($region)
	{
		case 'BKG': $startnumber = '1'; break;
		case 'BKM': $startnumber = '1';break;
		case 'CSD': $startnumber = '11101';break;
		default: $startnumber = '1';break;
	}
	return ($startnumber-1);
}

function getstartnumbernew($state_info)
{
    switch($state_info)
    {
        case '2018RL': $startnumber = '1'; break;
        case '2018RI': $startnumber = '1';break;
    default: $startnumber = '1';break;
}
return ($startnumber-1);
}

function checkforproduct($lastslno,$selectedproducts,$purchasevalues,$usagevalues)
{
	$result =  'true';
	if($selectedproducts[0] <> '')
	{
		$productcount = count($selectedproducts);
		$query1 = "select inv_dealercard.productcode,inv_dealercard.purchasetype,inv_dealercard.usagetype,inv_dealercard.addlicence  from inv_invoicenumbers left join inv_dealercard on inv_invoicenumbers.slno = inv_dealercard.invoiceid where right(inv_invoicenumbers.customerid,5) = '".$lastslno."' and inv_invoicenumbers.status <> 'CANCELLED';";
		$result1 = runmysqlquery($query1);
		$i=0;
		if(mysqli_num_rows($result1) == 0)
		{
			$result =  'true';
		}
		else
		{
			while($fetch1 = mysqli_fetch_array($result1))
			{
			
				for($i = 0; $i < mysqli_num_rows($result1); $i++)
				{
					if($fetch1['addlicence'] == 'yes' )
						$usagetype = 'addlic';
					else
						$usagetype = $fetch1['usagetype'];
					for($j = 0; $j < $productcount; $j++)
					{	
						if(($fetch1['productcode'] == $selectedproducts[$j]) && ($fetch1['purchasetype'] == $purchasevalues[$j]) && ($usagetype == $usagevalues[$j]))
						{
							$result =  'false'; break;
						}
					}
				}
			}
		}
	}
	return $result;
}

function checkforproductregistration($lastslno,$selectedproducts,$purchasevalues)
{
	$result = 'true';
	if($selectedproducts[0] <> '')
	{	
		$productcount = count($selectedproducts);
		for($i=0; $i<$productcount ; $i++)
		{
			if($purchasevalues[$i] == 'updation')
			{
				$query1 = "select * from inv_mas_product where productcode = '".$selectedproducts[$i]."';";
				$resultfetch1 = runmysqlqueryfetch($query1);
				$productgroup = $resultfetch1['group'];
				$query2 = "select distinct inv_mas_product.group from inv_customerproduct left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3) where customerreference = '".$lastslno."' and inv_mas_product.group = '".$productgroup."'";
				$result2 = runmysqlquery($query2);
				if(mysqli_num_rows($result2) == 0)
				{
					$result = 'false';
					return $result;
				}
				else
				{
					$fetch1 = mysqli_fetch_array($result2);
					$productgroup = $fetch1['group'];
					if($productgroup == '')
					{
						$result = 'false'; break;
					}
					else
					{
						$result = 'true';
					}
				}
			}
		}
	}
	return $result;
}
function getmaxslno()
{
	$query1 = "select ifnull(max(slno),0) + 1 as billref from inv_invoicenumbers";
	$resultfetch1 = runmysqlqueryfetch($query1);
	$onlineinvoiceslno = $resultfetch1['billref'];

	return $onlineinvoiceslno;
}
function getinvoiceno($customer_gstcode,$year)
{
	if($customer_gstcode == '29')
	{   
		$state_info = 'L';
		$varState = '2022RL';
		//$onlineinvoiceno='000001';
		//selct online invoice number
		//$queryonlineinv = "select ifnull(max(onlineinvoiceno),".getstartnumbernew($state_info).")+ 1 as invoicenotobeinserted from inv_invoicenumbers where state_info = '".$state_info."'";
		
			$queryonlineinv = "select ifnull(max(onlineinvoiceno),0)+ 1 as invoicenotobeinserted from inv_invoicenumbers where invoiceno like '%".$varState."%'";
	
		//Get the next invoice number from invoicenumbers table, for this new_invoice
			//$queryonlineinv = "select ifnull(max(onlineinvoiceno),0)+ 0 as invoicenotobeinserted from inv_invoicenumbers where state_info = '".$state_info."'";
			$resultfetchinv = runmysqlqueryfetch($queryonlineinv);
			$onlineinvoiceno = $resultfetchinv['invoicenotobeinserted'];
			$onlineinvoiceno=(string)$onlineinvoiceno;
			$onlineinvoiceno=sprintf('%06d', $onlineinvoiceno);
			$invoicenoformat = 'RSL'.$year.'R'.$state_info.''.$onlineinvoiceno;
	}
	else
	{
		//$onlineinvoiceno='000100';
		$state_info = 'I';
		$varState = '2022RI';
		//selct online invoice number
		//Get the next invoice number from invoicenumbers table, for this new_invoice
		//$queryonlineinv = "select ifnull(max(onlineinvoiceno),0)+ 1 as invoicenotobeinserted from inv_invoicenumbers where state_info = '".$state_info."'";
		
		//$queryonlineinv = "select ifnull(max(onlineinvoiceno),".getstartnumbernew($state_info).")+ 1 as invoicenotobeinserted from inv_invoicenumbers where state_info = '".$state_info."'";
		
		$queryonlineinv = "select ifnull(max(onlineinvoiceno),0)+ 1 as invoicenotobeinserted from inv_invoicenumbers where invoiceno like '%".$varState."%'";	
		
		$resultfetchinv = runmysqlqueryfetch($queryonlineinv);
		$onlineinvoiceno = $resultfetchinv['invoicenotobeinserted'];
		$onlineinvoiceno=sprintf('%06d', $onlineinvoiceno);
		$onlineinvoiceno=(string)$onlineinvoiceno;		
		$invoicenoformat = 'RSL'.$year.'R'.$state_info.''.$onlineinvoiceno;
	}

	return array($invoicenoformat,$onlineinvoiceno,$state_info);
}

?>