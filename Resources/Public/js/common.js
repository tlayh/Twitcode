/**
* activate tabs for textareas
*/
jQuery(document).ready(function () {
	// activate tabs for textarea
	$("textarea#code").tabby();

	// bind confirmation message on delete link
	if($("#deletelink").length) {
		$("#deletelink").click( function() { return confirm('Are you sure? This can not be undone!!'); } );
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