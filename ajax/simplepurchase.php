<?php 
include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');
include('../inc/checksession.php');

$switchtype = $_POST['switchtype'];
$dealerid = $_POST['dealerid'];
$billdate =  date("Y-m-d H:m:s");
//$amount = $_POST['amount'];
$taxamount = $_POST['taxamount'];
$netamount = $_POST['netamount'];
$billstatus = 'pending';
$productlastslno = $_POST['productlastslno'];
$product = $_POST['product'];
$quantity = $_POST['quantity'];
$usagetype = $_POST['usagetype'];
$purchasetype = $_POST['purchasetype'];
$lastbillno = $_POST['lastbillno'];
switch($switchtype)
{

	case 'productsave':
	{
		if($productlastslno == '')
		{
			if($lastbillno == '')
			{
				$query = "INSERT INTO inv_bill(dealerid,billdate,remarks,total,taxamount,netamount,billstatus) values('".$dealerid ."','".$billdate."','".$remarks."','".$amount."','".$taxamount."','".$netamount."','".$billstatus."')";
				$result = runmysqlquery($query);
				$fetch1 = runmysqlqueryfetch("SELECT MAX(slno) AS cusbillnumber FROM inv_bill;");
				$query = "INSERT INTO inv_billdetail (cusbillnumber , productcode , productquantity , usagetype , purchasetype) VALUES ('".$fetch1['cusbillnumber']."','".$product."','".$quantity."','".$usagetype."','".$purchasetype."');";
				$result = runmysqlquery($query);
				$firstbillnumber = $fetch1['cusbillnumber'];
				echo('1^fff^'.$firstbillnumber);
			}
			else
			{
				$query = "INSERT INTO inv_billdetail (cusbillnumber , productcode , productquantity , usagetype , purchasetype ) VALUES ('".$lastbillno."','".$product."','".$quantity."','".$usagetype."','".$purchasetype."');";
				$result = runmysqlquery($query);
				echo('1^fff^'.$lastbillno);
			}
		}
			else
			{
				$query = "UPDATE inv_billdetail SET productcode = '".$product."' , productquantity = '".$quantity."', productamount = '".$amount."' , usagetype = '".$usagetype."' , purchasetype = '".$purchasetype."' WHERE slno = '".$productlastslno."'";
				$result = runmysqlquery($query);
				echo('3^fff^'.$lastbillno);
		   }
	}
	break;
	case 'productdelete':
	{
		$productlastslno = $_POST['productlastslno'];
		$lastbillno = $_POST['lastbillno'];
		$query = "DELETE FROM inv_billdetail WHERE slno = '".$productlastslno."'";
		$result = runmysqlquery($query);
		echo('2^ff^'.$lastbillno);
	}
	break;
	case 'attachcards':
	{
				$dealerid = $_POST['dealerid'];
				$lastbillno = $_POST['lastbillno'];
				$fetch = runmysqlqueryfetch("SELECT  count(*) AS count FROM inv_billdetail LEFT JOIN inv_bill ON inv_bill.slno = inv_billdetail.cusbillnumber WHERE inv_bill.slno = '".$lastbillno."';");
				$count = $fetch['count'];
				if($count > 0)
				{
				   $fetchstatus  = runmysqlqueryfetch("SELECT billstatus FROM inv_bill WHERE slno = '".$lastbillno."';");
				   $billstatus = $fetchstatus['billstatus'];
				   if($billstatus == 'pending')
				   {
					   $query = "SELECT inv_billdetail.slno as slno, inv_billdetail.cusbillnumber as cusbillnumber, inv_billdetail.productcode as productcode, inv_billdetail.productquantity as productquantity, inv_billdetail.purchasetype as purchasetype, inv_billdetail.usagetype as usagetype, inv_billdetail.free as free, inv_billdetail.addlicence as addlicence, inv_bill.dealerid as dealerid FROM inv_billdetail LEFT JOIN inv_bill ON inv_bill.slno = inv_billdetail.cusbillnumber WHERE inv_bill.slno = '".$lastbillno."';";
						$result = runmysqlquery($query);
						while($fetch = mysqli_fetch_array($result))
						{
							$scratchlimit = $fetch['productquantity'];
							$query1 = "select cardid from inv_mas_scratchcard where attached = 'no' order by cardid limit  ".$scratchlimit." ;";
							$result1 = runmysqlquery($query1);
							while($fetch1 = mysqli_fetch_array($result1))
							{
							 $query2 = "INSERT INTO inv_dealercard ( dealerid, cardid, productcode, date, remarks, cusbillnumber, usagetype, purchasetype, free, addlicence) values('".$fetch['dealerid']."', '".$fetch1['cardid']."', '".$fetch['productcode']."', '".datetimelocal('Y-m-d')." ".datetimelocal('H:i:s')."', '".$fetch['remarks']."', '".$fetch['cusbillnumber']."', '".$fetch['usagetype']."', '".$fetch['purchasetype']."', '".$fetch['free']."', '".$fetch['addlicence']."');";
							 $result2 = runmysqlquery($query2);
							 $query3 = "update inv_mas_scratchcard set attached = 'yes' where attached = 'no' and cardid = ".$fetch1['cardid'].";";   
							 $result3 = runmysqlquery($query3);
							}
						 }
						 $query4 = "update inv_bill set billstatus = 'successful' where slno = '".$lastbillno."';";
						 $result4 = runmysqlquery($query4);
           				$query5 = "SELECT inv_dealercard.productcode, inv_dealercard.usagetype, inv_dealercard.purchasetype, inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber FROM inv_dealercard LEFT JOIN inv_mas_scratchcard ON inv_mas_scratchcard.cardid = inv_dealercard.cardid WHERE inv_dealercard.cusbillnumber='".$lastbillno."';";
						$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
						$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid">Product Code</td><td nowrap = "nowrap" class="td-border-grid">Usage Type</td><td nowrap = "nowrap" class="td-border-grid">Purchase Type</td><td nowrap = "nowrap" class="td-border-grid">Card Number</td><td nowrap = "nowrap" class="td-border-grid">Pin Number</td></tr>';
							
						$result5 = runmysqlquery($query5);
						$i_n = 0;
						while($fetch5 = mysqli_fetch_row($result5))
						{
							$i_n++;
							$color;
							if($i_n%2 == 0)
							$color = "#edf4ff";
							else
							$color = "#f7faff";
							$grid .= '<tr class="gridrow1" bgcolor='.$color.'>';
							for($i = 0; $i < count($fetch5); $i++)
							{
								$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$fetch5[$i]."</td>";
							}
							$grid .= "</tr>";
						}
						$grid .= "</table>";
						echo($grid);
					}
			    }
			}

	break;
	case 'getamount':
	{
		if(($usagetype == 'singleuser') && ($purchasetype == 'new'))
		{
			$query="SELECT newsuprice as price FROM inv_mas_product where productcode ='".$product."'";
			$result= runmysqlqueryfetch($query);
			$amount = $result['price'];
		}
		else if(($usagetype == 'singleuser') && ($purchasetype == 'updation'))
		{
			$query="SELECT updatesuprice as price FROM inv_mas_product where productcode ='".$product."'";
			$result= runmysqlqueryfetch($query);
			$result= runmysqlqueryfetch($query);
			$amount = $result['price'];
		}
		else if(($usagetype == 'newmuprice') && ($purchasetype == 'new'))
		{
			$query="SELECT updatesuprice as price FROM inv_mas_product where productcode ='".$product."'";
			$result= runmysqlqueryfetch($query);
			$amount = $result['price'];
		}
		else
		{
			$query="SELECT updatemuprice as price FROM inv_mas_product where productcode ='".$product."'";
			$result= runmysqlqueryfetch($query);
			$amount = $result['price'];
		}
			$query1 = "SELECT revenueshare AS share FROM inv_mas_dealer WHERE dealerid = '".$dealerid."'";
			$result1 = runmysqlqueryfetch($query1);
			$share = $result1['share'];
			$totalproductamount = ($quantity * $amount);
			$shareamount = $totalproductamount * (($share)*0.01);
			$totalshaeramount = $totalproductamount - $shareamount;
			$query2 = "SELECT sum(productamount) as totalamount FROM inv_billdetail WHERE cusbillnumber = '".$lastbillno."'";
			$resultfetch2 = runmysqlqueryfetch($query2);
			$totalamount = $resultfetch2['totalamount'];
			$query3 = "SELECT taxamount  FROM inv_mas_dealer WHERE dealerid = '".$dealerid."';";
			$resultfetch3 = runmysqlqueryfetch($query3);
			$taxamount = $resultfetch3['taxamount'];
			$netamount = $taxamount + $totalamount;
			echo($totalproductamount.'^'.$totalamount.'^'.$taxamount.'^'.$netamount);
	}
	break;
	case 'gridtoform':
	{
		$productlastslno = $_POST['productlastslno'];
		$query = "SELECT slno,cusbillnumber,productcode,productquantity,productamount,usagetype,purchasetype FROM inv_billdetail WHERE slno = '".$productlastslno."'";
		 $fetch = runmysqlqueryfetch($query);
		 echo($fetch['slno'].'^'.$fetch['cusbillnumber'].'^'.$fetch['productcode'].'^'.$fetch['productquantity'].'^'.$fetch['productamount'].'^'.$fetch['usagetype'].'^'.$fetch['purchasetype']);
	}
	break;
	case 'generategrid':
	{
	$productlastslno = $_POST['productlastslno'];
	$lastbillno = $_POST['lastbillno'];
	$query = "SELECT slno,cusbillnumber,productcode,productquantity,productamount,usagetype,purchasetype FROM inv_billdetail WHERE ((cusbillnumber = '".$lastbillno."') || (slno = '".$productlastslno."'))";
  $grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
  $grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid">Sl No</td><td nowrap = "nowrap" class="td-border-grid">Bill Number</td><td nowrap = "nowrap" class="td-border-grid">Product Code</td><td nowrap = "nowrap" class="td-border-grid">Product Quantity</td><td nowrap = "nowrap" class="td-border-grid">Product Amount</td><td nowrap = "nowrap" class="td-border-grid">Usage Type</td><td nowrap = "nowrap" class="td-border-grid">Purchase Type</td></tr>';
  $i_n = 0;
  $result = runmysqlquery($query);
  while($fetch = mysqli_fetch_row($result))
  {
   $i_n++;
   $color;
   if($i_n%2 == 0)
    $color = "#edf4ff";
   else
    $color = "#f7faff";
   $grid .= '<tr class="gridrow" bgcolor='.$color.' onclick="productgridtoform(\''.$fetch[0].'\')">';
   for($i = 0; $i < count($fetch); $i++)
   {
    $grid .= "<td nowrap='nowrap' class='td-border-grid'>".$fetch[$i]."</td>";
   }
   $grid .= "</tr>";
  }
  $grid .= "</table>";
  echo($grid);
	}
	break;
	case 'generatebills':
	 {
	  $query = "SELECT MAX(slno) AS lastbillnumber FROM inv_bill;";
	  $fetch = runmysqlqueryfetch($query);
	  $lastbillnumber = $fetch['lastbillnumber'];
	  $nextbillnumber =  $lastbillnumber + 1;
	  echo($nextbillnumber);
	 }
	 break;
}
?>