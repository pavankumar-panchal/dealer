<?php 

if(isset($_POST['submit']))
{
	$merchantAccessKey = $_POST['merchantAccessKey'];
	$transactionID = $_POST['transactionID'];
	$bankName = $_POST['bankName'];
	$issuerCode = $_POST['issuerCode'];
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
	$cardHolderName = $_POST['cardHolderName'];
	$cardNumber = $_POST['cardNumber'];
	$expiryMonth = $_POST['expiryMonth'];
	$cardType = $_POST['cardType'];
	$cvvNumber = $_POST['cvvNumber'];
	$expiryYear = $_POST['expiryYear'];
	$returnUrl = $_POST['returnUrl'];
	$amount = $_POST['amount'];

	$tarr = array(
			"merchantAccessKey" => "$merchantAccessKey",
			"transactionId" => "$transactionID",
			"bankName" => "$bankName",
			"issuerCode" => "$issuerCode",
			"addressState" => "$addressState",
			"addressCity" => "$addressCity",
			"addressStreet1" => "$addressStreet1",
			"addressCountry" => "$addressCountry",
			"addressZip" => "$addressZip",
			"firstName" => "$firstName",
			"lastName" => "$lastName",
			"phoneNumber" => "$phoneNumber",
			"email" => "$email",
			"paymentMode" => "$paymentMode",
			"cardHolderName" => "$cardHolderName",
			"cardNumber" => "$cardNumber",
			"expiryMonth" => "$expiryMonth",
			"cardType" => "$cardType",
			"cvvNumber" => "$cvvNumber",
			"expiryYear" => "$expiryYear",
			"returnUrl" => "$returnUrl",
			"amount" => "$amount"
	);
	$redirectUrl = "";
	set_include_path('../lib'.PATH_SEPARATOR.get_include_path());
	require_once('../lib/CitrusPay.php');
	CitrusPay::setApiKey("557976be2080d7560fd3e4e5a47a711c566f3eac",'sandbox');
	$response = Transaction::create($tarr,CitrusPay::getApiKey());
	$redirectUrl = $response->get_redirect_url();
	//echo "RES : ".$response->get_resp_msg();
	$response_code = $response->get_resp_code();
	if($redirectUrl != "" && $response_code == 200)
	{
		header("Location: $redirectUrl");
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Test Moto Transaction</title>
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
			<div>	
			<ul class="form-wrapper add-merchant clearfix">
				<form action="testTransaction.php" method="POST" name="TransactionForm">
					<li class="clearfix"> <label width="125px;">Merchant Access Key:</label><input class="text"
							name="merchantAccessKey" type="text" value="" />
					</li>
					<li class="clearfix"> <label width="125px;">Transaction Number:</label>
					<input class="text" name="transactionID"	type="text" value="" />
					</li>
					<li class="clearfix"> <label width="125px;">Bank Name:</label> <select class="text" name="bankName">
							<option value="">Select Bank</option>
							<option value="ICICI Bank">ICICI Bank</option>
							<option value="AXIS Bank">AXIS Bank</option>
							<option value="CITI Bank">CITI Bank</option>
							<option value="YES Bank">YES Bank</option>
							<option value="SBI Bank">SBI Bank</option>
							<option value="DEUTSCHE Bank">DEUTSCHE Bank</option>
							<option value="UNION Bank">UNION Bank</option>
							<option value="Indian Bank">Indian Bank</option>
							<option value="Federal Bank">Federal Bank</option>
							<option value="HDFC Bank">HDFC Bank</option>
							<option value="IDBI Bank">IDBI Bank</option>
						</select>
					</li>
					<li class="clearfix"> <label width="125px;">Issuer Code:</label><input class="text" name="issuerCode"
							type="text" value="" />
					<li class="clearfix"> <label width="125px;">State:</label><input class="text" name="addressState"
							type="text" value="" />
					</li>
					<li class="clearfix"> <label width="125px;">City:</label><input class="text" name="addressCity" type="text"
							value="" />
					</li>
					<li class="clearfix"> <label width="125px;">Address:</label><input class="text" name="addressStreet1"
							type="text" value="" />
					</li>
					<li class="clearfix"> <label width="125px;">Country:</label><input class="text" name="addressCountry"
							type="text" value="" />
					</li>
					<li class="clearfix"> <label width="125px;">Pin Code:</label><input class="text" name="addressZip" type="text"
							value="" />
					</li>
					<li class="clearfix"> <label width="125px;">First Name:</label><input class="text" name="firstName" type="text"
							value="" />
					</li>
					<li class="clearfix"> <label width="125px;">Last Name:</label><input class="text" name="lastName" type="text"
							value="" />
					</li>
					<li class="clearfix"> <label width="125px;">Mobile Number:</label><input class="text" name="phoneNumber" type="text"
							value="" />
					</li>
					<li class="clearfix"> <label width="125px;">Email:</label><input class="text" name="email" type="text"
							value="" />
					</li>
					<li class="clearfix"> <label width="125px;">Payment Mode:</label><select class="text" name="paymentMode">
							<option value="">Select Payment Mode</option>
							<option value="NET_BANKING">NetBanking</option>
							<option value="CREDIT_CARD">Credit Card</option>
							<option value="DEBIT_CARD">Debit Card</option>
						</select>
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
					<li class="clearfix"> <label width="125px;">Card Type:</label><input class="text" name="cardType" type="text"
							value="" />
					</li>
					<li class="clearfix"> <label width="125px;">CVV Number:</label><input class="text" name="cvvNumber" type="text"
							value="" />
					</li>
					<li class="clearfix"> <label width="125px;">Expiry Year:</label><input class="text" name="expiryYear" type="text"
							value="" />
					</li>
					<li class="clearfix"> <label width="125px;">Return Url:</label><input class="text" name="returnUrl" type="text"
							value="" />
					</li>
					<li class="clearfix"> <label width="125px;">Amount:</label><input class="text" name="amount" type="text"
							value="" />
					</li>
					<li class="clearfix"> <label width="125px;"></label>
						<input type="submit" name="submit"  class="btn-orange" value="Test Transaction" /> <input
							type="reset" name="reset" class="btn" value="Cancel" />
					</li>
				
				</form>
				</ul>
				</div>
			</div>
			<div
				style="padding-left: 700px; padding-bottom: 20px; padding-top: 20px;">
				<div>Copyrights © 2012 Citrus.</div>
			</div>
		</div>
	</div>

</body>
</html>

