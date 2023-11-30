// JavaScript Document
var cur = -1;
var ajaxcall2 = '';
var textbox;
var textid;
var divid;
var lookouttype;
var Keyval;
var WIE;
var WMF;
var PIE = 'fixed';
var PMF = 'absolute';
var hiddenregistrationtype; //used only in finding the scratch numbers
var customerreference; //used only in finding the scratch numbers
var productcode; //used only in finding the scratch numbers
var scratchcardfromfield; //used only in finding the scratch numbers after the from field
var dealerid; //used only in finding the scratch numbers after the from field
function dealerlookout(ev)
{
	WMF = '208px';
	WIE = '205px';
	textbox = document.getElementById('searchdealer');
	textid = document.getElementById('searchdealerid');
	divid = document.getElementById("loaddealerlist");
	lookouttype = "dealer"; 
	if(document.getElementById('lastslno')) { document.getElementById('lastslno').value = textid.value; }
	if(ev)
		Keyval = ev.keyCode;
	else
		Keyval = window.event.keyCode;
	showCompCat();
}

function ttdealerlookout(ev)
{
	WMF = '196px';
	WIE = '194px';
	textbox = document.getElementById('ttdealertext');
	textid = document.getElementById('ttdealer');
	divid = document.getElementById("loadttdealerlist");
	lookouttype = "ttdealer";
	if(ev)
		Keyval = ev.keyCode;
	else
		Keyval = window.event.keyCode;
	showCompCat();
}

function ttproductlookout(ev)
{
	WMF = '196px';
	WIE = '194px';
	textbox = document.getElementById('ttproducttext');
	textid = document.getElementById('ttproduct');
	divid = document.getElementById("loadttproductlist");
	lookouttype = "ttproduct";
	if(ev)
		Keyval = ev.keyCode;
	else
		Keyval = window.event.keyCode;
	showCompCat();
}

function firstdealerlookout(ev)
{
	WMF = '196px';
	WIE = '194px';
	textbox = document.getElementById('searchfirstdealerid');
	textid = document.getElementById('firstdealer');
	divid = document.getElementById("loadfirstdealerlist");
	lookouttype = "firstdealer";
	if(ev)
		Keyval = ev.keyCode;
	else
		Keyval = window.event.keyCode;
	showCompCat();
}

function delaerreplookout(ev)
{
	WMF = '196px';
	WIE = '194px';
	textbox = document.getElementById('searchdelaerrep');
	textid = document.getElementById('delaerrep');
	divid = document.getElementById("loaddelaerreplist");
	lookouttype = "delaerrep";
	if(ev)
		Keyval = ev.keyCode;
	else
		Keyval = window.event.keyCode;
	if(textbox.readOnly == false)
		showCompCat();
}

function scratchcardfromlookout(ev)
{
	WMF = '196px';
	WIE = '194px';
	textbox = document.getElementById('scratchcardfromtext');
	textid = document.getElementById('scratchcardfrom');
	divid = document.getElementById("loadscratchcardfromlist");
	lookouttype = "scratchcardfrom";
	if(ev)
		Keyval = ev.keyCode;
	else
		Keyval = window.event.keyCode;
	if(textbox.readOnly == false)
		showCompCat();
}

function scratchcardtolookout(ev)
{
	WMF = '196px';
	WIE = '194px';
	textbox = document.getElementById('scratchcardtotext');
	textid = document.getElementById('scratchcardto');
	divid = document.getElementById("loadscratchcardtolist");
	scratchcardfromfield = document.getElementById('scratchcardfrom').value;
	lookouttype = "scratchcardto";
	if(ev)
		Keyval = ev.keyCode;
	else
		Keyval = window.event.keyCode;
	if(textbox.readOnly == false)
		showCompCat();
}

function scratchcardfromreglookout(ev)
{
	WMF = '196px';
	WIE = '194px';
	textbox = document.getElementById('scratchcardfromtext');
	textid = document.getElementById('scratchcardfrom');
	divid = document.getElementById("loadscratchcardfromlist");
	lookouttype = "scratchcardfromreg";
	dealerid = document.getElementById('dealerlist').value;
	
	if(ev)
		Keyval = ev.keyCode;
	else
		Keyval = window.event.keyCode;
	if(textbox.readOnly == false)
		showCompCat();
}

function scratchcardtoreglookout(ev)
{
	WMF = '196px';
	WIE = '194px';
	textbox = document.getElementById('scratchcardtotext');
	textid = document.getElementById('scratchcardto');
	divid = document.getElementById("loadscratchcardtolist");
	scratchcardfromfield = document.getElementById('scratchcardfrom').value;
	lookouttype = "scratchcardtoreg";
	dealerid = document.getElementById('dealerlist').value;
	if(ev)
		Keyval = ev.keyCode;
	else
		Keyval = window.event.keyCode;
	if(textbox.readOnly == false)
		showCompCat();
}

function scratchcardfromunreglookout(ev)
{
	WMF = '196px';
	WIE = '194px';
	textbox = document.getElementById('scratchcardfromtext');
	textid = document.getElementById('scratchcardfrom');
	divid = document.getElementById("loadscratchcardfromlist");
	lookouttype = "scratchcardfromunreg";
	dealerid = document.getElementById('dealerlist').value;
	if(ev)
		Keyval = ev.keyCode;
	else
		Keyval = window.event.keyCode;
	if(textbox.readOnly == false)
		showCompCat();
}

function scratchcardtounreglookout(ev)
{
	WMF = '196px';
	WIE = '194px';
	textbox = document.getElementById('scratchcardtotext');
	textid = document.getElementById('scratchcardto');
	divid = document.getElementById("loadscratchcardtolist");
	scratchcardfromfield = document.getElementById('scratchcardfrom').value;
	dealerid = document.getElementById('dealerlist').value;
	lookouttype = "scratchcardtounreg";
	if(ev)
		Keyval = ev.keyCode;
	else
		Keyval = window.event.keyCode;
	if(textbox.readOnly == false)
		showCompCat();
}


function scratchnumberlookout(ev)
{	
	WMF = '196px';
	WIE = '194px';
	textbox = document.getElementById('searchscratchnumber');
	textid = document.getElementById('scratchnumber');
	divid = document.getElementById("loadscratchnumberlist");
	lookouttype = "scratchnumber";
	hiddenregistrationtype = document.getElementById('hiddenregistrationtype').value;
	customerreference = document.getElementById('lastslno').value; 
	productcode = document.getElementById('productcode').value;
	document.getElementById('transfercardfield').value = textbox.value;
	if(ev)
		Keyval = ev.keyCode;
	else
		Keyval = window.event.keyCode;
	showCompCat();
}

function selectscratchnumberlookout(ev)
{
	WMF = '196px';
	WIE = '194px';
	textbox = document.getElementById('searchscratchnumber');
	textid = document.getElementById('scratchnumber');
	divid = document.getElementById("loadscratchnumberlist");
	lookouttype = "selectscratchnumber";
	
	if(ev)
		Keyval = ev.keyCode;
	else
		Keyval = window.event.keyCode;
	showCompCat();
}

function cusbillnumberlookout(ev)
{
	WMF = '196px';
	WIE = '194px';
	textbox = document.getElementById('searchcusbillnumber');
	textid = document.getElementById('cusbillnumber');
	divid = document.getElementById("loadcusbillnumberlist");
	lookouttype = "cusbillnumber";
	
	if(ev)
		Keyval = ev.keyCode;
	else
		Keyval = window.event.keyCode;
	showCompCat();
}

function customerlookout(ev)
{
	WMF = '208px';
	WIE = '205px';
	textbox = document.getElementById('searchtext');
	textid = document.getElementById('searchtextid');
	divid = document.getElementById("loadcustomerlist");
	lookouttype = "customer";
	if(document.getElementById('lastslno')) 
	document.getElementById('lastslno').value = textid.value;
	if(ev)
		Keyval = ev.keyCode;
	else
		Keyval = window.event.keyCode;
	showCompCat();
}

function customerfromlookout(ev)
{
	WMF = '196px';
	WIE = '194px';
	textbox = document.getElementById('searchcustomerfrom');
	textid = document.getElementById('customerfrom');
	divid = document.getElementById("loadcustomerfromlist");
	lookouttype = "customerfrom";
	if(ev)
		Keyval = ev.keyCode;
	else
		Keyval = window.event.keyCode;
	if(textbox.readOnly == false)
		showCompCat();
}
function customertolookout(ev)
{
	WMF = '196px';
	WIE = '194px';
	textbox = document.getElementById('searchcustomerto');
	textid = document.getElementById('customerto');
	divid = document.getElementById("loadcustomertolist");
	lookouttype = "customerto";
	if(ev)
		Keyval = ev.keyCode;
	else
		Keyval = window.event.keyCode;
	if(textbox.readOnly == false)
		showCompCat();
}


function showCompCat()
{
	ajaxcall2 = '';
	
	var searchvalue = textbox.value;	//alert(searchvalue);
	if(searchvalue.length > 0 && (Keyval!=13 && Keyval!=38 && Keyval!=40))
	{
		if(lookouttype == 'scratchnumber')
		{
		var passData = "lookouttype=" + lookouttype + "&searchtext=" + escape(searchvalue) + "&hiddenregistrationtype=" + hiddenregistrationtype + "&customerreference=" + customerreference + "&productcode=" + productcode + "&dummy=" + Math.floor(Math.random()*10054300000);
		}
		else if(lookouttype == 'scratchcardto')
		{
			var passData = "lookouttype=" + lookouttype + "&scratchcardfromfield=" + scratchcardfromfield + "&searchtext=" + escape(searchvalue) + "&dummy=" + Math.floor(Math.random()*10054300000);
		}
		else if(lookouttype == 'scratchcardtounreg')
		{
			var passData = "lookouttype=" + lookouttype + "&scratchcardfromfield=" + scratchcardfromfield + "&dealerid=" + dealerid + "&searchtext=" + escape(searchvalue) + "&dummy=" + Math.floor(Math.random()*10054300000);
		}
		else if(lookouttype == 'scratchcardtoreg')
		{
			var passData = "lookouttype=" + lookouttype + "&scratchcardfromfield=" + scratchcardfromfield + "&dealerid=" + dealerid + "&searchtext=" + escape(searchvalue) + "&dummy=" + Math.floor(Math.random()*10054300000);
		}
		else if(lookouttype == 'scratchcardfromunreg')
		{
			var passData = "lookouttype=" + lookouttype + "&searchtext=" + escape(searchvalue) + "&dealerid=" + dealerid + "&dummy=" + Math.floor(Math.random()*10054300000);
		}
		else if(lookouttype == 'scratchcardfromreg')
		{
			var passData = "lookouttype=" + lookouttype + "&searchtext=" + escape(searchvalue) + "&dealerid=" + dealerid + "&dummy=" + Math.floor(Math.random()*10054300000);
		}
		else
		{
			var passData = "lookouttype=" + lookouttype + "&searchtext=" + escape(searchvalue) + "&dummy=" + Math.floor(Math.random()*10054300000);
		}
		ajaxcall2 = createajax();
		queryString = "../ajax/lookout.php";
		ajaxcall2.open("POST", queryString, true);
		ajaxcall2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajaxcall2.onreadystatechange = showResult; 
		ajaxcall2.send(passData);
	}
	else if(Keyval==13 || searchvalue=="")
	{
		hideResult(divid);
	}
}

function showResult()
{
	textid.value = "";
	
	if (ajaxcall2.readyState==4 || ajaxcall2.readyState=="complete")
	{
		var arrSearchVal = ajaxcall2.responseText.split("|%@%|~|$#`");
		var resultVal="";
		for(var s=0; s<arrSearchVal.length; s++)
		{
			var autoVal = '';
			var autoId = '';
			if(arrSearchVal[s].search(/#/)!= -1 )
			{ 
				var autoArray = arrSearchVal[s].split('#');
				autoVal = autoArray[1];
				businessname = autoVal; 
				autoId = autoArray[0];
				customerreference = autoId;
			}	
			else
			{   
				autoVal = arrSearchVal[s];
			}			
			resultVal += "<div onmousedown=assignVal(this,'"+customerreference+"'); onMouseOver='suggestResultOver(this);' onMouseOut='suggestResultOut(this);'>"+autoVal;
			resultVal += "<input type='hidden' name='"+lookouttype+"autoId' id='"+lookouttype+"autoId"+s+"' value='"+customerreference+"'>";
			resultVal += "<input type='hidden' name='"+lookouttype+"catname' id='"+lookouttype+"catname"+s+"' value='"+businessname+"'>";
			resultVal +="</div>"; 
		}
		
		divid.innerHTML = resultVal;
		var val = navigator.userAgent; 
		if(val.indexOf("MSIE") != -1)
		{ 
			divid.style.width = WIE;
			divid.style.position = PIE;
		} 
		if(val.indexOf("Firefox") != -1)
		{  
			divid.style.width = WMF;
			divid.style.position = PMF;
		} 
		divid.style.visibility = "visible";
		textbox.onkeydown=fn_keyAct;
	}
}

function hideResult(divid)
{
	divid.style.visibility = "hidden";
	divid.innerHTML = '';
		
	if(lookouttype == 'customer')
		customerdetailstoform(textid.value);
	else if(lookouttype == 'dealer')
		dealerdetailstoform(textid.value);
	else if(lookouttype == 'scratchnumber')
		scratchdetailstoform(textid.value);
}

function assignVal(div_value,catId)
{
	var idivval = div_value.innerHTML;
	if(catId!='')
	{ 	
		var Array_divval = idivval.split("<input");
		var result_final = Array_divval[0].replace('&amp;','&');
		textbox.value = result_final;
		textid.value = catId;
	}
}


function fn_keyAct(oEvent)
{
	var cSuggestionNodes = divid.childNodes;
	oEvent=oEvent ? oEvent : event;
	iKeyCode =  oEvent.keyCode;
	if (iKeyCode==38)
	{	
		if (cSuggestionNodes.length > 0 && cur > 0) 
		{
			var oNodenext = cSuggestionNodes[cur];
			window.suggestResultOut(oNodenext);
			var oNodecur = cSuggestionNodes[--cur];
			window.suggestResultOver(oNodecur);
			textbox.value = oNodecur.firstChild.nodeValue;   
		}
		else
		{
			var oNodecur = cSuggestionNodes[0];
			window.suggestResultOut(oNodecur);
			cur = cSuggestionNodes.length-1;
		}
	} 
	else if (iKeyCode==40)
	{
		if (cSuggestionNodes.length > 0 && cur < cSuggestionNodes.length-1) 
		{
			if(cur > -1) {
			var oNodeprev = cSuggestionNodes[cur];
			window.suggestResultOut(oNodeprev);
		}
		var oNodecur = cSuggestionNodes[++cur];
		window.suggestResultOver(oNodecur);
		if(oNodecur.firstChild.nodeValue==null)
		{
			cur=-1;
		}
		if(oNodecur.firstChild.nodeValue!=null)
		{
			textbox.value = oNodecur.firstChild.nodeValue;}
		}
		else
		{
			cur = -1;
		}
	} 
	else if (iKeyCode==13)
	{
		var catid = document.getElementById(lookouttype+'autoId'+(cur)).value;
		textid.value = catid;
		var catname = document.getElementById(lookouttype+'catname'+(cur)).value;
		textbox = catname;
		hideResult(divid);
	}
	else
	{
		cur = -1;
		showCompCat();
	}
}

function suggestResultOut(div_value)
{
	div_value.className = 'resultOut';
}

function suggestResultOver(div_value)
{
	div_value.className = 'resultOver';
}

