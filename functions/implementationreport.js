// var branchRequest = null;
// var saleRequest = null;
customerarray = new Array();

//Function to display All records ------------------------------------------
function getimpalldatadetails(type)
{
	var form = $('#submitform');
	if(type  == 'all')
	{
		var passdata = "switchtype=implalldata&dummy=" + Math.floor(Math.random()*100000000) + "&typeselect=" +encodeURIComponent(type);//alert(passdata)
	}
	else if(type  == 'search')
	{
		var c_value = '';
		var newvalue = new Array();
		var chks = $("input[name='summarize[]']");
		var values = validatestatuscheckboxes();
		if(values == false)	{$('#filter-form-error1').html(errormessage("Select A Status")); return false;	}

		for (var i = 0; i < chks.length; i++)
		{
			if ($(chks[i]).is(':checked'))
			{
				c_value += $(chks[i]).val()+ ',';
			}
		}
		var statuslist = c_value.substring(0,(c_value.length-1));
		var passdata = "switchtype=implalldata" +  "&dealer=" +encodeURIComponent($("#currentdealer").val()) +"&type=" +encodeURIComponent($("#type").val()) + "&category=" + encodeURIComponent($("#category").val())+ "&implementer=" + encodeURIComponent($("#implementer").val()) +  "&statuslist=" +encodeURIComponent(statuslist) + "&dummy=" + Math.floor(Math.random()*10054300000) + "&typeselect=" +encodeURIComponent(type);//alert(passdata)

	}
	var queryString = "../ajax/implementationreport.php";
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
				$('#filterdiv').hide()
				var response = ajaxresponse.split('^');
				$("#form-error").html('');
				if(response[0] == 1)
				{
					$('#alldata_total').html(response[1]);
					$('#alldata_raw').html(response[2]);
					//$('#alldata_activate').html(response[3]);
					if(type  == 'all')
					{
						document.getElementById("alldata_total").onclick = new Function('detailedgrid("totalalldata","all")');
						//document.getElementById("alldata_raw").onclick = new Function('detailedgrid("rawdata","all")');
						//document.getElementById("alldata_activate").onclick = new Function('detailedgrid("activatedata","all")');
					}
					else if(type  == 'search')
					{
						document.getElementById("alldata_total").onclick = new Function('detailedgrid("totalalldata","search")');
						//document.getElementById("alldata_raw").onclick = new Function('detailedgrid("rawdata","search")');
						//document.getElementById("alldata_activate").onclick = new Function('detailedgrid("activatedata","search")');
					}
					
					//alert(response[7]);
					$('#implementation_total').html(response[7]);
					$('#implementation_sale').html(response[8]);
					$('#implementation_withimp').html(response[9]);
					if(type  == 'all')
					{
						document.getElementById("implementation_total").onclick = new Function('detailedimplduegrid("totalimpduedata","all")');
						document.getElementById("implementation_sale").onclick = new Function('detailedimplduegrid("implsale","all")');
						document.getElementById("implementation_withimp").onclick = new Function('detailedimplduegrid("implimplementation","all")');
					}
					else if(type  == 'search')
					{
						document.getElementById("implementation_total").onclick = new Function('detailedimplduegrid("totalimpduedata","search")');
						document.getElementById("implementation_sale").onclick = new Function('detailedimplduegrid("implsale","search")');
						document.getElementById("implementation_withimp").onclick = new Function('detailedimplduegrid("implimplementation","search")');
					}
					
					
					// $('#region_total').html(response[10]);
					// $('#region_bkg').html(response[11]);
					//  $('#region_name').html(response[12]);
					// $('#region_count').html(response[13]);
					if(type  == 'all')
					{
						// document.getElementById("region_total").onclick = new Function('detailedregionactivegrid("totalregduedata","all")');
						// document.getElementById("region_bkg").onclick = new Function('detailedregionactivegrid("totalregbkgdata","all")');
						 //document.getElementById("region_bkm").onclick = new Function('detailedregionactivegrid("totalregbkmdata","all")');
						 //document.getElementById("region_count").onclick = new Function('detailedregionactivegrid(\'' + response[12] + '\',"all")');
					}
					else if(type  == 'search')
					{
						// document.getElementById("region_total").onclick = new Function('detailedregionactivegrid("totalregduedata","search")');
						// document.getElementById("region_bkg").onclick = new Function('detailedregionactivegrid("totalregbkgdata","search")');
						// document.getElementById("region_bkm").onclick = new Function('detailedregionactivegrid("totalregbkmdata","search")');
						//document.getElementById("region_count").onclick = new Function("detailedregionactivegrid(\'' + response[12] + '\','search')");
					}
					
					$('#status_branch').html(response[14]);
					$('#status_coordinatorapproval').html(response[15]);
					$('#status_coordinatorreject').html(response[16]);
					$('#status_pending').html(response[17]);
					$('#status_assigned').html(response[18]);
					$('#status_progess').html(response[19]);
					$('#status_completed').html(response[20]);
					$('#status_totaldue').html(response[21]);
					$('#status_branchreject').html(response[22]);
					if(type  == 'all')
					{
						document.getElementById("status_branch").onclick = new Function('getdisplaystatusgrid("status1","all")');
						document.getElementById("status_coordinatorapproval").onclick = new Function('getdisplaystatusgrid("status2","all")');
						document.getElementById("status_coordinatorreject").onclick = new Function('getdisplaystatusgrid("status3","all")');
						document.getElementById("status_pending").onclick = new Function('getdisplaystatusgrid("status4","all")');
						document.getElementById("status_assigned").onclick = new Function('getdisplaystatusgrid("status5","all")');
						document.getElementById("status_progess").onclick = new Function('getdisplaystatusgrid("status6","all")');
						document.getElementById("status_completed").onclick = new Function('getdisplaystatusgrid("status7","all")');
						document.getElementById("status_totaldue").onclick = new Function('getdisplaystatusgrid("statustotal","all")');
						document.getElementById("status_branchreject").onclick = new Function('getdisplaystatusgrid("status8","all")');
					}
					else if(type  == 'search')
					{
						document.getElementById("status_branch").onclick = new Function('getdisplaystatusgrid("status1","search")');
						document.getElementById("status_coordinatorapproval").onclick = new Function('getdisplaystatusgrid("status2","search")');
						document.getElementById("status_coordinatorreject").onclick = new Function('getdisplaystatusgrid("status3","search")');
						document.getElementById("status_pending").onclick = new Function('getdisplaystatusgrid("status4","search")');
						document.getElementById("status_assigned").onclick = new Function('getdisplaystatusgrid("status5","search")');

						document.getElementById("status_progess").onclick = new Function('getdisplaystatusgrid("status6","search")');
						document.getElementById("status_completed").onclick = new Function('getdisplaystatusgrid("status7","search")');
						document.getElementById("status_totaldue").onclick = new Function('getdisplaystatusgrid("statustotal","search")');
						document.getElementById("status_branchreject").onclick = new Function('getdisplaystatusgrid("status8","search")');
					}
					displaybranch(response[23]);
					displaybranchsaleswise(response[23]);
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

//Function to display Branch - Implementer Wise Records ------------------------------------------
function displaybranch(branchslno)
{
	var form = $('#submitform');
	var passdata = "switchtype=implbranchimpwise&branchslno="+encodeURIComponent(branchslno)+"&dummy=" + Math.floor(Math.random()*100000000);//alert(passdata)
	var queryString = "../ajax/implementationreport.php";//alert(passdata)
	$('#tabdisplayerror').html(getprocessingimage());
	// if(branchRequest != null)
	// {
	// 	branchRequest.abort();
	// 	$('#form-error').html("");
	// }
	$.ajax(
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
				$("#tabdisplayerror").html('');
				if(response[0] == 1)
				{
					$('#tabgriddisplayimp').html(response[1]);
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
//Function to display Branch - Sales Wise Records ------------------------------------------
function displaybranchsaleswise(branchslno)
{
	var form = $('#submitform');
	var passdata = "switchtype=implbranchsalewise&branchslno="+encodeURIComponent(branchslno)+"&dummy=" + Math.floor(Math.random()*100000000);//alert(passdata)
	var queryString = "../ajax/implementationreport.php";//alert(passdata)
	$('#tabdisplaymsg').html(getprocessingimage());
	// if(saleRequest != null)
	// {
	// 	saleRequest.abort();
	// 	$('#form-error').html("");
	// }
	$.ajax(
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
				$("#tabdisplaymsg").html('');
				if(response[0] == 1)
				{
					$('#tabgriddisplaysale').html(response[1]);
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

//Function to All Data display Popup grid ------------------------------------------
function detailedgrid(type,typeselect)
{
	var form = $('#submitform');
	if(typeselect  == 'all')
	{
		var passdata = "switchtype=impdetailedgrid&typelist="+encodeURIComponent(type)+"&typeselect="+encodeURIComponent(typeselect)+"&dummy=" + Math.floor(Math.random()*100000000);//alert(passdata)
	}
	else if(typeselect  == 'search')
	{
		var values = validatestatuscheckboxes();
		if(values == false)	{$('#filter-form-error1').html(errormessage("Select A Status")); return false;	}

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
		var passdata = "switchtype=impdetailedgrid" +  "&dealer=" +encodeURIComponent($("#currentdealer").val()) +"&type=" +encodeURIComponent($("#type").val()) + "&category=" + encodeURIComponent($("#category").val())+ "&implementer=" + encodeURIComponent($("#implementer").val()) +  "&statuslist=" +encodeURIComponent(statuslist) + "&dummy=" + Math.floor(Math.random()*10054300000) + "&typeselect=" +encodeURIComponent(typeselect) +"&typelist="+encodeURIComponent(type);//alert(passdata)

	}	
	var queryString = "../ajax/implementationreport.php";//alert(passdata)
	$('#form-error').html(getprocessingimage());
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
				$("#form-error").html('');
				if(response[0] == 1)
				{
					$('#displaygriddata').html(response[1]);
					$('#tabdescription').html(response[2]);
					$("").colorbox({ inline:true, href:"#colorboxdatagrid", onLoad: function() { $('#cboxClose').hide()}});
				}
				
			}
		}, 
		error: function(a,b)
		{
			$("#form-error").html(scripterror());
		}
	});	
}

//Function to Implementation Due display Popup grid ------------------------------------------
function detailedimplduegrid(type,typeselect)
{
	var form = $('#submitform');
	if(typeselect  == 'all')
	{
		var passdata = "switchtype=impdetailedimplduegrid&typelist="+encodeURIComponent(type)+"&typeselect="+encodeURIComponent(typeselect)+"&dummy=" + Math.floor(Math.random()*100000000);//alert(passdata)
	}
	else if(typeselect  == 'search')
	{
		var values = validatestatuscheckboxes();
		if(values == false)	{$('#filter-form-error1').html(errormessage("Select A Status")); return false;	}

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
		var passdata = "switchtype=impdetailedimplduegrid" +  "&dealer=" +encodeURIComponent($("#currentdealer").val()) +"&type=" +encodeURIComponent($("#type").val()) + "&category=" + encodeURIComponent($("#category").val())+ "&implementer=" + encodeURIComponent($("#implementer").val()) +  "&statuslist=" +encodeURIComponent(statuslist) + "&dummy=" + Math.floor(Math.random()*10054300000) + "&typeselect=" +encodeURIComponent(typeselect) +"&typelist="+encodeURIComponent(type);//alert(passdata)

	}	
	//var passdata = "switchtype=impdetailedimplduegrid&type="+encodeURIComponent(type)+"&dummy=" + Math.floor(Math.random()*100000000);//alert(passdata)
	var queryString = "../ajax/implementationreport.php";//alert(passdata)
	$('#form-error').html(getprocessingimage());
	ajaxcall5 = $.ajax(
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
				$("#form-error").html('');
				if(response[0] == 1)
				{
					$('#displaygriddata').html(response[1]);
					$('#tabdescription').html(response[2]);
					$("").colorbox({ inline:true, href:"#colorboxdatagrid", onLoad: function() { $('#cboxClose').hide()}});
				}
				
			}
		}, 
		error: function(a,b)
		{
			$("#form-error").html(scripterror());
		}
	});	
}

//Function to Status wise display Popup grid ------------------------------------------
function getdisplaystatusgrid(type,typeselect)
{
	var form = $('#submitform');
	if(typeselect  == 'all')
	{
		var passdata = "switchtype=displaystatusgrid&typelist="+encodeURIComponent(type)+"&typeselect="+encodeURIComponent(typeselect)+"&dummy=" + Math.floor(Math.random()*100000000);//alert(passdata)
	}
	else if(typeselect  == 'search')
	{
		var values = validatestatuscheckboxes();
		if(values == false)	{$('#filter-form-error1').html(errormessage("Select A Status")); return false;	}

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
		var passdata = "switchtype=displaystatusgrid" +  "&dealer=" +encodeURIComponent($("#currentdealer").val()) +"&type=" +encodeURIComponent($("#type").val()) + "&category=" + encodeURIComponent($("#category").val())+ "&implementer=" + encodeURIComponent($("#implementer").val()) +  "&statuslist=" +encodeURIComponent(statuslist) + "&dummy=" + Math.floor(Math.random()*10054300000) + "&typeselect=" +encodeURIComponent(typeselect) +"&typelist="+encodeURIComponent(type);//alert(passdata)

	}	
	//var passdata = "switchtype=displaystatusgrid&type="+encodeURIComponent(type)+"&dummy=" + Math.floor(Math.random()*100000000);//alert(passdata)
	var queryString = "../ajax/implementationreport.php";//alert(passdata)
	$('#form-error').html(getprocessingimage());
	ajaxcall6 = $.ajax(
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
				$("#form-error").html('');
				if(response[0] == 1)
				{
					$('#displaygriddata').html(response[1]);
					$('#tabdescription').html(response[2]);
					$("").colorbox({ inline:true, href:"#colorboxdatagrid", onLoad: function() { $('#cboxClose').hide()}});
				}
				
			}
		}, 
		error: function(a,b)
		{
			$("#form-error").html(scripterror());
		}
	});	
}

//Function to Branch - Implementer Wise display Popup grid ------------------------------------------
function displaygridofimpl(slno,type)
{
	if(type == 'totalofimpbranch')
	{
		var c_value = '';
		var chks = slno.split('#') 
		for (var i = 0; i < chks.length; i++)
		{
				c_value += "'" + chks[i] + "'" + ',';
		}
		var slnolist = c_value.substring(0,(c_value.length-1));
	}
	else
	{
		var slnolist = slno;
	}
	
	var form = $('#submitform');
	var passdata = "switchtype=displaygridofimpl&type="+encodeURIComponent(type)+"&slno="+encodeURIComponent(slnolist)+"&dummy=" + Math.floor(Math.random()*100000000);//alert(passdata)
	var queryString = "../ajax/implementationreport.php";//alert(passdata)
	$('#form-error').html(getprocessingimage());
	ajaxcall8 = $.ajax(
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
				$("#form-error").html('');
				if(response[0] == 1)
				{
					$('#displaygriddata').html(response[1]);
					$('#tabdescription').html(response[2]);
					$("").colorbox({ inline:true, href:"#colorboxdatagrid", onLoad: function() { $('#cboxClose').hide()}});
				}
				
			}
		}, 
		error: function(a,b)
		{
			$("#form-error").html(scripterror());
		}
	});	
}

//Function to Branch - Sales Wise display Popup grid ------------------------------------------
function displaygridofsale(slno,type)
{
	if(type == 'totalofbranchsalewise')
	{
		var c_value = '';
		var chks = slno.split('#') 
		for (var i = 0; i < chks.length; i++)
		{
				c_value += "'" + chks[i] + "'" + ',';
		}
		var slnolist = c_value.substring(0,(c_value.length-1));
	}
	else
	{
		var slnolist = slno;
	}
	
	var form = $('#submitform');
	var passdata = "switchtype=displaygridofsalewise&type="+encodeURIComponent(type)+"&slno="+encodeURIComponent(slnolist)+"&dummy=" + Math.floor(Math.random()*100000000);//alert(passdata)
	var queryString = "../ajax/implementationreport.php";//alert(passdata)
	$('#form-error').html(getprocessingimage());
	ajaxcall8 = $.ajax(
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
				$('#tabdescription').html(response[2]);
				$("#form-error").html('');
				if(response[0] == 1)
				{
					$('#displaygriddata').html(response[1]);
					$('#tabdescription').html(response[2]);
					$("").colorbox({ inline:true, href:"#colorboxdatagrid", onLoad: function() { $('#cboxClose').hide()}});
				}
				
			}
		}, 
		error: function(a,b)
		{
			$("#form-error").html(scripterror());
		}
	});	
}

function refreshcustomerarray()
{
	var passData = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000);//alert(passData)
	$("#customerselectionprocess").html(getprocessingimage());
	queryString = "../ajax/implementationreport.php";
	ajaxcall52 = $.ajax(
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
				
				for( var i=0; i<response.length; i++)
				{
					customerarray[i] = response[i]; 
				}
				getcustomerlist1();
				$("#customerselectionprocess").html('');
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
	customerdetailstoform(selectbox);	
}

function customerdetailstoform(cusid,impslno="")
{
	//alert(impslno);
	var form = $('#submitform'); 
	var passData = '';
	passData = "switchtype=implementation&cusid=" + cusid + "&impslno=" + impslno + "&dummy=" + Math.floor(Math.random()*100000000);
	var queryString = "../ajax/implementationreport.php";
	$("#customerselectionprocess").html(getprocessingimage());
	ajaxcall523 = $.ajax(
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
				var response = ajaxresponse.split('^');//alert(response[29])
				$("#customerselectionprocess").html('');
				if(response[0] == 1)
				{
					$('#displaydiv2').show();
					$('#displaydiv1').hide();
					
					$('#lastslno').val(response[1]);
					implemenationstatus(response[1]);
					generatecustomization();
					generaterafgrid();
					generateinvoicegrid(response[29],response[1]);
					$('#invoiceno').html(response[3]);
					$('#datedisplay').html(response[4]);
					var filename5 = response[31].split('/');
					$('#pofile').html( '<a onclick = "viewfilepath(\'' + response[31] + '\',\'5\')"  style="text-decoration:none; cursor:pointer">' + '<img src="../images/imax_zip_icon.gif" />' +' '+filename5[5] + '</a>');
					$('#noofcompanydisplay').html(response[5]);
					$('#noofmonthdisplay').html(response[7]);
					$('#processmonthdisplay').html(response[6]);
					if(response[8] == 'NO')
					{
						$('#shippmentdisplaydiv1').hide();
					}
					else
					{
						$('#shippmentdisplaydiv1').show();
						$('#shippmentdisplaydiv3').hide();
						$('#shipinvoicedisplay').html(response[9]);
					}
					if(response[10] == '')
					{
						$('#vendordisplay').html('Not Available');
						$('#aiffilename').html('');
						$('#aifdatedisplay').html('Not Available');
						$('#aifcreatedby').html('Not Available');

					}
					else
					{
						var filename3 = response[11].split('/');
						$('#aiffilename').html( '<a onclick = "viewfilepath(\'' + response[11] + '\',\'1\')"  style="text-decoration:none; cursor:pointer">' + '<img src="../images/imax_zip_icon.gif" />' +' '+filename3[5] + '</a>');
						$('#aifdatedisplay').html(response[12]);
						$('#aifcreatedby').html(response[13]);
						$('#vendordisplay').html(response[10]);
					}
					if(response[14] == 'NO')
					{
						$('#custstatusdisplay').html('Not Applicable');
						$('#custremarksdisplay').html('Not Available');
						$('#custgriddisplay').html('Not Available');
					}
					else
					{
						$('#custstatusdisplay').html(response[16]);
						$('#custremarksdisplay').html(response[15]);
						//$('#custgriddisplay').html('');
					}
					if(response[18] == 'NO')
					{
						$('#webimplementationdisplaydiv2').show();
						$('#webimplementationdisplaydiv1').hide();
					}
					else
					{
						$('#webimplementationdisplaydiv2').hide();
						$('#webimplementationdisplaydiv1').show();
						$('#webimplementationdisplay').html(response[19]);
					}
					$('#visitgriddisplay').html(response[20]);
					$('#visittotal').html(response[21]);
					$('#statuvisitdisplay').html(response[22]);
					$('#addongriddisplay').html(response[23]);
					if(response[24] == '')
					{
						$('#raffilename').html('');
						$('#rafdatedisplay').html('Not Available');
						$('#rafcreatedby').html('Not Available');

					}
					else
					{
						var filename4 = response[24].split('/');
						$('#raffilename').html( '<a onclick = "viewfilepath(\'' + response[24] + '\',\'2\')"  style="text-decoration:none; cursor:pointer">' + '<img src="../images/imax_pdf_icon.gif" />' +' '+filename4[5] + '</a>');
						$('#rafdatedisplay').html(response[25]);
						$('#rafcreatedby').html(response[26]);
					}
					if(response[27] == 'NO')
					{
						$('#shippmentdisplaydiv2').hide();
					}
					else
					{
						$('#shippmentdisplaydiv2').show();
						$('#shippmentdisplaydiv3').hide();
						$('#shipmanualdisplay').html(response[28]);
					}
					if(response[27] == 'NO' && response[8] == 'NO')
					{
						$('#shippmentdisplaydiv1').hide();
						$('#shippmentdisplaydiv2').hide();
						$('#shippmentdisplaydiv3').show();
					}
				}
			}
		}, 
		error: function(a,b)
		{
			$("#customerselectionprocess").html(scripterror());
		}
	});	
}

function implemenationstatus(lastslno)
{
	var form = $('#submitform');
	var passData =  "switchtype=implemenationstatus&lastslno=" + encodeURIComponent(lastslno) + "&dummy=" + Math.floor(Math.random()*100000000);//alert(passData)
	queryString = '../ajax/implementationreport.php';
	ajaxcall191 = $.ajax(
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
				var response = ajaxresponse.split('^');//alert(response)
				if(response[0] == 1)
				{
					if(response[1] == 'no' && response[2] == 'no'  && response[9] == 'no' && response[3] == 'no' && response[4] == 'pending')
					{
						$("#implementationstatus").html('Awaiting Branch Head Approval.');
						$("#implementationremarks").html('Your Implementation activity has been submitted in the system. It is awaiting the approval from respective head. This will be executed shortly.');
						$('#iccattachdisplay').hide();
						
					}
					if(response[1] == 'no' && response[9] == 'yes'  && response[4] == 'pending')
					{
						$("#implementationstatus").html('Fowarded back to Sales Person.');
						$("#implementationremarks").html('As there were few clarifications needed, your implementation activity has been forwarded back to respective Sales person.');
						$('#iccattachdisplay').hide();
						
					}
					else if(response[1] == 'yes'  && response[2] == 'no' && response[3] == 'no' && response[4] == 'pending')
					{
						$("#implementationstatus").html('Awaiting Co-ordinator Approval.');
						$("#implementationremarks").html('Your Implementation activity has been approved by respective head and now is with Coordinator. It wil be shortly reveiwed and processed further.');
						$('#iccattachdisplay').hide();
					}
					else if(response[1] == 'no' && response[2] == 'yes' && response[3] == 'no' && response[4] == 'pending')
					{
						$("#implementationstatus").html('Fowarded back to Branch Head.');
						$("#implementationremarks").html('As there were few clarifications needed, your implementation activity has been forwarded back to respective head. It shall be processed soon.');
						$('#iccattachdisplay').hide();
					}
					else if(response[1] == 'yes' && response[2] == 'no'  && response[3] == 'yes' && response[4] == 'pending' )
					{
						$("#implementationstatus").html('Implementation, Yet to be Assigned.');
						$("#implementationremarks").html('Your Implementation activity has been approved with all the levels. It will soon be assigned with Implementer and respective visits be scheduled.');
						$('#iccattachdisplay').hide();
					}
					else if(response[1] == 'yes' && response[2] == 'no'  && response[3] == 'yes' && response[4] == 'assigned' )
					{
						$("#implementationstatus").html('Assigned For Implementation.');
						$("#implementationremarks").html('You have been assigned with Implementer  <font color="#178BFF"><strong> '+ response[5].toUpperCase()+' </strong></font>. The visits scheduled shall be displayed here for your information / action.');
						$('#iccattachdisplay').hide();
					}
					else if(response[1] == 'yes' && response[2] == 'no'  && response[3] == 'yes' && response[4] == 'progess' )
					{
						$("#implementationstatus").html('Implementation in progess.');
						$("#implementationremarks").html('Visits are under progress for your Implementation. Our implmeneter has started his visits. The status remains the same until we receive "Implementation Completion Certificate"');
						$('#iccattachdisplay').hide();
					}
					else if(response[1] == 'yes' && response[2] == 'no'  && response[3] == 'yes' && response[4] == 'completed' )
					{
						$("#implementationstatus").html('Implementation Completed.');
						$("#implementationremarks").html('Your Implementation has been successfully completed. Please click here to view the "Implementation Completion Certificate".');
						$('#iccattachdisplay').show();
						if(response[6] == '')
						{
							$('#iccattachname').html('');
							$('#iccattachdatedisplay').html('Not Available');
							$('#iccattachcreatedby').html('Not Available');
	
						}
						else
						{
							var filename4 = response[6].split('/');
							$('#iccattachname').html( '<a onclick = "viewfilepath(\'' + response[6] + '\',\'3\')"  style="text-decoration:none; cursor:pointer">' + '<img src="../images/imax_zip_icon.gif" />' +' '+filename4[5] + '</a>');
							$('#iccattachdatedisplay').html(response[7]);
							$('#iccattachcreatedby').html(response[8]);
						}
						
					}
				}
				
			}, 
			error: function(a,b)
			{
				$('#customerselectionprocess').html(scripterror());
			}
		});	

	
}


function viewfilepath(filepath,filenumber)
{
	if(filepath != '')
		$('#'+'filepath'+filenumber).val(filepath);
		
	var form = $('#submitform');	
	$('#submitform').attr("action", "../ajax/imp-downloadfile.php?id="+filenumber+"") ;
	//$('#submitform').attr( 'target', '_blank' );
	$('#submitform').submit();
}

//Function to view the bill in pdf format----------------------------------------------------------------
function viewinvoice(slno)
{
	if(slno != '')
		$('#implonlineslno').val(slno);
		
	var form = $('#submitform');	
	if($('#implonlineslno').val() == '')
	{
		$('#form-error').html(errormessage('Please select a Invoice.')); return false;
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
		$('#implonlineslno').val(slno);
		
	var form = $('#submitform');	
	if($('#implonlineslno').val() == '')
	{
		$('#form-error').html(errormessage('Please select a Invoice.')); return false;
	}
	else
	{
		$('#submitform').attr("action", "../ajax/viewmatrixinvoicepdf.php") ;
		$('#submitform').attr( 'target', '_blank' );
		$('#submitform').submit();
	}
}

function generatecustomization()
{
	var form = $("#submitform");
	var passData = "switchtype=customizationgrid&imprslno="+ encodeURIComponent($('#lastslno').val());//alert(passData)
	var queryString = "../ajax/implementationreport.php";
	$('#customerselectionprocess').html(getprocessingimage());
	ajaxcall181 = $.ajax(
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
					var response = ajaxresponse.split('^');
					if(response[0] == 1)
					{
						$('#tabgroupgridc1_2').html(response[1]);
					}
					else if(response[0] == 2)
					{
						$('#tabgroupgridc1_2').html(scripterror());
					}
				}
				
			}, 
			error: function(a,b)
			{

				$('#customerselectionprocess').html(scripterror());
			}
		});	
	
}


function generaterafgrid()
{
	var form = $("#submitform");
	var passData = "switchtype=rafgrid&imprslno="+ encodeURIComponent($('#lastslno').val());//alert(passData)
	var queryString = "../ajax/implementationreport.php";
	$('#customerselectionprocess').html(getprocessingimage());
	ajaxcall186 = $.ajax(
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
					var response = ajaxresponse.split('^');//alert(response);
					if(response[0] == 1)
					{
						$('#rafgriddisplay').html(response[1]);
						$('#raftotal').html(response[2]);
					}
				}
				
			}, 
			error: function(a,b)
			{

				$('#customerselectionprocess').html(scripterror());
			}
		});	
	
}


function generateinvoicegrid(invoiceno,lastslno)
{
	var form = $("#submitform");
	var passData = "switchtype=invoicegrid&invoiceno="+ encodeURIComponent(invoiceno) + "&lastslno=" + encodeURIComponent(lastslno);//alert(passData)
	var queryString = "../ajax/implementationreport.php";
	$('#customerselectionprocess').html(getprocessingimage());
	ajaxcall186 = $.ajax(
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
					var response = ajaxresponse.split('^');//alert(response);
					if(response[0] == 2)
					{
						if(response[2]!= 'matrix')
							$('#invoicedisplay').html('<div align="center"><a style="font-size:16px" onclick = "viewinvoice(\'' + response[1] + '\')"   class="r-text">View Invoice &gt;&gt;</a></div>');
						else
							$('#invoicedisplay').html('<div align="center"><a style="font-size:16px" onclick = "viewmatrixinvoice(\'' + response[1] + '\')"   class="r-text">View Invoice &gt;&gt;</a></div>');
					}
					else if(response[0] == 1)
					{
						$('#invoicedisplay').html(response[1]);
					}
				}
				
			}, 
			error: function(a,b)
			{

				$('#customerselectionprocess').html(scripterror());
			}
		});	
	
}


function viewhistory(cusid,impslno)
{
	//alert(impslno);
	$().colorbox.close();
	tabopenimp2('2','tabg1');

	var passData = "switchtype=customergridtoform&cusid="+ encodeURIComponent(cusid);//alert(passData)
	var queryString = "../ajax/implementationreport.php";
	$('#customerselectionprocess').html(getprocessingimage());
	ajaxcall1865 = $.ajax(
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
				getcustlistonsearch(impslno);
			}
			
		}, 
		error: function(a,b)
		{

			$('#customerselectionprocess').html(scripterror());
		}
	});	
	
}

function getcustlistonsearch(impslno)
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
		customerdetailstoform(splits[1],impslno);
	}
}

function advancesearchimplementer()
{
	getimpalldatadetails('search');
	//getimpbranchduedatadetails('search');
	//$('#filterdiv').hide();

}


function displayDivfilter(elementid)
{
	if($('#'+ elementid).is(':visible'))
	{
		$('#'+ elementid).hide();
	}
	else
	{
		$('#'+ elementid).show();
	}
}


function advancesearch()
{
	var form = $("#submitform");
	var error = $("#filter-form-error");
	var values = validatestatuslistcheckboxes();
	if(values == false)	{error.html(errormessage("Select A Status")); return false;	}
	var c_value = '';
	var newvalue = new Array();
	var chks = $("input[name='summarizelist[]']");
	for (var i = 0; i < chks.length; i++)
	{
		if ($(chks[i]).is(':checked'))
		{
			c_value += $(chks[i]).val()+ ',';
		}
	}
	var statuslist = c_value.substring(0,(c_value.length-1));
	var passdata = "switchtype=searchlist"  +  "&dealer=" +encodeURIComponent($("#currentdealer2").val()) +"&type=" +encodeURIComponent($("#type2").val()) + "&category=" + encodeURIComponent($("#category2").val())+ "&implementer=" + encodeURIComponent($("#implementer2").val()) +  "&statuslist=" +encodeURIComponent(statuslist) + "&dummy=" + Math.floor(Math.random()*10054300000);//alert(passdata)
	
	$('#customerselectionprocess').html(getprocessingimage());
	queryString = "../ajax/implementationreport.php";
	ajaxcall399 = $.ajax(
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
				if(response == '')
					{
						$('#filterdiv2').show();
						customersearcharray = new Array();
						for( var i=0; i<response.length; i++)
						{
							customersearcharray[i] = response[i];
						}
						
						getcustomerlistonsearch();
						$("#customerselectionprocess").html(errormessage("Search Result"  + '<span style="padding-left: 15px;"><img src="../images/close-button.jpg" width="15" height="15" align="absmiddle" style="cursor: pointer; padding-bottom:2px" onclick="displayalcustomer()"></span> '))
						$("#totalcount").html('0');
						error.html(errormessage('No datas found to be displayed.')); 
					}
					else
					{
						$('#filterdiv2').hide();//alert(response);
						customersearcharray = new Array();
						for( var i=0; i<response.length; i++)
						{
							customersearcharray[i] = response[i];
						}
						getcustomerlistonsearch();
						$("#customerselectionprocess").html(successmessage("Search Result"  + '<span style="padding-left: 15px;"><img src="../images/close-button.jpg" width="15" height="15" align="absmiddle" style="cursor: pointer; padding-bottom:2px" onclick="displayalcustomer()"></span> '));
						$("#totalcount").html(customersearcharray.length);
						$("#filter-form-error2").html();

					}
			}
		}, 
		error: function(a,b)
		{
			$("#customerselectionprocess").html(scripterror());
		}
	});	
}

function getcustomerlistonsearch()
{	
	var form = $("#submitform" );
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

function validatestatuslistcheckboxes()
{
var chksvalue = $("input[name='summarizelist[]']");
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
	if (!hasChecked)
	{
		return false
	}
}


function displayalcustomer()
{	
	var form = $("#submitform" );
	var selectbox = $('#customerlist');
	$('#customerselectionprocess').html(successsearchmessage('All Customers...'));
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


//Function to reset the from to the default value-Meghana[21/12/2009]
function resetDefaultValues(oForm)
{
    var elements = oForm.elements; 
 	oForm.reset();
	$("#filter-form-error").html('');
	for (i=0; i<elements.length; i++) 
	{
		field_type = elements[i].type.toLowerCase();
	}
	
	switch(field_type)
	{
	
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

		default:$("#districtcodedisplaysearch").html('<select name="district2" class="swiftselect" id="district2" style="width:180px;"><option value="">ALL</option></select>') ;
			
	}
}

//Function to reset the from to the default value-Meghana[21/12/2009]
function resetDefaultValues2(oForm)
{
    var elements = oForm.elements; 
 	oForm.reset();
	$("#filter-form-error1").html('');
	for (i=0; i<elements.length; i++) 
	{
		field_type = elements[i].type.toLowerCase();
	}
	
	switch(field_type)
	{
	
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

		default:$("#districtcodedisplay").html('<select name="district" class="swiftselect" id="district" style="width:180px;"><option value="">ALL</option></select>') ;
			
	}
}
