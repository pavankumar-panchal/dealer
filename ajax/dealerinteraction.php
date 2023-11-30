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
$query = "Select slno,businessname,dealerusername from inv_mas_dealer where slno = '".$userid."'";
$fetch = runmysqlqueryfetch($query);
$businessname =strtoupper($fetch['businessname']); 
$dealerusername = $fetch['slno'];

switch($switchtype)
{
	case 'save':
	{
		$customerreference = $_POST['customerreference'];
		$interactiondate = datetimelocal('d-m-Y').' '.datetimelocal('H:i:s');
		$remarks = $_POST['remarks'];
		$interactiontype = $_POST['interactiontype'];
		$query = "Select businessname,slno from inv_mas_dealer where slno = '".$userid."'";
	  	$fetch = runmysqlqueryfetch($query);
		$dealerusername = $fetch['slno']; 
		if($lastslno == '')
		{
			$query = "Insert into inv_customerinteraction(customerid,createddate,createdby,remarks,interactiontype,modulename,lastmodifieddate,lastmodifiedby,createdip,lastmodifiedip) 
values ('".$customerreference."','".changedateformatwithtime($interactiondate)."','".$dealerusername."',
'".$remarks."','".$interactiontype."','dealer_module','".changedateformatwithtime($interactiondate)."','".$dealerusername."','".$_SERVER['REMOTE_ADDR']."','".$_SERVER['REMOTE_ADDR']."');";

			$result = runmysqlquery($query);
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','117','".date('Y-m-d').' '.date('H:i:s')."','".$customerreference."')";
			$eventresult = runmysqlquery($eventquery);
		}
		else
		{
			$query = "UPDATE inv_customerinteraction SET remarks = '".$remarks."',interactiontype = '".$interactiontype."',
lastmodifieddate ='".date('Y-m-d').' '.date('H:i:s')."' ,lastmodifiedby ='".$dealerusername."',lastmodifiedip = '".$_SERVER['REMOTE_ADDR']."' WHERE slno = '".$lastslno."'";
			$result = runmysqlquery($query);
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','118','".date('Y-m-d').' '.date('H:i:s')."','".$customerreference."')";
			$eventresult = runmysqlquery($eventquery);
		}
		
		$responsearray = array();
		$responsearray['errorcode'] = "1";
		$responsearray['errorcodemsg'] = "Dealer Interaction Record '".$customerreference."' Saved Successfully";
		echo(json_encode($responsearray));
		
	}
	break;

	case 'delete':
	{
		$lastslno = $_POST['lastslno'];
		$query = "DELETE FROM inv_customerinteraction WHERE slno = '".$lastslno."'";
		$result = runmysqlquery($query);
		
		$responsearray2 = array();
		$responsearray2['errorcode'] = "2";
		$responsearray2['errorcodemsg'] = "Dealer Interaction Record Deleted Successfully";
		echo(json_encode($responsearray2));
	}
	break;
	
	case 'generatecustomerlist':
	{
		$query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode,inv_mas_dealer.telecaller,inv_mas_dealer.branch,inv_mas_dealer.region
from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
 where inv_mas_dealer.slno = '".$userid."';";
		$fetch = runmysqlqueryfetch($query);
		$relyonexecutive = $fetch['relyonexecutive'];
		$telecaller = $fetch['telecaller'];
		$branch = $fetch['branch'];
		$district = $fetch['district'];
		$state = $fetch['statecode'];
		$region = $fetch['region'];
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
					$query = "select slno as slno, businessname as businessname from inv_mas_customer where region = ('1' or '3') order by businessname;";
					$result = runmysqlquery($query);
				}
				else
				{
					$query = "select slno as slno, businessname as businessname from inv_mas_customer where branch = '".$branch."' order by businessname;";
					$result = runmysqlquery($query);
				}
			}
		}
		$responsegridarray = array();
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$responsegridarray[$count] = $fetch['businessname'].'^'.$fetch['slno'];
			$count++;
		}
		echo(json_encode($responsegridarray));
	}
	break;
	
	case 'generategrid':
	{
		$startlimit = $_POST['startlimit'];
		$slno = $_POST['slno'];
		$showtype = $_POST['showtype'];
		$customerreference = $_POST['customerreference'];
		$query ="SELECT distinct count(*) as count from inv_customerinteraction  where inv_customerinteraction.customerid = '".$customerreference."'";  
		$fetch1 = runmysqlqueryfetch($query);
		if($fetch1['count'] > 0)
		{
			$query1 = "SELECT inv_customerinteraction.slno as slno,inv_mas_customer.businessname as businessname,
inv_customerinteraction.createddate as createddate,inv_customerinteraction.createdby as createdby,
inv_customerinteraction.remarks as remarks,inv_customerinteraction.modulename as  modulename,
inv_mas_interactiontype.interactiontype as  interactiontype FROM inv_customerinteraction 
LEFT JOIN inv_mas_customer on inv_mas_customer.slno = inv_customerinteraction.customerid 
left join inv_mas_interactiontype on inv_mas_interactiontype.slno = inv_customerinteraction.interactiontype
WHERE inv_mas_customer.slno = '".$customerreference."' order by createddate desc";
			$fetch6 = runmysqlquery($query1);
			$fetchresultcount = mysqli_num_rows($fetch6);
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
				$startlimit = $slno;
				$slno = $slno;
			}
		$query = "SELECT inv_customerinteraction.slno as slno,inv_mas_customer.businessname as businessname,
inv_customerinteraction.createddate as createddate,inv_customerinteraction.createdby as createdby,
inv_customerinteraction.remarks as remarks,inv_customerinteraction.modulename as  modulename,
inv_mas_interactiontype.interactiontype as interactiontype, inv_customerinteraction.lastmodifieddate as lastmodifieddate,inv_customerinteraction.lastmodifiedby as lastmodifiedby
FROM inv_customerinteraction LEFT JOIN inv_mas_customer on inv_mas_customer.slno = inv_customerinteraction.customerid 
left join inv_mas_interactiontype on inv_mas_interactiontype.slno = inv_customerinteraction.interactiontype
WHERE inv_mas_customer.slno = '".$customerreference."' order by createddate desc  LIMIT ".$startlimit.",".$limit.";";
			if($startlimit == 0)
			{
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid" style="text-align:left">';
			$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid">Sl No</td><td nowrap = "nowrap" class="td-border-grid">Customer Name</td><td nowrap = "nowrap" class="td-border-grid">Date</td><td nowrap = "nowrap" class="td-border-grid">Remarks</td><td nowrap = "nowrap" class="td-border-grid">Entered By</td><td nowrap = "nowrap" class="td-border-grid">Entered Through</td><td nowrap = "nowrap" class="td-border-grid">Interaction Category</td><td nowrap = "nowrap" class="td-border-grid">Last Modified Date</td></tr>';
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
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".changedateformatwithtime($fetch['createddate'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$fetch['remarks']."</td>";
			if($fetch['modulename'] == 'dealer_module')
			{
				$query2 ="select inv_mas_dealer.businessname as businessname from inv_mas_dealer 
left join inv_customerinteraction on inv_customerinteraction.createdby = inv_mas_dealer.slno 
WHERE inv_mas_dealer.slno = '".$fetch['createdby']."'";
				$fetchresult = runmysqlqueryfetch($query2);
				$businessname  = $fetchresult['businessname'];
				$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$businessname."</td>";
			}
			else
			{
				$query1 ="select fullname from inv_mas_users left join inv_customerinteraction on inv_customerinteraction .createdby = inv_mas_users.slno WHERE inv_mas_users.slno = '".$fetch['createdby']."'";
				$resultfetch = runmysqlqueryfetch($query1);
				$enteredby  = $resultfetch['fullname'];
				$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$enteredby."</td>";
			}
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".modulegropname($fetch['modulename'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$fetch['interactiontype']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>&nbsp;".changedateformatwithtime($fetch['lastmodifieddate'])."</td>";
			$grid .= "</tr>";

		}
		$grid .= "</table>";

			$fetchcount = mysqli_num_rows($result);
			if($slno >= $fetchresultcount)
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2" align ="left"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
			$linkgrid .= '<table><tr><td class="resendtext"><div align ="left" style="padding-right:10px"><a onclick ="cusmoregrid(\''.$startlimit.'\',\''.$slno.'\',\'more\');">Show More Records >></a><a onclick ="cusmoregrid(\''.$startlimit.'\',\''.$slno.'\',\'all\');" class="resendtext1"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
			
			$responsearray1 = array();
			$responsearray1['errorcode'] = "1";
			$responsearray1['grid'] = $grid;
			$responsearray1['fetchresultcount'] = $fetchresultcount;
			$responsearray1['linkgrid'] = $linkgrid;
			echo(json_encode($responsearray1));
			}
			else
			{
				$responsearray4 = array();
				$responsearray4['errorcode'] = "2";
				$responsearray4['grid'] = "No datas found to be displayed.";
				echo(json_encode($responsearray4));
			}	
		
	}
	break;
	
	case 'gridtoform':
	{
		$cusinteractionslno = $_POST['cusinteractionslno'];
		$query1 = "SELECT count(*) as count from inv_customerinteraction where slno = '".$cusinteractionslno."'";
		$fetch1 = runmysqlqueryfetch($query1);
		if($fetch1['count'] > 0)
		{
			$query = "SELECT inv_mas_customer.slno as slno,inv_mas_customer.businessname as businessname,
inv_customerinteraction.createddate as createddate,inv_customerinteraction.createdby as createdby,
inv_customerinteraction.remarks as remarks ,inv_customerinteraction.modulename as modulename ,
inv_mas_interactiontype.slno as  interactiontype
FROM inv_customerinteraction LEFT JOIN inv_mas_customer on inv_mas_customer.slno = inv_customerinteraction.customerid
left join inv_mas_interactiontype on inv_mas_interactiontype.slno = inv_customerinteraction.interactiontype
WHERE inv_customerinteraction.slno = '".$cusinteractionslno."';";
			$fetch = runmysqlqueryfetch($query);
			$createddate = changedateformatwithtime($fetch['createddate']);
			$modulename = $fetch['modulename'];
			if($modulename == 'dealer_module' )
			{
				$query2 ="select inv_mas_dealer .businessname as businessname,inv_mas_dealer.slno as dealerid from inv_mas_dealer left join inv_customerinteraction on inv_customerinteraction.createdby = inv_mas_dealer.slno 
WHERE inv_customerinteraction.slno = '".$cusinteractionslno."'";
				$fetchresult = runmysqlqueryfetch($query2);
				$businessname  = $fetchresult['businessname'];
				$dealerid  = $fetchresult['dealerid'];
				if($dealerusername == $dealerid)
				{
					$responsearray5 = array();
					$responsearray5['errorcode'] = "1";
					$responsearray5['businessname'] = $fetch['businessname'];
					$responsearray5['createddate'] = $createddate;
					$responsearray5['dealerbusinessname'] = $businessname;
					$responsearray5['remarks'] = $fetch['remarks'];
					$responsearray5['slno'] = $fetch['slno'];
					$responsearray5['modulename'] = modulegropname($modulename);
					$responsearray5['interactiontype'] = $fetch['interactiontype'];
					echo(json_encode($responsearray5));

					//echo('1^'.$fetch['businessname'].'^'.$createddate.'^'.$businessname.'^'.$fetch['remarks'].'^'.$fetch['slno'].'^'.modulegropname($modulename).'^'.$fetch['interactiontype']);
					break;
				}
				else
				{
					$responsearray6 = array();
					$responsearray6['errorcode'] = "3";
					$responsearray6['businessname'] = $fetch['businessname'];
					$responsearray6['createddate'] = $createddate;
					$responsearray6['dealerbusinessname'] = $businessname;
					$responsearray6['remarks'] = $fetch['remarks'];
					$responsearray6['slno'] = $fetch['slno'];
					$responsearray6['modulename'] = modulegropname($modulename);
					$responsearray6['interactiontype'] = $fetch['interactiontype'];
					echo(json_encode($responsearray6));
					
					//echo('3^'.$fetch['businessname'].'^'.$createddate.'^'.$businessname.'^'.$fetch['remarks'].'^'.$fetch['slno'].'^'.modulegropname($modulename).'^'.$fetch['interactiontype']);
					break;
				}
			}
			else
			{
				$query1 = "select inv_mas_users.fullname as enteredby from inv_mas_users left join inv_customerinteraction on inv_customerinteraction .createdby = inv_mas_users.slno WHERE inv_customerinteraction.slno = '".$cusinteractionslno."'";
				$fetch1 = runmysqlqueryfetch($query1);
				$enteredby =$fetch1['enteredby'];
				
				$responsearray7 = array();
				$responsearray7['errorcode'] = "2";
				$responsearray7['businessname'] = $fetch['businessname'];
				$responsearray7['createddate'] = $createddate;
				$responsearray7['dealerbusinessname'] = $businessname;
				$responsearray7['remarks'] = $fetch['remarks'];
				$responsearray7['slno'] = $fetch['slno'];
				$responsearray7['modulename'] = modulegropname($modulename);
				$responsearray7['interactiontype'] = $fetch['interactiontype'];
				echo(json_encode($responsearray7));	
							
				//echo('2^'.$fetch['businessname'].'^'.$createddate.'^'.$enteredby.'^'.$fetch['remarks'].'^'.$fetch['slno'].'^'.modulegropname($modulename).'^'.$fetch['interactiontype']);
			}
		}
		else
		{
			echo(''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.'');
		}
	}
	break;
	
	case 'displaycustomer':
	{
		$customerreference = $_POST['customerreference'];
		$query = "SELECT businessname from inv_mas_customer where slno = '".$customerreference."';";
		$fetch = runmysqlqueryfetch($query);
		
		$responsearray8 = array();
		$responsearray8['customerreference'] = $customerreference;
		$responsearray8['businessname'] = $fetch['businessname'];
		echo(json_encode($responsearray8));
		//echo($fetch['businessname'].'^'.$customerreference);
	}
	break;
	
	case 'getcurrentdate':
	{
			$date = datetimelocal('d-m-Y')."(".datetimelocal('H:i').")";
			echo($date);
	}
		break;
}

?>