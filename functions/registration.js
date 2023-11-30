// JavaScript Document
function formsubmit(command)
{
	var form = $('#submitform');
	var error = $('#form-error');
	var field = $('#customerselection');
	if($("input[name='customerselection']:checked").val() == 'particularcustomer') { var field = $('#searchtextid'); if(!field.val()) { error.html(errormessage('Select the Customer From the list.')); $('#searchtext').focus(); return false; } }
	var field = $('#searchtext');
	var field = $('#searchtextid');
	var field = $('#geography');
	if($("input[name='geography']:checked").val() == 'region') { var field = $('#region'); if(!field.val()) { error.html(errormessage('Select A Region.')); field.focus(); return false; } }
	if($("input[name='geography']:checked").val() == 'state') { var field = $('#state'); if(!field.val()) { error.html(errormessage('Select A State.')); field.focus(); return false; } }
	if($("input[name='geography']:checked").val() == 'district') { var field = $('#district'); if(!$('#state').val()) { error.html( errormessage('Select A State First.')); $('#state').focus(); return false; } if(!field.val()) { error.html(errormessage('Select A District.')); field.focus(); return false; } }
	var field = $('#DPC_fromdate');
	if(!field.val()) { error.html(errormessage("Enter the From Date.")); field.focus(); return false; }
	var field = $('#DPC_todate');
	if(!field.val()) { error.html(errormessage("Enter the To Date.")); field.focus(); return false; }
	var values = validateproductcheckboxes();
	if(values == false)	{error.html(errormessage("Select A Product"));  return false;	}
	else
	{
		error.html('');
		// if(command == 'view')
		// {
			form.attr('action','../reports/registrationreport.php');
			form.attr('target','_blank');
			form.submit();	
		// }
		// else
		// {
		// 	form.attr('action','../reports/registrationreport.php?id=toexcel');
		// 	//form.target = "_blank";
		// 	form.submit();	
		// }
	}
}


function enablegeography()
{
	var form = $('#submitform');
	var error = $('#form-error');
	
	var geography = $("input[name='geography']:checked").val();
	if(geography == 'all')
	{
		$('#region').attr('disabled',true);
		$('#region').val(''); $('#state').val(''); $('#district').val('');
		$('#state').attr('disabled',true);
		$('#district').attr('disabled',true);	
		$('#regiondiv').hide();
		$('#statediv').hide();
		$('#districtdiv').hide();
	}
	if(geography == 'region')
	{
		$('#region').attr('disabled',false);
		$('#state').attr('disabled',true);
		$('#district').attr('disabled',true);	
		$('#region').val(''); $('#state').val(''); $('#district').val('');
		$('#regiondiv').show();
		$('#statediv').hide();
		$('#districtdiv').hide();
	}
	if(geography == 'state')
	{
		$('#region').attr('disabled',true);
		$('#state').attr('disabled',false);
		$('#district').attr('disabled',true);	
		$('#region').val(''); $('#state').val(''); $('#district').val('');
		$('#regiondiv').hide();
		$('#statediv').show();
		$('#districtdiv').hide();
	}
	if(geography == 'district')
	{
		$('#region').attr('disabled',true);
		$('#state').attr('disabled',false);
		$('#district').attr('disabled',false);	
		$('#region').val(''); $('#state').val(''); $('#district').val('');
		$('#regiondiv').hide();
		$('#statediv').show();
		$('#districtdiv').show();
	}
}

function enablecustomersearch()
{

	var form = $('#submitform');
	var error = $('#form-error');
	var customerselection = $("input[name='customerselection']:checked").val();
	if(customerselection == 'allcustomer')
	{
		$('#searchtextid').attr('disabled',true);
		$('#searchtextid').val('');
		$('#searchtext').attr('disabled',true);
		$('#searchtext').val('');
		$('#loadcustomerlist').html('');
	}
	else
	{
		$('#searchtextid').attr('disabled',false);
		$('#searchtext').attr('disabled',false);
		//document.getElementById('loadcustomerlist').innerHTML = '';
	}
}

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

