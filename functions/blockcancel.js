// JavaScript Document

var cardarray = new Array();
//refreshcardarray();
/*var t;

keeprefreshingcard();

function keeprefreshingcard()
{
	t=setTimeout("refreshcardarray();",3000000000);	
	//refreshcardarray();
}*/


function formsubmit(changetype)
{
	var form = $('#cardsearchfilterform');
	var error = $('#form-error');
	var field = $('#remarks');
	var field = $('#scratchnumber');
	//var field = $('#pinremarksstatus');
	var actionfield = $('input[name=actiontype]:checked').val();
	if(actionfield == 'block')
	{
		var cardregistered = $('#cardregistered').html();
	if(cardregistered == 'yes')
	{ error.html(errormessage('As the card is registered so cannot be blocked.')); field.focus(); return false; }
		else
		var changetype = 'blockcard';
	}
	
	else if(actionfield == 'cancel')
	{
		var cardregistered = $('#cardregistered').html();
		if(cardregistered == 'no')
		{ 
			error.html(errormessage('As the card is not registered so cannot be cancelled.')); field.focus(); return false; }
			else
			changetype = 'cancelcard';
	}
	
	 else 
	  changetype = 'none';
	if(!field.val()) { error.html(errormessage('Select the Scratch card details.')); field.focus(); return false; }
	else
	{
		var passData = '';
		passData = "&type=" + encodeURIComponent(changetype) + "&scratchnumber=" + encodeURIComponent($('#scratchnumber').val()) + "&pinremarksstatus=" + encodeURIComponent($('#pinremarksstatus').val()) + "&remarks="+ encodeURIComponent($('#remarks').val()) ;//alert(passData);
		queryString = '../ajax/blockcancel.php';
		error.html(getprocessingimage());
		ajaxcall1 = $.ajax(
		{
			type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
			success: function(ajaxresponse,status)
			{	
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					var responsesplit = ajaxresponse.split('^');
					if(responsesplit[0] == '1')
					{
						error.html(successmessage(responsesplit[1]));
						$('#scratchnumber').val('');
						$('#pinremarksstatus').val('');
						griddata();
					}
					else
					{
						error.html(errormessage('Unable to Connect...' + response));
					}
				}
			}, 
			error: function(a,b)
			{
				error.html(scripterror());
			}
		});
	}
}

function carddetailstoform()
{
	$('#form-error').html('');
	var error = $('#form-error');
	var form = $('#cardsearchfilterform');
	var field = $.trim($('#cardid').val());
	//alert(field);
	if(!field)
	{
		var field1 = $('#pinno');
		if(!field1.val()) 
		{ 
		  alert("Enter the Card/Pin Number."); 
		  $('#pinno').focus(); 
		  return false; 
		}
	}
	else
	{
		if(!IsNumeric(field))
		{
			alert("Enter only digits.");
			//$('#cardid').val('');
			newentry();
			$('#pinno').focus();
			return false;
		}
	}
	//	form.reset();
		var passData = "type=carddetailstoform&pinno=" + encodeURIComponent($("#pinno").val()) 
		+ "&cardid=" + encodeURIComponent($("#cardid").val())
		+ "&dummy=" + Math.floor(Math.random()*100032680100);
		$('#cardselectionprocess').html(getprocessingimage());
		var queryString = "../ajax/blockcancel.php";
		error.html(getprocessingimage());
		ajaxcall23 = $.ajax(
		{
			type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
			success: function(ajaxresponse,status)
			{	
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					$("#form-error").html('');
					var response = ajaxresponse; //alert(response)
					if(response['nodata'] == "no data")
					{
						alert("Enter valid Pin");
						//$('#pinno').val('');
						newentry();
					}
					else
					{
						
						$('#cardlist').val(response['cardid']);
						$('#cardsearchtext').val(response['scratchnumber'] + ' | ' + response['cardid']);
						$('#cardselectionprocess').html('');
						$('#scratchnumber').val(response['cardid']);
						$('#remarks').val(response['remarks']);
						$('#cardattached').html(response['attached']);
						$('#cardregistered').html(response['registered']);
						//if(response['pinstatusremarks']!= "")
						$('#pinremarksstatus').val(response['pinstatusremarks']);
						if(response['blocked'] == 'yes')
							$('#actiontype1').attr("disabled", true); 
						else
							$('#actiontype1').removeAttr('checked'); 
							
						if(response['cancelled'] == 'yes')
							$('#actiontype2').attr("disabled", true);
						else
							$('#actiontype2').removeAttr('checked');

						if(response['cardstatus'] == 'Active')	
						{
							$('#actiontype1').attr("disabled", false);
							$('#actiontype2').removeAttr('checked');
							$('#actiontype2').attr("disabled", false);
							$('#actiontype1').removeAttr('checked'); 
							$('#save').attr("disabled", false);
							$('#save').removeAttr('disabled'); 
							$("#save").removeClass("swiftchoicebuttondisabled");
							$("#save").addClass("swiftchoicebutton");
						}
						else
						{
							$('#save').attr("disabled", true);
							$("#save").removeClass("swiftchoicebutton");
							$("#save").addClass("swiftchoicebuttondisabled");
						}
						// if(response['none'] == 'yes')
						// 	$('#actiontype0').prop("checked", true);
						
						$('#cardstatus').html(response['cardstatus']);
					}
				}
			}, 
			error: function(a,b)
			{
				$("#form-error").html(scripterror());
			}
		});
	
}

function griddata()
{
	var passData = "type=griddata&cardid=" + $("#cardid").val() +
	"&pinno=" + $("#pinno").val() +
	"&dummy=" + Math.floor(Math.random()*1000782200000);
	var queryString = "../ajax/blockcancel.php";
	
	ajaxcall24 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,
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
					
					//alert(response[1]);
					$('#tabgroupgridc3_1').html(response[1]);
				}
				else
				{
					error.html(errormessage('Unable to connect.'));
				}
			}
		}
	});
}


function clearcarddetails()
{
	$('#displaycardnumber').html('');
	$('#displayscratchno').html('');
	$('#displaypurchasetype').html('');
	$('#displayusagetype').html('');
	$('#displayattachedto').html('');
	$('#displayattachdate').html('');
	$('#displayregisteredto').html('');
	$('#displayregisterdate').html('');
	$('#displaycardremarks').html('');
	$('#displaycardstatus').html('');
	$('#cardselectionprocess').html('');
}

function newentry()
{
	
	var form = $('#cardsearchfilterform');
	$('#form-error').html('');
	$('#scratchnumber').val('');
	$('#remarks').val('');
	$('#cardattached').html('');
	$('#cardregistered').html('');
	$('#cardstatus').html('');
	$('#cardsearchtext').val('');
	$('#pinno').val('');
	$('#cardid').val('');
	$('#tabgroupgridc3_1').html('');
	$('#pinremarksstatus').val('');
	
}


