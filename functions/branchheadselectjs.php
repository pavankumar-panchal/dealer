function checkdealerselection(divid45,dealerid)
{

<?php
	include('../functions/phpfunctions.php');
	$dealerid = imaxgetcookie('dealeruserid');
	
		echo('dealerlist = \'');
		$query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode, inv_mas_dealer.region, inv_mas_dealer.branchhead as branchhead,inv_mas_dealer.telecaller as telecaller from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
 where inv_mas_dealer.slno = '".$dealerid."';";
		$fetch = runmysqlqueryfetch($query);
		$branchhead = $fetch['branchhead'];
		$telecaller = $fetch['telecaller'];
		if($branchhead == 'yes')
		{
				echo('<input type="hidden" id="logintype" name="logintype" value="branchhead" />');
		}
		else
		{
				echo('<input type="hidden" id="logintype" name="logintype" value="notbranchhead" />');
		}
		if($branchhead == 'yes' || $telecaller == 'yes')
		{
			$query1 = "select * from inv_mas_dealer where slno = '".$dealerid."';";
			$resultfetch1 = runmysqlqueryfetch($query1);
			$branch = $resultfetch1['branch'];
			$query = "SELECT distinct inv_mas_dealer.slno, inv_mas_dealer.businessname
FROM inv_invoicenumbers left join inv_mas_dealer on  inv_mas_dealer.slno = inv_invoicenumbers.dealerid 
where inv_mas_dealer.disablelogin = 'no' and inv_mas_dealer.dealernotinuse = 'no' 
and inv_mas_dealer.branch = '".$branch."' 
Union distinct
select distinct inv_invoicenumbers.createdbyid,inv_invoicenumbers.createdby from inv_invoicenumbers
left join inv_mas_dealer on  inv_mas_dealer.slno = inv_invoicenumbers.createdbyid 
where  inv_mas_dealer.telecaller = 'yes' and inv_mas_dealer.branch = '".$branch."'  order by businessname";
			$result1 = runmysqlquery($query);
			//echo($query);exit;
			//echo('<select name="dealerid" class="swiftselect" id="dealerid" style="width:200px;"><option value="all">ALL</option>');
			echo('<select name="dealerid" class="swiftselect" id="dealerid" onchange="disabledealer()" style="width:200px;"><option value=" ">ALL</option>');
			while($fetch = mysqli_fetch_array($result1))
			{
				echo('<option value="'.$fetch['slno'].'">'.$fetch['businessname'].'</option>');
			}	
				echo("</select>';"."\n\t\t");
		}
		else
		{
				$dealerpiece = " where slno = '".$dealerid."'";
				$query = "select businessname as dealername, slno as dealerid from inv_mas_dealer ".$dealerpiece." ";
				$result1 = runmysqlqueryfetch($query);
				echo($result1['dealername']);
				echo('<input type="hidden" id="dealerid" name="dealerid" value="'.$dealerid.'" /><input type="hidden" id="logintype" name="logintype" value="notbranchhead" />');
				echo("';");
		}
		
	
		
?>
	document.getElementById(divid45).innerHTML = dealerlist ;
}

