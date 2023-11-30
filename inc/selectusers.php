<?php
	$query = "SELECT slno, fullname FROM inv_mas_users ORDER BY fullname";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['slno'].'">'.$fetch['fullname'].'</option>');
	}
?>