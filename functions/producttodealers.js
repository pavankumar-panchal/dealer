// JavaScript Document

var productarray = new Array();


function formsubmit(changetype)
{
	var form = $("#submitform");
	var error = $("#form-error");
	var field = $("#productname");
	var fieldlist = $("#productlist");
	if(!field.val()) { error.html(errormessage('Select the Product')); fieldlist.focus(); return false; }
	var fieldlist = $("#dealerlist");
	var len =document.getElementById("selecteddealers").length;
	if(len==0) { error.html(errormessage('Select the Dealers')); fieldlist.focus(); return false; }
	else
	{
		var dealers = new Array();
		var e = document.getElementById('selecteddealers').options;
		for(var i=0;i<len;i++)
		{
			dealers[i] = e[i].value;
		}
		var passData = '';
		passData = "changetype=" + encodeURIComponent(changetype) + "&productname="+ encodeURIComponent($("#productname").val())+"&productcode="+ encodeURIComponent($("#productcode").val()) + "&dealers="+ encodeURIComponent(dealers);
		queryString = '../ajax/producttodealers.php';
		error.html('<img src="../images/imax-loading-image.gif" border="0" />');
		ajaxcall0 = $.ajax(
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
					error.html('');
					var response = ajaxresponse['errormessage'].split('^');//alert(response)
					if(response[0] == 1)
					{
						error.html(successmessage(response[1]));
						//refreshproductarray();
						//productdatagrid('');
						
						newentry();
					}
					else if(response[0] == 2)
					{
						error.html(errormessage(response[1]));
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


function newentry()
{
	$("#submitform" )[0].reset();
	$('#lastslno').val('');
	document.getElementById('add').disabled=true;
	document.getElementById('remove').disabled=true;
	document.getElementById('removeall').disabled=true;
	document.getElementById('dlist').innerHTML='<select name="dealerlist" size="5" class="swiftselect" id="dealerlist" style="width:210px; height:200px;" ></select>';
	deleteallentry();
	$('#detailsearchtext').val('');
	document.getElementById('productlist').selectedIndex=0;
}

function refreshproductarray()
{	
	var passData = "changetype=generateproductlist&dummy=" + Math.floor(Math.random()*10054300000);
	queryString = "../ajax/producttodealers.php";
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
				var response = ajaxresponse;
				productarray = new Array();
				for( var i=0; i<response.length; i++)
				{
					productarray[i] = response[i];
				}
				getproductlist();
				//$("#form-error").html(' ');
				$("#totalproductcount").html(productarray.length);
			}
		}, 
		error: function(a,b)
		{
			$("#productselectionprocess").html(scripterror());
		}
	});		
}


function getproductlist()
{	
	var form = $('#submitform');
	var selectbox = $('#productlist');
	var numberofproducts = productarray.length;
	$('#detailsearchtext').focus();
	var actuallimit = 500;
	var limitlist = (numberofproducts > actuallimit)?actuallimit:numberofproducts;
	
	//selectbox.options.length = 0;
	$('option', selectbox).remove();
	var options = selectbox.attr('options');
	
	for( var i=0; i<limitlist; i++)
	{
		var splits = productarray[i].split("^");
		options[options.length] = new Option(splits[0], splits[1]);
	}
	
}


function productdetailstoform(prdid)
{
	if(prdid != '')
	{
		$("#productselectionprocess").html('');
		var form = $("#submitform");
		$("#submitform" )[0].reset();
		var passData = "changetype=productdetailstoform&lastslno=" + encodeURIComponent(prdid) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
		$("#form-error").html(getprocessingimage());
		var queryString = "../ajax/producttodealers.php";
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
					$("#form-error").html('');
					var response = ajaxresponse;
					if(response['errorcode'] == '1')
					{
						if(response['productcode'] == '')
						alert('Product Code Not Available.');
						$("#lastslno").val(response['slno']);
						$("#productcode").val(response['productcode']);
						$("#productname").val(response['productname']);
						document.getElementById("dlist").innerHTML=response['dealerlist'];
						document.getElementById('selecteddealers').options.length = 0;
						
						document.getElementById('add').disabled=false;
						document.getElementById('remove').disabled=false;
						document.getElementById('removeall').disabled=false;
						document.getElementById('dofilter').disabled=false;
					}
					else
					{
						$("#form-error").html(errormessage("No datas found to be displayed."));
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

function selectfromlist()
{
	var selectbox = $("#productlist option:selected").val();
	$('#detailsearchtext').val($("#productlist option:selected").text());
	$('#detailsearchtext').select();
	productdetailstoform(selectbox);	
}


function selectaproduct(input)
{
	var selectbox = $('#productlist');
	if(input == "")
	{
		getproductlist();
	}
	else
	{
		$('option', selectbox).remove();
		var options = selectbox.attr('options');

		var addedcount = 0;
		for( var i=0; i < productarray.length; i++)
		{
				if(input.charAt(0) == "%")
				{
					withoutspace = input.substring(1,input.length);
					pattern = new RegExp(withoutspace.toLowerCase());
					comparestringsplit = productarray[i].split("^");
					comparestring = comparestringsplit[1];
				}
				else
				{
					pattern = new RegExp("^" + input.toLowerCase());
					comparestring = productarray[i];
				}
			var result1 = pattern.test(trimdotspaces(productarray[i]).toLowerCase());
			var result2 = pattern.test(productarray[i].toLowerCase());
			if(result1 || result2)
			{
				var splits = productarray[i].split("^");
				options[options.length] = new Option(splits[0], splits[1]);
				addedcount++;
				if(addedcount == 100)
					break;
			}
		}
	}
}


function scrollproduct(type)
{
	var selectbox = $('#productlist');
	var totalcus = $("#productlist option").length;
	var selectedcus = $("select#productlist").attr('selectedIndex');
	if(type == 'up' && selectedcus != 0)
		$("select#productlist").attr('selectedIndex', selectedcus - 1);
	else if(type == 'down' && selectedcus != totalcus)
		$("select#productlist").attr('selectedIndex', selectedcus + 1);
	selectfromlist();
}


//function to add values of the selected option to select box -Meghana[23/11/2009]
function addentry(dealercode)
{
	//Get the Select Box as an object
	var selectbox = document.getElementById('dealerlist');
	
	//Check if any product is select. Else, prompt to select a product.
	if(selectbox.selectedIndex < 0)
	{
		alert("Select a Dealer to Add.");
		return false;
	}
	
	//If the option is disabled, then do nothing.
	if(selectbox.options[selectbox.selectedIndex].disabled == true)
	{
		return false;
	}
	
	//Take the value and Text of selected product from selected index.
	var adddealervalue = selectbox.options[selectbox.selectedIndex].value;
	var adddealertext = selectbox.options[selectbox.selectedIndex].text;

	//When double clicked on a disabled, the other selected will be passed. So, compare the double clicked product value with selected value and return false.
	
	if(dealercode)
	{
		if(dealercode != adddealervalue)
			return false;
	}
	//Get the second Select Box as an object
	var secondselectbox = document.getElementById('selecteddealers');
	
	//Add the value to second select box
	var newindexforadding = secondselectbox.length;
	secondselectbox.options[newindexforadding] = new Option(adddealertext,adddealervalue);
	secondselectbox.options[newindexforadding].setAttribute('ondblclick', 'deleteentry("' + adddealervalue + '");');

	
	//Disable the added option in first select box
	selectbox.options[selectbox.selectedIndex].disabled = true;

}	

//function to remove values of the selected option from select box -Meghana[23/11/2009]
function deleteentry()
{
	//alert(productcode);
	//Get the select boxes as objects
	var selectbox = document.getElementById('dealerlist');
	var secondselectbox = document.getElementById('selecteddealers');
	
	//Check if any product is select. Else, prompt to select a product.
	if(secondselectbox.selectedIndex < 0)
	{
		alert("Select a Dealer to Remove.");
		return false;
	}

	//Take the value and Text of selected product from selected index.
	var deldealervalue = secondselectbox.options[secondselectbox.selectedIndex].value;
	var deldealertext = secondselectbox.options[secondselectbox.selectedIndex].text;
	
	//Run a loop for whole select box [2] and remove the entry where value is deletable
	for(i = 0; i < secondselectbox.length; i++)
    {
		loopvalue = secondselectbox.options[i].value;
		if(loopvalue == deldealervalue)
		{
			secondselectbox.options[i] = null;
		}
	}
	
	//Run a loop for whole select box [1] and Enable the entry where value is deleted
	for(i = 0; i < selectbox.length; i++)
    {
		loopvalue = selectbox.options[i].value;
		if(loopvalue == deldealervalue)
		{
			selectbox.options[i].disabled = false;
		}
	}

}


//function to remove all values of the selected option from select box -Meghana[25/11/2009]
function deleteallentry()
{
		//Get the select boxes as objects
		var dealerarray = new Array();
		var alldealerarray = new Array();
		var selectbox = document.getElementById('dealerlist');
		var secondselectbox = document.getElementById('selecteddealers');
		var secoundvalues = document.getElementById('dealerlist');
		for(var i=0; i < secoundvalues.length; i++ )
		{
			dealerarray[i] = secoundvalues[i].value;
		}
	
		var ckvalues = document.getElementById('selecteddealers');
		for(var i=0; i < ckvalues.length; i++ )
		{
			alldealerarray[i] = ckvalues[i].value;
		}
	
		//Run a loop for whole select box [2] and remove the entry where value is deletable
		for(i = 0; i < alldealerarray.length; i++)
		{
				secondselectbox.options[secondselectbox.length -1] = null;
		}
		//Run a loop for whole select box [1] and Enable the entry where value is deleted
		for(j = 0; j < selectbox.length; j++)
		{
				selectbox.options[j].disabled = false;
		}

}
