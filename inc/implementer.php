<?php
	$query = "SELECT slno,businessname from inv_mas_implementer where implementertype = 'implementer' and branchid = '".$branch."' and disablelogin='no'  order by slno";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['slno'].'">'.$fetch['businessname'].'</option>');
	}
?>