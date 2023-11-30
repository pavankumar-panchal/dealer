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
	$query = "select distinct inv_mas_product.productcode,inv_mas_product.productname from inv_productmapping left join inv_schemepricing on inv_productmapping.productcode = inv_schemepricing.product left join inv_mas_product on inv_mas_product.productcode = inv_productmapping.productcode where inv_schemepricing.scheme = '".$fetchproduct['slno']."' and inv_productmapping.dealerid = '".$userid."' ;";
	$result = runmysqlquery($query);
	echo('<select name="productcode" class="swiftselect-mandatory" id="productcode" style="width:200px;"  onchange="getamount("'.$userid.'")";><option value="">Select A product</option>');
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['productcode'].'">'.$fetch['productname'].'</option>');
	}
	echo('</select>\'; break; ');
}
?>
	}
	$('#'+divid).html(productlist);
}



