//Function to display records ------------------------------------------
function getinvoicedetails(startlimit)
{
	var form = $('#submitform');
	var startlimit = '';
	var passdata = "switchtype=invoicedetails&startlimit="+ encodeURIComponent(startlimit);
	var queryString = "../ajax/outstandingregister.php";
	$('#tabgroupgridc1_1').html(getprocessingimage());
	$('#tabgroupgridc1link').html('');
	ajaxcall3 = $.ajax(
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
					$('#tabgroupgridc1_1').html(response['grid']);
					$('#tabgroupgridc1link').html(response['linkgrid']);
					$('#totalinvoices').val(response['totalinvoices']);
					$('#totaloutstanding').val(response['totalamount']);
					$('#totaloutstanding').attr('title',response['totalamount1']);
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

//Function for "show more records" or  "show all records" link ------------------------------------------  
function getmoreinvoicedetails(startlimit,slnocount,showtype)
{
	var form = $('#submitform');
	var passdata = "switchtype=invoicedetails&startlimit="+ encodeURIComponent(startlimit) + "&slnocount=" + encodeURIComponent(slnocount) + "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);
	//alert(passData);
	var queryString = "../ajax/outstandingregister.php";
	$('#tabgroupgridc1link').html(getprocessingimage());
	ajaxcall14 = $.ajax(
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
					$('#resultgrid').html($('#tabgroupgridc1_1').html());
					$('#tabgroupgridc1_1').html($('#resultgrid').html().replace(/\<\/table\>/gi,'')+ response['grid']);
					$('#tabgroupgridc1link').html(response['linkgrid']);
					$('#totalinvoices').val(response['totalinvoices']);
					$('#totaloutstanding').val(response['totalamount']);
					$('#totaloutstanding').attr('title',response['totalamount1']);
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


//Function to Search the data from Inventory------------------------------------------
function searchfilter(startlimit)
{
	var error = $('#form-error');
	var form = $('#submitform');
	var fromdate = $('#DPC_fromdate').val();
	var field = $('#DPC_fromdate');
	if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
	//if(field.val())	{ if(!checkdate(field.val())) {$('#form-error').html('Date is not Valid.');field.focus(); return false; } }
	var sortby = $('#sortby').val();
	var sortby1 = $('#sort').val();
	var field = $('#aged');
	var dealerid = $('#dealerid').val();
	if(!field.val()) {error.html(errormessage('Please enter the number of Days')); field.focus(); return false;  }
	if(field.val())	{ if(!validatepositivenumbers(field.val())) { error.html(errormessage('Days are not valid.')); field.focus(); return false; } }
	error.html('');
	$('#hiddentotalinvoices').val('');
	$('#hiddentotaloutstanding').val('');
	var passdata = "switchtype=searchinvoices&fromdate=" + encodeURIComponent(fromdate) + "&startlimit=" + encodeURIComponent(startlimit) + "&sortby=" + encodeURIComponent(sortby) + "&aged=" + encodeURIComponent($('#aged').val()) + "&sortby1=" + encodeURIComponent(sortby1) + "&dealerid=" + encodeURIComponent(dealerid) + "&dummy=" + Math.floor(Math.random()*1000782200000); 
	ajaxcall9 = createajax();
	error.html(getprocessingimage());
	var queryString = "../ajax/outstandingregister.php";
	ajaxobjext14 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			error.html('') ;
			var response = ajaxresponse;
			if(response['errorcode'] == '1')
			{
				gridtab2(2,'tabgroupgrid','&nbsp; &nbsp;Search Results');
				$('#tabgroupgridwb2').html("Total Count :  " + response['fetchresultcount']);
				$('#tabgroupgridc2_1').html(response['grid']);
				$('#tabgroupgridc2link').html(response['linkgrid']);
				$('#totalinvoices').val(response['totalinvoices']);
				$('#totaloutstanding').val(response['totalamount']);
				$('#totaloutstanding').attr('title',response['totalamount1']);
				
				$('#hiddentotalinvoices').val(response['totalinvoices']);
				$('#hiddentotaloutstanding').val(response['totalamount']);
			}
			else
			{
				$('#tabgroupgridc1_1').html("No datas found to be displayed.");
			}
		}, 
		error: function(a,b)
		{
			$("#tabgroupgridc1_1").html(scripterror());
		}
	});	
}

function getmoresearchfilter(startlimit,slnocount,showtype)
{
	var fromdate = $('#DPC_fromdate').val();
	var sortby = $('#sortby').val();
	var sortby1 = $('#sort').val();
	var error = $('#form-error');
	var field = $('#aged');
	if(!field.val()) { error.html(errormessage("Enter the Days.")); field.focus(); return false; }
	if(field.val())	{ if(!validateamount(field.val())) { error.html(errormessage('Days are not valid.')); field.focus(); return false; } }
	var dealerid = $('#dealerid').val();
	$('#hiddentotalinvoices').val('');
	$('#hiddentotaloutstanding').val('');
	var passData = "switchtype=searchinvoices&fromdate=" + encodeURIComponent(fromdate) + "&sortby=" + encodeURIComponent(sortby) + "&aged=" + encodeURIComponent($('#aged').val())+ "&sortby1=" + encodeURIComponent(sortby1) + "&startlimit=" + encodeURIComponent(startlimit) + "&slnocount=" + encodeURIComponent(slnocount)+ "&dealerid=" + encodeURIComponent(dealerid) + "&showtype=" + encodeURIComponent(showtype) + "&dummy=" + Math.floor(Math.random()*1000782200000);

	$('#tabgroupgridc2link').html(getprocessingimage());
	var queryString = "../ajax/outstandingregister.php";
	ajaxobjext14 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
		success: function(response,status)
		{	
			error.html('');
			var ajaxresponse = response;
			if(ajaxresponse['errorcode'] == '1')
			{
				$('#tabgroupgridwb2').html("Total Count :  " + ajaxresponse['fetchresultcount']);
				$('#searchresultgrid').html($('#tabgroupgridc2_1').html());
				$('#tabgroupgridc2_1').html($('#searchresultgrid').html().replace(/\<\/table\>/gi,'')+ ajaxresponse['grid']);
				$('#tabgroupgridc2link').html(ajaxresponse['linkgrid']);
				$('#totalinvoices').val(ajaxresponse['totalinvoices']);
				$('#totaloutstanding').val(ajaxresponse['totalamount']);
				$('#totaloutstanding').attr('title',ajaxresponse['totalamount1']);
				$('#hiddentotalinvoices').val(ajaxresponse['totalinvoices']);
				$('#hiddentotaloutstanding').val(ajaxresponse['totalamount']);
			}
			else
			{
				$('#tabgroupgridc2_1').html("No datas found to be displayed.");
			}
		}, 
		error: function(a,b)
		{
			$("#tabgroupgridc2_1").html(scripterror());
		}
	});	
}

function filtertoexcel(command)
{
	var form = $('#submitform');
	var error = $('#form-error');
	error.html(getprocessingimage());
	if(command == 'toexcel')
	{
		error.html('');
		$('#submitform').attr("action", "../ajax/outstandingregistertoexcel.php?id=toexcel") ;
		$('#submitform').submit();
	}
}


function checkdate(datevalue) //dd-mm-yyyy Eg: 01-04-2008
{
 if(datevalue.length == 10)
 {
  if(isanumber(datevalue.charAt(0)) && isanumber(datevalue.charAt(1)) && isanumber(datevalue.charAt(3)) && isanumber(datevalue.charAt(4)) && isanumber(datevalue.charAt(6)) && isanumber(datevalue.charAt(7)) && isanumber(datevalue.charAt(8)) && isanumber(datevalue.charAt(9)) && datevalue.charAt(2) == '-' && datevalue.charAt(5) == '-')
   return true;
  else
   return false;
 }
 else
  return false;
}


function displayoutstdingtotal()
{
	$('#totalinvoices').val($('#hiddentotalinvoices').val());
	$('#totaloutstanding').val($('#hiddentotaloutstanding').val());
}