<?php defined('DUNAMIS') OR exit('No direct script access allowed');

define( 'DUN_MOD_THEMER', "@fileVers@" );

class ThemerDunModule extends WhmcsDunModule
{
	/**
	 * Stores the type of module this is
	 * @access		protected
	 * @var			string
	 * @since		1.0.0
	 */
	protected $type	= 'addon';
	
	
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
	
	
}