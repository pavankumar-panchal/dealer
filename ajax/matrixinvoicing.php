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
    case 'getcustomercount':
    {
        $customerarraycount = array();
        $query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode,inv_mas_dealer.branch,inv_mas_dealer.region
        from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
            where inv_mas_dealer.slno = '".$userid."';";
        $fetch = runmysqlqueryfetch($query);
        $relyonexecutive = $fetch['relyonexecutive'];
        $branch = $fetch['branch'];
        $district = $fetch['district'];
        $state = $fetch['statecode'];
        $region = $fetch['region'];

		// if($relyonexecutive == 'no')
		// {
		// 	$query = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname  from inv_mas_customer where inv_mas_customer.currentdealer = '".$userid."' order by businessname;";
		// 	$result = runmysqlquery($query);
		// }
		// else
		// {
			if($region == '1' || $region == '3')
			{
				$query = "select slno as slno, businessname as businessname from inv_mas_customer where (inv_mas_customer.region = '1' || inv_mas_customer.region = '3') order by businessname;";
				$result = runmysqlquery($query);
			}
			else
			{
				
				$query = "select slno as slno, businessname as businessname from inv_mas_customer where branch = '".$branch."' order by businessname;";
				$result = runmysqlquery($query);
			}
		//}

        $count = mysqli_num_rows($result);
        $customerarraycount['count'] = $count;
        echo(json_encode($customerarraycount));
    }
    break;

    case 'generatecustomerlist':
    {
		$customerarray = array();
        $limit = $_POST['limit'];
        $startindex = $_POST['startindex'];
        $query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode,inv_mas_dealer.branch,inv_mas_dealer.region
        from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
            where inv_mas_dealer.slno = '".$userid."';";
        $fetch = runmysqlqueryfetch($query);
        $relyonexecutive = $fetch['relyonexecutive'];
        $branch = $fetch['branch'];
        $district = $fetch['district'];
        $state = $fetch['statecode'];
        $region = $fetch['region'];
		
		// if($relyonexecutive == 'no')
		// {
		// 	$query = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname  from inv_mas_customer where inv_mas_customer.currentdealer = '".$userid."' order by businessname LIMIT ".$startindex.",".$limit.";";
		// 	$result = runmysqlquery($query);
		// }
		// else
		// {
			if($region == '1' || $region == '3')
			{
				$query = "select slno as slno, businessname as businessname from inv_mas_customer where (inv_mas_customer.region = '3') order by businessname LIMIT ".$startindex.",".$limit.";";
				$result = runmysqlquery($query);
			}
			else
			{
				
				$query = "select slno as slno, businessname as businessname from inv_mas_customer where branch = '".$branch."' order by businessname LIMIT ".$startindex.",".$limit.";";
				$result = runmysqlquery($query);
			}
		//}
		
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$customerarray[$count] = $fetch['businessname'].'^'.$fetch['slno'];
			$count++;
		}
        echo(json_encode($customerarray));
        
    }
    break;

    // case 'getsubproduct':
    // {
    //     $responsearray =array();
    //     $product = $_POST['productvalue'];
    //     $getid = $_POST['getid'];
    //     $query = "select subproduct,inv_matrixsubprd.id as subid from inv_matrixsubprd left join inv_mas_matrixproduct on inv_matrixsubprd.prdid = inv_mas_matrixproduct.id where prdid ='".$product."'";
    //     $result = runmysqlquery($query);
    //     $optionval = '<select name="productsubtype" class="swiftselect-mandatory" id="productsubtype"'.$getid.'" style="width:130px;" >';
    //     $optionval .= '<option value="">Select Sub Product</option>';
    //     while($fetch = mysqli_fetch_array($result))
    //     {
    //         $optionval .= "<option value='".$fetch['subid']."'>".$fetch['subproduct']."</option>";
    //     }
    //     $optionval .= '</select>';
    //     $responsearray['errorcode'] = '1';
    //     $responsearray['optionval'] = $optionval;
    //     echo json_encode($responsearray);

    // }
    // break;
    
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
			
			$query_gstn = "select inv_mas_state.state_gst_code as state_gst_code,inv_mas_customer.businessname as businessname,inv_mas_customer.gst_no as gst_no,inv_mas_customer.sez_enabled as sez_enabled,inv_mas_state.statename,inv_mas_customer.panno
			from inv_mas_customer left join inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer 
			left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district 
			left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode where inv_mas_customer.slno = '".$lastslno."';";
			
			$fetch_gstn = runmysqlqueryfetch($query_gstn);
			//$gst_no = $fetch_gstn['gst_no'];
			
			$panno = $fetch_gstn['panno'];
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
						
			if($relyonexecutive == 'no')
			{
				$dealerpiece = " AND inv_mas_customer.currentdealer = '".$userid."'";
			}
			else
			{
				if($region == '1' || $region == '3')
				{
					$dealerpiece = " AND (inv_mas_customer.region = '3') ";
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

			$invoicegrid = '';
			$query1 = "select * from inv_matrixinvoicenumbers where right(customerid,5) = ".$lastslno." order by slno desc";
			$result1 = runmysqlquery($query1);
			$count = mysqli_num_rows( $result1);
			if($count > 0)
			{
				$invoicegrid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
				$invoicegrid .= '<tr class="tr-grid-header" align ="left">
					<td nowrap = "nowrap" class="td-border-grid" align ="left">Sl No</td>
					<td nowrap = "nowrap" class="td-border-grid" align ="left">Date</td>
					<td nowrap = "nowrap" class="td-border-grid" align ="left">Invoice Number</td>
					<td nowrap = "nowrap" class="td-border-grid" align ="left">Invoice Amount</td>
					<td nowrap = "nowrap" class="td-border-grid" align ="left">Status</td>
					<td nowrap = "nowrap" class="td-border-grid" align ="left">Generated By</td>
					<td nowrap = "nowrap" class="td-border-grid" align ="left">Action</td></tr>';
				$i_n1 = 0;$slno1 = 0;
				while($fetch1 = mysqli_fetch_array($result1))
				{
					$i_n1++;$slno1++;
					$color1;
					if($i_n1%2 == 0)
						$color1 = "#edf4ff";
					else
						$color1 = "#f7faff";
					$invoicegrid .= '<tr class="gridrow1" bgcolor='.$color1.'>';
					$invoicegrid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slno1."</td> ";
					$invoicegrid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformatwithtime($fetch1['createddate'])."</td>";
					$invoicegrid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch1['invoiceno']."</td>";
					$invoicegrid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch1['netamount']."</td>";
					$invoicegrid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch1['status']."</td>";
					$invoicegrid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch1['createdby']."</td>";
					$invoicegrid .= '<td nowrap="nowrap" class="td-border-grid" align="center"> <a onclick="viewmatrixinvoice(\''.$fetch1['slno'].'\');" class="resendtext"> View >></a> </td>';
					$invoicegrid .= "</tr>";
				}
			}
			
			$query2 = "select * from inv_customerreqpending where customerid = '".$lastslno."' and inv_customerreqpending.customerstatus = 'Pending' and requestfrom = 'dealer_module';";
			$result2 = runmysqlquery($query2);
			
			if(mysqli_num_rows($result2) > 0)
			{
				$query = "select inv_customerreqpending.customerid as slno, inv_customerreqpending.businessname as companyname,inv_customerreqpending.place,inv_customerreqpending.address,inv_mas_region.category as region,inv_mas_branch.branchname as branch,inv_mas_customercategory.businesstype as businesstype,inv_mas_customertype.customertype as customertype,inv_mas_dealer.businessname as dealername,inv_customerreqpending.stdcode, inv_mas_customer.pincode,inv_mas_district.districtname, inv_mas_state.statename,inv_mas_customer.customerid,inv_mas_customer.sez_enabled,inv_mas_state.state_gst_code
				from inv_mas_customer left join inv_customerreqpending on inv_customerreqpending.customerid = inv_mas_customer.slno left join inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region 
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
				$contactperson = explode(',', trim($contactvalues,','));
				$contactp = $contactperson[0];

				$sez_enabled = $fetch_gstn['sez_enabled'];
				$branchid = $fetch_gstn['branchid'];
				$statename = $fetch_gstn['statename'];

				// $pincodestatus = pincodestatus($fetch['pincode'],$statename);
				
				$responsearray['errorcode'] = "1";
				$responsearray['slno'] = $fetch['slno'];
				$responsearray['customerid'] = $customerid;
				$responsearray['companyname'] = $fetch['companyname'];
				$responsearray['contactvalues'] = $contactp;
				$responsearray['address'] = $address;
				$responsearray['address1'] = $fetch['address'];
				$responsearray['pincode'] = $fetch['pincode'];
				$responsearray['pincodestatus'] = $pincodestatus;
				$responsearray['phoneres'] = $phone;
				$responsearray['cellres'] = $cell;
				$responsearray['emailidres'] = $emailidplit;
				$responsearray['region'] = $fetch['region'];
				$responsearray['branch'] = $fetch['branch'];
				$responsearray['businesstype'] = $fetch['businesstype'];
				$responsearray['customertype'] = $fetch['customertype'];
				$responsearray['gst_no'] = $new_gst_no;
				$responsearray['gstin_id'] = $gstin_id;
				$responsearray['sez_enabled'] = $sez_enabled;
				$responsearray['dealeridhid'] = $userid;
				$responsearray['dealername'] = $fetch['dealername'];
			    $responsearray['state_gst_code'] = $fetch['state_gst_code'];
				$responsearray['invoicegrid'] = $invoicegrid;
				$responsearray['panno'] = $panno;
				echo(json_encode($responsearray));
				
					//echo('1^'.$fetch['slno'].'^'.$customerid.'^'.$fetch['companyname'].'^'.trim($contactvalues,',').'^'.$address.'^'.trim($phoneres,',').'^'.trim($cellres,',').'^'.trim($emailidres,',').'^'.$fetch['region'].'^'.$fetch['branch'].'^'.$fetch['businesstype'].'^'.$fetch['customertype'].'^'.$fetch['dealername']);
			}
			else
			{
				$query1 = "SELECT count(*) as count from inv_mas_customer left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode where inv_mas_customer.slno = '".$lastslno."'".$dealerpiece."";
				$fetch1 = runmysqlqueryfetch($query1);
				if($fetch1['count'] > 0)
				{
					$query = "select inv_mas_customer.slno as slno, inv_mas_customer.businessname as companyname,inv_mas_customer.place,inv_mas_customer.address,inv_mas_region.category as region,inv_mas_branch.branchname as branch	,inv_mas_customercategory.businesstype as businesstype,inv_mas_customertype.customertype as customertype,inv_mas_dealer.businessname as dealername,inv_mas_customer.stdcode, inv_mas_customer.pincode,inv_mas_district.districtname, inv_mas_state.statename,inv_mas_customer.customerid,inv_mas_customer.sez_enabled,inv_mas_state.state_gst_code  from inv_mas_customer left join inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode where inv_mas_customer.slno = '".$lastslno."'".$dealerpiece."";
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
					$contactperson = explode(',', trim($contactvalues,','));
					$contactp = $contactperson[0];
					$sez_enabled = $fetch['sez_enabled'];
					$branchid = $fetch['branchid'];
					$statename = $fetch['statename'];

					// $pincodestatus = pincodestatus($fetch['pincode'],$statename);
					
					$responsearray['errorcode'] = "1";
					$responsearray['slno'] = $fetch['slno'];
					$responsearray['customerid'] = $customerid;
					$responsearray['companyname'] = $fetch['companyname'];
					$responsearray['contactvalues'] = $contactp;
					$responsearray['address'] = $address;
					$responsearray['address1'] = $fetch['address'];
					$responsearray['pincode'] = $fetch['pincode'];
					$responsearray['pincodestatus'] = $pincodestatus;
					$responsearray['phoneres'] = $phone;
					$responsearray['cellres'] = $cell;
					$responsearray['emailidres'] = $emailidplit;
					$responsearray['region'] = $fetch['region'];
					$responsearray['branch'] = $fetch['branch'];
					$responsearray['businesstype'] = $fetch['businesstype'];
					$responsearray['customertype'] = $fetch['customertype'];
					$responsearray['dealername'] = $fetch['dealername'];
					$responsearray['gst_no'] = $new_gst_no;
					$responsearray['gstin_id'] = $gstin_id;
				    $responsearray['sez_enabled'] = $sez_enabled;
				    $responsearray['state_gst_code'] = $fetch['state_gst_code'];
				    $responsearray['dealeridhid'] = $userid;
					$responsearray['invoicegrid'] = $invoicegrid;
					$responsearray['panno'] = $panno;
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

    case 'proceedforpurchase' :
    {
        $lastslno = $_POST['lastslno'];
        $gstcheck = $_POST['gstcheck'];
		$dealerid = $_POST['dealerid'];
		$dealername = $_POST['dealername'];
		$paymentamount = $_POST['paymentamount'];
		$paymentremarks = $_POST['paymentremarks'];
        $invoiceremarks = $_POST['invoiceremarks'];

        $purchasevalues = $_POST['purchasevalues'];
		$purchasevaluessplit = explode('#',$purchasevalues);

		$producthiddenvalues = $_POST['producthiddenvalues'];
		$productvaluesplit = explode('*',$producthiddenvalues);

        $quantityvalues = $_POST['quantityvalues'];
		$quantityvaluessplit = explode(',',$quantityvalues);

        $unitamtvalues = $_POST['unitamtvalues'];
		$unitamtvaluessplit = explode('*',$unitamtvalues);

        $invoiceamountvalues = $_POST['invoiceamountvalues'];
		$invoiceamountvaluessplit = explode('*',$invoiceamountvalues);

        $fromsrlnovalues = $_POST['fromsrlnovalues'];
		$fromsrlnovaluessplit = explode('~',$fromsrlnovalues);
		//print_r($fromsrlnovaluessplit); exit;
		$fromsrlno = explode(",",$_POST['fromsrlno']); 
		//print_r($fromsrlno); exit;

        // $tosrlnovalues = $_POST['tosrlnovalues'];
		// $tosrlnovaluessplit = explode('~',$tosrlnovalues);
        $businessname = $_POST['cusname'];
        
        $state_gst_code = $_POST['state_gst_code'];
        $branchhidden = $_POST['branchhidden'];
        $customer_gstno = ($_POST['customer_gstno'] == 'Not Registered Under GST' || $gstcheck == 'gstinconfirm') ? "0": $_POST['customer_gstno'];
        $contactperson = $_POST['cuscontactperson'];
        $address = $_POST['cusaddress'];
        $emailid = $_POST['cusemail'];
        $phone = $_POST['cusphone'];
        $cell = $_POST['cuscell'];
        $type = $_POST['custype'];
        $category = $_POST['cuscategory'];
		$podate = (empty($_POST['podate'])) ? '' : changedateformat($_POST['podate']);
        $poreference = $_POST['poreference'];

        $igstamount = $_POST['igstamount'];
        $cgstamount = $_POST['cgstamount'];
        $sgstamount = $_POST['sgstamount'];
        $panno = $_POST['panno'];
        $seztaxtype = $_POST['seztaxtype'];

		//Get the customer details
		$query1 = "select * from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode =inv_mas_customer.district left join inv_mas_state on inv_mas_state.statecode =inv_mas_district.statecode left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region left join inv_mas_branch on  inv_mas_branch.slno = inv_mas_customer.branch left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category  where inv_mas_customer.slno = '".$lastslno."';";
		$fetch = runmysqlqueryfetch($query1);
		
	
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
		$place = $fetch['place'];
		$district = $fetch['districtcode'];
		$state = $fetch['statename'];
		$pincode = $fetch['pincode'];
		$branchname = $fetch['branchname'];
		$custcontactperson = trim($cuscontactperson,',');
		$stdcode = $fetch['stdcode'];
		$stdcode = ($stdcode == '')?'':$stdcode.' - ';
		$customerid17digit = $fetch['customerid'];

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
			$newcustomerid = $statecode.$district.$dealerid.$firstproduct.$lastslno;
			$password = generatepwd();
			//update customer master with customer product
			$query = "update inv_mas_customer set firstdealer = '".$dealerid."' , firstproduct = '".$firstproduct."', 
			initialpassword = '".$password."', loginpassword = AES_ENCRYPT('".$password."','imaxpasswordkey'),
			customerid = '".$newcustomerid."' where slno = '".$lastslno."';";
			$result = runmysqlquery($query);
			$generatedcustomerid = $newcustomerid;
			//sendwelcomeemail($lastslno, $userid);
	
		}

        $slno = 0;
        $amount = 0;
        $description = "";
		$purchasetype = "";
		$array = $fromsrlno;
		$productsplit = [];
		$serialfilter = [];
        $cardid = "";
        for($i=0;$i<count($productvaluesplit);$i++)
        {
			$slno++;
			$productsplit = explode('#',$productvaluesplit[$i]);
			$product[] = $productsplit[1];

			if($purchasevaluessplit[$i] == 'new')
			{
				$purchasetype = 'New';
			}
			elseif($purchasevaluessplit[$i] == 'updation')
			{
				$purchasetype = 'Updation';
			}

			// if($productsplit[1]!= 3)
			// {
			$serialno = array_slice($array,0,$quantityvaluessplit[$i]);
			$serialfilter = array_filter($serialno);
			//print_r($serialno);
			if($serialfilter)
				$cardid = implode("/",$serialfilter);
			// }
			// else
			// 	$cardid = '';
			
			$splicearray = array_splice($array,0,$quantityvaluessplit[$i]);
			//print_r($splicearray);
			
            // if($quantityvaluessplit[$i] > 1)
            //     $cardid =  $fromsrlnovaluessplit[$i]. '-' .$tosrlnovaluessplit[$i] ;
            // else
            //     $cardid =  $fromsrlnovaluessplit[$i];

            if($i > 0)
                $description .= '*';
            
            $description .= $slno.'$'.$productsplit[0].'$'.$purchasetype.'$'.$cardid.'$'.$invoiceamountvaluessplit[$i];

            $amount += $invoiceamountvaluessplit[$i];
			
        }
		//echo $description; exit;
		//print_r($products); exit;
		$products = implode('#',$product);
        
        $netamount = $amount +  $cgstamount + $sgstamount + $igstamount;
        $netamount = round($netamount);

        $amountinwords = convert_number($netamount);
        $invoicedate = date('Y-m-d').' '.date('H:i:s');

        //Get the logged in  dealer details
        $query0 = "select billingname,inv_mas_region.category as region,inv_mas_dealer.emailid as dealeremailid,inv_mas_dealer.region as regionid,inv_mas_dealer.branch  as branchid,inv_mas_dealer.district as dealerdistrict  from inv_mas_dealer left join inv_mas_region on inv_mas_region.slno = inv_mas_dealer.region  where inv_mas_dealer.slno = '".$dealerid."';";
        $fetch0 = runmysqlqueryfetch($query0);
        $dealername = $fetch0['billingname'];
        $dealerregion = $fetch0['region'];
        $dealeremailid = $fetch0['dealeremailid'];
        $regionid = $fetch0['regionid'];
        $branchid = $fetch0['branchid'];
        $dealerdistrict = $fetch0['dealerdistrict'];

		//update region and branch of customer as per dealer
        $panno = ($_POST['panno'] == '') ? "": " , panno = '".$_POST['panno']."'";
		$query11 = "update inv_mas_customer set currentdealer = '".$dealerid."',branch = '".$branchid."', region = '".$regionid."'".$panno." where slno = '".$lastslno."';";
		$result11 = runmysqlquery($query11);

        //Added for Branch Name correction in invoices	
        $query_branch_name = "select branchname,branch_type,branch_gst_code from inv_mas_branch where slno = $branchid ;";
        $fetch_branch_name = runmysqlqueryfetch($query_branch_name);
        $dealer_branch_name = $fetch_branch_name['branchname'];
        $branch_type = $fetch_branch_name['branch_type'];
        $branch_gst_code = $fetch_branch_name['branch_gst_code'];


        $result = checkcustomer($lastslno);
		if($result == 'true')
        {
            if(!empty($customer_gstno) && $gstcheck!= 'gstinconfirm')
            {
                require_once('generatematrixirn.php');		
				
				$gstinurl = "https://demo.saralgsp.com/eivital/v1.04/Master";
				$dataArray = ['gstin' => $customer_gstno];
				
				$parameters = http_build_query($dataArray);

				$getUrl = $gstinurl."?".$parameters;
				//open connection
				$irnCurl = curl_init();

				curl_setopt($irnCurl, CURLOPT_URL, $getUrl);
				//So that curl_exec returns the contents of the cURL; rather than echoing it
				curl_setopt($irnCurl, CURLOPT_RETURNTRANSFER, true);

				// Set HTTP Header for POST request 
				curl_setopt($irnCurl, CURLOPT_HTTPHEADER, array(
					"AuthenticationToken: $authenticationToken",
					"SubscriptionId: $subscriptionId",
					"user_name: $UserName",
					"AuthToken: $AuthToken",
					"sek: $sek",
					"Gstin: $gstin",
					)
				);
				//for debug only!
				curl_setopt($irnCurl, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($irnCurl, CURLOPT_SSL_VERIFYPEER, false);

				//execute post
				$irnresult = curl_exec($irnCurl);
				//print_r($irnresult);
				//Print error if any
				if(curl_errno($irnCurl))
				{
					echo 'error:' . curl_error($irnCurl);
				} 
				curl_close($irnCurl);
				//echo($irnresult);
				$decodedata = json_decode($irnresult);
				$status = $decodedata->Status;
				$taxtype = $decodedata->TxpType;

            }
            
			$sezenabled = $fetch['sez_enabled'];
            if($status === "ACT" || empty($customer_gstno) || $gstcheck == 'gstinconfirm')
            {
				if(empty($customer_gstno) && $seztaxtype =='yes')
				{
					$errormsg = 'Invoice cannot be generated for non GSTIN SEZ customer';
					echo(json_encode('3^'.$errormsg.'^'.$errorcode));
				}
				else if($taxtype == 'REG' && $seztaxtype=='yes')
				{
					echo(json_encode("3^Customer's GSTIN should be SEZ Type".'^'.$taxtype));
				}
				else if($taxtype == 'SEZ' && $sezenabled=='no' && $seztaxtype=='no')
				{
					$errormsg = "For SEZ tax invoice , SEZ option should be enabled and it should be IGST amount.";
					echo(json_encode("3^".$errormsg.'^'.$taxtype));
				}
				else
				{
					$query1 = "select ifnull(max(slno),0) + 1 as billref from inv_matrixreqpending";
					$resultfetch1 = runmysqlqueryfetch($query1);
					$onlineinvoiceslno = $resultfetch1['billref'];

					$query11 = "Insert into inv_matrixreqpending(slno,customerid,businessname,contactperson,address,
					place,pincode,emailid,description,dealername,createddate,createdby,amount,
					igst,cgst,sgst,netamount,phone,cell,customertype,customercategory,region,
					category,dealerid,products,productquantity,createdbyid,totalproductpricearray,actualproductpricearray,module,stdcode,branch,amountinwords,paymentamount,remarks,invoiceremarks,branchid,regionid,podate,poreference,seztaxtype,invoice_type,state_info,gst_no,reqstatus) values('".$onlineinvoiceslno."','".cusidcombine($generatedcustomerid)."','".$businessname."','".$contactperson."',
					'".addslashes($address)."','".$place."','".$pincode."','".$emailid."','".$description."',
					'".$dealername."','".$invoicedate."','".$loggedindealername."','".$amount."','".$igstamount."','".$cgstamount."','".$sgstamount."','".$netamount."',
					'".$phone."','".$cell."','".$type."','".$category."','".$dealerregion."','".$dealerregion."','".$dealerid."','".$products."','".$quantityvalues."','".$userid."','".$invoiceamountvalues."','".$unitamtvalues."','dealer_module','".$stdcode."','".$dealer_branch_name."',
					'".$amountinwords."','".$paymentamount."','".addslashes($paymentremarks)."','".addslashes($invoiceremarks)."','".$branchid."','".$regionid."','".$podate."','".$poreference."','".$seztaxtype."','".$branch_type."','".$branch_gst_code ."','".$customer_gstno."','Pending');";
					$result11 = runmysqlquery($query11);

					$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','269','".date('Y-m-d').' '.date('H:i:s')."','".$onlineinvoiceslno."')";
					$eventresult = runmysqlquery($eventquery);

					sendinvoicerequestemail($onlineinvoiceslno);
					echo(json_encode('1^Invoice Generation request has been sent!'.'^'.$status.'^'.$taxtype));
				}
                
            }
            else
            {
                if($gstcheck!= 'gstinconfirm' && $customer_gstno!= '0')
                    echo(json_encode('2^'.$status.'^'.$taxtype));
            }

        }
        else
		{
			echo(json_encode('2^Invalid Customer^"'.$lastslno.'" '));
		}
    }
    break;

	case 'getdealerdetails':
	{
		$responsearray = array();
		$dealerid = $_POST['dealerid'];
		$query = "select branch as branchid from inv_mas_dealer where inv_mas_dealer.slno = '".$dealerid."'";
		$resultfetch = runmysqlqueryfetch($query);
		$branchid = $resultfetch['branchid'];

		$query_branch_name = "select branch_gstin,branch_gst_code from inv_mas_branch where slno = $branchid ;";
		$fetch_branch_name = runmysqlqueryfetch($query_branch_name);
		$branch_gstin = $fetch_branch_name['branch_gstin'];
		$branch_gst_code = $fetch_branch_name['branch_gst_code'];

		$responsearray['errorcode'] = 1;
		$responsearray['branch_gst_code'] = $branch_gst_code;
		$responsearray['branch_gstin'] = $branch_gstin;
		echo json_encode($responsearray);
	}
	break;

	case 'gethsncode':
	{
		$responsearray = array();
		$productid = $_POST['productid'];
		$query = "select hsncode from inv_mas_matrixproduct where id = '".$productid."'";
		$resultfetch = runmysqlqueryfetch($query);
		$hsncode = $resultfetch['hsncode'];

		echo json_encode('1^'.$hsncode);
	}
	break;
}

// function pincodestatus($pincode,$statename)
// {
// 	$getdetails = file_get_contents('https://api.postalpincode.in/pincode/'.$pincode);
// 	//echo "<pre>";
// 	//print_r(json_decode($getdetails,true));
// 	$verifypincode = json_decode($getdetails,true);
// 	$status = $verifypincode[0]['Status'];
// 	$pinstate = $verifypincode[0]['PostOffice'][0]['State'];
// 	if($status == "Success" && $pinstate == $statename )
// 		$pincodestatus = 1;
// 	else
// 		$pincodestatus = "";
	
// 	return $pincodestatus;
// }

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
?>