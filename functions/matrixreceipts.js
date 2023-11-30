var customerarray = new Array();
var customerarray1 = new Array();
var customerarray2 = new Array();
var customerarray3 = new Array();
var customerarray4 = new Array();
var process1;var process2;var process3;var process4;


function formsubmit(command)
{
	var form = $('#submitform');
	var error = $('#form-error');
	if($('#invoivcelist').length > 0)
	{
		var field = $('#invoivcelist');
		var invoiceno = $('#invoivcelist').val();
		if(!field.val()) { error.html(errormessage("Select an Invoivce No.")); field.focus(); return false; }
	}
	else
	{
		
		var invoiceno = '';
	}
	var field = $('#receiptamount');
	if(!field.val()) { error.html(errormessage("Enter the Amount.")); field.focus(); return false; }
	if(field.val())	{ if(!validateamount(field.val())) { error.html(errormessage('Amount is not Valid.')); field.focus(); return false; } }
	var paymentmode = $('input[name=paymentmode]:checked').val();
	var field = $('#remarks');
	var field = $('#privatenote');
	if($('#lastslno').val() == '')
	{
		if(($('#receiptamount').val()*1) > ($('#invoiceamount').html()*1))
		{
			//alert($('#receiptamount').val())
			error.html(errormessage('Receipt Amount is greater than invoice amount.')); return false; 
		}
	}
	if(paymentmode == 'chequeordd')
	{
			var field = $('#DPC_chequedate');
			if(!field.val()) {$('#form-error').html(errormessage('Please enter the Cheque Date')); field.focus(); return false; }
			var field = $('#chequeno');
			if(!field.val()) { $('#form-error').html(errormessage('Please enter the Cheque No')); field.focus(); return false; }
			if(field.val()){ if(!validateamount(field.val())) { $('#form-error').html(errormessage('Cheque No is not Valid')); field.focus(); return false; }}
			var field = $('#drawnon');
			if(!field.val()) { $('#form-error').html(errormessage('Please enter the Drawn On'));  field.focus(); return false; }
	}
	var passData = "";
	passData =  "switchtype=save&customerreference=" + encodeURIComponent($('#cusslno').val()) + "&invoivcelist=" + encodeURIComponent(invoiceno) + "&privatenote=" + encodeURIComponent($('#privatenote').val()) +  "&remarks=" + encodeURIComponent($('#remarks').val()) +  "&paymentmode=" + encodeURIComponent(paymentmode) +  "&receiptamount=" + encodeURIComponent($('#receiptamount').val())+  "&chequedate=" + encodeURIComponent($('#DPC_chequedate').val())+  "&chequeno=" + encodeURIComponent($('#chequeno').val()) +  "&drawnon=" + encodeURIComponent($('#drawnon').val())+  "&depositdate=" + encodeURIComponent($('#DPC_depositdate').val())+  "&receiptdate=" + encodeURIComponent($('#DPC_receiptdate').val()) +  "&lastslno=" + encodeURIComponent($('#lastslno').val()) + "&dummy=" + Math.floor(Math.random()*100000000);
	queryString = '../ajax/matrixreceipts.php';
	error.html(getprocessingimage());
	ajaxcall1 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse.split('^');//alert(response)
				if(response[0] == '1')
				{
					error.html(successmessage(response[1]));
					generatereceiptgrid('');
					newcreditentry();

				}
				else
				{
					error.html(errormessage('Unable to Connect...' + ajaxresponse));
				}
			}
		}, 
		error: function(a,b)
		{
			error.html(scripterror());
		}
	});
}

function gettotalcustomercount()
{
	var form = $('#customerselectionprocess');
	var passData = "switchtype=getcustomercount&dummy=" + Math.floor(Math.random()*10054300000);
	queryString = "../ajax/matrixreceipts.php";
	ajaxcall1 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse;
				if(response == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				$("#totalcount").html(response['count']);
				refreshcustomerarray(response['count']);
			}
		}, 
		error: function(a,b)
		{
			$("#customerselectionprocess").html(scripterror());
		}
	});	
}



function refreshcustomerarray(customercount)
{
	var form = $('#customerselectionprocess');
	var totalcustomercount = customercount;
	var limit = Math.round(totalcustomercount/4);
	//alert(limit);
	var startindex = 0;
	var startindex1 = (limit)+1;
	var startindex2 = (limit*2)+1;
	var startindex3 = (limit*3)+1;
	var form = $('#cardsearchfilterform');
	var passData = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex);
	var passData1 = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex1);
	var passData2 = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex2);
	var passData3 = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex3);
	$('#customerselectionprocess').html(getprocessingimage());
	queryString = "../ajax/matrixreceipts.php";
	ajaxcall2 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse;
				for( var i=0; i<response.length; i++)
				{
					customerarray1[i] = response[i];
				}
				process1 = true;
				compilecustomerarray();
			}
		}, 
		error: function(a,b)
		{
			$("#customerselectionprocess").html(scripterror());
		}
	});	
	
	queryString = "../ajax/matrixreceipts.php";
	ajaxcall3 = $.ajax(
	{
		type: "POST",url: queryString, data: passData1, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse;//alert(response)
				for( var i=0; i<response.length; i++)
				{
					customerarray2[i] = response[i];
				}
				process2 = true;
				compilecustomerarray();
			}
		}, 
		error: function(a,b)
		{
			$("#customerselectionprocess").html(scripterror());
		}
	});	

	queryString = "../ajax/matrixreceipts.php";
	ajaxcall4 = $.ajax(
	{
		type: "POST",url: queryString, data: passData2, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse;//alert(response)
				for( var i=0; i<response.length; i++)
				{
					customerarray3[i] = response[i];
				}
				process3 = true;
				compilecustomerarray();
			}
		}, 
		error: function(a,b)
		{
			$("#customerselectionprocess").html(scripterror());
		}
	});	
	
	queryString = "../ajax/matrixreceipts.php";
	ajaxcall5 = $.ajax(
	{
		type: "POST",url: queryString, data: passData3, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse;//alert(response)
				for( var i=0; i<response.length; i++)
				{
					customerarray4[i] = response[i];
				}
				process4 = true;
				compilecustomerarray();
			}
		}, 
		error: function(a,b)
		{
			$("#customerselectionprocess").html(scripterror());
		}
	});	

}

function compilecustomerarray()
{
	if(process1 == true && process2 == true && process3 == true && process4 == true)
	{
		customerarray = customerarray1.concat(customerarray2.concat(customerarray3.concat(customerarray4)));
		flag = true;
		$("#customerselectionprocess").html(successsearchmessage('All Customers...'))
		getcustomerlist1();
		
	}
	else
	return false;
}


function getcustomerlist1()
{	
	disableformelemnts();
	var form = $('#filterform');
	var selectbox = $('#customerlist');
	var numberofcustomers = customerarray.length;
	$('#detailsearchtext').focus();
	var actuallimit = 500;
	var limitlist = (numberofcustomers > actuallimit)?actuallimit:numberofcustomers;
	
	$('option', selectbox).remove();
	var options = selectbox.attr('options');

	//selectbox.options.length = 0;
	
	for( var i=0; i<limitlist; i++)
	{
		var splits = customerarray[i].split("^");
		options[options.length] = new Option(splits[0], splits[1]);
	}
}

function newcreditentry()
{
	var form = $('#submitform');
	$('#submitform')[0].reset();
	enablereceptsbutton();
	$('#lastslno').val('');
	$('#invoiceamount').html('Not Available');
	$('#amountreceived').html('Not Available');
	$('#balanceamount').html('Not Available');
	$('#displayreceiptstatus').html('Not Available');
	$('#displayreceiptremarks').html('Not Available');
	$('#paymentremarksdiv').show();
	$('#paymentdetailsdiv').hide();
	$('#save').attr("disabled", false); 
	$("#save").removeClass('swiftchoicebuttondisabled');
	$("#save").addClass('swiftchoicebutton');
	$('#invoivcelist').attr("disabled", false); 
	$('#displayreceiptdiv').hide();
	$('#viewtheinvoice').hide();
	$('#viewthereceipt').hide();
	$('#sendthereceipt').hide();
	$('#sendcancelledreceipt').hide();
}


function selectfromlist()
{
	$('#cusslno').val('');
	$('#viewtheinvoice').hide();
	$('#viewthereceipt').hide();
	$('#sendthereceipt').hide();
	$('#sendcancelledreceipt').hide();
	$('#displayreceiptdiv').hide();
	var selectbox = $("#customerlist option:selected").val();
	$('#cusslno').val($("#customerlist option:selected").val());
	$('#detailsearchtext').val($("#customerlist option:selected").text());
	$('#detailsearchtext').select();
	$('#displaycustomername').html($("#customerlist option:selected").text());
	newcreditentry();
	$('#form-error').html('');
	generatereceiptgrid('');
	getuserinvoicelist();
	enablereceptsbutton();
	$('#cancelbutton').attr("disabled", false); 
	$('#cancelbutton').removeClass('swiftchoicebuttondisabledbignew');	
	$('#cancelbutton').addClass('swiftchoicebuttonbignew');


}

function selectacustomer(input)
{
	var selectbox = $('#customerlist');
	var pattern = new RegExp("^" + input.toLowerCase());
	
	if(input == "")
	{
		getcustomerlist1();
	}
		else
	{
		$('option', selectbox).remove();
		var options = selectbox.attr('options');
		var addedcount = 0;
		for( var i=0; i < customerarray.length; i++)
		{
				if(input.charAt(0) == "%")
				{
					withoutspace = input.substring(1,input.length);
					pattern = new RegExp(withoutspace.toLowerCase());
					comparestringsplit = customerarray[i].split("^");
					comparestring = comparestringsplit[1];
				}
				else
				{
					pattern = new RegExp("^" + input.toLowerCase());
					comparestring = customerarray[i];
				}
				var result1 = pattern.test(trimdotspaces(customerarray[i]).toLowerCase());
				var result2 = pattern.test(customerarray[i].toLowerCase());
				if(result1 || result2)
				{
					var splits = customerarray[i].split("^");
					options[options.length] = new Option(splits[0], splits[1]);
					addedcount++;
					if(addedcount == 100)
						break;
				}
		}
	}
}


function customersearch(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 38)
		scrollcustomer('up');
	else if(KeyID == 40)
		scrollcustomer('down');
	else
	{
		var form = $('#submitform');
		var input = $('#detailsearchtext').val();
		selectacustomer(input);
	}
}

function scrollcustomer(type)
{
	var selectbox = $('#customerlist');
	var totalcus = $("#customerlist option").length;
	var selectedcus = $("select#customerlist").attr('selectedIndex');
	if(type == 'up' && selectedcus != 0)
		$("select#customerlist").attr('selectedIndex', selectedcus - 1);
	else if(type == 'down' && selectedcus != totalcus)
		$("select#customerlist").attr('selectedIndex', selectedcus + 1);
	selectfromlist()
}


function disableformelemnts()
{
	var count = document.submitform.elements.length;
	for (i=0; i<count; i++) 
	{
		var element = document.submitform.elements[i]; 
		element.disabled=true; 
	}
}

function enableformelemnts()
{
	var count = document.submitform.elements.length;
	for (i=0; i<count; i++) 
	{
		var element = document.submitform.elements[i]; 
		element.disabled=false; 
	}
}


function getuserinvoicelist()
{
	var form = $('#submitform');
	var passData = "switchtype=getuserinvoicelist&customerreference=" + encodeURIComponent($('#cusslno').val()) + "&dummy=" + Math.floor(Math.random()*10054300000);//alert(passData);
	$('#form-error').html(getprocessingimage());	
	queryString = "../ajax/matrixreceipts.php";
	ajaxcall4 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse.split('^');
				$('#form-error').html('');	
				if(response[0] == '1')
				{
					$('#smsaccountlist').html(response[1]);
					enableformelemnts();
				}
				else if(response[0] == '2')
				{
					$('#smsaccountlist').html(response[1]);
					disableformelemnts();
				}
				else
				{
					$('#form-error').html(errormessage('Unable to Connect.'));
				}
			}
		}, 
		error: function(a,b)
		{
			$("#form-error").html(scripterror());
		}
	});
}

function getinovoiceamount()
{
	var form = $('#submitform');
	var error = $('#form-error');
	var field = $('#invoivcelist');//alert($('#invoivcelist').val());
	if(!field.val()) { error.html(errormessage("Select an Invoivce No.")); field.focus(); return false; }
	var passData = "switchtype=getinovoiceamount&invoiceno=" + encodeURIComponent($('#invoivcelist').val());
	$('#form-error').html( getprocessingimage());	
	queryString = "../ajax/matrixreceipts.php";
	ajaxcall4 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse;
				if(response['errorcode'] == '1')
				{
					if(response['netamount'] == '0')
					{
						$('#save').attr("disabled", true); 
						$("#save").addClass('swiftchoicebuttondisabled');
						$("#save").removeClass('swiftchoicebutton')
					}
					else
					{
						$('#save').attr("disabled", false); 
						$("#save").removeClass('swiftchoicebuttondisabled');
						$("#save").addClass('swiftchoicebutton')
					}
					$('#form-error').html('');	
					$('#balanceamount').html(response['netamount']);
					$('#amountreceived').html(response['receivedamount']);
					$('#invoiceamount').html(response['totalamount']);
					
					//$('#submitform')[0].reset();
				}
				else
				{
					$('#invoiceamount').html("Not available");
				}
			}
		}, 
		error: function(a,b)
		{
			$("#form-error").html(scripterror());
		}
	});
}

//Function to generate Receipt Grid
function generatereceiptgrid(startlimit)
{
	if($('#cusslno').val() != '')
	{
		var form = $('#submitform');
		var startlimit = '';
		var passData = "switchtype=generatereceiptgrid&startlimit="+ encodeURIComponent(startlimit) + "&customerreference=" + $('#cusslno').val();
		var queryString = "../ajax/matrixreceipts.php";
		$('#tabgroupgridc1_1').html(getprocessingimage());
		$('#tabgroupgridc1link').html('');
		ajaxcall5 = $.ajax(
		{
			type: "POST",url: queryString, data: passData, cache: false,
			success: function(ajaxresponse,status)
			{	
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
					$('#tabgroupgridwb1').html("Total Count :  " + response[2]);
					$('#tabgroupgridc1_1').html(response[1]);
					$('#tabgroupgridc1link').html(response[3]);
					}
					else
					{
						$('#tabgroupgridc1_1').html("No datas found to be displayed.");
					}
				}
			}, 
			error: function(a,b)
			{
				$("#tabgroupgridc1_1").html(scripterror());
			}
		});
	}
}

//Function to "show all" or "Show More" Records
function getmoregeneratereceiptgrid(startlimit,slnocount,showtype)
{
	if($('#cusslno').val() != '')
	{
		var form = $('#submitform');
	//	$('#lastslno').val() = id;	
		var passData = "switchtype=generatereceiptgrid&startlimit="+ encodeURIComponent(startlimit) + "&slnocount=" + encodeURIComponent(slnocount) + "&showtype=" + encodeURIComponent(showtype) + "&customerreference=" + $('#cusslno').val() + "&dummy=" + Math.floor(Math.random()*1000782200000);
		//alert(passData);
		var queryString = "../ajax/matrixreceipts.php";
		$('#tabgroupgridc1link').html(getprocessingimage());
		ajaxcall6 = $.ajax(
		{
			type: "POST",url: queryString, data: passData, cache: false,
			success: function(ajaxresponse,status)
			{	
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					var response = ajaxresponse.split('^');//alert(response);
					if(response[0] == '1')
					{
						$('#tabgroupgridwb1').html("Total Count :  " + response[2]);
						$('#resultgrid').html($('#tabgroupgridc1_1').html());
						$('#tabgroupgridc1_1').html($('#resultgrid').html().replace(/\<\/table\>/gi,'')+ response[1]);
						$('#tabgroupgridc1link').html(response[3]);
					}
					else
					{
						$('#tabgroupgridc1_1').html("No datas found to be displayed.");
					}
				}
			}, 
			error: function(a,b)
			{
				$("#tabgroupgridc1_1").html(scripterror());
			}
		});
	}
}

function receiptgridtoform(slno)
{
	if(slno != '')
	{
		var form = $('#submitform');
		var error = $('#form-error');
		error.html('');
		var passData = "switchtype=gridtoform&lastslno=" + encodeURIComponent(slno) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
		error.html(getprocessingimage());
		var queryString = "../ajax/matrixreceipts.php";
		ajaxcall7 = $.ajax(
		{
			type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
			success: function(ajaxresponse,status)
			{	
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					var response = ajaxresponse;
					if(response['errorcode'] == '1')
					{ 
						error.html('');
						$('#viewtheinvoice').show();
						$('#viewthereceipt').show();
						if(response['status'] == 'ACTIVE')
						{
							$('#sendthereceipt').show();
							$('#sendcancelledreceipt').hide();
						}
						else if(response['status'] == 'CANCELLED')
						{
							$('#sendcancelledreceipt').show();
							$('#sendthereceipt').hide();
						}
						$('#displayreceiptdiv').show();
						$('#lastslno').val(response['slno']);
						$('#invoivcelist').attr("disabled", true); 
						$('#invoivcelist').val(response['invoiceno']);
						$('#receiptamount').val(response['receiptamount']);
						$('#invoiceamount').html(response['invoiceamount']);
						$('#remarks').val(response['receiptremarks']);
						$('#privatenote').val(response['privatenote']);
						$('#'+response['paymentmode']).attr("checked","checked");
						$('#DPC_chequedate').val(response['chequedate']);
						$('#chequeno').val(response['chequeno']);
						$('#drawnon').val(response['drawnon']);
						$('#DPC_depositdate').val(response['depositdate']);
						$('#DPC_receiptdate').val(response['receiptdate']);
						$('#balanceamount').html(response['balanceamount']);
						$('#amountreceived').html(response['receiptamount']);
						$('#entereddate').html(response['createddate']);
						$('#enteredby').html(response['createdby']);
						$('#displayreceiptstatus').html(response['status']);
						$('#displayreceiptremarks').html(response['statusremarks']);
						if(response['paymentmode'] == 'chequeordd')
						{
							$('#paymentremarksdiv').show();
							$('#paymentdetailsdiv').hide();
						}
						else
						{
							$('#paymentremarksdiv').hide();
							$('#paymentdetailsdiv').show();
						}
						$('#save').attr("disabled", true); 
						$("#save").removeClass('swiftchoicebutton');
						$("#save").addClass('swiftchoicebuttondisabled');
					}
					else
					{
						error.html(errormessage('Unable to Connect.'));
					}
				}
			}, 
			error: function(a,b)
			{
				error.html(scripterror());
			}
		});
	}
	else
		$('#quantity').attr('readOnly',false);
}


function hideorshowremarksdiv()
{
	var paymenttype = $("input[name='paymentmode']:checked").val();
	if(paymenttype == 'cash')
	{
		$('#paymentremarksdiv').hide();
		$('#paymentdetailsdiv').show();
	}
	else if(paymenttype == 'onlinetransfer')
	{
		$('#paymentremarksdiv').hide();
		$('#paymentdetailsdiv').show();
	}
	else if(paymenttype == 'chequeordd')
	{
		$('#paymentremarksdiv').show();
		$('#paymentdetailsdiv').hide();
	}
	else
	{
		$('#paymentremarksdiv').hide();
		$('#paymentdetailsdiv').show();
	}
	/*if($('#paymentremarksdiv').is(':visible'))
	{
			$('#paymentremarksdiv').hide();
	}
	else
	{
		$('#paymentremarksdiv').show();
	}*/
}


function viewinvoice()
{
	if($('#invoivcelist').val() != '')
		$('#onlineslno').val($('#invoivcelist').val()); 
		
	var form = $('#submitform');	
	if($('#onlineslno').val() == '')
	{
		$('#form-error').html(errormessage('Please select a Customer.')); return false;
	}
	else
	{
		$('#submitform').attr("action", "../ajax/viewmatrixinvoicepdf.php") ;
		$('#submitform').attr( 'target', '_blank' );
		$('#submitform').submit();
	}
}

function viewreceipt()
{
	if($('#lastslno').val() != '')
		$('#matrixreceiptno').val($('#lastslno').val());
	if($('#matrixreceiptno').val() == '')
	{
		$('#form-error').html(errormessage('Please select a Customer.')); return false;
	}
	else
	{
		$('#submitform').attr("action", "../ajax/viewreceipt-pdf.php") ;
		$('#submitform').attr( 'target', '_blank' );
		$('#submitform').submit();
	}
	
}

function sendreceipt(type)
{
	var form = $('#submitform');
	if($('#lastslno').val() == '')
	{
		$('#form-error').html(errormessage('Select a Receipt first.')); return false;
	}
	else
	{
		var confirmation = confirm('Are you sure you want to send this receipt?');
		if(confirmation)
		{
			$('#form-error').html(getprocessingimage());
			var passData = "switchtype=sendreceipt&receiptno=" + encodeURIComponent($('#lastslno').val()) +"&type=" + encodeURIComponent(type) + "&dummy=" + Math.floor(Math.random()*10054300000);
			$('#resendprocess').show();
			$('#resendemail').hide();
			$('#resendprocess').html(getprocessingimage());	
			queryString = "../ajax/matrixreceipts.php";
			ajaxcall8 = $.ajax(
			{
				type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
				success: function(ajaxresponse,status)
				{	
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
							$('#resendprocess').hide();
							$('#resendprocess').show();
							$('#form-error').html(successmessage(response[1]));
						}
						else if(response[0] == '2')
						{
							$('#resendprocess').hide();
							$('#resendemail').show();
							$('#form-error').html(errormessage(response[1]));
						}
					}
				}, 
				error: function(a,b)
				{
					$("#form-error").html(scripterror());
				}
			});
		}
	else
		return false;
	}
}

function disablereceptsbutton()
{
	$('#save').attr("disabled", true); 
	$("#save").removeClass('swiftchoicebutton');
	$("#save").addClass('swiftchoicebuttondisabled');
	
	$('#delete').attr("disabled", true); 
	$("#delete").removeClass('swiftchoicebutton');
	$("#delete").addClass('swiftchoicebuttondisabled');
	
	$('#receiptcancellation').attr("disabled", true); 
	$("#receiptcancellation").removeClass('swiftchoicebuttonbignew');
	$("#receiptcancellation").addClass('swiftchoicebuttondisabledbignew');
}

function enablereceptsbutton()
{
	$('#save').attr("disabled", false); 
	$("#save").removeClass('swiftchoicebuttondisabled');
	$("#save").addClass('swiftchoicebutton');
	
	$('#delete').attr("disabled", false); 
	$("#delete").removeClass('swiftchoicebuttondisabled');
	$("#delete").addClass('swiftchoicebutton');
	
	$('#receiptcancellation').attr("disabled", false); 
	$("#receiptcancellation").removeClass('swiftchoicebuttondisabledbignew');
	$("#receiptcancellation").addClass('swiftchoicebuttonbignew');
	
	
}