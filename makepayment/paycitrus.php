<?php

include("../functions/phpfunctions.php"); 
set_include_path('./lib'.PATH_SEPARATOR.get_include_path());
require_once('./lib/CitrusPay.php');
require_once 'Zend/Crypt/Hmac.php';


function generateHmacKey($data, $apiKey=null){
	$hmackey = Zend_Crypt_Hmac::compute($apiKey, "sha1", $data);
	//echo $hmackey;
	return $hmackey;
}

$flag = "";
//CitrusPay::setApiKey("3603d4818b687632c87ffef4e47c3cbea55986f3",'production');
CitrusPay::setApiKey("e9303f2f561e5dc5ceef2356b1b459245a58b9ee",'production');

//Receive the serial numer of record
 $lastslno = $_POST['onlineslno'];

//Ensure record numbers are right and recalculate the total of selected records.
$query = "select * from dealer_online_purchase where onlineinvoiceno = '".$lastslno."' ";
$result = runmysqlquery($query);
if(mysqli_num_rows($result) == 0)
{
	$errormessage = "Invalid Entry.";
	header("Location:../purchase/invoicing.php?error=".$errormessage);
	exit;
}
else
{
	$userdetails = mysqli_fetch_array($result);
	$product = $userdetails['products'];
	$split = explode('*',$product);
	$quantity = count($split);
}

/*-----------------------------Do not edit this piece of code - Begin-----------------------------*/
$query = "SHOW TABLE STATUS like 'transactions'";
$result = runicicidbquery($query);
$row = mysqli_fetch_array($result);
$nextautoincrementid = $row['Auto_increment'];

//$merchatid = "00004074";
$merchatid = "13PJAST4CP35VJR0YRJP";
$date = date('Y-m-d');
$time = date('H:i:s');
$userip = $_SERVER["REMOTE_ADDR"];
$userbrowserlanguage = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
$userbrowseragent = $_SERVER["HTTP_USER_AGENT"];
$relyontransactionid = $nextautoincrementid; 
/*-----------------------------Do not edit this piece of code - End-----------------------------*/



//Main Details
$responseurl = "http://www.imax.relyonsoft.com/dealer/makepayment/complete_citrus.php"; //Should not exceed 80 Chars
$orderid = ""; //Optional
$invoicenumber = ""; //Optional

//User Details
$company = substr($userdetails['businessname'],0,80); //Optional
$contactperson = substr($userdetails['contactperson'],0,50);
$address1 = substr($userdetails['address'],0,50);
$address2 = ""; //Optional
$address3 = ""; //Optional
$city = substr($userdetails['place'],0,30);
$state = "STATE";
$country = "IND"; //No change
$pincode =  $userdetails['pincode'];
$phone = substr($userdetails['phone'],0,15); //Optional
$emailid = substr($userdetails['emailid'],0,80); //Optional
$customerid = $userdetails['custreference'];
$amount = $userdetails['netamount'];   //Optional
$productname = "Relyon Online Purchase";

// Do not edit further, till the end.
//Do not touch this. Inserting the record to Relyon main Credit Card transaction table.
$query = "insert into `transactions` (date, time, userip, orderid, responseurl, invoicenumber, amount, company, contactperson, address1, address2, address3, city, state, pincode, phone, emailid, customerid, productname, quantity, userbrowserlanguage, userbrowseragent,recordreference,citrus)	values('".$date."', '".$time."', '".$userip."', '".$orderid."', '".$responseurl."', '".$invoicenumber."', '".$amount."', '".$company."', '".$contactperson."', '".addslashes($address1)."', '".addslashes($address2)."', '".addslashes($address3)."', '".$city."', '".$state."', '".$pincode."', '".$phone."', '".$emailid."', '".$customerid."', '".$productname."', '".$quantity."', '".$userbrowserlanguage."', '".$userbrowseragent."', '".$lastslno."','Y')";
$result = runicicidbquery($query);

$querytxnid="select max(id) as txnid from transactions";
$resulttxnid = runicicidbquery($querytxnid);
$fetchtxnid=mysqli_fetch_array($resulttxnid);
$txnid=$fetchtxnid['txnid'];

// Net banking code begins. Do not alter anything Further - Nagamani .....17-09-2012..............................
	$vanityUrl = "relyon";
	$currency = "INR";
	$_POST['currency']=$currency;
	$_POST['merchantAccessKey']=$merchatid;	
	$_POST['merchantTxnId']=$lastslno;
	$_POST['addressCity']=substr($userdetails['place'],0,30);
	$_POST['addressStreet1'] = substr($userdetails['address'],0,50);	
	$_POST['addressCountry'] = "India";
	$_POST['addressZip'] =  $userdetails['pincode'];
	$_POST['phoneNumber'] = substr($userdetails['phone'],0,15);
	$_POST['email'] = substr($userdetails['emailid'],0,80);
	$_POST['paymentMode']='NET_BANKING';
	$_POST['returnUrl'] = "http://www.imax.relyonsoft.com/dealer/makepayment/complete_citrus.php";
	$_POST['orderAmount'] = $userdetails['netamount'];
	
	$_POST['addressState']='';
	$_POST['firstName'] = '';
	$_POST['lastName'] = '';	
	$_POST['issuerCode'] = '';
	$_POST['cardHolderName'] ='';
	$_POST['cardNumber'] = '';
	$_POST['expiryMonth'] = '';
	$_POST['cardType'] = '';
	$_POST['cvvNumber'] = '';
	$_POST['expiryYear'] = '';
	$merchantTxnId= $txnid;
	$orderAmount=$userdetails['netamount'];
	$flag = "post";
	$data = "$vanityUrl$orderAmount$merchantTxnId$currency";
	$secSignature = generateHmacKey($data,CitrusPay::getApiKey());
	$action = CitrusPay::getCPBase()."$vanityUrl";  
	//$action = "http://localhost/netbank/test/testSSL.php";  
	$time = time()*1000;
	$time = number_format($time,0,'.','');
	$returnUrl='http://imax.relyonsoft.com/dealer/makepayment/complete_citrus.php';
	//redirect($action);
	?>
     <form action="<?php echo $action; ?>" method="POST"
		name="TransactionForm" id="transactionForm">
		<?php 
		if($flag == "post")
		{
		?>
		<p>
			<label> Merchant Access Key:</label><input name="merchantAccessKey"
				type="text" value="" /><?php /*echo $merchatid;*/ ?>
		</p>
		<p>
			<label> Transaction ID:</label><input name="merchantTxnId"
				type="text" value="<?php echo $merchantTxnId; ?>" />
		</p>
		<p>
			<label> addressState:</label><input name="addressState" type="text"
				value="karnataka" />
		</p>
		<p>
			<label> addressCity:</label><input name="addressCity" type="text"
				value="<?php echo substr($userdetails['place'],0,30); ?>" />
		</p>
		<p>
			<label> addressStreet1:</label><input name="addressStreet1"
				type="text" value="<?php echo substr($userdetails['address'],0,50); ?>" />
		</p>
		<p>
			<label> addressCountry:</label><input name="addressCountry"
				type="text" value="India" />
		</p>
		<p>
			<label> addressZip:</label><input name="addressZip" type="text"
				value="<?php echo $userdetails['pincode']; ?>" />
		</p>
		<p>
			<label> firstName:</label><input name="firstName" type="text"
				value="" />
		</p>
		<p>
			<label> lastName:</label><input name="lastName" type="text"
				value="" />
		</p>
		<p>
			<label> Mobile Number:</label><input name="phoneNumber" type="text"
				value="<?php echo $userdetails['cell']; ?>" />
		</p>
		<p>
			<label> email:</label><input name="email" type="text"
				value="<?php echo substr($userdetails['emailid'],0,80); ?>" />
		</p>
		<p>
			<label> paymentMode:</label><input name="paymentMode" type="text"
				value="NET_BANKING" />
		</p>
		<p>
			<label> issuerCode:</label><input name="issuerCode" type="text"
				value="" />
		</p>
		<p>
			<label> cardHolderName:</label><input name="cardHolderName"
				type="text" value="" />
		</p>
		<p>
			<label> cardNumber:</label><input name="cardNumber" type="text"
				value="" />
		</p>
		<p>
			<label> expiryMonth:</label><input name="expiryMonth" type="text"
				value="" />
		</p>
		<p>
			<label> cardType:</label><input name="cardType" type="text"
				value="" />
		</p>
		<p>
			<label> cvvNumber:</label><input name="cvvNumber" type="text"
				value="" />
		</p>
		<p>
			<label> expiryYear:</label><input name="expiryYear" type="text"
				value="" />
		</p>
		<p>
			<label> returnUrl:</label><input name="returnUrl" type="text"
				value="<?php echo $returnUrl;?>" />
		</p>
		<p>
			<label> amount:</label><input name="orderAmount" type="text"
				value="<?php echo $orderAmount; ?>" />
		</p>
		<p>
			Time: <input type="text" name="reqtime" value="<?php echo $time;?>" /> <input
				type="hidden" name="secSignature"
				value="<?php echo $secSignature;?>" /> <input type="hidden"
				name="currency" value="<?php echo $currency;?>" />
		</p>
        <?php } ?>
			</form>
    <?php
		
	function redirect($url) 
	{
		if(headers_sent()) 
		{
			 ?>
			 <html><head>
			 <script language="javascript" type="text/javascript">
			  window.self.location='<?php print($url);?>';
			 </script>
			 </head></html>
			 <?php
			 exit;
		} 
		else 
		{
			 header("Location: ".$url);
			 exit;
		}
	}
 ?>
 
 <?php 
	if($flag == "post")
	{
	?>
	<script type="text/javascript">
		document.getElementById("transactionForm").submit();
	</script>
	<?php 
	}
	?>