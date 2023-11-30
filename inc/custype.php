<?php
	$query = "SELECT slno,customertype FROM inv_mas_customertype ORDER BY customertype";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['slno'].'">'.$fetch['customertype'].'</option>');
	}
?>
