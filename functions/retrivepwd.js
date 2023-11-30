function validation()
{ 
	var form = document.submitform;
	var error = document.getElementById('form-error');
	var field = document.getElementById("dealerusername");
	if(!field.value)
	{
		error.innerHTML =errormessage("Dealer Username cannot be blank. Please enter a valid Dealer Username provided by Relyon."); field.focus();  return false;}
	/*if(field.value)
	{
		if(!validateusername(field.value)) 
	 	{ 
			 error.innerHTML = errormessage('User Name should not contain space.'); 
			 field.focus();
			 return false; 
		 }
	}*/
	else
	{
		ajaxcall0 = createajax();
		var passData  = "switchtype=dealerusername&dealerusername=" + encodeURIComponent(form.dealerusername.value) + "&dummy=" + Math.floor(Math.random()*100000000);  
		var queryString = "../ajax/retrivepwd.php";//alert(queryString)
		ajaxcall0.open("POST", queryString, true); 
		ajaxcall0.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajaxcall0.onreadystatechange = function()
		{
			if(ajaxcall0.readyState == 4)
			{
				if(ajaxcall0.status == 200)
				{
					var response = ajaxcall0.responseText;//alert(response)
					var responetext=response.split('^');//alert(responetext)
					if(responetext[0] == 1)
					{
						error.innerHTML ='';
						document.getElementById('emailresult').innerHTML=responetext[1];
						document.getElementById('dealerid').value=responetext[2];
						document.getElementById('tabc1').style.display = 'none';
						document.getElementById('tabc2').style.display = 'block';
						document.getElementById('displayselecteddealerid').innerHTML = field.value;
						disablenext();
					}
					else
					{
						error.innerHTML ='';
						error.innerHTML = errormessage(responetext[1]);
						enablenext();
					}
				}
			else
				document.getElementById('form-error').innerHTML = scripterror();
			}
		}
		ajaxcall0.send(passData);
		}
}


function formsubmiting(dealerid)
{
	var form = document.submitform; 
	var error = document.getElementById('form-error1');
	var emailresult = document.getElementById('email');
	var dealerid = document.getElementById("dealerid");
	var field = form.email;
	if(!field.value){error.innerHTML =errormessage("Select the Email ID"); field.focus();  return false;}
	else
	{
	var passData  = "switchtype=sendemail&emailresult=" + encodeURIComponent(emailresult.value)+ "&dealerid=" + encodeURIComponent(dealerid.value) + "&dummy=" + Math.floor(Math.random()*100000000); //alert(passData)
	ajaxcall1 = createajax();
	disablesend();
	error.innerHTML ='';
	document.getElementById('dealerprocess').innerHTML = getprocessingimage();
	var queryString = "../ajax/retrivepwd.php";
	ajaxcall1.open("POST", queryString, true); 
	ajaxcall1.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall1.onreadystatechange = function()
	{
		if(ajaxcall1.readyState == 4)
		{
			if(ajaxcall1.status == 200)
			{
				var response = ajaxcall1.responseText.split('^'); //alert(response)
				if(response[0] == 1)
				{
					error.innerHTML ='';
					document.getElementById('dealerprocess').innerHTML = '';
					document.getElementById('tabc2').style.display = 'none';
					document.getElementById('tabc3').style.display = 'block';
					document.getElementById('form-error2').innerHTML = displaysuccessmessage(response[1]);
				}
				else if(response[0] == 2)
					window.location = "http://imax.relyonsoft.net/dealer/index.php";
				else
					document.getElementById('form-error2').innerHTML = errormessage("Response unknown.");
			}
			else
				document.getElementById('form-error2').innerHTML = scripterror();
		}
	}
	ajaxcall1.send(passData);
	}
}

function validateusername(username)
{
	for (var i = 0 ; i < username.length ; i++)
	{
		var searchThis = username.indexOf(" ", i);
		if (searchThis < 0)
		{
			return true;
			break;
		}
		else
		{
			return false;
		}
	}
}