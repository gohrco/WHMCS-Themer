<?php defined('DUNAMIS') OR exit('No direct script access allowed');

if (! defined( 'DUN_MOD_THEMER' ) ) define( 'DUN_MOD_THEMER', "@fileVers@" );

class ThemerClientDunModule extends WhmcsDunModule
{
	/**
	 * Stores the type of module this is
	 * @access		protected
	 * @var			string
	 * @since		1.0.0
	 */
	protected $type	= 'addon';
	
	/**
	 * Constructor method
	 * @access		public
	 * @version		@fileVers@
	 * @param		array		- $options: contains an array of arguments
	 *
	 * @since		1.0.0
	 */
	public function __construct( $options = array() )
	{
		parent :: __construct( $options );
		
	}
	
	
	/**
	 * Renders the output for the admin area of the site (WHMCS > Addons > Module name)
	 * @access		public
	 * @version		@fileVers@
	 * 
	 * @return		string
	 * @since		1.0.0
	 */
	public function generateClientOutput()
	{
		// Be sure we can do this...
		if (! $this->_permissionCheck( false ) ) return;
		
		dunloader( 'form', true )->loadForm( 'clientselect', 'themer' );
		
		$this->_renderClientCss();
		$this->_renderClientJs();
	}
	
	
	/**
	 * Initializes the module
	 * @access		public
	 * @version		@fileVers@
	 *
	 * @since		1.0.0
	 */
	public function initialise()
	{
		dunloader( 'language', true )->loadLanguage( 'themer' );
		dunloader( 'hooks', true )->attachHooks( 'themer' );
		
	}
	
	
	/**
	 * Renders the output above the content beneath the opening body tag
	 * @access		public
	 * @version		@fileVers@
	 * 
	 * @return		string
	 * @since		1.0.0
	 */
	public function renderClientHeaderOutput()
	{
		// Be sure we can do this...
		if (! $this->_permissionCheck() ) return null;
		
		global $whmcs;
		
		// Handle save capability from front end
		if (! is_object( $whmcs ) ) return;
		if ( $this->_permissionCheck() && array_key_exists( 'themer', $whmcs->input ) ) $this->_saveSettings();
		
		$baseurl 	=   get_baseurl( 'themer' );
		$ln			=	"\n";
		
		$data	=	'<div id="themer">' . $ln
				.	'	<div id="themer-wrapper">' . $ln
				.	'		<div id="themer-slide">' . $ln
				.				$this->_renderClientBody() . $ln
				.	'		</div>' . $ln
				.	'		<div class="themerdrop" id="themer-clicker">' . $ln
				.	'			<div id="themer-clickinner">' . $ln
				.	'				<img src="' . $baseurl . 'assets/logo.png" />' . $ln
				.	'				<span class="txt">' . t( 'themer.client.module.title' ) . '</span>' . $ln 
				.	'			</div>' . $ln
				.	'		</div>' . $ln
				.	'	</div>' . $ln
				.	'</div>' . $ln;
		return $data;
	}
	
	
	/**
	 * Renders the output for the footer area
	 * @access		public
	 * @version		@fileVers@
	 * 
	 * @return		string
	 * @since		1.0.0
	 */
	public function renderFooter()
	{
		// Be sure we can do this...
		if (! $this->_permissionCheck( false ) ) return null;
		
		$lic	= dunloader( 'license', 'themer' );
		
		// Don't bother if they dont have branding
		if (! $lic->isBranded() ) return null;
		
		return '<div class="whmcscontainer"><p style="font-size: smaller; text-align: center">Theme Rendered with <a href="https://www.gohigheris.com/" target="_blank">WHMCS Themer</a></p></div>';
	}
	
	
	/**
	 * Method to gather fonts used to call up from Google
	 * @access		private
	 * @version		@fileVers@
	 * 
	 * @return		string
	 * @since		1.0.0
	 */
	private function _gatherFonts()
	{
		// Lets get the current theme
		$settings = $this->_getActiveTheme();
		
		$doc	= & dunloader( 'document', true );
		$check	=	array( 'font', 'txtelemgffont', 'txtelemh1font', 'txtelemh2font', 'txtelemh3font', 'txtelemh4font', 'txtelemh5font', 'txtelemh6font' );
		//$data	=	array( "Helvetica Neue" );
		$data	= array();
		
		foreach ( $check as $item ) {
			if ( $settings->$item != '- use primary -' ) $data[] = $settings->$item;
		}
		
		if ( empty( $data ) ) return false;
		
		return str_replace( " ", "+", implode( "|", $data ) );
	}
	
	
	/**
	 * Method to gather the current active theme
	 * @access		private
	 * @version		@fileVers@
	 * 
	 * @return		array of settings
	 * @since		1.0.0
	 */
	private function _getActiveTheme()
	{
		$db		= & dunloader( 'database', true );
		
		$db->setQuery( "SELECT `value` FROM `mod_themer_settings` WHERE `key` = 'usetheme'" );
		$tid	= $db->loadResult();
		
		$db->setQuery( "SELECT `params` FROM `mod_themer_themes` WHERE `id` = '" . $tid . "'" );
		$params	= $db->loadResult();
		
		return json_decode( $params, false );
	}
	
	
	/**
	 * Permissions check
	 * @access		private
	 * @version		@fileVers@
	 * @param		bool		- $full: indicates we want to check all possibilities
	 * 
	 * @return		boolean
	 * @since		1.0.0
	 */
	private function _permissionCheck( $full = true )
	{
		$config	= & dunloader( 'config', 'themer' );
		$lic	= & dunloader( 'license', 'themer' );
		
		// Lets see if we have a valid license first
		if (! $lic->isValid() ) return false;
		
		$wconfig	= & dunloader( 'config', true );
		$template	=   $wconfig->get( 'Template', 'portal' );
		
		if ( $template != 'default' ) return false;
		
		// Get out here if we are just checking license
		if (! $full ) return true;
		
		// Lets see if the front end selector is enabled
		$config->enable	= (bool) $config->enable;
		if (! $config->enable ) return false;
		
		// Now lets see if we are restricting by admin user
		if (! empty( $config->restrictuser ) ) {
			$sess = $GLOBALS['_SESSION'];
			if (! array_key_exists( 'adminloggedinstatus', $sess ) || $sess['adminloggedinstatus'] != 'true' ) return false;
			$ids	= explode('|', $config->restrictuser );
			if (! in_array( $sess['adminid'], $ids ) ) return false;
		}
		
		// Now lets check the IP address...
		if (! empty( $config->restrictip ) ) {
			$ip		= $GLOBALS['remote_ip'];
			$ips	= explode( ',', $config->restrictip );
			if (! in_array( $ip, $ips ) ) return false;
		}
		
		return true;
	}
	
	
	/**
	 * Renders the client side CSS
	 * @access		private
	 * @version		@fileVers@
	 * 
	 * @since		1.0.0
	 */
	private function _renderClientCss()
	{
		$db		= & dunloader( 'database', true );
		$doc	= & dunloader( 'document', true );
		
		// Add our CSS file first
		$baseurl 	=   get_baseurl( 'themer' );
		$baseuri	= & DunUri :: getInstance( $baseurl );
		
		if ( $this->_permissionCheck() ) {
			$doc->addStyleSheet( $baseurl . 'assets/reset.css' );						// Reset CSS
			$doc->addStyleSheet( $baseurl . 'bootstrap/css/themer.bootstrap.css' );		// Our bootstrap
			$doc->addStyleSheet( $baseurl . 'assets/client.css' );
			$css	= "#themer { position: absolute; height: 0; }";
			$doc->addStyleDeclaration( $css );
		}
		
		// Now lets add the various Fonts needed
		$ip = $GLOBALS['remote_ip'];
		if ( ( $fonts = $this->_gatherFonts() ) ) {
			$doc->addStyleSheet( $baseuri->getScheme() . '://fonts.googleapis.com/css?family=' . $fonts . '&userIp=' . $ip . '&key=AIzaSyAIpf_-Sp0uZZl0LcSXSPtgQwnXqFUIAO4' );
		}
		$doc->addStyleSheet( $baseurl . 'css.php' );
	}
	
	
	/**
	 * Renders the body for the client side
	 * @access		private
	 * @version		@fileVers@
	 * 
	 * @return		string
	 * @since		1.0.0
	 */
	private function _renderClientBody()
	{
		$db		= & dunloader( 'database', true );
		$form	= & dunloader( 'form', true );
		$data	=	null;
		
		// Build the baseuri
		$uri		=   DunUri :: getInstance( 'SERVER', true );
		$uri->setVar( 'settheme', '1' );
		
		// Grab all the themes and set them to the form field now
		$db->setQuery( "SELECT `name`, `id` FROM `mod_themer_themes` ORDER BY `name`" );
		$form->setOption( 'preset', $db->loadObjectList(), 'themer.clientselect' );
		
		// Get the selected theme
		$db->setQuery( "SELECT `value`, `name`, `params` FROM `mod_themer_settings` s INNER JOIN `mod_themer_themes` t ON s.value = t.id WHERE `key` = 'usetheme'" );
		$values	= $db->loadObject();
		$values->params = json_decode( $values->params, true );
		foreach ( $values->params as $k => $v ) $values->$k = $v;
		unset ( $values->params );
		$values->preset = $values->value;
		$values->assets = get_baseurl( 'themer' );
		
		// Lets set the field values in place
		$preset	=	$form->setValue( 'preset', $values->value, 'themer.clientselect' )->getField( 'preset', 'themer.clientselect' );
		
		$data	=	'<h3>' . t( 'themer.client.module.subtitle.presets' ) . '</h3>'
				.	'<form action="' . $GLOBALS['PHP_SELF'] . '" class="form-horizontal" method="post">'
				.	'	<div class="control-group">'
				.	'		<label for="preset" class="control-label">' . t( 'themer.client.themer.form.label.preset' ) . '</label>'
				.	'		<div class="controls">' . $form->getField( 'preset', 'themer.clientselect' )->field() . '</div>'
				.	'	</div>'
				.	'	<div class="control-group" style="margin-top: -12px">'
				.	'		<div class="controls">'
				.	'			' . $form->getButton( 'submit', array( 'class' => 'btn btn-primary btn-mini', 'value' => t( 'themer.client.themer.form.submit.changeto' ), 'name' => 'submit' ) )
				.	'		</div>'
				.	'	</div>'
				.	'<input type="hidden" name="themer" value="1" /></form>';
		
		$form->deleteField( 'preset', 'themer.clientselect' );
		$fields	=	$form->setValues( $values, 'themer.clientselect' );
		
		$data	.=	'<h3>' . t( 'themer.client.module.subtitle.change' ) . '</h3>'
				.	'<form action="' . $GLOBALS['PHP_SELF'] . '" class="form-horizontal" method="post">'
				.	$this->_renderForm( $fields, array() )
				.	'	<div class="control-group" style="margin-top: -12px">'
				.	'		<div class="controls">'
				.	'			' . $form->getButton( 'submit', array( 'class' => 'btn btn-primary btn-mini', 'value' => t( 'themer.client.themer.form.submit.update' ), 'name' => 'submit' ) )
				.	'		</div>'
				.	'	</div>'
				.	'<input type="hidden" name="themer" value="2" /></form>';
		
		return $data;
	}
	
	
	/**
	 * Builds the javascript
	 * @access		private
	 * @version		@fileVers@
	 * 
	 * @since		1.0.0
	 */
	private function _renderClientJs()
	{
		if (! $this->_permissionCheck() ) return;
		
		$doc	= & dunloader( 'document', true );
		
		$js = <<< JS
jQuery(document).ready( function() {
	jQuery('#themer-wrapper').css('margin-left', '-350px');
	jQuery('#themer-clicker').click( function() {
		var \$lefty = jQuery('#themer-wrapper');
		\$lefty.animate({
			marginLeft: parseInt(\$lefty.css('marginLeft'),10) == 0 ?
        			-(\$lefty.outerWidth() - 50) :
        			0
		});
	});
});
JS;
		$baseurl 	=   get_baseurl( 'themer' );
		
		$doc->addScript( $baseurl . 'bootstrap/js/bootstrap.min.js' );				// Our javascript
		$doc->addScript( $baseurl . 'assets/client.js' );							// Our javascript
		$doc->addScriptDeclaration( $js );
	}
	
	
	/**
	 * Common method for rendering fields into a form
	 * @access		private
	 * @version		@fileVers@
	 * @param		array		- $fields: contains an array of Field objects
	 *
	 * @return		string
	 * @since		1.0.0
	 */
	private function _renderForm( $fields = array(), $options = array() )
	{
		$data	= null;
		$foptn	= ( array_key_exists( 'fields', $options ) ? $options['fields'] : array() );
		
		foreach ( $fields as $field ) {	// Fields of Themes cycle
	
			if ( in_array( $field->get( 'type' ), array( 'hidden', 'wrapo', 'wrapc' ) ) ) {
				$data .= $field->field();
				continue;
			}
			
			$loptn	= array( 'class' => 'control-label', 'data-placement' => 'right', 'rel' => 'tooltip', 'data-original-title' => $field->description( array( 'type' => 'none' ) ) );
// 			<a data-placement="bottom" rel="tooltip" href="#" data-original-title="Tooltip on bottom">Tooltip on bottom</a>
			$data	.= <<< HTML
<div class="control-group">
	{$field->label( $loptn )}
	<div class="controls">
		{$field->field( $foptn )}
	</div>
</div>
HTML;
		}
		
		dunloader( 'document', true )->addScriptDeclaration( 'jQuery( \'label[class="control-label"]\').tooltip();' );
		
		return $data;
	}
	
	
	/**
	 * Saves the settings if requested on front end
	 * @access		private
	 * @version		@fileVers@
	 * 
	 * @since		1.0.0
	 */
	private function _saveSettings()
	{
		global $whmcs;
		
		$db		=   dunloader( 'database', true );
		$task	=   $whmcs->input['themer'];
		
		switch ( $task ) {
			case '2' :
				$db->setQuery( "SELECT `value`, `params` FROM `mod_themer_settings` s INNER JOIN `mod_themer_themes` t ON s.value = t.id WHERE `key` = 'usetheme'" );
				$theme	= $db->loadObject();
				$params = json_decode( $theme->params, false );
				
				foreach ( $params as $k => $v ) {
					if (! array_key_exists( $k, $whmcs->input ) ) continue;
					$params->$k = $whmcs->input[$k];
				}
				
				$theme->params = json_encode( $params );
				
				$db->setQuery( "UPDATE `mod_themer_themes` SET `params` = '{$theme->params}' WHERE `id` = '{$theme->value}'" );
				$db->query();
				
				break;
			default:
			case '1' :
				
				$tid	=   $whmcs->input['preset'];
				$db->setQuery( "UPDATE `mod_themer_settings` SET `value` = '" . $tid . "' WHERE `key` = 'usetheme'" );
				$db->query();
				
				break;
		}
	}
}