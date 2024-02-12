<?php

namespace Roots\Sage\PostTypes;

/*
 * Register custom post types.
 */
function register_post_types() {

  /**
   * Add custom post types here.
   */
  $post_types = [
    [
      'single_name' => 'Artwork',
      'plural_name' => 'Artwork',
      'slug'        => 'artwork',
      'has_archive' => false,
      'supports'    => ['title', 'thumbnail', 'custom-fields', 'revisions'],
      'menu_icon'   => 'dashicons-format-image',
    ],
    [
      'single_name' => 'Exhibition Artwork',
      'plural_name' => 'Exhibition Artworks',
      'slug'        => 'exb_artwork',
      'supports'    => ['title', 'thumbnail', 'revisions'],
      'menu_icon'   => 'dashicons-format-image',
      'rewrite'     => false,
      'public'      => false,
      'publicly_queryable' => false
    ],
    [
      'single_name' => 'Group',
      'plural_name' => 'Groups',
      'slug'        => 'group',
      'supports'    => ['title', 'thumbnail', 'custom-fields'],
      'menu_icon'   => 'dashicons-format-gallery',
      'rewrite'     => ['slug' => 'groups'],
    ],
    [
      'single_name' => 'Artist',
      'plural_name' => 'Artists',
      'slug'        => 'artist',
      'supports'    => ['title', 'thumbnail', 'custom-fields', 'revisions'],
      'menu_icon'   => 'dashicons-admin-customizer',
      'rewrite'     => ['slug' => 'artists'],
      'rest_base'   => 'artists'
    ],
    [
      'single_name' => 'Exhibition',
      'plural_name' => 'Exhibitions',
      'slug'        => 'exhibition',
      'has_archive' => false,
      'supports'    => ['title'],
      'menu_icon'   => 'dashicons-welcome-view-site'
    ],
    [
      'single_name' => 'Press',
      'plural_name' => 'Press',
      'slug'        => 'press',
      'supports'    => ['title', 'thumbnail'],
      'menu_icon'   => 'dashicons-format-aside',
      'rewrite'     => ['slug' => 'news-media'],
      'rest_base'   => 'press'
    ],
    [
      'single_name' => 'Essay',
      'plural_name' => 'Essays',
      'slug'        => 'essay',
      'supports'    => ['title', 'thumbnail', 'custom-fields', 'revisions'],
      'menu_icon'   => 'dashicons-welcome-learn-more',
      'rewrite'     => ['slug' => 'essays'],
      'rest_base'   => 'essays'
    ],
    [
      'single_name' => 'Video',
      'plural_name' => 'Videos',
      'slug'        => 'video',
      'supports'    => ['title', 'thumbnail'],
      'menu_icon'   => 'dashicons-format-video',
      'rewrite'     => ['slug' => 'videos'],
      'rest_base'   => 'videos'
    ],
    [
      'single_name' => 'Author',
      'plural_name' => 'Authors',
      'slug'        => 'entry_author',
      'supports'    => ['title'],
      'menu_icon'   => 'dashicons-book-alt',
      'rewrite'     => ['slug' => 'authors'],
      'rest_base'   => 'authors'
    ],
    [
      'single_name' => 'Staff Member',
      'plural_name' => 'Staff',
      'slug'        => 'staff_member',
      'supports'    => ['title', 'thumbnail'],
      'menu_icon'   => 'dashicons-businessman',
      'rewrite'     => ['slug' => 'staff'],
      'rest_base'   => 'staff'
    ],
    [
      'single_name' => 'PDF Archive',
      'plural_name' => 'PDF Archives',
      'slug'        => 'pdf_archive',
      'supports'    => ['title'],
      'menu_icon'   => 'dashicons-archive',
      'public'      => false,
      'rewrite'     => false
    ],
    [
      'single_name' => 'Bibliography Entry',
      'plural_name' => 'Bibliography',
      'slug'        => 'bibliography_entry',
      'supports'    => ['title'],
      'menu_icon'   => 'dashicons-text',
      'rewrite'     => ['slug' => 'bibliography'],
      'public'      => false,
      'has_archive' => true,
      'rest_base'   => 'bibliography'
    ]
  ];

  if( empty($post_types) )
    return;

  /*
   * Loop through post types and register each one.
   */
  foreach( $post_types as $type ) {

    $labels = array(
      'name'                => $type['plural_name'],
      'singular_name'       => $type['single_name'],
      'menu_name'           => $type['plural_name'],
      'name_admin_bar'      => $type['single_name'],
      'parent_item_colon'   => 'Parent '. $type['single_name'] .':',
      'all_items'           => 'All '. $type['plural_name'],
      'add_new_item'        => 'Add New '. $type['single_name'],
      'add_new'             => 'Add New',
      'new_item'            => 'New Item',
      'edit_item'           => 'Edit '. $type['single_name'],
      'update_item'         => 'Update Item',
      'view_item'           => 'View Item',
      'search_items'        => 'Search Item',
      'not_found'           => 'Not found',
      'not_found_in_trash'  => 'Not found in Trash',
    );

    /*
     * Use default args if none are provided.
     */
    $supports = isset( $type['supports'] ) ? $type['supports'] : array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' );
    $hierarchical = isset( $type['hierarchical'] ) ? $type['hierarchical'] : false;
    $public = isset( $type['public'] ) ? $type['public'] : true;
    $show_ui = isset( $type['show_ui'] ) ? $type['show_ui'] : true;
    $show_in_menu = isset( $type['show_in_menu'] ) ? $type['show_in_menu'] : true;
    $show_in_rest = isset( $type['show_in_rest'] ) ? $type['show_in_rest'] : false;
    $menu_position = isset( $type['menu_position'] ) ? $type['menu_position'] : 5;
    $menu_icon = isset( $type['menu_icon'] ) ? $type['menu_icon'] : 'dashicons-admin-generic';
    $show_in_admin_bar = isset( $type['show_in_admin_bar'] ) ? $type['show_in_admin_bar'] : true;
    $show_in_nav_menus = isset( $type['show_in_nav_menus'] ) ? $type['show_in_nav_menus'] : true;
    $can_export = isset( $type['can_export'] ) ? $type['can_export'] : true;
    $has_archive = isset( $type['has_archive'] ) ? $type['has_archive'] : true;
    $exclude_from_search = isset( $type['exclude_from_search'] ) ? $type['exclude_from_search'] : false;
    $publicly_queryable = isset( $type['publicly_queryable'] ) ? $type['publicly_queryable'] : true;
    $capability_type = isset( $type['capability_type'] ) ? $type['capability_type'] : 'post';
    $rewrite = isset( $type['rewrite'] ) ? $type['rewrite'] : array( 'slug' => $type['slug'] );
    $taxonomies = isset( $type['taxonomies'] ) ? $type['taxonomies'] : array();
    $rest_base = isset( $type['rest_base'] ) ? $type['rest_base'] : false;

    /*
     * Register post type.
     */
    register_post_type( $type['slug'], array(
      'labels'              => $labels,
      'supports'            => $supports,
      'hierarchical'        => $hierarchical,
      'public'              => $public,
      'show_ui'             => $show_ui,
      'show_in_menu'        => $show_in_menu,
      'show_in_rest'        => $show_in_rest,
      'menu_position'       => $menu_position,
      'menu_icon'           => $menu_icon,
      'show_in_admin_bar'   => $show_in_admin_bar,
      'show_in_nav_menus'   => $show_in_nav_menus,
      'can_export'          => $can_export,
      'has_archive'         => $has_archive,
      'exclude_from_search' => $exclude_from_search,
      'publicly_queryable'  => $publicly_queryable,
      'capability_type'     => $capability_type,
      'rewrite'             => $rewrite,
      'taxonomies'          => $taxonomies,
      'rest_base'           => $rest_base
    ));
  }

}
add_action( 'init', __NAMESPACE__ . '\\register_post_types', 0 );
