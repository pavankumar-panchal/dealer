<?php
	$query = "SELECT slno,servicename FROM inv_mas_service order by servicename;";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		 echo('<label for="'.$fetch['servicename'].'"><input type="checkbox" checked="checked"  name="itemarray[]" id="'.$fetch['servicename'].'" value ="'.$fetch['servicename'].'"  />&nbsp;'.$fetch['servicename']);
		 echo('</label>');
		 echo('<br/>');
	}
?>