<?php
// This file is generated. Do not modify it manually.
return array(
	'banner' => array(
		'name' => 'acf/dgwltd-banner',
		'title' => 'DGW.ltd Banner',
		'description' => 'Banner block',
		'style' => 'file:./banner.css',
		'category' => 'theme',
		'icon' => 'cover-image',
		'apiVersion' => 3,
		'supports' => array(
			'jsx' => true,
			'align' => false,
			'alignText' => true,
			'anchor' => true,
			'color' => array(
				'text' => true,
				'background' => true,
				'link' => true
			)
		),
		'styles' => array(
			array(
				'name' => 'default',
				'label' => 'Default',
				'isDefault' => true
			),
			array(
				'name' => 'monochrome',
				'label' => 'Monochrome'
			)
		),
		'acf' => array(
			'mode' => 'preview',
			'renderTemplate' => 'banner.php'
		),
		'attributes' => array(
			'align' => array(
				'type' => 'string',
				'default' => 'full'
			)
		)
	),
	'breadcrumbs' => array(
		'name' => 'acf/dgwltd-breadcrumbs',
		'title' => 'DGW.ltd Breadcrumbs',
		'description' => 'Breadcrumbs listing block',
		'category' => 'theme',
		'icon' => 'admin-links',
		'apiVersion' => 3,
		'supports' => array(
			'jsx' => true,
			'alignText' => false,
			'color' => false
		),
		'acf' => array(
			'mode' => 'preview',
			'renderTemplate' => 'breadcrumbs.php'
		)
	),
	'cards' => array(
		'name' => 'acf/dgwltd-cards',
		'title' => 'DGW.ltd Cards',
		'description' => 'Cards block',
		'style' => 'file:./index.css',
		'category' => 'theme',
		'icon' => 'tagcloud',
		'apiVersion' => 3,
		'acf' => array(
			'mode' => 'preview',
			'renderTemplate' => 'render.php'
		),
		'supports' => array(
			'jsx' => true,
			'align' => array(
				'none'
			),
			'anchor' => true
		)
	),
	'embed' => array(
		'name' => 'acf/dgwltd-embed',
		'title' => 'DGW.ltd Embed',
		'description' => 'Embed block',
		'style' => 'file:./embed.css',
		'viewScript' => 'file:./embed.js',
		'category' => 'theme',
		'icon' => 'format-video',
		'apiVersion' => 3,
		'supports' => array(
			'jsx' => true,
			'align' => false,
			'anchor' => true
		),
		'acf' => array(
			'mode' => 'edit',
			'renderTemplate' => 'embed.php'
		),
		'align' => 'full'
	),
	'hero' => array(
		'name' => 'acf/dgwltd-hero',
		'title' => 'DGW.ltd Hero',
		'description' => 'Hero block',
		'style' => 'file:./hero.css',
		'viewScript' => 'file:./hero.js',
		'category' => 'theme',
		'icon' => 'tide',
		'apiVersion' => 3,
		'supports' => array(
			'jsx' => true,
			'align' => false,
			'alignText' => true,
			'anchor' => true,
			'color' => array(
				'text' => true,
				'background' => true,
				'link' => true
			),
			'multiple' => false
		),
		'styles' => array(
			array(
				'name' => 'default',
				'label' => 'Default',
				'isDefault' => true
			),
			array(
				'name' => 'monochrome',
				'label' => 'Monochrome'
			)
		),
		'acf' => array(
			'mode' => 'preview',
			'renderTemplate' => 'hero.php'
		),
		'attributes' => array(
			'align' => array(
				'type' => 'string',
				'default' => 'full'
			)
		)
	),
	'picker' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'dgwltd/picker',
		'version' => '0.1.0',
		'title' => 'Picker',
		'category' => 'widgets',
		'icon' => 'table-col-before',
		'description' => 'Block component that allows you to pick posts and pages',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false,
			'color' => array(
				'background' => true,
				'text' => true
			),
			'align' => false,
			'anchor' => true
		),
		'attributes' => array(
			'heading' => array(
				'type' => 'string',
				'default' => 'Selected posts'
			),
			'contentType' => array(
				'type' => 'string',
				'default' => 'news'
			),
			'favouritePost' => array(
				'type' => 'number',
				'default' => null
			),
			'selectedPosts' => array(
				'type' => 'array',
				'default' => array(
					
				)
			)
		),
		'textdomain' => 'dgwltd-plugin',
		'editorStyle' => 'file:./index.css',
		'style' => 'file:./style-index.css',
		'render' => 'file:./render.php',
		'viewScript' => 'file:./view.js'
	),
	'promo-card' => array(
		'name' => 'acf/dgwltd-promo-card',
		'title' => 'DGW.ltd Promo card',
		'description' => 'Promo card block',
		'style' => 'file:./promo-card.css',
		'viewScript' => 'file:./promo-card.js',
		'category' => 'theme',
		'icon' => 'format-aside',
		'apiVersion' => 3,
		'supports' => array(
			'jsx' => true,
			'align' => array(
				'none'
			),
			'alignText' => true,
			'anchor' => true,
			'color' => array(
				'text' => true,
				'background' => true,
				'link' => true
			)
		),
		'acf' => array(
			'mode' => 'preview',
			'renderTemplate' => 'promo-card.php'
		),
		'styles' => array(
			array(
				'name' => 'default',
				'label' => 'Default',
				'isDefault' => true
			),
			array(
				'name' => 'dark',
				'label' => 'Dark'
			),
			array(
				'name' => 'light',
				'label' => 'Light'
			)
		),
		'align' => 'none'
	),
	'query' => array(
		'name' => 'acf/dgwltd-cards-query',
		'parent' => array(
			'acf/dgwltd-cards'
		),
		'title' => 'DGW.ltd - Query',
		'description' => 'Feature specific types of content in a grid',
		'category' => 'theme',
		'icon' => 'menu',
		'apiVersion' => 2,
		'acf' => array(
			'mode' => 'edit',
			'renderTemplate' => 'query.php'
		),
		'supports' => array(
			'jsx' => true,
			'align' => array(
				'none'
			),
			'anchor' => true
		)
	)
);
