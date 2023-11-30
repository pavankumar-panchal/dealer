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

$cardid = $_POST['cardid'];
$switchtype = $_POST['switchtype'];

switch($switchtype)
{
	case 'attachcard':
	{
		$attacheddate = datetimelocal('Y-m-d').' '.datetimelocal('H:i:s');
		$customerreference = $_POST['customerreference'];
		$remarks = $_POST['remarks'];
		
		$description = $_POST['description'];
		
		$query = "Update inv_dealercard set customerreference = '".$customerreference."',cuscardattacheddate = '".$attacheddate."',
		cuscardremarks = '".$remarks."', cuscardattachedby = '".$userid."', usertype = 'dealer' where cardid = '".$cardid."' ";
		$result = runmysqlquery($query);
		
		#Added on 06.03.2018
		
		$selected_sub_dealer = "";
		
		$select_sub_dealer = "select sub_dealer from inv_dealercard where cardid = '".$cardid."'";
		$result_sub_dealer = runmysqlquery($select_sub_dealer);
		
		while($fetch_sub_dealer = mysqli_fetch_array($result_sub_dealer)) {
		   $selected_sub_dealer =  $fetch_sub_dealer['sub_dealer'];
		}
		
		if(!is_null($selected_sub_dealer) && $selected_sub_dealer != '') {
        		$eventquery = "Insert into relyonso_imaxd_log.inv_logs_event (userid,system,eventtype,eventdatetime,remarks) 
        		values('".$selected_sub_dealer."','".$_SERVER['REMOTE_ADDR']."','119','".date('Y-m-d').' '.date('H:i:s')."','".$customerreference."')";
        		$eventresult = runmysqlquery_logs($eventquery);
        
            //insert into inv_invoicenumbers_dummy_regv2
    		$regvquery = "Insert into inv_invoicenumbers_dummy_regv2(dealerid,customerid,date,description,cardid) 
    		values('".$selected_sub_dealer."','".$customerreference."','".date('Y-m-d').' '.date('H:i:s')."','".$description."','".$cardid."')";
    		$eventresult = runmysqlquery($regvquery);
    		
    		#Added on 06.03.2018 Ends		
		} 
		
		sendfreecardemail($customerreference,$cardid);
		$responsearray1 = array();
		$responsearray1['errorcode'] = "1";
		$responsearray1['errormsg'] = "Card Attached Successfully.";
		
		echo(json_encode($responsearray1));
	
	}
	break;
	case 'generatecustomerlist':
	{
		$limit = $_POST['limit'];
		$startindex = $_POST['startindex'];
		$query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode,inv_mas_dealer.telecaller,inv_mas_dealer.branch,
		inv_mas_dealer.region,inv_mas_dealer.maindealers,inv_mas_dealer.dealerhead from inv_mas_dealer 
		left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
		where inv_mas_dealer.slno = '".$userid."';";
		$fetch = runmysqlqueryfetch($query);
		
		$relyonexecutive = $fetch['relyonexecutive'];
		$telecaller = $fetch['telecaller'];
		$branch = $fetch['branch'];
		$district = $fetch['district'];
		$state = $fetch['statecode'];
		$region = $fetch['region'];
		
		#Added on 22 Jan
		$maindealers = $fetch['maindealers'];
		$dealerhead = $fetch['dealerhead'];
		#Ends
		
		if($telecaller == 'yes')
		{
			$query = "select slno as slno, businessname as businessname from inv_mas_customer where branch in( select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."') order by businessname LIMIT ".$startindex.",".$limit.";";
			$result = runmysqlquery($query);
			if(mysqli_num_rows($result) == 0)
			{
				$query = "select slno as slno, businessname as businessname from inv_mas_customer where region = '2' order by businessname LIMIT ".$startindex.",".$limit.";";
				$result = runmysqlquery($query);
			}
		}
		else
		{
			if($relyonexecutive == 'no')
			{
				$query = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname  from inv_mas_customer where inv_mas_customer.currentdealer = '".$userid."' order by businessname LIMIT ".$startindex.",".$limit.";";
				$result = runmysqlquery($query);
			}
			else
			{
				if(($region == '1') || ($region == '3'))
				{
					$query = "select slno as slno, businessname as businessname from inv_mas_customer where (inv_mas_customer.region = '1' or inv_mas_customer.region = '3') order by businessname LIMIT ".$startindex.",".$limit.";";
					$result = runmysqlquery($query);
				}
				else
				{
					
					$query = "select slno as slno, businessname as businessname from inv_mas_customer where branch = '".$branch."' order by businessname LIMIT ".$startindex.",".$limit.";";
					$result = runmysqlquery($query);
				}
			}
		}
		
    	#Added on 22 Jan
		if($maindealers == 'yes')
		{
		   $query = "select slno as slno, businessname as businessname 
					from inv_mas_customer where inv_mas_customer.currentdealer IN (select slno from inv_mas_dealer where dealerhead = '".$userid."')
					OR inv_mas_customer.currentdealer = '".$userid."' order by businessname LIMIT ".$startindex.",".$limit.";";
					$result = runmysqlquery($query); 
		}
		#Ends
				
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
		$query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode,inv_mas_dealer.telecaller,inv_mas_dealer.branch,
		inv_mas_dealer.region,inv_mas_dealer.maindealers,inv_mas_dealer.dealerhead from inv_mas_dealer 
		left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
		where inv_mas_dealer.slno = '".$userid."';";
		$fetch = runmysqlqueryfetch($query);
		
		$relyonexecutive = $fetch['relyonexecutive'];
		$telecaller = $fetch['telecaller'];
		$branch = $fetch['branch'];
		$district = $fetch['district'];
		$state = $fetch['statecode'];
		$region = $fetch['region'];

        #Added on 22 Jan
		$maindealers = $fetch['maindealers'];
		$dealerhead = $fetch['dealerhead'];
		#Ends		
		
		if($telecaller == 'yes')
		{
			$query = "select slno as slno, businessname as businessname from inv_mas_customer where branch in( select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."') order by businessname;";
			$result = runmysqlquery($query);
			if(mysqli_num_rows($result) == 0)
			{
				$query = "select slno as slno, businessname as businessname from inv_mas_customer where region = '2' order by businessname;";
				$result = runmysqlquery($query);
			}
		}
		else
		{
			if($relyonexecutive == 'no')
			{
				$query = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname  from inv_mas_customer where inv_mas_customer.currentdealer = '".$userid."' order by businessname;";
				$result = runmysqlquery($query);
			}
			else
			{
				if(($region == '1') || ($region == '3'))
				{
					$query = "select slno as slno, businessname as businessname from inv_mas_customer where (inv_mas_customer.region = '1' or inv_mas_customer.region = '3') order by businessname;";
					$result = runmysqlquery($query);
				}
				else
				{
					
					$query = "select slno as slno, businessname as businessname from inv_mas_customer where branch = '".$branch."' order by businessname;";
					$result = runmysqlquery($query);
				}
			}
		}
		
		#Added on 22 Jan
		if($maindealers == 'yes')
		{
				$query = "select slno as slno, businessname as businessname from inv_mas_customer where 
				inv_mas_customer.currentdealer IN (select slno from inv_mas_dealer where dealerhead = '".$userid."') 
				OR inv_mas_customer.currentdealer = '".$userid."' order by businessname;";
				$result = runmysqlquery($query);
		}
		#Ends
		
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
		
		#Added on 06.03.2018
		
		$selected_Head = "";
		
		$select_dealerhead = "select dealerhead from inv_mas_dealer where slno = '".$userid."'";
		$result_dealerhead = runmysqlquery($select_dealerhead);
		
		while($fetch_head = mysqli_fetch_array($result_dealerhead)) {
		   $selected_Head =  $fetch_head['dealerhead'];
		}
		
		#Added on 06.03.2018 Ends
		
		if(!is_null($selected_Head) && $selected_Head != '') {	
		
    		$resultcount = "select inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber,inv_mas_dealer.businessname,inv_mas_product.productname,
    		inv_dealercard.purchasetype,inv_dealercard.usagetype,inv_mas_scheme.schemename,inv_dealercard.cuscardattacheddate,inv_mas_users.fullname,
    		inv_dealercard.cuscardremarks as remarks from inv_dealercard 
    		left join inv_mas_scratchcard on inv_dealercard.cardid =inv_mas_scratchcard.cardid 
    		left join inv_mas_dealer on inv_dealercard.sub_dealer = inv_mas_dealer.slno 
    		left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
    		left join inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme 
    		left join inv_mas_users on inv_mas_users.slno = inv_dealercard.cuscardattachedby 
    		where customerreference ='".$lastslno."' and 
    		inv_mas_dealer.slno = '".$userid."' order by inv_dealercard.cuscardattacheddate;";
		
		}
		else {
		    
		    $resultcount = "select inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber,inv_mas_dealer.businessname,inv_mas_product.productname,
    		inv_dealercard.purchasetype,inv_dealercard.usagetype,inv_mas_scheme.schemename,inv_dealercard.cuscardattacheddate,inv_mas_users.fullname,
    		inv_dealercard.cuscardremarks as remarks from inv_dealercard 
    		left join inv_mas_scratchcard on inv_dealercard.cardid =inv_mas_scratchcard.cardid 
    		left join inv_mas_dealer on inv_dealercard.dealerid = inv_mas_dealer.slno 
    		left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
    		left join inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme 
    		left join inv_mas_users on inv_mas_users.slno = inv_dealercard.cuscardattachedby 
    		where customerreference ='".$lastslno."' and 
    		inv_mas_dealer.slno = '".$userid."' order by inv_dealercard.cuscardattacheddate;";
		    
		}
		
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
		if(!is_null($selected_Head) && $selected_Head != '') {	
		    
    		$query = "select inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber,inv_mas_dealer.businessname,inv_mas_product.productname,
    		inv_dealercard.purchasetype,inv_dealercard.usagetype,inv_mas_scheme.schemename,inv_dealercard.cuscardattacheddate,inv_mas_users.fullname,
    		inv_dealercard.cuscardremarks as remarks from inv_dealercard 
    		left join inv_mas_scratchcard on inv_dealercard.cardid = inv_mas_scratchcard.cardid 
    		left join inv_mas_dealer on inv_dealercard.sub_dealer = inv_mas_dealer.slno 
    		left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
    		left join inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme 
    		left join inv_mas_users on inv_mas_users.slno = inv_dealercard.cuscardattachedby 
    		where customerreference ='".$lastslno."' and inv_mas_dealer.slno = '".$userid."' order by inv_dealercard.cuscardattacheddate LIMIT ".$startlimit.",".$limit.";";
		
		}
		else {
		    
    		$query = "select inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber,inv_mas_dealer.businessname,inv_mas_product.productname,
    		inv_dealercard.purchasetype,inv_dealercard.usagetype,inv_mas_scheme.schemename,inv_dealercard.cuscardattacheddate,inv_mas_users.fullname,
    		inv_dealercard.cuscardremarks as remarks from inv_dealercard 
    		left join inv_mas_scratchcard on inv_dealercard.cardid = inv_mas_scratchcard.cardid 
    		left join inv_mas_dealer on inv_dealercard.dealerid = inv_mas_dealer.slno 
    		left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
    		left join inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme 
    		left join inv_mas_users on inv_mas_users.slno = inv_dealercard.cuscardattachedby 
    		where customerreference ='".$lastslno."' and inv_mas_dealer.slno = '".$userid."' order by inv_dealercard.cuscardattacheddate LIMIT ".$startlimit.",".$limit.";";
		    
		}
		
		if($startlimit == 0)
		{
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid" style="text-align:left">';
			$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid">Sl No</td><td nowrap = "nowrap" class="td-border-grid">PIN Serial Number</td><td nowrap = "nowrap" class="td-border-grid">PIN Number</td><td nowrap = "nowrap" class="td-border-grid">Dealer</td><td nowrap = "nowrap" class="td-border-grid">product</td><td nowrap = "nowrap" class="td-border-grid">Usage Type</td><td nowrap = "nowrap" class="td-border-grid">Purchase Type</td><td nowrap = "nowrap" class="td-border-grid">Scheme</td><td nowrap = "nowrap" class="td-border-grid">Attached Date</td><td nowrap = "nowrap" class="td-border-grid">Attached By</td><td nowrap = "nowrap" class="td-border-grid">Remarks</td></tr>';
		}
		$i_n = 0;
		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_row($result))
		{
			$i_n++;$slnocount++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$grid .= '<tr class="gridrow1" bgcolor='.$color.'>';
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$slnocount."</td>";
			for($i = 0; $i < count($fetch); $i++)
			{
				if($i == 8)
				$grid .= "<td nowrap='nowrap' class='td-border-grid'>".changedateformatwithtime($fetch[$i])."</td>";
				else
				$grid .= "<td nowrap='nowrap' class='td-border-grid'>".gridtrim($fetch[$i])."</td>";
				
			}
			$grid .= "</tr>";
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
	case 'filtercardlist':
	{
	    $clickedProduct = $_POST['clickedProduct'];
	    
	    #Added on 06.03.2018
		
		$selected_Head = "";
		
		$select_dealerhead = "select dealerhead from inv_mas_dealer where slno = '".$userid."'";
		$result_dealerhead = runmysqlquery($select_dealerhead);
		
		while($fetch_head = mysqli_fetch_array($result_dealerhead)) {
		   $selected_Head =  $fetch_head['dealerhead'];
		}
		
		#Added on 06.03.2018 Ends
		if(!is_null($selected_Head) && $selected_Head != '') {
    		
    		$query = "SELECT inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber FROM inv_mas_scratchcard 
    		left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 
    		where inv_mas_scratchcard.attached = 'yes' and inv_mas_scratchcard.registered = 'no' 
    		and (inv_dealercard.customerreference = '' or inv_dealercard.customerreference is null) 
    		and inv_mas_scratchcard.blocked = 'no' and inv_dealercard.sub_dealer = '".$userid."' and inv_dealercard.productcode = '".$clickedProduct."'
    		ORDER BY scratchnumber";
		}
		else {
		    
		    $query = "SELECT inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber FROM inv_mas_scratchcard 
    		left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 
    		where inv_mas_scratchcard.attached = 'yes' and inv_mas_scratchcard.registered = 'no' 
    		and (inv_dealercard.customerreference = '' or inv_dealercard.customerreference is null) 
    		and inv_mas_scratchcard.blocked = 'no' and inv_dealercard.dealerid = '".$userid."' and inv_dealercard.productcode = '".$clickedProduct."'
    		ORDER BY scratchnumber";
		}
		
		//echo $query;
		
		$result = runmysqlquery($query);
		$responsecardarray = array();
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$responsecardarray[$count] =  $fetch['scratchnumber'].' | '.$fetch['cardid'].'^'.$fetch['cardid'];
			$count++;
		}
	    echo(json_encode($responsecardarray));		
	    
	}
	break;
	case 'getcardlist':
	{
		$limit = $_POST['limit'];
		$startindex = $_POST['startindex'];
		
		#Added on 06.03.2018
		
		$selected_Head = "";
		
		$select_dealerhead = "select dealerhead from inv_mas_dealer where slno = '".$userid."'";
		$result_dealerhead = runmysqlquery($select_dealerhead);
		
		while($fetch_head = mysqli_fetch_array($result_dealerhead)) {
		   $selected_Head =  $fetch_head['dealerhead'];
		}
		
		#Added on 06.03.2018 Ends
		
		if(!is_null($selected_Head) && $selected_Head != '') {
    		
    		$query = "SELECT inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber FROM inv_mas_scratchcard 
    		left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 
    		where inv_mas_scratchcard.attached = 'yes' and inv_mas_scratchcard.registered = 'no' 
    		and (inv_dealercard.customerreference = '' or inv_dealercard.customerreference is null) 
    		and inv_mas_scratchcard.blocked = 'no' and inv_dealercard.sub_dealer = '".$userid."' 
    		ORDER BY scratchnumber LIMIT ".$startindex.",".$limit." ";
		}
		else {
		    
		    $query = "SELECT inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber FROM inv_mas_scratchcard 
    		left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 
    		where inv_mas_scratchcard.attached = 'yes' and inv_mas_scratchcard.registered = 'no' 
    		and (inv_dealercard.customerreference = '' or inv_dealercard.customerreference is null) 
    		and inv_mas_scratchcard.blocked = 'no' and inv_dealercard.dealerid = '".$userid."' 
    		ORDER BY scratchnumber LIMIT ".$startindex.",".$limit." ";
		}
		
		//echo $query;
		
		$result = runmysqlquery($query);
		$responsecardarray = array();
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
    			$responsecardarray[$count] =  $fetch['scratchnumber'].' | '.$fetch['cardid'].'^'.$fetch['cardid'];
    			$count++;
		}
		echo(json_encode($responsecardarray));
		
	 }
	break;
	case 'getcardcount':
	{
		$responsearray3 = array();
		
		#Added on 06.03.2018
		
		$selected_Head = "";
		
		$select_dealerhead = "select dealerhead from inv_mas_dealer where slno = '".$userid."'";
		$result_dealerhead = runmysqlquery($select_dealerhead);
		
		while($fetch_head = mysqli_fetch_array($result_dealerhead)) {
		   $selected_Head =  $fetch_head['dealerhead'];
		}
		
		#Added on 06.03.2018 Ends
		
		if(!is_null($selected_Head) && $selected_Head != '') {		
		
		$query = "SELECT inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber 
		FROM inv_mas_scratchcard 
		left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid where 
		inv_mas_scratchcard.attached = 'yes' and inv_mas_scratchcard.registered = 'no' and 
		(inv_dealercard.customerreference = '' or inv_dealercard.customerreference is null) 
		and inv_mas_scratchcard.blocked = 'no' and inv_dealercard.sub_dealer = '".$userid."' ORDER BY scratchnumber";
		
		}
		else {
		   
		$query = "SELECT inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber 
		FROM inv_mas_scratchcard 
		left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid where 
		inv_mas_scratchcard.attached = 'yes' and inv_mas_scratchcard.registered = 'no' and 
		(inv_dealercard.customerreference = '' or inv_dealercard.customerreference is null) 
		and inv_mas_scratchcard.blocked = 'no' and inv_dealercard.dealerid = '".$userid."' ORDER BY scratchnumber";  
		    
		}
		
		$result = runmysqlquery($query);
		
		$count = mysqli_num_rows($result);
		$responsearray3['count'] = $count;
		echo(json_encode($responsearray3));
	}
	break;
	case 'scratchdetailstoform':
	{
		$cardid = $_POST['cardid'];
		
		#Added on 06.03.2018
		
		$selected_sub_dealer = "";
		
		$select_sub_dealer = "select sub_dealer from inv_dealercard where cardid = '".$cardid."'";
		$result_sub_dealer = runmysqlquery($select_sub_dealer);
		
		while($fetch_sub_dealer = mysqli_fetch_array($result_sub_dealer)) {
		   $selected_sub_dealer =  $fetch_sub_dealer['sub_dealer'];
		}
		
		#Added on 06.03.2018 Ends
		
		if(!is_null($selected_sub_dealer) && $selected_sub_dealer != '') {	
		
		$query = "SELECT distinct inv_dealercard.cardid , inv_mas_scratchcard.scratchnumber, inv_mas_scratchcard.blocked,inv_mas_scratchcard.cancelled,
		inv_mas_dealer.businessname as attachedto, inv_mas_dealer.slno as dealerid, inv_mas_product.productcode, inv_mas_product.productname,inv_dealercard.purchasetype,
		inv_dealercard.usagetype,inv_dealercard.date as attachdate,inv_dealercard.cuscardattacheddate as cuscardattacheddate, inv_mas_customer.businessname as registeredto,
		inv_dealercard.cuscardremarks as cuscardremarks 
		from inv_dealercard 
		left join inv_mas_scratchcard on inv_dealercard.cardid = inv_mas_scratchcard.cardid 
		left join inv_mas_dealer on inv_dealercard.sub_dealer = inv_mas_dealer.slno 
		left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode 
		left join inv_mas_customer on  inv_dealercard.customerreference = inv_mas_customer.slno 
		where inv_dealercard.cardid = '".$cardid."';";
		
		}
		else {
		    
		$query = "SELECT distinct inv_dealercard.cardid , inv_mas_scratchcard.scratchnumber, inv_mas_scratchcard.blocked,inv_mas_scratchcard.cancelled,
		inv_mas_dealer.businessname as attachedto, inv_mas_dealer.slno as dealerid, inv_mas_product.productcode, inv_mas_product.productname,inv_dealercard.purchasetype,
		inv_dealercard.usagetype,inv_dealercard.date as attachdate,inv_dealercard.cuscardattacheddate as cuscardattacheddate, inv_mas_customer.businessname as registeredto,
		inv_dealercard.cuscardremarks as cuscardremarks 
		from inv_dealercard 
		left join inv_mas_scratchcard on inv_dealercard.cardid = inv_mas_scratchcard.cardid 
		left join inv_mas_dealer on inv_dealercard.dealerid = inv_mas_dealer.slno 
		left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode 
		left join inv_mas_customer on  inv_dealercard.customerreference = inv_mas_customer.slno 
		where inv_dealercard.cardid = '".$cardid."';";
		    
		}
		
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
		$responsecardlist['attachedto'] = changedateformat($attcheddate);
		$responsecardlist['dealerid'] = $fetch['dealerid'];
		$responsecardlist['productcode'] = $fetch['productcode'];
		$responsecardlist['productname'] = $fetch['productname'];
		$responsecardlist['attcheddate'] = $fetch['attcheddate'];
		$responsecardlist['registeredto'] = $fetch['registeredto'];
		$responsecardlist['cardstatus'] = $cardstatus;
		$responsecardlist['cuscardattacheddate'] = changedateformatwithtime($fetch['cuscardattacheddate']);
		$responsecardlist['cuscardremarks'] = $fetch['cuscardremarks'];
		
		echo(json_encode($responsecardlist));
		
		//echo($fetch['cardid'].'^'.$fetch['scratchnumber'].'^'.$fetch['purchasetype'].'^'.$fetch['usagetype'].'^'.$fetch['attachedto'].'^'.$fetch['dealerid'].'^'.$fetch['productcode'].'^'.$fetch['productname'].'^'.changedateformat($attcheddate).'^'.''.'^'.''.'^'.$fetch['registeredto'].'^'.$cardstatus.'^'.changedateformatwithtime($fetch['cuscardattacheddate']).'^'.$fetch['cuscardremarks']);
	}
	break;
}

function sendfreecardemail($customerreference,$cardid)
{
	#Added on 06.03.2018
		
		$selected_sub_dealer = "";
		
		$select_sub_dealer = "select sub_dealer from inv_dealercard where cardid = '".$cardid."'";
		$result_sub_dealer = runmysqlquery($select_sub_dealer);
		
		while($fetch_sub_dealer = mysqli_fetch_array($result_sub_dealer)) {
		   $selected_sub_dealer =  $fetch_sub_dealer['sub_dealer'];
		}
		
		#Added on 06.03.2018 Ends
		
		if(!is_null($selected_sub_dealer) && $selected_sub_dealer != '') {
		    
		$query5 = "select inv_mas_customer.businessname,inv_mas_customer.customerid,inv_mas_customer.place,inv_mas_customer.slno,inv_mas_product.productname,
		inv_mas_scratchcard.scratchnumber as pinno,inv_mas_dealer.businessname as dealername from inv_dealercard 
		left join inv_mas_scratchcard on inv_dealercard.cardid = inv_mas_scratchcard.cardid
		left join inv_mas_customer on inv_mas_customer.slno = inv_dealercard.customerreference 
		left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
		left join inv_mas_dealer on inv_mas_dealer.slno = inv_dealercard.sub_dealer 
		where inv_dealercard.customerreference = '".$customerreference."' and inv_dealercard.cardid = '".$cardid."';";
		
		}
		else {
		    
		$query5 = "select inv_mas_customer.businessname,inv_mas_customer.customerid,inv_mas_customer.place,inv_mas_customer.slno,inv_mas_product.productname,
		inv_mas_scratchcard.scratchnumber as pinno,inv_mas_dealer.businessname as dealername from inv_dealercard 
		left join inv_mas_scratchcard on inv_dealercard.cardid = inv_mas_scratchcard.cardid
		left join inv_mas_customer on inv_mas_customer.slno = inv_dealercard.customerreference 
		left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
		left join inv_mas_dealer on inv_mas_dealer.slno = inv_dealercard.dealerid 
		where inv_dealercard.customerreference = '".$customerreference."' and inv_dealercard.cardid = '".$cardid."';";
		    
		}
		
	    $result = runmysqlqueryfetch($query5);
	
	// Fetch Contact Details
	$query1 ="SELECT slno,customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactdetails where customerid = '".$result['slno']."'; ";
	$resultfetch = runmysqlquery($query1);
	$valuecount = 0;
	while($fetchres = mysqli_fetch_array($resultfetch))
	{
		$contactperson = $fetchres['contactperson'];
		$emailid = $fetchres['emailid'];
		$contactvalues .= $contactperson;
		$contactvalues .= appendcomma($contactperson);
		$emailidres .= $emailid;
		$emailidres .= appendcomma($emailid);
		
	}
	$date = datetimelocal('d-m-Y');
	$businessname = $result['businessname'];
	$contactperson = trim($contactvalues,',');
	$customerslno = $result['slno'];
	$place = $result['place'];
	$customerid = $result['customerid'];
	$productname = $result['productname'];
	$pinno = $result['pinno'];
	$dealername = $result['dealername'];
	$emailid = trim($emailidres,',');
	
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab"))
		$emailid = 'archana.ab@relyonsoft.com';
	else
		$emailid = $emailid;
	
	$emailarray = explode(',',$emailid);
	$emailcount = count($emailarray);
	
	for($i = 0; $i < $emailcount; $i++)
	{
		if(checkemailaddress($emailarray[$i]))
		{
				$emailids[$emailarray[$i]] = $emailarray[$i];
		}
	}
	
	$toarray = $emailids;
	$fromname = "Relyon";
	$fromemail = "imax@relyon.co.in";
	require_once("../inc/RSLMAIL_MAIL.php");
	$msg = file_get_contents("../mailcontents/manualcuscardattach.htm");
	$textmsg = file_get_contents("../mailcontents/manualcuscardattach.txt");
	$date = datetimelocal('d-m-Y');
	$array = array();
	$array[] = "##DATE##%^%".$date;
	$array[] = "##NAME##%^%".$contactperson;
	$array[] = "##COMPANY##%^%".$businessname;
	$array[] = "##PLACE##%^%".$place;
	$array[] = "##CUSTOMERID##%^%".cusidcombine($customerid);
	$array[] = "##PRODUCTNAME##%^%".$productname;
	$array[] = "##SCRATCHCARDNO##%^%".$pinno;
	$array[] = "##CARDID##%^%".$cardid;
	$array[] = "##DEALERNAME##%^%".$dealername;
	$array[] = "##EMAILID##%^%".$emailid;
	
	$filearray = array(
		array('../images/relyon-logo.jpg','inline','8888888888'),
	);
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab"))
	{
		$bccemailids['rashmi'] ='rashmi.hk@relyonsoft.com';
	}
	else
	{
		$bccemailids['Relyonimax'] ='relyonimax@gmail.com';
		$bccemailids['bigmail'] ='bigmail@relyonsoft.com';
	}
	//$bccemailids['bigmail'] ='meghana.b@relyonsoft.com';
	$bccarray = $bccemailids;
	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$subject = "You have been issued with a PIN Number for ".$productname." registration.";
	$html = $msg;
	$text = $textmsg;
	$replyto = 'support@relyonsoft.com';
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,$bccarray,$filearray,$replyto); 
	
	//Insert the mail forwarded details to the logs table
	inserttologs(imaxgetcookie('dealeruserid'),$customerslno,$fromname,$fromemail,$emailid,null,$bccmailid ,$subject);	
}

?>