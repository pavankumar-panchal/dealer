
function getscheme(divid12,dealerid)
{
	switch(dealerid)
	{
			
<?php
include('../functions/phpfunctions.php');

$queryscheme = "SELECT distinct slno as dealerid FROM inv_mas_dealer ORDER BY businessname;";
$resultscheme = runmysqlquery($queryscheme);
while($fetchscheme = mysqli_fetch_array($resultscheme))
{
	echo("\t".'case "'.$fetchscheme['dealerid'].'":'."\n\t\t".'schemelist = \'');
	$query = "select distinct  inv_mas_scheme.slno,inv_mas_scheme.schemename from inv_mas_scheme 
left join inv_schememapping on inv_mas_scheme.slno = inv_schememapping.scheme where inv_schememapping.dealerid = '".$fetchscheme['dealerid']."' or inv_mas_scheme.slno = '1';";
	$result = runmysqlquery($query);
	echo('<select name="scheme" class="swiftselect-mandatory" id="scheme" style="width:200px;" onchange = "getproduct(\\\'displayproductcode\\\',this.value);">');
	while($fetch = mysqli_fetch_array($result))
	{
		
		if($fetch['slno'] == '1')
		{
			echo('<option value="'.$fetch['slno'].'" selected = "selected">'.$fetch['schemename'].'</option>');
		}
		else
			echo('<option value="'.$fetch['slno'].'">'.$fetch['schemename'].'</option>');
	}
	echo("</select>';"."\n\t\t"." break; "."\n");
}

?>
	}
	$('#'+divid12).html(schemelist);
}



