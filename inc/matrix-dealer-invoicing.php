<?php

	$userid = imaxgetcookie('dealeruserid');
	$query1 = "select * from inv_mas_dealer where slno = '".$userid."';";
	$resultfetch1 = runmysqlqueryfetch($query1);
	$branch = $resultfetch1['branch'];
	
	$query2 = "select group_concat(branchid) as branchid,count(*) as rowcount from inv_dealer_branch_mapping where dealerid = '".$userid."'";
	$resultfetch = runmysqlqueryfetch($query2);
	$rowcount = $resultfetch['rowcount'];
	if($rowcount > 0)
	{
		$branch = $resultfetch['branchid'];
		$allbrancharray = explode(',',$branch);
		$allbranchvalue = implode("','",$allbrancharray);
		$query = "SELECT slno,businessname FROM inv_mas_dealer where disablelogin = 'no' and dealernotinuse = 'no' and branch in ('".$allbranchvalue."') order by businessname;";
	}
	else
	{
	  $query = "SELECT slno,businessname FROM inv_mas_dealer where disablelogin = 'no' and dealernotinuse = 'no' and branch = '".$branch."' and relyonexecutive='yes' order by businessname;";
	}
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['slno'].'">'.wordwrap($fetch['businessname'], 25, "<br />\n").'</option>');
	}
?>