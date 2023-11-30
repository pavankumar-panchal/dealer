<?php
## Created By Bhavesh  ##
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

$cardid = $_POST['cardid'];
$switchtype = $_POST['switchtype'];
$cardlist = $_POST['cardlist'];

switch($switchtype)
{
	case 'attachcard':
	{
		$attacheddate = datetimelocal('Y-m-d').' '.datetimelocal('H:i:s');
		$customerreference = $_POST['customerreference'];//customerreference is id for Sub-Dealer 
		$lastyearusagecheck = $_POST['lastyearusagecheck'];
		$usagecheck = explode("#",$lastyearusagecheck);
		$description = $_POST['description'];
		$prodescription = explode("$",$description);
		$purchasetype = $prodescription[2];
		
		if($prodescription[3] == "Single User")
		   $usatype = "singleuser";
		else
		   $usatype = "multiuser";   
		//$scratchcard = $prodescription[5];
		
		$purcheck = $_POST['purcheck'];
		$licensepurchase = $_POST['licensepurchase'];
		$yearcount = $_POST['yearcount'];
		$remarks = $_POST['remarks'];
		
		#added by samar
		
		    $cardlist_array = explode("^",$cardlist);
		    
		    foreach($cardlist_array as $cardid) {
		        
		           checkattchdetails($attacheddate,$customerreference,$description,$remarks,$cardid,$userid);
		    }
		    
		#ends
		
			$responsearray1 = array();
            $responsearray1['errorcode'] = "1";
        	$responsearray1['errormsg'] = "Card Attached Successfully.";
        	echo(json_encode($responsearray1));
		
		//checkattchdetails($attacheddate,$customerreference,$description,$remarks,$cardid,$userid);
	  }
	break;
	case 'newupdationchange':
	{
		//$customername = $_POST['customername'];
		$customerlicenseid = $_POST['customerid'];
		$card = $_POST['card'];
		
		$query0 = "select inv_mas_product.subgroup from inv_dealercard
		left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
		left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
		where inv_dealercard.cardid = ".$card;
		$fetch0 = runmysqlqueryfetch($query0);
		$subgroup = $fetch0['subgroup'];
		
		$currentyearquery = "select year from inv_mas_product where subgroup = '".$subgroup."' order by year desc limit 1;";
		$currentyearfetch = runmysqlqueryfetch($currentyearquery);
		$currentyear = $currentyearfetch['year'];
		
		//query for taking 	current year updation card count	
	    $newquery1 = "select count(inv_dealercard.purchasetype)as purchasetype from inv_dealercard
		left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
		left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
		where inv_dealercard.customerreference = ".$customerlicenseid."
		and inv_mas_product.subgroup = '".$subgroup."' and inv_mas_product.year = '".$currentyear."' 
		and inv_dealercard.purchasetype = 'updation' order by year desc";
		$newfetch1 = runmysqlqueryfetch($newquery1);
		//$newfetch1 = mysqli_fetch_array($newresult1);
	    echo $currentyearcard = $newfetch1['purchasetype'];
		
		//query for taking last two year 
	    $yearquery = "select distinct(year) from inv_mas_product where year!= '".$currentyear."' order by year desc limit 2;";
		$yearresult = runmysqlquery($yearquery);
		while($yearfetch = mysqli_fetch_array($yearresult))
		{
			$yearcount[] = $yearfetch['year'];
		}
		
		
		//query for taking last two year count 
		$totalcards = "";
		$querychange1 = "select inv_mas_product.year from inv_dealercard
		left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
		left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
		where inv_dealercard.customerreference = ".$customerlicenseid."
		and inv_mas_product.subgroup = '".$subgroup."' and inv_mas_product.year in 
		('".$yearcount[0]."','".$yearcount[1]."') order by inv_mas_product.year desc limit 1";
		$resultchange1 = runmysqlquery($querychange1);
		$count = mysqli_num_rows($resultchange1);
		//$lasttwoyearcount = $count;
		
		
		if($count == 1)
		{
			$fetchchange1 = mysqli_fetch_array($resultchange1);
			$lasttwoyear = $fetchchange1['year'];

			//query for taking  card count based on last two year count
		    $querychange2 = "select count(inv_dealercard.purchasetype) as purchasetype from inv_dealercard
			left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
			left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
			where inv_dealercard.customerreference = ".$customerlicenseid."
			and inv_mas_product.subgroup = '".$subgroup."' and inv_mas_product.year = '".$lasttwoyear."'";
			$fetchchange2 = runmysqlqueryfetch($querychange2);
			//$count2 = mysqli_num_rows($resultchange2);
			//$fetchchange2 = mysqli_fetch_array($resultchange2);
			$totalcards = $fetchchange2['purchasetype'];
			
		}
			
		$custprodetails['totalcards'] = $totalcards;
		$custprodetails['lasttwoyearcount'] = $count;
		$custprodetails['currentyearcard'] = $currentyearcard;
		$custprodetails['lastyearusagecheck'] = $subgroup."#".$lasttwoyear;
		
		echo(json_encode($custprodetails));
	}
	break;
	case 'generatecustomerlist':
	{
		$limit = $_POST['limit'];
		$startindex = $_POST['startindex'];
		$query = "select inv_mas_dealer.businessname, inv_mas_dealer.slno from inv_mas_dealer 
        where inv_mas_dealer.disablelogin = 'no' AND inv_mas_dealer.dealertypehead = '".$userid."' order by businessname LIMIT ".$startindex.",".$limit.";";
		$result = runmysqlquery($query);

		$customerarray = array();
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$customerarray[$count] = $fetch['businessname'].'^'.$fetch['slno'];
			$count++;
		}
		echo(json_encode($customerarray));
		
	}
	break;
	case 'getcustomercount':
	{
		$customerarraycount = array();
		$query = "select inv_mas_dealer.businessname, inv_mas_dealer.slno from inv_mas_dealer 
        where inv_mas_dealer.disablelogin = 'no' AND inv_mas_dealer.dealertypehead = '".$userid."';";
        $result = runmysqlquery($query);
        
		$count = mysqli_num_rows($result);
		$customerarraycount['count'] = $count;
		echo(json_encode($customerarraycount));
	}
	break;
	case 'generategrid':
	{
		$lastslno = $_POST['lastslno'];
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		
		/*$resultcount = "select inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber,inv_mas_dealer.businessname,inv_mas_product.productname,
		inv_dealercard.purchasetype,inv_dealercard.usagetype,inv_mas_scheme.schemename,inv_dealercard.cuscardattacheddate,inv_mas_users.fullname,
		inv_dealercard.cuscardremarks as remarks from inv_dealercard 
		left join inv_mas_scratchcard on inv_dealercard.cardid = inv_mas_scratchcard.cardid 
		left join inv_mas_dealer on inv_dealercard.dealerid = inv_mas_dealer.slno 
		left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
		left join inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme 
		left join inv_mas_users on inv_mas_users.slno = inv_dealercard.cuscardattachedby 
		where customerreference ='".$lastslno."' and inv_mas_dealer.slno = '".$userid."' order by inv_dealercard.cuscardattacheddate;";*/
		
		
		$resultcount = "select cardid,subdealer,remarks from dealer_pin_allot where subdealer = '$lastslno';";
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
		
/*		$query = "select inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber,inv_mas_dealer.businessname,inv_mas_product.productname,
		inv_dealercard.purchasetype,inv_dealercard.usagetype,inv_mas_scheme.schemename,inv_dealercard.cuscardattacheddate,inv_mas_users.fullname,
		inv_dealercard.cuscardremarks as remarks from inv_dealercard 
		left join inv_mas_scratchcard on inv_dealercard.cardid = inv_mas_scratchcard.cardid 
		left join inv_mas_dealer on inv_dealercard.dealerid = inv_mas_dealer.slno 
		left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
		left join inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme 
		left join inv_mas_users on inv_mas_users.slno = inv_dealercard.cuscardattachedby 
		where customerreference ='".$lastslno."' and inv_mas_dealer.slno = '".$userid."' order by inv_dealercard.cuscardattacheddate LIMIT ".$startlimit.",".$limit.";";*/
		
		//$query = "select cardid,subdealer,remarks from dealer_pin_allot where subdealer = '".$lastslno."' order by id desc LIMIT ".$startlimit.",".$limit.";";
/*		$query = "select dealer_pin_allot.cardid,inv_dealercard.purchasetype,inv_dealercard.usagetype,dealer_pin_allot.subdealer,
		dealer_pin_allot.remarks,inv_mas_dealer.businessname,inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber
		from dealer_pin_allot 
		left join inv_dealercard on inv_dealercard.cardid = dealer_pin_allot.cardid 
		left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = dealer_pin_allot.cardid 
		left join inv_mas_dealer on inv_mas_dealer.slno = dealer_pin_allot.subdealer 
		where dealer_pin_allot.subdealer = '".$lastslno."' order by dealer_pin_allot.cardid desc LIMIT ".$startlimit.",".$limit.";";*/
		
		$query = "select dealer_pin_allot.cardid,inv_dealercard.purchasetype,inv_dealercard.usagetype,dealer_pin_allot.subdealer,
		dealer_pin_allot.remarks,inv_mas_dealer.businessname,inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber,inv_mas_product.productname 
		from dealer_pin_allot 
		left join inv_dealercard on inv_dealercard.cardid = dealer_pin_allot.cardid 
		left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = dealer_pin_allot.cardid 
		left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
		left join inv_mas_dealer on inv_mas_dealer.slno = dealer_pin_allot.subdealer 
		where dealer_pin_allot.subdealer = '".$lastslno."' order by dealer_pin_allot.cardid desc LIMIT ".$startlimit.",".$limit.";";		
		
		if($startlimit == 0)
		{
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid" style="text-align:left">';
			$grid .= '<tr class="tr-grid-header">
			<td nowrap = "nowrap" class="td-border-grid">Sl No</td>
			<td nowrap = "nowrap" class="td-border-grid">PIN Number</td>
			<td nowrap = "nowrap" class="td-border-grid">Scratch Card</td>
			<td nowrap = "nowrap" class="td-border-grid">Product Name</td>
			<td nowrap = "nowrap" class="td-border-grid">Purchase Type</td>
			<td nowrap = "nowrap" class="td-border-grid">Usage Type</td>
			<td nowrap = "nowrap" class="td-border-grid">Dealer</td>
			<td nowrap = "nowrap" class="td-border-grid">Remarks</td>
			</tr>';
		}
		
		$i_n = 0;
		$result = runmysqlquery($query);
		$counter = 1;
		while($fetch = mysqli_fetch_array($result))
		{
			$i_n++;
			$slnocount++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$grid .= '<tr class="gridrow1" bgcolor='.$color.'>';
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$counter.".</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$fetch['cardid']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$fetch['scratchnumber']."</td>";	
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$fetch['productname']."</td>";			
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$fetch['purchasetype']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$fetch['usagetype']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$fetch['businessname']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$fetch['remarks']."</td>";			
			
/*			for($i = 0; $i < count($fetch); $i++)
			{
				if($i == 8)
				$grid .= "<td nowrap='nowrap' class='td-border-grid'>".changedateformatwithtime($fetch[$i])."</td>";
				else
				$grid .= "<td nowrap='nowrap' class='td-border-grid'>".gridtrim($fetch[$i])."</td>";
				
			}*/
			$grid .= "</tr>";
			$counter++;
		}
		$grid .= "</table>";
		if($slnocount >= $fetchresultcount)
			
		$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
		$linkgrid .= '<table><tr><td class="resendtext"><div align ="left" style="padding-right:10px"><a onclick ="generatemordatagrid(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer">Show More Records >></a><a onclick ="generatemordatagrid(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
		
		$responsegrid = array();
		$responsegrid['errorcode'] = "1";
		$responsegrid['grid'] = $grid;
		$responsegrid['fetchresultcount'] = $fetchresultcount;
		$responsegrid['linkgrid'] = $linkgrid;
		echo(json_encode($responsegrid));
	}
	break;
	case 'getcardlist':  #edited by samar for nagarsha
	{
		$limit = $_POST['limit'];
		$startindex = $_POST['startindex'];
		$query = "select inv_mas_scratchcard.cardid, inv_mas_scratchcard.scratchnumber,inv_dealercard.purchasetype,inv_dealercard.usagetype,inv_dealercard.productcode from inv_mas_scratchcard
		left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
		left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode
		left join dealer_pin_allot on inv_dealercard.cardid = dealer_pin_allot.cardid
		where attached = 'yes' and registered = 'no' and blocked = 'no' and (inv_dealercard.customerreference = '' 
		or inv_dealercard.customerreference is null) and inv_mas_product.newproduct = 1 
		and dealer_pin_allot.cardid IS NULL
		and inv_dealercard.dealerid = '".$userid."' 
		LIMIT ".$startindex.",".$limit;
		$result = runmysqlquery($query);
		$responsecardarray = array();
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$responsecardarray[$count] =  $fetch['scratchnumber'].' | '.$fetch['cardid'].'^'.$fetch['cardid'].'^'.$fetch['purchasetype'].'^'.$fetch['usagetype'].'^'.$fetch['productcode'];
			$count++;
		}
		echo(json_encode($responsecardarray));
	 }
	break;
	case 'filterdealerpins':
	{
		$productid = $_POST['productid'];
		
		//$startindex = $_POST['startindex'];
		
		$query = "select inv_mas_scratchcard.cardid, inv_mas_scratchcard.scratchnumber,inv_dealercard.purchasetype,inv_dealercard.productcode from inv_mas_scratchcard
		left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
		left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode
		where attached = 'yes' and registered = 'no' and blocked = 'no' and (inv_dealercard.customerreference = '' 
		or inv_dealercard.customerreference is null) and inv_mas_product.newproduct = 1 and inv_dealercard.productcode = '$productid'
		and inv_dealercard.dealerid = '".$userid."'";
		
		$result = runmysqlquery($query);
		$responsecardarray = array();
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
		    $counter++;
		    $responsecardarray[$count] =  $fetch['scratchnumber'].' | '.$fetch['cardid'].'^'.$fetch['cardid'].'^'.$fetch['purchasetype'].'^'.$fetch['productcode'];
			//$responsecardarray[$count] =  $fetch['scratchnumber'].' | '.$fetch['cardid'].'^'.$fetch['cardid'];
			$count++;
		}
		echo(json_encode($responsecardarray));
	 }
	break;
	case 'getcardcount': #edited by samar for nagarsha
	{
		$responsearray3 = array();
		
		$query = "select inv_mas_scratchcard.cardid, inv_mas_scratchcard.scratchnumber from inv_mas_scratchcard
		left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
		left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode
		left join dealer_pin_allot on inv_dealercard.cardid = dealer_pin_allot.cardid
		where attached = 'yes' and registered = 'no' and blocked = 'no' and (inv_dealercard.customerreference = '' 
		or inv_dealercard.customerreference is null) and inv_mas_product.newproduct = 1
		and dealer_pin_allot.cardid IS NULL
		and inv_dealercard.dealerid = '".$userid."' ORDER BY scratchnumber";
		$result = runmysqlquery($query);
		$count = mysqli_num_rows($result);
		$responsearray3['count'] = $count;
		echo(json_encode($responsearray3));
	}
	break;
	case 'scratchdetailstoform':
	{
		$cardid = $_POST['cardid'];
		$query = "SELECT distinct inv_dealercard.cardid,inv_mas_scratchcard.scratchnumber,inv_mas_scratchcard.blocked,inv_mas_scratchcard.cancelled,
		inv_mas_dealer.businessname as attachedto, inv_mas_dealer.slno as dealerid,inv_mas_product.productcode, inv_mas_product.productname, inv_dealercard.purchasetype,
		inv_dealercard.usagetype, inv_dealercard.date as attachdate,inv_dealercard.cuscardattacheddate as cuscardattacheddate, inv_mas_customer.businessname as registeredto,
		inv_dealercard.cuscardremarks as cuscardremarks from inv_dealercard 
		left join inv_mas_scratchcard on inv_dealercard.cardid = inv_mas_scratchcard.cardid 
		left join inv_mas_dealer on inv_dealercard.dealerid = inv_mas_dealer.slno 
		left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode 
		left join inv_mas_customer on inv_dealercard.customerreference = inv_mas_customer.slno
		where inv_dealercard.cardid = '".$cardid."';";
		$fetch = runmysqlqueryfetch($query);
		
		$attcheddate = substr($fetch['attachdate'] ,0,10);
		$registereddate =$fetch['registereddate'];
		if($registereddate != '')
			$registereddate = changedateformat($registereddate);
			
			
		if($fetch['blocked'] == 'yes')
		$cardstatus = 'Blocked';
		else if($fetch['cancelled'] == 'yes')
		$cardstatus = 'Cancelled';
		else
		{
		$cardstatus = 'Active';
		}
		
		$responsecardlist = array();
		$responsecardlist['cardid'] = $fetch['cardid'];
		$responsecardlist['scratchnumber'] = $fetch['scratchnumber'];
		$responsecardlist['purchasetype'] = $fetch['purchasetype'];
		$responsecardlist['usagetype'] = $fetch['usagetype'];
		$responsecardlist['attachedto'] = $fetch['attachedto'];
		$responsecardlist['dealerid'] = $fetch['dealerid'];
		$responsecardlist['productcode'] = $fetch['productcode'];
		$responsecardlist['productname'] = $fetch['productname'];
		$responsecardlist['attcheddate'] = changedateformat($attcheddate);
		$responsecardlist['registeredto'] = $fetch['registeredto'];
		$responsecardlist['cardstatus'] = $cardstatus;
		$responsecardlist['cuscardattacheddate'] = changedateformatwithtime($fetch['cuscardattacheddate']);
		$responsecardlist['cuscardremarks'] = $fetch['cuscardremarks'];
		
		#Added on Feb 03 2018
		//$subdelaer = $fetch['subdealer'];
		$select_subdealer = "select slno as subdealerslno,businessname as subdealername from inv_mas_dealer
		where slno = (select subdealer from dealer_pin_allot where cardid = '".$cardid."' order by id desc limit 1)";
		//$fetch = runmysqlqueryfetch($select_subdealer);
		
		$result = runmysqlquery($select_subdealer);
		$count = mysqli_num_rows($result);
		
		if($count > 0)
		{
		    $fetch = runmysqlqueryfetch($select_subdealer);
		    $responsecardlist['subdealer'] = $fetch['subdealerslno'];
		    $responsecardlist['subdealername'] = $fetch['subdealername'];
		}
		else {
		    $responsecardlist['subdealer'] = "";
		    $responsecardlist['subdealername'] = "";
		}
		#Added on Feb 03 2018
		
		//$responsecardlist['subdealer'] = $count;//$fetch['subdealerslno'];
		//$responsecardlist['subdealername'] = $count;//$fetch['subdealername'];
		
		echo(json_encode($responsecardlist));
	}
	break;
}

// added by bhavesh 
function generatecustomerid($customerreference,$productcode,$delaerrep)
{
	$query = "SELECT * FROM inv_mas_customer where slno = '".$customerreference."'";
	$fetch = runmysqlqueryfetch($query);
	$district = $fetch['district'];
	$query = runmysqlqueryfetch("SELECT distinct statecode from inv_mas_district where districtcode = '".$district."'");
	$cusstatecode = $query['statecode'];
	$newcustomerid = $cusstatecode.$district.$delaerrep.$productcode.$customerreference;
	return $newcustomerid;
}

//to be checked at feb 3
function checkattchdetails($attacheddate,$customerreference,$description,$remarks,$cardid,$userid)
{
    $query = "update inv_dealercard set sub_dealer = '".$customerreference."' where cardid = '".$cardid."' ";
	$result = runmysqlquery($query);

	$eventquery = "Insert into dealer_pin_allot (cardid,maindealer,subdealer,ip,remarks,createddate) 
	values('".$cardid."','".$userid."','".$customerreference."','".$_SERVER['REMOTE_ADDR']."','".$remarks."','".$attacheddate."')";
	$eventresult = runmysqlquery($eventquery);
	
/*	$responsearray1 = array();
    $responsearray1['errorcode'] = "1";
	$responsearray1['errormsg'] = "Card Attached Successfully.";
	echo(json_encode($responsearray1));*/
}

?>