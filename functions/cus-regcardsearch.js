// JavaScript Document
var regcardarray = new Array();
var new_regcardarray = new Array();
var up_regcardarray = new Array();

var t3;

function new_refreshcuscardarray()
{
	var passData = "switchtype=newregcardlist&dummy=" + Math.floor(Math.random()*10054300000);
	queryString = "../ajax/customer.php";
	$('#reg-form-error').html(getprocessingimage());
	ajaxnewrequest = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			$('#reg-form-error').html('');
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
					new_regcardarray[i] = response[i];
					
				}
				getregcardlist();
			}
		}, 
		error: function(a,b)
		{
			$("#gridprocessing").html(scripterror());
		}
	});	
}


function up_refreshcuscardarray()
{
	var passData = "switchtype=updationcardlist&dummy=" + Math.floor(Math.random()*10054300000);
	queryString = "../ajax/customer.php";
	$('#reg-form-error').html(getprocessingimage());
	ajaxcall3 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			$('#reg-form-error').html('');
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
					up_regcardarray[i] = response[i];
				}
				getregcardlist();
			}
		}, 
		error: function(a,b)
		{
			$("#gridprocessing").html(scripterror());
		}
	});	
}

function new_cardarray()
{
	var passData = "switchtype=newregcardlist&dummy=" + Math.floor(Math.random()*10054300000);//alert(passData)
	queryString = "../ajax/customer.php";
	$('#reg-form-error').html(getprocessingimage());
	ajaxcall2 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{
			$('#reg-form-error').html('');	
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
					new_regcardarray[i] = response[i];
					
				}
				getregcardlist();
				$('#reg-form-error').html('');
			}
		}, 
		error: function(a,b)
		{
			$("#gridprocessing").html(scripterror());
		}
	});	
}

function getregcardlist()
{	
	var form = $('#registrationform');
	var selectbox = $('#scratchcardlist');
	var numberofcards = regcardarray.length;
	var actuallimit = 10;
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
	var totalcus = $("#scratchcardlist option").length;
	var selectedcus = $("select#scratchcardlist").attr('selectedIndex');
	if(type == 'up' && selectedcus != 0)
		$("select#scratchcardlist").attr('selectedIndex', selectedcus - 1);
	else if(type == 'down' && selectedcus != totalcus)
		$("select#scratchcardlist").attr('selectedIndex', selectedcus + 1);
	reg_selectcardfromlist();

}

