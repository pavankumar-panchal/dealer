<?php
ob_start("ob_gzhandler");

include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');
include('../inc/checksession.php');

if(imaxgetcookie('dealeruserid') <> '') 
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
		$currentdate = date('Y-m-d');
		
		$resultcount = "SELECT count(distinct inv_invoicenumbers.slno) as  count from inv_invoicenumbers  left join (select sum(receiptamount) as receiptamount, invoiceno, status from inv_mas_receipt where inv_mas_receipt.status <> 'CANCELLED' group by invoiceno) as inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid where left(inv_invoicenumbers.createddate,10) <= '".$currentdate."' and DATEDIFF('".$currentdate."',left(inv_invoicenumbers.createddate,10)) >= '0' and (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) > 0 and (inv_invoicenumbers.dealerid = '".$userid."' OR (inv_invoicenumbers.createdbyid = '".$userid."' AND inv_invoicenumbers.module = 'dealer_module')) and inv_invoicenumbers.status <> 'CANCELLED'  ORDER BY inv_invoicenumbers.slno ;";
		$fetch10 = runmysqlqueryfetch($resultcount);
		$fetchresultcount = $fetch10['count'];
		
		$invoiceresult = "SELECT distinct inv_invoicenumbers.slno, sum(inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) as outstandingamount  from inv_invoicenumbers  left join (select sum(receiptamount) as receiptamount, invoiceno, status from inv_mas_receipt where inv_mas_receipt.status <> 'CANCELLED' group by invoiceno) as inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid where left(inv_invoicenumbers.createddate,10) <= '".$currentdate."' and DATEDIFF('".$currentdate."',left(inv_invoicenumbers.createddate,10)) >= '0' and (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) > 0 and (inv_invoicenumbers.dealerid = '".$userid."' OR (inv_invoicenumbers.createdbyid = '".$userid."' AND inv_invoicenumbers.module = 'dealer_module')) and inv_invoicenumbers.status <> 'CANCELLED' group by inv_invoicenumbers.slno with rollup limit ".($fetchresultcount).",1 ";
		$fetchresult1 = runmysqlquery($invoiceresult);
		if(mysqli_num_rows($fetchresult1) > 0)
		{
			$fetchresult = runmysqlqueryfetch($invoiceresult);
			$totalinvoices = $fetchresultcount;
			$totalamount = $fetchresult['outstandingamount'];
		}
		else
		{
			$totalinvoices = '0';
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
		$query = "SELECT distinct inv_invoicenumbers.slno,left(inv_invoicenumbers.createddate,10) as createddate,inv_invoicenumbers.invoiceno,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_dealer.businessname as dealername,inv_invoicenumbers.netamount,ifnull(inv_mas_receipt.receiptamount,0) as receiptamount, (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) as outstandingamount , DATEDIFF('".$currentdate."',left(inv_invoicenumbers.createddate,10)) as age,inv_invoicenumbers.status from inv_invoicenumbers  left join (select sum(receiptamount) as receiptamount, invoiceno, status from inv_mas_receipt where inv_mas_receipt.status <> 'CANCELLED' group by invoiceno) as inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid where left(inv_invoicenumbers.createddate,10) <= '".$currentdate."' and DATEDIFF('".$currentdate."',left(inv_invoicenumbers.createddate,10))>= '0' and (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) > 0 and (inv_invoicenumbers.dealerid = '".$userid."' OR (inv_invoicenumbers.createdbyid = '".$userid."' AND inv_invoicenumbers.module = 'dealer_module')) and inv_invoicenumbers.status <> 'CANCELLED' ORDER BY inv_invoicenumbers.createddate desc LIMIT ".$startlimit.",".$limit." ; ";
		$result = runmysqlquery($query);
		if($startlimit == 0)
		{
		$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
		$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Status</td><td nowrap = "nowrap" class="td-border-grid" align="left">Customer ID</td><td nowrap = "nowrap" class="td-border-grid" align="left">Customer Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Sales Person</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Received Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Outstanding Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Age (Days)</td></tr>';
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
			if($fetch['outstandingamount'] < 0)
				$outstandingamount = '0';
			else
				$outstandingamount = $fetch['outstandingamount'];
			if($fetch['age'] < 0)
				$age = '0';
			else
				$age = $fetch['age'];
			
			$grid .= '<tr bgcolor='.$color.' class="gridrow1" align="left">';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat(trim($fetch['createddate']))."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['invoiceno']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['status']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['customerid'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim(trim($fetch['businessname']))."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['dealername']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['netamount'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['receiptamount'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".($outstandingamount)."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($age)."</td>";
			$grid .= "</tr>";
		}
		$grid .= "</table>";
		$fetchcount = mysqli_num_rows($result);
		if($slnocount >= $fetchresultcount)
			
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmoreinvoicedetails(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmoreinvoicedetails(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
			//echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid.'^'.$totalinvoices.'^'.formatnumber($totalamount).'^'.convert_number($totalamount);
			$responsearray = array();
			$responsearray['errorcode'] = "1";
			$responsearray['grid'] = $grid;
			$responsearray['fetchresultcount'] = $fetchresultcount;
			$responsearray['linkgrid'] = $linkgrid;
			$responsearray['totalinvoices'] = $totalinvoices;
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
		$sortby = $_POST['sortby'];
		$sortby1 = $_POST['sortby1'];
		$aged = $_POST['aged'];
		$totalinvoices = 0;
		if($_POST['dealerid'] == 'all') 
		{
			$query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode, inv_mas_dealer.region, inv_mas_dealer.branchhead as branchhead,inv_mas_dealer.telecaller as telecaller from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
where inv_mas_dealer.slno = '".$userid."';";
			$fetch = runmysqlqueryfetch($query);
			$branchhead = $fetch['branchhead'];
			$telecaller = $fetch['telecaller'];
			if($branchhead == 'yes')
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
			elseif($telecaller == 'yes')
			{
				$query33 = "select distinct inv_mas_dealer.slno as slno,inv_mas_dealer.businessname as businessname from inv_invoicenumbers left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.createdbyid where (inv_invoicenumbers.dealerid = '".$userid."' OR inv_invoicenumbers.createdbyid = '".$userid."') AND inv_invoicenumbers.module = 'dealer_module' order by businessname ";
				$result1 = runmysqlquery($query33);
				while($fetch = mysqli_fetch_array($result1))
				{
					$dealerlistdisplay.= '\''.$fetch['slno'].'\''.',';
				}
				$dealerpiece = (" and (inv_invoicenumbers.createdbyid IN (".trim($dealerlistdisplay,',').") or (inv_invoicenumbers.dealerid IN (".trim($dealerlistdisplay,',')."))) ");
				
			}
			
		}
		else
		{
			$dealerid = $_POST['dealerid'];
			$query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode, inv_mas_dealer.region, inv_mas_dealer.branchhead as branchhead,inv_mas_dealer.telecaller as telecaller from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
where inv_mas_dealer.slno = '".$userid."';";
			$fetch = runmysqlqueryfetch($query);
			$branchhead = $fetch['branchhead'];
			$telecaller = $fetch['telecaller'];
			if($telecaller == 'yes')
			{
				$query = "select distinct inv_mas_dealer.slno as slno,inv_mas_dealer.businessname as businessname from inv_invoicenumbers left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.createdbyid where (inv_invoicenumbers.dealerid = '".$userid."' OR inv_invoicenumbers.createdbyid = '".$userid."') AND inv_invoicenumbers.module = 'dealer_module' order by businessname;";
				$result1 = runmysqlquery($query);
				while($fetch = mysqli_fetch_array($result1))
				{
					$dealerlistdisplay.= '\''.$fetch['slno'].'\''.',';
				}	
				if($dealerid != $userid)
					$dealerpiece = (" and inv_invoicenumbers.dealerid = '9999999999' ");
				else
					$dealerpiece = (" and (inv_invoicenumbers.createdbyid IN (".trim($dealerlistdisplay,',').") or (inv_invoicenumbers.dealerid IN (".trim($dealerlistdisplay,',')."))) ");
			}
			else
			{
				$query22 = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.branchhead as branchhead,inv_mas_dealer.telecaller as telecaller from inv_mas_dealer 
where inv_mas_dealer.slno = '".$dealerid."';";
				$fetch22 = runmysqlqueryfetch($query22);
				if($fetch22['telecaller'] == 'yes')
					$dealerpiece = (" and (inv_invoicenumbers.createdbyid = '".$dealerid."' or  inv_invoicenumbers.dealerid = '".$dealerid."') ");
				else
					$dealerpiece = (" and inv_invoicenumbers.dealerid = '".$dealerid."' ");
			}
		}
		$fromdate = changedateformat($_POST['fromdate']);
		
		$paymentmodepiece = ($paymentmode == "")?(""):(" and inv_mas_receipt.paymentmode = '".$paymentmode."' ");
		
		//Calculation of Total Sale, Total Tax, Total Amount
		$resultcount = "SELECT count(distinct inv_invoicenumbers.slno) as  count from inv_invoicenumbers  
left join (select sum(receiptamount) as receiptamount, invoiceno,`status` from inv_mas_receipt where inv_mas_receipt.status <> 'CANCELLED' group by invoiceno) as inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid where left(inv_invoicenumbers.createddate,10) <= '".$fromdate."' and DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) >= '".$aged."' and (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) > 0 ".$dealerpiece." and inv_invoicenumbers.status <> 'CANCELLED'  ORDER BY  inv_invoicenumbers.slno ;";
		$fetch10 = runmysqlqueryfetch($resultcount);
		$fetchresultcount = $fetch10['count'];
		$totalinvoices = $fetchresultcount; 
		if($fetchresultcount > 0 )
		{
			$query11 = "SELECT inv_invoicenumbers.slno ,sum(inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) as outstandingamount from inv_invoicenumbers  
	left join (select sum(receiptamount) as receiptamount, invoiceno,`status` from inv_mas_receipt where inv_mas_receipt.status <> 'CANCELLED' group by invoiceno) as inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid where left(inv_invoicenumbers.createddate,10) <= '".$fromdate."' and DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) >= '".$aged."' and (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) > 0 ".$dealerpiece." and inv_invoicenumbers.status <> 'CANCELLED'  group by inv_invoicenumbers.slno with rollup   limit ".($fetchresultcount).",1";
			$fetchresult = runmysqlqueryfetch($query11);
			$totalamount = $fetchresult['outstandingamount'];
		}
		
		
		/*if($_POST['dealerid'] == 'all') 
		{
			$query1 = "select * from inv_mas_dealer where slno = '".$userid."';";
			$resultfetch1 = runmysqlqueryfetch($query1);
			$branch = $resultfetch1['branch'];
			$query = "SELECT distinct inv_mas_dealer.slno, inv_mas_dealer.businessname, inv_mas_dealer.disablelogin, inv_mas_dealer.dealernotinuse,inv_mas_dealer.branch  FROM inv_mas_dealer left join inv_invoicenumbers on  inv_invoicenumbers.dealerid = inv_mas_dealer.slno where inv_mas_dealer.disablelogin = 'no' and inv_mas_dealer.dealernotinuse = 'no' and inv_invoicenumbers.branchid = '".$branch."' order by businessname;";
			$result1 = runmysqlquery($query);
			while($fetch = mysqli_fetch_array($result1))
			{
				$resultcount1 = "SELECT count(distinct inv_invoicenumbers.slno) as  count from inv_invoicenumbers  
left join (select sum(receiptamount) as receiptamount, invoiceno,`status` from inv_mas_receipt where inv_mas_receipt.status <> 'CANCELLED' group by invoiceno) as inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid where left(inv_invoicenumbers.createddate,10) <= '".$fromdate."' and DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) >= '".$aged."' and (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) > 0  and inv_invoicenumbers.dealerid = '".$fetch['slno']."' and inv_invoicenumbers.status <> 'CANCELLED'  ORDER BY  inv_invoicenumbers.slno ;";
				$fetch101 = runmysqlqueryfetch($resultcount1);
				$invoiceresult1 = "SELECT inv_invoicenumbers.slno ,sum(inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) as outstandingamount from inv_invoicenumbers  
left join (select sum(receiptamount) as receiptamount, invoiceno,`status` from inv_mas_receipt where inv_mas_receipt.status <> 'CANCELLED' group by invoiceno) as inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid where left(inv_invoicenumbers.createddate,10) <= '".$fromdate."' and DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) >= '".$aged."' and (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) > 0 and  inv_invoicenumbers.dealerid = '".$fetch['slno']."' and inv_invoicenumbers.status <> 'CANCELLED'  group by inv_invoicenumbers.slno with rollup   limit ".($fetch101['count']).",1";
				$fetchres =runmysqlquery($invoiceresult1);
				if(mysqli_num_rows($fetchres) != '' )
				{
					$resultcount2 = "SELECT count(distinct inv_invoicenumbers.slno) as  count from inv_invoicenumbers  
left join (select sum(receiptamount) as receiptamount, invoiceno,`status` from inv_mas_receipt where inv_mas_receipt.status <> 'CANCELLED' group by invoiceno) as inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid where left(inv_invoicenumbers.createddate,10) <= '".$fromdate."' and DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) >= '".$aged."' and (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) > 0 and  inv_invoicenumbers.dealerid = '".$fetch['slno']."' and inv_invoicenumbers.status <> 'CANCELLED'  ORDER BY  inv_invoicenumbers.slno ;";
					$fetch102 = runmysqlqueryfetch($resultcount2);
					$invoiceresult = "SELECT inv_invoicenumbers.slno ,sum(inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) as outstandingamount from inv_invoicenumbers  
left join (select sum(receiptamount) as receiptamount, invoiceno,`status` from inv_mas_receipt where inv_mas_receipt.status <> 'CANCELLED' group by invoiceno) as inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid where left(inv_invoicenumbers.createddate,10) <= '".$fromdate."' and DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) >= '".$aged."' and (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) > 0 and  inv_invoicenumbers.dealerid = '".$fetch['slno']."' and inv_invoicenumbers.status <> 'CANCELLED'  group by inv_invoicenumbers.slno with rollup   limit ".($fetch102['count']).",1" ;

					$fetchres =runmysqlqueryfetch($invoiceresult);
					
					$totalamount += $fetchres['outstandingamount'];
				}
				else
					$totalamount = $totalamount;
			}
		}
		else
		{
			$invoiceresult1 = "SELECT inv_invoicenumbers.slno ,sum(inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) as outstandingamount from inv_invoicenumbers  
left join (select sum(receiptamount) as receiptamount, invoiceno,`status` from inv_mas_receipt where inv_mas_receipt.status <> 'CANCELLED' group by invoiceno) as inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid where left(inv_invoicenumbers.createddate,10) <= '".$fromdate."' and DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) >= '".$aged."' and (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) > 0 and  inv_invoicenumbers.dealerid = '".$dealerid."' and inv_invoicenumbers.status <> 'CANCELLED'  group by inv_invoicenumbers.slno with rollup   limit ".($fetchresultcount).",1;" ;
				$fetchres =runmysqlquery($invoiceresult1);
				if(mysqli_num_rows($fetchres) != '' )
				{
					$invoiceresult = "SELECT inv_invoicenumbers.slno ,sum(inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) as outstandingamount from inv_invoicenumbers  
left join (select sum(receiptamount) as receiptamount, invoiceno,`status` from inv_mas_receipt where inv_mas_receipt.status <> 'CANCELLED' group by invoiceno) as inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid where left(inv_invoicenumbers.createddate,10) <= '".$fromdate."' and DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) >= '".$aged."' and (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) > 0 and  inv_invoicenumbers.dealerid = '".$dealerid."' and inv_invoicenumbers.status <> 'CANCELLED'  group by inv_invoicenumbers.slno with rollup   limit ".($fetchresultcount).",1;" ;
					$fetchres =runmysqlquery($invoiceresult);
					while($fetch55 = mysqli_fetch_array($fetchres))
					{
						$totalamount += $fetch55['outstandingamount'];
					}
				}
		}*/
		
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
		$query = "SELECT distinct inv_invoicenumbers.slno,left(inv_invoicenumbers.createddate,10) as createddate,inv_invoicenumbers.invoiceno,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_dealer.businessname as dealername,inv_invoicenumbers.netamount,ifnull(inv_mas_receipt.receiptamount,0) as receiptamount, (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) as outstandingamount , DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) as age,inv_invoicenumbers.status from inv_invoicenumbers  left join (select sum(receiptamount) as receiptamount, invoiceno,`status` from inv_mas_receipt where inv_mas_receipt.status <> 'CANCELLED' group by invoiceno) as inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid where left(inv_invoicenumbers.createddate,10) <= '".$fromdate."' and DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) >= '".$aged."' and (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) > 0 ".$dealerpiece." and inv_invoicenumbers.status <> 'CANCELLED'  ORDER BY ".$sortby." ".$sortby1.", inv_invoicenumbers.createddate desc LIMIT ".$startlimit.",".$limit." ; ";
		$result = runmysqlquery($query);
		if($startlimit == 0)
		{
		$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
		$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Status</td><td nowrap = "nowrap" class="td-border-grid" align="left">Customer ID</td><td nowrap = "nowrap" class="td-border-grid" align="left">Customer Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Sales Person</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Received Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Outstanding Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Age (Days)</td></tr>';
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
			if($fetch['outstandingamount'] < 0)
				$outstandingamount = '0';
			else
				$outstandingamount = $fetch['outstandingamount'];
			if($fetch['age'] < 0)
				$age = '0';
			else
				$age = $fetch['age'];
			$grid .= '<tr bgcolor='.$color.' class="gridrow1" align="left">';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat(trim($fetch['createddate']))."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['invoiceno']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['status']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['customerid'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['businessname'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['dealername']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['netamount'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['receiptamount'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($outstandingamount)."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($age)."</td>";
			$grid .= "</tr>";
		}
		$grid .= "</table>";
		$fetchcount = mysqli_num_rows($result);
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','129','".date('Y-m-d').' '.date('H:i:s')."','view_outstandingregister')";
		$eventresult = runmysqlquery($eventquery);
		if($slnocount >= $fetchresultcount)
			
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmoresearchfilter(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmoresearchfilter(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';

			//echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid.'^'.$totalinvoices.'^'.formatnumber($totalamount).'^'.convert_number($totalamount);	
			
			$responsearray1 = array();
			$responsearray1['errorcode'] = "1";
			$responsearray1['grid'] = $grid;
			$responsearray1['fetchresultcount'] = $fetchresultcount;
			$responsearray1['linkgrid'] = $linkgrid;
			$responsearray1['totalinvoices'] = $totalinvoices;
			$responsearray1['totalamount'] = formatnumber($totalamount);
			$responsearray1['totalamount1'] = convert_number($totalamount);	
			echo(json_encode($responsearray1));
	}
	break;
	
	
	
}




?>