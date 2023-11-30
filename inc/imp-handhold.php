
<?php
	$query = "SELECT * from imp_mas_handholdtype";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		 echo('<label for = "'.$fetch['slno'].'"><input checked="checked" type="checkbox" name="handhold[]" id="'.$fetch['handholdtype'].'"    value ="'.$fetch['slno'].'" />&nbsp;'.$fetch['handholdtype']);
		 echo('<br/>');
	}
?>