<?php
	$query = "SELECT statecode,statename FROM inv_mas_state order by statename;";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['statecode'].'">'.$fetch['statename'].'</option>');
	}
?>