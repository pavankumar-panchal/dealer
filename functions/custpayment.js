var customerarray = new Array();


function formsubmit()
{
	var form = $('#submitform');
	var error = $('#form-error');
	var cusslno = $('#cusslno').val();
	var field = $('#productdecription');
	if(!field.val()) { error.html(errormessage("Enter the Product description. ")); field.focus(); return false; }
	var field = $('#billref');
	if(!field.val()) { error.html(errormessage("Enter the Bill References. ")); field.focus(); return false; }
	var field = $('#paymentamt');
	if(!field.val()) { error.html(errormessage("Enter the Amount. ")); field.focus(); return false; }
	if(!validateamount(field.val())) { error.html(errormessage('Enter a Valid Amount.')); field.focus(); return false; }
	if($('#paymentstatus').html() == 'UNPAID')
	{
		var paymentstatus = 'UNPAID';
	}
	else
	{
		var paymentstatus = $("#paymentstatus option:selected").val();
	}
	var field = $('#remarks');
	if(!field.val()) { error.html(errormessage("Enter the Remarks. ")); field.focus(); return false; }
	else
	{
		var passData ="switchtype=save&customerreference=" + encodeURIComponent($('#cusslno').val()) + "&remarks=" + encodeURIComponent($('#remarks').val()) + "&billref=" + encodeURIComponent($('#billref').val()) +  "&paymentamt=" + encodeURIComponent($('#paymentamt').val())  + "&Paymentdesc=" + encodeURIComponent($('#productdecription').val())+ "&paymentstatus=" + encodeURIComponent(paymentstatus) + "&lastslno=" + encodeURIComponent($('#lastslno').val()) + "&dummy=" + Math.floor(Math.random()*100000000);         //alert(passData)

		queryString = '../ajax/custpayment.php';
		var ajaxcall0 = createajax();//alert(passData);
		error.innerHTML = getprocessingimage();
		ajaxcall0.open('POST', queryString, true);
		ajaxcall0.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		ajaxcall0.onreadystatechange = function()
		{
			if(ajaxcall0.readyState == 4)
			{
				if(ajaxcall0.status == 200)
				{
					var ajaxresponse = ajaxcall0.responseText;
					if(ajaxresponse == 'Thinking to redirect')
					{
						window.location = "../logout.php";
						return false;
					}
					else
					{
						var response = ajaxresponse.split('^');//alert(ajaxresponse)
						if(response[0] == '1')
						{
							error.html(successmessage(response[1]));
							cuspaymentdatagrid('',cusslno);
							newentry();
						}
						else if(response[0] == '2')
						{
							error.html(successmessage(response[1]));
							cuspaymentdatagrid('',cusslno);
							newentry();
						}
						else if(response[0] == '3')
						{
							error.html(errormessage(response[1]));
							newentry();
						}
						else
						{
							error.html(errormessage('Unable to Connect...' + ajaxcall0.responseText));
						}
					}
				}
				else
					error.html(scripterror());
			}
		}
		ajaxcall0.send(passData);
	}
}

function refreshcustomerarray()
{
	var passData = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000);//alert(passData)
	var ajaxcall1 = createajax();
	$('#customerselectionprocess').html(getprocessingimage());
	queryString = "../ajax/custpayment.php";//alert(queryString)
	ajaxcall1.open("POST", queryString, true);
	ajaxcall1.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall1.onreadystatechange = function()
	{
		if(ajaxcall1.readyState == 4)
		{
			if(ajaxcall1.status == 200)
			{
				var response = ajaxcall1.responseText.split('^*^');
				if(response == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					customerarray = new Array();
					for( var i=0; i<response.length; i++)
					{
						customerarray[i] = response[i];
					}
					getcustomerlist1();
					$('#customerselectionprocess').html('');
				}
			}
			else
				$('#customerselectionprocess').html(scripterror());
		}
	}
	ajaxcall1.send(passData);
	
}

function getcustomerlist1()
{	
	//disableformelemnts();
	var form = $("#filterform");
	var selectbox = $("#customerlist");
	var numberofcustomers = customerarray.length;
	$("#detailsearchtext").focus();
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

function newentry()
{
	var form = $('#submitform');
	form[0].reset();
	$('#lastslno').val('');
	enablesave();
	$('#paymentdate').html('Not Avaliable');
	$('#paymentstatus').html('UNPAID');
	displayentry();
	if($('#resendreq'))
	$('#resendreq').hide();
}

function gridtoform(slno)
{
	if(slno != '')
	{//alert(slno);
		var form = $('#submitform');
		var error = $('#form-error');
		error.html('');
		 $('#lastslno').val(slno);
		var passData = "switchtype=gridtoform&cusinteractionslno=" + encodeURIComponent(slno) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
		ajaxcall2 = createajax();
		var queryString = "../ajax/custpayment.php";
		ajaxcall2.open("POST", queryString, true);
		ajaxcall2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajaxcall2.onreadystatechange = function()
		{
			if(ajaxcall2.readyState == 4)
			{
				if(ajaxcall2.status == 200)
				{
					var ajaxresponse = ajaxcall2.responseText;
					if(ajaxresponse == 'Thinking to redirect')
					{
						window.location = "../logout.php";
						return false;
					}
					else
					{
						var response = ajaxresponse.split('^');
						if(response[0] == '1' )
						{
							$('#displaycustomername').html(response[1]);
							$('#paymentdate').html(response[2]);
							$('#remarks').val(response[3]);
							$('#productdecription').val(response[4]);
							$('#billref').val(response[5]);
							$('#paymentamt').val(response[6]);
							$('#paymentstatus').html(response[7]);
							$('#displayentereddate').html(response[9]);
							$('#createddate').html(response[10]);
							$('#lastslno').val(slno);
							enablesave();
							if($('#resendreq'))
								$('#resendreq').show();
							//enabledelete();
						}
						else if(response[0] == '2')
						{
							$('#displaycustomername').html(response[1]);
							$('#paymentdate').html(response[2]);
							$('#remarks').val(response[3]);
							$('#productdecription').val(response[4]);
							$('#billref').val(response[5]);
							$('#paymentamt').val(response[6]);
							$('#paymentstatus').html(response[7]);
							$('#displayentereddate').html(response[9]);
							$('#createddate').html(response[10]);
							$('#lastslno').val(slno);
							disablesave();
							if($('#resendreq'))
								$('#resendreq').hide();

							//disabledelete();
						}else if(response[0] == '3' )
						{
							$('#displaycustomername').html(response[1]);
							$('#paymentdate').html(response[2]);
							$('#remarks').val(response[3]);
							$('#productdecription').val(response[4]);
							$('#billref').val(response[5]);
							$('#paymentamt').val(response[6]);
							$('#paymentstatus').html(response[7]);
							$('#displayentereddate').html(response[9]);
							$('#createddate').html(response[10]);
							$('#lastslno').val(slno);
							if($('#resendreq'))
								$('#resendreq').show();
							disablesave();
						}
					}
				}
				else
					error.html(scripterror());
			}
		}
		ajaxcall2.send(passData);
	}
}

function cuspaymentdatagrid(startlimit,customerslno)
{
	var passData = "switchtype=generategrid&lastslno=" + encodeURIComponent(customerslno)  + "&startlimit=" + encodeURIComponent(startlimit)+ "&dummy=" + Math.floor(Math.random()*10054300000);
	var ajaxcall3 = createajax();
	var queryString = "../ajax/custpayment.php";
	ajaxcall3.open("POST", queryString, true);
	ajaxcall3.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall3.onreadystatechange = function()
	{
		if(ajaxcall3.readyState == 4)
		{
			if(ajaxcall3.status == 200)
			{
				var response = ajaxcall3.responseText.split('^');
				if(response == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					if(response[0] == 1)
					{
						$('#tabgroupgridwb1').html("Total Count :  " + response[2]);
						$('#tabgroupgridc1_1').html(response[1]);//alert(response[1]);
						$('#tabgroupgridc1link').html(response[3]);
					}
					else if(response[0] == 2)
					{
						$('#tabgroupgridc1link').html('');
						$('#tabgroupgridwb1').html('');
						$('#tabgroupgridc1_1').html(response[1]);	
					}
				}
			}
			else
			$('#tabgroupgridc1').html(scripterror());
		}
	}
	ajaxcall3.send(passData);
}

//Function for "show more records" link - to get registration records
function cuspaymentgrid(startlimit,slno,showtype)
{
	var passData = "switchtype=generategrid&startlimit=" + encodeURIComponent(startlimit)+ "&slno=" + encodeURIComponent(slno)+ "&lastslno=" + encodeURIComponent($('#cusslno').val())  + "&showtype=" + encodeURIComponent(showtype)  +"&dummy=" + Math.floor(Math.random()*10054300000);
	//alert(passData);
	var ajaxcall4 = createajax();
	var queryString = "../ajax/custpayment.php";
	$('#tabgroupgridc1link').html('<img src="../images/inventory-processing.gif" border= "0">');
	ajaxcall4.open("POST", queryString, true);
	ajaxcall4.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall4.onreadystatechange = function()
	{
		if(ajaxcall4.readyState == 4)
		{
			if(ajaxcall4.status == 200)
			{
				var ajaxresponse = ajaxcall4.responseText;//alert(ajaxresponse);
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					var response = ajaxresponse.split('^');
					$('#tabgroupgridwb1').html("Total Count :  " + response[2]);
					$('#custresultgrid').html($('#tabgroupgridc1_1').html());
					$('#tabgroupgridc1_1').html($('#custresultgrid').html().replace(/\<\/table\>/gi,'')+ response[1] );
					$('#tabgroupgridc1link').html(response[3]);
				}
			}
			else
			$('#tabgroupgridc1').html(scripterror());
		}
	}
	ajaxcall4.send(passData);
}


function selectfromlist()
{
	var selectbox = $('#customerlist option:selected').val(); 
	var cusnamesearch = $('#detailsearchtext');
	$('#form-error').html('');
	cusnamesearch.val($('#customerlist option:selected').text());
	$('#displaycustomername').html(cusnamesearch.val());
	$('#cusslno').val(selectbox);
	cusnamesearch.select();
	cuspaymentdatagrid('',selectbox);
	newentry();
	displayentry();
}

function displayentry()
{
	var passData = "switchtype=displayentry&dummy=" + Math.floor(Math.random()*10054300000);
	var ajaxcall33 = createajax();//alert(passData)
	var queryString = "../ajax/custpayment.php";
	ajaxcall33.open("POST", queryString, true);
	ajaxcall33.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall33.onreadystatechange = function()
	{
		if(ajaxcall33.readyState == 4)
		{
			if(ajaxcall33.status == 200)
			{
				var response = ajaxcall33.responseText.split('^');//alert(response)
				if(response == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else if(response[0] == '1')
				{
					$('#displayentereddate').html(response[1]);
					$('#createddate').html(response[2]);
				}
			}
			else
			$('#tabgroupgridc1').html(scripterror());
		}
	}
	ajaxcall33.send(passData);
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
		var options = selectbox.attr('options');
	
		var addedcount = 0;
		for( var i=0; i < customerarray.length; i++)
		{
			if(pattern.test(trimdotspaces(customerarray[i]).toLowerCase()))
			{
				var splits = customerarray[i].split("^");
				options[options.length] = new Option(splits[0], splits[1]);
				addedcount++;
				if(addedcount == 100)
					break;
				//selectbox.options[0].selected= true;
				//customerdetailstoform(selectbox.options[0].value); //document.getElementById('delaerrep').disabled = true;
				//document.getElementById('hiddenregistrationtype').value = 'newlicence'; clearregistrationform(); validatemakearegistration(); 
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
		var form = $("#submitform");
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


function resendrequestemail()
{
	var customerid = $('#lastslno').val();
	var error = $('#form-error');
	if(customerid != '')
	{
		var confirmation = confirm("Are you sure you want to send a Payment Request Email to the selected customer?");
		if(confirmation)
		{
			var passData = "switchtype=resendrequestemail&customerslno=" + encodeURIComponent( $('#lastslno').val());//alert(passData)
			error.html(getprocessingimage());
			ajaxcall5 = createajax();
			var queryString = "../ajax/custpayment.php";
			ajaxcall5.open("POST", queryString, true);
			ajaxcall5.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			ajaxcall5.onreadystatechange = function()
			{
				if(ajaxcall5.readyState == 4)
				{
					if(ajaxcall5.status == 200)
					{
						var ajaxresponse = ajaxcall5.responseText.split('^');//alert(ajaxresponse)
						if(ajaxresponse == 'Thinking to redirect')
						{
							window.location = "../logout.php";
							return false;
						}
						else
						{
							if(ajaxresponse[0] == 1)
							{
								error.html(successmessage(ajaxresponse[1]));
							}
							else if(ajaxresponse[0] == 2)
							{
								error.html(errormessage(ajaxresponse[1]));
							}
						}
					}
					else
						error.html(scripterror());
				}
			}
			ajaxcall5.send(passData);
		}
	}
	else
	error.html(errormessage('Cannot send mail.'));
	return false;
}


function searchbycustomerid(cusid)
{
	$('#form-error').html('');
	var form = $('#submitform');
	$('#cusslno').val(cusid);
	form[0].reset();
	var str = cusid.length;
	if(str == '20')
	{
		var cusid = cusid.substring(15);
		
	}else if(str == '17')
	{
		var cusid = cusid.substring(12,17);
	}else if(str == '5')
	{
		var cusid = cusid;
	}
	var passData = "switchtype=searchbycustomerid&customerid=" + encodeURIComponent(cusid) + "&dummy=" + Math.floor(Math.random()*100032680100);
	ajaxcall6 = createajax();
	var queryString = "../ajax/custpayment.php";
	ajaxcall6.open("POST", queryString, true);
	ajaxcall6.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall6.onreadystatechange = function()
	{
		if(ajaxcall6.readyState == 4)
		{
			if(ajaxcall6.status == 200)
			{
				var ajaxresponse = ajaxcall6.responseText;
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					var response = (ajaxresponse).split("^");
					//enableformelemnts();
					if(response[1] == '')
					{
						alert('Customer Not Available.');
						$('#displaycustomername').html('');
						$('#tabgroupgridc1_1').html('');
						$('#tabgroupgridwb1').html('');
						//newentry();
					}
					else if(response[0] == '1' )
					{
						$('#displaycustomername').html(response[1]);
						$('#paymentdate').html(response[2]);
						$('#remarks').val(response[3]);
						$('#productdecription').val(response[4]);
						$('#billref').val(response[5]);
						$('#paymentamt').val(response[6]);
						$('#paymentstatus').html(response[7]);
						$('#displayentereddate').html(response[9]);
						$('#createddate').html(response[10]);
						enablesave();
						if($('#resendreq'))
								$('#resendreq').show();
						//enabledelete();
					}
					else if(response[0] == '2')
					{
						$('#displaycustomername').html(response[1]);
						$('#paymentdate').html(response[2]);
						$('#remarks').val(response[3]);
						$('#productdecription').val(response[4]);
						$('#billref').val(response[5]);
						$('#paymentamt').val(response[6]);
						$('#paymentstatus').html(response[7]);
						$('#displayentereddate').html(response[9]);
						$('#createddate').html(response[10]);
						disablesave();
						if($('#resendreq'))
								$('#resendreq').hide();
						//disabledelete();
					}else if(response[0] == '3' )
					{
						$('#displaycustomername').html(response[1]);
						$('#paymentdate').html(response[2]);
						$('#remarks').val(response[3]);
						$('#productdecription').val(response[4]);
						$('#billref').val(response[5]);
						$('#paymentamt').val(response[6]);
						$('#paymentstatus').html(response[7]);
						$('#displayentereddate').html(response[9]);
						$('#createddate').html(response[10]);
						disablesave();
						if($('#resendreq'))
								$('#resendreq').show();
					}
					else if(response[0] == '4')
					{
						$('#displaycustomername').html(response[1]);
						if($('#resendreq'))
								$('#resendreq').hide();
					}
					cuspaymentdatagrid('',cusid);
					
				}
			}
			else
				$('#form-error').html(scripterror());
		}
	}
	ajaxcall6.send(passData);
}

function searchbycustomeridevent(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 13)
	{
		var input = $('#searchcustomerid').val();
		searchbycustomerid(input);
	}
}
