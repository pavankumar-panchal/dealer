<?php 

set_include_path('../lib'.PATH_SEPARATOR.get_include_path());
require_once('../lib/CitrusPay.php');
require_once 'Zend/Crypt/Hmac.php';

function generateHmacKey($data, $apiKey=null){
	$hmackey = Zend_Crypt_Hmac::compute($apiKey, "sha1", $data);
	echo $hmackey;
	return $hmackey;
}

$action = "testSSL.php";
$flag = "";

CitrusPay::setApiKey("3603d4818b687632c87ffef4e47c3cbea55986f3",'sandbox');

if(isset($_POST['submit']))
{
	$vanityUrl = "cleartrip";
	$currency = "INR";
	$merchantAccessKey = $_POST['merchantAccessKey'];
	$merchantTxnId = $_POST['merchantTxnId'];
	$addressState = $_POST['addressState'];
	$addressCity = $_POST['addressCity'];
	$addressStreet1 = $_POST['addressStreet1'];
	$addressCountry = $_POST['addressCountry'];
	$addressZip = $_POST['addressZip'];
	$firstName = $_POST['firstName'];
	$lastName = $_POST['lastName'];
	$phoneNumber = $_POST['phoneNumber'];
	$email = $_POST['email'];
	$paymentMode = $_POST['paymentMode'];
	$issuerCode = $_POST['issuerCode'];
	$cardHolderName = $_POST['cardHolderName'];
	$cardNumber = $_POST['cardNumber'];
	$expiryMonth = $_POST['expiryMonth'];
	$cardType = $_POST['cardType'];
	$cvvNumber = $_POST['cvvNumber'];
	$expiryYear = $_POST['expiryYear'];
	$returnUrl = $_POST['returnUrl'];
	$orderAmount = $_POST['orderAmount'];
	$flag = "post";
	$data = "$vanityUrl$orderAmount$merchantTxnId$currency";
	$secSignature = generateHmacKey($data,CitrusPay::getApiKey());
	$action = CitrusPay::getCPBase()."$vanityUrl";  
	//$action = "http://localhost/netbank/test/testSSL.php";  
	$time = time()*1000;
	$time = number_format($time,0,'.','');
	/*$customParamsName = $_POST['customParamsName'];*/
	/*$customParamsValue = $_POST['customParamsValue'];*/
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Test SSL Integration</title>
<link href="css/default.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="page-header">
		<div class="page-wrap">
			<div class="logo-wrapper">
				<a href="http://www.citruspay.com/"> <img height="32" width="81"
					src="images/logo_citrus.png" alt="Citrus" />
				</a>
			</div>
		</div>
	</div>

	<div id="page-client-logo">&#160;</div>
	<div id="page-wrapper">
		<div class="box-white">
			<div class="page-content">
	<form action="<?php echo $action;?>" method="POST"
		name="TransactionForm" id="transactionForm">

		<?php 
		if($flag == "post")
		{
			?>
		<p>
			<label> Merchant Access Key:</label><input name="merchantAccessKey"
				type="text" value="<?php echo $merchantAccessKey;?>" />
		</p>
		<p>
			<label> Transaction ID:</label><input name="merchantTxnId"
				type="text" value="<?php echo $merchantTxnId;?>" />
		</p>
		<p>
			<label> addressState:</label><input name="addressState" type="text"
				value="<?php echo $addressState;?>" />
		</p>
		<p>
			<label> addressCity:</label><input name="addressCity" type="text"
				value="<?php echo $addressCity;?>" />
		</p>
		<p>
			<label> addressStreet1:</label><input name="addressStreet1"
				type="text" value="<?php echo $addressStreet1;?>" />
		</p>
		<p>
			<label> addressCountry:</label><input name="addressCountry"
				type="text" value="<?php echo $addressCountry;?>" />
		</p>
		<p>
			<label> addressZip:</label><input name="addressZip" type="text"
				value="<?php echo $addressZip;?>" />
		</p>
		<p>
			<label> firstName:</label><input name="firstName" type="text"
				value="<?php echo $firstName;?>" />
		</p>
		<p>
			<label> lastName:</label><input name="lastName" type="text"
				value="<?php echo $lastName;?>" />
		</p>
		<p>
			<label> Mobile Number:</label><input name="phoneNumber" type="text"
				value="<?php echo $phoneNumber;?>" />
		</p>
		<p>
			<label> email:</label><input name="email" type="text"
				value="<?php echo $email;?>" />
		</p>
		<p>
			<label> paymentMode:</label><input name="paymentMode" type="text"
				value="<?php echo $paymentMode;?>" />
		</p>
		<p>
			<label> issuerCode:</label><input name="issuerCode" type="text"
				value="<?php echo $issuerCode;?>" />
		</p>
		<p>
			<label> cardHolderName:</label><input name="cardHolderName"
				type="text" value="<?php echo $cardHolderName;?>" />
		</p>
		<p>
			<label> cardNumber:</label><input name="cardNumber" type="text"
				value="<?php echo $cardNumber;?>" />
		</p>
		<p>
			<label> expiryMonth:</label><input name="expiryMonth" type="text"
				value="<?php echo $expiryMonth;?>" />
		</p>
		<p>
			<label> cardType:</label><input name="cardType" type="text"
				value="<?php echo $cardType;?>" />
		</p>
		<p>
			<label> cvvNumber:</label><input name="cvvNumber" type="text"
				value="<?php echo $cvvNumber;?>" />
		</p>
		<p>
			<label> expiryYear:</label><input name="expiryYear" type="text"
				value="<?php echo $expiryYear;?>" />
		</p>
		<p>
			<label> returnUrl:</label><input name="returnUrl" type="text"
				value="<?php echo $returnUrl;?>" />
		</p>
		<p>
			<label> amount:</label><input name="orderAmount" type="text"
				value="<?php echo $orderAmount;?>" />
		</p>
		<p>
			Time: <input type="text" name="reqtime" value="<?php echo $time;?>" /> <input
				type="hidden" name="secSignature"
				value="<?php echo $secSignature;?>" /> <input type="hidden"
				name="currency" value="<?php echo $currency;?>" />
		</p>
		<!-- Custom parameter section starts here. 
		You can omit this section if no custom parameters have been defined.
		Hidden field value should be the name of the parameter created in Checkout settings page.
		It should follow customParams[0].name, customParams[1].name .. naming convention.
		For each custom parameter created, a text field with the naming convention  
		customParams[0].value,customParams[1].value .. should be captured.
		Please refer below code snippet for passing parameters to SSL Page.
		Uncomment the for loop after the PHP tag to pass parameters to SSL Page
		
		Also refer the else part of this loop to see how to capture Custom Params on your website
		
		
		 -->
		<?php 
			/* for($i=0;$i<count($customParamsName);++$i)
			{
			
			echo "<p><input type=\"hidden\" name=\"customParams[$i].name\" value=\"$customParamsName[$i]\" /></p>";
			echo "<p>$customParamsName[$i]: <input type=\"text\" name=\"customParams[$i].value\" value=\"$customParamsValue[$i]\" /></p>";
			} */
		}
		else
		{
			?>
		<div>	
		<ul class="form-wrapper add-merchant clearfix">
			<li class="clearfix"> <label width="125px;">Merchant Access Key:</label>
			<input class="text" name="merchantAccessKey"
				type="text" value="UWYSGWKA6M9302CALTCZ" /></li>
		
			<li class="clearfix"> <label width="125px;">Transaction Number:</label><input class="text" name="merchantTxnId"
				type="text" value="" /></li>
		
			<li class="clearfix"> <label width="125px;">State:</label><input class="text" name="addressState" type="text"
				value="" /></li>
		
			<li class="clearfix"> <label width="125px;">City:</label><input class="text" name="addressCity" type="text"
				value="" /></li>
		
			<li class="clearfix"> <label width="125px;">Address:</label><input class="text" name="addressStreet1"
				type="text" value="" /></li>
		
			<li class="clearfix"> <label width="125px;">Country:</label><input class="text" name="addressCountry"
				type="text" value="" /></li>
		
			<li class="clearfix"> <label width="125px;">Pin Code:</label><input class="text" name="addressZip" type="text"
				value="" /></li>
		
			<li class="clearfix"> <label width="125px;">First Name:</label><input class="text" name="firstName" type="text"
				value="" /></li>
		
			<li class="clearfix"> <label width="125px;">Last Name:</label><input class="text" name="lastName" type="text" value="" /></li>
		
			<li class="clearfix"> <label width="125px;">Mobile Number:</label><input class="text" name="phoneNumber" type="text"
				value="" /></li>
		
			<li class="clearfix"> <label width="125px;">Email:</label><input class="text" name="email" type="text" value="" />
			</li>
		
		<!-- Custom parameter section starts here. 
		You can omit this section if no custom parameters have been defined.
		Hidden field value should be the name of the parameter created in Checkout settings page.
		An array of Custom Parameter's Name and Custom Parameters Value should be passed to the POST script.
		Please refer below code snippet for passing Custom parameters to the POST script Page.
		
		Once the parameters are passed through a text input field they are captured in the script mentioned 
		in the Action attribute of the Form
		-->
		<!-- <input type="hidden" name="customParamsName[]" value="Roll Number" />
		<p>
			Roll Number <input type="text" class="text" name="customParamsValue[]" value="" />
		</p>
		<input type="hidden" name="customParamsName[]" value="age" />
		<p>
			age <input type="text" class="text" name="customParamsValue[]" value="" />
		</p> -->

		
			<li class="clearfix"> <label width="125px;">Payment Mode:</label><select class="text" name="paymentMode">
				<option value="">Select Payment Mode</option>
				<option value="NET_BANKING">NetBanking</option>
				<option value="CREDIT_CARD">Credit Card</option>
				<option value="DEBIT_CARD">Debit Card</option>
			</select>
			</li>
		
			<li class="clearfix"> <label width="125px;">Issuer Code:</label><input class="text" name="issuerCode" type="text"
				value="" />
				</li>
		
			<li class="clearfix"> <label width="125px;">Card Holder Name:</label><input class="text" name="cardHolderName"
				type="text" value="" />
				</li>
		
			<li class="clearfix"> <label width="125px;">Card Number:</label><input class="text" name="cardNumber" type="text"
				value="" />
				</li>
		
			<li class="clearfix"> <label width="125px;">Expiry Month:</label><input class="text" name="expiryMonth" type="text"
				value="" />
				</li>
		
			<li class="clearfix"> <label width="125px;">Card Type:</label><input class="text" name="cardType" type="text" value="" />
			</li>
		
			<li class="clearfix"> <label width="125px;">CVV Number:</label><input class="text" name="cvvNumber" type="text"
				value="" /></li>
		
			<li class="clearfix"> <label width="125px;">Expiry Year:</label><input class="text" name="expiryYear" type="text"
				value="" /></li>
		
			<li class="clearfix"> <label width="125px;">Return Url:</label><input class="text" name="returnUrl" type="text"
				value="" /></li>
		
			<li class="clearfix"> <label width="125px;">Amount:</label><input class="text" name="orderAmount" type="text" value="" />
			</li>
			</ul>
					<input type="submit" name="submit" class="btn-orange" value="Test Transaction" /> <input
				type="reset" class="btn" name="reset" value="Cancel" />
		</div>	
		<?php
		}
		?>
	</form>
	</div>
	<div
		style="padding-left: 700px; padding-bottom: 20px; padding-top: 20px;">
		<div>Copyrights © 2012 Citrus.</div>
	</div>
	</div>
	</div>
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
</body>
</html>
