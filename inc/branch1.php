<?php
	$query = "SELECT  branchname FROM inv_mas_branch ORDER BY branchname";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['branchname'].'">'.$fetch['branchname'].'</option>');
	}
?>
