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
	queryString = "../ajax/crossproduct.php";
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
	queryString = "../ajax/crossproduct.php";
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
	
	queryString = "../ajax/crossproduct.php";
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

	queryString = "../ajax/crossproduct.php";
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
	
	queryString = "../ajax/crossproduct.php";
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
	disableformelemnts();
	var form = $('#filterform');
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

function selectfromlist()
{
	var selectbox = $("#customerlist option:selected").val();
	$('#detailsearchtext').val($("#customerlist option:selected").text());
	$('#detailsearchtext').select();
	customerdetailstoform(selectbox);
	$('#displaycustomer').hide();
	$('#hidemoredetails').show();
	crossdatagrid(selectbox,'');
	yearwisedetails(selectbox);
	$('#filterdivdisplay').hide();
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

function searchbycustomeridevent(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 13)
	{
		var input = $('#searchcustomerid').val();
		searchbycustomerid(input);
	}
}

function customerdetailsfunc()
{
	if($('#displaycustomer').is(':visible'))
	{
		$('#displaycustomer').hide();
		$('#hidemoredetails').show();
	}
	else
	{
		$('#displaycustomer').show();
		$('#hidemoredetails').hide();
	}
}

//Customer details to form
function customerdetailstoform(cusid)
{
	if(cusid != '')
	{
		$('#customerselectionprocess').html('');
		var form = $('#submitform');
		$("#submitform" )[0].reset();
		var passdata = "switchtype=customerdetailstoform&lastslno=" + encodeURIComponent(cusid) + "&dummy=" + Math.floor(Math.random()*100032680100);
		$('#form-error').html(getprocessingimage());
		var queryString = "../ajax/crossproduct.php";
		ajaxcall3 = $.ajax(
			{
				type: "POST",url: queryString, data: passdata, cache: false,dataType: "json",
				success: function(ajaxresponse,status)
				{	
					$('#form-error').html('');
					var response = ajaxresponse;
					if(ajaxresponse == 'Thinking to redirect')
					{
						window.location = "../logout.php";
						return false;
					}
					else if(response['errorcode'] == '1')
					{
						$('#displaycustomerid').html(response['customerid']);
						$('#displaycompanyname').html('<strong>'+response['companyname']+'</strong>');
						$('#displaycompanynamehidden').val(response['companyname']);
						$('#displaycontactperson').html(response['contactvalues']);
						$('#displayaddress').html(response['address']);
						$('#displayphone').html(response['phone']);
						$('#displaycell').html(response['cell']);
						$('#displayemail').html(response['emailidplit']);
						$('#displayregion').html(response['region']);
						$('#displaybranch').html(response['branch']);
						if(response['businesstype'] == '')
							$('#displaytypeofcategory').html('Not Available');
						else
							$('#displaytypeofcategory').html(response['businesstype']);
						if(response['customertype'] == '')
							$('#displaytypeofcustomer').html('Not Available');
						else
							$('#displaytypeofcustomer').html(response['customertype']);
						$('#displaydealer').html(response['dealername']);
					} 
				}, 
				error: function(a,b)
				{
					$("#form-error").html(scripterror());
				}
			});	
	}
}


function yearwisedetails(cusid)
{
	var form = $('#submitform');
	var passdata = "switchtype=yearwisedetails&lastslno="+ encodeURIComponent(cusid);//alert(passData)
	var queryString = "../ajax/crossproduct.php";
	$("#form-error").html(getprocessingimage());
	ajaxcall4 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			$("#form-error").html('');
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
					$("#displaydetails").html(response['grid']);
				}
				else
					$("#form-error").html(scripterror());
			}
		}, 
		error: function(a,b)
		{
			$("#form-error").html(scripterror());
		}
	});	
}

function formsubmit(command)
{
	var passData = "";
	var form = $("#submitform" );
	var error = $("#form-error" );
	var field = $("#status");
	if(!field.val()) { error.html(errormessage("Select the Status")); field.focus(); return false; }

	var field = $("#remarks" );
	if(!field.val()) { error.html(errormessage("Enter the Remarls. ")); field.focus(); return false; }
	var field = $("#dealer");
	if(!field.val()) { error.html(errormessage("Select the proper dealer name from the list.")); field.focus(); return false; }
	
	passdata =  "switchtype=save&remarks=" + encodeURIComponent($("#remarks").val()) + "&dealer=" + encodeURIComponent($("#dealer").val()) + "&entereddate=" + encodeURIComponent($("#entereddate").html()) + "&followupdate=" + encodeURIComponent($("#DPC_followupdate").val())+ "&lastslno=" + encodeURIComponent($("#lastslno").val()) + "&customerid=" + encodeURIComponent($("#customerlist").val()) + "&productgroup=" + encodeURIComponent($("#productgroup").val()) + "&status=" + encodeURIComponent($("#status").val()) + "&dummy=" + Math.floor(Math.random()*100000000);
	//alert(passData)
		queryString = '../ajax/crossproduct.php';
		var ajaxcall0 = createajax();
		error.html(getprocessingimage());
		ajaxobjext14 = $.ajax(
		{
			type: "POST",url: queryString, data: passdata, cache: false,dataType: "json",
			success: function(ajaxresponse,status)
			{	
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location= '../logout.php';
					return false;
				}
				else
				{
					var response = ajaxresponse;
					if(response['errorcode'] == '1')
					{
						error.html(successmessage(response['errormsg']));
						crossdatagrid($("#customerlist").val(),'');
						
						newentry();
					}
					else if(response['errorcode'] == '3')
					{
						error.html(errormessage(response['errormsg']));
					}
				}
			}, 
			error: function(a,b)
			{
				error.html(scripterror());
			}
		});	
}


function crossdatagrid(id,startlimit)
{
	var form = $('#submitform');
	$('#cusslno').val(id);
	$('#filterdiv').show();
	displayDiv2('filterdiv','toggleimg2')
	var passdata = "switchtype=generategrid&lastslno="+ encodeURIComponent(id) + "&startlimit=" + encodeURIComponent(startlimit);
	var queryString = "../ajax/crossproduct.php";
	ajaxcall41 = createajax();
	$("#tabgroupgridc1_1").html(getprocessingimage());
	$("#tabgroupgridc1link").html('');
	$("#tabgroupgridwb1").html('');
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
					$("#tabgroupgridc1_1").html('');
					if(response['errorcode'] == 1)
					{
						gridtabcus6('1','tabgroupgrid','spp');
						$("#tabgroupgridc1_1").html(response['grid']);
						$("#tabgroupgridwb1").html("Total Count :  " + response['fetchresultcount']);
						$("#tabgroupgridc1link").html(response['linkgrid']);
					}
				}
			}, 
			error: function(a,b)
			{
				$("#tabgroupgridc1_1").html(scripterror());
			}
		});	
}

//Function for "show more records" link - to get registration records
function crossmoredatagrid(id,startlimit,slno,showtype)
{
	var form = $("#submitform" );
	$('#cusslno').val(id);	
	var passdata = "switchtype=generategrid&lastslno="+ encodeURIComponent(id) + "&startlimit=" + startlimit+ "&slno=" + slno + "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);
	var queryString = "../ajax/crossproduct.php";
	$("#tabgroupgridc1link").html(getprocessingimage());
	ajaxcall5 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
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
					$("#regresultgrid").html($("#tabgroupgridc1_1").html());
					$("#tabgroupgridc1_1").html($("#regresultgrid").html().replace(/\<\/table\>/gi,'')+ response['grid']);
					$("#tabgroupgridc1link").html(response['fetchresultcount']);
					$("#tabgroupgridwb1").html("Total Count :  " + response['linkgrid']);
					
					gridtabcus6(1,'tabgroupgrid','spp');
				}
				else
				{
					$("#tabgroupgridc1_1").html("No datas found to be displayed...");
				}
			}
		}, 
		error: function(a,b)
		{
			$("#tabgroupgridc1_1").html(scripterror());
		}
	});	
}

function gridtabcus6(activetab,groupname,tabdescription)
{
 var form = $("#submitform" );
 var totaltabs = 7;
 var activetabclass = "grid-active-tabclass6";
 var tabheadclass = "grid-tabclass6";
 for(var i=1 ; i <= totaltabs ; i++)
	{
		var tabhead = groupname + 'h' + i;
		if(i == activetab)
		{
			$('#'+tabhead).removeClass(tabheadclass);
			$('#'+tabhead).addClass(activetabclass);
			$('#productgroup').val(tabdescription);
			
			 if(tabdescription == 'sto')
				 $('#imagedisplay').html('<img src="../images/tabhead-saral-taxoffice.gif" border="0" align="absmiddle" >');
			else if(tabdescription == 'spp')
				 $('#imagedisplay').html('<img src="../images/tabhead-saral-paypack.gif" border="0" align="absmiddle" >');
			else if(tabdescription == 'tds')
				 $('#imagedisplay').html('<img src="../images/tabhead-saral-tds.gif" border="0" align="absmiddle" >');
			else if(tabdescription == 'svh')
				 $('#imagedisplay').html('<img src="../images/tabhead-saral-vat100.gif" border="0" align="absmiddle" >');
			else if(tabdescription == 'svi')
				 $('#imagedisplay').html('<img src="../images/tabhead-saral-vat.gif" border="0" align="absmiddle" >');
			else if(tabdescription == 'sac')
				 $('#imagedisplay').html('<img src="../images/tabhead-saral-accounts.gif" border="0" align="absmiddle" >');
			else if(tabdescription == 'xbrl')
				 $('#imagedisplay').html('<img src="../images/tabhead-saral-xbrl.gif" border="0" align="absmiddle" >');
			 
		}
		else 
		{
			$('#'+tabhead).removeClass(activetabclass);
			$('#'+tabhead).addClass(tabheadclass);
		
		}
	} 
}

function gridtoform(slno)
{
	if(slno != '')
	{
		var form = $('#submitform');
		var error = $('#form-error');
		error.html('');
		$('#gridslno').val(slno);
		var passdata = "switchtype=gridtoform&slno=" + encodeURIComponent(slno) + "&dummy=" + Math.floor(Math.random()*100032680100);
		$('#form-error').html(getprocessingimage());
		var queryString = "../ajax/crossproduct.php";
		ajaxcall31 = $.ajax(
		{
			type: "POST",url: queryString, data: passdata, cache: false,dataType: "json",
			success: function(ajaxresponse,status)
			{	
				$('#form-error').html('');
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				var response = ajaxresponse;//alert(response)
				if(response['errorcode'] == '1')
				{
					$('#remarks').val(response['remarks']);
					$('#entereddate').val(response['entereddate']);
					$('#DPC_followupdate').val(response['followupdate']);
					$('#displayenteredby').html(response['enteredby']);
					$('#dealer').val(response['todealer']);
					$('#lastslno').val(response['slno']);
					$('#status').val(response['status']);
					if(response['productgroup'] == 'spp')
						tabgridvalue = 1;
					else if(response['productgroup'] == 'sto')
						tabgridvalue = 2;
					else if(response['productgroup'] == 'tds')
						tabgridvalue = 3;
					else if(response['productgroup'] == 'svh')
						tabgridvalue = 4;
					else if(response['productgroup'] == 'svi')
						tabgridvalue = 5;
					else if(response['productgroup'] == 'sac')
						tabgridvalue = 6;
					else if(response['productgroup'] == 'xbrl')
						tabgridvalue = 7;
					gridtabcus6(tabgridvalue,'tabgroupgrid',response['productgroup']);

				}
				else
					error.innerHTML(scripterror());
			}, 
			error: function(a,b)
			{
				error.html(scripterror());
			}
		});	
	}
}


function newentry()
{
	var form = $('#submitform');
	$('#submitform')[0].reset();
	$('#lastslno').val('');
	$('#gridslno').val('');
	$('#cusslno').val('');
	gridtabcus6(1,'tabgroupgrid','spp');
	$('#dealer').val('');
	$('#status').val('');
	$('#remarks').val('');
	$('#filterdiv').show();
	$('#displaydetails').show();
	displayDiv2('filterdiv','toggleimg2')
	displayDiv2('displaydetails','toggleimg1')
}


function updatestatus()
{ 
	var form = $('#submitform'); 
	var cusid = $('#gridslno').val();
	var businessname = $('#displaycompanynamehidden').val();
	var error = $('#form-error');
	var field = $('#status');
	if($('#remarks').val() == '' && $('#displayenteredby').val() == '' && $('#entereddate').val() == '')
	{
		error.html(errormessage("Please Select a Lead Below.")); return false;  
	}	
	else if(!field.val()){error.html(errormessage("Select the Status")); return false; field.focus(); }
	else if(cusid != '')
	{
		var confirmation = confirm("Do you really want to Change the Status  for " + businessname + " ??");
		if(confirmation)
		{
			var passdata  = "switchtype=updatestatus&status=" + encodeURIComponent($('#status').val()) + "&cusid=" + cusid +  "&remarks=" + encodeURIComponent($('#overallremarks').val()) + "&dummy=" + Math.floor(Math.random()*100000000);//alert(passData) 
			var queryString = "../ajax/crossproduct.php"; 
			ajaxcall15 = $.ajax(
				{
					type: "POST",url: queryString, data: passdata, cache: false,dataType: "json",
					success: function(response,status)
					{	
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
								newentry();
								crossdatagrid($("#customerlist").val(),'');
							}
							else if(response1['errorcode'] == '2')
							{
								error.html(errormessage(response1['errormsg']));
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
		error.innerHTML = '';
		return false;
	}
}

function displayfilterDiv()
{
	if($('#filterdivdisplay').is(':visible'))
	{
		$('#filterdivdisplay').hide();
	}
	else
	{
		$('#filterdivdisplay').show();
	}
}

function selectallfunc(id,value)
{
	var selectall = document.getElementById(value);
	var chkvalues = document.getElementsByName(id);
	var changestatus = (selectall.checked == true)?true:false;
	for (var i=0; i < chkvalues.length; i++)
	{
		chkvalues[i].checked = changestatus;
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

//Function to make the display as block as well as none-------------------------------------------------------------

function displayDiv2(elementid,imgname)
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


/*//Function to enable the save button--------------------------------------------------------------------------------
function enableupdate()
{
	$('#update').attr('disabled',false);
	$('#update').removeClass('swiftchoicebuttondisabled');
	$('#update').addClass('swiftchoicebutton');
}

//Function to disable the save button--------------------------------------------------------------------------------
function disableupdate()
{
	$('#update').attr('disabled',true);
	$('#update').removeClass('swiftchoicebutton');
	$('#update').addClass('swiftchoicebuttondisabled');
}*/


function searchcustomerarray()
{
	var form = $('#searchfilterform');
	var error = $('#filter-form-error'); 
	var values = validatecheckboxes();
	if(values == false)	{error.html(errormessage("Select A Product Group")); return false;	}
	var textfield = $('#searchcriteria').val();
	//var subselection = getradiovalue(form.databasefield);
	var subselection = $("input[name='databasefield']:checked").val();
	var c_value = '';
	var c_value2 = '';
	var newvalue = new Array();
	var chks = $("input[name='groupname']");
	for (var i = 0; i < chks.length; i++)
	{
		if ($(chks[i]).is(':checked'))
		{
			c_value += "'" + $(chks[i]).val() + "'" + ',';
		}
	}
	
	var list1 = c_value.substring(0,(c_value.length-1));
	
	var chksvalue = $("input[name='groupdisplay']");
	for (var i = 0; i < chksvalue.length; i++)
	{
		if ($(chksvalue[i]).is(':checked'))
		{
			c_value2 += "'" + $(chksvalue[i]).val() + "'" + ',';
		}
	}
	
	var list2 = c_value2.substring(0,(c_value2.length-1));
	
	var passdata = "switchtype=searchcustomerlist&databasefield=" + encodeURIComponent(subselection) + "&state=" + encodeURIComponent($('#state2').val())  + "&region=" +encodeURIComponent($('#region2').val())+ "&district=" +encodeURIComponent($('#district2').val()) + "&textfield=" +encodeURIComponent(textfield) +  "&list1=" +encodeURIComponent(list1)+  "&list2=" +encodeURIComponent(list2) +"&dealer2=" +encodeURIComponent($('#currentdealer2').val()) + "&branch2=" + encodeURIComponent($('#branch2').val())+"&type2=" +encodeURIComponent($('#type2').val()) + "&category2=" + encodeURIComponent($('#category2').val())+ "&dummy=" + Math.floor(Math.random()*10054300000);
	$('#customerselectionprocess').html(getprocessingimage());
	queryString = "../ajax/crossproduct.php";
	ajaxobjext14 = $.ajax(
		{
			type: "POST",url: queryString, data: passdata, cache: false,dataType: "json",
			success: function(ajaxresponse,status)
			{	
				var response = ajaxresponse;//alert(response)
				if(response == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					if(response == '')
					{
						$('#filterdivdisplay').show();
						customersearcharray = new Array();
						for( var i=0; i<response.length; i++)
						{
							customersearcharray[i] = response[i];
						}
						getcustomerlistonsearch();
						$('#customerselectionprocess').html(errormessage("Search Result"  + '<span style="padding-left: 15px;"><img src="../images/close-button.jpg" width="15" height="15" align="absmiddle" style="cursor: pointer; padding-bottom:2px" onclick="displayalcustomer()"></span> '));
						$('#totalcount').html('0');
						error.html(errormessage('No datas found to be displayed.'));
					}
					else 
					{
						$('#filterdivdisplay').hide();
						customersearcharray = new Array();
						for( var i=0; i<response.length; i++)
						{
							customersearcharray[i] = response[i];
						}
						getcustomerlistonsearch();
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



function getcustomerlistonsearch()
{	
	var form = $("#searchfilterform" );
	var selectbox = $('#customerlist');
	var numberofcustomers = customersearcharray.length;
	$('#detailsearchtext').focus();
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


function displayalcustomer()
{	
	var form = $('#submitform');
	var selectbox = $('#customerlist');
	$('#customerselectionprocess').html(successsearchmessage('All Customer...'));
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
	$('#totalcount').html(customerarray.length);
	
}


function validatecheckboxes()
{
	var chksvalue = document.getElementsByName('groupname');
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