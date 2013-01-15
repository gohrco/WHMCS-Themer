<?php



class ThemerMinicolorsDunFields extends DunFields
{

	public function __construct( $settings = array() )
	{
		parent :: __construct( $settings );
		$names	= $this->getPropertyNames();
		
		foreach ( $settings as $key => $value ) {
			if ( in_array( $key, $names ) ) continue;
			$this->attributes[$key] = $value;
		}
		
		// Add the js / css to the header	
		$this->_addJavascript();
	}


	public function field( $options = array() )
	{
		$name	=   $this->name;
		$value	=	$this->value;
		
		$attr	=   array_merge( $this->attributes, $options );
		$field	=   '<input name="' . $name . '" type="minicolors" data-default="' . $value . '" ' . array_to_string( $attr ) . ' />';
		
		return $field;
	}
	
	
	private function _addJavascript()
	{
		static $data = false;

		if (! $data ) {
			$doc	= dunloader( 'document', true );
			
			$data	= true;
			$baseurl = get_baseurl( 'themer' );
			$doc->addScript( $baseurl . 'assets/jquery.minicolors.js' );
		}
	}
}