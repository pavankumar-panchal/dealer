// JavaScript Document
function formsubmit(command)
{
	var form = $('#submitform');
	var error = $('#form-error');
	var field = $('#DPC_attachtodate');
	if(!field.val()) { error.html(errormessage("Enter the Attach To Date.")); field.focus(); return false; }
	var field = $('#DPC_attachfromdate');
	if(!field.val()) { error.html(errormessage("Enter the Attach From Date.")); field.focus(); return false; }
	else
	{
		if(command == 'view')
		{
			error.html('');
			form.attr('action','../reports/exceldealerreport.php?id=view');
			form.attr('target','_blank');
			form.submit();	
		}
		else
		{
			error.html('');
			form.attr('action','../reports/exceldealerreport.php?id=toexcel');
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
		$('#region').val(''); $('#state').val(); 
		$('#state').attr('disabled',true);	
		$('#regiondiv').hide();
		$('#statediv').hide();
	}
	if(geography == 'region')
	{
		$('#region').attr('disabled',false);
		$('#state').attr('disabled',true);	
		$('#region').val(''); $('#state').val(); 
		$('#regiondiv').show();
		$('#statediv').hide();
	}
	if(geography == 'state')
	{
		$('#region').attr('disabled',true);
		$('#state').attr('disabled',false);	
		$('#region').val(''); $('#state').val(); 
		$('#regiondiv').hide();
		$('#statediv').show();
	}
}

