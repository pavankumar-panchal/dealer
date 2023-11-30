// JavaScript Document

function formsubmit()
{
	var form = document.submitform;
	//document.getElementById('dealerid').value= dealerid;
	var error = document.getElementById('form-error');
	var field = form.amount;
	if(!field.value){error.innerHTML = errormessage("Enter the amount");  field.focus(); return false;}
	if(!validatecreditamount(field.value)) {error.innerHTML = errormessage("Enter a Valid amount. It should be between Rupee 1 and One Lakh.");  field.focus(); return false; }
	
	error.innerHTML = "";
	form.action = 'http://imax.relyonsoft.com/dealer/paydealerlogin/pay.php';
	form.target = '_blank';
	form.submit();
}

function validatecreditamount(amount)
{
	if(amount > 1 && amount <= 100000)
		return true;
	return false;
}
