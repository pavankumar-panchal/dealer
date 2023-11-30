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


function validatestatuscheckboxes()
{
var chksvalue1 = $("input[name='summarize[]']");
var hasChecked1 = false;
for (var i = 0; i < chksvalue1.length; i++)
{
	if ($(chksvalue1[i]).is(':checked'))
	{
		hasChecked1 = true;
		return true
	}
}
	if (!hasChecked1)
	{
		return false
	}
}



function formsubmit()
{
	var form = $('#submitform');
	var error = $('#form-error');
	// var values = validateproductcheckboxes();
	// if(values == false)	{error.html(errormessage("Select Atleast one Product"));  return false;	}
	var values1 = validatestatuscheckboxes();
	if(values1 == false)	{error.html(errormessage("Select Atleast one Status"));  return false;	}
	var field = $('#DPC_fromdate');
	if(!field.val()) { error.html(errormessage("Enter the From Date.")); field.focus(); return false; }
	var field = $('#DPC_todate');
	if(!field.val()) { error.html(errormessage("Enter the To Date.")); field.focus(); return false; }
	else
	{
		error.html('');
		$('#submitform').attr("action", "../reports/implementationstatus.php") ;
		
		$('#submitform').attr( 'target','_blank' );
		$('#submitform').submit();
	}
	
}

function implementationresetfunc()
{
	$('#form-error').html('');
	$('#submitform')[0].reset();
}
