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

$switchtype = $_POST['switchtype'];
$subselection = $_POST['subselection'];
$fromdate = $_POST['fromdate'];
$fromdateconverted = changedateformat($fromdate);
$todate = $_POST['todate'];
$todateconverted = changedateformat($todate);
$dealerid = $_POST['dealerid'];
$startlimit = $_POST['startlimit'];
$slno = $_POST['slno'];
$showtype = $_POST['showtype'];
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


#Added on 2nd Feb 2018
	$query = "select inv_mas_dealer.maindealers,inv_mas_dealer.dealerhead from inv_mas_dealer where inv_mas_dealer.slno = '".$userid."';";
	$fetch = runmysqlqueryfetch($query);
	$maindealers = $fetch['maindealers'];
	$dealerhead = $fetch['dealerhead'];
#Ends on 2nd Feb 2018

switch($switchtype)
{
	case 'view':
	{
		switch($subselection)
		{
			case 'purchase':
				$query = "SELECT slno AS slno,billdate as `date`, netamount AS amount, 'PURCHASE' as transactiontype FROM inv_bill WHERE LEFT(billdate,10) BETWEEN '".$fromdateconverted."' AND '".$todateconverted ."' AND dealerid = '".$dealerid."' AND billstatus = 'successful' LIMIT ".$startlimit.",".$limit.";";
				if($maindealers == 'yes')
                {
                    $query = "SELECT slno AS slno,billdate as `date`, netamount AS amount, 'PURCHASE' as transactiontype 
    				FROM inv_bill WHERE LEFT(billdate,10) BETWEEN '".$fromdateconverted."' AND '".$todateconverted ."' AND 
    				(dealerid = '".$dealerid."' OR dealerid IN (select slno from inv_mas_dealer where dealerhead = '".$userid."')) AND billstatus = 'successful' LIMIT ".$startlimit.",".$limit.";";
                }		
			break;
			case 'credit':
				$query ="SELECT slno AS slno, createddate AS `date`,creditamount AS amount, 'CREDIT' as transactiontype FROM inv_credits WHERE LEFT(createddate,10) BETWEEN '".$fromdateconverted."'  AND '".$todateconverted ."' AND dealerid = '".$dealerid."' LIMIT ".$startlimit.",".$limit.";";
				if($maindealers == 'yes')
                {
                    $query = "SELECT slno AS slno, createddate AS `date`,creditamount AS amount, 'CREDIT' as transactiontype 
				    FROM inv_credits WHERE LEFT(createddate,10) BETWEEN '".$fromdateconverted."'  AND '".$todateconverted ."' AND (dealerid = '".$dealerid."' OR dealerid IN (select slno from inv_mas_dealer where dealerhead = '".$userid."')) LIMIT ".$startlimit.",".$limit.";";
                }
			break;
			case 'both':
				$query = "(SELECT slno as slno,billdate as `date`,netamount as amount, 'PURCHASE' as transactiontype from inv_bill where LEFT(billdate,10) between '".$fromdateconverted."' AND '".$todateconverted ."' AND dealerid = '".$dealerid."' AND billstatus = 'successful') 
union all 
(SELECT slno as slno,createddate  as `date`,creditamount as amount, 'CREDIT' as transactiontype from inv_credits where LEFT(createddate,10) between  '".$fromdateconverted ."'  AND '".$todateconverted ."' AND dealerid = '".$dealerid."' ) order by `date` LIMIT ".$startlimit.",".$limit.";";
			break;
                if($maindealers == 'yes')
                {
                    $query = "(SELECT slno as slno,billdate as `date`,netamount as amount, 'PURCHASE' as transactiontype from inv_bill where LEFT(billdate,10) between '".$fromdateconverted."' AND '".$todateconverted ."' AND (dealerid = '".$dealerid."' OR dealerid IN (select slno from inv_mas_dealer where dealerhead = '".$userid."')) AND billstatus = 'successful') union all (SELECT slno as slno,createddate  as `date`,creditamount as amount, 'CREDIT' as transactiontype from inv_credits where LEFT(createddate,10) between  '".$fromdateconverted ."'  AND '".$todateconverted ."' AND (dealerid = '".$dealerid."' OR dealerid IN (select slno from inv_mas_dealer where dealerhead = '".$userid."'))) order by `date` LIMIT ".$startlimit.",".$limit.";";
                }			
		}
			if($startlimit == 0)
			{
				$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid" style="text-align:left" >';
				
				$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid">Sl No</td><td nowrap = "nowrap" class="td-border-grid">Bill Date</td><td nowrap = "nowrap" class="td-border-grid">Amount</td><td nowrap = "nowrap" class="td-border-grid">Transaction Type</td>';
			}
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
				
				$grid .= '<tr class="gridrow1" bgcolor='.$color.'>';
				$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$slno."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid'>".changedateformat(substr(($fetch['date']),0,10))."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['amount']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['transactiontype']."</td>";
				$grid .= '</tr>';
			}
			$fetchcount = mysqli_num_rows($result);
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','136','".date('Y-m-d').' '.date('H:i:s')."','view_transactionsummary')";
			$eventresult = runmysqlquery($eventquery);
			if($fetchcount < $limit)
			
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
			$linkgrid .= '<table><tr><td class="resendtext"><div align ="left" style="padding-right:10px"><a onclick ="viewmoresummary(\''.$dealerid.'\',\''.$startlimit.'\',\''.$slno.'\',\'more\');">Show More Records >></a><a onclick ="viewmoresummary(\''.$dealerid.'\',\''.$startlimit.'\',\''.$slno.'\',\'all\');" class="resendtext1"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
			echo $grid.'^'.$linkgrid;
			//echo($query);
			
	}
}

?>
