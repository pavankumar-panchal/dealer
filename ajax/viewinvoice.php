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
	case 'invoicedetails':
	{
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		$resultcount = "select * from inv_invoicenumbers";
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
		$query = "select *,inv_invoicenumbers.slno as slno,inv_invoicenumbers.createddate from inv_invoicenumbers left join dealer_online_purchase on dealer_online_purchase.onlineinvoiceno = inv_invoicenumbers.slno left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5) where dealer_online_purchase.module = 'dealer_module' and dealer_online_purchase.createdby = '".$userid."' order by inv_invoicenumbers.slno desc  LIMIT ".$startlimit.",".$limit." ; ";
		$result = runmysqlquery($query);
		if($startlimit == 0)
		{
		$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
		//$grid = '<tr><td><table width="100%" cellpadding="3" cellspacing="0">';
		$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Customer ID</td><td nowrap = "nowrap" class="td-border-grid" align="left">Company Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Net Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Dealer Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Generated By</td><td nowrap = "nowrap" class="td-border-grid" align="left">Type</td></tr>';
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
			
			$grid .= '<tr bgcolor='.$color.' class="gridrow" onclick="gridtoform(\''.$fetch['slno'].'\')" align="left">';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['invoiceno'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".cusidcombine($fetch['customerid'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['businessname'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformatwithtime($fetch['createddate'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['netamount'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['dealername'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['createdby'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['purchasetype'])."</td>";
			$grid .= "</tr>";
		}
		$grid .= "</table>";
		$fetchcount = mysqli_num_rows($result);
		if($slnocount >= $fetchcount)
			
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmoreinvoicedetails(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmoreinvoicedetails(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
			echo '1^'.$grid.'^'.$fetchcount.'^'.$linkgrid;	
	}
	break;
	
	case 'gridtoform':
	{
		$slno = $_POST['slno'];
		$query1 = "SELECT *  from inv_invoicenumbers where slno = '".$slno ."'";
		$fetch1 = runmysqlqueryfetch($query1);

		
		$grid .= '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="100%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="4" class = "table-border-grid">';
		
		 $grid .= '<tr class="tr-grid-header"><td width="54%" align="left" valign="top"  colspan="2" ><strong>Customer Details</strong></td> <td width="46%" align="left" valign="top" ><strong>Invoice Details</strong></td> </tr>';
		
		$grid .= '<tr bgcolor="#f7faff"><td width="54%" align="left" valign="top"  colspan="2" class ="td-border-grid"><strong>Customer ID: </strong>'.$fetch1['customerid'].'</td> <td width="46%" align="left" valign="top" bgcolor="#f7faff" class ="td-border-grid"><strong>Date: </strong>'.changedateformatwithtime($fetch1['createddate']).'</td> </tr>';
		$grid .= '<tr bgcolor="#f7faff"> <td width="54%" align="left" valign="top"  colspan="2"  class ="td-border-grid"><strong>'.$fetch1['businessname'].'</strong></td>  <td width="46%" align="left" valign="top" bgcolor="#f7faff"  class ="td-border-grid"><strong>No: </strong>'.$fetch1['invoiceno'].'</td> </tr>';
		 $grid .= '<tr bgcolor="#f7faff"> <td width="54%" align="left" valign="top"  colspan="2"  class ="td-border-grid"><strong>Representative: </strong>'.$fetch1['dealername'].'</td> <td width="46%" align="left" valign="top" bgcolor="#f7faff"  class ="td-border-grid">&nbsp;</td> </tr>';
		             
		 $grid .= ' <tr><td colspan="3"  class ="td-border-grid"><table width="100%" border="0" align="center" cellpadding="3" cellspacing="0" >
	<tr><td ><table width="100%" border="1" cellspacing="0" cellpadding="3">
	<tr bgcolor="#faebd7"><td width="10%" ><div align="center"><strong>Sl No</strong></div></td><td width="80%" colspan="2" ><div align="center"><strong>Description of Sale</strong></div></td><td width="10%"><div align="center"><strong>Amount</strong></div></td></tr>';
	
	 $description = $fetch1['description'];
	 $offerdescription = $fetch1['offerdescription'];
	 $servicedescription = $fetch1['servicedescription'];
	 $offerremarks = $fetch1['offerremarks'];
	 $descriptionsplit = explode('*',$description);
	 $offerdescriptionsplit = explode('*',$offerdescription);
	 $servicedescriptionsplit = explode('*',$servicedescription);
	 
	 for($i=0;$i<count($descriptionsplit);$i++)
	 {
		$descriptionline = explode('$',$descriptionsplit[$i]);
		$purchasetype = ($descriptionline[2] == 'singleuser')?'Single User':'Multi User';
		$usagetype = ($descriptionline[1] == 'new')?'New':'Updation';
		if($fetch1['purchasetype'] == 'SMS')
		{
			$grid .= '<tr ><td width="10%" style="text-align:centre;">'.$descriptionline[0].'</td><td width="80%" style="text-align:left;" colspan="2" >'.$descriptionline[1].'</td><td  width="10%" style="text-align:right;" >'.$descriptionline[2].'</td></tr>';

		}
		else
		{
			$grid .= '<tr ><td width="10%" style="text-align:centre;">'.$descriptionline[0].'</td><td width="80%" style="text-align:left;" colspan="2" >'.$descriptionline[1].'<br/><span style="font-size:+7" ><strong>Purchase Type</strong> : '.$descriptionline[2].'&nbsp;/&nbsp;<strong>Usage Type</strong> :'.$descriptionline[3].'&nbsp;&nbsp;/ &nbsp;<strong>PIN Number </strong>: '.$descriptionline[4].' (<strong>Serial</strong> : '.$descriptionline[5].')</span></td><td  width="10%" style="text-align:right;" >'.$descriptionline[6].'</td></tr>';
		}
	}

	if($servicedescription <> '')
	{
		for($i=0;$i<count($servicedescriptionsplit);$i++)
		{
			$servicedescriptionsplitline = explode('$',$servicedescriptionsplit[$i]);
			$grid .= '<tr ><td width="10%" style="text-align:centre;">'.$servicedescriptionsplitline[0].'</td><td width="80%" style="text-align:left;" colspan="2" >'.$servicedescriptionsplitline[1].'</td><td  width="10%" style="text-align:right;" >'.$servicedescriptionsplitline[2].'</td></tr>';
		}
	}
	if($offerdescription <> '')
	{
		for($i=0;$i<count($offerdescriptionsplit);$i++)
		{
			$offerdescriptionsplitline = explode('$',$offerdescriptionsplit[$i]);
			$grid .= '<tr ><td width="10%" style="text-align:centre;">&nbsp;</td><td width="80%" style="text-align:left;" colspan="2" >'.strtoupper($offerdescriptionsplitline[1]).': '.$offerdescriptionsplitline[0].'&nbsp;</td><td  width="10%" style="text-align:right;" >'.$offerdescriptionsplitline[2].'&nbsp;</td></tr>';
		}
	}
	if($offerremarks <> '')
	{
		$grid .= '<tr ><td width="10%" style="text-align:centre;">&nbsp;</td><td width="80%" style="text-align:left;" colspan="2" >'.$offerremarks.'&nbsp;</td><td  width="10%" style="text-align:right;" >&nbsp;</td></tr>';
	}
	$grid .= '<tr><td  width="90%" colspan="3" style="text-align:right"><strong>Total</strong></td><td  width="10%" style="text-align:right">'.$fetch1['amount'].'</td></tr>';
	$grid .= '<tr><td colspan="2" style="text-align:left"><span style="font-size:10px" >'.$fetch1['servicetaxdesc'].'</span></td>
	<td  width="20%" style="text-align:right"><strong>Service Tax @ 12.3%</strong></td>
	<td  width="6%" style="text-align:right">'.$fetch1['servicetax'].'</td></tr>';
	$grid .= '<tr><td colspan="2" style="text-align:left"><span style="font-size:10px" >E.&amp;O.E.</span></td>
	<td  width="20%" style="text-align:right"><strong>Net Amount</strong></td>
	<td  width="6%" style="text-align:right"><img src="../images/relyon-rupee-small.jpg" width="9" height="11" border="0" align="absmiddle"  />&nbsp;&nbsp;'.$fetch1['netamount'].'</td></tr>';
	$grid .= '<tr><td colspan="4" style="text-align:left"><strong>Rupee in Words: </strong>'.$fetch1['amountinwords'].' Only</td></tr>';
	$grid .= '</table></td></tr><tr><td align="left" ><strong>Remarks: </strong>'.$fetch1['remarks'].'</td><td>&nbsp;</td></tr></table>';
	$grid .= '</td></tr></table></td></tr>';
	$grid .= '<tr> <td align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;">&nbsp;
	<td></tr></table></td></tr></table></td></tr></table>';
	echo('1^'.$grid);
		
	}
	break;
	case 'search':
	{
		$textfield = $_POST['textfield'];
		$subselection = $_POST['subselection'];
		$orderby = $_POST['orderby'];
		$region = $_POST['region'];
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		$fromdate = changedateformat($_POST['fromdate']);
		$todate = changedateformat($_POST['todate']);
		$region = $_POST['region'];
		$reporttype = $_POST['reporttype'];
		$region = $_POST['region'];
		$branch = $_POST['branch'];
		$productslist = str_replace('\\','',$_POST['productslist']);
		$generatedbypiece = 'left join inv_mas_dealer on inv_mas_dealer.slno = dealer_online_purchase.createdby
';
		$modulepiece = "and dealer_online_purchase.module = 'dealer_module'";

		$datepiece = "and left(inv_invoicenumbers.createddate,10) BETWEEN '".$fromdate."' and '".$todate."'";
		$regionpiece = ($region == "")?(""):(" and inv_mas_region.slno = '".$region."' ");
		$branchpiece = ($branch == "")?(""):(" and inv_mas_branch.slno = '".$branch."' ");
		$reporttypepiece = ($reporttype == "")?(""):(" and (inv_mas_receipt.slno is null or invoiceamount <> receiptamount) ");
		$productpiece = ($productslist == "")?(""):(" and inv_billdetail.productcode in (".$productslist.") ");
		
		if($showtype == 'all')
			$limit = 100000;
		else
			$limit = 10;
		if($startlimit == '')
		{
			$startlimit = 0;$slnocount = 0;
		}
		else
		{
			$startlimit = $slnocount;
			$slnocount = $slnocount;
		}

		switch($orderby)
		{
			case "customerid":
				$orderbyfield = "customerid";
				break;
			case "businessname":
				$orderbyfield = "businessname";
				break;
			case "contactperson":
				$orderbyfield = "contactperson";
				break;
			case "invoiceno":
				$orderbyfield = "invoiceno";
				break;
			case "createddate":
				$orderbyfield = "createddate";
				break;
			default:
				$orderbyfield = "businessname";
				break;
		}
		switch($subselection)
		{
			case "customerid":
				$query = "select distinct inv_invoicenumbers.slno,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_invoicenumbers.invoiceno,inv_invoicenumbers.createddate,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,(inv_mas_receipt.invoiceamount-inv_mas_receipt.receiptamount) as balanceamount,inv_mas_receipt.receiptamount,inv_invoicenumbers.netamount from inv_invoicenumbers left join inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno left join dealer_online_purchase on dealer_online_purchase.onlineinvoiceno = inv_invoicenumbers.slno left join inv_mas_customer on inv_mas_customer.slno = dealer_online_purchase.customerreference left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch left join inv_bill on inv_bill.onlineinvoiceno = inv_invoicenumbers.slno left join inv_billdetail on inv_bill.slno = inv_billdetail.cusbillnumber ".$generatedbypiece." where inv_invoicenumbers.customerid LIKE '%".$textfield."%' ".$datepiece.$regionpiece.$branchpiece.$reporttypepiece.$productpiece.$modulepiece." and dealer_online_purchase.createdby = '".$userid."' ORDER BY ".$orderbyfield."";
				break;
				
			case "contactperson":
				$query = "select distinct inv_invoicenumbers.slno,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_invoicenumbers.invoiceno,inv_invoicenumbers.createddate,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,(inv_mas_receipt.invoiceamount-inv_mas_receipt.receiptamount) as balanceamount,inv_mas_receipt.receiptamount,inv_invoicenumbers.netamount from inv_invoicenumbers left join inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno left join dealer_online_purchase on dealer_online_purchase.onlineinvoiceno = inv_invoicenumbers.slno left join inv_mas_customer on inv_mas_customer.slno = dealer_online_purchase.customerreference left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch left join inv_bill on inv_bill.onlineinvoiceno = inv_invoicenumbers.slno left join inv_billdetail on inv_bill.slno = inv_billdetail.cusbillnumber ".$generatedbypiece." where inv_invoicenumbers.contactperson LIKE '%".$textfield."%' ".$datepiece.$regionpiece.$branchpiece.$reporttypepiece.$productpiece.$modulepiece." and dealer_online_purchase.createdby = '".$userid."' ORDER BY ".$orderbyfield."";
				break;
				
			case "invoiceno":
				$query = "select distinct inv_invoicenumbers.slno,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_invoicenumbers.invoiceno,inv_invoicenumbers.createddate,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,(inv_mas_receipt.invoiceamount-inv_mas_receipt.receiptamount) as balanceamount,inv_mas_receipt.receiptamount,inv_invoicenumbers.netamount from inv_invoicenumbers left join inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno left join dealer_online_purchase on dealer_online_purchase.onlineinvoiceno = inv_invoicenumbers.slno left join inv_mas_customer on inv_mas_customer.slno = dealer_online_purchase.customerreference left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch left join inv_bill on inv_bill.onlineinvoiceno = inv_invoicenumbers.slno left join inv_billdetail on inv_bill.slno = inv_billdetail.cusbillnumber ".$generatedbypiece." where inv_invoicenumbers.invoiceno LIKE '%".$textfield."%' ".$datepiece.$regionpiece.$branchpiece.$reporttypepiece.$productpiece.$modulepiece." and dealer_online_purchase.createdby = '".$userid."' ORDER BY ".$orderbyfield."";
				break;
				
			default:
				$query = "select distinct inv_invoicenumbers.slno,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_invoicenumbers.invoiceno,inv_invoicenumbers.createddate,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,(inv_mas_receipt.invoiceamount-inv_mas_receipt.receiptamount) as balanceamount,inv_mas_receipt.receiptamount,inv_invoicenumbers.netamount from inv_invoicenumbers left join inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno left join dealer_online_purchase on dealer_online_purchase.onlineinvoiceno = inv_invoicenumbers.slno left join inv_mas_customer on inv_mas_customer.slno = dealer_online_purchase.customerreference left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch left join inv_bill on inv_bill.onlineinvoiceno = inv_invoicenumbers.slno left join inv_billdetail on inv_bill.slno = inv_billdetail.cusbillnumber ".$generatedbypiece." where inv_invoicenumbers.businessname LIKE '%".$textfield."%' ".$datepiece.$regionpiece.$branchpiece.$reporttypepiece.$productpiece.$modulepiece." and dealer_online_purchase.createdby = '".$userid."' ORDER BY ".$orderbyfield."";
				break;
		}
		$appendlimit =  " LIMIT ".$startlimit.",".$limit."";
		$result = runmysqlquery($query);
		$query = $query.$appendlimit; //echo($query);exit;
		$fetchresultcount = mysqli_num_rows($result);
		$result = runmysqlquery($query);
		if($startlimit == 0)
		{
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
			$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid"  align="left">Customer ID</td><td nowrap = "nowrap" class="td-border-grid"  align="left">Company Name</td><td nowrap = "nowrap" class="td-border-grid"  align="left">Invoice No</td><td nowrap = "nowrap" class="td-border-grid"  align="left">Date</td><td nowrap = "nowrap" class="td-border-grid"  align="left">Net Amount</td><td nowrap = "nowrap" class="td-border-grid"  align="left">Receipt Amount</td><td nowrap = "nowrap" class="td-border-grid"  align="left">Dealer Name</td><td nowrap = "nowrap" class="td-border-grid"  align="left">Generated By</td></tr>';
		}
		while($fetch = mysqli_fetch_array($result))
		{
			$i_n++;
			$slnocount++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$grid .= '<tr bgcolor='.$color.' class="gridrow" onclick="gridtoform(\''.$fetch['slno'].'\')"  align="left">';
			$grid .= "<td nowrap='nowrap' class='td-border-grid'  align='left'>".$slnocount."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['customerid']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['businessname'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['invoiceno'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformatwithtime($fetch['createddate'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['netamount'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['receiptamount'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['dealername'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['createdby'])."</td>";
			$grid .= "</tr>";
		}
		$fetchcount = mysqli_num_rows($result);
		if($fetchcount < $limit)
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmoresearchfilter(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >></a>&nbsp;&nbsp;<a onclick ="getmoresearchfilter(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" style="cursor:pointer" class="resendtext1>(Show All Records)</a></div></td></tr></table>';
		$grid .= "</table>";
		echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid.'^'.$query;
	}
	break;
	
	case 'resendinvoice':
	{
		$invoiceno = $_POST['invoiceno'];
		$sent = resendinvoice($invoiceno);
		echo($sent);
	}
	break;
	
}


function resendinvoice($invoiceno)
{
	$type = 'resend';
	$invoicedetails = vieworgeneratepdfinvoice($invoiceno,$type);
	$invoicedetailssplit = explode('^',$invoicedetails);
	$filebasename = $invoicedetailssplit[0];
	$businessname = $invoicedetailssplit[1];
	$invoiceno = $invoicedetailssplit[2];
	$emailid =  $invoicedetailssplit[3];
	$customerid =  $invoicedetailssplit[4];
	$slno = substr($customerid,15,20);
	if($filebasename <> '')
	{
		//Dummy line to override To email ID
		$emailid = 'rashmi.hk@relyonsoft.com';
		if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
			$emailids['rashmi'] = 'meghana.b@relyonsoft.com';
		else
			$emailids[$businessname] = $emailid;

	
		$fromname = "Relyon";
		$fromemail = "imax@relyon.co.in";
		require_once("../inc/RSLMAIL_MAIL.php");
		$msg = file_get_contents("../mailcontents/resend-invoice.htm");
		$textmsg = file_get_contents("../mailcontents/resend-invoice.txt");
	
		//Create an array of replace parameters
		$array = array();
		$date = datetimelocal('d-m-Y');
		$array[] = "##DATE##%^%".$date;
		$array[] = "##COMPANY##%^%".$businessname;
		$array[] = "##INVOICENO##%^%".$invoiceno;

		
		//Relyon Logo for email Content, as Inline [Not attachment]
		$filearray = array(
			array('../images/relyon-logo.jpg','inline','1234567890'),array('../filecreated/'.$filebasename,'attachment','1234567891')
		);
		$toarray = $emailids;
		if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
		{
			$bccemailids['rashmi'] ='rashmi.hk@relyonsoft.com';
			//$bccemailids['archanaab'] ='archana.ab@relyonsoft.com';
		}
		else
		{
			//$bccemailids['vijaykumar'] ='vijaykumar@relyonsoft.com';
			$bccemailids['Accounts'] ='accounts@relyonsoft.com';
			$bccemailids['bigmail'] ='bigmail@relyonsoft.com';
			$bccemailids['Relyonimax'] ='relyonimax@gmail.com';
		}
		$bccarray = $bccemailids;
		$msg = replacemailvariable($msg,$array);
		$textmsg = replacemailvariable($textmsg,$array);
		$subject = "Invoice: ".$invoiceno."";
		$html = $msg;
		$text = $textmsg;
		rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,$bccarray,$filearray);
		
		//Insert the mail forwarded details to the logs table
		$bccmailid = 'accounts@relyonsoft.com,bigmail@relyonsoft.com'; 
		inserttologs(imaxgetcookie('dealeruserid'),$slno,$fromname,$fromemail,$emailid,null,$bccmailid,$subject);
		return('1^Sent successfully');
	}
}


?>