// JavaScript Document
function formsubmit(userid)
{
	var form = $('#submitform'); 
	var error = $('#form-error');
	var field = $('#businessname');
	if(!field.val()){error.html(errormessage("Enter the Business/Company Name")); return false; field.focus(); }
	if(field.val()) { if(!validatebusinessname(field.val())) { error.html(errormessage('Business name contains special characters. Please use only Alpha / Numeric / space / hyphen / small brackets.')); field.focus(); return false; } }
	var field = $('#contactperson');
	if(!field.val()){error.html(errormessage("Enter the contact Name")); field.focus(); return false;  }
	if(field.val()) { if(!validatecontactperson(field.val())) { error.html(errormessage('Contact person name contains special characters. Please use only Alpha / Numeric / space / comma.')); field.focus(); return false; } }
	var field = form.address;
	var field = $('#state');
	if(!field.val()){error.html(errormessage("Select the state")); field.focus(); return false; }
	var field = $('#district');
	if(!field.val()){error.html(errormessage("Select the District")); field.focus(); return false; }
	var field = $('#pincode');
	if(!field.val()){error.html(errormessage("Enter the Pincode")); field.focus(); return false; }
	if(field.val()) { if(!validatepincode(field.val())) { error.html(errormessage('Enter the valid PIN Code.')); field.focus(); return false; } }
	var field = $('#emailid');
	if(!field.val()){error.html("Enter the Email Id"); }
	if(field.val())	{ if(!emailvalidation(field.val())) { error.html(errormessage('Enter the valid Email ID.')); field.focus(); return false; } }
	var field = $('#personalemailid');
	if(field.val())	{ if(!emailvalidation(field.val())) { error.html(errormessage('Enter the valid Email ID.')); field.focus(); return false; } }
	var field = $('#region');
	if(!field.val()){error.html(errormessage("Select the Region")); return false; field.focus();}
	var field = $('#stdcode');
	//if(!field.value){error.innerHTML = errormessage("Enter the STD code"); return false; field.focus();}
	if(field.val()) { if(!validatestdcode(field.val())) { error.html('Enter the valid STD Code.'); field.focus(); return false; } }
	var field = $('#phone');
	if(!field.val()){error.html(errormessage("Enter the Phone Number")); field.focus(); return false;}
	if(field.val()) { if(!validatephone(field.val())) { error.html('Enter the valid Phone Number.'); field.focus(); return false; } }
	var field = $('#cell');
	if(!field.val()){error.html(errormessage("Enter the Cell number")); field.focus(); return false; }
	if(field.val()) { if(!validatecell(field.val())) { error.html(errormessage('Enter the valid Cell Number.')); field.focus(); return false; } }
	var field = $('#website');
	var field = $('#place');
	if(!field.val()){error.html(errormessage("Enter the Place")); field.focus(); return false; }
	var field = $('#agreetoupdate');
	if(field.is(":checked") == true) var disablelogin = 'yes'; else disablelogin = 'no';
	if(disablelogin == 'yes')
	{
		var passdata = '';
		passdata = "switchtype=tempsave&contactperson=" + encodeURIComponent( $('#contactperson').val()) + "&businessname=" + encodeURIComponent( $('#businessname').val())  + "&address=" +encodeURIComponent( $('#address').val()) + "&place=" + encodeURIComponent( $('#place').val()) + "&state=" + encodeURIComponent( $('#state').val()) + "&district=" + encodeURIComponent($('#district').val()) + "&pincode=" + encodeURIComponent( $('#pincode').val()) + "&region=" + encodeURIComponent( $('#region').val()) + "&stdcode=" + encodeURIComponent( $('#stdcode').val()) + "&phone=" + encodeURIComponent($('#phone').val()) + "&cell=" + encodeURIComponent($('#cell').val()) + "&emailid=" + encodeURIComponent($('#emailid').val()) +"&website=" + encodeURIComponent($('#website').val())+"&personalemailid=" + encodeURIComponent($('#personalemailid').val()) +"&userid=" + encodeURIComponent(userid);
		//alert(passData);
		ajaxcall0 = createajax();//alert(passData);
		$('#cancelmeg').html('');
		error.html(getprocessingimage());
		var queryString = "../ajax/updateprofile.php";
		ajaxobjext14 = $.ajax(
			{
				type: "POST",url: queryString, data: passdata, cache: false,
				success: function(response,status)
				{	
					if(response == 'Thinking to redirect')
					{
						window.location = "../logout.php";
						return false;
					}
					else
					{
						error.html(successmessage(response));
						$('#updatewarningmeg').show();
						$('#cancelmeg').html('');
						$('#profilepending').html('');
					}
				}, 
				error: function(a,b)
				{
					error.html(scripterror());
				}
			});		
	}
	else
		error.html(errormessage('If you want to Update your profile, please confirm the checkbox.'));
}



function getdealerdetails(dealerid)
{
	//alert('dealerid');
	var form = $('#submitform'); 
	var error = $('#form-error');
	var passdata = '';
	passdata = "switchtype=getdealerdetails&dealerid=" + encodeURIComponent(dealerid);
	ajaxcall1 = createajax();//alert(passData);
	var queryString = "../ajax/updateprofile.php";
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
				var response = ajaxresponse.split('^');
				$('#contactperson').val(response[0]);
				$('#address').val(response[1]);
				$('#place').val(response[2]);
				$('#state').val(response[3]);
				districtcodeFunction('district',response[4]);
				$('#pincode').val(response[5]);
				$('#region').val(response[6]);
				$('#stdcode').val(response[7]);
				$('#phone').val(response[8]);
				$('#cell').val(response[9]);
				$('#emailid').val(response[10]);
				$('#website').val(response[11]);
				$('#createddate').html(response[12]);
				$('#businessname').val(response[13]);
				$('#personalemailid').val(response[15]);
				if(response[14] == '')
				{
					$('#updatewarningmeg').hide();
				}
			else
				$('#updatewarningmeg').show();
			}, 
			error: function(a,b)
			{
				error.html(scripterror());
			}
		});		
}
	
function validate(dealerid)
{ 
	var form = $('#submitform'); 
	var passdata  = "switchtype=undo&dealerid=" + encodeURIComponent(dealerid) + "&dummy=" + Math.floor(Math.random()*100000000); 
	var queryString = "../ajax/updateprofile.php";
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
					$('#contactperson').val(response[0]);
					$('#address').val(response[1]);
					$('#place').val(response[2]);
					$('#state').val(response[3]);
					districtcodeFunction('district',response[4]);
					$('#pincode').val(response[5]);
					$('#region').val(response[6]);
					$('#stdcode').val(response[7]);
					$('#phone').val(response[8]);
					$('#cell').val(response[9]);
					$('#emailid').val(response[10]);
					$('#website').val(response[11]);
					$('#createddate').html(response[12]);
					$('#businessname').val(response[13]);
					$('#personalemailid').val(response[14]);
				}
			}, 
			error: function(a,b)
			{
				$("#form-error").html(scripterror());
			}
		});		
}


function cancelupdate(dealerid)
{	
	var form = $('#submitform'); 
	var error = $('#form-error');
	$('#lastslno').val(dealerid);
	var passdata = '';
	passdata = "switchtype=cancelupdate&dealerid=" + encodeURIComponent(dealerid);
	ajaxcall3 = createajax();//alert(passData);
	var queryString = "../ajax/updateprofile.php";
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
				$('#cancelmeg').html(successmessage(ajaxresponse));
				$('#updatewarningmeg').hide();
				$('#form-error').html('');
				validate($('#lastslno').val());
			}
		}, 
		error: function(a,b)
		{
			$("#cancelmeg").html(scripterror());
		}
	});		
}

