

function webfontupdate( frame, src, value )
{
	jQuery( "iframe[id=" + frame + "]" ).attr( 'src', src + value );
}