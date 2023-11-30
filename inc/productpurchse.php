<?php
	//$userid = $_COOKIE['userid'];
	$query = "SELECT DISTINCT inv_mas_product.productcode,inv_mas_product.dealerpurchasecaption FROM inv_mas_product  where allowdealerpurchase = 'yes'AND notinuse = 'no' order by productname;";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['productcode'].'">'.wordwrap($fetch['dealerpurchasecaption'], 25, "<br />\n").' &nbsp;('.$fetch['productcode'].')</option>');
	}
?>
