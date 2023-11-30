<?php
	$query = "select `productname`, `group`, `productcode` from inv_mas_product where inv_mas_product.productcode BETWEEN 899 AND 905 AND inv_mas_product.productcode NOT IN (901);";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<label for = "'.$fetch['productname'].'"><input type="checkbox" class="productnames" name="productname[]"                id="'.$fetch['productname'].'"  producttype = "'.$fetch['group'].'"  value ="'.$fetch['productcode'].'" />&nbsp;'.$fetch['productname']);
		 echo('<font color = "#999999">&nbsp;('.$fetch['productcode'].')</font></label>');
		 echo('<br/>');

	}
?>
