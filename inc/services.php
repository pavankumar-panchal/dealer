<?php
	$query = "select * from inv_mas_service where disabled = 'no' order by servicename;";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['slno'].'">'.$fetch['servicename'].'</option>');
	}
?>
