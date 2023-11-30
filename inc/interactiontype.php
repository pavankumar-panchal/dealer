<?php
	$query = "SELECT slno, interactiontype FROM inv_mas_interactiontype ORDER BY interactiontype";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		if($fetch['slno'] == '1')
		{
		echo('<option value="'.$fetch['slno'].'" selected = "selected">'.$fetch['interactiontype'].'</option>');
		}
		else
		
		echo('<option value="'.$fetch['slno'].'">'.$fetch['interactiontype'].'</option>');
	}
?>
