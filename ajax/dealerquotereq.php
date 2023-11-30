<?

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

switch($switchtype)
{
	case 'generatecustomerlist':
	{
		$query = "select inv_mas_customer.slno as slno, inv_mas_customer.businessname
 as businessname from inv_mas_customer where inv_mas_customer.currentdealer = '".$userid."' order by businessname";
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
	
	case 'displaycustomer':
	{
		$customerreference = $_POST['customerreference'];
		$query = "SELECT businessname from inv_mas_customer where slno = '".$customerreference."';";
		$fetch = runmysqlqueryfetch($query);
		echo($fetch['businessname'].'^'.$customerreference);
	}
	break;
}

?>