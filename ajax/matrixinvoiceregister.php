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

$todaysdatepiece = "left(invoicedetailstodaydealer.invoicedate,10) = curdate()";

// Get all invoices of today into a temporary table.  
		
$querydrop = "Drop table if exists invoicedetailstodaydealer;";
$result = runmysqlquery($querydrop);

$query2 = "select * from inv_matrixinvoicenumbers where left(inv_matrixinvoicenumbers.createddate,10) = curdate()  and `status` <> 'CANCELLED'";
$result2 = runmysqlquery($query2);
$count = 0;
$totalamount1 = 0;
	
	$query = "CREATE TEMPORARY TABLE `invoicedetailstodaydealer` (                                       
				`slno` int(10) NOT NULL auto_increment,                             
				`invoiceno` int(10) default NULL,                                   
				`productcode` varchar(10) collate latin1_general_ci default NULL,   
				`usagetype` varchar(10) collate latin1_general_ci default NULL,     
				`amount` varchar(25) collate latin1_general_ci default NULL,        
				`purchasetype` varchar(25) collate latin1_general_ci default NULL,
				`dealerid` varchar(25) collate latin1_general_ci default NULL, 
				`invoicedate` datetime default '0000-00-00 00:00:00',
				`productgroup` varchar(25) collate latin1_general_ci default NULL, 
				`regionid` varchar(25) collate latin1_general_ci default NULL,   
				`branch` varchar(25) collate latin1_general_ci default NULL,  
				`branchname` varchar(25) collate latin1_general_ci default NULL,  
				`category` varchar(25) collate latin1_general_ci default NULL,   
				PRIMARY KEY  (`slno`)                                               
			);";
	$result = runmysqlquery($query);
		
	$query0 = "select * from inv_matrixinvoicenumbers where left(inv_matrixinvoicenumbers.createddate,10) = curdate()  and `status` <> 'CANCELLED' and products <> '' and (inv_matrixinvoicenumbers.dealerid= '".$userid."')";
	$result0 = runmysqlquery($query0);
		
	//echo($query0.'^'.$query1);exit;	
		
		
	// Insert Products
	while($fetch2 = mysqli_fetch_array($result0))
	{
		$count++;
		$products = explode('#',$fetch2['products']);
		$description = explode('*',$fetch2['description']);

		$productquantity = explode(',',$fetch2['productquantity']);
		$k=0;
		for($i = 0 ; $i < count($description);$i++)
		{
			for($j = 0 ; $j < $productquantity[$i];$j++)
			{
			  $totalamount = 0;
			  $splitdescription = explode('$',$description[$k]);
			  $productcode = $products[$i];
			  $amount = $splitdescription[4];
			  $purchasetype = $splitdescription[2];   
			  $totalamount1 = $amount ;
			    
			  // Fetch Product 	
			  $query1 = "select inv_mas_matrixproduct.group as productgroup from inv_mas_matrixproduct where id = '".$productcode."' ";
			  $result1 = runmysqlqueryfetch($query1);
			  
			  // Insert into invoice details table
			  $query3 = "insert into invoicedetailstodaydealer(invoiceno,productcode,usagetype,amount,purchasetype,dealerid,invoicedate,productgroup,regionid,branch,branchname,category) values('".$fetch2['slno']."','".$productcode."','".$usagetype."','".$totalamount1."','".$purchasetype."','".$fetch2['dealerid']."','".$fetch2['createddate']."','".$result1['productgroup']."','".$fetch2['regionid']."','".$fetch2['branchid']."','".$fetch2['branch']."','".$fetch2['category']."')";
			  $result3 =  runmysqlquery($query3);
			  $k++;	
			}
		}
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
		$fromdate = changedateformat($_POST['fromdate']);
		$todate = changedateformat($_POST['todate']);
		$todaynewtotal = 0 ;
		$todayupdationtotal = 0;
		$softwaretotal = 0;
		$softwareupdation = 0;
		$softwarenew = 0;
		$hardwaretotal = 0; 
		$hardwarenew = 0; 
		$hardwareupdation = 0;
		$overalltotal = 0;	 

		$query = "select inv_mas_dealer.relyonexecutive,inv_mas_dealer.branch, inv_mas_dealer.district, inv_mas_district.statecode, inv_mas_dealer.region, inv_mas_dealer.branchhead as branchhead from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
		where inv_mas_dealer.slno = '".$userid."';";
		$fetch = runmysqlqueryfetch($query);
		$branchhead = $fetch['branchhead'];
		$branch = $fetch['branch'];
		if($branchhead == 'yes')
		{
			$query = "SELECT distinct inv_mas_dealer.slno, inv_mas_dealer.businessname,inv_mas_dealer.branch
			FROM inv_matrixinvoicenumbers left join inv_mas_dealer on  inv_mas_dealer.slno = inv_matrixinvoicenumbers.dealerid 
			where  inv_mas_dealer.dealernotinuse = 'no' and inv_mas_dealer.branch = '".$branch."' order by businessname;";
			$result1 = runmysqlquery($query);
			while($fetch = mysqli_fetch_array($result1))
			{
				$dealerlistdisplay.= '\''.$fetch['slno'].'\''.',';
			}
			$dealerpiece = ("inv_matrixinvoicenumbers.dealerid IN (".trim($dealerlistdisplay,',').") ");
		}
		else
		{
			$dealerpiece = ("inv_matrixinvoicenumbers.dealerid = '".$userid."' ");
		}
		
		$resultcount = "select count(distinct inv_matrixinvoicenumbers.slno) as count from inv_matrixinvoicenumbers
		where ".$dealerpiece." and left(inv_matrixinvoicenumbers.createddate,10) = '".date('Y-m-d')."' AND inv_matrixinvoicenumbers.status <> 'CANCELLED'  order by inv_matrixinvoicenumbers.createddate desc;";
		$fetch10 = runmysqlqueryfetch($resultcount);
		$fetchresultcount = $fetch10['count'];
		
		//echo($resultcount);exit;
		
		
		$invoiceresult = "select distinct inv_matrixinvoicenumbers.slno,sum(inv_matrixinvoicenumbers.amount) as amount,sum(IFNULL(inv_matrixinvoicenumbers.igst,0)+IFNULL(inv_matrixinvoicenumbers.sgst,0)+IFNULL(inv_matrixinvoicenumbers.cgst,0)) as servicetax,sum(inv_matrixinvoicenumbers.netamount) as netamount
		from inv_matrixinvoicenumbers where ".$dealerpiece."  and left(inv_matrixinvoicenumbers.createddate,10) = '".date('Y-m-d')."' AND inv_matrixinvoicenumbers.status <> 'CANCELLED' group by inv_matrixinvoicenumbers.slno with rollup limit ".($fetchresultcount).",1 ";
		$fetchresult1 =runmysqlquery($invoiceresult);
		
		// Insert Data to Temporary tables   
		
		
		if(mysqli_num_rows($fetchresult1) > 0)
		{
			$fetchresult = runmysqlqueryfetch($invoiceresult);
			$totalinvoices = $fetchresultcount;
			$totalsalevalue = $fetchresult['amount'];
			$totaltax = $fetchresult['servicetax'];
			$totalamount = $fetchresult['netamount'];
		}
		else
		{
			$totalinvoices = '0';
			$totalsalevalue = '0';
			$totaltax = '0';
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
		$query = "select distinct inv_matrixinvoicenumbers.slno,left(inv_matrixinvoicenumbers.createddate,10) as createddate,inv_matrixinvoicenumbers.invoiceno,inv_matrixinvoicenumbers.customerid,inv_matrixinvoicenumbers.businessname,inv_matrixinvoicenumbers.amount,(IFNULL(inv_matrixinvoicenumbers.igst,0)+IFNULL(inv_matrixinvoicenumbers.sgst,0)+IFNULL(inv_matrixinvoicenumbers.cgst,0)) as servicetax,inv_matrixinvoicenumbers.netamount,inv_matrixinvoicenumbers.dealername,inv_matrixinvoicenumbers.createdby,inv_matrixinvoicenumbers.status, inv_matrixinvoicenumbers.products as products from inv_matrixinvoicenumbers
		where ".$dealerpiece."  and left(inv_matrixinvoicenumbers.createddate,10) = '".date('Y-m-d')."' AND inv_matrixinvoicenumbers.status <> 'CANCELLED' order by inv_matrixinvoicenumbers.createddate desc LIMIT ".$startlimit.",".$limit." ; ";
		$result = runmysqlquery($query);
		if($startlimit == 0)
		{
		$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
		$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Products</td><td nowrap = "nowrap" class="td-border-grid" align="left">Status</td><td nowrap = "nowrap" class="td-border-grid" align="left">Customer ID</td><td nowrap = "nowrap" class="td-border-grid" align="left">Customer Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Sale Value</td><td nowrap = "nowrap" class="td-border-grid" align="left">Tax Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Sales Person</td><td nowrap = "nowrap" class="td-border-grid" align="left">Prepared By</td><td nowrap = "nowrap" class="td-border-grid" align="left">Action</td></tr>';
		}
		$i_n = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$i_n++;
			$slnocount++;
			$color;
			$quantity = 0;
			$productquantity = explode(',',$fetch['productquantity']);
			$productcount = count($productquantity);
			for($i=0; $i< $productcount; $i++)
			{
				$quantity += $productquantity[$i];
			}
			
			
			$productsplit = explode('#',$fetch['products']);
			$productsplitcount = count($productsplit);
			
			
			$totalcount = $productsplitcount;
			if($i_n%2 == 0)
			
				$color = "#edf4ff";
			else
				$color = "#f7faff";
				
			$grid .= '<tr bgcolor='.$color.' class="gridrow1" align="left">';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat(trim($fetch['createddate']))."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['invoiceno']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='center'><a onclick='displayproductdetails(\"".$fetch['slno']."\");' class='resendtext' style = 'cursor:pointer'>".$totalcount."</a></td>";
			
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['status']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['customerid'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['businessname'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['amount']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['servicetax'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['netamount'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['dealername'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['createdby'])."</td>";
			$grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"> <a onclick="viewinvoice(\''.$fetch['slno'].'\');" class="resendtext" style = "cursor:pointer"> View >></a> </td>';
			$grid .= "</tr>";
		}
		$grid .= "</table>";
		$fetchcount = mysqli_num_rows($result);
		
		$productwisegrid  = '<table width="95%" cellspacing="0" border = "0" cellpadding="2" align = "left" class="table-border-grid" style="font-size:12px;" >';
		
		$productwisegrid .= '<tr class="tr-grid-header">';
		$productwisegrid .= '<td width = "22%" class="td-border-grid" nowrap="nowrap" ><div align="center" ><strong>Product</strong></div></td>';
		$productwisegrid .= '<td width = "26%" class="td-border-grid" nowrap="nowrap" style = "font-size:12px;"><div align="center" ><strong>New</strong></div></td>';
		$productwisegrid .= '<td width = "26%" class="td-border-grid" nowrap="nowrap" style = "font-size:12px;"><div align="center"><strong>Updation</strong></div></td>';
		$productwisegrid .= '<td width = "26%" class="td-border-grid" nowrap="nowrap" style = "font-size:12px;"><div align="center"><strong>Total</strong></div></td>';
		$productwisegrid .= '</tr>';
		
		
		// New Purchases of dealer based on product group and purchase type
		
		$querynewpurchase = "select ifnull(sum(amount),'0') as amount,productgroup from invoicedetailstodaydealer where purchasetype = 'New' group by productgroup;";
		$resultnewpurchase = runmysqlquery($querynewpurchase);
		while($fetchnewpurchase = mysqli_fetch_array($resultnewpurchase))
		{
			if($fetchnewpurchase['productgroup'] == 'Software')
				$softwarenewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'Hardware')
				$hardwarenewpurchase = $fetchnewpurchase['amount'];
		}
		
		// Updations of dealer based on product group and purchase type
		
		$queryupdationpurchase = "select ifnull(sum(amount),'0') as amount,productgroup from invoicedetailstodaydealer where purchasetype = 'Updation'  group by productgroup;";
		$resultupdationpurchase = runmysqlquery($queryupdationpurchase);
		while($fetchupdationpurchase = mysqli_fetch_array($resultupdationpurchase))
		{
			if($fetchupdationpurchase['productgroup'] == 'Software')
				$softwareupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'Hardware')
				$hardwareupdationpurchase = $fetchupdationpurchase['amount'];
				
		}
		$softwaretotal = $softwarenewpurchase + $softwareupdationpurchase;
		$hardwaretotal = $hardwarenewpurchase + $hardwareupdationpurchase;
		
		
		$overalltotal = $softwaretotal + $hardwaretotal ;
		
		$productwisegrid .= '<tr bgcolor="#F7FAFF">';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#F7FAFF">Software</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($softwarenewpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($softwareupdationpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($softwaretotal).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr  bgcolor="#edf4ff">';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="left">Hardware</td>';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" >'.formatnumber($hardwarenewpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" >'.formatnumber($hardwareupdationpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($hardwaretotal).'</td>';
		$productwisegrid .= '</tr>';
			

		// Calculate totals
		$todaynewtotal = $softwarenewpurchase + $hardwarenewpurchase ;
		$todayupdationtotal = $softwareupdationpurchase + $hardwareupdationpurchase;
		$overalltotal = $softwaretotal + $hardwaretotal ;
	
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#edf4ff"><strong>Total</strong></td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff"><strong>'.formatnumber($todaynewtotal).'</strong></td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff"><strong>'.formatnumber($todayupdationtotal).'</strong></td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff"><strong>'.formatnumber($overalltotal).'</strong></td>';
		$productwisegrid .= '</tr>';
		$productwisegrid .= '</table>';
		
		//echo($servicegrid);exit;
		if($slnocount >= $fetchresultcount)
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmoreinvoicedetails(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmoreinvoicedetails(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
		
		//echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid.'^'.$totalinvoices.'^'.formatnumber($totalsalevalue).'^'.formatnumber($totaltax).'^'.formatnumber($totalamount).'^'.convert_number($totalsalevalue).'^'.convert_number($totaltax).'^'.convert_number($totalamount).'^'.$productwisegrid.'^'.$servicegrid;	
		
		$responsearray = array();
		$responsearray['errorcode'] = "1";
		$responsearray['grid'] = $grid;
		$responsearray['fetchresultcount'] = $fetchresultcount;
		$responsearray['linkgrid'] = $linkgrid;
		$responsearray['totalinvoices'] = $totalinvoices;
		$responsearray['totalsalevalue'] = formatnumber($totalsalevalue);
		$responsearray['totaltax'] = number_format($totaltax,2);
		$responsearray['totalamount'] = formatnumber($totalamount);
		$responsearray['totalsalevalue1'] = convert_number($totalsalevalue);
		$responsearray['totaltax1'] = convert_number($totaltax);
		$responsearray['totalamount1'] = convert_number($totalamount);
		$responsearray['productwisegrid'] = $productwisegrid;
		echo(json_encode($responsearray));
	}
	
	break;
	case 'searchinvoices':
	{
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		$fromdate = changedateformat($_POST['fromdate']);
		$todate = changedateformat($_POST['todate']);
		$alltimecheck = $_POST['alltimecheck'];

		$todaynewtotal = 0 ;
		$todayupdationtotal = 0;
		$softwaretotal = 0;
		$softwareupdation = 0;
		$softwarenew = 0;
		$hardwaretotal = 0; 
		$hardwarenew = 0; 
		$hardwareupdation = 0;
		$overalltotal = 0;	

		$datepiece = ($alltimecheck == 'yes')?(""):(" AND (left(inv_matrixinvoicenumbers.createddate,10) between '".$fromdate."' and '".$todate."') ");	

		//if($_POST['dealerid'] == 'all')
		if($_POST['dealerid'] == ' ' &&  $_POST['leftdealerid'] == ' ')
		{
			$query = "select inv_mas_dealer.relyonexecutive,inv_mas_dealer.branch, inv_mas_dealer.district, inv_mas_district.statecode, inv_mas_dealer.region, inv_mas_dealer.branchhead as branchhead from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
			where inv_mas_dealer.slno = '".$userid."';";
			$fetch = runmysqlqueryfetch($query);
			$branchhead = $fetch['branchhead'];
			$branch = $fetch['branch'];
			if($branchhead == 'yes')
			{
				$query = "SELECT distinct inv_mas_dealer.slno, inv_mas_dealer.businessname,inv_mas_dealer.branch
				FROM inv_mas_dealer left join inv_matrixinvoicenumbers on  inv_mas_dealer.slno = inv_matrixinvoicenumbers.dealerid 
				where  inv_mas_dealer.dealernotinuse = 'no' and inv_mas_dealer.branch = '".$branch."' order by businessname;";
				$result1 = runmysqlquery($query);
				while($fetch = mysqli_fetch_array($result1))
				{
					$dealerlistdisplay.= '\''.$fetch['slno'].'\''.',';
				}
				$dealerpiece = (" and inv_matrixinvoicenumbers.dealerid IN (".trim($dealerlistdisplay,',').") ");
			}
		}
		else
		{
			//$dealerid = $_POST['dealerid'];
			$dealerid = ($_POST['dealerid'] == " ") ? $_POST['leftdealerid'] : $_POST['dealerid'];
			$dealerpiece = (" and inv_matrixinvoicenumbers.dealerid = '".$dealerid."' ");
			
		}
		//echo($dealerpiece);exit;
		
		//Calculation of Total Sale, Total Tax, Total Amount
		$resultcount = "select count(distinct inv_matrixinvoicenumbers.slno) as count from inv_matrixinvoicenumbers  
		where inv_matrixinvoicenumbers.slno <> '123456789' ".$datepiece.$dealerpiece." AND inv_matrixinvoicenumbers.status <> 'CANCELLED' order by inv_matrixinvoicenumbers.createddate desc;";
		$fetch10 = runmysqlqueryfetch($resultcount);
		$fetchresultcount = $fetch10['count'];
		$totalinvoices = $fetchresultcount;
		
		$query11 = "select sum(inv_matrixinvoicenumbers.amount) as amount,
		sum(IFNULL(inv_matrixinvoicenumbers.igst,0)+IFNULL(inv_matrixinvoicenumbers.sgst,0)+IFNULL(inv_matrixinvoicenumbers.cgst,0)) as servicetax,
		sum(inv_matrixinvoicenumbers.netamount) as netamount from inv_matrixinvoicenumbers
		where inv_matrixinvoicenumbers.slno <> '123456789' ".$datepiece.$dealerpiece." AND inv_matrixinvoicenumbers.status <> 'CANCELLED' order by inv_matrixinvoicenumbers.createddate desc";
		$fetchresult = runmysqlqueryfetch($query11);
		$totalsalevalue = $fetchresult['amount'];
		$totaltax = $fetchresult['servicetax'];
		$totalamountfinal = $fetchresult['netamount'];
		
		// Create Temporary table to store the data
		
			// Create Temporary tables 
		
		$querydrop = "Drop table if exists invoicedetailssearchdealer;";
		$result = runmysqlquery($querydrop);

		$queryproducts = "CREATE TEMPORARY TABLE `invoicedetailssearchdealer` (                                       
				  `slno` int(10) NOT NULL auto_increment,                             
				  `invoiceno` int(10) default NULL,                                   
				  `productcode` varchar(10) collate latin1_general_ci default NULL,   
				  `amount` varchar(25) collate latin1_general_ci default NULL,        
				  `purchasetype` varchar(25) collate latin1_general_ci default NULL,
				  `dealerid` varchar(25) collate latin1_general_ci default NULL, 
				  `invoicedate` datetime default '0000-00-00 00:00:00',
				  `productgroup` varchar(25) collate latin1_general_ci default NULL, 
				  `regionid` varchar(25) collate latin1_general_ci default NULL,   
				  `branch` varchar(25) collate latin1_general_ci default NULL,  
				  `branchname` varchar(25) collate latin1_general_ci default NULL,  
				  `category` varchar(25) collate latin1_general_ci default NULL,
				   PRIMARY KEY  (`slno`)                                               
				);";
		$result2 = runmysqlquery($queryproducts);	
		
				
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
		$query = "select distinct inv_matrixinvoicenumbers.slno,left(inv_matrixinvoicenumbers.createddate,10) as createddate,inv_matrixinvoicenumbers.invoiceno,inv_matrixinvoicenumbers.customerid,inv_matrixinvoicenumbers.businessname,inv_matrixinvoicenumbers.amount,(IFNULL(inv_matrixinvoicenumbers.igst,0)+IFNULL(inv_matrixinvoicenumbers.sgst,0)+IFNULL(inv_matrixinvoicenumbers.cgst,0)) as servicetax,inv_matrixinvoicenumbers.netamount,inv_matrixinvoicenumbers.dealername,inv_matrixinvoicenumbers.createdby,inv_matrixinvoicenumbers.status,inv_matrixinvoicenumbers.products as products,inv_matrixinvoicenumbers.description,inv_matrixinvoicenumbers.branch,inv_matrixinvoicenumbers.branchid,inv_matrixinvoicenumbers.category,inv_matrixinvoicenumbers.dealerid,inv_matrixinvoicenumbers.regionid,inv_matrixinvoicenumbers.productquantity from inv_matrixinvoicenumbers
		where inv_matrixinvoicenumbers.slno <> '123456789' ".$datepiece.$dealerpiece." AND inv_matrixinvoicenumbers.status <> 'CANCELLED' order by inv_matrixinvoicenumbers.createddate desc  ";
		$result30 = runmysqlquery($query); //echo($query); exit;

		while($fetch0 = mysqli_fetch_array($result30))
		{
			// Now insert selected invoice details to temporary table condidering all details of the each invoice
			if($fetch0['products'] <> '') {
                $count++;
				$products = explode('#',$fetch0['products']);
				$description = explode('*',$fetch0['description']);
				$productquantity = explode(',',$fetch0['productquantity']);
				$k=0;
				for($i = 0 ; $i < count($description);$i++)
				{
					for($j = 0 ; $j < $productquantity[$i];$j++)
					{		
						$amount = 0;
						$splitdescription = explode('$',$description[$k]);
						$productcode = $products[$i];
						$amount = $splitdescription[4];
						$purchasetype = $splitdescription[2]; 
					 

						// Fetch Product
						$query1 = "select inv_mas_matrixproduct.group as productgroup from inv_mas_matrixproduct where id = '".$productcode."' ";
						$result1 = runmysqlqueryfetch($query1);

						// Insert into invoice details table

						$query3 = "insert into invoicedetailssearchdealer(invoiceno,productcode,amount,purchasetype,dealerid,invoicedate,productgroup,regionid,branch,branchname,category) values('" . $fetch0['slno'] . "','" .$productcode. "','" . $amount . "','" . $purchasetype . "','" . $fetch0['dealerid'] . "','" . $fetch0['createddate'] . "','" . $result1['productgroup'] . "','" . $fetch0['regionid'] . "','" . $fetch0['branchid'] . "','" . $fetch0['branch'] . "','" . $fetch0['category'] . "')";
						$result3 = runmysqlquery($query3);
						$k++;
                    }
                }
            }
		}
		
		$addlimit = " LIMIT ".$startlimit.",".$limit.";";
		$query2 = $query.$addlimit;
		$result2 = runmysqlquery($query2);
		$grid = '';
		
		
		//echo($query);exit;
		if($startlimit == 0)
		{
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
			$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Products</td><td nowrap = "nowrap" class="td-border-grid" align="left">Status</td><td nowrap = "nowrap" class="td-border-grid" align="left">Customer ID</td><td nowrap = "nowrap" class="td-border-grid" align="left">Customer Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Sale Value</td><td nowrap = "nowrap" class="td-border-grid" align="left">Tax Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Sales Person</td><td nowrap = "nowrap" class="td-border-grid" align="left">Prepared By</td><td nowrap = "nowrap" class="td-border-grid" align="left">Action</td></tr>';
		}
		$i_n = 0;
		while($fetch = mysqli_fetch_array($result2))
		{
			$i_n++;
			$slnocount++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$productsplit = explode('#',$fetch['products']);
			$productsplitcount = count($productsplit);
			
			
			$totalcount = $productsplitcount;

			$grid .= '<tr bgcolor='.$color.' class="gridrow1" align="left">';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat(trim($fetch['createddate']))."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['invoiceno']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='center'><a onclick='displayproductdetails(\"".$fetch['slno']."\");' class='resendtext' style = 'cursor:pointer'>".$totalcount."</a></td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['status']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['customerid'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['businessname'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['amount']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['servicetax'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['netamount'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['dealername'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['createdby'])."</td>";
			$grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"> <a onclick="viewinvoice(\''.$fetch['slno'].'\');" class="resendtext" style = "cursor:pointer"> View >></a> </td>';
			$grid .= "</tr>";
		}
		$grid .= "</table>";		
		
		$productwisegrid  = '<table width="95%" cellspacing="0" border = "0" cellpadding="2" align = "left" class="table-border-grid" style="font-size:12px;" >';
		
		$productwisegrid .= '<tr class="tr-grid-header">';
		$productwisegrid .= '<td width = "22%" class="td-border-grid" nowrap="nowrap" ><div align="center" ><strong>Product</strong></div></td>';
		$productwisegrid .= '<td width = "26%" class="td-border-grid" nowrap="nowrap" style = "font-size:12px;"><div align="center" ><strong>New</strong></div></td>';
		$productwisegrid .= '<td width = "26%" class="td-border-grid" nowrap="nowrap" style = "font-size:12px;"><div align="center"><strong>Updation</strong></div></td>';
		$productwisegrid .= '<td width = "26%" class="td-border-grid" nowrap="nowrap" style = "font-size:12px;"><div align="center"><strong>Total</strong></div></td>';
		$productwisegrid .= '</tr>';

		$querynewpurchase = "select ifnull(sum(amount),'0') as amount,productgroup from invoicedetailssearchdealer where purchasetype = 'New' group by productgroup;";
		$resultnewpurchase = runmysqlquery($querynewpurchase);
		$softwarenewpurchase= 0;$hardwarenewpurchase = 0;
		while($fetchnewpurchase = mysqli_fetch_array($resultnewpurchase))
		{

			if($fetchnewpurchase['productgroup'] == 'Software')
				$softwarenewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'Hardware')
				$hardwarenewpurchase = $fetchnewpurchase['amount'];
		}
		
		//Fetch Group by Product Amount on Type UPDATION
		$queryupdationpurchase = "select ifnull(sum(amount),'0') as amount,productgroup from invoicedetailssearchdealer where purchasetype = 'Updation'  group by productgroup;";
		$resultupdationpurchase = runmysqlquery($queryupdationpurchase);
		$softwareupdationpurchase= 0;$hardwareupdationpurchase = 0;
		while($fetchupdationpurchase = mysqli_fetch_array($resultupdationpurchase))
		{

			if($fetchupdationpurchase['productgroup'] == 'Software')
				$softwareupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'Hardware')
				$hardwareupdationpurchase = $fetchupdationpurchase['amount'];
		}
			$softwaretotal = $softwarenewpurchase + $softwareupdationpurchase;
			$hardwaretotal = $hardwarenewpurchase + $hardwareupdationpurchase;

			$productwisegrid .= '<tr bgcolor="#F7FAFF">';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#F7FAFF">Software</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($softwarenewpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($softwareupdationpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($softwaretotal).'</td>';
			$productwisegrid .= '</tr>';
			
			$productwisegrid .= '<tr  bgcolor="#edf4ff">';
			$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="left">Hardware</td>';
			$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" >'.formatnumber($hardwarenewpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" >'.formatnumber($hardwareupdationpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($hardwaretotal).'</td>';
			$productwisegrid .= '</tr>';
			

		// Calculate totals
		$todaynewtotal = $softwarenewpurchase + $hardwarenewpurchase ;
		$todayupdationtotal = $softwareupdationpurchase + $hardwareupdationpurchase;
		$overalltotal = $softwaretotal + $hardwaretotal;
				
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#F7FAFF"><strong>Total</strong></td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF"><strong>'.formatnumber($todaynewtotal).'</strong></td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF"><strong>'.formatnumber($todayupdationtotal).'</strong></td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF"><strong>'.formatnumber($overalltotal).'</strong></td>';
		$productwisegrid .= '</tr>';
		$productwisegrid .= '</table>';
		
		$fetchcount = mysqli_num_rows($result30);
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','281','".date('Y-m-d').' '.date('H:i:s')."','view_matrixinvoiceregister')";
		$eventresult = runmysqlquery($eventquery);
		if($slnocount >= $fetchresultcount)
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmoresearchfilter(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmoresearchfilter(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
		
		//echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid.'^'.$totalinvoices.'^'.formatnumber($totalsalevalue).'^'.formatnumber($totaltax).'^'.formatnumber($totalamountfinal).'^'.convert_number($totalsalevalue).'^'.convert_number($totaltax).'^'.convert_number($totalamountfinal).'^'.$productwisegrid.'^'.$servicegrid;
		
		$responsearray1 = array();
		$responsearray1['errorcode'] = "1";
		$responsearray1['grid'] = $grid;
		$responsearray1['fetchresultcount'] = $fetchresultcount;
		$responsearray1['linkgrid'] = $linkgrid;
		$responsearray1['totalinvoices'] = $totalinvoices;
		$responsearray1['totalsalevalue'] = formatnumber($totalsalevalue);
		$responsearray1['totaltax'] = formatnumber($totaltax);
		$responsearray1['totalamountfinal'] = formatnumber($totalamountfinal);
		$responsearray1['totalsalevalue1'] = convert_number($totalsalevalue);
		$responsearray1['totaltax1'] = convert_number($totaltax);
		$responsearray1['totalamountfinal1'] = convert_number($totalamountfinal);
		$responsearray1['productwisegrid'] = $productwisegrid;
		echo(json_encode($responsearray1));			
	}
	break;
	
	case 'productdetailsgrid':
	{
		$productslno = $_POST['productslno'];
		$query = "select * from inv_matrixinvoicenumbers where slno = '".$productslno."' ;";
		$result = runmysqlquery($query);
		if($startlimit == 0)
		{
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid" style="font-size:12px">';
			$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Purchase Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Pin Serial Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Amount</td></tr>';
		}
		
		$i_n = 0;$slnocount = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$i_n++;
			$slno++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$description = $fetch['description'];
			$descriptionsplit = explode('*',$description);
			$descriptionsplitcount = count($descriptionsplit);
			
			$fetchresultantcount = $descriptionsplitcount;;
			
			//$fetchresultantcount = count($descriptionsplit); 
			//echo($fetchresultantcount);exit;
			if($description != '')
			{
				for($i=0;$i<count($descriptionsplit);$i++)
				{
					$descriptionline = explode('$',$descriptionsplit[$i]);
					$grid .= '<tr class="gridrow1" bgcolor='.$color.'>';
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$descriptionline[0]."</td> ";
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$descriptionline[1]."</td>";
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$descriptionline[2]."</td>";
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$descriptionline[3]."</td>";
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$descriptionline[4]."</td>";
					$grid .= "</tr>";
				}
			}
			
		}
		$grid .= "</table>";

		$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		
		//echo($fetchresultantcount);exit;
		$responsearray2 = array();
		$responsearray2['errorcode'] = "1";
		$responsearray2['grid'] = $grid;
		$responsearray2['fetchresultcount'] = $fetchresultantcount;
		$responsearray2['linkgrid'] = $linkgrid;
		echo(json_encode($responsearray2));			
		//echo '1^'.$grid.'^'.$fetchresultantcount.'^'.$linkgrid;
	}
	break;
	
}



?>