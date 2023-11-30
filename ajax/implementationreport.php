<?php
ob_start("ob_gzhandler");

include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');

if(imaxgetcookie('dealeruserid')<> '') 
$userid = imaxgetcookie('dealeruserid');
else
{ 
	echo('Thinking to redirect');
	exit;
}

$lastslno = $_POST['lastslno'];
$switchtype = $_POST['switchtype'];

switch($switchtype)
{
	case 'implalldata':
	{
		//Fetch All Data
		$dealer = $_POST['dealer'];
		$type = $_POST['type'];
		$statuslist = $_POST['statuslist'];
		$statuslistsplit = explode(',',$statuslist);
		$typeselect = $_POST['typeselect'];
		$category = $_POST['category'];
		$implementer = $_POST['implementer'];
		$dealer_typepiece = ($dealer == "")?(""):(" AND inv_mas_dealer.slno = '".$dealer."' ");
		if($type == 'Not Selected')
		{
			$typepiece = ($type == "")?(""):(" AND inv_mas_customer.type = '' ");
		}
		else
		{
			$typepiece = ($type == "")?(""):(" AND inv_mas_customer.type = '".$type."' ");
		}
		if($category == 'Not Selected')
		{
			$categorypiece = ($category == "")?(""):(" AND inv_mas_customer.category = '' ");
		}
		else
		{
			$categorypiece = ($category == "")?(""):(" AND inv_mas_customer.category = '".$category."' ");
		}		
		$implementerpersonpiece = ($implementer == "")?(""):(" AND inv_mas_implementer.slno = '".$implementer."' ");
		if($typeselect == 'search')
		{
			$countsummarize = count($statuslistsplit);
			for($i = 0; $i<$countsummarize; $i++)
			{
				if($i < ($countsummarize-1))
						$appendor = 'or'.' ';
					else
						$appendor = '';
				switch($statuslistsplit[$i])
				{
					case 'status1' :
					{
						$statuspiece = " imp_implementation.branchapproval = 'no' AND imp_implementation.branchreject = 'yes' AND imp_implementation.implementationstatus = 'pending' ";
					}
					break;
					case 'status2' :
					{
						$statuspiece = " imp_implementation.branchapproval = 'no' AND imp_implementation.branchreject = 'no' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'no' AND  imp_implementation.implementationstatus = 'pending'";
					}
					break;
					case 'status3' :
					{
						$statuspiece = " imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'no' AND imp_implementation.implementationstatus = 'pending' ";
					}
					break;
					case 'status4' :
					{
						$statuspiece = " imp_implementation.branchapproval = 'no' AND imp_implementation.coordinatorreject = 'yes' AND imp_implementation.coordinatorapproval = 'no' AND imp_implementation.implementationstatus = 'pending' ";
					}
					break;
					case 'status5' :
					{
						$statuspiece = " imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'pending' ";
					}
					break;
					case 'status6' :
					{
						$statuspiece = " imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'assigned' ";
					}
					break;
					case 'status7' :
					{
						$statuspiece = " imp_implementation.branchapproval = 'yes'  AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'progess' ";
					}
					break;
					case 'status8' :
					{
						$statuspiece = " imp_implementation.branchapproval = 'yes'  AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'completed' ";
					}
					break;
				}
				$finalstatuslist .= ''.'('.$statuspiece.')'.'  '.$appendor.'';
				
			}
			if($finalstatuslist != '')
			{
				$finalliststatus = ' AND'.'('.$finalstatuslist.')';
			}
			else
			{
				$finalliststatus = "";
			}
		}
		else
		{
			$finalliststatus = "";
		}

		$query = "select branchhead,branch,category from inv_mas_dealer left join inv_mas_region on inv_mas_dealer.region = inv_mas_region.slno where inv_mas_dealer.slno = '".$userid."'";
		$resultfetch = runmysqlqueryfetch($query);
		$branchhead = $resultfetch['branchhead'];
		$branch = $resultfetch['branch'];

		if($branchhead == 'yes')
		{
			$dealerpiece = " AND inv_mas_customer.branch = '".$branch."' and imp_implementation.customerreference!='' and imp_implementation.customerreference!='null'";
		}
		else
		{
			$dealerpiece = " AND inv_mas_dealer.slno = '".$userid."' and imp_implementation.customerreference!='' and imp_implementation.customerreference!='null'";
		}
		
		$query2 = "select distinct count(*) as slnocount from imp_implementation
		left join inv_mas_customer on inv_mas_customer.slno = imp_implementation.customerreference
		left join inv_mas_implementer on inv_mas_implementer.slno = imp_implementation.assignimplemenation 
		left join inv_mas_dealer on imp_implementation.dealerid = inv_mas_dealer.slno 
		where imp_implementation.slno <> '99999999'  ".$finalliststatus.$dealerpiece.$dealer_typepiece.$typepiece.$categorypiece.$implementerpersonpiece.";";
		$fetch2 = runmysqlqueryfetch($query2);
		// left join imp_cfentries on right(imp_cfentries.customerid,5) = imp_implementation.customerreference
		
		// $raw_total = $fetch1['slnocount'];
		$raw_total = 0;
		$activated_total = $fetch2['slnocount'];
		$totalofalldata = $raw_total + $activated_total;
		
		//Fetch Active Data
		$query3 = "select distinct count(*) as slnocount from imp_implementation 
		left join inv_mas_customer on inv_mas_customer.slno = imp_implementation.customerreference
		left join inv_mas_implementer on inv_mas_implementer.slno = imp_implementation.assignimplemenation 
		left join inv_mas_dealer on imp_implementation.dealerid = inv_mas_dealer.slno where imp_implementation.implementationstatus <> 'completed'   ".$finalliststatus.$dealerpiece.$dealer_typepiece.$typepiece.$categorypiece.$implementerpersonpiece.";";
		$fetch3 = runmysqlqueryfetch($query3);
		// left join imp_cfentries on right(imp_cfentries.customerid,5) = imp_implementation.customerreference
		
		$query4 = "select distinct count(*) as slnocount from imp_implementation 
		left join inv_mas_customer on inv_mas_customer.slno = imp_implementation.customerreference
		left join inv_mas_implementer on inv_mas_implementer.slno = imp_implementation.assignimplemenation 
		left join inv_mas_dealer on imp_implementation.dealerid = inv_mas_dealer.slno 
		where imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND 
		imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'completed'  ".$finalliststatus.$dealerpiece.$dealer_typepiece.$typepiece.$categorypiece.$implementerpersonpiece." ;";
		$fetch4 = runmysqlqueryfetch($query4);
		// left join imp_cfentries on right(imp_cfentries.customerid,5) = imp_implementation.customerreference
		
		$dueactive_total = $fetch3['slnocount'];
		$completactive_total = $fetch4['slnocount'];
		$totalofactivedata = $dueactive_total + $completactive_total;
		
		//Fetch Implementation Due Data
		$query5 = "select distinct count(*) as slnocount from imp_implementation 
		left join inv_mas_customer on inv_mas_customer.slno = imp_implementation.customerreference
		left join inv_mas_implementer on inv_mas_implementer.slno = imp_implementation.assignimplemenation 
		left join inv_mas_dealer on imp_implementation.dealerid = inv_mas_dealer.slno 
		where imp_implementation.implementationstatus <> 'completed' and imp_implementation.branchapproval = 'no' ".$finalliststatus.$dealerpiece.$dealer_typepiece.$typepiece.$categorypiece.$implementerpersonpiece." ;";
		$fetch5 = runmysqlqueryfetch($query5);
		// left join imp_cfentries on right(imp_cfentries.customerid,5) = imp_implementation.customerreference
		
		$query6 = "select distinct count(*) as slnocount from imp_implementation 
		left join inv_mas_customer on inv_mas_customer.slno = imp_implementation.customerreference
		left join inv_mas_implementer on inv_mas_implementer.slno = imp_implementation.assignimplemenation 
		left join inv_mas_dealer on imp_implementation.dealerid = inv_mas_dealer.slno 
		where imp_implementation.implementationstatus <> 'completed' and imp_implementation.branchapproval <> 'no'  ".$finalliststatus.$dealerpiece.$dealer_typepiece.$typepiece.$categorypiece.$implementerpersonpiece.";";
		$fetch6 = runmysqlqueryfetch($query6);
		// left join imp_cfentries on right(imp_cfentries.customerid,5) = imp_implementation.customerreference
		
		$dueimp_total = $fetch5['slnocount'];
		$completeimp_total = $fetch6['slnocount'];
		$totalofactiveimpdata = $dueimp_total + $completeimp_total;
				
		//Fetch Status wise Due Data
		$query8 = "select  count(*) as count from imp_implementation 
		left join inv_mas_customer on inv_mas_customer.slno = imp_implementation.customerreference
		left join inv_mas_implementer on inv_mas_implementer.slno = imp_implementation.assignimplemenation 
		left join inv_mas_dealer on imp_implementation.dealerid = inv_mas_dealer.slno 
		where imp_implementation.branchapproval = 'no' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'no' AND imp_implementation.branchreject = 'no' AND imp_implementation.implementationstatus = 'pending'  ".$finalliststatus.$dealerpiece.$dealer_typepiece.$typepiece.$categorypiece.$implementerpersonpiece."";
		$fetch8 = runmysqlqueryfetch($query8);
		$status_branch = $fetch8['count'];
		// left join imp_cfentries on right(imp_cfentries.customerid,5) = imp_implementation.customerreference
		
		$query84 = "select  count(*) as count from imp_implementation
		left join inv_mas_customer on inv_mas_customer.slno = imp_implementation.customerreference
		left join inv_mas_implementer on inv_mas_implementer.slno = imp_implementation.assignimplemenation 
		left join inv_mas_dealer on imp_implementation.dealerid = inv_mas_dealer.slno 
		where imp_implementation.branchapproval = 'no' AND imp_implementation.branchreject = 'yes' AND imp_implementation.implementationstatus = 'pending'  ".$finalliststatus.$dealerpiece.$dealer_typepiece.$typepiece.$categorypiece.$implementerpersonpiece."";
		$fetch84 = runmysqlqueryfetch($query84);
		$status_branchreject = $fetch84['count'];
		// left join imp_cfentries on right(imp_cfentries.customerid,5) = imp_implementation.customerreference
		
		$query9 = "select  count(*) as count  from imp_implementation 
		left join inv_mas_customer on inv_mas_customer.slno = imp_implementation.customerreference
		left join inv_mas_implementer on inv_mas_implementer.slno = imp_implementation.assignimplemenation 
		left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode 
		left join inv_mas_state on inv_mas_district.statecode = inv_mas_state.slno 
		left join inv_mas_dealer on imp_implementation.dealerid = inv_mas_dealer.slno 
		left join inv_mas_region on inv_mas_region.slno = inv_mas_dealer.region 
		left join inv_mas_branch on inv_mas_branch.slno = inv_mas_dealer.branch
		where imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'no' AND imp_implementation.implementationstatus = 'pending'  ".$finalliststatus.$dealerpiece.$dealer_typepiece.$typepiece.$categorypiece.$implementerpersonpiece."";
		$fetch9 = runmysqlqueryfetch($query9);
		$status_coordinatorapproval = $fetch9['count'];
		// left join imp_cfentries on right(imp_cfentries.customerid,5) = imp_implementation.customerreference
		
		$query10 = "select  count(*) as count  from imp_implementation 
		left join inv_mas_customer on inv_mas_customer.slno = imp_implementation.customerreference
		left join inv_mas_implementer on inv_mas_implementer.slno = imp_implementation.assignimplemenation 
		left join inv_mas_dealer on imp_implementation.dealerid = inv_mas_dealer.slno 
		where imp_implementation.branchapproval = 'no' AND imp_implementation.coordinatorreject = 'yes' AND imp_implementation.coordinatorapproval = 'no' AND imp_implementation.implementationstatus = 'pending'  ".$finalliststatus.$dealerpiece.$dealer_typepiece.$typepiece.$categorypiece.$implementerpersonpiece."";
		$fetch10 = runmysqlqueryfetch($query10);
		$status_coordinatorreject = $fetch10['count'];
		// left join imp_cfentries on right(imp_cfentries.customerid,5) = imp_implementation.customerreference
		
		$query11 = "select  count(*) as count  from imp_implementation 
		left join inv_mas_customer on inv_mas_customer.slno = imp_implementation.customerreference
		left join inv_mas_implementer on inv_mas_implementer.slno = imp_implementation.assignimplemenation 
		left join inv_mas_dealer on imp_implementation.dealerid = inv_mas_dealer.slno 
		where imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'pending'  ".$finalliststatus.$dealerpiece.$dealer_typepiece.$typepiece.$categorypiece.$implementerpersonpiece."";
		$fetch11 = runmysqlqueryfetch($query11);
		$status_pending = $fetch11['count'];
		// left join imp_cfentries on right(imp_cfentries.customerid,5) = imp_implementation.customerreference
		
		$query12 = "select  count(*) as count  from imp_implementation
		left join inv_mas_customer on inv_mas_customer.slno = imp_implementation.customerreference
		left join inv_mas_implementer on inv_mas_implementer.slno = imp_implementation.assignimplemenation 
		left join inv_mas_dealer on imp_implementation.dealerid = inv_mas_dealer.slno 
		where imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'assigned'  ".$finalliststatus.$dealerpiece.$dealer_typepiece.$typepiece.$categorypiece.$implementerpersonpiece."";
		$fetch12 = runmysqlqueryfetch($query12);
		$status_assigned = $fetch12['count'];
		// left join imp_cfentries on right(imp_cfentries.customerid,5) = imp_implementation.customerreference
		
		$query13 = "select  count(*) as count  from imp_implementation 
		left join inv_mas_customer on inv_mas_customer.slno = imp_implementation.customerreference
		left join inv_mas_implementer on inv_mas_implementer.slno = imp_implementation.assignimplemenation 
		left join inv_mas_dealer on imp_implementation.dealerid = inv_mas_dealer.slno 
		where imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'progess'  ".$finalliststatus.$dealerpiece.$dealer_typepiece.$typepiece.$categorypiece.$implementerpersonpiece."";
		$fetch13 = runmysqlqueryfetch($query13);
		$status_progess = $fetch13['count'];
		// left join imp_cfentries on right(imp_cfentries.customerid,5) = imp_implementation.customerreference
		
		$query14 = "select  count(*) as count  from imp_implementation
		left join inv_mas_customer on inv_mas_customer.slno = imp_implementation.customerreference
		left join inv_mas_implementer on inv_mas_implementer.slno = imp_implementation.assignimplemenation 
		left join inv_mas_dealer on imp_implementation.dealerid = inv_mas_dealer.slno 
		where imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'completed'  ".$finalliststatus.$dealerpiece.$dealer_typepiece.$typepiece.$categorypiece.$implementerpersonpiece."";
		$fetch14 = runmysqlqueryfetch($query14);
		$status_completed = $fetch14['count'];
		// left join imp_cfentries on right(imp_cfentries.customerid,5) = imp_implementation.customerreference

		$status_totaldue = $status_branch + $status_coordinatorapproval + $status_coordinatorreject + $status_pending + $status_assigned + $status_progess + $status_completed + $status_branchreject;
		
		
		echo('1^'.$totalofalldata.'^'.$raw_total.'^'.$activated_total.'^'.$totalofactivedata.'^'.$dueactive_total.'^'.$completactive_total.'^'.$totalofactiveimpdata.'^'.$dueimp_total.'^'.$completeimp_total.'^'.''.'^'.''.'^'.$region.'^'.$region_count.'^'.$status_branch.'^'.$status_coordinatorapproval.'^'.$status_coordinatorreject.'^'.$status_pending.'^'.$status_assigned.'^'.$status_progess.'^'.$status_completed.'^'.$status_totaldue.'^'.$status_branchreject.'^'.$branch);
		
	}
	break;
	
	case 'implbranchimpwise':
	{
		$branchslno = $_POST['branchslno'];
		$query = "select * from inv_mas_dealer where slno = '".$userid."'";
		$resultfetch = runmysqlqueryfetch($query);
		$branchhead = $resultfetch['branchhead'];
		$branch = $resultfetch['branch'];

		if($branchhead == 'yes')
		{
			$dealerpiece = " inv_mas_branch.slno = '".$branchslno."'";
		}
		else
		{
			$dealerpiece = " inv_mas_dealer.slno = '".$userid."'";
		}

		$query = "select inv_mas_implementer.slno as impslno,inv_mas_implementer.businessname as businessname, count(imp_implementation.assignimplemenation) as slnocount
		from imp_implementation
		left join inv_mas_dealer on imp_implementation.dealerid = inv_mas_dealer.slno
		left join inv_mas_implementer on  imp_implementation.assignimplemenation = inv_mas_implementer.slno
		left join inv_mas_branch on inv_mas_dealer.branch = inv_mas_branch.slno
		where ".$dealerpiece."  and inv_mas_implementer.disablelogin = 'no' and inv_mas_implementer.implementernotinuse = 'no' group by inv_mas_implementer.businessname";
		$result = runmysqlquery($query);
		$grid .= '<table width="100%" border="0" cellspacing="0" cellpadding="5" class="imp_table-border">';
		$grid .= '<tr class="imp_tr-grid-header1">';
		$grid .= '<td width="65%" colspan="2" align="center" class="imp_td-border">Branch - Implementer  Wise</td>';
		$grid .= '</tr>';
		$grid .= '<tr class="imp_tr-grid-header1">';
		$grid .= '<td width="65%" align="center" class="imp_td-border">Implementer</td>';
		$grid .= '<td width="35%" align="center" class="imp_td-border">Count</td>';
		$grid .= '</tr>';
		$totalimpslno  = "";$totalimpslno = "";
		if(mysqli_num_rows($result) <> 0)
		{
			$totalofbranchimpwise = $impcount =  0;
			while($fetchcount = mysqli_fetch_array($result))
			{
				$grid .= '<tr>';
				$grid .= '<td align="left" class="imp_td-border imp_fontstyle3" >'.$fetchcount['businessname'].'</td>';
				$grid .= '<td class="imp_td-border" align="center" ><span class="imp_fontstyle4" style="font-weight:bold"  bgcolor="#D3D3D3" onclick="displaygridofimpl(\''.$fetchcount['impslno'].'\',\'single\',\''.$fetchcount['impslno'].'\')">'.($fetchcount['slnocount']+$fetchcount['impslnocount']).'</span></td>';
				$grid .= '</tr>';
				$totalimpslno .= $fetchcount['impslno'].'#';
				
				$totalofbranchimpwise += $fetchcount['slnocount'];
			}
			//echo $impcount1;
			$grid .= '<tr>';
			$grid .= '<td align="left" class="imp_td-border imp_fontstyle3" style="font-weight:bold"  bgcolor="#D3D3D3">Total Due</td>';
			$grid .= '<td align="center" class="imp_td-border" bgcolor="#D3D3D3"><span class="imp_fontstyle4" style="font-weight:bold"   onclick="displaygridofimpl(\''.trim($totalimpslno,'#').'\',\'totalofimpbranch\')">'.$totalofbranchimpwise.'</span></td>';
			$grid .= '</tr>';
			$grid .= '</table>';	
		}
		elseif(mysqli_num_rows($result) == 0)
		{
			$grid .= '<tr><td colspan="2" bgcolor="#FFFFD2" class="imp_td-border imp_fontstyle2" style="font-weight:bold"><font color="#FF4F4F" >No Records</font></td></tr>';
		
		}
		//sleep(10);
		echo('1^'.$grid);
	}
	break;
	
	case 'implbranchsalewise':
	{
		$branchslno = $_POST['branchslno'];
		$query = "select * from inv_mas_dealer where slno = '".$userid."'";
		$resultfetch = runmysqlqueryfetch($query);
		$branchhead = $resultfetch['branchhead'];

		if($branchhead == 'yes')
		{
			$dealerpiece = " inv_mas_branch.slno = '".$branchslno."' and imp_implementation.customerreference!='' and imp_implementation.customerreference!='null'";
		}
		else
		{
			$dealerpiece = " inv_mas_dealer.slno = '".$userid."' and imp_implementation.customerreference!='' and imp_implementation.customerreference!='null'";
		}

		$query3 = "select inv_mas_dealer.slno as dealerid, inv_mas_dealer.businessname as businessname, count(imp_implementation.dealerid) as slnocount 
		from inv_mas_dealer left join imp_implementation on  imp_implementation.dealerid = inv_mas_dealer.slno
		left join inv_mas_branch on  inv_mas_branch.slno = inv_mas_dealer.branch
		where ".$dealerpiece." and inv_mas_dealer.disablelogin = 'no' and relyonexecutive='yes'  and inv_mas_dealer.dealernotinuse = 'no' group by inv_mas_dealer.businessname";
		$result = runmysqlquery($query3);
		$salegrid .= '<table width="100%" border="0" cellspacing="0" cellpadding="5" class="imp_table-border">';
		$salegrid .= '<tr class="imp_tr-grid-header1">';
		$salegrid .= '<td width="70%" colspan="2" align="center" class="imp_td-border">Branch - Sales person wise</td>';
		$salegrid .= '</tr>';
		$salegrid .= '<tr class="imp_tr-grid-header1">';
		$salegrid .= '<td width="70%" align="center" class="imp_td-border">Sales Person</td>';
		$salegrid .= '<td width="30%" align="center" class="imp_td-border">Count</td>';
		$salegrid .= '</tr>';
		if(mysqli_num_rows($result) <> 0)
		{
			$totalofbranchimpwise = 0;
			while($fetchcount = mysqli_fetch_array($result))
			{
				$salegrid .= '<tr>';
				$salegrid .= '<td align="left" class="imp_td-border imp_fontstyle3" >'.$fetchcount['businessname'].'</td>';
				$salegrid .= '<td class="imp_td-border" align="center"><span class="imp_fontstyle4" align="center"
				 onclick="displaygridofsale(\''.$fetchcount['dealerid'].'\',\'single\')">'.$fetchcount['slnocount'].'</span></td>';
				$salegrid .= '</tr>';
				$totalbranchsale .= $fetchcount['dealerid'].'#';
				$totalofbranchimpwise += $fetchcount['slnocount'];
			}
			$salegrid .= '<tr>';
			$salegrid .= '<td align="left" class="imp_td-border imp_fontstyle3" style="font-weight:bold"  bgcolor="#D3D3D3">Total Due</td>';
			$salegrid .= '<td align="center" class="imp_td-border" bgcolor="#D3D3D3" ><span class=" imp_fontstyle4" style="font-weight:bold" onclick="displaygridofsale(\''.trim($totalbranchsale,'#').'\',\'totalofbranchsalewise\')">'.$totalofbranchimpwise.'</span></td>';
			$salegrid .= '</tr>';
			$salegrid .= '</table>';	
		}
		elseif(mysqli_num_rows($result) == 0)
		{
			$salegrid .= '<tr><td colspan="2" bgcolor="#FFFFD2" class="imp_td-border imp_fontstyle2" style="font-weight:bold"><font color="#FF4F4F" >No Records</font></td></tr>';
		
		}
		//sleep(10);
		echo('1^'.$salegrid);
	}
	break;
	
	case 'impdetailedgrid':
	{
		$typelist = $_POST['typelist'];
		$typeselect = $_POST['typeselect'];
		$dealer = $_POST['dealer'];
		$type = $_POST['type'];
		$statuslist = $_POST['statuslist'];
		$statuslistsplit = explode(',',$statuslist);
		$category = $_POST['category'];
		$implementer = $_POST['implementer'];
		$dealer_typepiece = ($dealer == "")?(""):(" AND inv_mas_dealer.slno = '".$dealer."' ");
		if($type == 'Not Selected')
		{
			$typepiece = ($type == "")?(""):(" AND inv_mas_customer.type = '' ");
		}
		else
		{
			$typepiece = ($type == "")?(""):(" AND inv_mas_customer.type = '".$type."' ");
		}
		if($category == 'Not Selected')
		{
			$categorypiece = ($category == "")?(""):(" AND inv_mas_customer.category = '' ");
		}
		else
		{
			$categorypiece = ($category == "")?(""):(" AND inv_mas_customer.category = '".$category."' ");
		}		
		$implementerpersonpiece = ($implementer == "")?(""):(" AND inv_mas_implementer.slno = '".$implementer."' ");
		if($typeselect == 'search')
		{
			$countsummarize = count($statuslistsplit);
			for($i = 0; $i<$countsummarize; $i++)
			{
				if($i < ($countsummarize-1))
						$appendor = 'or'.' ';
					else
						$appendor = '';
				switch($statuslistsplit[$i])
				{
					case 'status1' :
					{
						$statuspiece = " imp_implementation.branchapproval = 'no' AND imp_implementation.branchreject = 'yes' AND imp_implementation.implementationstatus = 'pending' ";
					}
					break;
					case 'status2' :
					{
						$statuspiece = " imp_implementation.branchapproval = 'no' AND imp_implementation.branchreject = 'no' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'no' AND  imp_implementation.implementationstatus = 'pending'";
					}
					break;
					case 'status3' :
					{
						$statuspiece = " imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'no' AND imp_implementation.implementationstatus = 'pending' ";
					}
					break;
					case 'status4' :
					{
						$statuspiece = " imp_implementation.branchapproval = 'no' AND imp_implementation.coordinatorreject = 'yes' AND imp_implementation.coordinatorapproval = 'no' AND imp_implementation.implementationstatus = 'pending' ";
					}
					break;
					case 'status5' :
					{
						$statuspiece = " imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'pending' ";
					}
					break;
					case 'status6' :
					{
						$statuspiece = " imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'assigned' ";
					}
					break;
					case 'status7' :
					{
						$statuspiece = " imp_implementation.branchapproval = 'yes'  AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'progess' ";
					}
					break;
					case 'status8' :
					{
						$statuspiece = " imp_implementation.branchapproval = 'yes'  AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'completed' ";
					}
					break;
				}
				$finalstatuslist .= ''.'('.$statuspiece.')'.'  '.$appendor.'';
				
			}
			if($finalstatuslist != '')
			{
				$finalliststatus = ' AND'.'('.$finalstatuslist.')';
			}
			else
			{
				$finalliststatus = "";
			}
		}
		else
		{
			$finalliststatus = "";
		}
		if($typelist == 'totalalldata')
			$headingname = 'List of customers with Implementation, including C/F entries';
		elseif($typelist == 'rawdata')
			$headingname = 'List of customers from C/F entries, for whom implementation has not yet been created';
		elseif($typelist == 'activatedata')
			$headingname = 'List of customers for whom implmentation has been created successfully';
			
		$startgrid .= '<table width="100%" border="0" cellspacing="0" cellpadding="5" class="table-border-grid">';
		$startgrid .= '<tr  class="tr-grid-header">';
		$startgrid .= '<td align="left" nowrap = "nowrap" class="td-border-grid">Sl No</td><td align="left" nowrap = "nowrap" class="td-border-grid">Customer name</td><td nowrap = "nowrap" align="left" class="td-border-grid">Customer ID</td><td nowrap = "nowrap" align="left" class="td-border-grid">Sales Person</td><td align="left" nowrap = "nowrap" class="td-border-grid">Implementer</td><td align="left" nowrap = "nowrap" class="td-border-grid">Invoice Date</td><td align="left" nowrap = "nowrap" class="td-border-grid">Request Date</td><td align="left" nowrap = "nowrap" class="td-border-grid">Branch Head Approved date</td>
		<td align="left" nowrap = "nowrap" class="td-border-grid">Co-ordinator Approved date</td><td align="left" nowrap = "nowrap" class="td-border-grid">Assigned date</td><td align="left" nowrap = "nowrap" class="td-border-grid">Completed date</td><td align="left" nowrap = "nowrap" class="td-border-grid">Customization</td><td align="left" nowrap = "nowrap" class="td-border-grid">Attendance Integration</td><td align="left" nowrap = "nowrap" class="td-border-grid">&nbsp;</td>';
		$startgrid .= '</tr>';
		
		$query = "select * from inv_mas_dealer where slno = '".$userid."'";
		$resultfetch = runmysqlqueryfetch($query);
		$branchhead = $resultfetch['branchhead'];
		$branch = $resultfetch['branch'];

		if($branchhead == 'yes')
		{
			$dealerpiece = " AND inv_mas_customer.branch = '".$branch."'";
		}
		else
		{
			$dealerpiece = " AND inv_mas_dealer.slno = '".$userid."'";
		}
		
		//Fetch All Data
		if($typelist == 'activatedata' || $typelist == 'totalalldata')
		{
			//Fetch Activate Data
			$query2 = "select inv_mas_customer.slno as slnonumber, inv_mas_customer.businessname as custname,inv_mas_customer.customerid as cusid,
			inv_mas_dealer.businessname as salesperson, inv_mas_implementer.businessname as Implementer,
			left(inv_invoicenumbers.createddate,10) as invoicedate,left(imp_implementation.createddatetime,10) as requestdate,
			left(imp_implementation.branchapprovaldatetime,10) as branchappdate,
			left(imp_implementation.coordinatorappdatetime,10) as coordinatorappdate,
			left(imp_implementation.assignimpldatetime,10) as assignimpldate,
			left(dummy.visiteddate,10) as completeddate,imp_implementation.customizationapplicable as 
			customization, imp_implementation.attendanceapplicable as attendanceapplicable,imp_implementation.slno as impslno
			from imp_implementation
			left join inv_mas_customer on inv_mas_customer.slno = imp_implementation.customerreference
			left join inv_mas_dealer on inv_mas_dealer.slno = imp_implementation.dealerid
			left join inv_mas_implementer on inv_mas_implementer.slno = imp_implementation.assignimplemenation
			left join inv_invoicenumbers on inv_invoicenumbers.invoiceno = imp_implementation.invoicenumber
			left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode 
			left join inv_mas_state on inv_mas_district.statecode = inv_mas_state.slno 
			left join inv_mas_region on inv_mas_region.slno = inv_mas_dealer.region 
			left join inv_mas_branch on inv_mas_branch.slno = inv_mas_dealer.branch
			left join (select slno,min(visiteddate) as visiteddate,impref 
			from imp_implementationdays  where iccattachment = 'yes' 
			group by impref)as dummy on dummy.impref = imp_implementation.slno where imp_implementation.slno <> '99999999'  ".$finalliststatus.$dealerpiece.$state_typepiece.$district_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$implementerpersonpiece."; ";
			$result = runmysqlquery($query2);
			
			$i_n = 0;$slno = 0;
			if(mysqli_num_rows($result) <> 0)
			{
				while($fetch = mysqli_fetch_array($result))
				{
					$i_n++;
					$slno++;
					$color;
					if($i_n%2 == 0)
						$color = "#edf4ff";
					else
						$color = "#f7faff";
					$activated_grid .= '<tr bgcolor='.$color.'>';
					$activated_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slno."</td>";
					$activated_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['custname'])."</td>";
					$activated_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".cusidcombine($fetch['cusid'])."</td>";
					$activated_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['salesperson']."</td>";
					$activated_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['Implementer']."</td>";
					$activated_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch['invoicedate'])."</td>";
					$activated_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch['requestdate'])."</td>";
					$activated_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch['branchappdate'])."</td>";
					$activated_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch['coordinatorappdate'])."</td>";
					$activated_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch['assignimpldate'])."</td>";
					$activated_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch['completeddate'])."</td>";
					$activated_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".strtolower($fetch['customization'])."</td>";
					$activated_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".strtolower($fetch['attendanceapplicable'])."</td>";
					$activated_grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"> <a onclick="viewhistory(\''.$fetch['slnonumber'].'\',\''.$fetch['impslno'].'\');" class="resendtext" style = "cursor:pointer"> View History >></a> </td>';
					$activated_grid .= "</tr>";
				}
			}
			elseif(mysqli_num_rows($result) == 0)
			{
				$activated_grid .= '<tr><td colspan="14" bgcolor="#FFFFD2" class="td-border-grid" style="font-weight:bold"><font color="#FF4F4F" >No Records</font></td></tr>';
			
			}
		}

		$endgrid = "</table>";
		$grid = "";
		if($typelist == 'totalalldata')
		{
			if((mysqli_num_rows($result) == 0))
			{
				$grid = $startgrid.$activated_grid.$endgrid;
			}
			elseif((mysqli_num_rows($result) <> 0))
			{
				$grid = $startgrid.$activated_grid.$endgrid;
			}
		}
		// elseif($typelist == 'rawdata')
		// 	$grid = $startgrid.$raw_grid.$endgrid;
		elseif($typelist == 'activatedata')
			$grid = $startgrid.$activated_grid.$endgrid;
			
			
			
		echo('1^'.$grid.'^'.$headingname);
	}
	break;
	
	case 'impdetailedimplduegrid':
	{
		$typelist = $_POST['typelist'];
		$typeselect = $_POST['typeselect'];
		$dealer = $_POST['dealer'];
		$type = $_POST['type'];
		$statuslist = $_POST['statuslist'];
		$statuslistsplit = explode(',',$statuslist);
		$category = $_POST['category'];
		$implementer = $_POST['implementer'];
		$dealer_typepiece = ($dealer == "")?(""):(" AND inv_mas_dealer.slno = '".$dealer."' ");
		if($type == 'Not Selected')
		{
			$typepiece = ($type == "")?(""):(" AND inv_mas_customer.type = '' ");
		}
		else
		{
			$typepiece = ($type == "")?(""):(" AND inv_mas_customer.type = '".$type."' ");
		}
		if($category == 'Not Selected')
		{
			$categorypiece = ($category == "")?(""):(" AND inv_mas_customer.category = '' ");
		}
		else
		{
			$categorypiece = ($category == "")?(""):(" AND inv_mas_customer.category = '".$category."' ");
		}		
		$implementerpersonpiece = ($implementer == "")?(""):(" AND inv_mas_implementer.slno = '".$implementer."' ");
		if($typeselect == 'search')
		{
			$countsummarize = count($statuslistsplit);
			for($i = 0; $i<$countsummarize; $i++)
			{
				if($i < ($countsummarize-1))
						$appendor = 'or'.' ';
					else
						$appendor = '';
				switch($statuslistsplit[$i])
				{
					case 'status1' :
					{
						$statuspiece = " imp_implementation.branchapproval = 'no' AND imp_implementation.branchreject = 'yes' AND imp_implementation.implementationstatus = 'pending' ";
					}
					break;
					case 'status2' :
					{
						$statuspiece = " imp_implementation.branchapproval = 'no' AND imp_implementation.branchreject = 'no' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'no' AND  imp_implementation.implementationstatus = 'pending'";
					}
					break;
					case 'status3' :
					{
						$statuspiece = " imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'no' AND imp_implementation.implementationstatus = 'pending' ";
					}
					break;
					case 'status4' :
					{
						$statuspiece = " imp_implementation.branchapproval = 'no' AND imp_implementation.coordinatorreject = 'yes' AND imp_implementation.coordinatorapproval = 'no' AND imp_implementation.implementationstatus = 'pending' ";
					}
					break;
					case 'status5' :
					{
						$statuspiece = " imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'pending' ";
					}
					break;
					case 'status6' :
					{
						$statuspiece = " imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'assigned' ";
					}
					break;
					case 'status7' :
					{
						$statuspiece = " imp_implementation.branchapproval = 'yes'  AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'progess' ";
					}
					break;
					case 'status8' :
					{
						$statuspiece = " imp_implementation.branchapproval = 'yes'  AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'completed' ";
					}
					break;
				}
				$finalstatuslist .= ''.'('.$statuspiece.')'.'  '.$appendor.'';
				
			}
			if($finalstatuslist != '')
			{
				$finalliststatus = ' AND'.'('.$finalstatuslist.')';
			}
			else
			{
				$finalliststatus = "";
			}
		}
		else
		{
			$finalliststatus = "";
		}

		$query = "select * from inv_mas_dealer where slno = '".$userid."'";
		$resultfetch = runmysqlqueryfetch($query);
		$branchhead = $resultfetch['branchhead'];
		$branch = $resultfetch['branch'];

		if($branchhead == 'yes')
		{
			$dealerpiece = " AND inv_mas_customer.branch = '".$branch."'";
		}
		else
		{
			$dealerpiece = " AND inv_mas_dealer.slno = '".$userid."'";
		}

		if($typelist == 'totalimpduedata')
			$headingname = 'List of customers for with implementation,including with Sales person and Implementation in process';
		elseif($typelist == 'implsale')
			$headingname = 'List of customers for whom implementation is with Sales Person';
		elseif($typelist == 'implimplementation')
			$headingname = 'List of customers for whom implementation is with Implementation';
			
		$startgrid .= '<table width="100%" border="0" cellspacing="0" cellpadding="5" class="table-border-grid">';
		$startgrid .= '<tr  class="tr-grid-header">';
		$startgrid .= '<td align="left" nowrap = "nowrap" class="td-border-grid">Sl No</td><td align="left" nowrap = "nowrap" class="td-border-grid">Customer name</td><td nowrap = "nowrap" align="left" class="td-border-grid">Customer ID</td><td nowrap = "nowrap" align="left" class="td-border-grid">Sales Person</td><td align="left" nowrap = "nowrap" class="td-border-grid">Implementer</td><td align="left" nowrap = "nowrap" class="td-border-grid">Invoice Date</td><td align="left" nowrap = "nowrap" class="td-border-grid">Request Date</td><td align="left" nowrap = "nowrap" class="td-border-grid">Branch Head Approved date</td><td align="left" nowrap = "nowrap" class="td-border-grid">Co-ordinator Approved date</td><td align="left" nowrap = "nowrap" class="td-border-grid">Assigned date</td><td align="left" nowrap = "nowrap" class="td-border-grid">Completed date</td><td align="left" nowrap = "nowrap" class="td-border-grid">Customization</td><td align="left" nowrap = "nowrap" class="td-border-grid">Attendance Integration</td><td align="left" nowrap = "nowrap" class="td-border-grid">&nbsp;</td>';
		$startgrid .= '</tr>';
		
		$query = "select * from inv_mas_dealer where slno = '".$userid."'";
		$resultfetch = runmysqlqueryfetch($query);
		$branchhead = $resultfetch['branchhead'];
		$branch = $resultfetch['branch'];
		if($branchhead == 'yes')
		{
			$dealerpiece = " AND inv_mas_customer.branch = '".$branch."'";
		}
		else
		{
			$dealerpiece = " AND inv_mas_dealer.slno = '".$userid."'";
		}

		//Fetch All Data
		if($typelist == 'implsale' || $typelist == 'totalimpduedata')
		{
			//Fetch With Sales Data
			$query2 = "select inv_mas_customer.slno as slnonumber, inv_mas_customer.businessname as custname,inv_mas_customer.customerid as cusid,
			inv_mas_dealer.businessname as salesperson, inv_mas_implementer.businessname as Implementer,
			left(inv_invoicenumbers.createddate,10) as invoicedate,left(imp_implementation.createddatetime,10) as requestdate,
			left(imp_implementation.branchapprovaldatetime,10) as branchappdate,
			left(imp_implementation.coordinatorappdatetime,10) as coordinatorappdate,
			left(imp_implementation.assignimpldatetime,10) as assignimpldate,
			left(dummy.visiteddate,10) as completeddate,imp_implementation.customizationapplicable as 
			customization, imp_implementation.attendanceapplicable as attendanceapplicable,imp_implementation.slno as impslno
			from imp_implementation
			left join inv_mas_customer on inv_mas_customer.slno = imp_implementation.customerreference
			left join inv_mas_dealer on inv_mas_dealer.slno = imp_implementation.dealerid
			left join inv_mas_implementer on inv_mas_implementer.slno = imp_implementation.assignimplemenation
			left join inv_invoicenumbers on inv_invoicenumbers.invoiceno = imp_implementation.invoicenumber
			left join (select slno,min(visiteddate) as visiteddate,impref 
			from imp_implementationdays  where iccattachment = 'yes' group by impref)as dummy on dummy.impref = imp_implementation.slno
			where imp_implementation.branchapproval = 'no' and imp_implementation.implementationstatus <> 'completed' ".$finalliststatus.$dealerpiece.$dealer_typepiece.$typepiece.$categorypiece.$implementerpersonpiece."";
			$result = runmysqlquery($query2);
		
			$i_n = 0;$slno = 0;
			if(mysqli_num_rows($result) <> 0)
			{
				while($fetch = mysqli_fetch_array($result))
				{
					$i_n++;
					$slno++;
					$color;
					if($i_n%2 == 0)
						$color = "#edf4ff";
					else
						$color = "#f7faff";
					$implsale_grid .= '<tr bgcolor='.$color.'>';
					$implsale_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slno."</td>";
					$implsale_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['custname'])."</td>";
					$implsale_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".cusidcombine($fetch['cusid'])."</td>";
					$implsale_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['salesperson']."</td>";
					$implsale_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['Implementer']."</td>";
					$implsale_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch['invoicedate'])."</td>";
					$implsale_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch['requestdate'])."</td>";
					$implsale_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch['branchappdate'])."</td>";
					$implsale_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch['coordinatorappdate'])."</td>";
					$implsale_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch['assignimpldate'])."</td>";
					$implsale_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch['completeddate'])."</td>";
					$implsale_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".strtolower($fetch['customization'])."</td>";
					$implsale_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".strtolower($fetch['attendanceapplicable'])."</td>";
					$implsale_grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"> <a onclick="viewhistory(\''.$fetch['slnonumber'].'\',\''.$fetch['impslno'].'\');" class="resendtext" style = "cursor:pointer"> View History >></a> </td>';
					$implsale_grid .= "</tr>";
				}
			}
			elseif(mysqli_num_rows($result) == 0)
			{
				$implsale_grid .= '<tr><td colspan="14" bgcolor="#FFFFD2" class="td-border-grid" style="font-weight:bold"><font color="#FF4F4F" >No Records</font></td></tr>';
			}
		}
		if($typelist == 'implimplementation' || $typelist == 'totalimpduedata')
		{
			//Fetch With Implementation Data
			$query1 = "select inv_mas_customer.slno as slnonumber, inv_mas_customer.businessname as custname,inv_mas_customer.customerid as cusid,
			inv_mas_dealer.businessname as salesperson, inv_mas_implementer.businessname as Implementer,
			left(inv_invoicenumbers.createddate,10) as invoicedate,left(imp_implementation.createddatetime,10) as requestdate,
			left(imp_implementation.branchapprovaldatetime,10) as branchappdate,
			left(imp_implementation.coordinatorappdatetime,10) as coordinatorappdate,
			left(imp_implementation.assignimpldatetime,10) as assignimpldate,
			left(dummy.visiteddate,10) as completeddate,imp_implementation.customizationapplicable as 
			customization, imp_implementation.attendanceapplicable as attendanceapplicable,imp_implementation.slno as impslno
			from imp_implementation
			left join inv_mas_customer on inv_mas_customer.slno = imp_implementation.customerreference
			left join inv_mas_dealer on inv_mas_dealer.slno = imp_implementation.dealerid
			left join inv_mas_implementer on inv_mas_implementer.slno = imp_implementation.assignimplemenation
			left join inv_invoicenumbers on inv_invoicenumbers.invoiceno = imp_implementation.invoicenumber
			left join (select slno,min(visiteddate) as visiteddate,impref 
			from imp_implementationdays  where iccattachment = 'yes' group by impref)as dummy on dummy.impref = imp_implementation.slno where 
			imp_implementation.branchapproval <> 'no' and imp_implementation.implementationstatus <> 'completed' ".$finalliststatus.$dealerpiece.$dealer_typepiece.$typepiece.$categorypiece.$implementerpersonpiece.";";
			$result1 = runmysqlquery($query1);
			$i_n = 0;$slno1 = 0;
			if(mysqli_num_rows($result1) <> 0)
			{
				while($fetch1 = mysqli_fetch_array($result1))
				{
					$i_n++;
					if($typelist == 'totalimpduedata')
					{
						$slno++;
						$countvalue = $slno;
					}
					else
					{
						 $slno1++;
						 $countvalue = $slno1;
					}
					$color;
					if($i_n%2 == 0)
						$color = "#edf4ff";
					else
						$color = "#f7faff";
					$implementation_grid .= '<tr bgcolor='.$color.'>';
					$implementation_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$countvalue."</td>";
					$implementation_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch1['custname'])."</td>";
					$implementation_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".cusidcombine($fetch1['cusid'])."</td>";
					$implementation_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch1['salesperson']."</td>";
					$implementation_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch1['Implementer']."</td>";
					$implementation_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch1['invoicedate'])."</td>";
					$implementation_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch1['requestdate'])."</td>";
					$implementation_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch1['branchappdate'])."</td>";
					$implementation_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch1['coordinatorappdate'])."</td>";
					$implementation_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch1['assignimpldate'])."</td>";
					$implementation_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch1['completeddate'])."</td>";
					$implementation_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".strtolower($fetch1['customization'])."</td>";
					$implementation_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".strtolower($fetch1['attendanceapplicable'])."</td>";
					$implementation_grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"> <a onclick="viewhistory(\''.$fetch1['slnonumber'].'\',\''.$fetch1['impslno'].'\');" class="resendtext" style = "cursor:pointer"> View History >></a> </td>';
					$implementation_grid .= "</tr>";
				}
			}
			elseif(mysqli_num_rows($result1) == 0)
			{
				$implementation_grid .= '<tr><td height ="20px" colspan="14" bgcolor="#FFFFD2" class="td-border-grid" style="font-weight:bold"><font color="#FF4F4F" >No Records</font></td></tr>';
			}
		}
		$endgrid = "</table>";
		$grid = "";
		if($typelist == 'totalimpduedata')
		{
			if(mysqli_num_rows($result) == 0 && mysqli_num_rows($result1) == 0)
			{
				$grid = $startgrid.$implsale_grid.$endgrid;
			}
			elseif((mysqli_num_rows($result) == 0) && (mysqli_num_rows($result1) <> 0))
			{
				$grid = $startgrid.$implementation_grid.$implsale_grid.$endgrid;
			}
			elseif((mysqli_num_rows($result1) == 0) && (mysqli_num_rows($result) <> 0))
			{
				$grid = $startgrid.$implsale_grid.$implementation_grid.$endgrid;
			}
			elseif((mysqli_num_rows($result1) <> 0) && (mysqli_num_rows($result) <> 0))
			{
				$grid = $startgrid.$implsale_grid.$implementation_grid.$endgrid;
			}
		}
		elseif($typelist == 'implsale')
			$grid = $startgrid.$implsale_grid.$endgrid;
		elseif($typelist == 'implimplementation')
			$grid = $startgrid.$implementation_grid.$endgrid;
			
		echo('1^'.$grid.'^'.$headingname);
	}
	break;
	
	case 'displaystatusgrid':
	{
		$typelist = $_POST['typelist'];
		$typeselect = $_POST['typeselect'];
		$dealer = $_POST['dealer'];
		$type = $_POST['type'];
		$statuslist = $_POST['statuslist'];
		$statuslistsplit = explode(',',$statuslist);
		$category = $_POST['category'];
		$implementer = $_POST['implementer'];
		$dealer_typepiece = ($dealer == "")?(""):(" AND inv_mas_dealer.slno = '".$dealer."' ");
		if($type == 'Not Selected')
		{
			$typepiece = ($type == "")?(""):(" AND inv_mas_customer.type = '' ");
		}
		else
		{
			$typepiece = ($type == "")?(""):(" AND inv_mas_customer.type = '".$type."' ");
		}
		if($category == 'Not Selected')
		{
			$categorypiece = ($category == "")?(""):(" AND inv_mas_customer.category = '' ");
		}
		else
		{
			$categorypiece = ($category == "")?(""):(" AND inv_mas_customer.category = '".$category."' ");
		}		
		$implementerpersonpiece = ($implementer == "")?(""):(" AND inv_mas_implementer.slno = '".$implementer."' ");
		if($typeselect == 'search')
		{
			$countsummarize = count($statuslistsplit);
			for($i = 0; $i<$countsummarize; $i++)
			{
				if($i < ($countsummarize-1))
						$appendor = 'or'.' ';
					else
						$appendor = '';
				switch($statuslistsplit[$i])
				{
					case 'status1' :
					{
						$statuspiece = " imp_implementation.branchapproval = 'no' AND imp_implementation.branchreject = 'yes' AND imp_implementation.implementationstatus = 'pending' ";
					}
					break;
					case 'status2' :
					{
						$statuspiece = " imp_implementation.branchapproval = 'no' AND imp_implementation.branchreject = 'no' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'no' AND  imp_implementation.implementationstatus = 'pending'";
					}
					break;
					case 'status3' :
					{
						$statuspiece = " imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'no' AND imp_implementation.implementationstatus = 'pending' ";
					}
					break;
					case 'status4' :
					{
						$statuspiece = " imp_implementation.branchapproval = 'no' AND imp_implementation.coordinatorreject = 'yes' AND imp_implementation.coordinatorapproval = 'no' AND imp_implementation.implementationstatus = 'pending' ";
					}
					break;
					case 'status5' :
					{
						$statuspiece = " imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'pending' ";
					}
					break;
					case 'status6' :
					{
						$statuspiece = " imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'assigned' ";
					}
					break;
					case 'status7' :
					{
						$statuspiece = " imp_implementation.branchapproval = 'yes'  AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'progess' ";
					}
					break;
					case 'status8' :
					{
						$statuspiece = " imp_implementation.branchapproval = 'yes'  AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'completed' ";
					}
					break;
				}
				$finalstatuslist .= ''.'('.$statuspiece.')'.'  '.$appendor.'';
				
			}
			if($finalstatuslist != '')
			{
				$finalliststatus = ' AND'.'('.$finalstatuslist.')';
			}
			else
			{
				$finalliststatus = "";
			}
		}
		else
		{
			$finalliststatus = "";
		}

		$query = "select * from inv_mas_dealer where slno = '".$userid."'";
		$resultfetch = runmysqlqueryfetch($query);
		$branchhead = $resultfetch['branchhead'];
		$branch = $resultfetch['branch'];
		if($branchhead == 'yes')
		{
			$dealerpiece = " AND inv_mas_customer.branch = '".$branch."'";
		}
		else
		{
			$dealerpiece = " AND inv_mas_dealer.slno = '".$userid."'";
		}

		if($typelist == 'statustotal')
			$headingname = 'List of total customers with Implementation Status wise';
		elseif($typelist == 'status1')
			$headingname = 'List of customers with Implementation Status as Awaiting Branch Head Approval';
		elseif($typelist == 'status2')
			$headingname = 'List of customers with Implementation Status as Awaiting Co-ordinator Approval';
		elseif($typelist == 'status3')
			$headingname = 'List of customers with Implementation Status as Fowarded back to Branch Head';
		elseif($typelist == 'status4')
			$headingname = 'List of customers with Implementation Status as Implementation, Yet to be Assigned';
		elseif($typelist == 'status5')
			$headingname = 'List of customers with Implementation Status as Assigned For Implementation';
		elseif($typelist == 'status6')
			$headingname = 'List of customers with Implementation Status as Implementation in progess';
		elseif($typelist == 'status7')
			$headingname = 'List of customers with Implementation Status as Implementation Completed';
		elseif($typelist == 'status8')
			$headingname = 'List of customers with Implementation Status as Fowarded back to Sales Person';
			
		$startgrid .= '<table width="100%" border="0" cellspacing="0" cellpadding="5" class="table-border-grid">';
		$startgrid .= '<tr  class="tr-grid-header">';
		$startgrid .= '<td align="left" nowrap = "nowrap" class="td-border-grid">Sl No</td><td align="left" nowrap = "nowrap" class="td-border-grid">Customer name</td>
		<td nowrap = "nowrap" align="left" class="td-border-grid">Customer ID</td>
		<td nowrap = "nowrap" align="left" class="td-border-grid">Sales Person</td>
		<td align="left" nowrap = "nowrap" class="td-border-grid">Implementer</td>
		<td align="left" nowrap = "nowrap" class="td-border-grid">Invoice Date</td>
		<td align="left" nowrap = "nowrap" class="td-border-grid">Request Date</td>
		<td align="left" nowrap = "nowrap" class="td-border-grid">Branch Head Approved date</td>
		<td align="left" nowrap = "nowrap" class="td-border-grid">Co-ordinator Approved date</td>
		<td align="left" nowrap = "nowrap" class="td-border-grid">Assigned date</td>
		<td align="left" nowrap = "nowrap" class="td-border-grid">Completed date</td>
		<td align="left" nowrap = "nowrap" class="td-border-grid">Customization</td>
		<td align="left" nowrap = "nowrap" class="td-border-grid">Attendance Integration</td>
		<td align="left" nowrap = "nowrap" class="td-border-grid">No of Days</td>
		<td align="left" nowrap = "nowrap" class="td-border-grid">Implementation Status</td>
		<td align="left" nowrap = "nowrap" class="td-border-grid">Type of Implementation</td>
		<td align="left" nowrap = "nowrap" class="td-border-grid">&nbsp;</td>';
		$startgrid .= '</tr>';
		
		$statustype[0] = "imp_implementation.branchapproval = 'no' AND imp_implementation.branchreject = 'no' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'no' AND imp_implementation.implementationstatus = 'pending'";
		$statustype[1] = "imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'no' AND imp_implementation.implementationstatus = 'pending'";
		$statustype[2] = "imp_implementation.branchapproval = 'no' AND imp_implementation.coordinatorreject = 'yes' AND imp_implementation.coordinatorapproval = 'no' AND imp_implementation.implementationstatus = 'pending'";
		$statustype[3] = "imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'pending'";
		$statustype[4] = "imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'assigned'";
		$statustype[5] = "imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'progess'";
		$statustype[6] = "imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'completed'";
		$statustype[7] = "imp_implementation.branchapproval = 'no' AND imp_implementation.branchreject = 'yes'  AND imp_implementation.implementationstatus = 'pending'";
		
		//Fetch With Implementation Data
		for($i=0;$i<8;$i++)
		{
			$query1 = "select inv_mas_customer.slno as slnonumber, inv_mas_customer.businessname as custname,inv_mas_customer.customerid as cusid,
			inv_mas_dealer.businessname as salesperson, inv_mas_implementer.businessname as Implementer,
			left(inv_invoicenumbers.createddate,10) as invoicedate,left(imp_implementation.createddatetime,10) as requestdate,
			left(imp_implementation.branchapprovaldatetime,10) as branchappdate,
			left(imp_implementation.coordinatorappdatetime,10) as coordinatorappdate,
			left(imp_implementation.assignimpldatetime,10) as assignimpldate,
			left(dummy.visiteddate,10) as completeddate,imp_implementation.customizationapplicable as 
			customization, imp_implementation.attendanceapplicable as attendanceapplicable,
			imp_implementationdays.visiteddate as noofdays,imp_mas_implementationtype.imptype,imp_implementation.implementationstatus as impstatus,imp_implementation.slno as impslno
			from imp_implementation
			left join inv_mas_customer on inv_mas_customer.slno = imp_implementation.customerreference
			left join inv_mas_dealer on inv_mas_dealer.slno = imp_implementation.dealerid
			left join inv_mas_implementer on inv_mas_implementer.slno = imp_implementation.assignimplemenation
			left join imp_mas_implementationtype on imp_mas_implementationtype.slno = imp_implementation.implementationtype
			left join inv_invoicenumbers on inv_invoicenumbers.invoiceno = imp_implementation.invoicenumber
			left join (select imp_implementationdays.impref as impref, IFNULL(count(imp_implementationdays.visiteddate),'') as visiteddate from imp_implementationdays group by imp_implementationdays.impref) as  imp_implementationdays on imp_implementationdays.impref = imp_implementation.slno 
			left join (select slno,min(visiteddate) as visiteddate,impref from imp_implementationdays  where iccattachment = 'yes' group by impref)as dummy on dummy.impref = imp_implementation.slno 
			where ".$statustype[$i]." ".$finalliststatus.$dealerpiece.$dealer_typepiece.$typepiece.$categorypiece.$implementerpersonpiece.";";
			$result1 = runmysqlquery($query1);
			$i_n = 0;$slno1 = 0;$status_grid = "";
			if(mysqli_num_rows($result1) <> 0)
			{
				while($fetch1 = mysqli_fetch_array($result1))
				{
					$i_n++;
					if($typelist == 'statustotal')
					{
						$slno++;
						$countvalue = $slno;
					}
					else
					{
						 $slno1++;
						 $countvalue = $slno1;
					}
					$color;
					if($i_n%2 == 0)
						$color = "#edf4ff";
					else
						$color = "#f7faff";
					$status_grid .= '<tr bgcolor='.$color.'>';
					$status_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$countvalue."</td>";
					$status_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch1['custname'])."</td>";
					$status_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".cusidcombine($fetch1['cusid'])."</td>";
					$status_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch1['salesperson']."</td>";
					$status_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch1['Implementer']."</td>";
					$status_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch1['invoicedate'])."</td>";
					$status_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch1['requestdate'])."</td>";
					$status_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch1['branchappdate'])."</td>";
					$status_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch1['coordinatorappdate'])."</td>";
					$status_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch1['assignimpldate'])."</td>";
					$status_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch1['completeddate'])."</td>";
					$status_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".strtolower($fetch1['customization'])."</td>";
					$status_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".strtolower($fetch1['attendanceapplicable'])."</td>";
					$status_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".strtolower($fetch1['noofdays'])."</td>";
					$status_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".strtolower($fetch1['impstatus'])."</td>";
					$status_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".strtolower($fetch1['imptype'])."</td>";
					$status_grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"> <a onclick="viewhistory(\''.$fetch1['slnonumber'].'\',\''.$fetch1['impslno'].'\');" class="resendtext" style = "cursor:pointer"> View History >></a> </td>';
					$status_grid .= "</tr>";
				}
			}
			elseif(mysqli_num_rows($result1) == 0)
			{
				$status_grid .= '';
			}
			$finalstatus_grid[] = $status_grid;
		}
		$endgrid = "</table>";
		$grid = "";
		if($typelist == 'statustotal')
		{
			$grid .= $startgrid;
			for($j=0;$j<count($finalstatus_grid);$j++)
			{
				if($finalstatus_grid[$j] <> '')
					$grid .= $finalstatus_grid[$j];
			}
			$nodatagrid .= '<tr><td height ="20px" colspan="17" bgcolor="#FFFFD2" class="td-border-grid" style="font-weight:bold"><font color="#FF4F4F" >No Records</font></td></tr>';
			$grid .= $nodatagrid.$endgrid ;
		}
		for($k=0;$k<count($finalstatus_grid);$k++)
		{
			if($typelist == 'status'.($k+1))
			{
				if($finalstatus_grid[$k] <> '')
				{
					$grid = $startgrid.$finalstatus_grid[$k].$endgrid;
				}
				else
				{
					$nodatagrid .= '<tr><td height ="20px" colspan="14" bgcolor="#FFFFD2" class="td-border-grid" style="font-weight:bold"><font color="#FF4F4F" >No Records</font></td></tr>';
					$grid = $startgrid.$nodatagrid.$endgrid;
				}
			}
		}
			
		echo('1^'.$grid.'^'.$headingname);
	}
	break;
	
	case 'displaygridofimpl':
	{
		$slno = $_POST['slno'];
		$type = $_POST['type'];

		$startgrid .= '<table width="100%" border="0" cellspacing="0" cellpadding="5" class="table-border-grid">';
		$startgrid .= '<tr><td colspan="11"class="td-border-grid imp_fontheading" >'.$headingname.'</td></tr>';
		$startgrid .= '<tr  class="tr-grid-header">';
		$startgrid .= '<td align="left" nowrap = "nowrap" class="td-border-grid">Sl No</td><td align="left" nowrap = "nowrap" class="td-border-grid">Customer name</td><td nowrap = "nowrap" align="left" class="td-border-grid">Customer ID</td><td nowrap = "nowrap" align="left" class="td-border-grid">Sales Person</td><td align="left" nowrap = "nowrap" class="td-border-grid">Implementer</td><td align="left" nowrap = "nowrap" class="td-border-grid">Invoice Date</td><td align="left" nowrap = "nowrap" class="td-border-grid">Request Date</td><td align="left" nowrap = "nowrap" class="td-border-grid">Branch Head Approved date</td><td align="left" nowrap = "nowrap" class="td-border-grid">Co-ordinator Approved date</td><td align="left" nowrap = "nowrap" class="td-border-grid">Assigned date</td><td align="left" nowrap = "nowrap" class="td-border-grid">Completed date</td><td align="left" nowrap = "nowrap" class="td-border-grid">Customization</td><td align="left" nowrap = "nowrap" class="td-border-grid">Attendance Integration</td><td align="left" nowrap = "nowrap" class="td-border-grid">&nbsp;</td>';
		$startgrid .= '</tr>';
		
		
		if($type == 'totalofimpbranch')
		{
			$headingname = 'List of total customers with Due Implementation for Branch - Implementer Wise';
		}
		else
		{
			$query555 = "select inv_mas_implementer.businessname as Implementer,inv_mas_branch.branchname as branchname from
			inv_mas_implementer left join inv_mas_branch on inv_mas_implementer.branchid = inv_mas_branch.slno where inv_mas_implementer.slno = '".$slno."'";
			$resultfetch = runmysqlqueryfetch($query555);
			$headingname = 'List of customers with Due Implementation belonging to '.$resultfetch['Implementer'].' (Implementer) from '.$resultfetch['branchname'].'';
		}
		if($type <> 'totalofimpbranch')
		{
			//Fetch Completed Data
			$query1 = "select inv_mas_customer.slno as slnonumber, inv_mas_customer.businessname as custname,inv_mas_customer.customerid as cusid,
			inv_mas_dealer.businessname as salesperson, inv_mas_implementer.businessname as Implementer,
			left(inv_invoicenumbers.createddate,10) as invoicedate,left(imp_implementation.createddatetime,10) as requestdate,
			left(imp_implementation.branchapprovaldatetime,10) as branchappdate,
			left(imp_implementation.coordinatorappdatetime,10) as coordinatorappdate,
			left(imp_implementation.assignimpldatetime,10) as assignimpldate,
			left(dummy.visiteddate,10) as completeddate,imp_implementation.customizationapplicable as 
			customization, imp_implementation.attendanceapplicable as attendanceapplicable,imp_implementation.slno as impslno
			from imp_implementation left join inv_mas_customer on inv_mas_customer.slno = imp_implementation.customerreference
			left join inv_mas_dealer on inv_mas_dealer.slno = imp_implementation.dealerid
			left join inv_mas_implementer on inv_mas_implementer.slno = imp_implementation.assignimplemenation
			left join inv_invoicenumbers on inv_invoicenumbers.invoiceno = imp_implementation.invoicenumber
			left join (select slno,min(visiteddate) as visiteddate,impref 
			from imp_implementationdays  where iccattachment = 'yes' group by impref)as dummy on 
			dummy.impref = imp_implementation.slno where 
			inv_mas_implementer.slno = '".$slno."'";
			$result1 = runmysqlquery($query1);
		}
		elseif($type == 'totalofimpbranch')
		{
			$slnolist = str_replace('\\','',$slno );
			//Fetch Completed Data
			$query1 = "select inv_mas_customer.businessname as custname,inv_mas_customer.customerid as cusid,
			inv_mas_dealer.businessname as salesperson, inv_mas_implementer.businessname as Implementer,
			left(inv_invoicenumbers.createddate,10) as invoicedate,left(imp_implementation.createddatetime,10) as requestdate,
			left(imp_implementation.branchapprovaldatetime,10) as branchappdate,
			left(imp_implementation.coordinatorappdatetime,10) as coordinatorappdate,
			left(imp_implementation.assignimpldatetime,10) as assignimpldate,
			left(dummy.visiteddate,10) as completeddate,imp_implementation.customizationapplicable as 
			customization, imp_implementation.attendanceapplicable as attendanceapplicable,imp_implementation.slno as impslno
			from imp_implementation left join inv_mas_customer on inv_mas_customer.slno = imp_implementation.customerreference
			left join inv_mas_dealer on inv_mas_dealer.slno = imp_implementation.dealerid
			left join inv_mas_implementer on inv_mas_implementer.slno = imp_implementation.assignimplemenation
			left join inv_invoicenumbers on inv_invoicenumbers.invoiceno = imp_implementation.invoicenumber
			left join (select slno,min(visiteddate) as visiteddate,impref 
			from imp_implementationdays  where iccattachment = 'yes' group by impref)as dummy on 
			dummy.impref = imp_implementation.slno where 
			inv_mas_implementer.slno in(".$slnolist.")";
			$result1 = runmysqlquery($query1);
		}
		
		$i_n = 0;$slno1 = 0;
		if(mysqli_num_rows($result1) <> 0)
		{
			while($fetch1 = mysqli_fetch_array($result1))
			{
				$i_n++;
				$slno1++;
				$countvalue = $slno1;
				$color;
				if($i_n%2 == 0)
					$color = "#edf4ff";
				else
					$color = "#f7faff";
				
				$completeddata_grid .= '<tr bgcolor='.$color.'>';
				$completeddata_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$countvalue."</td>";
				$completeddata_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch1['custname'])."</td>";
				$completeddata_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".cusidcombine($fetch1['cusid'])."</td>";
				$completeddata_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch1['salesperson']."</td>";
				$completeddata_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch1['Implementer']."</td>";
				$completeddata_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch1['invoicedate'])."</td>";
				$completeddata_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch1['requestdate'])."</td>";
				$completeddata_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch1['branchappdate'])."</td>";
				$completeddata_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch1['coordinatorappdate'])."</td>";
				$completeddata_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch1['assignimpldate'])."</td>";
				$completeddata_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch1['completeddate'])."</td>";
				$completeddata_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".strtolower($fetch1['customization'])."</td>";
				$completeddata_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".strtolower($fetch1['attendanceapplicable'])."</td>";
				$completeddata_grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"> <a onclick="viewhistory(\''.$fetch1['slnonumber'].'\',\''.$fetch1['impslno'].'\');" class="resendtext" style = "cursor:pointer"> View History >></a> </td>';
				$completeddata_grid .= "</tr>";
			}
			
		}
		elseif(mysqli_num_rows($result1) == 0)
		{
			$completeddata_grid .= '<tr><td height ="20px" colspan="14" bgcolor="#FFFFD2" class="td-border-grid" style="font-weight:bold"><font color="#FF4F4F" >No Records</font></td></tr>';
			
			
		}
		$endgrid = "</table>";
		$grid = "";

		$grid = $startgrid.$completeddata_grid.$endgrid;
			
		echo('1^'.$grid.'^'.$headingname);
	}
	break;
	
	case 'displaygridofsalewise':
	{
		$slno = $_POST['slno'];
		$type = $_POST['type'];

		$startgrid .= '<table width="100%" border="0" cellspacing="0" cellpadding="5" class="table-border-grid">';
		$startgrid .= '<tr><td colspan="11"class="td-border-grid imp_fontheading" >'.$headingname.'</td></tr>';
		$startgrid .= '<tr  class="tr-grid-header">';
		$startgrid .= '<td align="left" nowrap = "nowrap" class="td-border-grid">Sl No</td><td align="left" nowrap = "nowrap" class="td-border-grid">Customer name</td><td nowrap = "nowrap" align="left" class="td-border-grid">Customer ID</td><td nowrap = "nowrap" align="left" class="td-border-grid">Sales Person</td><td align="left" nowrap = "nowrap" class="td-border-grid">Implementer</td><td align="left" nowrap = "nowrap" class="td-border-grid">Invoice Date</td><td align="left" nowrap = "nowrap" class="td-border-grid">Request Date</td><td align="left" nowrap = "nowrap" class="td-border-grid">Branch Head Approved date</td><td align="left" nowrap = "nowrap" class="td-border-grid">Co-ordinator Approved date</td><td align="left" nowrap = "nowrap" class="td-border-grid">Assigned date</td><td align="left" nowrap = "nowrap" class="td-border-grid">Completed date</td><td align="left" nowrap = "nowrap" class="td-border-grid">Customization</td><td align="left" nowrap = "nowrap" class="td-border-grid">Attendance Integration</td><td align="left" nowrap = "nowrap" class="td-border-grid">&nbsp;</td>';
		$startgrid .= '</tr>';
		
		
		if($type == 'totalofbranchsalewise')
		{
			$headingname = 'List of total customers with Due Implementation for Branch - Implementer Wise';
		}
		else
		{
			$query555 = "select inv_mas_dealer.businessname as salesperson,inv_mas_branch.branchname as branchname from
			inv_mas_dealer left join inv_mas_branch on inv_mas_branch.slno = inv_mas_dealer.branch where inv_mas_dealer.slno = '".$slno."'";
			$resultfetch = runmysqlqueryfetch($query555);
			$headingname = 'List of customers with Due Implementation belonging to '.$resultfetch['salesperson'].' (Sales) from '.$resultfetch['branchname'].'';
		}
		if($type <> 'totalofbranchsalewise')
		{
			//Fetch Completed Data
			$query1 = "select inv_mas_customer.slno as slnonumber, inv_mas_customer.businessname as custname,inv_mas_customer.customerid as cusid,
			inv_mas_dealer.businessname as salesperson, inv_mas_implementer.businessname as Implementer,
			left(inv_invoicenumbers.createddate,10) as invoicedate,left(imp_implementation.createddatetime,10) as requestdate,
			left(imp_implementation.branchapprovaldatetime,10) as branchappdate,
			left(imp_implementation.coordinatorappdatetime,10) as coordinatorappdate,
			left(imp_implementation.assignimpldatetime,10) as assignimpldate,
			left(dummy.visiteddate,10) as completeddate,imp_implementation.customizationapplicable as 
			customization, imp_implementation.attendanceapplicable as attendanceapplicable
			from imp_implementation left join inv_mas_customer on inv_mas_customer.slno = imp_implementation.customerreference
			left join inv_mas_dealer on inv_mas_dealer.slno = imp_implementation.dealerid
			left join inv_mas_implementer on inv_mas_implementer.slno = imp_implementation.assignimplemenation
			left join inv_invoicenumbers on inv_invoicenumbers.invoiceno = imp_implementation.invoicenumber
			left join (select slno,min(visiteddate) as visiteddate,impref 
			from imp_implementationdays  where iccattachment = 'yes' group by impref)as dummy on 
			dummy.impref = imp_implementation.slno where 
			inv_mas_dealer.slno = '".$slno."' and imp_implementation.customerreference!='' and imp_implementation.customerreference!='null'";
			$result1 = runmysqlquery($query1);
		}
		elseif($type == 'totalofbranchsalewise')
		{
			$slnolist = str_replace('\\','',$slno );
			//Fetch Completed Data
			$query1 = "select inv_mas_customer.slno as slnonumber, inv_mas_customer.businessname as custname,inv_mas_customer.customerid as cusid,
			inv_mas_dealer.businessname as salesperson, inv_mas_implementer.businessname as Implementer,
			left(inv_invoicenumbers.createddate,10) as invoicedate,left(imp_implementation.createddatetime,10) as requestdate,
			left(imp_implementation.branchapprovaldatetime,10) as branchappdate,
			left(imp_implementation.coordinatorappdatetime,10) as coordinatorappdate,
			left(imp_implementation.assignimpldatetime,10) as assignimpldate,
			left(dummy.visiteddate,10) as completeddate,imp_implementation.customizationapplicable as 
			customization, imp_implementation.attendanceapplicable as attendanceapplicable,imp_implementation.slno as impslno
			from imp_implementation left join inv_mas_customer on inv_mas_customer.slno = imp_implementation.customerreference
			left join inv_mas_dealer on inv_mas_dealer.slno = imp_implementation.dealerid
			left join inv_mas_implementer on inv_mas_implementer.slno = imp_implementation.assignimplemenation
			left join inv_invoicenumbers on inv_invoicenumbers.invoiceno = imp_implementation.invoicenumber
			left join (select slno,min(visiteddate) as visiteddate,impref 
			from imp_implementationdays  where iccattachment = 'yes' group by impref)as dummy on 
			dummy.impref = imp_implementation.slno where 
			inv_mas_dealer.slno in(".$slnolist.") and imp_implementation.customerreference!='' and imp_implementation.customerreference!='null'";
			$result1 = runmysqlquery($query1);
		}
		$i_n = 0;$slno1 = 0;
		if(mysqli_num_rows($result1) <> 0)
		{
			while($fetch1 = mysqli_fetch_array($result1))
			{
				$i_n++;
				$slno1++;
				$countvalue = $slno1;
				$color;
				if($i_n%2 == 0)
					$color = "#edf4ff";
				else
					$color = "#f7faff";
					
				
				$completeddata_grid .= '<tr bgcolor='.$color.'>';
				$completeddata_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$countvalue."</td>";
				$completeddata_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch1['custname'])."</td>";
				$completeddata_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".cusidcombine($fetch1['cusid'])."</td>";
				$completeddata_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch1['salesperson']."</td>";
				$completeddata_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch1['Implementer']."</td>";
				$completeddata_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch1['invoicedate'])."</td>";
				$completeddata_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch1['requestdate'])."</td>";
				$completeddata_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch1['branchappdate'])."</td>";
				$completeddata_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch1['coordinatorappdate'])."</td>";
				$completeddata_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch1['assignimpldate'])."</td>";
				$completeddata_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch1['completeddate'])."</td>";
				$completeddata_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".strtolower($fetch1['customization'])."</td>";
				$completeddata_grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".strtolower($fetch1['attendanceapplicable'])."</td>";
				$completeddata_grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"> <a onclick="viewhistory(\''.$fetch1['slnonumber'].'\',\''.$fetch1['impslno'].'\');" class="resendtext" style = "cursor:pointer"> View History >></a> </td>';
				$completeddata_grid .= "</tr>";
			}
			
		}
		elseif(mysqli_num_rows($result1) == 0)
		{
			$completeddata_grid .= '<tr><td height ="20px" colspan="14" bgcolor="#FFFFD2" class="td-border-grid" style="font-weight:bold"><font color="#FF4F4F" >No Records</font></td></tr>';
			
		}
		$endgrid = "</table>";
		$grid = "";

		$grid = $startgrid.$completeddata_grid.$endgrid;
			
		echo('1^'.$grid.'^'.$headingname);
	}
	break;
	case 'generatecustomerlist':
	{
		$query = "select * from inv_mas_dealer where slno = '".$userid."'";
		$resultfetch = runmysqlqueryfetch($query);
		$branchhead = $resultfetch['branchhead'];
		$branch = $resultfetch['branch'];

		if($branchhead == 'yes')
		{
			$dealerpiece = " inv_mas_customer.branch = '".$branch."'";
		}
		else
		{
			$dealerpiece = " imp_implementation.dealerid = '".$userid."'";
		}

		$query = "select  distinct inv_mas_customer.slno as slno,inv_mas_customer.businessname as businessname FROM imp_implementation left join inv_mas_customer on inv_mas_customer.slno = imp_implementation.customerreference where ".$dealerpiece." and imp_implementation.customerreference!='' and imp_implementation.customerreference!='null' order by businessname;";
		$result = runmysqlquery($query);
	
		$result = runmysqlquery($query);
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
	
	case 'implementation':
	{
		$cusid = $_POST['cusid'];
		$impslno = $_POST['impslno'];
		$lastimpslno = '';
		$query = "SELECT count(*) AS count FROM imp_implementation WHERE customerreference = '".$cusid."'";
		$fetch = runmysqlqueryfetch($query);
		$count = $fetch['count'];
		if($count > 0)
		{
			$query0 = "select max(imp_implementation.slno) as impslno,imp_implementation.customerreference from imp_implementation  where customerreference = '".$cusid."' group by imp_implementation.customerreference ;";
			$fetch0 = runmysqlqueryfetch($query0);

			$lastimpslno = (empty($impslno)) ? $fetch0['impslno'] : $impslno;

			$query = "SELECT inv_mas_customer.businessname as businessname, imp_implementation.slno,imp_implementation.customerreference, imp_implementation.invoicenumber,
			imp_implementation.podetails,imp_implementation.podetailspath, imp_implementation.numberofcompanies, imp_implementation.numberofmonths,
			imp_implementation.processingfrommonth, imp_implementation.shipinvoiceapplicable,
			imp_implementation.shipinvoiceremarks,imp_implementation.shipmanualapplicable,imp_implementation.invoicenumber,
			imp_implementation.shipmanualremarks,imp_implementation.attendancefilepath, imp_implementation.attendanceremarks, imp_implementation.attendancefiledate,inv_mas_dealer.businessname as attendancefileattachedby,
			imp_implementation.customizationapplicable, imp_implementation.customizationremarks,
			imp_implementation.customizationstatus, imp_implementation.implementationstatus , imp_implementation.webimplemenationapplicable,imp_implementation.webimplemenationremarks
			from imp_implementation 
			left join inv_mas_customer on inv_mas_customer.slno = imp_implementation.customerreference
			left join inv_mas_dealer on inv_mas_dealer.slno = imp_implementation.attendancefileattachedby  where imp_implementation.customerreference = '".$cusid."' and imp_implementation.slno = '".$lastimpslno."' ;";
			$fetch = runmysqlqueryfetch($query);
			
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','106','".date('Y-m-d').' '.date('H:i:s')."')";
			$eventresult = runmysqlquery($eventquery);
			
			//Assigned Implementation Grid Details  
			$query12 = "SELECT slno,assigneddate,remarks from imp_implementationdays  WHERE impref = '".$fetch['slno']."' order by  assigneddate is null and  createddatetime asc;";
			$assigngrid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
			$assigngrid .= '<tr class="tr-grid-header" align ="left"><td nowrap = "nowrap" class="td-border-grid" width="25%">Sl No</td><td nowrap = "nowrap"  width="35%" class="td-border-grid">Date</td><td width="40%" nowrap = "nowrap" class="td-border-grid">Remarks</td></tr>';
			$result11 = runmysqlquery($query12);
			$slno1 =0;
			while($fetch11 = mysqli_fetch_array($result11))
			{
				$slno1++;
				$assigngrid .= '<tr align ="left">';
				$assigngrid .= "<td nowrap='nowrap' class='td-border-grid'>".$slno1."</td>";
				if($fetch11['assigneddate'] == '')
					$assigngrid .= "<td nowrap='nowrap' class='td-border-grid'>Not Assigned</td>";
				else
					$assigngrid .= "<td nowrap='nowrap' class='td-border-grid'>".changedateformat($fetch11['assigneddate'])."</td>";
				$assigngrid .= "<td nowrap='nowrap' class='td-border-grid'>".gridtrim($fetch11['remarks'])."</td>";
				$assigngrid .= "</tr>";
			}
			$fetchcount = mysqli_num_rows($result11);
			if($fetchcount == '0')
				$assigngrid .= "<tr><td colspan ='3' class='td-border-grid'><div align='center'><font color='#FF0000'><strong>No Records to Display</strong></font></div></td></tr>";
				$assigngrid .= "</table>";
			
			//Visited Status Grid Details  
			$query14 = "SELECT imp_implementationdays.slno,assigneddate,imp_implementationdays.remarks,visitedstartflag,visitedendflag,impstatus,businessname from imp_implementationdays
			left join inv_mas_implementer on inv_mas_implementer.slno = imp_implementationdays.assignimplemenation  WHERE impref = '".$fetch['slno']."' order by assigneddate";
			$visitgrid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
			$visitgrid .= '<tr class="tr-grid-header" align ="left"><td nowrap = "nowrap" class="td-border-grid" >Visits</td><td nowrap = "nowrap" class="td-border-grid">Status</td><td nowrap = "nowrap" class="td-border-grid">Assign To</td></tr>';
			$result14 = runmysqlquery($query14);
			$slno2 =0;
			$fetchdetails = '';$customerflag = '';
			while($fetch14 = mysqli_fetch_array($result14))
			{
				$visitestartflag = $fetch14['visitedstartflag'];
				$visitedendflag = $fetch14['visitedendflag'];
				$status = $fetch14['impstatus'];
				$assignto = $fetch14['businessname'];
				$slno2++;
				
				$visitgrid .= '<tr align ="left">';
				$visitgrid .= "<td nowrap='nowrap' class='td-border-grid'>Visit".$slno2."</td>";
				$visitgrid .= "<td nowrap='nowrap' class='td-border-grid'>".$status ."</td>";
				$visitgrid .= "<td nowrap='nowrap' class='td-border-grid'>".$assignto ."</td>";
				$visitgrid .= "</tr>";
			}
			$fetchcount1 = mysqli_num_rows($result14);
			if($fetchcount1 == '0')
				$visitgrid .= "<tr><td colspan ='2' class='td-border-grid'><div align='center'><font color='#FF0000'><strong>No Records to Display</strong></font></div></td></tr>";
			
			$visitgrid .= "</table>";

			$visitgrid .= "<br><br>";
			$visitgrid .= '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
			$visitgrid .= '<tr class="tr-grid-header" align ="left"><td nowrap = "nowrap" class="td-border-grid" >Visits</td><td nowrap = "nowrap" class="td-border-grid">Data Backup</td><td nowrap = "nowrap" class="td-border-grid">ICC</td></tr>';
			$result17 = runmysqlquery($query14);
			$slno4 =0;
			while($fetch17 = mysqli_fetch_array($result17))
			{
				$slno4++;
				$databackup = $fetch17['databackuppath'];
				if(!empty($databackup))
				{
					$filename = explode('/',$fetch17['databackuppath']);
					$tddatabackup = '<td nowrap="nowrap" class="td-border-grid"><a onclick = "viewfilepath(\''.$databackup.'\',\'6\')"  style="text-decoration:none; cursor:pointer"><img src="../images/imax_zip_icon.gif"/>Databackup</a></td>';
				}
				else
				{
					$tddatabackup = "<td nowrap='nowrap' class='td-border-grid'>--</td>";
				}
				
				$iccattachment = $fetch17['iccattachmentpath'];
				if(!empty($iccattachment))
				{
					$iccfilename = explode('/',$fetch17['iccattachmentpath']);
					$tdicc = '<td nowrap="nowrap" class="td-border-grid"><a onclick = "viewfilepath(\''.$iccattachment.'\',\'7\')"  style="text-decoration:none; cursor:pointer"><img src="../images/imax_zip_icon.gif"/>ICC</a></td>';
				}
				else
				{
					$tdicc = "<td nowrap='nowrap' class='td-border-grid'>--</td>";
				}
				$visitgrid .= '<tr align ="left">';
				$visitgrid .= "<td nowrap='nowrap' class='td-border-grid'>Visit".$slno4."</td>";
				$visitgrid .= $tddatabackup ;
				$visitgrid .= $tdicc;
				$visitgrid .= "</tr>";
			}

			$fetchcount2 = mysqli_num_rows($result17);
			if($fetchcount2 == '0')
				$visitgrid .= "<tr><td colspan ='2' class='td-border-grid'><div align='center'><font color='#FF0000'><strong>No Records to Display</strong></font></div></td></tr>";

			$visitgrid .= "</table>";
				
			//Add-on's Grid Details  
			$query15 = "SELECT imp_addon.slno, imp_addon.customerid, imp_addon.refslno, imp_mas_addons.addonname as addon, imp_addon.remarks,imp_addon.addon as addonslno from imp_addon left join imp_mas_addons on imp_mas_addons.slno = imp_addon.addon where refslno  = '".$fetch['slno']."';";
			$addongrid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
			$addongrid .= '<tr class="tr-grid-header" align ="left"><td nowrap = "nowrap" class="td-border-grid" >Sl No</td><td nowrap = "nowrap" class="td-border-grid">Add-on</td><td nowrap = "nowrap" class="td-border-grid">Remarks</td></tr>';
			$result15 = runmysqlquery($query15);
			$slno3 =0;
			while($fetch15 = mysqli_fetch_array($result15))
			{
				$slno3++;
				$addongrid .= '<tr align ="left">';
				$addongrid .= "<td nowrap='nowrap' class='td-border-grid'>".$slno3."</td>";
				$addongrid .= "<td class='td-border-grid' >".$fetch15['addon']."</td>";
				$addongrid .= "<td class='td-border-grid' >".$fetch15['remarks']."</td>";
				$addongrid .= "</tr>";
			}
			$fetchcount2 = mysqli_num_rows($result15);
			if($fetchcount2 == '0')
				$addongrid .= "<tr><td colspan ='3' class='td-border-grid'><div align='center'><font color='#FF0000'><strong>No Records to Display</strong></font></div></td></tr>";
				$addongrid .= "</table>";
			
			//Fetch the Requriment Analysis Details
			$query16 = "select  imp_rafiles.slno,imp_rafiles.customerreference,imp_rafiles.attachfilepath,imp_rafiles.remarks,
			max(imp_rafiles.createddatetime) as rafcreateddatetime,imp_rafiles.createdby ,inv_mas_dealer.businessname as rafcreatedby from imp_rafiles  left join inv_mas_dealer on inv_mas_dealer.slno = imp_rafiles.createdby 
			where impref = '".$fetch['slno']."' group by customerreference";
			$result16 = runmysqlquery($query16);
			$count16 = mysqli_num_rows($result16);
			if($count16 > 0)
			{
				$fetch16 = runmysqlqueryfetch($query16);
				$filename = explode('/',$fetch16['attachfilepath']);
			}
			
			echo('1^'.$fetch['slno'].'^'.$fetch['customerreference'].'^'.$fetch['invoicenumber'].'^'.$fetch['podetails'].'^'.$fetch['numberofcompanies'].'^'.$fetch['processingfrommonth'].'^'.$fetch['numberofmonths'].'^'.strtoupper($fetch['shipinvoiceapplicable']).'^'.$fetch['shipinvoiceremarks'].'^'.$fetch['attendanceremarks'].'^'.$fetch['attendancefilepath'].'^'.changedateformatwithtime($fetch['attendancefiledate']).'^'.strtoupper($fetch['attendancefileattachedby']).'^'.strtoupper($fetch['customizationapplicable']).'^'.$fetch['customizationremarks'].'^'.$fetch['customizationstatus'].'^'.$fetch['implementationstatus'].'^'.strtoupper($fetch['webimplemenationapplicable']).'^'.$fetch['webimplemenationremarks'].'^'.$assigngrid.'^'.$fetchcount.'^'.$visitgrid.'^'.$addongrid.'^'.$fetch16['attachfilepath'].'^'.changedateformatwithtime($fetch16['rafcreateddatetime']).'^'.strtoupper($fetch16['rafcreatedby']).'^'.strtoupper($fetch['shipmanualapplicable']).'^'.$fetch['shipmanualremarks'].'^'.$fetch['invoicenumber'].'^'.$custgrid.'^'.$fetch['podetailspath']);
				
		}
		
	}
	break;
	
	case 'implemenationstatus':
	{
		$lastslno = $_POST['lastslno'];
		$query = "SELECT imp_implementation.branchapproval, imp_implementation.branchreject,imp_implementation.coordinatorreject, imp_implementation.coordinatorapproval, imp_implementation.implementationstatus, inv_mas_implementer.businessname from  imp_implementation left join inv_mas_implementer on inv_mas_implementer.slno = imp_implementation.assignimplemenation 
		where imp_implementation.slno = '".$lastslno."';";
		$fetch = runmysqlqueryfetch($query);
		
		$query1 = "Select iccattachmentpath,iccattachmentdate,inv_mas_implementer.businessname from imp_implementationdays
		left join  inv_mas_implementer on inv_mas_implementer.slno = imp_implementationdays.iccattachmentby
		where imp_implementationdays.impref = '".$lastslno."' and iccattachment = 'yes' order by imp_implementationdays.slno desc limit 1;;";
		$result = runmysqlquery($query1);
		$fetchcount = mysqli_num_rows($result);
		if($fetchcount <> 0)
			$result1 = runmysqlqueryfetch($query1);
		
		
		echo('1^'.$fetch['branchapproval'].'^'.$fetch['coordinatorreject'].'^'.$fetch['coordinatorapproval'].'^'.$fetch['implementationstatus'].'^'.$fetch['businessname'].'^'.$result1['iccattachmentpath'].'^'.$result1['iccattachmentdate'].'^'.$result1['businessname'].'^'.$fetch['branchreject']);
		
	}
	break;
	
	case 'customizationgrid':
	{
		$implastslno = $_POST['imprslno'];
		// $resultcount = "SELECT count(*) as count from imp_customizationfiles where imp_customizationfiles.impref = '".$implastslno."';";
		$resultcount = "SELECT count(customizationreffilepath) as count from imp_implementation where slno = '".$implastslno."';";
		$fetch10 = runmysqlqueryfetch($resultcount);
		$fetchresultcount = $fetch10['count'];;
		$grid = '<table width="100%" cellpadding="2" cellspacing="0" class="table-border-grid">';
		$grid .= '<tr class="tr-grid-header" align ="left"><td nowrap = "nowrap" class="td-border-grid" >Sl No</td><td nowrap = "nowrap" class="td-border-grid">Downloadlink</td></tr>';
		if($fetchresultcount <> 0)
		{
			// $query = "SELECT imp_customizationfiles.slno,imp_customizationfiles.remarks,imp_customizationfiles.attachfilepath from imp_customizationfiles  WHERE imp_customizationfiles.impref = '".$implastslno."' order by createddatetime DESC ;";
			$query = "SELECT customizationreffilepath from imp_implementation  WHERE slno = '".$implastslno."';";
			$i_n = 0;
			$result = runmysqlquery($query);
			while($fetch = mysqli_fetch_array($result))
			{
				$i_n++;
				$slno++;
				$color;
				if($i_n%2 == 0)
					$color = "#edf4ff";
				else
					$color = "#f7faff";
				$grid .= '<tr bgcolor='.$color.'  align ="left">';
				$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$slno."</td>";
				// $grid .= "<td nowrap='nowrap' class='td-border-grid'>".gridtrim($fetch['remarks'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid'><div align = 'center'><img src='../images/download-arrow.gif'  style='cursor:pointer' onclick = viewfilepath('".$fetch['customizationreffilepath']."','1') /></div></td>";
				$grid .= "</tr>";
			}
			$grid .= "</table>";
		}
		else
		{
			$grid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"  ><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font></td></tr></table>';
		}
		
		
		
		echo '1^'.$grid;
	}
	break;


	case 'rafgrid':
	{
		$implastslno = $_POST['imprslno'];
		$resultcount = "SELECT count(*) as count from imp_rafiles where imp_rafiles.impref = '".$implastslno."';";
		$fetch10 = runmysqlqueryfetch($resultcount);
		$fetchresultcount = $fetch10['count'];;
		$grid = '<table width="100%" cellpadding="2" cellspacing="0" class="table-border-grid">';
		$grid .= '<tr class="tr-grid-header" align ="left"><td nowrap = "nowrap" class="td-border-grid" >Sl No</td><td nowrap = "nowrap" class="td-border-grid">Remarks</td><td nowrap = "nowrap" class="td-border-grid">Downloadlink</td></tr>';
		if($fetchresultcount <> 0)
		{
			$query = "SELECT imp_rafiles.slno,imp_rafiles.remarks,imp_rafiles.attachfilepath from imp_rafiles  WHERE imp_rafiles.impref = '".$implastslno."' order by createddatetime DESC ;";
			$i_n = 0;
			$result = runmysqlquery($query);
			while($fetch = mysqli_fetch_array($result))
			{
				$i_n++;
				$slno++;
				$color;
				if($i_n%2 == 0)
					$color = "#edf4ff";
				else
					$color = "#f7faff";
				$grid .= '<tr bgcolor='.$color.'  align ="left">';
				$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$slno."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid'>".gridtrim($fetch['remarks'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid'><div align = 'center'><img src='../images/download-arrow.gif'  style='cursor:pointer' onclick = viewfilepath('".$fetch['attachfilepath']."','4') /></div></td>";
				$grid .= "</tr>";
			}
			$grid .= "</table>";
		}
		else
		{
			$grid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"  ><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font></td></tr></table>';
		}
		
		
		
		echo '1^'.$grid.'^'.$fetchresultcount;
	}
	break;
		
	case 'invoicegrid':
	{
		$invoiceno = $_POST['invoiceno'];
		$lastslno = $_POST['lastslno'];

		$query = "SELECT * from imp_implementation where imp_implementation.slno = '".$lastslno."';";
		$fetch = runmysqlqueryfetch($query);
		$producttype = $fetch['producttype'];

		if($producttype == 'matrix')
			$query121 = "select  count(*) as count from inv_matrixinvoicenumbers where invoiceno = '".$invoiceno."';";
		else
			$query121 = "select  count(*) as count from inv_invoicenumbers where invoiceno = '".$invoiceno."';";
		$fetch123 = runmysqlqueryfetch($query121);
		if($fetch123['count'] > 0)
		{
			if($producttype == 'matrix')
				$query156 = "select slno from inv_matrixinvoicenumbers where invoiceno = '".$invoiceno."'";
			else
				$query156 = "select slno from inv_invoicenumbers where inv_invoicenumbers.invoiceno = '".$invoiceno."'";
			$result = runmysqlqueryfetch($query156);
			$invoiceslno = $result['slno'];
			echo('2^'.$invoiceslno.'^'.$producttype);
			exit;
		}
		else
		{
			$query156 = "select distinct inv_mas_product.productname as product, imp_cfentries.usagetype,  imp_cfentries.purchasetype, inv_mas_scratchcard.scratchnumber, imp_cfentries.customerid from imp_cfentries 
		left join inv_mas_product on inv_mas_product.productcode = imp_cfentries.productcode left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = imp_cfentries.cardid where imp_cfentries.invoiceno = '".$invoiceno."'";
			$result = runmysqlquery($query156);
			$invoicegrid = '<table width="100%" border="0" cellspacing="0" cellpadding="3" class="table-border-grid">';
			$invoicegrid .='<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Slno</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Usage Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Purchase Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Pin Number</td></tr>';
			$valuecount = 0;
			while($fetchres = mysqli_fetch_array($result))
			{
					$slno++;
					$i_n++;
					if($i_n%2 == 0)
					$color = "#edf4ff";
					else
					$color = "#f7faff";
					$invoicegrid .= '<tr class="gridrow1"  bgcolor='.$color.' align="left">';
					$invoicegrid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$slno.'</td>';
					$invoicegrid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$fetchres['product'].'</td>';
					$invoicegrid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$fetchres['usagetype'].'</td>';
					$invoicegrid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$fetchres['purchasetype'].'</td>';
					$invoicegrid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$fetchres['scratchnumber'].'</td>';
					$invoicegrid .= '</tr>';
			}
			$invoicegrid .= '</table>';
			
			echo('1^'.$invoicegrid);
			exit;
		}
		
	}
	break;
		
	case 'customergridtoform':
	{
		$cusid = $_POST['cusid'];
		$impslno = $_POST['impslno'];
		$query = "select distinct inv_mas_customer.slno,inv_mas_customer.businessname from inv_mas_customer  where inv_mas_customer.slno = '".$cusid."' order  by inv_mas_customer.businessname;";
		$result = runmysqlquery($query);
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
		
	case 'searchlist':
	{
		$state = $_POST['state'];
		
		$dealer = $_POST['dealer'];
		$type = $_POST['type'];
		$statuslist = $_POST['statuslist'];
		$statuslistsplit = explode(',',$statuslist);
		$implementer = $_POST['implementer'];
		$dealer_typepiece = ($dealer == "")?(""):(" AND inv_mas_dealer.slno = '".$dealer."' ");
		if($type == 'Not Selected')
		{
			$typepiece = ($type == "")?(""):(" AND inv_mas_customer.type = '' ");
		}
		else
		{
			$typepiece = ($type == "")?(""):(" AND inv_mas_customer.type = '".$type."' ");
		}
		if($category == 'Not Selected')
		{
			$categorypiece = ($category == "")?(""):(" AND inv_mas_customer.category = '' ");
		}
		else
		{
			$categorypiece = ($category == "")?(""):(" AND inv_mas_customer.category = '".$category."' ");
		}		
		$implementerpersonpiece = ($implementer == "")?(""):(" AND inv_mas_implementer.slno = '".$implementer."' ");
		$countsummarize = count($statuslistsplit);
		for($i = 0; $i<$countsummarize; $i++)
		{
			if($i < ($countsummarize-1))
					$appendor = 'or'.' ';
				else
					$appendor = '';
			switch($statuslistsplit[$i])
			{
				case 'statuslist1' :
				{
					$statuspiece = " imp_implementation.branchapproval = 'no' AND imp_implementation.branchreject = 'yes' AND imp_implementation.implementationstatus = 'pending' ";
				}
				break;
				case 'statuslist2' :
				{
					$statuspiece = " imp_implementation.branchapproval = 'no' AND imp_implementation.branchreject = 'no' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'no' AND  imp_implementation.implementationstatus = 'pending'";
				}
				break;
				case 'statuslist3' :
				{
					$statuspiece = " imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'no' AND imp_implementation.implementationstatus = 'pending' ";
				}
				break;
				case 'statuslist4' :
				{
					$statuspiece = " imp_implementation.branchapproval = 'no' AND imp_implementation.coordinatorreject = 'yes' AND imp_implementation.coordinatorapproval = 'no' AND imp_implementation.implementationstatus = 'pending' ";
				}
				break;
				case 'statuslist5' :
				{
					$statuspiece = " imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'pending' ";
				}
				break;
				case 'statuslist6' :
				{
					$statuspiece = " imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'assigned' ";
				}
				break;
				case 'statuslist7' :
				{
					$statuspiece = " imp_implementation.branchapproval = 'yes'  AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'progess' ";
				}
				break;
				case 'statuslist8' :
				{
					$statuspiece = " imp_implementation.branchapproval = 'yes'  AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'completed' ";
				}
				break;
			}
			$finalstatuslist .= ''.'('.$statuspiece.')'.'  '.$appendor.'';
			
		}
		if($finalstatuslist != '')
		{
			$finalliststatus = ' AND'.'('.$finalstatuslist.')';
		}
		else
		{
			$finalliststatus = "";
		}
		$query = "select branchhead,branch,category from inv_mas_dealer left join inv_mas_region on inv_mas_dealer.region = inv_mas_region.slno where inv_mas_dealer.slno = '".$userid."'";
		$resultfetch = runmysqlqueryfetch($query);
		$branchhead = $resultfetch['branchhead'];
		$branch = $resultfetch['branch'];

		if($branchhead == 'yes')
		{
			$dealerpiece = " AND inv_mas_customer.branch = '".$branch."'";
		}
		else
		{
			$dealerpiece = " AND inv_mas_dealer.slno = '".$userid."'";
		}

		$searchcustomerlistarray = array();
		$count = 0;
		$query0 = "select max(imp_implementation.slno) as impslno,inv_mas_customer.businessname,imp_implementation.customerreference from imp_implementation left join inv_mas_customer on imp_implementation.customerreference = inv_mas_customer.slno where inv_mas_customer.slno <> '99999999999' group by imp_implementation.customerreference ORDER BY businessname;";
		$result0 = runmysqlquery($query0);

		$lastimpslno = (empty($impslno)) ? $fetch0['impslno'] : $impslno;
		while($fetch0 = mysqli_fetch_array($result0))
		{
			$query = "select distinct inv_mas_customer.slno,inv_mas_customer.businessname from imp_implementation
			left join inv_mas_customer on inv_mas_customer.slno = imp_implementation.customerreference
			left join inv_mas_implementer on inv_mas_implementer.slno = imp_implementation.assignimplemenation 
			left join inv_mas_dealer on imp_implementation.dealerid = inv_mas_dealer.slno 
			where imp_implementation.slno <> '99999999' and imp_implementation.slno = '".$fetch0['impslno']."'  ".$finalliststatus.$dealerpiece.$dealer_typepiece.$typepiece.$categorypiece.$implementerpersonpiece." ORDER BY inv_mas_customer.businessname;";
			$result = runmysqlquery($query);
			// left join imp_cfentries on right(imp_cfentries.customerid,5) = imp_implementation.customerreference
			
			while($fetch = mysqli_fetch_array($result))
			{
				$searchcustomerlistarray[$count] = $fetch['businessname'].'^'.$fetch['slno'];
				$count++;
				
			}
		}
		echo(json_encode($searchcustomerlistarray));
		//echo($query);
	}
	break;
	
}

?>