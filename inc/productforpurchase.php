<?php
	$query = "(select distinct inv_mas_product.productcode,inv_mas_product.productname,inv_mas_product.dealerpurchasecaption 
	from inv_productmapping 
	left join inv_schemepricing on inv_productmapping.productcode = inv_schemepricing.product 
	left join inv_mas_product on inv_mas_product.productcode = inv_productmapping.productcode 
	left join inv_mas_scheme on inv_schemepricing.scheme = inv_mas_scheme.slno where inv_mas_product.allowdealerpurchase = 'yes' and inv_schemepricing.scheme = '1' and  inv_productmapping.dealerid = '".$userid."' and inv_mas_product.notinuse = 'no')
	union
	(select productcode,productname,subgroup from inv_mas_product where subgroup = 'ESS' and inv_mas_product.allowdealerpurchase = 'yes' and inv_mas_product.notinuse = 'no') order by productname;";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['productcode'].'">'.$fetch['productname'].'</option>');
	}
?>
