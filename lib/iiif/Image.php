<?php

namespace Roots\Sage\IIIF;

/**
 * IIIF Image.
 * All IIIF images are hosted using an external service.
 * The image manifest is available at {$url}/info.json.
 *
 * @var string | $url
 */
class Image {

  public $iiif_image_url;

  /**
   * Initialize
   */
  public function __construct( $url ) {
    $this->iiif_image_url = $url;
  }

  /**
   * Get IIIF image data
   *
   * @return array
   */
  public function request() {
    $request = wp_remote_get( $this->iiif_image_url . 'info.json');
    return $request;
  }

  /**
   * Check if we have a valid response from the API.
   *
   * @param array $request
   * @return boolean
   */
  public function is_valid_response($request) {
    return !is_wp_error($request) && $request['response']['code'] === 200;
  }

  /**
   * Get image id.
   *
   * @return string
   */
  public function get_id() {
    return $this->iiif_image_url;
  }

  /**
   * Get image width.
   *
   * @return int
   */
  public function get_width() {
    $manifest = $this->get_manifest();
    return $manifest->width;
  }

  /**
   * Get image height.
   *
   * @return int
   */
  public function get_height() {
    $manifest = $this->get_manifest();
    return $manifest->height;
  }

  /**
   * Get image manifest.
   *
   * @return object
   */
  public function get_manifest() {

    $transient_name = 'leiden_iiif_image_'. md5($this->iiif_image_url);
    $transient = get_transient( $transient_name );

    if( $transient ) {
      return $transient;
    }

    $request = $this->request();
    $manifest = [];

    if ( $this->is_valid_response($request) ) {
      $manifest = json_decode($request['body']);
      set_transient( $transient_name, $manifest, DAY_IN_SECONDS );
    }

    return $manifest;
  }

  /**
   * Create a service object that can be used in Presentation API.
   *
   * @return object
   */
  public function get_service_obj() {
    $manifest = $this->get_manifest();

    // unset non-standard fields
    unset($manifest->protocol);
    unset($manifest->sizes);
    unset($manifest->tiles);
    unset($manifest->width);
    unset($manifest->height);

    return $manifest;
  }

}