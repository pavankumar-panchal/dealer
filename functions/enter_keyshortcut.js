$(document).ready(function(){
$('input.type_enter, select.type_enter, textarea.type_enter, button.type_enter,checkbox.type_enter').live('keypress', function(e) {
if (e.which == 13 && e.shiftKey) 
{
	var focusable = $('input.type_enter,select.type_enter,textarea.type_enter,button.type_enter').filter(':visible');
	if (focusable.eq(focusable.index(this)).is('.reverser_class')) 
	{
		return false;
	}
	focusable.eq(focusable.index(this)-1).focus();
	focusable.eq(focusable.index(this)).removeClass("css_enter1"); 
	focusable.eq(focusable.index(this)).removeClass("checkbox_enter1");
	if(focusable.eq(focusable.index(this)-1).get(0).type == 'text' || focusable.eq(focusable.index(this)-1).get(0).type == 'textarea' || focusable.eq(focusable.index(this)-1).get(0).type == 'select-one')
	{
		focusable.eq(focusable.index(this)-1).addClass("css_enter1");
	}
	if(focusable.eq(focusable.index(this)-1).get(0).type == 'checkbox')
	{
		focusable.eq(focusable.index(this)-1).addClass("checkbox_enter1");
	}
	return false;	
}
																															   
else																															   
if (e.keyCode==13) 
{
	var focusable = $('input.type_enter,select.type_enter,textarea.type_enter,button.type_enter').filter(':visible');
	focusable.eq(focusable.index(this)+1).focus();
	focusable.eq(focusable.index(this)).removeClass("css_enter1"); 
	focusable.eq(focusable.index(this)).removeClass("checkbox_enter1");
	if (focusable.eq(focusable.index(this)).is('.default')) 
	{
		$('#save').focus();
		$('#save').addClass("button_enter1");
		return false;
	}
	if (focusable.eq(focusable.index(this)).is('.saveclass')) 
	{
		formsubmit('save');
		return false;
	}
	if (focusable.eq(focusable.index(this)).is('.generallastfield')) 
	{
		tabopen5('2','tabg1');
		var focusable = $('input.type_enter,select.type_enter,textarea.type_enter,button.type_enter').filter(':visible');
		focusable.eq(focusable.index(this)+1).focus();
	} 
	if(focusable.eq(focusable.index(this)+1).get(0).type == 'text' || focusable.eq(focusable.index(this)+1).get(0).type == 'textarea' || focusable.eq(focusable.index(this)+1).get(0).type == 'select-one')
	{
		focusable.eq(focusable.index(this)+1).addClass("css_enter1");
	}
	if(focusable.eq(focusable.index(this)+1).get(0).type == 'checkbox')
	{
		focusable.eq(focusable.index(this)+1).addClass("checkbox_enter1");
	}
	return false;	
}
 
});

  
$('input[type=text].focus_redclass, select.focus_redclass, textarea.focus_redclass, input[type=checkbox].focus_redclass, input[type=button].focus_redclass').focus(function() {
if($(this).get(0).type == 'checkbox')
	$(this).addClass("checkbox_enter1"); 
else if($(this).get(0).type == 'text' || $(this).get(0).type == 'textarea' || $(this).get(0).type == 'select-one')
	$(this).addClass("css_enter1");  
else if($(this).get(0).type == 'button')
	$(this).addClass("button_enter1"); 
});

$('input[type=text].focus_redclass, select.focus_redclass, textarea.focus_redclass, input[type=checkbox].focus_redclass, input[type=button].focus_redclass').blur(function() {
if($(this).get(0).type == 'checkbox')
	$(this).removeClass("checkbox_enter1"); 
else if($(this).get(0).type == 'text' || $(this).get(0).type == 'textarea' || $(this).get(0).type == 'select-one')
	$(this).removeClass("css_enter1");  
else if($(this).get(0).type == 'button')
	$(this).removeClass("button_enter1"); 
});

});
