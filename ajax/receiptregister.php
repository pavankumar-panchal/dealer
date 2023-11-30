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
	case 'receiptdetails':
	{
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		$resultcount = "select count(distinct inv_mas_receipt.slno) as count from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno where  (inv_invoicenumbers.dealerid ='".$userid."' OR (inv_mas_receipt.createdby = '".$userid."' AND inv_invoicenumbers.module = 'dealer_module')) and left(inv_mas_receipt.receiptdate,10) = '".date('Y-m-d')."' AND inv_invoicenumbers.status <> 'CANCELLED' order by inv_mas_receipt.slno";
		$fetch10 = runmysqlqueryfetch($resultcount);
		$fetchresultcount = $fetch10['count'];
		
		$invoiceresult = "select distinct inv_mas_receipt.slno,sum(inv_mas_receipt.receiptamount) as receiptamount from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno where  (inv_invoicenumbers.dealerid ='".$userid."' OR (inv_invoicenumbers.createdbyid = '".$userid."' AND inv_invoicenumbers.module = 'dealer_module')) and left(inv_mas_receipt.receiptdate,10) = '".date('Y-m-d')."' AND inv_invoicenumbers.status <> 'CANCELLED' group by inv_mas_receipt.slno with rollup limit ".($fetchresultcount).",1 ";
		$fetchresult1 = runmysqlquery($invoiceresult);
		if(mysqli_num_rows($fetchresult1) > 0)
		{
			$fetchresult = runmysqlqueryfetch($invoiceresult);
			$totalreceipts = $fetchresultcount;
			$totalamount = $fetchresult['receiptamount'];
		}
		else
		{
			$totalreceipts = '0';
			$totalamount = '0';
		}
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
		$query = "select distinct inv_mas_receipt.slno,inv_mas_receipt.receiptdate,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_receipt.receiptamount,inv_mas_receipt.paymentmode,inv_invoicenumbers.invoiceno,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,inv_invoicenumbers.status from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno where  
		(inv_invoicenumbers.dealerid ='".$userid."' OR (inv_invoicenumbers.createdbyid = '".$userid."' AND inv_invoicenumbers.module = 'dealer_module')) and left(inv_mas_receipt.receiptdate,10) = '".date('Y-m-d')."' AND inv_invoicenumbers.status <> 'CANCELLED' order by inv_invoicenumbers.createddate desc LIMIT ".$startlimit.",".$limit." ; ";
		$result = runmysqlquery($query);
		if($startlimit == 0)
		{
		$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
		//$grid = '<tr><td><table width="100%" cellpadding="3" cellspacing="0">';
			$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Receipt No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Receipt Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Status</td><td nowrap = "nowrap" class="td-border-grid" align="left">Customer ID</td><td nowrap = "nowrap" class="td-border-grid" align="left">Customer Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Receipt Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Mode</td><td nowrap = "nowrap" class="td-border-grid" align="left">Sales Person</td><td nowrap = "nowrap" class="td-border-grid" align="left">Prepared By</td></tr>';
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
			
			$grid .= '<tr bgcolor='.$color.' class="gridrow1" align="left">';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['slno']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat(trim($fetch['receiptdate']))."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['invoiceno'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['status'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['customerid'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['businessname'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['receiptamount']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".getpaymentmode($fetch['paymentmode'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['dealername'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['createdby'])."</td>";
			$grid .= "</tr>";
		}
		$grid .= "</table>";
		$fetchcount = mysqli_num_rows($result);
		if($slnocount >= $fetchresultcount)
			
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmoreinvoicedetails(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmoreinvoicedetails(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
		
	
			//echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid.'^'.$totalreceipts.'^'.formatnumber($totalamount).'^'.convert_number($totalamount);	
			$responsearray = array();
			$responsearray['errorcode']	= "1";
			$responsearray['grid']	= $grid;
			$responsearray['fetchresultcount']	= $fetchresultcount;
			$responsearray['linkgrid']	= $linkgrid;
			$responsearray['totalreceipts']	= $totalreceipts;
			$responsearray['totalamount'] = formatnumber($totalamount);
			$responsearray['totalamount1'] = convert_number($totalamount);
			echo(json_encode($responsearray));
	}
	break;
	case 'searchinvoices':
	{
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		$paymentmode = $_POST['paymentmode'];
		$alltimecheck = $_POST['alltimecheck'];
		$fromdate = changedateformat($_POST['fromdate']);
		$todate = changedateformat($_POST['todate']);
		$totalreceipts = '0';
		$totalamount = '0';
		$paymentmodepiece = ($paymentmode == "")?(""):(" and inv_mas_receipt.paymentmode = '".$paymentmode."' ");
		$datepiece = ($alltimecheck == 'yes')?(""):(" AND (inv_mas_receipt.receiptdate BETWEEN '".$fromdate."' and '".$todate."') ");	
		//echo 'dealerid - '. $_POST['dealerid'];
		if($_POST['dealerid'] == 'all') 
		{
			$query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode, inv_mas_dealer.region, inv_mas_dealer.branchhead as branchhead,inv_mas_dealer.telecaller as telecaller from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
where inv_mas_dealer.slno = '".$userid."';";
			$fetch = runmysqlqueryfetch($query);
			$branchhead = $fetch['branchhead'];
			$telecaller = $fetch['telecaller'];
			$branch = $fetch['branch'];
			if($branchhead == 'yes' || $telecaller == 'yes')
			{
				$query1 = "select * from inv_mas_dealer where slno = '".$userid."';";
				$resultfetch1 = runmysqlqueryfetch($query1);
				$branch = $resultfetch1['branch'];
				$query = "SELECT distinct inv_mas_dealer.slno, inv_mas_dealer.businessname,inv_mas_dealer.branch
FROM inv_mas_dealer left join inv_invoicenumbers on  inv_mas_dealer.slno = inv_invoicenumbers.dealerid 
where inv_mas_dealer.disablelogin = 'no' and inv_mas_dealer.dealernotinuse = 'no' and inv_mas_dealer.branch = '".$branch."' order by businessname;";
				$result1 = runmysqlquery($query);
				while($fetch = mysqli_fetch_array($result1))
				{
					$dealerlistdisplay.= '\''.$fetch['slno'].'\''.',';
				}
				$dealerpiece = (" and inv_invoicenumbers.dealerid IN (".trim($dealerlistdisplay,',').") ");
			}
		}
		elseif($_POST['dealerid'] != 'all') 
		{
			$dealerid = $_POST['dealerid'];
			$query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode, inv_mas_dealer.region, inv_mas_dealer.branchhead as branchhead,inv_mas_dealer.telecaller as telecaller from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
where inv_mas_dealer.slno = '".$dealerid."';";
			$fetch = runmysqlqueryfetch($query);
			$branchhead = $fetch['branchhead'];
			$telecaller = $fetch['telecaller'];
			if($telecaller == 'yes' ||$branchhead == 'yes' )
				$dealerpiece = (" and (inv_invoicenumbers.createdbyid = ".$dealerid." or (inv_invoicenumbers.dealerid = ".$dealerid.") ");
			else
				$dealerpiece = (" and inv_invoicenumbers.dealerid = '".$dealerid."' ");
		}
		$databasefield = $_POST['databasefield'];
		$textfield = $_POST['textfield'];
		$productslist = $_POST['productscode'];
		$productlistsplit = explode(',',$productslist);
		$productlistsplitcount = count($productlistsplit);
		$status = $_POST['status'];
		$receiptstatus = $_POST['receiptstatus'];
		$cancelledinvoice = $_POST['cancelledinvoice'];
		$itemlist = $_POST['itemlist'];
		$reconciletype = $_POST['reconciletype'];
		$itemlistsplit = explode(',',$itemlist);
		$itemlistsplitcount = count($itemlistsplit);
		
		if($productslist != '')
		{
			for($i = 0;$i< $productlistsplitcount; $i++)
			{
				if($i < ($productlistsplitcount-1))
					$appendor = 'or'.' ';
				else
					$appendor = '';
					
				$finalproductlist .= ' inv_invoicenumbers.products'.' '.'like'.' "'.'%'.$productlistsplit[$i].'%'.'" '.$appendor."";
			}
		}
		
		
		if($itemlist != '')
		{
			for($j = 0;$j< $itemlistsplitcount; $j++)
			{
				if($j < ($itemlistsplitcount-1))
					$appendor1 = 'or'.' ';
				else
					$appendor1 = '';
					
				$finalitemlist .= ' inv_invoicenumbers.servicedescription'.' '.'like'.' "'.'%'.$itemlistsplit[$j].'%'.'" '.$appendor1."";
			}
		}
		if(($itemlist == '') && ($productslist == ''))
			$finallistarray = "";
		elseif(($itemlist != '') && ($productslist != ''))
			$finallistarray = ' AND ('.$finalproductlist.' '.'OR'.' '.$finalitemlist.')';
		elseif($productslist == '')
			$finallistarray = ' AND ('.$finalitemlist.')';
		elseif($itemlist == '')
			$finallistarray = ' AND ('.$finalproductlist.')';

		$reconciletype_piece = ($reconciletype == "")?(""):(" AND inv_mas_receipt.reconsilation = '".$reconciletype."' ");
		$statuspiece = ($status == "")?(""):(" AND inv_invoicenumbers.status = '".$status."'");
		$cancelledpiece = ($cancelledinvoice == "yes")?("AND inv_invoicenumbers.status <> 'CANCELLED'"):("");
		$receiptstatuspiece = ($receiptstatus == "")?(""):(" and inv_mas_receipt.restatus = '".$receiptstatus."' ");
		
		//echo($dealerpiece);exit;
		
		//Calculation of Total Sale, Total Tax, Total Amount
		/*$resultcount = "select count(distinct inv_mas_receipt.slno) as count from inv_mas_receipt 
left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno 
where  inv_mas_receipt.slno <> '123456789' ".$datepiece.$paymentmodepiece.$dealerpiece.$finallistarray.$reconciletype_piece.$statuspiece.$seriespiece.$cancelledpiece.$receiptstatuspiece."  order by inv_mas_receipt.slno;";
		$fetch10 = runmysqlqueryfetch($resultcount);
		$fetchresultcount = $fetch10['count'];
		$totalreceipts = $fetchresultcount;
			
		$query11 = "select sum(inv_mas_receipt.receiptamount) as receiptamount from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno where  inv_mas_receipt.slno <> '123456789' ".$datepiece.$paymentmodepiece.$dealerpiece.$finallistarray.$reconciletype_piece.$statuspiece.$seriespiece.$cancelledpiece.$receiptstatuspiece." order by inv_mas_receipt.slno;";
		$fetchresult = runmysqlqueryfetch($query11);
		$totalamount += $fetchresult['receiptamount'];*/
		
		
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
		
		
		switch($databasefield)
		{
			case "slno":
				$cusid = strlen($textfield);
				if($cusid == 17)
					$customerid = substr($textfield,12);
				else if($cusid == 20)
					$customerid = substr($textfield,15);
				else
					$customerid = $textfield;
					
				$query = "select distinct inv_mas_receipt.slno,inv_mas_receipt.receiptdate,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_receipt.receiptamount,inv_mas_receipt.paymentmode,inv_invoicenumbers.invoiceno,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,inv_mas_receipt.restatus as receiptstatus,inv_mas_receipt.reconsilation, inv_mas_receipt.receiptamount from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
 where right(inv_invoicenumbers.customerid,5) like '%".$customerid."%' ".$datepiece.$paymentmodepiece.$dealerpiece.$finallistarray.$reconciletype_piece.$statuspiece.$seriespiece.$cancelledpiece.$receiptstatuspiece."  order by inv_invoicenumbers.createddate desc";
				break;
		
			case "invoiceno":
				$query = "select distinct inv_mas_receipt.slno,inv_mas_receipt.receiptdate,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_receipt.receiptamount,inv_mas_receipt.paymentmode,inv_invoicenumbers.invoiceno,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,inv_mas_receipt.restatus as receiptstatus,inv_mas_receipt.reconsilation, inv_mas_receipt.receiptamount from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
 where inv_invoicenumbers.invoiceno LIKE '%".$textfield."%'  ".$datepiece.$paymentmodepiece.$dealerpiece.$finallistarray.$reconciletype_piece.$statuspiece.$seriespiece.$cancelledpiece.$receiptstatuspiece."  order by inv_invoicenumbers.createddate desc";
				break;
			case "receiptno":
				$query = "select distinct inv_mas_receipt.slno,inv_mas_receipt.receiptdate,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_receipt.receiptamount,inv_mas_receipt.paymentmode,inv_invoicenumbers.invoiceno,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,inv_mas_receipt.restatus as receiptstatus,inv_mas_receipt.reconsilation, inv_mas_receipt.receiptamount from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5) where inv_mas_receipt.slno LIKE '%".$textfield."%'  ".$datepiece.$paymentmodepiece.$dealerpiece.$finallistarray.$reconciletype_piece.$statuspiece.$seriespiece.$cancelledpiece.$receiptstatuspiece."  order by inv_invoicenumbers.createddate desc";
				break;
			case "chequeno":
				$query = "select distinct inv_mas_receipt.slno,inv_mas_receipt.receiptdate,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_receipt.receiptamount,inv_mas_receipt.paymentmode,inv_invoicenumbers.invoiceno,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,inv_mas_receipt.restatus as receiptstatus,inv_mas_receipt.reconsilation, inv_mas_receipt.receiptamount from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
 where inv_mas_receipt.chequeno LIKE '%".$textfield."%' ".$datepiece.$paymentmodepiece.$dealerpiece.$finallistarray.$reconciletype_piece.$statuspiece.$seriespiece.$cancelledpiece.$receiptstatuspiece."  order by inv_invoicenumbers.createddate desc";
				break;
			case "chequedate":
				$query = "select distinct inv_mas_receipt.slno,inv_mas_receipt.receiptdate,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_receipt.receiptamount,inv_mas_receipt.paymentmode,inv_invoicenumbers.invoiceno,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,inv_mas_receipt.restatus as receiptstatus,inv_mas_receipt.reconsilation, inv_mas_receipt.receiptamount from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5) where  inv_mas_receipt.chequedate LIKE '%".changedateformat($textfield)."%' ".$datepiece.$paymentmodepiece.$dealerpiece.$finallistarray.$reconciletype_piece.$statuspiece.$seriespiece.$cancelledpiece.$receiptstatuspiece."  order by inv_invoicenumbers.createddate desc";
				break;
			case "depositdate":
				$query = "select distinct inv_mas_receipt.slno,inv_mas_receipt.receiptdate,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_receipt.receiptamount,inv_mas_receipt.paymentmode,inv_invoicenumbers.invoiceno,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,inv_mas_receipt.restatus as receiptstatus,inv_mas_receipt.reconsilation, inv_mas_receipt.receiptamount from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5) where inv_mas_receipt.depositdate LIKE '%".changedateformat($textfield)."%'  ".$datepiece.$paymentmodepiece.$dealerpiece.$finallistarray.$reconciletype_piece.$statuspiece.$seriespiece.$cancelledpiece.$receiptstatuspiece."  order by inv_invoicenumbers.createddate desc";
				break;
			case "drawnon":
				$query = "select distinct inv_mas_receipt.slno,inv_mas_receipt.receiptdate,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_receipt.receiptamount,inv_mas_receipt.paymentmode,inv_invoicenumbers.invoiceno,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,inv_mas_receipt.restatus as receiptstatus,inv_mas_receipt.reconsilation, inv_mas_receipt.receiptamount from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5) where inv_mas_receipt.drawnon LIKE '%".$textfield."%'  ".$datepiece.$paymentmodepiece.$dealerpiece.$finallistarray.$reconciletype_piece.$statuspiece.$seriespiece.$cancelledpiece.$receiptstatuspiece."  order by inv_invoicenumbers.createddate desc";
				break;
				
			case "paymentamt":
				$query = "select distinct inv_mas_receipt.slno,inv_mas_receipt.receiptdate,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_receipt.receiptamount,inv_mas_receipt.paymentmode,inv_invoicenumbers.invoiceno,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,inv_mas_receipt.restatus as receiptstatus,inv_mas_receipt.reconsilation, inv_mas_receipt.receiptamount from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5) where inv_mas_receipt.receiptamount LIKE '%".$textfield."%' ".$datepiece.$paymentmodepiece.$dealerpiece.$finallistarray.$reconciletype_piece.$statuspiece.$seriespiece.$cancelledpiece.$receiptstatuspiece."  order by inv_invoicenumbers.createddate desc";
				break;
			
			default:
				$query = "select distinct inv_mas_receipt.slno,inv_mas_receipt.receiptdate,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_receipt.receiptamount,inv_mas_receipt.paymentmode,inv_invoicenumbers.invoiceno,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,inv_mas_receipt.restatus as receiptstatus,inv_mas_receipt.reconsilation, inv_mas_receipt.receiptamount from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5) where inv_invoicenumbers.businessname LIKE '%".$textfield."%' ".$datepiece.$paymentmodepiece.$dealerpiece.$finallistarray.$reconciletype_piece.$statuspiece.$seriespiece.$cancelledpiece.$receiptstatuspiece."  order by inv_invoicenumbers.createddate desc ";
				break;
		}

		$result = runmysqlquery($query);
		while($fetchres = mysqli_fetch_array($result))
		{
			$totalamount += $fetchres['receiptamount'];
			$totalreceipts =  mysqli_num_rows($result);
		}
		$fetchresultcount = mysqli_num_rows($result);
		$addlimit = " LIMIT ".$startlimit.",".$limit.";";
		$query1 = $query.$addlimit;
		$result1 = runmysqlquery($query1);
		if($startlimit == 0)
		{
		$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
		$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Receipt No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Receipt Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Receipt Status</td><td nowrap = "nowrap" class="td-border-grid" align="left">Customer ID</td><td nowrap = "nowrap" class="td-border-grid" align="left">Customer Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Receipt Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Mode</td><td nowrap = "nowrap" class="td-border-grid" align="left">Sales Person</td><td nowrap = "nowrap" class="td-border-grid" align="left">Prepared By</td><td nowrap = "nowrap" class="td-border-grid" align="left">Reconcile Type</td></tr>';
		}
		
		$i_n = 0;
		while($fetch = mysqli_fetch_array($result1))
		{
			$i_n++;
			$slnocount++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";

				$grid .= '<tr bgcolor='.$color.' class="gridrow1" align="left">';
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['slno']."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat(trim($fetch['receiptdate']))."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['invoiceno'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['receiptstatus'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['customerid'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['businessname'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['receiptamount']."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".getpaymentmode($fetch['paymentmode'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['dealername'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['createdby'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".strtoupper($fetch['reconsilation'])."</td>";
				$grid .= "</tr>";
		}
		$grid .= "</table>";
		$fetchcount = mysqli_num_rows($result);
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','127','".date('Y-m-d').' '.date('H:i:s')."','view_receiptregister')";
		$eventresult = runmysqlquery($eventquery);
		if($slnocount >= $fetchresultcount)
			
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmoresearchfilter(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmoresearchfilter(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
			
	
			//echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid.'^'.$totalreceipts.'^'.formatnumber($totalamount).'^'.convert_number($totalamount);	
			
			$responsearray1 = array();
			$responsearray1['errorcode']	= "1";
			$responsearray1['grid']	= $grid;
			$responsearray1['fetchresultcount']	= $fetchresultcount;
			$responsearray1['linkgrid']	= $linkgrid;
			$responsearray1['totalreceipts'] = $totalreceipts;
			$responsearray1['totalamount'] = formatnumber($totalamount);
			$responsearray1['totalamount1'] = convert_number($totalamount);
			echo(json_encode($responsearray1));
	}
	break;
	
}


?>