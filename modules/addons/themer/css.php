<?php
ob_start("ob_gzhandler");
header("Content-type: text/css; charset: UTF-8");


/*-- Dunamis Inclusion --*/
$path	= dirname( dirname( dirname( dirname(__FILE__) ) ) ) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'dunamis.php';
if ( file_exists( $path ) ) include_once( $path );
/*-- Dunamis Inclusion --*/

$dun	= & get_dunamis( 'themer' );
$db		= dunloader( 'database', true );
$config = dunloader( 'config', true );

// Lets start by cleaning up our system URI
$oururi	= DunUri :: getInstance();
$oururi->delVars();
$sysuri	= DunUri :: getInstance( $config->get( 'SystemURL' ) );
$sysuri->setScheme( $oururi->getScheme() );

$get = $GLOBALS['_GET'];

if ( array_key_exists( 'tid', $get ) ) {
	$tid = $get['tid'];
}
else {
	// Lets load the default theme we want to use
	$db->setQuery( "SELECT `value` FROM `mod_themer_settings` WHERE `key` = 'usetheme'" );
	$tid	= $db->loadResult();
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

#whmcsheader {
	background: <?php echo $css->contentbg; ?>;
	max-width: inherit;
}

.navbar .navbar-inner {
	width: inherit; -moz-border-radius: 0px;
	-webkit-border-radius: 0px;
	-o-border-radius: 0px;
	border-radius: 0;
}

.whmcscontainer,
.whmcscontainer .footer {
	background: transparent;
}

.footerdivider {
	display: none;
}

<?php
else :
?>

#whmcsheader,
.whmcscontainer,
.whmcscontainer .footer {
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

body,
.navbar-search .search-query,
input,
button,
select,
textarea {
	font-family: "<?php echo $fonts->primary; ?>",Helvetica,Arial,sans-serif;
}

.whmcscontainer,
p {
	font-family: "<?php echo $fonts->gen; ?>",Helvetica,Arial,sans-serif;
	font-size: <?php echo $css->txtelemgfsize; ?>px;
	line-height: <?php echo intval( ( 18 / 13 ) * $css->txtelemgfsize ); ?>px;
	color: <?php echo $css->txtelemgfcolor; ?>;
}

.whmcscontainer h1,
h1 {
	font-family: "<?php echo $fonts->h1; ?>",Helvetica,Arial,sans-serif;
	font-size: <?php echo $css->txtelemh1size; ?>px;
	line-height: <?php echo intval( ( 36 / 30 ) * $css->txtelemh1size ); ?>px;
	color: <?php echo $css->txtelemh1color; ?>;
}

.whmcscontainer h2,
h2 {
	font-family: "<?php echo $fonts->h2; ?>",Helvetica,Arial,sans-serif;
	font-size: <?php echo $css->txtelemh2size ?>px;
	line-height: <?php echo intval( ( 36 / 24 ) * $css->txtelemh2size ) ?>px;
	color: <?php echo $css->txtelemh2color; ?>;
}

.whmcscontainer h3,
h3 {
	font-family: "<?php echo $fonts->h3; ?>",Helvetica,Arial,sans-serif;
	font-size: <?php echo $css->txtelemh3size ?>px;
	line-height: <?php echo intval( ( 27 / 18 ) * $css->txtelemh3size ) ?>px;
	color: <?php echo $css->txtelemh3color; ?>;
}

.whmcscontainer h4,
h4 {
	font-family: "<?php echo $fonts->h4 ?>",Helvetica,Arial,sans-serif;
	font-size: <?php echo $css->txtelemh4size ?>px;
	line-height: <?php echo intval( ( 18 / 14 ) * $css->txtelemh4size ) ?>px;
	color: <?php echo $css->txtelemh4color; ?>;
}

.whmcscontainer h5,
h5 {
	font-family: "<?php echo $fonts->h5 ?>",Helvetica,Arial,sans-serif;
	font-size: <?php echo $css->txtelemh5size ?>px;
	line-height: <?php echo intval( ( 18 / 12 ) * $css->txtelemh5size ) ?>px;
	color: <?php echo $css->txtelemh5color; ?>;
}

.whmcscontainer h6,
h6 {
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
?>

#whmcsimglogo > a > img { display: none; }
#whmcsimglogo > a {
	background: url(' <?php echo $css->logo; ?>') no-repeat scroll 0 0 transparent;
	display: block;
	height: <?php echo $imgdata[1] ?>px;
	width: <?php echo $imgdata[0] ?>px;
}

<?php 
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

body {
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
				
body {
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

body {
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

body {
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
		
body {
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
		
body {
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
	
body {
	background: url('<?php echo $baseurl . 'assets/patterns/' . $css->bodyoptnpattern; ?>') repeat scroll 0 0 #EFEFEF;
}
	
		<?php
		break;
	
	// Image URL Background
	// ------------------------------------
	case '4': ?>

body {
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

a {			color: <?php echo $css->alinksstd ?>; }
a:visited {	color: <?php echo $css->alinksvis ?>; }
a:hover {	color: <?php echo $css->alinkshov ?>; }


<?php 
// End Hyperlinks



/** =======================================================================================================
 * -----------------------------------------
 * Navigation Bar
 * -----------------------------------------
 */

?>

.navbar-inner,
.footerdivider {
	background: <?php echo $css->navbarfrom ?>;
	background: -moz-linear-gradient(top,  <?php echo $css->navbarfrom ?> 0%, <?php echo $css->navbarto ?> 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,<?php echo $css->navbarfrom ?>), color-stop(100%,<?php echo $css->navbarto ?>));
	background: -webkit-linear-gradient(top,  <?php echo $css->navbarfrom ?> 0%,<?php echo $css->navbarto ?> 100%);
	background: -o-linear-gradient(top,  <?php echo $css->navbarfrom ?> 0%,<?php echo $css->navbarto ?> 100%);
	background: -ms-linear-gradient(top,  <?php echo $css->navbarfrom ?> 0%,<?php echo $css->navbarto ?> 100%);
	background: linear-gradient(to bottom,  <?php echo $css->navbarfrom ?> 0%,<?php echo $css->navbarto ?> 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $css->navbarfrom ?>', endColorstr='<?php echo $css->navbarto ?>',GradientType=0 );
}

.navbar .nav > li > a,
.navbar {
	color: <?php echo $css->navbartxt ?>;
}

.navbar .nav > li > a:hover {
	color: <?php echo $css->navbarhov ?>;
}

.dropdown-menu,
.dropdown-menu .divider {
	background-color: <?php echo $css->navbardropbg ?>;
}

.dropdown-menu li > a:hover,
.dropdown-menu .active > a,
.dropdown-menu .active > a:hover {
	background-color: <?php echo $css->navbardrophl ?>;
	color: <?php echo $css->navbardropbg ?>;
}

.dropdown-menu a { color: <?php echo $css->navbardroptxt ?>; }

<?php 
// End Navigation Bar



?>