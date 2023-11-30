<?php
	$query = "SELECT distinct inv_mas_product.productcode,inv_mas_product.productname,inv_mas_product.`group` as productgroup 
	FROM imp_implementation 
	left join inv_mas_dealer on imp_implementation.dealerid = inv_mas_dealer.slno
	left join inv_mas_product on inv_mas_product.productcode = imp_implementation.productcode 
	 where imp_implementation.productcode!='' and branch = '".$branch."'";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		 echo('<label for = "'.$fetch['productname'].'"><input checked="checked" type="checkbox" name="productname[]" id="'.$fetch['productname'].'"    value ="'.$fetch['productcode'].'" />&nbsp;'.$fetch['productname']);
		 echo('<font color = "#999999">&nbsp;('.$fetch['productcode'].')</font></label>');
		 echo('<br/>');
	}
?>