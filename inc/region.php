<?php
	$query = "SELECT slno,category from inv_mas_region order by slno";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['slno'].'">'.$fetch['category'].'</option>');
	}
?>