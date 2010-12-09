/**
* activate tabs for textareas
*/
jQuery(document).ready(function () {
	// activate tabs for textarea
	$("textarea#code").tabby();

	// bind confirmation message on delete link
	if($(".deletelink").length) {
		$(".deletelink").click( function() { return confirm('Are you sure? This can not be undone!!'); } );
	}

	// bind confirmation message on twitter link
	if($(".twitterlink").length) {
		$(".twitterlink").click( function() { return confirm('Are you sure you want to twitter your snippet again??'); } );
	}
	
});

function showPopup() {
	if($('#wrapper-codetocopy').length > 0) {
		$('#wrapper-codetocopy').show();
	}
}

function closePopup() {
	$('#wrapper-codetocopy').hide();
}