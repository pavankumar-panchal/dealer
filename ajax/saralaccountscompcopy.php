<?php
ob_start("ob_gzhandler");

include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');
include('../inc/checksession.php');

$lastslno = $_POST['lastslno'];
$switchtype = $_POST['switchtype'];


if(imaxgetcookie('dealeruserid') <> '') 
$userid = imaxgetcookie('dealeruserid');
else
{ 
	echo(json_encode('Thinking to redirect'));
	exit;
}

$query = "select * from inv_mas_dealer where slno = '".$userid."';";
$resultfetch = runmysqlqueryfetch($query);
$loggedindealername = $resultfetch['businessname'];

switch($switchtype)
{
	case 'customerdetailstoform':
	{
		$lastslno = $_POST['lastslno'];
		$query = "select * from inv_mas_customer where slno = '".$lastslno."';";
		$result = runmysqlquery($query);
		if(mysqli_fetch_row($result) > 0)
		{
			$fetchresult = runmysqlqueryfetch($query);
			$customerid = $fetchresult['customerid'];
		}
		else
		{
			$customerid = '';
		}
		echo('1^'.$customerid);
	}
	break;
	case 'generatepin':
	{
		$lastslno = substr($_POST['lastslno'],12,5); 
		if($lastslno == '')
			$result = 'true';
		else
			$result = checkcustomer($lastslno);
		if($result == 'true')
		{
		  $remarks = $_POST['remarks'];
		  //Get a New PIN 
		  $query = "SELECT attachPIN() as cardid;";
		  $result = runmysqlqueryfetch($query);
		  $cardid = $result['cardid'];
		  
		  //Insert PIN details in dealercard table
		  $query2 = "INSERT INTO inv_dealercard ( dealerid, cardid, productcode, date, cuscardremarks, cusbillnumber, usagetype, purchasetype, userid,scheme,initialusagetype,initialpurchasetype,initialproduct,initialdealerid,customerreference,cuscardattacheddate,usertype,cuscardattachedby) values('".$userid."', '".$cardid."', '213', '".datetimelocal('Y-m-d')." ".datetimelocal('H:i:s')."', '".$remarks."', '', 'singleuser', 'new', '2','6','singleuser','new','213','".$userid."','".$lastslno."','".datetimelocal('Y-m-d')." ".datetimelocal('H:i:s')."','dealer','".$userid."');";
		  $result2 = runmysqlquery($query2);
		  
		  $query3 ="select * from inv_mas_scratchcard where cardid = '".$cardid."';";
		  $resultfetch3 = runmysqlqueryfetch($query3);
		  $pinno = $resultfetch3['scratchnumber'];
		  
		  if($lastslno <> '')
		  {
			$query4 = "select * from inv_mas_customer where slno = '".$lastslno."';";
			$resultfetch4 = runmysqlqueryfetch($query4);
			$customerid = cusidcombine($resultfetch4['customerid']);
		  }
		  else
		  	$customerid = '(Not Available)';
		  
		  
		  $query6 = "select * from inv_mas_dealer where slno = '".$userid."'";
		  $resultfetch6 = runmysqlqueryfetch($query6);
		  $dealername = $resultfetch6['businessname'];
		  $contactperson = $resultfetch6['contactperson'];
		  $emailid = $resultfetch6['emailid'];
		  $tlemailid = $resultfetch6['tlemailid'];
		  $mgremailid = $resultfetch6['mgremailid'];
		  $hoemailid = $resultfetch6['hoemailid'];
		  
		 // $emailid = 'rashmi.hk@relyonsoft.com';
  
		  $emailarray = explode(',',$emailid);
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
		  
		  $bcceallemailid = $tlemailid.','.$mgremailid.','.$hoemailid;
		  $bccemailarray = explode(',',$bcceallemailid);
		  $bccemailcount = count($bccemailarray);
		  for($i = 0; $i < $bccemailcount; $i++)
		  {
			  if(checkemailaddress($bccemailarray[$i]))
			  {
				  if($i == 0)
					  $bccemailids[$contactperson] = $bccemailarray[$i];
				  else
					  $bccemailids[$bccemailarray[$i]] = $bccemailarray[$i];
			  }
		  }
			
			
		  $fromname = "Relyon";
		  $fromemail = "imax@relyon.co.in";
		  require_once("../inc/RSLMAIL_MAIL.php");
		  $msg = file_get_contents("../mailcontents/saralaccountscompcopy.htm");
		  $textmsg = file_get_contents("../mailcontents/saralaccountscompcopy.txt");
		  $subject = 'Saral Accounts: Complimentary copy PIN ('.$cardid.')';
		  $date = datetimelocal('d-m-Y');
		  $array = array();
		  $array[] = "##DATE##%^%".$date;
		  $array[] = "##NAME##%^%".$contactperson;
		  $array[] = "##PLACE##%^%".$place;
		  $array[] = "##CARDID##%^%".$cardid;
		  $array[] = "##PINNO##%^%".$pinno;
		  $array[] = "##EMAILID##%^%".$emailid;
		  $array[] = "##DEALERNAME##%^%".$contactperson;
		  $array[] = "##REMARKS##%^%".$remarks;
		  $array[] = "##SUBJECT##%^%".$subject;
		  $array[] = "##CUSTOMERID##%^%".$customerid;
		  
		  $filearray = array(
			  array('../images/relyon-logo.jpg','inline','1234567890')
		  );
		  if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk"))
		  {
			  $bccemailids['Meghna'] = 'meghana.b@relyonsoft.com';
		  }
		  else
		  {
			  $bccemailids['bigmail'] ='bigmail@relyonsoft.com';
			  $bccemailids['Relyonimax'] ='relyonimax@gmail.com';
		  }
		  
		  
		  $toarray = $emailids;
		  $bccarray = $bccemailids;
		  $msg = replacemailvariable2($msg,$array);
		  $textmsg = replacemailvariable2($textmsg,$array);
		  $html = $msg;
		  $text = $textmsg;
		  rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,$ccarray,$bccarray,$filearray,$replyto);
		  echo('1^PIN Number: '.$pinno);
		}
		else
		{
			echo('2^Invalid Customer');
		}
	}
	break;
	case 'generategrid':
	{
		$startlimit = $_POST['startlimit'];
		$slno = $_POST['slno'];
		$showtype = $_POST['showtype'];
		$resultcount = "select inv_dealercard.cardid, inv_mas_scratchcard.scratchnumber,inv_dealercard.cuscardattacheddate,inv_dealercard.usagetype,inv_dealercard.purchasetype,inv_dealercard.cuscardremarks from inv_dealercard left join inv_mas_dealer on inv_mas_dealer.slno = inv_dealercard.dealerid left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
 where productcode = '213' and scheme = '6' and dealerid = '".$userid."' order by cuscardattacheddate  desc ; ";
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
			$startlimit = $slno;
			$slno = $slno;
		}
		
		$query = "select inv_dealercard.cardid, 
inv_mas_scratchcard.scratchnumber,inv_dealercard.cuscardattacheddate,inv_dealercard.usagetype,inv_dealercard.purchasetype,inv_dealercard.cuscardremarks,
inv_mas_product.productname,inv_mas_customer.customerid from inv_dealercard left join inv_mas_dealer on inv_mas_dealer.slno = inv_dealercard.dealerid left join 
inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
left join inv_mas_customer on inv_mas_customer.slno=inv_dealercard.customerreference 
inner join inv_mas_product on inv_dealercard.productcode=inv_mas_product.productcode 
 where inv_dealercard.productcode = '213' and scheme = '6' and dealerid = '".$userid."' order by cuscardattacheddate  desc LIMIT ".$startlimit.",".$limit."; ";

		$result = runmysqlquery($query);
		if($startlimit == 0)
		{
		//$grid = '<table width="100%" cellpadding="3" cellspacing="0" "><tr><td>';
		$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
		$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Customer ID</td><td nowrap = "nowrap" class="td-border-grid" align="left">PIN Serial</td><td nowrap = "nowrap" class="td-border-grid" align="left">PIN Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Attached Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Usage Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Purchase Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Remarks</td></tr>';
		}
		
		$i_n = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$i_n++;
			$slno++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			if($fetch['customerid'] == '')
				$customerid = '';
			else
				$customerid  = cusidcombine($fetch['customerid']);
			$grid .= '<tr class="gridrow1" bgcolor='.$color.' align="left">';
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left' >".$slno."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left' >".$customerid."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['cardid']."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['scratchnumber']."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>". $fetch['productname'] ."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>".changedateformatwithtime($fetch['cuscardattacheddate'])."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>".strtoupper($fetch['usagetype'])."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>".strtoupper($fetch['purchasetype'])."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['cuscardremarks']."</td>";
			$grid .= "</tr>";
		}
		$grid .= "</table>";
 
		$fetchcount = mysqli_num_rows($result);
		if($slno >= $fetchresultcount)
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"  ><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font></td></tr></table>';
		else
			$linkgrid .= '<table><tr><td align ="left"><div align ="left" style="padding-right:10px">&nbsp;&nbsp;&nbsp;<a onclick ="getmorecarddetails(\''.$startlimit.'\',\''.$slno.'\',\'more\');" class="resendtext" style="cursor:pointer;">Show More Records >></a> &nbsp;&nbsp;&nbsp;<a onclick ="getmorecarddetails(\''.$startlimit.'\',\''.$slno.'\',\'all\');" class="resendtext1"><font color= "#000000">(Show All Records)</font></a></div></td></tr></table>';
	
		echo '1'.'^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid;
		
	}
	break;
	
}

function checkcustomer($lastslno)
{
	$userid = imaxgetcookie('dealeruserid');
	$query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode,inv_mas_dealer.telecaller,inv_mas_dealer.branch,inv_mas_dealer.region
from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
 where inv_mas_dealer.slno = '".$userid."';";
	$fetch = runmysqlqueryfetch($query);
	$relyonexecutive = $fetch['relyonexecutive'];
	$telecaller = $fetch['telecaller'];
	$region = $fetch['region'];
	$branch = $fetch['branch'];
	$district = $fetch['district'];
	$state = $fetch['statecode'];
	if($telecaller == 'yes')
	{
		$query = "select slno as slno, businessname as businessname from inv_mas_customer where branch in( select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."') order by businessname;";
		$result = runmysqlquery($query);
		if(mysqli_num_rows($result) == 0)
		{
			$query = "select slno as slno, businessname as businessname from inv_mas_customer where branch = '".$branch."' order by businessname;";
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
				$query = "select slno as slno, businessname as businessname from inv_mas_customer where region = '1' or region = '3' order by businessname;";
				$result = runmysqlquery($query);
			}
			else
			{
				$query = "select slno as slno, businessname as businessname from inv_mas_customer where branch in( select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."') order by businessname;";
				$result = runmysqlquery($query);
				if(mysqli_num_rows($result) == 0)
				{
					$query = "select slno as slno, businessname as businessname from inv_mas_customer where branch = '".$branch."' order by businessname;";
					$result = runmysqlquery($query);
				}
			}
		}
	}
	//echo($query); exit;
	$grid = '';
	$count = 1;
	$resultvalue = 'true';
	while($fetch = mysqli_fetch_array($result))
	{			
		if($lastslno == $fetch['slno'])
		{

			$resultvalue = 'true';
			break;
		}
		else
		{
			$resultvalue = 'false';
		}
	}
	return $resultvalue;
}



?>