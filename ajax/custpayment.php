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
		$customerreference = $_POST['customerreference'];
		$interactiondate = datetimelocal('d-m-Y');
		$interactiontime = datetimelocal('H:i:s');
		$remarks = $_POST['remarks'];
		$billref = $_POST['billref'];
		$paymentamt = $_POST['paymentamt'];
		$paymentstatus = $_POST['paymentstatus'];
		$paymentdesc = $_POST['Paymentdesc'];
		$lastslno = $_POST['lastslno'];
		$fetch56 = runmysqlqueryfetch("SELECT slno,businessname FROM inv_mas_dealer WHERE slno = '".$userid."'");
		$enteredby = $fetch56['slno'];
		if($lastslno == '')
		{
			$query = "Insert into inv_custpaymentreq(custreferences,productdesc,billref,remarks,paymentamt,paymentdate,paymenttime,enteredby,modulename,createddate) values ('".$customerreference."','".$paymentdesc."','".$billref."','".$remarks."','".$paymentamt."','".changedateformat($interactiondate)."','".changetimeformat($interactiontime)."','".$enteredby."','dealer_module','".date('Y-m-d')."');";
			$result = runmysqlquery($query);
		}
		else
		{
			$query = "SELECT inv_custpaymentreq.slno as slno,inv_custpaymentreq.paymentstatus as paymentstatus  FROM inv_custpaymentreq WHERE inv_custpaymentreq.slno = '".$lastslno."';";
				$result = runmysqlquery($query);
				if(mysqli_num_rows($result) == 0)
				{
					echo('3^'.'Invalid ');
					break;
				}
				else
				{
					$result = runmysqlqueryfetch($query);
					$paystatus = $result['paymentstatus'];
					if($paystatus == 'UNPAID')
					{
						$query = "UPDATE inv_custpaymentreq SET paymentdate = '".changedateformat($interactiondate)."',paymenttime = '".changetimeformat($interactiontime)."',remarks = '".$remarks."',billref = '".$billref."',paymentamt = '".$paymentamt."',productdesc = '".$paymentdesc."',paymentstatus = '".$paymentstatus."',manuallyupdate = 'YES' WHERE slno = '".$lastslno."';";
			$result = runmysqlquery($query);
					}
					else
					{
						echo('3^'.'Invalid ');
						break;
					}
				}
			
		}
			echo("1^"."Customer Payment Request '".$customerreference."' Saved Successfully");
	}
	break;

	
	case 'generatecustomerlist':
	{
		$query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode 
from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
 where inv_mas_dealer.slno = '".$userid."';";
		$fetch = runmysqlqueryfetch($query);
		$relyonexecutive = $fetch['relyonexecutive'];
		$district = $fetch['district'];
		$state = $fetch['statecode'];
		if($relyonexecutive == 'no')
		{
			$query = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname  from inv_mas_customer where inv_mas_customer.currentdealer = '".$userid."' order by businessname;";
			$result = runmysqlquery($query);
		}
		else
		{
			$query = "select inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname from inv_mas_customer left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode where inv_mas_district.districtcode in( select districtcode from inv_districtmapping where dealerid = '".$userid."') order by businessname;";
			$result = runmysqlquery($query);
			if(mysqli_num_rows($result) == 0)
			{
				$query = "select inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname 
	from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
	where inv_mas_district.statecode = '".$state."' order by businessname;";
				$result = runmysqlquery($query);
			}
		}
		$grid = '';
		$count = 1;
		while($fetch = mysqli_fetch_array($result))
		{
			if($count > 1)
				$grid .='^*^';
			$grid .= $fetch['businessname'].'^'.$fetch['slno'];
			$count++;
		}
		echo($grid);
	}
	break;
	case 'resendrequestemail':
	{
		$customerslno = $_POST['customerslno'];
		$query = "SELECT inv_mas_customer.businessname as businessname,inv_custpaymentreq.productdesc as productdesc,inv_custpaymentreq.billref as billref,inv_custpaymentreq.paymentamt as paymentamt FROM inv_custpaymentreq LEFT JOIN inv_mas_customer on inv_mas_customer.slno = inv_custpaymentreq.custreferences WHERE inv_custpaymentreq.slno = '".$customerslno."' and paymentstatus = 'UNPAID' ";
		$result2 = runmysqlquery($query);
		if( mysqli_num_rows($result2) == 0 )
		{
			echo('2^'.'Cannot send mail to a customer where payment request is already processed.');
			break;
		}
		else
		{
			$grid = '<table width="700px" cellpadding="3" cellspacing="0" border="1" align="center" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px" >';
			$grid .= '<tr bgcolor ="#E9E9D1"><td nowrap = "nowrap"><strong>Sl No</strong></td><td nowrap = "nowrap"><strong>Company</strong></td><td nowrap = "nowrap"><strong>Product</strong></td><td nowrap = "nowrap" ><strong>Bill Reference</strong></td><td nowrap = "nowrap" ><strong>Amount</strong></td></tr>';

			$k = 0;
			while($fetch2 = mysqli_fetch_array($result2))
			{
				
				$k++;
				$grid .= '<tr>';
				$grid .= "<td nowrap = 'nowrap'>".$k."</td>";
				$grid .= "<td nowrap = 'nowrap'>".$fetch2['businessname']."</td>";
				$grid .= "<td nowrap = 'nowrap'>".$fetch2['productdesc']."</td>";
				$grid .= "<td nowrap = 'nowrap'>".$fetch2['billref']."</td>";
				$grid .= "<td nowrap = 'nowrap'>".$fetch2['paymentamt']."</td>";
				$grid .= "</tr>";
			}
				$grid .= "</table>";
				$table = $grid;
				
			sendpaymentreqemail($customerslno,$table,$userid);
			echo('1^'.'Request Mail has been sent Successfully for the Selected Customer.');
		}
	}
	break;
	case 'generategrid':
	{
		$startlimit = $_POST['startlimit'];
		$slno = $_POST['slno'];
		$showtype = $_POST['showtype'];
		$customerreference = $_POST['lastslno'];
		$query ="SELECT distinct count(*) as count from inv_custpaymentreq where inv_custpaymentreq.custreferences = '".$customerreference."'  ";  
		$fetch1 = runmysqlqueryfetch($query);
		if($fetch1['count'] > 0)
		{
			$resultcount = "SELECT inv_custpaymentreq.slno as slno,inv_mas_customer.businessname as businessname,
inv_custpaymentreq.productdesc as productdesc,inv_custpaymentreq.billref as billref,
inv_custpaymentreq.paymentamt as paymentamt,inv_custpaymentreq.remarks as remarks,inv_custpaymentreq.paymentstatus as paymentstatus ,
inv_custpaymentreq.paymentdate as paymentdate,inv_custpaymentreq.paymenttime as paymenttime,inv_custpaymentreq.modulename as modulename,inv_custpaymentreq.enteredby as enteredby  from  inv_custpaymentreq  LEFT JOIN inv_mas_customer on inv_mas_customer.slno = inv_custpaymentreq.custreferences WHERE inv_mas_customer.slno = '".$customerreference."' order by paymentdate DESC";
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
		$query = "SELECT inv_custpaymentreq.slno as slno,inv_mas_customer.businessname as businessname,
inv_custpaymentreq.productdesc as productdesc,inv_custpaymentreq.billref as billref,
inv_custpaymentreq.paymentamt as paymentamt,inv_custpaymentreq.remarks as remarks,inv_custpaymentreq.paymentstatus as paymentstatus ,
inv_custpaymentreq.paymentdate as paymentdate,inv_custpaymentreq.paymenttime as paymenttime,inv_custpaymentreq.modulename as modulename,inv_custpaymentreq.enteredby as enteredby  from  inv_custpaymentreq  LEFT JOIN inv_mas_customer on inv_mas_customer.slno = inv_custpaymentreq.custreferences WHERE inv_mas_customer.slno = '".$customerreference."' order by paymentdate DESC LIMIT ".$startlimit.",".$limit.";";
 
 		if($startlimit == 0)
			{
				$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid" style="text-align:left">';
				$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid">Sl No</td><td nowrap = "nowrap" class="td-border-grid">Customer Name</td><td nowrap = "nowrap" class="td-border-grid">Product Descripition</td><td nowrap = "nowrap" class="td-border-grid">Bill Reference</td><td nowrap = "nowrap" class="td-border-grid">Payment Amount</td><td nowrap = "nowrap" class="td-border-grid">Remarks</td><td nowrap = "nowrap" class="td-border-grid">Payment Status</td><td nowrap = "nowrap" class="td-border-grid">Entered By</td><td nowrap = "nowrap" class="td-border-grid">Entered Through</td></tr>';
			}
		$i_n = 0;
		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_array($result))
		{
			$i_n++;$slno++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$grid .= '<tr class="gridrow" bgcolor='.$color.' onclick="gridtoform(\''.$fetch[0].'\')">';
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$slno."</td>";
			
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$fetch['businessname']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$fetch['productdesc']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$fetch['billref']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$fetch['paymentamt']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$fetch['remarks']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$fetch['paymentstatus']."</td>";
			if($fetch['modulename'] == 'dealer_module')
			{
				$query2 ="select inv_mas_dealer.businessname as dealerbusinessname from inv_mas_dealer 
left join inv_custpaymentreq on inv_custpaymentreq.enteredby = inv_mas_dealer.slno 
WHERE inv_mas_dealer.slno = '".$fetch['enteredby']."'";
				$fetchresult = runmysqlqueryfetch($query2);
				$dealerbusinessname  = $fetchresult['dealerbusinessname'];
				$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$dealerbusinessname."</td>";
			}
			else
			{
				$query1 ="select fullname from inv_mas_users left join inv_custpaymentreq on inv_custpaymentreq .enteredby = inv_mas_users.slno WHERE inv_mas_users.slno = '".$fetch['enteredby']."'";
				$resultfetch = runmysqlqueryfetch($query1);
				$enteredby  = $resultfetch['fullname'];
				$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$enteredby."</td>";
			}
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".modulegropname($fetch['modulename'])."</td>";
			
			$grid .= "</tr>";
		}
				$fetchcount = mysqli_num_rows($result);
				if($slno >= $fetchresultcount)
				$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
				else
				$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a class="resendtext" onclick ="cuspaymentgrid(\''.$startlimit.'\',\''.$slno.'\',\'more\');" style="cursor:pointer">Show More Records >></a>&nbsp;&nbsp;&nbsp;<a onclick ="cuspaymentgrid(\''.$startlimit.'\',\''.$slno.'\',\'all\');" class ="resendtext1" style="cursor:pointer"><font color= "#000000">(Show All Records)</font></a></div></td></tr></table>';
		
				echo('1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid);
			}
				else
			{
				echo('2^No datas found to be displayed.');
			}
	}
	break;
	
	case 'gridtoform':
	{
		$cusinteractionslno = $_POST['cusinteractionslno'];
		$query = "SELECT inv_custpaymentreq.slno as slno,inv_mas_customer.businessname as businessname,inv_mas_customer.customerid as customerid,inv_custpaymentreq.productdesc as productdesc,inv_custpaymentreq.billref as billref,
inv_custpaymentreq.paymentamt as paymentamt,inv_custpaymentreq.remarks as remarks,inv_custpaymentreq.paymentstatus as paymentstatus ,
inv_custpaymentreq.paymentdate as paymentdate,inv_custpaymentreq.paymenttime as paymenttime,inv_custpaymentreq.createddate as createddate,inv_custpaymentreq.modulename as modulename,inv_custpaymentreq.enteredby as enteredby FROM inv_custpaymentreq 
LEFT JOIN inv_mas_customer on inv_mas_customer.slno = inv_custpaymentreq.custreferences 
 WHERE inv_custpaymentreq.slno = '".$cusinteractionslno."';";
			$fetch = runmysqlqueryfetch($query);
			$createddate = changedateformat($fetch['paymentdate']);
			$createdtime = changetimeformat($fetch['paymenttime']);
			$paiddatetime = $createddate.' '.'('.$createdtime.')';
			
			if($fetch['modulename'] == 'dealer_module')
			{
				$query2 ="select inv_mas_dealer.businessname as dealerbusinessname from inv_mas_dealer 
left join inv_custpaymentreq on inv_custpaymentreq.enteredby = inv_mas_dealer.slno 
WHERE inv_mas_dealer.slno = '".$fetch['enteredby']."'";
				$fetchresult = runmysqlqueryfetch($query2);
				$enteredby  = $fetchresult['dealerbusinessname'];
			}
			else
			{
				$query1 ="select fullname from inv_mas_users left join inv_custpaymentreq on inv_custpaymentreq .enteredby = inv_mas_users.slno WHERE inv_mas_users.slno = '".$fetch['enteredby']."'";
				$resultfetch = runmysqlqueryfetch($query1);
				$enteredby  = $resultfetch['fullname'];
			}
			
			$paymentstatus = $fetch['paymentstatus'];
			if($paymentstatus == 'UNPAID')
			{
				if($userid == $fetch['enteredby'])
				{
					echo('1^'.$fetch['businessname'].'^'.''.'^'.$fetch['remarks'].'^'.$fetch['productdesc'].'^'.$fetch['billref'].'^'.$fetch['paymentamt'].'^'.'<select name="paymentstatus" class="swiftselect" id="paymentstatus" style="width:150px;"><option value="UNPAID">UNPAID</option><option value="PAID">PAID</option>'.'^'.$fetch['slno'].'^'.$enteredby.'^'. changedateformat($fetch['createddate']));
					
				}elseif($userid <> $fetch['enteredby']) 
				{
					echo('3^'.$fetch['businessname'].'^'.''.'^'.$fetch['remarks'].'^'.$fetch['productdesc'].'^'.$fetch['billref'].'^'.$fetch['paymentamt'].'^'.$fetch['paymentstatus'].'^'.$fetch['slno'].'^'.$enteredby.'^'. changedateformat($fetch['createddate']));
					
				}
			}
			elseif($paymentstatus == 'PAID')
			{
					echo('2^'.$fetch['businessname'].'^'.$paiddatetime.'^'.$fetch['remarks'].'^'.$fetch['productdesc'].'^'.$fetch['billref'].'^'.$fetch['paymentamt'].'^'.$fetch['paymentstatus'].'^'.$fetch['slno'].'^'.$enteredby.'^'. changedateformat($fetch['createddate']));
					break;
			}
	}
	break;
	
	case 'displayentry':
	{
		$query = "SELECT businessname,slno from inv_mas_dealer where slno = '".$userid."';";
		$fetch = runmysqlqueryfetch($query);
		echo('1^'.$fetch['businessname'].'^'.date('d-m-Y').' ('.date('H:i').')' );
	}
	break;
	case 'searchbycustomerid':
	{
		$customerid = $_POST['customerid'];
		$customeridlen = strlen($customerid);
		//$lastcustomerid = substr($customerid, $customeridlen - 5);
		$customerid = ($_POST['customerid'] == 5)?($_POST['customerid']):(substr($customerid, $customeridlen - 5));
		
		$query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode where inv_mas_dealer.slno = '".$userid."';";
		$fetch = runmysqlqueryfetch($query);
		$relyonexecutive = $fetch['relyonexecutive'];
		$district = $fetch['district'];
		$state = $fetch['statecode'];
		if($relyonexecutive == 'no')
		{
			$dealerpiece = " AND inv_mas_customer.currentdealer = '".$userid."'";
		}
		else
		{
			$query = "select * from inv_mas_customer left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode where inv_mas_district.districtcode in( select districtcode from inv_districtmapping where dealerid = '".$userid."') order by businessname;";
			$result = runmysqlquery($query);
			if(mysqli_num_rows($result) == 0)
			{
				$dealerpiece = " AND inv_mas_district.statecode = '".$state."' ";
			}
			else
				$dealerpiece = " AND inv_mas_district.districtcode in (select districtcode from inv_districtmapping where dealerid = '".$userid."')";
		}
		$query1 = "SELECT count(*) as count from inv_mas_customer left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode where inv_mas_customer.slno = '".$customerid."'".$dealerpiece."";
		$fetch2 = runmysqlqueryfetch($query1);
		if($fetch2['count'] > 0)
		{
			$query1 = "SELECT count(*) as count from inv_custpaymentreq where custreferences = '".$customerid."'";
			$fetch1 = runmysqlqueryfetch($query1);
			if($fetch1['count'] > 0)
			{
				$query = "SELECT inv_custpaymentreq.slno as slno,inv_mas_customer.businessname as businessname,inv_mas_customer.customerid as customerid,inv_custpaymentreq.productdesc as productdesc,inv_custpaymentreq.billref as billref,inv_custpaymentreq.paymentamt as paymentamt,inv_custpaymentreq.remarks as remarks,inv_custpaymentreq.paymentstatus as paymentstatus ,inv_custpaymentreq.paymentdate as paymentdate,inv_custpaymentreq.paymenttime as paymenttime,inv_custpaymentreq.enteredby as enteredby,inv_custpaymentreq.modulename as modulename,inv_custpaymentreq.createddate as createddate FROM inv_custpaymentreq LEFT JOIN inv_mas_customer on inv_mas_customer.slno = inv_custpaymentreq.custreferences 
	WHERE inv_mas_customer.slno = '".$customerid."';";
				$fetch = runmysqlqueryfetch($query);
			$createddate = changedateformat($fetch['paymentdate']);
			$createdtime = changetimeformat($fetch['paymenttime']);
			$paiddatetime = $createddate.' '.'('.$createdtime.')';
			
			if($fetch['modulename'] == 'dealer_module')
			{
				$query2 ="select inv_mas_dealer.businessname as dealerbusinessname from inv_mas_dealer 
left join inv_custpaymentreq on inv_custpaymentreq.enteredby = inv_mas_dealer.slno 
WHERE inv_mas_dealer.slno = '".$fetch['enteredby']."'";
				$fetchresult = runmysqlqueryfetch($query2);
				$enteredby  = $fetchresult['dealerbusinessname'];
			}
			else
			{
				$query1 ="select fullname from inv_mas_users left join inv_custpaymentreq on inv_custpaymentreq .enteredby = inv_mas_users.slno WHERE inv_mas_users.slno = '".$fetch['enteredby']."'";
				$resultfetch = runmysqlqueryfetch($query1);
				$enteredby  = $resultfetch['fullname'];
			}
			
			$paymentstatus = $fetch['paymentstatus'];
			if($paymentstatus == 'UNPAID')
			{
				if($userid == $fetch['enteredby'])
				{
					echo('1^'.$fetch['businessname'].'^'.''.'^'.$fetch['remarks'].'^'.$fetch['productdesc'].'^'.$fetch['billref'].'^'.$fetch['paymentamt'].'^'.'<select name="paymentstatus" class="swiftselect" id="paymentstatus" style="width:150px;"><option value="UNPAID">UNPAID</option><option value="PAID">PAID</option>'.'^'.$fetch['slno'].'^'.$enteredby.'^'. changedateformat($fetch['createddate']));
					
				}elseif($userid <> $fetch['enteredby']) 
				{
					echo('3^'.$fetch['businessname'].'^'.''.'^'.$fetch['remarks'].'^'.$fetch['productdesc'].'^'.$fetch['billref'].'^'.$fetch['paymentamt'].'^'.$fetch['paymentstatus'].'^'.$fetch['slno'].'^'.$enteredby.'^'. changedateformat($fetch['createddate']));
					
				}
			}
				elseif($paymentstatus == 'PAID')
				{
						echo('2^'.$fetch['businessname'].'^'.$paiddatetime.'^'.$fetch['remarks'].'^'.$fetch['productdesc'].'^'.$fetch['billref'].'^'.$fetch['paymentamt'].'^'.$fetch['paymentstatus'].'^'.$fetch['slno'].'^'.$enteredby.'^'.changedateformat($fetch['createddate']));
						break;
				}
			}
			else
				{
					$query1 = "SELECT businessname from inv_mas_customer where  slno  = '".$customerid."'";
					$fetch1 = runmysqlqueryfetch($query1);
					echo('4^'.$fetch1['businessname']);
				}
		}
		else
		{
			echo($customerid.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.'');
		}

	}
	break;
}
?>