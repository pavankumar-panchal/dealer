// JavaScript Document
function formsubmit(dealerid,startlimit)
{
	var form = $('#submitform');
	var error = $('#form-error');
	var field = $('#DPC_fromdate');
	if(!field.val()){error.html(errormessage("Enter the From Date")); return false; field.focus(); }
	var field = $('#DPC_todate');
	if(!field.val()){error.html(errormessage("Enter the To Date")); return false; field.focus(); }
	var field = $('#product');
	var passData = '';
	passdata = "switchtype=viewpurchase&fromdate=" + encodeURIComponent($('#DPC_fromdate').val())  + "&todate=" + encodeURIComponent($('#DPC_todate').val()) + "&product=" + encodeURIComponent($('#product').val()) + "&dealerid=" +encodeURIComponent(dealerid) + "&startlimit=" +encodeURIComponent(startlimit);
	ajaxcall0 = createajax();//alert(passData);
	error.html(getprocessingimage());
	var queryString = "../ajax/viewpurchase.php";
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
				$('#tabgroupgridc1_1').html(response[0]);
				$('#tabgroupgridc1link').html(response[2]);
				$('#tabgroupgridwb1').html("Total Count :  " + response[1]);
				$('#displaypurchasedetails').html('');
				error.html('');
			}
		}, 
		error: function(a,b)
		{
			error.html(scripterror());
		}
	});		
}


function viewmoredetails(dealerid,startlimit,slno,showtype)
{
	var form = $('#submitform');
	var error = $('#form-error');
	var passData = '';
	passdata = "switchtype=viewpurchase&fromdate=" + encodeURIComponent($('#DPC_fromdate').val())  + "&todate=" + encodeURIComponent($('#DPC_todate').val()) + "&product=" + encodeURIComponent($('#product').val()) + "&dealerid=" +encodeURIComponent(dealerid) + "&startlimit=" +encodeURIComponent(startlimit) + "&slno=" +encodeURIComponent(slno)+ "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);//alert(passData); 
	error.html(getprocessingimage());
	var queryString = "../ajax/viewpurchase.php";
	ajaxcall1 = $.ajax(
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
				$('#tabgroupgridc1_1').html($('#resultgrid').html().replace(/\<\/table\>/gi,'')+ response[0]) ;
				$('#tabgroupgridc1link').html(response[2]);
				$('#tabgroupgridwb1').html("Total Count :  " + response[1]);
				$('#displaypurchasedetails').html('');
				error.html('');
			}
		}, 
		error: function(a,b)
		{
			error.html(scripterror());
		}
	});		
}



function purchasedetailstoform(billno)
{
	var passData = ''; var startlimit = ''; 
	passdata = "switchtype=viewpurchasedetails&billno=" + encodeURIComponent(billno) + "&startlimit=" +encodeURIComponent(startlimit);
	var queryString = "../ajax/viewpurchase.php";
	ajaxcall2 = $.ajax(
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
				$('#displaypurchasedetails').html(response[0]);
				$('#tabgroupgridc2link').html(response[2]);
				$('#tabgroupgridwb2').html("Total Count :  " + response[1]);
			}
		}, 
		error: function(a,b)
		{
			$("#tabgroupgridc2link").html(scripterror());
		}
	});		
}

function getmorepurchasedetailstoform(billno,startlimit,slno,showtype)
{
	var passdata = ''; 
	passdata = "switchtype=viewpurchasedetails&billno=" + encodeURIComponent(billno) + "&startlimit=" +encodeURIComponent(startlimit) + "&slno=" +encodeURIComponent(slno); 
	var queryString = "../ajax/viewpurchase.php";
	ajaxcall3 = $.ajax(
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
				$('#displaybillno').html(':'+'('+'Billno:'+billno+')');
				$('#resultpurchasegrid').html($('#tabgroupgridc2_1').html());
				$('#tabgroupgridc2_1').html($('#resultpurchasegrid').html().replace(/\<\/table\>/gi,'')+ response[0]);
				$('#tabgroupgridc2link').html(response[2]);
				$('#tabgroupgridwb2').html("Total Count :  " + response[1]);
			}
		}, 
		error: function(a,b)
		{
			$("#tabgroupgridc2link").html(scripterror());
		}
	});		
}


