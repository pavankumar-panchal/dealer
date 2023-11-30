<?php
	$query = "SELECT slno,statusname FROM inv_mas_status ";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		if($fetch['slno'] == '1')
		{
			echo('<option value="'.$fetch['slno'].'" selected="selected">'.$fetch['statusname'].'</option>');
		}
		else
		{
			echo('<option value="'.$fetch['slno'].'">'.$fetch['statusname'].'</option>');
		}
	}
?>

