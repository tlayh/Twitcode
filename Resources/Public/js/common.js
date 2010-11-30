/**
* activate tabs for textareas
*/
jQuery(document).ready(function () {
	$("textarea#code").tabby();
});

function showPopup() {
	if($('#wrapper-codetocopy').length > 0) {
		$('#wrapper-codetocopy').show();
	}
}

function closePopup() {
	$('#wrapper-codetocopy').hide();
}