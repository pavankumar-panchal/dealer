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
	queryString = "../ajax/matrixinvoicing.php";
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
	queryString = "../ajax/matrixinvoicing.php";
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
	
	queryString = "../ajax/matrixinvoicing.php";
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

	queryString = "../ajax/matrixinvoicing.php";
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
	
	queryString = "../ajax/matrixinvoicing.php";
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
//Function to add all customers to select box
function getcustomerlist1()
{	
	//disableformelemnts_invoicing();
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

// function getsubproduct(el)
// {
//     var table = document.getElementById('seletedproductgrid');
// 	//var tbodyRowCount = table.getElementsByTagName('tbody')[0].rows.length;
// 	var trid = el.parentNode.parentNode.rowIndex;
//     var getid = trid+1;
//     //var productsubtype = table.rows[trid].cells[3].getElementsByTagName('select')[0].id;
//     //alert(getid);
    
//     var productval = $('#producttype'+getid).val();
//     //var productval = getid.value;
//     //alert(productval);
//     var passData ="";
//     passData = "switchtype=getsubproduct&productvalue=" + productval + "&getid=" + getid + "&dummy=" + Math.floor(Math.random()*10054300000);
//     //alert(passData);
//     var queryString = "../ajax/matrixinvoicing.php";
//     ajaxcall =  $.ajax({
//         type:"POST",data: passData,dataType: "json",url: queryString, cache: false,
//         success: function(ajaxresponse,status)
//         {
//             var response = ajaxresponse;
//             if(response['errorcode'] == 1)
//             {
//                 //alert(response['optionval']);
//                 $('#displayprosubtype'+getid).html(response['optionval']);
//             }
//         }
//     });
// }

function selectfromlist()
{
	var selectbox = document.getElementById('customerlist');
	var cusnamesearch = document.getElementById('detailsearchtext');
	cusnamesearch.value = selectbox.options[selectbox.selectedIndex].text;
	cusnamesearch.select();
    customerdetailstoform(selectbox.value);
    $('#displaycustomername').html($("#customerlist option:selected").text());
    $('#lastslno').val($("#customerlist option:selected").text());
}

function customerdetailstoform(cusid)
{
    var form = $("#submitform");
    $("#submitform")[0].reset();
    var error = $("#form-error");
    if(cusid!='')
    {
        var passdata = "switchtype=customerdetailstoform&lastslno=" + encodeURIComponent(cusid) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData);
        var queryString = "../ajax/matrixinvoicing.php";
        error.html('<img src="../images/imax-loading-image.gif" border="0" />');
		ajaxobjext12 = $.ajax(
		{
			type: "POST",url: queryString, data: passdata, cache: false,dataType: "json",
			success: function(ajaxresponse,status)
			{	
				var response = ajaxresponse;
				
				//console.log(response);
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else if(response['errorcode'] == '1')
				{
                    error.html('');
					$('#lastslno').val(response['slno']);
                    //alert(response['gst_no']);
                    if(response['gst_no'])
                    {
                        $('#displaycustomergst').val(response['gst_no']);
                    }
                    else
                    {
                        $('#displaycustomergst').val('Not Registered Under GST');
                    }
					
					$('#displaycustomerid').val(response['customerid']);
					$('#displaycompanyname').val(response['companyname']);
					$('#displaycontactperson').val(response['contactvalues']);
					$('#displayaddress').val(response['address']);
					$('#displayphone').val(response['phoneres']);
					$('#displaycell').val(response['cellres']);
					$('#displayemail').val(response['emailidres']);
					if(response['businesstype'] == null)
						$('#displaytypeofcategory').val('Not Available');
					else
						$('#displaytypeofcategory').val(response['businesstype']);
					if(response['customertype'] == null)
						$('#displaytypeofcustomer').val('Not Available');
					else
						$('#displaytypeofcustomer').val(response['customertype']);
                    $('#state_gst_code').val(response['state_gst_code']);
                    $('#address').val(response['address1']);
                    $('#pincode').val(response['pincode']);
                    $('#pincodestatus').val(response['pincodestatus']);
					//alert(response['invoicegrid']);
					$('#displayinvoices').html(response['invoicegrid']);
					$('#displaypanno').val(response['panno']);
					$('#sez_enabled').val(response['sez_enabled']);
					if(response['invoicegrid'])
                    	$('#displayinvoices').html(response['invoicegrid']);
					else
						$('#displayinvoices').html('No datas found to be displayed.');
                    
				}
                else if(response['errorcode'] == '2')
				{
					error.html(errormessage(response[1]));
				}
			}, 
			error: function(a,b)
			{
				error.html(scripterror());
			}
		});		
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

function additemgrid()
{
    var field = $('#displaycompanyname');
	if(field.val() == "")
	{
		alert("Pleast select Customer first.");
		return false;
	}
	var  table = document.getElementById('seletedproductgrid'),tbody = table.getElementsByTagName('tbody')[0],
    clone = tbody.rows[0].cloneNode(true);
	var gridlength = ++tbody.rows.length;
	if(gridlength == 11)
	{
		alert("Only 10 rows are allowed.");
		return false;
	}
    $('#productgrid').val(gridlength);
	var new_row = updateRow(clone.cloneNode(true), gridlength, true);
    tbody.appendChild(new_row);
    $("#textboxDiv"+gridlength).html('');
    $("#invamount"+gridlength).val('');
    //$('#productsubtype'+gridlength).find('option').not(':first').remove();
    
}

function updateRow(row, i, reset) 
{
    row.cells[6].id = 'textboxDiv' + i ;
	row.cells[0].innerHTML = i;
	var inp0 = row.cells[1].getElementsByTagName('select')[0];
	var inp1 = row.cells[2].getElementsByTagName('select')[0];
	var inp2 = row.cells[3].getElementsByTagName('input')[0];
	var inp3 = row.cells[4].getElementsByTagName('input')[0];
	var inp4 = row.cells[5].getElementsByTagName('input')[0];
	var inp5 = row.cells[7].getElementsByTagName('input')[0];
    
	inp0.id = 'purchasetype' + i;
	inp1.id = 'producttype' + i;
	inp2.id = 'quantity' + i;
	inp3.id = 'unitamt' + i;
	inp4.id = 'invamount' + i;
	inp5.id = 'productnamehidden' + i;
    //alert(inp0.id);
 
  if (reset) {
    inp1.value = inp2.value = inp3.value = inp4.value = inp0.value = inp5.value  = '';
  }
  //console.log(row);
  return row;
}

function removegrid(el)
{
	var table = document.getElementById('seletedproductgrid');
	var tbodyRowCount = table.getElementsByTagName('tbody')[0].rows.length;
	var i = el.parentNode.parentNode.parentNode.rowIndex;
	//alert(i);
	if(tbodyRowCount > 1)
	{
		table.deleteRow(i);
		k=0;
		for (row = 1; row <=table.rows.length; row++) 
		{
			table.rows[k].cells[0].innerHTML = row;
			table.rows[k].cells[1].getElementsByTagName('select')[0].id = 'purchasetype' + row;
			table.rows[k].cells[2].getElementsByTagName('select')[0].id = 'producttype' + row;
			table.rows[k].cells[3].getElementsByTagName('input')[0].id = 'quantity' + row;
			table.rows[k].cells[4].getElementsByTagName('input')[0].id = 'unitamt' + row;
			table.rows[k].cells[5].getElementsByTagName('input')[0].id = 'invamount' + row;
            table.rows[k].cells[6].id = 'textboxDiv' + row;
			table.rows[k].cells[7].getElementsByTagName('input')[0].id = 'productnamehidden' + row;

			k++;
		}
        $('#productgrid').val(table.rows.length);
	}
	else
	{
		alert("Minimum of ONE Row required to raise bill.");
		return false;
	}
}


function getSerialDiv(el)
{
    var table = document.getElementById('seletedproductgrid');
	var trid = el.parentNode.parentNode.rowIndex;
    var getid = trid+1;
    var srlVal = el.value;
    var srlid = el.id;
    $("#textboxDiv"+getid).html('');
    $("#unitamt"+getid).val('');
    $("#invamount"+getid).val('');
	var quantity = $("#quantity"+getid).val();
	var producttype = $("#producttype"+getid).val();
    // if(srlVal > 1) 
    // {
    //     $("#textboxDiv"+getid).append('<input name="fromsrlno[]" class="swifttext-mandatory" id="fromsrlno'+getid+'" placeholder="Enter Serial No" size="18" style="margin: 1px;" /> To&nbsp; <input name="tosrlno[]" class="swifttext-mandatory" id="tosrlno'+getid+'" placeholder="Enter Serial No" size="18" style="margin: 1px;" />' );
    // }
    // else{
    //$("#textboxDiv"+getid).append('<input name="fromsrlno[]" class="swifttext-mandatory" id="fromsrlno'+getid+'" placeholder="Enter Serial No" size="18" style="margin: 1px;" /><input type="hidden" name="tosrlno[]" class="swifttext-mandatory" id="tosrlno'+getid+'" placeholder="Enter Serial No" size="18" style="margin: 1px;" value="0"/>' );
	// if(producttype!=3)
	// {
		for(var i=1;i<=quantity;i++)
		{
			$("#textboxDiv"+getid).append('<input name="fromsrlno[]" class="swifttext-mandatory" id="fromsrlno'+i+'" placeholder="Enter Serial No" size="18" style="margin: 1px;" />' );
		}
	// }
    // else
	// 	$("#textboxDiv"+getid).append('<input type="hidden" value="0" name="fromsrlno[]" class="swifttext-mandatory" id="fromsrlno'+i+'" placeholder="Enter Serial No" size="18" style="margin: 1px;" />' );
    //}
}

function gettotalamount(el)
{
   var error = $("#form-error" );
    var emptyFields = false;
    $('input[name^="unitamt"]').each(function() {
        if(!(/^[0-9]*$/.test($(this).val())))
        {
            $(this).focus();
            $(this).val('');
            emptyFields = true;
        }
    });

    if (emptyFields) {
        alert('Digits only allowed!');
        //error.html(errormessage('Unit Amount must be digits'));
        return false;
    }
    var table = document.getElementById('seletedproductgrid');
	var trid = el.parentNode.parentNode.rowIndex;
    var getid = trid+1;
    var unitamt = el.value;
    var quantity = $("#quantity"+getid).val();
    var invamount = quantity*unitamt;
    $("#invamount"+getid).val(invamount);
}

function getproductname(el)
{
    var table = document.getElementById('seletedproductgrid');
	var trid = el.parentNode.parentNode.rowIndex;
    var getid = trid+1;
    //alert(getid);
    var productname = $("#producttype"+getid+" option:selected").text();
	var producttype = $("#producttype"+getid).val();
	var error = $('#form-error');
	var passdata = "switchtype=gethsncode&productid="+ encodeURIComponent(producttype) + "&dummy=" + Math.floor(Math.random()*10054300000);
	var queryString = "../ajax/matrixinvoicing.php";
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
				var response = ajaxresponse.split('^');
				if(response[0] == 1)
				{
					$("#productnamehidden"+getid).val(productname+'#'+producttype+'#'+response[1]);
					$("#quantity"+getid).val('');
				}
				
			}
		}, 
		error: function(a,b)
		{
			error.html(scripterror());
		}
	});	
}

function previewinvoice()
{
    var error = $("#form-error");
    var field = $('#displaycompanyname');
	if(field.val() == "")
	{
		alert("Pleast select Customer first.");
		return false;
	}

	
	if(($("#salesperson").is(":visible"))) 
	{ 
		var field = $("#salesperson");
		if(!field.val())
		{
			error.html(errormessage('Please select dealer')); field.focus(); return false; 
		} 
	}

	if($('#displaycustomergst').val() != '')
	{
		var address = $('#address').val();
	    if(address=="" || address.length < 3 || address.length >= 100)
		{
			alert("Please add/update your address(min length should be 3 and maximum should be 100) before proceeding further.");
			error.html('');
			return false;
		}

		// var pincodestatus = $('#pincodestatus').val();
		 var pincode = $('#pincode').val();
	
		if(pincode == "")
		{
			alert("Please add pincode to proceed further.");
			error.html('');
			return false;
		}

		if(!(/^[1-9][0-9]{5}$/.test(pincode)))
		{
			alert("Pincode of Buyer does not belong to his/her State.Update it before proceeding.");
			error.html('');
			return false;

		}
	}

    $('select[name^="purchasetype"]').each(function() {
        if($(this).val()=="")
        {
            $(this).focus();
            emptyFields = true;
        }
    });

    if (emptyFields) {
        error.html(errormessage('Please select Purchase type.'));
        //$(this).focus();
        return false;
     }

     var emptyFields = false;
     $('select[name^="producttype"]').each(function() {
        if($(this).val()=="")
        {
            $(this).focus();
            emptyFields = true;
        }
    });

    if (emptyFields) {
        error.html(errormessage('Please select Product type.'));
        //$(this).focus();
        return false;
     }

     var emptyFields = false;
     $('input[name^="quantity"]').each(function() {
        if($(this).val()=="")
        {
            $(this).focus();
            emptyFields = true;
        }
    });


    if (emptyFields) {
        error.html(errormessage('Please select Quantity.'));
        //$(this).focus();
        return false;
     }

     var emptyFields = false;
     $('input[name^="unitamt"]').each(function() {
        if($(this).val()=="")
        {
            $(this).focus();
            emptyFields = true;
        }
    });

    if (emptyFields) {
        error.html(errormessage('Please Enter Unit Amount.'));
        //$(this).focus();
        return false;
     }

    //  var emptyFields = false;
    //  $('input[name^="fromsrlno"]').each(function() {
    //     if($(this).val()=="")
    //     {
    //         $(this).focus();
    //         emptyFields = true;
    //     }
    // });

    // if (emptyFields) {
    //     error.html(errormessage('Please Enter Serial No.'));
    //     //$(this).focus();
    //     return false;
    //  }

    //  var emptyFields1 = false;
    // $('input[name^="tosrlno"]').each(function() {
    //     if($(this).val()=="")
    //     {
    //         $(this).focus();
    //         emptyFields1 = true;
    //     }
    // });

    // if (emptyFields1) {
    //     error.html(errormessage('Please Enter Serial No.'));
    //     //$(this).focus();
    //     return false;
    // }

   var field = $('#paymentamount');
    //alert(paymentamt);
    if(!field.val()){ $('#paymentamount').val('0'); error.html(errormessage('Payment amount must be zero if receipt is not required.')); field.focus(); return false;}
    else if(!(/^[0-9]*$/.test(field.val())))
    {
        $('#paymentamount').val('0');
        error.html(errormessage('Payment amount can only be digits.'));
        field.focus(); return false;
    }

    var field = $('#paymentremarks');
    if(!field.val()){ error.html(errormessage('Please Enter Payment remarks.')); field.focus(); return false;}

    var total_product_price = 0;
    var igst_tax_amount = 0;
	var cgst_tax_amount = 0;
	var sgst_tax_amount = 0;
    var netamount = 0;
    if($('#displaycustomergst').val() != '')
	{
	    $("#gstnidpreview").html($('#displaycustomergst').val());
	}
	else
	{
	    $("#gstnidpreview").html('Not Registered Under GST');
	}
    $("#customeridpreview").html($("#displaycustomerid").val());
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
	$('#custypepreview').html($("#displaytypeofcustomer").val());
	$('#cuscategorypreview').html($("#displaytypeofcategory").val());
	if($("#DPC_startdate").val() != '')
		$('#podatepreview').html($("#DPC_startdate").val());
	else
		$('#podatepreview').html('Not Avaliable');
	if($("#poreference").val() != '')
		$('#poreferencepreview').html($("#poreference").val());
	else
		$('#poreferencepreview').html('Not Avaliable');
    
    if(($("#salesperson").is(":visible"))) 
    {
        $('#marketingexepreview').html($("#salesperson option:selected").text());
    }
    else
    {
        $('#marketingexepreview').html($("#dealernamehidden").val());
    }
	$('#branchgstinpreview').html($("#branch_gstin").val());

    var PurchaseType = document.getElementsByName("purchasetype[]");
	var productnamehidden = document.getElementsByName("productnamehidden[]");
	var productType = document.getElementsByName("producttype[]");
	var quantity = document.getElementsByName("quantity[]");
	var unitAmt = document.getElementsByName("unitamt[]");
	var invoiceAmount = document.getElementsByName("invamount[]");
	var fromsrlno = document.getElementsByName("fromsrlno[]");
	// var tosrlno = document.getElementsByName("tosrlno[]");

    var purchasearray=Array.prototype.slice.call(PurchaseType);
    var purchaselist = purchasearray.map((o) => o.value).join("#");

    var producthiddenarray=Array.prototype.slice.call(productnamehidden);
    var producthiddenlist = producthiddenarray.map((o) => o.value).join("*");

    var productarray=Array.prototype.slice.call(productType);
    var productlist = productarray.map((o) => o.value).join("#");

    var quantityarray=Array.prototype.slice.call(quantity);
    var quantitylist = quantityarray.map((o) => o.value).join(",");

    var unitamtarray=Array.prototype.slice.call(unitAmt);
    var unitamtlist = unitamtarray.map((o) => o.value).join("*");

    var invoiceamountarray=Array.prototype.slice.call(invoiceAmount);
    var invoiceamountlist = invoiceamountarray.map(x => x.value).join("*");

    var fromsrlnoarray=Array.prototype.slice.call(fromsrlno);
    var fromsrlnolist = fromsrlnoarray.map(x => x.value).join("~");

	var srlvalues = $("input[name='fromsrlno[]']").map(function(){return $(this).val();}).get();

    // var tosrlnoarray=Array.prototype.slice.call(tosrlno);
    // var tosrlnolist = tosrlnoarray.map(x => x.value).join("~");

    var igstrate = $('#igstrate').val();
    var cgstrate = $('#cgstrate').val();
    var sgstrate = $('#sgstrate').val();
    var state_gst_code = $('#state_gst_code').val();
    var branchhidden = $('#branchhidden').val();

    var productgridrow = '';
    var productgrid = '';
    $("#previewproductgrid tr").remove();
    var productgridheader = '<tr bgcolor="#cccccc" ><td width="7%"><div align="center" ><strong>Sl No</strong></div></td><td width="64%"><div align="center"><strong>Description</strong></div></td><td width="5%"><div align="center"><strong>Qty</strong></div></td><td width="12%"><div align="center"><strong>Rate</strong></div></td><td width="12%"><div align="center"><strong>Amount</strong></div></td></tr>';

    var purchasevalues = purchaselist.split('#');
    var productnames = productlist.split('#');
    var producthiddennames = producthiddenlist.split('*');
    var quantityvalues = quantitylist.split(',');
    var unitamvalues = unitamtlist.split('*');
    var invoiceamountvalues = invoiceamountlist.split('*');
    var fromsrlnovalues = fromsrlnolist.split('~');
    // var tosrlnovalues = tosrlnolist.split('~');
    // if(productnames!="")
    // {
        var k=0;
        var purchasetype = '';
		var serialno = '';
		var array = [];

        for(i=0;i<productnames.length;i++)
        {
            k++;
			var productsplit = producthiddennames[i].split('#');
            if(purchasevalues[i]  == 'new')
				purchasetype = 'New';
            else if(purchasevalues[i]  == 'updation')
                purchasetype = 'Updation';

			array = srlvalues.slice(0,quantityvalues[i]);
			var filterarray = array.filter((x ="") => x.trim());
			if(filterarray.length!= 0)
			{
				serialno = filterarray.join("/");
			}
			else
			{
				serialno = '';
			}
				
			srlvalues.splice(0,quantityvalues[i]);

            var productgrid = '<tr><td width="7%" class="grey-td-border-grid" style="">' + k +'</td>';
            productgrid += '<td width="64%" class="grey-td-border-grid" style="text-align: left;"><font color="#FF0000">'+ productsplit[0] +'</font> <br/> ';
            productgrid += 'Purchase Type: <font color="#FF0000">'+purchasetype+'</font> / ';
			productgrid += 'Serial No:<font color="#FF0000">'+serialno+'</font> / ';
            productgrid += 'HSN: <font color="#FF0000">'+productsplit[2]+'</font></td>';
            productgrid += '<td width="5%" class="grey-td-border-grid" style="text-align: right;">' + quantityvalues[i] +'</td>';
            productgrid += '<td width="12%" class="grey-td-border-grid"style="text-align: right;">' + intToFormat(unitamvalues[i]) +'</td>';
            productgrid += '<td width="12%" class="grey-td-border-grid" style="text-align: right;">' + intToFormat(invoiceamountvalues[i]) +'</td><tr>';
            
            var productgridrow = productgridrow + productgrid ;
            total_product_price = parseInt(total_product_price)+parseInt(invoiceamountvalues[i]);
        }
        //console.log(total_product_price);
        var emptyrow = '<td colspan="4" class="grey-td-border-grid"  style="border-right:none"><br/><br/></td><td style="text-align: right;" class="grey-td-border-grid" >&nbsp;</td>';

        var amountgrid = '<tr><td colspan="3" class="grey-td-border-grid">Accounting Code For Service </td>';
		amountgrid += '<td valign="top" class="grey-td-border-grid"  width="15%" style="border-left:none"><div align = "right">Net Amount</div></td><td nowrap="nowrap" class="grey-td-border-grid" valign="top" style="text-align: right;" ><span id="netamountpreview"></span></td></tr>';
		
		var sezremarks = '';
		if($('#seztax').is(':checked'))
		{
			var sezremarks = "TAX NOT APPLICABLE AS CUSTOMER IS UNDER SPECIAL ECONOMIC ZONE.";
			sgst_tax_amount = '0.00';
			cgst_tax_amount = '0.00';
			igst_tax_amount = '0.00';
			amountgrid +='<tr><td colspan="3" class="grey-td-border-grid"  style="border-right:none"><span style="font-size:7px;color:#FF0000;text-align:left" >'+sezremarks+'</span></td><td   class="grey-td-border-grid" style="border-left:none" ><div align = "right">IGST @ '+igstrate+'%</div></td><td nowrap="nowrap" style="text-align: right;"  class="grey-td-border-grid" id="igstpreview">&nbsp;</td></tr>';
		}
		else
		{
			if(state_gst_code == branchhidden && $("#sez_enabled").val() == 'no')
			{
				sgst_tax_amount = (total_product_price * (cgstrate/100)).toFixed(2);
				cgst_tax_amount = (total_product_price * (sgstrate/100)).toFixed(2);
				igst_tax_amount = '0.00';
				
				amountgrid +='<tr><td colspan="4"  class="grey-td-border-grid" ><div align = "right">CGST @ '+cgstrate+'%</div></td><td nowrap="nowrap" style="text-align: right;"  class="grey-td-border-grid" id="cgstpreview">&nbsp;</td></tr>';
				amountgrid += '<tr><td colspan="4"  class="grey-td-border-grid" ><div align = "right">SGST @ '+sgstrate+'%</div></td><td nowrap="nowrap" style="text-align: right;"  class="grey-td-border-grid" id="sgstpreview">&nbsp;</td></tr>';
			
			}
			else
			{
				igst_tax_amount = (total_product_price * (igstrate/100)).toFixed(2);
				sgst_tax_amount = '0.00';
				cgst_tax_amount = '0.00';

				amountgrid +='<tr><td colspan="4"  class="grey-td-border-grid"  ><div align = "right">IGST @ '+igstrate+'%</div></td><td nowrap="nowrap" style="text-align: right;"  class="grey-td-border-grid" id="igstpreview">&nbsp;</td></tr>';
			}
		}
        
		amountgrid += '<tr><td colspan="4" class="grey-td-border-grid" ><div align = "right">Total</div></td><td nowrap="nowrap" style="text-align: right;"  class="grey-td-border-grid" style="text-align: right;" id="totalamountpreview">&nbsp;</td></tr>';
		amountgrid += '<tr><td colspan="5" class="grey-td-border-grid" style="border-right:none">Rupee In Words: <span id="rupeeinwordspreview"></span></td></tr>';
		netamount = total_product_price + parseFloat(cgst_tax_amount) + parseFloat(sgst_tax_amount)+ parseFloat(igst_tax_amount);
        netamount = Math.round(netamount);
		netamount = parseFloat(netamount).toFixed(2);

   // }
   if($('#displaycustomergst').val() == "Not Registered Under GST")
   {
		var fieldpan = $("#displaypanno");
		if(fieldpan.val() == "")
		{
			if(netamount > '200000.00')
			{
				alert("Please enter panno as the amount is above 2 Lakhs."); return false;
			}
			else if(netamount == '200000.00')
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
	
    $("#previewproductgrid").append(productgridheader+productgridrow+emptyrow+amountgrid);
    $("#totalamountpreview").html(intToFormat(netamount));
	$("#rupeeinwordspreview").html(NumbertoWords(netamount));
    $("#netamountpreview").html(intToFormat(total_product_price));
    $("#cgstpreview").html(intToFormat(cgst_tax_amount));
	$("#sgstpreview").html(intToFormat(sgst_tax_amount));
    $("#igstpreview").html(intToFormat(igst_tax_amount));
    $("#cgst_tax_amount").val(cgst_tax_amount);
	$("#sgst_tax_amount").val(sgst_tax_amount);
    $("#igst_tax_amount").val(igst_tax_amount);
    var invoiceremarks = $("#invoiceremarks").val();
	if(invoiceremarks == '')
		invoiceremarks = 'None';
	$("#invoiceremarkspreview").html(invoiceremarks);
	$("#paymentremarkspreview").html($("#paymentremarks").val());
	$("#proceedprocessingdiv").html('');
	enableproceedbutton();
    $("").colorbox({ inline:true, href:"#invoicepreviewdiv" , onLoad: function() { $('#cboxClose').hide()}});
}

function removerows()
{
	$("#seletedproductgrid").find("tr:gt(0)").remove();
	$('#purchasetype1').val('');
	$('#producttype1').val('');
	var quantity = $('#quantity1').val();
	for(i=1;i<=quantity;i++)
	{
		//alert('#fromsrlno'+i);
		$('#fromsrlno'+i).remove();
	}
	$('#quantity1').val('');
	$('#unitamt1').val('');
	$('#invamount1').val('');
	$('#productnamehidden1').val('');
	
	
}

function proceedforpurchase(getMessage)
{
	disableproceedbutton();
    var lastslno =  $('#lastslno').val();
    var displaycustomergst = $('#displaycustomergst').val();
    var displaycustomerid = $('#displaycustomerid').val();
    var displaycompanyname = $('#displaycompanyname').val();
    var displaycontactperson = $('#displaycontactperson').val();
    var displayaddress = $('#address').val();
    var displayphone = $('#displayphone').val();
    var displaycell = $('#displaycell').val();
    var displayemail = $('#displayemail').val();
    var displaytypeofcategory = '';
    var displaytypeofcustomer = '';
    if($('#displaytypeofcategory').val() == 'Not Available')
        displaytypeofcategory = '';
    else
        displaytypeofcategory=  $('#displaytypeofcategory').val();

    if($('#displaytypeofcustomer').val() == 'Not Available')
        displaytypeofcustomer = '';
    else
        displaytypeofcustomer =  $('#displaytypeofcustomer').val();

    var PurchaseType = document.getElementsByName("purchasetype[]");
	var productnamehidden = document.getElementsByName("productnamehidden[]");
	var productType = document.getElementsByName("producttype[]");
	var quantity = document.getElementsByName("quantity[]");
	var unitAmt = document.getElementsByName("unitamt[]");
	var invoiceAmount = document.getElementsByName("invamount[]");
	var fromsrlno = document.getElementsByName("fromsrlno[]");
	// var tosrlno = document.getElementsByName("tosrlno[]");

    var purchasearray=Array.prototype.slice.call(PurchaseType);
    var purchaselist = purchasearray.map((o) => o.value).join("#");

    var producthiddenarray=Array.prototype.slice.call(productnamehidden);
    var producthiddenlist = producthiddenarray.map((o) => o.value).join("*");

    var productarray=Array.prototype.slice.call(productType);
    var productlist = productarray.map((o) => o.value).join("#");

    var quantityarray=Array.prototype.slice.call(quantity);
    var quantitylist = quantityarray.map((o) => o.value).join(",");

    var unitamtarray=Array.prototype.slice.call(unitAmt);
    var unitamtlist = unitamtarray.map((o) => o.value).join("*");

    var invoiceamountarray=Array.prototype.slice.call(invoiceAmount);
    var invoiceamountlist = invoiceamountarray.map(x => x.value).join("*");

    var fromsrlnoarray=Array.prototype.slice.call(fromsrlno);
    var fromsrlnolist = fromsrlnoarray.map(x => x.value).join("~");

	var srlvalues = Array.from($("input[name='fromsrlno[]']").map(function(){return $(this).val();}).get());
	//console.log(srlvalues);

	//alert(fromsrlnolist);

    // var tosrlnoarray=Array.prototype.slice.call(tosrlno);
    // var tosrlnolist = tosrlnoarray.map(x => x.value).join("~");

    if(($("#salesperson").is(":visible"))) 
    {
        var dealername = $("#salesperson option:selected").text();
        var dealerid = $("#salesperson").val();
    }
    else
    {
        var dealername =  $("#dealernamehidden").val();
        var dealerid = $("#dealeridhidden").val();
    }
	var state_gst_code = $('#state_gst_code').val();
    var branchhidden = $('#branchhidden').val();

    var field = $('#seztax:checked').val();
	if(field != 'on') var seztaxtype = 'no'; else seztaxtype = 'yes';

    $("#proceedprocessingdiv").html('<img src="../images/imax-loading-image.gif" border="0" />');
    var passData = "switchtype=proceedforpurchase&purchasevalues=" + encodeURIComponent(purchaselist) + "&producthiddenvalues=" + encodeURIComponent(producthiddenlist) + "&quantityvalues=" + encodeURIComponent(quantitylist) + "&unitamtvalues=" + encodeURIComponent(unitamtlist)+ "&invoiceamountvalues=" + encodeURIComponent(invoiceamountlist)+ "&fromsrlnovalues=" + encodeURIComponent(fromsrlnolist)+ "&fromsrlno=" + encodeURIComponent(srlvalues)+ "&invoiceremarks=" + encodeURIComponent($('#invoiceremarks').val())+ "&paymentremarks=" + encodeURIComponent($('#paymentremarks').val())+ "&paymentamount=" + encodeURIComponent($('#paymentamount').val())+ "&lastslno=" + encodeURIComponent(lastslno) + "&customer_gstno=" + encodeURIComponent(displaycustomergst) + "&igstamount=" + encodeURIComponent($('#igst_tax_amount').val()) + "&cgstamount=" + encodeURIComponent($('#cgst_tax_amount').val()) + "&sgstamount=" + encodeURIComponent($('#sgst_tax_amount').val()) + "&dealerid=" + encodeURIComponent(dealerid) + "&dealername=" + encodeURIComponent(dealername) + "&customerid=" + encodeURIComponent(displaycustomerid)+ "&cusname=" + encodeURIComponent(displaycompanyname)+ "&cuscontactperson=" + encodeURIComponent(displaycontactperson)+ "&cusaddress=" + encodeURIComponent(displayaddress)+ "&cusemail=" + encodeURIComponent(displayemail)+ "&cusphone=" + encodeURIComponent(displayphone)+ "&cuscell=" + encodeURIComponent(displaycell)+ "&custype=" + encodeURIComponent(displaytypeofcustomer)+ "&cuscategory=" + encodeURIComponent(displaytypeofcategory)+ "&podate=" + encodeURIComponent($('#DPC_startdate').val())+ "&poreference=" + encodeURIComponent($('#poreference').val())+ "&state_gst_code=" + encodeURIComponent(state_gst_code)+ "&branchhidden=" + encodeURIComponent(branchhidden)+ "&gstcheck=" + encodeURIComponent(getMessage) + "&panno=" + encodeURIComponent($('#displaypanno').val()) + "&seztaxtype=" + (seztaxtype) + "&dummy=" + Math.floor(Math.random()*10054300000);
	//alert(passData);
    var queryString = "../ajax/matrixinvoicing.php";
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
                    alert(response[1]);
					enableproceedbutton();
                    $().colorbox.close();
                    $('#proceedprocessingdiv').html('');
					removerows();
                    $("#submitform")[0].reset();
                    //gridtoform(response['slno'],response['billslno']);
                    //generateinvoicedetails('');
                }
                else if(response[0] == 2)
                {
                    var gstinconfirm = confirm("GSTIN is not valid. Do you want to proceed further?");
					if(gstinconfirm)
					{
						//alert('true');
						//$('#gstinconfirm').val('gstinconfirm');
						proceedforpurchase('gstinconfirm');
					}
					else
					{
						$('#proceedprocessingdiv').html('');
						return false;
					}
                }
				else if(response[0] == 3)
				{
					alert(response[1]);
					$().colorbox.close();
				}
                
            }
        }, 
        error: function(a,b)
        {
            $("#proceedprocessingdiv").html(scripterror());
        }
    });
    
}

function disableproceedbutton()
{
	$('#proceed').removeClass('swiftchoicebutton');	
	$('#proceed').addClass('swiftchoicebuttondisabled');

}

function enableproceedbutton()
{
	$('#proceed').attr("disabled", false); 
	$('#proceed').removeClass('swiftchoicebuttondisabled');	
	$('#proceed').addClass('swiftchoicebutton');
	
}

function generateinvoicedetails()
{
	if($('#lastslno').val() == '')
		return false;
	else
	{
		var form = $('#submitform');
		var passdata = "switchtype=invoicedetailsgrid&lastslno="+ encodeURIComponent($('#lastslno').val()) + "&dummy=" + Math.floor(Math.random()*10054300000) ;
		var queryString = "../ajax/matrixinvoicing.php";
		// $('#invoicedetailsgridc1_1').html(getprocessingimage());
		// $('#invoicedetailsgridc1link').html('');
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

function getdealerdetails(dealerval)
{
	var error = $('#form-error');
	var dealerid = $("#salesperson").val();
	if(dealerid)
	{
		var passdata = "switchtype=getdealerdetails&dealerid="+ encodeURIComponent(dealerid) + "&dummy=" + Math.floor(Math.random()*10054300000);
		var queryString = "../ajax/matrixinvoicing.php";
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
						$('#branchhidden').val(response['branch_gst_code']);
						$('#branch_gstin').val(response['branch_gstin']);
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
	{
		$('#branchid').val('');
	}
	
}

function cancelpurchase()
{
	$('#form-error').html('');
	$().colorbox.close();
}

//Function to view the bill in pdf format----------------------------------------------------------------
function viewmatrixinvoice(slno)
{
	$('#invoicelastslno').val(slno);
	var form = $('#submitform');	
	// if($('#onlineslno').val() == '')
	// {
	// 	$('#productselectionprocess').html(errormessage('Please select a Customer.')); return false;
	// }
	// else
	// {
		$('#submitform').attr("action", "../ajax/viewmatrixinvoicepdf.php") ;
		$('#submitform').attr( 'target', '_blank' );
		$('#submitform').submit();
	//}
}