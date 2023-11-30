<?php
include('../functions/phpfunctions.php');
include('../inc/checksession.php');

$statecode = $_POST['statecode'];
$query = "SELECT districtcode,districtname FROM inv_mas_district WHERE statecode = '".$statecode."' order by districtname;";
$result = runmysqlquery($query);
echo('<select name="district" class="swiftselect-mandatory" id="district" style="width:230px;"><option value="">Select A District</option>');
while($fetch = mysqli_fetch_array($result))
{
	echo('<option value="'.$fetch['districtcode'].'">'.$fetch['districtname'].'</option>');
}
echo('</select>');
?>
