
function typechanger( type, value, e )
{
	var item = jQuery(e);
	jQuery('div[id*=wrap-select-' + type + '][data-groupid=' + item.attr( 'data-groupid' ) + ']').each( function() {
		
	    alert( jQuery(this).attr( 'id' ) );
	});
	
}