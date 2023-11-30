<!--<button id="rzp-button1">Pay </button>-->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
<meta name="viewport" content="width=device-width">
</script>
<body id='rzp-body'>
   <form name='razorpayform' action="completerazor.php" method="POST">
    <input type="text" name="razorpay_payment_id" id="razorpay_payment_id">
    <input type="text" name="razorpay_signature"  id="razorpay_signature" >
    <input type="text" name="razorpay_order_id" id="razorpay_order_id" value="<?php echo $razorpayOrderId; ?>">
    <input type="text" name="razorpay_amount" id="razorpay_amount" value="<?php echo $mainamount; ?>">
    <input type="text" name="razorpay_name" id="razorpay_name" value="<?php echo $contactperson; ?>">
    <input type="text" name="razorpay_company" id="razorpay_company" value="<?php echo $company; ?>">
    <input type="text" name="razorpay_add" id="razorpay_add" value="<?php echo $address1; ?>">
    <input type="text" name="razorpay_email" id="razorpay_email" value="<?php echo $emailid; ?>">
    <input type="text" name="razorpay_txnid" id="razorpay_txnid" value="<?php echo $txnid_nums; ?>">
    <input type="text" name="razorpay_place" id="razorpay_place" value="<?php echo $city; ?>">
  </form> 
</body>

<script>
// Checkout details as a json
var options = <?php echo $json?>;
//console.log(options);
/**
 * The entire list of Checkout fields is available at
 * https://docs.razorpay.com/docs/checkout-form#checkout-fields
 */
options.handler = function (response){
    document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
    document.getElementById('razorpay_signature').value = response.razorpay_signature;
    console.log(response);
    document.razorpayform.submit();
};

// Boolean whether to show image inside a white frame. (default: true)
options.theme.image_padding = true;

options.modal = {
    ondismiss: function() {
        console.log("This code runs when the popup is closed");
    },
    // Boolean indicating whether pressing escape key 
    // should close the checkout form. (default: true)
    escape: true,
    // Boolean indicating whether clicking translucent blank
    // space outside checkout form should close the form. (default: false)
    backdropclose: false
};

var rzp = new Razorpay(options);

document.getElementById('rzp-body').onload = function(e){
    rzp.open();
    e.preventDefault();
}
</script>