<?php
	$userid = imaxgetcookie('dealeruserid');
	$query = "SELECT DISTINCT imp.productcode,imp.dealerpurchasecaption FROM inv_mas_product imp 
	LEFT JOIN inv_dealercard invd ON invd.productcode = imp.productcode 
	WHERE imp.allowdealerpurchase = 'yes' AND imp.notinuse = 'no' AND imp.productcode 
	IN (SELECT DISTINCT productcode FROM inv_dealercard WHERE dealerid = '$userid' ) order by imp.productname;";

	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['productcode'].'">'.wordwrap($fetch['dealerpurchasecaption'], 25, "<br />\n").' &nbsp;('.$fetch['productcode'].')</option>');
	}
?>
