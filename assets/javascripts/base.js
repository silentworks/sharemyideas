/**
 * @author asmith, jhunter
 */
$(document).ready(function() {
	$('#idea-form p textarea#idea').bind('keydown keyup focus', {maxlimit: 320}, tooManyChar);
	$('#idea-form p input#title').bind('keydown keyup focus', {maxlimit: 120}, tooManyChar);
});

function tooManyChar(e) {
	var element = $(this);
	maxlimit = e.data.maxlimit || 320;
	var elem = element.val();
	
	if(elem.length > maxlimit)
	{
		var text = " characters over";
		element.css('background-color', '#fdd');	
	}
	else
	{
		var text = " characters left";
		element.css('background-color', '#fff');
	}
	
	element.prev().find('em').text((maxlimit - elem.length) + text);
}