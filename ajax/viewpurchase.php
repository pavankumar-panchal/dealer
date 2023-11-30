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

$switchtype = $_POST['switchtype'];
$fromdate = $_POST['fromdate'];
$fromdateconverted = changedateformat($fromdate);
$todate = $_POST['todate'];
$todateconverted = changedateformat($todate);
$product = $_POST['product'];
$dealerid = $_POST['dealerid'];

#Added on 2nd Feb 2018
	    
	$query = "select inv_mas_dealer.maindealers,inv_mas_dealer.dealerhead from inv_mas_dealer where inv_mas_dealer.slno = '".$userid."';";
	$fetch = runmysqlqueryfetch($query);
	$maindealers = $fetch['maindealers'];
	$dealerhead = $fetch['dealerhead'];
	    
#Ends on 2nd Feb 2018

switch($switchtype)
{
	case 'viewpurchase':
	{
		$startlimit = $_POST['startlimit'];
		$slno = $_POST['slno'];
		$showtype = $_POST['showtype'];
		$productpiece = ($product != "all")?(" AND inv_billdetail.productcode = '".$product."'"):("");
		$resultcount = "SELECT inv_bill.slno AS cusbillnumber,netamount FROM inv_bill LEFT JOIN inv_billdetail ON inv_bill.slno = inv_billdetail.cusbillnumber WHERE LEFT(inv_bill.billdate,'10') BETWEEN '".$fromdateconverted."' AND '".$todateconverted."'  AND inv_bill.dealerid ='".$dealerid."' ".$productpiece." AND inv_bill.billstatus ='successful' group by inv_bill.slno ;";
		
		#Added on Feb 2
		if($maindealers == 'yes')
        {
$resultcount = "SELECT inv_bill.slno AS cusbillnumber,netamount FROM inv_bill LEFT JOIN inv_billdetail ON inv_bill.slno = inv_billdetail.cusbillnumber WHERE LEFT(inv_bill.billdate,'10') BETWEEN '".$fromdateconverted."' AND '".$todateconverted."' AND (inv_bill.dealerid ='".$dealerid."' OR inv_bill.dealerid IN (select slno from inv_mas_dealer where dealerhead = '".$userid."')) ".$productpiece." AND inv_bill.billstatus ='successful' group by inv_bill.slno ;"; 
        }
		#Added on Feb 2
				
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
		$query = "SELECT inv_bill.slno AS cusbillnumber,netamount FROM inv_bill LEFT JOIN inv_billdetail ON inv_bill.slno = inv_billdetail.cusbillnumber WHERE LEFT(inv_bill.billdate,'10') BETWEEN '".$fromdateconverted."' AND '".$todateconverted."'  AND inv_bill.dealerid ='".$dealerid."' ".$productpiece." AND inv_bill.billstatus ='successful' group by inv_bill.slno LIMIT ".$startlimit.",".$limit.";";
		
		#Added on Feb 2
		if($maindealers == 'yes')
        {
$$query = "SELECT inv_bill.slno AS cusbillnumber,netamount FROM inv_bill LEFT JOIN inv_billdetail ON inv_bill.slno = inv_billdetail.cusbillnumber WHERE LEFT(inv_bill.billdate,'10') BETWEEN '".$fromdateconverted."' AND '".$todateconverted."' AND (inv_bill.dealerid ='".$dealerid."' OR inv_bill.dealerid IN (select slno from inv_mas_dealer where dealerhead = '".$userid."')) ".$productpiece." AND inv_bill.billstatus ='successful' group by inv_bill.slno LIMIT ".$startlimit.",".$limit.";";
        }
		#Added on Feb 2
				
		$result = runmysqlquery($query);
		if($startlimit == 0)
		{
			$grid .= '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid" style="text-align:left" >';
			$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid">Sl No</td><td nowrap = "nowrap" class="td-border-grid">Bill No</td><td nowrap = "nowrap" class="td-border-grid">Total Amount</td></tr>';
		}
		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_array($result))
		{
			$j++;
			$slno++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			
			$grid .= '<tr class="gridrow"  onclick ="purchasedetailstoform(\''.$fetch['cusbillnumber'].'\');" bgcolor='.$color.'>';
			
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$slno."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['cusbillnumber']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['netamount']."</td>";
			$grid .= '</tr>';
		}
		$grid .= "</table>";

		$fetchcount = mysqli_num_rows($result);
		if($slno >= $fetchresultcount)
		$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
		$linkgrid .= '<table><tr><td class="resendtext"><div align ="left" style="padding-right:10px"><a onclick ="viewmoredetails(\''.$dealerid.'\',\''.$startlimit.'\',\''.$slno.'\',\'more\');">Show More Records >></a>&nbsp;&nbsp;&nbsp;<a onclick ="viewmoredetails(\''.$dealerid.'\',\''.$startlimit.'\',\''.$slno.'\',\'all\');" class="resendtext1"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','135','".date('Y-m-d').' '.date('H:i:s')."','view_purchase')";
		$eventresult = runmysqlquery($eventquery);
		echo $grid.'^'.$fetchresultcount.'^'.$linkgrid;
		//echo($query);
	}
	break;
	
	break;
	case 'viewpurchasedetails':
	{
		$billno = $_POST['billno'];
		$startlimit = $_POST['startlimit'];
		$slno = $_POST['slno'];
		$showtype = $_POST['showtype'];
		$resultcount = "SELECT inv_mas_product.dealerpurchasecaption AS productname, inv_mas_product.productcode as productcode, inv_dealercard.usagetype as usagetype, inv_dealercard.purchasetype as purchasetype, inv_mas_scratchcard.cardid AS cardid,inv_mas_scratchcard.scratchnumber AS scratchnumber FROM (select * from inv_dealercard WHERE inv_dealercard.cusbillnumber='".$billno."') AS inv_dealercard JOIN inv_mas_scratchcard ON inv_mas_scratchcard.cardid = inv_dealercard.cardid JOIN inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode;";
		$resultfetch = runmysqlquery($resultcount);
		if($showtype == 'all')
		$limit = 100000;
		else
		$limit = 2;
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
		$query = "SELECT inv_mas_product.dealerpurchasecaption AS productname, inv_mas_product.productcode as productcode, inv_dealercard.usagetype as usagetype, inv_dealercard.purchasetype as purchasetype, inv_mas_scratchcard.cardid AS cardid,inv_mas_scratchcard.scratchnumber AS scratchnumber FROM (select * from inv_dealercard WHERE inv_dealercard.cusbillnumber='".$billno."') AS inv_dealercard JOIN inv_mas_scratchcard ON inv_mas_scratchcard.cardid = inv_dealercard.cardid JOIN inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode LIMIT ".$startlimit.",".$limit.";";
		$result = runmysqlquery($query);
		if($startlimit == 0)
		{
		$grid .= '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid" >';
		$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid">Sl No</td><td nowrap = "nowrap" class="td-border-grid">Product Code</td><td nowrap = "nowrap" class="td-border-grid">Usage Type</td><td nowrap = "nowrap" class="td-border-grid">Purchase Type</td><td nowrap = "nowrap" class="td-border-grid">Card Id</td><td nowrap = "nowrap" class="td-border-grid">Scratch Number</td></tr>';
		}
		while($fetch = mysqli_fetch_array($result))
		{
			$slno++;
			$i_n++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			
			$grid .= '<tr class="gridrow1" bgcolor='.$color.'>';
			
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$slno."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['productname']."&nbsp;(".$fetch['productcode'].")</td><td nowrap='nowrap' class='td-border-grid'>".strtoupper($fetch['usagetype'])."</td><td nowrap='nowrap' class='td-border-grid'>".strtoupper($fetch['purchasetype'])."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['cardid']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['scratchnumber']."</td>";
			$grid .= '</tr>';
		}
		$fetchcount = mysqli_num_rows($result);
		if($fetchcount < $limit)
		$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
		$linkgrid .= '<table><tr><td onclick ="getmorepurchasedetailstoform(\''.$billno.'\',\''.$startlimit.'\',\''.$slno.'\',\'more\');" class="resendtext"><div align ="left" style="padding-right:10px"><a on>Show More Records >></a>&nbsp;&nbsp;&nbsp;<a onclick ="getmorepurchasedetailstoform(\''.$billno.'\',\''.$startlimit.'\',\''.$slno.'\',\'all\');" class="resendtext1"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
		$grid .= "</table>";
		$fetchresultcount = mysqli_num_rows($resultfetch);
		echo $grid.'^'.$fetchresultcount.'^'.$linkgrid;
	}
	break;
	
}
 
?>