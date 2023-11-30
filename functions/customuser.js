$(document).ready(function(){
    $('#detailsprocessing').hide();
    $("#invoicedetails").hide();

    $("#select_all").click(function () {
       $(".productnames").attr('checked', $(this).attr('checked'));
   });
});

var totalarray = new Array();
var customerarray = new Array();
var customerarray1 = new Array();
var customerarray2 = new Array();
var customerarray3 = new Array();
var customerarray4 = new Array();
var process1;
var process2;
var process3;
var process4;

var invoicearray = new Array();
var rowcountvalue = 0;

//Get the customer list
function gettotalcustomercount()
{
	var form = $('#customerselectionprocess');
	var passData = "switchtype=getcustomercount&dummy=" + Math.floor(Math.random()*10054300000);
	queryString = "../ajax/customuser.php";
	$('#detailsprocessing').html(getprocessingimage());
	ajaxcall1 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
                beforeSend: function(){$('#detailsprocessing').show();},
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			
{				var response = ajaxresponse;
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
                complete: function(
			){$('#detailsprocessing').hide();},
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
	var form = $('#cardsearchfilterform');

	var passData = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex);
	var passData1 = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex1);
	var passData2 = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex2);
	var passData3 = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex3);
	$('#customerselectionprocess').html(getprocessingimage());
	queryString = "../ajax/customuser.php";
    $('#detailsprocessing').html(getprocessingimage());
	ajaxcall2 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
                beforeSend: function(){$('#detailsprocessing').show();},
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
				for( var i=0; i<response.length; i++)//alert(response)
				{
					customerarray1[i] = response[i];
				}
				process1 = true;
				compilecustomerarray();
                                
			}
		}, 
                complete: function(
			){$('#detailsprocessing').hide();},
		error: function(a,b)
		{
			$("#customerselectionprocess").html(scripterror());
		}
	});	
	
	queryString = "../ajax/customuser.php";
	$('#detailsprocessing').html(getprocessingimage());
	ajaxcall3 = $.ajax(
	{
		type: "POST",url: queryString, data: passData1, cache: false,dataType: "json",
                beforeSend: function(){$('#detailsprocessing').show();},
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
                complete: function(
			){$('#detailsprocessing').hide();},
		error: function(a,b)
		{
			$("#customerselectionprocess").html(scripterror());
		}
	});	

	queryString = "../ajax/customuser.php";
	$('#detailsprocessing').html(getprocessingimage());
	ajaxcall4 = $.ajax(
	{
		type: "POST",url: queryString, data: passData2, cache: false,dataType: "json",
                beforeSend: function(){$('#detailsprocessing').show();},
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
                complete: function(
			){$('#detailsprocessing').hide();},
		error: function(a,b)
		{
			$("#customerselectionprocess").html(scripterror());
		}
	});	
	
	queryString = "../ajax/customuser.php";
	$('#detailsprocessing').html(getprocessingimage());
	ajaxcall5 = $.ajax(
	{
		type: "POST",url: queryString, data: passData3, cache: false,dataType: "json", 
                beforeSend: function(){$('#detailsprocessing').show();},
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
                complete: function(
			){$('#detailsprocessing').hide();}, 
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
		$("#customerselectionprocess").html(successsearchmessage('All Customers...'))
		$("#CALBUTTONDPC_startdate").hide();
		getcustomerlist1();
		
	}
	else
	return false;
}


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

function customerdetailstoform(flag,data)
{	
 //flag =1 indicates customerid
 //flag =0 indicates invoice no
 
     if(flag == 1 && data !='')
     {
        var cusid = data;

        var passData = "switchtype=customerdetailstoform&lastslno=" + encodeURIComponent(cusid) +"&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData);
        

     }
     else if(flag == 0 && data !='')
     {

        var invoiceno = data;
        var passData = "switchtype=customerdetailstoforminvoice&lastslno=" + encodeURIComponent(invoiceno) +"&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData);
        $("#invoiceslists a").removeClass('active');
        $(this).addClass('linkactive');

     }
     
              $('#submitform').find('input:text, input:password, select, textarea').val('');
              $("input#save").attr('disabled', true); 
              $('#fetchdatatable .clearafter').html(''); 
              $('#viewpinvoice a').html('');  $(".additional").hide(); 

		//$('#customerselectionprocess').html('');
		var form = $('#submitform');
                
		$("#submitform" )[0].reset();
		//$('#customerdetailshide').show();
		$('#customerdetailsshow').hide();
		;
		var queryString = "../ajax/customuser.php";
         $('#detailsprocessing').html(getprocessingimage());       
		ajaxcall2 = $.ajax(
		{
			type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
                        beforeSend: function(){$('#detailsprocessing').show();},
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
					$('#searchcustomerid').val('');
					var response = (ajaxresponse);
					if(response['errorcode'] == '1')
					{

//Manual Addition of description Fields

if(response['e_mobile']==1){$('#check_mobile').attr('checked', true);}
if(response['e_desktop']==1){$('#check_desktop').attr('checked', true);}

$(".manualadd").remove();

var invoices_lists = response['invoices_list'];

var invoices_listing = invoices_lists.split(",");

var countlist;

for(countlist = 0; countlist < invoices_listing.length; countlist++ ) {

$("#invoiceslists").append('<a style="margin:5px;padding:5px;cursor: pointer;background: #006699;color:#fff" class="manualadd" onclick="customerdetailstoform(\'0\',\''+invoices_listing[countlist]+'\');" class="resendtext">'+invoices_listing[countlist]+'</a>');

}


var productbriefdescription = response['productbriefdescription'];
if(!productbriefdescription)
{
  productbriefdescription = "#";
}
var res_productbriefdescription = productbriefdescription.split("#");

var itembriefdescription = response['itembriefdescription'];
if(!itembriefdescription)
{
  itembriefdescription = "#";
}
var res_itembriefdescription = itembriefdescription.split("#");
var i,j;

var servicedescription = response['servicedescription'];
var res_servicedescription = servicedescription.split("*");


var description = response['description'];
if(description !== '')
{
var res_description = description.split("*");

for(i=0; i < res_description.length; i++ ) {
var res_description_split = res_description[i].split('$');
   if(res_productbriefdescription[i] === undefined){ res_productbriefdescription[i] = ''; }

$('#fetchdatatable > tbody:last-child').append('</tr><tr class="manualadd"><td bgcolor="#edf4ff">'+res_description_split[1]+' : </td><td bgcolor="#edf4ff"><input type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" name="updationprice[]" class="swifttext" id="updationprice" value="'+res_description_split[6]+'"></td><td bgcolor="#edf4ff">Product Description : </td><td bgcolor="#edf4ff"><input type="text" class="swifttext" name="briefdescription[]" id="briefdescription" value="'+res_productbriefdescription[i]+'"></td></tr>');

    }
}


for(i=0; i < res_servicedescription.length; i++ ) {

var res_servicedescription_split = res_servicedescription[i].split("$");

if(res_itembriefdescription[i] === undefined)
{
 res_itembriefdescription[i] = '';
}
$('#fetchdatatable > tbody:last-child').append('</tr><tr class="manualadd"><td bgcolor="#F7FAFF">'+res_servicedescription_split[1]+' : </td><td bgcolor="#F7FAFF"><input type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" class="swifttext" name="servicetype[]" value="'+res_servicedescription_split[2]+'"></td><td bgcolor="#F7FAFF">Item Description : </td><td bgcolor="#F7FAFF"><input type="text" name="itembriefdescription[]" class="swifttext" value="'+res_itembriefdescription[i]+'"></td></tr>');

}

var products = response['products'];
var res_products = products.split("#");
for (i=0; i < res_products.length; i++ ) {

}

var serviceamount = response['serviceamount'];
var res_serviceamount = serviceamount.split("#");
for (i=0; i < res_serviceamount.length; i++ ) {

}

var servicetype = response['servicetype'];
var res_servicetype = servicetype.split("~");
for (i=0; i < res_servicetype.length; i++ ) {

}

 //End of manual edition

			//$('#customerdetailshide').hide();
			$('#customerdetailsshow').show();
			enableformelemnts();
			disableitemselection();
			$('#lastslno').val(response['slno']);
			$('#displaycustomerid').html(response['customerid']);

//$('#displaycompanyname').html(response['companyname']);


var lastFive = response['customerid'].substr(response['customerid'].length - 5);

$("#displaycompanyname").html(response['companyname']);
                                                
						$('#onlineslno').val(response['invserial']);
						$('#onlinepslno').val(response['invpserial']);

      $("#viewinvoice").html('<a onclick="viewinvoice(\''+ response['invserial'] +'\');" class="resendtext">View Invoice</a><br/>');

      $("#viewpinvoice").html('<span style="padding:5px"><input name="save" type="button" class="swiftchoicebutton saveclass focus_redclass" id="save" value="Save" onclick="formsubmit(\'save\');" /> | <a onclick="viewperformainvoice(\''+ response['invpserial'] +'\');" class="resendtext">View Pro-forma Invoice</a> | <input type="button" class="swiftchoicebutton" value="Mail" class="swiftchoicebutton saveclass focus_redclass" id="indmail" onclick="mailtoindcus('+response["invpserial"]+');"></span><br/>');
    
                                             
                                                $('#displaycurrentdealer').html(response['dealername']);
						$('#finyear').html(response['year']);
						$('#amcinvno').html('<a onclick="viewinvoice(\''+ response['invserial'] +'\');" class="resendtext">'+response['invnumber']+'</a><br/>');
						$('#amcamt').html(response['amount']);
						//$('#yamcamt').html(response['f_amount']);
						//$('#uamcamt').val(response['amc_edited']);
						$('#amc_slno').val(response['amc_slno']);
						$('#mailcount').html(response['mail_count']);
                                                $('#invoiceremarks').val(response['invoiceremarks']);
                                                $('#invoicedate').html(response['invoicedate']);
                                                $('#amcemailid').val(response['amcemailid']);
                                                $('#renewaldate').html(response['renewaldate']);
						$('#custidtoview').val(response['customerid']);
 
                                                $("#dealerid").prepend('<option class="manualadd" value="'+response['dealerid']+'" selected="selected">'+ response['dealername']+'</option>');
                                                $('#customfilter').html(response['mail']);
                                              //$('#invoicedetail').html(response['invoice']);
                                                
					} 
				}

			}, 
                        complete: function(
			){$('#detailsprocessing').hide();},
			error: function(a,b)
			{
				$("#customerselectionprocess").html(scripterror());
                                //$('#customerdetailshide').html(scripterror());
			}
		});	
	
}


function viewinvoicedet(invno)
{
               var invno = slno;
               $('#viewcustomerdetails').attr("action", "http://imax.relyonsoft.in/home/index.php?a_link=manageinvoice&invoiceno="+invno); 
                    
		$('#viewcustomerdetails').attr( 'target', '_blank' );
		$('#viewcustomerdetails').submit();

  
}

function selectfromlist()
{
	$('#messagerow').html('<div align="center" style="height:15px;"><strong><font color="#FF0000">Select a Item First</font></strong></div>');
	var selectbox = document.getElementById('customerlist');
	var cusnamesearch = document.getElementById('detailsearchtext');
	cusnamesearch.value = selectbox.options[selectbox.selectedIndex].text;
	cusnamesearch.select();
	customerdetailstoform('1',selectbox.value);
       
//alert(selectbox.value);
        $("#displaycompanyname").attr("readonly","readonly");
        $("#displaycustomerid").attr("readonly","readonly");
        $("#finyear").attr("readonly","readonly");
        $("#amcinvno").attr("readonly","readonly");
        $("#amcamt").attr("readonly","readonly");
        
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
		var form = document.submitform;
		var input = document.getElementById('detailsearchtext').value;
		selectacustomer(input);
	}
}

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
	customerdetailstoform('1',cusid);
}


//Search customer by Invoice no
function searchbyinvoiceno(invoiceno)
{
	$('#detailsearchtext').val('');
	document.getElementById('form-error').innerHTML = '';
	var form = document.submitform;
	form.reset();
	var passData = "switchtype=searchbyinvoiceno&invoiceno=" + encodeURIComponent(invoiceno) + "&dummy=" + Math.floor(Math.random()*100032680100);
	var queryString = "../ajax/customuser.php";
	$('#detailsprocessing').html(getprocessingimage());
	ajaxcall4 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
                beforeSend: function(){$('#detailsprocessing').show();},
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var ajaxresponse = (ajaxresponse).split("#@#"); 
				var response1  = ajaxresponse[1];
				if(ajaxresponse[0] == '2')
				{
					alert('Invoice Not Available');
				}
				else
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
					/*$('#detailsearchtext').val(response[2])
					selectacustomer(response[2])
					$('#customerlist').val(response[1]);
					customerdetailstoform('1',response[1]);
					$('#searchinvoiceno').val('');*/
                                        
				}
			}
		}, 
                complete: function(
			){$('#detailsprocessing').hide();},
		error: function(a,b)
		{
			$("#form-error").html(scripterror());
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
		customerdetailstoform('1',splits[1]);
	}
}

function searchbycustomeridevent(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 13)
	{
		var input = document.getElementById('searchcustomerid').value;
		searchbycustomerid(input);
                //showcustomerdetail(input);
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


function resetdealername()
{
	$("#displaydealername").html('<font color="#FF0000">Not Selected</font>');
	$("#displaydealerdetailsicon").hide();
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






function zeroquantity(k)
{
	$("#quantitycheck"+k).val(0).attr("selected", "selected");
	 $(function () {
			$("#productquantity"+k).val('0');
		});
}

function getval(sel,k,purchasetypehtml,purchasetypehidden) {
      //alert(sel);
	 // alert($('#'+sel).val());
	  //alert(valuepur);
	  //alert(k);
	   //var valuepur = sel.value;
	   
	   var valuepur = $('#'+sel).val();
	   //alert(valuepur);
	   var totalpurchasecount = $('#hiddenpurchaseoflicense'+k).val();
	   //alert(totalpurchasecount);
	   var currentpurchasecount =$('#purchasecheck'+k).val();  //alert(currentpurchasecount);
	   var yearcountval = $('#yearcount'+k).val();
	   var purchase = $('#purchasetypehidden'+k).val();
	   var license = $('#productnamehidden'+k).val();
	   var incrementvalue = $('#incrementval').val();
	   
	   for(var z=1;z<k;z++)
	   {
		  if(z>=1)
		  {
			   //alert("1" + $('#productnamehidden'+z).val() + "=" + $('#productnamehidden'+k).val());
			  // alert("2" + $('#purchasetypehidden'+z).val());
			if(($('#productnamehidden'+z).val() == $('#productnamehidden'+k).val()) &&  
					($('#purchasetypehidden'+z).val() == "updation" || $('#purchasetypehidden'+k).val() == "updation" ) )
			{
			   //alert($('#hiddenproductoflicense'+z).val());
			   //alert("total=1");
				var totalcount = 1;
				//alert(totalcount);
			}
			if(totalcount!= 1)
			{
				 if(($('#productnamehidden'+z).val()!= $('#productnamehidden'+k).val())  &&  
						($('#purchasetypehidden'+k).val() == "updation"))
				{
					//alert("total=0");
					var totalcount = 0;
				}
			}
		  }
		  
	   }
	   var totcount = totalcount;
		var valj = 0;
		for(var i=1,j=k;i<=k;i++)
		{
			if(i > 1 && purchase == "new" && yearcountval == "")
			{
				//alert(i);
			      //alert("i is greater than 1.");
				  document.getElementById ("productquantity"+k).value = valuepur;
			}
			
			   
			if(i<k)
			{
				if(totcount == 1)
				{
					//alert("totalcount" + totcount);
				   if(($('#productnamehidden'+i).val() == $('#productnamehidden'+j).val()) &&  
					($('#purchasetypehidden'+k).val() == "updation"))
					{
						
						document.getElementById ("productquantity"+k).value = valuepur;
						 var x =0;
						 $("input[class=quantity_check_value]").each(function(f){
								var id = "#productquantity"+(f+1);
								var type_id = "#purchasetypehidden"+(f+1);
								var type = $(type_id).val(); 
								var product_id = "#productnamehidden"+(f+1);
								var product = $(product_id).val();
								y=parseInt($(id).val());
								if(type == "updation" && product == $('#productnamehidden'+j).val())
								{
								   x = x+y;
								}
								
							});
								
						if(currentpurchasecount==0)
						{
						   if(x <= totalpurchasecount)
						   {
							  //alert("match3");
							  document.getElementById ("productquantity"+k).value = valuepur;
						   }
						   else if(x > totalpurchasecount)
						   {
							  if(valj == 0)
							  {
								 var valj = i;
							  }
							  zeroquantity(k);
							  if(valj==i && valj!=0)
							  {
								  alert("You cannot select more than " + totalpurchasecount + " updation card");
								  $('#productamount'+k).val('');
							   }
						   }
						}
						else if(currentpurchasecount > 0)
						{
							var subtractedval1 = totalpurchasecount - currentpurchasecount;
							if(x<=subtractedval1)
							{
							   //alert("match8");
							   document.getElementById ("productquantity"+k).value = valuepur;
							}
							else if(x > subtractedval1 && subtractedval1 > 0)
							{
							   //alert("match9");
							  if(valj == 0)
							  {
								 var valj = i;
							  }
							  zeroquantity(k);
							  if(valj==i && valj!=0)
							  {
								  alert("You cannot select more than " + subtractedval1 + " updation card");
								  $('#productamount'+k).val('');
							   }
							}
							else if(subtractedval1 <= 0)
							{
								alert("You have 0 updation card");
								document.getElementById(purchasetypehtml).innerHTML = 'New';
								document.getElementById(purchasetypehidden).value = 'new';
								$('#purchasecheck'+k).val('');
								$('#yearcount'+k).val(''); 
								$('#hiddenpurchaseoflicense'+k).val('');
								$('#productamount'+k).val('');
								zeroquantity(k);
							}
						}
								
					}
				}
			   
			   else if(totcount == 0)
			   {
				   if(($('#productnamehidden'+i).val()!= $('#productnamehidden'+j).val())  &&  
					($('#purchasetypehidden'+j).val() == "updation"))
					//&& ($('#purchasetypehidden'+i).val()!="new")
					//&& ($('#purchasetypehidden'+i).val()!= "updation")
					{
					  for(var h=k;h<=incrementvalue;h++)
					  {
						   if((license == $('#productnamehidden'+h).val()) && 
						   (purchase == $('#purchasetypehidden'+h).val()))
						   {
							   if(h>k)
							   {
								 
								  $(function () {
									   $("#productquantity"+h).val('0');									  
								  });
								  $("#quantitycheck"+h).val(0).attr("selected", "selected");
							   }
						   }
						}
						
					
						if(currentpurchasecount==0)
						{
							if(valuepur <= totalpurchasecount)
						    {
							// alert("no match2");
							  document.getElementById ("productquantity"+k).value = valuepur;
						    }
							else if(valuepur > totalpurchasecount)
							{
							   //alert("no match3");
							    if(valj == 0)
								{
								 var valj = i;
								}
								zeroquantity(k);
								if(valj==i && valj!=0)
								{
								  alert("You can select only " + totalpurchasecount + " updation card");
								  $('#productamount'+k).val('');
								}
							}
						}
						else if(currentpurchasecount > 0)
						{
							var subtractedval2 = totalpurchasecount - currentpurchasecount;
							if(valuepur<=subtractedval2)
							{
							   //alert("no match9");
							  document.getElementById ("productquantity"+k).value = valuepur;
							}
							else if(valuepur > subtractedval2 && subtractedval2 > 0)
							{
								//alert("no match10");
							if(valj == 0)
							  {
								 var valj = i;
							  }
							  zeroquantity(k);
							  if(valj==i && valj!=0)
							  {
								  alert("You have " + subtractedval2 + " updation card");
								  $('#productamount'+k).val('');
							  }
							}
							else if(subtractedval2 <= 0)
							{
								alert("You have 0 updation card.");
								document.getElementById(purchasetypehtml).innerHTML = 'New';
								document.getElementById(purchasetypehidden).value = 'new';
								$('#purchasecheck'+k).val('');
								$('#yearcount'+k).val(''); 
								$('#hiddenpurchaseoflicense'+k).val('');
								$('#productamount'+k).val('');
								zeroquantity(k);
							}
						}
					}
						
					
				 }
			}
			else if(i==1)
			{
				if(purchase == "new" && yearcountval == "")
				{
					document.getElementById ("productquantity"+k).value = $('#'+sel).val();
				}
				else 
				{
					 for(var m=i;m<=incrementvalue;m++)
					  {
						   if((license == $('#productnamehidden'+m).val()) && 
						   (purchase == $('#purchasetypehidden'+m).val()))
						   {
							   if(m>k)
							   {
								   $(function () {
									        //alert("check");
											$("#productquantity"+m).val('0');
										  });
								  $("#quantitycheck"+m).val(0).attr("selected", "selected");
							   }
						   }
					   }
					   
					
						if(currentpurchasecount==0)
						{
							if(valuepur<=totalpurchasecount)
							{
							  document.getElementById ("productquantity"+k).value = valuepur; 
							}
							else if(valuepur>totalpurchasecount)
							{
								//check whether customer has past two or one year cards are available or not
								alert("You can select only " + totalpurchasecount + " updation card");
								$('#productamount'+k).val('');
								zeroquantity(k);
							}
						}
						else if(currentpurchasecount > 0)
						{
							var subtractedval = totalpurchasecount - currentpurchasecount;
							
							if(valuepur <= subtractedval)
							{
								document.getElementById ("productquantity"+k).value = valuepur;
							}
							else if(valuepur > subtractedval && subtractedval > 0)
							{
								//check whether customer has past two or one year are available or not
								alert("You have " + subtractedval + " updation card");
								$('#productamount'+k).val('');
								zeroquantity(k);
							}
							else if(subtractedval <= 0)
							{
								alert("You have 0 updation cards");
								document.getElementById(purchasetypehtml).innerHTML = 'New';
								document.getElementById(purchasetypehidden).value = 'new';
								$('#purchasecheck'+k).val('');
								$('#yearcount'+k).val(''); 
								$('#hiddenpurchaseoflicense'+k).val('');
								$('#productamount'+k).val('');
								zeroquantity(k);
							}
						}
					}
			}
		}
	
   
}
//Change purchase type
function editamountonpurchasetype(purchasetypehtml,purchasetypehidden,productselectedhidden,k,productnamehidden)
{
	
	var passData = "switchtype=newupdationchange&customerid=" + encodeURIComponent($('#lastslno').val()) +
	 "&productname=" + encodeURIComponent($('#productnamehidden'+k).val()) + 
	"&dummy=" + Math.floor(Math.random()*1000782200000);
	//alert(passData)
	$('#processing').show();
	$('#processing').html(getprocessingimage());
	var queryString = "../ajax/customuser.php";
	//error.html(getprocessingimage());
	//alert(k);
	
	ajaxcall1 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType:'json',
		success: function(ajaxresponse,status)
		{	
			var response = (ajaxresponse);
			
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
			 
			
			if(lasttwoyearcount == 0)
			{
				if((document.getElementById(purchasetypehidden).value == 'new') || (document.getElementById(purchasetypehidden).
				value == 'updation'))
				{
					$('#processing').hide();
					document.getElementById(purchasetypehtml).innerHTML = 'New';
					document.getElementById(purchasetypehidden).value = 'new';
					$('#purchasecheck'+k).val('');
					$('#yearcount'+k).val(''); 
					$('#hiddenpurchaseoflicense'+k).val('');
					$('#productamount'+k).val('');
					alert("Updation card is not available now");
				}
			}
			else if(lasttwoyearcount == 1)
			{
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
	for(i=0,j=1; i<productcount,j<=productcount; i++,j++)
	{	
		document.getElementById('productamount'+j).value = '';
		document.getElementById('totalamount').value = '';
		document.getElementById('sericetaxamount').value = ''; 
		document.getElementById('kk_cess').value = ''; 
		document.getElementById('sbtaxamount').value = ''; 
		document.getElementById('netamount').value  = '';
	}	
}


function enableordisabledescriptionfields(rowcount)
{
	var i = rowcount;
	var descriptiontype = '#descriptiontype'+i;
	var description = 'description'+i;
	var descriptionamount = 'descriptionamount'+i;
	if($(descriptiontype).val() != '')
	{
		$('#'+description).val('');
		$('#'+description).attr("disabled", false); 
		$('#'+descriptionamount).attr("disabled", false); 
		description1 = '#description'+(rowcount-1);
		$('#'+description).removeClass('swifttext-mandatory1');
		$('#'+description).addClass('swifttext');
		$('#'+descriptionamount).removeClass('swifttext-mandatory1');
		$('#'+descriptionamount).addClass('swifttext');
	}
	else
	{
		$('#'+description).val('Additional Charges');
		$('#'+description).attr("disabled", true); 
		$('#'+descriptionamount).attr("disabled", true); 
		$('#'+description).addClass('swifttext-mandatory1');
		$('#'+description).removeClass('swifttext');
		$('#'+descriptionamount).addClass('swifttext-mandatory1');
		$('#'+descriptionamount).removeClass('swifttext');
		$('#'+descriptionamount).val('');
	}
}


//New Product Entry
function newproductentry()
{
	$('#adddescriptionrowdiv').hide();
	$('#pricingdiv').hide();
	$('#adddescriptionrows tr').remove();
	$("#submitform" )[0].reset();
	$("#productcounthidden" ).val('');
	$("#form-error" ).html('');
	//$("#lastslno" ).val('');
	$("#offerremarkshidden" ).val('');
	$('#seletedproductgrid tr').remove();
	$("#seletedproductgrid").append('<tr class="tr-grid-header"><td width="9%" nowrap="nowrap" class="td-border-grid">Sl No</td><td width="27%" nowrap="nowrap" class="td-border-grid">Product</td><td width="15%" nowrap="nowrap" class="td-border-grid">Purchase Type</td><td width="13%" nowrap="nowrap" class="td-border-grid">Usage Type</td><td width="10%" nowrap="nowrap" class="td-border-grid">Quantity</td><td width="15%" nowrap="nowrap" class="td-border-grid">Unit Price</td><td width="11%" nowrap="nowrap" class="td-border-grid">Remove</td></tr><tr><td colspan="7" nowrap = "nowrap" class="td-border-grid" id="messagerow"><div align="center" style="height:15px;"><strong><font color="#FF0000">Select a Item First</font></strong></div></td></tr>');
	//hideorshowremarksdiv();
	removeofferremarksdiv();
	enableproceedbutton();
	hideorshowpaymentdetailsdiv();
	showhidepaymentinfodiv();
	showhidepaymentdiv();
	$('#displaydealerdetailsicon').hide();
	$('#resultdiv').hide();
	disableitemselection();
	resetdealername();
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
		//FY can be useful
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
		//$("img").attr('disabled', true); 
		$('#myfileuploadimage1').removeAttr('onclick');
	}
}

function disableitemselection()
{
	$('#product').attr('disabled', 'disabled');
	$('#product2').attr('disabled', 'disabled');
}

function enableitemselection()
{
	$('#product').removeAttr('disabled');
	$('#product2').removeAttr('disabled');
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
	$('#sericetaxamount').val('0');	
	$('#kk_cess').val('0');	
	$('#sbtaxamount').val('0');	
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
			amount = (amount) - descriptionamountvalues[i]*1;
			if(isNaN(amount))
				amount =0;
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
function generateinvoicedetails(startlimit)
{
	if($('#lastslno').val() == '')
	{
		$('#invoicedetailsgridwb1').html("");
		$('#invoicedetailsgridc1_1').html('<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid"  style="border-bottom:none;"><tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Generated By</td><td nowrap = "nowrap" class="td-border-grid" align="left">Action<input type="hidden" name="invoicelastslno" id="invoicelastslno" /><input type="hidden" name="filepathinvoicing" id="filepathinvoicing" /></td><td nowrap = "nowrap" class="td-border-grid" align="left">Email</td><td nowrap = "nowrap" class="td-border-grid" align="left">SEZ Download</td></tr><tr><td colspan="6" valign="top" class="td-border-grid"><div align="center">No datas found to display</div></td></tr></table>');
		$('#invoicedetailsgridc1link').html("");
		return false;
	}
	else
	{
		var form = $('#submitform');
		$('#invoicedetailsgridc1').show();
		$('#detailsdiv').hide();
		var passData = "switchtype=invoicedetailsgrid&lastslno="+ encodeURIComponent($('#lastslno').val()) + "&startlimit=" + encodeURIComponent(startlimit);
		var queryString = "../ajax/customuser.php";
		$('#invoicedetailsgridc1_1').html(getprocessingimage());
		$('#invoicedetailsgridc1link').html('');
		ajaxcall6= $.ajax(
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
					if(response[0] == 1)
					{
						$('#tabgroupgridwb2').html('');
						$('#invoicedetailsgridwb1').html("Total Count :  " + response[2]);
						$('#invoicedetailsgridc1_1').html(response[1]);
						$('#invoicedetailsgridc1link').html(response[3]);
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
	var passData = "switchtype=invoicedetailsgrid&lastslno="+ encodeURIComponent($('#lastslno').val()) + "&startlimit=" + startlimit+ "&slno=" + slno + "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);
	var queryString = "../ajax/customuser.php";
	$('#invoicedetailsgridc1link').html(getprocessingimage());
	ajaxcall7 = $.ajax(
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
				if(response[0] == '1')
				{
					$('#tabgroupgridwb2').html('');
					$('#invoicedetailsgridwb1').html("Total Count :  " + response[2]);
					$('#invoicedetailsresultgrid').html($('#invoicedetailsgridc1_1').html());
					$('#invoicedetailsgridc1_1').html($('#invoicedetailsresultgrid').html().replace(/\<\/table\>/gi,'')+ response[1] );
					$('#invoicedetailsgridc1link').html(response[3]);
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
	var inserial = $('#onlineslno').val();

        //alert(inserial);
		
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


function viewperformainvoice(slno)
{
	if(slno != '')
	//var inpserial = $('#onlinepslno').val();

        //alert(inpserial);
		
	var form = $('#submitform');	
	if($('#onlinepslno').val() == '')
	{
	  $('#productselectionprocess').html(errormessage('Please select a Customer.')); return false;
	}
	else
	{
		$('#submitform').attr("action", "../ajax/viewperformainvoicepdf.php") ;
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

function displayDiv()
{
	if($('#filterdiv').is(':visible'))
	{
		$('#filterdiv').hide();
	}
	else
	{
		$('#filterdiv').show();
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

function getdealerdetails()
{
	var form = $('#submitform');
	var field = $('#dealer');
	var customer = $('#customerlist');
	if(customer.val())
	{
		if(!field.val())
		{
			alert('Please select a dealer first'); field.focus(); return false;
		}
	}
	else
	{
		 //alert('Please select a Customer first'); field.focus(); return false;
	}
	var passData = "switchtype=getdealerdetails&dealerid="+ encodeURIComponent($('#dealer').val());//alert(passData);
	var queryString = "../ajax/customuser.php";
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
				if(response[0] == 1)
				{
					enableitemselection();
					$('#displaydealerdetailsicon').show();
					$('#dealeridhidden').val(response[1]);
					$('#displaydealername').html(response[2]);
					$('#displaydealerdetails').html(response[3]);
					$('#displaymarketingexe').html(response[2]);
					$('#dealer').val('');
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


function showdealerdetails()
{
	$("").colorbox({ inline:true, href:"#displaydealerdetails", onLoad: function() { $('#cboxClose').hide()}});
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


function resendinvoice(invoiceno)
{
	//alert(invoiceno);
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
			var passData = "switchtype=resendinvoice&invoiceno=" + encodeURIComponent(invoiceno) + "&dummy=" + Math.floor(Math.random()*10054300000);
			$('#resendprocess').show();
			$('#resendemail').hide();
			$('#resendprocess').html(getprocessingimage());	
			queryString = "../ajax/customuser.php";
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
						var response = ajaxresponse.split('^');
						if(response[0] == '1')
						{
							$('#resendprocess').hide();
							$('#resendemail').show();
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


function paynow(onlineslno)
{
	$('#onlineslno').val(onlineslno);
	$('#submitform').attr("action", "http://imax.relyonsoft.com/user/makepayment/pay.php") ;
	$('#submitform').attr( 'target', '_blank' );
	$('#submitform').submit();
}



function proceedforpurchase()
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
		  if(paymenttype == 'credit/debit')
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
		  var passData = "switchtype=proceedforpurchase&pricingtype=" +encodeURIComponent(pricingtype) + "&purchasevalues=" + encodeURIComponent(purchasevalues) + "&usagevalues=" + encodeURIComponent(usagevalues) + "&productamountvalues=" + encodeURIComponent(productamountvalues) + "&productquantityvalues=" + encodeURIComponent(productquantityvalues)  + "&descriptiontypevalues=" + encodeURIComponent(descriptiontypevalues) + "&descriptionvalues=" + encodeURIComponent(descriptionvalues) + "&descriptionamountvalues=" + encodeURIComponent(descriptionamountvalues)+ "&offeramount=" + encodeURIComponent(offeramount)+ "&inclusivetaxamount=" + encodeURIComponent(inclusivetaxamount)+ "&invoiceremarks=" + encodeURIComponent($('#invoiceremarks').val())+ "&paymentremarks=" + encodeURIComponent(paymentremarks)+ "&servicelist=" + encodeURIComponent(servicelist)+ "&serviceamountvalues=" + encodeURIComponent(serviceamountvalues)+ "&paymenttype=" + encodeURIComponent(paymenttype)+ "&lastslno=" + encodeURIComponent($('#lastslno').val())+ "&offerremarks=" + encodeURIComponent($('#offerremarkshidden').val()) + "&servicetaxamount=" + encodeURIComponent($('#sericetaxamount').val()) + "&kk_cess =" + encodeURIComponent($('#kk_cess').val()) + "&sbtaxamount=" + encodeURIComponent($('#sbtaxamount').val()) +  "&selectedcookievalue=" + encodeURIComponent(productlist)+ "&paymenttypeselected=" + encodeURIComponent($("input[name='paymentmodeselect']:checked").val())+ "&paymentmode=" + encodeURIComponent($("input[name='paymentmode']:checked").val()) + "&chequedate=" + encodeURIComponent($('#DPC_chequedate').val()) + "&duedate=" + encodeURIComponent($('#DPC_duedate').val()) + "&chequeno=" + encodeURIComponent($('#chequeno').val()) + "&drawnon=" + encodeURIComponent($('#drawnon').val()) + "&depositdate=" + encodeURIComponent($('#DPC_depositdate').val())+ "&paymentamount=" + encodeURIComponent($('#paymentamount').val())+ "&dealerid=" + encodeURIComponent($('#dealeridhidden').val()) + "&cusname=" + encodeURIComponent($('#displaycompanyname').html())+ "&cuscontactperson=" + encodeURIComponent($('#displaycontactperson').val())+ "&cusaddress=" + encodeURIComponent($('#displayaddress').val())+ "&cusemail=" + encodeURIComponent($('#displayemail').val())+ "&cusphone=" + encodeURIComponent($('#displayphone').val())+ "&cuscell=" + encodeURIComponent($('#displaycell').val())+ "&custype=" + encodeURIComponent($('#displaytypeofcustomer').html())+ "&invoicedated=" + encodeURIComponent(invoicedated)+ "&cuscategory=" + encodeURIComponent($('#displaytypeofcategory').html())+ "&servicelistvalues=" + encodeURIComponent(servicelistvalues) + "&privatenote=" + encodeURIComponent($('#privatenote').val())+ "&podate=" + encodeURIComponent($('#DPC_startdate').val())+ "&poreference=" + encodeURIComponent($('#poreference').val())+ "&productleveldescription=" + encodeURIComponent(productleveldescriptionlist)+ "&itemleveldescription=" + encodeURIComponent(itemleveldescriptionlist)+ "&seztaxtype=" + encodeURIComponent(seztaxtype)+ "&seztaxfilepath=" + encodeURIComponent($('#file_link').val())+ "&dummy=" + Math.floor(Math.random()*10054300000);
		  
		  queryString = "../ajax/customuser.php";
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
						  $('#onlineslno').val(response[2]);
						  $("#submitform").attr("action", "http://imax.relyonsoft.com/user/makepayment/pay.php");
						  $("#submitform").submit();	
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
	  }
	  else
		  return false;
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
	
	//$("#startdate").attr("id", "DPC_startdate");
	$("#DPC_startdate").val('');
	$("#DPC_startdate").removeClass("swifttext-readonly-border");
	$("#CALBUTTONDPC_startdate").show();
	$("#DPC_startdate").addClass("swifttext-mandatory");

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
	
	
	$("#displaycompanyname").val($("#cusnamehidden").val());
	$("#displaycontactperson").val($("#contactpersonhidden").val());
	$("#displayemail").val($("#emailhidden").val());
	$("#displayphone").val($("#phonehidden").val());
	$("#displaycell").val($("#cellhidden").val());
	$("#displaytypeofcustomer").val($("#custypehidden").val());
	$("#displaytypeofcategory").val($("#cuscategoryhidden").val());
	$("#displayaddress").val($("#addresshidden").val());
	
	$("#DPC_startdate").val('Not Avaliable');
	$("#DPC_startdate").removeClass("swifttext-mandatory");
	$("#CALBUTTONDPC_startdate").hide();
	$("#DPC_startdate").addClass("swifttext-readonly-border");
	
	$("#poreference").val('Not Avaliable');
	$("#poreference").removeClass("swifttext-mandatory");
	$("#poreference").addClass("swifttext-readonly-border");
	$("#poreference").attr("readonly","readonly"); 

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

function previewinvoice()
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
	if(paymenttype == 'credit/debit')
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
	$("#companynamepreview").html($("#displaycompanyname").html());
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
	var currentdate1 = day + "-" + month + "-" + year; 
	var currentdate2 = new Date(currentdate1.split('-')[2],currentdate1.split('-')[1],currentdate1.split('-')[0]);
	var expiredate1 = '3-4-2016';
	var expiredate2 = new Date(expiredate1.split('-')[2],expiredate1.split('-')[1],expiredate1.split('-')[0]);
	//alert (expiredate2.getTime() +' / '+ currentdate2.getTime())
	if(invoicedated == 'yes')
	{
		if(expiredate2.getTime() > currentdate2.getTime())
			$('#invoicedatepreview').html('31-03-2016 (23:55)');
		else
			$('#invoicedatepreview').html(" " +day + "-" + (month+1) + "-" + year + " (" + hours + ":" + minutes + ")");
	}
	else
	{
		$('#invoicedatepreview').html(" " +day + "-" + (month+1) + "-" + year + " (" + hours + ":" + minutes + ")");
	}
		/*
		if(currentdate > '04-04-2011')
			$('#invoicedatepreview').html(' 31-03-2011 (23:55)');
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
			var productgrid = '<tr ><td class="grey-td-border-grid" style="">' + k +'</td> <td colspan="2" nowrap="nowrap" class="grey-td-border-grid" style="text-align: left;"><font color="#FF0000">'+ productnames[i] +'</font> <br/> Purchase Type: <font color="#FF0000">'+purchasetype+'</font> / Usage Type: <font color="#FF0000">'+usagetype+'</font><br/><font color="#000"><strong>Product Description:</strong></font> '+productdescriptiontype+'   </td><td nowrap="nowrap" style="text-align: right;" class="grey-td-border-grid">'+ intToFormat(productamountvalues[i]) +'</td> </tr>';
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
			var productgrid = '<tr><td width="9%" class="grey-td-border-grid" style="">' + k +'</td> <td colspan="2" nowrap="nowrap" class="grey-td-border-grid" style="text-align: left;">'+ servicelistcount[i] +'<br/><font color="#000"><strong>Item Description:</strong></font>: '+itemdescriptiontype+'</td><td width="19%" nowrap="nowrap" style="text-align: right;" class="grey-td-border-grid">'+ intToFormat(serviceamountvaluessplit[i]) +'</td> </tr>';
			productgridrow = productgridrow+productgrid;
		}
	}
	if(descriptiontypevalues != '')
	{
		var descriptiontypevaluessplit = descriptiontypevalues.split('~');
		var descriptionvaluessplit = descriptionvalues.split('~');
		var descriptionamountvaluessplit = descriptionamountvalues.split('~');
		for(i=0;i<descriptiontypevaluessplit.length;i++)
		{
			k++;
			var productgrid = '<tr><td width="9%" class="grey-td-border-grid" style="">' + k +'</td> <td colspan="2" nowrap="nowrap" class="grey-td-border-grid" style="text-align: left;">'+ descriptiontypevaluessplit[i] +': '+descriptionvaluessplit[i]+'</td><td width="19%" nowrap="nowrap" style="text-align: right;" class="grey-td-border-grid">'+ intToFormat(descriptionamountvaluessplit[i]) +'</td> </tr>';
			productgridrow = productgridrow+productgrid;
		}
	}
	var offerremarks= $('#offerremarkshidden').val();
	if(offerremarks != '')
	{
		var productgrid = '<tr><td width="9%" class="grey-td-border-grid" style="">&nbsp;</td> <td colspan="2" nowrap="nowrap" class="grey-td-border-grid" style="text-align: left;">'+ offerremarks +'</td><td width="19%" nowrap="nowrap" style="text-align: right;" class="grey-td-border-grid">&nbsp;</td> </tr>';
		productgridrow = productgridrow+productgrid;
	}
	var emptyrow = '<td colspan="3" class="grey-td-border-grid"  style="border-right:none"><br/><br/></td><td style="text-align: right;" class="grey-td-border-grid" >&nbsp;</td>';
	var amountgrid = '<tr><td colspan="2" class="grey-td-border-grid"></td><td valign="top" class="grey-td-border-grid"  width="12%" style="border-left:none"><div align = "right">Net Amount</div></td><td nowrap="nowrap" style="text-align: right;" class="grey-td-border-grid" valign="top" ><span id="netamountpreview"></span></td></tr><tr><td colspan="2" class="grey-td-border-grid"  style="border-right:none"><span style="font-size:7px;color:#FF0000;text-align:left" >'+sezremarks+'</span></td><td valign="top" class="grey-td-border-grid" width="20%"  style="border-left:none"><div align = "right">Service Tax @ 14%</div></td><td nowrap="nowrap" style="text-align: right;" class="grey-td-border-grid" id="servicetaxpreview">&nbsp;</td></tr><tr><td colspan="2" class="grey-td-border-grid"  style="border-right:none"></td><td valign="top" class="grey-td-border-grid" width="20%"  style="border-left:none"><div align = "right">SB Cess @ 0.5%</div></td><td nowrap="nowrap" style="text-align: right;" class="grey-td-border-grid" id="sbtaxpreview">&nbsp;</td></tr><tr><td colspan="2" class="grey-td-border-grid"  style="border-right:none"></td><td valign="top" class="grey-td-border-grid" style="border-left:none"><div align = "right">KK Cess @ 0.5%</div></td><td nowrap="nowrap" style="text-align: right;" class="grey-td-border-grid" id="kk_cesstaxpreview">&nbsp;</td></tr><tr><td colspan="2" class="grey-td-border-grid"  style="border-right:none"></td><td valign="top" class="grey-td-border-grid" style="border-left:none"><div align = "right">Total</div></td><td nowrap="nowrap" style="text-align: right;" class="grey-td-border-grid" id="totalamountpreview">&nbsp;</td></tr><tr><td colspan="4" class="grey-td-border-grid" style="border-right:none">Rupee In Words: <span id="rupeeinwordspreview"></span></td> </tr>';
	
	$("#previewproductgrid").append(productgridheader+productgridrow+emptyrow+amountgrid);
	$("#netamountpreview").html(intToFormat($("#totalamount").val()));
	$("#servicetaxpreview").html(intToFormat($("#sericetaxamount").val()));
	$("#kk_cesstaxpreview").html(intToFormat($("#kk_cess").val()));
	$("#sbtaxpreview").html(intToFormat($("#sbtaxamount").val()));
	$("#totalamountpreview").html(intToFormat($("#netamount").val()));
	$("#rupeeinwordspreview").html(NumbertoWords($("#netamount").val()));
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
	var queryString = "../ajax/customuser.php";
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
						$('#invoicedetailsgridwb1').html('');
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
	var queryString = "../ajax/customuser.php";
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
					$('#invoicedetailsgridwb1').html('');
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
	$("").colorbox({ height:"425px", inline:true, href:"#inline_example1" , onLoad: function() { $('#cboxClose').hide()}});
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
	
	var row = '<tr id="removedescriptioncontactrow'+ slno+'"><td  width="5%"><div align="left"><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td><td width="11%"><div align="center"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:110px" class="swiftselect-mandatory type_enter focus_redclass"><option value="" selected="selected" >--Select--</option><option value="general" >General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="ithead/edp">IT-Head/EDP</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="CA" >CA</option><option value="others" >Others</option></select></div></td><td width="16%"><div align="center"><input name="name'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="name'+ slno+'"  style="width:110px"  maxlength="130"  autocomplete="off"/></div></td><td width="18%"><div align="center"><input name="phone'+ slno+'" type="text"class="swifttext  type_enter focus_redclass" id="phone'+ slno+'" style="width:95px" maxlength="100"  autocomplete="off" /></div></td><td width="15%"><div align="center"><input name="cell'+ slno+'" type="text" class="swifttext  type_enter focus_redclass" id="cell'+ slno+'" style="width:95px"  maxlength="10"  autocomplete="off"/></div></td><td width="27%"><div align="center"><input name="emailid'+ slno+'" type="text" class="swifttext  type_enter focus_redclass" id="emailid'+ slno+'" style="width:130px"  maxlength="200"  autocomplete="off"/></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removecontactrowdiv'+ slno+'" onclick ="removedescriptionrowscontact(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" /></td></tr>';
	
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
		error.html(errormessage("Minimum of ONE contact detail is mandatory")); 
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
		var row = '<tr id="removedescriptioncontactrow'+ slno+'"><td  width="5%"><div align="left"><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td><td width="11%"><div align="center"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:110px" class="swiftselect-mandatory  type_enter focus_redclass"><option value="" selected="selected" >--Select--</option><option value="general">General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="ithead/edp">IT-Head/EDP</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="CA" >CA</option><option value="others" >Others</option></select></div></td><td width="16%"><div align="center"><input name="name'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="name'+ slno+'"  style="width:110px"  maxlength="130"  autocomplete="off"/></div></td><td width="18%"><div align="center"><input name="phone'+ slno+'" type="text"class="swifttext type_enter focus_redclass" id="phone'+ slno+'" style="width:95px" maxlength="100"  autocomplete="off" /></div></td><td width="15%"><div align="center"><input name="cell'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="cell'+ slno+'" style="width:95px"  maxlength="10"  autocomplete="off"/></div></td><td width="27%"><div align="center"><input name="emailid'+ slno+'" type="text" class="swifttext type_enter focus_redclass default" id="emailid'+ slno+'" style="width:130px"  maxlength="200"  autocomplete="off"/></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removecontactrowdiv'+ slno+'" onclick ="removedescriptionrowscontact(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" /></td></tr>';
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


function formsubmit(command)
{	


	$('#save').removeClass('button_enter1');
	var passData = "";
	var form = $("#submitform" );
	var error = $("#form-error" );
	var amc_slno = '';
	var uamcamt = '';
        
        if(command == 'save')
	{
	  	var field = $("#amc_slno");
		if(!field.val()) 
		{ 
			error.html(errormessage('Enter the Updated AMC Amount.')); field.focus(); return false; 
		}
		else
		{

                     var servicetypes = [];
                     $("input[name='servicetype[]']").each(function(i){
                      servicetypes[i] = $(this).val();
                    });
                     var updationprices = [];
                     $("input[name='updationprice[]']").each(function(i){
                    updationprices[i] = $(this).val();
                    });
                    var itembriefdescriptions = [];
                     $("input[name='itembriefdescription[]']").each(function(i){
                    itembriefdescriptions[i] = $(this).val();
                    });
                    var briefdescriptions = [];
                     $("input[name='briefdescription[]']").each(function(i){
                      briefdescriptions[i] = $(this).val();
                    });
                    var productnametypes = [];
                     $("select[name='productnametypes[]']").each(function(i){
                      productnametypes[i] = $(this).val();
                    });
                    var usagetypes = [];
                     $("select[name='usagetypes[]']").each(function(i){
                      usagetypes[i] = $(this).val();
                    });
                    var updationprice_new = [];
                     $("input[name='updationprice_new[]']").each(function(i){
                      updationprice_new[i] = $(this).val();
                    });
                    var briefdescription_new = [];
                     $("input[name='briefdescription_new[]']").each(function(i){
                      briefdescription_new[i] = $(this).val();
                    });
                    var servicenametypes = [];
                     $("select[name='servicenametypes[]']").each(function(i){
                      servicenametypes[i] = $(this).val();
                    });
                    var servicetype_new = [];
                     $("input[name='servicetype_new[]']").each(function(i){
                      servicetype_new[i] = $(this).val();
                    });
                    var itembriefdescription_new = [];
                     $("input[name='itembriefdescription_new[]']").each(function(i){
                      itembriefdescription_new[i] = $(this).val();
                    });
                          var mob1=0;var desk1=0;
                         if($('#check_mobile').is(':checked')){ mob1=1; }
                         if($('#check_desktop').is(':checked')){ desk1=1; }
			var passData = '';
       			passData = "switchtype=save" + "&amc_slno="+ encodeURIComponent($('#amc_slno').val())+"&invoicelastslno="+encodeURIComponent($('#onlinepslno').val())+"&updationprice="+encodeURIComponent(updationprices)+"&servicetype="+encodeURIComponent(servicetypes)+"&invoiceremarks="+encodeURIComponent($('#invoiceremarks').val())+"&itembriefdescription="+encodeURIComponent(itembriefdescriptions)+"&briefdescription="+encodeURIComponent(briefdescriptions)+"&productnametypes="+encodeURIComponent(productnametypes)+"&usagetypes="+encodeURIComponent(usagetypes)+"&updationprice_new="+encodeURIComponent(updationprice_new)+"&briefdescription_new="+encodeURIComponent(briefdescription_new)+"&servicenametypes="+encodeURIComponent(servicenametypes)+"&servicetype_new="+encodeURIComponent(servicetype_new)+"&itembriefdescription_new="+encodeURIComponent(itembriefdescription_new)+"&invoicelastslno="+encodeURIComponent($('#onlinepslno').val())+"&amcemailid="+encodeURIComponent($('#amcemailid').val())+ "&dealerid="+ encodeURIComponent($('#dealerid').val())+"&mobile="+encodeURIComponent(mob1)+"&desktop="+encodeURIComponent(desk1);
//alert(passData);
			queryString = '../ajax/customuser.php';
			error.html(getprocessingimage());
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

						var responsesplit = ajaxresponse;

						if(responsesplit['errorcode'] == '1')
						{ 
							error.html(successmessage(responsesplit['errormessage']));
                                                      //$("#submitform").reset(); 
                                                       $('#submitform').find('input:text, input:password, select, textarea').val('');
                                                       $("input#save").attr('disabled', true); 
                                                      $('#fetchdatatable .clearafter').html(''); 
                                                      $('#viewpinvoice a').html('');  $(".additional").hide();                                                                                                          $("#check_mobile").reset();  
 $("#check_desktop").reset();  
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


function viewinvoicetable()
{
$("#invoicedetails").show();
$("#maildetails").hide();
}
function viewmailtable()
{
$("#invoicedetails").hide();
$("#maildetails").show();
}


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

		default:$("#districtcodedisplaysearch").html('<select name="district2" class="swiftselect" id="district2" style="width:180px;"><option value="">ALL</option></select>') ;
			
	}
}

function searchcustomerarray(callstatus)
{
	var form = $("#searchfilterform");
	var form = $("#submitform");
	var error = $("#filter-form-error");
        var invoicefromdate = $("#DPC_fromdate").val(); 
        var serviceflag = [];
                     $("input[name='servicetypeflag[]']:checked").each(function(i){
                    serviceflag[i] = $(this).val();
                    });
        var invoicetodate = $("#DPC_todate").val();
	var values = validateproductcheckboxes();
	if(values == false) {error.html(errormessage("Select A Product")); return false;	}
        if(values == true)  {error.html("");}    
	var textfield = $("#searchcriteria").val();
	var subselection = $("input[name='databasefield']:checked").val();
	var c_value = '';
	var newvalue = new Array();
	var chks = $("input[name='productarray[]']");
	var product = [];
                     $("input[name='productname[]']:checked").each(function(i){
                    product[i] = $(this).val();
                    });
	
	var productslist = c_value.substring(0,(c_value.length-1));
	var passData = "switchtype=advancesearchcustomerlist&databasefield=" + encodeURIComponent(subselection) + "&region=" +encodeURIComponent($("#region2").val())+ "&textfield=" +encodeURIComponent(textfield) +  "&product=" +encodeURIComponent(product) +"&dealer2=" +encodeURIComponent($("#currentdealer2").val()) + "&branch2=" + encodeURIComponent($("#branch2").val()) +"&invoicefromdate=" + encodeURIComponent(invoicefromdate) +"&invoicetodate=" + encodeURIComponent(invoicetodate)+"&serviceflag=" + encodeURIComponent(serviceflag)+"&operatorr=" + encodeURIComponent($("#operatorr").val())+"&mailcountsearch=" + encodeURIComponent($("#mailcountsearch").val()) + "&dummy=" + Math.floor(Math.random()*10054300000);
	//alert(passData)
		//$('#customerselectionprocess').html(getprocessingimage());
		queryString = "../ajax/customuser.php";
		ajaxcall3 = $.ajax(
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
					if(response == '')
                                        {
					                $('#filterdiv').show();
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
							$('#filterdiv').hide();
                                                        //alert(response);
							customersearcharray = new Array();
							for( var i=0; i<response.length; i++)
							{
								customersearcharray[i] = response[i];
							}
							flag = false;
							getcustomerlistonsearch();
							$("#customerselectionprocess").html(successmessage("Search Result"  + '<span style="padding-left: 15px;"><img src="../images/close-button.jpg" width="15" height="15" align="absmiddle" style="cursor: pointer; padding-bottom:2px" onclick="displayalcustomer()"></span> '));
							$("#totalcount").html(customersearcharray.length);
							$("#filter-form-error").html();
							

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
	$('input.focus_redclass,select.focus_redclass,textarea.focus_redclass').removeClass("css_enter1"); 
	$('input.focus_redclass,select.focus_redclass,textarea.focus_redclass').removeClass("checkbox_enter1");
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

function validateproductcheckboxes()
{
var chksvalue = $("input[name='productname[]']");
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

function mailtoindcus(slnum12345)
{
 
    var pinv_slno=slnum12345;
   passData = "passtype=mailcustomer&pinv_slno=" + encodeURIComponent(pinv_slno);

   queryString = "../ajax/mailcustomers1.php";
   $.ajax({
                         type: 'POST', url: queryString , data: passData, cache: false,
                            
                         success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse; //alert(response);
                                response1=response.toLowerCase();
                                 var checkerror="failed";
                                 var checkpermission="mailing option is not enable for this customer";
                                 if(response1.indexOf(checkerror) != -1)

                                {
                                  alert("Mail not sent(Error Occured) ");
                                }
                                else if(response1.indexOf(checkpermission) != -1)
                                 {
                                   alert(response);
                                 }
                                else
                                 {
                                  alert("Mail sent successfully");
                                 }

                        }
                                 
		},

		error: function(a,b)
		{
			$("#form-error").html(scripterror());
		}
          });
   
   
  
}




$(document).ready(function(){

    $(".additional").hide();

    $("#hide").click(function(){
        $(".additional").hide();
    });
    $("#show").click(function(){
        $(".additional").show();
    });
});



//Alert box design
var ALERT_TITLE = "Mail Confirmation";
var ALERT_BUTTON_TEXT = "Ok";

if(document.getElementById) {
	window.alert = function(txt) {
		createCustomAlert(txt);
	}
}

function createCustomAlert(txt) {
	d = document;

	if(d.getElementById("modalContainer")) return;

	mObj = d.getElementsByTagName("body")[0].appendChild(d.createElement("div"));
	mObj.id = "modalContainer";
	mObj.style.height = d.documentElement.scrollHeight + "px";
	
	alertObj = mObj.appendChild(d.createElement("div"));
	alertObj.id = "alertBox";
	if(d.all && !window.opera) alertObj.style.top = document.documentElement.scrollTop + "px";
	alertObj.style.left = (d.documentElement.scrollWidth - alertObj.offsetWidth)/2 + "px";
	alertObj.style.visiblity="visible";

	h1 = alertObj.appendChild(d.createElement("h1"));
	h1.appendChild(d.createTextNode(ALERT_TITLE));

	msg = alertObj.appendChild(d.createElement("p"));
	//msg.appendChild(d.createTextNode(txt));
	msg.innerHTML = txt;

	btn = alertObj.appendChild(d.createElement("a"));
	btn.id = "closeBtn";
	btn.appendChild(d.createTextNode(ALERT_BUTTON_TEXT));
	btn.href = "#";
	btn.focus();
	btn.onclick = function() { removeCustomAlert();return false; }

	alertObj.style.display = "block";
	
}

function removeCustomAlert() {
	document.getElementsByTagName("body")[0].removeChild(document.getElementById("modalContainer"));
}
function ful(){
alert('Alert this pages');
}