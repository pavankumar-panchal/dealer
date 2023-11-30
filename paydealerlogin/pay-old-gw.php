<?php


$referurl = parse_url($_SERVER['HTTP_REFERER']);
$referhost = $referurl['host'];
include("../functions/phpfunctions.php"); 

if(isset($_POST["businessname"],$_POST["contactperson"],$_POST["address"],$_POST["district"],$_POST["state"],$_POST["pincode"],$_POST["phone"],$_POST["emailid"],$_POST["dealerid"],$_POST["amount"]) == false || ($referhost <> 'meghanab' && $referhost <> 'imax.relyonsoft.net' && $referhost <> 'www.imax.relyonsoft.net') || checkemailaddress($_POST["emailid"]) == false)
{
	echo("Invalid Information.");
	exit;
}
	
	$query = "SHOW TABLE STATUS like 'transactions'";
	$result = runicicidbquery($query);
	$row = mysqli_fetch_array($result);
	$nextautoincrementid = $row['Auto_increment'];
	
	//Main Details
	$merchatid = "00004074";
	$date = datetimelocal('Y-m-d');
	$time = datetimelocal('H:i:s');
	$userip = $_SERVER["REMOTE_ADDR"];
	$userbrowserlanguage = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
	$userbrowseragent = $_SERVER["HTTP_USER_AGENT"];
	$relyontransactionid = $nextautoincrementid;
	$orderid = ""; //Optional
	$responseurl = "http://imax.relyonsoft.com/dealer/paydealerlogin/complete.php"; //Should not exceed 80 Chars
	$invoicenumber = ""; //Optional
	
	$amount = $_POST["amount"];
	
	
	//User Details
	$company = substr($_POST["businessname"],0,80); //Optional
	$contactperson = substr($_POST["contactperson"],0,50);
	$address1 = substr($_POST["address"],0,50);
	$address2 = ""; //Optional
	$address3 = ""; //Optional
	$city = substr($_POST["district"],0,30);
	$state = substr($_POST["state"],0,30);;
	$country = "IND";
	$pincode = ($_POST["pincode"] == '')?'111111':$_POST["pincode"];
	$phone = substr($_POST["phone"],0,15); //Optional
	$emailid = substr($_POST["emailid"],0,80); //Optional
	$customerid = ""; //Optional
	$recordreference = $_POST["dealerid"];
	
	
	$productname = "Saral iMax Credit";
	$quantity = "1";
	
	$query = "insert into `transactions` (date, time, userip, orderid, responseurl, invoicenumber, amount, company, contactperson, address1, address2, address3, city, state, pincode, phone, emailid, customerid, productname, quantity, userbrowserlanguage, userbrowseragent,recordreference)	values('".$date."', '".$time."', '".$userip."', '".$orderid."', '".$responseurl."', '".$invoicenumber."', '".$amount."', '".$company."', '".$contactperson."', '".$address1."', '".$address2."', '".$address3."', '".$city."', '".$state."', '".$pincode."', '".$phone."', '".$emailid."', '".$customerid."', '".$productname."', '".$quantity."', '".$userbrowserlanguage."', '".$userbrowseragent."', '".$recordreference."')";
	$result = runicicidbquery($query);
	
	
	
	// ICICI code begins. Do not alter anything Further - Vijay .................................................
	
include("Sfa/BillToAddress.php");
include("Sfa/CardInfo.php");
include("Sfa/Merchant.php");
include("Sfa/MPIData.php");
include("Sfa/ShipToAddress.php");
include("Sfa/PGResponse.php");
include("Sfa/PostLibPHP.php");
include("Sfa/PGReserveData.php");

$oMPI 			= 	new MPIData();
$oCI			=	new	CardInfo();
$oPostLibphp	=	new	PostLibPHP();
$oMerchant		=	new	Merchant();
$oBTA			=	new	BillToAddress();
$oSTA			=	new	ShipToAddress();
$oPGResp		=	new	PGResponse();
$oPGReserveData =	new PGReserveData();


$oMerchant->setMerchantDetails($merchatid, $merchatid, $merchatid,$userip,$relyontransactionid,$orderid,$responseurl,"POST","INR",$invoicenumber,"req.Sale",$amount,"","Ext1","true","Ext3","Ext4","New PHP");

$oBTA->setAddressDetails ($customerid, $company, $address1, $address2, $address3, $city, $state, $pincode, $country, $emailid);

$oSTA->setAddressDetails ($address1, $address2, $address3, $city, $state, $pincode, $country, $emailid);

#$oMPI->setMPIRequestDetails("1245","12.45","356","2","2 shirts","12","20011212","12","0","","image/gif, image/x-xbitmap, image/jpeg, image/pjpeg, application/vnd.ms-powerpoint, application/vnd.ms-excel, application/msword, application/x-shockwave-flash, */*","Mozilla/4.0 (compatible; MSIE 5.5; Windows NT 5.0)");

$oPGResp=$oPostLibphp->postSSL($oBTA,$oSTA,$oMerchant,$oMPI,$oPGReserveData);


if($oPGResp->getRespCode() == '000'){
	$url	=$oPGResp->getRedirectionUrl();
	#$url =~ s/http/https/;
	#print "Location: ".$url."\n\n";
	#header("Location: ".$url);
	redirect($url);
}else{
	print "Error Occured.<br>";
	print "Error Code:".$oPGResp->getRespCode()."<br>";
	print "Error Message:".$oPGResp->getRespMessage()."<br>";
}

# This will remove all white space
#$oResp =~ s/\s*//g;

# $oPGResp->getResponse($oResp);

#print $oPGResp->getRespCode()."<br>";

#print $oPGResp->getRespMessage()."<br>";

#print $oPGResp->getTxnId()."<br>";

#print $oPGResp->getEpgTxnId()."<br>";

function redirect($url) {
	if(headers_sent()){
	?>
		<html><head>
			<script language="javascript" type="text/javascript">
				window.self.location='<?php print($url);?>';
			</script>
		</head></html>
	<?php
		exit;
	} else {
		header("Location: ".$url);
		exit;
	}
}

?>