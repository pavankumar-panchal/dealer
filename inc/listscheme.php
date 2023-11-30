<?php
	$query = "SELECT slno, schemename FROM inv_mas_scheme ORDER BY schemename";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		
		echo('<option value="'.$fetch['slno'].'">'.$fetch['schemename'].'</option>');
	}
?>
