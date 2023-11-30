<?php

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


		$query1 = "select count(distinct inv_customerproduct.customerreference) AS slno,inv_mas_product.group
from inv_mas_customer 
LEFT JOIN inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference
left join inv_mas_dealer ON inv_mas_dealer.slno = inv_mas_customer.currentdealer
left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3)
where  inv_customerproduct.customerreference IS NOT NULL 
and inv_mas_product.group IN ('TDS','SPP','STO','SVH','SVI','SAC','OTHERS','CONTACT') and reregistration = 'no'
AND inv_mas_dealer.slno = '".$userid."' group by inv_mas_product.group";
		$result = runmysqlquery($query1);
		echo("[");
		$count = 1;
		while($fetch = mysqli_fetch_array($result))
		{
			if($count  > 1)
				echo(",");
			echo('['."'".$fetch['group']."'".','.$fetch['slno'].']');
			$count++;
		}
		echo("]");

?>