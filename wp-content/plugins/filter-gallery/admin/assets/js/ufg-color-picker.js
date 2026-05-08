jQuery(document).ready(function(jQuery){
	jQuery('.ufg-color-picker').wpColorPicker();
});
var UFGCpickerOptions = {
	// you can declare a default color here,
	defaultColor: false,
	// a callback to fire whenever the color changes to a valid color
	change: function(event, ui){},
	// a callback to fire when the input is emptied or an invalid color
	clear: function() {},
	// hide the color picker controls on load
	hide: true,
	// show a group of common colors beneath the square
	palettes: true
};
jQuery('.ufg-color-picker').wpColorPicker(UFGCpickerOptions);