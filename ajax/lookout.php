<?php
include('../inc/ajax-referer-security.php');
include("../functions/phpfunctions.php");
include('../inc/checksession.php');


$lookouttype = $_POST['lookouttype'];
$searchtext = $_POST['searchtext'];
$frstchar = substr($searchtext, 0, 1);
//if($frstchar == '.') { $searchtextfield = substr($searchtext, 1); } else { $searchtextfield = $searchtext; }
$searchtextfield = ($frstchar == '.')?(substr($searchtext, 1)):($searchtext);
$liketext = ($frstchar == '.')?("%".$searchtextfield."%"):($searchtextfield."%");
		$liketext1 = ($frstchar == '.')?(" and inv_mas_scratchcard.cardid LIKE '".$searchtextfield."%' "):(" and inv_mas_scratchcard.scratchnumber LIKE '".$searchtextfield."%' ");


switch($lookouttype)
{
	case 'customer':
	{
		$query = "SELECT slno,businessname,customerid FROM inv_mas_customer WHERE businessname LIKE '".$liketext."' ORDER BY businessname  LIMIT 0,10";
		$result = runmysqlquery($query);
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			if($count == 0)
				$grid .= $fetch['slno'].'#'.$fetch['businessname'];
			else
				$grid .= '|%@%|~|$#`'.$fetch['slno'].'#'.$fetch['businessname'];
			$count++;
		}
		echo($grid);
	}
	break;
	
	case 'dealer':
	{
		$query = "SELECT slno,businessname FROM inv_mas_dealer WHERE businessname LIKE '".$liketext."' ORDER BY businessname  LIMIT 0,10";
		$result = runmysqlquery($query);
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			if($count == 0)
				$grid .= $fetch['slno'].'#'.$fetch['businessname'];
			else
				$grid .= '|%@%|~|$#`'.$fetch['slno'].'#'.$fetch['businessname'];
			$count++;
		}
		echo($grid);
	}
	break;
	
	case 'firstdealer':
	{
		$query = "SELECT slno,businessname FROM inv_mas_dealer WHERE businessname LIKE '".$liketext."' ORDER BY businessname  LIMIT 0,10";
		$result = runmysqlquery($query);
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			if($count == 0)
				$grid .= $fetch['slno'].'#'.$fetch['businessname'];
			else
				$grid .= '|%@%|~|$#`'.$fetch['slno'].'#'.$fetch['businessname'];
			$count++;
		}
		echo($grid);
	}
	break;
	
	case 'delaerrep':
	{
		$query = "SELECT slno,businessname FROM inv_mas_dealer WHERE businessname LIKE '".$liketext."' ORDER BY businessname  LIMIT 0,10";
		$result = runmysqlquery($query);
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			if($count == 0)
				$grid .= $fetch['slno'].'#'.$fetch['businessname'];
			else
				$grid .= '|%@%|~|$#`'.$fetch['slno'].'#'.$fetch['businessname'];
			$count++;
		}
		echo($grid);
	}
	break;
	
	case 'ttdealer':
	{
		$query = "SELECT slno,businessname FROM inv_mas_dealer WHERE businessname LIKE '".$liketext."' ORDER BY businessname  LIMIT 0,10";
		$result = runmysqlquery($query);
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			if($count == 0)
				$grid .= $fetch['slno'].'#'.$fetch['businessname'];
			else
				$grid .= '|%@%|~|$#`'.$fetch['slno'].'#'.$fetch['businessname'];
			$count++;
		}
		echo($grid);
	}
	break;

	case 'ttproduct':
	{
		$query = "SELECT productcode,productname FROM inv_mas_product WHERE productname LIKE '".$liketext."' ORDER BY productname  LIMIT 0,10";
		$result = runmysqlquery($query);
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			if($count == 0)
				$grid .= $fetch['productcode'].'#'.$fetch['productname'];
			else
				$grid .= '|%@%|~|$#`'.$fetch['productcode'].'#'.$fetch['productname'];
			$count++;
		}
		echo($grid);
	}
	break;
	
	case 'customerfrom':
	{
		$query = "SELECT slno,businessname FROM inv_mas_customer WHERE  slno LIKE '".$liketext."' or businessname LIKE '".$liketext."' ORDER BY businessname LIMIT 0,20;";
		$result = runmysqlquery($query);
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			if($count == 0)
				$grid .= $fetch['slno'].'#'.$fetch['businessname'];
			else
				$grid .= '|%@%|~|$#`'.$fetch['slno'].'#'.$fetch['businessname'];
			$count++;
		}
		echo($grid);
	}
	break;
	
	case 'customerto':
	{
		$query = "SELECT slno,businessname FROM inv_mas_customer WHERE  slno LIKE '".$liketext."' or businessname LIKE '".$liketext."' ORDER BY businessname LIMIT 0,20;";
		$result = runmysqlquery($query);
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			if($count == 0)
				$grid .= $fetch['slno'].'#'.$fetch['businessname'];
			else
				$grid .= '|%@%|~|$#`'.$fetch['slno'].'#'.$fetch['businessname'];
			$count++;
		}
		echo($grid);
	}
	break;

	case 'scratchcardfrom':
	{
		$query = "SELECT cardid FROM inv_mas_scratchcard WHERE attached = 'no' AND cardid LIKE '".$liketext."' ORDER BY cardid LIMIT 0,10;";
		$result = runmysqlquery($query);
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			if($count == 0)
				$grid .= $fetch['cardid'].'#'.$fetch['cardid'];
			else
				$grid .= '|%@%|~|$#`'.$fetch['cardid'].'#'.$fetch['cardid'];
			$count++;
		}
		echo($grid);
	}
	break;
	
	case 'scratchcardfromreg':
	{
		$dealerid = $_POST['dealerid'];
		$query = "SELECT inv_mas_scratchcard.cardid AS cardid FROM inv_mas_scratchcard LEFT JOIN inv_dealercard ON inv_dealercard.cardid = inv_mas_scratchcard.cardid WHERE inv_mas_scratchcard.attached = 'yes' AND inv_mas_scratchcard.registered = 'yes' And inv_mas_scratchcard.cancelled = 'no' AND inv_dealercard.dealerid = '".$dealerid."' AND inv_mas_scratchcard.cardid LIKE '".$liketext."' ORDER BY inv_mas_scratchcard.cardid LIMIT 0,10;";
		$result = runmysqlquery($query);
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			if($count == 0)
				$grid .= $fetch['cardid'].'#'.$fetch['cardid'];
			else
				$grid .= '|%@%|~|$#`'.$fetch['cardid'].'#'.$fetch['cardid'];
			$count++;
		}
		echo($grid);
	}
	break;
	
	case 'scratchcardtoreg':
	{
		$scratchcardfromfield = $_POST['scratchcardfromfield'];
		$dealerid = $_POST['dealerid'];
		$query = "SELECT inv_mas_scratchcard.cardid AS cardid FROM inv_mas_scratchcard LEFT JOIN inv_dealercard ON inv_dealercard.cardid = inv_mas_scratchcard.cardid WHERE inv_mas_scratchcard.attached = 'yes' AND inv_mas_scratchcard.registered = 'yes' And inv_mas_scratchcard.cancelled = 'no' AND inv_dealercard.dealerid = '".$dealerid."' AND inv_mas_scratchcard.cardid LIKE '".$liketext."' AND inv_mas_scratchcard.cardid >= ".$scratchcardfromfield." ORDER BY inv_mas_scratchcard.cardid LIMIT 0,10;";
		$result = runmysqlquery($query);
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			if($count == 0)
				$grid .= $fetch['cardid'].'#'.$fetch['cardid'];
			else
				$grid .= '|%@%|~|$#`'.$fetch['cardid'].'#'.$fetch['cardid'];
			$count++;
		}
		echo($grid);
	}
	break;
	
	case 'scratchcardfromunreg':
	{
		$dealerid = $_POST['dealerid'];
		$query = "SELECT inv_mas_scratchcard.cardid AS cardid FROM inv_mas_scratchcard LEFT JOIN inv_dealercard ON inv_dealercard.cardid = inv_mas_scratchcard.cardid WHERE inv_mas_scratchcard.attached = 'yes' AND inv_dealercard.dealerid = '".$dealerid."' AND inv_mas_scratchcard.registered = 'no' And inv_mas_scratchcard.blocked = 'no' AND inv_mas_scratchcard.cardid LIKE '".$liketext."' ORDER BY inv_mas_scratchcard.cardid LIMIT 0,10;";
		$result = runmysqlquery($query);
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			if($count == 0)
				$grid .= $fetch['cardid'].'#'.$fetch['cardid'];
			else
				$grid .= '|%@%|~|$#`'.$fetch['cardid'].'#'.$fetch['cardid'];
			$count++;
		}
		echo($grid);
	}
	break;
	
	case 'scratchcardtounreg':
	{
		$scratchcardfromfield = $_POST['scratchcardfromfield'];
		$dealerid = $_POST['dealerid'];
		$query = "SELECT inv_mas_scratchcard.cardid AS cardid FROM inv_mas_scratchcard LEFT JOIN inv_dealercard ON inv_dealercard.cardid = inv_mas_scratchcard.cardid WHERE inv_mas_scratchcard.attached = 'yes' AND inv_dealercard.dealerid = '".$dealerid."' AND inv_mas_scratchcard.registered = 'no' And inv_mas_scratchcard.blocked = 'no' AND inv_mas_scratchcard.cardid LIKE '".$liketext."' AND inv_mas_scratchcard.cardid >= ".$scratchcardfromfield." ORDER BY inv_mas_scratchcard.cardid LIMIT 0,10;";
		$result = runmysqlquery($query);
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			if($count == 0)
				$grid .= $fetch['cardid'].'#'.$fetch['cardid'];
			else
				$grid .= '|%@%|~|$#`'.$fetch['cardid'].'#'.$fetch['cardid'];
			$count++;
		}
		echo($grid);
	}
	break;

	
	case 'scratchcardto':
	{
		$scratchcardfromfield = $_POST['scratchcardfromfield'];
		$query = "SELECT cardid FROM inv_mas_scratchcard WHERE attached = 'no' AND cardid LIKE '".$liketext."' AND cardid >= ".$scratchcardfromfield." ORDER BY cardid LIMIT 0,10;";
		$result = runmysqlquery($query);
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			if($count == 0)
				$grid .= $fetch['cardid'].'#'.$fetch['cardid'];
			else
				$grid .= '|%@%|~|$#`'.$fetch['cardid'].'#'.$fetch['cardid'];
			$count++;
		}
		echo($grid);
	}
	break;
	
	case 'scratchnumber':
	{
		$hiddenregistrationtype = $_POST['hiddenregistrationtype'];
		$customerreference = $_POST['customerreference'];
		$productcode = $_POST['productcode'];
		//echo($hiddenregistrationtype);
		//echo($customerreference);searchtextfield
	//$liketext1 = '';

		switch($hiddenregistrationtype)
		{
			case 'newlicence':
				/*$q1 = runmysqlqueryfetch("select count(*) as count FROM inv_mas_scratchcard LEFT JOIN inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid WHERE inv_mas_scratchcard.registered = 'no' and  inv_mas_scratchcard.attached='yes' and inv_dealercard.purchasetype = 'new' and inv_mas_scratchcard.blocked = 'no' and (inv_mas_scratchcard.scratchnumber LIKE '".$liketext."' or inv_mas_scratchcard.cardid LIKE '".$liketext."')");
				$query = "SELECT inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber FROM inv_mas_scratchcard LEFT JOIN inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid WHERE inv_mas_scratchcard.registered = 'no' and  inv_mas_scratchcard.attached='yes' and inv_mas_scratchcard.blocked = 'no' and inv_dealercard.purchasetype = 'new' and (inv_mas_scratchcard.scratchnumber LIKE '".$liketext."' or inv_mas_scratchcard.cardid LIKE '".$liketext."') ORDER BY inv_mas_scratchcard.cardid  LIMIT 0,10";*/
				//$q1 = runmysqlqueryfetch("select count(*) as count FROM inv_mas_scratchcard LEFT JOIN inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid WHERE inv_mas_scratchcard.registered = 'no' and  inv_mas_scratchcard.attached='yes' and inv_dealercard.purchasetype = 'new' and inv_mas_scratchcard.blocked = 'no' and (inv_mas_scratchcard.scratchnumber LIKE '".$liketext."' or inv_mas_scratchcard.cardid LIKE '".$liketext."')");
				$query = "SELECT inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber FROM inv_mas_scratchcard LEFT JOIN inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid WHERE inv_mas_scratchcard.registered = 'no' and  inv_mas_scratchcard.attached='yes' and inv_mas_scratchcard.blocked = 'no' and inv_dealercard.purchasetype = 'new' ".$liketext1." ORDER BY inv_mas_scratchcard.cardid  LIMIT 0,10";
			break;
			case 'updationlicense':
				//$q1 = runmysqlqueryfetch("select count(*) as count  FROM inv_mas_scratchcard LEFT JOIN inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid WHERE inv_mas_scratchcard.registered = 'no' and  inv_mas_scratchcard.attached='yes' and inv_dealercard.purchasetype = 'updation' and inv_mas_scratchcard.blocked = 'no' and (inv_mas_scratchcard.scratchnumber LIKE '".$liketext."' or inv_mas_scratchcard.cardid LIKE '".$liketext."')");
				$query = "SELECT inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber FROM inv_mas_scratchcard LEFT JOIN inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid WHERE inv_mas_scratchcard.registered = 'no' and  inv_mas_scratchcard.attached='yes' and inv_mas_scratchcard.blocked = 'no' and inv_dealercard.purchasetype = 'updation' ".$liketext1." ORDER BY inv_mas_scratchcard.cardid  LIMIT 0,10";
			break;
			case 'reregistration':
				//$q1 = runmysqlqueryfetch("select count(*) as count FROM inv_mas_scratchcard LEFT JOIN inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid LEFT JOIN inv_customerproduct on inv_customerproduct. dealerid = inv_dealercard.dealerid WHERE inv_mas_scratchcard.registered = 'yes' and  inv_mas_scratchcard.attached='yes' and inv_customerproduct.customerreference = '".$customerreference."'  and inv_mas_scratchcard.cancelled = 'no' and (inv_mas_scratchcard.scratchnumber LIKE '".$liketext."' or inv_mas_scratchcard.cardid LIKE '".$liketext."')");
				$query = "SELECT inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber FROM inv_mas_scratchcard LEFT JOIN inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid LEFT JOIN inv_customerproduct on inv_customerproduct. dealerid = inv_dealercard.dealerid WHERE inv_mas_scratchcard.registered = 'yes' and  inv_mas_scratchcard.attached='yes' and inv_customerproduct.customerreference = '".$customerreference."' and inv_mas_scratchcard.cancelled = 'no' ".$liketext1." ORDER BY inv_mas_scratchcard.cardid LIMIT 0,10";
			break;
		}

		$result = runmysqlquery($query);
		if(mysqli_num_rows($result) > 0)
		{
			$count = 0;
			while($fetch = mysqli_fetch_array($result))
			{
				if($count == 0)
					$grid .= $fetch['cardid'].'#'.$fetch['scratchnumber'].' | '.$fetch['cardid'];
				else
					$grid .= '|%@%|~|$#`'.$fetch['cardid'].'#'.$fetch['scratchnumber'].' | '.$fetch['cardid'];
				$count++;
			}
			echo($grid);
		}
	}
	break;
	
	case 'selectscratchnumber':
	{
		$query = "SELECT cardid,scratchnumber FROM inv_mas_scratchcard WHERE cardid LIKE '".$liketext."' ORDER BY cardid LIMIT 0,30;";
		$result = runmysqlquery($query);
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			if($count == 0)
					$grid .= $fetch['cardid'].'#'.$fetch['scratchnumber'].'  |  '.$fetch['cardid'];
				else
					$grid .= '|%@%|~|$#`'.$fetch['cardid'].'#'.$fetch['scratchnumber'].'  |  '.$fetch['cardid'];
			$count++;
		}
		echo($grid);
	}
	break;
	
	case 'cusbillnumber':
	{
		$query = "SELECT slno FROM inv_bill WHERE slno LIKE '".$liketext."' ORDER BY slno LIMIT 0,30;";
		$result = runmysqlquery($query);
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			if($count == 0)
					$grid .= $fetch['slno'].'#'.$fetch['slno'];
				else
					$grid .= '|%@%|~|$#`'.$fetch['slno'].'#'.$fetch['slno'];
			$count++;
		}
		echo($grid);
	}
	break;
	
	/*case '':
	{
		
	}
	break;*/
	
}
?>
