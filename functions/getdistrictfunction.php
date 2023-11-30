function getdistrictfilter(divid,statecode)
{

var districtcode = $('#district2').val();
	if(checkdistrictlist(districtcode, statecode) == true)
        return false;
	switch(statecode)
	{
		case '':
			districtlist = '<select name="district2" class="swiftselect" id="district2" style="width: 180px;"><option value="">ALL</option></select>';
			break;
			
<?php
include('../functions/phpfunctions.php');

$querystate = "SELECT distinct statecode FROM inv_mas_state order by statename;";
$resultstate = runmysqlquery($querystate);
while($fetchstate = mysqli_fetch_array($resultstate))
{
	echo('case "'.$fetchstate['statecode'].'": districtlist = \'');
	$query = "SELECT districtcode,districtname FROM inv_mas_district WHERE statecode = '".$fetchstate['statecode']."' order by districtname;";
	$result = runmysqlquery($query);
	echo('<select name="district2" class="swiftselect" id="district2" style="width:180px;"><option value="">ALL</option>');
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['districtcode'].'">'.$fetch['districtname'].'</option>');
	}
	echo('</select>\'; break; ');
}

?>
	}
	$('#'+divid).html(districtlist);
    return true;
}
function checkdistrictlist(districtcode, statecode)
{
    var fullstatearray = new Array();

<?php
		$query1 = "SELECT distinct statecode FROM inv_mas_state order by statename";
		$result = runmysqlquery($query1);
		while($fetchstate = mysqli_fetch_array($result))
		{
			$statecode =$fetchstate['statecode'];
			echo("\n");
			echo("fullstatearray['".$statecode."'] = new Array(");
			$query = "SELECT districtcode FROM inv_mas_district WHERE statecode = '".$statecode."' order by districtname;";
			$result2 = runmysqlquery($query);
			$count = 1;
			while($fetch = mysqli_fetch_array($result2))
			{
				if($count > 1)
					echo(",");
				echo("'");
				echo($fetch['districtcode']);
				echo("'");
				$count++;
			}
			echo(");");
			echo("\n");
		}
?>
    if(in_array(districtcode,fullstatearray[statecode]))
		return true;
    else
		return false;
}


