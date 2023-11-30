// JavaScript Document
var cardarray = new Array();
var cardarray1 = new Array();
var cardarray2 = new Array();
var cardarray3 = new Array();
var cardarray4 = new Array();

var process1 = false;
var process2 = false;
var process3 = false;
var process4 = false;


function gettotalcardcount()
{
	var form = $('#cardsearchfilterform');
	var passData = "switchtype=getcardcount&dummy=" + Math.floor(Math.random()*10054300000);
	queryString = "../ajax/customer.php";
	ajaxcall1 = $.ajax(
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
				var response = ajaxresponse['totalcardcount'];
				if(response == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					refreshcardarray(response);
				}
			}
		}, 
		error: function(a,b)
		{
			$("#cardselectionprocess").html(scripterror());
		}
	});	
}

function refreshcardarray(cardcount)
{
	var form = $('#cardsearchfilterform');
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
	$('#cardselectionprocess').html(getprocessingimage());
	queryString = "../ajax/customer.php";
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
				cardarray1 = new Array();
				for( var i=0; i<response.length; i++)
				{
					cardarray1[i] = response[i];
				}
				process1 = true;
				compilecardarray();
			}
		}, 
		error: function(a,b)
		{
			$("#cardselectionprocess").html(scripterror());
		}
	});	
	
	queryString = "../ajax/customer.php";
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
				cardarray2 = new Array();
				for( var i=0; i<response.length; i++)
				{
					cardarray2[i] = response[i];
				}
				process2 = true;
				compilecardarray();
			}
		}, 
		error: function(a,b)
		{
			$("#cardselectionprocess").html(scripterror());
		}
	});	

	queryString = "../ajax/customer.php";
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
				cardarray3 = new Array();
				for( var i=0; i<response.length; i++)
				{
					cardarray3[i] = response[i];
				}
				process3 = true;
				compilecardarray();
			}
		}, 
		error: function(a,b)
		{
			$("#cardselectionprocess").html(scripterror());
		}
	});	
	
	queryString = "../ajax/customer.php";
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
				cardarray4 = new Array();
				for( var i=0; i<response.length; i++)
				{
					cardarray4[i] = response[i];
				}
				process4 = true;
				compilecardarray();
			}
		}, 
		error: function(a,b)
		{
			$("#cardselectionprocess").html(scripterror());
		}
	});	

}

function compilecardarray()
{
	if(process1 == true && process2 == true && process3 == true && process4 == true)
	{
		cardarray = cardarray1.concat(cardarray2.concat(cardarray3.concat(cardarray4)));
		$('#cardselectionprocess').html('');
		getcardlist();
		
	}
	else
	return false;
}

function getcardlist()
{	
	var form = $('#cardsearchfilterform');
	var selectbox = $('#cardlist');
	var numberofcards = cardarray.length;
	var actuallimit = 500;
	var limitlist = (numberofcards > actuallimit)?actuallimit:numberofcards;
	
	$('option', selectbox).remove();
	var options = selectbox.attr('options');
	
	for( var i=0; i<limitlist; i++)
	{
		var splits = cardarray[i].split("^");
		options[options.length] = new Option(splits[0], splits[1]);

	}
}

function carddetailstoform(cardid)
{
	if(cardid != '')
	{	
		$('#carddetails').show();
		$('#form-error').html('');
		var form = $('#cardsearchfilterform');
		$('#cardsearchfilterform')[0].reset();
		var passData = "switchtype=carddetailstoform&cardlastslno=" + encodeURIComponent(cardid) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
		$('#cardselectionprocess').html(getprocessingimage());
		var queryString = "../ajax/customer.php";
		ajaxcall6 = $.ajax(
		{
			type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
			success: function(ajaxresponse,status)
			{	
				var response  = ajaxresponse;
				if(response == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else 
				if(response['errorcode'] == '1')
				{
					$('#cardlist').val(response['cardid']);//alert('1');
					$('#cardsearchtext').val(response['scratchnumber'] + ' | ' + response['cardid']);
					$('#displaycardnumber').html(response['cardid']);
					$('#displayscratchno').html(response['scratchnumber']);
					$('#displaypurchasetype').html(response['purchasetype']);
					$('#displayusagetype').html(response['usagetype']);
					$('#displayattachedto').html(response['attachedto']);
					$('#displayproductname').html(response['productname']);
					$('#displayattachdate').html(response['attcheddate']);
					$('#displayregisteredto').html(response['registeredto']);
					$('#displayregisterdate').html(response['registereddate']);
					$('#displaycardstatus').html(response['cardstatus']);
					$('#displaycardremarks').html(response['remarks']);
					$('#displayscheme').html(response['schemename']);
					$('#displayattachedtocust').html(response['businessname']);
					$('#cardselectionprocess').html('');
				}
				

		    },
			error: function(a,b)
			{
				$("#cardselectionprocess").html(scripterror());
			}
		});	
	}
}



function selectcardfromlist()
{
	var selectbox = $("#cardlist option:selected").val();
	var cardnosearch = $('#cardsearchtext');
    carddetailstoform(selectbox);
}


function selectacard(input)
{
	var selectbox = $('#cardlist');
	
	if(input == "")
	{
		getcardlist();
	}
	else
	{
		$('option', selectbox).remove();
		var options = selectbox.attr('options');
	
		var addedcount = 0;
		for( var i=0; i < cardarray.length; i++)
		{
			
			if(input.charAt(0) == ".")
			{
				withoutdot = input.substring(1,input.length);
				pattern = new RegExp("^" + withoutdot.toLowerCase());
				comparestringsplit = cardarray[i].split("^");
				comparestring = comparestringsplit[1];
			}
			else
			{
				pattern = new RegExp("^" + input.toLowerCase());
				comparestring = cardarray[i];
			}
			if(pattern.test(comparestring.toLowerCase()))
			{
				var splits = cardarray[i].split("^");
				options[options.length] = new Option(splits[0], splits[1]);
				addedcount++;
				if(addedcount == 100)
					break;
			}
		}
	}
}


function cardsearch(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 38)
		scrollcard('up');
	else if(KeyID == 40)
		scrollcard('down');
	else
	{
		var form = $('#cardsearchfilterform');
		var input = $('#cardsearchtext').val();
		selectacard(input);
	}
}

function scrollcard(type)
{
	var selectbox = $('#cardlist');
	var totalcus = $("#cardlist option").length;
	var selectedcus = $("select#cardlist").attr('selectedIndex');
	if(type == 'up' && selectedcus != 0)
		$("select#cardlist").attr('selectedIndex', selectedcus - 1);
	else if(type == 'down' && selectedcus != totalcus)
		$("select#cardlist").attr('selectedIndex', selectedcus + 1);
	selectcardfromlist();
}

function clearcarddetails()
{
	$('#displaycardnumber').html('');
	$('#displayscratchno').html('');
	$('#displaypurchasetype').html('');
	$('#displayproductname').html('');
	$('#displayusagetype').html('');
	$('#displayattachedto').html('');
	$('#displayattachdate').html('');
	$('#displayregisteredto').html('');
	$('#displayregisterdate').html('');
	$('#displaycardremarks').html('');
	$('#displaycardstatus').html('');
	$('#displayscheme').html('');
	$('#cardselectionprocess').html('');
}


