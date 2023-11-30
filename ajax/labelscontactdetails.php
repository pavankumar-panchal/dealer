<?php
include('../functions/phpfunctions.php');
require_once('../pdfbillgeneration/tcpdf.php');
ini_set('memory_limit', -1);
if(imaxgetcookie('dealeruserid')<> '') 
$userid = imaxgetcookie('dealeruserid');
else
{ 
	header('Location:../index.php');
}

$contactperson1 = $_POST['contactperson'];
$phone1 = $_POST['phone'];
$fax1 = $_POST['fax'];
$cell1 = $_POST['cell'];
$email1 = $_POST['emailid'];
$contact_result = $_POST['databasefield_contact'];
$phone_result = $_POST['databasefield_phone'];
$fax_result = $_POST['databasefield_fax'];
$cell_result = $_POST['databasefield_cell'];
$email_result = $_POST['databasefield_emailid'];
$customerid_result = $_POST['customerid'];

$color_result = $_POST['databasefield_color'];
$borderline = $_POST['border'];
$todate = $_POST['todate'];
$fromdate = $_POST['fromdate'];
$productcode = $_POST['productcode'];
$chks = $_POST['productname'];
for ($i = 0;$i < count($chks);$i++)
{
	$c_value .= "'" . $chks[$i] . "'" ."," ;
}
$productslist = rtrim($c_value , ',');
$value = str_replace('\\','',$productslist);

$reportdate = datetimelocal('d-m-Y');
$reregenable = $_POST['reregenable'];
$type = $_POST['type'];
$category = $_POST['category'];
$usagetype = $_POST['usagetype'];
$purchasetype = $_POST['purchasetype'];
$scheme = $_POST['scheme'];
$reregistration  = $_POST['rereg'];
$card  = $_POST['card'];

$usagetypepiece = ($usagetype == "")?(""):(" AND inv_dealercard.usagetype = '".$usagetype."' ");
$schemepiece = ($scheme == "")?(""):(" AND inv_mas_scheme.slno = '".$scheme."' ");
$purchasetypepiece = ($purchasetype == "")?(""):(" AND inv_dealercard.purchasetype = '".$purchasetype."' ");
$typepiece = ($type == "")?(""):(" AND inv_mas_customer.type = '".$type."' ");
$categorypiece = ($category == "")?(""):(" AND inv_mas_customer.category = '".$category."' ");

if($card == "") {$cardpiece = ""; }
elseif($card == 'withcard') {$cardpiece = " AND inv_customerproduct.cardid <> '' ";}
elseif($card == 'withoutcard') {$cardpiece = " AND inv_customerproduct.cardid = '' ";}

if($reregistration == "") {$reregistrationpiece = ""; }
elseif($reregistration == 'yes') {$reregistrationpiece =  " AND inv_customerproduct.reregistration = 'yes' ";}
elseif($reregistration == 'no') {$reregistrationpiece = " AND inv_customerproduct.reregistration = 'no' ";}

$datepiece = (($todate == "") && ($fromdate == ""))?(""):("  (inv_customerproduct.date BETWEEN '".changedateformat($fromdate)."' AND '".changedateformat($todate)."')   ");
	
if($reregenable == '')
{
		$productcodepiece = ($chks == "")?(""):(" inv_mas_product.productcode IN (".$value.") ");

		$query11 = "SELECT distinct inv_mas_customer.slno,inv_mas_customer.customerid,inv_mas_customer.businessname,inv_mas_customer.address,inv_mas_customer.place,inv_mas_district.districtname,inv_mas_state.statename,inv_mas_customer.pincode,inv_mas_customer.fax,inv_mas_customer.stdcode FROM inv_mas_dealer LEFT JOIN inv_customerproduct on inv_customerproduct.dealerid = inv_mas_dealer.slno LEFT JOIN inv_mas_customer on inv_mas_customer.slno = inv_customerproduct.customerreference LEFT JOIN inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3) LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region LEFT JOIN inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district LEFT JOIN inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category WHERE  ".$productcodepiece.$typepiece.$categorypiece.$pintypepiece." and inv_mas_customer.currentdealer = '".$userid."' ";
		$result = runmysqlquery($query11);
	//$query = "SELECT distinct inv_mas_customer.slno,inv_mas_customer.customerid,inv_mas_customer.businessname,inv_mas_customer.address,inv_mas_customer.place,inv_mas_district.districtname,inv_mas_state.statename,inv_mas_customer.pincode,inv_mas_customer.fax,inv_mas_customer.stdcode FROM inv_mas_dealer LEFT JOIN inv_customerproduct on inv_customerproduct.dealerid = inv_mas_dealer.slno LEFT JOIN inv_mas_customer on inv_mas_customer.slno = inv_customerproduct.customerreference LEFT JOIN inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3) LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region LEFT JOIN inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district LEFT JOIN inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category WHERE  ".$productcodepiece.$typepiece.$categorypiece.$pintypepiece.";";

}
else
{
		$productcodepiece = ($chks == "")?(""):(" AND inv_mas_product.productcode IN (".$value.") ");

		$query11 = "SELECT  distinct inv_mas_customer.slno, inv_mas_customer.customerid, inv_mas_customer.businessname, inv_mas_customer.address,inv_mas_customer.place,inv_mas_district.districtname,inv_mas_state.statename,inv_mas_customer.pincode,inv_mas_customer.fax,inv_mas_customer.stdcode  from inv_customerproduct LEFT JOIN inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno  LEFT JOIN inv_mas_dealer ON inv_mas_dealer.slno = inv_customerproduct.dealerid  LEFT JOIN inv_dealercard ON inv_dealercard.cardid = inv_customerproduct.cardid  LEFT JOIN inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3) LEFT JOIN inv_mas_scheme on inv_mas_scheme.slno =inv_dealercard.scheme LEFT JOIN inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district LEFT JOIN inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region WHERE  ".$datepiece.$reregistrationpiece.$productcodepiece.$typepiece.$categorypiece.$usagetypepiece.$purchasetypepiece.$schemepiece.$pintypepiece.$cardpiece." and inv_mas_customer.currentdealer = '".$userid."'";
		$result = runmysqlquery($query11);
		
	//$query = "SELECT  distinct inv_mas_customer.slno,inv_mas_customer.customerid,inv_mas_customer.businessname,inv_mas_customer.address,inv_mas_customer.place,inv_mas_district.districtname,inv_mas_state.statename,inv_mas_customer.pincode,inv_mas_customer.fax,inv_mas_customer.stdcode  from inv_customerproduct LEFT JOIN inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno  LEFT JOIN inv_mas_dealer ON inv_mas_dealer.slno = inv_customerproduct.dealerid  LEFT JOIN inv_dealercard ON inv_dealercard.cardid = inv_customerproduct.cardid  LEFT JOIN inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3) LEFT JOIN inv_mas_scheme on inv_mas_scheme.slno =inv_dealercard.scheme LEFT JOIN inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district LEFT JOIN inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region WHERE  ".$datepiece.$reregistrationpiece.$productcodepiece.$typepiece.$categorypiece.$usagetypepiece.$purchasetypepiece.$schemepiece.$pintypepiece.$cardpiece.";";

}
	//echo($query11);exit;
	$arrayreplace = array(',,,',',,');
	$numrows = (mysqli_num_rows ($result));
	// loop to create rows
	if($numrows >0)
	{
		if($borderline == 'on')
			$grid = '<table border="3" CELLSPACING="0" CELLPADDING="7" WIDTH ="100%">';
		else
			$grid = '<table border="0" CELLSPACING="0" CELLPADDING="7" WIDTH ="100%" >';
		$position = 1;
		while ($myrow = mysqli_fetch_array($result))
		{
			$customerid = $myrow['customerid'];
			$businessname= $myrow['businessname'];
			$address= $myrow['address'];
			$place= $myrow['place'];
			$districtname= $myrow['districtname'];
			$statename= $myrow['statename'];
			$pincode= $myrow['pincode'];
			$fax= $myrow['fax'];
			$stdcode= $myrow['stdcode'];
			
			// contact Details
			$querycontactdetails = "select customerid, GROUP_CONCAT(contactperson) as contactperson,  
GROUP_CONCAT(phone) as phone, GROUP_CONCAT(cell) as cell, GROUP_CONCAT(emailid) as emailid from inv_contactdetails where customerid = '".$myrow['slno']."'  group by customerid ";
			$resultcontactdetails = runmysqlqueryfetch($querycontactdetails);
			
			$contactvalues = removedoublecomma($resultcontactdetails['contactperson']);
			$phoneres = removedoublecomma($resultcontactdetails['phone']);
			$cellres = removedoublecomma($resultcontactdetails['cell']);
			$emailidres = removedoublecomma($resultcontactdetails['emailid']);
			
			if($contactperson1 == 'on')
			{
				if($contact_result == 'contactall')
					$result_contact = trim($contactvalues,',');
				elseif($contact_result == 'contactone')
					$result_contact = finalsplit(trim($contactvalues,','));
			}
			if($phone1 == 'on')
			{
				if($phone_result == 'phoneall')
					$result_phone = trim($phoneres,',');
				elseif($phone_result == 'phoneone')
					$result_phone = finalsplit(trim($phoneres,','));
			}
			if($fax1 == 'on')
			{
				if($fax_result == 'faxall')
					$result_fax = $fax;
				elseif($fax_result == 'faxone')
					$result_fax = finalsplit($fax);
			}
			
			if($cell1 == 'on')
			{
				if($cell_result == 'cellall')
					$result_cell = trim($cellres,',');
				elseif($cell_result == 'cellone')
					$result_cell = finalsplit(trim($cellres,','));
			}
			if($email1 == 'on')
			{	
				if($email_result == 'emailall')
					$result_emailid = trim($emailidres,',');
				elseif($email_result == 'emailone')
					$result_emailid = finalsplit(trim($emailidres,','));
			}
			if($position == 1)
			{
				$grid .= "<tr>";
				$count++;
			}	
			if($customerid_result == 'on')
			{
				$grid .= "<td height='153'><table width='100%' border='0' cellspacing='0' cellpadding='0'>
	<tr><td><strong>Cus ID:</strong> ".cusidcombine($customerid)."</td></tr><tr><td>".$result_contact."</td></tr><tr><td><strong>".$businessname."</strong></td></tr>" ; if($address <> ''){ $grid .= "<tr><td> ".$address."</td></tr>";} if($place <> ''){ $grid .= "<tr><td> ".$place."</td> </tr>" ; }	if($districtname <> ''){$grid .= "<tr> <td>".$districtname."</td> </tr>";}if($statename <> ''){
$grid .="<tr> <td><table width='100%' border='0' cellspacing='0' cellpadding='0'><tr> <td  width='50%'>".$statename."</td>";if($pincode <> '' ) $grid .= "<td  width='50%'>Pincode: ".$pincode."</td>"; $grid .= "</tr></table></td> </tr>";}if($result_phone <> '' &&  $stdcode <> '')$grid .= "<tr> <td> ".$stdcode.'-'.$result_phone."</td></tr>"; elseif($result_phone <> '')$grid .= "<tr> <td> ".$result_phone."</td></tr>";if($result_cell <> '')$grid .= "<tr> <td> ".$result_cell."</td></tr>";if($result_fax <> '' &&  $stdcode <> '')$grid .= "<tr> <td> ".$stdcode.'-'.$result_fax."</td></tr>";elseif($result_fax <> '')$grid .= "<tr> <td> ".$result_fax."</td></tr>";if($result_emailid <> '') { $grid .="<tr> <td>".strtolower($result_emailid)."</td> </tr>";}$grid .= "</table></td>\n";
			}
			else 
			{
				$grid .= " <td height='153'><table width='100%' border='0' cellspacing='0' cellpadding='0'>
<tr><td>".$result_contact."</td></tr><tr><td><strong>".$businessname."</strong></td></tr>" ; if($address <> ''){ $grid .= "<tr><td> ".$address."</td></tr>";} if($place <> ''){ $grid .= "<tr><td> ".$place."</td> </tr>" ; }if($districtname <> ''){$grid .= "<tr> <td>".$districtname."</td> </tr>";}if($statename <> ''){$grid .="<tr> <td><table width='100%' border='0' cellspacing='0' cellpadding='0'><tr> <td  width='50%'>".$statename."</td>";if($pincode <> '' ) $grid .= "<td  width='50%'>Pincode: ".$pincode."</td>"; $grid .= "</tr></table></td> </tr>";}if($result_phone <> '' &&  $stdcode <> '')$grid .= "<tr> <td> ".$stdcode.'-'.$result_phone."</td></tr>"; elseif($result_phone <> '')$grid .= "<tr> <td> ".$result_phone."</td></tr>";if($result_cell <> '')$grid .= "<tr> <td> ".$result_cell."</td></tr>";if($result_fax <> '' &&  $stdcode <> '')$grid .= "<tr> <td> ".$stdcode.'-'.$result_fax."</td></tr>";elseif($result_fax <> '')$grid .= "<tr> <td> ".$result_fax."</td></tr>";if($result_emailid <> '') { $grid .="<tr> <td>".strtolower($result_emailid)."</td> </tr>";}$grid .= "</table></td>\n";
			}
			if($position == 3)
			{
				$grid .= "</tr> "; 
				$position = 1;
			}
			else
			{ 
				$position++;
			}

		}
		$end = "";

		if($position != 1)
		{
			$end .= "</tr>";

		}
		$grid .= $end."</table> ";
				
	}
	$pdf = new  TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


	// remove default header/footer
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	
	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	
	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	
	
	//set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	
	//set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
	
	// set font
	$pdf->SetFont('Helvetica', '',7);
	
	if($color_result == 'grey' && $borderline == 'on')
		$pdf->SetDrawColor(200);
	elseif($color_result == 'black' && $borderline == 'on')
		$pdf->SetDrawColor(0);
		
	$pdf->AddPage();
		
	$pdf->WriteHTML($grid,true,0,true);
		
	$localdate = datetimelocal('Ymd');
	$localtime = datetimelocal('His');
	$filename = 'CustomerContactDetails(LabelFormat)'."-".$localdate."-".$localtime;
	$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','144','".date('Y-m-d').' '.date('H:i:s')."')";
	$eventresult = runmysqlquery($eventquery);
	$filebasename = $filename.".pdf";
	$addstring ="/imax/dealer/";
	if($_SERVER['HTTP_HOST'] == "meghanab" || $_SERVER['HTTP_HOST'] == "archanaab")
		$addstring = "/saralimax-user";
		
	//$filepath = $_SERVER['DOCUMENT_ROOT'].$addstring.'/labelprint/'.$filebasename;
	//$fp = fopen($filepath,"wa+");
	
$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','91','".date('Y-m-d').' '.date('H:i:s')."','Label Print(Customer Contact Details)')";

$eventresult = runmysqlquery($eventquery);	

$pdf->Output($filebasename ,'D');	

?>
