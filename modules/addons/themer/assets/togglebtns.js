

function togglebtns( id, value ) {
	
	// Set this hidden ID value
	jQuery( "#" + id ).val(value);
	
	// Hide all the option boxes
	jQuery( "div[id*=" + id + "optn]" ).each( function() {
		jQuery( this ).hide();
	});
	
	// Show the selected option
	jQuery( "div[id=" + id + "optn" + value + "]").show();
}