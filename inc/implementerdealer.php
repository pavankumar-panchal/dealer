<?php
	$query = "SELECT distinct inv_mas_dealer.slno,businessname FROM imp_implementation
	left join inv_mas_dealer on imp_implementation.dealerid = inv_mas_dealer.slno where branch = '".$branch."' and disablelogin='no' and relyonexecutive='yes' order by businessname;";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['slno'].'">'.wordwrap($fetch['businessname'], 25, "<br />\n").'</option>');
	}
?>
