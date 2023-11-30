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
	case 'save':
	{
		$responsearray = array();
		$customerreference = $_POST['customerreference'];
		$receiptremarks = $_POST['remarks'];
		$privatenote = $_POST['privatenote'];
		$invoiceno = $_POST['invoivcelist'];
		$invoiceamounttotal = getinvoiceamount($invoiceno);
		$invoiceamountsplit = explode('^',$invoiceamounttotal);
		$invoiceamount = $invoiceamountsplit[2];
		$receiptamount = $_POST['receiptamount'];
		$chequedate = changedateformat($_POST['chequedate']);
		$chequeno = $_POST['chequeno'];
		$chequeamount = $_POST['chequeamount'];
		$drawnon = $_POST['drawnon'];
		$depositdate = changedateformat($_POST['depositdate']);
		$paymentmode = $_POST['paymentmode'];
		//$receiptdate = $_POST['receiptdate'];
		$receiptdate = date('d-m-Y');
		
		if($lastslno == '')
		{
			//Insert Receipt Details 
			$query = "INSERT INTO inv_mas_receipt(invoiceno,receiptamount,paymentmode,receiptremarks,privatenote,createddate,createdby,createdip,lastmodifieddate,lastmodifiedby,lastmodifiedip,customerreference,chequedate,chequeno,chequeamount,drawnon,depositdate,receiptdate,receipttime,module) values('".$invoiceno."','".$receiptamount."','".$paymentmode."','".$receiptremarks."','".$privatenote."','".datetimelocal('Y-m-d').' '.datetimelocal('H:i:s')."','".$userid."','".$_SERVER['REMOTE_ADDR']."','".datetimelocal('Y-m-d').' '.datetimelocal('H:i:s')."','".$userid."','".$_SERVER['REMOTE_ADDR']."','".$customerreference."','".$chequedate."','".$chequeno."','".$chequeamount."','".$drawnon."','".$depositdate."','".changedateformat($receiptdate)."','".datetimelocal('H:i:s')."','dealer_module');";
			$result = runmysqlquery($query);
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','123','".date('Y-m-d').' '.date('H:i:s')."','".$customerreference."')";
			$eventresult = runmysqlquery($eventquery);
		}
		/*else
		{
			//Update Receipt Details
			$query = "UPDATE inv_mas_receipt set invoiceno = '".$invoiceno."',invoiceamount = '".$invoiceamount."',receiptamount = '".$receiptamount."',paymentmode = '".$paymentmode."',receiptremarks = '".$receiptremarks."',privatenote = '".$privatenote."',lastmodifieddate = '".date('Y-m-d').' '.date('H:i:s')."',lastmodifiedby ='".$userid."',	lastmodifiedip = '".$_SERVER['REMOTE_ADDR']."',chequedate = '".$chequedate."',chequeno = '".$chequeno."',chequeamount = '".$chequeamount."',drawnon = '".$drawnon."',depositdate = '".$depositdate."',receiptdate = '".$receiptdate."',receipttime = '".datetimelocal('H:i:s')."' where slno = '".$lastslno."'; ";
			$result = runmysqlquery($query);
		}*/
		
		$responsearray['errorcode'] = "1";
		$responsearray['errormsg'] = "Receipt Saved Successfully";
		echo(json_encode($responsearray));
		//echo("1^"."Receipt Saved Successfully");
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
	case 'gridtoform':
	{
		$lastslno = $_POST['lastslno'];
		$query = "select inv_mas_receipt.slno, inv_mas_receipt.receiptamount,inv_mas_receipt.invoiceno,inv_mas_receipt.receiptremarks,inv_mas_receipt.privatenote,inv_mas_receipt.paymentmode,inv_mas_receipt.createddate,inv_mas_receipt.chequedate,inv_mas_receipt.chequeno,inv_mas_receipt.drawnon,inv_mas_receipt.depositdate,inv_mas_receipt.receiptdate,inv_mas_receipt.createddate, inv_mas_dealer.businessname,inv_invoicenumbers.netamount as invoiceamount,inv_mas_receipt.module from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno  left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid  where inv_mas_receipt.slno =  '".$lastslno."' ;";
		$fetch = runmysqlqueryfetch($query);
		
		$query1 = "select sum(receiptamount) as receiptamount  from inv_mas_receipt where invoiceno = '".$fetch['invoiceno']."'";
		$fetch1 = runmysqlqueryfetch($query1);
		
		//to fetch the status of the receipt
		$query22 = "SELECT inv_mas_receipt.restatus, inv_mas_receipt.cancelledremarks, inv_mas_receipt.cancelledby, inv_mas_receipt.cancelleddate FROM inv_mas_receipt where inv_mas_receipt.slno = '".$lastslno."'";
		$result22 =  runmysqlqueryfetch($query22);

		if($fetch['module'] == 'user_module')
			$querypiece = "select inv_mas_users.fullname as createdby from inv_mas_receipt left join inv_mas_users on inv_mas_users.slno = inv_mas_receipt.createdby where inv_mas_receipt.slno = '".$lastslno."';";
		else
			$querypiece = "select inv_mas_dealer.businessname  as createdby from inv_mas_receipt left join inv_mas_dealer on inv_mas_dealer.slno = inv_mas_receipt.createdby  where inv_mas_receipt.slno = '".$lastslno."';";
 		$fetchname = runmysqlqueryfetch($querypiece);
		
		if($result22['restatus'] == 'CANCELLED')
		{
			$query011 = "select * from inv_mas_users where slno = '".$result22['cancelledby']."';";
			$resultfetch011 = runmysqlqueryfetch($query011);
			$changedby = $resultfetch011['fullname'];
			$statusremarks = 'Cancelled by '.$changedby.' on '.changedateformatwithtime($result22['cancelleddate']).' <br/>'.$result22['cancelledremarks'];
		}
		elseif($result22['restatus'] == 'ACTIVE')
				$statusremarks = 'Not Avaliable.';
				
		$receiptamount = $fetch1['receiptamount'];
		$balanceamount = $fetch['invoiceamount'] - $receiptamount;
		if($receiptamount == $fetch['invoiceamount'])
			$paid = 'yes';
		else
			$paid = 'no';
		$amount = getinvoiceamount($fetch['invoiceno']);
		$amountsplit = explode('^',$amount);
		$netamount = $amountsplit[2];
		
		$responsearray2 = array();
		$responsearray2['errorcode'] = "1";
		$responsearray2['slno'] = $fetch['slno'];
		$responsearray2['invoiceno'] = $fetch['invoiceno'];
		$responsearray2['receiptamount'] = $receiptamount;
		$responsearray2['netamount'] = $netamount;
		$responsearray2['receiptremarks'] = $fetch['receiptremarks'];
		$responsearray2['privatenote'] = $fetch['privatenote'];
		if($fetch['paymentmode'] == "Netbanking")
		{
			$fetch['paymentmode'] = "onlinetransfer";
		    $responsearray2['paymentmode'] = $fetch['paymentmode'];
		}
		else
		{
			$responsearray2['paymentmode'] = $fetch['paymentmode'];
		}
		$responsearray2['chequedate'] = changedateformat($fetch['chequedate']);
		$responsearray2['chequeno'] = $fetch['chequeno'];
		$responsearray2['drawnon'] = $fetch['drawnon'];
		$responsearray2['depositdate'] = changedateformat($fetch['depositdate']);
		$responsearray2['receiptdate'] = changedateformat($fetch['receiptdate']);
		$responsearray2['balanceamount'] = $balanceamount;
		$responsearray2['createddate'] = changedateformatwithtime($fetch['createddate']);
		$responsearray2['businessname'] = $fetch['businessname'];
		$responsearray2['paid'] = $paid;
		$responsearray2['receiptamount'] = $fetch['receiptamount'];
		$responsearray2['status'] = $result22['restatus'];
		$responsearray2['statusremarks'] = $statusremarks;
		$responsearray2['createdby'] = $fetchname['createdby'];
		echo(json_encode($responsearray2));
		
		//echo('1^'.$fetch['slno'].'^'.$fetch['invoiceno'].'^'.$receiptamount.'^'.$netamount.'^'.$fetch['receiptremarks'].'^'.$fetch['privatenote'].'^'.$fetch['paymentmode'].'^'.changedateformat($fetch['chequedate']).'^'.$fetch['chequeno'].'^'.$fetch['drawnon'].'^'.changedateformat($fetch['depositdate']).'^'.changedateformat($fetch['receiptdate']).'^'.$balanceamount.'^'.changedateformatwithtime($fetch['createddate']).'^'.$fetch['businessname'].'^'.$paid.'^'.$fetch['receiptamount'].'^'.$result22['status'].'^'.$statusremarks);
	}
	break;
	case 'generatereceiptgrid':
		{
			$customerreference = $_POST['customerreference'];
			$startlimit = $_POST['startlimit'];
			$slnocount = $_POST['slnocount'];
			$showtype = $_POST['showtype'];
			$resultcount = "select distinct inv_mas_receipt.slno as slno,inv_mas_customer.customerid,inv_invoicenumbers.netamount,inv_mas_receipt.receiptamount,inv_mas_receipt.createddate,inv_mas_dealer.businessname as createdby,inv_mas_receipt.receiptremarks,inv_mas_receipt.privatenote,inv_mas_receipt.lastmodifieddate	from inv_mas_receipt left join inv_mas_customer on inv_mas_customer.slno = inv_mas_receipt.customerreference left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno left join inv_mas_dealer on inv_mas_dealer.slno = inv_mas_receipt.createdby 	where inv_mas_customer.slno = '".$customerreference."' and inv_invoicenumbers.status <> 'CANCELLED';";
			$fetch10 = runmysqlquery($resultcount);
			$fetchresultcount = mysqli_num_rows($fetch10);
			if($showtype == 'all')
			$limit = 100000;
			else
			$limit = 10;
			if($startlimit == '')
			{
				$startlimit = 0;
				$slnocount = 0;
			}
			else
			{
				$startlimit = $slnocount ;
				$slnocount = $slnocount;
			}
			$query = "select distinct inv_mas_receipt.slno as slno,inv_mas_customer.customerid,inv_invoicenumbers.netamount,inv_mas_receipt.receiptamount ,inv_mas_receipt.createddate,inv_mas_dealer.businessname as createdby,inv_mas_receipt.receiptremarks,inv_mas_receipt.privatenote,inv_mas_receipt.lastmodifieddate,inv_mas_receipt.invoiceno
	,inv_invoicenumbers.status	from inv_mas_receipt left join inv_mas_customer on inv_mas_customer.slno = inv_mas_receipt.customerreference left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno left join inv_mas_dealer on inv_mas_dealer.slno = inv_mas_receipt.createdby where inv_mas_customer.slno = '".$customerreference."' and inv_invoicenumbers.status <> 'CANCELLED'  ORDER BY inv_mas_receipt.createddate desc LIMIT ".$startlimit.",".$limit."; ";
			$result = runmysqlquery($query);
			if($startlimit == 0)
			{
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
			$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Customer ID</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Receipt Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Remarks</td><td nowrap = "nowrap" class="td-border-grid" align="left">Private Note</td><td nowrap = "nowrap" class="td-border-grid" align="left">Created Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Last Modified Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Status</td></tr>';
			}
			
			$i_n = 0;
			while($fetch = mysqli_fetch_array($result))
			{
				$i_n++;
				$slnocount++;
				$color;
				if($i_n%2 == 0)
					$color = "#edf4ff";
				else
					$color = "#f7faff";
				$grid .= '<tr bgcolor='.$color.' class="gridrow" onclick ="receiptgridtoform(\''.$fetch['slno'].'\');" align="left" >';
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".cusidcombine($fetch['customerid'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['netamount'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['receiptamount'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['receiptremarks'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['privatenote'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformatwithtime($fetch['createddate'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformatwithtime($fetch['lastmodifieddate'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['status'])."</td>";
				$grid .= "</tr>";
			}
			$grid .= "</table>";
			$fetchcount = mysqli_num_rows($result);
			if($slnocount >= $fetchresultcount)
				
				$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
				else
				$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmoregeneratereceiptgrid(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >></a><a onclick ="getmoregeneratereceiptgrid(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
				
				$responsearray3 = array();
				$responsearray3['errorcode'] = "1";
				$responsearray3['grid'] = $grid;
				$responsearray3['fetchresultcount'] = $fetchresultcount;
				$responsearray3['linkgrid'] = $linkgrid;
				echo(json_encode($responsearray3));
				
				//echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid;	
		}
		break;
	case 'getuserinvoicelist':
	{
		$responsearray4 = array();
		$customerreference = $_POST['customerreference'];
		$query = "select slno, invoiceno from inv_invoicenumbers where right(customerid,5) = '".$customerreference."'  and inv_invoicenumbers.status <> 'CANCELLED'";
		$result = runmysqlquery($query);
		if(mysqli_num_rows($result) > 0)
		{
			$grid = '<select name="invoivcelist" class="swiftselect-mandatory" id="invoivcelist" style="width:200px;" onchange="getinovoiceamount();"> <option value="">Select a Invoice</option>';
			while($fetch = mysqli_fetch_array($result))
			{
				$grid .='<option value="'.$fetch['slno'].'">'.$fetch['invoiceno'].'</option>';
			}
			$grid .= '</select>';
			
			$responsearray4['errorcode'] = "1";
			$responsearray4['grid'] = $grid;
			echo(json_encode($responsearray4));
		}
		else
		{
			$grid = 'No Invoice Available';
			$responsearray4['errorcode'] = "2";
			$responsearray4['grid'] = $grid;
			echo(json_encode($responsearray4));
		}
		
	}
	break;
	case 'getinovoiceamount':
	{
		$invoiceno = $_POST['invoiceno'];
		$amount = getinvoiceamount($invoiceno);
		$amountsplit = explode('^',$amount);
		$netamount = $amountsplit[0];
		$receivedamount = $amountsplit[1];
		$totalamount = $amountsplit[2];
		
		$responsearray6 = array();
		$responsearray6['errorcode'] = "1";
		$responsearray6['netamount'] = $netamount;
		$responsearray6['receivedamount'] = $receivedamount;
		$responsearray6['totalamount'] = $totalamount;
		echo(json_encode($responsearray6));
		//echo('1^'.$netamount.'^'.$receivedamount.'^'.$totalamount);

	}
	break;
	
	case 'sendreceipt':
	{
		$receipt = $_POST['receiptno'];
		$type = $_POST['type'];
		if($type == 'resend')
			$message = sendreceipt($receipt,'resend');
		elseif($type == 'cancelled')
			$message = sendreceipt($receipt,'cancelled');
		$responsearray7 = array();
		$responsearray7['message'] = $message;
		echo(json_encode($responsearray7));
		//echo($message);
	}
	break;
}

//Function to get the invoice amount
function getinvoiceamount($invoiceno)
{
	$query = "Select * from inv_invoicenumbers where slno = '".$invoiceno."';";
	$resultfetch= runmysqlqueryfetch($query);
	$netamount = $resultfetch['netamount'];
	$query = "select sum(receiptamount) as receiptamount from inv_mas_receipt where invoiceno = '".$invoiceno."'" ;
	$resultfetch = runmysqlqueryfetch($query);
	$receiptamount = $resultfetch['receiptamount'];
	if($receiptamount == '')
		$receiptamount = 0;
	$totalamount = $netamount - $receiptamount;
	return $totalamount.'^'.$receiptamount.'^'.$netamount;
}

?>