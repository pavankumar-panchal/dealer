var customerarray = new Array();


//Function to display All records ------------------------------------------
function getalldummydetails()
{
	var form = $('#submitform');
	var passdata = "switchtype=getalldata&dummy=" + Math.floor(Math.random()*100000000);//alert(passdata)
	var queryString = "../ajax/custdata.php";//alert(passdata)
	$('#form-error').html(getprocessingimage());
	ajaxcall0 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
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
				$("#form-error").html('');
				if(response[0] == '1')
				{
					$('#type_blankemail').html(response[1]);//alert(response[12]);
					$('#type_relyonid').html(response[2]);
					$('#type_dealerid').html(response[3]);
					$('#type_dummyid').html(response[4]);
					$('#type_totalemail').html(response[5]);
					
					$('#type_blankcell').html(response[6]);
					$('#type_dummycell').html(response[7]);
					$('#type_totalcell').html(response[8]);
					
					$('#type_blankphone').html(response[9]);
					$('#type_dummyphone').html(response[10]);
					$('#type_totalphone').html(response[11]);
					
					$('#type_totaltype').html(response[12]);
					$('#type_totalcategory').html(response[13]);
					$('#type_typeotherse').html(response[14]);
					$('#type_categoryothers').html(response[15]);
					$('#type_overtotaltype').html(response[16]);
					$('#type_overtotalcategory').html(response[17]);
				}
				else
				{
					$('#form-error').html("No datas found to be displayed.");
				}
			}
		}, 
		error: function(a,b)
		{
			$("#form-error").html(scripterror());
		}
	});	
}



//Function to Detailed Data display of Emailid's ------------------------------------------
function detailedemailgrid(type)
{
	$('#displaygriddata').html('');
	$('#tabdescription').html('');
	$("#displaybutton").html('');
	$('#typeselected').val('');
	$("").colorbox({ inline:true, href:"#colorboxdatagrid" , onLoad: function() {
    $('#cboxClose').hide()}});
	var form = $('#submitform');
	var passdata = "switchtype=detailemailgrid&type="+encodeURIComponent(type)+"&dummy=" + Math.floor(Math.random()*100000000);//alert(passdata)
	var queryString = "../ajax/custdata.php";//alert(passdata)
	$('#errormsg').html(getprocessingimage());
	ajaxcall1 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
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
				$("#errormsg").html('');
				if(response[0] == '1')
				{
					$('#typeselected').val(type);
					$('#displaygriddata').html(response[1]);
					$('#tabdescription').html(response[2]);
					var inputrow = '<input type="button" name="toexcel" class="swiftchoicebutton" value="To Excel" id="toexcel" onclick = "detailstoexcel(\'emailidtype\',\'' + type + '\');" >&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Close" id="closepreviewbutton"  onclick="$().colorbox.close();" class="swiftchoicebutton"/>';
					$("#displaybutton").append(inputrow);
				}
				
			}
		}, 
		error: function(a,b)
		{
			$("#form-error").html(scripterror());
		}
	});	
}

//Function to Active Records display of Cell ------------------------------------------
function detailedcellgrid(type)
{
	$('#displaygriddata').html('');
	$('#tabdescription').html('');
	$("#displaybutton").html('');
	$('#typeselected').val('');
	$("").colorbox({ inline:true, href:"#colorboxdatagrid" , onLoad: function() {
    $('#cboxClose').hide()}});
	var form = $('#submitform');
	var passdata = "switchtype=detailcellgrid&type="+encodeURIComponent(type)+"&dummy=" + Math.floor(Math.random()*100000000);//alert(passdata)
	var queryString = "../ajax/custdata.php";//alert(passdata)
	$('#errormsg').html(getprocessingimage());
	ajaxcall2 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
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
				$("#errormsg").html('');
				if(response[0] == '1')
				{
					$('#typeselected').val(type);
					$('#displaygriddata').html(response[1]);
					$('#tabdescription').html(response[2]);
					var inputrow = '<input type="button" name="toexcel" class="swiftchoicebutton" value="To Excel" id="toexcel" onclick = "detailstoexcel(\'celltype\',\'' + type + '\');" >&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Close" id="closepreviewbutton"  onclick="$().colorbox.close();" class="swiftchoicebutton"/>';
					$("#displaybutton").append(inputrow);
					
				}
				
			}
		}, 
		error: function(a,b)
		{
			$("#form-error").html(scripterror());
		}
	});	
}

//Function to Active Records display of Phone ------------------------------------------
function detailedphonegrid(type)
{
	$('#displaygriddata').html('');
	$('#tabdescription').html('');
	$("#displaybutton").html('');
	$('#typeselected').val('');
	$("").colorbox({ inline:true, href:"#colorboxdatagrid" , onLoad: function() {
    $('#cboxClose').hide()}});
	var form = $('#submitform');
	var passdata = "switchtype=detailphonegrid&type="+encodeURIComponent(type)+"&dummy=" + Math.floor(Math.random()*100000000);//alert(passdata)
	var queryString = "../ajax/custdata.php";//alert(passdata)
	$('#errormsg').html(getprocessingimage());
	ajaxcall3 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
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
				$("#errormsg").html('');
				if(response[0] == '1')
				{
					$('#typeselected').val(type);
					$('#displaygriddata').html(response[1]);
					$('#tabdescription').html(response[2]);
					var inputrow = '<input type="button" name="toexcel" class="swiftchoicebutton" value="To Excel" id="toexcel" onclick = "detailstoexcel(\'phonetype\',\'' + type + '\');" >&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Close" id="closepreviewbutton"  onclick="$().colorbox.close();" class="swiftchoicebutton"/>';
					$("#displaybutton").append(inputrow);
				}
				
			}
		}, 
		error: function(a,b)
		{
			$("#form-error").html(scripterror());
		}
	});	
}

//Function to Active Records display of Type and category ------------------------------------------
function detailedcustgrid(type)
{
	$('#displaygriddata').html('');
	$('#tabdescription').html('');
	$("#displaybutton").html('');
	$('#typeselected').val('');
	$("").colorbox({ inline:true, href:"#colorboxdatagrid" , onLoad: function() {
    $('#cboxClose').hide()}});
	var form = $('#submitform');
	var passdata = "switchtype=detailcustgrid&type="+encodeURIComponent(type)+"&dummy=" + Math.floor(Math.random()*100000000);//alert(passdata)
	var queryString = "../ajax/custdata.php";//alert(passdata)
	$('#errormsg').html(getprocessingimage());
	ajaxcall4 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
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
				$("#errormsg").html('');
				if(response[0] == '1')
				{
					$('#typeselected').val(type);
					$('#displaygriddata').html(response[1]);
					$('#tabdescription').html(response[2]);
					var inputrow = '<input type="button" name="toexcel" class="swiftchoicebutton" value="To Excel" id="toexcel" onclick = "detailstoexcel(\'customertypecategory\',\'' + type + '\');" >&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Close" id="closepreviewbutton"  onclick="$().colorbox.close();" class="swiftchoicebutton"/>';
					$("#displaybutton").append(inputrow);
				}
				
				
			}
		}, 
		error: function(a,b)
		{
			$("#form-error").html(scripterror());
		}
	});
}


function detailstoexcel(command,type)
{
	var form = $('#submitform');
	var detailsform = $('#detailsform');
	$('#colorbox-error',detailsform).html(getprocessingimage());
	$('#colorbox-error',detailsform).html('');
	$('#submitform').attr("action", "../ajax/custdatatoexcel.php?id="+command) ;
	$('#submitform').attr( 'target','_blank' );
	$('#submitform').submit();
}


function refreshcustomerarray()
{
	
	var passData = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000);
	$("#customerselectionprocess").html(getprocessingimage());
	queryString = "../ajax/custdata.php";
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
				var response = ajaxresponse.split('^*^');//alert(response)
				customerarray = new Array();
				for( var i=0; i<response.length; i++)
				{
					customerarray[i] = response[i]; 
				}
				getcustomerlist1();
				$("#customerselectionprocess").html(successsearchmessage('All Customers...'))
				$("#totalcount").html(customerarray.length);
			}
		}, 
		error: function(a,b)
		{
			$("#customerselectionprocess").html(scripterror());
		}
	});	
}

function getcustomerlist1()
{	
	var form = $("#submitform" );
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

function selectacustomer(input)
{
	var selectbox = $('#customerlist');
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
	selectfromlist();
}

function selectfromlist()
{
	var selectbox = $("#customerlist option:selected").val();
	$('#detailsearchtext').val($("#customerlist option:selected").text());
	$('#detailsearchtext').select();
	cleardetailsform();
	customerdetailstoform(selectbox);
	$('#lastslno').val(selectbox);	
}


function getcustlistonsearch()
{	
	var form = $("#submitform" );
	var selectbox = $('#customerlist');
	var numberofcustomers = custarray.length;
	$('#detailsearchtext').focus();
	var actuallimit = 500;
	var limitlist = (numberofcustomers > actuallimit)?actuallimit:numberofcustomers;
	
	$('option', selectbox).remove();
	var options = selectbox.attr('options');
	
	for( var i=0; i<limitlist; i++)
	{
		var splits = custarray[i].split("^");
		options[options.length] = new Option(splits[0], splits[1]);
	}
	if(numberofcustomers == 1)
	{
		$('#detailsearchtext').val(splits[0])
		selectacustomer(splits[0])
		$('#customerlist').val(splits[1]);
		customerdetailstoform(splits[1]);
		$('#lastslno').val(splits[1]);
	}
}

function customerdetailstoform(cusid)
{
	if(cusid != '')
	{
		var form = $("#submitform" );
		var passData = "switchtype=customerdetailstoform&lastslno=" + encodeURIComponent(cusid) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
		var queryString = "../ajax/custdata.php";
		$("#customerselectionprocess").html(getprocessingimage())
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
					$("#customerselectionprocess").html('');
					var response = (ajaxresponse).split("^");
					if(response[0] == '')
					{
						
						alert('Customer Not Available.');
						cleardetailsform();
					} 
					$("#cusid").html(response[1]);
					$("#businessnamedisplay").html(response[2]);
					$("#addressdisplay").html(response[3]);
					$("#placedisplay").html(response[4]);
					$("#districtdisplay").html(response[5]);
					$("#statedisplay").html(response[6]);
					$("#pincodedisplay").html(response[7]);
					$("#regiondisplay").html(response[8]);
					$("#stdcodedisplay").html(response[9]);
					$("#websitedisplay").html(response[10]);
					$("#categorydisplay").html(response[11]);
					$("#typedisplay").html(response[12]);
					$("#currentdealerdisplay").html(response[13]);
					$("#disablelogindisplay").html(response[14].toUpperCase());
					$("#createddate").html(response[15]);
					$("#corporateorderdisplay").html(response[16].toUpperCase());
					$("#faxdisplay").html(response[17]);
					$("#activecustomerdisplay").html(response[18].toUpperCase());
					$("#branchdisplay").html(response[19]);
					$("#companycloseddisplay").html(response[20].toUpperCase());
					$("#isdealerdisplay").html(response[21].toUpperCase());
					$("#displayinwebsitedisplay").html(response[22].toUpperCase());
					$("#promotionalsmsdisplay").html(response[23].toUpperCase());
					$("#promotionalemaildisplay").html(response[24].toUpperCase());
					$("#contactdetailsgrid").html(response[25]);
					$("#statecode").val(response[26]);
					$("#districtcode").val(response[27]);
					$("#regioncode").val(response[28]);
					$("#branchcode").val(response[29]);
					$("#typecode").val(response[30]);
					$("#categorycode").val(response[31]);
					$("#dealerid").val(response[32]);
					$("#checkboxvalue").val(response[33]);
				}
			}, 
			error: function(a,b)
			{
				$("#customerselectionprocess").html(scripterror());
			}
		});	
	}
}

function viewhistory(cusid)
{
	cleardetailsform();
	$().colorbox.close();
	tabopenimp2('2','tabg1');

	var passData = "switchtype=customergridtoform&cusid="+ encodeURIComponent(cusid);//alert(passData)
	var queryString = "../ajax/custdata.php";
	$('#customerselectionprocess').html(getprocessingimage());
	ajaxcall17 = $.ajax(
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
				$('#customerselectionprocess').html('');
				var response = ajaxresponse.split('^*^');
				custarray = new Array();
				for( var i=0; i<response.length; i++)
				{
					custarray[i] = response[i];
				}
				getcustlistonsearch();
			}
			
		}, 
		error: function(a,b)
		{

			$('#customerselectionprocess').html(scripterror());
		}
	});	
	
}

function cleardetailsform()
{
	$("#cusid").html('');
	$("#businessnamedisplay").html('');
	$("#addressdisplay").html('');
	$("#placedisplay").html('');
	$("#districtdisplay").html('');
	$("#statedisplay").html('');
	$("#pincodedisplay").html('');
	$("#regiondisplay").html('');
	$("#stdcodedisplay").html('');
	$("#websitedisplay").html('');
	$("#categorydisplay").html('');
	$("#typedisplay").html('');
	$("#currentdealerdisplay").html('');
	$("#disablelogindisplay").html('');
	$("#createddatedisplay").html('')
	$("#corporateorderdisplay").html('');
	$("#faxdisplay").html('');
	$("#activecustomerdisplay").html('');
	$("#branchdisplay").html('');
	$("#companycloseddisplay").html('');
	$("#isdealerdisplay").html('');
	$("#displayinwebsitedisplay").html('');
	$("#promotionalsmsdisplay").html('');;
	$("#promotionalemaildisplay").html('');
	$("#contactdetailsgrid").html('');
	var row = '<table width="100%" border="0" cellspacing="0" cellpadding="5"  class="table-border-grid"><tr class="tr-grid-header"><td width="7%" align="center" class="td-border-grid">Slno</td><td width="15%" align="center" class="td-border-grid">Type</td><td width="19%" align="center" class="td-border-grid">Name</td><td width="18%" align="center" class="td-border-grid">Phone</td><td width="15%" align="center" class="td-border-grid">Cell</td><td width="26%" align="center" class="td-border-grid">Emailid</td></tr><tr><td colspan="6" class="td-border-grid" height="20px"  ><table width="100%" border="0" cellspacing="0" cellpadding="0" ><tr><td align="center"><font color="#FF4F4F"><strong>No datas found to be displayed</strong></font></td></tr></table></td></tr></table>';
	$("#contactdetailsgrid").append(row);
}

function displayalcustomer()
{	
	var form = $("#submitform" );
	var selectbox = $('#customerlist');
	var numberofcustomers = customerarray.length;
	$('#detailsearchtext').focus();
	$('#detailsearchtext').val('');
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

function selectedtype(type)
{
	
	var forms = $('#colorboxeditform');
	$('#headingname').html('');
	$("#fieldtype").html('');
	$("#companyname").html('');
	if(type == 'businessname')
	{
		var sizevalue = '70';
		var maxlengthvalue = '100';
	}
	else if(type == 'address')
	{
		var sizevalue = '70';
		var maxlengthvalue = '100';	
	}
	else
	{
		var sizevalue = '24';
		var maxlengthvalue = '100';	
	}
	if($("#"+type+'display').text() == 'Not Avaliable')
		var valuedetails = "";
	else
		var valuedetails = $("#"+type+'display').text();
		
	$('#headingname').html(type.toUpperCase()+':');
	$('#companyname').html($("#businessnamedisplay").html());
	if(type == 'address')
	{
		$("#fieldtype").html('<textarea name=\'' + type + '\' class="swifttext" id=\'' + type  + '\'  cols="60" rows="3" style="resize: none;"></textarea>' );
		$('#'+type).val(valuedetails);
	}
	else
	{
		$("#fieldtype").html('<input name=\'' + type + '\' class="swifttextarea" id=\'' + type  + '\'type = "text"  value =\'' + valuedetails + '\' size=\'' + sizevalue + '\' maxlength=\'' + maxlengthvalue + '\' >' );
	}
	
	$("").colorbox({ inline:true, href:"#displaygridtab" , onLoad: function() {
    $('#cboxClose').hide()},onComplete: function(){$('#'+type).focus();
    } });
	



}

function typeselectbox()
{
	$('#form-error1').html('');
	$("#companyname").html('');
	$('#selectcompanyname').html($("#businessnamedisplay").html());
	$("#state").val($('#statecode').val());
	getdistrict('districtcodedisplay', $('#statecode').val());
	$("#district").val($('#districtcode').val());
	$("").colorbox({ inline:true, href:"#displaystategrid" , onLoad: function() {
    $('#cboxClose').hide()}});
}

function checkboxtype()
{
	$('#form-error1').html('');
	$("#companyname").html('');
	$('#checkcompanyname').html($("#businessnamedisplay").html());
	var response = $("#checkboxvalue").val().split('$#$');
	autochecknew($("#activecustomer"),response[0]);
	autochecknew($("#disablelogin"),response[1]);
	autochecknew($("#corporateorder"),response[2]);
	autochecknew($("#companyclosed"),response[3]);
	autochecknew($("#isdealer"),response[4]);
	autochecknew($("#displayinwebsite"),response[5]);
	autochecknew($("#promotionalsms"),response[6]);
	autochecknew($("#promotionalemail"),response[7]);
	$("").colorbox({ inline:true, href:"#displaychexckboxgrid" , onLoad: function() {
    $('#cboxClose').hide()}});
}

function getcontactdetails(slno,slnocount)
{
	$('#contactslno').val('');
	$('#fieldslno').val('');
	$('#form-error2').html('');
	$("#companyname").html('');
	$('#contactcompanyname').html($("#businessnamedisplay").html());
	$('#selectiontype').val($('#'+'selecttype'+slnocount).html());
	$('#name').val($('#contactperson'+slnocount).html());
	$('#phone').val($('#phonetype'+slnocount).html());
	$('#cell').val($('#celltype'+slnocount).html());
	$('#emailid').val($('#emailtype'+slnocount).html());
	$('#contactslnocount').html(slnocount);
	$('#contactslno').val(slno);
	$('#fieldslno').val(slnocount);
	$("").colorbox({ inline:true, href:"#displaycontactgrid" , onLoad: function() {
    $('#cboxClose').hide()}});
	
}

function updatedetails()
{
	
	var fieldname = ($("#headingname").html().replace(":", "" ).replace(/ /g,'')).toLowerCase();
	var error = $('#form-error1');
	if(fieldname == 'businessname')
	{
		var field = $("#"+fieldname);
		if(!field.val()) { error.html(errormessage("Enter the Business Name [Company]. ")); field.focus(); return false; }
		if(field.val()) { if(!validatebusinessname(field.val())) { error.html(errormessage('Business name contains special characters. Please use only Alpha / Numeric / space / hyphen / small brackets.')); field.focus(); return false; } }
	}
	else if(fieldname == 'place')
	{
		var field = $("#"+fieldname);
		if(!field.val()) { error.html(errormessage("Enter the Place.")); field.focus(); return false; }
	}
	else if(fieldname == 'pincode')
	{
		var field = $("#"+fieldname);
		if(!field.val()) { error.html(errormessage("Enter the PinCode.")); field.focus(); return false; }
		if(field.val()) { if(!validatepincode(field.val())) { error.html(errormessage('Enter the valid PIN Code.')); field.focus(); return false; } }	
	}
	else if(fieldname == 'stdcode')
	{
		var field = $("#"+fieldname);
		if(field.val()) { if(!validatestdcode(field.val())) { error.html(errormessage('Enter the valid STD Code.')); field.focus(); return false; } }
	}
	else if(fieldname == 'fax')
	{
		var field = $("#"+fieldname);
		if(field.val()) { if(!validatephone(field.val())) { error.html(errormessage('Enter the valid Fax Number.')); field.focus(); return false; } }
	}
	else if(fieldname == 'website')
	{
		var field = $("#"+fieldname);
		if(field.val())	{ if(!validatewebsite(field.val())) { error.html(errormessage('Enter the valid Website.')); field.focus(); return false; } }	
	}
	else if(fieldname == 'state')
	{
		var fieldname = "";
		var fieldname = 'district';
		if(!$('#state').val()) { error.html(errormessage("Select the State. ")); $('#state').focus(); return false; }
		if(!$('#district').val()) { error.html(errormessage("Select the District. ")); $('#district').focus(); return false; }
		
	}
	/*var passarray =  new Array();
	pasarray = */
		var passData = "switchtype=processupdate&fieldname="+ encodeURIComponent(fieldname) + "&editvalue=" + $("#"+fieldname).val()  + "&lastslno=" + $('#lastslno').val()+ "&dummy=" + Math.floor(Math.random()*1000782200000);//alert(passData)
		var queryString = "../ajax/custdata.php";
		$('#form-error1').html(getprocessingimage());
		ajaxcall1 = $.ajax(
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
					$('#form-error1').html('');
					var response = ajaxresponse.split('^');//alert(response)
					if(response[0] == '1')
					{
						alert(response[1]);
						if(fieldname == 'district')
						{
							$('#districtdisplay').html(response[2]);
							$('#statedisplay').html(response[3]);
						}
						else
						{
							$('#'+fieldname+'display').html(response[2]);
						}
						$().colorbox.close();
					}
					
				}
			}, 
			error: function(a,b)
			{
				$("#form-error1").html(scripterror());
			}
		});	
}


function updatecontactdetails()
{
	var slnocount = $('#fieldslno').val();
	var error = $('#form-error2');
	var field = $("#selectiontype");
	if(!field.val()) { error.html(errormessage("Select the Type. ")); field.focus(); return false; }
	var field = $("#name");
	if(field.val()) { if(!contactpersonvalidate(field.val())) { error.html(errormessage('Contact person name contains special characters. Please use only Numeric / space.')); field.focus(); return false; } }
	var field = $("#phone");
	if(field.val()) { if(!validatephone(field.val())) { error.html(errormessage('Enter the valid Phone Number.')); field.focus(); return false; } }
	var field = $("#cell");
	if(field.val()) { if(!cellvalidation(field.val())) { error.html(errormessage('Enter the valid Cell Number.')); field.focus(); return false; } }
	var field = $("#emailid");
	if(field.val()) { if(!checkemail(field.val())) { error.html(errormessage('Enter the valid Email Id.')); field.focus(); return false; } }
	var passData = "switchtype=updatecontactdetails&contactslno="+ encodeURIComponent($('#contactslno').val())+ "&lastslno=" + $('#lastslno').val() + "&selectiontype=" + $('#selectiontype').val() + "&name=" + $('#name').val()+ "&phone=" + $('#phone').val()+ "&cell=" + $('#cell').val()+ "&emailid=" + $('#emailid').val()+ "&dummy=" + Math.floor(Math.random()*1000782200000);//alert(passData)
	var queryString = "../ajax/custdata.php";
	$('#form-error2').html(getprocessingimage());
	ajaxcall2 = $.ajax(
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
				$('#form-error2').html('');
				var response = ajaxresponse.split('^');//alert(response)
				if(response[0] == '1')
				{
					alert(response[1]);
					$('#selecttype'+slnocount).html(response[2]);
					$('#contactperson'+slnocount).html(response[3]);
					$('#phonetype'+slnocount).html(response[4]);
					$('#celltype'+slnocount).html(response[5]);
					$('#emailtype'+slnocount).html(response[6]);
					$().colorbox.close();
				}
				
			}
		}, 
		error: function(a,b)
		{
			$("#form-error2").html(scripterror());
		}
	});	
}


function updatecheckboxdetails()
{
	var field = $('#activecustomer:checked').val();
	if(field != 'on') var activecustomer = 'no'; else activecustomer = 'yes';
	var field = $('#disablelogin:checked').val();
	if(field != 'on') var disablelogin = 'no'; else disablelogin = 'yes';
	var field = $('#corporateorder:checked').val();
	if(field != 'on') var corporateorder = 'no'; else corporateorder = 'yes';
	var field = $('#companyclosed:checked').val();
	if(field != 'on') var companyclosed = 'no'; else companyclosed = 'yes';
	var field = $('#isdealer:checked').val();
	if(field != 'on') var isdealer = 'no'; else isdealer = 'yes';
	var field = $('#displayinwebsite:checked').val();
	if(field != 'on') var displayinwebsite = 'no'; else displayinwebsite = 'yes';
	var field = $('#promotionalsms:checked').val();
	if(field != 'on') var promotionalsms = 'no'; else promotionalsms = 'yes';
	var field = $('#promotionalemail:checked').val();
	if(field != 'on') var promotionalemail = 'no'; else promotionalemail = 'yes';
	var passData = "switchtype=updatecheckboxdetails&activecustomer="+ encodeURIComponent(activecustomer)+ "&lastslno=" + $('#lastslno').val() + "&disablelogin=" + (disablelogin) + "&corporateorder=" + (corporateorder)+ "&companyclosed=" + (companyclosed)+ "&isdealer=" + (isdealer)+ "&displayinwebsite=" + (displayinwebsite) + "&promotionalsms=" + (promotionalsms) + "&promotionalemail=" +(promotionalemail)+ "&dummy=" + Math.floor(Math.random()*1000782200000);//alert(passData)
	var queryString = "../ajax/custdata.php";
	$('#form-error3').html(getprocessingimage());
	ajaxcall3 = $.ajax(
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
				$('#form-error3').html('');
				var response = ajaxresponse.split('^');//alert(response)
				if(response[0] == '1')
				{
					alert(response[1]);
					$("#activecustomerdisplay").html(response[2]);
					$("#disablelogindisplay").html(response[3]);
					$("#corporateorderdisplay").html(response[4]);
					$("#companycloseddisplay").html(response[5]);
					$("#isdealerdisplay").html(response[6]);
					$("#displayinwebsitedisplay").html(response[7]);
					$("#promotionalsmsdisplay").html(response[8]);;
					$("#promotionalemaildisplay").html(response[9]);
					$().colorbox.close();
				}
				
			}
		}, 
		error: function(a,b)
		{
			$("#form-error3").html(scripterror());
		}
	});	
}
