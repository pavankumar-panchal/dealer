var totalarray = new Array();
var customerarray = new Array();
var customerarray1 = new Array();
var customerarray2 = new Array();
var customerarray3 = new Array();
var customerarray4 = new Array();

var process1 = false;
var process2 = false;
var process3 = false;
var process4 = false;

function gettotalcustomercount()
{
	var form = $('#customerselectionprocess');
	var passData = "switchtype=getcustomercount&dummy=" + Math.floor(Math.random()*10054300000);
	queryString = "../ajax/implementation.php";
	$('#customerselectionprocess').html(getprocessingimage());
	ajaxcall2356 = $.ajax(
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
				$('#customerselectionprocess').html('');
				var response = ajaxresponse;//alert(ajaxresponse)
				if(response == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				$('#totalcount').html(response['count']);
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
	var passData = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex);//alert(passData)
	var passData1 = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex1);//alert(passData1)
	var passData2 = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex2);//alert(passData2)
	var passData3 = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex3);//alert(passData3)
	$('#customerselectionprocess').html(getprocessingimage());
	queryString = "../ajax/implementation.php";
	ajaxcall2235 = $.ajax(
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
	
	queryString = "../ajax/implementation.php";
	ajaxcall333 = $.ajax(
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

	queryString = "../ajax/implementation.php";
	ajaxcall4444 = $.ajax(
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
	
	queryString = "../ajax/implementation.php";
	ajaxcall555 = $.ajax(
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
		getcustomerlist1();
		$('#customerselectionprocess').html(successsearchmessage('All Customers...'));
	}
	else
	return false;
}

function getcustomerlist1()
{	
	var form = $("#submitform");
	var selectbox = $('#customerlist');
	var numberofcustomers = customerarray.length;
	$("#detailsearchtext").focus();
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


function selectfromlist()
{
	var selectbox = $("#customerlist option:selected").val();
	$('#detailsearchtext').val($("#customerlist option:selected").text());
	$('#detailsearchtext').select();
	$('#filterdiv').hide();
	clearentry();
	$('#assigndiv').hide();
	checkvalidate();
	validatecustomer(selectbox);
	//enableformelemnts();
	//customerdetailstoform(selectbox);
	//invoicedetails(selectbox);
}

// function selectacustomer(input)
// {
// 	var selectbox = $('#customerlist');
// 	if(input == "")
// 		{
// 			getcustomerlist1();
// 		}
// 		else
// 		{
// 			$('option', selectbox).remove();
// 			var options = selectbox.attr('options');
// 			var addedcount = 0;
// 			for( var i=0; i < customerarray.length; i++)
// 			{
// 				if(input.charAt(0) == "%")
// 				{
// 					withoutspace = input.substring(1,input.length);
// 					pattern = new RegExp(withoutspace.toLowerCase());
// 					comparestringsplit = customerarray[i].split("^");
// 					comparestring = comparestringsplit[1];
// 				}
// 				else
// 				{
// 					pattern = new RegExp("^" + input.toLowerCase());
// 					comparestring = customerarray[i];
// 				}
// 				var result1 = pattern.test(trimdotspaces(customerarray[i]).toLowerCase());
// 				var result2 = pattern.test(customerarray[i].toLowerCase());
// 				if(result1 || result2)
// 				{
// 					var splits = customerarray[i].split("^");
// 					options[options.length] = new Option(splits[0], splits[1]);
// 					addedcount++;
// 					if(addedcount == 100)
// 						break;
// 				}
// 			}
// 		}
// }

function selectacustomer(input)
{
	var selectbox = $('#customerlist');
	if(flag == true)
	{
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
	else if(flag == false)
	{
		if(input == "")
		{
			searchcustomerlist1();
		}
		else
		{
			$('option', selectbox).remove();
			var options = selectbox.attr('options');
			
			var addedcount = 0;
			for( var i=0; i < customersearcharray.length; i++)
			{
					if(input.charAt(0) == "%")
					{
						withoutspace = input.substring(1,input.length);
						pattern = new RegExp(withoutspace.toLowerCase());
						comparestringsplit = customersearcharray[i].split("^");
						comparestring = comparestringsplit[1];
					}
					else
					{
						pattern = new RegExp("^" + input.toLowerCase());
						comparestring = customersearcharray[i];
					}
				if(pattern.test(customersearcharray[i].toLowerCase()))
				{
					var splits = customersearcharray[i].split("^");
					options[options.length] = new Option(splits[0], splits[1]);
					addedcount++;
					if(addedcount == 100)
						break;
				}
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


function searchbycustomeridevent(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 13)
	{
		var input = $('#searchcustomerid').val();
		searchbycustomerid(input);
	}
}

//Function to make the display as block as well as none-------------------------------------------------------------
function divdisplay(elementid,imgname)
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

//Customer details to form
function customerdetailstoform(cusid)
{
	if(cusid != '')
	{
		$('#customerselectionprocess').html('');
		var passData = "switchtype=customerdetailstoform&lastslno=" + encodeURIComponent(cusid) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
		$('#displaycompanyname').html(getprocessingimage());
		var queryString = "../ajax/implementation.php";
		ajaxobjext985 = $.ajax(
		{
			type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
			success: function(ajaxresponse,status)
			{	
					$('#form-error').html('');
					var response = ajaxresponse;//alert(response)
					if(response == 'Thinking to redirect')
					{
						window.location = "../logout.php";
						return false;
					}
					else if(response['errorcode'] == '1')
					{
						$('#customerid').val(response['slno']);
						$('#displaycustomerid').html(response['customerid']);
						$('#displaycompanyname').html('<strong>'+response['companyname']+'</strong>');
						$('#displaycontactperson').html(response['contactvalues']);
						$('#displayaddress').html(response['address']);
						$('#displayphone').html(response['phone']);
						$('#displaycell').html(response['cell']);
						$('#displayemail').html(response['emailidplit']);
						$('#displayregion').html(response['region']);
						$('#displaybranch').html(response['branch']);
						if(response['businesstype'] == null)
							$('#displaytypeofcategory').html('Not Available');
						else
							$('#displaytypeofcategory').html(response['businesstype']);
						if(response['customertype'] == null)
							$('#displaytypeofcustomer').html('Not Available');
						else
							$('#displaytypeofcustomer').html(response['customertype']);
						$('#displaydealer').html(response['dealername']);
						$("#displaycustomerdetails").hide();
						$('#toggleimg1').attr('src',"../images/plus.jpg");
					} 
			}, 
			error: function(a,b)
			{
				$('#form-error').html(scripterror());
			}
		});	
	}
	else
	$('#form-error').html(scripterror());
	
}

//Customer details to form
function invoicedetailsdisplay(cusid)
{
	if(cusid != '')
	{
		var form = $('#submitform');
		$('#finalinvoice').val('');
		//$("#submitform" )[0].reset();
		var passData = "switchtype=invoicedetails&lastslno=" + encodeURIComponent(cusid) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
		$('#invoice_error').html(getprocessingimage());
		var queryString = "../ajax/implementation.php";
		ajaxobjext5555 = $.ajax(
		{
			type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
			success: function(ajaxresponse,status)
			{	
					$('#cfentrydiv').hide();
					$('#invoiceentrydiv').hide();
					autochecknew($('#collectedamt'),'no');
					$('#collectedamt').attr("disabled", true);
					$('#invoice_error').html('');
					var response = ajaxresponse;
					if(response == 'Thinking to redirect')
					{
						window.location = "../logout.php";
						return false;
					} 
				//	alert(response['invoicegrid'])
					if(response['errorcode'] == '1')
					{
						if(response['count'] != 0)
						{
							$('#displayinvoicecfdiv').show();
							$('#displayinvoicediv').hide();
							$('#adddescriptionrows tr').remove();
							var rowcount = $('#adddescriptioncfrows tr').length;
							$('#adddescriptioncfrows tr').remove();
							var appendrow1 = '<tr >';
							if((response['invoicegrid'] != null) && (response['cfgrid'] != null))
							{
								var row = '<td width="19%"><strong>Select a Invoice: </strong></td><td width="20%" id="invoicedetailsdisplay">'+ response['invoicegrid']+'</td><td width="13%"><input name="confirm" type="button" class="swiftchoicebutton" id="confirm" value="Confirm" onclick="confirmation(\'invoicedetails\')" /></td><td width="17%">(or) <strong>C/F Entries: </strong></td><td width="19%">'+ response['cfgrid']+'</td><td width="12%"><span style="padding-right:10px"><input name="cfconfirm" type="button" class="swiftchoicebutton" id="cfconfirm" value="Confirm" onclick="confirmation(\'cfinvoice\')" /></span></td>';
							}
							else if(response['invoicegrid'] != null)
							{
								
								var row = '<td width="19%"><strong>Select a Invoice: </strong></td><td width="20%" id="invoicedetailsdisplay">'+ response['invoicegrid']+'</td><td width="61%"><input name="confirm" type="button" class="swiftchoicebutton" id="confirm" value="Confirm" onclick="confirmation(\'invoicedetails\')" /></td>';
							}
							else if(response['cfgrid'] != null)
							{
								var row = '<td width="14%"><strong>C/F Entries: </strong></td><td width="22%">'+ response['cfgrid']+'</td><td width="64%"><span style="padding-right:10px"><input name="cfconfirm" type="button" class="swiftchoicebutton" id="cfconfirm" value="Confirm" onclick="confirmation(\'cfinvoice\')" /></span></td>';
							}
							
							var appendrow2 = '</tr>';
							$("#adddescriptioncfrows").append(appendrow1 + row + appendrow2);
							
						}
						else
						{
							$('#displayinvoicediv').show();
							$('#displayinvoicecfdiv').hide();
							$('#adddescriptioncfrows tr').remove();
							var rowcount = $('#adddescriptionrows tr').length;
							$('#adddescriptionrows tr').remove();
							var row = '<tr><td width="20%"><strong>Select a Invoice: </strong></td><td width="23%" id="invoicedetailsdisplay">'+ response['invoicegrid']+'</td><td width="57%"><span style="padding-right:10px"><input name="confirm" type="button" class="swiftchoicebutton" id="confirm" value="Confirm" onclick="confirmation(\'invoicedetails\')" /></span></td></tr>';
							$("#adddescriptionrows").append(row);
						}
					}
			}, 
			error: function(a,b)
			{
				$('#form-error').html(scripterror());
			}
		});			
	}
}

function validateimplementation(cusid)
{
	resetvalue();
	$('#implementationid').html('Pending');
	$('#assigndiv').hide();
	$('#advdisplay').hide();
	$('#remarksname').html('');
	$('#implementationremarks').html('');
	if(cusid != '')
	{
		var form = $('#submitform');
		$("#submitform")[0].reset();
		var passData = "switchtype=implementationvalidate&lastslno=" + encodeURIComponent(cusid) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
		$('#form-error').html(getprocessingimage());
		var queryString = "../ajax/implementation.php";
		ajaxobjext6589 = $.ajax(
		{
			type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
			success: function(ajaxresponse,status)
			{	
				var response = ajaxresponse;//alert(response['errorcode'])
				if(response == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else if(response['errorcode'] == '1' || response['errorcode'] == '2')
				{
					$("#displaydetails").show();
					$("#displaytext").hide();
					$("#displayimplementation").hide();
					customerdetailstoform(cusid);
					invoicedetailsdisplay(cusid);
					$('#approvereject').attr('disabled',true);
					$('#approvereject').removeClass('swiftchoicebuttonbig');
					$('#approvereject').addClass('swiftchoicebuttondisabledbig');
					$('#save').attr('disabled',false);
					$('#save').removeClass('swiftchoicebuttondisabled');
					$('#save').addClass('swiftchoicebutton');

					generateraffilegrid('');
				}
				// else if(response['errorcode'] == '2')
				// {
				// 	$("#displaydetails").hide();
				// 	$("#displaytext").hide();
				// 	$("#displayimplementation").show();
				// 	$('#error-msg').html(errormessage('No Invoice Entry for Saral Paypack.'));
				// 	generateraffilegrid('');

				// }
				}, 
			error: function(a,b)
			{
				$('#form-error').html(scripterror());
			}
		});		
						
	}
}

function validatecustomer(cusid)
{
	if(cusid != '')
	{
		$('#form-error2').html('');
		var form = $('#submitform');
		$("#submitform")[0].reset();
		var passData = "switchtype=customervalidation&lastslno=" + encodeURIComponent(cusid) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
		$('#form-error').html(getprocessingimage());
		var queryString = "../ajax/implementation.php";
		ajaxcall31 = $.ajax(
		{
			type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
			success: function(ajaxresponse,status)
			{	
				$('#form-error').html('');
				var response = ajaxresponse;//alert(response)
				if(response == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else if(response['errorcode'] == '1')
				{
					$("#displaydetails").show();
					$("#displaytext").hide();
					$("#displayimplementation").hide();
					customerdetailstoform(cusid);
					implemenationstatus(response['slno']);
					invoicecfdetailstoform(response['slno']);
					
					$("#lastslno").val(response['slno']);
					generatecustomization('');
					rafnewentry();
					generateraffilegrid('');
					$("#customerid").val(response['customerreference']);
					$("#invoicedetails").val(response['invoicenumber']);
					
					
					$("#balancerecovery").val(response['balancerecoveryremarks']);
					$("#podate").val(response['podetails']);
					$("#sales_company").val(response['numberofcompanies']);
					$("#sales_tomonths").val(response['numberofmonths']);
					$("#sales_frommonth").val(response['processingfrommonth']);
					$("#sales_training").val(response['additionaltraining']);
					
					$("#sales_deliver").val(response['freedeliverables']);
					$("#sales_scheme").val(response['schemeapplicable']);
					autochecknew($("#sales_checkbox"),response['commissionapplicable']);
					checkboxvalidation('displaysales');
					$("#sales_name").val(response['commissionname']);
					$("#sales_emailid").val(response['commissionemail']);
					$("#sales_mobile").val(response['commissionmobile']);
					$("#sales_commission").val(response['commissionvalue']);
					autochecknew($("#sales_masterdata"),response['masterdatabyrelyon']);
					checkboxvalidation('displaymasterdata');
					$("#sales_noofemployee").val(response['masternumberofemployees']);
					$("#sales_remarks").val(response['salescommitments']);
					autochecknew($("#attendance"),response['attendanceapplicable']);
					checkboxvalidation('displayattendance');
					$("#attendance_vendor").val(response['attendanceremarks']);
					if(response['attendancefilepath'] != null)
					{
						var response2 = response['attendancefilepath'].split('/');
						$("#downloadlinkfile2").html('<div id="linkdetailsdiv2" style="display:block"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="18%" id="viewdetailsdiv2"><a onclick = "viewfilepath(\'' + response['attendancefilepath'] + '\',\'2\')" class="r-text" style="text-decoration:none" >View &#8250;&#8250;</a></td><td id="deletedetailsdiv2" width="82%"><a onclick="deletefilepath(\'' + response['attendancedeletefilepath'] + '\');" class="r-text" style="text-decoration:none" >Delete &#8250;&#8250;</a></td></tr></table>');
						$("#attendance_errorfile").val(response2[5]);
						$("#attach_link").val(response['attendancefilepath']);
					}
					autochecknew($("#shipment_invoice"),response['shipinvoiceapplicable']);
					checkboxvalidation('displayshipmentinvoice');
					$("#shipment_remarks").val(response['shipinvoiceremarks']);
					autochecknew($("#shipment_manual"),response['shipmanualapplicable']);
					checkboxvalidation('displayshipmentmanual');
					$("#manual_remarks").val(response['shipmanualremarks']);
					autochecknew($("#customization"),response['customizationapplicable']);
					checkboxvalidation('displaycustomization');
					$("#customization_remarks").val(response['customizationremarks']);
					$("#DPC_startdate").val(response['committedstartdate']);
					$("#DPC_podatedetails").val(response['podate']);
					if(response['customizationreffilepath'] != null)
					{
						var response25 = response['customizationreffilepath'].split('/');
						$("#customization_references").val(response25[5]);
						$("#cust_link").val(response['customizationreffilepath']);
					}
					if(response['customizationbackupfilepath'] != null)
					{
						var response26 = response['customizationbackupfilepath'].split('/');
						$("#customization_sppdata").val(response26[5]);
						$("#spp_link").val(response['customizationbackupfilepath']);
					}
					$("#customizationstatus").html(response['customizationstatus']);
					if((response['podetailspath'] != null))
					{
						var response8 = response['podetailspath'].split('/');
						$("#downloadlinkfile5").html('<div id="linkdetailsdiv5" style="display:block"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="18%" id="viewdetailsdiv5"><a  onclick = "viewfilepath(\'' + response['podetailspath'] + '\',\'5\')" class="r-text" style="text-decoration:none" >View &#8250;&#8250;</a></td></tr></table>');
						$("#po_uploads").val(response8[5]);
						$("#po_link").val(response['podetailspath']);
					}
					
					if(response['branchapproval'] == 'yes')
					{
						$('#approvereject').attr('disabled',true);
						$('#approvereject').removeClass('swiftchoicebuttonbig');
						$('#approvereject').addClass('swiftchoicebuttondisabledbig');
						$('#save').attr('disabled',true);
						$('#save').removeClass('swiftchoicebutton');
						$('#save').addClass('swiftchoicebuttondisabled');

					}
					else
					{
						$('#approvereject').attr('disabled',false);
						$('#approvereject').removeClass('swiftchoicebuttondisabledbig');
						$('#approvereject').addClass('swiftchoicebuttonbig');
						$('#save').attr('disabled',false);
						$('#save').removeClass('swiftchoicebuttondisabled');
						$('#save').addClass('swiftchoicebutton');

					}
					autochecknew($("#web_implementation"),response['webimplemenationapplicable']);
					autochecknew($("#customizationcustomerview"),response['customizationcustomerdisplay']);
					checkboxvalidation('displaywebimplementation');
					$("#web_remarks").val(response['webimplemenationremarks']);
					
					
					
					$('#seletedaddongrid tr').remove();
					var headerrow = '<tr class="tr-grid-header"><td width="11%" nowrap = "nowrap" class="td-border-grid" align="left">Slno</td><td width="30%" nowrap = "nowrap" class="td-border-grid" align="left">Add - On</td><td width="46%" nowrap = "nowrap" class="td-border-grid" align="left">Remarks</td><td width="13%" nowrap = "nowrap" class="td-border-grid" align="left">Remove</td></tr><tr><td colspan="4" class="td-border-grid" id="messagerow"><div align="center" style="height:15px;"><strong><font color="#FF0000">No Records Avaliable</font></strong></div></td></tr>';
					$("#seletedaddongrid").append(headerrow);
					$('#messagerow').remove();
					if(response['addonarray'] != null)
					{
						var countrow = response['addonarray'].split('****');
						for(k=1;k<=countrow.length;k++)
						{
							slno = k;
							var addonrowid = 'addonrowid'+k;
							var removelinkid = 'removelinkid'+k;
							var addontypeid = 'addontype'+k;
							var addonremarksid = 'addonremarks'+k;
							var addonrowslnoid = 'addonrowslno'+k;
							var row = '<tr class="gridrow1" id=\'' + addonrowid + '\'><td width="11%" nowrap = "nowrap" class="td-border-grid" align="left"  id=\'' + addonrowslnoid + '\'></td><td width="34%" nowrap = "nowrap" class="td-border-grid" align="left"  id=\'' + addontypeid + '\'></td><input type="hidden" name="addontypeidvalue'+ slno+'" id="addontypeidvalue'+ slno+'" value="" /><td width="42%" nowrap = "nowrap" class="td-border-grid" align="left"  id=\'' + addonremarksid + '\' ></td><td width="13%" nowrap = "nowrap" class="td-border-grid" align="left"><div align ="center"><font color = "#FF0000"><strong><a id="' + removelinkid + '" onclick = "deleteproductrow(\'' + slno + '\');" style="cursor:pointer;" >X</a></strong></font></div><input type="hidden" name="addonslno'+ slno+'" id="addonslno'+ slno+'" value="" /></td></tr>';
							$("#seletedaddongrid").append(row);
							$('#'+addonrowslnoid).html(slno);
						}
						splitvalue = new Array();
						for(var i=0;i<countrow.length;i++)
						{
							splitvalue[i] =  countrow[i].split('#');
							$("#"+'addontype'+(i+1)).html(splitvalue[i][0]);
							$("#"+'addonremarks'+(i+1)).html(splitvalue[i][1]);
							$("#"+'addonslno'+(i+1)).val(splitvalue[i][2]);
							$("#"+'addontypeidvalue'+(i+1)).val(splitvalue[i][3]);
						}
					}

				}
				else if(response['errorcode'] == '2')
				{
					$("#displaydetails").hide();
					$("#displaytext").hide();
					$("#displayimplementation").show();
					$("#downloadlinkfile5").html('');
					
				}
			},
			error: function(a,b)
			{
				$('#form-error').html(scripterror());
			}
		});	
	}
	else
	$('#form-error').html(scripterror());
}

function confirmation(command)
{
	$('#customerselectionprocess').html('');
	$('#productcode').val('');
	var form = $('#submitform');
	if(command == 'invoicedetails')
	{
		var field = $("#invoicedetails");
		if(!field.val()) { alert("Select the invoice"); field.focus(); return false; }
		if(typeof $('#invoicedetails').val()!= "undefined")
		{
			var char1 = $('#invoicedetails').val().split('%%');
			var rslno = char1[0];
			var invoiceslno = char1[1];
			//$('#productcode').val(char1[2]);
		}
		
		//var passData = "switchtype=invoiceconfirmation&rslno=" + encodeURIComponent(rslno) + "&invoiceslno=" + encodeURIComponent(invoiceslno) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
		if(char1[2] == 'matrix')
		{
			var invoicetype = char1[2];
			$('#producttype').val(char1[2]);
			var passData = "switchtype=invoicematrixconfirmation&rslno=" + encodeURIComponent(rslno)+ "&invoiceslno=" + encodeURIComponent(invoiceslno) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
		}
		else
		{
			$('#productcode').val(char1[2]);
			var passData = "switchtype=invoiceconfirmation&rslno=" + encodeURIComponent(rslno)+ "&invoiceslno=" + encodeURIComponent(invoiceslno) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
		}
	}
	else if(command == 'cfinvoice')
	{
		var field = $("#cfdetails" );
		if(!field.val()) { alert("Select the invoice"); field.focus(); return false; }
		var char1 = $('#cfdetails').val().split('%%');
		var rslno = char1[0];
		var invoiceslno = char1[1];
		$('#productcode').val(char1[2]);
		var passData = "switchtype=cfinvoiceconfirmation&rslno=" + encodeURIComponent(rslno) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)

	}
	$('#form-error').html(getprocessingimage());
	var queryString = "../ajax/implementation.php";
	ajaxobjext22 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			$('#form-error').html('');
			$('#searchcustomerid').val('');
			var response = ajaxresponse;//alert(response[4])
			if(response == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			if(response['errorcode'] == '1')
			{
				$('#receiptdisplay').html(response['receiptgrid']);
				$("#cfentrydiv").hide();
				$("#invoiceentrydiv").show();
				$("#paymentremarks.invoice").val('');
				checkboxvalidation('displaypayment');
				
				
				if(response['count'] == 0)
				{
					$('#displayinvoicediv').show();
					$('#displayinvoicecfdiv').hide();
					var rowcount = $('#adddescriptionrows tr').length;
					$('#adddescriptionrows tr').remove();
					$('#finalinvoice').val(response['invoiceno']);
					var row = '<tr><td width="21%" ><strong>Selected Invoice: </strong></td><td width="20%"><input type="text" name="invoicedetails"  class="swiftselect-mandatory" disabled="disabled" id="invoicedetails" size="20"  value="'+ response['invoiceno']+'"  /></td><td width="59%"><div style="text-align:left"><a  onclick="invoicedetailsdisplay(\''+response['customerid']+'\');"  class="r-text">Change &#8250;&#8250;</a></div></td></tr><tr ><td colspan="3">'+ response['grid']+'</td> </tr>';
					$("#adddescriptionrows").append(row);
				}
				else
				{
					$('#displayinvoicediv').show();
					$('#displayinvoicecfdiv').hide();
					var rowcount = $('#adddescriptioncfrows tr').length;
					$('#adddescriptioncfrows tr').remove();
					$('#finalinvoice').val(response['invoiceno']);
					var row = '<tr><td width="21%" ><strong>Selected Invoice: </strong></td><td width="20%"><input type="text" name="invoicedetails"  class="swiftselect-mandatory" disabled="disabled" id="invoicedetails" size="20"  value="'+ response['invoiceno']+'"  /></td><td width="59%"><div style="text-align:left"><a  onclick="invoicedetailsdisplay(\''+response['customerid']+'\');"  class="r-text">Change &#8250;&#8250;</a></div></td></tr><tr ><td colspan="3">'+ response['grid']+'</td> </tr>';
					$("#adddescriptioncfrows").append(row);
				}
				
				
			}
			if(response['errorcode'] == '2')
			{
				
				$('#cfentrydiv').show();
				$('#invoiceentrydiv').hide();
				$('#collectedamt').attr("disabled", false);
				$("#paymentremarks.cf").val('');
				checkboxvalidation('displaypayment');
				
				$('#displayinvoicediv').hide();
				$('#displayinvoicecfdiv').show();
				var rowcount = $('#adddescriptioncfrows tr').length;
				$('#adddescriptioncfrows tr').remove();
				$('#finalinvoice').val(response['invoiceno']);
				var row = '<tr><td width="21%" ><strong>Selected Invoice: </strong></td><td width="20%"><input type="text" name="invoicedetails"  class="swiftselect-mandatory" disabled="disabled" id="invoicedetails" size="20"  value="'+ response['invoiceno']+'"  /></td><td width="59%"><div style="text-align:left"><a  onclick="invoicedetailsdisplay(\''+response['customerid']+'\');"  class="r-text">Change &#8250;&#8250;</a></div></td></tr><tr ><td colspan="3">'+ response['grid']+'</td> </tr>';
				$("#adddescriptioncfrows").append(row);
				
			}
			
		}, 
		error: function(a,b)
		{
			$('#form-error').html(scripterror());
		}
	});
}


function checkboxvalidation(elementid)
{
	if(elementid == 'displaypayment')
	{
		if($('#collectedamt').is(':checked') == true)
		{
			$('#collectedamt').attr("disabled", false);
			if($("#cfentrydiv").is(":visible") && $("#invoiceentrydiv").is(":hidden"))
			{
				$('#paymentamt').attr('disabled',false);	
				$('#paymentremarks.cf').attr('disabled',false);
				
				$('#paymentamt').removeClass('diabledatefield');
				$('#paymentamt').addClass('swiftselect-mandatory');
				$('#paymentremarks.cf').removeClass('diabledatefield');
				$('#paymentremarks.cf').addClass('swiftselect-mandatory');
				
				
			}
			else if($("#cfentrydiv").is(":hidden") && $("#invoiceentrydiv").is(":visible"))
			{
				$('#receipt').attr('disabled',false);	
				$('#paymentremarks.invoice').attr('disabled',false);
				
				$('#receipt').removeClass('diabledatefield');
				$('#receipt').addClass('swiftselect-mandatory');
				$('#paymentremarks.invoice').removeClass('diabledatefield');
				$('#paymentremarks.invoice').addClass('swiftselect-mandatory');
				
			}

		}
		else
		{
			$('#collectedamt').attr("disabled", false);
			if($("#cfentrydiv").is(":visible") && $("#invoiceentrydiv").is(":hidden"))
			{
				$('#paymentamt').attr('disabled',true);	
				$('#paymentremarks.cf').attr('disabled',true);
				$('#paymentremarks.cf').val('');
				$('#paymentamt').val('');
				
				$('#paymentamt').removeClass('swiftselect-mandatory');
				$('#paymentamt').addClass('diabledatefield');
				$('#paymentremarks.cf').removeClass('swiftselect-mandatory');
				$('#paymentremarks.cf').addClass('diabledatefield');
				
			}
			else if($("#cfentrydiv").is(":hidden") && $("#invoiceentrydiv").is(":visible"))
			{
				$('#receipt').attr('disabled',true);	
				$('#paymentremarks.invoice').attr('disabled',true);
				$('#paymentremarks.invoice').val('');
				$('#receipt').val('');
				
				$('#receipt').removeClass('swiftselect-mandatory');
				$('#receipt').addClass('diabledatefield');
				$('#paymentremarks.invoice').removeClass('swiftselect-mandatory');
				$('#paymentremarks.invoice').addClass('diabledatefield');
			}

		}
	}
	else if(elementid == 'displaysales')
	{
		if($('#sales_checkbox').is(':checked') == true)
		{
			$('#sales_name').attr('disabled',false);	
			$('#sales_emailid').attr('disabled',false);	
			$('#sales_mobile').attr('disabled',false);	
			$('#sales_commission').attr('disabled',false);	

			$('#sales_name').removeClass('diabledatefield');
			$('#sales_name').addClass('swiftselect-mandatory');
			$('#sales_emailid').removeClass('diabledatefield');
			$('#sales_emailid').addClass('swiftselect-mandatory');
			
			$('#sales_mobile').removeClass('diabledatefield');
			$('#sales_mobile').addClass('swiftselect-mandatory');
			$('#sales_commission').removeClass('diabledatefield');
			$('#sales_commission').addClass('swiftselect-mandatory');

		}
		else
		{
			$('#sales_name').attr('disabled',true);	
			$('#sales_emailid').attr('disabled',true);	
			$('#sales_mobile').attr('disabled',true);	
			$('#sales_commission').attr('disabled',true);	
			
			$('#sales_name').removeClass('swiftselect-mandatory');
			$('#sales_name').addClass('diabledatefield');
			$('#sales_emailid').removeClass('swiftselect-mandatory');
			$('#sales_emailid').addClass('diabledatefield');
			
			$('#sales_mobile').removeClass('swiftselect-mandatory');
			$('#sales_mobile').addClass('diabledatefield');
			$('#sales_commission').removeClass('swiftselect-mandatory');
			$('#sales_commission').addClass('diabledatefield');
			
			$('#sales_name').val('');
			$('#sales_emailid').val('');
			$('#sales_mobile').val('');
			$('#sales_commission').val('');

		}
	}
	else if(elementid == 'displaymasterdata')
	{
		if($('#sales_masterdata').is(':checked') == true)
		{
			$('#sales_noofemployee').attr('disabled',false);	
			$('#sales_noofemployee').removeClass('diabledatefield');
			$('#sales_noofemployee').addClass('swiftselect-mandatory');
		}
		else
		{
			$('#sales_noofemployee').attr('disabled',true);	
			$('#sales_noofemployee').removeClass('swiftselect-mandatory');
			$('#sales_noofemployee').addClass('diabledatefield');
			
			$('#sales_noofemployee').val('');
		}
	}
	else if(elementid == 'displayattendance')
	{
		if($('#attendance').is(':checked') == true)
		{
			$('#attendance_vendor').attr('disabled',false);	
			$('#attendance_errorfile').attr('disabled',false);	
			
			$('#attendance_vendor').removeClass('diabledatefield');
			$('#attendance_vendor').addClass('swiftselect-mandatory');
		}
		else
		{
			$('#attendance_vendor').attr('disabled',true);	
			$('#attendance_errorfile').attr('disabled',true);
			
			$('#attendance_vendor').removeClass('swiftselect-mandatory');
			$('#attendance_vendor').addClass('diabledatefield');
			
			$('#attendance_vendor').val('');
			$('#attendance_errorfile').val('');
			$('#downloadlinkfile2').html('');
		}
	}
	else if(elementid == 'displayshipmentinvoice')
	{
		if($('#shipment_invoice').is(':checked') == true)
		{
			$('#shipment_remarks').attr('disabled',false);	
			$('#invoice_link').show();	
			$('#shipment_remarks').removeClass('diabledatefield');
			$('#shipment_remarks').addClass('swiftselect-mandatory');
		}
		else
		{
			$('#shipment_remarks').attr('disabled',true);	
			$('#invoice_link').hide();	
			$('#shipment_remarks').removeClass('swiftselect-mandatory');
			$('#shipment_remarks').addClass('diabledatefield');
			
			$('#shipment_remarks').val('');
		}
	}
	else if(elementid == 'displayshipmentmanual')
	{
		if($('#shipment_manual').is(':checked') == true)
		{
			$('#manual_remarks').attr('disabled',false);	
			$('#manual_link').show();	
			$('#manual_remarks').removeClass('diabledatefield');
			$('#manual_remarks').addClass('swiftselect-mandatory');
		}
		else
		{
			$('#manual_remarks').attr('disabled',true);	
			$('#manual_link').hide();	
			$('#manual_remarks').removeClass('swiftselect-mandatory');
			$('#manual_remarks').addClass('diabledatefield');
			
			$('#manual_remarks').val('');
		}
	}
	else if(elementid == 'displaycustomization')
	{
		if($('#customization').is(':checked') == true)
		{
			$('#customization_remarks').attr('disabled',false);	
			$('#customization_references').attr('disabled',false);	
			$('#customization_sppdata').attr('disabled',false);	
			$('#customizationcustomerview').attr('disabled',false);	
			
			$('#customization_remarks').removeClass('diabledatefield');
			$('#customization_remarks').addClass('swiftselect-mandatory');
		}
		else
		{
			$('#customization_remarks').attr('disabled',true);	
			$('#customization_references').attr('disabled',true);	
			$('#customization_sppdata').attr('disabled',true);
			$('#customizationcustomerview').attr('disabled',true);	
			
			$('#customization_remarks').removeClass('swiftselect-mandatory');
			$('#customization_remarks').addClass('diabledatefield');
			
			$('#customization_remarks').val('');
			$('#customization_references').val('');
			$('#customization_sppdata').val('');
			autochecknew($('#customizationcustomerview'),'no');
		}
	}
	else if(elementid == 'displaywebimplementation')
	{
		if($('#web_implementation').is(':checked') == true)
		{
			$('#web_remarks').attr('disabled',false);	
			$('#web_remarks').removeClass('diabledatefield');
			$('#web_remarks').addClass('swiftselect-mandatory');
		}
		else
		{
			$('#web_remarks').attr('disabled',true);	

			$('#web_remarks').removeClass('swiftselect-mandatory');
			$('#web_remarks').addClass('diabledatefield');
			$('#web_remarks').val('');
		}
	}
}

function formsubmit(command,type)
{
	var form = $("#submitform" );
	var error = $("#form-error" );
	var invfield = $("#invoicedetails");
	var typefield = '';
	var remarksfield = '';
	var slnofield = '';
	var contactarray = '';
	var paymentremarks = '';

	var field = $("#imp_statustype");
	if(!field.val()) { alert("Select the implementation type."); field.focus(); return false; }
	
	if($("#imptype_remarks option:selected").text() == 'Others')
	{
		var field = $("#imptype_remarks");
		if(!field.val()) { alert("Enter Remarks."); field.focus(); return false; }
	}

	//if(!field.val()) { alert("Select the invoice and confirm it."); field.focus(); return false; }
	//alert($('#invoicedetails').val());
	var invoicedetails= $('#invoicedetails').val();
	if(typeof invoicedetails!= "undefined")
	{
		var char1 = $('#invoicedetails').val().split('%%');
		var invoicenovalue = char1[0];
	}
	else
	{
		var invoicenovalue = "";
	}
	//alert(invfield.val());
	if(typeof invfield.val()!="undefined")
	{
		var field = $("#finalinvoice");
		if(!field.val() && invfield.val()!='') { alert("Confirm the selected invoice."); field.focus(); return false; }
	}
	
	var field = $('#collectedamt:checked').val();
	if(field != 'on') var collectedamt = 'no'; else collectedamt = 'yes';
	if(collectedamt == 'yes')
	{
		if($("#cfentrydiv").is(":visible") && $("#invoiceentrydiv").is(":hidden"))
		{
			var field = $("#paymentamt");
			if(!field.val()) { alert("Enter the Payment Amount"); field.focus(); return false;field.scroll();}
			if(field.val())	{ if(!validateamount(field.val())) { alert('Amount is not Valid.');field.focus();field.scroll(); return false; }}
			var field = $("#paymentremarks.cf" );
			if(!field.val()) { alert("Enter the Payment Remarks"); field.focus(); return false; }
			var paymentremarks = $("#paymentremarks.cf").val();
		}
		else if($("#cfentrydiv").is(":hidden") && $("#invoiceentrydiv").is(":visible"))
		{
			var field = $("#receipt");
			if(!field.val()) { alert("Select the Receipt"); field.focus(); return false;field.scroll();}
			var advancecollecreceipt = $("#receipt").val();
			
			var field = $("#paymentremarks.invoice");
			if(!field.val()) { alert("Enter the Payment Remarks"); field.focus(); return false; }
			var paymentremarks = $("#paymentremarks.invoice").val();
		}
		
		
	}

	//alert($("#imp_statustype").val());
	if($("#imp_statustype").val()!= '2' && $("#imp_statustype").val()!= '5' && $("#imp_statustype").val()!= '7' && $("#imp_statustype").val()!= '10')
	{
		var field = $("#balancerecovery" );
		if(!field.val()) { alert("Enter the Balance Recovery Schedule"); field.focus();field.scroll(); return false; }
	}
	

	if(($('#rafslno').val() != '') && ($('#deleterafslno').val() != ''))
	{
		var count = $('#rafslno').val().split(',');
		if((count.length) > 1)
			$('#raflag').val('yes'); 
		else
			$('#raflag').val('');
	}
	if($("#imp_statustype").val()!= '2' && $("#imp_statustype").val()!= '5' && $("#imp_statustype").val()!= '7' && $("#imp_statustype").val()!= '10')
	{
		var field = $("#raflag");
		if(!field.val()) { alert("Atleast one Requirement Analysis Format is required."); $('#attachement_raf').focus(); $('#attachement_raf').scroll();return false; }
	}
	
	var field = $('#sales_checkbox:checked').val();
	if(field != 'on') var sales_checkbox = 'no'; else sales_checkbox = 'yes';

	var field = $('#sales_masterdata:checked').val();
	if(field != 'on') var sales_masterdata = 'no'; else sales_masterdata = 'yes';

	if($("#imp_statustype").val()!= '2' && $("#imp_statustype").val()!= '5' && $("#imp_statustype").val()!= '7' && $("#imp_statustype").val()!= '10')
	{
		var field = $("#podate" );
		if(!field.val()) { alert("Enter the Purchase Order Number"); field.focus();field.scroll(); return false; }
		var field = $("#DPC_podatedetails" );
		if(!field.val()) { alert("Enter the Purchase Date"); field.focus();field.scroll(); return false; }
		// var field = $("#po_link" );
		// if(!field.val()) { alert("Please Attach the PO Uploads"); field.focus();field.scroll(); return false; }
		var field = $("#sales_company" );
		if(!field.val()) { alert("Enter the Number  of Companies to be processed"); field.focus();field.scroll(); return false; }
		var field = $("#sales_tomonths" );
		if(!field.val()) { alert("Enter the Number of Months to be processed"); field.focus();field.scroll(); return false; }
		var field = $("#sales_frommonth" );
		if(!field.val()) { alert("Enter the Number of Months Processing "); field.focus();field.scroll(); return false; }
		var field = $("#sales_training" );
		if(!field.val()) { alert("Enter the Additional Training Commitment"); field.focus();field.scroll(); return false; }
		var field = $("#DPC_startdate" );
		if(!field.val()) { alert("Enter the Commitment of Start date"); field.focus();field.scroll(); return false; }
		var field = $("#sales_deliver" );
		if(!field.val()) { alert("Enter the Any Free Deliverables"); field.focus();field.scroll(); return false; }
		var field = $("#sales_scheme" );
		if(!field.val()) { alert("Enter the Any Scheme Applicable"); field.focus();field.scroll(); return false; }
		
		if(sales_checkbox == 'yes')
		{
			var field = $("#sales_name" );
			if(!field.val()) { alert("Enter the Name"); field.focus();field.scroll(); return false; }
			var field = $("#sales_emailid" );
			if(!field.val()) { alert("Enter the Emailid"); field.focus();field.scroll(); return false; }
			if(field.val())	{ if(!emailvalidation(field.val())) { alert('Enter the valid Email ID.'); field.focus(); return false; } }
			var field = $("#sales_mobile" );
			if(!field.val()) { alert("Enter the Mobile Number"); field.focus();field.scroll(); return false; }
			if(field.val()) { if(!validatecell(field.val())) { alert('Enter the valid Mobile Number.'); field.focus(); return false; } }
			var field = $("#sales_commission" );
			if(!field.val()) { alert("Enter the Commission"); field.focus();field.scroll(); return false; }
		}
		
		if(sales_masterdata == 'yes')
		{
			var field = $("#sales_noofemployee" );
			if(!field.val()) { alert("Enter the Number of Employees to be Entered"); field.focus();field.scroll(); return false; }
			
		}
		
		var field = $("#sales_remarks" );
		if(!field.val()) { alert("Enter the Commitments of Sales Person"); field.focus();field.scroll(); return false; }
	}
	var field = $('#attendance:checked').val();
	if(field != 'on') var attendance = 'no'; else attendance = 'yes';
	if(attendance == 'yes')
	{
		var field = $("#attendance_vendor" );
		if(!field.val()) { alert("Enter the Vendor Details"); field.focus();field.scroll(); return false; }
		var field = $("#attendance_errorfile" );
		if(!field.val()) { alert("Please Attach the Attendance Integration Reference File"); field.focus();field.scroll(); return false; }
	}
	var rowcount = $('#seletedaddongrid tr').length;
	//alert(rowcount)
	for(j=1;j<(rowcount-1);j++)
	{
		var typefield = $("#"+'addontypeidvalue'+j);
		var remarksfield = $("#"+'addonremarks'+j);
		var slnofield = $("#"+'addonslno'+j);
		
		if(j == 1)
			contactarray = typefield.val() + '#' + remarksfield.html()+ '#' + slnofield.val() ;
		else
			contactarray = contactarray + '~' + typefield.val()  + '#' + remarksfield.html()+ '#' + slnofield.val();
	}
	//alert(contactarray)
	var field = $('#shipment_invoice:checked').val();
	if(field != 'on') var shipment_invoice = 'no'; else shipment_invoice = 'yes';
	if(shipment_invoice == 'yes')
	{
		var field = $("#shipment_remarks" );
		if(!field.val()) { alert("Enter the Shipment Invoice Remarks"); field.focus();field.scroll(); return false; }
	}
	var field = $('#shipment_manual:checked').val();
	if(field != 'on') var shipment_manual = 'no'; else shipment_manual = 'yes';
	if(shipment_manual == 'yes')
	{
		var field = $("#manual_remarks" );
		if(!field.val()) { alert("Enter the Shipment Manual Remarks"); field.focus();field.scroll(); return false; }
	}
	var field = $('#customizationcustomerview:checked').val();
	if(field != 'on') var customizationcustomerview = 'no'; else customizationcustomerview = 'yes';
	var field = $('#customization:checked').val();
	if(field != 'on') var customization = 'no'; else customization = 'yes';
	
	if(customization == 'yes')
	{
		var field = $("#customization_remarks" );
		if(!field.val()) { alert("Enter the Customization Remarks"); field.focus();field.scroll(); return false; }
		var field = $("#customization_references" );
		if(!field.val()) { alert("Please Attach Customization References Files"); field.focus();field.scroll(); return false; }
	}
	var field = $('#web_implementation:checked').val();
	if(field != 'on') var web_implementation = 'no'; else web_implementation = 'yes';
	if(web_implementation == 'yes')
	{
		var field = $("#web_remarks" );
		if(!field.val()) { alert("Enter the Web Implementation Remarks"); field.focus();field.scroll(); return false; }
	}

		var passData = "";
		if(command == 'save')
		{
			passData =  "switchtype=save&customerreference=" + encodeURIComponent($('#customerlist').val()) + "&imp_statustype=" + encodeURIComponent($('#imp_statustype').val()) + 
			"&imptype_remarks=" + encodeURIComponent($('#imptype_remarks').val()) + "&invoicenumber=" + encodeURIComponent(invoicenovalue)  + "&advancecollected=" + encodeURIComponent(collectedamt) 
			+ "&advanceamount=" + encodeURIComponent($("#paymentamt").val())+ "&advancecollecreceipt=" + encodeURIComponent($("#receipt").val()) + "&advanceremarks=" 
			+ encodeURIComponent(paymentremarks) + "&balancerecoveryremarks=" + encodeURIComponent($('#balancerecovery').val())  
			 + "&podetails=" + encodeURIComponent($('#podate').val()) + "&numberofcompanies=" + encodeURIComponent($('#sales_company').val()) 
			 + "&numberofmonths=" + encodeURIComponent($('#sales_tomonths').val()) + "&processingfrommonth=" + encodeURIComponent($('#sales_frommonth').val()) 
			 + "&advancecollected=" + encodeURIComponent(collectedamt) + "&advanceamount=" + encodeURIComponent($('#paymentamt').val()) 
			 + "&additionaltraining=" + encodeURIComponent($('#sales_training').val()) 
			 + "&freedeliverables=" + encodeURIComponent($('#sales_deliver').val())  + "&schemeapplicable=" + encodeURIComponent($('#sales_scheme').val()) 
			  + "&numberofcompanies=" + encodeURIComponent($('#sales_company').val()) 
			 + "&numberofmonths=" + encodeURIComponent($('#sales_tomonths').val()) + "&processingfrommonth=" + encodeURIComponent($('#sales_frommonth').val()) 
			  + "&commissionapplicable=" + encodeURIComponent(sales_checkbox) + "&commissionname=" + encodeURIComponent($('#sales_name').val()) + "&commissionemail=" + encodeURIComponent($('#sales_emailid').val())  + "&commissionmobile=" + encodeURIComponent($('#sales_mobile').val()) + "&commissionvalue=" + encodeURIComponent($('#sales_commission').val()) + "&masterdatabyrelyon=" + encodeURIComponent(sales_masterdata) + "&masternumberofemployees=" + encodeURIComponent($('#sales_noofemployee').val()) + "&salescommitments=" + encodeURIComponent($('#sales_remarks').val())  + "&attendanceapplicable=" + encodeURIComponent(attendance) + "&attendanceremarks=" + encodeURIComponent($('#attendance_vendor').val())+ "&attendancefilepath=" + encodeURIComponent($('#attach_link').val())+ "&attendancedeletefilepath=" + encodeURIComponent($('#delete_link').val())+ "&shipinvoiceapplicable=" + encodeURIComponent(shipment_invoice) + "&shipinvoiceremarks=" + encodeURIComponent($('#shipment_remarks').val()) + "&shipmanualapplicable=" + encodeURIComponent(shipment_manual) + "&shipmanualremarks=" + encodeURIComponent($('#manual_remarks').val()) + "&customizationapplicable=" + encodeURIComponent(customization)+ "&customizationremarks=" + encodeURIComponent($('#customization_remarks').val())+ "&customizationreffilepath=" + encodeURIComponent($('#cust_link').val()) + "&customizationbackupfilepath=" + encodeURIComponent($('#spp_link').val()) + "&customizationstatus=" + encodeURIComponent($('#customizationstatus').html()) +"&webimplemenationapplicable=" + encodeURIComponent(web_implementation)+ "&webimplemenationremarks=" + encodeURIComponent($('#web_remarks').val()) + "&lastslno=" + encodeURIComponent($('#lastslno').val()) + "&rafslno=" + encodeURIComponent($('#rafslno').val())+ "&deleterafslno=" + encodeURIComponent($('#deleterafslno').val())+  "&contactarray=" + encodeURIComponent(contactarray)+ "&totalarray=" + encodeURIComponent(totalarray)+ "&pofilepath=" + encodeURIComponent($('#po_link').val())+ "&customizationcustomerview=" + encodeURIComponent(customizationcustomerview)+ "&DPC_startdate=" + encodeURIComponent($("#DPC_startdate" ).val())+ "&productcode=" + encodeURIComponent($("#productcode" ).val())+ "&DPC_podatedetails=" + encodeURIComponent($("#DPC_podatedetails" ).val()) + "&producttype=" + encodeURIComponent($('#producttype').val()) + "&dummy=" + Math.floor(Math.random()*100000000);//alert(passData)
			$('#save').attr('disabled',true);
			$('#save').addClass('swiftchoicebuttondisabled');
			$('#save').removeClass('swiftchoicebutton');
			

		}
		else if(command == 'approve')
		{
			passData =  "switchtype=approve&customerreference=" + encodeURIComponent($('#customerlist').val()) + "&lastslno=" + encodeURIComponent($('#lastslno').val()) + "&dummy=" + Math.floor(Math.random()*100000000);//alert(passData)
		}
		else
		{
				alert('You are using a wrong provision.');
				return false;
		}
		queryString = '../ajax/implementation.php';
		error.html('<img src="../images/imax-loading-image.gif" border="0" />');
		ajaxobjext233 = $.ajax(
		{
			type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
			success: function(ajaxresponse,status)
			{	
				error.html('');
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				var response = ajaxresponse;
				if(type == 'approve')
				{
					$('#lastslno').val(response['refslno']);
					return false;
					
				}
				if(response['errorcode'] == '1')
				{
					implemenationstatus(response['refslno']);
					generateraffilegrid('');
					$('#lastslno').val(response['refslno']);
					alert(response['errormsg']);
				}
				else if(response['errorcode'] == '2')
				{
					updatedmail(response['refslno']);
					implemenationstatus(response['refslno']);
					generateraffilegrid('');
					$('#lastslno').val(response['refslno']);
					alert(response['errormsg']);
				}
				else
				{
					$('#form-error').html(scripterror());
				}
			},
		error: function(a,b)
		{
			$('#form-error').html(scripterror());
		}
	});		
}

function addselectedaddons()
{
	var field = $("#addon");
	if(!field.val())
	{
		alert('Please select a Add - On'); field.focus();return false;
	}
	var rowcount = $('#seletedaddongrid tr').length;
	$('#messagerow').remove();
	
	if(rowcount == 2)
		var k = 1;
	else
		var k = (rowcount-1);
	var form = $('#submitform');
	var addonrowid = 'addonrowid'+k;
	var removelinkid = 'removelinkid'+k;
	
	var addonremarksid = 'addonremarks'+k;
	var addonrowslnoid = 'addonrowslno'+k;
	var addontypeid = 'addontypeid'+k;
	
	var addonname =$("#addon option:selected").text();
	var addontypeidvalue = $("#addon option:selected").val();
	var addonremarks =$("#addon_remarks").val();

	var productgrid ='<tr class="gridrow1" id=\'' + addonrowid + '\'><td width="11%" nowrap = "nowrap" class="td-border-grid" align="left"  id=\'' + addonrowslnoid + '\'>' + k + '</td><td width="34%" nowrap = "nowrap" class="td-border-grid" align="left"  >' + addonname + '</td><input type="hidden" name="addontypeidvalue'+ k+'" id="addontypeidvalue'+ k+'" value="'+ addontypeidvalue +'"/><td width="42%" nowrap = "nowrap" class="td-border-grid" align="left"  id=\'' + addonremarksid + '\' >' + addonremarks + '</td><td width="13%" nowrap = "nowrap" class="td-border-grid" align="left"><div align ="center"><font color = "#FF0000"><strong><a id="' + removelinkid + '" onclick = "deleteproductrow(\'' + k + '\');" style="cursor:pointer;" >X</a></strong></font></div><input type="hidden" name="addonslno'+ k+'" id="addonslno'+ k+'" value="" /></td></tr>';
	$("#seletedaddongrid").append(productgrid);
	
	$('#addon').val('');
	$('#addon_remarks').val('');
}


//delete product from list
function deleteproductrow(id)
{
	if(totalarray == '')
		totalarray = $('#addonslno'+id).val();
	else
		totalarray = totalarray  + ',' + $('#addonslno'+id).val();
	$('#addonrowid'+id).remove();
	var rowcount = $('#seletedaddongrid tr').length;
	countval = 0;
	for(i=1;i<=(rowcount+1);i++)
	{
		var addonrowid = '#addonrowid'+i;
		var removelinkid = '#removelinkid'+i;
		var addontypeid = '#addontype'+i;
		var addonremarksid = '#addonremarks'+i;
		var addonrowslnoid = '#addonrowslno'+i;
		if($(addonrowid).length > 0)
		{
			countval++;
			$("#addontype"+ i).attr("id","addontype"+ countval);
			$("#addonremarks"+ i).attr("id","addonremarks"+ countval);
			$("#removelinkid"+ i).attr("id","removelinkid"+ countval);
			$("#addonrowid"+ i).attr("id","addonrowid"+ countval);
			$("#addontypeidvalue"+ i).attr("id","addontypeidvalue"+ countval);
			$("#addontypeidvalue"+ countval).html(countval);
			$("#addonslno"+ i).attr("id","addonslno"+ countval);
			$("#addonslno"+ countval).html(countval);
			$("#addonrowslno"+ i).attr("id","addonrowslno"+ countval);
			$("#addonrowslno"+countval).html(countval);
			document.getElementById("removelinkid" + countval).onclick = new Function('deleteproductrow("' + countval + '")');
		}
	}
	if(rowcount == 2)
	{
		 newaddonentry();
	}
}



function deletefilepath(pathlink)
{
	var passData = "switchtype=deletepath&pathlink=" + encodeURIComponent(pathlink)+"&lastslno=" + encodeURIComponent($('#lastslno').val()) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
	var queryString = "../ajax/implementation.php";
	ajaxobjext1 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			var response = ajaxresponse;//alert(response)
			if(response == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			if(response['errorcode'] == '1')
			{
				$('#linkdetailsdiv2').hide();
				$('#attendance_errorfile').val('');
				$('#attach_link').val('');
				$('#filepath2').val('');
				$('#delete_link').val('');
			}
		},
		error: function(a,b)
		{
			error.html(scripterror());
		}
	});		
				
}

function clearentry()
{
	var form = $("#submitform" );
	$("#submitform")[0].reset();
	$('#fileuploaddiv').hide();
	$('#invoiceentrydiv').hide();
	$('#cfentrydiv').hide();
	$('#attendance_fileuploaddiv').hide();
	$('#references_fileuploaddiv').hide();
	$('#sppdata_fileuploaddiv').hide();
	$("#submitform")[0].reset();
	$("#lastslno").val('');
	$("#customerid").val('');
	$("#error-msg").html('');
	$("#raflag").val('');
	$("#groupvalue").val('');
	$("#impraflastslno").val('');
	$("#assignid").val('');
	$("#raslno").val('');
	$("#deleteraslno").val('');
	$('#form-error2').val('');
	$('#collectedamt').attr("disabled", true);
	newaddonentry();
	totalarray = '';
	contactarray = '';
	
}

//New Product Entry
function newaddonentry()
{
	$("#submitform" )[0].reset();
	$("#form-error" ).html('');
	$('#seletedaddongrid tr').remove();
	var row = '<tr class="tr-grid-header"><td width="11%" nowrap = "nowrap" class="td-border-grid" align="left">Slno</td><td width="30%" nowrap = "nowrap" class="td-border-grid" align="left">Add - On</td><td width="46%" nowrap = "nowrap" class="td-border-grid" align="left">Remarks</td><td width="13%" nowrap = "nowrap" class="td-border-grid" align="left">Remove</td></tr><tr><td colspan="4" class="td-border-grid" id="messagerow"><div align="center" style="height:15px;"><strong><font color="#FF0000">No Records Avaliable</font></strong></div></td></tr>';
	$("#seletedaddongrid").append(row);
}

//save implementation assign days
function rafilesupdate(command)
{
	var form = $('#submitform');
	var error = $('#form-error2');

	var field =  $('#requrimentremarks');
	if(!field.val()) { error.html(errormessage("Enter the Remarks ")); field.focus(); return false;  }
	// var field = $('#link_value');
	// if(!field.val()) { error.html(errormessage("Select the file to upload. ")); field.focus(); return false; }

	else
	{
		var passData = "";
		if(command == 'saveraf')
		{
			
			passData =  "switchtype=rafilesave&lastslno=" + encodeURIComponent($('#lastslno').val()) + "&requrimentremarks=" + encodeURIComponent($('#requrimentremarks').val())+ "&link_value=" + encodeURIComponent($('#link_value').val())  +  "&impraflastslno=" + encodeURIComponent($('#impraflastslno').val())+"&customerreference=" + encodeURIComponent($('#customerlist').val()) + "&dummy=" + Math.floor(Math.random()*100000000);   
			
		}
		else
		{
			passData =  "switchtype=rafiledelete&impraflastslno=" + encodeURIComponent($('#impraflastslno').val())+"&customerreference=" + encodeURIComponent($('#customerlist').val())  + "&dummy=" + Math.floor(Math.random()*10000000000);//alert(passData)
		}
		queryString = '../ajax/implementation.php';
		ajaxobjext1 = $.ajax(
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
						var response = ajaxresponse;//alert(response)
						if(response['errorcode'] == '1')
						{
							error.html(successmessage(response['errormsg']));
							$('#rafslno').val(response['slnolist']);
							rafnewentry();
							generateraffilegrid('');
						}
						if(response['errorcode'] == '2')
						{
							error.html(successmessage(response['errormsg']));
							$('#rafslno').val(response['slnolist']);
							$('#deleterafslno').val(response['impraflastslno']);
							rafnewentry();
							generateraffilegrid('');
						}
					}

			},
			error: function(a,b)
			{
				error.html(scripterror());
			}
		});	
	}
}

function generateraffilegrid(startlimit)
{
	$('#form-error').html('');
	var form = $("#submitform");
	var passData = "switchtype=rafilegrid&lastslno=" + encodeURIComponent($('#customerlist').val()) + "&impref=" + encodeURIComponent($('#lastslno').val()) + "&startlimit=" + encodeURIComponent(startlimit)+ "&dummy=" + Math.floor(Math.random()*10054300000);//alert(passData)
	$("#tabgroupgridc1_1").html(getprocessingimage());	
	queryString = "../ajax/implementation.php";
	ajaxobjext225 = $.ajax(
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
					$("#tabgroupgridc1_1").html(response['grid']);//alert(response[4])
					if(response['fetchcount'] == 0)
						$("#raflag").val('');
					else
						$("#raflag").val('yes');
					$("#getmorerecordslink").html(response['linkgrid']);
					//$("#tabgroupcount").html(response[3]);
				}
				else
				{
					$("#form-error").html(errormessage('Unable to Connect...' ));
				}
			}
		},
		error: function(a,b)
		{
			$("#form-error").html(scripterror());
		}
	});		
}

function rafgridtoform(id)
{
	if(id != '')
	{
		var passData = "switchtype=rafgridtoform&implastslno=" + encodeURIComponent(id) + "&dummy=" + Math.floor(Math.random()*100032680100);
		$('#impraflastslno').val(id);
		$('#form-error2').html(getprocessingimage());
		var queryString = "../ajax/implementation.php";
		ajaxobjext66 = $.ajax(
		{
			type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
			success: function(ajaxresponse,status)
			{	
				$('#form-error2').html('');
				var response = ajaxresponse;
				if(response == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else if(response['errorcode'] == '1')
				{
					$('#attachement_raf').val(response['filename']);
					$('#requrimentremarks').val(response['remarks']);
					$('#link_value').val(response['attachfilepath']);
				} 
			},
			error: function(a,b)
			{
				$('#form-error1').html(scripterror());
			}
		});	
	}
	else
		$('#form-error1').html(scripterror());
}

//New form entry
function rafnewentry()
{
	var form = $('#submitform');
	$('#impraflastslno').val('');
	$('#attachement_raf').val('');
	$('#requrimentremarks').val('');

}

function resetfunc()
{
	var form = $('#submitform');
	passData =  "switchtype=resetfunc&customerreference=" + encodeURIComponent($('#customerlist').val()) + "&lastslno=" + encodeURIComponent($('#lastslno').val()) + "&dummy=" + Math.floor(Math.random()*100000000);//alert(passData)
	queryString = '../ajax/implementation.php';
	ajaxobjext898 = $.ajax(
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
			var response = ajaxresponse;
			if(response[0] == '1')
			{
				validatecustomer($('#customerlist').val());
			}
			else if(response[0] == '2')
			{
				invoicedetailsdisplay($("#customerlist option:selected").val());
				generateraffilegrid('');
				newaddonentry();
				resetvalue();
				$('#submitform')[0].reset();
			}
		},
		error: function(a,b)
			{
				error.html(scripterror());
			}
		});	
	   	
}

function approvedetails(type)
{
	var form = $('#'+ 'submitform');
	var form2 = $('#'+ 'filterform');
	var submitform = $('#approverejectform');
	if(type == 'approved')
	{
		if(! $('#appremarks').val()) { alert("Enter the Approval Remarks "); $('#appremarks').focus(); return false;  }
		var remarks = $('#appremarks').val();
		var error = $('#app-approval-error');
		if($('#collectedamt').is(':checked') == true && $('#displayadvanceremarks').is(':visible'))
		{
			if(!$('#advremarks').val()) { alert("Enter the Advances Remarks"); $('#appremarks').focus(); return false; }
		}
		var advremarks = $('#advremarks').val();
	}
	else if(type == 'rejected')
	{
		if(! $('#rejremarks').val()) { alert("Enter the Rejected Remarks "); $('#appremarks').focus(); return false;  }
		var remarks = $('#rejremarks').val();
		var error = $('#rej-approval-error');
		var advremarks = '';
	}
	

	passData =  "switchtype=approve&customerreference=" + encodeURIComponent($('#customerlist',form2).val()) + "&lastslno=" + encodeURIComponent($('#lastslno',form).val())+ "&remarks=" + encodeURIComponent(remarks)+ "&type=" + encodeURIComponent(type)+ "&advremarks=" + encodeURIComponent(advremarks) + "&dummy=" + Math.floor(Math.random()*100000000);//alert(passData)
	queryString = '../ajax/implementation.php';
	error.html(getprocessingimage());
	ajaxobjext666 = $.ajax(
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
				var response = ajaxresponse;
				if(response['errorcode'] == '3')
				{
					implemenationstatus($('#lastslno',form).val());
					$('#approvereject').attr('disabled',true);
					$('#approvereject').removeClass('swiftchoicebuttonbig');
					$('#approvereject').addClass('swiftchoicebuttondisabledbig');
					$('#save').attr('disabled',true);
					$('#save').removeClass('swiftchoicebutton');
					$('#save').addClass('swiftchoicebuttondisabled');
					
					$('#app-approval-error').html('');
					$().colorbox.close();
				}
				else if(response['errorcode'] == '2')
				{
					implemenationstatus($('#lastslno',form).val());
					$('#approvereject').attr('disabled',false);
					$('#approvereject').removeClass('swiftchoicebuttondisabledbig');
					$('#approvereject').addClass('swiftchoicebuttonbig');
					$('#save').attr('disabled',false);
					$('#save').removeClass('swiftchoicebuttondisabled');
					$('#save').addClass('swiftchoicebutton');
					
					$('#rej-approval-error').html('');
					$().colorbox.close();
				}
				else
				{
					$('#form-error').html(scripterror());
				}
		},
		error: function(a,b)
		{
			$('#form-error').html(scripterror());
		}
	});		
}

function approvevalidate()
{
	var form = $('#'+ 'submitform');
	var result = formsubmit('save','approve');
	if(result == false)
		return false;
	$("").colorbox({   height:"200px", inline:true, href:"#inline_example1", onLoad: function() { $('#cboxClose').hide()}});
	$('#displaytab2').hide();
	 $('#displaytab3').hide();
	 $('#displaytab1').show();
}

function implemenationstatus(lastslno)
{
	var form = $('#submitform');
	var passData =  "switchtype=implemenationstatus&lastslno=" + encodeURIComponent(lastslno) + "&dummy=" + Math.floor(Math.random()*100000000);//alert(passData)
	queryString = '../ajax/implementation.php';
	ajaxobjext547 = $.ajax(
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
			var response = ajaxresponse;//alert(ajaxresponse)
			if(response['errorcode'] == '1')
			{
				if(response['branchreject'] == 'no' && response['branchapproval'] == 'no' && response['coordinatorreject'] == 'no' && response['coordinatorapproval'] == 'no' && response['branchreject'] == 'no' && response['implementationstatus'] == 'pending')
				{
					$("#implementationid").html('Awaiting Branch Head Approval.');
					$('#assigndiv').hide();
					$('#advdisplay').hide();
					$('#remarksname').html('');
					$('#implementationremarks').html('');
					resetvalue();
					
				}
				else if(response['branchreject'] == 'yes'  && response['branchapproval'] == 'no' && response['implementationstatus'] == 'pending')
				{
					$("#implementationid").html('Fowarded back to Sales Person.');
					$('#assigndiv').hide();
					$('#advdisplay').hide();
					$('#remarksname').html('Branch Head Rejected Remarks:');
					$('#implementationremarks').html(response['branchrejectremarks']);
					
				}
				else if(response['branchapproval'] == 'yes'  && response['coordinatorreject'] == 'no' && response['coordinatorapproval'] == 'no' && response['implementationstatus'] == 'pending')
				{
					$("#implementationid").html('Awaiting Co-ordinator Approval.');
					$('#assigndiv').hide();
					$('#remarksname').html('Branch Head Remarks:');
					$('#implementationremarks').html(response['branchremarks']);
					if(response['advancecollected'] == 'no')
					{
						$('#advdisplay').show();
						$('#advremarksid').html(response['advancesnotcollectedremarks']);
					}
					else
					{
						$('#advdisplay').hide();
					}
				}
				else if(response['branchapproval'] == 'no' && response['coordinatorreject'] == 'yes' && response['coordinatorapproval'] == 'no' && response['implementationstatus'] == 'pending')
				{
					$("#implementationid").html('Fowarded back to Branch Head.');
					$('#assigndiv').hide();
					$('#advdisplay').hide();
					$('#remarksname').html('Co-ordinator Reject Remarks:');
					$('#implementationremarks').html(response['coordinatorrejectremarks']);
				}
				else if(response['branchapproval'] == 'yes' && response['coordinatorreject'] == 'no'  && response['coordinatorapproval'] == 'yes' && response['implementationstatus'] == 'pending' )
				{
					$("#implementationid").html('Implementation, Yet to be Assigned.');
					$('#assigndiv').hide();
					$('#advdisplay').hide();
					$('#remarksname').html('Co-ordinator Approval Remarks:');
					$('#implementationremarks').html(response['coordinatorapprovalremarks']);
				}
				else if(response['branchapproval'] == 'yes' && response['coordinatorreject'] == 'no'  && response['coordinatorapproval'] == 'yes' && response['implementationstatus'] == 'assigned' )
				{
					$("#implementationid").html('Assigned For Implementation.');
					$('#assigndiv').show();
					$('#advdisplay').hide();
					$('#assignid').val(response['tablegrid'])
					$('#remarksname').html('');
					$('#implementationremarks').html('');
				}
				else if(response['branchapproval'] == 'yes' && response['coordinatorreject'] == 'no'  && response['coordinatorapproval'] == 'yes' && response['implementationstatus'] == 'progess' )
				{
					$("#implementationid").html('Implementation in progess.');
					$('#assigndiv').hide();
					$('#advdisplay').hide();
					$('#remarksname').html('');
					$('#implementationremarks').html('');
				}
				else if(response['branchapproval'] == 'yes' && response['coordinatorreject'] == 'no'  && response['coordinatorapproval'] == 'yes' && response['implementationstatus'] == 'completed' )
				{
					$("#implementationid").html('Implementation Completed.');
					$('#assigndiv').hide();
					$('#advdisplay').hide();
					$('#remarksname').html('');
					$('#implementationremarks').html('');
					disablevalue();
				}
			}
		},
		error: function(a,b)
		{
			error.html(scripterror());
		}
	});		
}

function tooltip()
{
	if (ns6||ie) 
	{
		tipobj.style.display="block";
		tipobj.innerHTML = document.getElementById('assignid').value; 
		enabletip=true;
		return false;
	}
	
}


function checkvalidate()
{
	autochecknew($('#collectedamt'),'no');
	$('#linkdisplay').hide();
	$('#cfentrydiv').hide();
	$('#displayinvoicediv').hide();	
	
	$('#paymentamt').removeClass('swiftselect-mandatory');
	$('#paymentamt').addClass('diabledatefield');
	$('#paymentremarks').removeClass('swiftselect-mandatory');
	$('#paymentremarks').addClass('diabledatefield');
	

	autochecknew($('#sales_checkbox'),'no');
	$('#sales_name').attr('disabled',true);	
	$('#sales_emailid').attr('disabled',true);	
	$('#sales_mobile').attr('disabled',true);	
	$('#sales_commission').attr('disabled',true);	
	$('#sales_name').removeClass('swiftselect-mandatory');
	$('#sales_name').addClass('diabledatefield');
	$('#sales_emailid').removeClass('swiftselect-mandatory');
	$('#sales_emailid').addClass('diabledatefield');
	$('#sales_mobile').removeClass('swiftselect-mandatory');
	$('#sales_mobile').addClass('diabledatefield');
	$('#sales_commission').removeClass('swiftselect-mandatory');
	$('#sales_commission').addClass('diabledatefield');
	$('#sales_name').val('');
	$('#sales_emailid').val('');
	$('#sales_mobile').val('');
	$('#sales_commission').val('');

	autochecknew($('#sales_checkbox'),'no');
	$('#sales_noofemployee').attr('disabled',true);	
	$('#sales_noofemployee').removeClass('swiftselect-mandatory');
	$('#sales_noofemployee').addClass('diabledatefield');
	$('#sales_noofemployee').val('');

	autochecknew($('#attendance'),'no');
	$('#attendance_vendor').attr('disabled',true);	
	$('#attendance_errorfile').attr('disabled',true);
	$('#attendance_vendor').removeClass('swiftselect-mandatory');
	$('#attendance_vendor').addClass('diabledatefield');
	$('#attendance_vendor').val('');
	$('#attendance_errorfile').val('');
	$('#downloadlinkfile2').html('');
	
	autochecknew($('#shipment_invoice'),'no');
	$('#shipment_remarks').attr('disabled',true);	
	$('#invoice_link').hide();	
	$('#shipment_remarks').removeClass('swiftselect-mandatory');
	$('#shipment_remarks').addClass('diabledatefield');
	$('#shipment_remarks').val('');
	
	autochecknew($('#shipment_manual'),'no');
	$('#manual_remarks').attr('disabled',true);	
	$('#manual_link').hide();	
	$('#manual_remarks').removeClass('swiftselect-mandatory');
	$('#manual_remarks').addClass('diabledatefield');
	$('#manual_remarks').val('');
	
	autochecknew($('#customization'),'no');
	$('#customization_remarks').attr('disabled',true);	
	$('#customizationcustomerview').attr('disabled',true);	
	$('#customization_references').attr('disabled',true);	
	$('#customization_sppdata').attr('disabled',true);
	$('#customization_remarks').removeClass('swiftselect-mandatory');
	$('#customization_remarks').addClass('diabledatefield');
	$('#customization_remarks').val('');
	$('#customization_sppdata').val('');
	$('#customization_references').val('');
	autochecknew($('#customizationcustomerview'),'no');
	
	autochecknew($('#web_implementation'),'no');
	$('#web_remarks').attr('disabled',true);	
	$('#web_remarks').removeClass('swiftselect-mandatory');
	$('#web_remarks').addClass('diabledatefield');
	$('#web_remarks').val('');
}

function resetvalue()
{
	$('#assigndiv').hide();
	$('#approvereject').attr('disabled',false);
	$('#approvereject').removeClass('swiftchoicebuttondisabledbig');
	$('#approvereject').addClass('swiftchoicebuttonbig');
	$('#save').attr('disabled',false);
	$('#save').removeClass('swiftchoicebuttondisabled');
	$('#save').addClass('swiftchoicebutton');
	
	$('#saveraf').attr('disabled',false);
	$('#saveraf').removeClass('swiftchoicebuttondisabled');
	$('#saveraf').addClass('swiftchoicebutton');
	$('#new').attr('disabled',false);
	$('#new').removeClass('swiftchoicebuttondisabled');
	$('#new').addClass('swiftchoicebutton');
	$('#deleteraf').attr('disabled',false);
	$('#deleteraf').removeClass('swiftchoicebuttondisabled');
	$('#deleteraf').addClass('swiftchoicebutton');
	
	$('#add').attr('disabled',false);
	$('#add').removeClass('swiftchoicebuttondisabled');
	$('#add').addClass('swiftchoicebutton');
}

function disablevalue()
{
	$('#approvereject').attr('disabled',true);
	$('#approvereject').removeClass('swiftchoicebuttonbig');
	$('#approvereject').addClass('swiftchoicebuttondisabledbig');
	$('#save').attr('disabled',true);
	$('#save').removeClass('swiftchoicebutton');
	$('#save').addClass('swiftchoicebuttondisabled');
	
	$('#saveraf').attr('disabled',true);
	$('#saveraf').removeClass('swiftchoicebutton');
	$('#saveraf').addClass('swiftchoicebuttondisabled');
	$('#new').attr('disabled',true);
	$('#new').removeClass('swiftchoicebutton');
	$('#new').addClass('swiftchoicebuttondisabled');
	$('#deleteraf').attr('disabled',true);
	$('#deleteraf').removeClass('swiftchoicebutton');
	$('#deleteraf').addClass('swiftchoicebuttondisabled');
	
	$('#add').attr('disabled',true);
	$('#add').removeClass('swiftchoicebutton');
	$('#add').addClass('swiftchoicebuttondisabled');
}


function viewfilepath(filepath,formno)
{
	if(filepath != '')
		$('#'+'filepath'+formno).val(filepath);

	var form = $('#submitform');	
	$('#submitform').attr("action", "../ajax/downloadfile.php?id="+formno+"") ;
	//$('#submitform').attr( 'target', '_blank' );
	$('#submitform').submit();
}


function generatecustomization(startlimit)
{
	var form = $("#submitform");
	var passData = "switchtype=customizationgrid&imprslno="+ encodeURIComponent($('#lastslno').val())+ "&startlimit=" + encodeURIComponent(startlimit);//alert(passData)
	var queryString = "../ajax/implementation.php";
	ajaxobjext875 = $.ajax(
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
						$('#tabgroupgridc1_2').html(response['grid']);
						$('#tabgroupgridc2link').html(response['linkgrid']);
					}
					else if(response['errorcode'] == '2')
					{
						$('#tabgroupgridc1_2').html(scripterror());
					}
				}
			}, 
			error: function(a,b)
			{
				$('#form-error').html(scripterror());
			}
		});		
				
}


function getmorecustomerregistration(startlimit,slno,showtype)
{
	var form = $('#submitform');
	var passData = "switchtype=customizationgrid&imprslno="+ encodeURIComponent($('#lastslno').val()) + "&startlimit=" + startlimit+ "&slno=" + slno+ "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);
//alert(passData);
	var queryString = "../ajax/implementation.php";
	$('#form-error').html(getprocessingimage());
	ajaxobjext3875 = $.ajax(
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
					var response = ajaxresponse;//alert(response)
					if(response['errorcode'] == '1')
					{
						$('#form-error').html('');
						$('#regresultgrid2').html($('#tabgroupgridc1_2').html());
						$('#tabgroupgridc1_2').html($('#regresultgrid2').html().replace(/\<\/table\>/gi,'')+ response['grid']) ;
						$('#tabgroupgridc2link').html(response['linkgrid']);
					}
					else
					if(response['errorcode'] == '2')
					{
						$('#tabgroupgridc1_1').html(scripterror());
					}
				}
				
			}, 
			error: function(a,b)
			{
				$('#form-error').html(scripterror());
			}
		});		
}


/*function approvedmail(lastslno)
{
	var error = $('#form-error');
	var passData = "switchtype=appovedmail&lastslno=" + encodeURIComponent(lastslno);//alert(passData)
	error.html(getprocessingimage());//alert(passData)
	var queryString = "../ajax/implementation.php";
	ajaxobjext144 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			error.html('');
			var response = ajaxresponse;//alert(response)
			if(response == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response1 = response;
				if(response1['errorcode'] == '1')
				{
					return true;
				}
			}
		}, 
		error: function(a,b)
		{
			error.html(scripterror());
		}
	});		
}*/

function updatedmail(lastslno)
{
	var error = $('#form-error');
	var passData = "switchtype=updatedmail&lastslno=" + encodeURIComponent(lastslno);
	error.html(getprocessingimage());//alert(passData)
	var queryString = "../ajax/implementation.php";
	ajaxobjext146 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			error.html('');
			var response = ajaxresponse;//alert(response)
			if(response == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response1 = response;
				if(response1['errorcode'] == '1')
				{
					return true;
				}
			}
			},
			error: function(a,b)
			{
				error.html(scripterror());
			}
	});			
}


function shippmentmail(type)
{
	var error = $('#error-'+type);
	if(type == 'invoice')
	{
		var field = $("#shipment_remarks" );
		if(!field.val()) { error.html(errormessage("Enter the Invoice Remarks")); field.focus(); return false; }
		var remarks = $("#shipment_remarks" ).val();
	}
	else if(type == 'manual')
	{
		var field = $("#manual_remarks" );
		if(!field.val()) { error.html(errormessage("Enter the Manual Remarks")); field.focus(); return false; }
		var remarks = $("#manual_remarks" ).val();
	}
	var passData = "switchtype=shipinvoicemail&customerid=" + encodeURIComponent($('#customerid').val())+"&type=" + encodeURIComponent(type)+"&remarks=" + encodeURIComponent(remarks);
	error.html(getprocessingimage());//alert(passData)
	var queryString = "../ajax/implementation.php";
	ajaxcall1981 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			error.html('');
			var response = ajaxresponse;//alert(response)
			if(response == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response1 = response;
				if(response1['errorcode'] == '1')
				{
					if(type == 'manual')
					{
						$('#error-'+type).html(successmessage(response1['result']));
					}
					else if(type == 'invoice')
					{
						$('#error-'+type).html(successmessage(response1['result']));
					}
				}
			}
		}, 
		error: function(a,b)
		{
			error.html(scripterror());
		}
	});		
}


//Search customer by customer id
function searchbycustomerid(cusid)
{
	var form = $('#submitform');
	$('#submitform')[0].reset();
	var passData = "switchtype=searchbycustomerid&customerid=" + encodeURIComponent(cusid) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
	$('#form-error').html(getprocessingimage());
	var queryString = "../ajax/implementation.php";
	ajaxcall5 = $.ajax(
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
				$('#form-error').html('');
				var response = (ajaxresponse); 
				if(response['errorcode'] == 2)
				{
					alert('Customer Not Available.');
				}
				else
				{
					$('#detailsearchtext').val(response['businessname']);
					selectacustomer(response['businessname']);
					$('#customerlist').val(response['slno']);
					$('#assigndiv').hide();
					$('#displaydetails').hide();
					$('#displayimplementation').hide();
					$('#displaytext').show();
					checkvalidate();
					validatecustomer(response['slno']);
				}
			}
		}, 
		error: function(a,b)
		{
			$('#form-error').html(scripterror());
		}
	});		
}

function displayDiv()
{
	$("#filterdiv").toggle();
}


var totalarray = new Array();
var customerarray = new Array();

function advancesearchimplementer()
{
	var form = $('#searchfilterform');
	var error = $('#filter-form-error'); 
	var textfield = $('#searchcriteria').val();
	var subselection = $("input[name='databasefield']:checked").val();
	var values = validatestatuscheckboxes();
	if(values == false)	{$('#filter-form-error').html(errormessage("Select A Status")); return false;	}

	var c_value = '';
	var newvalue = new Array();
	var chks = $("input[name='summarize[]']");
	for (var i = 0; i < chks.length; i++)
	{
		if ($(chks[i]).is(':checked'))
		{
			c_value += $(chks[i]).val()+ ',';
		}
	}
	var statuslist = c_value.substring(0,(c_value.length-1));
	
	var passData = "switchtype=searchcustomerlist&databasefield=" + encodeURIComponent(subselection) + "&state=" + encodeURIComponent($("#state2").val())  + "&region=" +encodeURIComponent($("#region2").val())+ "&district=" +encodeURIComponent($("#district2").val()) + "&textfield=" +encodeURIComponent(textfield) +  "&dealer2=" +encodeURIComponent($("#currentdealer2").val()) + "&branch2=" + encodeURIComponent($("#branch2").val())+"&type2=" +encodeURIComponent($("#type2").val()) + "&category2=" + encodeURIComponent($("#category2").val())+ "&statuslist=" + encodeURIComponent(statuslist)+ "&dummy=" + Math.floor(Math.random()*10054300000);
//	alert(passData)
	$('#customerselectionprocess').html(getprocessingimage());
	queryString = "../ajax/implementation.php";
	ajaxcall6 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			var response = ajaxresponse;
			if(response == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				if(response == '')
				{
					$('#filterdiv').show();
					customersearcharray = new Array();
					for( var i=0; i<response.length; i++)
					{
						customersearcharray[i] = response[i];
					}
					flag = false;
					searchcustomerlist1();
					$('#customerselectionprocess').html(errormessage("Search Result"  + '<span style="padding-left: 15px;"><img src="../images/close-button.jpg" width="15" height="15" align="absmiddle" style="cursor: pointer; padding-bottom:2px" onclick="displayalcustomer()"></span> '));
					$('#totalcount').html('0');
					error.html(errormessage('No datas found to be displayed.'));
					$('#displaydetails').hide();
					$('#displayimplementation').hide();
					$('#displaytext').show();
				}
				else 
				{
					$('#filterdiv').hide();
					customersearcharray = new Array();
					for( var i=0; i<response.length; i++)
					{
						customersearcharray[i] = response[i];
					}
					flag = false;
					searchcustomerlist1();
					$('#customerselectionprocess').html(successmessage('<span style="padding-bottom:0px">Search Result </span>   ' + '<span style="padding-left: 15px;"><img src="../images/close-button.jpg" width="15" height="15" align="absmiddle" style="cursor: pointer; padding-bottom:2px" onclick="displayalcustomer()"></span>'));
					$('#totalcount').html(customersearcharray.length);
					$('#filter-form-error').html('');
					$('#displaydetails').hide();
					$('#displayimplementation').hide();
					$('#displaytext').show();
				}
			}
		}, 
		error: function(a,b)
		{
			error.html(scripterror());
		}
	});		
}


function searchcustomerlist1()
{	
	var form = $("#searchfilterform");
	//document.getElementById('customerselectionprocess').innerHTML = '';
	var selectbox = $('#customerlist');
	var numberofcustomers = customersearcharray.length;
	$("#detailsearchtext").focus();
	var actuallimit = 500;
	var limitlist = (numberofcustomers > actuallimit)?actuallimit:numberofcustomers;
	$('option', selectbox).remove();
	var options = selectbox.attr('options');
	
	for( var i=0; i<limitlist; i++)
	{
		var splits = customersearcharray[i].split("^");
		options[options.length] = new Option(splits[0], splits[1]);
	}
}


function resetDefaultValues(oForm)
{
    var elements = oForm.elements; 
 	oForm.reset();
	$("#filter-form-error").html('');
	for(var i=0; i<elements.length;i++) 
	{
		field_type = elements[i].type.toLowerCase();//alert(field_type)
	}
	
	switch(field_type)
	{
	
		case "text": 
			elements[i].value = ""; 
			break;
		case "radio":
			if(elements[i].checked == 'databasefield1')
			{
				elements[i].checked = true;
			}
			else
			{
				elements[i].checked = false; 
			}
			break;
		case "checkbox":
  			if (elements[i].checked) 
			{
   				elements[i].checked = true; 
			}
			break;
		case "select-one":
		{
  			 for (var k=0, l=oForm.elements[i].options.length; k<l; k++)
			 {
             	oForm.elements[i].options[k].selected = oForm.elements[i].options[k].defaultSelected;
			 }
		}
			break;

		default: $('#districtcodedisplaysearch').html('<select name="district2" class="swiftselect" id="district2" style="width:180px;"><option value="">ALL</option></select>');
			break;
	}
}

function displayalcustomer()
{	
	var form = $("#submitform");
	flag = true;
	var selectbox = $('#customerlist');
	$('#customerselectionprocess').html(successsearchmessage('All Customers...'));
	var numberofcustomers = customerarray.length;
	$("#detailsearchtext").focus();
	var actuallimit = 500;
	var limitlist = (numberofcustomers > actuallimit)?actuallimit:numberofcustomers;

	$('option', selectbox).remove();
	var options = selectbox.attr('options');

	for( var i=0; i<limitlist; i++)
	{
		var splits = customerarray[i].split("^");
		options[options.length] = new Option(splits[0], splits[1]);
	}
	$('#totalcount').html(customerarray.length);
	
}

function searchbystatus()
{
	var form = $("#filterform");
	var passData =  "switchtype=filtercustomerlist&impsearch=" + encodeURIComponent($('#imp_status').val())+ "&dummy=" + Math.floor(Math.random()*100000000);//alert(passData)
	$('#customerselectionprocess').html(getprocessingimage());
	queryString = "../ajax/implementation.php";
	ajaxobjext6662 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			var response = ajaxresponse;//alert(ajaxresponse)
			if(response == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				searcharray = new Array();
				for( var i=0; i<response.length; i++)
				{
					searcharray[i] = response[i];
				}
				filtercustomerlist2();
				$("#customerselectionprocess").html('');
				$('#displayfilter').hide();
				$("#totalcount").html(searcharray.length);
			}
		},
		error: function(a,b)
		{
			$('#customerselectionprocess').html(scripterror());
		}
	});		
			
}

function filtercustomerlist2()
{	
	var form = $('#submitform');
	var selectbox = $('#customerlist');
	var numberofcustomers = searcharray.length;
	$('#detailsearchtext').focus();
	var actuallimit = 500;
	var limitlist = (numberofcustomers > actuallimit)?actuallimit:numberofcustomers;
	$('option', selectbox).remove();
	var options = selectbox.attr('options');
	
	for( var i=0; i<limitlist; i++)
	{
		var splits = searcharray[i].split("^");
		options[options.length] = new Option(splits[0], splits[1]);
	}
	
}


function displayfilterdiv()
{
	if($('#displayfilter').is(':visible'))
		$('#displayfilter').hide();
	else
		$('#displayfilter').show();
}


function advcollectedmail(type)
{
	
	var field = $('#collectedamt:checked').val();
	if(field != 'on') var collectedamt = 'no'; else collectedamt = 'yes';
	if(collectedamt == 'yes')
	{
		if(type == 'advamount')
		{
			var error = $("#advance-error1");
			var field = $("#paymentamt");
			if(!field.val()) { error.html(errormessage("Enter the Payment Amount")); field.focus(); return false;field.scroll(); }
			if(field.val())	{ if(!validateamount(field.val())) { error.html(errormessage('Amount is not Valid.'));field.focus();field.scroll(); return false; } }
			var field = $("#paymentremarks.cf" );
			if(!field.val()) { error.html(errormessage("Enter the Payment Remarks")); field.focus(); return false; }
		}
		else
		{
			var error = $("#advance-error2");
			var field = $("#receipt");
			if(!field.val()) { error.html(errormessage("Select the Receipt")); field.focus(); return false;field.scroll(); }
			var field = $("#paymentremarks.invoice" );
			if(!field.val()) { error.html(errormessage("Enter the Payment Remarks")); field.focus(); return false; }
		}
		var passData = "switchtype=advcollectmail&customerid=" + encodeURIComponent($('#customerid').val())+ "&lastslno=" + encodeURIComponent($('#lastslno').val())+ "&type=" + encodeURIComponent(type);
		error.html(getprocessingimage());//alert(passData)
		var queryString = "../ajax/implementation.php";
		ajaxcall185488 = $.ajax(
		{
			type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
			success: function(ajaxresponse,status)
			{	
				error.html('');
				var response = ajaxresponse;//alert(response)
				if(response == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					var response1 = response;
					if(response1['errorcode'] == '1')
					{
						error.html(successmessage(response1['errormsg']));
					}
				}
			}, 
			error: function(a,b)
			{
				error.html(scripterror());
			}
		});	
	}
}



function displayapproverejdiv(divtype)
{
	var form = $('#'+ 'submitform');
	var submitform = $('#approverejectform');
	
	if(divtype == 'approvetype')
	{
		 $('#displaytab1').hide();
		 $('#displaytab3').hide();
		 $('#displaytab2').show();
		 if($('#collectedamt',form).is(':checked')== true)
		{
			$("").colorbox({ height:"250px",inline:true, href:"#inline_example1", onLoad: function() { $('#cboxClose').hide()}});
			$('#displayadvanceremarks1').hide();
			$('#appremarks').val('');
			$('#advremarks').val('');
		}
		else
		{
			$("").colorbox({ height:"300px", inline:true, href:"#inline_example1" , onLoad: function() { $('#cboxClose').hide()}});
			$('#displayadvanceremarks1').show();	
			$('#appremarks').val('');
			$('#advremarks').val('');
		}
		 
	}
	else if(divtype == 'rejecttype')
	{
		 $('#displaytab1').hide();
		 $('#displaytab2').hide();
		 $('#displaytab3').show();
		 $('#displayadvanceremarks2').hide();
	}
}

function invoicecfdetailstoform(implslno)
{
	var error = $("#invoice_error");
	var passData = "switchtype=invoicecfdetailstoform&implslno=" + encodeURIComponent(implslno);
	error.html(getprocessingimage());//alert(passData)
	var queryString = "../ajax/implementation.php";
	ajaxcall189= $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			error.html('');
			var response = ajaxresponse;
			if(response == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response1 = response;
				//alert(response1['productcode']);
				if(response1['errorcode'] == '1')
				{
					$('#displayinvoicediv').show();
					$('#displayinvoicecfdiv').hide();
					$('#adddescriptioncfrows tr').remove();
					var rowcount = $('#adddescriptionrows tr').length;
					$('#adddescriptionrows tr').remove();
					$('#imp_statustype').val(response1['implementationtype']);
					$('#imptype_remarks').val(response1['impremarks']);
					// if($('#imp_statustype option:selected').text() == "Others")
					// {
					// 	$('#remarksdiv').show();
					// 	$('#imptype_remarks').val(response1['impremarks']);
					// }
					$('#finalinvoice').val(response1['invoicenumber']);
					var row = '<tr><td width="21%" ><strong>Selected Invoice: </strong></td><td width="20%"><input type="text" name="invoicedetails"  class="swiftselect-mandatory" disabled="disabled" id="invoicedetails" size="20"  value="'+ response1['invoicenumber']+'"  /></td><td width="59%"><div style="text-align:left"><a onclick="invoicedetailsdisplay(\'' + response1['customerreference'] + '\');"  class="r-text">Change &#8250;&#8250;</a></div></td></tr><tr ><td colspan="3">'+ response1['grid']+'</td> </tr>';
					$("#adddescriptionrows").append(row);
					autochecknew($("#collectedamt"),response1['advancecollected']);
					
					$("#cfentrydiv").hide();
					$("#invoiceentrydiv").show();
					$("#receiptdisplay").html(response1['receiptgrid']);
					$("#paymentremarks.invoice").val(response1['advanceremarks']);
					$("#productcode").val(response1['productcode']);
					checkboxvalidation('displaypayment');
				}
				else if(response1['errorcode'] == '2')
				{
					$('#displayinvoicediv').show();
					$('#displayinvoicecfdiv').hide();
					$('#adddescriptioncfrows tr').remove();
					var rowcount = $('#adddescriptionrows tr').length;
					$('#adddescriptionrows tr').remove();
					$('#finalinvoice').val(response1['invoicenumber']);
					var row = '<tr><td width="21%" ><strong>Selected Invoice: </strong></td><td width="20%"><input type="text" name="invoicedetails"  class="swiftselect-mandatory" disabled="disabled" id="invoicedetails" size="20"  value="'+ response1['invoicenumber']+'"  /></td><td width="59%"><div style="text-align:left"><a onclick="invoicedetailsdisplay(\'' + response1['customerreference'] + '\');"  class="r-text">Change &#8250;&#8250;</a></div></td></tr><tr ><td colspan="3">'+ response1['grid']+'</td> </tr>';
					$("#adddescriptionrows").append(row);
					autochecknew($("#collectedamt"),response1['advancecollected']);
					$("#cfentrydiv").show();
					$("#invoiceentrydiv").hide();
					checkboxvalidation('displaypayment');
					$("#paymentamt").val(response1['advanceamount']);
					$("#paymentremarks.cf").val(response1['advanceremarks']);
					$("#productcode").val(response1['productcode']);
					checkboxvalidation('displaypayment');
				}
			}
		}, 
		error: function(a,b)
		{
			error.html(scripterror());
		}
	});	
}



//Function to view the bill in pdf format----------------------------------------------------------------
function viewinvoice(slno)
{
	if(slno != '')
		$('#implementationslno').val(slno);
		
	var form = $('#submitform');	
	if($('#implementationslno').val() == '')
	{
		$('#form-error').html(errormessage('Please select a Customer.')); return false;
	}
	else
	{
		$('#submitform').attr("action", "../ajax/viewinvoicepdf.php") ;
		$('#submitform').attr( 'target', '_blank' );
		$('#submitform').submit();
	}
}

function viewmatrixinvoice(slno)
{
	if(slno != '')
		$('#implementationslno').val(slno);
		
	var form = $('#submitform');	
	if($('#implementationslno').val() == '')
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


function validatestatuscheckboxes()
{
	var chksvalue = $("input[name='summarize[]']");
	var hasChecked = false;
	for (var i = 0; i < chksvalue.length; i++)
	{
		if ($(chksvalue[i]).is(':checked'))
		{
			hasChecked = true;
			return true
		}
	}
	if(!hasChecked)
	{
		return false
	}
}