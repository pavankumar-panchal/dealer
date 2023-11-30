var totalarray = new Array();
var customerarray = new Array();
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
	
	var passData = "switchtype=searchcustomerlist&databasefield=" + encodeURIComponent(subselection) + "&state=" + encodeURIComponent($("#state2").val())  + "&region=" +encodeURIComponent($("#region2").val())+ "&district=" +encodeURIComponent($("#district2").val()) + "&textfield=" +encodeURIComponent(textfield) +  "&productscode=" +encodeURIComponent(productslist) +"&dealer2=" +encodeURIComponent($("#currentdealer2").val()) + "&branch2=" + encodeURIComponent($("#branch2").val())+"&type2=" +encodeURIComponent($("#type2").val()) + "&category2=" + encodeURIComponent($("#category2").val())+ "&dummy=" + Math.floor(Math.random()*10054300000);
	//alert(passData)
	var ajaxcall2 = createajax();
	$('#customerselectionprocess').html(getprocessingimage());
	queryString = "../ajax/customer.php";
	ajaxcall2.open("POST", queryString, true);
	ajaxcall2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall2.onreadystatechange = function()
	{
		if(ajaxcall2.readyState == 4)
		{
			if(ajaxcall2.status == 200)
			{
				var response = ajaxcall2.responseText.split('^*^');//alert(response)
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
		    }
			else
				$('#customerselectionprocess').html(scripterror());
		}
	}
	ajaxcall2.send(passData);
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



function refreshcustomerarray()
{
	var passData = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000);
	$('#customerselectionprocess').html(getprocessingimage());
	var ajaxcall11 = createajax();
	queryString = "../ajax/customer.php";
	ajaxcall11.open("POST", queryString, true);
	ajaxcall11.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall11.onreadystatechange = function()
	{
		if(ajaxcall11.readyState == 4)
		{
			if(ajaxcall11.status == 200)
			{
				var response = ajaxcall11.responseText.split('^*^');//alert(response)
				if(response == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					customerarray = new Array();
					if(response == '')
					{
						document.getElementById('customerselectionprocess').innerHTML = '';
						for( var i=0; i<response.length; i++)
						{
							customerarray[i] = response[i];
						}
						getcustomerlist1();
						$('#customerselectionprocess').html(successsearchmessage('No Customer...'));
						$('#totalcount').html('0');
					}
					else
					{
						$('#customerselectionprocess').html('');
						for( var i=0; i<response.length; i++)
						{
							customerarray[i] = response[i];
						}
						flag = true;
						getcustomerlist1();
						$('#customerselectionprocess').html(successsearchmessage('All Customers...'));
						$('#totalcount').html(customerarray.length);
					}
				}
			}
			else
				$('#customerselectionprocess').html(scripterror());
		}
	}
	ajaxcall11.send(passData);
}

function getcustomerlist1()
{	

	var form = $("#submitform");
	//document.getElementById('customerselectionprocess').innerHTML = '';
	var selectbox = $('#customerlist');
	//document.getElementById('customerselectionprocess').innerHTML = successsearchmessage('All Customer...');
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
//Function select the tab in display-Meghana[18/12/2009]
function displayDiv()
{
	$("#filterdiv").toggle();
}

function formsubmit(command)
{
	var form = $("#submitform" );
	var error = $("#form-error" );
	var phonevalues = '';
	var cellvalues = '';
	var emailvalues = '';
	var namevalues = '';
	var field = $("#businessname" );
	if(!field.val()) { error.html(errormessage("Enter the Business Name [Company]. ")); field.focus(); return false; }
	if(field.val()) { if(!validatebusinessname(field.val())) { error.html(errormessage('Business name contains special characters. Please use only Alpha / Numeric / space / hyphen / small brackets. ')); field.focus(); return false; } }
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
		if(field.val()) { if(!validatephone(field.val())) { error.html(errormessage('Enter the valid Phone Number.')); field.focus(); return false; } }
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

	tabopen5('1','tabg1');
	
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
		/*if(!field.value) { error.innerHTML = errormessage("Enter the STD Code. "); field.focus(); return false; }*/
	if(field.val()) { if(!validatestdcode(field.val())) { error.html(errormessage('Enter the valid STD Code.')); field.focus(); return false; } }
	var field = $("#fax");
	if(field.val()) { if(!validatephone(field.val())) { error.html(errormessage('Enter the valid Fax Number.')); field.focus(); return false; } }
	//Website validation - Rashmi -18/11/09
	var field = $("#website");
	if(field.val())	{ if(!validatewebsite(field.val())) { error.html(errormessage('Enter the valid Website.')); field.focus(); return false; } }
	var field = $('#companyclosed:checked').val();
	if(field != 'on') var companyclosed = 'no'; else companyclosed = 'yes';
	var field = $('#promotionalsms:checked').val();
	if(field != 'on') var promotionalsms = 'no'; else promotionalsms = 'yes';
	var field = $('#promotionalemail:checked').val();
	if(field != 'on') var promotionalemail = 'no'; else promotionalemail = 'yes';

		var passData = "";
		if(command == 'save')
		{
			passData =  "switchtype=save&businessname=" + encodeURIComponent($("#businessname" ).val()) + "&customerid=" + encodeURIComponent($('#customerid').val())  + "&address=" + encodeURIComponent($('#address').val()) + "&place=" + encodeURIComponent($('#place').val()) + "&pincode=" + encodeURIComponent($('#pincode').val()) + "&district=" + encodeURIComponent($('#district').val())  + "&category=" + encodeURIComponent($('#category').val()) + "&type=" + encodeURIComponent($('#type').val()) + "&stdcode=" + encodeURIComponent($('#stdcode').val()) + "&fax=" + encodeURIComponent($('#fax').val()) + "&companyclosed=" + encodeURIComponent(companyclosed) + "&promotionalsms=" + encodeURIComponent(promotionalsms) + "&promotionalemail=" + encodeURIComponent(promotionalemail) + "&website=" + encodeURIComponent($('#website').val()) + "&remarks=" + encodeURIComponent($('#remarks').val())  + "&lastslno=" + encodeURIComponent($('#lastslno').val()) + "&contactarray=" + encodeURIComponent(contactarray)+ "&totalarray=" + encodeURIComponent(totalarray)+ "&dummy=" + Math.floor(Math.random()*100000000);//alert(passData)

		}
		else
		{
				alert('You are using a wrong provision.');
				return false;
		}
		queryString = '../ajax/customer.php';
		var ajaxcall0 = createajax();
		error.html('<img src="../images/imax-loading-image.gif" border="0" />');
		ajaxcall0.open('POST', queryString, true);
		ajaxcall0.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		ajaxcall0.onreadystatechange = function()
		{
			if(ajaxcall0.readyState == 4)
			{
				if(ajaxcall0.status == 200)
				{
					var ajaxresponse = ajaxcall0.responseText;
					if(ajaxresponse == 'Thinking to redirect')
					{
						window.location = "../logout.php";
						return false;
					}
					else
					var response = ajaxresponse.split('^');//alert(ajaxresponse)
					if(response[0] == '1')
					{
						error.html(successmessage(response[1]));
						refreshcustomerarray();
						newentry();
						rowwdelete();
					}
					else if(response[0] == '2')
					{
						error.html(successmessage(response[1]));
						refreshcustomerarray();
						newentry();
						rowwdelete();
					}
					else
					{
						error.html(errormessage('Unable to Connect....' + ajaxcall0.responseText));
					}
				}
				else
					error.html(scripterror());
		   }
		}
		ajaxcall0.send(passData);	
}


function newentry()
{
	var form = $('#submitform');
	totalarray = '';
	tabopen5('1','tabg1');
	form[0].reset();
	$('#lastslno').val('');
	$('#businessname').focus();
	$('#disablelogin').html('');
	$('#corporateorder').html('');
	$('#profilepending').html('');
	$('#activecustomer').html('Not Avaliable');
	$('#remarks').readOnly = false;
	enablesave();
	disableregistration();
	$('#createddate').html('Not Available');
	$('districtcodedisplay').html('<select name="district" class="swiftselect-mandatory" id="district" style="width:200px;"><option value="">Select A State First</option></select>');	
	//document.getElementById('tabgroupgridc1').innerHTML = 'No datas found to be displayed.';
	gridtabcus4('1','tabgroupgrid','&nbsp; &nbsp;Current Registrations');
	//document.getElementById('tabgroupgridc1').innerHTML = 'No datas found to be displayed.';
	clearregistrationform();
	clearcarddetails();
	//closedetailsdiv();
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
		var row = '<tr id="removedescriptionrow'+ slno+'"><td  width="5%"><div align="left"><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td><td width="11%"><div align="center"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:110px" class="swiftselect-mandatory"><option value="" selected="selected" >--Select--</option><option value="general">General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="ithead/edp">IT-Head/EDP</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="others" >Others</option></select></div></td><td width="16%"><div align="center"><input name="name'+ slno+'" type="text" class="swifttext" id="name'+ slno+'"  style="width:115px"  maxlength="130"  autocomplete="off"/></div></td><td width="18%"><div align="center"><input name="phone'+ slno+'" type="text"class="swifttext" id="phone'+ slno+'" style="width:110px" maxlength="100"  autocomplete="off" /></div></td><td width="15%"><div align="center"><input name="cell'+ slno+'" type="text" class="swifttext" id="cell'+ slno+'" style="width:100px"  maxlength="10"  autocomplete="off"/></div></td><td width="27%"><div align="center"><input name="emailid'+ slno+'" type="text" class="swifttext" id="emailid'+ slno+'" style="width:150px"  maxlength="200"  autocomplete="off"/></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" /></td></tr>';
		$("#adddescriptionrows").append(row);
		$('#'+value).html(slno);
	}
		
}

function generatecustomerregistration(id,startlimit)
{
	var form = $("#submitform");
	$('#lastslno').val(id);	
	var passData = "switchtype=customerregistration&lastslno="+ encodeURIComponent($('#lastslno').val()) + "&startlimit=" + encodeURIComponent(startlimit);//alert(passData)
	var queryString = "../ajax/customer.php";
	ajaxcall4 = createajax();
	$('#tabgroupgridc1_1').html(getprocessingimage());
	$('#tabgroupgridc1link').html('');
	ajaxcall4.open("POST", queryString, true);
	ajaxcall4.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall4.onreadystatechange = function()
	{
		if(ajaxcall4.readyState == 4)
		{
			if(ajaxcall4.status == 200)
			{
				var ajaxresponse = ajaxcall4.responseText;
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					var response = ajaxresponse.split('^');//alert(ajaxresponse)
					if(response[0] == '1')
					{
						gridtabcus4(1,'tabgroupgrid','&nbsp; &nbsp;Current Registrations');
						$('#tabgroupgridwb1').html("Total Count :  " + response[2]);
						$('#tabgroupgridc1_1').html(response[1]); //alert(response[0]);
						$('#tabgroupgridc1link').html(response[3]); //alert(response[2]);
					}
					else
					if(response[0] == '2')
					{
						$('#tabgroupgridc1_1').html(scripterror());
					}
				}
				
			}
			else
				$('#tabgroupgridc1_1').html(scripterror());
		}
	}
	ajaxcall4.send(passData);
}


function getmorecustomerregistration(id,startlimit,slno,showtype)
{
	var form = $('#submitform');
	$('#lastslno').val(id);	
	var passData = "switchtype=customerregistration&lastslno="+ encodeURIComponent($('#lastslno').val()) + "&startlimit=" + startlimit+ "&slno=" + slno+ "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);
//	alert(passData);
	var queryString = "../ajax/customer.php";
	ajaxcall10 = createajax();
	$('#tabgroupgridc1link').html('<img src="../images/inventory-processing.gif" border= "0">');
	ajaxcall10.open("POST", queryString, true);
	ajaxcall10.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall10.onreadystatechange = function()
	{
		if(ajaxcall10.readyState == 4)
		{
			if(ajaxcall10.status == 200)
			{
				var ajaxresponse = ajaxcall10.responseText;
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
						gridtabcus4(1,'tabgroupgrid','&nbsp; &nbsp;Current Registrations');
						$('#tabgroupgridwb1').html("Total Count :  " + response[2]);
						$('#regresultgrid').html($('#tabgroupgridc1_1').html());
						$('#tabgroupgridc1_1').html($('#regresultgrid').html().replace(/\<\/table\>/gi,'')+ response[1]) ;
						$('#tabgroupgridc1link').html(response[3]);
					}
					else
					if(response[0] == '2')
					{
						$('#tabgroupgridc1_1').html(scripterror());
					}
				}
				
			}
			else
				$('#tabgroupgridc1_1').html(scripterror());
		}
	}
	ajaxcall10.send(passData);
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
		var passData = "switchtype=customerdetailstoform&lastslno=" + encodeURIComponent(cusid) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
		ajaxcall3 = createajax();
		$('#form-error').html(getprocessingimage());
		var queryString = "../ajax/customer.php";
		ajaxcall3.open("POST", queryString, true);
		ajaxcall3.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajaxcall3.onreadystatechange = function()
		{
			if(ajaxcall3.readyState == 4)
			{
				if(ajaxcall3.status == 200)
				{
					$('#form-error').html('');
					$('#searchcustomerid').val('');
					var response = (ajaxcall3.responseText).split("^");//alert(response)
					if(response == 'Thinking to redirect')
					{
						window.location = "../logout.php";
						return false;
					}
					else
					if(response[0] == '')
					{
						
						alert('Customer Not Available.');
						$('#districtcodedisplay').html('<select name="district" class="swiftselect-mandatory" id="district"><option value="">Select A State First</option></select>');	
						$('#tabgroupgridc1').html('No datas found to be displayed.');
						//gridtabcus4('1','tabgroupgrid','&nbsp; &nbsp;Current Registrations');
						clearregistrationform();
						return false;
						
					} 
					//alert(response)
					//disablesave();
					$('#lastslno').val(response[0]);//alert(response[16])
					generatecustomerregistration(response[0],''); 
					generatecustomerattachcards(response[0]);
					enableregistration();
					$('#customerid').val(response[1]);
					$('#businessname').val(response[2]);
					$('#short_url').html(response[26] +"\r\n"+ response[2]+"\r\n"+ response[3]+"\n"+ response[4]+"\n"+ response[23]+"\n"+ response[24]+"\n" + response[7]+"\r\n"+ response[27]+"\n"+ response[28]+"\n"+ response[29]);
					//form.contactperson.value = response[3];
					$('#address').val(response[3]);
					$('#place').val(response[4]);
					$('#state').val(response[6]);
					getdistrict('districtcodedisplay', response[6])
					$('#district').val(response[5]);
					$('#pincode').val(response[7]);
					$('#stdcode').val(response[8]);
					$('#website').val(response[9]);
					$('#category').val(response[10]);
					$('#type').val(response[11]);
					$('#remarks').val(response[12]);
					//form.remarks.readOnly = true;
					$('#currentdealer').val(response[13]);
					$('#disablelogin').html(response[14]);
					$('#createddate').html(response[15]);
					$('#corporateorder').html(response[16]);
					$('#fax').val(response[17]); 
					$('#activecustomer').html(response[21]);//alert(response[23])
					$('#branchdisplay').html(response[22]);
					if(response[19] == '')
					{
						$('#profilepending').html('');
					}
					else
					$('#profilepending').html('<div class ="displaysuccessbox">' + response[19]+ '</div>');
					autochecknew(form.companyclosed,response[22]);//alert(response[30])
					autochecknew(form.promotionalsms,response[30]);
					autochecknew(form.promotionalemail,response[31]);
					
					var countrow = response[25].split('****');
					$('#adddescriptionrows tr').remove();
					for(k=1;k<=countrow.length;k++)
					{
						slno = k;
						rowid = 'removedescriptionrow'+ slno;
						
						if(k == 10)
						{
							var value = 'contactname'+slno;
							$('#adddescriptionrowdiv').hide();
							var row = '<tr id="removedescriptionrow'+ slno+'"><td  width="5%"><div align="left"><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td><td width="11%"><div align="center"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:110px" class="swiftselect-mandatory"><option value="" selected="selected" >--Select--</option><option value="general">General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="ithead/edp">IT-Head/EDP</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="others" >Others</option></select></div></td><td width="16%"><div align="center"><input name="name'+ slno+'" type="text" class="swifttext" id="name'+ slno+'"  style="width:115px"  maxlength="130"  autocomplete="off"/></div></td><td width="18%"><div align="center"><input name="phone'+ slno+'" type="text"class="swifttext" id="phone'+ slno+'" style="width:110px" maxlength="100"  autocomplete="off" /></div></td><td width="15%"><div align="center"><input name="cell'+ slno+'" type="text" class="swifttext" id="cell'+ slno+'" style="width:100px"  maxlength="10"  autocomplete="off"/></div></td><td width="27%"><div align="center"><input name="emailid'+ slno+'" type="text" class="swifttext" id="emailid'+ slno+'" style="width:150px"  maxlength="200"  autocomplete="off"/></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" /></td></tr>';
						}
						else if(k == 1)
						{
							var value = 'contactname'+slno;
							$('#adddescriptionrowdiv').show();
							var row = '<tr id="removedescriptionrow'+ slno+'"><td  width="5%"><div align="left"><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td><td width="11%"><div align="center"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:110px" class="swiftselect-mandatory"><option value="" selected="selected" >--Select--</option><option value="general">General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="ithead/edp">IT-Head/EDP</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="others" >Others</option></select></div></td><td width="16%"><div align="center"><input name="name'+ slno+'" type="text" class="swifttext" id="name'+ slno+'"  style="width:115px"  maxlength="130"  autocomplete="off"/></div></td><td width="18%"><div align="center"><input name="phone'+ slno+'" type="text"class="swifttext" id="phone'+ slno+'" style="width:110px" maxlength="100"  autocomplete="off" /></div></td><td width="15%"><div align="center"><input name="cell'+ slno+'" type="text" class="swifttext" id="cell'+ slno+'" style="width:100px"  maxlength="10"  autocomplete="off"/></div></td><td width="27%"><div align="center"><input name="emailid'+ slno+'" type="text" class="swifttext" id="emailid'+ slno+'" style="width:150px"  maxlength="200"  autocomplete="off"/></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" /></td></tr>';
						}
						else
						{
							var value = 'contactname'+slno;
							$('#adddescriptionrowdiv').show();
							var row = '<tr id="removedescriptionrow'+ slno+'"><td  width="5%"><div align="left"><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td><td width="11%"><div align="center"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:110px" class="swiftselect-mandatory"><option value="" selected="selected" >--Select--</option><option value="general" >General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="ithead/edp">IT-Head/EDP</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="others" >Others</option></select></div></td><td width="16%"><div align="center"><input name="name'+ slno+'" type="text" class="swifttext" id="name'+ slno+'"  style="width:115px"  maxlength="130"  autocomplete="off"/></div></td><td width="18%"><div align="center"><input name="phone'+ slno+'" type="text"class="swifttext" id="phone'+ slno+'" style="width:110px" maxlength="100"  autocomplete="off" /></div></td><td width="15%"><div align="center"><input name="cell'+ slno+'" type="text" class="swifttext" id="cell'+ slno+'" style="width:100px"  maxlength="10"  autocomplete="off"/></div></td><td width="27%"><div align="center"><input name="emailid'+ slno+'" type="text" class="swifttext" id="emailid'+ slno+'" style="width:150px"  maxlength="200"  autocomplete="off"/></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" /></td></tr>';
						}
						$("#adddescriptionrows").append(row);
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
				else
				$('#customerselectionprocess').html(scripterror());
			 }
		}
		ajaxcall3.send(passData);
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
				if(pattern.test(customerarray[i].toLowerCase()))
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
	var passData = "switchtype=searchbycustomerid&customerid=" + encodeURIComponent(cusid) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
	ajaxcall5 = createajax(passData);
	var queryString = "../ajax/customer.php";
	ajaxcall5.open("POST", queryString, true);
	ajaxcall5.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall5.onreadystatechange = function()
	{
		if(ajaxcall5.readyState == 4)
		{
			if(ajaxcall5.status == 200)
			{
				var ajaxresponse = ajaxcall5.responseText;
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				var response = (ajaxresponse).split("^");// alert(response)
				$('#filterdiv').hide();
				if(response[0] == '')
				{
					alert('Customer Not Available.');
					$('#districtcodedisplay').html('<select name="district" class="swiftselect-mandatory" id="district"><option value="">Select A State First</option></select>');	
					$('#tabgroupgridc1_1').html('No datas found to be displayed.');
					$('#tabgroupgridc1link').html('');
					$('#tabgroupgridwb1').html("Total Count :  "+ 0);
					clearregistrationform();
					return false;
				}

				$('#lastslno').val(response[0]);//alert(response[16])
				generatecustomerregistration(response[0],''); 
				generatecustomerattachcards(response[0]);
				enableregistration();
				$('#customerid').val(response[1]);
				$('#businessname').val(response[2]);
				$('#short_url').html(response[26] +"\r\n"+ response[2]+"\r\n"+ response[3]+"\n"+ response[4]+"\n"+ response[23]+"\n"+ response[24]+"\n" + response[7]+"\r\n"+ response[27]+"\n"+ response[28]+"\n"+ response[29]);
				//form.contactperson.value = response[3];
				$('#address').val(response[3]);
				$('#place').val(response[4]);
				$('#state').val(response[6]);
				getdistrict('districtcodedisplay', response[6])
				$('#district').val(response[5]);
				$('#pincode').val(response[7]);
				$('#stdcode').val(response[8]);
				$('#website').val(response[9]);
				$('#category').val(response[10]);
				$('#type').val(response[11]);
				$('#remarks').val(response[12]);
				//form.remarks.readOnly = true;
				$('#currentdealer').val(response[13]);
				$('#disablelogin').html(response[14]);
				$('#createddate').html(response[15]);
				$('#corporateorder').html(response[16]);
				$('#fax').val(response[17]); 
				$('#activecustomer').html(response[20]);//alert(response[23])
				$('#branchdisplay').html(response[21]);
				if(response[19] == '')
				{
					$('profilepending').html('');
				}
				else
				$('#profilepending').html('<div class ="displaysuccessbox">' + response[19]+ '</div>');
				autochecknew(form.companyclosed,response[22]);
				autochecknew(form.promotionalsms,response[26]);
				autochecknew(form.promotionalemail,response[27]);
				
				var countrow = response[25].split('****');//alert(response[25])
				$('#adddescriptionrows tr').remove();
				for(k=1;k<=countrow.length;k++)
				{
					slno = k;
					rowid = 'removedescriptionrow'+ slno;
					
					if(k == 10)
					{
						var value = 'contactname'+slno;
						$('#adddescriptionrowdiv').hide();
						var row = '<tr id="removedescriptionrow'+ slno+'"><td  width="5%"><div align="left"><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td><td width="11%"><div align="center"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:110px" class="swiftselect-mandatory"><option value="" selected="selected" >--Select--</option><option value="general">General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="ithead/edp">IT-Head/EDP</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="others" >Others</option></select></div></td><td width="16%"><div align="center"><input name="name'+ slno+'" type="text" class="swifttext" id="name'+ slno+'"  style="width:115px"  maxlength="130"  autocomplete="off"/></div></td><td width="18%"><div align="center"><input name="phone'+ slno+'" type="text"class="swifttext" id="phone'+ slno+'" style="width:110px" maxlength="100"  autocomplete="off" /></div></td><td width="15%"><div align="center"><input name="cell'+ slno+'" type="text" class="swifttext" id="cell'+ slno+'" style="width:100px"  maxlength="10"  autocomplete="off"/></div></td><td width="27%"><div align="center"><input name="emailid'+ slno+'" type="text" class="swifttext" id="emailid'+ slno+'" style="width:150px"  maxlength="200"  autocomplete="off"/></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" /></td></tr>';
					}
					else if(k == 1)
					{
						var value = 'contactname'+slno;
						$('#adddescriptionrowdiv').show();
						var row = '<tr id="removedescriptionrow'+ slno+'"><td  width="5%"><div align="left"><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td><td width="11%"><div align="center"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:110px" class="swiftselect-mandatory"><option value="" selected="selected" >--Select--</option><option value="general">General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="ithead/edp">IT-Head/EDP</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="others" >Others</option></select></div></td><td width="16%"><div align="center"><input name="name'+ slno+'" type="text" class="swifttext" id="name'+ slno+'"  style="width:115px"  maxlength="130"  autocomplete="off"/></div></td><td width="18%"><div align="center"><input name="phone'+ slno+'" type="text"class="swifttext" id="phone'+ slno+'" style="width:110px" maxlength="100"  autocomplete="off" /></div></td><td width="15%"><div align="center"><input name="cell'+ slno+'" type="text" class="swifttext" id="cell'+ slno+'" style="width:100px"  maxlength="10"  autocomplete="off"/></div></td><td width="27%"><div align="center"><input name="emailid'+ slno+'" type="text" class="swifttext" id="emailid'+ slno+'" style="width:150px"  maxlength="200"  autocomplete="off"/></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" /></td></tr>';
					}
					else
					{
						var value = 'contactname'+slno;
						$('#adddescriptionrowdiv').show();
						var row = '<tr id="removedescriptionrow'+ slno+'"><td  width="5%"><div align="left"><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td><td width="11%"><div align="center"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:110px" class="swiftselect-mandatory"><option value="" selected="selected" >--Select--</option><option value="general" >General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="ithead/edp">IT-Head/EDP</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="others" >Others</option></select></div></td><td width="16%"><div align="center"><input name="name'+ slno+'" type="text" class="swifttext" id="name'+ slno+'"  style="width:115px"  maxlength="130"  autocomplete="off"/></div></td><td width="18%"><div align="center"><input name="phone'+ slno+'" type="text"class="swifttext" id="phone'+ slno+'" style="width:110px" maxlength="100"  autocomplete="off" /></div></td><td width="15%"><div align="center"><input name="cell'+ slno+'" type="text" class="swifttext" id="cell'+ slno+'" style="width:100px"  maxlength="10"  autocomplete="off"/></div></td><td width="27%"><div align="center"><input name="emailid'+ slno+'" type="text" class="swifttext" id="emailid'+ slno+'" style="width:150px"  maxlength="200"  autocomplete="off"/></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" /></td></tr>';
					}
					$("#adddescriptionrows").append(row);
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
			else
				$('#form-error').html(scripterror());
		}
	}
	ajaxcall5.send(passData);
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
		var passData = "switchtype=scratchdetailstoform&cardid=" + encodeURIComponent(cardid) + "&dummy=" + Math.floor(Math.random()*100032680100); 
		ajaxcall5 = createajax();
		$('#reg-form-error').html(getprocessingimage());
		var queryString = "../ajax/customer.php";
		ajaxcall5.open("POST", queryString, true);
		ajaxcall5.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajaxcall5.onreadystatechange = function()
		{
			if(ajaxcall5.readyState == 4)
			{
				if(ajaxcall5.status == 200)
				{
						$('#reg-form-error').html('');
						$('#scratchcradloading').html('');
						$('#detailsonscratch').show();
						//document.getElementById('transferimagespan').style.visibility='visible';
						var response = (ajaxcall5.responseText).split("^"); //alert(response)
						if(response == 'Thinking to redirect')
						{
							window.location = "../logout.php";
							return false;
						}
						else if(response[0] == '1')
						{
							$('#cardnumberdisplay').html(response[1]);
							$('#scratchnodisplay').html(response[2]);
							$('#purchasetypedisplay').html(response[3]);
							$('#usagetypedisplay').html(response[4]);
							$('#attachedtodisplay').html(response[5]);
							$('#productdisplay').html(response[8] + ' [' + response[7] + ']');
							$('#registeredtodisplay').html(response[12]);
							$('#attachdatedisplay').html(response[9]);
							$('#registerdatedisplay').html(response[10]);
							$('#cardstatusdisplay').html(response[13]);
							$('#schemedisplay').html(response[14]);
							
							//document.getElementById('searchdelaerrep').value = response[4]; //dealername
							$('#delaerrep').val(response[6]);//dealeridal
							//alert(document.getElementById('delaerrep').value);
							$('#productname').val(response[8] + ' [' + response[7] + ']');
							$('#productcode').val(response[7]);
							/* Transfer Data Details */
							$('#tfpurchasetype').val(response[3]);
							$('#tfusagetype').val(response[4]);
							$('#tfdealer').val(response[5]); //dealername
							$('#tfproduct').val(response[8]);
						}
				}
				else
				$('#reg-form-error').html(scripterror());
			}
		}
		ajaxcall5.send(passData);
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
		$('#scratchdisplay').hide();
		regcardarray = up_regcardarray;
		getregcardlist();
	}
}

function makearegistration()
{
	//document.getElementById('transferimagespan').style.display='visible';
	var form = $('#registrationform');
	var error = $('#reg-form-error');
	var registrationfieldradio = $('input[name=registrationfieldradio]:checked').val();
	if(registrationfieldradio == 'newlicence')
	{
		$('#hiddenregistrationtype').val('newlicence');
	
		if(!$('#lastslno').val()) { error.html(errormessage("Please Select a Customer from list above."));  return false; }
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
		var scratchnumber = $('#scratchcardlist').val();
		if(!scratchnumber) { error.html(errormessage("Please Select the Scratch Number from the list.")); field.focus(); return false; }
		var field = $('#computerid');
		if(!field.val()) { error.html(errormessage("Please Enter the Computer ID.")); field.focus(); return false; }
		if(field.val()) { if(!computeridvalidate(field.val())) { error.html(errormessage("Enter the valid Computerid ")); field.focus(); return false; } }
		var field = $('#billno');
		if(!field.val()) { error.html(errormessage("Please Enter the Bill Number.")); field.focus(); return false; }
		var field = $('#billamount');
		if(!field.val()) { error.html(errormessage("Please Enter the Bill Amount.")); field.focus(); return false; }
		if(field.val())	{ if(!validateamount(field.val())) { error.html(errormessage('Amount is not Valid.')); field.focus(); return false; } }
	}
		var passData = "switchtype=generatesoftkey&registrationtype=" + encodeURIComponent($('#hiddenregistrationtype').val()) + "&scratchnumber=" + encodeURIComponent(scratchnumber) + "&customerid=" + encodeURIComponent($('#lastslno').val()) + "&delaerrep=" + encodeURIComponent($('#delaerrep').val()) + "&productcode=" + encodeURIComponent($('#productcode').val()) +  "&productname=" + encodeURIComponent($('#productname').val()) + "&computerid=" + encodeURIComponent($('#computerid').val()) + "&billno=" + encodeURIComponent($('#billno').val()) + "&billamount=" + encodeURIComponent($('#billamount').val()) + "&regremarks=" + encodeURIComponent($('#regremarks').val()) + "&usagetype=" + encodeURIComponent($('#usagetypedisplay').html()) + "&purchasetype=" +  encodeURIComponent($('#purchasetypedisplay').html()) + "&dummy=" + Math.floor(Math.random()*100032680100);
		ajaxcall6 = createajax();
		error.innerHTML = getprocessingimage();
		var queryString = "../ajax/customer.php";
		ajaxcall6.open("POST", queryString, true);
		ajaxcall6.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajaxcall6.onreadystatechange = function()
		{
			if(ajaxcall6.readyState == 4)
			{
				if(ajaxcall6.status == 200)
				{
					error.innerHTML = '';
					var response = (ajaxcall6.responseText).split("^");
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
				}
				else
					error.html(scripterror());
			}
		}
		ajaxcall6.send(passData);
}

function enableregistration()
{
	//document.getElementByNames('registrationfieldradio').disabled = false;
	$('#registrationfieldradio0').attr('disabled',false);
	$('#registrationfieldradio1').attr('disabled',false);
	$('#searchscratchnumber').attr('disabled',false);
	$('#scratchnumber').attr('disabled',false);
	$('#delaerrep').attr('disabled',true);
	//document.getElementById('searchdelaerrep').disabled = false;
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
	//document.getElementByNames('registrationfieldradio').disabled = true;
	if($('#registrationfieldradio0')) $('#registrationfieldradio0').attr('disabled',true);
	if($('#registrationfieldradio1')) $('#registrationfieldradio1').attr('disabled',true);
	if($('#registrationfieldradio2')) $('#registrationfieldradio2').attr('disabled',true);
	if($('#registrationfieldradio3')) $('#registrationfieldradio3').attr('disabled',true);
	$('#searchscratchnumber').attr('disabled',true);
	$('#scratchnumber').attr('disabled',true);
	$('#delaerrep').attr('disabled',true);
	//document.getElementById('searchdelaerrep').disabled = true;
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
	//document.getElementById('searchdelaerrep').value = '';
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
	$('#tfdealer').val($('#attachedtodisplay').html()); //dealername
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

function getreregscratchcards()
{
	
	var lastslno = $('#lastslno').val();
	var passData = "switchtype=getreregscratchcardlist&lastslno=" + encodeURIComponent($('#lastslno').val());
	ajaxcall8 = createajax();//alert(passData);
	var queryString = "../ajax/customer.php";
	ajaxcall8.open("POST", queryString, true);
	ajaxcall8.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall8.onreadystatechange = function()
	{
		if(ajaxcall8.readyState == 4)
		{
			if(ajaxcall8.status == 200)
			{
				var ajaxresponse = ajaxcall8.responseText; //alert(ajaxresponse);
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				$('#dispreregcardlist').html(ajaxresponse);
			}
			else
				error.html(scripterror());
		}
	}
	ajaxcall8.send(passData);
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
	var passData = "switchtype=generatecustomerattachcards&lastslno="+ encodeURIComponent($('#lastslno').val());
	//alert(passData);
	var queryString = "../ajax/customer.php";
	ajaxcall9 = createajax();
	ajaxcall9.open("POST", queryString, true);
	ajaxcall9.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall9.onreadystatechange = function()
	{//alert(passData);
		if(ajaxcall9.readyState == 4)
		{
			if(ajaxcall9.status == 200)
			{
				var ajaxresponse = ajaxcall9.responseText;
				var response = ajaxresponse.split("^");
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
						$('#tabgroupgridc4').html(response[1]);
					}
					else
					{
						$('#tabgroupgridc4').html(scripterror());
					}
				}
			}
			else
				$('#tabgroupgridc4').html(scripterror());
		}
	}
	ajaxcall9.send(passData);
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



//Function to reset the from to the default value-Meghana[21/12/2009]
function resetDefaultValues(oForm)
{
    var elements = oForm.elements; 
 	oForm.reset();
	$('#filter-form-error').html('');
	for (i=0; i<elements.length; i++) 
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
	
	var row = '<tr id="removedescriptionrow'+ slno+'"><td  width="5%"><div align="left"><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td><td width="11%"><div align="center"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:110px" class="swiftselect-mandatory"><option value="" selected="selected" >--Select--</option><option value="general" >General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="ithead/edp">IT-Head/EDP</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="others" >Others</option></select></div></td><td width="16%"><div align="center"><input name="name'+ slno+'" type="text" class="swifttext" id="name'+ slno+'"  style="width:115px"  maxlength="130"  autocomplete="off"/></div></td><td width="18%"><div align="center"><input name="phone'+ slno+'" type="text"class="swifttext" id="phone'+ slno+'" style="width:110px" maxlength="100"  autocomplete="off" /></div></td><td width="15%"><div align="center"><input name="cell'+ slno+'" type="text" class="swifttext" id="cell'+ slno+'" style="width:100px"  maxlength="10"  autocomplete="off"/></div></td><td width="27%"><div align="center"><input name="emailid'+ slno+'" type="text" class="swifttext" id="emailid'+ slno+'" style="width:150px"  maxlength="200"  autocomplete="off"/></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" /></td></tr>';
	
	$("#adddescriptionrows").append(row);
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
	else
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


function generateinvoicedetails(startlimit)
{
	
	var form = $('#detailsform');
	$('#invoicedetailsgridc1').show();
	$("#hiddeninvoiceslno").val($("#lastslno").val()); 
	//$('#detailsdiv').hide();
	var passData = "switchtype=invoicedetailsgrid&lastslno="+ encodeURIComponent($('#lastslno').val()) + "&startlimit=" + encodeURIComponent(startlimit);
	var queryString = "../ajax/customer.php";
	ajaxcall41 = createajax();
	$('#invoicedetailsgridc1_1').html(getprocessingimage());
	$('#invoicedetailsgridc1link').html('');
	ajaxcall41.open("POST", queryString, true);
	ajaxcall41.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall41.onreadystatechange = function()
	{
		if(ajaxcall41.status == 200)
			{
				var ajaxresponse = ajaxcall41.responseText;
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					var response = ajaxresponse.split('^');//alert(response[0]);
					if(response[0] == 1)
					{
						$('#invoicedetailsgridwb1').html("Total Count :  " + response[2]);
						$('#invoicedetailsgridc1_1').html(response[1]);
						$('#invoicedetailsgridc1link').html(response[3]);
						//$('#invoicelastslno').val(response[4]);
					}
					else
					{
						$('#invoicedetailsgridc1_1').html("No datas found to be displayed");
					}
				}
				
			}
		else
			$('#invoicedetailsgridc1_1').html(scripterror());
	}
	ajaxcall41.send(passData);
}

function displayinvoicedetails()
{
	if($('#lastslno').val() != '')
	{
		generateinvoicedetails('');
		$("").colorbox({ inline:true, href:"#invoicedetailsgrid" });
	}
	else
	{
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

function generateinvoicedetails(startlimit)
{
	
	var form = $('#detailsform');
	$('#invoicedetailsgridc1').show();
	$("#hiddeninvoiceslno").val($("#lastslno").val()); 
	//$('#detailsdiv').hide();
	var passData = "switchtype=invoicedetailsgrid&lastslno="+ encodeURIComponent($('#lastslno').val()) + "&startlimit=" + encodeURIComponent(startlimit);
	var queryString = "../ajax/customer.php";
	ajaxcall41 = createajax();
	$('#invoicedetailsgridc1_1').html(getprocessingimage());
	$('#invoicedetailsgridc1link').html('');
	ajaxcall41.open("POST", queryString, true);
	ajaxcall41.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall41.onreadystatechange = function()
	{
		if(ajaxcall41.status == 200)
			{
				var ajaxresponse = ajaxcall41.responseText;
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					var response = ajaxresponse.split('^');//alert(response[0]);
					if(response[0] == 1)
					{
						$('#invoicedetailsgridwb1').html("Total Count :  " + response[2]);
						$('#invoicedetailsgridc1_1').html(response[1]);
						$('#invoicedetailsgridc1link').html(response[3]);
						//$('#invoicelastslno').val(response[4]);
					}
					else
					{
						$('#invoicedetailsgridc1_1').html("No datas found to be displayed");
					}
				}
				
			}
		else
			$('#invoicedetailsgridc1_1').html(scripterror());
	}
	ajaxcall41.send(passData);
}

