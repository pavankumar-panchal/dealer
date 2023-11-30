<?php
	$userid = imaxgetcookie('dealeruserid');
	$query = "select distinct inv_mas_scheme.slno as slno,inv_mas_scheme.schemename as schemename from inv_mas_scheme left join inv_dealercard on inv_dealercard.scheme =  inv_mas_scheme.slno where inv_dealercard.dealerid = '".$userid."' order by slno";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['slno'].'">'.$fetch['schemename'].'</option>');
	}
?>
