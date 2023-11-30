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
var checksection;

var invoicearray = new Array();
var rowcountvalue = 0;

function gettotalcustomercount()
{
	var form = $('#customerselectionprocess');
	var passData = "switchtype=getcustomercount&dummy=" + Math.floor(Math.random()*10054300000);
	queryString = "../ajax/invoicing.php";
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
	var passData = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit+1) + "&startindex=" + encodeURIComponent(startindex);//alert(passData)
	var passData1 = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex1);//alert(passData1)
	var passData2 = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex2);//alert(passData2)
	var passData3 = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex3);//alert(passData3)
	$('#customerselectionprocess').html(getprocessingimage());
	queryString = "../ajax/invoicing.php";
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
	
	queryString = "../ajax/invoicing.php";
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

	queryString = "../ajax/invoicing.php";
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
	
	queryString = "../ajax/invoicing.php";
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
		$("#CALBUTTONDPC_startdate").hide();
	}
	else
	return false;
}
//Function to add all customers to select box
function getcustomerlist1()
{	
	disableformelemnts_invoicing();
	var form = document.submitform;
	var selectbox = document.getElementById('customerlist');
	var numberofcustomers = customerarray.length;
	document.filterform.detailsearchtext.focus();
	var actuallimit = 500;
	var limitlist = (numberofcustomers > actuallimit)?actuallimit:numberofcustomers;

	selectbox.options.length = 0;

	for( var i=0; i<limitlist; i++)
	{
		var splits = customerarray[i].split("^");
		selectbox.options[selectbox.length] = new Option(splits[0], splits[1]);
	}
	
}

//Function to display all customers
function displayalcustomer()
{	
	var form = document.submitform;
	var selectbox = document.getElementById('customerlist');
	document.getElementById('customerselectionprocess').innerHTML = successsearchmessage('All Customer...');
	var numberofcustomers = customerarray.length;
	document.filterform.detailsearchtext.focus();
	var actuallimit = 500;
	var limitlist = (numberofcustomers > actuallimit)?actuallimit:numberofcustomers;

	selectbox.options.length = 0;

	for( var i=0; i<limitlist; i++)
	{
		var splits = customerarray[i].split("^");
		selectbox.options[selectbox.length] = new Option(splits[0], splits[1]);
	}
	document.getElementById('totalcount').innerHTML = customerarray.length;
}


//Customer details to form
var productprice_actual = 0;

function customerdetailstoform(cusid)
{
    productprice_actual = 0;
    
	if(cusid != '')
	{
		$("#displaycustomerdetails").hide();
		$('#toggleimg1').attr('src',"../images/plus.jpg");
		$('#customerselectionprocess').html('');
		var form = $('#submitform');
		$("#submitform")[0].reset();
		var passdata = "switchtype=customerdetailstoform&lastslno=" + encodeURIComponent(cusid) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData);
		$('#customerdetailshide').show();
		$('#customerdetailsshow').hide();
		$('#customerdetailshide').html(getprocessingimage());
		var queryString = "../ajax/invoicing.php";
		ajaxobjext12 = $.ajax(
		{
			type: "POST",url: queryString, data: passdata, cache: false,dataType: "json",
			success: function(ajaxresponse,status)
			{	
				$('#searchcustomerid').val('');
				var response = ajaxresponse;
				
				//console.log(response);
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else if(response['errorcode'] == '1')
				{
					$('#customerdetailshide').hide();
					$('#customerdetailsshow').show();
					enableformelemnts();
					if($("#dealer").length > 0)
					{
						disableitemselection();
						$('#dealer').removeAttr('disabled');
					}
					$('#lastslno').val(response['slno']);
					$('#displaycustomerid').html(response['customerid']);
					$('#displaycompanyname').val(response['companyname']);
					$('#displaycontactperson').val(response['contactvalues']);
					$('#displayaddress').val(response['address']);
					$('#displayphone').val(response['phoneres']);
					$('#displaycell').val(response['cellres']);
					$('#displayemail').val(response['emailidres']);
					if(response['businesstype'] == null)
						$('#displaytypeofcategory').html('Not Available');
					else
						$('#displaytypeofcategory').html(response['businesstype']);
					if(response['customertype'] == null)
						$('#displaytypeofcustomer').html('Not Available');
					else
						$('#displaytypeofcustomer').html(response['customertype']);
					$('#displaydealer3').html(response['dealername']);
					generateinvoicedetails('');
					gridtab2(1,'tabgroupgrid','&nbsp; &nbsp;Existing Invoices');
					$("#cusnamehidden").val(response['companyname']);
					$("#addresshidden").val(response['address']);
					$("#contactpersonhidden").val(response['contactvalues']);
					$("#emailhidden").val(response['emailidres']);
					$("#phonehidden").val(response['phoneres']);
					$("#cellhidden").val(response['cellres']);
					$("#sez_enabled").val(response['sez_enabled']);
					//$('#customer_gst').html(response['gst_no']);
					//$('#displaycustomergst').val(response['gst_no']);
					//added on 03/11/2017 for disabling autoupdate of gstin 
						if((response['gst_no'] == "")||(response['gst_no'] == null))
						{
						   $('#displaycustomergst').val("");
						   document.getElementById("gst_type_yes").disabled = false;
	                       document.getElementById("gst_type_no").disabled = false;
                    	}
                    	else
                    	{
                    	   $('#displaycustomergst').val(response['gst_no']);
                    	   document.getElementById("gst_type_yes").disabled = true;
                    	   document.getElementById("gst_type_no").disabled = true;
                    	   
                    	}
					$('#state_gst').html(response['state_gst_code']);
					$('#customer_hidden_id').val(response['slno']);
					$("#custypehidden").val(response['customertype']);
					$("#cuscategoryhidden").val(response['businesstype']);
					$("#dealeridhidden").val(response['dealeridhid']);
					$("#CALBUTTONDPC_startdate").hide();
					$('#displaymarketingexe').html('Not Selected');
					$('#DPC_startdate').val('Not Avaliable');
					$('#poreference').val('Not Avaliable');
					$('#customer_gst').val(response['gstin_id']);
					$('#displaypanno').val(response['panno']);
					getdealerdetails1();
				} 
				else if(response['errorcode'] == '2')
				{
					$('#customerselectionprocess').html(errormessage(response[1]));
				}
				resetdealername();
			}, 
			error: function(a,b)
			{
				$("#gridprocess").html(scripterror());
			}
		});		
	}
}

//function to clear all customer details
function clearcustomerdetails()
{
	$('#displaycustomerid').html('');
	$('#displaycompanyname').html('');
	$('#displaycontactperson').html('');
	$('#displayaddress').html('');
	$('#displayphone').html('');
	$('#displaycell').html('');
	$('#displayemail').html('');
	$('#displayregion').html('');
	$('#displaybranch').html('');
	$('#displaytypeofcategory').html('');
	$('#displaytypeofcustomer').html('');
	$('#displaydealer').html('');
}

function selectfromlist()
{
	$('#messagerow').html('<div align="center" style="height:15px;"><strong><font color="#FF0000">Select a Item First</font></strong></div>');
	var selectbox = document.getElementById('customerlist');
	var cusnamesearch = document.getElementById('detailsearchtext');
	cusnamesearch.value = selectbox.options[selectbox.selectedIndex].text;
	cusnamesearch.select();
	newproductentry();
	customerdetailstoform(selectbox.value);
	//$("#viewinvoicediv").hide();
	hideorshowpaymentdetailsdiv();
	showhidepaymentinfodiv();
	showhidepaymentdiv();
	enablebuttontype();
	sezfunc();
	customerdetailsmakereadonly();
}

//Function to select a customer from the list
function selectacustomer(input)
{
	var selectbox = document.getElementById('customerlist');
	if(input == "")
	{
		getcustomerlist1();
	}
	else
	{
		selectbox.options.length = 0;
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
				selectbox.options[selectbox.length] = new Option(splits[0], splits[1]);
				addedcount++;
				if(addedcount == 100)
					break;
			}
		}
	}
}

//functin to search customer
function customersearch(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 38)
		scrollcustomer('up');
	else if(KeyID == 40)
		scrollcustomer('down');
	else
	{
		var form = document.submitform;
		var input = document.getElementById('detailsearchtext').value;
		selectacustomer(input);
	}
}

//function to search customer using arrow keys
function scrollcustomer(type)
{
	var selectbox = document.getElementById('customerlist');
	var totalcus = selectbox.options.length;
	var selectedcus = selectbox.selectedIndex;
	if(type == 'up' && selectedcus != 0)
		selectbox.selectedIndex = selectedcus - 1;
	else if(type == 'down' && selectedcus != totalcus)
		selectbox.selectedIndex = selectedcus + 1;
	selectfromlist();
}


//Search customer by customer id
function searchbycustomerid(cusid)
{
	document.getElementById('form-error').innerHTML = '';
	var form = document.submitform;
	form.reset();
	var passdata = "switchtype=searchbycustomerid&customerid=" + encodeURIComponent(cusid) + "&dummy=" + Math.floor(Math.random()*100032680100);
	var queryString = "../ajax/invoicing.php";
	ajaxcall5 = $.ajax(
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
				var response = (ajaxresponse); 
				if(response['cusslno'] == '')
				{
					alert('Customer Not Available.');
				}
				else
				{
					$('#detailsearchtext').val(response['businessname']);
					selectacustomer(response['businessname']);
					$('#customerlist').val(response['cusslno']);
					$("#sez_enabled").val(response['sez_enabled']);
					$('#customer_gst').html(response['customer_gst']);
					$('#customer_hidden_id').val(response['customerid']);
					customerdetailstoform(response['cusslno']);
				}
			}
		}, 
		error: function(a,b)
		{
			$("#gridprocess").html(scripterror());
		}
	});		
}

//Search customer by Invoice no
function searchbyinvoiceno(invoiceno)
{
	$('#detailsearchtext').val('');
	document.getElementById('form-error').innerHTML = '';
	$('#submitform')[0].reset();;
	var passdata = "switchtype=searchbyinvoiceno&invoiceno=" + encodeURIComponent(invoiceno) + "&dummy=" + Math.floor(Math.random()*100032680100);
	$('#customerselectionprocess').html(getprocessingimage());
	var queryString = "../ajax/invoicing.php";
	ajaxcall5 = $.ajax(
		{
			type: "POST",url: queryString, data: passdata, cache: false,dataType: "json",
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
					var ajaxresponse = ajaxresponse;
					var response1  = ajaxresponse['grid'];
					if(ajaxresponse['errorcode'] == '1')
					{
						var response = response1.split('^*^');
						invoicearray = new Array();
						for( var i=0; i<response.length; i++)
						{
							invoicearray[i] = response[i];
						}
						getinvoicelistonsearch();
						$("#totalcount").html(invoicearray.length);
						$("#filter-form-error").html();
						$('#searchinvoiceno').val('');
						if(response.length > 1)
						{
							newproductentry();
							$('#lastslno').val('');
							resetdealerdisplaydetails();
							generateinvoicedetails('');
						}
					}
					else
					{
						alert('Invalid Invoice No');
					}
				}
			}, 
			error: function(a,b)
			{
				$("#gridprocess").html(scripterror());
			}
		});		
}

function getinvoicelistonsearch()
{	
	var form = $("#submitform" );
	var selectbox = $('#customerlist');
	var numberofcustomers = invoicearray.length;
	$('#detailsearchtext').focus();
	var actuallimit = 500;
	var limitlist = (numberofcustomers > actuallimit)?actuallimit:numberofcustomers;
	
	$('option', selectbox).remove();
	var options = selectbox.attr('options');
	
	for( var i=0; i<limitlist; i++)
	{
		var splits = invoicearray[i].split("^");
		options[options.length] = new Option(splits[0], splits[1]);
	}
	if(numberofcustomers == 1)
	{
		$('#detailsearchtext').val(splits[0])
		selectacustomer(splits[0])
		$('#customerlist').val(splits[1]);
		customerdetailstoform(splits[1]);
	}
}

function searchbyinvoicenoevent(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 13)
	{
		var input = document.getElementById('searchinvoiceno').value;
		searchbyinvoiceno(input);
	}
}

function searchbycustomeridevent(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 13)
	{
		var input = document.getElementById('searchcustomerid').value;
		searchbycustomerid(input);
	}
}

function resetdealerdisplaydetails()
{
	$('#displaycustomerid').html('');
	$('#displaycompanyname').html('');
	$('#displaycontactperson').html('');
	$('#displayaddress').html('');
	$('#displayphone').html('');
	$('#displaycell').html('');
	$('#displayemail').html('');
	$('#displayregion').html('');
	$('#displaybranch').html('');
	$('#displaytypeofcategory').html('Not Available');
	$('#displaytypeofcustomer').html('Not Available');
}

//Adding the selected products to productlist
function addselectedproduct(command)
{
	var field = $("#product");
	var field1 = $("#product2");
	if(command == 'software')
	{
		
		if(!field.val()){ alert('Please select a Item(Software)'); field.focus();return false;}
		else
		{
		     //Ajax to get product prices
		    productprice_actual++;
		    
			var passData = "switchtype=productsprices&productcode=" + encodeURIComponent(field.val()) + "&dummy=" + Math.floor(Math.random()*100032680100);
			//alert(passData);
			var queryString = "../ajax/invoicing.php";
			ajaxcall4 = $.ajax(
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
						var response  = ajaxresponse;
						//console.log(response);
						var singleusernew = response['singleusernew'];
						subgroup = response['subgroup'];
						if (typeof(singleusernew) == "undefined" && singleusernew == null ) {
							singleusernew = 0;
						}
						var singleuserupdation = response['singleuserupdation'];
						if (typeof(singleuserupdation) == "undefined" && singleuserupdation == null ) {
							singleuserupdation = 0;
						}
						var multiusernew = response['multiusernew'];
						if (typeof(multiusernew) == "undefined" && multiusernew == null ) {
							multiusernew = 0;
						}
						var multiuserpdation = response['multiuserpdation'];
						if (typeof(multiuserpdation) == "undefined" && multiuserpdation == null ) {
							multiuserpdation = 0;
						}
						//debugger;
						$('#productgroup').val(response['subgroup']);
						$('#productprice_actual'+productprice_actual).text(singleusernew);
						//$('#productamount'+productprice_actual).val(singleusernew);

						getproductgrid();
					}
				}, 
				error: function(a,b)
				{
					$("#form-error").html(scripterror());
				}
			});
		    //ends ajax
		}    
	}
	else
	{
		if(!field1.val()){ alert('Please select a Item(Other)'); field1.focus();return false;}
		else
		{
		    productprice_actual++;
		}
		getproductgrid();
	}
	
}

//added new function with old piece of code
function getproductgrid()
{
	var field = $("#product");
	$('#messagerow').remove();
	//$("#viewinvoicediv").hide();
	var rowcount = $('#seletedproductgrid tr').length;
	if(rowcount == 2)
		var k = 1;
	else
		var k = (rowcount-1);
	var form = $('#submitform');
	var productgrid ='';
	var editpurchasetypeid = 'editpurchasetype'+k;
	var editpurchasetypehtml = 'editpurchasetypehtml'+k;
	var editpurchasetypelinkid = 'editpurchasetypelinkid'+k;
	var editusagetypelinkid = 'editusagetypelinkid'+k;
	var purchasetypehidden = 'purchasetypehidden'+k;
	var editusagetypeid = 'editusagetype'+k;
	var editusagetypehtml = 'editusagetypehtml'+k;
	var usagetypehidden = 'usagetypehidden'+k;
	var productamount = 'productamount'+k;
	var productrowid = 'productrowid'+k;
	var productquantity = 'productquantity'+k;
	var productrowslnoid = 'productrowslnoid'+k;
	var productgroupid = 'productgroupid'+k
	var productname =$("#product option:selected").text();
	var productvalue =  $("#product").val();
	var removelinkid = 'removelinkid'+k;
	var itemtype = 'itemtype'+k;
	var productselectedhidden = 'productselectedhidden'+k;
	var productnamehidden = 'productnamehidden'+k;
	var quantitycheck = 'quantitycheck'+k;
	var purchasecheck = 'purchasecheck'+k;
	var hiddenpurchaseoflicense = 'hiddenpurchaseoflicense'+k;
	var yearcount = 'yearcount'+k;
	var incrementval = $("#incrementval").val(k);
	
	var editpurchasetypehtml_value = $.trim($("#editpurchasetypehtml").html());
	var editusagetypehtml_value = $.trim($("#editusagetypehtml").html());
	
	var productprice_actual_data = 'productprice_actual'+k;
	//var productprice_hidden_data = 'productprice_hidden'+k;
	
	var productleveldescription = 'productleveldescription'+k;
	var productgroup = $('#productgroup').val();
	if(field.val() != '')
	{
		if(productgroup == 'ESS')
			productgrid = '<tr id=\'' + productrowid + '\'><td nowrap="nowrap" class="td-border-grid" id=\'' + productrowslnoid + '\' >' + k + '</td><td nowrap="nowrap" class="td-border-grid">' + productname + ' <input name="productselectedhidden[]" class="swiftselect" id=\'' + productselectedhidden + '\'  type = "hidden" size="4" value = ' + productvalue + '><input type="hidden" name="productgroupcheck[]" class="swiftselect" id=\'' + productgroupid + '\' value = ' + productgroup + ' size="4"  /><input type="hidden" name="purchasecheck[]" class="swiftselect" id=\'' + purchasecheck + '\'  size="4"  /><input type="hidden" class="swiftselect" name="hiddenpurchaseoflicense[]" id=\'' + hiddenpurchaseoflicense + '\' size="4"  /><input type="hidden" class="swiftselect" name="yearcount[]" id=\'' + yearcount + '\' size="4"  /><input name="productnamehidden[]" class="swiftselect" id=\'' + productnamehidden + '\' type = "hidden" value = \'' + productname + '\' ><div style="padding-top:5px"><input type="text" value="Description (Optional)" style="width:200px;" autocomplete="off" maxlength="30" id=\'' + productleveldescription + '\' class="swifttext description-blur" onblur="descriptionblur(\'' + productleveldescription + '\')" onfocus="descriptionfocus(\'' + productleveldescription + '\')" name= "productleveldescriptiontype[]"></div></td><td valign="top"  class="td-border-grid"> <div align="center"><span id=\'' + editpurchasetypehtml + '\' align = "center">New </span><br/><span id= "editdiv' + productvalue + '" style="display:block;" align = "center"><a id="' + editpurchasetypelinkid + '" style="cursor:pointer" onclick = "editamountonpurchasetype(\'' + editpurchasetypehtml + '\',\'' + purchasetypehidden + '\',\'' + productselectedhidden + '\',\'' + k + '\',\'' + productnamehidden + '\');actualvalues(\'' + productvalue + '\',\'' + productprice_actual_data + '\',\'' + k + '\');" class = "r-text">( Change )</a> </span><input name="purchasetypehidden" class="swiftselect" id=\'' + purchasetypehidden + '\' type = "hidden" value = "new" ><input name="itemtype" class="swiftselect" id=\'' + itemtype + '\' type = "hidden" value = "product"></div></td><td valign="top" class="td-border-grid"> <div align="center"><span id=\'' + editusagetypehtml + '\' align = "center"></span><br/><span id= \'' + editusagetypeid + '\' style="display:block;" align = "center"> </span><input name="usagetypehidden" class="swiftselect" id=\'' + usagetypehidden + '\' type = "hidden" value = "" ></div></td><td nowrap="nowrap" class="td-border-grid" > <input type="hidden" name="productquantity" id=\''+ productquantity +'\' value="0" size="8" class="quantity_check_value" /><div align="center"><select name="quantitycheck" class="swiftselect" id=\'' + quantitycheck + '\' style="width:50px;" onchange ="calculatenormalprice(); getval(\'' + quantitycheck + '\',\'' + k + '\',\'' + editpurchasetypehtml + '\',\'' + purchasetypehidden + '\');" onkeypress="calculatenormalprice();"><option value="0">Select</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option></select></div></td><td nowrap="nowrap" id="productprice_actual'+ k + '" class="td-border-grid"></td><input type="hidden" class="swiftselect" size="4" name="productprice_hidden" id="productprice_hidden'+ k + '" value ="0" /><td nowrap="nowrap" class="td-border-grid" ><input name="productamount" type="text" class="swifttext" id=\'' + productamount + '\'  maxlength="6"  autocomplete="off" style="width:80px;text-align:right" onkeyup ="calculatenormalprice();" onchange ="calculatenormalprice();"/></td><td nowrap="nowrap" class="td-border-grid" ><div align ="center"><font color = "#FF0000"><strong><a id="' + removelinkid + '" onclick = "deleteproductrow(\'' + k + '\');" style="cursor:pointer;" >X</a></strong></font></div></td></tr>';
		else
			productgrid = '<tr id=\'' + productrowid + '\'><td nowrap="nowrap" class="td-border-grid" id=\'' + productrowslnoid + '\' >' + k + '</td><td nowrap="nowrap" class="td-border-grid">' + productname + ' <input name="productselectedhidden[]" class="swiftselect" id=\'' + productselectedhidden + '\'  type = "hidden" size="4" value = ' + productvalue + '><input type="hidden" name="productgroupcheck[]" class="swiftselect" id=\'' + productgroupid + '\' value = ' + productgroup + ' size="4"  /><input type="hidden" name="purchasecheck[]" class="swiftselect" id=\'' + purchasecheck + '\'  size="4"  /><input type="hidden" class="swiftselect" name="hiddenpurchaseoflicense[]" id=\'' + hiddenpurchaseoflicense + '\' size="4"  /><input type="hidden" class="swiftselect" name="yearcount[]" id=\'' + yearcount + '\' size="4"  /><input name="productnamehidden[]" class="swiftselect" id=\'' + productnamehidden + '\' type = "hidden" value = \'' + productname + '\' ><div style="padding-top:5px"><input type="text" value="Description (Optional)" style="width:200px;" autocomplete="off" maxlength="30" id=\'' + productleveldescription + '\' class="swifttext description-blur" onblur="descriptionblur(\'' + productleveldescription + '\')" onfocus="descriptionfocus(\'' + productleveldescription + '\')" name= "productleveldescriptiontype[]"></div></td><td valign="top"  class="td-border-grid"> <div align="center"><span id=\'' + editpurchasetypehtml + '\' align = "center">New </span><br/><span id= "editdiv' + productvalue + '" style="display:block;" align = "center"><a id="' + editpurchasetypelinkid + '" style="cursor:pointer" onclick = "editamountonpurchasetype(\'' + editpurchasetypehtml + '\',\'' + purchasetypehidden + '\',\'' + productselectedhidden + '\',\'' + k + '\',\'' + productnamehidden + '\');actualvalues(\'' + productvalue + '\',\'' + productprice_actual_data + '\',\'' + k + '\');" class = "r-text">( Change )</a> </span><input name="purchasetypehidden" class="swiftselect" id=\'' + purchasetypehidden + '\' type = "hidden" value = "new" ><input name="itemtype" class="swiftselect" id=\'' + itemtype + '\' type = "hidden" value = "product"></div></td><td valign="top" class="td-border-grid"> <div align="center"><span id=\'' + editusagetypehtml + '\' align = "center">Singleuser</span><br/><span id= \'' + editusagetypeid + '\' style="display:block;" align = "center"><a id="' + editusagetypelinkid + '" style="cursor:pointer" onclick = "editamountonusagetype(\'' + editusagetypehtml + '\',\'' + usagetypehidden + '\');actualvalue(\'' + productvalue + '\',\'' + productprice_actual_data + '\');" class="r-text">( Change )</a> </span><input name="usagetypehidden" class="swiftselect" id=\'' + usagetypehidden + '\' type = "hidden" value = "singleuser" ></div></td><td nowrap="nowrap" class="td-border-grid" > <input type="hidden" name="productquantity" id=\''+ productquantity +'\' value="0" size="8" class="quantity_check_value" /><div align="center"><select name="quantitycheck" class="swiftselect" id=\'' + quantitycheck + '\' style="width:50px;" onchange ="calculatenormalprice(); getval(\'' + quantitycheck + '\',\'' + k + '\',\'' + editpurchasetypehtml + '\',\'' + purchasetypehidden + '\');" onkeypress="calculatenormalprice();"><option value="0">Select</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option></select></div></td><td nowrap="nowrap" id="productprice_actual'+ k + '" class="td-border-grid"></td><input type="hidden" class="swiftselect" size="4" name="productprice_hidden" id="productprice_hidden'+ k + '" value ="0" /><td nowrap="nowrap" class="td-border-grid" ><input name="productamount" type="text" class="swifttext" id=\'' + productamount + '\'  maxlength="6"  autocomplete="off" style="width:80px;text-align:right" onkeyup ="calculatenormalprice();" onchange ="calculatenormalprice();"/></td><td nowrap="nowrap" class="td-border-grid" ><div align ="center"><font color = "#FF0000"><strong><a id="' + removelinkid + '" onclick = "deleteproductrow(\'' + k + '\');" style="cursor:pointer;" >X</a></strong></font></div></td></tr>';
	}
	else
	{
		var productname2 =$("#product2 option:selected").text();
		var productvalue =  $("#product2").val();
		var serviceselectedhidden = 'productselectedhidden'+k;
		var serviceselectedvaluehidden = 'productnamehidden'+k;
		var itemleveldescription = 'itemleveldescription'+k;
		productgrid = '<tr id=\'' + productrowid + '\'><td nowrap="nowrap" class="td-border-grid" id=\'' + productrowslnoid + '\' >' + k + '</td><td nowrap="nowrap" class="td-border-grid" colspan = "4">' + productname2 + ' <input name="itemtype" class="swiftselect" id=\'' + itemtype + '\' type = "hidden" value = "service"><input name="serviceselectedhidden[]" class="swiftselect" id=\'' + serviceselectedhidden + '\' type = "hidden" value =\'' + productname2 + '\'><input type="hidden" name="productquantity" id=\''+ productquantity +'\' value="0" size="8" class="quantity_check_value" /><input name="serviceselectedvaluehidden[]" class="swiftselect" id=\'' + serviceselectedvaluehidden + '\' type = "hidden" value =\'' + productvalue + '\'><div style="padding-top:5px"><input type="text"  name = "itemleveldescriptiontype[]" value="Description (Optional)" style="width:255px;" autocomplete="off" maxlength="30" id=\'' + itemleveldescription + '\'  class="swifttext description-blur" onblur="descriptionblur(\'' + itemleveldescription + '\')" onfocus="descriptionfocus(\'' + itemleveldescription + '\')" ></div></td><td nowrap="nowrap" class="td-border-grid">&nbsp;</td><td nowrap="nowrap" class="td-border-grid" ><input name="productamount" type="text" class="swifttext" id=\'' + productamount + '\'  maxlength="6"  autocomplete="off" style="width:80px;text-align:right" onkeyup ="calculatenormalprice();" onchange ="calculatenormalprice();"/></td><td nowrap="nowrap" class="td-border-grid" ><div align ="center"><font color = "#FF0000"><strong><a id="' + removelinkid + '" onclick = "deleteproductrow(\'' + k + '\');" style="cursor:pointer;" >X</a></strong></font></div></td></tr>';
	}
	$("#seletedproductgrid").append(productgrid);
	$('#resultdiv').show();//alert($('#seletedproductgrid tr').length);
	if($('#seletedproductgrid tr').length == 3)
	{
		//alert('here');
		adddescriptionrows();
	}
	$("#product" ).val('');
	$("#product2" ).val('');
	$("#onlineslno" ).val('');
	
	$("select option:contains('Discount %')").attr("disabled","");
	$("select option:contains('Discount amount')").attr("disabled","");
	
		//Added on 13th Feb 2018 for Parameh Sir Requirement 
	var checkforgoadealers = $("#dealeridhidden").val();
	
	//console.log(checkforgoadealers);
	if(checkforgoadealers == '1102' || checkforgoadealers == '1396' || checkforgoadealers == '1103'       || checkforgoadealers == '1644' || checkforgoadealers == '1104'){
	    $("input[name*='productamount']").attr('readonly','readonly');
	}
	//Ends
}

//delete product from list
function deleteproductrow(id)
{
	$('#productrowid'+id).remove();
	var rowcount = $('#seletedproductgrid tr').length;
	rowcount = (rowcount-2);
	countval = 0;
	var incrementval = $("#incrementval").val();
	var incrementvalue = incrementval- 1;
	$("#incrementval").val(incrementvalue);
	
	for(i=1;i<=(rowcount+1);i++)
	{
		var editpurchasetypeid = '#editpurchasetype'+i;
		var editpurchasetypehtml = '#editpurchasetypehtml'+i;
		var editpurchasetypelinkid = '#editpurchasetypelinkid'+i;
		var editusagetypelinkid = '#editusagetypelinkid'+i;
		var purchasetypehidden = '#purchasetypehidden'+i;
		var editusagetypeid = '#editusagetype'+i;
		var editusagetypehtml = '#editusagetypehtml'+i;
		var usagetypehidden = '#usagetypehidden'+i;
		var productamount = '#productamount'+i;
		var productrowid = '#productrowid'+i;
		var productquantity = '#productquantity'+i;
		var productrowslnoid = '#productrowslnoid'+i;
		var removelinkid = '#removelinkid'+i;
		var productselectedhidden = '#productselectedhidden'+i;
		var productnamehidden = '#productnamehidden'+i;
		var productleveldescription = '#productleveldescription' + i;
		var quantitycheck = 'quantitycheck'+i;
		var purchasecheck = 'purchasecheck'+i;
		var hiddenpurchaseoflicense = 'hiddenpurchaseoflicense'+i;
		var yearcount = 'yearcount'+i;
		var productgroup = '#productgroup'+i;
		var productgroupid = '#productgroupid'+i;
		if($(productrowid).length > 0)
		{
			countval++;
			if($(editpurchasetypeid).length > 0)
				$("#editpurchasetype"+ i).attr("id","editpurchasetype"+ countval);
			if($(editpurchasetypehtml).length > 0)
				$("#editpurchasetypehtml"+ i).attr("id","editpurchasetypehtml"+ countval);
			if($(purchasetypehidden).length > 0)
				$("#purchasetypehidden"+ i).attr("id","purchasetypehidden"+ countval);
			if($(editusagetypeid).length > 0)
				$("#editusagetype"+ i).attr("id","editusagetype"+ countval);
			if($(editusagetypehtml).length > 0)
				$("#editusagetypehtml"+ i).attr("id","editusagetypehtml"+ countval);
			if($(usagetypehidden).length > 0)
				$("#usagetypehidden"+ i).attr("id","usagetypehidden"+ countval);
			if($(productgroupid).length > 0)
				$("#productgroupid"+ i).attr("id","productgroupid"+ countval);
			$("#productamount"+ i).attr("id","productamount"+ countval);
			$("#productrowid"+ i).attr("id","productrowid"+ countval);
			$("#productnamehidden"+ i).attr("id","productnamehidden"+ countval);
			$("#productrowslnoid"+ i).attr("id","productrowslnoid"+ countval);
			$("#productselectedhidden"+ i).attr("id","productselectedhidden"+ countval);
			$("#itemtype"+ i).attr("id","itemtype"+ countval);
			$("#productnamehidden"+ i).attr("id","productnamehidden"+ countval);
			document.getElementById("productleveldescription" + i).onfocus = new Function('descriptionfocus("productleveldescription' + countval + '")') ;
			document.getElementById("productleveldescription" + i).onblur = new Function('descriptionblur("productleveldescription' + countval + '")') ;
			$("#productleveldescription"+ i).attr("id","productleveldescription"+ countval);
			$("#productrowslnoid"+ countval).html(countval);
			
			if($("#productquantity"+ i).length > 0)
				$("#productquantity"+ i).attr("id","productquantity"+ countval);
				
			if($("#quantitycheck"+ i).length > 0)
                        {
				$("#quantitycheck"+ i).attr("id","quantitycheck"+ countval);
			        document.getElementById("quantitycheck" + countval).onchange = new Function('getval("quantitycheck' + countval + '" , "' + countval + '")');
                        }	
			if($("#purchasecheck"+ i).length > 0)
				$("#purchasecheck"+ i).attr("id","purchasecheck"+ countval);
				
			if($("#hiddenpurchaseoflicense"+ i).length > 0)
				$("#hiddenpurchaseoflicense"+ i).attr("id","hiddenpurchaseoflicense"+ countval);
				
			if($("#yearcount"+ i).length > 0)
				$("#yearcount"+ i).attr("id","yearcount"+ countval);

			if($("#editpurchasetypelinkid"+ i).length > 0)
			{
				$("#editpurchasetypelinkid"+ i).attr("id","editpurchasetypelinkid"+ countval);
				document.getElementById("editpurchasetypelinkid" + countval).onclick = new Function('editamountonpurchasetype("editpurchasetypehtml' + countval + '" , "purchasetypehidden' + countval + '" , "productselectedhidden' + countval + '" , "' + countval + '" , "productnamehidden' + countval + '")') ;
			}
			
				/*alert("quantitycheck" + countval);
				$("#quantitycheck"+ i).attr("id","quantitycheck"+ countval);
				document.getElementById("quantitycheck" + countval).onchange = new Function('getval("quantitycheck' + countval + '" , "valueval' + countval + '")') ;*/
				/*alert("quantitycheck"+ i);
				if($("#quantitycheck"+ i ).val().length > 0)
			    {*/
					//alert("subtracted");
					
			//}
				
				
			
			if($("#editusagetypelinkid"+ i).length > 0)
			{
				$("#editusagetypelinkid"+ i).attr("id","editusagetypelinkid"+ countval);
				document.getElementById("editusagetypelinkid" + countval).onclick = new Function('editamountonusagetype("editusagetypehtml' + countval + '" , "usagetypehidden' + countval + '")') ;
			}
			$("#removelinkid"+ i).attr("id","removelinkid"+ countval);
			document.getElementById("removelinkid" + countval).onclick = new Function('deleteproductrow("' + countval + '")');
		}
	}
	var productarray=document.getElementsByName("productselectedhidden[]");
	var productvalues = '';
	for(i=0;i<productarray.length;i++)
	{
		productvalues += productarray[i].value  + '#';
	}
	var productlist = productvalues.substring(0,(productvalues.length-1));//alert(rowcount);
	if(rowcount == 0)
	{
		 newproductentry();
	}
	calculatenormalprice();
}


function customerdetailsdisplayandhide()
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


//To add description rows
function adddescriptionrows()
{
	//alert('1');
	var rowcount = $('#adddescriptionrows tr').length;
	if(rowcount == 5)
	{
		$('#adddescriptionrowdiv').hide();
		//var row = '<tr id="removedescriptionrow"><td width="19%"><select name="descriptiontype" class="swiftselect" id="descriptiontype" onchange="enableordisabledescriptionfields(\'' + rowcount + '\');calculatenormalprice();" onkeypress="calculatenormalprice();"><option value="" selected="selected">None</option><option value="add" >Add</option><option value="less" >Less</option><option value="percentage" >Discount %</option><option value="amount" >Discount amount</option></select></td><td width="35%"><input name=description type="text" class="swifttext-mandatory1" id=description   maxlength="150"  autocomplete="off" style="width:250px;" disabled="disabled" value="Additional Charges" /><td width="17%"><div align="left"><input name=descriptionamount type="text" class="swifttext-mandatory1" id=descriptionamount   maxlength="10"  autocomplete="off" style="width:80px;text-align:right" disabled="disabled" onkeyup="calculatenormalprice();" onchange ="calculatenormalprice();"/></div><span id="discountsection'+rowcount+'" style="display:none;float:right;font-weight:bold;width:188px;margin-top: -17px;">Discount value<input id="dicountvalue" name="dicountvalue"  value="" disabled style="width:80px;float: right;display:none" /></span></td> <td width="9%"><div align ="center" id="removerowdiv" style="display:none;" ><font color = "#FF0000"><strong><a onclick ="removedescriptionrows();calculatenormalprice()" style="cursor:pointer;">X</a></strong></font></div></td></tr>';
		var row = '<tr id="removedescriptionrow"><td width="19%"><select name="descriptiontype" class="swiftselect" id="descriptiontype" onchange="enableordisabledescriptionfields(\'' + rowcount + '\');calculatenormalprice();" onkeypress="calculatenormalprice();"><option value="" selected="selected">None</option><option value="less" >Less</option><option value="percentage" >Discount %</option><option value="amount" >Discount amount</option></select></td><td width="35%"><input name=description type="text" class="swifttext-mandatory1" id=description   maxlength="150"  autocomplete="off" style="width:250px;" disabled="disabled" value="Additional Charges" /><td width="17%"><div align="left"><input name=descriptionamount type="text" class="swifttext-mandatory1" id=descriptionamount   maxlength="10"  autocomplete="off" style="width:80px;text-align:right" disabled="disabled" onkeyup="calculatenormalprice();" onchange ="calculatenormalprice();"/></div><span id="discountsection'+rowcount+'" style="display:none;float:right;font-weight:bold;width:188px;margin-top: -17px;">Discount value<input id="dicountvalue" name="dicountvalue"  value="" disabled style="width:80px;float: right;display:none" /></span></td> <td width="9%"><div align ="center" id="removerowdiv" style="display:none;" ><font color = "#FF0000"><strong><a onclick ="removedescriptionrows();calculatenormalprice()" style="cursor:pointer;">X</a></strong></font></div></td></tr>';
	}
	else
	{
		$('#adddescriptionrowdiv').show();
		// var row = '<tr id="removedescriptionrow"><td width="19%"><select name="descriptiontype" class="swiftselect" id="descriptiontype" onchange="enableordisabledescriptionfields(\'' + rowcount + '\');calculatenormalprice();" onkeypress="calculatenormalprice();"><option value="" selected="selected">None</option><option value="add" >Add</option><option value="less" >Less</option><option value="percentage" >Discount %</option><option value="amount" >Discount amount</option></select></td><td width="35%"><input name=description type="text" class="swifttext-mandatory1" id=description   maxlength="150"  autocomplete="off" style="width:250px;" disabled="disabled" value="Additional Charges" /><td width="17%"><div align="left"><input name=descriptionamount type="text" class="swifttext-mandatory1" id=descriptionamount   maxlength="10"  autocomplete="off" style="width:80px;text-align:right" disabled="disabled" onkeyup="calculatenormalprice();" onchange ="calculatenormalprice();"/></div><span id="discountsection'+rowcount+'" style="display:none;float:right;font-weight:bold;width:188px;margin-top: -17px;">Discount value<input id="dicountvalue" name="dicountvalue"  value="" disabled style="width:80px;float: right;display:none" /></span></td> <td width="9%"><div align ="center" id="removerowdiv" style="display:none;" ><font color = "#FF0000"><strong><a onclick ="removedescriptionrows();calculatenormalprice()" style="cursor:pointer;">X</a></strong></font></div></td></tr>';
		var row = '<tr id="removedescriptionrow"><td width="19%"><select name="descriptiontype" class="swiftselect" id="descriptiontype" onchange="enableordisabledescriptionfields(\'' + rowcount + '\');calculatenormalprice();" onkeypress="calculatenormalprice();"><option value="" selected="selected">None</option><option value="less" >Less</option><option value="percentage" >Discount %</option><option value="amount" >Discount amount</option></select></td><td width="35%"><input name=description type="text" class="swifttext-mandatory1" id=description   maxlength="150"  autocomplete="off" style="width:250px;" disabled="disabled" value="Additional Charges" /><td width="17%"><div align="left"><input name=descriptionamount type="text" class="swifttext-mandatory1" id=descriptionamount   maxlength="10"  autocomplete="off" style="width:80px;text-align:right" disabled="disabled" onkeyup="calculatenormalprice();" onchange ="calculatenormalprice();"/></div><span id="discountsection'+rowcount+'" style="display:none;float:right;font-weight:bold;width:188px;margin-top: -17px;">Discount value<input id="dicountvalue" name="dicountvalue"  value="" disabled style="width:80px;float: right;display:none" /></span></td> <td width="9%"><div align ="center" id="removerowdiv" style="display:none;" ><font color = "#FF0000"><strong><a onclick ="removedescriptionrows();calculatenormalprice()" style="cursor:pointer;">X</a></strong></font></div></td></tr>';
	}
	//alert(row);
	$("#adddescriptionrows").append(row);
	//alert('test');
	$("#descriptiontype").attr("id","descriptiontype"+ rowcount)
	$("#description").attr("id","description"+ rowcount);
	$("#descriptionamount").attr("id","descriptionamount"+ rowcount);
	$("#removerowdiv").attr("id","removerowdiv"+ rowcount);
	$("#removedescriptionrow").attr("id","removedescriptionrow"+ rowcount);
	for(i=0,j=0; i<rowcount; i++,j++)
	{	
		if((rowcount-1) == j)
			$('#removerowdiv'+(j+1)).show();
		else
			$('#removerowdiv'+(j+1)).hide();
	}
	
	var showdiscounts = 'false';
	var hidediscounts = 'false';
	enabledisablediscounts(rowcount,showdiscounts,hidediscounts);	
	
}


//Remove description row
function removedescriptionrows()
{
	//alert('1');
	var rowcount = $('#adddescriptionrows tr').length;
	if(rowcount == 2)
	{
		$('#removedescriptionrow'+(rowcount-1)).remove();
		$('#removerowdiv'+(rowcount-2)).hide();
	}
	else
	{
		$('#removedescriptionrow'+(rowcount-1)).remove();
		$('#removerowdiv'+(rowcount-2)).show();	
		$('#adddescriptionrowdiv').show();
	}
	calculatenormalprice();
}

function zeroquantity(k)
{
	$("#quantitycheck"+k).val(0).attr("selected", "selected");
	 $(function () {
			$("#productquantity"+k).val('0');
		});
}

function getval(sel,k,purchasetypehtml,purchasetypehidden) {
	   
	   var valuepur = $('#'+sel).val();
	   var totalpurchasecount = $('#hiddenpurchaseoflicense'+k).val();
	   var currentpurchasecount =$('#purchasecheck'+k).val();
	   var yearcountval = $('#yearcount'+k).val();
	   var purchase = $('#purchasetypehidden'+k).val();
	   var license = $('#productnamehidden'+k).val();
	   var incrementvalue = $('#incrementval').val();
	   
/* console.log('valuepur : '+valuepur+' totalpurchasecount : '+totalpurchasecount+' currentpurchasecount : '+currentpurchasecount+' yearcountval : '+yearcountval+
	   ' purchase : '+purchase+' license : '+license+' incrementvalue : '+incrementvalue); */
	if($('#productgroupid'+k).val()!= "ESS") {
		for (var z = 1; z < k; z++) {
			if (z >= 1) {
				//alert("1" + $('#productnamehidden'+z).val() + "=" + $('#productnamehidden'+k).val());
				// alert("2" + $('#purchasetypehidden'+z).val());
				if (($('#productnamehidden' + z).val() == $('#productnamehidden' + k).val()) &&
					($('#purchasetypehidden' + z).val() == "updation" || $('#purchasetypehidden' + k).val() == "updation")) {
					//alert($('#hiddenproductoflicense'+z).val());
					// alert("total=1");
					var totalcount = 1;
					//alert(totalcount);
				}
				if (totalcount != 1) {
					if (($('#productnamehidden' + z).val() != $('#productnamehidden' + k).val()) &&
						($('#purchasetypehidden' + k).val() == "updation")) {
						//alert("total=0");
						var totalcount = 0;
					}
				}
			}

		}
		var totcount = totalcount;
		var valj = 0;
		for (var i = 1, j = k; i <= k; i++) {
			if (i > 1 && purchase == "new" && yearcountval == "") {
				//alert(i);
				// alert("i is greater than 1.");
				document.getElementById("productquantity" + k).value = valuepur;
			}


			if (i < k) {
				if (totcount == 1) {
					//alert("totalcount" + totcount);
					if (($('#productnamehidden' + i).val() == $('#productnamehidden' + j).val()) &&
						($('#purchasetypehidden' + k).val() == "updation")) {

						document.getElementById("productquantity" + k).value = valuepur;
						var x = 0;
						$("input[class=quantity_check_value]").each(function (f) {
							var id = "#productquantity" + (f + 1);
							var type_id = "#purchasetypehidden" + (f + 1);
							var type = $(type_id).val();
							var product_id = "#productnamehidden" + (f + 1);
							var product = $(product_id).val();
							y = parseInt($(id).val());
							if (type == "updation" && product == $('#productnamehidden' + j).val()) {
								x = x + y;
							}

						});

						if (currentpurchasecount == 0) {
							if (x <= totalpurchasecount) {
								//alert("match3");
								document.getElementById("productquantity" + k).value = valuepur;
							} else if (x > totalpurchasecount) {
								if (valj == 0) {
									var valj = i;
								}
								zeroquantity(k);
								if (valj == i && valj != 0) {
									alert("You cannot select more than " + totalpurchasecount + " updation card");
									$('#productamount' + k).val('');
								}
							}
						} else if (currentpurchasecount > 0) {
							var subtractedval1 = totalpurchasecount - currentpurchasecount;
							if (x <= subtractedval1) {
								//alert("match8");
								document.getElementById("productquantity" + k).value = valuepur;
							} else if (x > subtractedval1 && subtractedval1 > 0) {
								//alert("match9");
								if (valj == 0) {
									var valj = i;
								}
								zeroquantity(k);
								if (valj == i && valj != 0) {
									alert("You cannot select more than " + subtractedval1 + " updation card");
									$('#productamount' + k).val('');
								}
							} else if (subtractedval1 <= 0) {
								alert("You have 0 updation card");
								document.getElementById(purchasetypehtml).innerHTML = 'New';
								document.getElementById(purchasetypehidden).value = 'new';
								$('#purchasecheck' + k).val('');
								$('#yearcount' + k).val('');
								$('#hiddenpurchaseoflicense' + k).val('');
								$('#productamount' + k).val('');
								zeroquantity(k);
							}
						}

					}
				} else if (totcount == 0) {

					//alert('inside total count');
					if (($('#productnamehidden' + i).val() != $('#productnamehidden' + j).val()) &&
						($('#purchasetypehidden' + j).val() == "updation"))
					//&& ($('#purchasetypehidden'+i).val()!="new")
					//&& ($('#purchasetypehidden'+i).val()!= "updation")
					{
						for (var h = k; h <= incrementvalue; h++) {
							if ((license == $('#productnamehidden' + h).val()) &&
								(purchase == $('#purchasetypehidden' + h).val())) {
								if (h > k) {

									$(function () {
										$("#productquantity" + h).val('0');
									});
									$("#quantitycheck" + h).val(0).attr("selected", "selected");
								}
							}
						}


						if (currentpurchasecount == 0) {
							if (valuepur <= totalpurchasecount) {
								//alert("no match2");
								document.getElementById("productquantity" + k).value = valuepur;
							} else if (valuepur > totalpurchasecount) {
								//alert("no match3");
								if (valj == 0) {
									var valj = i;
								}
								zeroquantity(k);
								if (valj == i && valj != 0) {
									alert("You can select only " + totalpurchasecount + " updation card");
									$('#productamount' + k).val('');
								}
							}
						} else if (currentpurchasecount > 0) {
							var subtractedval2 = totalpurchasecount - currentpurchasecount;
							if (valuepur <= subtractedval2) {
								//alert("no match9");
								document.getElementById("productquantity" + k).value = valuepur;
							} else if (valuepur > subtractedval2 && subtractedval2 > 0) {
								//alert("no match10");
								if (valj == 0) {
									var valj = i;
								}
								zeroquantity(k);
								if (valj == i && valj != 0) {
									alert("You have " + subtractedval2 + " updation card");
									$('#productamount' + k).val('');
								}
							} else if (subtractedval2 <= 0) {
								alert("You have 0 updation card.");
								document.getElementById(purchasetypehtml).innerHTML = 'New';
								document.getElementById(purchasetypehidden).value = 'new';
								$('#purchasecheck' + k).val('');
								$('#yearcount' + k).val('');
								$('#hiddenpurchaseoflicense' + k).val('');
								$('#productamount' + k).val('');
								zeroquantity(k);
							}
						}
					}


				}
			} else if (i == 1) {
				if (purchase == "new" && yearcountval == "") {
					document.getElementById("productquantity" + k).value = $('#' + sel).val();
				} else {
					for (var m = i; m <= incrementvalue; m++) {
						if ((license == $('#productnamehidden' + m).val()) &&
							(purchase == $('#purchasetypehidden' + m).val())) {
							if (m > k) {
								$(function () {
									//alert("check");
									$("#productquantity" + m).val('0');
								});
								$("#quantitycheck" + m).val(0).attr("selected", "selected");
							}
						}
					}


					if (currentpurchasecount == 0) {
						if (valuepur <= totalpurchasecount) {
							document.getElementById("productquantity" + k).value = valuepur;
						} else if (valuepur > totalpurchasecount) {
							//check whether customer has past two or one year cards are available or not
							alert("You can select only " + totalpurchasecount + " updation card");
							$('#productamount' + k).val('');
							zeroquantity(k);
						}
					} else if (currentpurchasecount > 0) {
						var subtractedval = totalpurchasecount - currentpurchasecount;

						if (valuepur <= subtractedval) {
							document.getElementById("productquantity" + k).value = valuepur;
						} else if (valuepur > subtractedval && subtractedval > 0) {
							//check whether customer has past two or one year are available or not
							alert("You have " + subtractedval + " updation card");
							$('#productamount' + k).val('');
							zeroquantity(k);
						} else if (subtractedval <= 0) {
							alert("You have 0 updation cards");
							document.getElementById(purchasetypehtml).innerHTML = 'New';
							document.getElementById(purchasetypehidden).value = 'new';
							$('#purchasecheck' + k).val('');
							$('#yearcount' + k).val('');
							$('#hiddenpurchaseoflicense' + k).val('');
							$('#productamount' + k).val('');
							zeroquantity(k);
						}
					}
				}
			}
		}
	}
	else
	{
		document.getElementById("productquantity" + k).value = valuepur;
	}
   
}

//Change purchase type
function editamountonpurchasetype(purchasetypehtml,purchasetypehidden,productselectedhidden,k,productnamehidden)
{
	var passData = "switchtype=newupdationchange&customerid=" + encodeURIComponent($('#lastslno').val()) +
	 "&productname=" + encodeURIComponent($('#productnamehidden'+k).val()) + 
	"&dummy=" + Math.floor(Math.random()*1000782200000);
	$('#processing').show();
	$('#processing').html(getprocessingimage());
	//alert(passData)
	var queryString = "../ajax/invoicing.php";
	//var queryString = "../ajax/invoicingnewupdationchanges.php";
	//error.html(getprocessingimage());
	//alert(k);
	
	ajaxcall1 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType:'json',
		success: function(ajaxresponse,status)
		{	
			var response = (ajaxresponse);
			
			console.log('currentyearcard : '+response['currentyearcard']);
			console.log('totalcards : '+response['totalcards']);
			console.log('lasttwoyearcount : '+response['lasttwoyearcount']);
			
			//2013-14 year product name
			//var oldproductname = $('#hiddenproductoflicense'+k).val(response['oldproductname']);
			//var hidproduct = $('#hiddenproductoflicense'+k).val();
			
			 //current year card check
			var currentyearcard = $('#purchasecheck'+k).val(response['currentyearcard']);
			//var currentyearcard0 =$('#purchasecheck'+k).val(); 
			
			//card count of 2013-14 and between current year and 2013-14 
			var totalcards = $('#hiddenpurchaseoflicense'+k).val(response['totalcards']);
			var totalcount = $('#hiddenpurchaseoflicense'+k).val();
			//alert(totalcount);
			
			//year count
			var yearcount = $('#yearcount'+k).val(response['lasttwoyearcount']);
			var lasttwoyearcount = $('#yearcount'+k).val();
			//alert(lasttwoyearcount);
			 
/*console.log('currentyearcard : '+currentyearcard+' totalcards : '+totalcards+' totalcount : '+totalcount+' yearcount : '+yearcount+
	   ' lasttwoyearcount : '+lasttwoyearcount);*/		
			
			/*if(lasttwoyearcount == "" || (totalcount == 0 &&  lasttwoyearcount == ""))*/
			if(lasttwoyearcount == 0)
			{
				if(document.getElementById(purchasetypehidden).value == 'new' || (document.getElementById(purchasetypehidden).
				value == 'updation'))
				{
					$('#processing').hide();
					document.getElementById(purchasetypehtml).innerHTML = 'New';
					document.getElementById(purchasetypehidden).value = 'new';
					$('#purchasecheck'+k).val('');
					$('#yearcount'+k).val('');
					$('#productamount'+k).val(''); 
					$('#hiddenpurchaseoflicense'+k).val('');
					alert("Updation card is not available now");
				}
			}
			else if(lasttwoyearcount == 1)
			{
				console.log('purchasetypehidden : '+document.getElementById(purchasetypehidden).value);
				if(document.getElementById(purchasetypehidden).value == 'new')
				{
					zeroquantity(k);
					$('#processing').hide();
					document.getElementById(purchasetypehtml).innerHTML = 'Updation';
					document.getElementById(purchasetypehidden).value = 'updation';
				}
				else if(document.getElementById(purchasetypehidden).value == 'updation')
				{
					//alert("second");
					$('#processing').hide();
					$('#purchasecheck'+k).val('');
					$('#yearcount'+k).val(''); 
					$('#hiddenpurchaseoflicense'+k).val('');
					$('#productamount'+k).val('');
					zeroquantity(k);
					document.getElementById(purchasetypehtml).innerHTML = 'New';
					document.getElementById(purchasetypehidden).value = 'new';
				}
			}
		 
		}
	});
	
}
/*function editamountonpurchasetype(purchasetypehtml,purchasetypehidden)
{
	if(document.getElementById(purchasetypehidden).value == 'new')
	{
		document.getElementById(purchasetypehtml).innerHTML = 'Updation';
		document.getElementById(purchasetypehidden).value = 'updation';
	}
	else
	{
		document.getElementById(purchasetypehtml).innerHTML = 'New';
		document.getElementById(purchasetypehidden).value = 'new';
	}
}*/


//Change Usagetype
function editamountonusagetype(usagetypehtml,usagetypehidden)
{
	if(document.getElementById(usagetypehidden).value == 'singleuser')
	{
		document.getElementById(usagetypehtml).innerHTML = 'Multiuser';
		document.getElementById(usagetypehidden).value = 'multiuser';
	}
	else if(document.getElementById(usagetypehidden).value == 'multiuser')
	{
		document.getElementById(usagetypehtml).innerHTML = 'Add License';
		document.getElementById(usagetypehidden).value = 'addlic';
	}
	else if(document.getElementById(usagetypehidden).value == 'addlic')
	{
		document.getElementById(usagetypehtml).innerHTML = 'Singleuser';
		document.getElementById(usagetypehidden).value = 'singleuser';
	}
}


//Function to calculate the amount
function calculatepricing(command,validate)
{
	//alert(command);
	var form = document.getElementById('submitform');
	var rowcount = $('#adddescriptionrows tr').length;
	var productcount = ($('#seletedproductgrid tr').length)-2;
	var count = document.getElementById('productcounthidden').value;
	var offeramount = document.getElementById('offeramount').value;

	var pricingtype = getradiovalue(form.pricing);
	$('#form-error').html(getprocessingimage());
	$('#displayofferremarksdiv').show();
	if(pricingtype == command)
	{
		var purchasearrray = new Array();
		var purchasevalues = new Array();
		var usagearrray = new Array();
		var usagevalues = new Array();
		var productamountarrray = new Array();
		var productamountvalues = new Array();
		var productquantityarrray = new Array();
		var productquantityvalues = new Array();
		var descriptiontypearrray = new Array();
		var descriptiontypevalues = new Array();
		var descriptionarrray = new Array();
		var descriptionvalues = new Array();
		var descriptionamountarrray = new Array();
		var descriptionamountvalues = new Array();
		for(i=0,j=1; i<productcount,j<=(productcount); i++,j++)
		{	

			if($("#itemtype"+ j).val() == 'product')
			{
				purchasearrray[i] = 'purchasetypehidden' + j;
				purchasevalues[i] = document.getElementById(purchasearrray[i]).value;
				usagearrray[i] = 'usagetypehidden' + j;
				usagevalues[i] = document.getElementById(usagearrray[i]).value;
				productquantityarrray[i] = 'productquantity' + j;
				productquantityvalues[i] = document.getElementById(productquantityarrray[i]).value;
			}
			productamountarrray[i] = 'productamount' + j;
			productamountvalues[i] = document.getElementById(productamountarrray[i]).value;


			if(purchasearrray == '')
			{
				 $('#form-error').html(''); alert('Please enter the amount'); return false;
			}
		}
		for(i=0,j=0; i<rowcount,j<rowcount; i++,j++)
		{	
			descriptiontypearrray[i] = 'descriptiontype' + j;
			descriptiontypevalues[i] = document.getElementById(descriptiontypearrray[i]).value;
			descriptionarrray[i] = 'description' + j;
			descriptionvalues[i] = document.getElementById(descriptionarrray[i]).value;
			descriptionamountarrray[i] = 'descriptionamount' + j;
			descriptionamountvalues[i] = document.getElementById(descriptionamountarrray[i]).value;
			if(descriptiontypevalues[i] != '')
			{
				var field = document.getElementById(descriptionarrray[i]);
				if(!field.value) {
					$('#form-error').html('');
					alert('Please Enter the Description...')
					field.focus();  return false;}
				var field = document.getElementById(descriptionamountarrray[i]);
				if(!field.value) {
					$('#form-error').html('');
					alert('Please Enter the amount...');
					field.focus();  return false;}
				if(field.value)	{ if(!validateamountfield(field.value)) {
					$('#form-error').html('');
					alert('Amount is not Valid.');
					field.focus(); return false; } }
			}
		}
		if(pricingtype == 'offer')
		{
			var field = $('#offeramount');
			if(!field.val())
			{
				$('#form-error').html('');
				alert('Please Enter the offer amount.');
				field.focus(); return false;
			}
			if(field.val())	{ if(!validateamountfield(field.val())) {
				$('#form-error').html('');
				alert('Amount is not Valid.')
				field.focus(); return false; } }
			var field = $('#offerremarks');
			if(!field.val())
			{
				$('#form-error').html('');

				alert('Please Enter the offer Description.');
				field.focus(); return false;
			}
		}
		else if(pricingtype == 'inclusivetax')
		{
		    
		    $("[name*=descriptionamount]").each(function (index, value) { 
              $(this).val('');
            });
            $("[name*=description]").each(function (index, value) { 
              $(this).val('');
            });
            $("[name*=descriptiontype]").val($("[name*=descriptiontype] option:first").val());
            $("[class*=dicountvalue]").hide();
            $("[class*=dicountvalue]").val('');
            $("[id*=discountsection]").hide();
            
            
			var field = $('#inclusivetaxamount');

			if(!field.val())
			{
				$('#form-error').html('');
				alert('Please Enter the amount.');
				field.focus(); return false;
			}
			if(field.val())	{ if(!validateamountfield(field.val())) {
				$('#form-error').html('');
				alert('Amount is not Valid.');
				field.focus(); return false; } }
		}
		if((pricingtype == 'offer') && ($('#offerremarks').val() != ''))
		{
			$('#displayofferremarksdiv').html('Offer: '+$('#offerremarks').val());
			$('#displayofferremarksdiv').addClass('messagebox');
			$('#removeoffermegdiv').show();
			$('#offerremarkshidden').val($('#displayofferremarksdiv').html());
		}
		else
		{
			removeofferremarksdiv();
		}
		$('#pricingdiv').hide();
		var productarray=document.getElementsByName("productselectedhidden[]");

		var productvalues = '';
		for(i=0;i<productarray.length;i++)
		{
			productvalues += productarray[i].value  + '#';

		}
		var productlist = productvalues.substring(0,(productvalues.length-1));//alert(descriptionamountvalues);
		var field = $('#seztax:checked').val();
		if(field != 'on') var seztaxtype = 'no'; else seztaxtype = 'yes';
		
		var field = $('#invoicedated:checked').val();
		if(field != 'on') var invoicedated = 'no'; else invoicedated = 'yes';
		
		var inclusivetaxamount = document.getElementById('inclusivetaxamount').value;

		
		var passData = "switchtype=calculateamount&pricingtype=" +(pricingtype) + "&purchasevalues=" + (purchasevalues) + "&usagevalues=" + (usagevalues) + "&productamountvalues=" + (productamountvalues) + "&productquantityvalues=" + (productquantityvalues)  + "&descriptiontypevalues=" + (descriptiontypevalues) + "&descriptionvalues=" + (descriptionvalues) + "&descriptionamountvalues=" + (descriptionamountvalues)+ "&offeramount=" + (offeramount)+ "&inclusivetaxamount=" + (inclusivetaxamount)+ "&selectedcookievalue=" + (productlist)+ "&seztaxtype=" + (seztaxtype)+ "&invoicedated=" + (invoicedated)+ "&dummy=" + Math.floor(Math.random()*10054300000);
		
		queryString = "../ajax/invoicing.php";
		ajaxcall5 = $.ajax(
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
					$('#form-error').html('');
					var response = ajaxresponse.split('^');
					/*if(response[0] == 1)
					{
						document.getElementById("totalamount").value = response[1];
						document.getElementById("sericetaxamount").value = response[2];
						document.getElementById("netamount").value = response[3];
						$("#productcounthidden").val(response[5]);
					}*/
					//else if(response[0] == 2)
					//alert(response);
					
					if(response[0] == 2)
					{
						//console.log(productamountarray);
						productamountarray = response[1].split('*');
						productarraycount = productamountarray.length;
						k= 0;
						j= 0;
						for(i=0;i<productcount;i++)
						{
							k++;
							if($("#itemtype"+ k).val() == 'product')
							{
								productamountfield = 'productamount'+k;
								document.getElementById(productamountfield).value = productamountarray[j];
								j++;
							}
						}
						//document.getElementById("totalamount").value = response[2];
						//document.getElementById("sericetaxamount").value = response[3];
						//document.getElementById("sbtaxamount").value = response[7];
						//document.getElementById("netamount").value = response[4];
						$("#productcounthidden").val(response[2]);
						calculatenormalprice();
					}
					else
					{
						document.getElementById('form-error').innerHTML = scripterror();
					}
				}
			}, 
			error: function(a,b)
			{
				$("#form-error").html(scripterror());
			}
		});
	}
	else
		alert('Please select a Pricing type');
}


function validatepricingfield()
{
	var pricingtype = $("input[name='pricing']:checked").val();
	if(pricingtype == 'offer')
	{
		$('#offeramtdiv').show();
		$('#inclusivetaxamtdiv').hide();
		$('#offerdescriptiondiv').show();
		$('#displayapplylink').html('&nbsp;<a onclick="calculatepricing(\'offer\');" class="r-text">Apply &#8250;&#8250;</a>');
	}
	else if(pricingtype == 'inclusivetax')
	{
		$('#inclusivetaxamtdiv').show();
		$('#offeramtdiv').hide();
		$('#offerdescriptiondiv').hide();
		$('#displayapplylink').html('&nbsp;&nbsp;<a onclick="calculatepricing(\'inclusivetax\');" class="r-text">Apply &#8250;&#8250;</a>');
	}
	else if(pricingtype == 'default')
	{
		$('#inclusivetaxamtdiv').hide();
		$('#offeramtdiv').hide();
		$('#offerdescriptiondiv').hide();
		$('#displayapplylink').html('Make product prices to their Default price &nbsp;<a onclick="calculatepricing(\'default\');" class="r-text">Apply &#8250;&#8250;</a>');
	}
	else
	{
		$('#inclusivetaxamtdiv').hide();
		$('#offeramtdiv').hide();
		$('#offerdescriptiondiv').hide();
		$('#displayapplylink').html('Make product price Empty &nbsp;<a onclick="makefieldsempty();" class="r-text">Apply &#8250;&#8250;</a');
		//emptyfields();
	}
	$('#offeramount').val('');
	$('#inclusivetaxamount').val('');
	$('#offerremarks').val('');
	removeofferremarksdiv();
}

function makefieldsempty()
{
	var form = $('#submitform');
	var rowcount = $('#adddescriptionrows tr').length;
	var productcount = ($('#seletedproductgrid tr').length)-2;
	var count = document.getElementById('productcounthidden').value;;
	var productamountarrray = new Array();
	
	document.getElementById('netamount').value  = '';
	document.getElementById('igsttaxamount').value  = '';
	document.getElementById('cgsttaxamount').value  = '';
	document.getElementById('sgsttaxamount').value  = '';
	document.getElementById('dicountvalue').value  = '';
	//document.getElementById('dicountvalue').html  = '';
	document.getElementById('totalamount').value = '';
	
	$("[name*=productamount]").each(function (index, value){ 
	        $(this).val('');
    });
    $("[name*=description]").each(function (index, value){ 
	        $(this).val('');
    });
    $("[name*=descriptionamount]").each(function (index, value){ 
	        $(this).val('');
    });
/*	for(i=0,j=1; i<productcount,j<=productcount; i++,j++)
	{	
		document.getElementById('sericetaxamount').value = ''; 
		document.getElementById('kk_cess').value = ''; 
		document.getElementById('sbtaxamount').value = ''; 
		document.getElementById('descriptionamount'+j).value  = '';
		document.getElementById('productamount'+j).value = '';
		document.getElementById('descriptionamount'+i).value  = '';
	}*/	
}

function enableordisabledescriptionfields(rowcount)
{
	var i = rowcount;
	var descriptiontype = '#descriptiontype'+i;
	var description = 'description'+i;
	var descriptionamount = 'descriptionamount'+i;

  
	if($(descriptiontype).val() != '')
	{
	
	    var description_type = $(descriptiontype).val();
	    if($(descriptiontype).val() == 'percentage' || $(descriptiontype).val() == 'amount')
	    {
	        //$('#discountsection').css('display','');
	        //$('#adddescriptionrowdiv').hide();
	        $('#'+description).val('Discount');
	        $('#'+description).css({'visibility':'hidden','width':'0px'});
	        if($(descriptiontype).val() == 'percentage')
	        {
	            
	            $('#'+description).closest('td').width('0%'); 
                $('#'+descriptionamount).closest('td').css('width','70%'); 
                $('#'+descriptionamount).attr('maxlength', '2');
	            $('#discountsection'+i).show();
	            $('#discountsection'+i+' #dicountvalue').show();
	            $('#discountsection'+i+' #dicountvalue').attr('disabled','false');
	            $('#discountsection'+i+' #dicountvalue').attr('readonly','true');
	            
	        }
	        else
	        {
	            $('#discountsection'+i).hide();
	            $('#discountsection'+i+' #dicountvalue').hide();
	            $('#discountsection'+i+' #dicountvalue').attr('disabled','true');
	            $('#discountsection'+i+' #dicountvalue').attr('readonly','false');
	            $('#'+descriptionamount).attr('maxlength', '30');
	        }
	      
	    }
	    else
	    {
	        $('#'+description).val('');
	        //$('#adddescriptionrowdiv').show();
	        //$('#discountsection').css('display','none');
	        $('#'+description).css({'visibility':'','width':'250px'});
            $('#'+description).closest('td').css('width','35% ');
            $('#'+descriptionamount).closest('td').css('width', '17%');
	        $('#'+descriptionamount).attr('maxlength', '10');
	        /*$('#discountsection').hide();
	        $('#dicountvalue').hide();*/
	        $('#discountsection'+i).hide();
            $('#discountsection'+i+' #dicountvalue').hide();
            $('#discountsection'+i+' #dicountvalue').attr('disabled','true');
            $('#discountsection'+i+' #dicountvalue').attr('readonly','false');
	    }
	    
		//$('#'+description).val('');
		$('#'+description).attr("disabled", false); 
		$('#'+descriptionamount).attr("disabled", false); 
		description1 = '#description'+(rowcount-1);
		$('#'+description).removeClass('swifttext-mandatory1');
		$('#'+description).addClass('swifttext');
		$('#'+descriptionamount).removeClass('swifttext-mandatory1');
		$('#'+descriptionamount).addClass('swifttext');
		$('#'+descriptionamount).val('');
	}
	else
	{
	    $('#'+description).css({'visibility':'','width':'250px'});
        $('#'+description).closest('td').css('width','35% ');
        $('#'+descriptionamount).closest('td').css('width', '17%');
	    
		$('#'+description).val('Additional Charges');
		$('#'+description).attr("disabled", true); 
		$('#'+descriptionamount).attr("disabled", true); 
		$('#'+description).addClass('swifttext-mandatory1');
		$('#'+description).removeClass('swifttext');
		$('#'+descriptionamount).addClass('swifttext-mandatory1');
		$('#'+descriptionamount).removeClass('swifttext');
		$('#'+descriptionamount).val('');
		$('#discountsection'+i+' #dicountvalue').val('');
		/*$('#discountsection').css('display','none');
		$('#dicountvalue').hide();*/
		//$('#adddescriptionrowdiv').hide();
		$('#discountsection'+i).hide();
        $('#discountsection'+i+' #dicountvalue').hide();
        $('#discountsection'+i+' #dicountvalue').attr('disabled','true');
        $('#discountsection'+i+' #dicountvalue').attr('readonly','false');
	}
	
	var showdiscounts = 'false';
	var hidediscounts = 'false';
	enabledisablediscounts(rowcount,showdiscounts,hidediscounts);
}

function enabledisablediscounts(rowcount,showdiscounts,hidediscounts)
{
    var i = rowcount;
	var descriptiontype = '#descriptiontype'+i;
	var description = 'description'+i;
	var descriptionamount = 'descriptionamount'+i;

        for(var j = 0; j<= rowcount; j++)
	    {
	        if($('#descriptiontype'+j).val() == 'percentage' || $('#descriptiontype'+j).val() == 'amount')
	        {
	            hidediscounts = 'true';
	        }
	        if(hidediscounts == 'false '&& $('#descriptiontype'+j).val() == '')
	        {
	            hidediscounts = 'false';
	        }
	    } 
	    //debugger;
/*	    if((hidediscounts == 'true' && showdiscounts == 'false') || (hidediscounts == 'true' && showdiscounts == 'true'))
	    {
	       $("select option:contains('Discount %')").attr("disabled","disabled");
	       $("select option:contains('Discount amount')").attr("disabled","disabled");
	       
	    }
	    if(hidediscounts == 'false' && showdiscounts == 'true')
	    {
	       $("select option:contains('Discount %')").attr("disabled","");
	       $("select option:contains('Discount amount')").attr("disabled","");
	       
	    }*/
	    //debugger;
	    if(hidediscounts == 'true')
	    {
	       $("select option:contains('Discount %')").attr("disabled","disabled");
	       $("select option:contains('Discount amount')").attr("disabled","disabled");
	    }
	    if(hidediscounts == 'false')
	    {
	        $("select option:contains('Discount %')").attr("disabled","");
	        $("select option:contains('Discount amount')").attr("disabled","");
	    }
}

function enableamountorper()
{
    var discount_type = $('#discounttype').val();
    if(discount_type == "percentage")
    {
        $("#discountamount").val('');
        $("#discountamount").attr('maxlength','2');
        $("#discountamount").removeClass('swifttext-mandatory1');
        $("#discountamount").addClass('swifttext');
        $("#discountamount").attr('disabled', false);
    }
    else if(discount_type == "amount")
    {
        $("#discountamount").val('');
        $("#discountamount").removeClass('swifttext-mandatory1');
        $("#discountamount").attr('maxlength','20');
        $("#discountamount").addClass('swifttext');
        $("#discountamount").attr('disabled', false);
    }
    else
    {
        $("#discountamount").val('');
        $("#discountamount").attr('maxlength','20');
        $("#discountamount").attr('disabled', true);
        $("#discountamount").removeClass('swifttext');
        $("#discountamount").addClass('swifttext-mandatory1');
   
    }
    
}


//New Product Entry
function newproductentry()
{
	$('#adddescriptionrowdiv').hide();
	$('#resultdiv').hide();
	$('#pricingdiv').hide();
	$('#adddescriptionrows tr').remove();
	$("#submitform" )[0].reset();
	$("#productcounthidden" ).val('');
	$("#form-error" ).html('');
	//$("#lastslno" ).val('');
	$("#offerremarkshidden" ).val('');
	$('#seletedproductgrid tr').remove();
	//$("#seletedproductgrid").append('<tr class="tr-grid-header"><td width="9%" nowrap="nowrap" class="td-border-grid">Sl No</td><td width="27%" nowrap="nowrap" class="td-border-grid">Product</td><td width="15%" nowrap="nowrap" class="td-border-grid">Purchase Type</td><td width="13%" nowrap="nowrap" class="td-border-grid">Usage Type</td><td width="10%" nowrap="nowrap" class="td-border-grid">Quantity</td><td width="15%" nowrap="nowrap" class="td-border-grid">Unit Price</td><td width="11%" nowrap="nowrap" class="td-border-grid">Remove</td></tr><tr><td colspan="7" nowrap = "nowrap" class="td-border-grid" id="messagerow"><div align="center" style="height:15px;"><strong><font color="#FF0000">Select a Item First</font></strong></div></td></tr>');
	$("#seletedproductgrid").append('<tr class="tr-grid-header"><td width="9%" nowrap="nowrap" class="td-border-grid">Sl No</td><td width="27%" nowrap="nowrap" class="td-border-grid">Product</td><td width="15%" nowrap="nowrap" class="td-border-grid">Buy Type</td><td width="13%" nowrap="nowrap" class="td-border-grid">Usage Type</td><td width="10%" nowrap="nowrap" class="td-border-grid">Qty</td><td width="15%" nowrap="nowrap" class="td-border-grid">Real</td><td width="15%" nowrap="nowrap" class="td-border-grid">Unit Price</td><td width="11%" nowrap="nowrap" class="td-border-grid">Remove</td></tr><tr><td colspan="8" nowrap = "nowrap" class="td-border-grid" id="messagerow"><div align="center" style="height:15px;"><strong><font color="#FF0000">Select a Item First</font></strong></div></td></tr>');
	//hideorshowremarksdiv();
	removeofferremarksdiv();
	enableproceedbutton();
	hideorshowpaymentdetailsdiv();
	showhidepaymentinfodiv();
	showhidepaymentdiv();
	//clearcustomerdetails();
	resetdealername();
	if($("#dealer").length > 0)
	{
		disableitemselection();
	}
}


function newinvoiceentry()
{
	var confirmation = confirm("Are you sure you want to clear the existing invoice?");
	if(confirmation)
	{
		newproductentry();
	}
}


function enableformelemnts()
{
	var count = document.submitform.elements.length;
	for (i=0; i<count; i++) 
	{
		var element = document.submitform.elements[i]; 
		//alert (element.id);
		//if(element.id != 'invoicedated')
			element.disabled=false; 
	}
}


function disableformelemnts_invoicing()
{
	var count = document.submitform.elements.length;
	for (i=0; i<count; i++) 
	{
		var element = document.submitform.elements[i]; 
		element.disabled=true; 
		$('#myfileuploadimage1').removeAttr('onclick');
	}
}


function pricingdivhideshow()
{
	if($('#pricingdiv').is(':visible'))
		$('#pricingdiv').hide();
	else
	{
		$('#pricingdiv').show();
		validatepricingfield();
	}
}


function calculatenormalprice(command)
{
    
	var form = $('#submitform');
	var rowcount = $('#adddescriptionrows tr').length;
	var productcount = ($('#seletedproductgrid tr').length)-2;
	var count = document.getElementById('productcounthidden').value;
	
	var igstrates = document.getElementById('igstrate').value;
	var cgstrates = document.getElementById('cgstrate').value;
	var sgstrates = document.getElementById('sgstrate').value;
	
	
	var descriptiontypearrray = new Array();
	var descriptiontypevalues = new Array();
	var descriptionarrray = new Array();
	var descriptionvalues = new Array();
	var descriptionamountarrray = new Array();
	var descriptionamountvalues = new Array();
	var purchasearrray = new Array();
	var purchasevalues = new Array();
	var usagearrray = new Array();
	var usagevalues = new Array();
	var productamountarrray = new Array();
	var productamountvalues = new Array();
	var productquantityarrray = new Array();
	var productquantityvalues = new Array();
	var productamount = 0;
	
	var totalamount = 0;
	var igst_tax_amount = 0;
	var cgst_tax_amount = 0;
	var sgst_tax_amount = 0;
	var servicetax = 0;
	var netamount = 0;
	var discount_amount_value = 0;
	
  	
	
	for(i=0,j=1; i<productcount,j<=productcount; i++,j++)
	{	
		if($('#usagetypehidden' + j).length > 0)
		{
			purchasearrray[i] = 'purchasetypehidden' + j;
			purchasevalues[i] = document.getElementById(purchasearrray[i]).value;
			usagearrray[i] = 'usagetypehidden' + j;
			usagevalues[i] = document.getElementById(usagearrray[i]).value;
			productquantityarrray[i] = 'quantitycheck' + j;
			productquantityvalues[i] = document.getElementById(productquantityarrray[i]).value;
		}

		productamountarrray[i] = 'productamount' + j;
		productamountvalues[i] = document.getElementById(productamountarrray[i]).value;
		//alert(productamountarrray);
		//alert(productamountvalues);
		var field = document.getElementById(productamountarrray[i]);
		if(command == 'validate')
		{
			if(!field.value)
			{ 
				alert('Please Enter the amount');
				field.focus(); return false;
			}
			if(field.value){ if(!validateamountfield(field.value))
			{
				alert('Amount is not Valid.');
				field.focus(); return false; }
			}
		}
		else
		{
			if($('#quantitycheck' + j).length > 0)
				productamount += (productamountvalues[i])*1*productquantityvalues[i];
			else
				productamount += (productamountvalues[i])*1;
		}
	}
	totalamount = productamount;
	if(isNaN(totalamount))
	{
		$('#totalamount').val('0');		
		//$('#sericetaxamount').val('0');
		//$('#kk_cess').val('0');		
		//$('#sbtaxamount').val('0');	
		$('#netamount').val('0');
		$('#igsttaxamount').val('0');
		$('#cgsttaxamount').val('0');
		$('#sgsttaxamount').val('0');
	}
	if(productamount == 0)
	{
		$('#totalamount').val('0');		
		//$('#sericetaxamount').val('0');
		//$('#kk_cess').val('0');		
		//$('#sbtaxamount').val('0');	
		$('#netamount').val('0');	
		$('#paymentamount').val('0');
		$('#igsttaxamount').val('0');
		$('#cgsttaxamount').val('0');
		$('#sgsttaxamount').val('0');
	}
	else
	{
	    var calculate_discount = 0;
	    var row_value = 0;
	    
	    for(i=0,j=0; i<rowcount,j<rowcount; i++,j++)
	    {	
	   
		descriptiontypearrray[i] = 'descriptiontype' + j;
		descriptiontypevalues[i] = document.getElementById(descriptiontypearrray[i]).value;
	    //alert(descriptiontypevalues[i]);
		descriptionarrray[i] = 'description' + j;
		descriptionvalues[i] = document.getElementById(descriptionarrray[i]).value;
		
        if(descriptiontypearrray[i] != "percentage")
        {
           descriptionamountarrray[i] = 'descriptionamount' + j;  
        }
         descriptionamountvalues[i] = document.getElementById(descriptionamountarrray[i]).value;
         
		   if(isNaN(document.getElementById(descriptionamountarrray[i]).value))
		     	document.getElementById(descriptionamountarrray[i]).value = '0';
		    	
			
		
		if(command == 'validate')
		{
			if(descriptiontypevalues[i] == 'add' || descriptiontypevalues[i] == 'less' || descriptiontypevalues[i] == 'amount'  )
			{
				var field = document.getElementById(descriptionarrray[i]);
				if(!field.value)
				{
					alert('Please Enter the Description...');
					field.focus(); 
					return false;
				}
				var field = document.getElementById(descriptionamountarrray[i]);
				if(!field.value)
				{ 
					alert('Please Enter the amount...');
					field.focus(); 	return false;
				}
				if(field.value)	{ if(!validateamountfield(field.value)) 
				{
					alert('Amount is not Valid.');
					field.focus(); return false; }
				}
			}
		    if(descriptiontypevalues[i] == 'percentage' )
		    {
		        var field = document.getElementById(descriptionarrray[i]);
		        if(!field.value)
				{ 
					alert('Please Enter the discount percentage...');
					field.focus(); 	return false;
				}
				if(field.value)	{ if(!validateamountfield(field.value)) 
				{
					alert('Percentage is not Valid.');
					field.focus(); return false; }
				}
				
				
		    }
		
	
	     } 
	      if(descriptiontypevalues[i] == 'percentage' )
		  {
		       calculate_discount++;
		       row_value = i;
		        
		  }     
	   }

	   
	    
		if(isNaN(totalamount))
			totalamount = 0;
		if(descriptionamountvalues != '')
		{
			amount = getdescriptionamount(descriptionamountvalues,descriptiontypevalues);
		}	
		else
			amount = 0;
			
		//alert(amount);
		if(isNaN(amount))
			amount = 0;
		totalamount = totalamount + ((amount)*1) ;
	   
	    // To  be moved
    	if(calculate_discount == 1)
		{
		   var discount_percentage = document.getElementById(descriptionamountarrray[row_value]).value;
		   
			var discount_value = (discount_percentage/100)*totalamount;
			totalamount = totalamount - ((discount_percentage/100)*totalamount);
			
			var discount_value = (discount_value).toFixed(2);
			$('#discountsection'+row_value+' #dicountvalue').val(discount_value);
		}
	// Ends	
	 
		var field = $('#invoicedated:checked').val();
		if(field != 'on') var invoicedated = 'no'; else invoicedated = 'yes';
		
		var currentTime = new Date();
		var month = currentTime.getMonth();
		var day = currentTime.getDate();
		var year = currentTime.getFullYear();

		var currentdate1 = day + "-" + month + "-" + year; 
		var currentdate2 = new Date(currentdate1.split('-')[2],currentdate1.split('-')[1],currentdate1.split('-')[0]);
		var expiredate1 = '1-5-2015';
		var expiredate2 = new Date(expiredate1.split('-')[2],expiredate1.split('-')[1],expiredate1.split('-')[0]);
		var expiredate3 = '4-3-2015';
		var expiredate4 = new Date(expiredate3.split('-')[2],expiredate3.split('-')[1],expiredate3.split('-')[0]);
		
		//Edited by Divya - 13/11/2015
		
		var expiredate5 = '15-10-2015';
		var expiredate6 = new Date(expiredate5.split('-')[2],expiredate5.split('-')[1],expiredate5.split('-')[0]);
		//alert(expiredate6);
		//alert(currentdate2);
		
		var gstdate = "01-07-2017";
		var gstdate_expire = new Date(gstdate.split('-')[2],gstdate.split('-')[1],gstdate.split('-')[0]);
		
		if($('#seztax').is(':checked'))
		{
			//servicetax = Math.round(totalamount * 0);
			//sbtax = Math.round(totalamount * 0);
			//kk_cess = Math.round(totalamount * 0);
			cgst_tax_amount = 0.00;
			sgst_tax_amount = 0.00;
			igst_tax_amount = 0.00;
			//igst_tax_amount = Math.round(totalamount * (igstrates/100));
		}
		else
		{
		    //servicetax = Math.round(totalamount * 0);
			//sbtax = Math.round(totalamount * 0);
			//kk_cess = Math.round(totalamount * 0);
			if(checksection == "igst")
			{
			    igst_tax_amount = (totalamount * (igstrates/100)).toFixed(2);
				sgst_tax_amount = '0.00';
				cgst_tax_amount = '0.00';
			}
			else
			{
		      sgst_tax_amount = (totalamount * (cgstrates/100)).toFixed(2);
		      cgst_tax_amount = (totalamount * (sgstrates/100)).toFixed(2);
			  igst_tax_amount = '0.00';
			}
		} 
		
		if ($('#igsttaxamount').is(':visible'))
		{
		    
           $('#igsttaxamount').val(igst_tax_amount);
           $('#cgsttaxamount').val(cgst_tax_amount);
           $('#sgsttaxamount').val(sgst_tax_amount);
           netamount = parseFloat(totalamount) + parseFloat(igst_tax_amount);
        }
        if ($('#cgsttaxamount').is(':visible')) 
        {
            
           $('#cgsttaxamount').val(cgst_tax_amount);
           $('#sgsttaxamount').val(sgst_tax_amount);
           $('#igsttaxamount').val(igst_tax_amount);
           netamount = parseFloat(totalamount) + parseFloat(cgst_tax_amount) + parseFloat(sgst_tax_amount);
        }

        //netamount = totalamount + igst_tax_amount + cgst_tax_amount + sgst_tax_amount;
		totalamount = (totalamount).toFixed(2);
		$('#totalamount').val(totalamount);	
		$('#discountamount').val(discount_amount_value);	
		//$('#sericetaxamount').val(0);
		//$('#kk_cess').val(0);
		//$('#sbtaxamount').val(0);	
		//$('#gsttaxamount').val(gst_amount);
		netamount = Math.round(netamount);
		netamount = parseFloat(netamount).toFixed(2);
		//netamount = Math.round(netamount).toFixed(2);
		$('#netamount').val(netamount);	
		if($('#partialpayment').is(':checked') == false)
		$('#paymentamount').val(netamount);
	}  
	
}

function emptyfields()
{
	var rowcount = $('#adddescriptionrows tr').length;
	var count = document.getElementById('productcounthidden').value;
	var descriptiontypearrray = new Array();
	var descriptiontypevalues = new Array();
	var descriptionarrray = new Array();
	var descriptionvalues = new Array();
	var descriptionamountarrray = new Array();
	var descriptionamountvalues = new Array();
	var purchasearrray = new Array();
	var purchasevalues = new Array();
	var usagearrray = new Array();
	var usagevalues = new Array();
	var productamountarrray = new Array();
	var productamountvalues = new Array();
	var productquantityarrray = new Array();
	var productquantityvalues = new Array();
	$('#totalamount').val('0');
	$('#sbtaxamount').val('0');
	$('#sericetaxamount').val('0');
	$('#kk_cess').val('0');		
	$('#netamount').val('0');	
	for(i=0,j=0; i<rowcount; i++,j++)
	{	
		descriptiontypearrray[i] = 'descriptiontype' + j;
		descriptionarrray[i] = 'description' + j;
		descriptionamountarrray[i] = 'descriptionamount' + j;
	}
	for(i=0,j=1; i<count; i++,j++)
	{	
		purchasearrray[i] = 'purchasetypehidden' + j;
		usagearrray[i] = 'usagetypehidden' + j;
		productamountarrray[i] = 'productamount' + j;
		document.getElementById(productamountarrray[i]).value = '0';
		productquantityarrray[i] = 'productquantity' + j;
	}
	enableordisabledescriptionfields();
}

function getdescriptionamount(descriptionamountvalues,descriptiontypevalues)
{
	amount = 0;
	descriptioncount = descriptionamountvalues.length;
	for(i=0;i<descriptioncount; i++)
	{
		if(descriptiontypevalues[i] == 'add')
			amount = (amount) + descriptionamountvalues[i] *1;
		else
		{
		    if(descriptiontypevalues[i] == 'less' || descriptiontypevalues[i] == "amount")
		    {
    			amount = (amount) - descriptionamountvalues[i]*1;
    			if(isNaN(amount))
    				amount =0;
		    }		
		}
	}
	return amount;
}


function removeofferremarksdiv()
{
	$('#displayofferremarksdiv').html('');
	$('#displayofferremarksdiv').hide();
	$('#removeoffermegdiv').hide();
	$('#offerremarkshidden').val('');
	$('#displayofferremarksdiv').removeClass('messagebox');
}


function generateinvoicedetails(startlimit)
{
	if($('#lastslno').val() == '')
		return false;
	else
	{
		var form = $('#submitform');
		$('#invoicedetailsgridc1').show();
		$('#detailsdiv').hide();
		var passdata = "switchtype=invoicedetailsgrid&lastslno="+ encodeURIComponent($('#lastslno').val()) + "&startlimit=" + encodeURIComponent(startlimit);
		var queryString = "../ajax/invoicing.php";
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
							$('#invoicedetailsgridwb1').html("Total Count :  " + response['fetchresultcount']);
							$('#invoicedetailsgridc1_1').html(response['grid']);
							$('#invoicedetailsgridc1link').html(response['linkgrid']);
							enableproceedbutton();
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
}

//Function for "show more records" link - to get registration records
function getmoreinvoicedetails(id,startlimit,slno,showtype)
{
	var form = $('#submitform');
	$('#lastslno').val(id);
	var passdata = "switchtype=invoicedetailsgrid&lastslno="+ encodeURIComponent($('#lastslno').val()) + "&startlimit=" + startlimit+ "&slno=" + slno + "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);
	var queryString = "../ajax/invoicing.php";
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
					var response = ajaxresponse;
					if(response['errorcode'] == '1')
					{
						$('#invoicedetailsgridwb1').html("Total Count :  " + response['fetchresultcount']);
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


//Function to view the bill in pdf format----------------------------------------------------------------
function viewinvoice(slno)
{
	if(slno != '')
		$('#onlineslno').val(slno);
	
	var form = $('#submitform');	
	if($('#onlineslno').val() == '')
	{
		$('#productselectionprocess').html(errormessage('Please select a Customer.')); return false;
	}
	else
	{
		$('#submitform').attr("action", "../ajax/viewinvoicepdf.php") ;
		$('#submitform').attr( 'target', '_blank' );
		$('#submitform').submit();
	}
}

//Function to make the display as block as well as none-------------------------------------------------------------

function displayDiv2(elementid,imgname)
{
	if($('#'+ elementid).is(':visible'))
	{
		$("#displaycustomerdetails").hide();
		if($('#'+ imgname))
			$('#'+ imgname).attr('src',"../images/plus.jpg");
	}
	else
	{
		$("#displaycustomerdetails").show();
		if($('#'+ imgname))
			$('#'+ imgname).attr('src',"../images/minus.jpg");
	}
}

function disableproceedbutton()
{
	$('#proceed').attr("disabled", true); 
	$('#proceed').removeClass('swiftchoicebutton');	
	$('#proceed').addClass('swiftchoicebuttondisabled');	
}

function enableproceedbutton()
{
	$('#proceed').attr("disabled", false); 
	$('#proceed').removeClass('swiftchoicebuttondisabled');	
	$('#proceed').addClass('swiftchoicebutton');	
}




function showhidepaymentdiv()
{
	var paymenttype = $("input[name='modeofpayment']:checked").val();
	if(paymenttype == 'others')
	{
		$("#paymentmadenow").show();
		$("#paymentdiv").show();
	}
	else if(paymenttype == 'upi')
	{
		$("#paymentmadenow").hide();
		$("#paymentdiv").hide();
	}
	else
	{
		$("#paymentmadenow").hide();
		$("#paymentdiv").hide();
	}
}

function showhidepaymentinfodiv()
{
	var paymenttype = $("input[name='paymentmodeselect']:checked").val();
	if(paymenttype == 'paymentmadenow')
	{
		$("#paymentmadenow").show();
		$("#willpaylater").hide();
	}
	else
	{
		$("#willpaylater").show();
		$("#paymentmadenow").hide();
	}
}


function hideorshowpaymentdetailsdiv()
{
	var paymenttype = $("input[name='paymentmode']:checked").val();
	
	if(paymenttype == 'chequeordd')
	{
		$("#paymentdetailsdiv2").show();
		$("#paymentdetailsdiv1").hide();
		$("#cashwarningmessage").hide();
		$("#bankdetailstip").hide();
	}
	else if(paymenttype == 'onlinetransfer')
	{
		$("#paymentdetailsdiv1").show();
		$("#paymentdetailsdiv2").hide();
		$("#cashwarningmessage").hide();
		$("#bankdetailstip").show();
	}
	else
	{
		$("#cashwarningmessage").show();
		$("#paymentdetailsdiv1").show();
		$("#paymentdetailsdiv2").hide();
		$("#bankdetailstip").hide();
	}
}


function disableorenablepaymentamount()
{
	if($('#partialpayment').is(':checked') == true)
	{
		$('#paymentamount').attr('readonly', false);
		$('#paymentamount').val('');
		$('#paymentamount').addClass('swifttext-mandatory');
		$('#paymentamount').removeClass('swifttext-readonly');
	}
	else
	{
		$('#paymentamount').attr('readonly', true);	
		$('#paymentamount').val($('#netamount').val());
		$('#paymentamount').removeClass('swifttext-mandatory');
		$('#paymentamount').addClass('swifttext-readonly');
	}
}

function resendinvoice(invoiceno,slno)
{
	var form = $('#submitform');
	if(invoiceno == '')
	{
		$('#form-error').html(errormessage('Select a Invoice first.')); return false;
	}
	else
	{
		var confirmation = confirm('Are you sure you want to resend the Invoice?');
		if(confirmation)
		{
			var passdata = "switchtype=resendinvoice&invoiceno=" + encodeURIComponent(invoiceno) + "&dummy=" + Math.floor(Math.random()*10054300000);
			var process = 'resendprocess'+slno; 
			var resendmail = 'resendemail'+slno;
			$('#'+process).show();
			$('#'+resendmail).hide();
			$('#'+process).html(getprocessingimage());	
			queryString = "../ajax/invoicing.php";
			ajaxcall10 = $.ajax(
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
							
							var data = response[0];
							data = data.substr(1);
							
							if(data == 1)
							{
								
								$('#'+process).hide();
								$('#'+resendmail).show();
								alert('Invoice sent successfully to the selected Customer');
							}
							else
								$('#form-error').html(errormessage('Connection Failed.'));
									}
					}, 
					error: function(a,b)
					{
						$("#form-error").html(scripterror());
					}
				});		
		}
	else
		return false;
	}
}
function proceedpayment()
{
	var form = document.submitexistform;	
	var mode1=document.submitexistform.paymode[0].checked;
	var mode2=document.submitexistform.paymode[1].checked;
	var res=document.getElementById('lslnop').value;
	var mode='';
	if(mode1==true && mode2==false)
	{
		//mode="credit";		
		/*form.action = '../makepayment/pay.php';
		form.submit();*/
		
		$('#submitform').attr("action", "http://imax.relyonsoft.com/dealer/makepayment/pay.php") ;
		$('#submitform').attr( 'target', '_blank' );
		$('#submitform').submit();
		$(".modalOverlay").remove();         
		unloadPopupBox();
	}
	else if(mode1==false && mode2==true)
	{
		//mode="internet";
		/*form.action = '../makepayment/paycitrus.php';
		form.submit();*/
		
		$('#submitform').attr("action", "http://imax.relyonsoft.com/dealer/makepayment/paycitrus.php") ;
		$('#submitform').attr( 'target', '_blank' );
		$('#submitform').submit();
		$(".modalOverlay").remove();         
		unloadPopupBox();
	}
	else
	{
		//alert("Select mode of payment");
		$('#err').html('Select mode of payment');
		document.getElementById("paymode").focus();
	}
}

function paynow(onlineslno)
{    
    $('#onlineslno').val(onlineslno);
	$('#submitform').attr("action", "http://imax.relyonsoft.com/dealer/makepayment/paymode.php") ;
	$('#submitform').attr( 'target', '_blank' );
	$('#submitform').submit();
	    
	/*	loadPopupBox();
	$("body").append('<div class="modalOverlay">'); 
	$('#lslnop').val(onlineslno);
	$('#onlineslno').val(onlineslno);
	$('#onlineslno').val(onlineslno);
	$('#submitform').attr("action", "http://imax.relyonsoft.com/dealer/makepayment/pay.php") ;
	$('#submitform').attr( 'target', '_blank' );
	$('#submitform').submit();*/
}
function unloadPopupBox() {    // TO Unload the Popupbox
	var msg='&nbsp;';
	$('#err').html(msg);
	$('#invoicedetailsgrid').fadeOut("slow");
	/*$('body').css({ // this is just for style        
		"opacity": "1"  
	});*/ 
}    

function loadPopupBox() {    // To Load the Popupbox
	$('#invoicedetailsgrid').fadeIn("slow");
	/*$('body').css({ // this is just for style
		"opacity": "0.3"  
	});*/         
} 
function proceedforpurchase(getMessage)
{
	  var form = document.getElementById('submitform');
	  //if(getMessage!= 'gstconfirm')
	  //	var confirmation = confirm("Please read the invoice contents above, once more. Invoice, once generated, cannot be edited or cancelled, subject to approval. To read it once more, click CANCEL. To proceed, click OK.");
	  //if(confirmation || getMessage == 'gstconfirm')
	  //{
		  $('#proceedprocessingdiv').html(getprocessingimage());
		  var rowcount = $('#adddescriptionrows tr').length;
		  var count = $('#seletedproductgrid tr').length;
		  count = (count-2);
		  var pricingtype = getradiovalue(form.pricing);
		  var paymenttype = getradiovalue(form.modeofpayment);
		  var inclusivetaxamount = document.getElementById('inclusivetaxamount').value;
		  var offeramount = document.getElementById('offeramount').value;
		  var field = $('#dealeridhidden');
		  if(!field.val())
		  {
			  $('#form-error').html('');
			  alert('Please Select a Dealer and click Go.');
			  field.focus(); return false;
		  }
		  var purchasearrray = new Array();
		  var purchasevalues = new Array();
		  var usagearrray = new Array();
		  var usagevalues = new Array();
		  var productamountarrray = new Array();
		  var productamountvalues = new Array();
		  var productquantityarrray = new Array();
		  var productquantityvalues = new Array();
		  var serviceamountarrray = new Array();
		  var serviceamountvalues = new Array();
		  
		  for(i=0,j=1; i<count; i++,j++)
		  {	
			  if($("#itemtype"+ j).val() == 'product')
			  {
				  purchasearrray[i] = 'purchasetypehidden' + j;
				  purchasevalues[i] = document.getElementById(purchasearrray[i]).value;
				  usagearrray[i] = 'usagetypehidden' + j;
				  usagevalues[i] = document.getElementById(usagearrray[i]).value;
				  productquantityarrray[i] = 'productquantity' + j;
				  productquantityvalues[i] = document.getElementById(productquantityarrray[i]).value;
				  productamountarrray[i] = 'productamount' + j;
				  productamountvalues[i] = document.getElementById(productamountarrray[i]).value;
				  var field = document.getElementById(productamountarrray[i]);
				  if(!field.value) { 
					  $('#form-error').html('');
					  alert('Please Enter the amount');
					  field.focus(); return false;}
				  else if(field.value)	{ 
				  if(!validateamountfield(field.value)) {
					  $('#form-error').html('');
					  alert('Amount is not Valid');
					  field.focus(); return false; } }
				  else
					  return true;
			  }
			  else
			  {
				  if(serviceamountvalues == '')
					  serviceamountvalues = serviceamountvalues + document.getElementById('productamount' + j).value;
				  else
					  serviceamountvalues = serviceamountvalues + '~' + document.getElementById('productamount' + j).value;
				  var field =document.getElementById('productamount' + j);
				  if(!field.value) { 
					  $('#form-error').html('');
					  alert('Please Enter the amount');
					  field.focus(); return false;}
				  else if(field.value)	{ 
				  if(!validateamountfield(field.value)) {
					  $('#form-error').html('');
					  alert('Amount is not Valid');
					  field.focus(); return false; } }
				  else
					  return true;
	  
			  }
		  }
		  var descriptiontypearrray = new Array();
		  var descriptiontypevalues = new Array();
		  var descriptionarrray = new Array();
		  var descriptionvalues = new Array();
		  var descriptionamountarrray = new Array();
		  var descriptionamountvalues = new Array();
		
		  for(i=0,j=0; i<rowcount,j<rowcount; i++,j++)
		  {	//alert('here');
		    
			  
				  if(descriptiontypearrray == '')
					  descriptiontypearrray = descriptiontypearrray + 'descriptiontype' + j;
				  else
					  descriptiontypearrray = descriptiontypearrray + '~' + 'descriptiontype' + j;
				  if(descriptiontypevalues == '')
					  descriptiontypevalues = descriptiontypevalues + document.getElementById('descriptiontype' + j).value;
				  else
					  descriptiontypevalues = descriptiontypevalues + '~' + document.getElementById('descriptiontype' + j).value;
					  
				  if(descriptionarrray == '')
					  descriptionarrray = descriptionarrray + 'description' + j;
				  else
					  descriptionarrray = descriptionarrray + '~' + 'description' + j;
				  
				  if(descriptionvalues == '')
				  {
				      if(document.getElementById('descriptiontype' + j).value == "percentage")
				      {
				          var discount_description = 'Discount '+ document.getElementById('descriptionamount' + j).value + '%';
				          
				          descriptionvalues = descriptionvalues + discount_description;
				      }
				      else if(document.getElementById('descriptiontype' + j).value == "amount")
				      {
				          var discount_description = 'Discount';
				          
				          descriptionvalues = descriptionvalues + discount_description;
				      }
				      else
				      {
					    descriptionvalues = descriptionvalues + document.getElementById('description' + j).value;
				      } 
				  }	  
				  else
				  {
				      if(document.getElementById('descriptiontype' + j).value == "percentage")
				      {
				          var discount_description = 'Discount '+ document.getElementById('description' + j).value + document.getElementById('descriptionamount' + j).value + '%';
				          
				        descriptionvalues = descriptionvalues + '~' + discount_description;
				      }
				      else if(document.getElementById('descriptiontype' + j).value == "amount")
				      {
				          var discount_description = 'Discount';
				          
				          descriptionvalues = descriptionvalues + '~' + discount_description;
				      }
				      else
				      {
					    descriptionvalues = descriptionvalues + '~' + document.getElementById('description' + j).value;
				      }  
				  }	  
					  
				  if(descriptionamountarrray == '')
					  descriptionamountarrray = descriptionamountarrray + 'descriptionamount' + j;
				  else
					  descriptionamountarrray = descriptionamountarrray + '~' + 'descriptionamount' + j;;
				  
				  if(descriptionamountvalues == '')
				  {
				      if(document.getElementById('descriptiontype' + j).value == "percentage")
				       {
				         descriptionamountvalues = descriptionamountvalues + $('#discountsection'+j+' #dicountvalue').val(); 
				       }
				       else
				       {
					     descriptionamountvalues =descriptionamountvalues + document.getElementById('descriptionamount' + j).value;
				  
				       }       
				  }	  
				  else
				  {
				       if(document.getElementById('descriptiontype' + j).value == "percentage")
				       {
				         descriptionamountvalues = descriptionamountvalues + '~' + $('#discountsection'+j+' #dicountvalue').val();
				       }
				       else
				       {
					     descriptionamountvalues = descriptionamountvalues + '~' + document.getElementById('descriptionamount' + j).value;
				       }  
				  }	  
			  
	         
		  }
		  
		  
		  if(descriptiontypearrray != '')
		  {
			  descriptiontypesplit = descriptiontypearrray.split('~');
			  descriptiontypevaluesplit = descriptiontypevalues.split('~');
			  descriptionarrraysplit = descriptionarrray.split('~');
			  descriptionvaluessplit = descriptionvalues.split('~');
			  descriptionamountarrraysplit = descriptionamountarrray.split('~');
			  descriptionamountvaluessplit = descriptionamountvalues.split('~');
			  for(var k=0;k<descriptiontypesplit.length;k++)
			  {
				  if(descriptiontypevaluesplit[k] != '')
				  {
					  var field = document.getElementById(descriptionarrraysplit[k]);
					  if(!field.value) {
						  $('#form-error').html(''); 
						  alert('Please Enter the Description...')
						  field.focus();  return false;}
					  var field = document.getElementById(descriptionamountarrraysplit[k]);
					  if(!field.value) {
						  $('#form-error').html(''); 
						  alert('Please Enter the amount');
						  field.focus();  return false;}
					  if(field.value)	{ if(!validateamountfield(field.value)) {
						  $('#form-error').html('');  
						  alert('Amount is not Valid.');
						  field.focus(); return false; } }
				  }
			  }
		  }
	    
		  if(pricingtype == 'offer')
		  {
			  var field = $('#offeramount');
			  if(!field.val())
			  {
				  $('#form-error').html('');
				  alert('Please Enter the offer amount.');
				  field.focus(); return false;
			  }
			  if(field.val())	{ if(!validateamountfield(field.val())) {
				  $('#form-error').html('');
				  alert('Amount is not Valid.')
				  field.focus(); return false; } }
		  }
		  else if(pricingtype == 'inclusivetax')
		  {
			  var field = $('#inclusivetaxamount');
			  if(!field.val())
			  {
				  $('#form-error').html('');
				  alert('Please Enter the amount.');
				  field.focus(); return false;
			  }
			  if(field.val())	{ if(!validateamountfield(field.val())) {
				  $('#form-error').html('');
				  alert('Amount is not Valid.');
				  field.focus(); return false; } }
		  }
		  if(paymenttype == 'cheque/dd/neft')
		  {
			  var field = $('#paymentremarks');
			  if(!field.val())
			  {
				  $('#form-error').html('');
				  alert('Please Enter the Payment Remarks.');
				  field.focus(); return false;
			  }
		  }
		  
		  
		  //disableproceedbutton();
		  var productarray=document.getElementsByName("productselectedhidden[]");
		  var productvalues = '';
		  for(i=1;i<=productarray.length;i++)
		  {
			  //added on 30.06.2017
		       /*var s = i+1;
		       var q = 0;
                       if($('#quantitycheck'+s).val() == undefined || $('#quantitycheck'+s).val() == '')
		       {
		          s++;
		       }
		       var quantity_check =$('#quantitycheck'+s).val();
		       
		       for(q=1; q<=quantity_check;q++)
		       {
			     productvalues += productarray[i].value  + '#';
		       } */
			   
			   var productselectedhiddenvalue = $('#productselectedhidden'+i).val();
    		   if(i != productarray.length) {
    		       productvalues += productselectedhiddenvalue+'#';
    		    }
    		    else {
    		        productvalues += productselectedhiddenvalue;
    		    }
		       
		  }
		 var productlist = productvalues;//productvalues.substring(0,(productvalues.length-1));//alert(productamountvalues);
		  var productnamearray=document.getElementsByName("productnamehidden[]");
		  var productnamevalues = '';
		  for(i=0;i<productarray.length;i++)
		  {
			  productnamevalues += productnamearray[i].value  + '#';
		  }
		  var productnamelist = productnamevalues.substring(0,(productnamevalues.length-1));//alert(productamountvalues);
		  var productleveldescriptionarray = document.getElementsByName("productleveldescriptiontype[]");
		  var productleveldescriptionvalues = '';
		  for(i=0;i<productleveldescriptionarray.length;i++)
		  {
			  var producttypedes = '';
			  if((productleveldescriptionarray[i].value == '') || (productleveldescriptionarray[i].value == 'Description (Optional)'))
				  var producttypedes = 'Not Avaliable';
			  else
				  var producttypedes = productleveldescriptionarray[i].value
			  productleveldescriptionvalues +=  producttypedes + '#';
		  }
		  var productleveldescriptionlist = productleveldescriptionvalues.substring(0,(productleveldescriptionvalues.length-1));
		  
		  var servicearray=document.getElementsByName("serviceselectedhidden[]");
		  var servicevalues = '';
		  for(i=0;i<servicearray.length;i++)
		  {
			  servicevalues += servicearray[i].value  + '#';
		  }
		  var servicelist = servicevalues.substring(0,(servicevalues.length-1));
		  
		  var servicearrayvalues=document.getElementsByName("serviceselectedvaluehidden[]");
		  var serviceslnovalues = '';
		  for(i=0;i<servicearrayvalues.length;i++)
		  {
			  serviceslnovalues += servicearrayvalues[i].value  + '#';
		  }
		  var servicelistvalues = serviceslnovalues.substring(0,(serviceslnovalues.length-1));
		  
		  var itemleveldescriptionarray = document.getElementsByName("itemleveldescriptiontype[]");
		  var itemleveldescriptionvalues = '';
		  for(i=0;i<itemleveldescriptionarray.length;i++)
		  {
			  var itemtypedes = '';
			  if((itemleveldescriptionarray[i].value == '') || (itemleveldescriptionarray[i].value == 'Description (Optional)'))
				  var itemtypedes = 'Not Avaliable';
			  else
				  var itemtypedes = itemleveldescriptionarray[i].value
			  itemleveldescriptionvalues +=  itemtypedes + '#';
		  }
		  var itemleveldescriptionlist = itemleveldescriptionvalues.substring(0,(itemleveldescriptionvalues.length-1));
		  
		  
	  //	return false;
		  var paymentmodeselect = $("input[name='paymentmodeselect']:checked").val();
		  if(paymentmodeselect == 'paymentmadelater')
			  var paymentremarks = $('#remarks').val();
		  else
			  var paymentremarks = $('#paymentremarks').val();
		  if($('#seztax').is(':checked'))
		  {
			  var field = $("#seztaxattachment" );
			  if(!field.val()) { alert("Please Attach the SEZ Certificate"); field.focus();field.scroll(); return false; }	
		  }	
		  if(paymenttype == 'credit/debit' || paymenttype == 'upi')
		  {
			  disableproceedbutton();
		  }
		  else
		  {
			  if($("input[name='paymentmode']:checked").val() == 'chequeordd' && paymentmodeselect == 'paymentmadenow')
			  {
				  var field = $('#DPC_chequedate');
				  if(!field.val()) {$('#form-error').html(''); alert('Please enter the Cheque Date'); field.focus(); return false; }
				  var field = $('#chequeno');
				  if(!field.val()) { $('#form-error').html(''); alert('Please enter the Cheque No'); field.focus(); return false; }
				  if(field.val()){ if(!validateamountfield(field.val())) { $('#form-error').html(''); alert('Cheque No is not Valid.'); field.focus(); return false; }}
				  var field = $('#drawnon');
				  if(!field.val()) { $('#form-error').html(''); alert('Please enter the Drawn On'); field.focus(); return false; }
			  }
		  }
		  var field = $('#paymentamount');
		  if(field.val() == '')
		  {
			  $('#form-error').html(''); alert('Please enter the Payment amount'); field.focus(); return false;
		  }
		  $('#proceedprocessingdiv').html(getprocessingimage());
		  var field = $('#invoicedated:checked').val();
		  if(field != 'on') var invoicedated = 'no'; else invoicedated = 'yes';
		  
		  var field = $('#seztax:checked').val();
		  if(field != 'on') var seztaxtype = 'no'; else seztaxtype = 'yes';
		  
		  var netamount = $('#netamount').val();
		  if(netamount == 0)
		  {
			  $('#proceedprocessingdiv').html('');
			  alert('Amount cannot be Zero'); return false;
		  }
	  //	disableproceedbutton();
		  var passData = "switchtype=proceedforpurchase&pricingtype=" +encodeURIComponent(pricingtype) + "&purchasevalues=" + encodeURIComponent(purchasevalues) + "&usagevalues=" + encodeURIComponent(usagevalues) + "&productamountvalues=" + encodeURIComponent(productamountvalues) + "&productquantityvalues=" + encodeURIComponent(productquantityvalues)  + "&descriptiontypevalues=" + encodeURIComponent(descriptiontypevalues) + "&descriptionvalues=" + encodeURIComponent(descriptionvalues) + "&descriptionamountvalues=" + encodeURIComponent(descriptionamountvalues)+ "&offeramount=" + encodeURIComponent(offeramount)+ "&inclusivetaxamount=" + encodeURIComponent(inclusivetaxamount)+ "&invoiceremarks=" + encodeURIComponent($('#invoiceremarks').val())+ "&paymentremarks=" + encodeURIComponent(paymentremarks)+ "&servicelist=" + encodeURIComponent(servicelist)+ "&serviceamountvalues=" + encodeURIComponent(serviceamountvalues)+ "&paymenttype=" + encodeURIComponent(paymenttype)+ "&lastslno=" + encodeURIComponent($('#lastslno').val())+ "&offerremarks=" + encodeURIComponent($('#offerremarkshidden').val()) + "&customer_gstno=" + encodeURIComponent($('#displaycustomergst').val()) + "&customer_gst=" + encodeURIComponent($('#customer_gst').val()) + "&igstamount=" + encodeURIComponent($('#igsttaxamount').val()) + "&cgstamount=" + encodeURIComponent($('#cgsttaxamount').val()) + "&sgstamount=" + encodeURIComponent($('#sgsttaxamount').val()) +  "&selectedcookievalue=" + encodeURIComponent(productlist)+ "&paymenttypeselected=" + encodeURIComponent($("input[name='paymentmodeselect']:checked").val())+ "&paymentmode=" + encodeURIComponent($("input[name='paymentmode']:checked").val()) + "&chequedate=" + encodeURIComponent($('#DPC_chequedate').val()) + "&duedate=" + encodeURIComponent($('#DPC_duedate').val()) + "&chequeno=" + encodeURIComponent($('#chequeno').val()) + "&drawnon=" + encodeURIComponent($('#drawnon').val()) + "&depositdate=" + encodeURIComponent($('#DPC_depositdate').val())+ "&paymentamount=" + encodeURIComponent($('#paymentamount').val())+ "&dealerid=" + encodeURIComponent($('#dealeridhidden').val()) + "&cusname=" + encodeURIComponent($('#displaycompanyname').val())+ "&cuscontactperson=" + encodeURIComponent($('#displaycontactperson').val())+ "&cusaddress=" + encodeURIComponent($('#displayaddress').val())+ "&cusemail=" + encodeURIComponent($('#displayemail').val())+ "&cusphone=" + encodeURIComponent($('#displayphone').val())+ "&cuscell=" + encodeURIComponent($('#displaycell').val())+ "&custype=" + encodeURIComponent($('#displaytypeofcustomer').html())+ "&invoicedated=" + encodeURIComponent(invoicedated)+ "&cuscategory=" + encodeURIComponent($('#displaytypeofcategory').html())+ "&servicelistvalues=" + encodeURIComponent(servicelistvalues) + "&privatenote=" + encodeURIComponent($('#privatenote').val())+ "&podate=" + encodeURIComponent($('#DPC_startdate').val())+ "&poreference=" + encodeURIComponent($('#poreference').val())+ "&productleveldescription=" + encodeURIComponent(productleveldescriptionlist)+ "&itemleveldescription=" + encodeURIComponent(itemleveldescriptionlist)+ "&seztaxtype=" + encodeURIComponent(seztaxtype)+ "&seztaxfilepath=" + encodeURIComponent($('#file_link').val())+ "&panno=" + encodeURIComponent($('#displaypanno').val())+ "&gstcheck=" + encodeURIComponent(getMessage) + "&dummy=" + Math.floor(Math.random()*10054300000);
		  queryString = "../ajax/invoicing.php";
		  ajaxcall10 = $.ajax(
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
					  var response = ajaxresponse.split('^');
					  
					  $('#proceedprocessingdiv').html('');
					  if(response[0] == 1)
					  {
						  newproductentry();
						  $('#onlineslno').val(response[2]);
						  $("").colorbox({ inline:true, href:"#viewinvoicediv" , onLoad: function() { $('#cboxClose').hide()}});
						  generateinvoicedetails('');
						  gridtab2(1,'tabgroupgrid','&nbsp; &nbsp;Invoice Details');
					  }
					  else if(response[0] == 2)
					  {
						  $('#proceedprocessingdiv').html(errormessage(response[1]));
					  }
					  else if(response[0] == 3)
					  {
						alert("Invoice generated");
						  //$('#onlineslno').val(response[2]);
						  //$("#submitform").attr("action", "http://imax.relyonsoft.com/user/makepayment/pay.php");
						  //$("#submitform").submit();	
					  }
					  else if(response[0] == 4)
					  {
						//alert("duplicate1");
						  $('#proceedprocessingdiv').html(errormessage(response[1]));
					  }
					  else if(response[0] == 5)
					  {
						  //getconfirmsg(response[2],response[1]);
						  var gstconfirm = confirm("Customer GSTIN is not valid. Do you want to proceed further?");
						  if(gstconfirm)
						  {
							  //alert('true');
							  proceedforpurchase('gstconfirm');
						  }
						  else
							  return false;
					  }
					  else
					  {
						  $('#proceedprocessingdiv').html(errormessage(response[1]));
					  }
				  }
			  }, 
			  error: function(a,b)
			  {
				  $("#proceedprocessingdiv").html(scripterror());
			  }
		  });
	 // }
	  //else
		  //return false;
}


function checkforproduct()
{
	var form = document.getElementById('submitform');
	var confirmation = confirm("Please read the invoice contents above, once more. Invoice, once generated, cannot be edited or cancelled, subject to approval. To read it once more, click CANCEL. To proceed, click OK.");
	if(confirmation)
	{
		$('#proceedprocessingdiv').html(getprocessingimage());
		var rowcount = $('#adddescriptionrows tr').length;
		var count = $('#seletedproductgrid tr').length;
		count = (count-2);
		var pricingtype = getradiovalue(form.pricing);
		var paymenttype = getradiovalue(form.modeofpayment);
		var inclusivetaxamount = document.getElementById('inclusivetaxamount').value;
		var offeramount = document.getElementById('offeramount').value;
		var purchasearrray = new Array();
		var purchasevalues = new Array();
		var usagearrray = new Array();
		var usagevalues = new Array();
		var productamountarrray = new Array();
		var productamountvalues = new Array();
		var productquantityarrray = new Array();
		var productquantityvalues = new Array();
		var serviceamountarrray = new Array();
		var serviceamountvalues = new Array();
		var productdescriptionarrray = new Array();
		var productdescriptionvalues = new Array();
		var itemdescriptionarrray = new Array();
		var itemdescriptionvalues = new Array();
		
		
		for(i=0,j=1; i<count; i++,j++)
		{	
			if($("#itemtype"+ j).val() == 'product')
			{
				purchasearrray[i] = 'purchasetypehidden' + j;
				purchasevalues[i] = document.getElementById(purchasearrray[i]).value;
				usagearrray[i] = 'usagetypehidden' + j;
				usagevalues[i] = document.getElementById(usagearrray[i]).value;
				productquantityarrray[i] = 'productquantity' + j;
				productquantityvalues[i] = document.getElementById(productquantityarrray[i]).value;
				productamountarrray[i] = 'productamount' + j;
				productamountvalues[i] = document.getElementById(productamountarrray[i]).value;
				productdescriptionarrray[i] = 'productleveldescription' + j;
				productdescriptionvalues[i] = document.getElementById(productdescriptionarrray[i]).value;
				
				var field = document.getElementById(productamountarrray[i]);
				if(!field.value) { 
					$('#form-error').html('');
					alert('Please Enter the amount');
					field.focus(); return false;}
				else if(field.value)	{ 
				if(!validateamountfield(field.value)) {
					$('#form-error').html('');
					alert('Amount is not Valid');
					field.focus(); return false; } }
				else
					return true;
			}
			else
			{
				if(serviceamountvalues == '')
					serviceamountvalues = serviceamountvalues + document.getElementById('productamount' + j).value;
				else
					serviceamountvalues = serviceamountvalues + '~' + document.getElementById('productamount' + j).value;
				itemdescriptionarrray[j] = 'itemleveldescription' + j;
				itemdescriptionvalues[j] = document.getElementById(itemdescriptionarrray[j]).value;
				var field =document.getElementById('productamount' + j);
				if(!field.value) { 
					$('#form-error').html('');
					alert('Please Enter the amount');
					field.focus(); return false;}
				else if(field.value)	{ 
				if(!validateamountfield(field.value)) {
					$('#form-error').html('');
					alert('Amount is not Valid');
					field.focus(); return false; } }
				else
					return true;
			}
		}
		var descriptiontypearrray = new Array();
		var descriptiontypevalues = new Array();
		var descriptionarrray = new Array();
		var descriptionvalues = new Array();
		var descriptionamountarrray = new Array();
		var descriptionamountvalues = new Array();
		for(i=0,j=0; i<rowcount,j<rowcount; i++,j++)
		{	
			if(document.getElementById('descriptiontype' + j).value != '')
			{
				if(descriptiontypearrray == '')
					descriptiontypearrray = descriptiontypearrray + 'descriptiontype' + j;
				else
					descriptiontypearrray = descriptiontypearrray + '~' + 'descriptiontype' + j;
				if(descriptiontypevalues == '')
					descriptiontypevalues = descriptiontypevalues + document.getElementById('descriptiontype' + j).value;
				else
					descriptiontypevalues = descriptiontypevalues + '~' + document.getElementById('descriptiontype' + j).value;
					
				if(descriptionarrray == '')
					descriptionarrray = descriptionarrray + 'description' + j;
				else
					descriptionarrray = descriptionarrray + '~' + 'description' + j;
				
				if(descriptionvalues == '')
					descriptionvalues = descriptionvalues + document.getElementById('description' + j).value;
				else
					descriptionvalues = descriptionvalues + '~' + document.getElementById('description' + j).value;
					
				if(descriptionamountarrray == '')
					descriptionamountarrray = descriptionamountarrray + 'descriptionamount' + j;
				else
					descriptionamountarrray = descriptionamountarrray + '~' + 'descriptionamount' + j;;
				
				if(descriptionamountvalues == '')
					descriptionamountvalues =descriptionamountvalues + document.getElementById('descriptionamount' + j).value;
				else
					descriptionamountvalues = descriptionamountvalues + '~' + document.getElementById('descriptionamount' + j).value;
			}
		}
		if(descriptiontypearrray != '')
		{
			descriptiontypesplit = descriptiontypearrray.split('~');
			descriptiontypevaluesplit = descriptiontypevalues.split('~');
			descriptionarrraysplit = descriptionarrray.split('~');
			descriptionvaluessplit = descriptionvalues.split('~');
			descriptionamountarrraysplit = descriptionamountarrray.split('~');
			descriptionamountvaluessplit = descriptionamountvalues.split('~');
			for(var k=0;k<descriptiontypesplit.length;k++)
			{
				if(descriptiontypevaluesplit[k] != '')
				{
					var field = document.getElementById(descriptionarrraysplit[k]);
					if(!field.value) {
						$('#form-error').html(''); 
						alert('Please Enter the Description...')
						field.focus();  return false;}
					var field = document.getElementById(descriptionamountarrraysplit[k]);
					if(!field.value) {
						$('#form-error').html(''); 
						alert('Please Enter the amount');
						field.focus();  return false;}
					if(field.value)	{ if(!validateamountfield(field.value)) {
						$('#form-error').html('');  
						alert('Amount is not Valid.');
						field.focus(); return false; } }
				}
			}
		}
		if(pricingtype == 'offer')
		{
			var field = $('#offeramount');
			if(!field.val())
			{
				$('#form-error').html('');
				alert('Please Enter the offer amount.');
				field.focus(); return false;
			}
			if(field.val())	{ if(!validateamountfield(field.val())) {
				$('#form-error').html('');
				alert('Amount is not Valid.')
				field.focus(); return false; } }
		}
		else if(pricingtype == 'inclusivetax')
		{
			var field = $('#inclusivetaxamount');
			if(!field.val())
			{
				$('#form-error').html('');
				alert('Please Enter the amount.');
				field.focus(); return false;
			}
			if(field.val())	{ if(!validateamountfield(field.val())) {
				$('#form-error').html('');
				alert('Amount is not Valid.');
				field.focus(); return false; } }
		}
		if(paymenttype == 'cheque/dd/neft')
		{
			var field = $('#paymentremarks');
			if(!field.val())
			{
				$('#form-error').html('');
				alert('Please Enter the Payment Remarks.');
				field.focus(); return false;
			}
		}
		//disableproceedbutton();
		var productarray=document.getElementsByName("productselectedhidden[]");
		var productvalues = '';
		for(i=0;i<productarray.length;i++)
		{
			productvalues += productarray[i].value  + '#';
		}
		var productlist = productvalues.substring(0,(productvalues.length-1));//alert(productamountvalues);
		
		var productnamearray=document.getElementsByName("productnamehidden[]");
		var productnamevalues = '';
		for(i=0;i<productarray.length;i++)
		{
			productnamevalues += productnamearray[i].value  + '#';
		}
		var productnamelist = productnamevalues.substring(0,(productnamevalues.length-1));//alert(productamountvalues);
		
		var servicearray=document.getElementsByName("serviceselectedhidden[]");
		var servicevalues = '';
		for(i=0;i<servicearray.length;i++)
		{
			servicevalues += servicearray[i].value  + '#';
		}
		
		
		var servicelist = servicevalues.substring(0,(servicevalues.length-1));
		
		var servicearrayvalues=document.getElementsByName("serviceselectedvaluehidden[]");
		var serviceslnovalues = '';
		for(i=0;i<servicearrayvalues.length;i++)
		{
			serviceslnovalues += servicearrayvalues[i].value  + '#';
		}
		var servicelistvalues = serviceslnovalues.substring(0,(serviceslnovalues.length-1));
		var paymentmodeselect = $("input[name='paymentmodeselect']:checked").val();
		if(paymentmodeselect == 'paymentmadelater')
			var paymentremarks = $('#remarks').val();
		else
			var paymentremarks = $('#paymentremarks').val();
		var field = $('#paymentamount');
		if(field.val() == '')
		{
			$('#form-error').html(''); alert('Please enter the Payment amount'); field.focus(); return false;
		}
		if(paymenttype == 'credit/debit' || paymenttype == 'upi')
		{
			disableproceedbutton();
		}
		else
		{
			if($("input[name='paymentmode']:checked").val() == 'chequeordd' && paymentmodeselect == 'paymentmadenow')
			{
				var field = $('#DPC_chequedate');
				if(!field.val()) {$('#form-error').html(''); alert('Please enter the Cheque Date'); field.focus(); return false; }
				var field = $('#chequeno');
				if(!field.val()) { $('#form-error').html(''); alert('Please enter the Cheque No'); field.focus(); return false; }
				if(field.val()){ if(!validateamountfield(field.val())) { $('#form-error').html(''); alert('Cheque No is not Valid.'); field.focus(); return false; }}
				var field = $('#drawnon');
				if(!field.val()) { $('#form-error').html(''); alert('Please enter the Drawn On'); field.focus(); return false; }
			}
			else if(paymentmodeselect == 'paymentmadelater')
			{
				var field = $('#DPC_duedate');
				if(!field.val()) {$('#form-error').html(''); alert('Please enter the Cheque Date'); field.focus(); return false; }
			}
		}
		//alert($("#dealer").val());
		if($("#dealer").length > 0)
		{
			var field = $('#dealeridhidden');
			if(!field.val())
			{
				$('#form-error').html('');
				alert('Please Select a Dealer and click Go.');
				field.focus(); return false;
			}
			var dealerid = $("#dealeridhidden").val();
		}
		else
		{
			var dealerid = '';
		}
		var field = $('#invoicedated:checked').val();
		if(field != 'on') var invoicedated = 'no'; else invoicedated = 'yes';
		var netamount = $('#netamount').val();
		if(netamount == 0)
		{
			$('#proceedprocessingdiv').html('');
			alert('Amount cannot be Zero'); return false;
		}
	//disableproceedbutton();
		$('#proceedprocessingdiv').html(getprocessingimage());
		var passdata = "switchtype=checkforproduct&purchasevalues=" + encodeURIComponent(purchasevalues) + "&usagevalues=" + encodeURIComponent(usagevalues) + "&lastslno=" + encodeURIComponent($('#lastslno').val()) + "&selectedcookievalue=" + encodeURIComponent(productlist) + "&dealerid=" + encodeURIComponent(dealerid) + "&dummy=" + Math.floor(Math.random()*10054300000); 
		var queryString = "../ajax/invoicing.php";
		ajaxcall411 = $.ajax(
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
							$('#proceedprocessingdiv').html('');
							if(response['productcheck'] == 'producttpresent')
							{
								var confirmation = confirm("An invoice is already present for the product. Are you sure you want to proceed?");
								if(confirmation)
								{
									proceedforpurchase();
								}
							}
							else
							{
								proceedforpurchase();
							}
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
	else
		return false;
}

function getdealerdetails1()
{
	var form = $('#submitform');
	var field = $('#dealer');
	var selecteddealer = $( "#dealer option:selected" ).val();
	$('#dealeridhidden').val(selecteddealer);
	var customer = $('#customerlist');
	var customer_hidden_id = $('#customer_hidden_id').val();
	if(customer.val())
	{
		if(!field.val())
		{
			//alert('Please select a dealer first'); field.focus(); return false;
		}
	}
	else
	{
		alert('Please select a Customer first'); field.focus(); return false;
	}
	/*if(!customer_hidden_id)
	{
	    alert('Please select a Customer first'); return false;
	}*/
	var passData = "switchtype=getdealerdetails&dealerid="+ encodeURIComponent($('#dealeridhidden').val()) + "&customer_hidden_id=" + customer_hidden_id + "&customer_gstno=" + encodeURIComponent($('#displaycustomergst').val()) +"&customer_gst=" + encodeURIComponent($('#customer_gst').val());
	//alert(passData);
	var queryString = "../ajax/invoicing.php";
//	$('#displaydealerdetails').html(getprocessingimage());
	ajaxcall9 = $.ajax(
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
				//alert(response[4]+response[5]+response[6]);
				
				if(response[0] == 1)
				{
					enableitemselection();
					$('#displaydealerdetailsicon').show();
					
					$('#displaydealername').html(response[2]);
					$('#displaydealerdetails').html(response[3]);
					$('#displaymarketingexe').html(response[2]);
					$('#dealer').val('');
				}
				else
				{
					$('#invoicedetailsgridc1_1').html("No datas found to be displayed");
				}
				if(response[4] == 'yes' && response[5] != '29')
				{
				    //alert(response[5]+response[6]);
				    $("#seztax").click();
				    document.getElementById("myfileuploadimage1").onclick = new Function("fileuploaddivid('','seztaxattachment','seztaxuploaddiv','595px','35%','6',document.getElementById('customerlist').value,'file_link','')") ;
				    $('#sextaxdivdisplay').show();
				    $("#igsttaxrow").show();
				    $("#cgsttaxrow").hide();
				    $("#sgsttaxrow").hide();
				    checksection = "igst";
				}
				else
				{
				    $('#myfileuploadimage1').removeAttr('onclick');//chnaged for gst
				}
				
				if(response[5] != '29')
				{
				    //alert(response[5]+response[6]);
				    //$("#seztax").click();
				    $('#sextaxdivdisplay').hide();
				    $("#igsttaxrow").show();
				    $("#cgsttaxrow").hide();
				    $("#sgsttaxrow").hide();
				    checksection = "igst";
				}
				if(response[4] == 'no' && response[5] == '29')
				{
				    //alert(response[4]);
				    $('#sextaxdivdisplay').hide();
				    $("#igsttaxrow").hide();
				    $("#cgsttaxrow").show();
				    $("#sgsttaxrow").show();
				    checksection = "csgst";
				}
				if(response[4] == 'yes' && response[5] == '29')
				{
				    //alert(response[4]);
				    //$("#seztax").click();
				    //$('#sextaxdivdisplay').show();
				    $("#igsttaxrow").show();
				    $("#cgsttaxrow").hide();
				    $("#sgsttaxrow").hide();
				    checksection = "igst";
				}
			}
		}, 
		error: function(a,b)
		{
			$("#invoicedetailsgridc1_1").html(scripterror());
		}
	});
}

function showdealerdetails()
{
	$("").colorbox({ inline:true, href:"#displaydealerdetails" , onLoad: function() { $('#cboxClose').hide()}});
}

function enableitemselection()
{
	$('#product').removeAttr('disabled');
	$('#product2').removeAttr('disabled');
}

function disableitemselection()
{
	$('#product').attr('disabled', 'disabled');
	$('#product2').attr('disabled', 'disabled');
}

function resetdealername()
{
	$("#displaydealername").html('<font color="#FF0000">Not Selected</font>');
	$("#displaydealerdetailsicon").hide();
}


function customerdetailsremovereadonly()
{
	disablebuttontype();
	$("#displaycompanyname").removeClass("swifttext-readonly-border-h");
	$("#displaycompanyname").addClass("swifttext-mandatory");
	$("#displaycompanyname").removeAttr("readonly");
	
	$("#displayaddress").removeClass("swifttext-readonly-border");
	$("#displayaddress").addClass("swifttext-mandatory");
	$("#displayaddress").removeAttr("readonly"); 
	

	$("#displaycontactperson").removeClass("swifttext-readonly-border");
	$("#displaycontactperson").addClass("swifttext-mandatory");
	$("#displaycontactperson").removeAttr("readonly"); 
	
	$("#displayemail").removeClass("swifttext-readonly-border");
	$("#displayemail").addClass("swifttext-mandatory");
	$("#displayemail").removeAttr("readonly"); 
	
	$("#displayphone").removeClass("swifttext-readonly-border");
	$("#displayphone").addClass("swifttext-mandatory");
	$("#displayphone").removeAttr("readonly"); 
	
	$("#displaycell").removeClass("swifttext-readonly-border");
	$("#displaycell").addClass("swifttext-mandatory");
	$("#displaycell").removeAttr("readonly");
	
	$("#poreference").val('');
	$("#poreference").removeClass("swifttext-readonly-border");
	$("#poreference").addClass("swifttext-mandatory");
	$("#poreference").removeAttr("readonly");
	
	//$("#displaycustomergst").removeClass("swifttext-readonly-border");
	//$("#displaycustomergst").addClass("swifttext-mandatory");
	//$("#displaycustomergst").removeAttr("readonly");
	//added on 03/11/2017 for disabling gstin autoupdate
	if($("#displaycustomergst").val() == "")
	{
    	$("#displaycustomergst").removeClass("swifttext-readonly-border");
    	$("#displaycustomergst").addClass("swifttext-mandatory");
    	$("#displaycustomergst").removeAttr("readonly");
	}
	
	//$("#startdate").attr("id", "DPC_startdate");
	$("#DPC_startdate").val('');
	$("#DPC_startdate").removeClass("swifttext-readonly-border");
	$("#CALBUTTONDPC_startdate").show();
	$("#DPC_startdate").addClass("swifttext-mandatory");

	$("#displaypanno").removeClass("swifttext-readonly-border");
	$("#displaypanno").addClass("swifttext-mandatory");
	$("#displaypanno").removeAttr("readonly");

}

function customerdetailsmakereadonly()
{
	enablebuttontype();
	$("#displaycompanyname").removeClass("swifttext-mandatory");
	$("#displaycompanyname").addClass("swifttext-readonly-border-h");
	$("#displaycompanyname").attr("readonly","readonly"); 
	
	$("#displayaddress").removeClass("swifttext-mandatory");
	$("#displayaddress").addClass("swifttext-readonly-border");
	$("#displayaddress").attr("readonly","readonly"); 
	
	$("#displaycontactperson").removeClass("swifttext-mandatory");
	$("#displaycontactperson").addClass("swifttext-readonly-border");
	$("#displaycontactperson").attr("readonly","readonly"); 
	
	$("#displayemail").removeClass("swifttext-mandatory");
	$("#displayemail").addClass("swifttext-readonly-border");
	$("#displayemail").attr("readonly","readonly"); 
	
	$("#displayphone").removeClass("swifttext-mandatory");
	$("#displayphone").addClass("swifttext-readonly-border");
	$("#displayphone").attr("readonly","readonly"); 
	
	$("#displaycell").removeClass("swifttext-mandatory");
	$("#displaycell").addClass("swifttext-readonly-border");
	$("#displaycell").attr("readonly","readonly"); 
	
	$("#DPC_startdate").val('Not Avaliable');
	$("#DPC_startdate").removeClass("swifttext-mandatory");
	$("#CALBUTTONDPC_startdate").hide();
	$("#DPC_startdate").addClass("swifttext-readonly-border");
	
	$("#poreference").val('Not Avaliable');
	$("#poreference").removeClass("swifttext-mandatory");
	$("#poreference").addClass("swifttext-readonly-border");
	$("#poreference").attr("readonly","readonly");
	
	$("#displaypanno").removeClass("swifttext-mandatory");
	$("#displaypanno").addClass("swifttext-readonly-border");
	$("#displaypanno").attr("readonly","readonly");
	
	$("#displaycompanyname").val($("#cusnamehidden").val());
	$("#displaycontactperson").val($("#contactpersonhidden").val());
	$("#displayemail").val($("#emailhidden").val());
	$("#displayphone").val($("#phonehidden").val());
	$("#displaycell").val($("#cellhidden").val());
	$("#displaytypeofcustomer").val($("#custypehidden").val());
	$("#displaytypeofcategory").val($("#cuscategoryhidden").val());
	$("#displayaddress").val($("#addresshidden").val());
	//added on 03/11/2017 for disabling gstin autoupdate
	var isReadonly = $("#displaycustomergst").attr('readonly');
	 //alert(isReadonly);
	if (!isReadonly)
	{
	    $("#displaycustomergst").val("");
	}
	$("#displaycustomergst").removeClass("swifttext-mandatory");
	$("#displaycustomergst").addClass("swifttext-readonly-border");
	$("#displaycustomergst").attr("readonly","readonly"); 

}


function disablebuttontype()
{
	$('#edit').attr("disabled", true); 
	$('#edit').removeClass('swiftchoicebutton');	
	$('#edit').addClass('swiftchoicebuttondisabled');
	
	$('#cancelinvoice').attr("disabled", true); 
	$('#cancelinvoice').removeClass('swiftchoicebutton');	
	$('#cancelinvoice').addClass('swiftchoicebuttondisabled');
}

function enablebuttontype()
{
	$('#edit').attr("disabled", false); 
	$('#edit').removeClass('swiftchoicebuttondisabled');	
	$('#edit').addClass('swiftchoicebutton');
	
	$('#cancelinvoice').attr("disabled", false); 
	$('#cancelinvoice').removeClass('swiftchoicebuttondisabled');	
	$('#cancelinvoice').addClass('swiftchoicebutton');
	
}

function previewinvoice() //chnaged for gst
{
	var form = document.getElementById('submitform');
	var field = $("#displaycompanyname" );
	if(!field.val()) { alert("Enter the Business Name [Company]. "); field.focus(); return false; }
	if(field.val()) { if(!validatebusinessname(field.val())) { alert('Business name contains special characters. Please use only Alpha / Numeric / space / hyphen / small brackets. '); field.focus(); return false; } }
	var field = $("#displaycontactperson");
	if(!field.val()) { alert("Enter the Contact Person Name. "); field.focus(); return false; }
	if(field.val()) { if(!validatecontactperson(field.val())) {alert('Contact person name contains special characters. Please use only Numeric / space.'); field.focus(); return false; } }
	var field = $("#displayemail");
	if(!field.val()) { alert("Enter the Email Id. "); field.focus(); return false; }
	if(field.val()) { if(!emailvalidation(field.val())) { alert('Enter the valid Email Id.'); field.focus(); return false; } }
	var field = $("#displayphone");
	if(!field.val()) { alert("Enter the Phone No. "); field.focus(); return false; }
	if(field.val()) { if(!validatephone(field.val())) { alert('Enter the valid Phone Number.'); field.focus(); return false; } }
	var field = $("#displaycell");
	if(!field.val()) { alert("Enter the Cell No. "); field.focus(); return false; }
	if(field.val()) { if(!validatecell(field.val())) { alert('Enter the valid Cell Number.'); field.focus(); return false; } }
	 
	    state_gst_code = $("#state_gst").html();//samar
		state_gst_code = $.trim(state_gst_code);
		
		
		var customer_gstin = '';
		
		var gst_type_input_value = $('input[name=gst_type_input_value]:checked').val();
		if(gst_type_input_value == 'yes') { 
		
			
	    var field = $("#displaycustomergst");
	     var fieldval = $("#displaycustomergst").val();
	    
		if(!field.val()) { alert("Enter the GSTIN No. "); field.focus(); return false; }
		if(field.val()) { if(!validategstin(field.val())) { alert('For GSTIN only Alpha / Numeric are allowed.'); field.focus(); return false; } }
		if(field.val()) { if(!validategstinregex(field.val(),state_gst_code)) { alert('State GST Code Not Matching.'); field.focus(); return false; } }
		if(field.val()) { if(fieldval.length != 15) { alert('GSTIN should be 15 chars.'); field.focus(); return false; } }
		
		
		if(field.val() != '') { 
    	    if(field.val() != 'Not Registered Under GST') { 
            	var gstinformat = new RegExp('^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}[0-9A-Z]{2}$|^[0-9]{2}[A-Z]{4}[0-9]{5}[A-Z]{1}[1-9A-Z]{1}[0-9A-Z]{2}$');
            	if (!gstinformat.test(field.val())) { alert('State GST Code Not in Format.'); field.focus(); return false; }
            	if(field.val()) { if(!validategstinregex(field.val(),state_gst_code)) { alert('State GST Code Not Matching.'); field.focus();return false; } }
    	    }
	    }
		
		
		customer_gstin = field.val();
		
		}
		else {
		    
		    customer_gstin = 'Not Registered Under GST';
			var fieldpan = $("#displaypanno");
			if(fieldpan.val() == "")
			{
				var netfield = parseInt($("#netamount").val());
				if(netfield >= '200000.00')
				{
					alert("Please enter panno as the amount is above 2 Lakhs."); return false;
				}
				else if(netfield == '200000.00')
				{
					alert("Please enter panno as the amount is 2 Lakhs."); return false;
				}
			}

			if(fieldpan.val()!= '')
			{
				var regex = /[A-Z]{5}[0-9]{4}[A-Z]{1}$/;    
				if(!regex.test(fieldpan.val()))
				{
					alert('PAN NO is not valid.'); fieldpan.focus();  return false;
				}
			}
		} 
	
	//$('#form-error').html(getprocessingimage());
	var rowcount = $('#adddescriptionrows tr').length;
	var count = $('#seletedproductgrid tr').length;
	count = (count-2);
	var pricingtype = getradiovalue(form.pricing);
	var paymenttype = getradiovalue(form.modeofpayment);
	var inclusivetaxamount = document.getElementById('inclusivetaxamount').value;
	var offeramount = document.getElementById('offeramount').value;
	var field = $('#dealeridhidden');
	if(!field.val())
	{
		$('#form-error').html('');
		alert('Please Select a Dealer and click Go.');
		field.focus(); return false;
	}
	var purchasearrray = new Array();
	var purchasevalues = new Array();
	var usagearrray = new Array();
	var usagevalues = new Array();
	var productamountarrray = new Array();
	var productamountvalues = new Array();
	var productquantityarrray = new Array();
	var productquantityvalues = new Array();
	//added for ESS
	var productgrouparrray = new Array();
	var productgroupvalues = new Array();
	var productgrouparr = new Array();
	//end
	var serviceamountarrray = new Array();
	var serviceamountvalues = new Array();
	var productdescriptionarrray = new Array();
	var productdescriptionvalues = new Array();
	var itemdescriptionarrray = new Array();
	var itemdescriptionvalues = new Array();
	var total_product_price = 0;
	var final_amount = 0;
	for(i=0,j=1; i<count; i++,j++)
	{	
		if($("#itemtype"+ j).val() == 'product')
		{
			purchasearrray[i] = 'purchasetypehidden' + j;
			purchasevalues[i] = document.getElementById(purchasearrray[i]).value;
			usagearrray[i] = 'usagetypehidden' + j;
			usagevalues[i] = document.getElementById(usagearrray[i]).value;
			productquantityarrray[i] = 'productquantity' + j;
			productquantityvalues[i] = document.getElementById(productquantityarrray[i]).value;
			productamountarrray[i] = 'productamount' + j;
			productamountvalues[i] = document.getElementById(productamountarrray[i]).value;
			productgrouparrray[i] = 'productgroupid' + j;
			productgroupvalues[i] = document.getElementById(productgrouparrray[i]).value;
			productgrouparr.push(productgroupvalues[i]);
			
			//alert(productamountvalues[i]);
			//alert(productquantityvalues[i]);
			final_amount = final_amount + parseInt(productamountvalues[i]*productquantityvalues[i]);
			//alert(final_amount);
			
			productdescriptionarrray[i] = 'productleveldescription' + j;
			productdescriptionvalues[i] = document.getElementById(productdescriptionarrray[i]).value;
			var field = document.getElementById(productamountarrray[i]);
			if(!field.value) { 
				$('#form-error').html('');
				alert('Please Enter the amount');
				field.focus(); return false;}
			else if(field.value)	{ 
			if(!validateamountfield(field.value)) {
				$('#form-error').html('');
				alert('Amount is not Valid');
				field.focus(); return false; } }
			else
			return true;
			var field = document.getElementById(productquantityarrray[i]);
			if(field.value==0) { 
				//$('#form-error').html('');
				alert('Please select the quantity');
				 return false;}
			
		}
		else
		{
			itemdescriptionarrray[j] = 'itemleveldescription' + j;
			itemdescriptionvalues[j] = document.getElementById(itemdescriptionarrray[j]).value;
			
			
			//final_amount = final_amount + parseInt(itemdescriptionvalues[j]);
			//alert(itemdescriptionvalues[j]);
			
			if(serviceamountvalues == '')
			{
				serviceamountvalues = serviceamountvalues + document.getElementById('productamount' + j).value;
			}
			else
			{
				serviceamountvalues = serviceamountvalues + '~' + document.getElementById('productamount' + j).value;
			}	
			var field =document.getElementById('productamount' + j);
			if(!field.value) { 
				$('#form-error').html('');
				alert('Please Enter the amount');
				field.focus(); return false;
			    
			}
			else if(field.value)	
			{ 
    			if(!validateamountfield(field.value)) 
    			{
    				$('#form-error').html('');
    				alert('Amount is not Valid');
    				field.focus(); return false; 
    			    
    			} 
			    else
			    {
			        final_amount = final_amount + parseInt(field.value);
			        //alert(field.value);
			    }
			}
			else
			{
				return true;
			}	

		}
	}
	var descriptiontypearrray = new Array();
	var descriptiontypevalues = new Array();
	var descriptionarrray = new Array();
	var descriptionvalues = new Array();
	var descriptionamountarrray = new Array();
	var descriptionamountvalues = new Array();
	var discounted_value =0;
	for(i=0,j=0; i<rowcount,j<rowcount; i++,j++)
	{	//alert('here');
	   
		if(document.getElementById('descriptiontype' + j).value != '')
		{
		   
		   if(document.getElementById('descriptiontype' + j).value == 'add' || document.getElementById('descriptiontype' + j).value == 'less' || document.getElementById('descriptiontype' + j).value == 'percentage' || document.getElementById('descriptiontype' + j).value == 'amount')
		   {
    			if(descriptiontypearrray == '')
    				descriptiontypearrray = descriptiontypearrray + 'descriptiontype' + j;
    			else
    				descriptiontypearrray = descriptiontypearrray + '~' + 'descriptiontype' + j;
    			if(descriptiontypevalues == '')
    				descriptiontypevalues = descriptiontypevalues + document.getElementById('descriptiontype' + j).value;
    			else
    				descriptiontypevalues = descriptiontypevalues + '~' + document.getElementById('descriptiontype' + j).value;
    				
    			if(descriptionarrray == '')
    				descriptionarrray = descriptionarrray + 'description' + j;
    			else
    				descriptionarrray = descriptionarrray + '~' + 'description' + j;
    			
    			if(descriptionvalues == '')
    			{
    			    if(document.getElementById('descriptiontype' + j).value == "percentage")
    			    {
    			        var discount_description = 'Discount '+document.getElementById('descriptionamount' + j).value + '%';
    			        descriptionvalues = descriptionvalues + discount_description;
    			    }
    			    else if(document.getElementById('descriptiontype' + j).value == "amount")
    			    {
    			        var discount_description = 'Discount';
    			        descriptionvalues = descriptionvalues + discount_description;
    			    }
    			    else
    			    {
    				    descriptionvalues = descriptionvalues + document.getElementById('description' + j).value;
    			    }	    
    			}	
    			else
    			{
    			    if(document.getElementById('descriptiontype' + j).value == "percentage")
    			    {
    			        var discount_description = 'Discount '+document.getElementById('descriptionamount' + j).value + '%';
    			        descriptionvalues = descriptionvalues + '~' + discount_description;
    			    }
    			    else if(document.getElementById('descriptiontype' + j).value == "amount")
    			    {
    			        var discount_description = 'Discount';
    			        descriptionvalues = descriptionvalues + '~' + discount_description;
    			    }
    			    else
    			    {
    				    descriptionvalues = descriptionvalues + '~' + document.getElementById('description' + j).value;
    			    }    
    			}	
    			if(descriptionamountarrray == '')
    				descriptionamountarrray = descriptionamountarrray + 'descriptionamount' + j;
    			else
    				descriptionamountarrray = descriptionamountarrray + '~' + 'descriptionamount' + j;;
    			
    			if(descriptionamountvalues == '')
    			{
    		       	if(document.getElementById('descriptiontype' + j).value == 'percentage')
		            {
		                descriptionamountvalues = descriptionamountvalues + $('#discountsection'+j+' #dicountvalue').val();
		            }      
		            else
		            {
    				  descriptionamountvalues =descriptionamountvalues + document.getElementById('descriptionamount' + j).value;
		            }  
    			}      
    			else
    			{
    			    if(document.getElementById('descriptiontype' + j).value == 'percentage')
		            {
		                descriptionamountvalues = descriptionamountvalues + '~' + $('#discountsection'+j+' #dicountvalue').val();
		            }      
		            else
		            {
    				   descriptionamountvalues = descriptionamountvalues + '~' + document.getElementById('descriptionamount' + j).value;
		            }   
    			}      
		   }
		   /*if(document.getElementById('descriptiontype' + j).value == 'percentage')
		   {
		         descriptionamountvalues = $('#discountsection'+j+' #dicountvalue').val();
		   }*/
	      
		}
	  	
	}
    //console.log(descriptionamountvalues);
	if(descriptiontypearrray != '')
	{
		descriptiontypesplit = descriptiontypearrray.split('~');
		descriptiontypevaluesplit = descriptiontypevalues.split('~');
		descriptionarrraysplit = descriptionarrray.split('~');
		descriptionvaluessplit = descriptionvalues.split('~');
		descriptionamountarrraysplit = descriptionamountarrray.split('~');
		descriptionamountvaluessplit = descriptionamountvalues.split('~');
		for(var k=0;k<descriptiontypesplit.length;k++)
		{
			if(descriptiontypevaluesplit[k] != '')
			{
				var field = document.getElementById(descriptionarrraysplit[k]);
				if(!field.value) {
					$('#form-error').html(''); 
					alert('Please Enter the Description...')
					field.focus();  return false;}
				var field = document.getElementById(descriptionamountarrraysplit[k]);
				if(!field.value) {
					$('#form-error').html(''); 
					alert('Please Enter the amount');
					field.focus();  return false;}
				if(field.value)	{ if(!validateamountfield(field.value)) {
					$('#form-error').html('');  
					alert('Amount is not Valid.');
					field.focus(); return false; } }
			}
		}
	}
	if(pricingtype == 'offer')
	{
		var field = $('#offeramount');
		if(!field.val())
		{
			$('#form-error').html('');
			alert('Please Enter the offer amount.');
			field.focus(); return false;
		}
		if(field.val())	{ if(!validateamountfield(field.val())) {
			$('#form-error').html('');
			alert('Amount is not Valid.')
			field.focus(); return false; } }
	}
	else if(pricingtype == 'inclusivetax')
	{
		var field = $('#inclusivetaxamount');
		if(!field.val())
		{
			$('#form-error').html('');
			alert('Please Enter the amount.');
			field.focus(); return false;
		}
		if(field.val())	{ if(!validateamountfield(field.val())) {
			$('#form-error').html('');
			alert('Amount is not Valid.');
			field.focus(); return false; } }
	}
	if(paymenttype == 'cheque/dd/neft')
	{
		var field = $('#paymentremarks');
		if(!field.val())
		{
			$('#form-error').html('');
			alert('Please Enter the Payment Remarks.');
			field.focus(); return false;
		}
	}
		//disableproceedbutton();
	var productarray=document.getElementsByName("productselectedhidden[]");
	var productvalues = '';
	for(i=0;i<productarray.length;i++)
	{
		productvalues += productarray[i].value  + '#';
	}
	var productlist = productvalues.substring(0,(productvalues.length-1));//alert(productamountvalues);
	var productnamearray=document.getElementsByName("productnamehidden[]");
	var productnamevalues = '';
	for(i=0;i<productarray.length;i++)
	{
		productnamevalues += productnamearray[i].value  + '#';
	}
	var productnamelist = productnamevalues.substring(0,(productnamevalues.length-1));//alert(productamountvalues);
	var servicearray=document.getElementsByName("serviceselectedhidden[]");
	var servicevalues = '';
	for(i=0;i<servicearray.length;i++)
	{
		servicevalues += servicearray[i].value  + '#';
	}
	var servicelist = servicevalues.substring(0,(servicevalues.length-1));
	
	var servicearrayvalues=document.getElementsByName("serviceselectedvaluehidden[]");
	var serviceslnovalues = '';
	for(i=0;i<servicearrayvalues.length;i++)
	{
		serviceslnovalues += servicearrayvalues[i].value  + '#';
	}
	var servicelistvalues = serviceslnovalues.substring(0,(serviceslnovalues.length-1)); 
//	return false;
	var paymentmodeselect = $("input[name='paymentmodeselect']:checked").val();
	if(paymentmodeselect == 'paymentmadelater')
		var paymentremarks = $('#remarks').val();
	else
		var paymentremarks = $('#paymentremarks').val();
	
	if($('#seztax').is(':checked'))
	{
		var field = $("#seztaxattachment" );
		if(!field.val()) { alert("Please Attach the SEZ Certificate"); field.focus();field.scroll(); return false; }
		var sezremarks = "TAX NOT APPLICABLE AS CUSTOMER IS UNDER SPECIAL ECONOMIC ZONE.";	
	}
	else
	{
		var sezremarks = "";
	}
	if(paymenttype == 'credit/debit' || paymenttype == 'upi')
	{
		disableproceedbutton();
	}
	else
	{
		if($("input[name='paymentmode']:checked").val() == 'chequeordd' && paymentmodeselect == 'paymentmadenow')
		{
			var field = $('#DPC_chequedate');
			if(!field.val()) {$('#form-error').html(''); alert('Please enter the Cheque Date'); field.focus(); return false; }
			var field = $('#chequeno');
			if(!field.val()) { $('#form-error').html(''); alert('Please enter the Cheque No'); field.focus(); return false; }
			if(field.val()){ if(!validateamountfield(field.val())) { $('#form-error').html(''); alert('Cheque No is not Valid.'); field.focus(); return false; }}
			var field = $('#drawnon');
			if(!field.val()) { $('#form-error').html(''); alert('Please enter the Drawn On'); field.focus(); return false; }
		}
	}
	var field = $('#paymentamount');
	if(field.val() == '')
	{
		$('#form-error').html(''); alert('Please enter the Payment amount'); field.focus(); return false;
	}
	$("#previewproductgrid tr").remove();
	$("#customeridpreview").html($("#displaycustomerid").html());
	$("#companynamepreview").html($("#displaycompanyname").val());
	var contactperson = $("#displaycontactperson").val().split(',');
	$("#contactpersonpreview").html(contactperson[0]);
	$("#addresspreview").html($("#displayaddress").val());
	var emailid = $("#displayemail").val().split(',');
	$("#emailpreview").html(emailid[0]);
	var phone = $("#displayphone").val().split(',');
	$("#phonepreview").html(phone[0]);
	var cell = $("#displaycell").val().split(',');
	$("#cellpreview").html(cell[0]);
	$('#custypepreview').html($("#displaytypeofcustomer").html());
	$('#cuscategorypreview').html($("#displaytypeofcategory").html());
	if($("#DPC_startdate").val() != '')
		$('#podatepreview').html($("#DPC_startdate").val());
	else
		$('#podatepreview').html('Not Avaliable');
	if($("#poreference").val() != '')
		$('#poreferencepreview').html($("#poreference").val());
	else
		$('#poreferencepreview').html('Not Avaliable');
	$('#marketingexepreview').html($("#displaymarketingexe").html());
	var field = $('#invoicedated:checked').val();
	if(field != 'on') var invoicedated = 'no'; else invoicedated = 'yes';
	
	var currentTime = new Date();
	var month = currentTime.getMonth();
	var day = currentTime.getDate();
	var year = currentTime.getFullYear();
	var hours = currentTime.getHours();
	var minutes = currentTime.getMinutes();
	if(hours < 10)
		hours = "0" + hours;
	if(minutes < 10)
		minutes = "0" + minutes;
	var currentdate1 = year + "-" + month + "-" + day; 
	var currentdate2 = new Date(currentdate1.split('-')[2],currentdate1.split('-')[1],currentdate1.split('-')[0]);
	var expiredate1 = '2019-4-3';
	var expiredate2 = new Date(expiredate1.split('-')[2],expiredate1.split('-')[1],expiredate1.split('-')[0]);
	//alert (expiredate2.getTime() +' / '+ currentdate2.getTime());

	var t1 = Date.parse(expiredate1);
    var t2 = Date.parse(currentdate1);

	if(invoicedated == 'yes')
	{
		if(t1 > t2)
			$('#invoicedatepreview').html('31-03-2019 (23:55)');
		else
			$('#invoicedatepreview').html(" " +day + "-" + (month+1) + "-" + year + " (" + hours + ":" + minutes + ")");
	}
	else
	{
		$('#invoicedatepreview').html(" " +day + "-" + (month+1) + "-" + year + " (" + hours + ":" + minutes + ")");
	}
		/*
		if(currentdate > '01-04-2017')
			$('#invoicedatepreview').html(' 31-03-2017 (23:55)');
		else
		{
			if(hours < 10)
				hours = "0" + hours;
			if(minutes < 10)
				minutes = "0" + minutes;
			$('#invoicedatepreview').html(" " +day + "-" + month + "-" + year + " (" + hours + ":" + minutes + ")");
		}*/
	
	
	k=0;

	var productgridheader = '<tr bgcolor="#cccccc" ><td width="50" nowrap="nowrap" style="text-align: left;" class="grey-td-border-grid">Sl No</td><td colspan="2" width="390" nowrap="nowrap" class="grey-td-border-grid" style="text-align: left;">Description</td><td width="80" nowrap="nowrap" style="text-align: left;" class="grey-td-border-grid">Amount</td></tr>';
	productgridrow = '';
	if(productlist != '')
	{
		var productcount = productlist.split('#');
		var productnames = productnamelist.split('#');
		var productgridrow = '';
		var productgrid = ''
		var productamountvalues = removedoublecomma(productamountvalues);
		var productdescriptionvalues = removedoublecomma(productdescriptionvalues);
		for(i=0;i<productcount.length;i++)
		{
			k++; 
			if(purchasevalues[i]  == 'new')
				var purchasetype = 'New';
			else
				var purchasetype = 'Updation';
				
			if(usagevalues[i]  == 'singleuser')
				var usagetype = 'Singleuser';
			else if(usagevalues[i]  == 'multiuser')
				var usagetype = 'Multiuser';
			else
				var usagetype = 'Add license';
			if((productdescriptionvalues[i]  == '') || (productdescriptionvalues[i]  == 'Description (Optional)'))
				var productdescriptiontype = 'Not Avaliable';
			else 
				var productdescriptiontype = productdescriptionvalues[i];
			
			if(productgrouparr[i] == 'ESS')	
				var productgrid = '<tr ><td class="grey-td-border-grid" style="">' + k +'</td> <td colspan="2" nowrap="nowrap" class="grey-td-border-grid" style="text-align: left;"><font color="#FF0000">'+ productnames[i] +'</font> <br/> Purchase Type: <font color="#FF0000">'+purchasetype+'</font> <br/><font color="#000"><strong>Product Description:</strong></font> '+productdescriptiontype+'  /  <font color="#000"><strong>SAC </strong></font>: Information Technology Services</td><td nowrap="nowrap" style="text-align: right;" class="grey-td-border-grid">'+ intToFormat(productamountvalues[i]) +'</td> </tr>';
			else
			var productgrid = '<tr ><td class="grey-td-border-grid" style="">' + k +'</td> <td colspan="2" nowrap="nowrap" class="grey-td-border-grid" style="text-align: left;"><font color="#FF0000">'+ productnames[i] +'</font> <br/> Purchase Type: <font color="#FF0000">'+purchasetype+'</font> / Usage Type: <font color="#FF0000">'+usagetype+'</font><br/><font color="#000"><strong>Product Description:</strong></font> '+productdescriptiontype+'  / <font color="#000"><strong>SAC </strong></font>: Information Technology Services </td><td nowrap="nowrap" style="text-align: right;" class="grey-td-border-grid">'+ intToFormat(productamountvalues[i]) +'</td> </tr>';
			total_product_price = total_product_price + productamountvalues[i];
			var productgridrow = productgridrow + productgrid ;
		}
	}
	if(servicelist != '')
	{
		var itemdescriptionvalues = removedoublecomma(itemdescriptionvalues);
		var servicelistcount = servicelist.split('#');
		var serviceamountvaluessplit = serviceamountvalues.split('~');
		for(i=0;i<servicelistcount.length;i++)
		{
			if((itemdescriptionvalues[i]  == '') || (itemdescriptionvalues[i]  == 'Description (Optional)'))
				var itemdescriptiontype = 'Not Avaliable';
			else 
			
				var itemdescriptiontype = itemdescriptionvalues[i];
			k++; 
			var productgrid = '<tr><td width="9%" class="grey-td-border-grid" style="">' + k +'</td> <td colspan="2" nowrap="nowrap" class="grey-td-border-grid" style="text-align: left;">'+ servicelistcount[i] +'<br/><font color="#000"><strong>Item Description </strong></font>: '+itemdescriptiontype+'</td><td width="19%" nowrap="nowrap" style="text-align: right;" class="grey-td-border-grid">'+ intToFormat(serviceamountvaluessplit[i]) +'</td> </tr>';
			total_product_price = total_product_price + serviceamountvaluessplit[i];
			productgridrow = productgridrow+productgrid;
		}
	}

	
	if(descriptiontypevalues != '')
	{
	    	
	/*-----------Added for total amount --------------*/
	//k++;
	var productgrid_added = '<tr><td width="9%" class="grey-td-border-grid" style="">&nbsp;&nbsp;</td> <td colspan="2" nowrap="nowrap" class="grey-td-border-grid" style="text-align: left;">Invoice Total</td><td width="19%" nowrap="nowrap" style="text-align: right;" class="grey-td-border-grid">'+ intToFormat(final_amount) +'</td> </tr>';
	
	//productgridrow = productgridrow+productgrid_added;
	
	/*-------------Ends----------------*/
	    
		var descriptiontypevaluessplit = descriptiontypevalues.split('~');
		var descriptionvaluessplit = descriptionvalues.split('~');
		var descriptionamountvaluessplit = descriptionamountvalues.split('~');
		for(i=0;i<descriptiontypevaluessplit.length;i++)
		{
			
			if(descriptiontypevaluessplit[i] == "percentage" || descriptiontypevaluessplit[i] == "amount")
			{
			    var productgrid = '<tr><td width="9%" class="grey-td-border-grid" style=""></td> <td colspan="2" nowrap="nowrap" class="grey-td-border-grid" style="text-align: left;">'+descriptionvaluessplit[i]+'</td><td width="19%" nowrap="nowrap" style="text-align: right;" class="grey-td-border-grid">'+ intToFormat(descriptionamountvaluessplit[i]) +'</td> </tr>';
			}
			else
			{
			    var productgrid = '<tr><td width="9%" class="grey-td-border-grid" style=""></td> <td colspan="2" nowrap="nowrap" class="grey-td-border-grid" style="text-align: left;">'+ descriptiontypevaluessplit[i] +': '+descriptionvaluessplit[i]+'</td><td width="19%" nowrap="nowrap" style="text-align: right;" class="grey-td-border-grid">'+ intToFormat(descriptionamountvaluessplit[i]) +'</td> </tr>';
			}
			
			productgridrow = productgridrow+productgrid;
		}
		
		productgridrow = productgridrow+productgrid_added;
		
	}
	
	var offerremarks= $('#offerremarkshidden').val();
	if(offerremarks != '')
	{
		var productgrid = '<tr><td width="9%" class="grey-td-border-grid" style="">&nbsp;</td> <td colspan="2" nowrap="nowrap" class="grey-td-border-grid" style="text-align: left;">'+ offerremarks +'</td><td width="19%" nowrap="nowrap" style="text-align: right;" class="grey-td-border-grid">&nbsp;</td> </tr>';
		productgridrow = productgridrow+productgrid;
	}
	var emptyrow = '<td colspan="3" class="grey-td-border-grid"  style="border-right:none"><br/><br/></td><td style="text-align: right;" class="grey-td-border-grid" >&nbsp;</td>';
	
	var igst_amount = $("#igsttaxamount").val();
	var igst_rate = $("#igstrate").val();
	var cgst_rate = $("#cgstrate").val();
	var sgst_rate = $("#sgstrate").val();
	
	if (!$("#igsttaxrow").is(':visible'))
	{
	    var amountgrid = '<tr><td colspan="2" class="grey-td-border-grid">Accounting Code For Service </td><td valign="top" class="grey-td-border-grid"  width="20%" style="border-left:none"><div align = "right">Net Amount</div></td><td nowrap="nowrap" style="text-align: right;" class="grey-td-border-grid" valign="top" ><span id="netamountpreview"></span></td></tr><tr><td colspan="2" class="grey-td-border-grid"  style="border-right:none"><span style="font-size:7px;color:#FF0000;text-align:left" >'+sezremarks+'</span></td><td valign="top" class="grey-td-border-grid" width="20%"  style="border-left:none"><div align = "right">CGST @ '+cgst_rate+'%</div></td><td nowrap="nowrap" style="text-align: right;" class="grey-td-border-grid" id="cgstpreview">&nbsp;</td></tr><tr><td colspan="2" class="grey-td-border-grid" style="border-right:none"><span style="font-size:7px;color:#FF0000;text-align:left"></span></td><td valign="top" class="grey-td-border-grid" width="20%"  style="border-left:none"><div align = "right">SGST @ '+sgst_rate+'%</div></td><td nowrap="nowrap" style="text-align: right;" class="grey-td-border-grid" id="sgstpreview">&nbsp;</td><td valign="top" class="grey-td-border-grid" width="20%"  style="border-left:none; display:none"><div align = "right">Service Tax @ 14%</div></td><td nowrap="nowrap" style="text-align: right;display:none" class="grey-td-border-grid" id="servicetaxpreview">&nbsp;</td></tr><tr style="display:none;"><td colspan="2" class="grey-td-border-grid"  style="border-right:none"></td><td valign="top" class="grey-td-border-grid" width="20%"  style="border-left:none;display:none"><div align = "right">SB Cess @ 0.5%</div></td><td nowrap="nowrap" style="text-align: right;display:none" class="grey-td-border-grid" id="sbtaxpreview">&nbsp;</td></tr><tr style="display:none;"><td colspan="2" class="grey-td-border-grid"  style="border-right:none"></td><td valign="top" class="grey-td-border-grid" style="border-left:none;display:none"><div align = "right">KK Cess @ 0.5%</div></td><td nowrap="nowrap" style="text-align: right;display:none" class="grey-td-border-grid" id="kk_cesstaxpreview">&nbsp;</td></tr><tr><td colspan="2" class="grey-td-border-grid"  style="border-right:none"></td><td valign="top" class="grey-td-border-grid" style="border-left:none"><div align = "right">Total</div></td><td nowrap="nowrap" style="text-align: right;" class="grey-td-border-grid" id="totalamountpreview">&nbsp;</td></tr><tr><td colspan="4" class="grey-td-border-grid" style="border-right:none">Rupee In Words: <span id="rupeeinwordspreview"></span></td> </tr>';
	    $("#previewproductgrid").append(productgridheader+productgridrow+emptyrow+amountgrid);
	    $("#cgstpreview").html(intToFormat($("#cgsttaxamount").val()));
	    $("#sgstpreview").html(intToFormat($("#sgsttaxamount").val()));
	
	}
	else
	{
	  var amountgrid = '<tr><td colspan="2" class="grey-td-border-grid">Accounting Code For Service </td><td valign="top" class="grey-td-border-grid"  width="20%" style="border-left:none"><div align = "right">Net Amount</div></td><td nowrap="nowrap" style="text-align: right;" class="grey-td-border-grid" valign="top" ><span id="netamountpreview"></span></td></tr><tr><td colspan="2" class="grey-td-border-grid"  style="border-right:none"><span style="font-size:7px;color:#FF0000;text-align:left" >'+sezremarks+'</span></td><td valign="top" class="grey-td-border-grid" width="20%"  style="border-left:none"><div align = "right">IGST @ '+igst_rate+'%</div></td><td nowrap="nowrap" style="text-align: right;" class="grey-td-border-grid" id="igstpreview">&nbsp;</td><td valign="top" class="grey-td-border-grid" width="20%"  style="border-left:none; display:none"><div align = "right">Service Tax @ 14%</div></td><td nowrap="nowrap" style="text-align: right;display:none" class="grey-td-border-grid" id="servicetaxpreview">&nbsp;</td></tr><tr style="display:none;"><td colspan="2" class="grey-td-border-grid"  style="border-right:none"></td><td valign="top" class="grey-td-border-grid" width="20%"  style="border-left:none;display:none"><div align = "right">SB Cess @ 0.5%</div></td><td nowrap="nowrap" style="text-align: right;display:none" class="grey-td-border-grid" id="sbtaxpreview">&nbsp;</td></tr><tr style="display:none;"><td colspan="2" class="grey-td-border-grid"  style="border-right:none"></td><td valign="top" class="grey-td-border-grid" style="border-left:none;display:none"><div align = "right">KK Cess @ 0.5%</div></td><td nowrap="nowrap" style="text-align: right;display:none" class="grey-td-border-grid" id="kk_cesstaxpreview">&nbsp;</td></tr><tr><td colspan="2" class="grey-td-border-grid"  style="border-right:none"></td><td valign="top" class="grey-td-border-grid" style="border-left:none"><div align = "right">Total</div></td><td nowrap="nowrap" style="text-align: right;" class="grey-td-border-grid" id="totalamountpreview">&nbsp;</td></tr><tr><td colspan="4" class="grey-td-border-grid" style="border-right:none">Rupee In Words: <span id="rupeeinwordspreview"></span></td> </tr>';
	  $("#previewproductgrid").append(productgridheader+productgridrow+emptyrow+amountgrid);
	  $("#igstpreview").html(intToFormat($("#igsttaxamount").val()));
	    
	}    

	$("#netamountpreview").html(intToFormat($("#totalamount").val()));
	$("#servicetaxpreview").html(intToFormat($("#sericetaxamount").val())).css('display','none');
	$("#kk_cesstaxpreview").html(intToFormat($("#kk_cess").val())).css('display','none');
	$("#sbtaxpreview").html(intToFormat($("#sbtaxamount").val())).css('display','none');
	$("#totalamountpreview").html(intToFormat($("#netamount").val()));
	$("#rupeeinwordspreview").html(NumbertoWords($("#netamount").val()));
	if($('#displaycustomergst').val() != '')
	{
	    $("#gstnidpreview").html($('#displaycustomergst').val());
	}
	else
	{
	    $("#gstnidpreview").html('Not Registered Under GST');
	}
	
	//$("#generatedbypreview").html($("#displaymarketingexe").html());
	var invoiceremarks = $("#invoiceremarks").val();
	if(invoiceremarks == '')
		invoiceremarks = 'None';
	$("#invoiceremarkspreview").html(invoiceremarks);
	$("#proceedprocessingdiv").html('');
	getpaymentremarks();
	enableproceedbutton();
	$("").colorbox({ inline:true, href:"#invoicepreviewdiv" , onLoad: function() { $('#cboxClose').hide()}});
}

function getpaymentremarks()
{
	var form = document.getElementById('submitform');
	var paymenttype = getradiovalue(form.modeofpayment);
	var chequedate = $("#DPC_chequedate").val();
	var chequeno = $("#chequeno").val();
	var drawnon = $("#drawnon").val();
	var paymentamount = $("#paymentamount").val();
	var duedate = $("#DPC_duedate").val();
	var paymentremarks;
	if(paymenttype == 'credit/debit')
		paymentremarks = 'Selected for Credit/Debit Card Payment. This is subject to successful transaction';
	else if(paymenttype == 'upi')
		paymentremarks = 'Selected for UPI Payment.This is subject to successful transaction.';
	else
	{
		var paymentmodeselect = $("input[name='paymentmodeselect']:checked").val();
		if(paymentmodeselect == 'paymentmadelater')
			var paymentremarks = 'Payment Due!! (Due Date: '+ duedate + ') ' + $("#remarks").val();
		else
		{
			var paymentmode = $("input[name='paymentmode']:checked").val();
			if(paymentmode == 'chequeordd')
				paymentremarks = 'Received Cheque No: '+ chequeno+', dated '+chequedate+', drawn on '+drawnon+', for amount '+paymentamount+'. Cheques received are subject to realization.';
			else if(paymentmode == 'cash')
				paymentremarks = $("#paymentremarks").val();
			else
				paymentremarks = 'Payment through Online Transfer. '+$("#paymentremarks").val()+'';
		}
	}
	$("#paymentremarkspreview").html(paymentremarks);
}

function cancelpurchase()
{
	$('#form-error').html('');
	$().colorbox.close();
}


function generatecustomerregistration(startlimit)
{
	var form = $("#submitform");
//	$('#lastslno').val(id);	
	var passdata = "switchtype=customerregistration&lastslno="+ encodeURIComponent($('#lastslno').val()) + "&startlimit=" + encodeURIComponent(startlimit);//alert(passData)
	var queryString = "../ajax/invoicing.php";
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
					var response = ajaxresponse;
					if(response['errorcode'] == '1')
					{
						//gridtab2(1,'tabgroupgrid','&nbsp; &nbsp;Current Registrations');
						$('#tabgroupgridwb2').html("Total Count :  " + response['count']);
						$('#tabgroupgridc1_1').html(response['grid']);
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


function getmorecustomerregistration(id,startlimit,slno,showtype)
{
	var form = $('#submitform');
	$('#lastslno').val(id);	
	var passdata = "switchtype=customerregistration&lastslno="+ encodeURIComponent($('#lastslno').val()) + "&startlimit=" + startlimit+ "&slno=" + slno+ "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);
	var queryString = "../ajax/invoicing.php";
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
					//gridtab2(1,'tabgroupgrid','&nbsp; &nbsp;Current Registrations');
					$('#tabgroupgridwb2').html("Total Count :  " + response['count']);
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


function formsubmitsave()
{
	$("").colorbox({ height:"425px", inline:true, href:"#inline_example1", onLoad: function() { $('#cboxClose').hide()}});
	tabopen5('1','tabg1');
	$('#savesubmitform')[0].reset();
	$('#form-error').html('');
}

//To add description rows
function adddescriptioncontactrows()
{
	$("#form-error").html('');
	var rowcount = ($('#adddescriptioncontactrows tr').length);
	if(rowcount == 1)
		slno  = (rowcount+1);
	else
		slno = rowcount + 1;

	rowid = 'removedescriptioncontactrow'+ slno;
	var value = 'contactname'+slno;
	
	var row = '<tr id="removedescriptioncontactrow'+ slno+'"><td  width="5%"><div align="left"><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td><td width="11%"><div align="center"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:110px" class="swiftselect-mandatory type_enter focus_redclass"><option value="" selected="selected" >--Select--</option><option value="general" >General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="ithead/edp">IT-Head/EDP</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="others" >Others</option></select></div></td><td width="16%"><div align="center"><input name="name'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="name'+ slno+'"  style="width:110px"  maxlength="130"  autocomplete="off"/></div></td><td width="18%"><div align="center"><input name="phone'+ slno+'" type="text"class="swifttext  type_enter focus_redclass" id="phone'+ slno+'" style="width:95px" maxlength="100"  autocomplete="off" /></div></td><td width="15%"><div align="center"><input name="cell'+ slno+'" type="text" class="swifttext  type_enter focus_redclass" id="cell'+ slno+'" style="width:95px"  maxlength="10"  autocomplete="off"/></div></td><td width="27%"><div align="center"><input name="emailid'+ slno+'" type="text" class="swifttext  type_enter focus_redclass" id="emailid'+ slno+'" style="width:130px"  maxlength="200"  autocomplete="off"/></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removecontactrowdiv'+ slno+'" onclick ="removedescriptionrowscontact(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" /></td></tr>';
	
	$("#adddescriptioncontactrows").append(row);
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
		$('#adddescriptioncontactrowdiv').hide();
	else
		$('#adddescriptioncontactrowdiv').show();
}

//Remove description row
function removedescriptionrowscontact(rowid,rowslno)
{
	if(totalarray == '')
		totalarray = $('#contactslno'+rowslno).val();
	else
		totalarray = totalarray  + ',' + $('#contactslno'+rowslno).val();
	var error = $("#form-error");
	$('#adddescriptioncontactrowdiv').show();
	var rowcount = $('#adddescriptioncontactrows tr').length;
	if(rowcount == 1)
	{
		// error.html(errormessage("Minimum of ONE contact detail is mandatory")); 
		alert("Minimum of ONE contact detail is mandatory");
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
			var removedescriptionrow = '#removedescriptioncontactrow'+i;
			var contactslno =  '#contactslno'+i;
			var removerowdiv = '#removecontactrowdiv1'+i;
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
				
				$("#removedescriptioncontactrow"+ i).attr("name","removedescriptioncontactrow"+ countval);
				$("#removedescriptioncontactrow"+ i).attr("id","removedescriptioncontactrow"+ countval);
				
				$("#contactslno"+ i).attr("name","contactslno"+ countval);
				$("#contactslno"+ i).attr("id","contactslno"+ countval);
				
				$("#contactname"+ i).attr("id","contactname"+ countval);
				$("#contactname"+ countval).html(countval);
				
				$("#removecontactrowdiv"+ i).attr("id","removecontactrowdiv"+ countval);
				document.getElementById("removecontactrowdiv"+ countval).onclick = new Function('removedescriptionrowscontact("removedescriptioncontactrow' + countval + '" ,"' + countval + '")') ;
						
			}
		}
	}
}


function findlasttd()
{
	if($("#adddescriptioncontactrows td").find('input').is('.default'))
	{
		$("#adddescriptioncontactrows td").find('input').removeClass("default");
		$("#adddescriptioncontactrows td:last").prev("td").find('input').addClass("default");
	}
	else
	{
		$("#adddescriptioncontactrows td:last").prev("td").find('input').addClass("default");
	}
}

function rowwdelete()
{
	totalarray = '';
	var rowcount = $('#adddescriptioncontactrows tr').length;
	if(rowcount <=10)
	{
		slno =1;
		$('#adddescriptioncontactrows tr').remove();
		rowid = 'removedescriptioncontactrow'+ slno;
		var value = 'contactname'+slno;
		var row = '<tr id="removedescriptioncontactrow'+ slno+'"><td  width="5%"><div align="left"><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td><td width="11%"><div align="center"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:110px" class="swiftselect-mandatory  type_enter focus_redclass"><option value="" selected="selected" >--Select--</option><option value="general">General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="ithead/edp">IT-Head/EDP</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="others" >Others</option></select></div></td><td width="16%"><div align="center"><input name="name'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="name'+ slno+'"  style="width:110px"  maxlength="130"  autocomplete="off"/></div></td><td width="18%"><div align="center"><input name="phone'+ slno+'" type="text"class="swifttext type_enter focus_redclass" id="phone'+ slno+'" style="width:95px" maxlength="100"  autocomplete="off" /></div></td><td width="15%"><div align="center"><input name="cell'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="cell'+ slno+'" style="width:95px"  maxlength="10"  autocomplete="off"/></div></td><td width="27%"><div align="center"><input name="emailid'+ slno+'" type="text" class="swifttext type_enter focus_redclass default" id="emailid'+ slno+'" style="width:130px"  maxlength="200"  autocomplete="off"/></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removecontactrowdiv'+ slno+'" onclick ="removedescriptionrowscontact(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" /></td></tr>';
		$("#adddescriptioncontactrows").append(row);
		findlasttd();
		$('#'+value).html(slno);
	}
		
}

function newentrycontact()
{
	var form = $('#savesubmitform');
	tabopen5('1','tabg1');
	 $('#savesubmitform')[0].reset();
	$('#cuslastslno').val('');
	enablesave();
	$('districtcodedisplay').html('<select name="district" class="swiftselect-mandatory" id="district" style="width:200px;"><option value="">Select A State First</option></select>');	
}


function formsubmitcontact(command)
{
	var form = $("#savesubmitform" );
	$('#save').removeClass('button_enter1');
	var error = $("#form-error" );
	var phonevalues = '';
	var cellvalues = '';
	var emailvalues = '';
	var namevalues = '';
	tabopen5('1','tabg1');
	var field = $("#businessname" );
	if(!field.val()) { error.html(errormessage("Enter the Business Name [Company]. ")); field.focus(); return false; }
	if(field.val()) { if(!validatebusinessname(field.val())) { error.html(errormessage('Business name contains special characters. Please use only Alpha / Numeric / space / hyphen / small brackets. ')); field.focus(); return false; } }
	var rowcount = $('#adddescriptioncontactrows tr').length;
	tabopen5('2','tabg1');
	var l=1;
	while(l<=rowcount)
	{
		if(!$("#selectiontype1").val())
		{
				// error.html(errormessage("Minimum of ONE contact detail is mandatory"));return false;
				alert("Minimum of ONE contact detail is mandatory");
				return false;
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
	
	var field = $("#gst_nos" );
	//if(!field.val()) { error.html(errormessage("Enter the Business Name [Company]. ")); field.focus(); return false; }
	if(field.val()) { if(!validategstin(field.val())) { error.html(errormessage('For GSTIN only Alpha / Numeric are allowed.')); field.focus(); return false; } }
	
	
	var field = $("#pincode" );
	if(!field.val()) { error.html(errormessage("Enter the PinCode. ")); field.focus(); return false; }
	if(field.val()) { if(!validatepincode(field.val())) { error.html(errormessage('Enter the valid PIN Code.')); field.focus(); return false; } }
	var field = $("#stdcode");
	if(field.val()) { if(!validatestdcode(field.val())) { error.html(errormessage('Enter the valid STD Code.')); field.focus(); return false; } }
	var field = $("#fax");
	if(field.val()) { if(!validatephone(field.val())) { error.html(errormessage('Enter the valid Fax Number.')); field.focus(); return false; } }
	var field = $("#website");
	if(field.val())	{ if(!validatewebsite(field.val())) { error.html(errormessage('Enter the valid Website.')); field.focus(); return false; } }
	var field = $("#type" );
	if(!field.val()) { error.html(errormessage("Select the Type. ")); field.focus(); return false; }
	var field = $("#category" );
	if(!field.val()) { error.html(errormessage("Select the Category. ")); field.focus(); return false; }
	$('#cuslastslno').val(' ');
		var passdata = "";
		if(command == 'save')
		{
			passdata =  "switchtype=contactsave&businessname=" + encodeURIComponent($("#businessname" ).val())  + "&address=" + encodeURIComponent($('#address').val()) + "&place=" + encodeURIComponent($('#place').val()) + "&pincode=" + encodeURIComponent($('#pincode').val()) + "&district=" + encodeURIComponent($('#district').val())  + "&category=" + encodeURIComponent($('#category').val()) + "&type=" + encodeURIComponent($('#type').val()) + "&stdcode=" + encodeURIComponent($('#stdcode').val()) + "&fax=" + encodeURIComponent($('#fax').val()) + "&website=" + encodeURIComponent($('#website').val()) + "&remarks=" + encodeURIComponent($('#remarks').val())  + "&lastslno=" + encodeURIComponent($('#cuslastslno').val()) + "&contactarray=" + encodeURIComponent(contactarray)+ "&totalarray=" + encodeURIComponent(totalarray)+ "&dummy=" + Math.floor(Math.random()*100000000);//alert(passdata)

		}
		else
		{
				alert('You are using a wrong provision.');
				return false;
		}
		queryString = '../ajax/invoicing.php';
		error.html('<img src="../images/imax-loading-image.gif" border="0" />');
		ajaxcall5690 = $.ajax(
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
						$().colorbox.close();
						$('#detailsearchtext').val('');
						gettotalcustomercount();
						newentrycontact();
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


function descriptionfocus(id)
{
	if($('#'+id).val() == 'Description (Optional)')
	{
		$('#'+id).val('');
		$('#'+id).removeClass("description-blur");
		$('#'+id).addClass("description-focus");
	}
	else
	return false;
}

function descriptionblur(id)
{
	if($('#'+id).val() == '')
	{
		$('#'+id).val('Description (Optional)');
		$('#'+id).removeClass("description-focus");
		$('#'+id).addClass("description-blur");
	}
	else
	return false;
}


function descriptiontrim(value)
{
	var desiredlength = 40;
	var length = value.length;
	if(length >= desiredlength)
	{
		value = value.substring( 0, desiredlength);
		value += ".....";
	}
	return value;
}

function sezfunc()
{
	if($('#seztax').is(':checked'))
	{
		document.getElementById("myfileuploadimage1").onclick = new Function("fileuploaddivid('','seztaxattachment','seztaxuploaddiv','595px','35%','6',document.getElementById('customerlist').value,'file_link','')") ;
		$('#sextaxdivdisplay').show();
		$('#seztaxuploaddiv').hide();
		
		$("#igsttaxrow").show();
		$("#cgsttaxrow").hide();
		$("#sgsttaxrow").hide();
		
		calculatenormalprice();
	}
	else
	{
		$('#myfileuploadimage1').removeAttr('onclick');
		$('#sextaxdivdisplay').hide();
		$('#seztaxuploaddiv').hide();
		
		if(checksection == "csgst")
		{
		   $("#igsttaxrow").hide();
		   $("#cgsttaxrow").show();
		   $("#sgsttaxrow").show();
		}
		
		
		calculatenormalprice();
	}
	
}



function viewdownloadpath(filepath)
{
	$('#filepathinvoicing').val('');
	if(filepath != '')
		$('#filepathinvoicing').val(filepath);
	var form = $('#submitformgrid');	
	$('#submitformgrid').attr("action", "../ajax/downloadfile.php?id=invoicing") ;
	//$('#submitformgrid').attr( 'target', '_blank' );
	$('#submitformgrid').submit();
}

function actualvalue(productcode, productpriceactual)
{
    var productcode = productcode;
    var productpriceactual = productpriceactual;
    productpriceactual = productpriceactual.replace('productprice_actual','');
    
    var editpurchasetypehtml = $.trim($('#editpurchasetypehtml'+productpriceactual).text());
    var editusagetypehtml = $.trim($('#editusagetypehtml'+productpriceactual).text());
    //editpurchasetypehtml = editpurchasetypehtml.trim();
    //editusagetypehtml = editusagetypehtml.trim();
   // alert(editpurchasetypehtml+'-'+editusagetypehtml);
    
    //Ajax Call to fill actual value
    
    //var passData = "switchtype=productspricesfill&productcode=" + encodeURIComponent(productcode) + "&dummy=" + Math.floor(Math.random()*100032680100);
    var passData = "switchtype=productspricesfill&productcode=" + encodeURIComponent(productcode) + "&editpurchasetypehtml=" + encodeURIComponent(editpurchasetypehtml) + "&editusagetypehtml=" + encodeURIComponent(editusagetypehtml) + "&dummy=" + Math.floor(Math.random()*10054300000);
	//alert(passData);
	var queryString = "../ajax/invoicing.php";
	ajaxcall4 = $.ajax(
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
				var response  = ajaxresponse;
				var product_actual_prices = response['product_actual_prices'];
				
				$('#productprice_actual'+productpriceactual).html(product_actual_prices);
				$('#productamount'+productpriceactual).val(product_actual_prices);
				calculatenormalprice();
			}
		}, 
		error: function(a,b)
		{
			$("#form-error").html(scripterror());
		}
	   });
    
    //Ajax Call ends
}


function actualvalues(productcode, productpriceactual,lineno)
{
    //alert(productpriceactual);

    var productcode = productcode;
    var editpurchasetypehtml = $.trim($('#editpurchasetypehtml'+lineno).text());
    var editusagetypehtml = $.trim($('#editusagetypehtml'+lineno).text());
    
    if(editpurchasetypehtml == 'New')
    {
        editpurchasetypehtml = 'Updation';
    }
    else
    {
        editpurchasetypehtml = 'New';
    }
    
    //alert(editpurchasetypehtml + editusagetypehtml);

    var passData = "switchtype=productspricesfill&productcode=" + encodeURIComponent(productcode) + "&editpurchasetypehtml=" + encodeURIComponent(editpurchasetypehtml) + "&editusagetypehtml=" + encodeURIComponent(editusagetypehtml) + "&dummy=" + Math.floor(Math.random()*10054300000);
	//alert(passData);
	var queryString = "../ajax/invoicing.php";
	ajaxcall4 = $.ajax(
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
				var response  = ajaxresponse;
				var product_actual_prices = response['product_actual_prices'];
				//console.log(product_actual_prices);
				
				$('#productprice_actual'+lineno).html(product_actual_prices);
				$('#productamount'+lineno).val(product_actual_prices);
				calculatenormalprice();
			}
		}, 
		error: function(a,b)
		{
			$("#form-error").html(scripterror());
		}
	   });
    
    //Ajax Call ends
}

function checkDisabled(evt) {
    //alert('sdfsdfsdf');
    var val = $("input[name=gst_type_input_value]:checked").val();
    if (val == 'no') {
        $('#displaycustomergst').val('');
        $('#displaycustomergst').attr('readonly', true);
        $('#displaycustomergst').css("background-color", "yellow");
    } else {
        $('#displaycustomergst').removeAttr('readonly');
        $('#displaycustomergst').css("background-color", "#F0F0F0");
    }
}
