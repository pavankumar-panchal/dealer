<?php
	// $query = "select distinct inv_mas_product.productcode,inv_mas_product.productname 
	// from inv_mas_product
	// left join inv_dealercard on inv_mas_product.productcode = inv_dealercard.productcode 
	// left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
	// where inv_dealercard.dealerid = '".$userid."' and inv_mas_scratchcard.blocked='no' and cancelled = 'no' and (inv_dealercard.`customerreference` is NULL or customerreference='') order by productname;";

	$query = "select distinct inv_mas_product.productcode,inv_mas_product.productname,inv_mas_product.dealerpurchasecaption 
	from inv_productmapping 
	left join inv_schemepricing on inv_productmapping.productcode = inv_schemepricing.product 
	left join inv_mas_product on inv_mas_product.productcode = inv_productmapping.productcode 
	left join inv_dealercard on inv_mas_product.productcode = inv_dealercard.productcode
	left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
	left join inv_mas_scheme on inv_schemepricing.scheme = inv_mas_scheme.slno where inv_mas_product.allowdealerpurchase = 'yes' and inv_schemepricing.scheme = '1' and  inv_productmapping.dealerid = '".$userid."' and inv_dealercard.dealerid = '".$userid."' and inv_mas_product.notinuse = 'no' and inv_mas_scratchcard.blocked='no' and cancelled = 'no' and (inv_dealercard.`customerreference` is NULL or inv_dealercard.customerreference='') order by productname;";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['productcode'].'">'.$fetch['productname'].'</option>');
	}
?>
