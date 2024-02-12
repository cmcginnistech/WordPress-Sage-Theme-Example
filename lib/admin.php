<?php

namespace Roots\Sage\Admin;

use helpers;

/**
 * Remove unneccessary meta tags from head
 * 1. Remove shortlink
 * 2. Remove generator (reveals current WordPress version)
 */
function cleanup_head() {
  remove_action('wp_head', 'wp_shortlink_wp_head', 10);
  remove_action('wp_head', 'wp_generator');
}
add_action('init', __NAMESPACE__ . '\\cleanup_head');

/**
 * Filter WYSIWYG pasted text.
 * @link https://jonathannicol.com/blog/2015/02/19/clean-pasted-text-in-wordpress/
 */
function filter_tinymce_pasted_text( $in ) {
  $in['paste_preprocess'] = "function(plugin, args){
    // Strip all HTML tags except those we have whitelisted
    var whitelist = 'p,b,strong,i,em,h2,h3,h4,h5,h6,ul,li,ol,a';
    var stripped = jQuery('<div>' + args.content + '</div>');
    var els = stripped.find('*').not(whitelist);
    for (var i = els.length - 1; i >= 0; i--) {
      var e = els[i];
      jQuery(e).replaceWith(e.innerHTML);
    }
    // Strip all class and id attributes
    stripped.find('*').removeAttr('id').removeAttr('class');
    // Return the clean HTML
    args.content = stripped.html();
  }";
  return $in;
}
add_filter('tiny_mce_before_init', __NAMESPACE__ . '\\filter_tinymce_pasted_text');

/**
 * Remove the additional CSS section, introduced in WP 4.7, from the Customizer.
 * @param $wp_customize WP_Customize_Manager
 */
function remove_custom_css($wp_customize) {
  $wp_customize->remove_section('custom_css');
}
add_action('customize_register', __NAMESPACE__ . '\\remove_custom_css');

/**
 * Improve ACF styles
 */
function add_admin_acf_styling() {
  echo '<style type="text/css">
    /* component title */
    .acf-flexible-content .layout .acf-fc-layout-handle {
      background: #0073aa;
      color: white !important;
    }
    /* component number */
    .acf-flexible-content .layout .acf-fc-layout-order {
      background: white;
      color: #0073aa !important;
      font-weight: bold;
    }
    /* repeater row handle */
    .acf-row .acf-row-handle.order {
      background: #40a8d1 !important;
      color: white !important;
      text-shadow: #014662 0 1px 0 !important;
      border-color: #0a678d;
    }
    /* repeater row (odd) handle */
    .acf-row:nth-child(odd) .acf-row-handle.order {
      background: #0085ba !important;
    }
    /* field group metabox headings */
    .acf-postbox .ui-sortable-handle {
      background-color: #006799;
      color: white;
    }
    /* field group metabox controls */
    .acf-postbox .acf-hndle-cog,
    .acf-postbox .toggle-indicator {
      color: white !important;
    }
  </style>';
}
add_action('admin_enqueue_scripts', __NAMESPACE__ . '\\add_admin_acf_styling');

/**
 * Remove unused menu items (ex: posts)
 */
function remove_menus() {
  remove_menu_page('edit.php');
}
add_action('admin_menu', __NAMESPACE__ . '\\remove_menus');

/**
 * Remove post type support
 */
function remove_support() {
  remove_post_type_support('page', 'editor');
}
add_action('init', __NAMESPACE__ . '\\remove_support');

/**
 * Get the data for our rich sidebar tooltips.
 * Seen when you hover over an artwork/artist/essay/video link
 * in a text component.
 */
function get_tooltip_data(){

  // check our ajax referrer
  check_ajax_referer('get_tooltip_data_nonce');

  // validation check
  if ( isset($_POST['action']) && $_POST['action'] == "get_tooltip_data" ){

    $permalink = $_POST['permalink'];
    $id = url_to_postid($permalink);
    $post_type = get_post_type($id);
    $allowed_types = ['artwork', 'artist', 'essay', 'video'];

    if ( $id !== 0 && in_array($post_type, $allowed_types) ) {
      include(locate_template("templates/tooltips/tooltip-{$post_type}.php"));
    }
    elseif ( !in_array($post_type, $allowed_types) ) {
      echo 'not-supported';
    } else {
      echo 'error';
    }

    die();
  }

  echo 'error';
  die();
}
add_action('wp_ajax_get_tooltip_data', __NAMESPACE__ .'\\get_tooltip_data');
add_action('wp_ajax_nopriv_get_tooltip_data', __NAMESPACE__ .'\\get_tooltip_data');

/**
 * Disable ACF fields
 */
function disable_ACF_field( $field ) {
  $field['disabled'] = 1;
  return $field;
}
add_filter("acf/load_field/name=version", __NAMESPACE__ .'\\disable_ACF_field');
add_filter("acf/load_field/name=autogenerated_pdf_file", __NAMESPACE__ .'\\disable_ACF_field');
add_filter("acf/load_field/name=pdf_filename", __NAMESPACE__ .'\\disable_ACF_field');
add_filter("acf/load_field/name=archive_post_type", __NAMESPACE__ .'\\disable_ACF_field');

/**
 * Save collection_count_meta for artists.
 * @priority < 10 - runs BEFORE post data has been saved
 *
 * @hooked acf/save_post
 */
function update_artist_collection_count_meta( $post_id ) {

  $post_type = get_post_type($post_id);

  /*
  Update artists on post save.
  */
  if ( $post_type == 'artist' ) {
    $connected_artwork = $_POST['acf']['field_5a7b2d7ac2184'];
    $count = !empty($connected_artwork) ? count($connected_artwork) : 0;
    update_post_meta( $post_id, 'artist_collection_items_count', $count );
  }

  /*
  When an artwork is saved, we need to make sure we update
  the connected artist's collection count meta field.
  */
  if ( $post_type == 'artwork' ) {
    // use get_post_meta to get the current value saved in DB.
    $old_artist = get_post_meta($post_id, 'artwork_to_artist_2way', true);
    // use $_POST to get the new value
    $new_artist = $_POST['acf']['field_5a86fa0cc1d0f'];

    // always update new artist
    if($old_artist !== $new_artist){
      $new_artist_current_artwork = get_post_meta($new_artist, 'artist_collection_items_count', true);
      $new_artist_new_count = absint($new_artist_current_artwork + 1);
      update_post_meta( $new_artist, 'artist_collection_items_count', $new_artist_new_count );
    }

    // if we changed the artist, we need to update the old artist's count.
    // $old_artist will be null for new posts.
    if ( $old_artist !== $new_artist && $old_artist !== null && $old_artist !== '' ) {
      $old_artist_current_artwork = get_post_meta($old_artist, 'artist_collection_items_count', true);
      $old_artist_new_count = absint($old_artist_current_artwork - 1);
      update_post_meta( $old_artist, 'artist_collection_items_count', $old_artist_new_count );
    }
  }

}
add_action('acf/save_post', __NAMESPACE__ .'\\update_artist_collection_count_meta', 1);


/**
 * Add artist sort name to artworks on save_post
 *
 * @hooked save_post
 */
function save_artwork_sort_data( $post_id ) {

  if ( get_post_type($post_id) !== 'artwork' ) {
    return;
  }

  $artist_id = get_post_meta($post_id, 'artwork_to_artist_2way', true);
  $sort_name = get_post_meta($artist_id, 'artist_sort_name', true);
  $mediums = get_the_terms($post_id, 'medium');
  $location = helpers\get_artwork_location($post_id);

  if ( !empty($mediums) ) {
    update_post_meta( $post_id, 'artwork_medium_sort_name', strtolower($mediums[0]->name) );
  }

  if ( $sort_name ) {
    update_post_meta( $post_id, 'artwork_artist_sort_name', $sort_name );
  }

  if ( $location ) {
    $location = str_replace('The ', '', $location);
    update_post_meta( $post_id, 'artwork_location_sort_name', $location );
  } else {
    delete_post_meta( $post_id, 'artwork_location_sort_name' );
  }

}
add_action('save_post', __NAMESPACE__ .'\\save_artwork_sort_data');

function save_bibliography_sort_data( $post_id ) {


  if ( get_post_type($post_id) !== 'bibliography_entry' ) {
    return;
  }

  $title = get_the_title($post_id);
  $title_start_letter = $title[0];

  $sort_letter = get_post_meta($post_id, 'sort_letter', true);

  if ($title_start_letter === $sort_letter){
    return;
  } else {
    update_post_meta( $post_id, 'sort_letter', $title_start_letter );
  }

}
add_action('save_post', __NAMESPACE__ .'\\save_bibliography_sort_data');