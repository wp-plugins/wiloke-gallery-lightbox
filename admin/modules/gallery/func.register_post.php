<?php

register_post_type
(
	self::PIG_POST_TYPE,
	array
	(
		'labels' 		=> array
		(
			'name' 			=> 'Gallery Lightbox',
			'singular_name'	=>'Gallery Lightbox',
		),
		'public'		=> true,
		'show_ui'		=> true,
		'show_in_menu'	=> true,
		'menu_icon'		=> 'dashicons-welcome-widgets-menus',
		'description'	=> 'Very easy to create a gallery for your website',
		'hierarchical'	=> false,
		'supports'		=> array('post-formats', 'title',  'thumbnail', 'revisions'),
		'rewrite'		=> array('slug'=>'pi-gallery')
		
	)
);
 
register_taxonomy
(
	'pi_filters', //taxname
 	self::PIG_POST_TYPE,
 	array
 	(
 		'labels'			=> array 
 		(
	 		'name' 			=> 'Filters',
	 		'singular_name'	=> 'Filter',
	 		'menu_name'		=> 'Filters',
	 		'add_new_item'	=> 'Add new Filter',
	 		'new_item_name' => 'New Filter Name'
 		),
 		'show_ui'           => true,
		'show_admin_column' => true,
		'publicly_queryable' => true,
		'query_var'         => true,
 		'public'			=> true,
 		'hierarchical'		=> true,
 		'description'		=> 'Each filter item as item in the  navigation filter',
 		'rewrite'			=> array('slug'=>'pi-filters')
 	)
);


register_taxonomy
(
	'pi_albums', //taxname
 	self::PIG_POST_TYPE,
 	array
 	(
 		'labels'			=> array
 		(
	 		'name' 			=> 	'Albums',
	 		'singular_name'	=>	'Album',
	 		'menu_name'		=>	'Albums',
	 		'add_new_item'	=>	'Add new Album',
	 		'new_item_name' =>  'New Album Name'
 		),
 		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
 		'public'			=> true,
 		'hierarchical'		=> true,
 		'rewrite'			=> array('slug'=>'pi-albums')
 	)
);