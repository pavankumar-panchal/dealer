$(document).ready(function () {

jQuery(function(){
	jQuery('ul.sf-menu').superfish();

});

$("#rightnav ul li.main").hover(function(){
  $(this).addClass('hover');
 },
 function() {
  $(this).removeClass('hover');
 }
);


$(document).pngFix(); 

/*jQuery(function($) {
    $("img[@src$=png], #image-one, #image-two").pngfix();
});*/
// Preload all rollovers
$(".live-chat img").each(function() {
	// Set the original src
	rollsrc = $(this).attr("src");
	rollON = rollsrc.replace('OFF', 'ON');
	newImg = new Image(); // create new image obj
	$(newImg).attr("src", rollON); // set new obj's src
});


// Navigation rollovers
$(".live-chat a").mouseover(function(){
	imgsrc = $(this).children("img").attr("src");
	
	if (typeof(imgsrc) != 'undefined') {
	imgsrcON = imgsrc.replace('OFF', 'ON');
	$(this).children("img").attr("src", imgsrcON);
	}
	
});

// Handle mouseout
$(".live-chat a").mouseout(function(){
	if (typeof(imgsrc) != 'undefined') {
	$(this).children("img").attr("src", imgsrc);
	}
});		

		
		
// Preload all rollovers
$("#mastheadlinks img").each(function() {
	// Set the original src
	rollsrc = $(this).attr("src");
	rollON = rollsrc.replace('OFF', 'ON');
	newImg = new Image(); // create new image obj
	$(newImg).attr("src", rollON); // set new obj's src
});


// Navigation rollovers
$("#mastheadlinks a").mouseover(function(){
	imgsrc = $(this).children("img").attr("src");
	
	if (typeof(imgsrc) != 'undefined') {
	imgsrcON = imgsrc.replace('OFF', 'ON');
	$(this).children("img").attr("src", imgsrcON);
	}
	
});

// Handle mouseout
$("#mastheadlinks a").mouseout(function(){
	if (typeof(imgsrc) != 'undefined') {
	$(this).children("img").attr("src", imgsrc);
	}
});		



});