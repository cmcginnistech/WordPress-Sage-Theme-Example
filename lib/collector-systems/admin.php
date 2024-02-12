<?php

namespace CollectorSystems\Admin;

use CollectorSystems\CollectorSystems_Client;

/**
 * Add admin bar menu button.
 */
function add_clear_cache_button() {
  global $wp_admin_bar;

  $args = [
    'id' => 'js-clear-location-cache',
    'title' => __('Clear Location Cache', 'sage'),
    'href' => '#'
  ];

  if ( is_admin() ) {
    $wp_admin_bar->add_menu($args);
  }

}
//add_action('admin_bar_menu', __NAMESPACE__ .'\\add_clear_cache_button', 2000);

/**
 * Populate location field in backend.
 */
function pop_location_name( $value, $post_id, $field ) {
  $csdata = get_option('leiden_cs_data');
  $inv_num = get_field('inventory_number', $post_id);

  if ( array_key_exists($inv_num, $csdata) ) {
    return $csdata[$inv_num]['locationname'];
  } else {
    return null;
  }

}
//add_filter("acf/load_value/name=location_name", __NAMESPACE__ .'\\pop_location_name', 10, 3);

/**
 * Disable location field.
 */
function disable_location_name( $field ) {
  $field['disabled'] = 1;
  return $field;
}
//add_filter("acf/load_field/name=location_name", __NAMESPACE__ .'\\disable_location_name', 10, 1);

/**
 * Clear the location cache from options table.
 */
function clear_location_cache(){

  // check our ajax referrer
  check_ajax_referer('request-nonce', '_ajax_nonce');

  // validation check
  if ( isset($_POST['action']) && $_POST['action'] == "clear_location_cache" ){

    $csc = new CollectorSystems_Client();
    $message = $csc->update();

    echo $message;
    die();
  }

  echo 'Error';
  die();
}
add_action('wp_ajax_clear_location_cache', __NAMESPACE__ .'\\clear_location_cache');
