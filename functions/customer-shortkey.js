// JavaScript Document
function customer_short_keys()
{
	shortcut.add("Alt+Shift+N",function() {
	newentry(); 
	$('#form-error').html('');
	cleargrid();rowwdelete()
});
		shortcut.add("Alt+Shift+S",function() {
	formsubmit('save');
});
		
	
	shortcut.add("Alt+Shift+A",function() {
	displayDiv('1','filterdiv')
});
	shortcut.add("Alt+Shift+C",function() {
	$('#detailsearchtext').val('');
	$('#detailsearchtext').focus();
	$('input.focus_redclass,select.focus_redclass,textarea.focus_redclass').removeClass("css_enter1"); 
	$('input.focus_redclass,select.focus_redclass,textarea.focus_redclass').removeClass("checkbox_enter1");
	$('#form-error').html('');

});
		shortcut.add("Alt+Shift+G",function() {
	window.scrollTo(600,600);
	gridtabcus4('1','tabgroupgrid','&nbsp; &nbsp;Current Registrations');
	
});
		
		shortcut.add("Alt+Shift+R",function() {
window.scrollTo(600,600);
gridtabcus4('2','tabgroupgrid','&nbsp; &nbsp;Generate New Registration');
 
});
		
		shortcut.add("Alt+Shift+P",function() {
window.scrollTo(600,600);
gridtabcus4('3','tabgroupgrid','&nbsp; &nbsp;PIN Number Details');
});
	
		shortcut.add("Alt+Shift+I",function() {
	window.scrollTo(600,600);
gridtabcus4('4','tabgroupgrid','&nbsp; &nbsp;PIN Number Details');
});	
		
	/*shortcut.add("Shift+R",function() {
	displayrcidata()
});	
	shortcut.add("Shift+I",function() {
	displayinvoicedetails()
});	*/
	
$(document).ready(function() {
  var  isShift = false;var  isAlt = false;
    $(document).keydown(function(e) {
        if(e.which == 16) 
		{
           isShift = true;
		}
		if(e.which == 18)
		{
            isAlt = true;
		}
        if(e.which == 191 && isShift && isAlt) {
            $("").colorbox({ inline:true, href:"#shortcut-grid"});
			isShift = false;
			isAlt = false;
			return false;
        }
    });
     
});

}