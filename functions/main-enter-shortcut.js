$(document).ready(function(){
  $('input.type_enter').live('keypress', function(e) {
 if (e.keyCode==13) 
 {
	var focusable = $('input.type_enter').filter(':visible');
	
	focusable.eq(focusable.index(this)+1).focus();
	focusable.eq(focusable.index(this)).removeClass("css_enter1"); 
	focusable.eq(focusable.index(this)).removeClass("button_enter1"); 
	if (focusable.eq(focusable.index(this)+1).is('.default')) 
	{
		$('#login').focus();
		return true;
		form.submit();
	}
	if(focusable.eq(focusable.index(this)+1).get(0).type == 'password' || focusable.eq(focusable.index(this)+1).get(0).type == 'text')
	{
		focusable.eq(focusable.index(this)+1).addClass("css_enter1");
	}
	else
	{
		focusable.eq(focusable.index(this)+1).addClass("button_enter1");
	}
	
	return false;
 }
});

});