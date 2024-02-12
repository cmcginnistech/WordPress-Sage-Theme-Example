<?php

use Roots\Sage\Assets;

/**
 * The Leiden Viewer
 */
class Leiden_Viewer {

  public $asset_folder = '/LeidenCollectionSamples/images/';

  public $asset_url;

  /**
   * Initialize.
   */
  public function __construct() {
    add_action( 'wp_enqueue_scripts', array($this, 'viewer_scripts') );

    if ( defined('WP_DEBUG') && defined('LC_VIEWER_ASSET_PATH') ) {
      $this->asset_url = LC_VIEWER_ASSET_PATH . 'images/';
    } else {
      $this->asset_url = get_site_url(null, '/') . $this->asset_folder;
    }
  }

  /**
   * Get currently queried artwork.
   *
   * @return object | $artwork
   */
  public function get_artwork_on_view() {
    $slug = get_query_var('viewer_post_slug');

    if (!$slug) {
      return false;
    }

    $artwork = get_page_by_path( $slug, OBJECT, 'artwork' );
    return $artwork;
  }

  /**
   * Get image files.
   *
   * @return array | $files
   */
  public function get_views() {

    $artwork_obj = $this->get_artwork_on_view();

    if ( !$artwork_obj ) {
      return false;
    }

    $post_id = $artwork_obj->ID;
    $available_types = get_field('available_image_types', $post_id);
    $available_IIIF_types = get_field('iiif_image_types', $post_id);

    if ( empty($available_types) && empty($available_IIIF_types) ) {
      return false;
    }

    // if we have IIIF data, use that
    if ( !empty($available_IIIF_types) ) {
      foreach ( $available_IIIF_types as $type ) {
        $files[$type['type']] = [
          'url' => $type['image_url']
        ];
      }

    // if not fallback to DZI files on server (legacy)
    } else {
      foreach ( $available_types as $type ) {
        $filename = $type['image_filename'];
        $files[$type['type']] = [
          'file' => $filename .'_files',
          'dzi' => $this->get_dzi_data($filename)
        ];
      }
    }

    return $files;
  }

  /**
   * Get DZI file info
   *
   * @return array | $data
   */
  public function get_dzi_data( $filename ) {

    // $file_path = ABSPATH . $this->asset_folder;
    $file_path = $this->asset_url;
    $dzi_file = $filename .'.dzi';
    $image_loc = $filename;

    // check if the DZI file exists
    // if ( !file_exists($file_path . $dzi_file) ) {
    //   return false;
    // }

    // check if the dzi file exists
    // will return status 200 if file exists
    $header_response = get_headers( $file_path . $dzi_file, 1 );
    if ( strpos( $header_response[0], "200" ) === false ) {
      return false;
    }

    // setup array for data
    $data = [
      'file' => $file_path . $dzi_file
    ];

    // now we will read the contents of the DZI file
    // (which is an XML file)
    $dzi_obj = simplexml_load_file( $file_path . $dzi_file );
    $namespaces = $dzi_obj->getNamespaces();

    foreach ($namespaces as $namespace) {
      $data['xmlns'] = $namespace;
    }

    foreach ($dzi_obj->attributes() as $attr_name => $attr_value) {
      $data[$attr_name] = $attr_value->__toString();
    }

    foreach ($dzi_obj->Size->attributes() as $attr_name => $attr_value) {
      $data['sizes'][$attr_name] = $attr_value->__toString();
    }

    return $data;
  }

  /**
   * Viewer scripts.
   */
  public function viewer_scripts() {

    $artwork = $this->get_artwork_on_view();
    $asset_url = $this->asset_url;

    if (!$artwork) {
      return false;
    }

    $is_iiif = !empty(get_field('iiif_image_types', $artwork->ID));

    $viewer_vars = [
      'currentURL' => get_permalink(get_the_ID()),
      'syncVars' => [
        'imageLoc' => $asset_url,
        'post_slug' => $artwork->post_name,
        'post_id' => $artwork->ID,
        'inventory_num' => get_field('inventory_number', $artwork->ID),
        'image_files' => $this->get_views(),
        'iiif' => $is_iiif
      ],
      'themeURL' => get_stylesheet_directory_uri(),
      'postObj' => get_post(get_the_ID()),
    ];

    wp_enqueue_script('sage/viewer/js', Assets\asset_path('scripts/viewer.js'), ['sage/js'], null, true);
    wp_localize_script('sage/viewer/js', 'leidenViewerVars', $viewer_vars);
  }

  /**
   * Output the viewer markup.
   */
  public function the_viewer() {
    echo '<div id="all_views" class="viewer-container"></div>';
  }

}

/**
 * Initialize early for script loading.
 */
new Leiden_Viewer();
