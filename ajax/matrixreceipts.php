<?php

ob_start("ob_gzhandler");

include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');

if(imaxgetcookie('dealeruserid')<> '') 
$userid = imaxgetcookie('dealeruserid');
else
{ 
	echo(json_encode('Thinking to redirect'));
	exit;
}
include('../inc/checksession.php');


$lastslno = $_POST['lastslno'];
$switchtype = $_POST['switchtype'];
switch($switchtype)
{
	case 'save':
	{
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
		$drawnon = $_POST['drawnon'];
		$depositdate = changedateformat($_POST['depositdate']);
		$paymentmode = $_POST['paymentmode'];
		$receiptdate = changedateformat($_POST['receiptdate']);
		
		if($lastslno == '')
		{
			//Insert Receipt Details 
			$query = "INSERT INTO inv_mas_receipt(matrixinvoiceno,receiptamount,paymentmode,receiptremarks,privatenote,createddate,createdby,createdip,lastmodifieddate,lastmodifiedby,lastmodifiedip,customerreference,chequedate,chequeno,drawnon,depositdate,receiptdate,receipttime,module) values('".$invoiceno."','".$receiptamount."','".$paymentmode."','".$receiptremarks."','".$privatenote."','".datetimelocal('Y-m-d').' '.datetimelocal('H:i:s')."','".$userid."','".$_SERVER['REMOTE_ADDR']."','".datetimelocal('Y-m-d').' '.datetimelocal('H:i:s')."','".$userid."','".$_SERVER['REMOTE_ADDR']."','".$customerreference."','".$chequedate."','".$chequeno."','".$drawnon."','".$depositdate."','".$receiptdate."','".datetimelocal('H:i:s')."','dealer_module');";
			$result = runmysqlquery($query);
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','286','".date('Y-m-d').' '.date('H:i:s')."','".$customerreference."')";
			$eventresult = runmysqlquery($eventquery);
			
		}
		// else
		// {
		// 	//Update Receipt Details
		// 	$query = "UPDATE inv_mas_receipt set matrixinvoiceno = '".$invoiceno."',receiptamount = '".$receiptamount."',paymentmode = '".$paymentmode."',receiptremarks = '".$receiptremarks."',privatenote = '".$privatenote."',lastmodifieddate = '".date('Y-m-d').' '.date('H:i:s')."',lastmodifiedby ='".$userid."',	lastmodifiedip = '".$_SERVER['REMOTE_ADDR']."',chequedate = '".$chequedate."',chequeno = '".$chequeno."',drawnon = '".$drawnon."',depositdate = '".$depositdate."',receiptdate = '".$receiptdate."',receipttime = '".datetimelocal('H:i:s')."' where slno = '".$lastslno."'; ";
		// 	$result = runmysqlquery($query);
		// 	$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','287','".date('Y-m-d').' '.date('H:i:s')."','".$customerreference."')";
		// 	$eventresult = runmysqlquery($eventquery);
		// }
		echo(json_encode("1^"."Receipt Saved Successfully"));
	}
	break;
	case 'generatecustomerlist':
	{
		$limit = $_POST['limit'];
		$startindex = $_POST['startindex'];
		$customerarray = array();

		$query = "select inv_mas_dealer.branch,inv_mas_dealer.region from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode where inv_mas_dealer.slno = '".$userid."';";
        $fetch = runmysqlqueryfetch($query);
        $branch = $fetch['branch'];
        $region = $fetch['region'];

		if($region == '3')
		{
			$query = "select slno as slno, businessname as businessname from inv_mas_customer where (inv_mas_customer.region = '3') order by businessname LIMIT ".$startindex.",".$limit.";";
		}
		else
		{
			$query = "select slno as slno, businessname as businessname from inv_mas_customer where branch = '".$branch."' order by businessname LIMIT ".$startindex.",".$limit.";";
		}
		$result = runmysqlquery($query);
		$grid = '';
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$customerarray[$count] = $fetch['businessname'].'^'.$fetch['slno'];
			$count++;
		}
		//echo($grid);
		echo(json_encode($customerarray));
	}
	break;
	case 'getcustomercount':
	{
		$responsearray3 = array();

		$query = "select inv_mas_dealer.branch,inv_mas_dealer.region from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode where inv_mas_dealer.slno = '".$userid."';";
        $fetch = runmysqlqueryfetch($query);
		$branch = $fetch['branch'];
        $region = $fetch['region'];

		if($region == '1' || $region == '3')
		{
			$query = "SELECT count(*) as count FROM inv_mas_customer where (inv_mas_customer.region = '3') ORDER BY businessname";
		}
		else
		{
			$query = "SELECT count(*) as count FROM inv_mas_customer where branch = '".$branch."' ORDER BY businessname";
		}
		
		$resultfetch = runmysqlqueryfetch($query);
		$count = $resultfetch['count'];
		$responsearray3['count'] = $count;
		echo(json_encode($responsearray3));
	}
	break;
	case 'gridtoform':
	{
		$gridtoformarray = array();
		$lastslno = $_POST['lastslno'];
		$query = "select * from inv_mas_receipt where slno =  '".$lastslno."';";
		$resultfetch = runmysqlqueryfetch($query);
		$query1 = "select sum(receiptamount) as receiptamount  from inv_mas_receipt where matrixinvoiceno = '".$resultfetch['matrixinvoiceno']."' and restatus <> 'CANCELLED'";
		$fetch1 = runmysqlqueryfetch($query1);
		$receiptamount = $fetch1['receiptamount'];


		if($resultfetch['module'] == 'user_module')
			$querypiece = "select inv_mas_receipt.slno,sum(inv_mas_receipt.receiptamount) as receiptamount,inv_mas_receipt.matrixinvoiceno,inv_mas_receipt.receiptremarks,
			inv_mas_receipt.privatenote,inv_mas_receipt.paymentmode,inv_mas_receipt.createddate,inv_mas_receipt.chequedate,inv_mas_receipt.chequeno,inv_mas_receipt.drawnon,inv_mas_receipt.depositdate,inv_mas_receipt.receiptdate,inv_mas_receipt.createddate,inv_mas_users.fullname as createdby,inv_matrixinvoicenumbers.netamount as invoiceamount,inv_mas_receipt.restatus,inv_mas_receipt.cancelledremarks,inv_mas_receipt.cancelledby,inv_mas_receipt.cancelleddate from inv_mas_receipt 
			left join inv_matrixinvoicenumbers on inv_matrixinvoicenumbers.slno = inv_mas_receipt.matrixinvoiceno
			left join inv_mas_users on inv_mas_users.slno = inv_mas_receipt.createdby where inv_mas_receipt.slno = '".$lastslno."' group by inv_mas_receipt.slno;";
		else
			$querypiece = "select inv_mas_receipt.slno,sum(inv_mas_receipt.receiptamount)  as  receiptamount,inv_mas_receipt.matrixinvoiceno,inv_mas_receipt.receiptremarks,
			inv_mas_receipt.privatenote,inv_mas_receipt.paymentmode,inv_mas_receipt.createddate,inv_mas_receipt.chequedate,inv_mas_receipt.chequeno,inv_mas_receipt.drawnon,inv_mas_receipt.depositdate,inv_mas_receipt.receiptdate,inv_mas_receipt.createddate,inv_mas_dealer.businessname  as createdby,inv_matrixinvoicenumbers.netamount as invoiceamount from inv_mas_receipt left join inv_matrixinvoicenumbers on inv_matrixinvoicenumbers.slno = inv_mas_receipt.matrixinvoiceno
			left join inv_mas_dealer on inv_mas_dealer.slno = inv_mas_receipt.createdby  where inv_mas_receipt.slno = '".$lastslno."' group by inv_mas_receipt.slno;";
 		$fetch = runmysqlqueryfetch($querypiece);
		
		//to fetch the status of the receipt
		$query22 = "SELECT inv_mas_receipt.restatus, inv_mas_receipt.cancelledremarks, inv_mas_receipt.cancelledby, inv_mas_receipt.cancelleddate FROM inv_mas_receipt where inv_mas_receipt.slno = '".$lastslno."'";
		$result22 =  runmysqlqueryfetch($query22);
		
		 if($result22['restatus'] == 'CANCELLED')
		{
			$query011 = "select * from inv_mas_users where slno = '".$result22['cancelledby']."';";
			$resultfetch011 = runmysqlqueryfetch($query011);
			$changedby = $resultfetch011['fullname'];
			$statusremarks = 'Cancelled by '.$changedby.' on '.changedateformatwithtime($result22['cancelleddate']).' <br/>'.$result22['cancelledremarks'];
		}
		elseif($result22['restatus'] == 'ACTIVE')
				$statusremarks = 'Not Avaliable.';
		
		//$amount = getinvoiceamount($fetch['matrixinvoiceno']);
		$balanceamount = $fetch['invoiceamount'] - $receiptamount;
		//$amountsplit = explode('^',$amount);
		//$netamount = $amountsplit[2];
		if($receiptamount == $fetch['invoiceamount'])
			$paid = 'yes';
		else
			$paid = 'no';
		
		$gridtoformarray['errorcode'] = '1';
		$gridtoformarray['slno'] = $fetch['slno'];
		$gridtoformarray['invoiceno'] = $fetch['matrixinvoiceno'];
		$gridtoformarray['receiptamount'] = $receiptamount;
		$gridtoformarray['invoiceamount'] = $fetch['invoiceamount'];
		$gridtoformarray['receiptremarks'] = $fetch['receiptremarks'];
		$gridtoformarray['privatenote'] = $fetch['privatenote'];
		if($fetch['paymentmode'] == "Netbanking")
		{
		   $fetch['paymentmode'] = "onlinetransfer";
		   $gridtoformarray['paymentmode'] = $fetch['paymentmode'];
		}
		else
		{
		  $gridtoformarray['paymentmode'] = $fetch['paymentmode'];
		}
		$gridtoformarray['chequedate'] = changedateformat($fetch['chequedate']);
		$gridtoformarray['chequeno'] = $fetch['chequeno'];
		$gridtoformarray['drawnon'] = $fetch['drawnon'];
		$gridtoformarray['depositdate'] = changedateformat($fetch['depositdate']);
		$gridtoformarray['receiptdate'] = changedateformat($fetch['receiptdate']);
		$gridtoformarray['balanceamount'] = $balanceamount;
		$gridtoformarray['createddate'] = changedateformatwithtime($fetch['createddate']);
		$gridtoformarray['createdby'] = $fetch['createdby'];
		$gridtoformarray['paid'] = $paid;
		$gridtoformarray['invoiceamount'] = $fetch['invoiceamount'];
		$gridtoformarray['status'] = $result22['restatus'];
		$gridtoformarray['statusremarks'] = $statusremarks;
		
		
		echo(json_encode($gridtoformarray));
		
		//echo('1^'.$fetch['slno'].'^'.$fetch['matrixinvoiceno'].'^'.$receiptamount.'^'.$fetch['invoiceamount'].'^'.$fetch['receiptremarks'].'^'.$fetch['privatenote'].'^'.$fetch['paymentmode'].'^'.changedateformat($fetch['chequedate']).'^'.$fetch['chequeno'].'^'.$fetch['drawnon'].'^'.changedateformat($fetch['depositdate']).'^'.changedateformat($fetch['receiptdate']).'^'.$balanceamount.'^'.changedateformatwithtime($fetch['createddate']).'^'.$fetch['createdby'].'^'.$paid.'^'.$resultfetch['invoiceamount'].'^'.$querypiece.'^'.$result22['status'].'^'.$statusremarks);
	}
	break;
	case 'generatereceiptgrid':
		{
			$customerreference = $_POST['customerreference'];
			$startlimit = $_POST['startlimit'];
			$slnocount = $_POST['slnocount'];
			$showtype = $_POST['showtype'];
			$resultcount = "select distinct inv_mas_receipt.slno as slno,inv_mas_customer.customerid,inv_matrixinvoicenumbers.netamount,inv_mas_receipt.receiptamount ,inv_mas_receipt.createddate,inv_mas_dealer.businessname as createdby,inv_mas_receipt.receiptremarks,inv_mas_receipt.privatenote,inv_mas_receipt.lastmodifieddate 
			from inv_mas_receipt 
			left join inv_mas_customer on inv_mas_customer.slno = inv_mas_receipt.customerreference 
			left join inv_matrixinvoicenumbers on inv_matrixinvoicenumbers.slno = inv_mas_receipt.matrixinvoiceno
			left join inv_mas_dealer on inv_mas_dealer.slno = inv_mas_receipt.createdby 
			where inv_mas_customer.slno = '".$customerreference."' and inv_matrixinvoicenumbers.status <> 'CANCELLED';";
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
			$query = "select distinct inv_mas_receipt.slno as slno,inv_mas_customer.customerid,inv_matrixinvoicenumbers.netamount,inv_mas_receipt.receiptamount ,inv_mas_receipt.createddate,inv_mas_dealer.businessname as createdby,inv_mas_receipt.receiptremarks,inv_mas_receipt.privatenote,inv_mas_receipt.lastmodifieddate,inv_mas_receipt.matrixinvoiceno,inv_matrixinvoicenumbers.status , inv_mas_receipt.restatus as receiptstatus, inv_mas_receipt.cancelledremarks as cancelledremarks from inv_mas_receipt
			left join inv_mas_customer on inv_mas_customer.slno = inv_mas_receipt.customerreference 
			left join inv_matrixinvoicenumbers on inv_matrixinvoicenumbers.slno = inv_mas_receipt.matrixinvoiceno
			left join inv_mas_dealer on inv_mas_dealer.slno = inv_mas_receipt.createdby 
			where inv_mas_customer.slno = '".$customerreference."' and inv_matrixinvoicenumbers.status <> 'CANCELLED'  ORDER BY inv_mas_receipt.createddate desc LIMIT ".$startlimit.",".$limit."; ";
			$result = runmysqlquery($query);
			if($startlimit == 0)
			{
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
			$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Customer ID</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Receipt Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Remarks</td><td nowrap = "nowrap" class="td-border-grid" align="left">Cancelled Remarks</td><td nowrap = "nowrap" class="td-border-grid" align="left">Private Note</td><td nowrap = "nowrap" class="td-border-grid" align="left">Created Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Last Modified Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Status</td><td nowrap = "nowrap" class="td-border-grid" align="left">Receipt Status</td></tr>';
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
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['cancelledremarks'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['privatenote'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformatwithtime($fetch['createddate'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformatwithtime($fetch['lastmodifieddate'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['status'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['receiptstatus'])."</td>";
				$grid .= "</tr>";
			}
			$grid .= "</table>";
			$fetchcount = mysqli_num_rows($result);
			if($slnocount >= $fetchresultcount)
				
				$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
				else
				$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmoregenerateschemegrid(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >></a><a onclick ="getmoregeneratereceiptgrid(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
				
				echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid;	
		}
		break;

	case 'getuserinvoicelist':
	{
		$customerreference = $_POST['customerreference'];
		$query = "select slno, invoiceno from inv_matrixinvoicenumbers where right(customerid,5) = '".$customerreference."' and inv_matrixinvoicenumbers.status <> 'CANCELLED' ";
		$result = runmysqlquery($query);
		if(mysqli_num_rows($result) > 0)
		{
			$grid = '<select name="invoivcelist" class="swiftselect-mandatory" id="invoivcelist" style="width:200px;" onchange="getinovoiceamount();"> <option value="">Select a Invoice</option>';
			while($fetch = mysqli_fetch_array($result))
			{
				$grid .='<option value="'.$fetch['slno'].'">'.$fetch['invoiceno'].'</option>';
			}
			$grid .= '</select>';
			echo(json_encode('1^'.$grid));
		}
		else
		{
			$grid = 'No Invoice Available';
			echo(json_encode('2^'.$grid));
		}
		
	}
	break;
	case 'getinovoiceamount':
	{
		$getinovoiceamountarray = array();
		
		$invoiceno = $_POST['invoiceno'];
		$amount = getinvoiceamount($invoiceno);
		$amountsplit = explode('^',$amount);
		$netamount = $amountsplit[0];
		$receivedamount = $amountsplit[1];
		$totalamount = $amountsplit[2];
		$getinovoiceamountarray['errorcode'] = '1';
		$getinovoiceamountarray['netamount'] = $netamount;
		$getinovoiceamountarray['receivedamount'] = $receivedamount;
		$getinovoiceamountarray['totalamount'] = $totalamount;
		echo(json_encode($getinovoiceamountarray));
		//echo('1^'.$netamount.'^'.$receivedamount.'^'.$totalamount);
	}
	break;
	
	case 'sendreceipt':
	{
		$receipt = $_POST['receiptno'];
		$type = $_POST['type'];
		if($type == 'resend')
			$message = sendreceipt('','resend',$receipt);
		elseif($type == 'cancelled')
			$message = sendreceipt('','cancelled',$receipt);
		echo(json_encode($message));
	}
	break;
}

//Function to get the invoice amount
function getinvoiceamount($invoiceno)
{
	$query = "Select * from inv_matrixinvoicenumbers where slno = '".$invoiceno."';";
	$resultfetch= runmysqlqueryfetch($query);
	$netamount = $resultfetch['netamount'];
	$query = "select sum(receiptamount) as receiptamount from inv_mas_receipt where matrixinvoiceno = '".$invoiceno."' and restatus <> 'CANCELLED'" ;
	$resultfetch = runmysqlqueryfetch($query);
	$receiptamount = $resultfetch['receiptamount'];
	if($receiptamount == '')
		$receiptamount = 0;
	$totalamount = $netamount - $receiptamount;
	return $totalamount.'^'.$receiptamount.'^'.$netamount;
}
?>