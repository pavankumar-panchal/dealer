<html>
<head>
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
	<style>
		div.dt-buttons {
			position: relative;
			float: left;
			padding-top: 2%;
			padding-left: 2%;
		}
		.dataTables_wrapper .dataTables_filter {
			float: right;
			text-align: right;
			padding-top: 2%;
			padding-right: 5%;
		}


		.dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter, .dataTables_wrapper .dataTables_info, .dataTables_wrapper .dataTables_processing, .dataTables_wrapper .dataTables_paginate {
			color: #333;
			padding-left: 5%;
			padding-top: 2%;
		}
	</style>
</head>
<body>
<?php
ini_set('memory_limit', '2048M');

include('../functions/phpfunctions.php');


//PHPExcel
//require_once '../phpgeneration/PHPExcel.php';

//PHPExcel_IOFactory
//require_once '../phpgeneration/PHPExcel/IOFactory.php';

$flag = $_POST['flag'];
if($flag == '')
{
	$url = '../home/index.php?a_link=registrationreport'; 
	header("location:".$url);
	exit;
}
elseif($flag == 'true')
{
	$userid = imaxgetcookie('dealeruserid');
	$id = $_GET['id'];
	$fromdate = $_POST['fromdate'];
	$todate = $_POST['todate'];
	$customerselection = $_POST['customerselection'];
	$searchtext = $_POST['searchtext'];
	$searchtextid = $_POST['searchtextid'];
	$geography = $_POST['geography'];
	$region = $_POST['region'];
	$state = $_POST['state'];
	$district = $_POST['district'];
	$dealer = $_POST['displayalldealer'];
	$groupon = $_POST['groupon'];
	$otheroptions = $_POST['otheroptions'];
	$card = $_POST['card'];
	$reregistration = $_POST['reregistration'];
	//$productcode = $_POST['productcode'];
	$billnumberfrom = $_POST['billnumberfrom'];
	$billnumberto = $_POST['billnumberto'];
	$multilogin = $_POST['multilogin'];
	$usagetype = $_POST['usagetype'];
	$purchasetype = $_POST['purchasetype'];
	$generatedby = $_POST['generatedby'];
	$chks = $_POST['productname'];
	for ($i = 0;$i < count($chks);$i++)
	{
		$c_value .= "'" . $chks[$i] . "'" ."," ;
	}
	$productslist = rtrim($c_value , ',');
	$value = str_replace('\\','',$productslist);

	//exit;
	
	#Added on 2nd Feb 2018
	$query = "select inv_mas_dealer.maindealers,inv_mas_dealer.dealerhead from inv_mas_dealer where inv_mas_dealer.slno = '".$userid."';";
	$fetch = runmysqlqueryfetch($query);
	$maindealers = $fetch['maindealers'];
	$dealerhead = $fetch['dealerhead'];
    #Ends on 2nd Feb 2018
    
		if($fromdate <> '' && $todate <> '')
		{
			$customerselectionpiece = ($customerselection == 'allcustomer')?(""):(" AND inv_customerproduct.customerreference = '".$searchtextid."' ");
			
			if($geography == "") { $geographypiece = ""; } 
			elseif($geography == "region") { $geographypiece = " AND inv_mas_customer.region = '".$region."' " ; }
			elseif($geography == "state") { $geographypiece = " AND inv_mas_district.statecode = '".$state."' " ; }
			elseif($geography == "district") { $geographypiece = " AND inv_mas_customer.district = '".$district."' " ; }
			
			
			if($card == "") {$cardpiece = ""; }
			elseif($card == 'withcard') {$cardpiece = " AND inv_customerproduct.cardid <> '' ";}
			elseif($card == 'withoutcard') {$cardpiece = " AND inv_customerproduct.cardid = '' ";}
			
			if($reregistration == "") {$reregistrationpiece = ""; }
			elseif($reregistration == 'yes') {$reregistrationpiece =  " AND inv_customerproduct.reregistration = 'yes' ";}
			elseif($reregistration == 'no') {$reregistrationpiece = " AND inv_customerproduct.reregistration = 'no' ";}
			
			$generatedbypiece = ($generatedby == "")?(""):(" AND inv_customerproduct.generatedby = '".$generatedby."' ");
			//$productcodepiece = ($productcode == "")?(""):(" AND inv_dealercard.productcode = '".$productcode."' ");
			$productcodepiece = ($chks == "")?(""):(" AND  inv_dealercard.productcode IN (".$value.") ");
			$usagetypepiece = ($usagetype == "")?(""):(" AND inv_dealercard.usagetype = '".$usagetype."' ");
			$purchasetypepiece = ($purchasetype == "")?(""):(" AND inv_dealercard.purchasetype = '".$purchasetype."' ");
			$dealertypepiece = ($dealer == "")?(""):(" AND inv_mas_dealer.slno = '".$dealer."' ");
			$billnumberpiece = ($billnumberfrom == "" || $billnumberto == "")?(""):(" AND inv_customerproduct.cusbillnumber BETWEEN '".$billnumberfrom."' AND '".$billnumberto."' ");
			
			if($groupon == 'product') { $grouponpiece = " inv_mas_product.productname "; }
			elseif($groupon == 'generatedby') { $grouponpiece = " inv_mas_users.fullname "; }
			elseif($groupon == 'dealer') { $grouponpiece = " inv_mas_dealer.businessname "; }	
			elseif($groupon == 'date') { $grouponpiece = " inv_customerproduct.date "; }	
			
			$query0 = "select * from inv_mas_dealer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_dealer.district where inv_mas_dealer.slno = '".$userid."'";
			$result0 = runmysqlqueryfetch($query0);
			$relyonexecutive = $result0['relyonexecutive'];
			$state = $result0['statecode'];
			$statepiece = ($state == 02 || $state == 37 ) ? "where inv_mas_district.statecode in (02,37)" : "where inv_mas_district.statecode = '".$state."'";

			if($relyonexecutive == 'no')
			{
				
				$query = "select *  from inv_mas_customer where inv_mas_customer.currentdealer = '".$userid."' order by businessname;";
				$result = runmysqlquery($query);
				if(mysqli_num_rows($result) == 0)
				{
					$dealerpiece = " where";
				}
				else
		
					$dealerpiece = " where inv_mas_dealer.slno = '".$userid."'";
			}
			else
			{
				if(($_POST['dealerid'] == 'all') || ($_POST['dealerid'] == ''))
				{
					$userid = imaxgetcookie('dealeruserid');
				}
				else
					$userid = $_POST['dealerid'];

				$query = "select * from inv_mas_customer left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode where inv_mas_district.districtcode in( select districtcode from inv_districtmapping where dealerid = '".$userid."') ;";
					$result = runmysqlquery($query);
					if(mysqli_num_rows($result) == 0)
					{
						if(($_POST['dealerid'] == 'all') )
						{
							$dealerpiece = $statepiece;
						}
						else
						{
							$dealerpiece = " where inv_mas_dealer.slno = '".$userid."'";
						}
					}
					else
					{
						if(($_POST['dealerid'] == 'all') )
						{
							$dealerpiece = " LEFT JOIN inv_districtmapping on inv_mas_customer.district = inv_districtmapping.districtcode WHERE  inv_mas_customer.district is not null and inv_districtmapping.dealerid =  '".$userid."'";
						}
						else
						{
							$dealerpiece = " where inv_mas_customer.currentdealer = '".$userid."'";
						}
					}
			}
			
			$query = "SELECT distinct inv_mas_customer.businessname as customername, 
		 inv_customerproduct.cardid as cardid, 
		inv_customerproduct.date as `date`, inv_customerproduct.computerid as computerid, 
		inv_customerproduct.softkey as softkey, inv_mas_dealer.slno as slno,inv_mas_users.fullname as userid, inv_mas_dealer.businessname as dealername, inv_customerproduct.billnumber as cusbillnumber,  inv_customerproduct.billamount as billamount,  inv_customerproduct.remarks as remarks, inv_mas_product.productname as productname,inv_mas_scheme.schemename,inv_mas_district.districtname as district,inv_customerproduct.reregistration as reregistration from inv_customerproduct  LEFT JOIN inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno
		   LEFT JOIN inv_mas_users on inv_mas_users.slno = inv_customerproduct.generatedby  LEFT JOIN inv_mas_dealer ON inv_mas_dealer.slno = inv_customerproduct.dealerid  LEFT JOIN inv_dealercard ON inv_dealercard.cardid = inv_customerproduct.cardid  LEFT JOIN inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3) LEFT JOIN inv_mas_scheme on inv_mas_scheme.slno =inv_dealercard.scheme left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district ".$dealerpiece." and inv_customerproduct.date BETWEEN '".changedateformat($fromdate)."' AND '".changedateformat($todate)."'    ".$geographypiece.$reregistrationpiece.$cardpiece.$customerselectionpiece.$generatedbypiece.$productcodepiece.$usagetypepiece.$purchasetypepiece.$billnumberpiece.$dealertypepiece."  ORDER BY `date` desc, ".$grouponpiece.";";
		
		}
		
		$result = runmysqlquery($query); ?>

		<table class="table table-border table-condensed table-bordered" style="margin-top: 5%" id="example">
        <thead>
        <tr>
            <th>Sl No</th>
            <th>Product</th>
            <th>Customer</th>
            <th>Pin Serial Number</th>
            <th>Product</th>
            <th>Date</th>
            <th>Computer ID</th>
            <th>Soft Key</th>
            <th>Generated By</th>
            <th>Dealer</th>
            <th>BIll Number</th>
            <th>Billed Amount</th>
            <th>Remarks</th>
            <th>Scheme</th>
            <th>District</th>
            <th>Re-Registration</th>

        </tr>
        </thead>
        <tbody>
        <?php
        $slno = 1;
            while ($fetch = mysqli_fetch_array($result)) {

                ?>

<tr>
	<td><?php echo $slno++; ?></td>
	<td><?php echo $fetch['productname']; ?></td>
	<td><?php echo $fetch['customername']; ?></td>
	<td><?php echo $fetch['cardid']; ?></td>
	<td><?php echo $fetch['productname']; ?></td>
	<td><?php echo changedateformat($fetch['date']); ?></td>
	<td><?php echo $fetch['computerid']; ?> </td>
	<td><?php echo $fetch['softkey']; ?></td>
	<td><?php echo $fetch['userid']; ?></td>
	<td><?php echo $fetch['dealername']; ?> </td>
	<td><?php echo $fetch['cusbillnumber']; ?> </td>
	<td><?php echo $fetch['billamount']; ?></td>
	<td><?php echo $fetch['remarks']; ?></td>
	<td><?php echo $fetch['schemename']; ?> </td>
	<td><?php echo $fetch['district']; ?> </td>
	<td><?php echo $fetch['reregistration']; ?> </td>
</tr>
<?php } ?>
</tbody>
</table>
<?php

$query = 'select slno,dealerusername from inv_mas_dealer where slno = '.$userid.'';
$fetchres = runmysqlqueryfetch($query);
$dealername = $fetchres['dealerusername'];
$localdate = datetimelocal('Ymd');
$localtime = datetimelocal('His');
$filebasename = "RegistrationReport".$localdate."-".$localtime."-".strtolower($dealername).".xls";

?>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script>

	$(document).ready(function() {
		//alert("passing");
		var filename="<?php echo $filebasename; ?>";
		//alert(filename);
		$('#example').DataTable({
			dom: 'Blfrtip',
			buttons: [
				{
					extend: 'excelHtml5',

					title: 'Relyon Softech Limited, Bangalore.',
					messageTop: 'Product Registration Details',
					filename: filename,
					customize: function( xlsx ) {
						var sheet = xlsx.xl.worksheets['sheet1.xml'];
						$('row:first c', sheet).attr( 's', '50');
						$('row:first c', sheet).attr( 's', '2');
						// $('row c[r*="2"]', sheet).attr('s', '50');
						// $('row c[r*="2"]', sheet).attr('s', '2');
						//$('row c[r*="3"]', sheet).attr('s', '27');
						// $('row c[r*="3"]', sheet).attr('s', '42');
						$('row:eq(1) c', sheet).attr( 's', '50');
						$('row:eq(1) c', sheet).attr( 's', '2');
						$('row:eq(2) c', sheet).attr( 's', '42');
						// $('row:eq(2) c', sheet).attr( 's', '17');
						//$('row:eq(2) c', sheet).attr( 's', '');
						insertdata();
						//$('row c:nth-child(2)', sheet).attr('s', '50');
					}
					// messageTop: 'Relyon Softech Limited, Bangalore.'

				}
			]
		} );
	} );
</script>


<?php

$query1 ="INSERT INTO relyon_imax.inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','excel_registration_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
$result = runmysqlquery($query1);

$eventquery = "Insert into relyon_imax.inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','141','".date('Y-m-d').' '.date('H:i:s')."','excel_registration_report".'-'.strtolower($dealername)."')";
$eventresult = runmysqlquery($eventquery);
?>
<script>
	function insertdata()
	{
		<?php
		$query1 ="INSERT INTO relyon_imax.inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','view_registration_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
		$result = runmysqlquery($query1);

		$eventquery = "Insert into relyon_imax.inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','140','".date('Y-m-d').' '.date('H:i:s')."','view_registration_report')";
		$eventresult = runmysqlquery($eventquery);
		?>
	}
</script>
</body>
</html>
<?php } ?>