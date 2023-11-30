<?php 
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
require('../config.php');
require('../razorpay-php/Razorpay.php');
session_start(); 
include("../functions/phpfunctions.php");

$referurl = parse_url($_SERVER['HTTP_REFERER']);
$referhost = $referurl['host'];

if(isset($_POST["businessname"],$_POST["contactperson"],$_POST["address"],$_POST["district"],$_POST["state"],$_POST["pincode"],$_POST["phone"],$_POST["emailid"],$_POST["dealerid"],$_POST["amount"]) == false || ($referhost <> 'meghanab' && $referhost <> 'imax.relyonsoft.net' && $referhost <> 'www.imax.relyonsoft.net') || checkemailaddress($_POST["emailid"]) == false)
{
	echo("Invalid Information.");
	exit;
}


/*-----------------------------Do not edit this piece of code - Begin-----------------------------*/

$query = "SHOW TABLE STATUS like 'transactions'";
$result = runicicidbquery($query);
$row = mysqli_fetch_array($result);
$nextautoincrementid = $row['Auto_increment'];


$merchatid = "00004074";
$date = date('Y-m-d');
$time = date('H:i:s');
$userip = $_SERVER["REMOTE_ADDR"];
$userbrowserlanguage = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
$userbrowseragent = $_SERVER["HTTP_USER_AGENT"];
$relyontransactionid = $nextautoincrementid; 

/*-----------------------------Do not edit this piece of code - End-----------------------------*/

//Main Details
$orderid = ""; //Optional
$invoicenumber = ""; //Optional

//User Details
$company = substr($_POST['businessname'],0,80); //Optional
$contactperson = substr($_POST['contactperson'],0,50);
$address1 = substr($_POST['address'],0,50);
$address2 = ""; //Optional
$address3 = ""; //Optional
$city = substr($_POST['district'],0,30);
$state = substr($_POST["state"],0,30);
$country = "IND"; //No change
$pincode =  ($_POST["pincode"] == '')?'111111':$_POST["pincode"];
$phone = substr($_POST['phone'],0,15); //Optional
$emailid = substr($_POST['emailid'],0,80); //Optional
$customerid = "";
$amount = $_POST['amount']; 
$recordreference = $_POST["dealerid"];
$productname = "Saral iMax Credit";
$quantity = "1";
//exit;


// Create the Razorpay Order

use Razorpay\Api\Api;

$api = new Api($keyId, $keySecret);

//
// We create an razorpay order using orders api
// Docs: https://docs.razorpay.com/docs/orders
//
//$manurcpt=$recordreference."#".$lastslno;

$manurcpt=$recordreference;

$orderData = [
    'receipt'         => $manurcpt,
    'amount'          => $amount * 100, // 2000 rupees in paise
    'currency'        => 'INR',
    'payment_capture' => 1 // auto capture
];
//exit;

$razorpayOrder = $api->order->create($orderData);
//var_dump($razorpayOrder); exit;
$razorpayOrderId = $razorpayOrder['id'];

$_SESSION['razorpay_order_id'] = $razorpayOrderId;
$mainamount=$amount;
$displayAmount = $amount = $orderData['amount'];

if ($displayCurrency !== 'INR')
{
    $url = "https://api.fixer.io/latest?symbols=$displayCurrency&base=INR";
    $exchange = json_decode(file_get_contents($url), true);

    $displayAmount = $exchange['rates'][$displayCurrency] * $amount / 100;
}

$data = [
    "key"               => $keyId,
    "amount"            => $amount,
    "name"              => $contactperson,
    "description"       => "Saral iMax Credit",
    "image"             => "http://relyonsoft.com/wp-content/uploads/2015/01/Relyon-Logo-142x50.png",
    "prefill"           => [
    "name"              => $contactperson,
    "email"             => $emailid,
    "contact"           => $phone,
    ],
    "notes"             => [
    "address"           => $address1,
    "merchant_order_id" => $manurcpt, //cusid and onlineinvoiceno
    ],
    
    "theme"             => [
    "color"             => "#E68C22"
    ],
    "order_id"          => $razorpayOrderId,
];

if ($displayCurrency !== 'INR')
{
    $data['display_currency']  = $displayCurrency;
    $data['display_amount']    = $displayAmount;
}

$json = json_encode($data);
$responseSuccessURL="http://relyonsoft.com/dealer/paydealerlogin/completerazor.php";
//exit;
?>
<html>
	<head>
		<title>Relyonsoftech Payment gateway</title>
	</head>
	<body onLoad="document.frm1.submit()">

		<?php

        $responseSuccessURL = "http://relyonsoft.com/dealer/paydealerlogin/completerazor.php"; //Need to change as per location of response page
		
		//Do not touch this. Inserting the record to Relyon main Credit Card transaction table.

$query = "insert into `transactions` (date, time, userip, orderid, responseurl, invoicenumber, amount, company, contactperson, address1,
address2, address3, city, state, pincode, phone, emailid, customerid, productname, quantity, userbrowserlanguage, userbrowseragent,recordreference,razorpay) 
values('".$date."', '".$time."', '".$userip."', '".$razorpayOrderId."', '".$responseSuccessURL."','".$invoicenumber."', '".$mainamount."', '".$company."', 
'".$contactperson."', '".addslashes($address1)."', '".addslashes($address2)."', '".addslashes($address3)."', '".$city."', '".$state."', 
'".$pincode."', '".$phone."', '".$emailid."', '".$customerid."', '".$productname."', '".$quantity."', '".$userbrowserlanguage."', '".$userbrowseragent."', 
'".$recordreference."','Y')";
$result = runicicidbquery($query);

$querytxnid = "select max(id) as txnid from transactions";
$resulttxnid = runicicidbquery($querytxnid);
$fetchtxnid = mysqli_fetch_array($resulttxnid);
$txnid_nums = $fetchtxnid['txnid'];

require("../checkout/manual.php");

		?>

</body>
</html>