function getdealer(divid32,dealerid)
{

<?php
	$dealerid = imaxgetcookie('dealeruserid');
	include('../functions/phpfunctions.php');
		echo('dealerlist = \'');
		$query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode, inv_mas_dealer.region
from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
 where inv_mas_dealer.slno = '".$dealerid."';";
		$fetch = runmysqlqueryfetch($query);
		$relyonexecutive = $fetch['relyonexecutive'];
		$district = $fetch['district'];
		$state = $fetch['statecode'];
		$region = $fetch['region'];
		if($relyonexecutive == 'no')
		{
				$dealerpiece = " where slno = '".$dealerid."'";
		}
		else
		{
				$dealerpiece = " where region = '".$region."'";

		}
		$query = "select businessname as dealername, slno as dealerid from inv_mas_dealer ".$dealerpiece." ";
		$result1 = runmysqlquery($query);
		echo('<select name="dealerid" class="swiftselect-mandatory" id="dealerid" style="width:223px;"><option value="">ALL</option>');
		while($fetch = mysqli_fetch_array($result1))
		{
			echo('<option value="'.$fetch['dealerid'].'">'.$fetch['dealername'].'</option>');
			echo("\n");
		}	

		echo("</select>';"."\n\t\t");
		
?>	
}

