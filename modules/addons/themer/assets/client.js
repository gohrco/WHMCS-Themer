

function updateCss( field, value )
{
	// Stores the full width value (1|0)
	var fullwidth = jQuery( '#fullwidth' ).val();
	
	// Figure out what to do
	switch( field ) {
	
	// Content background
	case 'contentbg' :
		if ( fullwidth == '1' ) {
			jQuery('#whmcsheader').css( 'background', value );
		}
		else {
			jQuery('#whmcsheader,.whmcscontainer,.whmcscontainer .footer').css( 'background', value );
		}
		break;
	
	// Big changer for body background due to options :(
	case 'bodytype' :
		
		value = jQuery( '#bodytype' ).val();
		
		switch ( value ) {
		
		// Solid background
		case '1' :
			
			var from = jQuery( 'input[name="bodyoptnsolid"]' ).val();
			
			jQuery( 'body' ).css( 'background', '' );
			jQuery( 'body' ).css( 'filter', '' );
			
			updateBody( '1', from, null, null );
			
			break;
			
		// Gradient background (yikes)
		case '2' :
			
			var from = jQuery( 'input[name="bodyoptnfrom"]' ).val();
			var to = jQuery( 'input[name="bodyoptnto"]' ).val();
			var dir = jQuery( 'select[name="bodyoptndir"]' ).val();
			
			jQuery( 'body' ).css( 'background', '' );
			jQuery( 'body' ).css( 'filter', '' );
			
			updateBody( '2', from, to, dir );
			
			break;
			
		// Pattern background
		case '3' :
			
			var from = jQuery( 'select[name="bodyoptnpattern"]' ).val();
			
			jQuery( 'body' ).css( 'background', '' );
			jQuery( 'body' ).css( 'filter', '' );
			
			updateBody( '3', from, null, null );
			
			break;
		
		// URL Background
		case '4' :
			
			var from = jQuery( 'input[name="bodyoptnimage"]' ).val();
			
			jQuery( 'body' ).css( 'background', '' );
			jQuery( 'body' ).css( 'filter', '' );
			
			updateBody( '4', from, null, null );
			
			break;
		}
		
		break;
	// End Body Type
	
	case 'bodyoptnsolid' :
		
		updateBody( '1', value, null, null );
		break;
		
	case 'bodyoptnfrom' :
		var to = jQuery( 'input[name="bodyoptnto"]' ).val();
		var dir = jQuery( 'select[name="bodyoptndir"]' ).val();
		updateBody( '2', value, to, dir );
		break;
	
	case 'bodyoptnto' :
		var from = jQuery( 'input[name="bodyoptnfrom"]' ).val();
		var dir = jQuery( 'select[name="bodyoptndir"]' ).val();
		updateBody( '2', from, value, dir );
		break;
		
	case 'bodyoptndir' :
		var from = jQuery( 'input[name="bodyoptnfrom"]' ).val();
		var to = jQuery( 'input[name="bodyoptnto"]' ).val();
		updateBody( '2', from, to, value );
		break;
		
	case 'bodyoptnpattern' :
		updateBody( '3', value, null, null );
		break;
		
	case 'bodyoptnimage' :
		updateBody( '4', value, null, null );
		break;
	// End body selections
		
	// Hyperlinks
	case 'alinksstd' :
		jQuery( 'a' ).css( 'color', value );
		break;
	case 'alinksvis' :
		jQuery( 'a' ).css( 'color', value );
		break;
	case 'alinkshov' :
		jQuery( 'a' ).css( 'color', value );
		break;
	// End Hyperlinks
		
	// Navbar
	case 'navbarfrom' :
		var to = jQuery( 'input[name="navbarto"]' ).val();
		updateNavbar( value, to );
		break;
	case 'navbarto' :
		var from = jQuery( 'input[name="navbarfrom"]' ).val();
		updateNavbar( from, value );
		break;
	case 'navbartxt' :
		jQuery( '.navbar .nav > li > a, .navbar' ).css( 'color', value );
		break;
	case 'navbarhov' :
		jQuery( '.navbar .nav > li > a' ).css( 'color', value );
		break;
	case 'navbardropbg' :
		jQuery( '.dropdown-menu,.dropdown-menu .divider' ).css( 'background-color', value );
		jQuery( '.dropdown-menu li > a,.dropdown-menu .active a' ).css( 'color', value );
		break;
	case 'navbardroptxt' :
		jQuery( '.dropdown-menu a' ).css('color', value );
		break;
	case 'navbardrophl' :
		jQuery( '.dropdown-menu li > a,.dropdown-menu .active a' ).css( 'background-color', value );
		break;
	// End Navbar
		
	// Text Elements
	case 'txtelemgfcolor' :
		jQuery('.whmcscontainer,p' ).css( 'color', value );
		break;
	case 'txtelemh1color' :
		jQuery( '.whmcscontainer h1,h1' ).css( 'color', value );
		break;
	case 'txtelemh2color' :
		jQuery( '.whmcscontainer h2,h2' ).css( 'color', value );
		break;
	case 'txtelemh3color' :
		jQuery( '.whmcscontainer h3,h3' ).css( 'color', value );
		break;
	case 'txtelemh4color' :
		jQuery( '.whmcscontainer h4,h4' ).css( 'color', value );
		break;
	case 'txtelemh5color' :
		jQuery( '.whmcscontainer h5,h5' ).css( 'color', value );
		break;
	case 'txtelemh6color' :
		jQuery( '.whmcscontainer h6,h6' ).css( 'color', value );
		break;
	// End Text Elements
	}
	
}


function updateBody( type, from, to, dir )
{
	switch ( type ) {
	case '1' :
		
		jQuery( 'body' ).css( 'background', 'none repeat scroll 0 0 ' + from );
		
		break;
	case '2' :
		
		jQuery( 'body' ).css( 'background', from );
		
		switch ( dir ) {
		case '1' :
			jQuery( 'body' ).css( 'background', '-moz-linear-gradient(top,  ' + from + ' 0%, ' + to + ' 100%)' );
			jQuery( 'body' ).css( 'background', '-webkit-gradient(linear, left top, left bottom, color-stop(0%,'+from+'), color-stop(100%,'+to+')' );
			jQuery( 'body' ).css( 'background', '-webkit-linear-gradient(top,  '+from+' 0%,'+to+' 100%)' );
			jQuery( 'body' ).css( 'background', '-o-linear-gradient(top,  '+from+' 0%,'+to+' 100%)' );
			jQuery( 'body' ).css( 'background', '-ms-linear-gradient(top,  '+from+' 0%,'+to+' 100%)' );
			jQuery( 'body' ).css( 'background', 'linear-gradient(to bottom,  '+from+' 0%,'+to+' 100%)' );
			jQuery( 'body' ).css( 'filter', "progid:DXImageTransform.Microsoft.gradient( startColorstr='"+from+"', endColorstr='"+to+"',GradientType=0 )" );
			jQuery( 'body' ).css( 'background-color', to );
			jQuery( 'body' ).css( 'background-repeat', 'repeat-x' );
			break;
		case '2' :
			jQuery( 'body' ).css( 'background', '-moz-linear-gradient(left,  ' + from + ' 0%, ' + to + ' 100%)' );
			jQuery( 'body' ).css( 'background', '-webkit-gradient(linear, left top, right top, color-stop(0%,'+from+'), color-stop(100%,'+to+')' );
			jQuery( 'body' ).css( 'background', '-webkit-linear-gradient(left,  '+from+' 0%,'+to+' 100%)' );
			jQuery( 'body' ).css( 'background', '-o-linear-gradient(left,  '+from+' 0%,'+to+' 100%)' );
			jQuery( 'body' ).css( 'background', '-ms-linear-gradient(left,  '+from+' 0%,'+to+' 100%)' );
			jQuery( 'body' ).css( 'background', 'linear-gradient(to right,  '+from+' 0%,'+to+' 100%)' );
			jQuery( 'body' ).css( 'filter', "progid:DXImageTransform.Microsoft.gradient( startColorstr='"+from+"', endColorstr='"+to+"',GradientType=1 )" );
			break;
		case '3' :
			jQuery( 'body' ).css( 'background', '-moz-linear-gradient(-45deg,  ' + from + ' 0%, ' + to + ' 100%)' );
			jQuery( 'body' ).css( 'background', '-webkit-gradient(linear, left top, right bottom, color-stop(0%,'+from+'), color-stop(100%,'+to+')' );
			jQuery( 'body' ).css( 'background', '-webkit-linear-gradient(-45deg,  '+from+' 0%,'+to+' 100%)' );
			jQuery( 'body' ).css( 'background', '-o-linear-gradient(-45deg,  '+from+' 0%,'+to+' 100%)' );
			jQuery( 'body' ).css( 'background', '-ms-linear-gradient(-45deg,  '+from+' 0%,'+to+' 100%)' );
			jQuery( 'body' ).css( 'background', 'linear-gradient(135deg,  '+from+' 0%,'+to+' 100%)' );
			jQuery( 'body' ).css( 'filter', "progid:DXImageTransform.Microsoft.gradient( startColorstr='"+from+"', endColorstr='"+to+"',GradientType=1 )" );
			break;
		case '4' :
			jQuery( 'body' ).css( 'background', '-moz-linear-gradient(45deg,  ' + from + ' 0%, ' + to + ' 100%)' );
			jQuery( 'body' ).css( 'background', '-webkit-gradient(linear, left bottom, right top, color-stop(0%,'+from+'), color-stop(100%,'+to+')' );
			jQuery( 'body' ).css( 'background', '-webkit-linear-gradient(45deg,  '+from+' 0%,'+to+' 100%)' );
			jQuery( 'body' ).css( 'background', '-o-linear-gradient(45deg,  '+from+' 0%,'+to+' 100%)' );
			jQuery( 'body' ).css( 'background', '-ms-linear-gradient(45deg,  '+from+' 0%,'+to+' 100%)' );
			jQuery( 'body' ).css( 'background', 'linear-gradient(45deg,  '+from+' 0%,'+to+' 100%)' );
			jQuery( 'body' ).css( 'filter', "progid:DXImageTransform.Microsoft.gradient( startColorstr='"+from+"', endColorstr='"+to+"',GradientType=1 )" );
			break;
		case '5' :
			jQuery( 'body' ).css( 'background', '-moz-linear-gradient(center, ellipse cover, ' + from + ' 0%, ' + to + ' 100%)' );
			jQuery( 'body' ).css( 'background', '-webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%,'+from+'), color-stop(100%,'+to+')' );
			jQuery( 'body' ).css( 'background', '-webkit-linear-gradient(center, ellipse cover,  '+from+' 0%,'+to+' 100%)' );
			jQuery( 'body' ).css( 'background', '-o-linear-gradient(center, ellipse cover,  '+from+' 0%,'+to+' 100%)' );
			jQuery( 'body' ).css( 'background', '-ms-linear-gradient(center, ellipse cover,  '+from+' 0%,'+to+' 100%)' );
			jQuery( 'body' ).css( 'background', 'linear-gradient(ellipse at center,  '+from+' 0%,'+to+' 100%)' );
			jQuery( 'body' ).css( 'filter', "progid:DXImageTransform.Microsoft.gradient( startColorstr='"+from+"', endColorstr='"+to+"',GradientType=1 )" );
			break;
		}
		
		
		
		
		break;
	case '3' :
		
		var path = jQuery( '#assets' ).val();
		jQuery( 'body' ).css( 'background', 'url("' + path + 'assets/patterns/' + from + '") repeat scroll 0 0 #ffffff ');
		
		break;
	case '4' :
		
		jQuery( 'body' ).css( 'background', 'url("' + from + '") repeat scroll 0 0 #ffffff ');
		
		break;
	}
}


function updateNavbar( from, to )
{
	jQuery( '.navbar-inner,.footerdivider' )
		.css( 'background', from )
		.css( 'background', '-moz-linear-gradient(top,  '+from+' 0%, '+to+' 100%)' )
		.css( 'background', '-webkit-gradient(linear, left top, left bottom, color-stop(0%,'+from+'), color-stop(100%,'+to+'))' )
		.css( 'background', '-webkit-linear-gradient(top,  '+from+' 0%,'+to+' 100%)' )
		.css( 'background', '-o-linear-gradient(top,  '+from+' 0%,'+to+' 100%)' )
		.css( 'background', '-ms-linear-gradient(top,  '+from+' 0%,'+to+' 100%)' )
		.css( 'background', 'linear-gradient(to bottom,  '+from+' 0%,'+to+' 100%)' )
		.css( 'filter', "progid:DXImageTransform.Microsoft.gradient( startColorstr='"+from+"', endColorstr='"+to+"',GradientType=0 )" );
}