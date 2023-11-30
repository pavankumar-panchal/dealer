function selectdeselectall(showtype)
{
	var selectproduct = $('#selectproduct');
	var chkvalues = $("input[name='productname[]']");
	if(showtype == 'one')
	{
		for (var i=0; i < chkvalues.length; i++)
		{
			if($(chkvalues[i]).is(':checked'))
			{
				$(chkvalues[i]).attr('checked',false);
			}
			if( $('#selectproduct').val() == 'ALL')
				$(chkvalues[i]).attr('checked',true);
			else if(selectproduct.value == 'NONE')
				$(chkvalues[i]).attr('checked',true);
			else if(chkvalues[i].getAttribute('producttype') == $('#selectproduct').val())
			{
				$(chkvalues[i]).attr('checked',true);
				$('#groupvalue').val('');
			}
		}
	}else if(showtype == 'more')
	{

		var addproductvalue = $("#selectproduct option:selected").val();
		if($('#groupvalue').val() == '')
			$('#groupvalue').val($('#groupvalue').val() +  addproductvalue);
		else
			$('#groupvalue').val($('#groupvalue').val() + '%' +  addproductvalue);
		for (var i=0; i < chkvalues.length; i++)
		{
			if($(chkvalues[i]).is(':checked'))
			{
				$(chkvalues[i]).attr('checked',false);
			}

			var var1 = $('#groupvalue').val().split('%');
			for( var j=0; j<var1.length; j++)
			{
				if($('#selectproduct').val() == 'ALL')
					$(chkvalues[i]).attr('checked',true);
				else if($('#selectproduct').val() == 'NONE')
					$(chkvalues[i]).attr('checked',false);
				if(chkvalues[i].getAttribute('producttype') == var1[j])
				{
					$(chkvalues[i]).attr('checked',true);
				}
			}
		}
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


function enabledisablereregistartion()
{
	var form = $('#submitform');
	if($('#reregenable').is(':checked') == true)
	{
		$('#DPC_fromdate').attr('disabled',false);	
		$('#DPC_todate').attr('disabled',false);	
		$('#rereg').attr('disabled',false);	
		$('#usagetype').attr('disabled',false);	
		$('#purchasetype').attr('disabled',false);	
		$('#scheme').attr('disabled',false);	
		$('#rereg').attr('disabled',false);	
		$('#card').attr('disabled',false);
		
		$('#usagetype').removeClass('sdiabledatefield');
		$('#purchasetype').removeClass('sdiabledatefield');
		$('#scheme').removeClass('sdiabledatefield');
		$('#rereg').removeClass('sdiabledatefield');
		$('#DPC_fromdate').removeClass('diabledatefield');
		$('#DPC_todate').removeClass('diabledatefield');
		$('#card').removeClass('sdiabledatefield');
		
		$('#usagetype').addClass('swiftselect-mandatory');
		$('#purchasetype').addClass('swiftselect-mandatory');
		$('#scheme').addClass('swiftselect-mandatory');
		$('#rereg').addClass('swiftselect-mandatory');
		$('#DPC_fromdate').addClass('swiftselect-mandatory');
		$('#DPC_todate').addClass('swiftselect-mandatory');
		$('#card').addClass('swiftselect-mandatory');
	}
	else 
	{
		$('#fromdate').attr('disabled',true);	
		$('#todate').attr('disabled',true);	
		$('#rereg').attr('disabled',true);	
		$('#usagetype').attr('disabled',true);	
		$('#purchasetype').attr('disabled',true);	
		$('#scheme').attr('disabled',true);	
		$('#rereg').attr('disabled',true);	
		$('#card').attr('disabled',true);
		
		$('#usagetype').addClass('sdiabledatefield');
		$('#purchasetype').addClass('sdiabledatefield');
		$('#scheme').addClass('sdiabledatefield');
		$('#rereg').addClass('sdiabledatefield');
		$('#DPC_fromdate').addClass('diabledatefield');
		$('#DPC_todate').addClass('diabledatefield');
		$('#card').addClass('sdiabledatefield');
		
		$('#usagetype').removeClass('swiftselect-mandatory');
		$('#purchasetype').removeClass('swiftselect-mandatory');
		$('#scheme').removeClass('swiftselect-mandatory');
		$('#rereg').removeClass('swiftselect-mandatory');
		$('#DPC_fromdate').removeClass('swiftselect-mandatory');
		$('#DPC_todate').removeClass('swiftselect-mandatory');
		$('#card').removeClass('swiftselect-mandatory');
		
	}
		
}



function formsubmit()
{
	var form = $('#submitform');
	var error = $('#form-error');
	var values = validateproductcheckboxes();
	if(values == false)	{error.html(errormessage("Select A Product"));  return false;	}
	var field = $('#DPC_fromdate');
	if(!field.val()) { error.html(errormessage("Enter the From Date.")); field.focus(); return false; }
	var field = $('#DPC_todate');
	if(!field.val()) { error.html(errormessage("Enter the To Date.")); field.focus(); return false; }
	else
	{
		error.html('');
		$('#submitform').attr("action", "../ajax/labelscontactdetails.php") ;
		$('#submitform').submit();
	}
	
}

