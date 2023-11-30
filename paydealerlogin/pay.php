<?php 
include("ipg-util.php"); 
include("../functions/phpfunctions.php"); 

$referurl = parse_url($_SERVER['HTTP_REFERER']);
$referhost = $referurl['host'];

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


?>
<html>
	<head>
		<title>Relyonsoftech Payment gateway</title>
	</head>
	<body onLoad="document.frm1.submit()">

		<?php

        $responseSuccessURL = "http://imax.relyonsoft.com/dealer/paydealerlogin/complete.php"; //Need to change as per location of response page
        $responseFailURL = "http://imax.relyonsoft.com/dealer/paydealerlogin/complete.php";       //Need to change as per location of response page

		$CT = $amount;
		$txntype = 'sale';
		$currency = '356';
		$mode = 'payonly';
		$storename = '3300004074';
		$sharedsecret = 'fjC6?e\rb5S^`';
		$oid = "pg".time();
		
		//Do not touch this. Inserting the record to Relyon main Credit Card transaction table.

$query = "insert into `transactions` (date, time, userip, orderid, responseurl, invoicenumber, amount, company, contactperson, address1, address2, address3, city, state, pincode, phone, emailid, customerid, productname, quantity, userbrowserlanguage, userbrowseragent,recordreference) values('".$date."', '".$time."', '".$userip."', '".$oid."', '".$responseSuccessURL."', '".$invoicenumber."', '".$amount."', '".$company."', '".$contactperson."', '".addslashes($address1)."', '".addslashes($address2)."', '".addslashes($address3)."', '".$city."', '".$state."', '".$pincode."', '".$phone."', '".$emailid."', '".$customerid."', '".$productname."', '".$quantity."', '".$userbrowserlanguage."', '".$userbrowseragent."', '".$recordreference."')";
$result = runicicidbquery($query);

		?>

			<form method="post" name="frm1" action="https://www4.ipg-online.com/connect/gateway/processing">
			<input type="hidden" name="timezone" value="IST" />
			<input type="hidden" name="authenticateTransaction" value="true" />
			<input size="50" type="hidden" name="txntype" value="<?php echo $txntype ?>"  />
			<input size="50" type="hidden" name="txndatetime" value="<?php echo getDateTime(); ?>"  />
			<input size="50" type="hidden" name="hash" value="<?php echo createHash($CT,"356",$storename,$sharedsecret); ?>"  />
			<input size="50" type="hidden" name="currency" value="<?php echo $currency ?>"  />
			<input size="50" type="hidden" name="mode" value="<?php echo $mode ?>"  />
			<input size="50" type="hidden" name="storename" value="<?php echo $storename ?>"  />
			<input size="50" type="hidden" name="chargetotal" value="<?php echo $CT ?>"  />
			<input size="50" type="hidden" name="sharedsecret" value="<?php echo $sharedsecret ?>"  />
			<input size="50" type="hidden" name="oid" value="<?php echo $oid; ?>"  />
			<input type="hidden" name="responseSuccessURL" value="<?php echo $responseSuccessURL ?>"  />
			<input type="hidden" name="responseFailURL" value="<?php echo $responseFailURL ?>"  />
			<input type="hidden" name="hash_algorithm" value="SHA1"/>
			<input type="hidden" name="SubmitButton" value="Submit"/>

</form>
</body>
</html>