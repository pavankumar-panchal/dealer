function getselectiontype(type)
{
<?php include('../functions/phpfunctions.php');?>
    var form = $('#colorboxeditform');
	$('#headingname').html('');
	$("#fieldtype").html('');
	if(type == 'branch')
	{
<?php
	$query1 = "SELECT slno, branchname FROM inv_mas_branch ORDER BY branchname;";
	$result1 = runmysqlquery($query1);
	echo('branchlist = \'');
	echo('<select name="branch" class="swiftselect" id="branch" style="width:200px;" ><option value="">Select A Branch</option>');
	while($fetch1 = mysqli_fetch_array($result1))
	{
		echo('<option value="'.$fetch1['slno'].'">'.$fetch1['branchname'].'</option>');
	}
	echo('</select>\';');

?>
		$('#headingname').html('BRANCH'+':');
		$("#fieldtype").html(branchlist);
        $("#branch").val($("#branchcode").val());
	}
   else if(type == 'currentdealer')
	{
<?php
	$query2 = "SELECT slno,businessname FROM inv_mas_dealer order by businessname;";
	$result2 = runmysqlquery($query2);
	echo('dealerlist = \'');
	echo('<select name="currentdealer" class="swiftselect" id="currentdealer" style="width:200px;" ><option value="">Make A Selection</option>');
	while($fetch2 = mysqli_fetch_array($result2))
	{
		echo('<option value="'.$fetch2['slno'].'">'.$fetch2['businessname'].'</option>');
	}
	echo('</select>\';');

?>
		$('#headingname').html('CURRENT DEALER'+':');
		$("#fieldtype").html(dealerlist);
        $("#currentdealer").val($("#dealerid").val());
        
	}
    else if(type == 'region')
	{
<?php
	$query3 = "SELECT slno, category FROM inv_mas_region ORDER BY category;";
	$result3 = runmysqlquery($query3);
	echo('regionlist = \'');
	echo('<select name="region" class="swiftselect" id="region" style="width:200px;" ><option value="">Select A Region</option>');
	while($fetch3 = mysqli_fetch_array($result3))
	{
		echo('<option value="'.$fetch3['slno'].'">'.$fetch3['category'].'</option>');
	}
	echo('</select>\';');

?>
		$('#headingname').html('REGION'+':');
		$("#fieldtype").html(regionlist);
        $("#region").val($("#regioncode").val());
        
	}
 	else if(type == 'type')
	{
<?php
	$query3 = "SELECT slno,customertype FROM inv_mas_customertype ORDER BY customertype;";
	$result3 = runmysqlquery($query3);
	echo('typelist = \'');
	echo('<select name="type" class="swiftselect" id="type" style="width:200px;" ><option value="">Type Selection</option>');
	while($fetch3 = mysqli_fetch_array($result3))
	{
		echo('<option value="'.$fetch3['slno'].'">'.$fetch3['customertype'].'</option>');
	}
	echo('</select>\';');

?>
		$('#headingname').html('TYPE'+':');
		$("#fieldtype").html(typelist);
        $("#type").val($("#typecode").val());
        
	}
    else if(type == 'category')
	{
<?php
	$query4 = "SELECT slno,businesstype FROM inv_mas_customercategory ORDER BY businesstype;";
	$result4 = runmysqlquery($query4);
	echo('categorylist = \'');
	echo('<select name="category" class="swiftselect" id="category" style="width:200px;" ><option value="">Category Selection</option>');
	while($fetch4 = mysqli_fetch_array($result4))
	{
		echo('<option value="'.$fetch4['slno'].'">'.$fetch4['businesstype'].'</option>');
	}
	echo('</select>\';');

?>
		$('#headingname').html('CATEGORY'+':');
		$("#fieldtype").html(categorylist);
        $("#category").val($("#categorycode").val());
        
	}
        $("").colorbox({ inline:true, href:"#displaygridtab" , onLoad: function() {
        $('#cboxClose').hide()}});
    
}



