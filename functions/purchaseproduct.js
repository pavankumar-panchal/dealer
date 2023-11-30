// JavaScript Document

function formsubmit(command,dealerid)
{
	var form = document.submitform;
	var error = document.getElementById('form-error');
	var field = form.product;
	if(!field.value){error.innerHTML = errormessage("Select the Product"); field.focus(); return false;  }
	var field = form.purchasetype;
	if(!field.value){error.innerHTML = errormessage("Select the Purchase Type"); field.focus(); return false; }
	var field = form.usagetype;
	if(!field.value){error.innerHTML = errormessage("Select the Usage Type"); field.focus(); return false;  }
	var field = form.quantity;
	if(!field.value){error.innerHTML = errormessage("Enter the Quantity"); field.focus(); return false;  }
	var field = form.remarks;
	if(!field.value){error.innerHTML = errormessage("Enter the Remarks"); field.focus(); return false;  }
	var field = form.amount;
	if(!field.value){error.innerHTML = errormessage("The Usage/Purchase type is invalid for selected scheme "); field.focus(); return false;  }
	error.innerHTML = getprocessingimage();
	var passData = '';
	if(command == 'save')
	{
		passData = "switchtype=productsave&product=" + encodeURIComponent(form.product.value) + "&productrate=" + encodeURIComponent(form.productrate.value) + "&usagetype=" + encodeURIComponent(form.usagetype.value) + "&quantity=" + encodeURIComponent(form.quantity.value) + "&amount=" + encodeURIComponent(form.amount.value) + "&purchasetype=" + encodeURIComponent(form.purchasetype.value) + "&lastbillno=" + encodeURIComponent(form.lastbillno .value) + "&dealerid=" + encodeURIComponent(dealerid) + "&productlastslno=" + encodeURIComponent(form.productlastslno.value) + "&scheme=" + encodeURIComponent(form.scheme.value)+ "&remarks=" + encodeURIComponent(form.remarks.value);
	}
	else
	{
		passData = "switchtype=productdelete&productlastslno=" + encodeURIComponent(form.productlastslno.value) + "&lastbillno=" + form.lastbillno .value;
	}
	ajaxcall0 = createajax();
	var queryString = "../ajax/purchaseproduct.php";
	ajaxcall0.open("POST", queryString, true);
	ajaxcall0.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall0.onreadystatechange = function()
	{
		if(ajaxcall0.readyState == 4)
		{
			var ajaxresponse = ajaxcall0.responseText;
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse.split('^');
				if(response[0] == '1')
				{
				form.lastbillno.value = response[2];
				//getamount(dealerid);
				document.getElementById('confirm').disabled = false;
				getnetamount(dealerid);
				generategrid();
				newproduct();
				}
				if(response[0]=='2')
				{
					generategrid(dealerid);
					//getamount(dealerid);
					getnetamount(dealerid);
					newproduct();
				}
				if(response[0]=='3')
				{
					generategrid();
					newproduct();
				}
			}
		}
	}
	ajaxcall0.send(passData);
}

function getinvoicedetails()
{
	var form = document.submitform;
	var error = document.getElementById('form-error');
	var ajaxcall1 = createajax();
	passData="switchtype=getinvoicedetails";
	queryString = "../ajax/purchaseproduct.php";//alert(passData);
	ajaxcall1.open('POST', queryString, true);
	ajaxcall1.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	ajaxcall1.onreadystatechange = function()
	{
		if(ajaxcall1.readyState == 4)
		{
			var ajaxresponse = ajaxcall1.responseText; 
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var invoicegrid = ajaxresponse;
				if(!invoicegrid)
				{
					document.getElementById('displayinvoices').innerHTML = 'No datas found to be displayed.';
				}
				else
				{
					document.getElementById('displayinvoices').innerHTML = ajaxresponse;
				}
			}
				
		}
	}
	ajaxcall1.send(passData);

}

//Function to view the bill in pdf format----------------------------------------------------------------
function viewdealerinvoice(slno)
{
	$('#invoicelastslno').val(slno);
	var form = $('#submitform');	
	// if($('#onlineslno').val() == '')
	// {
	// 	$('#productselectionprocess').html(errormessage('Please select a Customer.')); return false;
	// }
	// else
	// {
		$('#submitform').attr("action", "../ajax/viewdealerinvoicepdf.php") ;
		$('#submitform').attr( 'target', '_blank' );
		$('#submitform').submit();
	//}
}

function getamount(dealerid)
{
	var form = document.submitform;
	var error = document.getElementById('form-error');
	var field = form.scheme;;
	var field = form.product;
	if(!field.value){ return false; field.focus(); }
	var field = form.purchasetype; 
	if(!field.value){ return false; field.focus(); }
	var field = form.usagetype;
	if(!field.value){ return false; field.focus(); }
	var field = form.quantity;
	if(!field.value){ return false; field.focus(); }
	else
	{
		error.innerHTML = getprocessingimage();
		var passData = '';	
		passData = "switchtype=getamount&product=" + encodeURIComponent(form.product.value)  + "&usagetype=" + encodeURIComponent(form.usagetype.value)+ "&scheme=" + encodeURIComponent(form.scheme.value) + "&quantity=" + encodeURIComponent(form.quantity.value) + "&purchasetype=" + encodeURIComponent(form.purchasetype.value) + "&dealerid=" + encodeURIComponent(dealerid) + "&lastbillno=" + encodeURIComponent(form.lastbillno.value);
		ajaxcall0 = createajax();
		var queryString = "../ajax/purchaseproduct.php";
		ajaxcall0.open("POST", queryString, true);
		ajaxcall0.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajaxcall0.onreadystatechange = function()
		{
			if(ajaxcall0.readyState == 4)
			{
				error.innerHTML = '';
				var ajaxresponse = ajaxcall0.responseText.split('^'); 
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					if(ajaxresponse[0] == '1')
					{
						document.getElementById('amount').value = ajaxresponse[1];
						document.getElementById('productrate').value = ajaxresponse[2];
					}
					else
					{
						error.innerHTML = errormessage(ajaxresponse[1]); 
						document.getElementById('amount').value = '';
						document.getElementById('productrate').value = '';
					}
				}
			}
		}
		ajaxcall0.send(passData);
	}
}

function getnetamount(dealerid)
{
	var form = document.submitform;
	var error = document.getElementById('form-error');
	var passData = '';		
	passData = "switchtype=netamount&lastbillno=" + encodeURIComponent(form.lastbillno.value) + "&dealerid=" + encodeURIComponent(dealerid);
	ajaxcall2 = createajax();//alert(passData);
	var queryString = "../ajax/purchaseproduct.php";
	ajaxcall2.open("POST", queryString, true);
	ajaxcall2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall2.onreadystatechange = function()
	{
		if(ajaxcall2.readyState == 4)
		{
			var ajaxresponse = ajaxcall2.responseText;
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse.split('^');
				document.getElementById('totalamount').value = response[0];
				document.getElementById('taxamount').value = response[1];
				document.getElementById('netamount').value = response[2];
			}
			
		}
	}
	ajaxcall2.send(passData);
}

function getcurrentcredit(dealerid)
{
	var form = document.submitform;
	var error = document.getElementById('form-error');
	var passData = '';		
	passData = "switchtype=getcurrentcredit&dealerid=" + encodeURIComponent(dealerid);
	ajaxcall4 = createajax();//
	var queryString = "../ajax/purchaseproduct.php";
	ajaxcall4.open("POST", queryString, true);
	ajaxcall4.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall4.onreadystatechange = function()
	{
		if(ajaxcall4.readyState == 4)
		{
			var ajaxresponse = ajaxcall4.responseText;
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse.split('^');//alert(ajaxresponse);
				document.getElementById('currentcredit').innerHTML = response[0];
				document.getElementById('taxname').innerHTML = response[1]+':'+'('+response[2]+'%'+')';
			}
		
		}
	}
	ajaxcall4.send(passData);
}


function generategrid()
{
	var form = document.submitform;
	var error = document.getElementById('form-error');
	var ajaxcall1 = createajax();
	passData="switchtype=generategrid&lastbillno="+ encodeURIComponent(form.lastbillno.value) + "&productlastslno=" + encodeURIComponent(form.productlastslno.value) ;
	queryString = "../ajax/purchaseproduct.php";//alert(passData);
	ajaxcall1.open('POST', queryString, true);
	ajaxcall1.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	ajaxcall1.onreadystatechange = function()
	{
		if(ajaxcall1.readyState == 4)
		{
			var ajaxresponse = ajaxcall1.responseText; 
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
				document.getElementById('displayproductdetails').innerHTML = ajaxresponse;
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
	var passData="switchtype=gridtoform&productlastslno="+ encodeURIComponent(form.productlastslno.value);
	queryString = "../ajax/purchaseproduct.php";//alert(passData);
	ajaxcall3.open('POST', queryString, true);
	ajaxcall3.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	ajaxcall3.onreadystatechange = function()
	{
		if(ajaxcall3.readyState == 4)
		{
			var ajaxresponse = ajaxcall3.responseText; 
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse.split('^');
				form.productlastslno.value = response[0];
				form.product.value = response[2];
				form.purchasetype.value = response[6];
				form.usagetype.value = response[5];
				form.quantity.value = response[3];
				form.amount.value = response[4];
				form.scheme.value = response[7];
			}
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
	form.productrate.value ='';
	form.productlastslno.value ='';
	/*form.reset();*/
	error.innerHTML = '';
}




function checkforproducts(dealerid)
{
	var form = document.submitform;
	var error = document.getElementById('form-error');
	var formerror = document.getElementById('form-error-confirm');
	var billno = document.getElementById('lastbillno').value;
	if(billno == '')
	{
		error.innerHTML = errormessage("Select atleast one Product to proceed"); return false;
	}
	else
	{
		var ajaxcall5 = createajax();
		var passData="switchtype=checkforproducts&lastbillno="+ encodeURIComponent(form.lastbillno.value) + "&dealerid=" + encodeURIComponent(dealerid);
		queryString = "../ajax/purchaseproduct.php";//alert(passData);
		formerror.innerHTML = getprocessingimage();
		ajaxcall5.open('POST', queryString, true);
		ajaxcall5.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		ajaxcall5.onreadystatechange = function()
		{
			if(ajaxcall5.readyState == 4)
			{
				var ajaxresponse = ajaxcall5.responseText; 
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					var response = ajaxresponse.split('^');
					if(response[0] == 1)
					{
						formerror.innerHTML = '';
						form.lastbillno.value = response[2];
						getcurrentcreditfromdb(dealerid);
						
					}
				}
			}
		}
		ajaxcall5.send(passData);
	}
}


function attachcards(dealerid)
{
	var form = document.submitform;//alert(form.lastbillno.value);
	var error = document.getElementById('form-error-confirm');
	var totalamount = form.totalamount.value;
	var taxamount = form.taxamount.value;
	var netamount = form.netamount.value;
	var ajaxcall6 = createajax();
	var netamount = form.netamount.value;
	var currentcredit = document.getElementById('currentcredit').innerHTML; 
	error.innerHTML = 'Processing ... <img src="../images/inventory-processing.gif" border="0" />';
	/*if((currentcredit-netamount) < 0)
	{
		error.innerHTML = errormessage("No sufficient credits available"); return false;
	}
	else
	{*/
		
		var confirmation = confirm("Are you sure you want to proceed");
		if(confirmation)
		{
			document.getElementById('confirm').disabled = true;
			error.innerHTML = getprocessingimage();
			var passData="switchtype=attachcards&lastbillno="+ encodeURIComponent(form.lastbillno.value) + "&dealerid=" + encodeURIComponent(dealerid) + "&netamount=" + encodeURIComponent(netamount) + "&currentcredit=" + encodeURIComponent(currentcredit) + "&totalamount=" + encodeURIComponent(totalamount) + "&taxamount=" + encodeURIComponent(taxamount) + "&netamount=" + encodeURIComponent(netamount);
			queryString = "../ajax/purchaseproduct.php";  
			ajaxcall6.open('POST', queryString, true);
			ajaxcall6.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			ajaxcall6.onreadystatechange = function()
			{
				if(ajaxcall6.readyState == 4)
				{
					var ajaxresponse = ajaxcall6.responseText;// alert(ajaxresponse)
					if(ajaxresponse == 'Thinking to redirect')
					{
						window.location = "../logout.php";
						return false;
					}
					else
					{
						var response = ajaxresponse.split('^');//
						if(response[0] == 1)
						{
							document.getElementById('maindiv').style.display = 'none';
							document.getElementById('carddetails').style.display = 'block';
							document.getElementById('displayscratchcarddetails').innerHTML = response[1];
							error.innerHTML = '';
							getcurrentcredit(dealerid);
						}
						else
						error.innerHTML = errormessage(response[1]);
					}
				}
			}
			ajaxcall6.send(passData);
		}
		else
		error.innerHTML = '';
	//}
}

function getcurrentcreditfromdb(dealerid)
{
	var form = document.submitform;
	var error = document.getElementById('form-error');
	var passData = '';		
	passData = "switchtype=getcurrentcredit&dealerid=" + encodeURIComponent(dealerid);
	ajaxcall7 = createajax();//
	var queryString = "../ajax/purchaseproduct.php";
	ajaxcall7.open("POST", queryString, true);
	ajaxcall7.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall7.onreadystatechange = function()
	{
		if(ajaxcall7.readyState == 4)
		{
			var ajaxresponse = ajaxcall7.responseText;
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse.split('^');//alert(ajaxresponse);
				document.getElementById('currentcredit').innerHTML = response[0];
				attachcards(dealerid);
			}
		
		}
	}
	ajaxcall7.send(passData);
}

function newentry()
{
	var form = document.submitform;
	form.reset();
	document.getElementById('form-error').innerHTML = '';
	document.getElementById('displayproductdetails').innerHTML ='';
	form.lastbillno.value ='';
	form.productlastslno.value ='';
	
}

function newtransactionentry()
{
	var form = document.submitform;
	document.getElementById('maindiv').style.display = 'block';
	document.getElementById('carddetails').style.display = 'none';
	document.getElementById('displayproductdetails').innerHTML ='';
	document.getElementById('form-error').innerHTML = '';
	form.lastbillno.value ='';
	form.productlastslno.value ='';
	form.totalamount.value = '';
	form.taxamount.value = '';
	form.netamount.value = '';
	document.getElementById('confirm').disabled = false;
//	form.reset();
}

function gotodashboard()
{
	form.action = "../index.php?a_link=dashboard";
	form.submit();
}