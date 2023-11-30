

function enableformelemnts()
{
	var count = document.submitform.elements.length;
	for (i=0; i<count; i++) 
	{
		var element = document.submitform.elements[i]; 
		element.disabled=false; 
	}
}

function onblurvalue()
{
	var dtStr =  document.getElementById('customerid').value;
	var val=dtStr.replace(/-/g,"");
	document.getElementById('customerid').value = val;
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

function validatecustomerid(cusid)
{
	var numericExpresion1 = /^\d{17}$/;
	var numericExpresion = /^[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{5}$/;
	var numericExpresion2 = /^[0-9]{4}(\s)[0-9]{4}(\s)[0-9]{4}(\s)[0-9]{5}$/;
	//var numericExpresion3 = /^\d{5}$/;
	if(cusid.match(numericExpresion)) return true;
	else if(cusid.match(numericExpresion1)) return true;
	else if(cusid.match(numericExpresion2)) return true;
	//else if(cusid.match(numericExpresion3)) return true;
	else return false;
}


function formsubmit()
{
	var form = $('#submitform');
	var field = $("#customerid" );
	//if(!field.val()) { $('#form-error').html(errormessage("Enter the Customer ID. ")); field.focus(); return false; }
	if(field.val()) { if(!validatecustomerid(field.val())) { $('#form-error').html(errormessage('Enter a Valid 17 digit Customer ID ')); field.focus(); return false; } }
	if($('#remarks').val() == '')
	{
		$('#form-error').html(errormessage('Please enter the Remarks.')); $('#remarks').focus(); return false;
	}
	if($('#remarks').val().length < 5)
	{
		$('#form-error').html(errormessage('Remarks text should be at least 5 characters.')); $('#remarks').focus(); return false;
	}
	onblurvalue();
	var passdata = "switchtype=generatepin&lastslno=" + encodeURIComponent($('#customerid').val()) + "&remarks=" +encodeURIComponent($('#remarks').val())+ "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData);
	$('#form-error').html(getprocessingimage());
	var queryString = "../ajax/saralaccountscompcopy.php";
	ajaxobjext12 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(ajaxresponse,status)
		{	
			var response = ajaxresponse.split("^");
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else if(response[0] == '1')
			{
				$('#form-error').html(successmessage(response[1]));
				$("#submitform")[0].reset();
				generatecarddetails('')
			
			} 
			else if(response[0] == '2')
			{
				$('#form-error').html(successmessage(response[1]));
			}
			else
			{
				$("#form-error").html(scripterror());
			}
		}, 
		error: function(a,b)
		{
			$("#form-error").html(scripterror());
		}
	});	
}

function clearform()
{
	$("#submitform")[0].reset();
	$("#form-error").html('');
}


function generatecarddetails(startlimit)
{
	var form = $("#submitform");
	var passdata = "switchtype=generategrid&startlimit=" + encodeURIComponent(startlimit);//alert(passData)
	var queryString = "../ajax/saralaccountscompcopy.php";
	$('#tabgroupgridc1_1').html(getprocessingimage());
	$('#tabgroupgridc1link').html('');
	ajaxobjext14 = $.ajax(
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
					var response = ajaxresponse.split('^');//alert(ajaxresponse)
					if(response[0] == '1')
					{
						$('#tabgroupgridwb1').html("Total Count :  " + response[2]);
						$('#tabgroupgridc1_1').html(response[1]); //alert(response[0]);
						$('#tabgroupgridc1link').html(response[3]); //alert(response[2]);
					}
					else
					if(response[0] == '2')
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


function getmorecarddetails(startlimit,slno,showtype)
{
	var form = $('#submitform');
	var passdata = "switchtype=generategrid&startlimit=" + startlimit+ "&slno=" + slno+ "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);
//	alert(passData);
	var queryString = "../ajax/saralaccountscompcopy.php";
	$('#tabgroupgridc1link').html(getprocessingimage());
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
				if(response[0] == '1')
				{
					$('#tabgroupgridwb1').html("Total Count :  " + response[2]);
					$('#regresultgrid').html($('#tabgroupgridc1_1').html());
					$('#tabgroupgridc1_1').html($('#regresultgrid').html().replace(/\<\/table\>/gi,'')+ response[1]) ;
					$('#tabgroupgridc1link').html(response[3]);
				}
				else
				if(response[0] == '2')
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
