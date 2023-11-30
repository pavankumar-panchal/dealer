<?php
	$userid = imaxgetcookie('dealeruserid');
	$query = "SELECT DISTINCT inv_mas_product.productcode,inv_mas_product.dealerpurchasecaption FROM inv_mas_product left join inv_dealercard on inv_dealercard.productcode = inv_mas_product.productcode where allowdealerpurchase = 'yes'AND notinuse = 'no' AND inv_dealercard.dealerid = '".$userid."'  order by productname;";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['productcode'].'">'.wordwrap($fetch['dealerpurchasecaption'], 25, "<br />\n").' &nbsp;('.$fetch['productcode'].')</option>');
	}
?>
