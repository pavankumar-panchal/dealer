var customerarray = new Array();
var customerarray1 = new Array();
var customerarray2 = new Array();
var customerarray3 = new Array();
var customerarray4 = new Array();

var process1 = false;
var process2 = false;
var process3 = false;
var process4 = false;

function formsubmit(command)
{
	var form = $('#submitform');
	var error = $('#form-error');
	var field = $('#remarks');
	if(!field.val()) { error.html(errormessage("Enter the Remarks. ")); field.focus(); return false; }
	else
	{
		var passData = "";
		if(command == 'save')
		{
			passdata =  "switchtype=save&customerreference=" + encodeURIComponent($('#customerlist').val()) + "&remarks=" + encodeURIComponent($('#remarks').val()) + "&interactiontype=" + encodeURIComponent($('#interaction').val()) +  "&cusinteractionslno=" + encodeURIComponent($('#cusinteractionslno').val()) +  "&lastslno=" + encodeURIComponent($('#lastslno').val())+ "&dummy=" + Math.floor(Math.random()*100000000)//;alert(passdata);
			
		}
		else
		{
			passdata =  "switchtype=delete&lastslno=" + encodeURIComponent($('#lastslno').val()) + "&lastslno=" + encodeURIComponent($('#lastslno').val()) + "&dummy=" + Math.floor(Math.random()*10000000000);
		}
		queryString = '../ajax/dealerinteraction.php';
		error.html(getprocessingimage());
		ajaxobjext14 = $.ajax(
		{
			type: "POST",url: queryString, data: passdata, cache: false,dataType: "json",
			success: function(ajaxresponse,status)
			{	
					if(ajaxresponse == 'Thinking to redirect')
					{
						window.location = "../logout.php";
						return false;
					}
					else
					var response = ajaxresponse;
					if(response['errorcode'] == '1')
					{
						error.html(successmessage(response['errorcodemsg']));
						cusinteractiondatagrid('');
						newentry();
					}
					else if(response['errorcode'] == '2')
					{
						error.html(successmessage(response['errorcodemsg']));
						cusinteractiondatagrid('');
						newentry();
					}
					else
					{
						error.html(errormessage('Unable to connect....'));
					}
			}, 
			error: function(a,b)
			{
				error.html(scripterror());
			}
		});	
	}
}


function gettotalcustomercount()
{
	var form = $('#customerselectionprocess');
	var passData = "switchtype=getcustomercount&dummy=" + Math.floor(Math.random()*10054300000);
	queryString = "../ajax/customer.php";
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
	var startindex = 0;
	var startindex1 = (limit)+1;
	var startindex2 = (limit*2)+1;
	var startindex3 = (limit*3)+1;
	var passData = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex);
	var passData1 = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex1);
	var passData2 = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex2);
	var passData3 = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex3);
	$('#customerselectionprocess').html(getprocessingimage());
	queryString = "../ajax/dealerinteraction.php";
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
	
	queryString = "../ajax/dealerinteraction.php";
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

	queryString = "../ajax/dealerinteraction.php";
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
	
	queryString = "../ajax/dealerinteraction.php";
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
		var flag = true;
		$("#customerselectionprocess").html(successsearchmessage('All Customers...'));
		getcustomerlist1();
	}
	else
	return false;
}


function getcustomerlist1()
{	
	disableformelemnts();
	var form = $('#submitform');
	var selectbox = $('#customerlist');
	var numberofcustomers = customerarray.length;
	$('#detailsearchtext').focus();
	var actuallimit = 500;
	var limitlist = (numberofcustomers > actuallimit)?actuallimit:numberofcustomers;

	$('option', selectbox).remove();
	var options = selectbox.attr('options');

	for( var i=0; i<limitlist; i++)
	{
		var splits = customerarray[i].split("^");
		options[options.length] = new Option(splits[0], splits[1]);
	}
	
}
function newentry()
{
	var form = $('#submitform');
	form[0].reset();
	$('#lastslno').val('');
	enablesave();
	$('#enteredthrough').html('');
}

function gridtoform(slno)
{
	if(slno != '')
	{
		var form = $('#submitform');
		var error = $('#form-error');
		error.html('');
		$('#lastslno').val(slno);
		var passdata = "switchtype=gridtoform&cusinteractionslno=" + encodeURIComponent(slno) + "&dummy=" + Math.floor(Math.random()*100032680100);
		$('#productselectionprocess').html(getprocessingimage());
		var queryString = "../ajax/dealerinteraction.php";
		ajaxcall3 = $.ajax(
		{
			type: "POST",url: queryString, data: passdata, cache: false,dataType: "json",
			success: function(ajaxresponse,status)
			{	
				$('#productselectionprocess').html('');
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				var response = ajaxresponse;//alert(response)
				if(response['errorcode'] == '1')
				{
					
					$('#displaycustomername').html(response['businessname']);
					$('#interactiondate').html(response['createddate']);
					$('#displayenteredby').html(response['dealerbusinessname']);
					$('#remarks').val(response['remarks']);
					$('#cusinteractionslno').val(response['slno']);
					$('#enteredthrough').html(response['modulename']);
					$('#interaction').val(response['interactiontype']);
					enablesave();
				}
				else if(response['errorcode'] == '2')
				{
					$('#displaycustomername').html(response['businessname']);
					$('#interactiondate').html(response['createddate']);
					$('#displayenteredby').html(response['dealerbusinessname']);
					$('#remarks').val(response['remarks']);
					$('#cusinteractionslno').val(response['slno']);
					$('#enteredthrough').html(response['modulename']);
					$('#interaction').val(response['interactiontype']);
					disablesave();
				}
				else if(response['errorcode'] == '3')
				{	
					disablesave();
					$('#displaycustomername').html(response['businessname']);
					$('#interactiondate').val(response['createddate']);
					$('#displayenteredby').html(response['dealerbusinessname']);
					$('#remarks').val(response['remarks']);
					$('#cusinteractionslno').val(response['slno']);
					$('#enteredthrough').val(response['modulename']);
					$('#interaction').val(response['interactiontype']);
					
				}
			}, 
			error: function(a,b)
			{
				$("#productselectionprocess").html(scripterror());
			}
		});	
	}
}

function cusinteractiondatagrid(startlimit)
{
	var passdata = "switchtype=generategrid&customerreference=" + encodeURIComponent($('#customerlist').val()) + "&startlimit=" + encodeURIComponent(startlimit)+ "&dummy=" + Math.floor(Math.random()*10054300000);
	//alert(passData);
	$('#tabgroupgridc1_1').html(getprocessingimage());
	queryString = "../ajax/dealerinteraction.php";
	ajaxcall2 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			$('#tabgroupgridc1_1').html('');
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse;
				if(response['errorcode'] == 1)
				{
					$('#tabgroupgridwb1').html("Total Count :  " + response['fetchresultcount']);
					$('#tabgroupgridc1_1').html(response['grid']);//alert(response[1]);
					$('#tabgroupgridc1link').html(response['linkgrid']);
				}
				else if(response['errorcode'] == 2)
				{
					$('#tabgroupgridc1link').html('');
					$('#tabgroupgridwb1').html('');
					$('#tabgroupgridc1_1').html(response['grid']);	
				}
			}
		}, 
		error: function(a,b)
		{
			$("#tabgroupgridc1_1").html(scripterror());
		}
	});	
}

//Function to "Show more records" or "Show all records" of Customer module
function cusmoregrid(startlimit,slno,showtype)
{
	var passdata = "switchtype=generategrid&startlimit=" + encodeURIComponent(startlimit)+ "&slno=" + encodeURIComponent(slno)+ "&customerreference=" + encodeURIComponent($('#customerlist').val()) + "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);//alert(passData);
	queryString = "../ajax/dealerinteraction.php";
	ajaxobjext17 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,dataType: "json",
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
					$('#tabgroupgridwb1').html("Total Count :  " + response['fetchresultcount']);
					$('#custresultgrid').html($('#tabgroupgridc1_1').html());
					$('#tabgroupgridc1_1').html($('#custresultgrid').html().replace('</table>','')+ response['grid']);
					$('#tabgroupgridc1link').html(response['linkgrid']);
				}
				else
				if(response['errorcode'] == '2')
				{
					$('#tabgroupgridc1link').html('');
					$('#tabgroupgridwb1').html('');
					$('#tabgroupgridc1_1').html(response['grid']);	
				}
			}
		}, 
		error: function(a,b)
		{
			$("#tabgroupgridc1_1").html(scripterror());
		}
	});	
}


function displaycustomername()
{
	var passdata = "switchtype=displaycustomer&customerreference=" + encodeURIComponent($('#customerlist').val()) + "&dummy=" + Math.floor(Math.random()*10054300000);//alert(passData)
	queryString = "../ajax/dealerinteraction.php";
	ajaxobjext141 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse;// alert(response)
				$('#displaycustomername').html(response['businessname']);
				$('#cusinteractionslno').val(response['customerreference']);
			}
		}, 
		error: function(a,b)
		{
			$("#displaycustomername").html(scripterror());
		}
	});	
}


function selectfromlist()
{
	var selectbox = $('#customerlist');
	var cusnamesearch = $('#detailsearchtext');
	cusnamesearch.val($('#customerlist option:selected').text());
	cusnamesearch.select();
	displaycustomername();
	newentry();
	$('#form-error').html('');
	cusinteractiondatagrid('');
	enableformelemnts();
	
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
			if(pattern.test(trimdotspaces(customerarray[i]).toLowerCase()))
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
	selectfromlist();
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


function getcurrentdate()
{
	var passData = "&switchtype=getcurrentdate&dummy=" + Math.floor(Math.random()*10054300000);//alert(passData)
	var ajaxca11 = createajax();
	queryString = "../ajax/dealerinteraction.php";
	ajaxca11.open("POST", queryString, true);
	ajaxca11.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxca11.onreadystatechange = function()
	{
		if(ajaxca11.readyState == 4)
		{
			if(ajaxca11.status == 200)
			{
				var ajaxresponse = ajaxca11.responseText;//alert(response)
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					$('#interactiondate').html(ajaxresponse);
				}
			}
		}
	}
	ajaxca11.send(passData);
}