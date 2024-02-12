<?php

namespace Roots\Sage\Taxis;

/*
 * Register custom taxonomies.
 */
function register_taxonomies() {

	/**
	 * Add custom taxonomies here.
	 */
	$taxis = [
		[
			'slug'         => 'subject',
			'single_name'  => 'Subject Matter',
			'plural_name'  => 'Subject Matters',
			'object_type'	 => 'artwork',
			'rewrite'			 => false,
			'show_admin_column' => false
		],
		[
			'slug'         => 'date',
			'single_name'  => 'Date',
			'plural_name'  => 'Dates',
			'object_type'	 => 'artwork',
			'rewrite'			 => false,
			'show_admin_column' => false
		],
		[
			'slug'         => 'medium',
			'single_name'  => 'Medium',
			'plural_name'  => 'Mediums',
			'object_type'	 => 'artwork',
			'rewrite'			 => false,
			'show_admin_column' => false
		],
		[
			'slug'         => 'location',
			'single_name'  => 'Location',
			'plural_name'  => 'Locations',
			'object_type'	 => 'artwork',
			'rewrite'			 => false,
			'show_admin_column' => false
		],
		[
			'slug'         => 'iconclass',
			'single_name'  => 'IconClass Term',
			'plural_name'  => 'IconClass Terms',
			'object_type'	 => 'artwork',
			'rewrite'			 => false,
			'meta_box_cb'	 => false,
			'show_admin_column' => false
		],
		[
			'slug'         => 'video_cat',
			'single_name'  => 'Category',
			'plural_name'  => 'Categories',
			'object_type'	 => 'video',
			'public'			 => false,
			'rewrite'			 => false
		],
		[
      'single_name'  => 'Exhibition',
      'plural_name'  => 'Exhibitions',
      'slug'         => 'press_exhibition',
      'object_type'  => 'press',
      'hierarchical' => false,
			'meta_box_cb'  => false,
      'rewrite' 		 => [
        'slug'       => 'news-media/exhibition',
        'with_front' => false
      ]
    ],
    [
      'single_name'  => 'Category',
      'plural_name'  => 'Categories',
      'slug'         => 'press_category',
      'object_type'  => 'press',
      'hierarchical' => false,
      'meta_box_cb'  => false,
      'rewrite' => [
        'slug'       => 'news-media/category',
        'with_front' => false
      ]
		],
		[
			'slug'         => 'theme',
			'single_name'  => 'Theme',
			'plural_name'  => 'Themes',
			'object_type'	 => 'essay',
			'public'			 => false,
			'rewrite'			 => false,
		],
	];

	if( empty($taxis) )
		return;

	/*
	 * Loop through taxonomies and register each one.
	 */
	foreach( $taxis as $taxi ) {

		$labels = array(
			'name'              => $taxi['plural_name'],
			'singular_name'     => $taxi['single_name'],
			'search_items'      => 'Search '. $taxi['plural_name'],
			'all_items'         => 'All '. $taxi['plural_name'],
			'parent_item'       => 'Parent '. $taxi['single_name'],
			'parent_item_colon' => 'Parent '. $taxi['single_name'] .':',
			'edit_item'         => 'Edit '. $taxi['single_name'],
			'update_item'       => 'Update '. $taxi['single_name'],
			'add_new_item'      => 'Add New '. $taxi['single_name'],
			'new_item_name'     => 'New '. $taxi['single_name'] .' Name',
			'menu_name'         => $taxi['plural_name'],
		);

		/*
		 * Use default args if none are provided.
		 */
		$hierarchical = isset( $taxi['hierarchical'] ) ? $taxi['hierarchical'] : true;
		$public = isset( $taxi['public'] ) ? $taxi['public'] : true;
		$show_ui = isset( $taxi['show_ui'] ) ? $taxi['show_ui'] : true;
		$show_admin_column = isset( $taxi['show_admin_column'] ) ? $taxi['show_admin_column'] : true;
		$query_var = isset( $taxi['query_var'] ) ? $taxi['query_var'] : true;
		$rewrite = isset( $taxi['rewrite'] ) ? $taxi['rewrite'] : array( 'slug' => $taxi['slug'] );
    $show_in_menu = isset( $taxi['show_in_menu'] ) ? $taxi['show_in_menu'] : true;
    $meta_box_cb = isset( $taxi['meta_box_cb'] ) ? $taxi['meta_box_cb'] : null;

		/*
		 * Register taxonomy.
		 */
		 register_taxonomy( $taxi['slug'], $taxi['object_type'], array(
			'hierarchical'      => $hierarchical,
			'public' 						=> $public,
			'labels'            => $labels,
			'show_ui'           => $show_ui,
			'show_admin_column' => $show_admin_column,
			'query_var'         => $query_var,
			'rewrite'           => $rewrite,
      'show_in_menu'      => $show_in_menu,
      'meta_box_cb'       => $meta_box_cb
		));
	}

}
add_action( 'init', __NAMESPACE__ . '\\register_taxonomies', 0 );
