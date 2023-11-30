function validating(userid)
{  
	var form = $('#submitform'); 
	var error = $('#form-error');
	var field = $('#oldpassword');
	if(!field.val()){error.html(errormessage("Enter the Password")); field.focus(); return false; }
	var field = $('#newpassword');
	if(!field.val()){error.html(errormessage("Enter the New Password")); field.focus(); return false; }
	var field = $('#confirmpassword');
	if(!field.val()){error.html(errormessage("Re-Enter the New Password")); field.focus(); return false; }
	else
	{
	//alert('test1')
	var passdata  = "switchtype=change&oldpassword=" + encodeURIComponent($('#oldpassword').val())  + "&newpassword=" + encodeURIComponent($('#newpassword').val()) + "&confirmpassword=" + encodeURIComponent($('#confirmpassword').val()) + "&userid=" + encodeURIComponent(userid) + "&dummy=" + Math.floor(Math.random()*100000000);
	error.html(getprocessingimage());
	var queryString = "../ajax/changepwd.php"; 
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
				var responetext=response.split('^');//alert(responetext)
				if(responetext[0] == 1)
				{
					$('#form-error').html(successmessage(responetext[1]));
				}
				else 
				{
					$('#form-error').html(errormessage(responetext[1]));
				}
			}
		}, 
		error: function(a,b)
		{
			$("#form-error").html(scripterror());
		}
	});		
	}
}

