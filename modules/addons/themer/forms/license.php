<?php


$form	= array(
		'license'	=> array(
				'order'			=> 10,
				'type'			=> 'text',
				'value'			=> null,
				'validation'	=> '',
				'label'			=> 'themer.admin.themer.form.config.label.license',
				'description'	=> 'themer.admin.themer.form.config.description.license',
		),
		'status' => array(
				'order'			=> 20,
				'type'			=> 'information',
				'value'			=> array(),
				'validation'	=> '',
				'label'			=> 'themer.admin.themer.form.config.label.status',
				'description'	=> 'themer.admin.themer.form.config.description.status',
				),
		'branding' => array(
				'order'			=> 30,
				'type'			=> 'information',
				'value'			=> array(),
				'validation'	=> '',
				'label'			=> 'themer.admin.themer.form.config.label.branding',
				'description'	=> 'themer.admin.themer.form.config.description.branding',
		),
		'info'	=> array(
				'order'			=> 40,
				'type'			=> 'information',
				'value'			=> array(),
				'nodesc'		=> true,
				'validation'	=> '',
				'label'			=> 'themer.admin.themer.form.config.label.info',
		),
);