<?php

namespace CollectorSystems;

/**
 * Class for connecting to the Collector Systems API
 */
class CollectorSystems_Client {

  public $api_url = 'https://api.collectorsystems.com/13101';

  /**
   * Empty construct method
   */
  public function __construct() {}

  /**
   * Request to API
   */
  private function do_request() {
    $url = $this->api_url . '/objects?fields=inventorynumber,title,locationname&pretty=1';
    $request = wp_remote_get( $url );
  	return $request;
  }

  /**
   * Update a artworks's post meta by doing a meta query for
   * it's inventory number.
   *
   * @param string | $inv_num
   * @param string | $locationname
   * @return void
   */
  private function update_post_meta_by_inv_num( $inv_num, $locationname ) {
    $location = str_replace('The ', '', $locationname);

    $posts = get_posts([
      'post_type' => 'artwork',
      'posts_per_page' => 1,
      'meta_key' => 'inventory_number',
      'meta_value' => $inv_num
    ]);

    if ( !empty($posts) ) {
      update_post_meta( $posts[0]->ID, 'artwork_location_sort_name', $location );
    }
  }

  /**
   * Process data for saving to DB.
   *
   * example:
   * 'FB-101' => [
   *   'title' => 'Man with a Book',
   *   'locationname' => 'Davis Museum, Wellesley College'
   * ]
   *
   * @return array
   */
  private function process_data() {
    $request = $this->do_request();

    if ( is_wp_error($request) ) {
      return $request;
    }
    elseif ( $request['response']['code'] !== 200 ) {
      return 'Error';
    }
    else {
      $body = json_decode($request['body']);
      $results = [];
    }

    foreach ( $body->data as $item ) {
      $inv = $item->inventorynumber;
      $loc = $item->locationname;
      $results[$inv] = [
        'title' => $item->title,
        'locationname' => $loc
      ];
      $this->update_post_meta_by_inv_num($inv, $loc);
    }

    return $results;
  }

  /**
   * Update data in WP options table.
   */
  public function update() {
    $data = $this->process_data();

    if ( is_array($data) && !is_wp_error($data) ) {
      update_option('leiden_cs_data', $data);
      $message = 'success';
    } else {
      $message = 'can\'t connect';
    }

    return $message;
  }

}
