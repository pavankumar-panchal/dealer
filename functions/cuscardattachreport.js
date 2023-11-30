// JavaScript Document
function formsubmit(command)
{
	var form = $('#submitform');
	var error = $('#form-error');

	var field = $('#dealerid');
//	if(!field.value) { error.innerHTML = errormessage("Select a Dealer.");  field.focus(); return false; }
	var field = $('#geography'); 
	if($("input[name='geography']:checked").val() == 'region') { var field =$('#region'); if(!field.val()) { error.html(errormessage('Select A Region.')); field.focus(); return false; } }
	if($("input[name='geography']:checked").val() == 'state') { var field = $('#state'); if(!field.val()) { error.html(errormessage('Select A State.')); field.focus(); return false; } }
	var field = $('#DPC_attachtodate');
	if(!field.val()) { error.html(errormessage("Enter the Attach To Date.")); field.focus(); return false; }
	var field = $('#DPC_attachfromdate');
	if(!field.val()) { error.html(errormessage("Enter the Attach From Date.")); field.focus(); return false; }
	var values = validateproductcheckboxes();
	if(values == false)	{error.html(errormessage("Select A Product")); field.focus(); return false;	}
	else
	{
		if(command == 'view')
		{
			error.html('');
			form.attr('action','../reports/excelcuscardattachreport.php?id=view');
			form.attr('target','_blank');
			form.submit();	
		}
		else
		{
			error.html('');
			form.attr('action',"../reports/excelcuscardattachreport.php?id=toexcel");
			//form.target = "_blank";
			form.submit();	
		}
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
		$('#region').val(''); 
		$('#state').val(''); 
		$('#state').attr('disabled',true);
		$('#regiondiv').hide();
		$('#statediv').hide();
	}
	if(geography == 'region')
	{
		$('#region').attr('disabled',false);
		$('#state').attr('disabled',true);
		$('#region').val(''); 
		$('#state').val(''); 
		$('#regiondiv').show();
		$('#statediv').hide();
	}
	if(geography == 'state')
	{
		$('#region').attr('disabled',true);
		$('#state').attr('disabled',false);
		$('#region').val(''); 
		$('#state').val('');
		$('#regiondiv').hide();
		$('#statediv').show();
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

