// JavaScript Document
//Page Created By Bhavesh 
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
	var error = $('#form-error');//alert(form.cuslastslno.value);
	var passData = "";
	
	//added by samar
	    var countClass = $('.fs-options div.selected').length;
	    //console.log(countClass);

        var cardlist = '';
        $('.fs-options div.selected').each(function (index, value) { 
          var cardid = $(this).attr('data-value');
          cardlist += cardid+'^';
        });
    	
    	cardlist = cardlist.replace(/.$/,'');
    	//console.log(cardlist);
	//ends
	
	if(!$('#scratchcardlist').val())
	{
		error.html(errormessage('Please select a card from the list')); return false;
	}
	else
	{
		var field = $("#customerlist") 
		if(!field.val())
		{ error.html(errormessage('Please Select Dealer')); field.focus();return false;}
		
		passdata =  "switchtype=attachcard&customerreference=" + encodeURIComponent($('#customerlist').val()) 
		+ "&cardid=" + encodeURIComponent($('#scratchcardlist').val())+ "&yearcount=" + encodeURIComponent($('#yearcount').val())
		+ "&cardlist=" + encodeURIComponent(cardlist)+ "&purcheck=" + encodeURIComponent($('#purcheck').val())+ 
		"&licensepurchase=" + encodeURIComponent($('#licensepurchase').val())+ "&description=" + encodeURIComponent($('#description').val())+ "&remarks=" +
		encodeURIComponent($('#remarks').val()) + "&lastyearusagecheck=" +  encodeURIComponent($('#lastyearusagecheck').val()) + 
		"&dummy=" + Math.floor(Math.random()*100000000);
	    
	    //console.log(passdata);return false;
		
		queryString = '../ajax/dealerpinallot.php';
		error.html(getprocessingimage());
		ajaxobjext10 = $.ajax(
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
				    //console.log(ajaxresponse);
					var splitajaxresponse = ajaxresponse;
					if(splitajaxresponse['errorcode'] == '1')
					{
						error.html(successmessage(splitajaxresponse['errormsg']));
						generatedatagrid('');
						//gettotalcusattachcard();
						newentry();
						
						//added by samar
						
    						var e = jQuery.Event("keyup");
                            $('.fs-search input').val('').trigger(e);
                            $('.fs-options').find('.selected').remove();
                            $('.fs-label').html('');
                            $('.fs-label').html('Select some cards');
                            $('#detailsonscratch').hide();
                            disableattachcard();
						
						//ends
					}
					else
					{
						error.html(errormessage('Unable to Connect...'));
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

function getcustomerdetails(card)
{
		var customername = $('#displaycustomername').html();
		var passData = "switchtype=newupdationchange&card="+encodeURIComponent(card)+"&customerid="+encodeURIComponent($('#lastslno').val())+
		"&dummy=" + Math.floor(Math.random()*1000782200000);
		//alert(passData);
		var queryString = "../ajax/dealerpinallot.php";
		ajaxcall1 = $.ajax(
		{
			type: "POST",url: queryString, data: passData, cache: false,dataType:'json',
			success: function(ajaxresponse,status)
			{
				var response = (ajaxresponse);
				//var hiddenproductoflicense = $('#proname').val(response['productname']);
				//var hidproduct = $('#proname').val();
				alert(response);
				alert(response['currentyearcard']);
					alert(response['totalcards']);
						alert(response['lasttwoyearcount']);
							alert(response['lastyearusagecheck']);
				var currentyearcard = $('#purcheck').val(response['currentyearcard']);
				//var hidpurtype0 =$('#purcheck').val(); 
				
				var totalcards = $('#licensepurchase').val(response['totalcards']);
				var totalcount = $('#licensepurchase').val();
				
				var yearcount = $('#yearcount').val(response['lasttwoyearcount']);
				var lasttwoyearcount = $('#yearcount').val();
				
				var usagecount = $('#lastyearusagecheck').val(response['lastyearusagecheck']);
				var lastyearusagecheck = $('#lastyearusagecheck').val();
				
				$("#remarks").val('');
			}
		 });
	}

function newentry()
{
	var form = $('#submitform');
	form[0].reset();
	$('#detailsonscratch').hide();
}

function gettotalcustomercount()
{
	var form = $('#customerselectionprocess');
	var passData = "switchtype=getcustomercount&dummy=" + Math.floor(Math.random()*10054300000);
	queryString = "../ajax/dealerpinallot.php";
	$('#customerselectionprocess').html(getprocessingimage());
	ajaxcall1 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			$('#customerselectionprocess').html('');	
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
	queryString = "../ajax/dealerpinallot.php";
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
	
	queryString = "../ajax/dealerpinallot.php";
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

	queryString = "../ajax/dealerpinallot.php";
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
	
	queryString = "../ajax/dealerpinallot.php";
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
		getcustomerlist1();
		$('#customerselectionprocess').html('');	
	}
	else
	return false;
}


//changing attr to prop
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
	var options = selectbox.prop('options');
	
	for( var i=0; i<limitlist; i++)
	{
		var splits = customerarray[i].split("^");
		//options[options.length] = new Option(splits[0], splits[1]);
		$('#customerlist').append('<option value="'+splits[1]+'">'+splits[0]+'</option>');
	}
}

function generatedatagrid(startlimit)
{
	var passdata = "switchtype=generategrid&lastslno=" + encodeURIComponent($('#customerlist').val()) + "&dummy=" + Math.floor(Math.random()*10054300000);//alert(passdata);
	queryString = "../ajax/dealerpinallot.php";
	ajaxcall2 = $.ajax(
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
					//alert(response['errorcode']);
					if(response['errorcode'] == '1')
					{
						$('#tabgroupgridwb1').html("Total Count :  " + response['fetchresultcount']);
						$('#tabgroupgridc1_1').html(response['grid']);
						$('#tabgroupgridc1link').html(response['linkgrid']);
						$('#lastslno').val($('#customerlist').val());
						gettotalcusattachcard();
					}
					else
					{
						$('#tabgroupgridc1').html("No datas found to be displayed.");
					}
				}
			}, 
			error: function(a,b)
			{
				$("#tabgroupgridc1").html(scripterror());
			}
		});	
}

//function to display 'Show more records' or 'Show all records' 
function generatemordatagrid(startlimit,slnocount,showtype)
{
	var passdata = "switchtype=generategrid&lastslno=" + encodeURIComponent($('#customerlist').val()) + "&startlimit=" + encodeURIComponent(startlimit)+ "&slnocount=" + encodeURIComponent(slnocount)+ "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);//alert(passData);
	queryString = "../ajax/dealerpinallot.php";
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
				//alert(response['errorcode']);
				if(response['errorcode'] == '1')
				{
					$('#tabgroupgridwb1').html("Total Count :  " + response['fetchresultcount']);
					$('#custresultgrid'). html($('#tabgroupgridc1_1').html());
					$('#tabgroupgridc1_1').html($('#custresultgrid').html().replace(/\<\/table\>/gi,'')+ response['grid']);
					$('#tabgroupgridc1link').html(response['linkgrid']);
				}
				else
				{
					$('#tabgroupgridc1').html("No datas found to be displayed.");
				}
			}
		}, 
		error: function(a,b)
		{
			$("#tabgroupgridc1").html(scripterror());
		}
	});	
}

function selectfromlist()
{
	var selectbox = $('#customerlist');
	var cusnamesearch = $('#detailsearchtext');
	cusnamesearch.val($('#customerlist option:selected').text());
	$('#displaycustomername').html(cusnamesearch.val());
	cusnamesearch.select();
	enableformelemnts();
	//disableattachcard();
	newentry();
	$('#form-error').html('');
	generatedatagrid('');
	selectedproductdealer();//added for nagarsha
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
		var options = selectbox.prop('options');
		
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
				//alert(customerarray[i]);
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
		var input =  $('#detailsearchtext').val();
		selectacustomer(input);
	}
}

//changing attr to prop
function scrollcustomer(type)
{
	var selectbox = $('#scratchcardlist');
	var totalcus = $("#scratchcardlist option").length;
	var selectedcus = $("select#scratchcardlist").prop('selectedIndex');
	if(type == 'up' && selectedcus != 0)
		$("select#scratchcardlist").prop('selectedIndex', selectedcus - 1);
	else if(type == 'down' && selectedcus != totalcus)
		$("select#scratchcardlist").prop('selectedIndex', selectedcus + 1);

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