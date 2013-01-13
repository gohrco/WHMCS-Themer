<?php



class ThemerTogglebtnDunFields extends DunFields
{
	protected $_optid	= 'id';
	protected $_optname	= 'name';
	protected $options	= array();
	protected $value	= array();
	
	public function __construct( $settings = array() )
	{
		foreach( array( 'options', 'value' ) as $item ) {
			if ( array_key_exists( $item, $settings ) ) {
				$this->$item = (array) $settings[$item];
				unset( $settings[$item] );
			}
		}
		
		parent :: __construct( $settings );
		
		foreach ( $settings as $key => $value ) {
			$this->attributes[$key] = $value;
		}
	}


	public function field()
	{
		$name		=	$this->name;
		$value		=	(array) $this->value;
		$id			=	$this->getId();
		$onclick	=	null;
		$class		=	null;
		
		// Catch onclicks
		if ( isset( $this->attributes['onclick'] ) ) {
			$onclick = $this->attributes['onclick'];
			unset( $this->attributes['onclick'] );
		}
		
		if ( isset ( $this->attributes['class'] ) ) {
			$class = $this->attributes['class'];
			unset( $this->attributes['class'] );
		}
		
		$attr		=	array_to_string( $this->attributes );
		$optns		=	$this->options;
		$oid		=	$this->_optid;
		$oname		=	$this->_optname;
		
		$field		=	'<div class="btn-group" data-toggle="buttons-radio">'
					.	'<input type="hidden" id="' . $id . '" name="' . $name . '" value="' . $value[0] . '" />';
		
		foreach ( $optns as $optn ) {
			$optn		=	(object) $optn;
			$selected	=	( in_array( $optn->$oid, $value ) ? 'active ' : '' );
			$field		.=	'<button type="button" class="btn btn-primary ' . $selected . $class
						. '" onclick="javascript:togglebtns(\'' . $id . '\', ' . $optn->$oid . ' ); ' . $onclick . '">'
						. t( $optn->$oname ) . '</button>';
		}
		
		$this->_addJavascript();
		
		return $field . '</div>';
	}
	
	
	/**
	 * Adds the javascript to the document
	 * @access		private
	 * @version		@fileVers@
	 * 
	 * @since		1.0.0
	 */
	private function _addJavascript()
	{
		static $data = false;
		
		$doc	= dunloader( 'document', true );
		
		if (! $data ) {
			$data	= true;
			$baseurl = get_baseurl( 'themer' );
			$doc->addScript( $baseurl . 'assets/togglebtns.js' );
		}
		
		$id		= $this->getId();
		$value	= $this->get( 'value' );
		$value	= empty( $value ) ? array('1') : $value;
		$doc->addScriptDeclaration( 'jQuery("document").ready( function () { togglebtns(\'' . $id . '\', \'' . $value[0] . '\' ); });' );
	}
}