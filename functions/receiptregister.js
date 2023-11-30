//Function to display records ------------------------------------------
function getinvoicedetails(startlimit)
{
	var form = $('#submitform');
	var startlimit = '';
	var passdata = "switchtype=receiptdetails&startlimit="+ encodeURIComponent(startlimit);
	var queryString = "../ajax/receiptregister.php";
	$('#tabgroupgridc1_1').html(getprocessingimage());
	$('#tabgroupgridc1link').html('');
	ajaxcall3 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,dataType: "json",
		success: function(response,status)
		{	
			var ajaxresponse = response;
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
					$('#totalreceipts').val(response['totalreceipts']);
					$('#totalamount').val(response['totalamount']);
					$('#totalamount').attr('title',response['totalamount1']);
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
	var passdata = "switchtype=receiptdetails&startlimit="+ encodeURIComponent(startlimit) + "&slnocount=" + encodeURIComponent(slnocount) + "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);
	//alert(passData);
	var queryString = "../ajax/receiptregister.php";
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
					$('#totalreceipts').val(response['totalreceipts']);
					$('#totalamount').val(response['totalamount']);
					$('#totalamount').attr('title',response['totalamount1']);
				}
				else
				{
					$('#tabgroupgridc1_1').html("No datas found to be displayed.");
				}
			}
		}, 
		error: function(a,b)
		{
			$("#gridprocess").html(scripterror());
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
	//if(field.val())	{ if(!checkdate(field.val())) {  alert('2'); $('#form-error').html(errormessage('Date is not Valid.'));field.focus(); return false; } }
	var todate = $('#DPC_todate').val();
	var field = $('#DPC_todate');
	if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
//	if(field.val())	{ if(!checkdate(field.val())) {$('#form-error').html(errormessage('Date is not Valid.'));field.focus(); return false; } }
	var paymentmode = $('#paymentmode').val();
	if($('#logintype').val() == 'branchhead')
	{
		
		var values = validateproductcheckboxes();
		var itemvalues = validateitemcheckboxes();
		if((values == false))
		{
			if(itemvalues == false)
			{
				$('#form-error').html(errormessage("Select A Product/Item")); return false;	
			}
		}
		var textfield = $("#searchcriteria").val();
		var subselection = $("input[name='databasefield']:checked").val();
		var c_value = '';
		var newvalue = new Array();
		var chks = $("input[name='productarray[]']");
		for (var i = 0; i < chks.length; i++)
		{
			if ($(chks[i]).is(':checked'))
			{
				c_value += $(chks[i]).val() + ',';
			}
		}
		
		var productslist = c_value.substring(0,(c_value.length-1));
		var listchks_value = '';
		var listchks = $("input[name='itemarray[]']");
		for (var i = 0; i < listchks.length; i++)
		{
			if ($(listchks[i]).is(':checked'))
			{
				listchks_value += $(listchks[i]).val() + ',';
			}
		}
		
		var itemlist = listchks_value.substring(0,(listchks_value.length-1));
		
		var field = $('#cancelledinvoice:checked').val();
		if(field != 'on') var cancelledinvoice = 'no'; else cancelledinvoice = 'yes';
		var status = $("#status").val();
		var receiptstatus = $("#receiptstatus").val();
		var reconsilation = $("#reconsilation").val();
	}
	else
	{
		var subselection = "";
		var textfield = "";
		var productslist = "";
		var cancelledinvoice = "";
		var status = "";
		var receiptstatus = "";
		var itemlist = "";
		var reconsilation = "";
	}
	var dealerid = $('#dealerid').val();
	var field = $('#alltime:checked').val();
	if(field != 'on') var alltimecheck = 'no'; else alltimecheck = 'yes';
	error.html('');
	$('#hiddentotalreceipts').val('');
	$('#hiddentotalsalevalue').val('');
	var passdata = "switchtype=searchinvoices&fromdate=" + encodeURIComponent(fromdate) + "&todate=" + encodeURIComponent(todate)+ "&paymentmode=" + encodeURIComponent(paymentmode)+ "&dealerid=" + encodeURIComponent(dealerid) +"&databasefield=" + encodeURIComponent(subselection)+ "&startlimit=" + encodeURIComponent(startlimit) + "&alltimecheck=" +encodeURIComponent(alltimecheck)+ "&textfield=" +encodeURIComponent(textfield) +  "&productscode=" +encodeURIComponent(productslist)  + "&status=" +encodeURIComponent(status)+ "&cancelledinvoice=" +encodeURIComponent(cancelledinvoice) + "&receiptstatus=" +encodeURIComponent(receiptstatus) + "&itemlist=" + encodeURIComponent(itemlist) + "&reconciletype=" + encodeURIComponent(reconsilation)+ "&dummy=" + Math.floor(Math.random()*1000782200000); //alert(passdata)
	ajaxcall9 = createajax();
	error.html(getprocessingimage());
	var queryString = "../ajax/receiptregister.php";
	ajaxcall9 = $.ajax(
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
				$('#totalreceipts').val(response['totalreceipts']);
				$('#totalamount').val(response['totalamount']);
				$('#totalamount').attr('title',response['totalamount1']);
				
				$('#hiddentotalreceipts').val(response['totalreceipts']);
				$('#hiddentotalsalevalue').val(response['totalamount']);
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
	var field = $('#DPC_fromdate');
	if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
	var todate = $('#DPC_todate').val();
	var field = $('#DPC_todate');
	if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
	var paymentmode = $('#paymentmode').val();
	
	if($('#logintype').val() == 'branchhead')
	{
		var values = validateproductcheckboxes();
		var itemvalues = validateitemcheckboxes();
		if((values == false))
		{
			if(itemvalues == false)
			{
				$('#form-error').html(errormessage("Select A Product/Item")); return false;	
			}
		}
		var textfield = $("#searchcriteria").val();
		var subselection = $("input[name='databasefield']:checked").val();
		var c_value = '';
		var newvalue = new Array();
		var chks = $("input[name='productarray[]']");
		for (var i = 0; i < chks.length; i++)
		{
			if ($(chks[i]).is(':checked'))
			{
				c_value += $(chks[i]).val() + ',';
			}
		}
		
		var productslist = c_value.substring(0,(c_value.length-1));
		var listchks_value = '';
		var listchks = $("input[name='itemarray[]']");
		for (var i = 0; i < listchks.length; i++)
		{
			if ($(listchks[i]).is(':checked'))
			{
				listchks_value += $(listchks[i]).val() + ',';
			}
		}
		
		var itemlist = listchks_value.substring(0,(listchks_value.length-1));
		
		var field = $('#cancelledinvoice:checked').val();
		if(field != 'on') var cancelledinvoice = 'no'; else cancelledinvoice = 'yes';
		var status = $("#status").val();
		var receiptstatus = $("#receiptstatus").val();
		var reconsilation = $("#reconsilation").val();
	}
	else
	{
		var subselection = "";
		var textfield = "";
		var productslist = "";
		var cancelledinvoice = "";
		var status = "";
		var receiptstatus = "";
		var itemlist = "";
		var reconsilation = "";
	}
	
	var dealerid = $('#dealerid').val();
	var error = $('#form-error');
	var field = $('#alltime:checked').val();
	if(field != 'on') var alltimecheck = 'no'; else alltimecheck = 'yes';
	$('#hiddentotalreceipts').val('');
	$('#hiddentotalsalevalue').val('');
	var passdata = "switchtype=searchinvoices&fromdate=" + encodeURIComponent(fromdate) + "&todate=" + encodeURIComponent(todate)+ "&paymentmode=" + encodeURIComponent(paymentmode) + "&dealerid=" + encodeURIComponent(dealerid) +"&databasefield=" + encodeURIComponent(subselection)+ "&startlimit=" + encodeURIComponent(startlimit) + "&slnocount=" + encodeURIComponent(slnocount) + "&showtype=" + encodeURIComponent(showtype) + "&alltimecheck=" +encodeURIComponent(alltimecheck) + "&textfield=" +encodeURIComponent(textfield) +  "&productscode=" +encodeURIComponent(productslist)  + "&status=" +encodeURIComponent(status)+ "&cancelledinvoice=" +encodeURIComponent(cancelledinvoice) + "&receiptstatus=" +encodeURIComponent(receiptstatus) + "&itemlist=" + encodeURIComponent(itemlist) + "&reconciletype=" + encodeURIComponent(reconsilation)+ "&dummy=" + Math.floor(Math.random()*1000782200000);

	ajaxcall10 = createajax();
	$('#tabgroupgridc2link').html(getprocessingimage());
	var queryString = "../ajax/receiptregister.php";
	ajaxcall10 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,dataType: "json",
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
				$('#totalreceipts').val(ajaxresponse['totalreceipts']);
				$('#totalamount').val(ajaxresponse['totalamount']);
				$('#totalamount').attr('title',ajaxresponse['totalamount1']);
				
				$('#hiddentotalreceipts').val(ajaxresponse['totalreceipts']);
				$('#hiddentotalsalevalue').val(ajaxresponse['totalamount']);
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
		if($('#logintype').val() == 'branchhead')
		{
			var values = validateproductcheckboxes();
			var itemvalues = validateitemcheckboxes();
			if((values == false))
			{
				if(itemvalues == false)
				{
					$('#form-error').html(errormessage("Select A Product/Item")); return false;	
				}
			}
		}
		$('#submitform').attr("action", "../ajax/receiptregistertoexcel.php?id=toexcel") ;
		$('#submitform').submit();
	}
}


function displayreceipttotal()
{
	$('#totalreceipts').val($('#hiddentotalreceipts').val());
	$('#totalamount').val($('#hiddentotalamount').val());
}

function disablethedates()
{
	if($('#alltime').is(':checked'))
	{
		$('#DPC_fromdate').attr('disabled',true);	
		$('#DPC_todate').attr('disabled',true);
		$('#DPC_fromdate').removeClass('swifttext-mandatory');
		$('#DPC_todate').removeClass('swifttext-mandatory');
		$('#DPC_fromdate').addClass('swifttext-mandatory-register');
		$('#DPC_todate').addClass('swifttext-mandatory-register');
	}
	else
	{
		$('#DPC_fromdate').attr('disabled',false);	
		$('#DPC_todate').attr('disabled',false);
		$('#DPC_fromdate').removeClass('swifttext-mandatory-register');
		$('#DPC_todate').removeClass('swifttext-mandatory-register');
		$('#DPC_fromdate').addClass('swifttext-mandatory');
		$('#DPC_todate').addClass('swifttext-mandatory');
	}
}
function displayDiv()
{
	if($('#filterdiv').is(':visible'))
		$("#filterdiv").hide();
	else
		$("#filterdiv").show();
}

function selectdeselectall(showtype)
{
	var selectproduct = $('#selectproduct');
	var chkvalues = $("input[name='productarray[]']");
	if(showtype == 'one')
	{
		for (var i=0; i < chkvalues.length; i++)
		{
			if($(chkvalues[i]).is(':checked'))
			{
				$(chkvalues[i]).attr('checked',false);
			}
			if( $('#selectproduct').val() == 'ALL')
				$(chkvalues[i]).attr('checked',true);
			else if(selectproduct.value == 'NONE')
				$(chkvalues[i]).attr('checked',true);
			else if(chkvalues[i].getAttribute('producttype') == $('#selectproduct').val())
			{
				$(chkvalues[i]).attr('checked',true);
				$('#groupvalue').val($("#selectproduct option:selected").val());
			}
		}
	}else if(showtype == 'more')
	{

		var addproductvalue = $("#selectproduct option:selected").val();
		if($('#groupvalue').val() == '')
			$('#groupvalue').val($('#groupvalue').val() +  addproductvalue);
		else
			$('#groupvalue').val($('#groupvalue').val() + '%' +  addproductvalue);
		for (var i=0; i < chkvalues.length; i++)
		{
			if($(chkvalues[i]).is(':checked'))
			{
				$(chkvalues[i]).attr('checked',false);
			}

			var var1 = $('#groupvalue').val().split('%');
			for( var j=0; j<var1.length; j++)
			{
				if($('#selectproduct').val() == 'ALL')
					$(chkvalues[i]).attr('checked',true);
				else if($('#selectproduct').val() == 'NONE')
					$(chkvalues[i]).attr('checked',false);
				if(chkvalues[i].getAttribute('producttype') == var1[j])
				{
					$(chkvalues[i]).attr('checked',true);
				}
			}
		}
	}
}

function validateproductcheckboxes()
{
var chksvalue = $("input[name='productarray[]']");
var hasChecked = false;
for (var i = 0; i < chksvalue.length; i++)
{
	if ($(chksvalue[i]).is(':checked'))
	{
		hasChecked = true;
		return true
	}
}
	if (!hasChecked)
	{
		return false
	}
}

function validateitemcheckboxes()
{
var chks_value = $("input[name='itemarray[]']");
var h_Checked = false;
for (var i = 0; i < chks_value.length; i++)
{
	if ($(chks_value[i]).is(':checked'))
	{
		h_Checked = true;
		return true
	}
}
	if (!h_Checked)
	{
		return false
	}
}