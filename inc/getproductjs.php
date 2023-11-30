function getproduct(divid,schemeid)
{
	switch(schemeid)
	{
			
<?php
		include('../functions/phpfunctions.php');
		$userid = imaxgetcookie('dealeruserid');
		$queryproduct = "SELECT distinct slno FROM inv_mas_scheme ORDER BY schemename;";
		$resultproduct = runmysqlquery($queryproduct);
		while($fetchproduct = mysqli_fetch_array($resultproduct))
		{
			echo('case "'.$fetchproduct['slno'].'": productlist = \'');
			$query = "select distinct inv_mas_scheme.todate,inv_mas_product.productcode,inv_mas_product.productname from inv_productmapping left join inv_schemepricing on inv_productmapping.productcode = inv_schemepricing.product left join inv_mas_product on inv_mas_product.productcode = inv_productmapping.productcode left join inv_mas_scheme on inv_schemepricing.scheme = inv_mas_scheme.slno where inv_mas_product.allowdealerpurchase = 'yes' and inv_schemepricing.scheme = '".$fetchproduct['slno']."' and  inv_productmapping.dealerid = '".$userid."' and inv_mas_scheme.todate > curdate();";
			$result = runmysqlquery($query);
			echo('<div align="left"><select name="product" class="swiftselect-mandatory" id="product" style="width:180px;"  onchange="getamount('.$userid.')";><option value="">Select A product</option>');
			while($fetch = mysqli_fetch_array($result))
			{
				echo('<option value="'.$fetch['productcode'].'">'.$fetch['productname'].'</option>');
			}
			echo('</select></div>\'; break; ');
		}
?>
	}
	document.getElementById(divid).innerHTML = productlist;
}
