<?php
	$query = "SELECT  category FROM inv_mas_region ORDER BY category";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['category'].'">'.$fetch['category'].'</option>');
	}
?>
