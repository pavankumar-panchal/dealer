// JavaScript Document

function createajax()
{
   var objectname = false;	
	try { /*Internet Explorer Browsers*/ objectname = new ActiveXObject('Msxml2.XMLHTTP'); } 
	catch (e)
	{
		try { objectname = new ActiveXObject('Microsoft.XMLHTTP'); } 
		catch (e)  
		{
			try { /*// Opera 8.0+, Firefox, Safari*/ objectname = new XMLHttpRequest();	} 
			catch (e) { /*Something went wrong*/ alert('Your browser is not responding for Javascripts.'); return false; }
		}
	}
	return objectname;
}

function districtcodeFunction(selectid, comparevalue)
{
	var statecode = document.getElementById('state').value;
	var districtDisplay = document.getElementById('districtcodedisplay');
	passData = "statecode=" + statecode  + "&dummy=" + Math.floor(Math.random()*1100011000000);
	ajaxcalld = createajax();
	var queryString = "../ajax/selectdistrictonstate.php";
	ajaxcalld.open("POST", queryString, true);
	ajaxcalld.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcalld.onreadystatechange = function()
	{
		if(ajaxcalld.readyState == 4)
		{
			districtDisplay.innerHTML = ajaxcalld.responseText;
			if(selectid && comparevalue)
			autoselect(selectid, comparevalue);
		}
	}
	ajaxcalld.send(passData);return true;
}

function autoselect(selectid,comparevalue)
{
	var selection = document.getElementById(selectid);
	for(var i = 0; i < selection.length; i++) 
	{
		if(selection[i].value == comparevalue)
		{
			selection[i].selected = "1";
			return;
		}
	}
}

function IsNumeric(sText)
{
   var ValidChars = "0123456789.";
   var IsNumber=true;
   var Char; 
   for (i = 0; i < sText.length && IsNumber == true; i++) 
      { 
      Char = sText.charAt(i); 
      if (ValidChars.indexOf(Char) == -1) 
         {
         IsNumber = false;
         }
      }
	  if(IsNumber == false)
	   sText = '';
   return sText;
}


//Function to check the particular option in <input type =check> Tag, with the compare value------------------------
function autocheck(selectid,comparevalue)
{
	var selection = selectid;
		if('yes' == comparevalue)
		{
			selection.checked = true;
			return;
		}
		else
		{
			selection.checked = false;
			return;
		}
}

//Function to check the particular option in <input type =check> Tag, with the compare value------------------------
function autochecknew(selectid,comparevalue)
{
		var selection = selectid;
		if('yes' == comparevalue)
		{
			$(selection).attr('checked',true)
			return;
		}
		else
		{
			$(selection).attr('checked',false)
			return;
		}
}

//function to validate the GSTIN
function validategstin(contactname)
{
 var numericExpression = /^[a-zA-Z0-9_.-]*$/i;
 if(contactname.match(numericExpression)) return true;
 else return false;
}


function validategstinregex(value,stategstcode) {
 var valueEntered = value;
 var stategstcode = stategstcode;
 
 var valueEntered_new = valueEntered.substring(0, 2);
 //alert(stategstcode + valueEntered)
 //if(contactname.match(numericExpression)) return true;
 //else return false;
  if(valueEntered_new != stategstcode) {
    return false;
  }
  else
  {
      return true;
  }
}

/*function formsubmit(userid)
{
	var form = document.submitform; 
	var error = document.getElementById('form-error');
	var field = form.contactperson;
	if(!field.value){error.innerHTML =errormessage("Enter the contact Name"); return false; field.focus(); }
	var field = form.address;
	if(!field.value){error.innerHTML =errormessage("Enter the Address"); return false; field.focus(); }
	
	var field = form.state;
	if(!field.value){error.innerHTML = errormessage("Select the state");  return false; field.focus();}
	var field = form.district;
	if(!field.value){error.innerHTML =errormessage("Select the District"); return false; field.focus();}
	var field = form.pincode;
	if(!field.value){error.innerHTML =errormessage("Enter the Pincode"); return false; field.focus();}
	if(field.value) { if(!validatepincode(field.value)) { error.innerHTML = errormessage('Enter the valid PIN Code.'); field.focus(); return false; } }
	var field = form.emailid;
	if(!field.value){error.innerHTML ="Enter the Email Id"; }
	if(field.value)	{ if(!emailvalidation(field.value)) { error.innerHTML = errormessage('Enter the valid Email ID.'); field.focus(); return false; } }
	var field = form.region;
	if(!field.value){error.innerHTML = errormessage("Select the Region"); return false; field.focus();}
	var field = form.stdcode;
	if(!field.value){error.innerHTML = errormessage("Enter the STD code"); return false; field.focus();}
	if(field.value) { if(!validatestdcode(field.value)) { error.innerHTML = 'Enter the valid STD Code.'; field.focus(); return false; } }
	var field = form.phone;
	if(!field.value){error.innerHTML = errormessage("Enter the Phone Number"); return false; field.focus();}
	if(field.value) { if(!validatephone(field.value)) { error.innerHTML = 'Enter the valid Phone Number.'; field.focus(); return false; } }
	var field = form.cell;
	if(!field.value){error.innerHTML = errormessage("Enter the Cell number"); return false; field.focus();}
	if(field.value) { if(!validatecell(field.value)) { error.innerHTML = errormessage('Enter the valid Cell Number.'); field.focus(); return false; } }
	var field = form.website;
	var field = form.place;
	if(!field.value){error.innerHTML = errormessage("Enter the Place"); return false; field.focus();}
	else
	{
	var passData = '';
	passData = "switchtype=tempsave&contactperson=" + form.contactperson.value  + "&address=" + form.address.value + "&place=" + form.place.value + "&state=" + form.state.value + "&district=" + form.district.value + "&pincode=" + form.pincode.value + "&region=" + form.region.value + "&stdcode=" + form.stdcode.value + "&phone=" + form.phone.value + "&cell=" + form.cell.value + "&emailid=" + form.emailid.value +"&website=" + form.website.value +"&userid=" + userid;alert(passData);
	ajaxcall0 = createajax();alert(passData);
	var queryString = "../ajax/dealerlogin.php";
	ajaxcall0.open("POST", queryString, true);
	ajaxcall0.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall0.onreadystatechange = function()
	{
		if(ajaxcall0.readyState == 4)
		{
			var response = ajaxcall0.responseText;alert(response);
			document.getElementById('form-error').innerHTML = successmessage(response);
		}
	}
	ajaxcall0.send(passData);
	}
}*/

function getprocessingimage()
{
	var imagehtml = '<img src="../images/imax-loading-image.gif" border="0"/>';
	return imagehtml;
}

function validatepincode(pincodenumber)
{
	var numericExpression = /^[^0]+[0-9]{5}$/i;
	if(pincodenumber.match(numericExpression)) return true;
	else return false;
}

function validatecell(cellnumber)
{
	var numericExpression = /^[6|7|8|9]\d{9}(?:(?:([,][\s]|[;][\s]|[,;])[6|7|8|9]\d{9}))*$/i;
	//var numericExpression = /^[7|8|9]+[0-9]{9,9}(?:(?:[,;][7|8|9]+[0-9]{9,9}))*$/i;
	if(cellnumber.match(numericExpression)) return true;
	else return false;
}

function validatephone(phonenumber)
{
	var numericExpression = /^[^9]\d{5,7}(?:(?:([,][\s]|[;][\s]|[,;])[^9]\d{5,7}))*$/i;
	if(phonenumber.match(numericExpression)) return true;
	else return false;
}
function validatephones(phonenumber)
{
	var numericExpression = /^(?:[1-9]\d*|\d)$/;
	if(phonenumber.match(numericExpression)) return true;
	else return false;
}

function emailvalidation(emailid)
{
	var emailExp = /^[A-Z0-9\._%-]+@[A-Z0-9\.-]+\.[A-Z]{2,8}(?:(?:[,;][A-Z0-9\._%-]+@[A-Z0-9\.-]+))*$/i;
	var emails = emailid.replace(/[,\s]*,[,\s]*/g, ",").replace(/^,/, "").replace(/,$/, "");
	if(emails.match(emailExp)) { return true; }
	else { return false; }
}

function validatestdcode(stdcodenumber)
{
	var numericExpression = /^[0]+[0-9]{2,4}$/i;
	if(stdcodenumber.match(numericExpression)) return true;
	else return false;
}

function cellvalidation(cellnumber)
{
 var numericExpression = /^[6|7|8|9]+[0-9]{9,9}$/i;
 if(cellnumber.match(numericExpression)) return true;
 else return false;
}

function contactpersonvalidate(contactname)
{
 var numericExpression = /^([A-Z\s\()]+[a-zA-Z\s()])$/i;
 if(contactname.match(numericExpression)) return true;
 else return false;
}

function checkemail(mailid)
{
 var numericExpression = /^[A-Z0-9\._%-]+@[A-Z0-9\.-]+\.[A-Z]{2,10}$/i;
 if(mailid.match(numericExpression)) return true;
 else return false;
}

function errormessage(message)
{
	var msg = '<div class="errorbox">' + message + '</div>';
	return msg;
}

function successmessage(message)
{
	var msg = '<div class="successbox">' + message + '</div>';
	return msg;
}
function successsearchmessage(message)
{
	var msg = '<div class="successsearchbox">' + message + '</div>';
	return msg;
}


function getradiovalue(radioname)
{
	if(radioname.value)
		return radioname.value;
	else
	{
		for(var i = 0; i < radioname.length; i++) 
		{
			if(radioname[i].checked) 
				return radioname[i].value;
		}
	}
}


function bodyonload(dealerid)
{	
/*	if(typeof getcustomerlist1 == 'function') { getcustomerlist1(); }	*/
	if(typeof getcurrentcredit == 'function') { getcurrentcredit(dealerid); }
	if(typeof getdealerdetails == 'function') { getdealerdetails(dealerid); }	
	if(typeof getscheme == 'function') { getscheme('displayschemecode',dealerid); }	
	if(typeof getproduct == 'function') { getproduct('displayproductcode',$('#scheme').val()); }	
	if(typeof getdealer == 'function') { getdealer('displayalldealer',dealerid); }
	if(typeof checkdealerselection == 'function') { checkdealerselection ('displaydealer',dealerid); }	
	if(typeof checkleftdealerselection == 'function') { checkleftdealerselection ('displayleftdealer',dealerid); }
	if(typeof getinvoicedetails == 'function') { getinvoicedetails(''); }
	if(typeof disableformelemnts_invoicing == 'function') { disableformelemnts_invoicing(); }
	if(typeof addInfoCopyButton == 'function') { addInfoCopyButton(); }
	if(typeof dasboard_short_keys == 'function') { dasboard_short_keys(); }
	if(typeof customer_short_keys == 'function') { customer_short_keys(); }
	if(typeof generatecarddetails == 'function') { generatecarddetails(''); }	
	if(typeof getalldummydetails == 'function') { getalldummydetails()};
	if(typeof getimpalldatadetails == 'function') { getimpalldatadetails('all')};
}


function displaysuccessmessage(message)
{
	var msg = '<div class="displaysuccess">' + message + '</div>';
	return msg;
}

function disablebutton(element)
{
	$(element).attr('disabled',true);
	$(element).removeClass('swiftchoicebutton');
	$(element).addClass('swiftchoicebuttondisabled');
}

function enablebutton(element)
{
	$(element).attr('disabled',false);
	$(element).removeClass('swiftchoicebuttondisabled');
	$(element).addClass('swiftchoicebutton');
}



function gridtabcus4(activetab,tabgroupname,tabdescription)
{
	var totaltabs = 5;
	var activetabheadclass = 'grid-active-tabclass';
	var tabheadclass = 'grid-tabclass';
	
	for(var i=1; i<=totaltabs; i++)
	{
		var tabhead = tabgroupname + 'h' + i;
		var tabcontent = tabgroupname + 'c' + i;
		var tabwaitbox = tabgroupname + 'wb' + i;
		if(i == activetab)
		{
			$('#'+tabhead).attr('class',activetabheadclass);
			$('#'+tabcontent).show();
			if($('#'+tabwaitbox)) {$('#'+tabwaitbox).show(); }
			$('#tabdescription').html(tabdescription);
		}
		else
		{
			$('#'+tabhead).attr('class',tabheadclass);
			$('#'+tabcontent).hide();
			if($('#'+tabwaitbox)) { $('#'+tabwaitbox).hide(); }
			//document.getElementById('tabdescription').innerHTML = '';
		}
	}
}

function tabopen6(activetab,tabgroupname)
{
	var totaltabs = 7;
	var activetabheadclass = "producttabheadactive";
	var tabheadclass = "producttabhead";
	
	for(var i=1; i<=totaltabs; i++)
	{

		var tabhead = tabgroupname + 'h' + i;
		var tabcontent = tabgroupname + 'c' + i;
		if(i == activetab)
		{

			//document.getElementById(tabhead).className = activetabheadclass;
			$('#'+tabcontent).show();
			//alert(tabcontent);
		}
		else
		{

		//	document.getElementById(tabhead).className = tabheadclass;
			$('#'+tabcontent).hide();
			//alert(tabcontent);
		}
	}
}


//Function to enable the save button--------------------------------------------------------------------------------
function enablesave()
{
	$('#save').attr('disabled',false);
	$('#save').attr('class','swiftchoicebutton');
}

//Function to disable the save button--------------------------------------------------------------------------------
function disablesave(){
	$('#save').attr('disabled',true);
	$('#save').attr('class','swiftchoicebuttondisabled');
	$('#save').css('cursor','');
}

//Function to enable the New button--------------------------------------------------------------------------------
function enablenew()
{
	$('#new').attr('disabled',false);
	$('#new').attr('class','swiftchoicebutton');
}

//Function to disable the New button--------------------------------------------------------------------------------
function disablenew(){
	$('#new').attr('disabled',true);
	$('#new').attr('class','swiftchoicebuttondisabled');
	$('#new').css('cursor','');
}

function validateamount(amount)
{
	var numericExpression = /^[-+]?[0-9]\d{0,9}(\.\d{1,2})?%?$/;
	if(amount.match(numericExpression)) return true;
	else return false;
}
function validatepositivenumbers(amount)
{
	var numericExpression = /^[+]?[0-9]\d{0,9}(\.\d{1,2})?%?$/;
	if(amount.match(numericExpression)) return true;
	else return false;
}

function validateamountfield(amount)
{
	var numericExpression = /^[+]?[0-9]\d{0,9}?%?$/;
	if(amount.match(numericExpression)) return true;
	else return false;
}
function enablenext()
{
	$('#next').attr('disabled',false);
	$('#next').attr('class','swiftchoicebutton');
	$('#next').css('cursor','pointer');
}


function disablenext()
{
	$('#next').attr('disabled',true);
	$('#next').attr('class','swiftchoicebuttondisabled');
	$('#next').css('cursor','');
}

function disablesend()
{
	$('#send').attr('disabled',true);
	$('#send').attr('class','swiftchoicebuttondisabled');
	$('#send').css('cursor','');
}


function displayelement(displayelementid,hideelementid)
{
	var delement = $('#'+displayelementid);
	var helement = $('#'+hideelementid);
	delement.show();; helement.hide(); 
}


//Validation of website - common function  Rashmi -18/11/2009
function validatewebsite(website)
{
	var websiteExpression = /^(www\.)?[a-zA-Z0-9-\.,]+\.[a-zA-Z]{2,4}$/i;
	if(website.match(websiteExpression)) return true;
	else return false;
}

//Function to enable the delete button------------------------------------------------------------------------------
function enabledelete()
{
	$('#delete').attr('disabled',false);
	$('#delete').attr('className','swiftchoicebutton');
}
//Function to display a error message if the script failed-Meghana[11/12/2009]
function scripterror()
{
	var msghtml = '<div class="errorbox">Unable to Connect....</div>';
	return msghtml;
}


function in_array(checkvalue, arrayobject) 
{
	for(var i = 0, l = arrayobject.length; i < l; i++) 
	{
		if(arrayobject[i] == checkvalue) 
		{
			return true;
		}
	}
	return false;
}


function computeridvalidate(compid)
{
	var numericExpresion = /^[0-9]{3}0[0|9]-[0-9]{9}$/;
	if(compid.match(numericExpresion)) return true;
	return false;
}

function validatecontactperson(contactname)
{
	var numericExpression = /^([A-Z\s\()]+[a-zA-Z\s()])(?:(?:[,;]([A-Z\s()]+[a-zA-Z\s()])))*$/i;
	if(contactname.match(numericExpression)) return true;
	else return false;
}


function validatebusinessname(contactname)
{
	var numericExpression = /^([A-Z0-9\s\-()]+[a-zA-Z0-9\s-()])(?:(?:[,;]([A-Z0-9\s-()]+[a-zA-Z0-9\s-()])))*$/i;
	if(contactname.match(numericExpression)) return true;
	else return false;
}

//Function to change the css of active tab and select the tab in display grid part----------------------------------
function gridtab2(activetab,tabgroupname,tabdescription)
{
	var totaltabs = 2;
	var activetabheadclass = 'grid-active-tabclass';
	var tabheadclass = 'grid-tabclass';
	
	for(var i=1; i<=totaltabs; i++)
	{
		var tabhead = tabgroupname + 'h' + i;
		var tabcontent = tabgroupname + 'c' + i;
		var tabwaitbox = tabgroupname + 'wb' + i;
		var tabviewbox = tabgroupname + 'view' + i;

		
		if(i == activetab)
		{
			$('#'+tabhead).removeClass(tabheadclass);
			$('#'+tabhead).addClass(activetabheadclass);
			if($('#'+tabcontent)) { $('#'+tabcontent).show(); }
			$('#'+tabcontent).show();
			if($('#'+tabwaitbox)) { $('#'+tabwaitbox).show(); }
			if($('#'+tabviewbox)) { $('#'+tabviewbox).show(); }
			$('#tabdescription').html(tabdescription);
		}
		else
		{
			$('#'+tabhead).removeClass(activetabheadclass);
			$('#'+tabhead).addClass(tabheadclass);
			$('#'+ tabcontent).hide();
			if($('#'+tabwaitbox)) { $('#'+tabwaitbox).hide(); }
			if($('#'+tabviewbox)) { $('#'+tabviewbox).hide(); }
		}
	}
}


function tabopen5(activetab,tabgroupname)
{
	var totaltabs = 2;
	var activetabheadclass = "producttabheadactive";
	var tabheadclass = "producttabhead";
	
	for(var i=1; i<=totaltabs; i++)
	{
		var tabhead = tabgroupname + 'h' + i;
		var tabcontent = tabgroupname + 'c' + i;
		if(i == activetab)
		{
			$('#'+tabhead).attr('class',activetabheadclass);
			$('#'+tabcontent).show();
		}
		else
		{
			$('#'+tabhead).attr('class',tabheadclass);
			$('#'+tabcontent).hide();
		}
	}
}


function checkdate(datevalue) //dd-mm-yyyy Eg: 01-04-2008
{
 if(datevalue.length == 10)
 {
  if(isanumber(datevalue.charAt(0)) && isanumber(datevalue.charAt(1)) && isanumber(datevalue.charAt(3)) && isanumber(datevalue.charAt(4)) && isanumber(datevalue.charAt(6)) && isanumber(datevalue.charAt(7)) && isanumber(datevalue.charAt(8)) && isanumber(datevalue.charAt(9)) && datevalue.charAt(2) == '-' && datevalue.charAt(5) == '-')
   return true;
  else
   return false;
 }
 else
  return false;
}


//Function to Convert Number to Words
function NumbertoWords(s)
{
	var th = ['','Thousand','Million', 'Billion','Trillion'];

	var dg = ['Zero','One','Two','Three','Four', 'Five','Six','Seven','Eight','Nine']; 
	var tn = ['Ten','Eleven','Twelve','Thirteen', 'Fourteen','Fifteen','Sixteen', 'Seventeen','Eighteen','Nineteen']; 
	var tw = ['Twenty','Thirty','Forty','Fifty', 'Sixty','Seventy','Eighty','Ninety']; 
	s = s.toString();
	s = s.replace(/[\, ]/g,''); 
	if (s != parseFloat(s)) return 'not a number'; 
	var x = s.indexOf('.'); if (x == -1) x = s.length; 
	if (x > 15) return 'too big'; var n = s.split(''); 
	var str = ''; 
	var sk = 0; 
	for (var i=0; i < x; i++) 
	{
		if ((x-i)%3==2) 
		{
			if (n[i] == '1') 
			{
				str += tn[Number(n[i+1])] + ' '; i++; sk=1;
			}
			else 
			if (n[i]!=0) 
			{
				str += tw[n[i]-2] + ' ';
				sk=1;
			}
		} 
		else 
		if (n[i]!=0) 
		{
			str += dg[n[i]] +' '; 
			if ((x-i)%3==0) 
			str += 'hundred ';sk=1;
		} 
		if ((x-i)%3==1) 
		{
			if (sk) 
				str += th[(x-i-1)/3] + ' ';sk=0;
		}
	} 
	if (x != s.length)
	{
		var y = s.length; 
		str += 'point '; 
		for (var i=x+1; i<y; i++) 
			str += dg[n[i]] +' ';
	} 
	return str.replace(/\s+/g,' ')+ 'only';
}

function removedoublecomma(string)
{
	finalstring = string;
	var newArr = new Array();for (k in finalstring) if(finalstring[k]) newArr.push(finalstring[k])
	return newArr;
}


function intToFormat(nStr)
{
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	var z = 0;
	var len = String(x1).length;
	var num = parseInt((len/2)-1);
	while (rgx.test(x1))
	{
		if(z > 0)
		{
			x1 = x1.replace(rgx, '$1' + ',' + '$2');
		}
		else
		{
			x1 = x1.replace(rgx, '$1' + ',' + '$2');
			rgx = /(\d+)(\d{2})/;
		}
		z++;
		num--;
		if(num == 0)
		{
			break;
		}
	}
	return x1 + x2;
}

function trimdotspaces(text)
{
	var output = text.replace(/ /g,""); 
	var output2 = output.replace(/\./g,"");
	return output2;
}


function tabopenimp2(activetab,tabgroupname)
{
	var totaltabs = 2;
	var activetabheadclass = "imptabheadactive";
	var tabheadclass = "imptabhead";
	
	for(var i=1; i<=totaltabs; i++)
	{
		var tabhead = tabgroupname + 'h' + i;
		var tabcontent = tabgroupname + 'c' + i;
		if(i == activetab)
		{
			$('#'+tabhead).removeClass(tabheadclass);
			$('#'+tabhead).addClass(activetabheadclass);
			$('#'+tabcontent).show();
		}
		else
		{
			$('#'+tabhead).removeClass(activetabheadclass);
			$('#'+tabhead).addClass(tabheadclass);
			$('#'+ tabcontent).hide();
		}
	}
}

function selectdeselectcommon(selectionid,checkboxname)
{
	var selectproduct = $('#' + selectionid);
	var chkvalues = $("input[name='"+ checkboxname +"']");
	for (var i=0; i < chkvalues.length; i++)
	{
		if($(chkvalues[i]).is(':checked'))
		{
			$(chkvalues[i]).attr('checked',false);
		}
		if(($('#'+selectionid).is(':checked')) == true) 
			$(chkvalues[i]).attr('checked',true);
		else if(($('#'+selectionid).is(':checked')) == false) 
			$(chkvalues[i]).attr('checked',false);
	}
}

function sortTable(n) {
   
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("myTable");
  switching = true;

  dir = "asc"; 
  /*img='<img src="../images/arrow.png" height="10px" width="10px">';
   img1='<img src="../images/downarrow.png" height="10px" width="10px">';
  text=$(el).text();
  html=$(el).html();
  if(text.match(img1))
   $(el).html(text+img);
  else
  $(el).html(text+img1);*/
  
  while (switching) {
 
    switching = false;
    rows = table.getElementsByTagName("TR");
 
    for (i = 1; i < (rows.length - 1); i++) {
     
      shouldSwitch = false;

      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
   
      if (dir == "asc") {
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
      
          shouldSwitch= true;
         
          break;
        }
      } else if (dir == "desc") {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
         
          shouldSwitch= true;
         
          break;
        }
      }
    }
    if (shouldSwitch) {

      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;

      switchcount ++;      
    } else {
 
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}

function tabopenimp2(activetab,tabgroupname)
{
	var totaltabs = 2;
	var activetabheadclass = "imptabheadactive";
	var tabheadclass = "imptabhead";
	
	for(var i=1; i<=totaltabs; i++)
	{
		var tabhead = tabgroupname + 'h' + i;
		var tabcontent = tabgroupname + 'c' + i;
		if(i == activetab)
		{
			$('#'+tabhead).removeClass(tabheadclass);
			$('#'+tabhead).addClass(activetabheadclass);
			$('#'+tabcontent).show();
		}
		else
		{
			$('#'+tabhead).removeClass(activetabheadclass);
			$('#'+tabhead).addClass(tabheadclass);
			$('#'+ tabcontent).hide();
		}
	}
}