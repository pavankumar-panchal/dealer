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
require_once '../phpgeneration/PHPExcel.php';

//PHPExcel_IOFactory
require_once '../phpgeneration/PHPExcel/IOFactory.php';

$flag = $_POST['flag'];
if($flag == '')
{
	$url = '../home/index.php?a_link=dealerstockreport'; 
	header("location:".$url);
	exit;
}
elseif($flag == 'true')
{
	$userid = imaxgetcookie('dealeruserid');
	$id = $_GET['id'];
	$todate = $_POST['todate'];
	$fromdate = $_POST['fromdate'];
	$productcode = $_POST['productname'];
	$usagetype = $_POST['usagetype'];
	$purchasetype = $_POST['purchasetype'];
	$free = $_POST['free'];
	$registered = $_POST['registered'];
	$scheme = $_POST['scheme'];
	$pintype = $_POST['pintype'];
	$chks = $_POST['productname'];
	for ($i = 0;$i < count($chks);$i++)
	{
		$c_value .= "'" . $chks[$i] . "'" ."," ;
	}
	$productslist = rtrim($c_value , ',');
	$value = str_replace('\\','',$productslist);

	#Added on 2nd Feb 2018

	$query = "select inv_mas_dealer.branchhead,inv_mas_dealer.dealerhead from inv_mas_dealer where inv_mas_dealer.slno = '".$userid."';";
	$fetch = runmysqlqueryfetch($query);
	$maindealers = $fetch['branchhead'];
	$dealerhead = $fetch['dealerhead'];

    #Ends on 2nd Feb 2018

		if($todate <> '' && $fromdate <> '')
		{
			//echo($reportdate);
			$productcodepiece = ($productcode == "")?(""):(" AND inv_dealercard.productcode IN (".$value.") ");
			$usagetypepiece = ($usagetype == "")?(""):(" AND inv_dealercard.usagetype = '".$usagetype."' ");
			$purchasetypepiece = ($purchasetype == "")?(""):(" AND inv_dealercard.purchasetype = '".$purchasetype."' ");
			$registeredpiece = ($registered == "")?(""):(" AND inv_mas_scratchcard.registered = '".$registered."' ");
			$schemepiece = ($scheme == "")?(""):(" AND inv_mas_scheme.slno = '".$scheme."' ");
			$datepiece = " AND left(inv_dealercard.date,10) BETWEEN '".changedateformat($fromdate)."' AND '".changedateformat($todate)."'";

			if ($pintype == "") {
				$pintypepiece = " AND inv_mas_scratchcard.blocked = 'no'";
			} else if ($pintype == "blocked") {
				$pintypepiece = " AND inv_mas_scratchcard.blocked = 'yes'";
			} else if ($pintype == "cancelled") {
				$pintypepiece = " AND inv_mas_scratchcard.cancelled = 'yes'";
			}
			else if($pintype == "active") {$pintypepiece = " AND inv_mas_scratchcard.cancelled = 'no' AND  inv_mas_scratchcard.blocked = 'no'";}

			if($dealerhead!= "" || ($dealerhead == '' && $maindealers == 'no'))
			{
				if($pintype == "unregistered") {
					$query = "select inv_mas_dealer.businessname as dealername,inv_mas_scratchcard.cardid as cardid, 
				inv_mas_product.productname as productname,inv_dealercard.date as attcheddate, 
				inv_mas_scratchcard.scratchnumber as scratchnumber,inv_dealercard.usagetype as usagetype,
				inv_dealercard.purchasetype as purchasetype,inv_mas_users.fullname,inv_mas_scheme.schemename as scheme,inv_bill.remarks as billremarks  
				from inv_dealercard 
				left join inv_mas_dealer on inv_mas_dealer.slno = inv_dealercard.dealerid 
				LEFT JOIN inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
				LEFT JOIN inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode  
				left join inv_mas_users on inv_mas_users.slno = inv_dealercard.userid 
				LEFT JOIN inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme 
			    left join inv_bill on inv_bill.slno = inv_dealercard.cusbillnumber  
			    WHERE inv_dealercard.cardid!=0 and inv_dealercard.dealerid = '" . $userid . "' 
			      " . $datepiece . $productcodepiece . $usagetypepiece . $purchasetypepiece  . $schemepiece . $pintypepiece . ";";
				}
				else{


					$query = "select inv_mas_customer.businessname as cusname,inv_mas_dealer.businessname as dealername,inv_mas_scratchcard.cardid as cardid, 
				inv_mas_product.productname as productname,inv_dealercard.date as attcheddate,inv_customerproduct.billnumber as billnumber, 
				inv_customerproduct.date as registereddate,inv_mas_scratchcard.scratchnumber as scratchnumber,inv_dealercard.usagetype as usagetype,
				inv_dealercard.purchasetype as purchasetype,inv_mas_users.fullname,inv_mas_scheme.schemename as scheme,inv_bill.remarks as billremarks  
				from inv_dealercard 
				left join inv_mas_dealer on inv_mas_dealer.slno = inv_dealercard.dealerid 
				LEFT JOIN inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid  
				LEFT JOIN  inv_customerproduct on inv_dealercard.cardid = inv_customerproduct.cardid and inv_customerproduct.reregistration = 'no' 
				LEFT JOIN inv_mas_customer on inv_mas_customer.slno = inv_customerproduct.customerreference 
				LEFT JOIN inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode  
				left join inv_mas_users on inv_mas_users.slno = inv_dealercard.userid 
				LEFT JOIN inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme 
			    left join inv_bill on inv_bill.slno = inv_dealercard.cusbillnumber  
			    WHERE inv_dealercard.cardid!=0 and inv_dealercard.dealerid = '" . $userid . "' 
			      " . $datepiece . $productcodepiece . $usagetypepiece . $purchasetypepiece . $registeredpiece . $schemepiece . $pintypepiece . ";";
				}
		}
		else
		{
		   //exit;
		    #Added on Feb 02
			    if($maindealers == 'yes')
	            {
					if($pintype == "unregistered") {


						$query = "select inv_mas_dealer.businessname as dealername,inv_mas_scratchcard.cardid as cardid, 
	    			inv_mas_product.productname as productname,inv_dealercard.date as attcheddate,
	    			inv_mas_scratchcard.scratchnumber as scratchnumber,inv_dealercard.usagetype as usagetype,
	    			inv_dealercard.purchasetype as purchasetype,inv_mas_users.fullname,inv_mas_scheme.schemename as scheme,inv_bill.remarks as billremarks  
	    			from inv_dealercard 
	    			left join inv_mas_dealer on inv_mas_dealer.slno = inv_dealercard.dealerid 
	    			LEFT JOIN inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 
	    			LEFT JOIN inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode  
	    			left join inv_mas_users on inv_mas_users.slno = inv_dealercard.userid 
	    			LEFT JOIN inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme 
	    		    left join inv_bill on inv_bill.slno = inv_dealercard.cusbillnumber  
	    		    WHERE (inv_dealercard.dealerid IN (select slno from inv_mas_dealer where dealerhead = '" . $userid . "') or inv_dealercard.dealerid = '" . $userid . "')
	    		     " . $datepiece . $productcodepiece . $usagetypepiece . $purchasetypepiece . $registeredpiece . $schemepiece . $pintypepiece . ";";
					}
					else
					{
						$query = "select inv_mas_customer.businessname as cusname,inv_mas_dealer.businessname as dealername,inv_mas_scratchcard.cardid as cardid, 
	    			inv_mas_product.productname as productname,inv_dealercard.date as attcheddate,inv_customerproduct.billnumber as billnumber, 
	    			inv_customerproduct.date as registereddate,inv_mas_scratchcard.scratchnumber as scratchnumber,inv_dealercard.usagetype as usagetype,
	    			inv_dealercard.purchasetype as purchasetype,inv_mas_users.fullname,inv_mas_scheme.schemename as scheme,inv_bill.remarks as billremarks  
	    			from inv_dealercard 
	    			left join inv_mas_dealer on inv_mas_dealer.slno = inv_dealercard.dealerid 
	    			LEFT JOIN inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid  
	    			LEFT JOIN  inv_customerproduct on inv_dealercard.cardid = inv_customerproduct.cardid and inv_customerproduct.reregistration = 'no' 
	    			LEFT JOIN inv_mas_customer on inv_mas_customer.slno = inv_customerproduct.customerreference 
	    			LEFT JOIN inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode  
	    			left join inv_mas_users on inv_mas_users.slno = inv_dealercard.userid 
	    			LEFT JOIN inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme 
	    		    left join inv_bill on inv_bill.slno = inv_dealercard.cusbillnumber  
	    		    WHERE (inv_dealercard.dealerid IN (select slno from inv_mas_dealer where dealerhead = '" . $userid . "') OR (inv_mas_customer.currentdealer = '" . $userid . "') or inv_dealercard.dealerid = '" . $userid . "')
	    		     " . $datepiece . $productcodepiece . $usagetypepiece . $purchasetypepiece . $registeredpiece . $schemepiece . $pintypepiece . ";";
					}

	            }
	        }
            #Ends Added on feb 02
		}
		$result = runmysqlquery($query);
?>
<table class="table table-border table-condensed table-bordered" style="margin-top: 5%" id="example">
	<thead>
	<tr>
		<th>Sl No</th>
		<th>Dealer</th>
		<th>Pin Serial Number</th>
		<th>Product</th>
		<th>Date</th>
		<th>Bill No</th>
		<th>Registered On</th>
		<th>Registered To</th>
		<th>PIN Number</th>
		<th>Usagetype</th>
		<th>Purchase Type</th>
		<th>Attached By</th>
		<th>Scheme</th>
		<th>Purchase Remarks</th>

	</tr>
	</thead>
	<tbody>
	<?php
    $slno = 1;
		while($fetch = mysql_fetch_array($result)) {
			if ($fetch['registereddate'] == '')
				$registereddate = '';
			else
				$registereddate = changedateformat($fetch['registereddate']);

			?>

			<tr>
				<td><?php echo $slno++; ?></td>
				<td><?php echo $fetch['dealername']; ?></td>
				<td><?php echo $fetch['cardid']; ?></td>
				<td><?php echo $fetch['productname']; ?></td>
				<td><?php echo changedateformat(substr($fetch['attcheddate'], 0, 10)); ?></td>
				<td><?php echo $fetch['billnumber']; ?> </td>
				<td><?php echo $registereddate; ?></td>
				<td><?php echo $fetch['cusname']; ?></td>
				<td><?php echo $fetch['scratchnumber']; ?></td>
				<td><?php echo $fetch['usagetype']; ?> </td>
				<td><?php echo $fetch['purchasetype']; ?> </td>
				<td><?php echo $fetch['fullname']; ?></td>
				<td><?php echo $fetch['scheme']; ?></td>
				<td><?php echo $fetch['billremarks']; ?></td>

			</tr>
		<?php  } ?>
	</tbody>
</table>
	<?php

	$query = 'select slno,dealerusername from inv_mas_dealer where slno = '.$userid.'';
	$fetchres = runmysqlqueryfetch($query);
	$dealername = $fetchres['dealerusername'];
	$localdate = datetimelocal('Ymd');
	$localtime = datetimelocal('His');
	$filebasename = "DealerDetails".$localdate."-".$localtime."-".strtolower($dealername).".xls";

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
					messageTop: 'Dealer Inventory Details.',
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

    $query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','view_dealerdetails_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
    $result = runmysqlquery($query1);

    $eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','138','".date('Y-m-d').' '.date('H:i:s')."','view_dealerdetails_report')";
    $eventresult = runmysqlquery($eventquery);



}
?>
	<script>
		function insertdata()
		{
			<?php
            $eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','139','".date('Y-m-d').' '.date('H:i:s')."','excel_dealerdetails_report".'-'.strtolower($dealername)."')";
            $eventresult = runmysqlquery($eventquery);
			?>
		}
	</script>
	</body>
	</html>