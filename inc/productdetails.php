<?php
	$query = "SELECT productcode,productname,`group` as productgroup FROM inv_mas_product order by productname;";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		 echo('<label><input type="checkbox" checked="checked"  name="productarray[]" id="'.$fetch['productname'].'" value ="'.$fetch['productcode'].'"  producttype ="'.$fetch['productgroup'].'" />&nbsp;'.$fetch['productname']);
		 echo('<font color = "#999999">&nbsp;('.$fetch['productcode'].')</font></label>');
		 echo('<br/>');
	}
?>