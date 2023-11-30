
function getscheme(divid12,dealerid)
{

		
<?php
	include('../functions/phpfunctions.php');
	$userid = imaxgetcookie('dealeruserid');
	echo('schemelist = \'');
	$query = "select distinct  inv_mas_scheme.slno,inv_mas_scheme.schemename from inv_mas_scheme 
left join inv_schememapping on inv_mas_scheme.slno = inv_schememapping.scheme where inv_schememapping.dealerid = '".$userid."' or inv_mas_scheme.slno = '1' and inv_mas_scheme.todate > curdate();";
	$result = runmysqlquery($query);
	echo('<select name="scheme" class="swiftselect-mandatory" id="scheme" style="width:180px;" onchange = "getproduct(\\\'displayproductcode\\\',this.value);">');
	while($fetch = mysqli_fetch_array($result))
	{
		
		if($fetch['slno'] == '1')
		{
			echo('<option value="'.$fetch['slno'].'" selected = "selected">'.$fetch['schemename'].'</option>');
		}
		else
			echo('<option value="'.$fetch['slno'].'">'.$fetch['schemename'].'</option>');
	}
	echo("</select>';"."\n\t\t");


?>
	document.getElementById(divid12).innerHTML = schemelist;
}

