$(document).ready(function(){
$("#showloadpic1").hide();
$("#mailmsg").hide();
   $("#select_all").click(function () {
       $(".productnames").attr('checked', $(this).attr('checked'));
   });
 $("#all-customer-filter").click(function(){
       
        $(".customer-list").attr('checked', $(this).attr('checked'));
    });
});

function showall()
{
formsubmit('showall');
}
function showmore()
{
 
 localStorage.clickcount = Number(localStorage.clickcount)+10;
 formsubmit('showmore');
}

function formsubmit(command)
{

                     if(command=='filter')
                        {
                           localStorage.clickcount=10;
                        }
                     if(command=='showall')
                        {
                           localStorage.clickcount=0;
                        }


        var limit=localStorage.clickcount;
        
	
        var error = $("#filter-form-error");
        var invoicefromdate = $("#DPC_fromdate").val(); 
        var invoicetodate = $("#DPC_todate").val();
	var values = validateproductcheckboxes();
	if(values == false)	{$('#filter-form-error').show();error.html(errormessage("Select A Product")); return false;}
        else{$('#filter-form-error').hide();}
	var textfield = $("#searchcriteria").val();
	var subselection = $("input[name='databasefield']:checked").val();
	var c_value = '';
	var newvalue = new Array();
	var chks = $("input[name='productarray[]']");
	var product = [];
                     $("input[name='productname[]']:checked").each(function(i){
                    product[i] = $(this).val();
                    });
	var serviceflag = [];
                     $("input[name='servicetypeflag[]']:checked").each(function(i){
                    serviceflag[i] = $(this).val();
                    });
	var productslist = c_value.substring(0,(c_value.length-1));
	var passData = "switchtype=getcustomerlist&databasefield=" + encodeURIComponent(subselection) + "&region=" +encodeURIComponent($("#region2").val())+ "&textfield=" +encodeURIComponent(textfield) +  "&product=" +encodeURIComponent(product) +"&dealer2=" +encodeURIComponent($("#currentdealer2").val()) + "&branch2=" + encodeURIComponent($("#branch2").val()) +"&invoicefromdate=" + encodeURIComponent(invoicefromdate) +"&invoicetodate=" + encodeURIComponent(invoicetodate)+"&limit=" + encodeURIComponent(limit) +"&serviceflag=" + encodeURIComponent(serviceflag)+"&operatorr=" + encodeURIComponent($("#operatorr").val())+"&mailcountsearch=" + encodeURIComponent($("#mailcountsearch").val()) + "&dummy=" + Math.floor(Math.random()*10054300000);
	//alert(passData)
		//$('#customerselectionprocess').html(getprocessingimage());
		queryString = "../ajax/mailtoamccustomer.php";
		ajaxcall3 = $.ajax(
		{
			type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
			beforeSend: function(){$('#showloadpic').show();$('#showloadpic1').show();},
                        success: function(ajaxresponse,status)
			{	
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					var response = ajaxresponse;//alert(response);
					$('#amccustomerfilter').html(response['grid']);
                                        $('#total').html(response['total']);
				}
			}, 
                       complete: function(
			){$('#showloadpic').hide();$('#showloadpic1').hide();},
			error: function(a,b)
			{
				$("#customerselectionprocess").html(scripterror());
			}
		});	

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
function validatecustomercheckboxes()
{
var chksvalue1 = $("input[name='customersl[]']");
var hasChecked1 = false;
for (var i = 0; i < chksvalue1.length; i++)
{
	if ($(chksvalue1[i]).is(':checked'))
	{
		hasChecked1 = true
		return true
	}
}
	if (!hasChecked1)
	{
		return false;
	}
}
function mailtoo()
{
  var checked_customer = validatecustomercheckboxes();
    
   if(checked_customer)
   {
     $("#form-error").html('');
     var pinv_slno = [];
   $("input[name='customersl[]']:checked").each(function(){              
                    pinv_slno.push($(this).val());
                   
   });
   passData = "passtype=mailcustomer&pinv_slno=" + encodeURIComponent(pinv_slno);

   queryString = "../ajax/mailcustomers1.php";
   $.ajax({
                         type: 'POST', url: queryString , data: passData, cache: false,
                          beforeSend: function(){$("#mailmsg").show();},  
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
                                  response=response.toLowerCase();
                                 var checkerror="failed";
                                 if(response.indexOf(checkerror) != -1)

                                {
                                  alert("Mail not sent(Error Occured) ");
                                }
                                else
                                 {
                                   alert("Mail  sent Successfully");
                                 }
			}
		},
             complete: function(
			){$('#mailmsg').hide();},

		error: function(a,b)
		{
			$("#form-error").html(scripterror());
		}
          });
   
   }
   else
   {
      $("#form-error").html('<div class="errorbox">No Customers are selected</div>');
   }
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


function viewperformainvoicee(slno)
{
	        $('#invoicelastslno').val(slno);
	
		$('#submitformm').attr("action", "../ajax/viewperformainvoicepdf.php") ;
		$('#submitformm').attr( 'target', '_blank' );
		$('#submitformm').submit();
	

}





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


