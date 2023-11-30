<?php
	$query = "SELECT slno, branchname FROM inv_mas_branch ORDER BY branchname";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['slno'].'">'.$fetch['branchname'].'</option>');
	}
?>
