<?php defined('DUNAMIS') OR exit('No direct script access allowed');

if (! defined( 'DUN_MOD_THEMER' ) ) define( 'DUN_MOD_THEMER', "@fileVers@" );

class ThemerAdminDunModule extends WhmcsDunModule
{
	/**
	 * Stores the type of module this is
	 * @access		protected
	 * @var			string
	 * @since		1.0.0
	 */
	protected $type	= 'addon';
	
	/**
	 * Retrieves the configuration array for the product in the addon modules menu
	 * @access		public
	 * @version		@fileVers@
	 * 
	 * @return		array
	 * @since		1.0.0
	 */
	public function getAdminConfig()
	{
		$data = array(
				"name"			=> t( 'themer.admin.config.title' ),
				"version"		=> "@fileVers@",
				"author"		=> t( 'themer.admin.config.author' ),
				"description"	=> t( 'themer.admin.config.description' ),
				"language"		=> "english",
				"fields"		=> array()
		);
		
		return $data;
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
	 * Renders the output for the admin area of the site (WHMCS > Addons > Module name)
	 * @access		public
	 * @version		@fileVers@
	 * 
	 * @return		string
	 * @since		1.0.0
	 */
	public function renderAdminOutput()
	{
		$input	= dunloader( 'input', true );
		$action	= $input->getVar( 'action', 'themes' );
		$submit	= $input->getVar( 'submit', false );
		
		if ( $submit ) {
			$this->_saveForm();
		}
		
		$doc		=   dunloader( 'document', true );
		$baseurl 	=   get_baseurl( 'themer' );
		
		$doc->addStyleDeclaration( '#contentarea > div > h1, #content > h1 { display: none; }' );	// Wipes out WHMCS' h1
		$doc->addStyleDeclaration( '.contentarea > h1 { display: none; }' );	// Wipes out WHMCS' h1 in 5.0.3
		
		load_bootstrap( 'themer' );
		$doc->addStyleSheet( $baseurl . 'assets/jquery.minicolors.css' );
		$title	= $this->_getTitle();
		$navbar = $this->_getNavigation();
		$body	= $this->_getBody();
		
		$data	= <<< HTML
<div style="float:left;width:100%;">
	<div id="themer">
		{$title}
		{$navbar}
		{$body}
	</div>
</div>
<div style="clear: both; "> </div>
HTML;
	
		return $data;
		
	}
	
	
	/**
	 * Renders the sidebar for the admin area
	 * @access		public
	 * @version		@fileVers@
	 * 
	 * @return		string
	 * @since		1.0.0
	 */
	public function renderAdminSidebar()
	{
		return;
	}
	
	
	/**
	 * Gathers the configuration
	 * @access		private
	 * @version		@fileVers@
	 * @param		bool		- $asarray: indicates we want it back as an array
	 * 
	 * @return		array|object of values
	 * @since		1.0.0
	 */
	private function _gatherConfig( $asarray = false )
	{
		$db	= dunloader( 'database', true );
		
		$db->setQuery( "SELECT * FROM mod_themer_settings" );
		$results	= $db->loadObjectList();
		$values		= array();
		
		if ( $asarray ) {
			foreach ( $results as $result ) $values[$result->key] = $result->value;
			return $values;
		}
		
		$values		= (object) $values;
		
		// Set the values up
		foreach ( $results as $result ) {
			$item = $result->key;
			$values->$item = $result->value;//$values->$result->key = $result->value;
		}
		
		return $values;
	}
	
	
	/**
	 * Gathers the body for an admin page
	 * @access		private
	 * @version		@fileVers@
	 * 
	 * @return		string
	 * @since		1.0.0
	 */
	private function _getBody()
	{
		$input	= dunloader( 'input', true );
		$action	= $input->getVar( 'action', 'themes' );
		
		$doc	=	dunloader( 'document', true );
		$db		=	dunloader( 'database', true );
		$form	=	dunloader( 'form', true );
		$data	=   null;
		
		switch ( $action ) {
			
			case 'themes' :
				
				$task	= $input->getVar( 'task', false );
				$sac	= $input->getVar( 'saveandclose', false );
				$task	= $sac ? 'saveandclose' : $task;
				
				switch ( $task ) {
					
					// Edit a specific theme
					case 'edittheme':
						
						$tid	= $input->getVar( 'tid', false );
						
						// Pull all the themes from the database
						$db->setQuery( "SELECT * FROM `mod_themer_themes` WHERE `id` = '{$tid}'" );
						$theme	= $db->loadObject();
						$params	= json_decode( $theme->params, true );
						foreach ( $params as $k => $v ) $theme->$k = $v;
						
						$fields	= $form->setValues( $theme, 'themer.theme' );
						$render	= $this->_renderForm( $fields, array() );
						
						$data	.=	'<form action="addonmodules.php?module=themer&task=edittheme&action=themes" class="form-horizontal" method="post">'
								.	$render
								.	'<div class="form-actions"> '
								.	$form->getButton( 'submit', array( 'class' => 'btn btn-primary btn-large span2', 'value' => t( 'themer.form.apply' ), 'name' => 'save' ) ) . ' '
								.	$form->getButton( 'submit', array( 'class' => 'btn btn-large btn-inverse span2', 'value' => t( 'themer.form.saveandclose' ), 'name' => 'saveandclose' ) ) . ' '
								.	'<a href="addonmodules.php?module=themer&action=themes" class="btn btn-large span2 pull-right"> ' . t( 'themer.form.close' ) . '</a>'
								.	'</div>'
								.	'<input type="hidden" name="tid" value="' . $theme->id . '" />'
								.	'<input type="hidden" name="submit" value="1" />'
								.	'</form>';
						
						break;
					// The default action to take
					case 'saveandclose' :
					default:
						
						// Get the current theme being used on the front end
						$db->setQuery( "SELECT `value` FROM `mod_themer_settings` WHERE `key` = 'usetheme'" );
						$tid	= $db->loadResult();
						
						// Build the Add New Theme form
						$data	.=	'<div class="pull-right">'
								.	'	<form action="addonmodules.php?module=themer&action=themes" class="form-inline" method="post">'
								.	'		<input name="name" type="text" placeholder="' . t( 'themer.admin.themer.form.theme.placeholder.name' ) . '"> '
								.	'			<button type="submit" class="btn btn-success">' . t( 'themer.admin.themer.form.theme.button.addnew' ) . '</button>'
								.	'			<input name="submit" value="1" type="hidden" /><input type="hidden" name="task" value="addnew" />'
								.	'	</form>'
								.	'</div>'
								.	'<div style="clear: both; "> </div>';
						
						// Pull all the themes from the database
						$db->setQuery( "SELECT * FROM `mod_themer_themes` ORDER BY `name`" );
						$items	= $db->loadObjectList();
						
						// Cycle through the themes
						foreach ( $items as $item ) {
							
							$data	.=	'<div class="row-fluid well well-small">'
									.	'	<div class="span12">'
									.	( $item->id != '1'
									?	'		<h3><a href="addonmodules.php?module=themer&action=themes&task=edittheme&tid=' . $item->id . '">' . $item->name . '</a></h3>'
									:	'		<h3>' . $item->name . '</h3>' )
									.	'	</div>'
									.	'	<div class="span12">'
									.	'		<p>' . $item->description . '</p>'
									.	'	</div>'
									.	'	<div class="span12">'
									.	'		<a href="addonmodules.php?module=themer&action=themes&submit=1&task=makedefault&tid=' . $item->id . '" class="btn btn-inverse span2 '
									.	( $item->id == $tid
									?	'disabled">' . t( 'themer.admin.themer.form.theme.isselected.theme' )
									:	'">' . t ( 'themer.admin.themer.form.theme.button.makedefault' ) )
									. '</a> '
									.	'		<a href="addonmodules.php?module=themer&action=themes&submit=1&task=copytheme&tid=' . $item->id . '" class="btn btn-inverse span2">' . t( 'themer.admin.themer.form.theme.button.copytheme' ) . '</a>'
									.	( $item->id != '1' 
									?	'<a href="addonmodules.php?module=themer&action=themes&task=edittheme&tid=' . $item->id . '" class="btn btn-primary span1">' . t( 'themer.form.edit' ) . '</a>'
									:	'' )
									.	(! in_array( $item->id, array( '1', $tid ) )
									?	'<a href="addonmodules.php?module=themer&action=themes&submit=1&task=delete&tid=' . $item->id . '" class="btn btn-danger span1">' . t( 'themer.form.delete' ) . '</a>'
									: '' )
									.	'	</div>'
									.	'</div>';
						}	// End Task Switch
				} // End Theme Action Switch
				
				break;
				
			case 'config' :
				
				$db->setQuery( "SELECT * FROM mod_themer_settings" );
				$results	= $db->loadObjectList();
				$values		= array();
				
				// Set the values up
				foreach ( $results as $result ) $values[$result->key] = $result->value;
				$fields = $form->setValues( $values, 'themer.config' );
				
				$data	=	'<form action="addonmodules.php?module=themer&action=config" class="form-horizontal" method="post">'
						.		$this->_renderForm( $fields )
						.		'<div class="form-actions">'
						.			$form->getButton( 'submit', array( 'class' => 'btn btn-primary', 'value' => t( 'themer.form.submit' ), 'name' => 'submit' ) )
						.			$form->getButton( 'reset', array( 'class' => 'btn', 'value' => t( 'themer.form.cancel' ) ) )
						.		'</div>'
						.	'</form>';
				break;
				
			case 'license' :
				
				$lic	= dunloader( 'license', 'themer' );
				$parts	= $lic->getItems();
				
				// Set license
				$config	= dunloader( 'config', 'themer' );
				$config->refresh();
				$form->setValue( 'license', $config->get( 'license' ), 'themer.license' );
				
				// Set status
				if (! array_key_exists( 'supnextdue', $parts ) ) {
					$state = 'important';
				}
				else {
					$state	= ( strtotime( $parts['supnextdue'] ) >= strtotime( date("Ymd") ) ? 'success' : ( $parts['status'] == 'Invalid' ? 'important' : 'warning' ) );
				}
				
				$sttxt	= ( $state == 'success' ? 'Active' : ( $state == 'important' ? 'Invalid License' : 'Expired' ) );
				$form->setValue( 'status', '<span class="label label-' . $state . '"> ' . $sttxt . ' </span>', 'themer.license' );
				
				// Set Branding
				$form->setValue( 'branding', t( 'themer.admin.themer.form.config.branding.' . ( $lic->isBranded() ? 'branded' : 'nobrand' ) ), 'themer.license' );
				
				// Set information
				$info	= array();
				if ( $state != 'important' ) {
					$use	= array( 'registeredname', 'companyname', 'regdate', 'supnextdue' );
					foreach ( $use as $i ) {
						
						// Check to see if we have the item
						if (! array_key_exists( $i, $parts ) ) continue;
						$info[]	= ( $i != 'supnextdue' ? t( 'themer.admin.themer.form.config.info.' . $i, $parts[$i] ) : t( 'themer.admin.themer.form.config.info.supnextdue', $state, $parts[$i] ) );
					}
				}
				else {
					if (! isset( $parts['message'] ) ) {
						$info[]	= t( 'themer.admin.themer.form.config.info.invalidkey' );
					}
					else {
						$info[]	= t( 'themer.admin.themer.form.config.info.invalidmsg', $parts['message'] );
					}
				}
				
				$form->setValue( 'info', $info, 'themer.license' );
				
				// Grab the fields
				$fields = $form->loadForm( 'license', 'themer' );
				
				$data	=	'<form action="addonmodules.php?module=themer&action=license" class="form-horizontal" method="post">'
						.		$this->_renderForm( $fields )
						.		'<div class="form-actions">'
						.			$form->getButton( 'submit', array( 'class' => 'btn btn-primary', 'value' => t( 'themer.form.submit' ), 'name' => 'submit' ) )
						.			$form->getButton( 'reset', array( 'class' => 'btn', 'value' => t( 'themer.form.cancel' ) ) )
						.		'</div>'
						.	'</form>';
				break;
		}
		
		return $data;
	}
	
	
	/**
	 * Method to generate the navigation bar at the top
	 * @access		private
	 * @version		@fileVers@
	 * 
	 * @return		string
	 * @since		1.0.0
	 */
	private function _getNavigation()
	{
		$input	= dunloader( 'input', true );
		$action	= $input->getVar( 'action', 'themes' );
		$task	= $input->getVar( 'task', null );
		
		$uri	= DunUri :: getInstance('SERVER', true );
		$uri->delVars();
		$uri->setVar( 'module', 'themer' );
		
		$html	= '<ul class="nav nav-pills">';
		
		foreach( array( 'themes', 'config', 'license' ) as $item ) {
			
			if ( $item == $action && $task != 'edittheme' ) {
				$html .= '<li class="active"><a href="#">' . t( 'themer.admin.module.navbar.' . $item ) . '</a></li>';
				continue;
			}
			
			$uri->setVar( 'action', $item );
			$html .= '<li><a href="' . $uri->toString() . '">' . t( 'themer.admin.module.navbar.' . $item ) . '</a></li>';
		}
		
		
		$html	.= '</ul>';
		return $html;
	}
	
	
	/**
	 * Method to generate the title at the top of the page
	 * @access		private
	 * @version		@fileVers@
	 * 
	 * @return		string
	 * @since		1.0.0
	 */
	private function _getTitle()
	{
		$input	= dunloader( 'input', true );
		$action	= $input->getVar( 'action', 'themes' );
		$task	= $input->getVar( 'task', null );
		
		return '<h1>' . t( 'themer.admin.module.title', t( 'themer.admin.module.title.' . $action . ( $task ? '.' . $task : '' ) ) ) . '</h1>';
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
		
			if ( in_array( $field->get( 'type' ), array( 'wrapo', 'wrapc' ) ) ) {
				$data .= $field->field();
				continue;
			}
		
			$data	.= <<< HTML
<div class="control-group">
	{$field->label( array( 'class' => 'control-label' ) )}
	<div class="controls">
		{$field->field( $foptn )}
		{$field->description( array( 'type' => 'span', 'class' => 'help-block help-inline' ) )}
	</div>
</div>
HTML;
		}
		
		return $data;
	}
	
	
	/**
	 * Method to save the form when saved
	 * @access		private
	 * @version		@fileVers@
	 * 
	 * @since		1.0.0
	 */
	private function _saveForm()
	{
		$db		= dunloader( 'database', true );
		$input	= dunloader( 'input', true );
		$action	= $input->getVar( 'action', 'themes' );
		
		switch ( $action ) {
			// Save the theme settings
			case 'themes' :
				
				// Check license and task
				if (! dunloader( 'license', 'themer' )->isValid() ) return;
				if (! $input->getVar( 'task', false ) ) return;
				
				$task	= $input->getVar( 'task', 'default' );
				$tid	= $input->getVar( 'tid', null );
				
				switch( $task ) {
					case 'addnew' :
						$db->setQuery( "SELECT `params` FROM `mod_themer_themes` WHERE `id` = '1'" );
						$params	= $db->loadResult();
						
						$db->setQuery( "INSERT INTO `mod_themer_themes` (`name`, `params` ) VALUES ('" . $input->getVar( 'name', null ) . "', '" . $params . "' ); ");
						$db->query();
						break;
					case 'delete' :
						$db->setQuery( "DELETE FROM `mod_themer_themes` WHERE `id` = '" . $tid . "'" );
						$db->query();
						break;
					case 'makedefault' :
						$db->setQuery( "UPDATE `mod_themer_settings` SET `value` = '" . $tid . "' WHERE `key` = 'usetheme'" );
						$db->query();
						break;
					case 'copytheme' :
						$db->setQuery( "SELECT * FROM `mod_themer_themes` WHERE `id` = '" . $tid . "'" );
						$theme	= $db->loadObject();
						
						$db->setQuery( "INSERT INTO `mod_themer_themes` (`name`, `description`, `params` ) VALUES ('" . $theme->name . " (copy)', '" . $theme->description . "', '" . $theme->params . "' ); ");
						$db->query();
						break;
						
					case 'edittheme' :
						
						$params	= array(
						'fullwidth' => 'string',
						'contentbg' => 'string',
						'font' => 'string',
						'logo' => 'string',
						'bodytype' => 'string',
						'bodyoptnsolid'	=> 'string',
						'bodyoptnfrom' => 'string',
						'bodyoptnto' => 'string',
						'bodyoptndir' => 'string',
						'bodyoptnpattern' => 'string',
						'bodyoptnimage' => 'string',
						'alinks'	=> 'string',
						'alinksstd' => 'string','alinksvis' => 'string','alinkshov' => 'string',
						'navbarfrom' => 'string','navbarto' => 'string','navbartxt' => 'string','navbarhov' => 'string',
						'navbardropbg' => 'string','navbardroptxt' => 'string','navbardrophl' => 'string',
						'txtelemgffont' => 'string','txtelemgfsize' => 'string','txtelemgfcolor' => 'string',
						'txtelemh1font' => 'string','txtelemh1size' => 'string','txtelemh1color' => 'string',
						'txtelemh2font' => 'string','txtelemh2size' => 'string','txtelemh2color' => 'string',
						'txtelemh3font' => 'string','txtelemh3size' => 'string','txtelemh3color' => 'string',
						'txtelemh4font' => 'string','txtelemh4size' => 'string','txtelemh4color' => 'string',
						'txtelemh5font' => 'string','txtelemh5size' => 'string','txtelemh5color' => 'string',
						'txtelemh6font' => 'string','txtelemh6size' => 'string','txtelemh6color' => 'string',);
						
						foreach( $params as $key => $value ) {
							$params[$key] = $input->getVar( $key, null, 'request', $value );
						}
						
						$name	= $db->Quote( $input->getVar( 'name' ) );
						$desc	= $db->Quote( $input->getVar( 'description' ) );
						$paramstring	= $db->Quote( json_encode( $params ), false );
						$tid	= $db->Quote( $input->getVar( 'tid' ) );
						$db->setQuery( "UPDATE `mod_themer_themes` SET `name` = " . $name . ", `description` = " . $desc . ", `params` = " . $paramstring . " WHERE `id` = " . $tid );
						$db->query();
						
						break;
						
				}	// End Task Switch;
				
				break;
				
			// Save our configuration settings
			case 'config' :
				
				// Check license
				if (! dunloader( 'license', 'themer' )->isValid() ) return;
				
				$config	= array(
						'enable' => 'int',
						'restrictip' => 'string',
						'restrictuser' => 'array',
						'fontselect' => 'int'
						);
				
				foreach ( $config as $item => $filter ) {
					$key = $item;
					$value = $input->getVar( $item, null, 'request', $filter );
					
					if ( is_array( $value ) ) $value = implode( '|', $value );
					$db->setQuery( "UPDATE `mod_themer_settings` SET `value` = " . $db->Quote( $value ) . " WHERE `key` = '{$key}'" );
					$db->query();
				}
				break;
			case 'license' :
				$save = array( 'license' => $input->getVar( 'license', null ), 'localkey' => null );
				
				foreach ( $save as $key => $value ) {
					$db->setQuery( "UPDATE `mod_themer_settings` SET `value` = '{$value}' WHERE `key` = '{$key}'" );
					$db->query();
				}
				break;
		}
	}
}