// JavaScript Document

function formsubmit(command,dealerid)
{
	var form = document.submitform;
	var error = document.getElementById('form-error');
	var field = form.product;
	if(!field.value){error.innerHTML = errormessage("Select the Product"); return false; field.focus(); }
	var field = form.purchasetype;
	if(!field.value){error.innerHTML = errormessage("Select the Purchase Type"); return false; field.focus(); }
	var field = form.usagetype;
	if(!field.value){error.innerHTML = errormessage("Select the Usage Type"); return false; field.focus(); }
	var field = form.quantity;
	if(!field.value){error.innerHTML = errormessage("Enter the Quantity"); return false; field.focus(); }
	var passData = '';
	if(command == 'save')
	{
		passData = "switchtype=productsave&product=" + form.product.value  + "&usagetype=" + form.usagetype.value + "&quantity=" + form.quantity.value + "&purchasetype=" + form.purchasetype.value + "&lastbillno=" + form.lastbillno .value + "&dealerid=" + dealerid + "&productlastslno=" + form.productlastslno.value;alert(passData);
	}
	else
	{
		passData = "switchtype=productdelete&productlastslno=" + form.productlastslno.value + "&lastbillno=" + form.lastbillno .value;alert(passData)
	}
	ajaxcall0 = createajax();//alert(passData);
	var queryString = "../ajax/simplepurchase.php";
	ajaxcall0.open("POST", queryString, true);
	ajaxcall0.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall0.onreadystatechange = function()
	{
		if(ajaxcall0.readyState == 4)
		{
			var ajaxresponse = ajaxcall0.responseText;
			var response = ajaxresponse.split('^');
			if(response[0] == '1')
			{
			form.lastbillno.value = response[2];
			//getamount();
			generategrid();
			newproduct();
			}
			if(response[0]=='2')
			{
				generategrid();
				newproduct();
			}
			if(response[0]=='3')
			{
				generategrid();
				newproduct();
			}
		}
	}
	ajaxcall0.send(passData);
	
}


/*function getamount(dealerid)
{
	//alert('test');
	var form = document.submitform;
	var error = document.getElementById('form-error');
	var field = form.product;
	if(!field.value){error.innerHTML = errormessage("Select the Product"); return false; field.focus(); }
	var field = form.purchasetype;
	if(!field.value){error.innerHTML = errormessage("Select the Purchase Type"); return false; field.focus(); }
	var field = form.usagetype;
	if(!field.value){error.innerHTML = errormessage("Select the Usage Type"); return false; field.focus(); }
	var field = form.quantity;
	//var dealerid = '1001';
	var passData = '';		
	passData = "switchtype=getamount&product=" + form.product.value  + "&usagetype=" + form.usagetype.value + "&quantity=" + form.quantity.value+ "&purchasetype=" + form.purchasetype.value + "&dealerid=" + dealerid + "&lastbillno=" + form.lastbillno.value;
	ajaxcall0 = createajax();alert(passData);
	var queryString = "../ajax/purchaseproduct.php";
	ajaxcall0.open("POST", queryString, true);
	ajaxcall0.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall0.onreadystatechange = function()
	{
		if(ajaxcall0.readyState == 4)
		{
			var ajaxresponse = ajaxcall0.responseText;alert(ajaxresponse);
			var response = ajaxresponse.split('^');
			document.getElementById('amount').value = response[0];
			document.getElementById('totalamount').value = response[1];
			document.getElementById('taxamount').value = response[2];
			document.getElementById('netamount').value = response[3];
			
		}
	}
	ajaxcall0.send(passData);
}*/

/*function generatebillnumber()
{
	var form = document.submitform;
	var error = document.getElementById('form-error');
	var passData = '';		
	passData = "switchtype=generatebillnumber";
	ajaxcall2 = createajax();//alert(passData);
	var queryString = "../ajax/purchaseproduct.php";
	ajaxcall2.open("POST", queryString, true);
	ajaxcall2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall2.onreadystatechange = function()
	{
		if(ajaxcall2.readyState == 4)
		{
			var ajaxresponse = ajaxcall2.responseText;alert(ajaxresponse);
			form.billnumber.value = 	ajaxresponse		
		}
	}
	ajaxcall2.send(passData);
}*/


function generategrid()
{
	var form = document.submitform;
	var error = document.getElementById('form-error');
	var ajaxcall1 = createajax();
	passData="switchtype=generategrid&lastbillno="+ form.lastbillno.value + "&productlastslno=" + form.productlastslno.value ;
	queryString = "../ajax/purchaseproduct.php";alert(passData);
	ajaxcall1.open('POST', queryString, true);
	ajaxcall1.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	ajaxcall1.onreadystatechange = function()
	{
		if(ajaxcall1.readyState == 4)
		{
			var ajaxresponse = ajaxcall1.responseText; alert(ajaxresponse);
			document.getElementById('displayproductdetails').innerHTML = ajaxresponse;
			resetvalues();
		}
	}
	ajaxcall1.send(passData);
}

function productgridtoform(id)
{	
	var form = document.submitform;
	var error = document.getElementById('form-error');
	form.productlastslno.value = id;
	var ajaxcall3 = createajax();
	var passData="switchtype=gridtoform&productlastslno="+ form.productlastslno.value;
	queryString = "../ajax/purchaseproduct.php";
	ajaxcall3.open('POST', queryString, true);
	ajaxcall3.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	ajaxcall3.onreadystatechange = function()
	{
		if(ajaxcall3.readyState == 4)
		{alert(passData);
			var ajaxresponse = ajaxcall3.responseText; alert(ajaxresponse);
			var response = ajaxresponse.split('^');
			form.productlastslno.value = response[0];
			form.product.value = response[2];
			form.purchasetype.value = response[6];
			form.usagetype.value = response[5];
			form.quantity.value = response[3];
			form.amount.value = response[4];
		}
	}
	ajaxcall3.send(passData);
	
}



function newproduct()
{

	var form = document.submitform;
	var error = document.getElementById('form-error');
	form.product.value ='';
	form.usagetype.value ='';
	form.purchasetype.value ='';
	form.quantity.value ='';
	form.amount.value ='';
	form.productlastslno.value ='';
	error.innerHTML = '';
}


function attachcards(dealerid)
{
	//var dealerid = '1001';
	var form = document.submitform;
	var error = document.getElementById('form-error');
	//var netamount = form.netamount.value;
	var ajaxcall4 = createajax();
	var passData="switchtype=attachcards&lastbillno="+ form.lastbillno.value + "&dealerid=" + dealerid;
	queryString = "../ajax/purchaseproduct.php";
	ajaxcall4.open('POST', queryString, true);
	ajaxcall4.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	ajaxcall4.onreadystatechange = function()
	{
		if(ajaxcall4.readyState == 4)
		{alert(passData);
			var ajaxresponse = ajaxcall4.responseText; alert(ajaxresponse);
			document.getElementById('maindiv').style.display = 'none';
			document.getElementById('carddetails').style.display = 'block';
			document.getElementById('displayscratchcarddetails').innerHTML = ajaxresponse;
		}
	}
	ajaxcall4.send(passData);
}

function newentry()
{
	//alert('newtransaction');
	var form = document.submitform;
	form.reset();
	document.getElementById('form-error').innerHTML = '';
	document.getElementById('displayproductdetails').innerHTML ='';
	form.lastbillno.value ='';
	form.productlastslno.value ='';
}