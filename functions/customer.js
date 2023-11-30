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

//Function to search the records -Meghana-[23/12/2009]
function searchdealerarray()
{
	var form = $('#searchfilterform');
	var error = $('#filter-form-error'); 
	var values = validateproductcheckboxes();
	if(values == false)	{error.html(errormessage("Select A Product")); return false;}
	var textfield = $('#searchcriteria').val();
	var subselection = $("input[name='databasefield']:checked").val();
	var c_value = '';
	var newvalue = new Array();
	var chks = $("input[name='productarray[]']");
	for (var i = 0; i < chks.length; i++)
	{
		if ($(chks[i]).is(':checked'))
		{
			c_value += "'" + $(chks[i]).val() + "'" + ',';
		}
	}
	
	var productslist = c_value.substring(0,(c_value.length-1));
	
	var passdata = "switchtype=searchcustomerlist&databasefield=" + encodeURIComponent(subselection) + "&state=" + encodeURIComponent($("#state2").val())  + "&region=" +encodeURIComponent($("#region2").val())+ "&district=" +encodeURIComponent($("#district2").val()) + "&textfield=" +encodeURIComponent(textfield) +  "&productscode=" +encodeURIComponent(productslist) +"&dealer2=" +encodeURIComponent($("#currentdealer2").val()) + "&branch2=" + encodeURIComponent($("#branch2").val())+"&type2=" +encodeURIComponent($("#type2").val()) + "&category2=" + encodeURIComponent($("#category2").val())+ "&dummy=" + Math.floor(Math.random()*10054300000);
	//alert(passData)
	$('#customerselectionprocess').html(getprocessingimage());
	queryString = "../ajax/customer.php";
	ajaxobjext146 = $.ajax(
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
					var response = ajaxresponse;//alert(response)
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
	var selectbox = $('#customerlist');
	var numberofcustomers = customersearcharray.length;
	$("#detailsearchtext").focus();
	$('input.focus_redclass,select.focus_redclass,textarea.focus_redclass').removeClass("css_enter1"); 
	$('input.focus_redclass,select.focus_redclass,textarea.focus_redclass').removeClass("checkbox_enter1");
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

function displaysurrender(pin,lastslno)
{
	if($('#lastslno').val() != '')
	{
		surrenderhistory(pin,lastslno);
		$("").colorbox({ inline:true, href:"#surrendergrid", onLoad: function() { $('#cboxClose').hide()}});
	}
	else
	{
		return false;
	}
}

function surrenderhistory(pin,customerid)
{
	var form = $('#submitform');
	$("#lastslno").val(customerid);
	var passData = "switchtype=surrenderhistory&lastslno="+ encodeURIComponent(customerid)
	+ "&pin=" + encodeURIComponent(pin);
	//alert(passData);
	var queryString = "../ajax/customer.php";
	$("#surrendergridc7").html('');
	$('#surrendergridc7').html(getprocessingimage());
	ajaxcall14 = $.ajax(
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
					//alert(response[1]);
					$("#surrendergridc7").html(response[1]);
				}
				/*else{ $("#surrendergridc7").html(scripterror()); }*/
			}
		}, 
		error: function(a,b)
		{
			$("#surrendergridc7").html(scripterror());
		}
	});	
}


function gettotalcustomercount()
{
	var form = $('#customerselectionprocess');
	var passData = "switchtype=getcustomercount&dummy=" + Math.floor(Math.random()*10054300000);
	queryString = "../ajax/customer.php";
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
				var response = ajaxresponse;
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
	if(limit == 0)
		limit = 1;
	//alert(limit);
	var startindex = 0;
		var startindex1 = (limit)+1;
	var startindex2 = (limit*2)+1;
	var startindex3 = (limit*3)+1;
	var passData = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit+1) + "&startindex=" + encodeURIComponent(startindex);//alert(passData)
	var passData1 = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex1);//alert(passData1)
	var passData2 = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex2);//alert(passData2)
	var passData3 = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex3);//alert(passData3)
	$('#customerselectionprocess').html(getprocessingimage());
	queryString = "../ajax/customer.php";
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
	
	queryString = "../ajax/customer.php";
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

	queryString = "../ajax/customer.php";
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
	
	queryString = "../ajax/customer.php";
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
	$('input.focus_redclass,select.focus_redclass,textarea.focus_redclass').removeClass("css_enter1"); 
	$('input.focus_redclass,select.focus_redclass,textarea.focus_redclass').removeClass("checkbox_enter1");
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

function displayalcustomer()
{	
	var form = $("#submitform");
	flag = true;
	var selectbox = $('#customerlist');
	$('#customerselectionprocess').html(successsearchmessage('All Customers...'));
	var numberofcustomers = customerarray.length;
	$("#detailsearchtext").focus();
	$('input.focus_redclass,select.focus_redclass,textarea.focus_redclass').removeClass("css_enter1"); 
	$('input.focus_redclass,select.focus_redclass,textarea.focus_redclass').removeClass("checkbox_enter1");
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

//Function select the tab in display-Meghana[18/12/2009]
function displayDiv()
{
	$("#filterdiv").toggle();
}

function formsubmit(command)
{
	var form = $("#submitform" );
	$('#save').removeClass('button_enter1');
	var error = $("#form-error" );
	var phonevalues = '';
	var cellvalues = '';
	var emailvalues = '';
	var namevalues = '';
	var state_gst_code = '';
	var sez_enabled = '';
	
	
	tabopen5('1','tabg1');
	var field = $("#businessname" );
	if(!field.val()) { error.html(errormessage("Enter the Business Name [Company]. ")); field.focus(); return false; }
	if(field.val()) { if(!validatebusinessname(field.val())) { error.html(errormessage('Business name contains special characters. Please use only Alpha / Numeric / space / hyphen / small brackets. ')); field.focus(); return false; } }
	
	    state_gst_code = $("#state_gst").html();
		state_gst_code = $.trim(state_gst_code);
	    //alert(state_gst_code);
	
	    var field = $("#gst_no");
	    var fieldval = field.val();
		//if(!field.val()) { error.html(errormessage("Enter the Business Name [Company]. ")); field.focus(); return false; }

		if(fieldval!= "")
		{
			if(field.val()) { if(!validategstin(field.val())) { error.html(errormessage('For GSTIN only Alpha / Numeric are allowed.')); field.focus(); return false; } }
			if(field.val()) { if(fieldval.length != 15) { error.html(errormessage('GSTIN should be 15 chars.')); field.focus(); return false; } }
			if(field.val()) { if(!validategstinregex(field.val(),state_gst_code)) { error.html(errormessage('State GST Code Not Matching.')); field.focus(); return false; } }
			var gstinformat = new RegExp('^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}[0-9A-Z]{2}$|^[0-9]{2}[A-Z]{4}[0-9]{5}[A-Z]{1}[1-9A-Z]{1}[0-9A-Z]{2}$');
            if (!gstinformat.test(field.val())) { alert('State GST Code Not in Format.'); field.focus(); return false; }

            var effective_from = $("#effective_from").val();
		    //if(effective_from == '') { error.html(errormessage('Please Select Date.')); newgstin(); $("#effective_from").focus(); return false; }

		}
		
	var rowcount = $('#adddescriptionrows tr').length;
	tabopen5('2','tabg1');
	var l=1;
	while(l<=rowcount)
	{
		if(!$("#selectiontype1").val())
		{
				error.html(errormessage("Minimum of ONE contact detail is mandatory"));return false;
		}
		else
		var field = $("#"+'selectiontype'+l);
		if(!field.val()) { error.html(errormessage("Select the Type. ")); field.focus(); return false; }
		var field = $("#"+'phone'+l);
		if(field.val()) { if(!validatephones(field.val())) { error.html(errormessage('Enter the valid Phone Number.')); field.focus(); return false; } }
		var field = $("#"+'cell'+l);
		if(field.val()) { if(!cellvalidation(field.val())) { error.html(errormessage('Enter the valid Cell Number.')); field.focus(); return false; } }
		var field = $("#"+'emailid'+l);
		if(field.val()) { if(!checkemail(field.val())) { error.html(errormessage('Enter the valid Email Id.')); field.focus(); return false; } }
		var field = $("#"+'name'+l);
		if(field.val()) { if(!contactpersonvalidate(field.val())) { error.html(errormessage('Contact person name contains special characters. Please use only Numeric / space.')); field.focus(); return false; } }
		l++;
		
	}
	for(j=1;j<=rowcount;j++)
	{
		var typefield = $("#"+'selectiontype'+j);

		var namefield = $("#"+'name'+j);
		if(namevalues == '')
			var namevalues = namefield.val();
		else
			var namevalues = namevalues + '~' + namefield.val();
		var phonefield = $("#"+'phone'+j);
		if(phonevalues == '')
			var phonevalues = phonefield.val();
		else
			var phonevalues = phonevalues + '~' + phonefield.val();
		var cellfield = $("#"+'cell'+j);
		if(cellvalues == '')
			var cellvalues = cellfield.val();
		else
			var cellvalues = cellvalues + '~' + cellfield.val();
		var emailfield = $("#"+'emailid'+j);
		if(emailvalues == '')
			var emailvalues = emailfield.val();
		else
			var emailvalues = emailvalues + '~' + emailfield.val();
		
		var slnofield = $("#"+'contactslno'+j);
		if(j == 1)
			contactarray = typefield.val() + '#' + namefield.val() + '#' +phonefield.val() + '#' + cellfield.val() + '#' + emailfield.val() + '#' + slnofield.val();
		else
			contactarray = contactarray + '****' + typefield.val()  + '#' + namefield.val() + '#' +phonefield.val() + '#' + cellfield.val() + '#' + emailfield.val() + '#' + slnofield.val();
	}

	if(namevalues == '')
		{error.html(errormessage("Enter Atleast One Contact Person Name."));return false;}
	if(phonevalues == '')
		{error.html(errormessage("Enter Atleast One Phone Number."));return false;}
	if(cellvalues == '')
		{error.html(errormessage("Enter Atleast One Cell Number."));return false;}
	if(emailvalues == '')
		{error.html(errormessage("Enter Atleast One Email Id."));return false;}

	var field = $("#place" );
	if(!field.val()) { error.html(errormessage("Enter the Place. ")); field.focus(); return false; }
	var field = $("#state" );
	if(!field.val()) { error.html(errormessage("Select the State. ")); field.focus(); return false; }
	var field = $("#district" );
	if(!field.val()) { error.html(errormessage("Select the District. ")); field.focus(); return false; }
	var field = $("#pincode" );
	if(!field.val()) { error.html(errormessage("Enter the PinCode. ")); field.focus(); return false; }
	if(field.val()) { if(!validatepincode(field.val())) { error.html(errormessage('Enter the valid PIN Code.')); field.focus(); return false; } }
	var field = $("#stdcode");
	if(field.val()) { if(!validatestdcode(field.val())) { error.html(errormessage('Enter the valid STD Code.')); field.focus(); return false; } }
	var field = $("#fax");
	if(field.val()) { if(!validatephone(field.val())) { error.html(errormessage('Enter the valid Fax Number.')); field.focus(); return false; } }
	//Website validation - Rashmi -18/11/09
	var field = $("#website");
	if(field.val())	{ if(!validatewebsite(field.val())) { error.html(errormessage('Enter the valid Website.')); field.focus(); return false; } }

		var field = $("#category");
		if(!field.val()) { error.html(errormessage("Select a Category.")); field.focus(); return false; }

		var field = $("#type");
		if(!field.val()) { error.html(errormessage("Select a Type.")); field.focus(); return false; }

	var field = $('#companyclosed:checked').val();
	if(field != 'on') var companyclosed = 'no'; else companyclosed = 'yes';
	var field = $('#promotionalsms:checked').val();
	if(field != 'on') var promotionalsms = 'no'; else promotionalsms = 'yes';
	var field = $('#promotionalemail:checked').val();
	if(field != 'on') var promotionalemail = 'no'; else promotionalemail = 'yes';

    var sez_enabled = $('input[name=sez_enabled]:checked').val();

		var passdata = "";
		if(command == 'save')
		{
			passdata =  "switchtype=save&businessname=" + encodeURIComponent($("#businessname" ).val())+ "&tanno=" + encodeURIComponent($('#tanno').val())  + "&gst_no=" + encodeURIComponent($("#gst_no").val())  + "&sez_enabled=" + encodeURIComponent(sez_enabled) + "&customerid=" + encodeURIComponent($('#customerid').val())  + "&address=" + encodeURIComponent($('#address').val()) + "&place=" + encodeURIComponent($('#place').val()) + "&pincode=" + encodeURIComponent($('#pincode').val()) + "&district=" + encodeURIComponent($('#district').val())  + "&category=" + encodeURIComponent($('#category').val()) + "&type=" + encodeURIComponent($('#type').val()) + "&stdcode=" + encodeURIComponent($('#stdcode').val()) + "&fax=" + encodeURIComponent($('#fax').val()) + "&companyclosed=" + encodeURIComponent(companyclosed) + "&promotionalsms=" + encodeURIComponent(promotionalsms) + "&gst_no=" + encodeURIComponent($("#gst_no").val())  + "&promotionalemail=" + encodeURIComponent(promotionalemail) + "&website=" + encodeURIComponent($('#website').val()) + "&remarks=" + encodeURIComponent($('#remarks').val())  + "&lastslno=" + encodeURIComponent($('#lastslno').val()) + "&contactarray=" + encodeURIComponent(contactarray)+ "&totalarray=" + encodeURIComponent(totalarray)+"&effective_from="+encodeURIComponent(effective_from)+ "&dummy=" + Math.floor(Math.random()*100000000);//alert(passData)

		}
		else
		{
				alert('You are using a wrong provision.');
				return false;
		}
		queryString = '../ajax/customer.php';
		error.html('<img src="../images/imax-loading-image.gif" border="0" />');
		ajaxcall0 = $.ajax(
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
					if(response['successcode'] == '1')
					{
						error.html(successmessage(response['successmessage']));
						gettotalcustomercount();
						newentry();
						rowwdelete();
					}
					else if(response['successcode'] == '2')
					{
						error.html(successmessage(response['successmessage']));
						gettotalcustomercount();
						newentry();
						rowwdelete();
					}
					else
					{
						error.html(errormessage('Unable to Connect....'));
					}

				}, 
				error: function(a,b)
				{
					error.html(scripterror());
				}
	});		
}

function forcesurrenderdetails(command)
{
	var form = $('#forcesurrender');
	var error = $("#surrender-form-error");
	var field = $('#emailtocustomer:checked').val();
	if(field != 'on'){ var emailtocustomer = 'no';} else{ emailtocustomer = 'yes'; }
	
	if(command =='forcesurrender')
	{
		var field = $("#forceremarks" );
		if(!field.val()) { error.html(errormessage("Enter the Remarks.")); field.focus(); return false; }
		
		var confirmation = confirm("Are you sure you want to Surrender the selected Pin number?");
		if(confirmation)
		{
			var passData = "switchtype=forcesurrenderdetails&switchforce="+command+
			"&refslno="+ encodeURIComponent($("#forsurrender").val()) +
			"&forceremarks="+ encodeURIComponent($("#forceremarks").val()) +
			"&emailtocustomer="+ encodeURIComponent(emailtocustomer) + 
			"&custmailid="+ encodeURIComponent($("#emailid1").val()) + 
			"&compname="+ encodeURIComponent($("#businessname").val()) +
			 "&custid="+ encodeURIComponent($("#customerid").val()) + 
			 "&dummy=" + Math.floor(Math.random()*100000000);
		}
	}
	else
	{
		var passData = "switchtype=forcesurrenderdetails&switchforce="+command+
		"&refslno="+ encodeURIComponent($("#forsurrender").val()) +
		"&forceremarks="+ encodeURIComponent($("#forceremarks").val()) + 
		"&dummy=" + Math.floor(Math.random()*100000000);
	}
		//alert(passData);
		var queryString = "../ajax/customer.php";
		error.html('');
		error.html(getprocessingimage());
		ajaxcall14 = $.ajax(
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
						$("#countforcesurrender").html(response[1]);
						$("#countonlinesurrender").html(response[2]);
						$("#pinstatussurrender").html(response[3]);
						error.html('');
						if(response[3] == 'Surrender')
						{ disablebutton('#savesur'); } else{enablebutton('#savesur'); }
					}
					else if(response[0] == '2')
					{
						$("#forceremarks").val('');
						error.html(errormessage(response[1]));
					}
					else if(response[0] == '3')
					{
						$("#forceremarks").val('');
						error.html(successmessage(response[1]));
						alert(response[1]);
						$('#emailtocustomer').removeAttr('checked');
						forcesurrenderdetails('countforcesurrender');
					}
					else
					{ error.html(''); }
				}
		}, 
		error: function(a,b)
		{
			$("#surrender-form-error").html(scripterror());
		}
	});	
}

function newentry()
{
	var form = $('#submitform');
	totalarray = '';
	tabopen5('1','tabg1');
	form[0].reset();
	$('#lastslno').val('');
	$('#disablelogin').html('');
	$('#corporateorder').html('');
	$('#profilepending').html('');
	$('#activecustomer').html('');
	$('#remarks').readOnly = false;
	enablesave();
	disableregistration();
	$('#createddate').html('Not Available');
	$('districtcodedisplay').html('<select name="district" class="swiftselect-mandatory" id="district" style="width:200px;"><option value="">Select A State First</option></select>');	
	gridtabcus4('1','tabgroupgrid','&nbsp; &nbsp;Current Registrations');
	clearregistrationform();
	clearcarddetails();
	$("#salessummary").html('<table width="100%" border="0" cellspacing="0" cellpadding="4" class="table-border-grid"> <tr class="tr-grid-header"> <td width="22%" class="td-border-grid">&nbsp;</td><td width="24%" class="td-border-grid" align="center"><strong>Bill</strong></td><td width="25%" class="td-border-grid" align="center"><strong>PIN</strong></td> <td width="29%" class="td-border-grid" align="center"><strong>Regn</strong></td></tr><tr><td class="td-border-grid" bgcolor="#F7FAFF"><strong>XBRL</strong></td><td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td><td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td><td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td></tr><tr><td class="td-border-grid"  bgcolor="#edf4ff"><strong>TDS</strong></td><td class="td-border-grid"  bgcolor="#edf4ff">&nbsp;</td><td class="td-border-grid"  bgcolor="#edf4ff">&nbsp;</td><td class="td-border-grid"  bgcolor="#edf4ff">&nbsp;</td></tr><tr><td class="td-border-grid" bgcolor="#F7FAFF"><strong>STO</strong></td><td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td><td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td><td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td></tr><tr><td class="td-border-grid" bgcolor="#F7FAFF"><strong>SPP</strong></td><td class="td-border-grid" bgcolor="#edf4ff">&nbsp;</td><td class="td-border-grid" bgcolor="#edf4ff">&nbsp;</td><td class="td-border-grid" bgcolor="#edf4ff">&nbsp;</td></tr><tr><td class="td-border-grid" bgcolor="#F7FAFF"><strong>GST</strong></td><td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td><td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td><td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td></tr><tr><td class="td-border-grid" bgcolor="#F7FAFF"><strong>SAC</strong></td><td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td><td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td><td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td></tr></table>');
	//$("#salessummary").html('<table width="100%" border="0" cellspacing="0" cellpadding="4" class="table-border-grid"> <tr class="tr-grid-header"> <td width="22%" class="td-border-grid">&nbsp;</td><td width="24%" class="td-border-grid" align="center"><strong>Bill</strong></td><td width="25%" class="td-border-grid" align="center"><strong>PIN</strong></td> <td width="29%" class="td-border-grid" align="center"><strong>Regn</strong></td></tr><tr><td class="td-border-grid" bgcolor="#F7FAFF"><strong>XBRL</strong></td><td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td><td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td><td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td></tr><tr><td class="td-border-grid"  bgcolor="#edf4ff"><strong>TDS</strong></td><td class="td-border-grid"  bgcolor="#edf4ff">&nbsp;</td><td class="td-border-grid"  bgcolor="#edf4ff">&nbsp;</td><td class="td-border-grid"  bgcolor="#edf4ff">&nbsp;</td></tr><tr><td class="td-border-grid"  bgcolor="#F7FAFF"><strong>SVI</strong></td><td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td><td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td><td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td></tr><tr><td class="td-border-grid"  bgcolor="#edf4ff"><strong>SVH</strong></td><td class="td-border-grid"  bgcolor="#edf4ff">&nbsp;</td><td class="td-border-grid"  bgcolor="#edf4ff">&nbsp;</td><td class="td-border-grid"  bgcolor="#edf4ff">&nbsp;</td></tr><tr><td class="td-border-grid" bgcolor="#F7FAFF"><strong>STO</strong></td><td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td><td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td><td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td></tr><tr><td class="td-border-grid" bgcolor="#F7FAFF"><strong>SPP</strong></td><td class="td-border-grid" bgcolor="#edf4ff">&nbsp;</td><td class="td-border-grid" bgcolor="#edf4ff">&nbsp;</td><td class="td-border-grid" bgcolor="#edf4ff">&nbsp;</td></tr><tr><td class="td-border-grid"  bgcolor="#F7FAFF"><strong>GST</strong></td><td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td><td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td><td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td></tr></table>');
}


function rowwdelete()
{
	totalarray = '';
	var rowcount = $('#adddescriptionrows tr').length;
	if(rowcount <=10)
	{
		slno =1;
		$('#adddescriptionrows tr').remove();
		rowid = 'removedescriptionrow'+ slno;
		var value = 'contactname'+slno;
		var row = '<tr id="removedescriptionrow'+ slno+'"><td  width="5%"><div align="left"><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td><td width="11%"><div align="center"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:110px" class="swiftselect-mandatory  type_enter focus_redclass"><option value="" selected="selected" >--Select--</option><option value="general">General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="ithead/edp">IT-Head/EDP</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="CA" >CA</option><option value="manager" >MANAGER</option><option value="others" >Others</option></select></div></td><td width="16%"><div align="center"><input name="name'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="name'+ slno+'"  style="width:115px"  maxlength="130"  autocomplete="off"/></div></td><td width="18%"><div align="center"><input name="phone'+ slno+'" type="text"class="swifttext type_enter focus_redclass" id="phone'+ slno+'" style="width:110px" maxlength="100"  autocomplete="off" /></div></td><td width="15%"><div align="center"><input name="cell'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="cell'+ slno+'" style="width:100px"  maxlength="10"  autocomplete="off"/></div></td><td width="27%"><div align="center"><input name="emailid'+ slno+'" type="text" class="swifttext" id="emailid'+ slno+'" style="width:150px"  maxlength="200"  autocomplete="off"/></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" /></td></tr>';
		$("#adddescriptionrows").append(row);
		findlasttd();
		$('#'+value).html(slno);
	}
		
}

function generatecustomerregistration(id,startlimit)
{
	var form = $("#submitform");
	$('#lastslno').val(id);	
	var passdata = "switchtype=customerregistration&lastslno="+ encodeURIComponent($('#lastslno').val()) + "&startlimit=" + encodeURIComponent(startlimit);//alert(passData)
	var queryString = "../ajax/customer.php";
	$('#tabgroupgridc1_1').html(getprocessingimage());
	$('#tabgroupgridc1link').html('');
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
					var response = ajaxresponse;//alert(ajaxresponse)
					if(response['errorcode'] == '1')
					{
						gridtabcus4(1,'tabgroupgrid','&nbsp; &nbsp;Current Registrations');
						$('#tabgroupgridwb1').html("Total Count :  " + response['count']);
						$('#tabgroupgridc1_1').html(response['grid']);
						$('#tabgroupgridc1link').html(response['linkgrid']); 
						$("#disforcesurrender").html(response['selectforce']);
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
			$("#tabgroupgridc1_1").html(scripterror());
		}
	});		
}


function getmorecustomerregistration(id,startlimit,slno,showtype)
{
	var form = $('#submitform');
	$('#lastslno').val(id);	
	var passdata = "switchtype=customerregistration&lastslno="+ encodeURIComponent($('#lastslno').val()) + "&startlimit=" + startlimit+ "&slno=" + slno+ "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);
	var queryString = "../ajax/customer.php";
	$('#tabgroupgridc1link').html(getprocessingimage());
	ajaxcall10 = $.ajax(
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
					gridtabcus4(1,'tabgroupgrid','&nbsp; &nbsp;Current Registrations');
					$('#tabgroupgridwb1').html("Total Count :  " + response['count']);
					$('#regresultgrid').html($('#tabgroupgridc1_1').html());
					$('#tabgroupgridc1_1').html($('#regresultgrid').html().replace(/\<\/table\>/gi,'')+ response['grid']) ;
					$('#tabgroupgridc1link').html(response['linkgrid']);
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
			$("#tabgroupgridc1_1").html(scripterror());
		}
	});		
}


function customerdetailstoform(cusid)
{
	if(cusid != '')
	{
		$('#form-error').html('');
		var form = $('#submitform');
		form[0].reset();
		totalarray = '';
		tabopen5('1','tabg1');
		var passdata = "switchtype=customerdetailstoform&lastslno=" + encodeURIComponent(cusid) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
		$('#form-error').html(getprocessingimage());
		var queryString = "../ajax/customer.php";
		ajaxcall3 = $.ajax(
			{
				type: "POST",url: queryString, data: passdata, cache: false,dataType: "json",
				success: function(ajaxresponse,status)
				{	
					$('#form-error').html('');
					$('#searchcustomerid').val('');
					
					if(ajaxresponse == 'Thinking to redirect')
					{
						window.location = "../logout.php";
						return false;
					}
					else
					{
						var response = ajaxresponse;
						console.log(response);

						if(response['cusslno'] == '')
						{
							
							alert('Customer Not Available.');
							$('#districtcodedisplay').html('<select name="district" class="swiftselect-mandatory" id="district"><option value="">Select A State First</option></select>');	
							$('#tabgroupgridc1').html('No datas found to be displayed.');
							clearregistrationform();
							return false;
							
						} 
						$('#lastslno').val(response['cusslno']);//alert(response[16])
						generatecustomerregistration(response['cusslno'],''); 
						generatecustomerattachcards(response['cusslno']);
						enableregistration();
						$('#customerid').val(response['customerid']);
						$('#businessname').val(response['businessname']);
						$('#short_url').val(response['rescontact'] +"\r\n"+ response['businessname']+"\r\n"+ response['address']+"\n"+response['place']+"\n"+ response['districtname']+"\n"+ response['statename']+"\n" + response['pincode']+"\r\n"+ response['resphone']+"\n"+ response['rescell']+"\n"+ response['resemailid']);
						$('#address').val(response['address']);
						$('#place').val(response['place']);
						$('#tanno').val(response['tanno']);
						
						//$("#gst_no").val(response['gst_no']);
						//readonly attribute added to gstin field
						//if(response['gst_no'] == "" || response['gst_no'] == null)
    					//{
        					 
        				 //document.getElementById("gst_no").readOnly =false;
    					  //$("#gst_no").css('background','#fff');
        					 
    					//}
    					//else
    					//{
    					    //alert(response['gst_no']);
        				//$("#gst_no").css('background','#FEFFE6');
        				//document.getElementById("gst_no").readOnly = true;
        				$("#gst_no").val(response['gst_no']);
        				$("#effective_from").val(response['effective_from']);
    					   
    					//}
						
    					$("#state_gst").text(response['state_gst']);
    					
    					if(response['sez_enabled'] == 'yes')
    					{
    					    $("#sez_enabled_yes").attr('checked', 'checked');
    					}
    					else
    					{
    					  $("#sez_enabled_no").attr('checked', 'checked'); 
    					}
						
						
						$('#state').val(response['state']);
						//$("#gst_no").val(response['gst_no']);
						getdistrict('districtcodedisplay', response['state'])
						$('#district').val(response['district']);
						$('#pincode').val(response['pincode']);
						$('#stdcode').val(response['stdcode']);
						$('#website').val(response['website']);
						$('#category').val(response['category']);
						$('#type').val(response['type']);
						$('#remarks').val(response['remarks']);
						$('#currentdealer').val(response['currentdealer']);
						$('#disablelogin').html(response['disablelogin']);
						$('#createddate').html(response['createddate']);
						$('#corporateorder').html(response['corporateorder']);
						$('#fax').val(response['fax']); 
						$('#activecustomer').html(response['activecustomer']);//alert(response[23])
						$('#branchdisplay').html(response['branch']); //alert(response[32])
						$('#salessummary').html(response['grid']);
						if(response['pendingrequestmsg'] == '')
						{
							$('#profilepending').html('');
						}
						else
						$('#profilepending').html('<div class ="displaysuccessbox">' + response['pendingrequestmsg'] + '</div>');
						autochecknew($('#companyclosed'),response['companyclosed']);//alert(response[30])
						autochecknew($('#promotionalsms'),response['promotionalsms']); //alert(response[31])
						autochecknew($('#promotionalemail'),response['promotionalemail']);
					
						
						var countrow = response['contactarray'].split('****');
						$('#adddescriptionrows tr').remove();
						for(k=1;k<=countrow.length;k++)
						{
							slno = k;
							rowid = 'removedescriptionrow'+ slno;
							
							if(k == 10)
							{
								var value = 'contactname'+slno;
								$('#adddescriptionrowdiv').hide();
								var row = '<tr id="removedescriptionrow'+ slno+'"><td  width="5%"><div align="left"><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td><td width="11%"><div align="center"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:110px" class="swiftselect-mandatory type_enter focus_redclass"><option value="" selected="selected" >--Select--</option><option value="general">General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="ithead/edp">IT-Head/EDP</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="CA" >CA</option><option value="manager" >MANAGER</option><option value="others" >Others</option></select></div></td><td width="16%"><div align="center"><input name="name'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="name'+ slno+'"  style="width:115px"  maxlength="130"  autocomplete="off"/></div></td><td width="18%"><div align="center"><input name="phone'+ slno+'" type="text"class="swifttext" id="phone'+ slno+'" style="width:110px" maxlength="100"  autocomplete="off" /></div></td><td width="15%"><div align="center"><input name="cell'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="cell'+ slno+'" style="width:100px"  maxlength="10"  autocomplete="off"/></div></td><td width="27%"><div align="center"><input name="emailid'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="emailid'+ slno+'" style="width:150px"  maxlength="200"  autocomplete="off"/></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" /></td></tr>';
							}
							else if(k == 1)
							{
								var value = 'contactname'+slno;
								$('#adddescriptionrowdiv').show();
								var row = '<tr id="removedescriptionrow'+ slno+'"><td  width="5%"><div align="left"><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td><td width="11%"><div align="center"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:110px" class="swiftselect-mandatory type_enter focus_redclass"><option value="" selected="selected" >--Select--</option><option value="general">General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="ithead/edp">IT-Head/EDP</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="CA" >CA</option><option value="manager" >MANAGER</option><option value="others" >Others</option></select></div></td><td width="16%"><div align="center"><input name="name'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="name'+ slno+'"  style="width:115px"  maxlength="130"  autocomplete="off"/></div></td><td width="18%"><div align="center"><input name="phone'+ slno+'" type="text"class="swifttext type_enter focus_redclass" id="phone'+ slno+'" style="width:110px" maxlength="100"  autocomplete="off" /></div></td><td width="15%"><div align="center"><input name="cell'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="cell'+ slno+'" style="width:100px"  maxlength="10"  autocomplete="off"/></div></td><td width="27%"><div align="center"><input name="emailid'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="emailid'+ slno+'" style="width:150px"  maxlength="200"  autocomplete="off"/></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" /></td></tr>';
							}
							else
							{
								var value = 'contactname'+slno;
								$('#adddescriptionrowdiv').show();
								var row = '<tr id="removedescriptionrow'+ slno+'"><td  width="5%"><div align="left"><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td><td width="11%"><div align="center"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:110px" class="swiftselect-mandatory type_enter focus_redclass"><option value="" selected="selected" >--Select--</option><option value="general" >General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="ithead/edp">IT-Head/EDP</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="CA" >CA</option><option value="manager" >MANAGER</option><option value="others" >Others</option></select></div></td><td width="16%"><div align="center"><input name="name'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="name'+ slno+'"  style="width:115px"  maxlength="130"  autocomplete="off"/></div></td><td width="18%"><div align="center"><input name="phone'+ slno+'" type="text"class="swifttext type_enter focus_redclass" id="phone'+ slno+'" style="width:110px" maxlength="100"  autocomplete="off" /></div></td><td width="15%"><div align="center"><input name="cell'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="cell'+ slno+'" style="width:100px"  maxlength="10"  autocomplete="off"/></div></td><td width="27%"><div align="center"><input name="emailid'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="emailid'+ slno+'" style="width:150px"  maxlength="200"  autocomplete="off"/></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" /></td></tr>';
							}
							$("#adddescriptionrows").append(row);
							$('input[type=text].focus_redclass, select.focus_redclass, textarea.focus_redclass, input[type=checkbox].focus_redclass, input[type=button].focus_redclass').focus(function() {
							if($(this).get(0).type == 'checkbox')
								$(this).addClass("checkbox_enter1"); 
							else if($(this).get(0).type == 'text' || $(this).get(0).type == 'textarea' || $(this).get(0).type == 'select')
								$(this).addClass("css_enter1");  
							else if($(this).get(0).type == 'button')
								$(this).addClass("button_enter1"); 
							});
							$('input[type=text].focus_redclass, select.focus_redclass, textarea.focus_redclass, input[type=checkbox].focus_redclass, input[type=button].focus_redclass').blur(function() {
							if($(this).get(0).type == 'checkbox')
								$(this).removeClass("checkbox_enter1"); 
							else if($(this).get(0).type == 'text' || $(this).get(0).type == 'textarea' || $(this).get(0).type == 'select')
								$(this).removeClass("css_enter1");  
							else if($(this).get(0).type == 'button')
								$(this).removeClass("button_enter1"); 
							});
							findlasttd();
							$('#'+value).html(slno);
							
						}
					
						splitvalue = new Array();
						for(var i=0;i<countrow.length;i++)
						{
							splitvalue[i] =  countrow[i].split('#');
							$("#"+'selectiontype'+(i+1)).val(splitvalue[i][0]);
							$("#"+'name'+(i+1)).val(splitvalue[i][1]);
							$("#"+'phone'+(i+1)).val(splitvalue[i][2]);
							$("#"+'cell'+(i+1)).val(splitvalue[i][3]);
							$("#"+'emailid'+(i+1)).val(splitvalue[i][4]);
							$("#"+'contactslno'+(i+1)).val(splitvalue[i][5]);
						}
					}

				}, 
				error: function(a,b)
				{
					$("#form-error").html(scripterror());
				}
			});		
	}
}

function selectfromlist()
{
	var selectbox = $("#customerlist option:selected").val();
	$('#detailsearchtext').val($("#customerlist option:selected").text());
	$('#detailsearchtext').select();
	$('#filterdiv').hide();
	$('#tabgroupgridwb1').html('');
	customerdetailstoform(selectbox);	
	$('#hiddenregistrationtype').val('newlicence');
	clearregistrationform(); 
	validatemakearegistration();   
	$('#delaerrep').attr("disabled", true);
}

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
		var form = $("#submitform");
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


function searchbycustomerid(cusid)
{
	$('#profilepending').html('');
	$('#form-error').html('');
	var form = $('#submitform');
	form[0].reset();
	totalarray = '';
	tabopen5('1','tabg1');
	var passdata = "switchtype=searchbycustomerid&customerid=" + encodeURIComponent(cusid) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
	var queryString = "../ajax/customer.php";
	$('#form-error').html(getprocessingimage());
	ajaxcall523 = $.ajax(
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
					var response = (ajaxresponse);// alert(response)
					$('#form-error').html('');
					$('#filterdiv').hide();
					if(response['cusslno'] == '')
					{
						alert('Customer Not Available.');
						$('#districtcodedisplay').html('<select name="district" class="swiftselect-mandatory" id="district"><option value="">Select A State First</option></select>');	
						$('#tabgroupgridc1_1').html('No datas found to be displayed.');
						$('#tabgroupgridc1link').html('');
						$('#tabgroupgridwb1').html("Total Count :  "+ 0);
						clearregistrationform();
						return false;
					}
	
					$('#lastslno').val(response['cusslno']);//alert(response[16])
					generatecustomerregistration(response['cusslno'],''); 
					generatecustomerattachcards(response['cusslno']);
					enableregistration();
					$('#customerid').val(response['customerid']);
					$('#businessname').val(response['businessname']);
					$('#short_url').val(response['rescontact'] +"\r\n"+ response['businessname']+"\r\n"+ response['address']+"\n"+ response['place']+"\n"+ response['districtname']+"\n"+ response['statename']+"\n" + response['pincode']+"\r\n"+ response['resphone']+"\n"+ response['rescell']+"\n"+ response['resemailid']);
					$('#address').val(response['address']);
					$('#place').val(response['place']);
					
					//$("#gst_no").val(response['gst_no']);
					//to disable the edit of gstin through customer master screen
				/*if(response['gst_no'] == "" || response['gst_no'] == null )
					{
    					 document.getElementById("gst_no").readOnly = false;
					    $("#gst_no").css('background','#fff');
					}
					else
					{
					    
					    //alert(response['gst_no']);
    					 $("#gst_no").css('background','#FEFFE6');
    					 document.getElementById("gst_no").readOnly = true;*/
    					 $("#gst_no").val(response['last_new_gst_no']);
    					 $("#effective_from").val(response['effective_from']);

    					 //alert($("#gst_no").val(response['last_new_gst_no']));
    					 
					//}
					if(response['sez_enabled'] == 'yes')
					{
					    $("#sez_enabled_yes").attr('checked', 'checked');
					}
					else
					{
					  $("#sez_enabled_no").attr('checked', 'checked');  
					}
					
					$("#state_gst").html(response['state_gst']);
					$('#state').val(response['state']);
					//$("#gst_no").val(response['gst_no']);
					getdistrict('districtcodedisplay', response['state'])
					$('#district').val(response['district']);
					$('#pincode').val(response['pincode']);
					$('#stdcode').val(response['stdcode']);
					$('#website').val(response['website']);
					$('#category').val(response['category']);
					$('#type').val(response['type']);
					$('#remarks').val(response['remarks']);
					$('#currentdealer').val(response['currentdealer']);
					$('#disablelogin').html(response['disablelogin']);
					$('#createddate').html(response['createddate']);
					$('#corporateorder').html(response['corporateorder']);
					$('#fax').val(response['fax']); 
					$('#activecustomer').html(response['activecustomer']);//alert(response[23])
					$('#branchdisplay').html(response['branch']);
					if(response['pendingrequestmsg'] == '')
					{
						$('profilepending').html('');
					}
					else
					$('#profilepending').html('<div class ="displaysuccessbox">' + response['pendingrequestmsg']+ '</div>');
					autochecknew($('#companyclosed'),response['companyclosed']);
					autochecknew($('#promotionalsms'),response['promotionalsms']);
					autochecknew($('#promotionalemail'),response['promotionalemail']);
					$("#salessummary").html(response['grid']);
					
					var countrow = response['contactarray'].split('****');//alert(response[25])
					$('#adddescriptionrows tr').remove();
					for(k=1;k<=countrow.length;k++)
					{
						slno = k;
						rowid = 'removedescriptionrow'+ slno;
						
						if(k == 10)
						{
							var value = 'contactname'+slno;
							$('#adddescriptionrowdiv').hide();
							var row = '<tr id="removedescriptionrow'+ slno+'"><td  width="5%"><div align="left"><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td><td width="11%"><div align="center"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:110px" class="swiftselect-mandatory  type_enter focus_redclass"><option value="" selected="selected" >--Select--</option><option value="general">General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="ithead/edp">IT-Head/EDP</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="CA" >CA</option><option value="manager" >MANAGER</option><option value="others" >Others</option></select></div></td><td width="16%"><div align="center"><input name="name'+ slno+'" type="text" class="swifttext  type_enter focus_redclass" id="name'+ slno+'"  style="width:115px"  maxlength="130"  autocomplete="off"/></div></td><td width="18%"><div align="center"><input name="phone'+ slno+'" type="text"class="swifttext type_enter focus_redclass" id="phone'+ slno+'" style="width:110px" maxlength="100"  autocomplete="off" /></div></td><td width="15%"><div align="center"><input name="cell'+ slno+'" type="text" class="swifttext  type_enter focus_redclass" id="cell'+ slno+'" style="width:100px"  maxlength="10"  autocomplete="off"/></div></td><td width="27%"><div align="center"><input name="emailid'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="emailid'+ slno+'" style="width:150px"  maxlength="200"  autocomplete="off"/></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" /></td></tr>';
						}
						else if(k == 1)
						{
							var value = 'contactname'+slno;
							$('#adddescriptionrowdiv').show();
							var row = '<tr id="removedescriptionrow'+ slno+'"><td  width="5%"><div align="left"><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td><td width="11%"><div align="center"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:110px" class="swiftselect-mandatory type_enter focus_redclass"><option value="" selected="selected" >--Select--</option><option value="general">General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="ithead/edp">IT-Head/EDP</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="CA" >CA</option><option value="manager" >MANAGER</option><option value="others" >Others</option></select></div></td><td width="16%"><div align="center"><input name="name'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="name'+ slno+'"  style="width:115px"  maxlength="130"  autocomplete="off"/></div></td><td width="18%"><div align="center"><input name="phone'+ slno+'" type="text"class="swifttext type_enter focus_redclass" id="phone'+ slno+'" style="width:110px" maxlength="100"  autocomplete="off" /></div></td><td width="15%"><div align="center"><input name="cell'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="cell'+ slno+'" style="width:100px"  maxlength="10"  autocomplete="off"/></div></td><td width="27%"><div align="center"><input name="emailid'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="emailid'+ slno+'" style="width:150px"  maxlength="200"  autocomplete="off"/></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" /></td></tr>';
						}
						else
						{
							var value = 'contactname'+slno;
							$('#adddescriptionrowdiv').show();
							var row = '<tr id="removedescriptionrow'+ slno+'"><td  width="5%"><div align="left"><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td><td width="11%"><div align="center"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:110px" class="swiftselect-mandatory type_enter focus_redclass"><option value="" selected="selected" >--Select--</option><option value="general" >General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="ithead/edp">IT-Head/EDP</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="CA" >CA</option><option value="manager" >MANAGER</option><option value="others" >Others</option></select></div></td><td width="16%"><div align="center"><input name="name'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="name'+ slno+'"  style="width:115px"  maxlength="130"  autocomplete="off"/></div></td><td width="18%"><div align="center"><input name="phone'+ slno+'" type="text"class="swifttext type_enter focus_redclass" id="phone'+ slno+'" style="width:110px" maxlength="100"  autocomplete="off" /></div></td><td width="15%"><div align="center"><input name="cell'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="cell'+ slno+'" style="width:100px"  maxlength="10"  autocomplete="off"/></div></td><td width="27%"><div align="center"><input name="emailid'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="emailid'+ slno+'" style="width:150px"  maxlength="200"  autocomplete="off"/></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" /></td></tr>';
						}
						$("#adddescriptionrows").append(row);
						$('input[type=text].focus_redclass, select.focus_redclass, textarea.focus_redclass, input[type=checkbox].focus_redclass, input[type=button].focus_redclass').focus(function() {
						if($(this).get(0).type == 'checkbox')
							$(this).addClass("checkbox_enter1"); 
						else if($(this).get(0).type == 'text' || $(this).get(0).type == 'textarea' || $(this).get(0).type == 'select')
							$(this).addClass("css_enter1");  
						else if($(this).get(0).type == 'button')
							$(this).addClass("button_enter1"); 
						});
						$('input[type=text].focus_redclass, select.focus_redclass, textarea.focus_redclass, input[type=checkbox].focus_redclass, input[type=button].focus_redclass').blur(function() {
						if($(this).get(0).type == 'checkbox')
							$(this).removeClass("checkbox_enter1"); 
						else if($(this).get(0).type == 'text' || $(this).get(0).type == 'textarea' || $(this).get(0).type == 'select')
							$(this).removeClass("css_enter1");  
						else if($(this).get(0).type == 'button')
							$(this).removeClass("button_enter1"); 
						});
						findlasttd();
						$('#'+value).html(slno);
						
					}
				
					splitvalue = new Array();
					for(var i=0;i<countrow.length;i++)
					{
						splitvalue[i] =  countrow[i].split('#');
						$("#"+'selectiontype'+(i+1)).val(splitvalue[i][0]);
						$("#"+'name'+(i+1)).val(splitvalue[i][1]);
						$("#"+'phone'+(i+1)).val(splitvalue[i][2]);
						$("#"+'cell'+(i+1)).val(splitvalue[i][3]);
						$("#"+'emailid'+(i+1)).val(splitvalue[i][4]);
						$("#"+'contactslno'+(i+1)).val(splitvalue[i][5]);
					}
				}
			}, 
			error: function(a,b)
			{
				$("#form-error").html(scripterror());
			}
		});		
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
				$('#groupvalue').val('');
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

function scratchdetailstoform(cardid)
{
	if(cardid != '')
	{
		var passdata = "switchtype=scratchdetailstoform&cardid=" + encodeURIComponent(cardid) + "&dummy=" + Math.floor(Math.random()*100032680100); //alert(passdata)
		$('#reg-form-error').html(getprocessingimage());
		var queryString = "../ajax/customer.php";
		ajaxcall5 = $.ajax(
			{
				type: "POST",url: queryString, data: passdata, cache: false,dataType: "json",
				success: function(ajaxresponse,status)
				{	
					$('#reg-form-error').html('');
					$('#scratchcradloading').html('');
					$('#detailsonscratch').show();
					if(ajaxresponse == 'Thinking to redirect')
					{
						window.location = "../logout.php";
						return false;
					}
					var response = ajaxresponse;
					if(response['errorcode'] == '1')
					{
						$('#cardnumberdisplay').html(response['cardid']);
						$('#scratchnodisplay').html(response['scratchnumber']);
						$('#purchasetypedisplay').html(response['purchasetype']);
						$('#usagetypedisplay').html(response['usagetype']);
						$('#attachedtodisplay').html(response['attachedto']);
						$('#productdisplay').html(response['productcode'] + ' [' + response['productname'] + ']');
						$('#registeredtodisplay').html(response['registeredto']);
						$('#attachdatedisplay').html(response['attcheddate']);
						$('#registerdatedisplay').html(response['registereddate']);
						$('#cardstatusdisplay').html(response['cardstatus']);
						$('#schemedisplay').html(response['schemename']);
						
						$('#delaerrep').val(response['dealerid']);
						$('#productname').val(response['productcode'] + ' [' + response['productname'] + ']');
						$('#productcode').val(response['productcode']);
						$('#tfpurchasetype').val(response['purchasetype']);
						$('#tfusagetype').val(response['usagetype']);
						$('#tfdealer').val(response['attachedto']); 
						$('#tfproduct').val(response['productname']);
					}
				}, 
				error: function(a,b)
				{
					$("#reg-form-error").html(scripterror());
				}
			});		
	}
}
function validatemakearegistration()
{
	var form = $('#registrationform');
	var error =  $('#reg-form-error'); 
 	var registrationfieldradio = $('input[name=registrationfieldradio]:checked').val();
	if(registrationfieldradio == 'newlicence')
	{
		$('#delaerrep').attr('disabled',true);
		$('#scratchnumber').attr('readOnly',false);
		$('#searchscratchnumber').attr('readOnly',false);
		$('#scratchdisplay').show();
		regcardarray = new_regcardarray;
		getregcardlist();
	}
	else if(registrationfieldradio == 'updationlicense')
	{
		$('#delaerrep').attr('disabled',true);
		$('#scratchnumber').attr('readOnly',false);
		$('#searchscratchnumber').attr('readOnly',false);
		$('#scratchdisplay').show();
		regcardarray = up_regcardarray;
		getregcardlist();
	}
}

function makearegistration()
{
	var form = $('#registrationform');
	var error = $('#reg-form-error');
	var registrationfieldradio = $('input[name=registrationfieldradio]:checked').val();
	if(registrationfieldradio == 'newlicence')
	{
		$('#hiddenregistrationtype').val('newlicence');
		if(!$('#lastslno').val()) { error.html(errormessage("Please Select a Customer from list above.")); field.focus(); return false; }
		var scratchnumber = $('#scratchcardlist');
		if(!scratchnumber.val()) { error.html(errormessage("Please Select the Scratch Number from the list.")); scratchnumber.focus(); return false; }
		var field = $('#computerid');
		if(!field.val()) { error.html(errormessage("Please Enter the Computer ID.")); field.focus(); return false; }
		if(field.val()) { if(!computeridvalidate(field.val())) { error.html(errormessage("Enter the valid Computerid ")); field.focus(); return false; } }
		var field = $('#billno');
		if(!field.val()) { error.html(errormessage("Please Enter the Bill Number.")); field.focus(); return false; }
		var field = $('#billamount');
		if(!field.val()) { error.html(errormessage("Please Enter the Bill Amount.")); field.focus(); return false; }
		if(field.val())	{ if(!validateamount(field.val())) { error.html(errormessage('Amount is not Valid.')); field.focus(); return false; } }
	
	}
	else if(registrationfieldradio == 'updationlicense')
	{
		$('#hiddenregistrationtype').val('updationlicense');
		
		if(!$('#lastslno').val()) { error.html(errormessage("Please Select a Customer from list above.")); field.focus(); return false; }
		var scratchnumber = $('#scratchcardlist');
		if(!scratchnumber.val()) { error.html(errormessage("Please Select the Scratch Number from the list.")); field.focus(); return false; }
		var field = $('#computerid');
		if(!field.val()) { error.html(errormessage("Please Enter the Computer ID.")); field.focus(); return false; }
		if(field.val()) { if(!computeridvalidate(field.val())) { error.html(errormessage("Enter the valid Computerid ")); field.focus(); return false; } }
		var field = $('#billno');
		if(!field.val()) { error.html(errormessage("Please Enter the Bill Number.")); field.focus(); return false; }
		var field = $('#billamount');
		if(!field.val()) { error.html(errormessage("Please Enter the Bill Amount.")); field.focus(); return false; }
		if(field.val())	{ if(!validateamount(field.val())) { error.html(errormessage('Amount is not Valid.')); field.focus(); return false; } }
	}
		var passdata = "switchtype=generatesoftkey&registrationtype=" + encodeURIComponent($('#hiddenregistrationtype').val()) + "&scratchnumber=" + encodeURIComponent(scratchnumber.val()) + "&customerid=" + encodeURIComponent($('#lastslno').val()) + "&delaerrep=" + encodeURIComponent($('#delaerrep').val()) + "&productcode=" + encodeURIComponent($('#productcode').val()) +  "&productname=" + encodeURIComponent($('#productname').val()) + "&computerid=" + encodeURIComponent($('#computerid').val()) + "&billno=" + encodeURIComponent($('#billno').val()) + "&billamount=" + encodeURIComponent($('#billamount').val()) + "&regremarks=" + encodeURIComponent($('#regremarks').val()) + "&usagetype=" + encodeURIComponent($('#usagetypedisplay').html()) + "&purchasetype=" +  encodeURIComponent($('#purchasetypedisplay').html()) + "&dummy=" + Math.floor(Math.random()*100032680100); //alert(passdata)
		error.html(getprocessingimage());
		var queryString = "../ajax/customer.php";
		ajaxcall6 = $.ajax(
			{
				type: "POST",url: queryString, data: passdata, cache: false,dataType: "json",
				success: function(ajaxresponse,status)
				{	
					error.html('');
					var response = ajaxresponse.split("^"); 
					
					if(response == 'Thinking to redirect')
					{
						window.location = "../logout.php";
						return false;
					}
					else
					{
						if(response[0] == 2) 
						{ 
							error.html(errormessage(response[1])); $('#computerid').focus(); 
						}
						else
						{
							alert(response[1]);//response message when soft key is generated
							generatecustomerregistration($('#lastslno').val(),''); 
							generatecustomerattachcards($('#lastslno').val());
							customerdetailstoform($('#lastslno').val());	
							new_refreshcuscardarray();
							up_refreshcuscardarray();
							
							//Update Customer ID and Password field, if it is a new cusotmer
							if(response[2] != "")
							{
								var customeridpassword = response[2].split("%");
								$('#customerid').val(customeridpassword[0]);
							}
						
							gridtabcus4(1,'tabgroupgrid','&nbsp; &nbsp;Current Registrations');
							form[0].reset();
							clearregistrationform();
						}
					}
				}, 
				error: function(a,b)
				{
					error.html(scripterror());
				}
			});		
}

function enableregistration()
{
	$('#registrationfieldradio0').attr('disabled',false);
	$('#registrationfieldradio1').attr('disabled',false);
	$('#searchscratchnumber').attr('disabled',false);
	$('#scratchnumber').attr('disabled',false);
	$('#delaerrep').attr('disabled',true);
	$('#productname').attr('disabled',false);
	$('#productcode').attr('disabled',false);;
	$('#computerid').attr('disabled',false);;
	$('#billno').attr('disabled',false);
	$('#billamount').attr('disabled',false);
	$('#regremarks').attr('disabled',false);
	$('#generateregistration').attr('disabled',false);
	$('#registrationclearall').attr('disabled',false);
	$('#closereg').attr('disabled',false);
}

function disableregistration()
{
	if($('#registrationfieldradio0')) $('#registrationfieldradio0').attr('disabled',true);
	if($('#registrationfieldradio1')) $('#registrationfieldradio1').attr('disabled',true);
	if($('#registrationfieldradio2')) $('#registrationfieldradio2').attr('disabled',true);
	if($('#registrationfieldradio3')) $('#registrationfieldradio3').attr('disabled',true);
	$('#searchscratchnumber').attr('disabled',true);
	$('#scratchnumber').attr('disabled',true);
	$('#delaerrep').attr('disabled',true);
	$('#productname').attr('disabled',true);
	$('#productcode').attr('disabled',true);
	$('#computerid').attr('disabled',true);
	$('#billno').attr('disabled',true);
	$('#billamount').attr('disabled',true);
	$('#regremarks').attr('disabled',true);
	$('#generateregistration').attr('disabled',true);
	$('#registrationclearall').attr('disabled',true);
	$('#closereg').attr('disabled',true);
}

function clearregistrationform()
{
	$('#detailsonscratch').hide();
	$('#reg-form-error').html('');
	$('#searchscratchnumber').val('');
	$('#scratchnumber').val('');
	$('#delaerrep').val('');
	$('#productname').val('');
	$('#productcode').val('');
	$('#computerid').val('');
	$('#billno').val('');
	$('#billamount').val('');
	$('#regremarks').val('');
	$('#cardnumberdisplay').html('');
	$('#scratchnodisplay').html('');
	$('#purchasetypedisplay').html('');
	$('#usagetypedisplay').html('');
	$('#attachedtodisplay').html('');
	$('#registeredtodisplay').html('');
	$('#attachdatedisplay').html('');
	$('#registerdatedisplay').html('');
}




function tranfervalues()
{
	disabletranfer();
	$('#tranfer-form-error').html('');
	displayelement('transferscratchcarddiv','tabgroupgridc2'); 
	$('#transfercardfield').val($('#searchscratchnumber').val());
	$('#tfpurchasetype').val($('#purchasetypedisplay').html());
	$('#tfusagetype').val($('#usagetypedisplay').html());
	$('#tfdealer').val($('#attachedtodisplay').html()); 
	$('#tfproduct').val($('#productname').val());
	scratchdetailstoform($('#scratchnumber').val());
}

function disabletranfer()
{
	$('#ttdealerto').attr('disabled',true);
	$('#ttproductto').attr('disabled',true);
	$('#ttpurchasetype').attr('disabled',true);
	$('#ttusagetype').attr('disabled',true);
}

function cleargrid()
{
	$('#tabgroupgridc1_1').html('No datas found to be displayed.');
	$('#tabgroupgridc1link').html('');
	$('#regresultgrid').html('');
	$('#tabgroupgridwb1').html('');
}

function generatecustomerattachcards(customerid)
{
	var form = $('#submitform');
	$('#lastslno').val(customerid);	
	var passdata = "switchtype=generatecustomerattachcards&lastslno="+ encodeURIComponent($('#lastslno').val());
	var queryString = "../ajax/customer.php";
	ajaxcall9 = $.ajax(
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
					var response = ajaxresponse;//alert(ajaxresponse);
					if(response['errorcode'] == '1')
					{
						$('#tabgroupgridc4').html(response['grid']);
					}
					else
					{
						$('#tabgroupgridc4').html(scripterror());
					}
				}
			}, 
			error: function(a,b)
			{
				$("#tabgroupgridc4").html(scripterror());
			}
		});		
}

function validateproductcheckboxes()
{
	var chksvalue = document.getElementsByName('productarray[]');
	var hasChecked = false;
	for (var i = 0; i < chksvalue.length; i++)
	{
		if (chksvalue[i].checked)
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


//Function to reset the from to the default value-Meghana[21/12/2009]
function resetDefaultValues(oForm)
{
    var elements = oForm.elements; 
 	oForm.reset();
	$("#filter-form-error").html('');
	for(var i=0; i<elements.length;i++) 
	{
		field_type = elements[i].type.toLowerCase();
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


//To add description rows
function adddescriptionrows()
{
	$("#form-error").html('');
	var rowcount = ($('#adddescriptionrows tr').length);
	if(rowcount == 1)
		slno  = (rowcount+1);
	else
		slno = rowcount + 1;

	rowid = 'removedescriptionrow'+ slno;
	var value = 'contactname'+slno;
	
	var row = '<tr id="removedescriptionrow'+ slno+'"><td  width="5%"><div align="left"><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td><td width="11%"><div align="center"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:110px" class="swiftselect-mandatory type_enter focus_redclass"><option value="" selected="selected" >--Select--</option><option value="general" >General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="ithead/edp">IT-Head/EDP</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="CA" >CA</option><option value="manager" >MANAGER</option><option value="others" >Others</option></select></div></td><td width="16%"><div align="center"><input name="name'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="name'+ slno+'"  style="width:115px"  maxlength="130"  autocomplete="off"/></div></td><td width="18%"><div align="center"><input name="phone'+ slno+'" type="text"class="swifttext  type_enter focus_redclass" id="phone'+ slno+'" style="width:110px" maxlength="100"  autocomplete="off" /></div></td><td width="15%"><div align="center"><input name="cell'+ slno+'" type="text" class="swifttext  type_enter focus_redclass" id="cell'+ slno+'" style="width:100px"  maxlength="10"  autocomplete="off"/></div></td><td width="27%"><div align="center"><input name="emailid'+ slno+'" type="text" class="swifttext  type_enter focus_redclass" id="emailid'+ slno+'" style="width:150px"  maxlength="200"  autocomplete="off"/></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" /></td></tr>';
	
	$("#adddescriptionrows").append(row);
	$('input[type=text].focus_redclass, select.focus_redclass, textarea.focus_redclass, input[type=checkbox].focus_redclass, input[type=button].focus_redclass').focus(function() {
	if($(this).get(0).type == 'checkbox')
		$(this).addClass("checkbox_enter1"); 
	else if($(this).get(0).type == 'text' || $(this).get(0).type == 'textarea' || $(this).get(0).type == 'select')
		$(this).addClass("css_enter1");  
	else if($(this).get(0).type == 'button')
		$(this).addClass("button_enter1"); 
	});
	$('input[type=text].focus_redclass, select.focus_redclass, textarea.focus_redclass, input[type=checkbox].focus_redclass, input[type=button].focus_redclass').blur(function() {
	if($(this).get(0).type == 'checkbox')
		$(this).removeClass("checkbox_enter1"); 
	else if($(this).get(0).type == 'text' || $(this).get(0).type == 'textarea' || $(this).get(0).type == 'select')
		$(this).removeClass("css_enter1");  
	else if($(this).get(0).type == 'button')
		$(this).removeClass("button_enter1"); 
	});
	findlasttd();
	$('#'+value).html(slno);
	if(slno == 10)
		$('#adddescriptionrowdiv').hide();
	else
		$('#adddescriptionrowdiv').show();
}

//Remove description row
function removedescriptionrows(rowid,rowslno)
{
	if(totalarray == '')
		totalarray = $('#contactslno'+rowslno).val();
	else if($('#contactslno'+rowslno).val())
		totalarray = totalarray  + ',' + $('#contactslno'+rowslno).val();
	var error = $("#form-error");
	$('#adddescriptionrowdiv').show();
	var rowcount = $('#adddescriptionrows tr').length;
	if(rowcount == 1)
	{
		error.html(errormessage("Minimum of ONE contact detail is mandatory")); 
		return false;
	}
	else
	{
		$('#'+rowid).remove();
		findlasttd();
		var countval = 0;
		for(i=1;i<=(rowcount+1);i++)
		{
			var selectiontype = '#selectiontype'+i;
			var designationtype = '#designationtype'+i;
			var name = '#name'+i;
			var phone = '#phone'+i;
			var cell = '#cell'+i;
			var emailid = '#emailid'+i;
			var removedescriptionrow = '#removedescriptionrow'+i;
			var contactslno =  '#contactslno'+i;
			var removerowdiv = '#removerowdiv'+i;
			if($(removedescriptionrow).length > 0)
			{
				countval++;
				$("#selectiontype"+ i).attr("name","selectiontype"+ countval);
				$("#selectiontype"+ i).attr("id","selectiontype"+ countval);
				
				$("#name"+ i).attr("name","name"+ countval);
				$("#name"+ i).attr("id","name"+ countval);
				
				$("#phone"+ i).attr("name","phone"+ countval);
				$("#phone"+ i).attr("id","phone"+ countval);
				
				$("#cell"+ i).attr("name","cell"+ countval);
				$("#cell"+ i).attr("id","cell"+ countval);
				
				$("#emailid"+ i).attr("name","emailid"+ countval);
				$("#emailid"+ i).attr("id","emailid"+ countval);
				
				$("#removedescriptionrow"+ i).attr("name","removedescriptionrow"+ countval);
				$("#removedescriptionrow"+ i).attr("id","removedescriptionrow"+ countval);
				
				$("#contactslno"+ i).attr("name","contactslno"+ countval);
				$("#contactslno"+ i).attr("id","contactslno"+ countval);
				
				$("#contactname"+ i).attr("id","contactname"+ countval);
				$("#contactname"+ countval).html(countval);
				
				$("#removerowdiv"+ i).attr("id","removerowdiv"+ countval);
				document.getElementById("removerowdiv"+ countval).onclick = new Function('removedescriptionrows("removedescriptionrow' + countval + '" ,"' + countval + '")') ;
						
			}
		}
	}
}



function displayinvoicedetails(invoicetype)
{
	if($('#lastslno').val() != '')
	{
		if(invoicetype == 'pinvoice')
		{
			generateinvoicedetails('');
		}
		else
		{
			generatematrixinvoicedetails('');
		}
		
		$("").colorbox({ inline:true, href:"#invoicedetailsgrid", onLoad: function() { $('#cboxClose').hide()}});
	}
	else
	{
		alert('Please select Customer');
		return false;
	}
}

//Function to view the bill in pdf format----------------------------------------------------------------
function viewinvoice(slno)
{
	if(slno != '')
		$('#onlineslno').val(slno);
		
	var form = $('#detailsform');	
	if($('#onlineslno').val() == '')
	{
		$('#productselectionprocess').html(errormessage('Please select a Customer.')); return false;
	}
	else
	{
		$('#detailsform').attr("action", "../ajax/viewinvoicepdf.php") ;
		$('#detailsform').attr( 'target', '_blank' );
		$('#detailsform').submit();
	}
}

function viewmatrixinvoice(slno)
{
	if(slno != '')
		$('#onlineslno').val(slno);
		
	var form = $('#detailsform');	
	if($('#onlineslno').val() == '')
	{
		$('#productselectionprocess').html(errormessage('Please select a Customer.')); return false;
	}
	else
	{
		$('#detailsform').attr("action", "../ajax/viewmatrixinvoicepdf.php") ;
		$('#detailsform').attr( 'target', '_blank' );
		$('#detailsform').submit();
	}
}

function generateinvoicedetails(startlimit)
{
	
	var form = $('#detailsform');
	$('#invoicedetailsgridc1').show();
	$("#hiddeninvoiceslno").val($("#lastslno").val()); 
	var passdata = "switchtype=invoicedetailsgrid&lastslno="+ encodeURIComponent($('#lastslno').val()) + "&startlimit=" + encodeURIComponent(startlimit);
	var queryString = "../ajax/customer.php";
	$('#invoicedetailsgridc1_1').html(getprocessingimage());
	$('#invoicedetailsgridc1link').html('');
	ajaxcall41 = $.ajax(
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
				if(response['errorcode'] == 1)
				{
					$('#invoicedetailsgridwb1').html("Total Count :  " + response['resultcount']);
					$('#invoicedetailsgridc1_1').html(response['grid']);
					$('#invoicedetailsgridc1link').html(response['linkgrid']);
				}
				else
				{
					$('#invoicedetailsgridc1_1').html("No datas found to be displayed");
				}
			}	
		}, 
		error: function(a,b)
		{
			$("#invoicedetailsgridc1_1").html(scripterror());
		}
	});		
}


//Function for "show more records" link - to get registration records
function getmoreinvoicedetails(id,startlimit,slno,showtype)
{
	var form = $('#submitform');
	$('#lastslno').val(id); //alert('here');
	var passdata = "switchtype=invoicedetailsgrid&lastslno="+ encodeURIComponent($('#lastslno').val()) + "&startlimit=" + startlimit+ "&slno=" + slno + "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);
	var queryString = "../ajax/customer.php";
	$('#invoicedetailsgridc1link').html(getprocessingimage());
	ajaxcall51 = $.ajax(
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
					var response = ajaxresponse; //alert(response)
					if(response['errorcode'] == '1')
					{
						$('#invoicedetailsgridwb1').html("Total Count :  " + response['resultcount']);
						$('#invoicedetailsresultgrid').html($('#invoicedetailsgridc1_1').html());
						$('#invoicedetailsgridc1_1').html($('#invoicedetailsresultgrid').html().replace(/\<\/table\>/gi,'')+ response['grid'] );
						$('#invoicedetailsgridc1link').html(response['linkgrid']);
					}
					else
					{
						$('#invoicedetailsgridc1_1').html("No datas found to be displayed");
					}
				}
			}, 
			error: function(a,b)
			{
				$("#invoicedetailsgridc1_1").html(scripterror());
			}
		});		
}

function generatematrixinvoicedetails(startlimit)
{
	
	var form = $('#detailsform');
	$('#invoicedetailsgridc1').show();
	$("#hiddeninvoiceslno").val($("#lastslno").val()); 
	var passdata = "switchtype=matrixinvoicedetailsgrid&lastslno="+ encodeURIComponent($('#lastslno').val()) + "&startlimit=" + encodeURIComponent(startlimit);
	var queryString = "../ajax/customer.php";
	$('#invoicedetailsgridc1_1').html(getprocessingimage());
	$('#invoicedetailsgridc1link').html('');
	ajaxcall41 = $.ajax(
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
				if(response['errorcode'] == 1)
				{
					$('#invoicedetailsgridwb1').html("Total Count :  " + response['resultcount']);
					$('#invoicedetailsgridc1_1').html(response['grid']);
					$('#invoicedetailsgridc1link').html(response['linkgrid']);
				}
				else
				{
					$('#invoicedetailsgridc1_1').html("No datas found to be displayed");
				}
			}	
		}, 
		error: function(a,b)
		{
			$("#invoicedetailsgridc1_1").html(scripterror());
		}
	});		
}

function getmorematrixinvoicedetails(id,startlimit,slno,showtype)
{
	var form = $('#submitform');
	$('#lastslno').val(id); //alert('here');
	var passdata = "switchtype=matrixinvoicedetailsgrid&lastslno="+ encodeURIComponent($('#lastslno').val()) + "&startlimit=" + startlimit+ "&slno=" + slno + "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);
	var queryString = "../ajax/customer.php";
	$('#invoicedetailsgridc1link').html(getprocessingimage());
	ajaxcall51 = $.ajax(
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
					var response = ajaxresponse; //alert(response)
					if(response['errorcode'] == '1')
					{
						$('#invoicedetailsgridwb1').html("Total Count :  " + response['resultcount']);
						$('#invoicedetailsresultgrid').html($('#invoicedetailsgridc1_1').html());
						$('#invoicedetailsgridc1_1').html($('#invoicedetailsresultgrid').html().replace(/\<\/table\>/gi,'')+ response['grid'] );
						$('#invoicedetailsgridc1link').html(response['linkgrid']);
					}
					else
					{
						$('#invoicedetailsgridc1_1').html("No datas found to be displayed");
					}
				}
			}, 
			error: function(a,b)
			{
				$("#invoicedetailsgridc1_1").html(scripterror());
			}
		});		
}

function findlasttd()
{
	if($("#adddescriptionrows td").find('input').is('.default'))
	{
		$("#adddescriptionrows td").find('input').removeClass("default");
		$("#adddescriptionrows td:last").prev("td").find('input').addClass("default");
	}
	else
	{
		$("#adddescriptionrows td:last").prev("td").find('input').addClass("default");
	}
}

function getgstcode(statecode){
    //alert(statecode);
    var passData = "switchtype=customergstcode&stateid="+ encodeURIComponent(statecode);
	var queryString = "../ajax/customer.php";
    ajaxcall17 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false, dataType: "json",
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
				//alert(response['state_gst_code'] );
				if(response['state_gst_code'] != '')
				{
					//$('#form-error-alert').html('');
					$('#state_gst').html(response['state_gst_code']);
				}
				else
				{
					alert('No GST Code For State' );
				}
			}
		}, 
		error: function(a,b)
		{
			$("#form-error-alert").html(scripterror());
		}
	});	
}
function changeToUpperCase(t) {
   var eleVal = document.getElementById(t.id);
   eleVal.value= eleVal.value.toUpperCase().replace(/ /g,'');
}