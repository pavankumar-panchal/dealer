//Function to display records ------------------------------------------
function getinvoicedetails(startlimit)
{
	var form = $('#submitform');
	var startlimit = '';
	var passdata = "switchtype=invoicedetails&startlimit="+ encodeURIComponent(startlimit);
	var queryString = "../ajax/matrixinvoiceregister.php";
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
				var response = ajaxresponse;//alert(response);
				if(response['errorcode'] == '1')
				{
					$('#tabgroupgridwb1').html("Total Count :  " + response['fetchresultcount']);
					$('#tabgroupgridc1_1').html(response['grid']);
					$('#tabgroupgridc1link').html(response['linkgrid']);
					$('#totalinvoices').val(response['totalinvoices']);
					$('#totalsalevalue').val(response['totalsalevalue']);
					$('#totaltax').val(response['totaltax']);
					$('#totalamount').val(response['totalamount']);
					$('#totalsalevalue').attr('title',response['totalsalevalue1']);
					$('#totaltax').attr('title',response['totaltax1']);
					$('#totalamount').attr('title',response['totalamount1']);
					$('#productsummarydiv').html(response['productwisegrid']);
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

//Function for "show more records" or  "show all records" link ------------------------------------------  
function getmoreinvoicedetails(startlimit,slnocount,showtype)
{
	var form = $('#submitform');
	var passdata = "switchtype=invoicedetails&startlimit="+ encodeURIComponent(startlimit) + "&slnocount=" + encodeURIComponent(slnocount) + "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);
	//alert(passData);
	var queryString = "../ajax/matrixinvoiceregister.php";
	$('#tabgroupgridc1link').html(getprocessingimage());
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
			{
				var response = ajaxresponse; 
				if(response['errorcode'] == '1')
				{
					$('#tabgroupgridwb1').html("Total Count :  " + response['fetchresultcount']);
					$('#resultgrid').html($('#tabgroupgridc1_1').html());
					$('#tabgroupgridc1_1').html($('#resultgrid').html().replace(/\<\/table\>/gi,'')+ response['grid']);
					$('#tabgroupgridc1link').html(response['linkgrid']);
					$('#totalinvoices').val(response['totalinvoices']);
					$('#totalsalevalue').val(response['totalsalevalue']);
					$('#totaltax').val(response['totaltax']);
					$('#totalamount').val(response['totalamount']);
					$('#totalsalevalue').attr('title',response['totalsalevalue1']);
					$('#totaltax').attr('title',response['totaltax1']);
					$('#totalamount').attr('title',response['totalamount1']);
					$('#productsummarydiv').html(response['productwisegrid']);
					
					
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

function disabledealer()
{
	var leftdealers =  $('#leftdealerid').val();
	var activedealers =  $('#dealerid').val();
	//alert(leftdealers);
	//alert(activedealers);
	if(leftdealers!= " ")
	{
		$('#dealerid').val('');
		//alert($('#dealerid').val(''));
		$('#dealerid').attr('disabled',true).css('background-color', 'grey');
	}
	else if(activedealers!=" "){
		$('#leftdealerid').val('');
		//alert($('#dealerid').val());
		$('#leftdealerid').attr('disabled',true).css('background-color', 'grey');
	}
	else
	{
		//alert(2);
		$('#dealerid').attr('disabled',false).css('background-color', '');
		$('#leftdealerid').attr('disabled',false).css('background-color', '');

	}

}


//Function to Search the data from Inventory------------------------------------------
function searchfilter(startlimit)
{
	var error = $('#form-error');
	var form = $('#submitform');
	var fromdate = $('#DPC_fromdate').val();
	var field = $('#DPC_fromdate');
	if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
	//if(field.val())	{ if(!checkdate(field.val())) {$('#form-error').html(errormessage('Date is not Valid.'));field.focus(); return false; } }
	var todate = $('#DPC_todate').val();
	var field = $('#DPC_todate');
	if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
	var dealerid = $('#dealerid').val();
	var leftdealerid = $('#leftdealerid').val();
	var field = $('#alltime:checked').val();
	if(field != 'on') var alltimecheck = 'no'; else alltimecheck = 'yes';
	error.html('');
	$('#hiddentotalinvoices').val('');
	$('#hiddentotalsalevalue').val('');
	$('#hiddentotaltax').val('');
	$('#hiddentotalamount').val('');
	var passdata = "switchtype=searchinvoices&fromdate=" + encodeURIComponent(fromdate) + "&todate=" + encodeURIComponent(todate) + "&startlimit=" + encodeURIComponent(startlimit)  + "&dealerid=" + encodeURIComponent(dealerid) + "&leftdealerid=" + encodeURIComponent(leftdealerid) + "&alltimecheck=" +encodeURIComponent(alltimecheck)+ "&dummy=" + Math.floor(Math.random()*1000782200000);//alert(passData)
	error.html(getprocessingimage());
	var queryString = "../ajax/matrixinvoiceregister.php";
	ajaxobjext14 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			error.html('') ;
			var response = ajaxresponse;//alert(response[11]); alert(response[12]);  
			if(response['errorcode'] == '1')
			{
				gridtab2(2,'tabgroupgrid','&nbsp; &nbsp;Search Results'); 
				$('#tabgroupgridwb2').html("Total Count :  " + response['fetchresultcount']);
				$('#tabgroupgridc2_1').html(response['grid']);
				$('#tabgroupgridc2link').html(response['linkgrid']);
				$('#totalinvoices').val(response['totalinvoices']);
				$('#totalsalevalue').val(response['totalsalevalue']);
				$('#totaltax').val(response['totaltax']);
				$('#totalamount').val(response['totalamountfinal']);
				$('#totalsalevalue').attr('title',response['totalsalevalue1']);
				$('#totaltax').attr('title',response['totaltax1']);
				$('#totalamount').attr('title',response['totalamountfinal1']);
				$('#productsummarydiv').html(response['productwisegrid']);
				
				$('#hiddentotalinvoices').val(response['totalinvoices']);
				$('#hiddentotalsalevalue').val(response['totalsalevalue']);
				$('#hiddentotaltax').val(response['totaltax']);
				$('#hiddentotalamount').val(response['totalamountfinal']);
			}
			else
			{
				$('#tabgroupgridc1_1').html("No datas found to be displayed.");
			}
		}, 
		error: function(a,b)
		{
			$("#gridprocess").html(scripterror());
		}
	});	
}

function getmoresearchfilter(startlimit,slnocount,showtype)
{
	var fromdate = $('#DPC_fromdate').val();
	var field = $('#DPC_fromdate');
	if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
	//if(field.val())	{ if(!checkdate(field.val())) {$('#form-error').html(errormessage('Date is not Valid.'));field.focus(); return false; } }
	var todate = $('#DPC_todate').val();
	var field = $('#DPC_todate');
	if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
	var dealerid = $('#dealerid').val();
	var leftdealerid = $('#leftdealerid').val();
	var error = $('#form-error');
	var field = $('#alltime:checked').val();
	if(field != 'on') var alltimecheck = 'no'; else alltimecheck = 'yes';
	$('#hiddentotalinvoices').val('');
	$('#hiddentotalsalevalue').val('');
	$('#hiddentotaltax').val('');
	$('#hiddentotalamount').val('');
	var passdata = "switchtype=searchinvoices&fromdate=" + encodeURIComponent(fromdate) + "&todate=" + encodeURIComponent(todate) + "&startlimit=" + encodeURIComponent(startlimit) + "&dealerid=" + encodeURIComponent(dealerid) + "&leftdealerid=" + encodeURIComponent(leftdealerid) + "&slnocount=" + encodeURIComponent(slnocount) + "&showtype=" + encodeURIComponent(showtype)+ "&alltimecheck=" +encodeURIComponent(alltimecheck) + "&dummy=" + Math.floor(Math.random()*1000782200000);

	$('#tabgroupgridc2link').html(getprocessingimage());
	var queryString = "../ajax/matrixinvoiceregister.php";
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
				$('#totalinvoices').val(ajaxresponse['totalinvoices']);
				$('#totalsalevalue').val(ajaxresponse['totalsalevalue']);
				$('#totaltax').val(ajaxresponse['totaltax']);
				$('#totalamount').val(ajaxresponse['totalamountfinal']);
				$('#totalsalevalue').attr('title',ajaxresponse['totalsalevalue1']);
				$('#totaltax').attr('title',ajaxresponse['totaltax1']);
				$('#totalamount').attr('title',ajaxresponse['totalamountfinal1']);
				$('#productsummarydiv').html(ajaxresponse['productwisegrid']);
				
				$('#hiddentotalinvoices').val(ajaxresponse['totalinvoices']);
				$('#hiddentotalsalevalue').val(ajaxresponse['totalsalevalue']);
				$('#hiddentotaltax').val(ajaxresponse['totaltax']);
				$('#hiddentotalamount').val(ajaxresponse['totalamountfinal']);
			}
			else
			{
				$('#tabgroupgridc2_1').html("No datas found to be displayed.");
			}
		}, 
		error: function(a,b)
		{
			$("#gridprocess").html(scripterror());
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
		$('#submitform').attr("action", "../ajax/matrixinvoiceregistertoexcel.php?id=toexcel") ;
		$('#submitform').submit();
	}
}


//Function to view the bill in pdf format----------------------------------------------------------------
function viewinvoice(slno)
{
	if(slno != '')
		$('#onlineslno').val(slno);
		
	var form = $('#submitform');	
	if($('#onlineslno').val() == '')
	{
		$('#productselectionprocess').html(errormessage('Please select a Customer.')); return false;
	}
	else
	{
		$('#submitform').attr("action", "../ajax/viewmatrixinvoicepdf.php") ;
		$('#submitform').attr( 'target', '_blank' );
		$('#submitform').submit();
	}
}


function displayproductdetails(slno)
{
	if(slno != '')
	{
		var form = $('#detailsform');
		$('#productslno',form).val(slno);
		generateproductdetails();
		$("").colorbox({ inline:true, href:"#productdetailsgrid", onLoad: function() { $('#cboxClose').hide()}});
	}
	else
	{
		return false;
	}
}


function generateproductdetails()
{
	
	var form = $('#detailsform');
	$('#productdetailsgridc1').show();
	//$('#detailsdiv').hide();
	var passdata = "switchtype=productdetailsgrid&productslno="+ encodeURIComponent($('#productslno').val()) ;//alert(passData)
	var queryString = "../ajax/matrixinvoiceregister.php";
	ajaxcall99 = createajax();
	$('#productdetailsgridc1_1').html(getprocessingimage());
	$('#productdetailsgridc1link').html('');
	ajaxobjext14 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,dataType: "json",
		success: function(response,status)
		{	
			var ajaxresponse = response;//alert(ajaxresponse)
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
					$('#productdetailsgridwb1').html("Total Count :  " + response['fetchresultcount']);
					$('#productdetailsgridc1_1').html(response['grid']);
					$('#productdetailsgridc1link').html(response['linkgrid']);
					//$('#invoicelastslno').val(response[4]);
				}
				else
				{
					$('#productdetailsgridc1_1').html("No datas found to be displayed");
				}
			}
		}, 
		error: function(a,b)
		{
			$("#gridprocess").html(scripterror());
		}
	});	
}


function displayinvoicetotal()
{
	$('#totalinvoices').val($('#hiddentotalinvoices').val());
	$('#totalsalevalue').val($('#hiddentotalsalevalue').val());
	$('#totaltax').val($('#hiddentotaltax').val());
	$('#totalamount').val($('#hiddentotalamount').val());
}



function displayproductwisesummary(elementid,imgname)
{
	if($('#'+ elementid).is(':visible'))
	{
		$('#'+ elementid).hide();
		if($('#'+ imgname))
			$('#'+ imgname).attr('src',"../images/plus.jpg");
	}
	else
	{
		$('#'+ elementid).show();
		if($('#'+ imgname))
			$('#'+ imgname).attr('src',"../images/minus.jpg");
		
	}
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