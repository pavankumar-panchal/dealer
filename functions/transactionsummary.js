// JavaScript Document

function formsubmit(command,dealerid,startlimit)
{	
	var form = $('#submitform');
	var error = $('#form-error');
	var field = $('#DPC_fromdate');
	if(!field.val()){error.html(errormessage("Enter the From Date")); return false; field.focus(); }
	var field = $('#DPC_todate');
	if(!field.val()){error.html(errormessage("Enter the To Date")); return false; field.focus(); }
	//alert(command);
	if(command == 'view')
	{
		var subselection = $("input[name='transactiontype']:checked").val();
		var passdata = '';
		passdata = "switchtype=view&fromdate=" + encodeURIComponent($('#DPC_fromdate').val())  + "&todate=" + encodeURIComponent($('#DPC_todate').val()) + "&subselection=" + encodeURIComponent(subselection) + "&dealerid=" + encodeURIComponent(dealerid) + "&startlimit=" +encodeURIComponent(startlimit); 
		error.html(getprocessingimage());
		var queryString = "../ajax/transactionsummary.php";
		ajaxcall0 = $.ajax(
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
						$('#tabgroupgridc1_1').html(response[0]);
						$('#tabgroupgridc1link').html(response[1]);
						error.html('');
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
		
		form.attr('action','../ajax/toexcel.php');
		form.submit();
	}
}

function viewmoresummary(dealerid,startlimit,slno,showtype)
{	
	var form = $('#submitform');
	var error = $('#form-error');
	var field = $('#DPC_fromdate');
	if(!field.val()){error.html(errormessage("Enter the From Date")); return false; field.focus(); }
	var field = $('#DPC_todate');
	if(!field.val()){error.html(errormessage("Enter the To Date")); return false; field.focus(); }
	var subselection = $("input[name='transactiontype']:checked").val();
	var passdata = '';
	passdata = "switchtype=view&fromdate=" + encodeURIComponent($('#DPC_fromdate').val())  + "&todate=" + encodeURIComponent($('#DPC_todate').val()) + "&subselection=" + encodeURIComponent(subselection) + "&dealerid=" + encodeURIComponent(dealerid) + "&startlimit=" +encodeURIComponent(startlimit) + "&slno=" +encodeURIComponent(slno)+ "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);//alert(passData); 
	error.html(getprocessingimage());
	var queryString = "../ajax/transactionsummary.php";
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
					var response = ajaxresponse.split('^');
					$('#resultgrid').html($('#tabgroupgridc1_1').html());
					$('#tabgroupgridc1_1').html($('#resultgrid').html().replace(/\<\/table\>/gi,'')+ response[0]);
					$('#tabgroupgridc1link').html(response[1]);
					error.html('');
				}
			}, 
			error: function(a,b)
			{
				error.html(scripterror());
			}
		});		

}



