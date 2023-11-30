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
$servicedatetoday = "left(servicestodaydealer.createddate,10) = curdate()";

// Get all invoices of today into a temporary table.  
		
$querydrop = "Drop table if exists invoicedetailstodaydealer;";
$result = runmysqlquery($querydrop);

$querydrop1 = "Drop table if exists servicestodaydealer;";
$result1 = runmysqlquery($querydrop1);
	
	
	// Create Temporary table to insert 'ITEM SOFTWARE' details
	$queryservices = "CREATE TEMPORARY TABLE `servicestodaydealer` ( 
		`slno` int(10) NOT NULL auto_increment, 
		 `invoiceno` int(10) default NULL,      
		 `servicename` varchar(100) collate latin1_general_ci default NULL, 
		 `serviceamount` varchar(10) collate latin1_general_ci default NULL, 
		 `createddate` datetime default '0000-00-00 00:00:00',
		`dealerid` varchar(25) collate latin1_general_ci default NULL, 
		`regionid` varchar(25) collate latin1_general_ci default NULL,   
		`branch` varchar(25) collate latin1_general_ci default NULL,  
		`branchname` varchar(25) collate latin1_general_ci default NULL, 
		`category` varchar(25) collate latin1_general_ci default NULL,   
		 PRIMARY KEY  (`slno`)    
	 );";
	$result = runmysqlquery($queryservices);

	$query2 = "select * from inv_invoicenumbers where left(inv_invoicenumbers.createddate,10) = curdate()  and `status` <> 'CANCELLED'";
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
	
	// Insert data to invoicedetails table
	$query0 = "select * from inv_invoicenumbers where left(inv_invoicenumbers.createddate,10) = curdate() and products <> '' and `status` <> 'CANCELLED' and (inv_invoicenumbers.dealerid= '".$userid."'  OR (inv_invoicenumbers.createdbyid = '".$userid."' AND inv_invoicenumbers.module = 'dealer_module'))";
	$result0 = runmysqlquery($query0);
	
	// Insert Services
	while($fetch2 = mysqli_fetch_array($result0))
	{
		$serviceamount = 0;
		if($fetch2['servicedescription'] <> '')
		{
			$serviceamountsplit = explode('*',$fetch2['servicedescription']);
			for($k = 0 ;$k < count($serviceamountsplit);$k++)
			{
				$finalsplit = explode('$',$serviceamountsplit[$k]); //echo($offerdescriptionsplit[$j]);exit;
				$serviceamount = $serviceamount + $finalsplit[2];
				// Insert into services table 
				$insertservices = "INSERT INTO servicestodaydealer(invoiceno,servicename,serviceamount,createddate,dealerid,regionid,branch,branchname,category) values('".$fetch2['slno']."','". $finalsplit[1]."','". $finalsplit[2]."','".$fetch2['createddate']."','".$fetch2['dealerid']."','".$fetch2['regionid']."','".$fetch2['branchid']."','".$fetch2['branch']."','".$fetch2['category']."')";
				$result = runmysqlquery($insertservices);
			}
		}
	}
		
	$query1 = "select * from inv_invoicenumbers where left(inv_invoicenumbers.createddate,10) = curdate()  and `status` <> 'CANCELLED' and products <> '' and (inv_invoicenumbers.dealerid= '".$userid."'  OR (inv_invoicenumbers.createdbyid = '".$userid."' AND inv_invoicenumbers.module = 'dealer_module'))";
	$result22 = runmysqlquery($query1);
		
	//echo($query0.'^'.$query1);exit;	
		
		
		// Insert Products
		while($fetch2 = mysqli_fetch_array($result22))
		{
			$count++;
			$totalamount = 0;
			$products = explode('#',$fetch2['products']);
			for($i = 0 ; $i < count($products);$i++)
			{
				$totalamount = 0;
				$description = explode('*',$fetch2['description']);
				$splitdescription = explode('$',$description[$i]);
				
				$productcode = $products[$i];
				$usagetype = $splitdescription[3];
				$amount = $splitdescription[6];
				$purchasetype = $splitdescription[2];   //echo($usagetype.'^'.$amount.'^'.$purchasetype); exit;
				
				if($i == 0)
				{
					$totalamount1 = $amount ;
				}
				else 
				{
					$totalamount1 = $amount;
				}
						
				// Fetch Product 	
				
				$query1 = "select inv_mas_product.group as productgroup from inv_mas_product where productcode = '".$productcode."' ";
						
				$result1 = runmysqlqueryfetch($query1);
				
				// Insert into invoice details table
				
				$query3 = "insert into invoicedetailstodaydealer(invoiceno,productcode,usagetype,amount,purchasetype,dealerid,invoicedate,productgroup,regionid,branch,branchname,category) values('".$fetch2['slno']."','".$productcode."','".$usagetype."','".$totalamount1."','".$purchasetype."','".$fetch2['dealerid']."','".$fetch2['createddate']."','".$result1['productgroup']."','".$fetch2['regionid']."','".$fetch2['branchid']."','".$fetch2['branch']."','".$fetch2['category']."')";
				$result3 =  runmysqlquery($query3);
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
		$tdstotal = 0;
		$tdsnew = 0; 
		$tdsupdation = 0;
		$spptotal = 0; 
		$sppnew = 0; 
		$sppupdation = 0;
		$stototal = 0;
		$stonew = 0 ; 
		$stoupdation = 0;
		$svhtotal = 0;
		$svhnew = 0;
		$svhupdation = 0;
		$svitotal = 0; 
		$svinew = 0 ; 
		$sviupdation = 0;
		$sactotal = 0;
		$sacnew = 0;
		$sacupdation = 0; 
		$otherstotal = 0;
		$othersnew = 0 ; 
		$othersupdation = 0;
		$overalltotal = 0;	
		$servicestotaltoday = 0; 
		$xbrltotal = 0;
		$xbrlnew = 0;
		$xbrlupdation = 0;
		$gsttotal = 0;
		$gstnew = 0;
		$gstupdation = 0; 
		
		
		$resultcount = "select count(distinct inv_invoicenumbers.slno) as count from inv_invoicenumbers
left join dealer_online_purchase on dealer_online_purchase.onlineinvoiceno = inv_invoicenumbers.slno
where  (inv_invoicenumbers.dealerid= '".$userid."'  OR (inv_invoicenumbers.createdbyid = '".$userid."' AND inv_invoicenumbers.module = 'dealer_module')) and left(inv_invoicenumbers.createddate,10) = '".date('Y-m-d')."' AND inv_invoicenumbers.status <> 'CANCELLED'  order by inv_invoicenumbers.createddate desc;";
		$fetch10 = runmysqlqueryfetch($resultcount);
		$fetchresultcount = $fetch10['count'];
		
		//echo($resultcount);exit;
		
		
		$invoiceresult = "select distinct inv_invoicenumbers.slno,sum(inv_invoicenumbers.amount) as amount,sum(inv_invoicenumbers.servicetax+IFNULL(inv_invoicenumbers.sbtax,0)+IFNULL(inv_invoicenumbers.kktax,0)+IFNULL(inv_invoicenumbers.igst,0)+IFNULL(inv_invoicenumbers.sgst,0)+IFNULL(inv_invoicenumbers.cgst,0)) as servicetax,sum(inv_invoicenumbers.netamount) as netamount
from inv_invoicenumbers left join dealer_online_purchase on dealer_online_purchase.onlineinvoiceno = inv_invoicenumbers.slno where   (inv_invoicenumbers.dealerid= '".$userid."' OR (inv_invoicenumbers.createdbyid = '".$userid."' AND inv_invoicenumbers.module = 'dealer_module'))  and left(inv_invoicenumbers.createddate,10) = '".date('Y-m-d')."' AND inv_invoicenumbers.status <> 'CANCELLED' group by inv_invoicenumbers.slno with rollup limit ".($fetchresultcount).",1 ";
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
		$query = "select distinct inv_invoicenumbers.slno,left(inv_invoicenumbers.createddate,10) as createddate,inv_invoicenumbers.invoiceno,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_invoicenumbers.amount,(inv_invoicenumbers.servicetax+IFNULL(inv_invoicenumbers.sbtax,0)+IFNULL(inv_invoicenumbers.kktax,0)+IFNULL(inv_invoicenumbers.igst,0)+IFNULL(inv_invoicenumbers.sgst,0)+IFNULL(inv_invoicenumbers.cgst,0)) as servicetax,inv_invoicenumbers.netamount,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,inv_invoicenumbers.status, inv_invoicenumbers.products as products, inv_invoicenumbers.servicedescription as servicedescription from inv_invoicenumbers
		left join dealer_online_purchase on dealer_online_purchase.onlineinvoiceno = inv_invoicenumbers.slno
		where  (inv_invoicenumbers.dealerid= '".$userid."' OR (inv_invoicenumbers.createdbyid = '".$userid."' AND inv_invoicenumbers.module = 'dealer_module'))  and left(inv_invoicenumbers.createddate,10) = '".date('Y-m-d')."' AND inv_invoicenumbers.status <> 'CANCELLED' order by inv_invoicenumbers.createddate desc LIMIT ".$startlimit.",".$limit." ; ";
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
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
				$productsplit = explode('#',$fetch['products']);
				$productsplitcount = count($productsplit);
				
				$servicedescriptionsplit = explode('*',$fetch['servicedescription']);
				$servicedescriptioncount = count($servicedescriptionsplit);
				
				if($fetch['products'] == '')
					$totalcount = $servicedescriptioncount;
				elseif(($fetch['products'] != '') && ($fetch['servicedescription'] != ''))
					$totalcount = $servicedescriptioncount + $productsplitcount;
				else
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
		$fetchcount = mysqli_num_rows($result);
		
		$productwisegrid  = '<table width="95%" cellspacing="0" border = "0" cellpadding="2" align = "left" class="table-border-grid" style="font-size:12px;" >';
		
		$productwisegrid .= '<tr class="tr-grid-header">';
		$productwisegrid .= '<td width = "22%" class="td-border-grid" nowrap="nowrap" ><div align="center" ><strong>Product</strong></div></td>';
		$productwisegrid .= '<td width = "26%" class="td-border-grid" nowrap="nowrap" style = "font-size:12px;"><div align="center" ><strong>New</strong></div></td>';
		$productwisegrid .= '<td width = "26%" class="td-border-grid" nowrap="nowrap" style = "font-size:12px;"><div align="center"><strong>Updation</strong></div></td>';
		$productwisegrid .= '<td width = "26%" class="td-border-grid" nowrap="nowrap" style = "font-size:12px;"><div align="center"><strong>Total</strong></div></td>';
		$productwisegrid .= '</tr>';
		
		
		// New Purchases of dealer based on product group and purchase type
		
		$query200 = "select ifnull(sum(amount),'0') as amount from invoicedetailstodaydealer where purchasetype = 'New' and productgroup = 'TDS' and  ".$todaysdatepiece."" ;
		$result200 = runmysqlqueryfetch($query200);
		$tdsnew =  $tdsnew + $result200['amount'];
		 
		$query201 = "select ifnull(sum(amount),'0') as amount from invoicedetailstodaydealer where  purchasetype = 'New' and productgroup = 'SPP' and  ".$todaysdatepiece."" ;
		$result201 = runmysqlqueryfetch($query201);
		$sppnew =  $sppnew + $result201['amount'];
		
		$query202= "select ifnull(sum(amount),'0') as amount from invoicedetailstodaydealer where  purchasetype = 'New' and productgroup = 'STO' and  ".$todaysdatepiece."" ;
		$result202 = runmysqlqueryfetch($query202);
		$stonew = $stonew + $result202['amount'];
		
		$query203 = "select ifnull(sum(amount),'0') as amount from invoicedetailstodaydealer where  purchasetype = 'New' and productgroup = 'SVH' and  ".$todaysdatepiece."" ;
		$result203 = runmysqlqueryfetch($query203);
		$svhnew = $svhnew + $result203['amount'];
		
		$query204 = "select ifnull(sum(amount),'0') as amount from invoicedetailstodaydealer where  purchasetype = 'New' and productgroup = 'SVI' and  ".$todaysdatepiece."" ;
		$result204 = runmysqlqueryfetch($query204);
		$svinew = $svinew + $result204['amount'];
		
		$query205 = "select ifnull(sum(amount),'0') as amount from invoicedetailstodaydealer where  purchasetype = 'New' and productgroup = 'SAC' and  ".$todaysdatepiece."" ;
		$result205 = runmysqlqueryfetch($query205);
		$sacnew =  $sacnew + $result205['amount'];
		
		$query206 = "select ifnull(sum(amount),'0') as amount from invoicedetailstodaydealer where  purchasetype = 'New' and productgroup = 'XBRL' and  ".$todaysdatepiece."" ;
		$result206 = runmysqlqueryfetch($query206);
		$xbrlnew =  $xbrlnew + $result206['amount'];
		
		$query300 = "select ifnull(sum(amount),'0') as amount from invoicedetailstodaydealer where  purchasetype = 'New' and productgroup = 'GST' and  ".$todaysdatepiece."" ;
		$result300 = runmysqlqueryfetch($query300);
		$gstnew =  $gstnew + $result206['amount'];
		
		$query212 = "select ifnull(sum(amount),'0') as amount from invoicedetailstodaydealer where  purchasetype = 'New' and productgroup = 'OTHERS' and  ".$todaysdatepiece."" ;
		$result212 = runmysqlqueryfetch($query212);
		$othersnew = $othersnew + $result212['amount'];
		
		// Updations of dealer based on product group and purchase type
		
		$query206 = "select ifnull(sum(amount),'0') as amount from invoicedetailstodaydealer where purchasetype = 'Updation' and productgroup = 'TDS' and  ".$todaysdatepiece."" ;
		$result206 = runmysqlqueryfetch($query206);
		$tdsupdation = $tdsupdation + $result206['amount'];
		
		$query207 = "select ifnull(sum(amount),'0') as amount from invoicedetailstodaydealer where  purchasetype = 'Updation' and productgroup = 'SPP' and  ".$todaysdatepiece."" ;
		$result207 = runmysqlqueryfetch($query207);
		$sppupdation = $sppupdation + $result207['amount'];
		
		$query208= "select ifnull(sum(amount),'0') as amount from invoicedetailstodaydealer where purchasetype = 'Updation' and productgroup = 'STO' and  ".$todaysdatepiece."" ;
		$result208 = runmysqlqueryfetch($query208);
		$stoupdation = $stoupdation + $result208['amount'];
		
		$query209 = "select ifnull(sum(amount),'0') as amount from invoicedetailstodaydealer where purchasetype = 'Updation' and productgroup = 'SVH' and  ".$todaysdatepiece."" ;
		$result209 = runmysqlqueryfetch($query209);
		$svhupdation =  $svhupdation + $result209['amount'];
		
		$query210 = "select ifnull(sum(amount),'0') as amount from invoicedetailstodaydealer where  purchasetype = 'Updation' and productgroup = 'SVI' and  ".$todaysdatepiece."" ;
		$result210 = runmysqlqueryfetch($query210);
		$sviupdation = $sviupdation + $result210['amount'];
		
		$query211 = "select ifnull(sum(amount),'0') as amount from invoicedetailstodaydealer where  purchasetype = 'Updation' and productgroup = 'SAC' and  ".$todaysdatepiece."" ;
		$result211 = runmysqlqueryfetch($query211);
		$sacupdation = $sacupdation + $result211['amount'];
		
		$query212 = "select ifnull(sum(amount),'0') as amount from invoicedetailstodaydealer where  purchasetype = 'Updation' and productgroup = 'XBRL' and  ".$todaysdatepiece."" ;
		$result212 = runmysqlqueryfetch($query212);
		$xbrlupdation = $xbrlupdation + $result212['amount'];
		
		$query301 = "select ifnull(sum(amount),'0') as amount from invoicedetailstodaydealer where  purchasetype = 'Updation' and productgroup = 'GST' and  ".$todaysdatepiece."" ;
		$result301 = runmysqlqueryfetch($query301);
		$gstupdation = $gstupdation + $result301['amount'];
		
		$query213 = "select ifnull(sum(amount),'0') as amount from invoicedetailstodaydealer where  purchasetype = 'Updation' and productgroup = 'OTHERS' and  ".$todaysdatepiece."" ;
		$result213 = runmysqlqueryfetch($query213);
		$othersupdation = $othersupdation + $result213['amount'];
		
		// Calculate totals
		$todaynewtotal = $tdsnew + $sppnew + $stonew + $svhnew + $svinew + $sacnew + $othersnew+ $xbrlnew + $gstnew;
		$todayupdationtotal = $tdsupdation + $sppupdation+ $stoupdation + $svhupdation + $sviupdation + $sacupdation + $othersupdation+ $xbrlupdation+ $gstupdation;
		
		$tdstotal = $tdsnew + $tdsupdation;
		$spptotal = $sppnew + $sppupdation;
		$stototal = $stonew + $stoupdation;
		$svhtotal = $svhnew + $svhupdation;
		$svitotal = $svinew + $sviupdation;
		$sactotal = $sacnew + $sacupdation;
		$xbrltotal = $xbrlnew + $xbrlupdation;
		$gsttotal = $gstnew + $gstupdation;
		$otherstotal = $othersnew + $othersupdation;
		
		
		$overalltotal = $tdstotal + $spptotal + $stototal + $svhtotal + $svitotal + $sactotal + $otherstotal + $xbrltotal+ $gsttotal;
		
		$productwisegrid .= '<tr bgcolor="#F7FAFF">';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#F7FAFF">TDS</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($tdsnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($tdsupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($tdstotal).'</td>';
		$productwisegrid .= '</tr>';
	
	
		$productwisegrid .= '<tr  bgcolor="#edf4ff">';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="left">SPP</td>';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" >'.formatnumber($sppnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" >'.formatnumber($sppupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($spptotal).'</td>';
		$productwisegrid .= '</tr>';
	
	
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#F7FAFF">STO</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($stonew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($stoupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($stototal).'</td>';
		$productwisegrid .= '</tr>';
	
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#edf4ff">SVH</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($svhnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($svhupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#edf4ff">'.formatnumber($svhtotal).'</td>';
		$productwisegrid .= '</tr>';
	
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="left" bgcolor="#F7FAFF">SVI</td>';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($svinew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($sviupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($svitotal).'</td>';
		$productwisegrid .= '</tr>';
	
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left" bgcolor="#edf4ff">SAC</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($sacnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($sacupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#edf4ff">'.formatnumber($sactotal).'</td>';
		$productwisegrid .= '</tr>';
		
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left" bgcolor="#edf4ff">XBRL</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($xbrlnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($xbrlupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#edf4ff">'.formatnumber($xbrltotal).'</td>';
		$productwisegrid .= '</tr>';
	
	    $productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left" bgcolor="#edf4ff">GST</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($gstnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($gstupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#edf4ff">'.formatnumber($gsttotal).'</td>';
		$productwisegrid .= '</tr>';
		
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left" bgcolor="#F7FAFF">OTHERS</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($othersnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($othersupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($otherstotal).'</td>';
		$productwisegrid .= '</tr>';
	
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#F7FAFF"><strong>Total</strong></td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF"><strong>'.formatnumber($todaynewtotal).'</strong></td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF"><strong>'.formatnumber($todayupdationtotal).'</strong></td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF"><strong>'.formatnumber($overalltotal).'</strong></td>';
		$productwisegrid .= '</tr>';
		$productwisegrid .= '</table>';
		
		// Prepare Services Summary 
		
		$servicegrid = '<table width="100%" cellspacing="0" border = "0" cellpadding="2" align = "left" class="table-border-grid" style="font-size:12px;">';
		
		//Write the header Row of the table
		$servicegrid .= '<tr class="tr-grid-header"><td nowrap="nowrap" class="td-border-grid" width = "60%" align="center" ><strong>Service Name</strong/></td><td nowrap="nowrap" class="td-border-grid" align="center" width = "40%"><strong>Total</strong></td></tr>';
		
		$servicesall = "select ifnull(services.netamount,'0') as amount,inv_mas_service.servicename from inv_mas_service left join (select ifnull(sum(serviceamount),'0') as netamount,servicename from servicestodaydealer where  ".$servicedatetoday."  group by servicename) as services on services.servicename = inv_mas_service.servicename order by inv_mas_service.servicename";
		$resultallservices = runmysqlquery($servicesall);
		$totalservices = 0;
		$i_n = 0;
		//echo(mysqli_num_rows($resultallservices));exit;
		while($fetchallservices = mysqli_fetch_array($resultallservices))
		{
			$i_n++;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$totalservices = $totalservices + $fetchallservices['amount'];
			$servicegrid .= '<tr bgcolor='.$color.'>';
			$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">'.$fetchallservices['servicename'].'</td>';
			$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($fetchallservices['amount']).'</td>';
			$servicegrid .= '</tr>';			
		}
		$servicegrid .= '<tr >';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left"><strong>Total</strong></td>';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right"><strong>'.formatnumber($totalservices).'</strong></td></tr>';
		$servicegrid .= '</table>';	
		
		
		/*$queryserviceamctoday = "select ifnull(sum(serviceamount),'0') as netamount from servicestodaydealer where  ".$servicedatetoday." and servicename like '%AMC Charges%'";
		$resultserviceamctoday = runmysqlqueryfetch($queryserviceamctoday);
		$amcttoday = $resultserviceamctoday['netamount'];
		
		$queryserviceattinttoday = "select ifnull(sum(serviceamount),'0') as netamount from servicestodaydealer where  ".$servicedatetoday." and servicename like '%Attendance Integration%'";
		$resultserviceattinttoday = runmysqlqueryfetch($queryserviceattinttoday);
		$attinttoday = $resultserviceattinttoday['netamount'];
		
		$queryservicecusttoday = "select ifnull(sum(serviceamount),'0') as netamount from servicestodaydealer where  ".$servicedatetoday." and servicename like '%Customization%'";
		$resultservicecusttoday = runmysqlqueryfetch($queryservicecusttoday);
		$custtoday = $resultservicecusttoday['netamount'];
		
		$queryserviceeiptoday = "select ifnull(sum(serviceamount),'0') as netamount from servicestodaydealer where  ".$servicedatetoday." and servicename like '%Employee Information Portal (EIP- SPP)%'";
		$resultserviceeiptoday = runmysqlqueryfetch($queryserviceeiptoday);
		$eiptoday = $resultserviceeiptoday['netamount'];
		
		$queryserviceimplementationtoday = "select ifnull(sum(serviceamount),'0') as netamount from servicestodaydealer where ".$servicedatetoday." and servicename like '%Implementation%'";
		$resultserviceimplementationtoday = runmysqlqueryfetch($queryserviceimplementationtoday);
		$implementationtoday = $resultserviceimplementationtoday['netamount'];
		
		$queryservicepptoday = "select ifnull(sum(serviceamount),'0') as netamount from servicestodaydealer where  ".$servicedatetoday." and servicename like '%Payroll Processing%'";
		$resultservicepptoday = runmysqlqueryfetch($queryservicepptoday);
		$pptoday = $resultservicepptoday['netamount'];
		
		$queryservicesmstoday = "select ifnull(sum(serviceamount),'0') as netamount from servicestodaydealer where  ".$servicedatetoday." and servicename like '%SMS Credits%'";
		$resultservicesmstoday = runmysqlqueryfetch($queryservicesmstoday);
		$smstoday = $resultservicesmstoday['netamount'];
		
		$queryservicesupporttoday = "select ifnull(sum(serviceamount),'0') as netamount from servicestodaydealer where  ".$servicedatetoday." and servicename like '%Support%'";
		$resultservicesupporttoday = runmysqlqueryfetch($queryservicesupporttoday);
		$supporttoday = $resultservicesupporttoday['netamount'];
		
		$queryservicetastoday = "select ifnull(sum(serviceamount),'0') as netamount from servicestodaydealer where  ".$servicedatetoday." and servicename like '%Time Attendance Solution (T&A-SPP)%'";
		$resultservicetastoday = runmysqlqueryfetch($queryservicetastoday);
		$tastoday = $resultservicetastoday['netamount'];
		
		$queryservicetrainingtoday = "select ifnull(sum(serviceamount),'0') as netamount from servicestodaydealer where  ".$servicedatetoday." and servicename like '%training%'";
		$resultservicetrainingtoday = runmysqlqueryfetch($queryservicetrainingtoday);
		$trainingtoday = $resultservicetrainingtoday['netamount'];
			
			// Add all services to get total 
			
		$servicestotaltoday = $amcttoday + $attinttoday + $custtoday + $eiptoday + $implementationtoday + $pptoday + $smstoday + 
			$supporttoday + $tastoday + $trainingtoday; 
		
		$servicegrid .= '<tr bgcolor="#F7FAFF">';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">AMC Charges</td>';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($amcttoday).'</td>';
		$servicegrid .= '</tr>';
		
		$servicegrid .= '<tr bgcolor="#edf4ff">';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">Attendance Integration</td>';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($attinttoday).'</td>';
		$servicegrid .= '</tr>';
		
		$servicegrid .= '<tr  bgcolor="#F7FAFF">';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">Customization</td>';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($custtoday).'</td>';
		$servicegrid .= '</tr>';
		
		$servicegrid .= '<tr bgcolor="#edf4ff">';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left">Employee Information Portal (EIP- SPP)</td>';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right">'.formatnumber($eiptoday).'</td>';
		$servicegrid .= '</tr>';
		
		$servicegrid .= '<tr bgcolor="#F7FAFF">';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">Implementation</td>';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($implementationtoday).'</td>';
		$servicegrid .= '</tr>';
		
		$servicegrid .= '<tr bgcolor="#edf4ff">';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">Payroll Processing</td>';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"   align="right">'.formatnumber($pptoday).'</td>';
		$servicegrid .= '</tr>';
		
		$servicegrid .= '<tr  bgcolor="#F7FAFF">';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">SMS Credits</td>';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($smstoday).'</td>';
		$servicegrid .= '</tr>';
		
		$servicegrid .= '<tr bgcolor="#edf4ff">';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">Support</td>';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($supporttoday).'</td>';
		$servicegrid .= '</tr>';
		
		$servicegrid .= '<tr  bgcolor="#F7FAFF">';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">Time Attendance Solution (T&A-SPP)</td>';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($tastoday).'</td>';
		$servicegrid .= '</tr>';
		
		$servicegrid .= '<tr bgcolor="#edf4ff">';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">Training</td>';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($trainingtoday).'</td>';
		$servicegrid .= '</tr>';
		
		$servicegrid .= '<tr bgcolor="#F7FAFF">';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left"><strong>Total</strong></td>';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right"><strong>'.formatnumber($servicestotaltoday).'</strong></td>';
		$servicegrid .= '</tr>';
		$servicegrid .= '</table>';*/
		
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
		$responsearray['servicegrid'] = $servicegrid;
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
		$totalinvoices = '0';
		$totalsalevalue = '0';
		$totaltax = '0';
		$totalamount = '0';
		$todaynewtotal = 0 ;
		$todayupdationtotal = 0;
		$tdstotal = 0;
		$tdsnew = 0; 
		$tdsupdation = 0;
		$spptotal = 0; 
		$sppnew = 0; 
		$sppupdation = 0;
		$stototal = 0;
		$stonew = 0 ; 
		$stoupdation = 0;
		$svhtotal = 0;
		$svhnew = 0;
		$svhupdation = 0;
		$svitotal = 0; 
		$svinew = 0 ; 
		$sviupdation = 0;
		$sactotal = 0;
		$sacnew = 0;
		$sacupdation = 0; 
		$otherstotal = 0;
		$othersnew = 0 ; 
		$othersupdation = 0;
		$overalltotal = 0;	
		$servicestotaltoday = 0; 
		$xbrltotal = 0;
		$xbrlnew = 0;
		$xbrlupdation = 0; 
		$gsttotal = 0;
		$gstnew = 0;
		$gstupdation = 0; 
		$amcttoday = 0;
		$attinttoday =0 ; $custtoday =0 ; $eiptoday = 0; $implementationtoday = 0; $pptoday = 0; $smstoday = 0; 
		$supporttoday = 0; $tastoday = 0; $trainingtoday = 0; 
		$datepiece = ($alltimecheck == 'yes')?(""):(" AND (left(inv_invoicenumbers.createddate,10) between '".$fromdate."' and '".$todate."') ");	

		//if($_POST['dealerid'] == 'all')
		if($_POST['dealerid'] == ' ' &&  $_POST['leftdealerid'] == ' ')
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
// 				$query = "SELECT distinct inv_mas_dealer.slno, inv_mas_dealer.businessname,inv_mas_dealer.branch
// FROM inv_mas_dealer left join inv_invoicenumbers on  inv_mas_dealer.slno = inv_invoicenumbers.dealerid 
// where inv_mas_dealer.disablelogin = 'no' and inv_mas_dealer.dealernotinuse = 'no' and inv_mas_dealer.branch = '".$branch."' order by businessname;";
				$query = "SELECT distinct inv_mas_dealer.slno, inv_mas_dealer.businessname,inv_mas_dealer.branch
FROM inv_mas_dealer left join inv_invoicenumbers on  inv_mas_dealer.slno = inv_invoicenumbers.dealerid 
where  inv_mas_dealer.dealernotinuse = 'no' and inv_mas_dealer.branch = '".$branch."' order by businessname;";
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
			//$dealerid = $_POST['dealerid'];
			$dealerid = ($_POST['dealerid'] == " ") ? $_POST['leftdealerid'] : $_POST['dealerid'];
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
		//echo($dealerpiece);exit;
		
		//Calculation of Total Sale, Total Tax, Total Amount
		$resultcount = "select count(distinct inv_invoicenumbers.slno) as count from inv_invoicenumbers  
left join dealer_online_purchase on dealer_online_purchase.onlineinvoiceno = inv_invoicenumbers.slno
where inv_invoicenumbers.slno <> '123456789' ".$datepiece.$dealerpiece." AND inv_invoicenumbers.status <> 'CANCELLED' order by inv_invoicenumbers.createddate desc;";
		$fetch10 = runmysqlqueryfetch($resultcount);
		$fetchresultcount = $fetch10['count'];
		$totalinvoices = $fetchresultcount;
		
		$query11 = "select sum(inv_invoicenumbers.amount) as amount,
sum(inv_invoicenumbers.servicetax+IFNULL(inv_invoicenumbers.sbtax,0)+IFNULL(inv_invoicenumbers.kktax,0)+IFNULL(inv_invoicenumbers.igst,0)+IFNULL(inv_invoicenumbers.sgst,0)+IFNULL(inv_invoicenumbers.cgst,0)) as servicetax,
sum(inv_invoicenumbers.netamount) as netamount from inv_invoicenumbers
where inv_invoicenumbers.slno <> '123456789' ".$datepiece.$dealerpiece." AND inv_invoicenumbers.status <> 'CANCELLED' order by inv_invoicenumbers.createddate desc";
		$fetchresult = runmysqlqueryfetch($query11);
		$totalsalevalue = $fetchresult['amount'];
		$totaltax = $fetchresult['servicetax'];
		$totalamountfinal = $fetchresult['netamount'];
		
		// Create Temporary table to store the data
		
			// Create Temporary tables 
		
		$querydrop = "Drop table if exists invoicedetailssearchdealer;";
		$result = runmysqlquery($querydrop);

		$querydrop1 = "Drop table if exists servicessearchdealer;";
		$result1 = runmysqlquery($querydrop1);
	
	
		// Create Temporary table to insert 'ITEM SOFTWARE' details
		$queryservices = "CREATE TEMPORARY TABLE `servicessearchdealer` ( 
		`slno` int(10) NOT NULL auto_increment, 
		 `invoiceno` int(10) default NULL,      
		 `servicename` varchar(100) collate latin1_general_ci default NULL, 
		 `serviceamount` varchar(10) collate latin1_general_ci default NULL, 
		 `createddate` datetime default '0000-00-00 00:00:00',
		`dealerid` varchar(25) collate latin1_general_ci default NULL, 
		`regionid` varchar(25) collate latin1_general_ci default NULL,   
		`branch` varchar(25) collate latin1_general_ci default NULL,  
		`branchname` varchar(25) collate latin1_general_ci default NULL, 
		`category` varchar(25) collate latin1_general_ci default NULL,   
		 PRIMARY KEY  (`slno`)    
	 );";
		$result0 = runmysqlquery($queryservices);

		
		$queryproducts = "CREATE TEMPORARY TABLE `invoicedetailssearchdealer` (                                       
				  `slno` int(10) NOT NULL auto_increment,                             
				  `invoiceno` int(10) default NULL,                                   
				  `productcode` varchar(10) collate latin1_general_ci default NULL,   
				  `usagetype` varchar(50) collate latin1_general_ci default NULL,     
				  `amount` varchar(25) collate latin1_general_ci default NULL,        
				  `purchasetype` varchar(25) collate latin1_general_ci default NULL,
				  `dealerid` varchar(25) collate latin1_general_ci default NULL, 
				  `invoicedate` datetime default '0000-00-00 00:00:00',
				  `productgroup` varchar(25) collate latin1_general_ci default NULL, 
				  `regionid` varchar(25) collate latin1_general_ci default NULL,   
				  `branch` varchar(25) collate latin1_general_ci default NULL,  
				  `branchname` varchar(25) collate latin1_general_ci default NULL,  
				  `category` varchar(25) collate latin1_general_ci default NULL,
				  `scratchnumber` varchar(25) collate latin1_general_ci default NULL,   
				  `cardid` varchar(25) collate latin1_general_ci default NULL,      
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
		$query = "select distinct inv_invoicenumbers.slno,left(inv_invoicenumbers.createddate,10) as createddate,inv_invoicenumbers.invoiceno,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_invoicenumbers.amount,(inv_invoicenumbers.servicetax+IFNULL(inv_invoicenumbers.sbtax,0)+IFNULL(inv_invoicenumbers.kktax,0)+IFNULL(inv_invoicenumbers.igst,0)+IFNULL(inv_invoicenumbers.sgst,0)+IFNULL(inv_invoicenumbers.cgst,0)) as servicetax,inv_invoicenumbers.netamount,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,inv_invoicenumbers.status,inv_invoicenumbers.products as products,inv_invoicenumbers.servicedescription as servicedescription from inv_invoicenumbers
left join dealer_online_purchase on dealer_online_purchase.onlineinvoiceno = inv_invoicenumbers.slno
where inv_invoicenumbers.slno <> '123456789' ".$datepiece.$dealerpiece." AND inv_invoicenumbers.status <> 'CANCELLED' order by inv_invoicenumbers.createddate desc  ";
		$result30 = runmysqlquery($query); //echo($query); exit;

		while($fetch0 = mysqli_fetch_array($result30))
		{
			// Now insert selected invoice details to temporary table condidering all details of the each invoice
			
			$query2 = "select * from inv_invoicenumbers where slno = '".$fetch0['slno']."'";
			$fetch2 = runmysqlqueryfetch($query2); //echo($query2);exit;
			// Insert data to services table
			$serviceamount = 0;
			if($fetch2['servicedescription'] <> '')
			{
				$serviceamountsplit = explode('*',$fetch2['servicedescription']);
				for($k = 0 ;$k < count($serviceamountsplit);$k++)
				{
					$finalsplit = explode('$',$serviceamountsplit[$k]); //echo($offerdescriptionsplit[$j]);exit;
					$serviceamount = $serviceamount + $finalsplit[2];
					// Insert into services table 
					$insertservices = "INSERT INTO servicessearchdealer(invoiceno,servicename,serviceamount,createddate,dealerid,regionid,branch,branchname,category) values('".$fetch2['slno']."','". $finalsplit[1]."','". $finalsplit[2]."','".$fetch2['createddate']."','".$fetch2['dealerid']."','".$fetch2['regionid']."','".$fetch2['branchid']."','".$fetch2['branch']."','".$fetch2['category']."')";
					$result = runmysqlquery($insertservices);
				}
			}
			// Insert data to invoice detals table 
			
			if($fetch2['products'] <> '') {
                $count++;
                $totalamount = 0;
                $products = explode('#', $fetch2['products']);
				$description = explode('*',$fetch2['description']);
                // $productquan = explode(',', $fetch2['productquantity']);
				$productquantity = explode(',',$fetch2['productquantity']);

                // $data = array();
                // for($k=0;$k<count($products);$k++)
                // {
                //     $data[] = $products[$k].$k;

                // }
                // $products1 = implode(',', $data);
                // $products2 = explode(',', $products1);
                //print_r($products);
                // $proquan = array_combine($products2, $productquan);
               // print_r($proquan);
              $k=0;
			  for($i = 0 ; $i < count((array)$description);$i++)
			  {
                    //echo $productkey ."check" . $value . "<br>";
                    for($j = 0 ; $j < $productquantity[$i];$j++)
					{
                        $totalamount = 0;
                        $description = explode('*', $fetch2['description']);
                        $splitdescription = explode('$', $description[$k]);

                        $productcode = $products[$i];
                        $usagetype = $splitdescription[3];
                        $scratchnumber = $splitdescription[4];
                        $cardid = $splitdescription[5];
                        $amount = $splitdescription[6];
                        $purchasetype = $splitdescription[2];   //echo($usagetype.'^'.$amount.'^'.$purchasetype); exit;


                            $totalamount = $amount;
                        //}

                        // Fetch Product

                        $query1 = "select inv_mas_product.group as productgroup from inv_mas_product where productcode = '" . $productcode . "' ";
                        $result1 = runmysqlqueryfetch($query1);

                        // Insert into invoice details table

                        $query3 = "insert into invoicedetailssearchdealer(invoiceno,productcode,usagetype,amount,purchasetype,dealerid,invoicedate,productgroup,regionid,branch,branchname,category,scratchnumber,cardid) values('" . $fetch2['slno'] . "','" . substr($productkey,0,3) . "','" . $usagetype . "','" . $totalamount . "','" . $purchasetype . "','" . $fetch2['dealerid'] . "','" . $fetch2['createddate'] . "','" . $result1['productgroup'] . "','" . $fetch2['regionid'] . "','" . $fetch2['branchid'] . "','" . $fetch2['branch'] . "','" . $fetch2['category'] . "','" . $scratchnumber . "','" . $cardid . "')";
                        $result3 = runmysqlquery($query3);

                        $k++;
                    }
                }
            }
		}
		
		
		$addlimit = " LIMIT ".$startlimit.",".$limit.";";
		$query1 = $query.$addlimit;
		$result1 = runmysqlquery($query1);
		$grid = '';
		
		
		//echo($query);exit;
		if($startlimit == 0)
		{
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
			$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Products</td><td nowrap = "nowrap" class="td-border-grid" align="left">Status</td><td nowrap = "nowrap" class="td-border-grid" align="left">Customer ID</td><td nowrap = "nowrap" class="td-border-grid" align="left">Customer Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Sale Value</td><td nowrap = "nowrap" class="td-border-grid" align="left">Tax Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Sales Person</td><td nowrap = "nowrap" class="td-border-grid" align="left">Prepared By</td><td nowrap = "nowrap" class="td-border-grid" align="left">Action</td></tr>';
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
			$productsplit = explode('#',$fetch['products']);
			$productsplitcount = count($productsplit);
			
			$servicedescriptionsplit = explode('*',$fetch['servicedescription']);
			$servicedescriptioncount = count($servicedescriptionsplit);
			
			if($fetch['products'] == '')
				$totalcount = $servicedescriptioncount;
			elseif(($fetch['products'] != '') && ($fetch['servicedescription'] != ''))
				$totalcount = $servicedescriptioncount + $productsplitcount;
			else
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
		
		// Fetch the selected product groups 
		
		/*$querygetgroups = "select distinct productgroup from productgroups";
		$resultgetgroups = runmysqlquery($querygetgroups);
		$groups = '';
		while($fetch10 = mysqli_fetch_array($resultgetgroups))
		{
			if($groups == '')
				$groups = $fetch10['productgroup'];
			else
				$groups = $groups.','.$fetch10['productgroup'];
		}
		$splitgroup = explode(',',$groups);*/
		
		
		$productwisegrid  = '<table width="95%" cellspacing="0" border = "0" cellpadding="2" align = "left" class="table-border-grid" style="font-size:12px;" >';
		
		$productwisegrid .= '<tr class="tr-grid-header">';
		$productwisegrid .= '<td width = "22%" class="td-border-grid" nowrap="nowrap" ><div align="center" ><strong>Product</strong></div></td>';
		$productwisegrid .= '<td width = "26%" class="td-border-grid" nowrap="nowrap" style = "font-size:12px;"><div align="center" ><strong>New</strong></div></td>';
		$productwisegrid .= '<td width = "26%" class="td-border-grid" nowrap="nowrap" style = "font-size:12px;"><div align="center"><strong>Updation</strong></div></td>';
		$productwisegrid .= '<td width = "26%" class="td-border-grid" nowrap="nowrap" style = "font-size:12px;"><div align="center"><strong>Total</strong></div></td>';
		$productwisegrid .= '</tr>';
		// Check if the prduct group exists in selected product group .
		//$splitselectedproductgroups = explode(',',$fetchproductgroups);
		//if(in_array('TDS',$splitgroup))
		//{

			$query200 = "select ifnull(sum(amount),'0') as amount from invoicedetailssearchdealer where purchasetype = 'New' and productgroup = 'TDS' " ;
			$result200 = runmysqlqueryfetch($query200);
			$tdsnew =  $tdsnew + $result200['amount'];
			
			$query206 = "select ifnull(sum(amount),'0') as amount from invoicedetailssearchdealer where purchasetype = 'Updation' and productgroup = 'TDS' " ;
			$result206 = runmysqlqueryfetch($query206);
			$tdsupdation = $tdsupdation + $result206['amount'];
			
			$tdstotal = $tdsnew + $tdsupdation;
			
			$productwisegrid .= '<tr bgcolor="#F7FAFF">';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#F7FAFF">TDS</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($tdsnew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($tdsupdation).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($tdstotal).'</td>';
			$productwisegrid .= '</tr>';
			
		//}
		//if(in_array( 'SPP',$splitgroup))
		//{
			$query201 = "select ifnull(sum(amount),'0') as amount from invoicedetailssearchdealer where  purchasetype = 'New' and productgroup = 'SPP' " ;
			$result201 = runmysqlqueryfetch($query201);
			$sppnew =  $sppnew + $result201['amount'];
			
			$query207 = "select ifnull(sum(amount),'0') as amount from invoicedetailssearchdealer where  purchasetype = 'Updation' and productgroup = 'SPP' " ;
			$result207 = runmysqlqueryfetch($query207);
			$sppupdation = $sppupdation + $result207['amount'];
			
			$spptotal = $sppnew + $sppupdation;
			
			$productwisegrid .= '<tr  bgcolor="#edf4ff">';
			$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="left">SPP</td>';
			$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" >'.formatnumber($sppnew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" >'.formatnumber($sppupdation).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($spptotal).'</td>';
			$productwisegrid .= '</tr>';
		/*}
		if(in_array( 'STO',$splitgroup))
		{*/
			$query202= "select ifnull(sum(amount),'0') as amount from invoicedetailssearchdealer where  purchasetype = 'New' and productgroup = 'STO' " ;
			$result202 = runmysqlqueryfetch($query202);
			$stonew = $stonew + $result202['amount'];
			
			$query208= "select ifnull(sum(amount),'0') as amount from invoicedetailssearchdealer where purchasetype = 'Updation' and productgroup = 'STO' " ;
			$result208 = runmysqlqueryfetch($query208);
			$stoupdation = $stoupdation + $result208['amount'];
			
			$stototal = $stonew + $stoupdation;
			
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#F7FAFF">STO</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($stonew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($stoupdation).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($stototal).'</td>';
			$productwisegrid .= '</tr>';
			
		/*}
		if(in_array('SVH',$splitgroup))
		{*/
			$query203 = "select ifnull(sum(amount),'0') as amount from invoicedetailssearchdealer where  purchasetype = 'New' and productgroup = 'SVH' " ;
			$result203 = runmysqlqueryfetch($query203);
			$svhnew = $svhnew + $result203['amount'];
			
			$query209 = "select ifnull(sum(amount),'0') as amount from invoicedetailssearchdealer where purchasetype = 'Updation' and productgroup = 'SVH' " ;
			$result209 = runmysqlqueryfetch($query209);
			$svhupdation =  $svhupdation + $result209['amount'];
			
			$svhtotal = $svhnew + $svhupdation;
			
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#edf4ff">SVH</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($svhnew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($svhupdation).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#edf4ff">'.formatnumber($svhtotal).'</td>';
			$productwisegrid .= '</tr>';
		/*}
		if(in_array('SVI',$splitgroup))
		{*/
			$query204 = "select ifnull(sum(amount),'0') as amount from invoicedetailssearchdealer where  purchasetype = 'New' and productgroup = 'SVI'   " ;
			$result204 = runmysqlqueryfetch($query204);
			$svinew = $svinew + $result204['amount'];
			
			$query210 = "select ifnull(sum(amount),'0') as amount from invoicedetailssearchdealer where  purchasetype = 'Updation' and productgroup = 'SVI' " ;
			$result210 = runmysqlqueryfetch($query210);
			$sviupdation = $sviupdation + $result210['amount'];
			
			$svitotal = $svinew + $sviupdation;
			
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="left" bgcolor="#F7FAFF">SVI</td>';
			$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($svinew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($sviupdation).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($svitotal).'</td>';
			$productwisegrid .= '</tr>';
		/*}
		if(in_array('SAC',$splitgroup))
		{*/
			$query205 = "select ifnull(sum(amount),'0') as amount from invoicedetailssearchdealer where  purchasetype = 'New' and productgroup = 'SAC' " ;
			$result205 = runmysqlqueryfetch($query205);
			$sacnew =  $sacnew + $result205['amount'];
			
			$query211 = "select ifnull(sum(amount),'0') as amount from invoicedetailssearchdealer where  purchasetype = 'Updation' and productgroup = 'SAC' " ;
			$result211 = runmysqlqueryfetch($query211);
			$sacupdation = $sacupdation + $result211['amount'];
			
			$sactotal = $sacnew + $sacupdation;
			
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left" bgcolor="#edf4ff">SAC</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($sacnew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($sacupdation).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#edf4ff">'.formatnumber($sactotal).'</td>';
			$productwisegrid .= '</tr>';
			
			$query206 = "select ifnull(sum(amount),'0') as amount from invoicedetailssearchdealer where  purchasetype = 'New' and productgroup = 'XBRL' " ;
			$result206 = runmysqlqueryfetch($query206);
			$xbrlnew =  $xbrlnew + $result206['amount'];
			
			$query219 = "select ifnull(sum(amount),'0') as amount from invoicedetailssearchdealer where  purchasetype = 'Updation' and productgroup = 'XBRL' " ;
			$result219 = runmysqlqueryfetch($query219);
			$xbrlupdation = $xbrlupdation + $result219['amount'];
			
			$xbrltotal = $xbrlnew + $xbrlupdation;
			
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left" bgcolor="#edf4ff">XBRL</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($xbrlnew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($xbrlupdation).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#edf4ff">'.formatnumber($xbrltotal).'</td>';
			$productwisegrid .= '</tr>';
		//}
		
			/*}
		if(in_array('GST',$splitgroup))
		{*/
			$query302 = "select ifnull(sum(amount),'0') as amount from invoicedetailssearchdealer where  purchasetype = 'New' and productgroup = 'GST'   " ;
			$result302 = runmysqlqueryfetch($query302);
		 $gstnew = $gstnew + $result302['amount'];
			
			$query303 = "select ifnull(sum(amount),'0') as amount from invoicedetailssearchdealer where  purchasetype = 'Updation' and productgroup = 'GST' " ;
			$result303 = runmysqlqueryfetch($query303);
		 $gstupdation = $gstupdation + $result303['amount'];
			
			$gsttotal = $gstnew + $gstupdation;
			
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="left" bgcolor="#F7FAFF">GST</td>';
			$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($gstnew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($gstupdation).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($gsttotal).'</td>';
			$productwisegrid .= '</tr>';
		/*}
		if(in_array('OTHERS',$splitgroup))
		{*/
			
			$query220 = "select ifnull(sum(amount),'0') as amount from invoicedetailssearchdealer where  purchasetype = 'New' and productgroup = 'OTHERS' " ;
			$result220 = runmysqlqueryfetch($query220);
			$othersnew =  $othersnew + $result220['amount'];
			
			$query221 = "select ifnull(sum(amount),'0') as amount from invoicedetailssearchdealer where  purchasetype = 'Updation' and productgroup = 'OTHERS' " ;
			$result221 = runmysqlqueryfetch($query221);
			$othersupdation = $othersupdation + $result221['amount'];
			
			$otherstotal = $othersnew + $othersupdation;
			
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left" bgcolor="#F7FAFF">OTHERS</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($othersnew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($othersupdation).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($otherstotal).'</td>';
			$productwisegrid .= '</tr>';
		
			
		// Calculate totals
		$todaynewtotal = $tdsnew + $sppnew + $stonew + $svhnew + $svinew + $sacnew + $othersnew + $xbrlnew + $gstnew;
		$todayupdationtotal = $tdsupdation + $sppupdation+ $stoupdation + $svhupdation + $sviupdation + $sacupdation +$othersupdation + $xbrlupdation + $gstupdation;
		
		$overalltotal = $tdstotal + $spptotal + $stototal + $svhtotal + $svitotal + $sactotal + $otherstotal + $xbrltotal+ $gsttotal;
	
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#F7FAFF"><strong>Total</strong></td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF"><strong>'.formatnumber($todaynewtotal).'</strong></td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF"><strong>'.formatnumber($todayupdationtotal).'</strong></td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF"><strong>'.formatnumber($overalltotal).'</strong></td>';
		$productwisegrid .= '</tr>';
		$productwisegrid .= '</table>';
		
		// Prepare Services Summary 
		$servicegrid = '<table width="100%" cellspacing="0" border = "0" cellpadding="2" align = "left" class="table-border-grid" style="font-size:12px;">';
		
		//Write the header Row of the table
		$servicegrid .= '<tr class="tr-grid-header"><td nowrap="nowrap" class="td-border-grid" width = "60%" align="center" ><strong>Service Name</strong/></td><td nowrap="nowrap" class="td-border-grid" align="center" width = "40%"><strong>Total</strong></td></tr>';
		
		$servicesall = "select ifnull(services.netamount,'0') as amount,inv_mas_service.servicename from inv_mas_service left join (select ifnull(sum(serviceamount),'0') as netamount,servicename from servicessearchdealer  group by servicename) as services on services.servicename = inv_mas_service.servicename order by inv_mas_service.servicename";
		$resultallservices = runmysqlquery($servicesall);
		$totalservices = 0;
		$i_n = 0;
		//echo(mysqli_num_rows($resultallservices));exit;
		while($fetchallservices = mysqli_fetch_array($resultallservices))
		{
			$i_n++;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$totalservices = $totalservices + $fetchallservices['amount'];
			$servicegrid .= '<tr bgcolor='.$color.'>';
			$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">'.$fetchallservices['servicename'].'</td>';
			$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($fetchallservices['amount']).'</td>';
			$servicegrid .= '</tr>';			
		}
		$servicegrid .= '<tr >';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left"><strong>Total</strong></td>';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right"><strong>'.formatnumber($totalservices).'</strong></td></tr>';
		$servicegrid .= '</table>';	
		
		/*$queryserviceamctoday = "select ifnull(sum(serviceamount),'0') as netamount from servicessearchdealer where  servicename like '%AMC Charges%'";
		$resultserviceamctoday = runmysqlqueryfetch($queryserviceamctoday);
		$amcttoday = $resultserviceamctoday['netamount'];
		
		$queryserviceattinttoday = "select ifnull(sum(serviceamount),'0') as netamount from servicessearchdealer where servicename like '%Attendance Integration%'";
		$resultserviceattinttoday = runmysqlqueryfetch($queryserviceattinttoday);
		$attinttoday = $resultserviceattinttoday['netamount'];
		
		$queryservicecusttoday = "select ifnull(sum(serviceamount),'0') as netamount from servicessearchdealer where servicename like '%Customization%'";
		$resultservicecusttoday = runmysqlqueryfetch($queryservicecusttoday);
		$custtoday = $resultservicecusttoday['netamount'];
		
		$queryserviceeiptoday = "select ifnull(sum(serviceamount),'0') as netamount from servicessearchdealer where  servicename like '%Employee Information Portal (EIP- SPP)%'";
		$resultserviceeiptoday = runmysqlqueryfetch($queryserviceeiptoday);
		$eiptoday = $resultserviceeiptoday['netamount'];
		
		$queryserviceimplementationtoday = "select ifnull(sum(serviceamount),'0') as netamount from servicessearchdealer where servicename like '%Implementation%'";
		$resultserviceimplementationtoday = runmysqlqueryfetch($queryserviceimplementationtoday);
		$implementationtoday = $resultserviceimplementationtoday['netamount'];
		
		$queryservicepptoday = "select ifnull(sum(serviceamount),'0') as netamount from servicessearchdealer where   servicename like '%Payroll Processing%'";
		$resultservicepptoday = runmysqlqueryfetch($queryservicepptoday);
		$pptoday = $resultservicepptoday['netamount'];
		
		$queryservicesmstoday = "select ifnull(sum(serviceamount),'0') as netamount from servicessearchdealer where   servicename like '%SMS Credits%'";
		$resultservicesmstoday = runmysqlqueryfetch($queryservicesmstoday);
		$smstoday = $resultservicesmstoday['netamount'];
		
		$queryservicesupporttoday = "select ifnull(sum(serviceamount),'0') as netamount from servicessearchdealer where  servicename like '%Support%'";
		$resultservicesupporttoday = runmysqlqueryfetch($queryservicesupporttoday);
		$supporttoday = $resultservicesupporttoday['netamount'];
		
		$queryservicetastoday = "select ifnull(sum(serviceamount),'0') as netamount from servicessearchdealer where  servicename like '%Time Attendance Solution (T&A-SPP)%'";
		$resultservicetastoday = runmysqlqueryfetch($queryservicetastoday);
		$tastoday = $resultservicetastoday['netamount'];
		
		$queryservicetrainingtoday = "select ifnull(sum(serviceamount),'0') as netamount from servicessearchdealer where servicename like '%training%'";
		$resultservicetrainingtoday = runmysqlqueryfetch($queryservicetrainingtoday);
		$trainingtoday = $resultservicetrainingtoday['netamount'];
			
			// Add all services to get total 
			
		$servicestotaltoday = $amcttoday + $attinttoday + $custtoday + $eiptoday + $implementationtoday + $pptoday + $smstoday + 
			$supporttoday + $tastoday + $trainingtoday; 
		
		$servicegrid .= '<tr bgcolor="#F7FAFF">';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">AMC Charges</td>';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($amcttoday).'</td>';
		$servicegrid .= '</tr>';
		
		$servicegrid .= '<tr bgcolor="#edf4ff">';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">Attendance Integration</td>';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($attinttoday).'</td>';
		$servicegrid .= '</tr>';
		
		$servicegrid .= '<tr  bgcolor="#F7FAFF">';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">Customization</td>';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($custtoday).'</td>';
		$servicegrid .= '</tr>';
		
		$servicegrid .= '<tr bgcolor="#edf4ff">';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left">Employee Information Portal (EIP- SPP)</td>';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right">'.formatnumber($eiptoday).'</td>';
		$servicegrid .= '</tr>';
		
		$servicegrid .= '<tr bgcolor="#F7FAFF">';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">Implementation</td>';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($implementationtoday).'</td>';
		$servicegrid .= '</tr>';
		
		$servicegrid .= '<tr bgcolor="#edf4ff">';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">Payroll Processing</td>';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"   align="right">'.formatnumber($pptoday).'</td>';
		$servicegrid .= '</tr>';
		
		$servicegrid .= '<tr  bgcolor="#F7FAFF">';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">SMS Credits</td>';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($smstoday).'</td>';
		$servicegrid .= '</tr>';
		
		$servicegrid .= '<tr bgcolor="#edf4ff">';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">Support</td>';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($supporttoday).'</td>';
		$servicegrid .= '</tr>';
		
		$servicegrid .= '<tr  bgcolor="#F7FAFF">';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">Time Attendance Solution (T&A-SPP)</td>';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($tastoday).'</td>';
		$servicegrid .= '</tr>';
		
		$servicegrid .= '<tr bgcolor="#edf4ff">';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">Training</td>';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($trainingtoday).'</td>';
		$servicegrid .= '</tr>';
		
		$servicestotaltoday = $amcttoday + $attinttoday + $custtoday + $eiptoday + $implementationtoday + $pptoday + $smstoday + 
			$supporttoday + $tastoday + $trainingtoday; 
			
		$servicegrid .= '<tr bgcolor="#edf4ff">';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left"><strong>Total</strong></td>';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right"><strong>'.formatnumber($servicestotaltoday).'</strong></td>';
		$servicegrid .= '</tr>';*/
		
		$fetchcount = mysqli_num_rows($result30);
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','125','".date('Y-m-d').' '.date('H:i:s')."','view_invoiceregister')";
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
		$responsearray1['servicegrid'] = $servicegrid;
		echo(json_encode($responsearray1));			
	}
	break;
	
	case 'productdetailsgrid':
		{
			$productslno = $_POST['productslno'];
			$query = "select * from inv_invoicenumbers where slno = '".$productslno."' ;";
			$result = runmysqlquery($query);
			if($startlimit == 0)
			{
				$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid" style="font-size:12px">';
				$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Purchase Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Usage Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Pin Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">Pin Serial Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Amount</td></tr>';
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
				
				$servicedescriptionsplit = explode('*',$fetch['servicedescription']);
				$servicedescriptioncount = count($servicedescriptionsplit);

				if($fetch['products'] == '')
				$fetchresultantcount = $servicedescriptioncount;
				elseif(($fetch['products'] != '') && ($fetch['servicedescription'] != ''))
					$fetchresultantcount = $servicedescriptioncount + $descriptionsplitcount;
				else
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
						$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$descriptionline[5]."</td>";
						$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$descriptionline[6]."</td>";
						$grid .= "</tr>";
					}
				}
				
				if($fetch['servicedescription'] <> '')
				{
					for($i=0; $i<$servicedescriptioncount; $i++)
					{
						$count++;
						$servicedescriptionline = explode('$',$servicedescriptionsplit[$i]);
						$grid .= '<tr bgcolor='.$color.' >';
						$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$servicedescriptionline[0]."</td> ";
						$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$servicedescriptionline[1]."</td>";
						$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>&nbsp;</td>";
						$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>&nbsp;</td>";
						$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>&nbsp;</td>";
						$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>&nbsp;</td>";
						$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$servicedescriptionline[2]."</td>";
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