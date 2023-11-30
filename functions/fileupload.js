// JavaScript Document
function startUpload(formno)
{
	document.getElementById('f1_upload_process'+formno).innerHTML = 'Loading...<br/><img src="../images/loader.gif" />';
	document.getElementById('f1_upload_form'+formno).style.visibility = 'hidden';
	document.getElementById('f1_upload_process'+formno).style.visibility = 'visible';
	return true;
}

function stopUpload(success,formno,divid)
{
	var result = '';
	var spanid = document.getElementById('span_downloadlinkfile'+formno).value;
	var textfield = document.getElementById('text_filebox'+formno).value;
	var linkid = document.getElementById('link'+formno).value;
	var deletelink = document.getElementById('deletelink'+formno).value;
	switch(success)
	{
		case "2":
		if(formno == '1')
		{
			result = '<span class="emsg">File Extension does not match.. It should be PDF!<\/span><br/><br/>';
			document.getElementById('f1_upload_process'+formno).innerHTML = result;
		}
		else
		{
			result = '<span class="emsg">File Extension does not match.. It should be Zip!<\/span><br/><br/>';
			document.getElementById('f1_upload_process'+formno).innerHTML = result;

		}
		break;
		case "3":
			result = '<span class="emsg">File Already Exists by this name!<\/span><br/><br/>';
			document.getElementById('f1_upload_process'+formno).innerHTML = result;
			break;
		case "4":
			result = '<span class="emsg">There was an error during file upload!<\/span><br/><br/>';
			document.getElementById('f1_upload_process'+formno).innerHTML = result;
			break;
		default:
			document.getElementById('f1_upload_process'+formno).innerHTML = '';
			var links = success.split('|^|');
			document.getElementById(textfield).value = links[1];
			document.getElementById(linkid).value = links[0];
			if(formno == '2')
			{
				document.getElementById(deletelink).value = links[2];
			}
			if(formno == '5')
			{
				document.getElementById(spanid).innerHTML = '<div id="linkdetailsdiv'+formno+'" style="display:block"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="18%" id="viewdetailsdiv'+formno+'"><a onclick = "viewfilepath(\'' + links[0] + '\',\'' + formno + '\')" class="r-text" style="text-decoration:none" >View &#8250;&#8250;</a></td></tr></table>';
				document.getElementById(divid).style.display='none';
			}
			if(spanid != '' && formno != '5')
			{
				document.getElementById(spanid).innerHTML = '<div id="linkdetailsdiv'+formno+'" style="display:block"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="18%" id="viewdetailsdiv'+formno+'"><a onclick = "viewfilepath(\'' + links[0] + '\',\'' + formno + '\')" class="r-text" style="text-decoration:none" >View &#8250;&#8250;</a></td><td id="deletedetailsdiv'+formno+'" width="82%"><a onclick="deletefilepath(\'' + links[2] + '\');" class="r-text" style="text-decoration:none" >Delete &#8250;&#8250;</a></td></tr></table>';
				document.getElementById(divid).style.display='none';
				//reset();
				break;
			}
			else
			{
				document.getElementById(divid).style.display='none';
				break;
			}
	}
	document.getElementById('f1_upload_form'+formno).style.visibility = 'visible';      
    document.getElementById('f1_upload_process'+formno).style.visibility = 'visible';
	return true;   
}

function fileuploaddivid(spanid,textfield,divid,top,left,formslno,cusid,linkid,deleteid)
{
	var dividstyle = document.getElementById(divid).style;
	dividstyle.display='block';
	dividstyle.position = 'absolute';
	dividstyle.left = left;
	dividstyle.top = top;
	dividstyle.width = '400px';
	dividstyle.background = '#5989d5';
	
	document.getElementById('span_downloadlinkfile'+formslno).value = spanid;
	document.getElementById('text_filebox'+formslno).value = textfield;
	document.getElementById('cusid'+formslno).value = cusid;
	document.getElementById('link'+formslno).value = linkid;
	document.getElementById('deletelink'+formslno).value = deleteid;
}



