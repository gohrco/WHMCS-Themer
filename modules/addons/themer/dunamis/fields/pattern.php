<?php


class ThemerPatternDunFields extends DunFields
{
	protected $_optid	= 'name';
	protected $_optname	= 'name';
	protected $options	= array();
	protected $value	= array();
	
	public function __construct( $settings = array() )
	{
		foreach( array( 'value' ) as $item ) {
			if ( array_key_exists( $item, $settings ) ) {
				$this->$item = (array) $settings[$item];
				unset( $settings[$item] );
			}
		}
		
		parent :: __construct( $settings );
		
		foreach ( $settings as $key => $value ) {
			$this->attributes[$key] = $value;
		}
		
		$this->_loadOptions();
	}
	
	
	/**
	 * Renders a form field
	 * @access		public
	 * @version		@fileVers@
	 * @param		array		- $options: any options to pass along
	 * 
	 * @return		string containing form field
	 * @since		1.0.0
	 */
	public function field( $options = array() )
	{
		$name		= $this->name;
		$value		= (array) $this->value;
		$id			= $this->id;
		
		$attr		= array_to_string( array_merge( $this->attributes, $options ) );
		$optns		= $this->options;
		
		$form		= '<select id="' . $id . '" name="'.$name.'"'.$attr.">\n";
		$oid		= $this->_optid;
		$oname		= $this->_optname;
		
		foreach ( $optns as $optn ) {
			$optn		=	(object) $optn;
			$selected	=	( in_array( $optn->$oid, $value ) ? ' selected="selected"' : '' ); 
			$form		.=	'<option value="' . $optn->$oid . '"' . $selected . '>' . t( $optn->$oname ) . "</option>\n";
		}
		
		return $form . '</select>';
	}
	
	
	/**
	 * Method to set an array option to the field
	 * @access		public
	 * @version		@fileVers@
	 * @param		array		- $options: contains array of name | id pairs
	 * 
	 * @since		1.0.0
	 */
	public function setOption( $options = array() )
	{
		$this->options = $options;
	}
	
	
	private function _loadOptions()
	{
		$dir	= WhmcsDunModule :: buildModulePath( 'themer', 'addon' ) . 'assets' . DIRECTORY_SEPARATOR . 'patterns';
		$dh		= opendir( $dir );
		$data	= array();
		$files	= array();
		
		while ( ( $filename = readdir( $dh ) ) !== false ) {
			if ( in_array( $filename, array( '.', '..' ) ) ) continue;
			$files[]	= $filename;
		}
		
		sort( $files );
		foreach ( $files as $file ) $data[] = (object) array( 'name' => $file );
		
		$this->options = $data;
	}
}