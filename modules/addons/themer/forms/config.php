<?php


$form	= array(
		'enable'		=> array(
				'order'			=> 10,
				'type'			=> 'toggleyn',
				'value'			=> true,
				'validation'	=> '',
				'labelon'		=> 'themer.form.toggleyn.enabled',
				'labeloff'		=> 'themer.form.toggleyn.disabled',
				'label'			=> 'themer.admin.themer.form.config.label.enable',
				'description'	=> 'themer.admin.themer.form.config.description.enable',
				),
		
		'restrictip'	=> array(
				'order'			=> 20,
				'type'			=> 'text',
				'value'			=> null,
				'validation'	=> '',
				'label'			=> 'themer.admin.themer.form.config.label.restrictip',
				'description'	=> 'themer.admin.themer.form.config.description.restrictip',
				),
		
		'restrictuser'	=> array(
				'order'			=> 30,
				'type'			=> 'whmcsadmins',
				'value'			=> null,
				'split'			=> '|',
				'validation'	=> '',
				'multiple'		=> 'multiple',
				'label'			=> 'themer.admin.themer.form.config.label.restrictuser',
				'description'	=> 'themer.admin.themer.form.config.description.restrictuser',
				),
		'fontselect' => array(
				'order'			=> 40,
				'type'			=> 'togglebtn',
				'value'			=> array( '1' ),
				'validation'	=> '',
				'options'		=> array(
						array( 'id' => '1', 'name' => 'themer.admin.themer.form.config.fontselect.optn.all' ),
						array( 'id' => '2', 'name' => 'themer.admin.themer.form.config.fontselect.optn.top' ),
						array( 'id' => '3', 'name' => 'themer.admin.themer.form.config.fontselect.optn.tdg' ),
						array( 'id' => '4', 'name' => 'themer.admin.themer.form.config.fontselect.optn.new' ),
				),
				'label'			=> 'themer.admin.themer.form.config.label.fontselect',
				'description'	=> 'themer.admin.themer.form.config.description.fontselect',
		),
		);