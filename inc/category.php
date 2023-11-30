<?php
	$query = "SELECT slno,businesstype FROM inv_mas_customercategory ORDER BY businesstype";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['slno'].'">'.$fetch['businesstype'].'</option>');
	}
?>
