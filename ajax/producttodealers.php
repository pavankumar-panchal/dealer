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
$q1="select * from inv_mas_dealer where slno='".$userid."';";
$f1 = runmysqlqueryfetch($q1);
$branch=$f1['branch'];
$region=$f1['region'];

$changetype = $_POST['changetype'];
$lastslno = $_POST['lastslno'];
switch($changetype)
{
	case 'save':
	{
		$responsearray = array();
		$productname = $_POST['productname'];
		$productcode = $_POST['productcode'];
		$dealerids = $_POST['dealers'];
		$dealerids=explode(',',$dealerids);
		
		foreach ($dealerids as $value) 
		{   		
			$dealerid = $value;
			if($lastslno == '')
			{
				$query = "select * from inv_productmapping where dealerid = '".$dealerid."' and productcode = '".$productcode."' ";
				$result = runmysqlquery($query);
				if(mysqli_num_rows($result) == 0)
				{
					$query = "INSERT INTO inv_productmapping(dealerid,productcode,userid,createddate,createdip,lastmodifieddate,lastmodifiedip,lastmodifiedby) VALUES('".$dealerid."','".$productcode."','".$userid."','".date('Y-m-d').'('.date('H:i:s').')'."','".$_SERVER['REMOTE_ADDR']."','".date('Y-m-d').' '.date('H:i:s')."','".$_SERVER['REMOTE_ADDR']."','".$userid."')";
					$result = runmysqlquery($query);
					
					$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','14','".date('Y-m-d').' '.date('H:i:s')."')";
					$eventresult = runmysqlquery($eventquery);
				
					$responsearray['errormessage'] =  "1^ Record Saved Successfully.";
				}
				else
					$responsearray['errormessage'] =  "2^ The Product already exists to the dealerid".$dealerid.".";				
			}
			else
			{
				$query = "select * from inv_productmapping where dealerid = '".$dealerid."' and productcode = '".$productcode."' and slno <> '".$lastslno."'";
				$result = runmysqlquery($query);
				if(mysqli_num_rows($result) == 0)
				{
					$query = "UPDATE inv_productmapping SET productcode = '".$productcode."',lastmodifieddate ='".date('Y-m-d').'('.date('H:i:s').')'."',lastmodifiedip ='".$_SERVER['REMOTE_ADDR']."', lastmodifiedby = '".$userid."'  WHERE slno = '".$lastslno."'";
					$result = runmysqlquery($query);
					$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','15','".date('Y-m-d').' '.date('H:i:s')."')";
					$eventresult = runmysqlquery($eventquery);
					$responsearray['errormessage'] =  "1^ Record Saved Successfully.";
				}
				else
					$responsearray['errormessage'] =  "2^ The Product already exists to the selected dealer.";
	
			}			
		}
		echo(json_encode($responsearray));
	}
	break;

	case 'generateproductlist':
	{
		$generateproductlistarray = array();
		$query = "SELECT slno,productname,productcode FROM inv_mas_product where notinuse='no' ORDER BY productname";
		$result = runmysqlquery($query);
		//$grid = '<select name="productlist" size="5" class="swiftselect" id="productlist" style="width:210px; height:400px;" onclick ="selectfromlist();" onchange="selectfromlist();"  >';
		$grid = '';
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$generateproductlistarray[$count] = $fetch['productname'].'^'.$fetch['productcode'];
			$count++;
		}
		echo(json_encode($generateproductlistarray));
	}
	break;
	
	case 'productdetailstoform':
	{
		$productdetailstoformarray = array();
		$query1 = "SELECT count(*) as count from inv_mas_product where productcode = '".$lastslno."'";
		$fetch1 = runmysqlqueryfetch($query1);
		if($fetch1['count'] > 0) 		
		{
			$query = "SELECT  inv_mas_product.slno,inv_mas_product.productcode,inv_mas_product.productname from inv_mas_product where productcode = '".$lastslno."';";
			$fetch = runmysqlqueryfetch($query);
			$productdetailstoformarray['errorcode'] = '1';
			$productdetailstoformarray['slno'] = $fetch['slno'];
			$productdetailstoformarray['productcode'] = $fetch['productcode'];
			$productdetailstoformarray['productname'] = $fetch['productname'];
			
			$query = "SELECT slno,businessname FROM inv_mas_dealer where slno <> '532568855'  AND inv_mas_dealer.disablelogin = 'no' 
and slno not in(select dealerid from inv_productmapping where productcode='". $fetch['productcode']."') and branch='".$branch."' and region='".$region."'  ORDER BY businessname";
//echo $query;
			$result = runmysqlquery($query);
			$productlistoptions = '';
			$arrdealers="";
			$i=0;
			$productlistoptions='<select name="dealerlist" size="5" class="swiftselect" id="dealerlist" style="width:210px; height:200px;" >';
			while($fetch = mysqli_fetch_array($result))
			{
				//$arrdealers[$i++]='<option value="'.$fetch['slno'].'"  ondblclick="addentry(\''.$fetch['slno'].'\')">'.$fetch['businessname'].'</option>';
			  $productlistoptions .= '<option value="'.$fetch['slno'].'"  ondblclick="addentry(\''.$fetch['slno'].'\')">'.$fetch['businessname'].'</option>|';
			}
			$productlistoptions.='</select>';
			$productdetailstoformarray['dealerlist']=$productlistoptions;
							
			echo(json_encode($productdetailstoformarray));
		}
		else
		{
			$productdetailstoformarray['errorcode'] = '2';
			$productdetailstoformarray['slno'] = $lastslno;
			$productdetailstoformarray['productcode'] = '';
			$productdetailstoformarray['productname'] = '';
		}
	}
	break;
	
	case 'productsearch':
	{
		/*$textfield = $_POST['textfield'];
		$subselection = $_POST['subselection'];
		$orderby = $_POST['orderby'];
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
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
		if(strlen($textfield) > 0)
		{
			switch($orderby)
			{
				case "productcode":
					$orderbyfield = "productcode";
					break;
				case "productname":
					$orderbyfield = "productname";
					break;
				case "productnotinuse":
					$orderbyfield = "notinuse";
					break;
				case "producttype":
					$orderbyfield = "type";
					break;
				case "productgroup":
					$orderbyfield = "group";
					break;
				default:
					$orderbyfield = "productname";
					break;
			}
			switch($subselection)
			{
				case "productcode":
					$query = "SELECT inv_mas_product.slno,inv_mas_product.productcode,inv_mas_product.productname,inv_mas_product.notinuse,inv_mas_product.allowdealerpurchase,inv_mas_product.dealerpurchasecaption,inv_mas_product.updation,inv_mas_users.fullname  from inv_mas_product LEFT JOIN inv_mas_users on inv_mas_product.userid = inv_mas_users.slno WHERE  productcode LIKE '%".$textfield."%' ORDER BY ".$orderbyfield." LIMIT ".$startlimit.",".$limit.";";
					break;
					case "productname":
					$query = "SELECT inv_mas_product.slno,inv_mas_product.productcode,inv_mas_product.productname,inv_mas_product.notinuse,inv_mas_product.allowdealerpurchase,inv_mas_product.dealerpurchasecaption,inv_mas_product.updation,inv_mas_users.fullname from inv_mas_product LEFT JOIN inv_mas_users on inv_mas_product.userid = inv_mas_users.slno  WHERE  productname LIKE '%".$textfield."%' ORDER BY ".$orderbyfield." LIMIT ".$startlimit.",".$limit.";";
					break;
					case "productnotinuse":
					$query = "SELECT inv_mas_product.slno,inv_mas_product.productcode,inv_mas_product.productname,inv_mas_product.notinuse,inv_mas_product.allowdealerpurchase,inv_mas_product.dealerpurchasecaption,inv_mas_product.updation,inv_mas_users.fullname from inv_mas_product LEFT JOIN inv_mas_users on inv_mas_product.userid = inv_mas_users.slno  WHERE  notinuse LIKE '%".$textfield."%' ORDER BY ".$orderbyfield." LIMIT ".$startlimit.",".$limit.";";
					break;
					case "producttype":
					$query = "SELECT inv_mas_product.slno,inv_mas_product.productcode,inv_mas_product.productname,inv_mas_product.notinuse,inv_mas_product.allowdealerpurchase,inv_mas_product.dealerpurchasecaption,inv_mas_product.updation,inv_mas_users.fullname from inv_mas_product LEFT JOIN inv_mas_users on inv_mas_product.userid = inv_mas_users.slno  WHERE  `type` LIKE '%".$textfield."%' ORDER BY ".$orderbyfield." LIMIT ".$startlimit.",".$limit.";";
					break;
					case "productgroup":
					$query = "SELECT inv_mas_product.slno,inv_mas_product.productcode,inv_mas_product.productname,inv_mas_product.notinuse,inv_mas_product.allowdealerpurchase,inv_mas_product.dealerpurchasecaption,inv_mas_product.updation,inv_mas_users.fullname from inv_mas_product LEFT JOIN inv_mas_users on inv_mas_product.userid = inv_mas_users.slno  WHERE  `group` LIKE '%".$textfield."%' ORDER BY ".$orderbyfield." LIMIT ".$startlimit.",".$limit.";";
					break;
			}
			if($startlimit == 0)
			{
				$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid" >';
				
				$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Code</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Not In Use</td><td nowrap = "nowrap" class="td-border-grid" align="left">Updation</td><td nowrap = "nowrap" class="td-border-grid" align="left">Allow Dealer Purchase</td><td nowrap = "nowrap" class="td-border-grid" align="left">Dealer Purchase Caption</td><td nowrap = "nowrap" class="td-border-grid" align="left">User Name</td></tr>';
			}
			$result = runmysqlquery($query);
			//$slnocount = 0;
			while($fetch = mysqli_fetch_array($result))
			{
				$i_n++;
				$slnocount++;
				if($i_n%2 == 0)
					$color = "#edf4ff";
				else
					$color = "#f7faff";
				static $count = 0;
				$count++;
				$radioid = 'nameloadcustomerradio'.$count;
				$grid .= '<tr class="gridrow" onclick ="productdetailstoform(\''.$fetch['productcode'].'\');"  bgcolor='.$color.'>';
				
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['productcode'])."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".addslashes($fetch['productname'])."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['notinuse'])."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['updation'])."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['allowdealerpurchase'])."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['dealerpurchasecaption'])."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['fullname'])."</td>";
			
				$grid .= '</tr>';
			}
			$grid .= "</table>";

			$fetchcount = mysqli_num_rows($result);
			if($fetchcount < $limit)
			
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
			$linkgrid .= '<table><tr ><td align ="left"><div align ="left" style="padding-right:10px">&nbsp;&nbsp;&nbsp;<a onclick ="getmoresearchfilter(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" class="resendtext"  style="cursor:pointer;">Show More Records >> &nbsp;&nbsp;&nbsp;</a><a onclick ="getmoresearchfilter(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
			
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','7','".date('Y-m-d').' '.date('H:i:s')."')";
			$eventresult = runmysqlquery($eventquery);
				
			echo '1^'.$grid.'^'.$fetchcount.'^'.$linkgrid;	
		}*/
	}
	break;

	case 'generategrid':
	{
		/*$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		$resultcount = "SELECT inv_mas_product.slno,inv_mas_product.productcode,inv_mas_product.productname,
inv_mas_product.notinuse,inv_mas_product.allowdealerpurchase,
inv_mas_product.dealerpurchasecaption,inv_mas_product.updation,inv_mas_users.fullname as lastmodifiedby 
from inv_mas_product LEFT JOIN inv_mas_users on inv_mas_product.userid = inv_mas_users.slno  WHERE notinuse = 'No' ";
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
		$query = "SELECT inv_mas_product.slno,inv_mas_product.productcode,inv_mas_product.productname,
inv_mas_product.notinuse,inv_mas_product.allowdealerpurchase,
inv_mas_product.dealerpurchasecaption ,inv_mas_product.updation,inv_mas_users.fullname as lastmodifiedby 
from inv_mas_product LEFT JOIN inv_mas_users on inv_mas_product.userid = inv_mas_users.slno  WHERE notinuse = 'No' LIMIT ".$startlimit.",".$limit.";";
		if($startlimit == 0)
		{
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid" >';
			$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Code</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Not In Use</td><td nowrap = "nowrap" class="td-border-grid" align="left">Updation</td><td nowrap = "nowrap" class="td-border-grid" align="left">Allow Dealer Purchase</td><td nowrap = "nowrap" class="td-border-grid" align="left">Dealer Purchase Caption</td><td nowrap = "nowrap" class="td-border-grid" align="left">User Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Last Modified Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Last Modified By</td></tr>';
		}
			$result = runmysqlquery($query);
			while($fetch = mysqli_fetch_array($result))
			{
				$slnocount++;
				$i_n++;
				$color;
				if($i_n%2 == 0)
					$color = "#edf4ff";
				else
					$color = "#f7faff";
				
				$grid .= '<tr class="gridrow" onclick ="productdetailstoform(\''.$fetch['productcode'].'\');"  bgcolor='.$color.'>';
				
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['productcode']."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".addslashes($fetch['productname'])."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['notinuse']."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['updation']."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['allowdealerpurchase']."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['dealerpurchasecaption']."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['fullname']."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['lastmodifieddate']."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['lastmodifiedby']."</td>";
			
				$grid .= '</tr>';
			}
			$grid .= "</table>";
			$fetchcount = mysqli_num_rows($result);
			if($slnocount >= $fetchresultcount)
			
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px">&nbsp;&nbsp;&nbsp;<a onclick ="getmoreproductdatagrid(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" class="resendtext" style="cursor:pointer">Show More Records >></a>&nbsp;&nbsp;&nbsp;<a onclick ="getmoreproductdatagrid(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');"  style="cursor:pointer" class="resendtext1"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
			
			echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid;*/	
	}
	break;
	
	case 'filter':
	{
		$all = $_POST['all'];
		$filterString="";
		if(isset($_POST['branchhead']))
		{
			$filterString.=" and branchhead='yes'";
		}
		if(isset($_POST['relyonexecutive']))
		{
			$filterString.=" and relyonexecutive='yes'";
		}
		if(isset($_POST['region']))
		{ 
			$filterString.=" and region=".$_POST['region'];
		}
		if(isset($_POST['branch']))
		{
			$filterString.=" and branch=".$_POST['branch'];
		}
		if($filterString=="")
		{
			$query = "SELECT slno,businessname FROM inv_mas_dealer where slno <> '532568855'  AND inv_mas_dealer.disablelogin = 'no' 
and slno not in(select dealerid from inv_productmapping where productcode='". $_POST['productcode']."')  ORDER BY businessname";
		}
		else
		{
			$query = "SELECT slno,businessname FROM inv_mas_dealer where slno <> '532568855'  AND inv_mas_dealer.disablelogin = 'no' 
and slno not in(select dealerid from inv_productmapping where productcode='".$_POST['productcode']."')".$filterString."  ORDER BY businessname";
		}
		//echo $query;exit;
			$result = runmysqlquery($query);
			$productlistoptions = '';
			$arrdealers="";
			$i=0;
			$productlistoptions='<select name="dealerlist" size="5" class="swiftselect" id="dealerlist" style="width:210px; height:200px;" >';
			while($fetch = mysqli_fetch_array($result))
			{
				//$arrdealers[$i++]='<option value="'.$fetch['slno'].'"  ondblclick="addentry(\''.$fetch['slno'].'\')">'.$fetch['businessname'].'</option>';
			  $productlistoptions .= '<option value="'.$fetch['slno'].'"  ondblclick="addentry(\''.$fetch['slno'].'\')">'.$fetch['businessname'].'</option>|';
			  
			}
			$productlistoptions.='</select>';
			$productdetailstoformarray['dealerlist']=$productlistoptions;
			if($productlistoptions != '')
				$productdetailstoformarray['errorcode'] = '1';	
			else
				$productdetailstoformarray['errorcode'] = '2';	
			echo(json_encode($productdetailstoformarray));
	}
	break;
}
?>
