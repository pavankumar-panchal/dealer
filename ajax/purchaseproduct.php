<?php 
ob_start("ob_gzhandler");

include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');
include('../inc/checksession.php');

$switchtype = $_POST['switchtype'];
$dealerid = $_POST['dealerid'];
$amount = $_POST['amount'];
$taxamount = $_POST['taxamount'];
$netamount = $_POST['netamount'];
$billstatus = 'justcreated';
$productlastslno = $_POST['productlastslno'];
$product = $_POST['product'];
$quantity = $_POST['quantity'];
$usagetype = $_POST['usagetype'];
$purchasetype = $_POST['purchasetype'];
$lastbillno = $_POST['lastbillno'];
$scheme = $_POST['scheme'];
$remarks= $_POST['remarks'];
$productrate= $_POST['productrate'];

if(imaxgetcookie('dealeruserid')<> '') 
$userid = imaxgetcookie('dealeruserid');
else
{ 
	echo(json_encode('Thinking to redirect'));
	exit;
}

switch($switchtype)
{

	case 'productsave':
	{
		if($productlastslno == '')
		{
			if($lastbillno == '')
			{
				$billdate =  datetimelocal('Y-m-d H:m:s');
				$query = "INSERT INTO inv_bill(dealerid,billdate,remarks,total,taxamount,netamount,billstatus) values('".$dealerid ."','".$billdate."','".$remarks."','0','0','0','".$billstatus."')";
				$result = runmysqlquery($query);
				$fetch1 = runmysqlqueryfetch("SELECT MAX(slno) AS cusbillnumber FROM inv_bill;");
				$query = "INSERT INTO inv_billdetail (cusbillnumber , productcode , productquantity , productamount ,productrate, usagetype , purchasetype,scheme) VALUES ('".$fetch1['cusbillnumber']."','".$product."','".$quantity."','".$amount."','".$productrate."','".$usagetype."','".$purchasetype."','".$scheme."');";
				$result = runmysqlquery($query);
				$firstbillnumber = $fetch1['cusbillnumber'];
				echo('1^fff^'.$firstbillnumber);
			}
			else
			{
				$query = "INSERT INTO inv_billdetail (cusbillnumber , productcode , productquantity , productamount ,productrate, usagetype , purchasetype,scheme ) VALUES ('".$lastbillno."','".$product."','".$quantity."','".$amount."','".$productrate."','".$usagetype."','".$purchasetype."','".$scheme."');";
				$result = runmysqlquery($query);
				echo('1^fff^'.$lastbillno);
			}
		}
		else
		{
			$query = "UPDATE inv_billdetail SET productcode = '".$product."' , productquantity = '".$quantity."', productamount = '".$amount."' , productrate = '".$productrate."' , usagetype = '".$usagetype."' , purchasetype = '".$purchasetype."', scheme = '".$scheme."' WHERE slno = '".$productlastslno."'";
			$result = runmysqlquery($query);
			echo('1^fff^'.$lastbillno);
	   }
	}
	break;
	case 'productdelete':
	{
		$productlastslno = $_POST['productlastslno'];
		$lastbillno = $_POST['lastbillno'];
		$query = "DELETE FROM inv_billdetail WHERE slno = '".$productlastslno."'";
		$result = runmysqlquery($query);
		echo('2^ff^'.$lastbillno);
	}
	break;
	case 'checkforproducts':
	{
		$dealerid = $_POST['dealerid'];
		$lastbillno = $_POST['lastbillno'];
		$query = "SELECT count(*) as count FROM inv_bill WHERE dealerid = '".$dealerid ."' AND billstatus ='justcreated' AND slno ='".$lastbillno."' ;";
		$fetch = runmysqlqueryfetch($query);
		if($fetch['count'] > 0)
		{
			echo('1^ff^'.$lastbillno);
		}
		else
		{
			echo('Select the products');
		}
	}
	break;
	/*case 'checkforcredit':
	{
		$dealerid = $_POST['dealerid'];
		$lastbillno = $_POST['lastbillno'];
		$netamount = $_POST['netamount'];
		$currentcredit = $_POST['currentcredit'];
	}
	break;*/
	case 'attachcards':
	{
				$dealerid = $_POST['dealerid'];
				$lastbillno = $_POST['lastbillno'];
				$totalamount = $_POST['totalamount'];
				$taxamount = $_POST['taxamount'];
				$netamount = $_POST['netamount'];
				$creditaval = getcurrentcredit($dealerid);
				$query1 = "SELECT sum(creditamount) as totalcredit FROM inv_credits WHERE dealerid = '".$dealerid."'";
				$fetchresult = runmysqlqueryfetch($query1);
				$totalcredit = $fetchresult['totalcredit'];
				if($totalcredit == '')
				{
				$totalcreditavl =0;
				}
				else
				$totalcreditavl = getcurrentcredit($dealerid);
				if($totalcreditavl >= $netamount)
				{
					$query = "UPDATE inv_bill SET total = '".$totalamount."',taxamount ='".$taxamount."',netamount='".$netamount."',billstatus='pending', userid ='2' WHERE dealerid = '".$dealerid."' AND slno = '".$lastbillno."' ";
					$result = runmysqlquery($query );
			
					
					$fetch = runmysqlqueryfetch("SELECT  count(*) AS count FROM inv_billdetail LEFT JOIN inv_bill ON inv_bill.slno = inv_billdetail.cusbillnumber WHERE inv_bill.slno = '".$lastbillno."';");
					$count = $fetch['count'];
					if($count > 0)
					{
					   $fetchstatus  = runmysqlqueryfetch("SELECT billstatus FROM inv_bill WHERE slno = '".$lastbillno."';");
					   $billstatus = $fetchstatus['billstatus'];
					   if($billstatus == 'pending')
					   {
						   $query = "SELECT inv_billdetail.slno as slno, inv_billdetail.cusbillnumber as cusbillnumber, inv_billdetail.productcode as productcode, inv_billdetail.productquantity as productquantity, inv_billdetail.purchasetype as purchasetype, inv_billdetail.usagetype as usagetype, inv_billdetail.scheme as scheme, inv_billdetail.addlicence as addlicence, inv_bill.dealerid as dealerid,inv_bill.netamount as netamount FROM inv_billdetail LEFT JOIN inv_bill ON inv_bill.slno = inv_billdetail.cusbillnumber WHERE inv_bill.slno = '".$lastbillno."';";
							$result = runmysqlquery($query);
							while($fetch = mysqli_fetch_array($result))
							{
								$scratchlimit = $fetch['productquantity'];
								$dealerid = $fetch['dealerid'];
								$cusbillid = $fetch['slno'];
								for($i=0; $i< $scratchlimit; $i++)
								{
									$query15 = "SELECT attachPIN() as cardid;";
									$result52 = runmysqlqueryfetch($query15);
									$query2 = "INSERT INTO inv_dealercard ( dealerid, cardid, productcode, date, remarks, cusbillnumber, usagetype, purchasetype, userid,scheme,initialusagetype,initialpurchasetype,initialproduct,initialdealerid,cusbillid) values('".$fetch['dealerid']."', '".$result52['cardid']."', '".$fetch['productcode']."', '".datetimelocal('Y-m-d')." ".datetimelocal('H:i:s')."', '".$fetch['remarks']."', '".$fetch['cusbillnumber']."', '".$fetch['usagetype']."', '".$fetch['purchasetype']."', '2','".$fetch['scheme']."','".$fetch['usagetype']."','".$fetch['purchasetype']."','".$fetch['productcode']."','".$fetch['dealerid']."','".$cusbillid."');";
									$result2 = runmysqlquery($query2);
									
									$query4 = "Insert into 
									inv_logs_purchase(dealerid,cardid,productcode,billamount,usagetype,purchasetype,scheme,purchasedate,userid,system,module)
values('".$fetch['dealerid']."','".$result52['cardid']."','".$fetch['productcode']."','".$fetch['netamount']."','".$fetch['usagetype']."','".$fetch['purchasetype']."','1','".datetimelocal('Y-m-d')." ".datetimelocal('H:i:s')."','".$userid."','".$_SERVER['REMOTE_ADDR']."','dealer_module')";
									$result4 =  runmysqlquery($query4);
								}
								
							 }
							 $query4 = "update inv_bill set billstatus = 'successful' where slno = '".$lastbillno."';";
							 $result4 = runmysqlquery($query4);
							 //echo("1^ Cards Attached Successfully.");
							$query5 = "SELECT inv_mas_product.dealerpurchasecaption AS productname, inv_mas_product.productcode as productcode, inv_dealercard.usagetype as usagetype, inv_dealercard.purchasetype as purchasetype, inv_mas_scratchcard.cardid AS cardid,inv_mas_scratchcard.scratchnumber AS scratchnumber,inv_mas_scheme.schemename  FROM (select * from inv_dealercard WHERE inv_dealercard.cusbillnumber='".$lastbillno."') AS inv_dealercard JOIN inv_mas_scratchcard ON inv_mas_scratchcard.cardid = inv_dealercard.cardid JOIN inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode left join inv_mas_scheme on inv_mas_scheme.slno =inv_dealercard.scheme;";
							$grid = '<table width="100%" cellpadding="3" cellspacing="0"  border = "1px" class="table-border-grid" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;text-align:left" >';
							$grid .= '<tr class="tr-grid-header"  bgcolor ="#E9E9D1"><td nowrap = "nowrap" class="td-border-grid">Sl No</td><td nowrap = "nowrap" class="td-border-grid">Product Name</td><td nowrap = "nowrap" class="td-border-grid">Usage Type</td><td nowrap = "nowrap" class="td-border-grid">Purchase Type</td><td nowrap = "nowrap" class="td-border-grid">Card Number</td><td nowrap = "nowrap" class="td-border-grid">Pin Number</td><td nowrap = "nowrap" class="td-border-grid">Scheme</td></tr>';
								
							$result5 = runmysqlquery($query5);
							$i_n = 0;$j=0;
							while($fetch5 = mysqli_fetch_array($result5))
							{
								$j++;
								$i_n++;
								$color;
								if($i_n%2 == 0)
								$color = "#edf4ff";
								else
								$color = "#f7faff";
								$grid .= '<tr class="gridrow1" bgcolor='.$color.'>';
								
									$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$j."</td>";
									$grid .= "<td nowrap='nowrap' class='td-border-grid'>".strtoupper($fetch5['productname'])." (".$fetch5['productcode'].")</td>";
									$grid .= "<td nowrap='nowrap' class='td-border-grid'>".strtoupper($fetch5['usagetype'])."</td>";
									$grid .= "<td nowrap='nowrap' class='td-border-grid'>".strtoupper($fetch5['purchasetype'])."</td>";
									$grid .= "<td nowrap='nowrap' class='td-border-grid'>".strtoupper($fetch5['cardid'])."</td>";
									$grid .= "<td nowrap='nowrap' class='td-border-grid'>".strtoupper($fetch5['scratchnumber'])."</td>";
									$grid .= "<td nowrap='nowrap' class='td-border-grid'>".strtoupper($fetch5['schemename'])."</td>";
							
								$grid .= "</tr>";
							}
							$grid .= "</table>";
							echo('1^'.$grid);
							$table = $grid ;
							$query1 = "select sum(netamount) as totalpurchaseamount from inv_bill where inv_bill.slno = '".$lastbillno."';";
							$fetch1 = runmysqlqueryfetch($query1);
							$query13 = "select remarks from inv_bill where inv_bill.slno = '".$lastbillno."';";
							$fetch1113 = runmysqlqueryfetch($query13);
							$remarkadded = $fetch1113['remarks'];
							$totalpurchaseamount = $fetch1['totalpurchaseamount'];
							$remarks = $fetch1['totalpurchaseamount'];
							$totalcredit = getcurrentcredit($dealerid);
							$query2 = "SELECT businessname,place,emailid,tlemailid,mgremailid,hoemailid,contactperson,remarks FROM inv_mas_dealer WHERE slno ='".$dealerid."' ";
							$fetch2 = runmysqlqueryfetch($query2);
							$businessname = $fetch2['businessname'];
							$place = $fetch2['place'];
						 	$emailid = $fetch2['emailid'];
							$tlemailid = $fetch2['tlemailid'];
							$mgremailid = $fetch2['mgremailid'];
							$hoemailid = $fetch2['hoemailid'];
							$contactperson = $fetch2['contactperson'];
												#########  Mailing Starts -----------------------------------
							//Dummy email ID
						 	//$emailid = 'meghana.b@relyonsoft.com';
							$emailarray = explode(',',$emailid);
							$bcceallemailid = $tlemailid.','.$mgremailid.','.$hoemailid;
							$bccemailarray = explode(',',$bcceallemailid);
							$bccemailcount = count($bccemailarray);
							$emailcount = count($emailarray);
							
							
							for($i = 0; $i < $emailcount; $i++)
							{
								if(checkemailaddress($emailarray[$i]))
								{
									if($i == 0)
										$emailids[$contactperson] = $emailarray[$i];
									else
										$emailids[$emailarray[$i]] = $emailarray[$i];
								}
							}
							for($i = 0; $i < $bccemailcount; $i++)
							{
								if(checkemailaddress($bccemailarray[$i]))
								{
									if($i == 0)
										$bccemailids[$contactperson] = $bccemailarray[$i];
									else
									{
										if($i==1)
										{
											if($bccemailids[$contactperson]!=$bccemailarray[$i])
											{
												$bccemailids[$bccemailarray[$i]] = $bccemailarray[$i];
											}
										}
										else if($i==2)
										{
											if($bccemailids[$contactperson]!=$bccemailarray[$i] && $bccemailarray[$i-1]!=$bccemailarray[$i])
											{
												$bccemailids[$bccemailarray[$i]] = $bccemailarray[$i];
											}
										}
										//$bccemailids[$bccemailarray[$i]] = $bccemailarray[$i];
									}
								}
								else
								{
									if($i == 0)
									{
										$bccemailids[$contactperson] ='';
									}										
								}
							}
							
							$fromname = "Relyon";
							$fromemail = "imax@relyon.co.in";
							$subject = "Testing";
							require_once("../inc/RSLMAIL_MAIL.php");
							$msg = file_get_contents("../mailcontents/newpurchase.htm");
							$textmsg = file_get_contents("../mailcontents/newpurchase.txt");
							$date = datetimelocal('d-m-Y');
							$array = array();
							$array[] = "##DATE##%^%".$date;
							$array[] = "##NAME##%^%".$contactperson;
							$array[] = "##COMPANY##%^%".$businessname;
							$array[] = "##PLACE##%^%".$place;
							$array[] = "##TABLE##%^%".$table;
							$array[] = "##BILLNO##%^%".$lastbillno;
							$array[] = "##EMAILID##%^%".$emailid;
							$array[] = "##AMOUNT##%^%".$totalpurchaseamount;
							$array[] = "##TOTALCREDIT##%^%".$totalcredit;
							$array[] = "##REMARKS##%^%".$remarkadded;
							$filearray = array(
								array('../images/relyon-logo.jpg','inline','1234567890') , array('../images/relyon-rupee-small.jpg','inline','1234567892')
							);
							if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab"))
							{
								$bccemailids['Rashmi'] = 'rashmi.hk@relyonsoft.com';
								$toarray = $emailids;
							}
							else
							{
								$toarray = $emailids;
								$bccemailids['Relyonimax'] ='relyonimax@gmail.com';
								$bccemailids['bigmail'] ='bigmail@relyonsoft.com';
							}
							
							//$bccemailids['bigmail'] ='archana.ab@relyonsoft.com';

							$bccarray = $bccemailids;
							$msg = replacemailvariable2($msg,$array);
							$textmsg = replacemailvariable2($textmsg,$array);
							$subject = 'New products purchased by "'.$businessname.'"';
							$html = $msg;
							$text = $textmsg;
							//rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,$bccarray,$filearray); 
							
							//Insert the mail forwarded details to the logs table
							$bccmailid = $bcceallemailid.','.'bigmail@relyonsoft.com'; 
							$resultvalue = str_replace(',,',',',$bccmailid);
							$resultbcc = ltrim($resultvalue,',');
							inserttologs($userid,$dealerid,$fromname,$fromemail,$emailid,null,$resultbcc ,$subject);
							
							#########  Mailing Ends ----------------------------------------
					   }
				   }
			    }
				else
				echo('2^No sufficient credits available');
	}
	break;
	case 'getamount':
	{
		$scheme = $_POST['scheme'];
		if(($usagetype == 'singleuser') && ($purchasetype == 'new'))
		{
			$query="SELECT newsuprice as price FROM inv_schemepricing where product ='".$product."' and scheme = '".$scheme."';";
			$result= runmysqlqueryfetch($query);
			$amount = $result['price'];
			$revenueshare = 'revenuesharenewsale';
		}
		else if(($usagetype == 'singleuser') && ($purchasetype == 'updation'))
		{
			$query="SELECT updatesuprice as price FROM inv_schemepricing where product ='".$product."' and scheme = '".$scheme."';";
			$result= runmysqlqueryfetch($query);
			$result= runmysqlqueryfetch($query);
			$amount = $result['price'];
			$revenueshare = 'revenueshareupsale';
		}
		else if(($usagetype == 'additionallicense') && ($purchasetype == 'new'))
		{
			$query="SELECT newaddlicenseprice as price FROM inv_schemepricing where product ='".$product."' and scheme = '".$scheme."';";
			$result= runmysqlqueryfetch($query);
			$result= runmysqlqueryfetch($query);
			$amount = $result['price'];
			$revenueshare = 'revenuesharenewsale';
		}
		else if(($usagetype == 'additionallicense') && ($purchasetype == 'updation'))
		{
			$query="SELECT updationaddlicenseprice as price FROM inv_schemepricing where product ='".$product." and scheme = '".$scheme."';'";
			$result= runmysqlqueryfetch($query);
			$result= runmysqlqueryfetch($query);
			$amount = $result['price'];
			$revenueshare = 'revenueshareupsale';
		}
		else if(($usagetype == 'multiuser') && ($purchasetype == 'new'))
		{
			$query="SELECT newmuprice as price FROM inv_schemepricing where product ='".$product."' and scheme = '".$scheme."';";
			$result= runmysqlqueryfetch($query);
			$amount = $result['price'];
			$revenueshare = 'revenuesharenewsale';
		}
		else
		{
			$query="SELECT updatemuprice as price FROM inv_schemepricing where product ='".$product."' and scheme = '".$scheme."';";
			$result= runmysqlqueryfetch($query);
			$amount = $result['price'];
			$revenueshare = 'revenueshareupsale';
		}
			$query1 = "SELECT ".$revenueshare." AS share FROM inv_mas_dealer WHERE slno = '".$dealerid."'";
			$result1 = runmysqlqueryfetch($query1);
			$share = $result1['share'];
			if($amount == 'NA')
			{
				echo('2^ This Usage/purchase Type is invalid for selected Scheme');
			}
			else
			{
				//$proshareamount = round();
				$productrate = round($amount - ($amount * ($share/100)));

				
				//$totalproductamount = ($quantity * $amount);
				//$shareamount =roundnearestvalue($totalproductamount * (($share)/100));
				$netproductamount = $productrate*$quantity;
				//echo $netproductamount; exit;
				echo('1^'.$netproductamount.'^'.$productrate);
			}
	}
	break;

	case 'netamount':
	{
		$query2 = "SELECT sum(productamount) as totalamount FROM inv_billdetail WHERE cusbillnumber = '".$lastbillno."'";
		$resultfetch2 = runmysqlqueryfetch($query2);
		$totalamount = $resultfetch2['totalamount'];
		$query3 = "SELECT taxamount  FROM inv_mas_dealer WHERE slno = '".$dealerid."';";
		$resultfetch3 = runmysqlqueryfetch($query3);
		$taxamount = $resultfetch3['taxamount'];
		$taxamountindecimal = $taxamount/100;
		$totaltax = round($taxamountindecimal*$totalamount);
		$netamount = $totaltax + $totalamount;
		echo($totalamount.'^'.$totaltax.'^'.$netamount);
	}
	break;
	case 'gridtoform':
	{
		$productlastslno = $_POST['productlastslno'];
		$query = "SELECT slno,cusbillnumber,productcode,productquantity,productamount,usagetype,purchasetype,scheme FROM inv_billdetail WHERE slno = '".$productlastslno."'";
		 $fetch = runmysqlqueryfetch($query);
		 echo($fetch['slno'].'^'.$fetch['cusbillnumber'].'^'.$fetch['productcode'].'^'.$fetch['productquantity'].'^'.$fetch['productamount'].'^'.$fetch['usagetype'].'^'.$fetch['purchasetype'].'^'.$fetch['scheme']);
	}
	break;

	case 'getinvoicedetails':
	{
		$invoicequery = "select * from inv_dealer_invoicenumbers where dealerreference = '".$userid."' order by slno desc;";
		$invoiceresult = runmysqlquery($invoicequery);
		$invoicecount = mysqli_num_rows($invoiceresult);
		if($invoicecount > 0)
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
			while($invoicefetch = mysqli_fetch_array($invoiceresult))
			{
				$i_n1++;$slno1++;
				$color1;
				if($i_n1%2 == 0)
					$color1 = "#edf4ff";
				else
					$color1 = "#f7faff";
				$invoicegrid .= '<tr class="gridrow1" bgcolor='.$color1.'>';
				$invoicegrid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slno1."</td> ";
				$invoicegrid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformatwithtime($invoicefetch['createddate'])."</td>";
				$invoicegrid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$invoicefetch['invoiceno']."</td>";
				$invoicegrid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$invoicefetch['netamount']."</td>";
				$invoicegrid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$invoicefetch['status']."</td>";
				$invoicegrid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$invoicefetch['createdby']."</td>";
				$invoicegrid .= '<td nowrap="nowrap" class="td-border-grid" align="center"> <a onclick="viewdealerinvoice(\''.$invoicefetch['slno'].'\');" class="resendtext"> View >></a> </td>';
				$invoicegrid .= "</tr>";
			}
		}
		echo($invoicegrid);

	}
	break;

	case 'generategrid':
	{
	$productlastslno = $_POST['productlastslno'];
	$lastbillno = $_POST['lastbillno'];
	$query = "SELECT inv_billdetail.slno,inv_billdetail.cusbillnumber,inv_mas_product.productname,inv_billdetail.productcode,inv_billdetail.productquantity,inv_billdetail.productamount,inv_billdetail.usagetype,inv_billdetail.purchasetype,inv_mas_scheme.schemename FROM inv_billdetail left join inv_mas_product on inv_mas_product.productcode =  inv_billdetail.productcode left join inv_mas_scheme on inv_mas_scheme.slno = inv_billdetail.scheme WHERE cusbillnumber = '".$lastbillno."';";
  $grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
  $grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid">Sl No</td><td nowrap = "nowrap" class="td-border-grid">Product Name</td><td nowrap = "nowrap" class="td-border-grid">Product Code</td><td nowrap = "nowrap" class="td-border-grid">Product Quantity</td><td nowrap = "nowrap" class="td-border-grid">Product Amount</td><td nowrap = "nowrap" class="td-border-grid">Usage Type</td><td nowrap = "nowrap" class="td-border-grid">Purchase Type</td><td nowrap = "nowrap" class="td-border-grid">Scheme</td></tr>';
   $i_n = 0; $j=0;
  $result = runmysqlquery($query);
  while($fetch = mysqli_fetch_row($result))
  {
 	$j++;
   $i_n++;
   $color;
   if($i_n%2 == 0)
    $color = "#edf4ff";
   else
    $color = "#f7faff";
   $grid .= '<tr class="gridrow" bgcolor='.$color.' onclick="productgridtoform(\''.$fetch[0].'\')">';
    $grid .= "<td nowrap='nowrap' class='td-border-grid'>".$j."</td>";
   for($i = 2; $i < count($fetch); $i++)
   {
   
    $grid .= "<td nowrap='nowrap' class='td-border-grid'>".$fetch[$i]."</td>";
   }
   $grid .= "</tr>";
  }
  $grid .= "</table>";
  echo($grid);
	}
	break;
	case 'getcurrentcredit':
	 {
		/*  $query0 = "SELECT sum(creditamount) as totalcredit FROM inv_credits WHERE dealerid = '".$dealerid."'";
		  $resultfetch = runmysqlqueryfetch($query0);
		  $totalcredit = $resultfetch['totalcredit'];
		  $query1 = "SELECT sum(netamount) as billedamount FROM inv_bill WHERE dealerid = '".$dealerid."' AND billstatus ='successful'";
		  $resultfetch1 = runmysqlqueryfetch($query1);
		  $billedamount =$resultfetch1['billedamount'];
		  $totalcredit = $totalcredit - $billedamount;*/
		  $totalcredit = getcurrentcredit($dealerid);
		  $query2 = "SELECT taxname FROM inv_mas_dealer WHERE slno = '".$dealerid."'";
		  $resultfetch2 = runmysqlqueryfetch($query2);
		  $taxname = $resultfetch2['taxname'];
		  $query3 = "SELECT taxamount FROM inv_mas_dealer WHERE slno ='".$dealerid."';";
		  $resultfetch3 = runmysqlqueryfetch($query3);
		  $taxamount = $resultfetch3 ['taxamount'];
		  echo($totalcredit.'^'.$taxname.'^'.$taxamount);
		  
	 }
	 break;
}


function getcurrentcredit($dealerid)
{
	$query0 = "SELECT sum(creditamount) as totalcredit FROM inv_credits WHERE dealerid = '".$dealerid."'";
	$resultfetch = runmysqlqueryfetch($query0);
	$totalcredit = $resultfetch['totalcredit'];
	$query1 = "SELECT sum(netamount) as billedamount FROM inv_bill WHERE dealerid = '".$dealerid."' AND billstatus ='successful'";
	$resultfetch1 = runmysqlqueryfetch($query1);
	$billedamount = $resultfetch1['billedamount'];
	$totalcreditavl = $totalcredit - $billedamount;
	return $totalcreditavl;
}
?>