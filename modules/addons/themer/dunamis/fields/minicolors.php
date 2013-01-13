<?php



class ThemerMinicolorsDunFields extends DunFields
{

	public function __construct( $settings = array() )
	{
		parent :: __construct( $settings );

		foreach ( $settings as $key => $value ) {
			$this->attributes[$key] = $value;
		}
		
		// Add the js / css to the header	
		$this->_addJavascript();
	}


	public function field( $options = array() )
	{
		$name	=   $this->name;
		$value	=   $this->value;
		
		
		$attr	=   array_merge( $this->attributes, $options );
		$field	=   '<input name="' . $name . '" value="' . $value . '" data-default="' . $value . '" type="minicolors" ' . array_to_string( $attr ) . ' />';
		
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