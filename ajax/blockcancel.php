<?php
ob_start("ob_gzhandler");
ini_set('memory_limit', -1);

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

$type = $_POST['type'];
$changetype = $_POST['changetype'];
$scratchnumber = $_POST['scratchnumber'];
$remarks = $_POST['remarks'];
//$searchtext = $_POST['searchtext'];
//$frstchar = substr($searchtext, 0, 1);
//$liketext = ($frstchar == '.')?(" Where inv_mas_scratchcard.cardid LIKE '".$searchtextfield."%' "):("");
switch($type)
{
	
	case 'blockcard':
	{
		$query = "UPDATE inv_mas_scratchcard SET blocked = 'yes', remarks ='".$remarks."'  WHERE cardid = '".$scratchnumber."';";
		$result = runmysqlquery($query);
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','292','".date('Y-m-d').' '.date('H:i:s')."','".$scratchnumber."')";
		$eventresult = runmysqlquery($eventquery);
		$blockquery = "insert into inv_blockcanceldetails(cardid,userid,datetime,status,remarks,pinremarksstatus,module) values('".$scratchnumber."','".$userid."','".date('Y-m-d').' '.date('H:i:s')."','Blocked','".$remarks."','','dealer_module')";
		$blockresult = runmysqlquery($blockquery);
		echo(json_encode('1^Card blocked Successfully'));
	}
	break;
	
	// case 'unblockcard':
	// {
	// 	$query = "UPDATE inv_mas_scratchcard SET blocked = 'no', remarks ='".$remarks."'  WHERE cardid = '".$scratchnumber."';";
	// 	$result = runmysqlquery($query);
	// 	$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','68','".date('Y-m-d').' '.date('H:i:s')."','".$scratchnumber."')";
	// 	$eventresult = runmysqlquery($eventquery);
	// 	echo(json_encode('1^Card Unblocked Successfully'));
	// }
	// break;
	
	case 'cancelcard':
	{
		$query = "UPDATE inv_mas_scratchcard SET cancelled = 'yes', remarks ='".$remarks."',module='dealer_module'  WHERE cardid = '".$scratchnumber."';";
		$result = runmysqlquery($query);
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','292','".date('Y-m-d').' '.date('H:i:s')."','".$scratchnumber."')";
		$eventresult = runmysqlquery($eventquery);
		$cancelquery = "insert into inv_blockcanceldetails(cardid,userid,datetime,status,remarks,pinremarksstatus,module) values('".$scratchnumber."','".$userid."','".date('Y-m-d').' '.date('H:i:s')."','Cancelled','".$remarks."','','dealer_module')";
		$cancelresult = runmysqlquery($cancelquery);
		echo(json_encode('1^Card Cancelled Successfully'));
	}
	break;
	
	// case 'none':
	// {
	// 	$query = "UPDATE inv_mas_scratchcard SET blocked = 'no', cancelled = 'no', remarks ='".$remarks."'  WHERE cardid = '".$scratchnumber."';";
	// 	$result = runmysqlquery($query);
	// 	$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','69','".date('Y-m-d').' '.date('H:i:s')."','".$scratchnumber."')";
	// 	$eventresult = runmysqlquery($eventquery);
	// 	$activequery = "insert into inv_blockcanceldetails(cardid,userid,datetime,status,remarks,pinremarksstatus) values('".$scratchnumber."','".$userid."','".date('Y-m-d').' '.date('H:i:s')."','Active','".$remarks."','".$pinremarksstatus."')";
	// 	$activeresult = runmysqlquery($activequery);
	// 	echo(json_encode('1^Card Get Activated Successfully'));
	// }
	// break;
	
	case 'griddata':
	{
		$pinno = trim($_POST['pinno']);
		$cardid = trim($_POST['cardid']);
		
		if($cardid!= "")
		{
			$carddetails = "cardid = '".$cardid."'";
		}
		if($pinno!= "")
		{
			$carddetails = "scratchnumber = '".$pinno."'";
		}
		
		if($pinno!= "" && $cardid!= "")
		{
			$carddetails = "scratchnumber = '".$pinno."' and cardid = '".$cardid."'";
		}
		
		$query = "select * from inv_mas_scratchcard  where  ".$carddetails;
		$fetch = runmysqlqueryfetch($query);
		$i_n = 0;
		$card = $fetch['cardid'];
		
		$query1 = "select inv_mas_users.fullname,inv_mas_dealer.businessname,inv_blockcanceldetails.datetime,inv_blockcanceldetails.remarks,inv_blockcanceldetails.status,inv_blockcanceldetails.module
		from inv_blockcanceldetails
		left join inv_mas_dealer on inv_blockcanceldetails.userid = inv_mas_dealer.slno
		left join inv_mas_users on inv_blockcanceldetails.userid = inv_mas_users.slno
		where cardid =  '".$card."' order by inv_blockcanceldetails.slno desc";
		$result1 = runmysqlquery($query1);
		$fetchresultcount = mysqli_num_rows($result1);
		$grid = '<table width="100%" cellpadding="3" cellspacing="0"  class="table-border-grid" 
		style="font-size:12px">';
		$grid .= '<tr class="tr-grid-header">
		<td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Username</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Date</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Status</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Remarks</td>
		</tr>';
		if($fetchresultcount>0)
		{
			while($fetch1=mysqli_fetch_array($result1))
			{
				$i_n++;
				$color;
				if($i_n%2 == 0)
					$color = "#edf4ff";
				else
					$color = "#f7faff";
									
				if($fetch1['module']== 'user_module' || empty($fetch1['module']))
					$businessname = $fetch1['fullname'];
				else
					$businessname = $fetch1['businessname'];

				$grid .= '<tr class="gridrow" bgcolor='.$color.'>';
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$i_n."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$businessname."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch1['datetime']."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch1['status']."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".wordwrap($fetch1['remarks'],25,"<br>\n")."</td>";
				$grid .= "</tr>";
			 }
		  }
		  else
		  {
			  $grid .= '<tr class="gridrow" bgcolor='.$color.'>';
			  $grid .= "<td nowrap='nowrap' class='td-border-grid' align='left' colspan='5'>
			  <strong>No Records Found</strong>
			  </td>
			  </tr>";
		  }
		   $grid .= "</table>";
		
		echo '1^'.$grid;
	}
     break;
	 
	case 'carddetailstoform':
	{
		$carddetailstoformarray = array();
		//$cardlastslno = $_POST['cardlastslno'];
		$pinno = trim($_POST['pinno']);
		$cardid = trim($_POST['cardid']);
		
		if($cardid!= "")
		{
			$carddetails = "cardid = '".$cardid."'";
		}
		if($pinno!= "")
		{
			$carddetails = "scratchnumber = '".$pinno."'";
		}
		
		if($pinno!= "" && $cardid!= "")
		{
			$carddetails = "scratchnumber = '".$pinno."' and cardid = '".$cardid."'";
		}
		
		$query = "select * from inv_mas_scratchcard  where  ".$carddetails;
		$result = runmysqlquery($query);
		if(mysqli_num_rows($result) > 0)
		/*$result1 = runmysqlquery($query1);
		while($fetch1 = mysqli_fetch_array($result1))
		{
			$card = $fetch1['cardid'];*/
		
			/*$query = "SELECT distinct inv_dealercard.cardid , inv_mas_scratchcard.scratchnumber, inv_mas_dealer.businessname as attachedto, inv_mas_dealer.slno as dealerid, inv_mas_product.productcode, inv_mas_product.productname, inv_dealercard.purchasetype, inv_dealercard.usagetype, inv_dealercard.date as attachdate, inv_customerproduct.date as registereddate, inv_mas_customer.businessname as registeredto, inv_mas_scratchcard.remarks,inv_mas_scratchcard.blocked,inv_mas_scratchcard.cancelled ,inv_mas_scratchcard.attached,
			inv_mas_scratchcard.registered from inv_dealercard left join inv_mas_scratchcard on inv_dealercard.cardid = inv_mas_scratchcard.cardid left join inv_mas_dealer on inv_dealercard.dealerid = inv_mas_dealer.slno left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode left join (select distinct cardid, customerreference, MIN(date) AS date from inv_customerproduct GROUP BY date) AS inv_customerproduct on  inv_dealercard.cardid = inv_customerproduct.cardid left join inv_mas_customer on  inv_customerproduct.customerreference = inv_mas_customer.slno where inv_dealercard.cardid = '".$card."' order by cardid;";*/
	    {
			$fetch = runmysqlqueryfetch($query);
			$query1 = "select pinremarksstatus from inv_blockcanceldetails left join inv_pinremarksstatus on inv_blockcanceldetails.pinremarksstatus = inv_pinremarksstatus.id where cardid = '".$fetch['cardid']."' order by slno desc limit 1";
			$result1 = runmysqlquery($query1);
			if(mysqli_num_rows($result1))
			{
				$fetch1 = runmysqlqueryfetch($query1);
				if($fetch1['pinremarksstatus'] == null)
					$pinstatus = ' ';
				else
					$pinstatus = $fetch1['pinremarksstatus'];
			}
			else
				$pinstatus=" ";
			$cardstatus = "";
			if($fetch['blocked'] == 'yes')
				$cardstatus = 'Blocked';
			elseif($fetch['cancelled'] == 'yes')
				$cardstatus = 'Cancelled';
			else
			{
				$cardstatus = 'Active';
			}
	
			
			if($cardstatus == 'Active')
			$none = 'yes';
				else
			$none = '';
			
			$carddetailstoformarray['cardid'] = $fetch['cardid'];
			$carddetailstoformarray['scratchnumber'] = $fetch['scratchnumber'];
			$carddetailstoformarray['purchasetype'] = $fetch['purchasetype'];
			$carddetailstoformarray['usagetype'] = $fetch['usagetype'];
			$carddetailstoformarray['attachedto'] = $fetch['attachedto'];
			$carddetailstoformarray['dealerid'] = $fetch['dealerid'];
			$carddetailstoformarray['productcode'] = $fetch['productcode'];
			$carddetailstoformarray['productname'] = $fetch['productname'];
			$carddetailstoformarray['attachdate'] = $fetch['attachdate'];
			$carddetailstoformarray['registereddate'] = $fetch['registereddate'];
			$carddetailstoformarray['registeredto'] = $fetch['registeredto'];
			$carddetailstoformarray['remarks'] = $fetch['remarks'];
			$carddetailstoformarray['cardstatus'] = $cardstatus;
			$carddetailstoformarray['attached'] = $fetch['attached'];
			$carddetailstoformarray['registered'] = $fetch['registered'];
			$carddetailstoformarray['blocked'] = $fetch['blocked'];
			$carddetailstoformarray['cancelled'] = $fetch['cancelled'];
			$carddetailstoformarray['pinstatusremarks'] = $pinstatus;
			$carddetailstoformarray['none'] = $none;
			
			echo(json_encode($carddetailstoformarray));	
		}
		else
		{
			$carddetailstoformarray['nodata'] = "no data";
			
			echo(json_encode($carddetailstoformarray));	
		}

		//echo($fetch['cardid'].'^'.$fetch['scratchnumber'].'^'.$fetch['purchasetype'].'^'.$fetch['usagetype'].'^'.$fetch['attachedto'].'^'.$fetch['dealerid'].'^'.$fetch['productcode'].'^'.$fetch['productname'].'^'.$fetch['attachdate'].'^'.$fetch['registereddate'].'^'.$fetch['registeredto'].'^'.$fetch['remarks'].'^'.$cardstatus.'^'.$fetch['attached'].'^'.$fetch['registered'].'^'.$fetch['blocked'].'^'.$fetch['cancelled'].'^'.$none);
		///}
	}
	break;
}
?>
