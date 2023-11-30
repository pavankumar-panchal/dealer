// JavaScript Document
//Page Created By Bhavesh 
var regcardarray = new Array();
var regcardarray1 = new Array();
var regcardarray2 = new Array();
var regcardarray3 = new Array();
var regcardarray4 = new Array();


var process1 = false;
var process2 = false;
var process3 = false;
var process4 = false;

function gettotalcusattachcard()
{
	var form = $('#scratchcradloading');
	var passData = "switchtype=getcardcount&dummy=" + Math.floor(Math.random()*10054300000);//alert(passData)
	queryString = "../ajax/dealerpinallot.php";
	$('#scratchcradloading').html(getprocessingimage());
	ajaxcall1 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			$('#scratchcradloading').html('');//alert(ajaxresponse)
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse;
				if(response == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{//alert (response['count']);
					$('#totalcountofcard').html(response['count']);
					refreshcusattachcardarray(response['count']);
				}
				
			}
		}, 
		error: function(a,b)
		{
			$("#scratchcradloading").html(scripterror());
		}
	});	
}

function refreshcusattachcardarray(cardcount)
{
	var totalcardcount = cardcount;
	
	var limit = Math.round(totalcardcount/4);
	if(limit==0)
	{
		limit=totalcardcount;
	}
	//alert(limit);
	var startindex = 0;
	var startindex1 = (limit)+1;
	//alert (startindex1);
	var startindex2 = (limit*2)+1;
	var startindex3 = (limit*3)+1;
	var form = $('#cardsearchfilterform');
	var passData = "switchtype=getcardlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit+1) + "&startindex=" + encodeURIComponent(startindex);
	var passData1 = "switchtype=getcardlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex1);
	var passData2 = "switchtype=getcardlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex2);
	var passData3 = "switchtype=getcardlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex3);
	
	regcardarray1=[];
	regcardarray2=[];
	regcardarray3=[];
	regcardarray4=[];
	$('#scratchcradloading').html(getprocessingimage());
	queryString = "../ajax/dealerpinallot.php";
	ajaxcall2 = $.ajax(
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
				var response = ajaxresponse;
				//console.log('response 1:'+response);
				for( var i=0; i<response.length; i++)
				{
					regcardarray1[i] = response[i];
				}
				process1 = true;
				compilecardarray();
			}
		}, 
		error: function(a,b)
		{
			$("#scratchcradloading").html(scripterror());
		}
	});	
	
	queryString = "../ajax/dealerpinallot.php";
	ajaxcall3 = $.ajax(
	{
		type: "POST",url: queryString, data: passData1, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse;//alert(response)
				//console.log('response 2:'+response);
				for( var i=0; i<response.length; i++)
				{
					regcardarray2[i] = response[i];
				}
				process2 = true;
				compilecardarray();
			}
		}, 
		error: function(a,b)
		{
			$("#scratchcradloading").html(scripterror());
		}
	});	

	queryString = "../ajax/dealerpinallot.php";
	ajaxcall4 = $.ajax(
	{
		type: "POST",url: queryString, data: passData2, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse;//alert(response)
				//console.log('response 3:'+response);
				for( var i=0; i<response.length; i++)
				{
					regcardarray3[i] = response[i];
				}
				process3 = true;
				compilecardarray();
			}
		}, 
		error: function(a,b)
		{
			$("#scratchcradloading").html(scripterror());
		}
	});	
	
	queryString = "../ajax/dealerpinallot.php";
	ajaxcall5 = $.ajax(
	{
		type: "POST",url: queryString, data: passData3, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse;//alert(response)
				//console.log('response 4:'+response);
				for( var i=0; i<response.length; i++)
				{
					regcardarray4[i] = response[i];
				}
				process4 = true;
				compilecardarray();
			}
		}, 
		error: function(a,b)
		{
			$("#scratchcradloading").html(scripterror());
		}
	});	
}

function compilecardarray()
{
	if(process1 == true && process2 == true && process3 == true && process4 == true)
	{
		regcardarray = regcardarray1.concat(regcardarray2.concat(regcardarray3.concat(regcardarray4)));
		//console.log('regcardarray'+regcardarray);
		getregcardlist();
		$('#scratchcradloading').html('');
		//addcheckbox();//added by samar
	}
	else
	return false;
}

function addcheckbox() {
    
    var length = $('select#scratchcardlist option').length;
    
    for(var i=0; i<length; i++) {
        
        var value = $('#scratchcardlist option:nth-child('+i+')').val();
        $('#scratchcardlist option:nth-child('+i+')').before('<input type="checkbox" name="allotpins[]" value="'+value+'">');
    }
}    

//changing attr to prop
function getregcardlist()
{	
	var form = $('#registrationform');
	var selectbox = $('#scratchcardlist');
	var numberofcards = regcardarray.length;
	var actuallimit = 500;
	var limitlist = (numberofcards > actuallimit)?actuallimit:numberofcards;

	$('option', selectbox).remove();
	var options = selectbox.prop('options');
	
	for( var i=0; i<limitlist; i++)
	{
		var splits = regcardarray[i].split("^");
	
		var purchasetype = splits[2];
		var usagetype = splits[3];
		var typeofpurchase = purchasetype.substring(0,1);
	    var typeofusage= usagetype.substring(0,1);
		typeofpurchase = typeofpurchase.toUpperCase();
		typeofusage= typeofusage.toUpperCase();
		
		typeofusage += 'U';
		
		if(typeofpurchase == 'N') { typeofpurchase = 'New'; }
		if(typeofpurchase == 'U') { typeofpurchase = 'Updation'; }
		
		//var cardvalue = splits[0]+' ('+typeofpurchase+')'+'('+typeofusage+')'+' - '+splits[4];
		
		var cardvalue = splits[0]+' | '+typeofpurchase+' | '+typeofusage+' | '+splits[4];
		
		//$('#scratchcardlist').append('<input type="checkbox" name="allotpins[]" value="'+splits[1]+'"><option value="'+splits[1]+'">'+cardvalue+'</option>');
		//options[options.length] = new Option(cardvalue, splits[1]);//added by samar
		$('#scratchcardlist').append('<option value="'+splits[1]+'">'+cardvalue+'</option>');
	}
	
	//fillmanual();
	//$('.manualselect').fSelect();
    //$('.fs-label-wrap .fs-label').trigger("click");
	setTimeout(function(){fillmanual()},1000);
}

function fillmanual() {
    $('.manualselect').fSelect();
    $('.fs-label-wrap .fs-label').trigger("click");
}    

function reg_selectcardfromlist()
{
	var selectbox = $('#scratchcardlist');
	
	cardnosearch.val($('#scratchcardlist option:selected').text());
	cardnosearch.select();//alert(selectbox.value);
    scratchdetailstoform(selectbox.val());	
	$('#form-error').html('');
}

//changing attr to prop

function reg_selectacard(input)
{
	var selectbox = $('#scratchcardlist');
	
	var productid = $("#selectedproduct").find("option:selected").val();
	var strlength = productid.length;

	if(input == "" && strlength == 0)
	{
		getregcardlist();
	}
	else if(input == "" && strlength != 0)
	{
		selectedproductdealer();
	}
	else
	{
		var initiallength = selectbox.length;
		
		$('option', selectbox).remove();
		var options = selectbox.prop('options');
	
		var addedcount = 0;
		for( var i=0; i < regcardarray.length; i++)
		{
			
			if(input.charAt(0) == ".")
			{
				withoutdot = input.substring(1,input.length);
				pattern = new RegExp("^" + withoutdot.toLowerCase());
				comparestringsplit = regcardarray[i].split("^");
				comparestring = comparestringsplit[1];
			}
			else
			{
				pattern = new RegExp("^" + input.toLowerCase());
				comparestring = regcardarray[i];
			}
			if(pattern.test(comparestring.toLowerCase()))
			{
				var splits = regcardarray[i].split("^");
				//options[options.length] = new Option(splits[0], splits[1]);
				$('#scratchcardlist').append('<option value="'+splits[1]+'">'+splits[0]+'</option>');
				addedcount++;
				if(addedcount == 10) {
				    //break;
				}    
			}
		}
	}
}

function reg_cardsearch(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 38)
		reg_scrollcard('up');
	else if(KeyID == 40)
		reg_scrollcard('down');
	else
	{
		var form = $('#registrationform');
		var input = $('#searchscratchnumber').val();
		reg_selectacard(input);
	}
}

//changing attr to prop
function reg_scrollcard(type)
{
	var selectbox = $('#scratchcardlist');
	var totalcard = $("#scratchcardlist option").length;
	var selectedcard = $("select#scratchcardlist").prop('selectedIndex');
	if(type == 'up' && selectedcard != 0)
		$("select#scratchcardlist").prop('selectedIndex', selectedcard - 1);
	else if(type == 'down' && selectedcard != totalcard)
		$("select#scratchcardlist").prop('selectedIndex', selectedcard + 1);
	reg_selectcardfromlist();
}

function scratchdetailstoform(cardid)
{
	if(cardid != '')
	{
		//enableattachcard();
		var passdata = "switchtype=scratchdetailstoform&cardid=" + encodeURIComponent(cardid) + "&dummy=" + Math.floor(Math.random()*100032680100); 
		ajaxcall1 = createajax();
		$('#scratchcradloading').html(getprocessingimage());
		var queryString = "../ajax/dealerpinallot.php";
		ajaxcall1 = $.ajax(
			{
				type: "POST",url: queryString, data: passdata, cache: false,dataType: "json",
				success: function(ajaxresponse,status)
				{	
					$('#scratchcradloading').html('');
					$('#detailsonscratch').show();
					if(ajaxresponse == 'Thinking to redirect')
					{
						window.location = "../logout.php";
						return false;
					}
					else
					{
						var response = ajaxresponse;
						$('#cardnumberdisplay').html(response['cardid']);
						$('#scratchnodisplay').html(response['scratchnumber']);
						$('#purchasetypedisplay').html(response['purchasetype']);
						$('#usagetypedisplay').html(response['usagetype']);
						$('#attachedtodisplay').html(response['attachedto']);
						$('#productdisplay').html(response['productname'] + ' [' + response['productcode'] + ']');
						$('#registeredtodisplay').html(response['registeredto']);
						$('#attachdatedisplay').html(response['attcheddate']);
						$('#cardstatusdisplay').html(response['cardstatus']);
						$('#cardattacheddate').html(response['cuscardattacheddate']);
						$('#remarks').html(response['cuscardremarks']);
						
						$('#subdealer').html(response['subdealer']);
						$('#subdealername').html(response['subdealername']);
						
						if (response['usagetype']== 'singleuser') 
						{
							var usagetype= 'Single User';
						}
						else if (response['usagetype']== 'multiuser')
						{
							var usagetype= 'Multi User';
						}
						else if (response['usagetype']== 'additionallicense')
						{
							var usagetype= 'Additional License';
						}
						
						//$('#description').html(response['productname'] + ' [' + response['productcode'] + ']');
						document.getElementById('description').value = "1$"+response['productname']+"$"+response['purchasetype']+"$"+usagetype+"$"+response['scratchnumber']+"$"+response['cardid']+"$0";
					}

				}, 
				error: function(a,b)
				{
					$("#scratchcradloading").html(scripterror());
				}
			});	
	}
	else
	{
		var error = $('#form-error');
		error.html(errormessage("Sorry!! No PIN No. Available")); return false;
	}
}
//Function to enable the Attach card button--------------------------------------------------------------------------------
function enableattachcard()
{
    var lastslno = $('#lastslno').val();
    if(lastslno != '') {
    	$('#attachcard').prop('disabled',false);
    	$('#attachcard').prop('className','swiftchoicebuttonbig');
    }
}
//Function to Enable the Attach card button--------------------------------------------------------------------------------
function disableattachcard()
{
	$('#attachcard').prop('disabled',true);
	$('#attachcard').prop('className','swiftchoicebuttondisabledbig');
}


function gettotalcustomercount()
{
	var form = $('#customerselectionprocess');
	var passData = "switchtype=getcustomercount&dummy=" + Math.floor(Math.random()*10054300000);
	queryString = "../ajax/dealerselect.php";
	ajaxcall2356 = $.ajax(
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
				var response = ajaxresponse;
				if(response == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				$('#totalcount').html(response['count']);
				refreshcustomerarray(response['count']);
			}
		}, 
		error: function(a,b)
		{
			$("#customerselectionprocess").html(scripterror());
		}
	});	
}

function selectedusagetype() {
    
    selectedproductdealer();
    /*
    var productid = $("#selectedproduct").find("option:selected").val();
    var usageid = $("#usagetype").find("option:selected").val();
    var purchasetype = $("#purchasetype").find("option:selected").val();

    var Values = new Array();
    Values.push(usageid);
    Values.push(productid);
    Values.push(purchasetype);
    var strlength = (Values).length;
    var e = jQuery.Event("keyup");
    $('.fs-search input').val(Values).trigger(e);
    $('.fs-options').find('.selected').trigger('click');
    $('.fs-label').html('');
    $('.fs-label').html('Select some cards');
    $('#detailsonscratch').hide();*/
}

function selectedpurchasetype() {
    
    selectedproductdealer();
    /*var productid = $("#selectedproduct").find("option:selected").val();
    var purchasetype = $("#purchasetype").find("option:selected").val();
    var usageid = $("#usagetype").find("option:selected").val();
    
      var Values = new Array();
    Values.push(usageid);
    Values.push(productid);
    Values.push(purchasetype);
    var strlength = Values.length;
    var e = jQuery.Event("keyup");
    $('.fs-search input').val(Values).trigger(e);
    $('.fs-options').find('.selected').trigger('click');
    $('.fs-label').html('');
    $('.fs-label').html('Select some cards');
    $('#detailsonscratch').hide();*/
}
    

$("div.fs-search input").bind('paste', function() {

});	

function selectedproductdealer() {
    
    var productid = $("#selectedproduct").find("option:selected").val();
    
    var purchasetype = $("#purchasetype").find("option:selected").val();
    var usagetype = $("#usagetype").find("option:selected").val();
    
    var e = jQuery.Event("keyup");
    $('.fs-search input').val(productid).trigger(e);
    $('.fs-options').find('.selected').trigger('click');
    
    $('.fs-label').html('');
    $('.fs-label').html('Select some cards');
    $('#detailsonscratch').hide();
    
/*  
    var strlength = productid.length;
    var e = jQuery.Event("keyup");
    $('.fs-search input').val(productid).trigger(e);
    $('.fs-options').find('.selected').trigger('click');
    $('.fs-label').html('');
    $('.fs-label').html('Select some cards');
    $('#detailsonscratch').hide();
*/    
/*    console.log('purchasetype : ' + purchasetype);
    console.log('usagetype : ' + usagetype);*/

	//if(usagetype != '') { usagetype += 'U'; }
		
	if(purchasetype == 'N') { purchasetype = 'New'; }
	if(purchasetype == 'U') { purchasetype = 'Updation'; }    
    
    //added on 19th July 2018
    $('div.fs-option').hide();
    $('div.fs-option:contains(| '+productid+')').show();
    
    var countpurchasetype = 1;
    var countusagetype = 1;
    
    if(purchasetype != '') {
        
        $("div.fs-option:visible").each(function() {
            
/*            console.log(countpurchasetype);
            countpurchasetype++;*/
            
            if($(this).is(':contains(| '+purchasetype+')')) {
                $(this).show();
            }
            else {
                $(this).hide();
            }
            //$('div.fs-option').hide();
            //$('div.fs-option:contains('+purchasetype+')').show();
        });
    }
    if(usagetype != '') {
        
        $("div.fs-option:visible").each(function() {
            
            if($(this).is(':contains(| '+usagetype+'U)')) {
                $(this).show();
            }
            else {
                $(this).hide();
            }   
        
            
/*          console.log(countusagetype);
            countusagetype++;*/
            
            /*$('div.fs-option:visible').hide();
            $('div.fs-option:visible:contains('+usagetype+')').show();*/
        });
    }
    
    //$('.fs-options').find('.fs-option').removeClass('selected');
    
/*  if(strlength == 3)
    {
        var passdata = "switchtype=filterdealerpins&productid="+encodeURIComponent(productid)+"&dummy="+Math.floor(Math.random()*100032680100); 
	    queryString = "../ajax/dealerpinallot.php";
        $.ajax
        ({
    		type: "POST",url: queryString, data: passdata, cache: false,dataType: "json",
    		success: function(ajaxresponse,status)
    		{	
    			if(ajaxresponse == 'Thinking to redirect')
    			{
    				window.location = "../logout.php";
    				return false;
    			}
    			else
    			{
    				var response = ajaxresponse;
    				//console.log(response);
    				$('#scratchcardlist').empty();
    				$('.fs-options').remove();
    				
    				$.each(response, function (index, value) {
                        var value = value;                        
                        var splits = value.split("^");
                        var purchasetype = splits[2];
                        var typeofpurchase = purchasetype.substring(0,1);
                        typeofpurchase = typeofpurchase.toUpperCase();
                        var cardvalue = splits[0]+' ('+typeofpurchase+')';
                		console.log('cardvalue : '+cardvalue);
                		$('#scratchcardlist').append('<option value="'+splits[1]+'">'+cardvalue+'</option>');
    				});
    				fillmanual();
    				
    				//debugger;
    				
                    var selectbox = $('#scratchcardlist');
                    var numberofcards = response.length;
                    var actuallimit = 500;
                    var limitlist = (numberofcards > actuallimit)?actuallimit:numberofcards;
                    
                    //$('option', selectbox).remove();
                    //var options = selectbox.attr('options');
                    
                    //$('#scratchcardlist').find('option').remove();
                    $('#scratchcardlist').empty();
                    	
                    for( var i=0; i<limitlist; i++)
                    {
                    	var splits = response[i].split("^");
                    	//options[options.length] = new Option(splits[0], splits[1]);
                    	//$('#scratchcardlist').append('<option value="'+splits[1]+'">'+splits[0]+'</option>');
                    }
    				//getregcardlist();
    			}
    		}, 
    		error: function(a,b)
    		{
    			$("#scratchcradloading").html(scripterror());
    		}
	    });	
    }
    else {
        	var cardcount = $('#totalcountofcard').text();
			refreshcusattachcardarray(cardcount);
			$('#searchscratchnumber').val('');
    }*/
    //alert(strlength);
}