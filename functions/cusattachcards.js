// JavaScript Document
var regcardarray = new Array();
var regcardarray1 = new Array();
var regcardarray2 = new Array();
var regcardarray3 = new Array();
var regcardarray4 = new Array();


var process1 = false;
var process2 = false;
var process3 = false;
var process4 = false;

function gettotalcusattachcard()
{
	var form = $('#scratchcradloading');
	var passData = "switchtype=getcardcount&dummy=" + Math.floor(Math.random()*10054300000);//alert(passData)
	queryString = "../ajax/cus-cardattach.php";
	$('#scratchcradloading').html(getprocessingimage());
	ajaxcall1 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			$('#scratchcradloading').html('');//alert(ajaxresponse)
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
				refreshcusattachcardarray(response['count']);
			}
		}, 
		error: function(a,b)
		{
			$("#scratchcradloading").html(scripterror());
		}
	});	
}

function refreshcusattachcardarray(cardcount)
{
	var totalcardcount = cardcount;
	var limit = Math.round(totalcardcount/4);
	//alert(limit);
	var startindex = 0;
	var startindex1 = (limit)+1;
	var startindex2 = (limit*2)+1;
	var startindex3 = (limit*3)+1;
	var form = $('#cardsearchfilterform');
	var passData = "switchtype=getcardlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex);
	var passData1 = "switchtype=getcardlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex1);
	var passData2 = "switchtype=getcardlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex2);
	var passData3 = "switchtype=getcardlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex3);
	$('#scratchcradloading').html(getprocessingimage());
	queryString = "../ajax/cus-cardattach.php";
	ajaxcall2 = $.ajax(
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
					regcardarray1[i] = response[i];
				}
				process1 = true;
				compilecardarray();
			}
		}, 
		error: function(a,b)
		{
			$("#scratchcradloading").html(scripterror());
		}
	});	
	
	queryString = "../ajax/cus-cardattach.php";
	ajaxcall3 = $.ajax(
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
					regcardarray2[i] = response[i];
				}
				process2 = true;
				compilecardarray();
			}
		}, 
		error: function(a,b)
		{
			$("#scratchcradloading").html(scripterror());
		}
	});	

	queryString = "../ajax/cus-cardattach.php";
	ajaxcall4 = $.ajax(
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
					regcardarray3[i] = response[i];
				}
				process3 = true;
				compilecardarray();
			}
		}, 
		error: function(a,b)
		{
			$("#scratchcradloading").html(scripterror());
		}
	});	
	
	queryString = "../ajax/cus-cardattach.php";
	ajaxcall5 = $.ajax(
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
					regcardarray4[i] = response[i];
				}
				process4 = true;
				compilecardarray();
			}
		}, 
		error: function(a,b)
		{
			$("#scratchcradloading").html(scripterror());
		}
	});	

}

function compilecardarray()
{
	if(process1 == true && process2 == true && process3 == true && process4 == true)
	{
		regcardarray = regcardarray1.concat(regcardarray2.concat(regcardarray3.concat(regcardarray4)));
		getregcardlist();
		$('#scratchcradloading').html('');
		
	}
	else
	return false;
}




function getregcardlist()
{	
	var form = $('#registrationform');
	var selectbox = $('#scratchcardlist');
	var numberofcards = regcardarray.length;
	var actuallimit = 500;
	var limitlist = (numberofcards > actuallimit)?actuallimit:numberofcards;

	$('option', selectbox).remove();
	var options = selectbox.attr('options');
	
	for( var i=0; i<limitlist; i++)
	{
		var splits = regcardarray[i].split("^");
		options[options.length] = new Option(splits[0], splits[1]);
	}
}



function reg_selectcardfromlist()
{
	var selectbox = $('#scratchcardlist');
	
	var cardnosearch = $('#searchscratchnumber');
	cardnosearch.val($('#scratchcardlist option:selected').text());
	cardnosearch.select();//alert(selectbox.value);
    scratchdetailstoform(selectbox.val());	
	$('#form-error').html('');
}

function reg_selectacard(input)
{
	var selectbox = $('#scratchcardlist');
	
	if(input == "")
	{
		getregcardlist();
	}
	else
	{
		var initiallength = selectbox.length;
		
		$('option', selectbox).remove();
		var options = selectbox.attr('options');
	
		var addedcount = 0;
		for( var i=0; i < regcardarray.length; i++)
		{
			
			if(input.charAt(0) == ".")
			{
				withoutdot = input.substring(1,input.length);
				pattern = new RegExp("^" + withoutdot.toLowerCase());
				comparestringsplit = regcardarray[i].split("^");
				comparestring = comparestringsplit[1];
			}
			else
			{
				pattern = new RegExp("^" + input.toLowerCase());
				comparestring = regcardarray[i];
			}
			if(pattern.test(comparestring.toLowerCase()))
			{
				var splits = regcardarray[i].split("^");
				options[options.length] = new Option(splits[0], splits[1]);
				addedcount++;
				if(addedcount == 10)
					break;
			}
		}
	}
}

function reg_cardsearch(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 38)
		reg_scrollcard('up');
	else if(KeyID == 40)
		reg_scrollcard('down');
	else
	{
		var form = $('#registrationform');
		var input = $('#searchscratchnumber').val();
		reg_selectacard(input);
	}
}

function reg_scrollcard(type)
{
	var selectbox = $('#scratchcardlist');
	var totalcard = $("#scratchcardlist option").length;
	var selectedcard = $("select#scratchcardlist").attr('selectedIndex');
	if(type == 'up' && selectedcard != 0)
		$("select#scratchcardlist").attr('selectedIndex', selectedcard - 1);
	else if(type == 'down' && selectedcard != totalcard)
		$("select#scratchcardlist").attr('selectedIndex', selectedcard + 1);
	reg_selectcardfromlist();
}

function scratchdetailstoform(cardid)
{
	if(cardid != '')
	{
		enableattachcard();
		var passdata = "switchtype=scratchdetailstoform&cardid=" + encodeURIComponent(cardid) + "&dummy=" + Math.floor(Math.random()*100032680100); 
		ajaxcall1 = createajax();
		$('#scratchcradloading').html(getprocessingimage());
		var queryString = "../ajax/cus-cardattach.php";
		ajaxcall1 = $.ajax(
			{
				type: "POST",url: queryString, data: passdata, cache: false,dataType: "json",
				success: function(ajaxresponse,status)
				{	
					$('#scratchcradloading').html('');
					$('#detailsonscratch').show();
					if(ajaxresponse == 'Thinking to redirect')
					{
						window.location = "../logout.php";
						return false;
					}
					else
					{
						var response = ajaxresponse;
						$('#cardnumberdisplay').html(response['cardid']);
						$('#scratchnodisplay').html(response['scratchnumber']);
						$('#purchasetypedisplay').html(response['purchasetype']);
						$('#usagetypedisplay').html(response['usagetype']);
						$('#attachedtodisplay').html(response['attachedto']);
						$('#productdisplay').html(response['productname'] + ' [' + response['productcode'] + ']');
						$('#registeredtodisplay').html(response['registeredto']);
						$('#attachdatedisplay').html(response['attcheddate']);
						$('#cardstatusdisplay').html(response['cardstatus']);
						$('#cardattacheddate').html(response['cuscardattacheddate']);
						$('#remarks').html(response['cuscardremarks']);
					}
						
//Added on 21-03-2018

						if (response['usagetype']== 'singleuser') 
						{
							var usagetype= 'Single User';
						}
						else if (response['usagetype']== 'multiuser')
						{
							var usagetype= 'Multi User';
						}
						else if (response['usagetype']== 'additionallicense')
						{
							var usagetype= 'Additional License';
						}
document.getElementById('description').value = "1$"+response['productname']+"$"+response['purchasetype']+"$"+usagetype+"$"+response['scratchnumber']+"$"+response['cardid']+"$0";
//ends					

				}, 
				error: function(a,b)
				{
					$("#scratchcradloading").html(scripterror());
				}
			});	
	}
}
//Function to enable the Attach card button--------------------------------------------------------------------------------
function enableattachcard()
{
	$('#attachcard').attr('disabled',false);
	$('#attachcard').attr('className','swiftchoicebuttonbig');
}
//Function to Enable the Attach card button--------------------------------------------------------------------------------
function disableattachcard()
{
	$('#attachcard').attr('disabled',true);
	$('#attachcard').attr('className','swiftchoicebuttondisabledbig');
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