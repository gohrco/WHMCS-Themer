<?php 

if (! defined('DUNAMIS') ) {
	// We are using this as a call back
	$get = $GLOBALS['_GET'];
	
	$link = ( $get != 'Helvetica Neue' ? '<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=' . $get['font'] . '">' : '' );
	
	if ( isset ( $get['font'] ) ) {
		echo <<< HTML
<html>
	<head>
		{$link}
		<style>
			body {
				background-color: #F5F5F5;
				font-family: '{$get['font']}', serif;
				font-size: 24px;
				text-align: center;
			}
		</style>
	</head>
	<body>
		This is some sample text
	</body>
</html>
HTML;
	}
	exit();
}


class ThemerWebfontDunFields extends DunFields
{
	protected $_optid	= 'name';
	protected $_optname	= 'name';
	protected $options	= array();
	protected $primary	= false;
	protected $value	= array();
	
	public function __construct( $settings = array() )
	{
		foreach( array( 'primary', 'value' ) as $item ) {
			if ( $item == 'primary' && array_key_exists( 'primary', $settings ) ) {
				$this->primary = $settings['primary'];
				unset( $settings['primary'] );
				continue;
			}
			
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
		$baseurl	= get_baseurl( 'themer' );
		
		$attr		= array_to_string( array_merge( $this->attributes, $options ) );
		$optns		= $this->options;
		
		$form		= '<select id="' . $id . '" name="'.$name.'" onchange=\'javascript:webfontupdate("' . $name . '-iframe", "' . $baseurl . 'dunamis/fields/webfont.php?font=", this.value);\' '.$attr.">\n";
		$oid		= $this->_optid;
		$oname		= $this->_optname;
		
		foreach ( $optns as $optn ) {
			$optn		=	(object) $optn;
			$selected	=	( in_array( $optn->$oid, $value ) ? ' selected="selected"' : '' ); 
			$form		.=	'<option value="' . $optn->$oid . '"' . $selected . '>' . t( $optn->$oname ) . "</option>\n";
		}
		
		$usefont	= $value[0];
		
		$form	.=	'</select>'
				.	'<iframe id="' . $name .'-iframe" name="' . $name .'-iframe" src="' . $baseurl . 'dunamis/fields/webfont.php?font=' . $usefont . '" height="50px" width="400px" class="pull-right" ></iframe>';
				
		$this->_addJavascript();
		return $form;
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
			$doc->addScript( $baseurl . 'assets/webfont.js' );
		}
	}
	
	
	/**
	 * Loads the options for the form
	 * @access		private
	 * @version		@fileVers@
	 * 
	 * @since		1.0.0
	 */
	private function _loadOptions()
	{
		static $response = array();
		
		if ( empty( $response ) ) {
			$ip			= $GLOBALS['remote_ip'];
			$config		= dunloader( 'config', 'themer' );
			$fret		= $config->get( 'fontselect' );
			$sort		= ( $fret == '1' ? 'alpha' : ( $fret == '2' ? 'popularity' : ( $fret == '3' ? 'trending' : 'date' ) ) );
			
			$client		= load_google();
			$client->setApplicationName("WHMCS Themer by Go Higher");
			$client->setDeveloperKey('AIzaSyAIpf_-Sp0uZZl0LcSXSPtgQwnXqFUIAO4');
			
			$service = new Google_WebfontsService($client);
			$fonts = $service->webfonts->listWebfonts( array( $sort ) );
			
			$limit	= ( $fret == '1' ? 100000 : 30 );
			$count	= 0;
			
			foreach ( $fonts['items'] as $font ) {
				$response[] = array( 'name' => $font['family'] );
				$count++;
				if ( $count >= $limit ) break;
			}
		}
		
		if ( $this->primary ) {
			$data[] = array( 'name' => 'Helvetica Neue' );
		}
		else {
			$data[] = array( 'name' => '- use primary -' );
		}
		
		$data	= array_merge( $data, $response );
		
		$this->setOption( $data );
	}
}