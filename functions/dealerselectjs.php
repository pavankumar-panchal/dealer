function getdealer(divid45,dealerid)
{

<?php
	include('../functions/phpfunctions.php');
	$dealerid = imaxgetcookie('dealeruserid');
	
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
				$query = "select businessname as dealername, slno as dealerid from inv_mas_dealer ".$dealerpiece." ";
				$result1 = runmysqlqueryfetch($query);
				echo($result1['dealername']);
				echo("';");
		}
		else 
		{
			$dealerpiece = " where region = '".$region."'";
			$query = "select businessname as dealername, slno as dealerid from inv_mas_dealer ".$dealerpiece." ";
			$result1 = runmysqlquery($query);
			echo('<select name="dealerid" class="swiftselect" id="dealerid" style="width:225px;"><option value="all">ALL</option>');
			while($fetch = mysqli_fetch_array($result1))
			{
				echo('<option value="'.$fetch['dealerid'].'">'.$fetch['dealername'].'</option>');
			}	
				echo("</select>';"."\n\t\t");
		}
	
		
?>
	document.getElementById(divid45).innerHTML = dealerlist ;
}

