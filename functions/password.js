function formsubmiting(keyvalue)
{  
	var form = document.submitpwdform; 
	var error = document.getElementById('form-error');
	var field = form.password;
	if(!field.value){error.innerHTML =errormessage("Enter the New Password"); return false; field.focus(); }
	var field = form.confirmpwd;
	if(!field.value){error.innerHTML = errormessage("Re-Enter the New Password");  return false; field.focus();}
	if(form.password.value != form.confirmpwd.value)
	{
		error.innerHTML =errormessage("New and confirm passwords does not match."); return false; field.focus();
	}
	else
	{
		var passData  = "switchtype=retrivepwd&password=" + encodeURIComponent(form.password.value)  + "&confirmpwd=" + encodeURIComponent(form.confirmpwd.value) + "&key=" + encodeURIComponent(keyvalue) + "&dummy=" + Math.floor(Math.random()*100000000);//alert(passData)
		ajaxcall0 = createajax();
		var queryString = "../ajax/password.php"; 
		ajaxcall0.open("POST", queryString, true); 
		ajaxcall0.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajaxcall0.onreadystatechange = function()
		{
			if(ajaxcall0.readyState == 4)
			{
				if(ajaxcall0.status == 200)
				{
					var response = ajaxcall0.responseText;
					var responetext=response.split('^');//alert(responetext)
					if(responetext[0] == 1)
					{
						document.getElementById('form-error').innerHTML = successmessage(responetext[1]);
						form.reset();
					}
					else if(responetext[0] == 2)
					{
						document.getElementById('form-error').innerHTML = errormessage(responetext[1]);
						form.reset();
					}
					else
					{
						document.getElementById('form-error').innerHTML = errormessage("Response unknown.");
					}
				}
				else
					document.getElementById('form-error').innerHTML = scripterror();
			}
		}
	ajaxcall0.send(passData);
	}
}

