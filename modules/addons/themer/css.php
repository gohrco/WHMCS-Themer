<?php
ob_start("ob_gzhandler");
header("Content-type: text/css; charset: UTF-8");

if (! defined( 'WHMCS' ) ) define( 'WHMCS', true );

/*-- Dunamis Inclusion --*/
$path	= dirname( dirname( dirname( dirname(__FILE__) ) ) ) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'dunamis.php';
if ( file_exists( $path ) ) include_once( $path );
/*-- Dunamis Inclusion --*/

$dun		=	get_dunamis( 'themer' );
$db			=	dunloader( 'database', true );
$config 	=	dunloader( 'config', true );
$valid		=	dunloader( 'license', 'themer' )->isValid();

if (! $valid ) exit();

// Lets start by cleaning up our system URI
$oururi	= DunUri :: getInstance();
$oururi->delVars();
$sysuri	= DunUri :: getInstance( $config->get( 'SystemURL' ) );
$sysuri->setScheme( $oururi->getScheme() );

$get = $GLOBALS['_GET'];

if ( array_key_exists( 'tid', $get ) ) {
	$tid = mysql_real_escape_string( $get['tid'] );
}
else {
	// Lets load the default theme we want to use
	$db->setQuery( "SELECT `value` FROM `mod_themer_settings` WHERE `key` = 'usetheme'" );
	$tid	= $db->loadResult();
}

$m = null;
if ( array_key_exists( 'm', $get ) ) {
	$m = '#' . $get['m'] . ' ';
}

// Now lets load the parameters
$db->setQuery( "SELECT `params` FROM `mod_themer_themes` WHERE `id` = '" . $tid . "'" );
$params	= $db->loadResult();

$css		= json_decode( $params, false );
$baseurl	= str_replace( 'css.php', '', $oururi->toString() );

/** =======================================================================================================
 * -----------------------------------------
 * Check for use of Full Width Setting
 * -----------------------------------------
 */
if ( $css->fullwidth ) : ?>

<?php echo $m; ?>#whmcsheader {
	background: <?php echo $css->contentbg; ?>;
	max-width: inherit;
}

<?php echo $m; ?>.topbar .fill,
<?php echo $m; ?>.navbar .navbar-inner {
	width: inherit; -moz-border-radius: 0px;
	-webkit-border-radius: 0px;
	-o-border-radius: 0px;
	border-radius: 0;
}

<?php echo $m; ?>.whmcscontainer,
<?php echo $m; ?>.whmcscontainer .footer {
	background: transparent;
}

<?php echo $m; ?>.footerdivider {
	display: none;
}

<?php
else :
?>

<?php echo $m; ?>#whmcsheader,
<?php echo $m; ?>.whmcscontainer,
<?php echo $m; ?>.whmcscontainer .footer {
	background: <?php echo $css->contentbg; ?>;
}

<?php 
endif;	// End Full Width


/** =======================================================================================================
 * -----------------------------------------
 * Text Elements
 * -----------------------------------------
 */
$fonts = (object) array(
		'primary' => $css->font,
		'gen'	=> ( $css->txtelemgffont == '- use primary -' ? $css->font : $css->txtelemgffont ),
		'h1'	=> ( $css->txtelemh1font == '- use primary -' ? $css->font : $css->txtelemh1font ),
		'h2'	=> ( $css->txtelemh2font == '- use primary -' ? $css->font : $css->txtelemh2font ),
		'h3'	=> ( $css->txtelemh3font == '- use primary -' ? $css->font : $css->txtelemh3font ),
		'h4'	=> ( $css->txtelemh4font == '- use primary -' ? $css->font : $css->txtelemh4font ),
		'h5'	=> ( $css->txtelemh5font == '- use primary -' ? $css->font : $css->txtelemh5font ),
		'h6'	=> ( $css->txtelemh6font == '- use primary -' ? $css->font : $css->txtelemh6font ),
		);
?>

body <?php echo $m; ?>,
<?php echo $m; ?>.navbar-search .search-query,
<?php echo $m; ?>input,
<?php echo $m; ?>button,
<?php echo $m; ?>select,
<?php echo $m; ?>textarea {
	font-family: "<?php echo $fonts->primary; ?>",Helvetica,Arial,sans-serif;
}

<?php echo $m; ?>.whmcscontainer,
<?php echo $m; ?>p {
	font-family: "<?php echo $fonts->gen; ?>",Helvetica,Arial,sans-serif;
	font-size: <?php echo $css->txtelemgfsize; ?>px;
	line-height: <?php echo intval( ( 18 / 13 ) * $css->txtelemgfsize ); ?>px;
	color: <?php echo $css->txtelemgfcolor; ?>;
}

<?php echo $m; ?>.whmcscontainer h1,
<?php echo $m; ?>h1 {
	font-family: "<?php echo $fonts->h1; ?>",Helvetica,Arial,sans-serif;
	font-size: <?php echo $css->txtelemh1size; ?>px;
	line-height: <?php echo intval( ( 36 / 30 ) * $css->txtelemh1size ); ?>px;
	color: <?php echo $css->txtelemh1color; ?>;
}

<?php echo $m; ?>.whmcscontainer h2,
<?php echo $m; ?>h2 {
	font-family: "<?php echo $fonts->h2; ?>",Helvetica,Arial,sans-serif;
	font-size: <?php echo $css->txtelemh2size ?>px;
	line-height: <?php echo intval( ( 36 / 24 ) * $css->txtelemh2size ) ?>px;
	color: <?php echo $css->txtelemh2color; ?>;
}

<?php echo $m; ?>.whmcscontainer h3,
<?php echo $m; ?>h3 {
	font-family: "<?php echo $fonts->h3; ?>",Helvetica,Arial,sans-serif;
	font-size: <?php echo $css->txtelemh3size ?>px;
	line-height: <?php echo intval( ( 27 / 18 ) * $css->txtelemh3size ) ?>px;
	color: <?php echo $css->txtelemh3color; ?>;
}

<?php echo $m; ?>.whmcscontainer h4,
<?php echo $m; ?>h4 {
	font-family: "<?php echo $fonts->h4 ?>",Helvetica,Arial,sans-serif;
	font-size: <?php echo $css->txtelemh4size ?>px;
	line-height: <?php echo intval( ( 18 / 14 ) * $css->txtelemh4size ) ?>px;
	color: <?php echo $css->txtelemh4color; ?>;
}

<?php echo $m; ?>.whmcscontainer h5,
<?php echo $m; ?>h5 {
	font-family: "<?php echo $fonts->h5 ?>",Helvetica,Arial,sans-serif;
	font-size: <?php echo $css->txtelemh5size ?>px;
	line-height: <?php echo intval( ( 18 / 12 ) * $css->txtelemh5size ) ?>px;
	color: <?php echo $css->txtelemh5color; ?>;
}

<?php echo $m; ?>.whmcscontainer h6,
<?php echo $m; ?>h6 {
	font-family: "<?php echo $fonts->h6; ?>",Helvetica,Arial,sans-serif;
	font-size: <?php echo $css->txtelemh6size ?>px;
	line-height: <?php echo intval( ( 18 / 11 ) * $css->txtelemh6size ) ?>px;
	color: <?php echo $css->txtelemh6color; ?>;
}

<?php 
// End Text Elements


/** =======================================================================================================
 * -----------------------------------------
 * Logo Handling
 * -----------------------------------------
 */


if ( DunUri :: isInternal( $css->logo ) ) {
	$exists	=	file_exists( DUN_ENV_PATH . $css->logo );
}
else {
	$exists	=	( file_get_contents( $css->logo ) !== false ? true : false );
}

// Be sure the image exists
if ( $exists ) :
	// Check if we are using an internal reference
	if ( DunUri :: isInternal( $css->logo ) ) {
		$uri	= clone( $sysuri );
		$uri->setPath( rtrim( $uri->getPath(), '/' ) . '/' . trim( $css->logo, '/' ) );
	}
	else {
		$uri = DunUri :: getInstance( $css->logo );
		$uri->setScheme( $oururi->getScheme() );
	}
	
	$css->logo = $uri->toString();

	$imgdata = @getimagesize( $css->logo );
 
/**
 * Hide the image in the header
 * -------------------------------------------------
 */?>
<?php echo $m; ?>#whmcsimglogo > img,
<?php echo $m; ?>#whmcsimglogo > a > img { display: none; }


<?php 
/**
 * Place the image - but depends on version of WHMCS
 * -------------------------------------------------
 * 		WHMCS 5.1+
 */
if ( version_compare( DUN_ENV_VERSION, '5.1', 'ge' ) ) : ?>

<?php echo $m; ?>#whmcslogo { padding: 20px 0; }
<?php echo $m; ?>#whmcsimglogo > a {
	background: url( '<?php echo $css->logo; ?>' ) no-repeat scroll 0 50% transparent;
	display: block;
	height: <?php echo $imgdata[1] ?>px;
	width: <?php echo $imgdata[0] ?>px;
}

<?php
/**
 * 		WHMCS 5.0
 */
else : ?>
<?php echo $m; ?>div#whmcsimglogo {
	background: url( '<?php echo $css->logo; ?>' ) no-repeat scroll 0 50% transparent;
	display: block;
	height: <?php echo $imgdata[1] ?>px;
	width: <?php echo $imgdata[0] ?>px;
}

<?php
endif;

endif; // End $exists check
// End Logo Handling



/** =======================================================================================================
 * -----------------------------------------
 * Body Background
 * -----------------------------------------
 */

// Figure out the body background css
switch ( $css->bodytype ) :

// Solid Background Color
// ------------------------------------
case '1': ?>

body <?php echo $m; ?>{
	background-color: <?php echo $css->bodyoptnsolid ?>;
}
		
	<?php 
	break;

// Gradient Background Color
// ------------------------------------
case '2':
	
	switch ( $css->bodyoptndir ) :
	
	// Vertical Top to Bottom
	// ------------------------------------
	case '1' : ?>
				
body <?php echo $m; ?>{
	background: <?php echo $css->bodyoptnfrom ?>;
	background: -moz-linear-gradient(top,  <?php echo $css->bodyoptnfrom ?> 0%, <?php echo $css->bodyoptnto ?> 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,<?php echo $css->bodyoptnfrom ?>), color-stop(100%,<?php echo $css->bodyoptnto ?>));
	background: -webkit-linear-gradient(top,  <?php echo $css->bodyoptnfrom ?> 0%,<?php echo $css->bodyoptnto ?> 100%);
	background: -o-linear-gradient(top,  <?php echo $css->bodyoptnfrom ?> 0%,<?php echo $css->bodyoptnto ?> 100%);
	background: -ms-linear-gradient(top,  <?php echo $css->bodyoptnfrom ?> 0%,<?php echo $css->bodyoptnto ?> 100%);
	background: linear-gradient(to bottom,  <?php echo $css->bodyoptnfrom ?> 0%,<?php echo $css->bodyoptnto ?> 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $css->bodyoptnfrom ?>', endColorstr='<?php echo $css->bodyoptnto ?>',GradientType=0 );
	background-color: <?php echo $css->bodyoptnto; ?>;
	background-repeat: repeat-x;
}

		<?php 
		break;
	
	// Horizontal Left to Right
	// ------------------------------------
	case '2' : ?>

body <?php echo $m; ?>{
	background: <?php echo $css->bodyoptnfrom ?>;
	background: -moz-linear-gradient(left,  <?php echo $css->bodyoptnfrom ?> 0%, <?php echo $css->bodyoptnto ?> 100%);
	background: -webkit-gradient(linear, left top, right top, color-stop(0%,<?php echo $css->bodyoptnfrom ?>), color-stop(100%,<?php echo $css->bodyoptnto ?>));
	background: -webkit-linear-gradient(left,  <?php echo $css->bodyoptnfrom ?> 0%,<?php echo $css->bodyoptnto ?> 100%);
	background: -o-linear-gradient(left,  <?php echo $css->bodyoptnfrom ?> 0%,<?php echo $css->bodyoptnto ?> 100%);
	background: -ms-linear-gradient(left,  <?php echo $css->bodyoptnfrom ?> 0%,<?php echo $css->bodyoptnto ?> 100%);
	background: linear-gradient(to right,  <?php echo $css->bodyoptnfrom ?> 0%,<?php echo $css->bodyoptnto ?> 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $css->bodyoptnfrom ?>', endColorstr='<?php echo $css->bodyoptnto ?>',GradientType=1 );
}

			<?php
			break;
			
		// Diagonal Top Left to Bottom Right
		// ------------------------------------
		case '3': ?>

body <?php echo $m; ?>{
	background: <?php echo $css->bodyoptnfrom ?>;
	background: -moz-linear-gradient(-45deg,  <?php echo $css->bodyoptnfrom ?> 0%, <?php echo $css->bodyoptnto ?> 100%);
	background: -webkit-gradient(linear, left top, right bottom, color-stop(0%,<?php echo $css->bodyoptnfrom ?>), color-stop(100%,<?php echo $css->bodyoptnto ?>));
	background: -webkit-linear-gradient(-45deg,  <?php echo $css->bodyoptnfrom ?> 0%,<?php echo $css->bodyoptnto ?> 100%);
	background: -o-linear-gradient(-45deg,  <?php echo $css->bodyoptnfrom ?> 0%,<?php echo $css->bodyoptnto ?> 100%);
	background: -ms-linear-gradient(-45deg,  <?php echo $css->bodyoptnfrom ?> 0%,<?php echo $css->bodyoptnto ?> 100%);
	background: linear-gradient(135deg,  <?php echo $css->bodyoptnfrom ?> 0%,<?php echo $css->bodyoptnto ?> 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $css->bodyoptnfrom ?>', endColorstr='<?php echo $css->bodyoptnto ?>',GradientType=1 );
}

			<?php
			break;
			
		// Diagonal Bottom Left to Top Right
		// ------------------------------------
		case '4': ?>
		
body <?php echo $m; ?>{
	background: <?php echo $css->bodyoptnfrom ?>;
	background: -moz-linear-gradient(45deg,  <?php echo $css->bodyoptnfrom ?> 0%, <?php echo $css->bodyoptnto ?> 100%);
	background: -webkit-gradient(linear, left bottom, right top, color-stop(0%,<?php echo $css->bodyoptnfrom ?>), color-stop(100%,<?php echo $css->bodyoptnto ?>));
	background: -webkit-linear-gradient(45deg,  <?php echo $css->bodyoptnfrom ?> 0%,<?php echo $css->bodyoptnto ?> 100%);
	background: -o-linear-gradient(45deg,  <?php echo $css->bodyoptnfrom ?> 0%,<?php echo $css->bodyoptnto ?> 100%);
	background: -ms-linear-gradient(45deg,  <?php echo $css->bodyoptnfrom ?> 0%,<?php echo $css->bodyoptnto ?> 100%);
	background: linear-gradient(45deg,  <?php echo $css->bodyoptnfrom ?> 0%,<?php echo $css->bodyoptnto ?> 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $css->bodyoptnfrom ?>', endColorstr='<?php echo $css->bodyoptnto ?>',GradientType=1 );
}

			<?php 
			break;
			
		// Radial
		// ------------------------------------
		case '5': ?>
		
body <?php echo $m; ?>{
	background: <?php echo $css->bodyoptnfrom ?>;
	background: -moz-radial-gradient(center, ellipse cover,  <?php echo $css->bodyoptnfrom ?> 0%, <?php echo $css->bodyoptnto ?> 100%);
	background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%,<?php echo $css->bodyoptnfrom ?>), color-stop(100%,<?php echo $css->bodyoptnto ?>));
	background: -webkit-radial-gradient(center, ellipse cover,  <?php echo $css->bodyoptnfrom ?> 0%,<?php echo $css->bodyoptnto ?> 100%);
	background: -o-radial-gradient(center, ellipse cover,  <?php echo $css->bodyoptnfrom ?> 0%,<?php echo $css->bodyoptnto ?> 100%);
	background: -ms-radial-gradient(center, ellipse cover,  <?php echo $css->bodyoptnfrom ?> 0%,<?php echo $css->bodyoptnto ?> 100%);
	background: radial-gradient(ellipse at center,  <?php echo $css->bodyoptnfrom ?> 0%,<?php echo $css->bodyoptnto ?> 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $css->bodyoptnfrom ?>', endColorstr='<?php echo $css->bodyoptnto ?>',GradientType=1 );
}

			<?php 
			break;
		
		endswitch;
		
		break;
		
	// Pattern Background
	// ------------------------------------
	case '3': ?>
	
body <?php echo $m; ?>{
	background: url('<?php echo $baseurl . 'assets/patterns/' . $css->bodyoptnpattern; ?>') repeat scroll 0 0 #EFEFEF;
}
	
		<?php
		break;
	
	// Image URL Background
	// ------------------------------------
	case '4': ?>

body <?php echo $m; ?>{
	background: url('<?php echo $css->bodyoptnimage ?>') repeat scroll 0 0 #EFEFEF;
}

		<?php
		break;
		
endswitch;
// End Body Background


/** =======================================================================================================
 * -----------------------------------------
 * Hyperlinks
 * -----------------------------------------
 */

?>

<?php echo $m; ?>a {			color: <?php echo $css->alinksstd ?>; }
<?php echo $m; ?>a:visited {	color: <?php echo $css->alinksvis ?>; }
<?php echo $m; ?>a:hover {	color: <?php echo $css->alinkshov ?>; }


<?php 
// End Hyperlinks



/** =======================================================================================================
 * -----------------------------------------
 * Navigation Bar
 * -----------------------------------------
 */
?>

<?php 
/**
 * Navbar Background Color
 * 		.footerdivider						// (common to all)
 * 		.topbar-inner						// WHMCS 5.0
 * 		.topbar .fill						// """""""""
 * 		.navbar-inner						// WHMCS 5.1
 * ---------------------------------------------------------------------
 */ ?>
<?php echo $m; ?>.topbar-inner,
<?php echo $m; ?>.topbar .fill,
<?php echo $m; ?>.navbar-inner,
<?php echo $m; ?>.footerdivider {
	background: <?php echo $css->navbarfrom ?>;
	background: -moz-linear-gradient(top,  <?php echo $css->navbarfrom ?> 0%, <?php echo $css->navbarto ?> 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,<?php echo $css->navbarfrom ?>), color-stop(100%,<?php echo $css->navbarto ?>));
	background: -webkit-linear-gradient(top,  <?php echo $css->navbarfrom ?> 0%,<?php echo $css->navbarto ?> 100%);
	background: -o-linear-gradient(top,  <?php echo $css->navbarfrom ?> 0%,<?php echo $css->navbarto ?> 100%);
	background: -ms-linear-gradient(top,  <?php echo $css->navbarfrom ?> 0%,<?php echo $css->navbarto ?> 100%);
	background: linear-gradient(to bottom,  <?php echo $css->navbarfrom ?> 0%,<?php echo $css->navbarto ?> 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $css->navbarfrom ?>', endColorstr='<?php echo $css->navbarto ?>',GradientType=0 );
}


<?php 
/**
 * Navbar Text Color
 * 		.topbar .fill .whmcscontainer > ul > li > a			// WHMCS 5.0
 * 		.topbar .fill .whmcscontainer						// """""""""
 * 		.navbar .nav > li > a								// WHMCS 5.1
 * 		.navbar												// """""""""
 * ---------------------------------------------------------------------
 */ ?>
<?php echo $m; ?>.topbar .fill .whmcscontainer > ul > li > a,
<?php echo $m; ?>.topbar .fill .whmcscontainer,
<?php echo $m; ?>.navbar .nav > li > a,
<?php echo $m; ?>.navbar {
	color: <?php echo $css->navbartxt ?>;
}


<?php 
/**
 * Navbar Text Hover Color
 * 		.topbar .fill .whmcscontainer > ul > li > a:hover	// WHMCS 5.0
 * 		.navbar .nav > li > a:hover							// WHMCS 5.1
 * ---------------------------------------------------------------------
 */ ?>
<?php echo $m; ?>.topbar .fill .whmcscontainer > ul > li > a:hover,
<?php echo $m; ?>.navbar .nav > li > a:hover {
	color: <?php echo $css->navbarhov ?>;
}


<?php 
/**
 * Navbar Submenus Background Color
 * 		.topbar div > ul.secondary-nav .menu-dropdown		// WHMCS 5.0
 * 		.topbar div > ul.secondary-nav .dropdown-menu		// """""""""
 * 		.topbar div > ul .menu-dropdown .divider			// """""""""
 * 		.topbar div > ul .dropdown-menu .divider			// """""""""
 * 		.dropdown-menu										// WHMCS 5.1
 * 		.dropdown-menu .divider								// """""""""
 * ---------------------------------------------------------------------
 */ ?>
<?php echo $m; ?>.topbar div > ul.secondary-nav .menu-dropdown,
<?php echo $m; ?>.topbar div > ul.secondary-nav .dropdown-menu,
<?php echo $m; ?>.topbar div > ul .menu-dropdown .divider,
<?php echo $m; ?>.topbar div > ul .dropdown-menu .divider,
<?php echo $m; ?>.dropdown-menu,
<?php echo $m; ?>.dropdown-menu .divider {
	background-color: <?php echo $css->navbardropbg ?>;
}


<?php 
/**
 * Navbar Submenus Active Item Background and Color
 * 		.topbar div > ul .menu-dropdown li a:hover	// WHMCS 5.0
 * 		.topbar div > ul .dropdown-menu li a:hover	// """""""""
 * 		.dropdown-menu li > a:hover					// WHMCS 5.1
 * 		.dropdown-menu .active > a					// """""""""
 * 		.dropdown-menu .active > a:hover			// """""""""
 * ---------------------------------------------------
 */ ?>
<?php echo $m; ?>.topbar div > ul .menu-dropdown li a:hover,
<?php echo $m; ?>.topbar div > ul .dropdown-menu li a:hover,
<?php echo $m; ?>.dropdown-menu li > a:hover,
<?php echo $m; ?>.dropdown-menu .active > a,
<?php echo $m; ?>.dropdown-menu .active > a:hover {
	background: none repeat scroll 0 0 <?php echo $css->navbardrophl ?>;
	color: <?php echo $css->navbardropbg ?>;
}


<?php 
/**
 * Navbar Submenus Link Color
 * 		.topbar div > ul .menu-dropdown li a		// WHMCS 5.0
 * 		.topbar div > ul .dropdown-menu li a		// """""""""
 * 		.dropdown-menu a							// WHMCS 5.1
 * ---------------------------------------------------
 */ ?>
<?php echo $m; ?>.topbar div > ul .menu-dropdown li a,
<?php echo $m; ?>.topbar div > ul .dropdown-menu li a,
<?php echo $m; ?>.dropdown-menu a {
	color: <?php echo $css->navbardroptxt ?>;
}

<?php 
// End Navigation Bar



?>