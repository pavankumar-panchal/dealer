<?php
	$query = "select distinct `group` as prdgroup  from inv_mas_product where  `group` not in ('NA','CONTACT','SURVEY') order by `group`;";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['prdgroup'].'">'.$fetch['prdgroup'].'</option>');
	}
?>
