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

switch($switchtype)
{
	case 'attachcard':
	{
		$attacheddate = datetimelocal('Y-m-d').' '.datetimelocal('H:i:s');
		$customerreference = $_POST['customerreference'];
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
		//exit;
		//if($proname!= "" && $descriptiontwo == "updation")
		//{
			if($purchasetype == "updation" && ($usagecheck[0]!="SBSN" && $usagecheck[0]!="SBSN" && $usagecheck[0]!="SASN" && $usagecheck[0]!="SAPN" && $usagecheck[0]!="SBEN"))
			{   	
				if($yearcount == 1)
				{
					if($purcheck < $licensepurchase)
					{
					
					    $query5 = "select usagetype,count(usagetype) as usagecount from inv_dealercard
						left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
                        where inv_dealercard.customerreference = ".$customerreference." and inv_mas_product.year  = '".$usagecheck[1]."'
						and inv_mas_product.subgroup  = '".$usagecheck[0]."' and inv_dealercard.usagetype = '".$usatype."' 
						group by inv_dealercard.usagetype";
						$result5 = runmysqlquery($query5);
						$count5 = mysqli_num_rows($result5);
						
						if($count5 > 0)
						{	
						    
						$fetch5 = runmysqlqueryfetch($query5);
						
						$query6 = "select usagetype from inv_dealercard 
						left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
						where inv_dealercard.customerreference = ".$customerreference." and 
						inv_mas_product.productname  = '".$proname."' and inv_dealercard.usagetype = '".$usatype."' ;";
						
						$result6 = runmysqlquery($query6);
						$count6 = mysqli_num_rows($result6);
							
							if($count6 < $fetch5['usagecount'])
							{	
								checkattchdetails($attacheddate,$customerreference,$description,$remarks,$cardid,$userid);
							}
							else
							{ 
								$responsearray1['errorcode'] = "1";
								$responsearray1['errormsg'] = "Not eligible to take Updation ".$prodescription[3]." card.";
								echo(json_encode($responsearray1));
							}
						}
						else
						{
							$responsearray1['errorcode'] = "1";
							$responsearray1['errormsg'] = "Not eligible to take Updation ".$prodescription[3]." card.";
							echo(json_encode($responsearray1));
						}
							
					    
					}
					else
					{	
						$responsearray1['errorcode'] = "1";
						$responsearray1['errormsg'] = "Customer has zero updation card.";
						echo(json_encode($responsearray1));
					}
				}
				else
				{	
					$responsearray1['errorcode'] = "1";
					$responsearray1['errormsg'] = "Customer has not taken card from last two year.";
					echo(json_encode($responsearray1));
				}
			}
			else
			{	
				checkattchdetails($attacheddate,$customerreference,$description,$remarks,$cardid,$userid);
			}
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
		//$fetch0 = mysqli_fetch_array($result0);
		$subgroup = $fetch0['subgroup'];
		
		$currentyearquery = "select year from inv_mas_product where subgroup = '".$subgroup."' order by year desc limit 1;";
		$currentyearfetch = runmysqlqueryfetch($currentyearquery);
		//$currentyearfetch = mysqli_fetch_array($currentyearresult);
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
		$currentyearcard = $newfetch1['purchasetype'];
		
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
					from inv_mas_customer where inv_mas_customer.currentdealer IN (select slno from inv_mas_dealer where dealertypehead = '".$userid."')
					OR inv_mas_customer.currentdealer = '".$userid."' order by businessname LIMIT ".$startindex.",".$limit.";";
					$result = runmysqlquery($query); 
		}
		#Ends
		
		//$query;
		
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
				inv_mas_customer.currentdealer IN (select slno from inv_mas_dealer where dealertypehead = '".$userid."') 
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
		
		$select_dealerhead = "select dealertypehead as dealerhead from inv_mas_dealer where slno = '".$userid."'";
		$result_dealerhead = runmysqlquery($select_dealerhead);
		
		while($fetch_head = mysqli_fetch_array($result_dealerhead)) {
		   $selected_Head =  $fetch_head['dealerhead'];
		}
		
		#Added on 06.03.2018 Ends
		
		if(!is_null($selected_Head) && $selected_Head != '') {		
		
		$resultcount = "select inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber,inv_mas_dealer.businessname,inv_mas_product.productname,
		inv_dealercard.purchasetype,inv_dealercard.usagetype,inv_mas_scheme.schemename,inv_dealercard.cuscardattacheddate,inv_mas_users.fullname,
		inv_dealercard.cuscardremarks as remarks from inv_dealercard 
		left join inv_mas_scratchcard on inv_dealercard.cardid = inv_mas_scratchcard.cardid 
		left join inv_mas_dealer on inv_dealercard.sub_dealer = inv_mas_dealer.slno 
		left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
		left join inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme 
		left join inv_mas_users on inv_mas_users.slno = inv_dealercard.cuscardattachedby 
		where customerreference ='".$lastslno."' and inv_mas_dealer.slno = '".$userid."' 
		order by inv_dealercard.cuscardattacheddate;";
		
		}
		else {
		    
    		$resultcount = "select inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber,inv_mas_dealer.businessname,inv_mas_product.productname,
    		inv_dealercard.purchasetype,inv_dealercard.usagetype,inv_mas_scheme.schemename,inv_dealercard.cuscardattacheddate,inv_mas_users.fullname,
    		inv_dealercard.cuscardremarks as remarks from inv_dealercard 
    		left join inv_mas_scratchcard on inv_dealercard.cardid = inv_mas_scratchcard.cardid 
    		left join inv_mas_dealer on inv_dealercard.dealerid = inv_mas_dealer.slno 
    		left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
    		left join inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme 
    		left join inv_mas_users on inv_mas_users.slno = inv_dealercard.cuscardattachedby 
    		where customerreference ='".$lastslno."' and inv_mas_dealer.slno = '".$userid."' 
    		order by inv_dealercard.cuscardattacheddate;";		    
		    
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
		    
    		$query = "select inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber,inv_mas_dealer.businessname,inv_mas_product.productname,inv_dealercard.purchasetype,
    		inv_dealercard.usagetype,inv_mas_scheme.schemename,inv_dealercard.cuscardattacheddate,inv_mas_users.fullname,inv_dealercard.cuscardremarks as remarks 
    		from inv_dealercard left join inv_mas_scratchcard on inv_dealercard.cardid =inv_mas_scratchcard.cardid 
    		left join inv_mas_dealer on inv_dealercard.sub_dealer = inv_mas_dealer.slno 
    		left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
    		left join inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme 
    		left join inv_mas_users on inv_mas_users.slno = inv_dealercard.cuscardattachedby 
    		where customerreference ='".$lastslno."' and inv_mas_dealer.slno = '".$userid."' 
    		order by inv_dealercard.cuscardattacheddate LIMIT ".$startlimit.",".$limit.";";
		
		}
		else {
		    
    		$query = "select inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber,inv_mas_dealer.businessname,inv_mas_product.productname,inv_dealercard.purchasetype,
    		inv_dealercard.usagetype,inv_mas_scheme.schemename,inv_dealercard.cuscardattacheddate,inv_mas_users.fullname,inv_dealercard.cuscardremarks as remarks 
    		from inv_dealercard left join inv_mas_scratchcard on inv_dealercard.cardid =inv_mas_scratchcard.cardid 
    		left join inv_mas_dealer on inv_dealercard.dealerid = inv_mas_dealer.slno 
    		left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
    		left join inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme 
    		left join inv_mas_users on inv_mas_users.slno = inv_dealercard.cuscardattachedby 
    		where customerreference ='".$lastslno."' and inv_mas_dealer.slno = '".$userid."' 
    		order by inv_dealercard.cuscardattacheddate LIMIT ".$startlimit.",".$limit.";";		    
		    
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
	case 'getcardlist':
	{
		$limit = $_POST['limit'];
		$startindex = $_POST['startindex'];
		$pin = $_POST['pin'];
		$pinpiece = ($pin!="") ? " and inv_dealercard.productcode ='".$pin."'" : '';

		$purtype = $_POST['purtype'];
		$purtypepiece = ($purtype!="") ? " and inv_dealercard.purchasetype ='".$purtype."'" : '';
		
		#Added on 06.03.2018
		
		$selected_Head = "";
		
		$select_dealerhead = "select dealertypehead as dealerhead from inv_mas_dealer where slno = '".$userid."'";
		$result_dealerhead = runmysqlquery($select_dealerhead);
		
		while($fetch_head = mysqli_fetch_array($result_dealerhead)) {
		   $selected_Head =  $fetch_head['dealerhead'];
		}
		
		#Added on 06.03.2018 Ends
		
		if(!is_null($selected_Head) && $selected_Head != '') {			
		
		$query = "select inv_mas_scratchcard.cardid, inv_mas_scratchcard.scratchnumber from inv_mas_scratchcard
		left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
		left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode
		where attached = 'yes' and registered = 'no' and blocked = 'no' and (inv_dealercard.customerreference = '' 
		or inv_dealercard.customerreference is null) and inv_mas_product.newproduct = 1
		and (inv_dealercard.sub_dealer = '$userid' OR inv_dealercard.dealerid = '$userid') ".$pinpiece.$purtypepiece." LIMIT ".$startindex.",".$limit;
		
		}
		else {
		    
		$query = "select inv_mas_scratchcard.cardid, inv_mas_scratchcard.scratchnumber from inv_mas_scratchcard
		left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
		left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode
		where attached = 'yes' and registered = 'no' and blocked = 'no' and (inv_dealercard.customerreference = '' 
		or inv_dealercard.customerreference is null) and inv_mas_product.newproduct = 1
		and inv_dealercard.dealerid = '".$userid."' ".$pinpiece.$purtypepiece." LIMIT ".$startindex.",".$limit;
		
		}
		
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
		$pin = $_POST['pin'];
		$pinpiece = ($pin!="") ? " and inv_dealercard.productcode ='".$pin."'" : '';

		$purtype = $_POST['purtype'];
		$purtypepiece = ($purtype!="") ? " and inv_dealercard.purchasetype ='".$purtype."'" : '';
		
		$select_dealerhead = "select dealertypehead as dealerhead from inv_mas_dealer where slno = '".$userid."'";
		$result_dealerhead = runmysqlquery($select_dealerhead);
		
		while($fetch_head = mysqli_fetch_array($result_dealerhead)) {
		   $selected_Head =  $fetch_head['dealerhead'];
		}
		
		#Added on 06.03.2018 Ends
		
		if(!is_null($selected_Head) && $selected_Head != '') {	
		
		$query = "select inv_mas_scratchcard.cardid, inv_mas_scratchcard.scratchnumber from inv_mas_scratchcard
		left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
		left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode
		where attached = 'yes' and registered = 'no' and blocked = 'no' and (inv_dealercard.customerreference = '' 
		or inv_dealercard.customerreference is null) and inv_mas_product.newproduct = 1
		and (inv_dealercard.sub_dealer = '$userid' OR inv_dealercard.dealerid = '$userid') ".$pinpiece.$purtypepiece."  ORDER BY scratchnumber ";
		
		}
		else {
		    
		$query = "select inv_mas_scratchcard.cardid, inv_mas_scratchcard.scratchnumber from inv_mas_scratchcard
		left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
		left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode
		where attached = 'yes' and registered = 'no' and blocked = 'no' and (inv_dealercard.customerreference = '' 
		or inv_dealercard.customerreference is null) and inv_mas_product.newproduct = 1
		and inv_dealercard.dealerid = '".$userid."' ".$pinpiece.$purtypepiece." ORDER BY scratchnumber ";
		    
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
		    
		$query = "SELECT distinct inv_dealercard.cardid , inv_mas_scratchcard.scratchnumber,inv_mas_scratchcard.blocked,inv_mas_scratchcard.cancelled,
		inv_mas_dealer.businessname as attachedto,inv_mas_dealer.slno as dealerid,inv_mas_product.productcode, inv_mas_product.productname,inv_dealercard.purchasetype,
		inv_dealercard.usagetype, inv_dealercard.date as attachdate,inv_dealercard.cuscardattacheddate as cuscardattacheddate,inv_mas_customer.businessname as registeredto,
		inv_dealercard.cuscardremarks as cuscardremarks from inv_dealercard left join inv_mas_scratchcard on inv_dealercard.cardid = inv_mas_scratchcard.cardid 
		left join inv_mas_dealer on inv_dealercard.sub_dealer = inv_mas_dealer.slno left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode 
        	left join inv_mas_customer on inv_dealercard.customerreference = inv_mas_customer.slno where inv_dealercard.cardid = '".$cardid."';";
		
		}
		else {
		    
		$query = "SELECT distinct inv_dealercard.cardid , inv_mas_scratchcard.scratchnumber,inv_mas_scratchcard.blocked,inv_mas_scratchcard.cancelled,
		inv_mas_dealer.businessname as attachedto,inv_mas_dealer.slno as dealerid,inv_mas_product.productcode, inv_mas_product.productname,inv_dealercard.purchasetype,
		inv_dealercard.usagetype, inv_dealercard.date as attachdate,inv_dealercard.cuscardattacheddate as cuscardattacheddate,inv_mas_customer.businessname as registeredto,
		inv_dealercard.cuscardremarks as cuscardremarks from inv_dealercard left join inv_mas_scratchcard on inv_dealercard.cardid = inv_mas_scratchcard.cardid 
		left join inv_mas_dealer on inv_dealercard.dealerid = inv_mas_dealer.slno left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode 
        	left join inv_mas_customer on inv_dealercard.customerreference = inv_mas_customer.slno where inv_dealercard.cardid = '".$cardid."';";		    
		    
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
		$responsecardlist['attachedto'] = $fetch['attachedto'];
		$responsecardlist['dealerid'] = $fetch['dealerid'];
		$responsecardlist['productcode'] = $fetch['productcode'];
		$responsecardlist['productname'] = $fetch['productname'];
		$responsecardlist['attcheddate'] = changedateformat($attcheddate);
		$responsecardlist['registeredto'] = $fetch['registeredto'];
		$responsecardlist['cardstatus'] = $cardstatus;
		$responsecardlist['cuscardattacheddate'] = changedateformatwithtime($fetch['cuscardattacheddate']);
		$responsecardlist['cuscardremarks'] = $fetch['cuscardremarks'];
		
		echo(json_encode($responsecardlist));
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
	
	if(($_SERVER['HTTP_HOST'] == "bhumika") || ($_SERVER['HTTP_HOST'] == "192.168.2.79"))
	{
		$emailid = 'bhumika.p@relyonsoft.com';
	}
	else
	{
		$emailid = $emailid;
	}
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
	$msg = file_get_contents("../mailcontents/dealerpincardattach.htm");
	$textmsg = file_get_contents("../mailcontents/dealerpincardattach.txt");
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
	if(($_SERVER['HTTP_HOST'] == "bhumika") || ($_SERVER['HTTP_HOST'] == "192.168.2.79"))
	{
		$bccemailids['bhumika'] ='bhumika.p@relyonsoft.com';
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

function checkattchdetails($attacheddate,$customerreference,$description,$remarks,$cardid,$userid)
{
	$query2 = "select * from inv_mas_customer where slno = '".$customerreference."'";
	$fetch2 = runmysqlqueryfetch($query2);
	$custidno = cusidcombine($fetch2['customerid']);
	
	#Added on 06.03.2018
		
		$selected_sub_dealer = "";
		
		$select_sub_dealer = "select sub_dealer from inv_dealercard where cardid = '".$cardid."'";
		$result_sub_dealer = runmysqlquery($select_sub_dealer);
		
		while($fetch_sub_dealer = mysqli_fetch_array($result_sub_dealer)) {
		   $selected_sub_dealer =  $fetch_sub_dealer['sub_dealer'];
		}
		
	#Added on 06.03.2018 Ends
	
	
	if($custidno == "")
	{
		//Generate Customer ID then Update the Inv_dealercard and insert into inv_invoicenumbers_dummy_regv2
		
		//generate customerid, update it to customer master and send welcome email
			
	/*$query22 = "Select * from inv_mas_customer where slno ='".$customerreference."' and customerid = ''; ";
	$fetchresult = runmysqlquery($query22);
	if(mysqli_num_rows($fetchresult) <> 0)
	//{*/
		//$query23 = "Select productcode, cardid, dealerid from inv_dealercard where cardid ='".$cardid."' and dealerid = '".$userid."'; ";
		if(!empty($selected_sub_dealer))
		{
		$query23 = "Select productcode, cardid, dealerid from inv_dealercard where cardid ='".$cardid."' and sub_dealer = '".$userid."'; ";
		}
		else
		{
		$query23 = "Select productcode, cardid, dealerid from inv_dealercard where cardid ='".$cardid."' and dealerid = '".$userid."'; ";		
		}
		
		$fetch23 = runmysqlqueryfetch($query23);
		$delaerrep = $fetch23['dealerid'];
		$productcode = $fetch23['productcode'];
		
		$newcustomerid = generatecustomerid($customerreference,$productcode,$delaerrep);
		$password = generatepwd();
		
		// updating new customerid
		$query14 = "UPDATE inv_mas_customer SET customerid = '".$newcustomerid."',loginpassword = AES_ENCRYPT('".$password."','imaxpasswordkey'),initialpassword = '".$password."', firstproduct ='".$productcode."',firstdealer ='".$delaerrep."',passwordchanged = 'N' WHERE slno = '".$customerreference."'";
		$result = runmysqlquery($query14); 
		
		//inserting in log
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','42','".date('Y-m-d').' '.date('H:i:s')."','".$customerreference."')";
		$eventresult = runmysqlquery($eventquery);
		$sendcustomeridpassword = cusidcombine($newcustomerid)."%".$password;
		$custidno = cusidcombine($newcustomerid);
		
		// Updating into dealercard
		$query = "Update inv_dealercard set customerreference = '".$customerreference."' ,cuscardattacheddate = '".$attacheddate."' ,cuscardremarks = '".$remarks."' ,cuscardattachedby = '".$userid."', usertype = 'dealer' where cardid = '".$cardid."' ";
		$result = runmysqlquery($query);
		
		//Fetching deatils from multiple table to insert into description (format slno$PN$PT$UT$PIN$CRDID$amt)
	
		
		//insert into inv_invoicenumbers_dummy_regv2
		$regvquery = "Insert into inv_invoicenumbers_dummy_regv2(dealerid,customerid,date,description,cardid) values('".$userid."','".$custidno."','".date('Y-m-d').' '.date('H:i:s')."','".$description."','".$cardid."')";
		
		if(!is_null($selected_sub_dealer) && $selected_sub_dealer != '') {
    		//insert into inv_invoicenumbers_dummy_regv2
    		$regvquery = "Insert into inv_invoicenumbers_dummy_regv2(dealerid,customerid,date,description,cardid) 
    		values('".$selected_sub_dealer."','".$custidno."','".date('Y-m-d').' '.date('H:i:s')."','".$description."','".$cardid."')";
		} 	
				
		$eventresult = runmysqlquery($regvquery);
		
		// inserting Log
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','119','".date('Y-m-d').' '.date('H:i:s')."','".$customerreference."')";
		
		if(!is_null($selected_sub_dealer) && $selected_sub_dealer != '') {
    		// inserting Log
    		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) 
    		values ('".$selected_sub_dealer."','".$_SERVER['REMOTE_ADDR']."','119','".date('Y-m-d').' '.date('H:i:s')."','".$customerreference."')"; 
		}  
				
		$eventresult = runmysqlquery($eventquery);
		
		$responsearray1 = array();
		$responsearray1['errorcode'] = "1";
		$responsearray1['errormsg'] = "Card Attached Successfully.";
		
		//sending mail 
		$customerslno = $customerreference;
		sendwelcomeemail($customerslno,$userid);
		sendfreecardemail($customerreference,$cardid);
		
	}
	else
	{
		$query = "Update inv_dealercard set customerreference = '".$customerreference."' ,cuscardattacheddate = '".$attacheddate."' ,cuscardremarks = '".$remarks."' ,cuscardattachedby = '".$userid."', usertype = 'dealer' where cardid = '".$cardid."' ";
		$result = runmysqlquery($query);
		
		
		//insert into inv_invoicenumbers_dummy_regv2
		$regvquery = "Insert into inv_invoicenumbers_dummy_regv2(dealerid,customerid,date,description,cardid) values('".$userid."','".$custidno."','".date('Y-m-d').' '.date('H:i:s')."','".$description."','".$cardid."')";
		
		if(!is_null($selected_sub_dealer) && $selected_sub_dealer != '') {
		    
    		//insert into inv_invoicenumbers_dummy_regv2
    		$regvquery = "Insert into inv_invoicenumbers_dummy_regv2(dealerid,customerid,date,description,cardid) 
    		values ('".$selected_sub_dealer."','".$custidno."','".date('Y-m-d').' '.date('H:i:s')."','".$description."','".$cardid."')";
		    
		}
		
		$eventresult = runmysqlquery($regvquery);
		
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','119','".date('Y-m-d').' '.date('H:i:s')."','".$customerreference."')";
		
		if(!is_null($selected_sub_dealer) && $selected_sub_dealer != '') {
		    
    		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) 
    		values ('".$selected_sub_dealer."','".$_SERVER['REMOTE_ADDR']."','119','".date('Y-m-d').' '.date('H:i:s')."','".$customerreference."')";
		 
		}  
				
		$eventresult = runmysqlquery($eventquery);
		sendfreecardemail($customerreference,$cardid);
		
		$responsearray1 = array();
		$responsearray1['errorcode'] = "1";
		$responsearray1['errormsg'] = "Card Attached Successfully.";
		
	}
	echo(json_encode($responsearray1));
}

?>